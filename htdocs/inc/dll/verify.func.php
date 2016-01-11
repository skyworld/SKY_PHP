<?php

function VerifyUsername($str)
{
    if (strlen($str) > 20 || strlen($str) < 4)
    	return -1;
    else if (preg_match("/\`|\?|\'|\"|\^|\||\&|\*|\!|\<|\>|\(|\)|\//", $str))
    	return -2;
    else return 1;
}


function VerifyPassword($str)
{
    if (strlen($str) > 20 || strlen($str) < 6)
    	return -1;
    else
    {
    	$a = 0;
		if(preg_match("/[\w]/",$str)) $a++;
    	if(preg_match("/[\~\!\@\#\%\^\&\*\'\"\<\>\(\)]/",$str)) $a++;
    	if(strlen($str)>10) $a++;

		if($a==0 ||$a==1) return 1;
		else return $a;
    }
}

function VerifyRealName($str)
{
    if (!preg_match("/^[\x80-\xff]+$/", $str)) return false;
    return true;
}

function VerifyEmail($str)
{
    if (!preg_match("/^([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4})$/", $str)) return false;
    return true;
}

function VerifyMobile($str)
{
    if (!preg_match("/^\d{11}$/", $str)) return false;
    return true;
}

function VerifyTime($timestr)
{
    if (!preg_match("/\b(\d{4})\s*[-\/.,]\s*(\d{1,2})\s*[-\/.,]\s*(\d{1,2})\b/", $timestr)) return false;
    if (!preg_match("/\b(\d{1,2})\s*[:��]\s*(\d{1,2})\s*[:��]\s*(\d{1,2})\b/", $timestr)) return false;
    return true;
}

function VerifyGroupName($str)
{
    if (!preg_match("/^[^<>\?&'\"]{2,30}$/", $str)) return false;
    return true;
}

function VerifyCommon($str)
{
	if (!preg_match("/^[^<>\?&'\"]{1,400}$/", $str)) return false;
    return true;
}

// end of script