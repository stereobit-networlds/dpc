<?php
if (!defined("ZIP_DPC")) {
define("ZIP_DPC",true);

//$__DPC['ZIP_DPC'] = 'zip';

require_once("zip.lib.php");
	
class zip extends {
    
	function zip($files=null,$zipname='out.zip') {
	
	   $addedfiles = implode(",",$files);
	   
	   $z = new PHPZip();
	   $z -> Zip($addedfiles, $zipname);
	}
	
	 
}
}	
?>