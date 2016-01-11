<?php
/**
 * If you are not authorized to modify this file, do NOT touch it.
 * @file file.func.php
 * @author SkyWorld
 * @date 2011-1-19
 * @description This file contains all funtions about file option
 **/
if(!defined('IN_NCG')) exit ('Access Denied.');

    /**
	 * @name imageUpload
	 * @author SkyWorld
	 * @date 2011-1-19
	 * @description this function to call will upload a image to an file
     * @parameters  [string] $file: the name of uploading
     * 				[string] $newName: new filename of the uploaded file
     * @return [int]:
     * 			-3: illegal file
     * 			-2: can not move file
     * 			-1: wrong type
     * 			 0: upload successfully
     * 			 1: over the maxsize of PHP ini
     * 			 2: over the maxsize of HTML form
     * 			 3: upload partly
     *           4: no file to upload
     **/

function imageUpload($file, $newName)
{
	if($_FILES[$file]['error']>0)
	{
		return $_FILES[$file]['error'];
	}

	$e = explode(".", $_FILES[$file]['name']);
	$subType = strtolower ($e[count($e)-1]);  // get image type
	if($subType!='gif' && $subType!= 'jpg' && $subType!='png'  && $subType!='jpeg')
	{
		return -1;
	}

	if(is_uploaded_file($_FILES[$file]['tmp_name']))
	{
		$newName .= '.'.$subType;
		if(!move_uploaded_file($_FILES[$file]['tmp_name'],$newName))
		{
			return -2;
		}
	}
	else
	{
		return -3;
	}
	return 0;
}
// end off script