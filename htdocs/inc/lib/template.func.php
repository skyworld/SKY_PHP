<?php

/**
 * If you are not authorized to modify this file, do NOT touch it.
 * @file template.func.php
 * @author SkyWorld
 * @date 2011-1-16
 * @description This file contains all funtions that related to templtes option
 **/


if(!defined('IN_NCG')) exit ('Access Denied.');

    /**
	 * @name tplrefresh
	 * @author SkyWorld
	 * @date 2011-1-16
	 * @description To judge wether the template need to be refreshed
	 * 				If need refreshing return true, otherwise return false
     * @parameters  void
     * @return bool
     **/
function tplrefresh($comFileName){

	if(!is_readable($comFileName))
		return true;
	if(@filemtime($comFileName) > @filemtime($comFileName))
		return false;
	else
		return true;
}

    /**
	 * @name tplrefresh
	 * @author SkyWorld
	 * @date 2011-1-16
	 * @description This funtion used to parse templates
	 * 				If teplates has been complied return the complied filename
	 * 				Otherwise complie the templates, and then return the complied filename
     * @parameters  void
     * @return bool
     **/
function template($fileName){
	$fileName = trim($fileName);
	$tplFile = NCG_TEMPLATES.$fileName;
	$comFileName = NCG_TEMPLATES_C.dirname($fileName).'/com_'.basename($fileName).'.php';
	$templates_c_dir = dirname($comFileName);


	if (is_readable($comFileName) && !tplrefresh($comFileName)){
		return $comFileName;
	}


	if (file_exists($tplFile)){
		$temp="<?php if (!defined('IN_NCG')) exit ('Access Denied.'); ?>\n";
		$temp .=file_get_contents($tplFile);

		/*patterns array*/
		$pattern = array(
			'/\{\s*template\s*(.+?)\s*\}/',
			'/\s*\$([\w_]+)\.([\w_]+)\.([\w_]+).([\w_]+)\s*/',
			'/\s*\$([\w_]+)\.([\w_]+)\.([\w_]+)\s*/',
			'/\s*\$([\w_]+)\.([\w_]+)\s*/',
			'/\{\s*\$([\w\x7f-\xff][\w\x7f-\xff\[\]\"\']*)\s*\}/i',
			'/<!--\s*if\s*(.+?)\s*-->/',
			'/<!--\s*else\s*-->/',
			'/<!--\s*else\s*if\s*(.+?)\s*-->/',
			'/<!--\s*\/if\s*-->/',
			'/<!--\s*loop\s+\\$(.+?)\s+\\$(.+?)\s+\\$(.+?)\s*-->/',
			'/<!--\s*loop\s+\\$(.+?)\s+\\$(.+?)\s*-->/',
			'/<!--\s*\/loop\s*-->/',
			'/\{([A-Z].+?)\}\}/'
		);

		/*replacements array*/
		$replacement = array(
			'<?php include template ${1} ?>',
			'\$${1}[\'${2}\'][\'${3}\'][\'${4}\']',
			'\$${1}[\'${2}\'][\'${3}\']',
			'\$${1}[\'${2}\']',
			'<?php echo $${1}; ?>',
			'<?php if ${1} { ?>',
			'<?php } else { ?>',
			'<?php } elseif ${1} { ?>',
			'<?php } ?>',
			'<?php foreach ($${1} as $${2} => $${3}) { ?>',
			'<?php foreach ($${1} as $${2}) { ?>',
			'<?php } ?>',
			'<?php echo ${1}; ?>'

		);
		$temp = preg_replace($pattern,$replacement,$temp);
		$temp = trim($temp);

		if (!file_exists($templates_c_dir))
		{
			@mkdir($templates_c_dir, 0777, true) or die("File dir can not be created!");
		}

		file_put_contents($comFileName, $temp);
		return $comFileName;
	}else{
		die("Template file cannot be found.");
	}
}

// end  off script