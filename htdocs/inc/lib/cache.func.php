<?php
/**
 * If you are not authorized to modify this file, do NOT touch it.
 * @file cache.func.php
 * @author SkyWorld
 * @date 2011-1-18
 * @description This file contains all funtions about cache
 **/

if(!defined('IN_NCG')) exit ('Access Denied.');

    /**
	 * @name cachePage
	 * @author SkyWorld
	 * @date 2011-1-18
	 * @description this function to call will set up and handle output caching
     * @parameters  $refresh: time before cache to be refreshed
     * @return void
     **/
function cachePage($refresh = 3600)
{

	$hash = sha1($_SERVER['PHP_SELF'].'|G|'.serialize($_GET).'|P|'.serialize($_POST).'|C|'.serialize($_COOKIE).'|S|'.serialize($_SESSION)).'.php';
	$file = NCG_CACHE.$hash;
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
	 * @date 2011-1-18
	 * @description this function will be registered in function cachePage
	 * 				this function to call will get page contents and flush page
	 * @parameters  $file: path and filename of cached page
     * @return void
     **/
function endCache($file)
{
	//get page data and preventing illegal access to cache files.
	$output = ob_get_flush();

	flush();

	file_put_contents($file, $output, LOCK_EX);

}

// end of script