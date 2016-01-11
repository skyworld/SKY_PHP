<?php
/**
 * If you are not authorized to modify this file, do NOT touch it.
 * @file promote.func.php
 * @author SkyWorld
 * @date 2011-9-13
 * @description This file contains all funtions to promote program's efficiency
 **/

if(!defined('IN_SKY')) exit ('Access Denied.');

    /**
	 * @name getMicrotime
	 * @author SkyWorld
	 * @date 2011-11-12
	 * @description this function to call you will get the system microtime
     * @parameters  void
     * @return [float] the system microtime
     **/
function getMicrotime(){
	list($usec,$sec) = explode(" ",microtime());
	$num = (float)$usec+(float)$sec;
	return  sprintf("%.5f",$num);
}


    /**
	 * @name getRunTimeInfo
	 * @author SkyWorld
	 * @date 2011-11-12
	 * @description This function to call you will get run time information
	 * 				this function will return an string cotains the information including
	 * 				script run time and memory usage.
     * @parameters  void
     * @return [float] the system microtime
     **/
function getRunTimeInfo(){
	global $sky_start_time;
	$mtime = explode(' ', microtime());
	$end_time = $mtime[1] + $mtime[0];
	return sprintf('<div style="background:green;color:white;margin:auto;">Run time %.5f s,  Memory usage %.5f Mb</div>'
					,$end_time-$sky_start_time,memory_get_usage()/(1024*1024));
}

    /**
	 * @name cachePage
	 * @author SkyWorld
	 * @date 2011-9-13
	 * @description this function to call will set up and handle output caching
     * @parameters  $refresh: time before cache to be refreshed
     * @return void
     **/
function cachePage($refresh = 3600)
{
	if (!file_exists(SKY_CACHE))
	{
		@mkdir(SKY_CACHE, 0777, true) or die("Cache dir can not be created!");
	}
	$hash = sha1($_SERVER['PHP_SELF'].'|G|'.serialize($_GET).'|P|'.serialize($_POST).'|C|'.serialize($_COOKIE).'|S|'.serialize($_SESSION)).'.php';
	$file = SKY_CACHE.$hash;
	if(file_exists($file) && time()-@filemtime($file)<$refresh)
	{
		readfile($file);
		exit();
	}
	else
	{
		ignore_user_abort();
		register_shutdown_function('endCache',$file);
		ob_start();
	}
}

    /**
	 * @name encCache
	 * @author SkyWorld
	 * @date 2011-9-12
	 * @description this function will be registered in function cachePage
	 * 				this function to call will get page contents and flush page
	 * @parameters  $file: path and filename of cached page
     * @return void
     **/
function endCache($file)
{
	//get page data and preventing illegal access to cache files.
	$output ='<?php if(!defined(\'IN_SKY\')) exit (\'Access Denied.\'); ?>';
	$output .= ob_get_flush();
	flush();
	file_put_contents($file, $output, LOCK_EX);

}

// end off script
