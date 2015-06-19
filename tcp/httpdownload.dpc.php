<?php

/*
$FILENAME = ""; //SET YOUR FILE HERE

if (!$FILENAME || !file_exists($FILENAME)) {
	echo "Please set your target file \$FILENAME on line 2\n";
	exit();
}


include_once "class.httpdownload.php";
$object = new httpdownload;

$bandwidth = @intval(implode('',file('bandwidth.txt'))) / 1024;
if ($bandwidth > 1024)
{
	$bandwidth = round($bandwidth / 1024 , 2);
	$bandwidth .= " MB";
}
else
{
	$bandwidth .= " KB";
}


switch (@$_SERVER['QUERY_STRING']) {
case 'resume_speed':
case 'noresume_speed':
case 'resume':
case 'noresume':
	$object->set_byfile($FILENAME);
	if (@$_SERVER['QUERY_STRING'] != 'resume') $object->use_resume = false;
	if (strpos($_SERVER['QUERY_STRING'],'speed') !== false ) $object->speed = 100;
	
	$object->download();
break;
case 'data':
case 'dataresume':
	$data = implode('' , file($FILENAME));
	$object->set_bydata($data);
	if (@$_SERVER['QUERY_STRING'] != 'dataresume') $object->use_resume = false;
	$object->set_filename(basename($FILENAME));
	$object->download();
break;
case 'auth':
	$object->set_byfile($FILENAME);
	$object->use_auth = true;
	$object->handler['auth'] = "test_auth";
	$object->download();
break;
case 'url':
	$object->set_byurl('http://www.php.net/get/php_manual_chm.zip/from/cr.php.net/mirror');
	$object->download();
break;
}

if ($object->bandwidth > 0)
{
	error_reporting(E_NONE);
	$b = intval(implode('',file('bandwidth.txt'))) + $object->bandwidth;
	$f = fopen('bandwidth.txt','wb');
	fwrite($f,$b);
	fclose($f);
	exit;
}

function test_auth($user,$pass) { //test authentication function
	if ($user == 'user' && $pass == 'pass') return true;
	return false;
}
*/

/*
<head>
<style>
<!--
body         { font-family: Tahoma; font-size: 12px }
a            { color: #FF0000 }
-->
</style>
</head>

<title>HTTPDownload example</title>

<h2><font color="navy">HttpDownload</font></h2>Select a link and try it with a download manager (like <a href="http://reget.com">Reget</a>) .<br><br>

Total bandwidth used : <B><?=$bandwidth?></B>

<br><br>
<a href="test.php?noresume">Download file</a><br>
<a href="test.php?noresume_speed">Download file (speed limit 100 kbs)</a><br>
<a href="test.php?resume">Download file with resume</a><br>
<a href="test.php?resume_speed">Download file with resume (speed limit 100 kbs) </a><br>
<a href="test.php?data">Download file data (May slow)</a><br>
<a href="test.php?dataresume">Download file data with resume (May slow)</a><br>
<a href="test.php?auth">Authentication download (user/pass)</a><br>
<a href="test.php?url">URL Download (simple redirect)</a><br>

<p><font size="1"><font color="#808080">( Click 
<a href="http://en.vietapi.com/wiki/index.php/PHP:_HttpDownload">
<font color="black">here</font></a><font color=""> to view class 
information )</font></p>
*/


$__DPCSEC['HTTPDOWNLOAD_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("HTTPDOWNLOAD_DPC")) && (seclevel('HTTPDOWNLOAD_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("HTTPDOWNLOAD_DPC",true);

$__DPC['HTTPDOWNLOAD_DPC'] = 'httpdownload';

$__EVENTS['HTTPDOWNLOAD_DPC'][0]='httpdownload';
$__EVENTS['HTTPDOWNLOAD_DPC'][1]='resume_speed';
$__EVENTS['HTTPDOWNLOAD_DPC'][2]='noresume_speed';
$__EVENTS['HTTPDOWNLOAD_DPC'][3]='resume';
$__EVENTS['HTTPDOWNLOAD_DPC'][4]='noresume';
$__EVENTS['HTTPDOWNLOAD_DPC'][5]='data';
$__EVENTS['HTTPDOWNLOAD_DPC'][6]='dataresume';
$__EVENTS['HTTPDOWNLOAD_DPC'][7]='auth';
$__EVENTS['HTTPDOWNLOAD_DPC'][8]='url';

$__ACTIONS['HTTPDOWNLOAD_DPC'][0]='httpdownload';
$__ACTIONS['HTTPDOWNLOAD_DPC'][1]='resume_speed';
$__ACTIONS['HTTPDOWNLOAD_DPC'][2]='noresume_speed';
$__ACTIONS['HTTPDOWNLOAD_DPC'][3]='resume';
$__ACTIONS['HTTPDOWNLOAD_DPC'][4]='noresume';
$__ACTIONS['HTTPDOWNLOAD_DPC'][5]='data';
$__ACTIONS['HTTPDOWNLOAD_DPC'][6]='dataresume';
$__ACTIONS['HTTPDOWNLOAD_DPC'][7]='auth';
$__ACTIONS['HTTPDOWNLOAD_DPC'][8]='url';

$d = GetGlobal('controller')->require_dpc('tcp/httpdownload.lib.php');
require_once($d); 

class httpdownload {

   var $FILENAME,$object,$bandwidth;

   function httpdownload($myfile=null) {
	  
	  //because it is included as dpc object (not as lib)
	  //actions and events procced as normal
	  //but FILENAME iss et at construct and can't be changed from inside of
	  //another dpc ... so change it thru session param witch is set by the 
	  //other dpc and then call set_download method
	  if ($sesfile = GetSessionParam('HTTPDOWNLOADFILE'))
	    $this->FILENAME = $sesfile;//session parameter
	  elseif (isset($myfile))
	    $this->FILENAME = $myfile;//as constructor param (see load as lib)	
	  //else	//else test file
	    //$this->FILENAME = "c:/php/webos/projects/re-coding/demo/delphi2java.zip";
		
      $this->object = new _httpdownload;
	  
      $this->bandwidth = @intval(implode('',file('bandwidth.txt'))) / 1024;
      if ($this->bandwidth > 1024) {
	      $this->bandwidth = round($this->bandwidth / 1024 , 2);
	      $this->bandwidth .= " MB";
      }
      else {
	      $this->bandwidth .= " KB";
      }	    
   }
   
   function init($myfile) {
   
      if (isset($myfile)) {
	  
	    $this->FILENAME = $myfile;
	  }		  	   
   }
   
   function event($event=null) {
   
      //switch (@$_SERVER['QUERY_STRING']) {
	  switch ($event) {
		case 'resume_speed':
		case 'noresume_speed':
		case 'resume':
		case 'noresume':
						$this->object->set_byfile($this->FILENAME);
						//if (@$_SERVER['QUERY_STRING'] != 'resume') $object->use_resume = false;
						//if (strpos($_SERVER['QUERY_STRING'],'speed') !== false ) $object->speed = 100;
						if ($event != 'resume') $this->object->use_resume = false;
						if (strpos($event,'speed') !== false ) $this->object->speed = 100;
	
						$this->object->download();
						break;
		case 'data':
		case 'dataresume':
						$data = implode('' , file($this->FILENAME));
						$this->object->set_bydata($data);
						//if (@$_SERVER['QUERY_STRING'] != 'dataresume') $object->use_resume = false;
						if ($event != 'dataresume') $this->object->use_resume = false;
						$this->object->set_filename(basename($this->FILENAME));
						
						$this->object->download();
						break;
	    case 'auth':
						$this->object->set_byfile($this->FILENAME);
						$this->object->use_auth = true;
						$this->object->handler['auth'] = "test_auth";
						
						$this->object->download();
						break;
		case 'url':     //SET THE URL
						$this->object->set_byurl('http://www.php.net/get/php_manual_chm.zip/from/cr.php.net/mirror');
						$this->object->download();
						break;
	  }   
	  
      if ($this->object->bandwidth > 0) {
 	    error_reporting(E_NONE);
	    $b = intval(implode('',file('bandwidth.txt'))) + $this->object->bandwidth;
	    $f = fopen('bandwidth.txt','wb');
	    fwrite($f,$b);
	    fclose($f);
	    exit;
      }	  
   }
   
   function action($action=null) {
   
      $ret = $this->select_download_type();
	  return ($ret);
   }
   
   function test_auth($user,$pass) { //test authentication function
	  if ($user == 'user' && $pass == 'pass') return true;
	  return false;
   }
   
   
   function select_download_type() {
   
/*      $ret = <<<EOF
<a href="?noresume">Download file</a><br>
<a href="?noresume_speed">Download file (speed limit 100 kbs)</a><br>
<a href="?resume">Download file with resume</a><br>
<a href="?resume_speed">Download file with resume (speed limit 100 kbs) </a><br>
<a href="?data">Download file data (May slow)</a><br>
<a href="?dataresume">Download file data with resume (May slow)</a><br>
<a href="?auth">Authentication download (user/pass)</a><br>
<a href="?url">URL Download (simple redirect)</a><br>   
EOF;*/

   $ret  = seturl("t=noresume","Download file") . "<br>";
   $ret .= seturl("t=noresume_speed","Download file (speed limit 100 kbs)") . "<br>";
   $ret .= seturl("t=resume","Download file with resume") . "<br>";
   $ret .= seturl("t=resume_speed","Download file with resume (speed limit 100 kbs)") . "<br>";
   $ret .= seturl("t=data","Download file data") . "<br>";
   $ret .= seturl("t=dataresume","Download file data with resume") . "<br>";         
   $ret .= seturl("t=auth","Authentication download (user/pass)") . "<br>"; 
   $ret .= seturl("t=url","URL Download (simple redirect)") . "<br>";       

   return ($ret);
   }  
   
   function set_download($type=null,$title=null) {
   
     switch ($type) {

	   case 'URL'    : $mytitle = (isset($title)?$title:"URL Download (simple redirect)");
	                   $ret  = seturl("t=url",$mytitle); 
				       break;	 
	   case 'AUTH'   : $mytitle = (isset($title)?$title:"Authentication download (user/pass)");
	                   $ret  = seturl("t=auth",$mytitle); 
				       break;		 
	   case 'RDATA'  : $mytitle = (isset($title)?$title:"Download file data with resume");
	                   $ret  = seturl("t=dataresume",$mytitle); 
				       break;	 
	   case 'DATA'   : $mytitle = (isset($title)?$title:"Download file data");
	                   $ret  = seturl("t=data",$mytitle); 
				       break;	 
	   case 'RSPEED' : $mytitle = (isset($title)?$title:"Download file with resume (speed limit 100 kbs)");
	                   $ret  = seturl("t=resume_speed",$mytitle); 
				       break;	 
	   case 'RESUME' : $mytitle = (isset($title)?$title:"Download file with resume");
	                   $ret  = seturl("t=resume",$mytitle); 
				       break;
	   case 'NRSPEED': $mytitle = (isset($title)?$title:"Download file (speed limit 100 kbs)");
	                   $ret  = seturl("t=noresume_speed",$mytitle); 				 	 
	                   break;
	   default       : $mytitle = (isset($title)?$title:"Download file");
	                   $ret  = seturl("t=noresume",$mytitle); 
	 }
	 //echo $ret;
	 return ($ret);
   }
   
   function set_filename($filename) {
   
     SetSessionParam('HTTPDOWNLOADFILE',$filename);
   }
   
   function get_filename() {
   
     return (GetSessionParam('HTTPDOWNLOADFILE'));
   }
   
   
};
}
?>