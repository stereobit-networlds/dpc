<?php
$__DPCSEC['ADMINFS_DPC']='2;1;1;1;1;1;1;1;9';

if ((!defined("ADMINFS_DPC")) && (seclevel('ADMINFS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("ADMINFS_DPC",true);

$__DPC['ADMINFS_DPC'] = 'adminfs';

$__EVENTS['ADMINFS_DPC'][0]='adminfs';
$__EVENTS['ADMINFS_DPC'][1]='cnfedit';
$__EVENTS['ADMINFS_DPC'][2]='txtedit';
$__EVENTS['ADMINFS_DPC'][3]='htmledit';

$__ACTIONS['ADMINFS_DPC'][0]='adminfs';
$__ACTIONS['ADMINFS_DPC'][1]='cnfedit';
$__ACTIONS['ADMINFS_DPC'][2]='txtedit';
$__ACTIONS['ADMINFS_DPC'][3]='htmledit';

$__DPCATTR['ADMINFS_DPC']['adminfs'] = 'adminfs,1,0,1,1,1,0,0,0,0';
$__DPCATTR['ADMINFS_DPC']['cnfedit'] = 'cnfedit,1,0,1,1,1,0,0,0,0';
$__DPCATTR['ADMINFS_DPC']['txtedit'] = 'txtedit,1,0,1,1,1,0,0,0,0';
$__DPCATTR['ADMINFS_DPC']['htmledit'] = 'htmledit,1,0,1,1,1,0,0,0,0';

$__LOCALE['ADMINFS_DPC'][0] = 'ADMINFS_DPC;Admin fs;Admin fs';


class adminfs {
	
    var $userLevelID;

    var $outpoint;
	var $bullet;
	var $rightarrow;
	var $agent;
    var $url;
	var $fspath;
	var $fs;	
	
	function adminfs() { 
	    $UserSecID = GetGlobal('UserSecID');
	    $__USERAGENT = GetGlobal('__USERAGENT');	
	    $GRX = GetGlobal('GRX');	
		
        $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);		
		$this->agent = $__USERAGENT;
        $this->url = $_SERVER['HTTP_HOST'];	
		
		$this->fsalias = paramload('FSYSTEM','fs');
        $this->fspath = paramload('SHELL','prpath');		

        if ($GRX) {
			 $this->outpoint = loadTheme('point');
			 $this->bullet = loadTheme('bullet');
             $this->rightarrow = loadTheme('rarrow');
		}
	    else {
			 $this->outpoint = "|";
			 $this->bullet = "&nbsp;";
	         $this->rightarrow = ">";
		}
	}

    function event($sAction) {

       switch($sAction) {
	 
         case "adminfs"  : break;			 		 
         case "cnfedit"  : break;	
         case "txtedit"  : break;	
         case "htmledit" : break;			 		 		 
       }
	}

    function action($action) {	
	   $c = GetReq('c');

	   switch ($action) {
		 case "adminfs"  : $data = $this->file_browser($c); 
		 	               $swin = new window($c,$data);
	                       $out = $swin->render("center::99%::0::group_win_body::left::0::0::");	
	                       unset ($swin);	
		                   break;							
         case "cnfedit"  : break;	
         case "txtedit"  : break;	
         case "htmledit" : break;			 		 		 
	   }

		return ($out);
	}

	
	
function readtfile($file) {

  $fp = fopen($file,"r");
  $fdata = fread($fp,filesize($file));

  fclose($fp);
  
  return ($fdata);
} 

function writetfile($file,$data) {

  $fp = fopen($file,"w");

  if ($fp) {
    fwrite($fp,$data);

    fclose($fp);

    return true;
  }

  return false;
  
}


function readini($inifile) {
     $inivals = array();
	 
	 if (file_exists($inifile)) {
     $ini_file = file ($inifile);
	 
	 if (is_array($ini_file)) {
        //while (list ($line_num, $line) = each ($ini_file)) {
	    foreach ($ini_file as $line_num => $line) {
		   $split = explode ("=", $line);
           $inivals[$split[0]] = rtrim($split[1]);   
        }	 
	 }
	 return ($inivals);
	 }
 }
 
function getfs_alias($fs=null) {
  $lan = GetReq('lan');

  if ($lan) $path = $this->fspath . $lan . "/" . $this->fsalias . "/";
       else $path = $this->fspath . "/" . $this->fsalias . "/";

  $alias = readini($path."fs.ini");
  //print_r($alias);
  //echo $path;

  if ($fs) {
   // $fsp = explode("/",$fs);
   // $maxc = count($fsp);
   // $fs1 = $fsp[$maxc-1];

   // return ($alias[$fs1]); 
    return ($alias[$fs]); 

  }
  else 
    return ($alias); //array
}

function show_fs_path($viewtype=null) {
  $lan = GetReq('lan');
  $fs = GetReq('fs');

  $alias = $this->getfs_alias();

  $fsp = explode("/",$fs);
  $maxc = count($fsp)-1;
  $i=0;

  //home link
  $link = seturl("t=fs&lan=$lan", $alias['home']);
  $ret = $link . "&nbsp;$this->rightarrow&nbsp;";

  foreach ($fsp as $id=>$cp) {
  
    if ($fs_link) $fs_link .= "/" . $cp;
             else $fs_link = $cp;
    //echo $fs_link,"<br>";

    switch ($viewtype) {
        case '1': $link = seturl("t=fs&fs=" . $fs_link . "&lan=" . $lan, $alias[$fs_link]); break;
        default : $link = seturl("t=fs&fs=" . $fs_link . "&lan=" . $lan, $alias[$fs_link]);
    }  
    if ($i<$maxc)
        $ret .= $link . "&nbsp;$this->rightarrow&nbsp;";
    else
        $ret .= $alias[$fs_link];

    $i+=1;
  }

  return ($ret);
}

function getfs_type($fs=null) {
  $lan = GetReq('lan');

  if ($lan) $path = $this->fspath . $lan . "/" . $this->fsalias . "/";
       else $path = $this->fspath . "/" . $this->fsalias . "/";

  $type = $this->readini($path."config.ini");
  //print_r($alias);
  //echo $path;

  if ($fs) {
    return ($type[$fs]); 
  }
  else 
    return ($type); //array
}

function getfs_oftype($typ='TXT') {

  $alias = $this->getfs_alias();
  $type = $this->getfs_type();

  $ret = "<select name=\"directory\"><option selected >Select path</option>";
  reset($type);
  foreach ($type as $p=>$t) {
   
    if ($t==$typ) $ret .= "<option>" . $alias[$p] . "</option>"; 
  }

  return ($ret);
}

function file_browser($dir=null,$type=null) {
  $lan = GetReq('lan');

  if ($lan) $path = $this->fspath . $lan;
       else $path = $this->fspath;
  if ($dir) $cpath = $path . $dir . "/";  
       else $cpath = $path;

  $fpath = $cpath;

  if (is_dir($fpath)) {

    $mydir = dir($fpath); //echo $fpath;

    while ($fileread = $mydir->read()) {//echo $fileread,"<br>";

      if ((is_dir($fpath.$fileread)) && ($fileread!='.') && ($fileread!='..') ) {


         //echo "<li><a href=" . URL . "admin/browser.phtml?lan=$lan&c=" . $dir."/".$fileread . ">$fileread</a>";
		 $out .= $this->bullet . seturl("t=adminfs&c=". $dir."/".$fileread,$fileread) . "<br>"; 
      }
      else/*if (is_file($fpath.fileread))*/ {

         $ffpath = urlencode($fpath . $fileread);
       
         if (strstr($fileread,".txt"))
           //echo "<li><a href=" . URL . "admin/txtedit.phtml?lan=$lan&c=$ffpath" .">$fileread</a>";
		   $out .= $this->bullet . seturl("t=txtedit&c=". $ffpath,$fileread) . "<br>";
         elseif ( (strstr($fileread,".htm")) || (strstr($fileread,".html")) )
           //echo "<li><a href=" . URL . "admin/htmledit.phtml?lan=$lan&c=$ffpath" .">$fileread</a>";
		   $out .= $this->bullet . seturl("t=htmledit&c=". $ffpath,$fileread) . "<br>";
         elseif (strstr($fileread,".ini"))
           //echo "<li><a href=" . URL . "admin/cnfedit.phtml?lan=$lan&c=$ffpath" .">$fileread</a>";
		   $out .= $this->bullet . seturl("t=cnfedit&c=". $ffpath,$fileread) . "<br>";
         else
           //echo "<li>" . $fileread;
		   $out .= $this->bullet . $fileread . "<br>";

      }

    }

    $mydir->close();
	
	return ($out);
  } 
  
}

};
}
?>