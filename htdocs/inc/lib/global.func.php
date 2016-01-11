<?php
/**
 * 一些能够全局使用的方法
 * @author skyworld<pgg200@qq.com>
 * @package inc.lib
 */

if(!defined('IN_SKY')) exit ('Access Denied.');


/**
 * 自动加载方法
 * @param String $className 类名
 */
function autoload($className)
{
	$autoloadPath = array(
		SKY_LIB, 
		SKY_DLL,
		SKY_CONTROLLER,
		SKY_CONTROLLER.'/basecontroller/',
		SKY_MODEL,
		SKY_BUSINESS_SERVICE,
		SKY_BUSINESS_COMM
	);
	
	foreach($autoloadPath as $path)
	{
		$file = $path.$className.'.class.php';
		if(is_file($file))
		{
			require_once($file);
		}
	}
}



/**
 * If you are not authorized to modify this file, do NOT touch it.
 * @file global.func.php
 * @author SkyWorld
 * @date 2011-9-12
 * @description This file contains all the global functions of the framework
 */

if(!defined('IN_SKY')) exit ('Access Denied.');

    /**
	 * @name strip_sql
	 * @author SkyWorld
	 * @date 2011-9-12
	 * @description this function to call will strip the dangerous words of sql
     * @parameters  $string: string to be strip off, the string can also be an array
     * @return $string: also can be an array
     **/
function strip_sql($string)
{
	global $search_arr,$replace_arr;
	return is_array($string) ? array_map('strip_sql',$string) : preg_replace($search_arr,$replace_arr,$string);
}

    /**
	 * @name new_addslashes
	 * @author SkyWorld
	 * @date 2011-9-12
	 * @description this function to call will strip the dangerous words of sql
     * @parameters  $string: string to be add slashes, and $string also can be array
     * @return $string: also can be an array
     **/
function new_addslashes($string)
{
	if(!is_array($string)) return addslashes($string);
	foreach($string as $key => $val)
		$string[$key] = new_addslashes($string);
	return $string;
}


    /**
	 * @name tplrefresh
	 * @author SkyWorld
	 * @date 2011-9-12
	 * @description To judge wether the template need to be refreshed
	 * 				If need refreshing return true, otherwise return false
     * @parameters String $comFile compiled file's path
     * 			   String $tplFile template file's path
     * @return boolen  If the template need to be refresh return true, otherwise return false
     **/
function tplrefresh($comFile,$tplFile){
	if(!is_readable($comFile))
		return true;
	if(@filemtime($comFile) > @filemtime($tplFile))
		return false;
	else
		return true;
}

    /**
	 * @name tplrefresh
	 * @author SkyWorld
	 * @date 2011-9-17
	 * @description This funtion used to parse templates
	 * 				If teplates has been complied return the complied filename
	 * 				Otherwise complie the templates, and then return the complied filename
     * @parameters  void
     * @return bool
     **/
function template($fileName){

	$fileName = trim($fileName);
	$fileDir = dirname($fileName)=='.' || dirname($fileName)=='/' ? '' : dirname($fileName).'/';
	$tplFile = SKY_TEMPLATES.$fileName;
	$comFile = SKY_TEMPLATES_C.$fileDir.'com_'.basename($fileName).'.php';
	$templates_c_dir = dirname($comFile);

	if (is_readable($comFile) && !tplrefresh($comFile,$tplFile)){
		return $comFile;
	}

	$temp="<?php if (!defined('IN_SKY')) exit ('Access Denied.'); ?>\n";
	$temp .=@file_get_contents($tplFile);

	/*patterns array*/
	if(file_exists($tplFile))
	{
	$pattern = array(
		'/\{\s*template\s*(.+?)\s*\}/',
		'/\{\s*\$([\w\x7f-\xff][\w\x7f-\xff\[\]\"\']*)\s*\}/i',
		'/<!--\s*if\s*(.+?)\s*-->/',
		'/<!--\s*else\s*-->/',
		'/<!--\s*else\s*if\s*(.+?)\s*-->/',
		'/<!--\s*\/if\s*-->/',
		'/<!--\s*loop\s+\\$(.+?)\s+\\$(.+?)\s+\\$(.+?)\s*-->/',
		'/<!--\s*loop\s+\\$(.+?)\s+\\$(.+?)\s*-->/',
		'/<!--\s*\/loop\s*-->/',
		'/\{\s*([A-Z_]+?)\s*\}/',
		'/\{\s*lang\s+(\w+?)\s*\}/',
		'/\{\s*lang\s+(\w+?)\s+(\w+?)\s*\}/',
		'/\{\s*php\s+(.+?)\s*\}/'
	);

	/*replacements array*/
	$replacement = array(
		'<?php include template ${1} ?>',
		'<?php echo $${1}; ?>',
		'<?php if ${1} { ?>',
		'<?php } else { ?>',
		'<?php } elseif ${1} { ?>',
		'<?php } ?>',
		'<?php foreach ($${1} as $${2} => $${3}) { ?>',
		'<?php foreach ($${1} as $${2}) { ?>',
		'<?php } ?>',
		'<?php echo ${1}; ?>',
		'<?php echo \$lang[\'${1}\']; ?>',
		'<?php echo \$lang[\'${1}\'][\'${2}\']; ?>',
		'<?php ${1}?>'
	);
	$temp = preg_replace($pattern,$replacement,$temp);
	$temp = trim($temp);

	if (!file_exists($templates_c_dir))
	{
		@mkdir($templates_c_dir, 0777, true) or die("File dir can not be created!");
	}
	file_put_contents($comFile, $temp);
	}else{
		die('Template file cannot be found.');
	}
	return $comFile;
}

/**
 * 根据生日计算年龄
 * @param  Date  $YTD 生日
 * @return Number     年龄
 */
function getAge($YTD){
	$YTD = strtotime($YTD);
	$year = date('Y', $YTD);
	if(($month = (date('m') - date('m', $YTD))) < 0){
  		$year++;
 	}else if ($month == 0 && date('d') - date('d', $YTD) < 0){
  		$year++;
 	}
 	return date('Y') - $year;
}



function do_post_request($url, $postdata, $files = null)
{
    $data = "";
    $boundary = "---------".substr(md5(rand(0,32000)), 0, 10);

    //Collect Postdata
    foreach($postdata as $key => $val)
    {
        $data .= "--$boundary\r\n";
        $data .= "Content-Disposition: form-data; name=\"".$key."\"\r\n\r\n".$val."\r\n";
    }

    $data .= "--$boundary\r\n";

    //Collect Filedata
    foreach($files as $key => $file)
    {
        $fileContents = file_get_contents($file['tmp_name']);
        $data .= "Content-Disposition: form-data; name=\"{$key}\"; filename=\"{$file['name']}\"\r\n";
        $data .= "Content-Type: image/png\r\n";
        $data .= "Content-Transfer-Encoding: binary\r\n\r\n";
        $data .= $fileContents."\r\n";
        $data .= "--$boundary--\r\n";
    }

    $params = array('http' => array(
           'method' => 'POST',
           'header' => 'Content-Type: multipart/form-data; boundary='.$boundary,
           'content' => $data
        ));



   $ctx = stream_context_create($params);
   $fp = fopen($url, 'rb', false, $ctx);

   if (!$fp) {
      die ("can not open server!");
   }

   $response = @stream_get_contents($fp);
   if($response === false) {
        die ("can not get message form server!");
      //throw new Exception("Problem reading data from {$url}, {$php_errormsg}");
   }
   return $response;
}


/**
* Respose A Http Request
*
* @param string $url
* @param array $post
* @param string $method
* @param bool $returnHeader
* @param string $cookie
* @param bool $bysocket
* @param string $ip
* @param integer $timeout
* @param bool $block
* @return string Response
*/
function httpRequest($url,$post='',$method='GET',$limit=0,$returnHeader=FALSE,$cookie='',$bysocket=FALSE,$ip='',$timeout=15,$block=FALSE) {
   $return = '';
   $matches = parse_url($url);

   !isset($matches['host']) && $matches['host'] = '';
   !isset($matches['path']) && $matches['path'] = '';
   !isset($matches['query']) && $matches['query'] = '';
   !isset($matches['port']) && $matches['port'] = '';

   $host = $matches['host'];
   $path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
   $port = !empty($matches['port']) ? $matches['port'] : 80;

   if(strtolower($method) == 'post') {
	   $post = (is_array($post) and !empty($post)) ? http_build_query($post) : $post;
	   $out = "POST $path HTTP/1.0\r\n";
	   $out .= "Accept: */*\r\n";
	   //$out .= "Referer: $boardurl\r\n";
	   $out .= "Accept-Language: zh-cn\r\n";
	   $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
	   $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
	   $out .= "Host: $host\r\n";
	   $out .= 'Content-Length: '.strlen($post)."\r\n";
	   $out .= "Connection: Close\r\n";
	   $out .= "Cache-Control: no-cache\r\n";
	   $out .= "Cookie: $cookie\r\n\r\n";
	   $out .= $post;
   } else {
	   $out = "GET $path HTTP/1.0\r\n";
	   $out .= "Accept: */*\r\n";
	   //$out .= "Referer: $boardurl\r\n";
	   $out .= "Accept-Language: zh-cn\r\n";
	   $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
	   $out .= "Host: $host\r\n";
	   $out .= "Connection: Close\r\n";
	   $out .= "Cookie: $cookie\r\n\r\n";
   }

   $fp = fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);

   if(!$fp) return ''; else {
	   $header = $content = '';
		 stream_set_blocking($fp, 0);
	//   stream_set_blocking($fp, $block);
//	   stream_set_timeout($fp, $timeout);
	   fwrite($fp, $out);
	return NULL;
	   $status = stream_get_meta_data($fp);
/*
	   if(!$status['timed_out']) {//未超时
		   while (!feof($fp)) {
			   $header .= $h = fgets($fp);
			   if($h && ($h == "\r\n" ||  $h == "\n")) break;
		   }

		   $stop = false;
		   while(!feof($fp) && !$stop) {
			   $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
			   $content .= $data;
			   if($limit) {
				   $limit -= strlen($data);
				   $stop = $limit <= 0;
			   }
		   }
	   }*/
	fclose($fp);
		return NULL;
	   return $returnHeader ? array($header,$content) : $content;
   }
}


function removeEmoji($text) {

    $clean_text = "";

    // Match Emoticons
    $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $clean_text = preg_replace($regexEmoticons, '', $text);

    // Match Miscellaneous Symbols and Pictographs
    $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $clean_text = preg_replace($regexSymbols, '', $clean_text);

    // Match Transport And Map Symbols
    $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
    $clean_text = preg_replace($regexTransport, '', $clean_text);

    // Match Miscellaneous Symbols
    $regexMisc = '/[\x{2600}-\x{26FF}]/u';
    $clean_text = preg_replace($regexMisc, '', $clean_text);

    // Match Dingbats
    $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);

    return $clean_text;
}

function sql_injection($content)  
{  
	if (!get_magic_quotes_gpc()) {  
		if (is_array($content)) {  
			foreach ($content as $key=>$value) {  
					$content[$key] = addslashes($value);  
				}  
		} else {  
				addslashes($content);  
			}  
		}  
	return $content;  
}

function http_get_data($url) {  
    $ch = curl_init ();  
    curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );  
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );  
    curl_setopt ( $ch, CURLOPT_URL, $url );  
    ob_start ();  
    curl_exec ( $ch );  
    $return_content = ob_get_contents ();  
    ob_end_clean ();  
      
    $return_code = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );  
    return $return_content;  
}
// end of script

