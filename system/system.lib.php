<?php

//global functions
require_once("globals.lib.php");

function GetGlobal($param) {
  
  //support session vars
  $ret = $_SESSION[$param];
  
  if ($ret) 
    return ($ret);
  //else...
  
  return ($GLOBALS[$param]);
}

function SetGlobal($param,$val=null) {
  
  $GLOBALS[$param] = $val;
}

/*require_once("sysdb.lib.php");
require_once("controller.lib.php");
require_once("dispatcher.lib.php");
require_once("session.lib.php");

//class libs
require_once("parser.lib.php");
require_once("ktimer.lib.php"); //ktimer class
require_once("azdgcrypt.lib.php"); //AZDGcrypt class
require_once("client.lib.php");
*/

//////////////////////////////////////////////
// SHARED MEMORY 
//////////////////////////////////////////////

//FTOK IS COPIED FROM UNIX LIBC---DISBALED FOR LINUX DISTRIBUTION---
//return a key for use on shmop_open
/*
function ftok($pathname, $proj_id) {

   $st = @stat($pathname);
   if (!$st) {
        return -1;
   }
   
   $key = sprintf("%u", (($st['ino'] & 0xffff) | (($st['dev'] & 0xff) << 16) | (($proj_id & 0xff) << 24)));
   return $key;
} 
*/
  
function shmem_read_ini($inifile,$proj_id,$ini_param=0) {

   $shm_key = ftok($inifile, $proj_id);  //get ftok of file
   $shm_id_{$proj_id} = @shmop_open($shm_key, "a", 0, 0); 
		 
   if(!$shm_id_{$proj_id}) {
   
		   $myini = @parse_ini_file($inifile,$ini_param);
		   $data = serialize($myini);
		   $sizeofdata = strlen($data);
           $shm_id_{$proj_id} = shmop_open($shm_key, "c", 0644, $sizeofdata);	
		   $shm_bytes_written = shmop_write($shm_id_{$proj_id}, $data, 0);
		   echo 'BYTES written :',$shm_bytes_written , '\n';		    
   }
   else {
   
   	       //shmop_delete($shm_id_{$proj_id});
		   //return(0);
		   
           $shm_size = shmop_size($shm_id_{$proj_id});
           echo "SHM Block Size: ".$shm_size. " has been opened.\n";
           // Now lets read the string back
           $my_string = shmop_read($shm_id_{$proj_id}, 0, $shm_size);
           if(!$my_string) {
              echo "Couldn't read from shared memory block\n";
			  $myini = @parse_ini_file($inifile,$ini_param);
           }
           echo "The data inside shared memory was: ".$my_string."\n\n\n";   		  
		   $myini = unserialize($my_string);
   }
   
   return ($myini); 
}

//////////////////////////////////////////////
// ENVIRONMENT
//////////////////////////////////////////////
function redirect($url) {
	 
   echo 'REDIRECT:' . $url;
   header("Location: http://".$url); 
   exit;   
}
   
function getthemicrotime() {
   
   list($usec,$sec) = explode(" ",microtime());
   return ((float)$usec + (float)$sec);
} 

function raise_error($err,$status='ECHO',$description='') {

   switch ($err) {
     case 3   : $error = "Error reading project file : 003"; break;
     case 2   : $error = "Invalid project : 002\n"; break;
	 case 1   : $error = "Invalid configuration : 001\n"; break;
	 case 0   : 
	 default  : $error = $description; 
   }
   
   switch ($status) {
     case 'EXIT': die($error.','.$err);
	 case 'ECHO': echo $error,',',$err; break;
	 case 'BACK': return ($error.','.$err);	 
   }
}

function get_filesize($dsize) { 

	if (strlen($dsize)>=10)
		return number_format($dsize/1073741824,1)." Gb";
	elseif (strlen($dsize)<=9 and strlen($dsize)>=7)
		return number_format($dsize/1048576,1)." Mb";
	else
		return number_format($dsize/1024,1)." Kb";
} 

function copyr($source, $dest) { 
        // Simple copy for a file 
        if (is_file($source)) { 
          return copy($source, $dest); 
        } 
  
        // Make destination directory 
        if (!is_dir($dest)) { 
           mkdir($dest); 
        } 
  
        // Loop through the folder 
        $dir = dir($source); 
        while (false !== $entry = $dir->read()) { 
          // Skip pointers 
          if ($entry == '.' || $entry == '..') { 
            continue; 
          } 
  
          // Deep copy directories 
          if ($dest !== "$source/$entry") { 
            $this->copyr("$source/$entry", "$dest/$entry"); 
          } 
        } 
  
        // Clean up 
        $dir->close(); 
        return true; 
}	 

function scanargs($label) {
  //global $argv,$argc;
  $argc = GetGlobal('argc');
  $argv = GetGlobal('argv');
   
  reset ($argv);  
  foreach ($argv as $arg_num => $arg) {  
    if ($arg==$label) {
	  //print $argv[$arg_num+1]; 
	  return ($argv[$arg_num+1]);
	}  
  }    
} 

function load_dl($extlib,$os) {

  if (!extension_loaded($extlib)) {
  
    /*if ($os=='WINDOWS') {
	  dl($extlib.".dll");
	}
	else {
	  dl($extlib.".so");
	} */ 

    dl($extlib . (strstr(PHP_OS, 'WIN') ? '.dll' : '.so'));
  }
}


function setsyspath($path,$type='UNIX') {
  
  switch ($type) {
    case 'WINDOWS':
    case 'MSDOS': $outpath = ereg_replace("/","\\",$path); break;	
	default     :
	case 'LINUX':
    case 'UNIX' : $outpath = ereg_replace("[\x5c\]","/",$path); break;	
  }

  if ($outpath) return ($outpath); 
           else return ($path);
}

function iniload($section) {
  $config = GetGlobal('config');

  if (is_array($config[$section])) 
    return TRUE;
  else
    return FALSE;
}

function paramload($section,$param) {
  $config = GetGlobal('config');
  //echo '<pre>'; print_r($config); echo '</pre>';
  if (is_array($config[$section]))     
	return ($config[$section][$param]);

}

function arrayload($section,$array) {
  $config = GetGlobal('config');
  
  if (is_array($config[$section])) {
    $data = $config[$section][$array];
	
	if ($data) return(explode(",",$data));
	//return ($out);
  }  
}

//GLOBAL
function remote_paramload($section,$param,$remoteapppath,$usepath=null) {
  $config = GetGlobal('config');
	
  if ($usepath) {//switch db case
    $config = @parse_ini_file($remoteapppath."config.ini",true);
	$t_config = @parse_ini_file($remoteapppath."myconfig.txt",true);
	
    if (is_array($t_config[$section]) && isset($t_config[$section][$param])) 
      return ($t_config[$section][$param]);
    elseif (is_array($config[$section]))     
	  return ($config[$section][$param]);	
  }
  
  //get from mem	
  if ($ret = $config[$section][$param]) 
    return ($ret);

}

function remote_arrayload($section,$array,$remoteapppath,$usepath=null) {
  $config = GetGlobal('config');
	
  if ($usepath) {//switch db case
    $config = @parse_ini_file($remoteapppath."config.ini",true);
	$t_config = @parse_ini_file($remoteapppath."myconfig.txt",true);
	
    if (is_array($t_config[$section]) && isset($t_config[$section][$array])) 
      return (explode(",",$t_config[$section][$array]));
    elseif (is_array($config[$section]))     
	  return (explode(",",$config[$section][$array]));	
  }	
	
  //get from mem	
  if ($data = $config[$section][$array]) 
    return(explode(",",$data));
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

 //use img from default image dir
 function loadimage($img,$alt=null,$altpicpath=null) {	
   
     $ip = $_SERVER['HTTP_HOST'];
     $pr = paramload('SHELL','protocol');
	 
	 if (isset($altpicpath))
	   $pp = $altpicpath;	 
	 else  
	   $pp = paramload('SHELL','picpath');	 
	 
	 //$source = paramload('SHELL','urlbase') . paramload('SHELL','picpath') . $img; 
	 $source = $pr . $ip . $pp . $img;	 
     
	 $out = "<img src=\"$source\" border=\"0\" alt=\"$alt\">";

	 return ($out);
 } 
 
//use img with full path 
function loadimagexy($img,$xmax=null,$ymax=null,$alt=null) {	   
     
	 if (($xmax) && ($ymax))
       $out = "<img src=\"$img\" border=\"0\" width=\"$xmax\" height=\"$ymax\" alt=\"$alt\">";	 
	 else
	   $out = "<img src=\"$img\" border=\"0\" alt=\"$alt\">";

	 return ($out);
 } 
 
 ////////////////////////////////////////////////////////////////
 //   ICON FUNCTIONS
 ////////////////////////////////////////////////////////////////
 function loadicon($icon,$comment='',$jscript='') { 
     $thema = GetGlobal('thema');	 
     $theme = GetGlobal('theme');	 	 
	 
     $ip = $_SERVER['HTTP_HOST'];
     $pr = paramload('SHELL','protocol'); 
	 $inpath = paramload('ID','hostinpath');
	 	 
	 if (!$thema) $thema = paramload('SHELL','deftheme');
     $themepath = $inpath . '/' . $theme['path'] . $thema . ".theme"; 	  

	 //$source = paramload('SHELL','urlbase') . $themepath . $icon; 
	 $source = $pr . $ip . $themepath . $icon; 
	      
	 $out = "<img src=\"$source\" border=\"0\" alt=\"$comment\" $jscript>";

	 return ($out);
 }
 
 function icon($iconpath='',$url,$title,$vtype=0,$ssl=0) {
    
    $iconimg = seturl($url,loadicon($iconpath,$title),$ssl);	  
	$icontitle = "<B>" . seturl($url,$title,$ssl) . "</B>";   
	
	
	switch ($vtype) {
	  default :
	  case 0  : //icon image and title at bottom
	            if ($iconpath) {
	              $iwin1 = new window('',$iconimg);
	              $out = $iwin1->render("center::100%::0::group_icons::center::0::0::");
	              unset ($iwin1);
	            }
	            $iwin2 = new window('',$icontitle);
	            $out .= $iwin2->render("center::100%::0::group_icons::center::0::0::");
	            unset ($iwin2);
				break;
				
	  case 1  : //icon image and title at right
	            if ($iconpath) {			
	              $myicon[] = $iconimg;
				  $iattr[] = "left;1%";
	            }			
	            $myicon[] = $icontitle;
				$iattr[] = "left;99%;middle;";
				  								
	            $iwin1 = new window('',$myicon,$iattr);
	            $out = $iwin1->render("center::100%::0::group_icons::left::0::0::");
	            unset ($iwin1);
				break;				
				
	  case 2  : //only icon image
	            if ($iconpath) {
	              //$iwin1 = new window('',$iconimg);
	              $out = $iconimg;// $iwin1->render("center::100%::0::group_icons::center::0::0::");
	              //unset ($iwin1);
	            }
				break;				
								
	  case 3  : //only icon title
	            if ($icontitle) {
	              //$iwin1 = new window('',$icontitle);
	              $out = $icontitle;//$iwin1->render("center::100%::0::group_icons::center::0::0::");
	              //unset ($iwin1);
	            }
				break;					
	}			
			
	return ($out);
 }
 
 //same as icon but url is plain text
 function icon2($iconpath='',$url,$title,$vtype=0,$ssl=0) {
    
    $iconimg = url($url,loadicon($iconpath,$title));	  
	$icontitle = "<B>" . url($url,$title) . "</B>";   
	
	
	switch ($vtype) {
	  default :
	  case 0  : //icon image and title at bottom
	            if ($iconpath) {
	              $iwin1 = new window('',$iconimg);
	              $out = $iwin1->render("center::100%::0::group_icons::center::0::0::");
	              unset ($iwin1);
	            }
	            $iwin2 = new window('',$icontitle);
	            $out .= $iwin2->render("center::100%::0::group_icons::center::0::0::");
	            unset ($iwin2);
				break;
				
	  case 1  : //icon image and title at right
	            if ($iconpath) {			
	              $myicon[] = $iconimg;
				  $iattr[] = "left;1%";
	            }			
	            $myicon[] = $icontitle;
				$iattr[] = "left;99%;middle;";
				  								
	            $iwin1 = new window('',$myicon,$iattr);
	            $out = $iwin1->render("center::100%::0::group_icons::left::0::0::");
	            unset ($iwin1);
				break;				
				
	  case 2  : //only icon image
	            if ($iconpath) {
	              //$iwin1 = new window('',$iconimg);
	              $out = $iconimg;// $iwin1->render("center::100%::0::group_icons::center::0::0::");
	              //unset ($iwin1);
	            }
				break;				
								
	  case 3  : //only icon title
	            if ($icontitle) {
	              //$iwin1 = new window('',$icontitle);
	              $out = $icontitle;//$iwin1->render("center::100%::0::group_icons::center::0::0::");
	              //unset ($iwin1);
	            }
				break;					
	}			
			
	return ($out);
 } 
 
 
 /////////////////////////////////////////////////////////////////
 // THEME FUNCTIONS
 /////////////////////////////////////////////////////////////////
 function loadTheme($them,$comment='',$nohtml=0,$jscript='',$relativepath=0) {

     $thema = GetGlobal('thema');	 
     $theme = GetGlobal('theme');
	 
     $ip = $_SERVER['HTTP_HOST'];
	 //$subpath = pathinfo($_SERVER['PHP_SELF'],PATHINFO_DIRNAME); 
	 //echo $subpath;
	 $inpath = paramload('ID','hostinpath');
	 
     $pr = paramload('SHELL','protocol');	 
	 
	 if (!$thema) $thema = paramload('SHELL','deftheme');

	 //$url = paramload('SHELL','urlbase');
	 $url = $pr . $ip;	 
     $path = $inpath . '/' . $theme['path'] . $thema . ".theme/"; 
	 
	 //select path type 0=absolute addresss (http://...),1=relative address (/xxx/yy)	 
	 (($relativepath) ? $source = $path . $theme[$them] :
	                    $source = $url . $path . $theme[$them]);
     //echo $source,'<br>';
	 if ($theme[$them]) {
	   if (!$nohtml) 
	     $out = "<img src=\"". $source . "\" border=\"0\" alt=\"$comment\" $jscript>";
	   else 
	     $out = $source;
	 }
	 else
	   $out = null;		  

	 return ($out);
 }

 
 function getThemepath() {	 
       $theme = GetGlobal('theme');
	   
       $ip = $_SERVER['HTTP_HOST'];
       $pr = paramload('SHELL','protocol');		
	   $url = $pr . $ip;		      
	   
	   $param = (getTheme() ? getTheme() : paramload('SHELL','deftheme'));	   
	   
       $ptheme = "/" . $theme['path'];
	   $ntheme = $param . ".theme/"; 
	   $themepath = $url . $ptheme . $ntheme; 	      
	   
	   //echo $themepath;
	   return ($themepath);
 }  
 
////////////////////////////////////////
// SECURITY
////////////////////////////////////////
function encode($var,$default=false) {
		
	if ($var) {
	  
	  if ((defined("CRYPT_DPC")) && ($default==false)) {
		$outvar = GetGlobal('controller')->calldpc_method('crypt.encode use '.$var); 
		//$outvar = $var; //must solve external call to dpc
		//print $outvar.".";
		//print calldpc_var('crypt.cryptmethod');
	  }
      elseif (defined("CIPHERSABER_LIB")) {
        $cp = new ciphersaber;
	    $outvar = $cp->encrypt($var,'1234567890abcdefdgklm#$%^&');
	    unset($cp);	  	
	  }  	  
      else {
        $cp = new AzDGCrypt('1234567890abcdefdgklm#$%^&');
	    $outvar = $cp->crypt($var);
	    unset($cp);
	  }	
	  if ($outvar) 
	    return ($outvar);	
    }
	
	return ($var);
}

function decode($var,$default=false) {

	if ($var) {
	  
	  if ((defined("CRYPT_DPC")) && ($default==false)) {
	    //echo 'crypt_dpc';
		$outvar = GetGlobal('controller')->calldpc_method('crypt.decode use '.$var); 
		//$outvar = $var; //must solve external call to dpc		
		//print $outvar.".";
		//print calldpc_var('crypt.cryptmethod');
	  }
      elseif (defined("CIPHERSABER_LIB")) {
	    //echo 'cipher lib';
        $cp = new ciphersaber;
	    $outvar = $cp->decrypt($var,'1234567890abcdefdgklm#$%^&');
	    unset($cp);	  	
	  }  	  
	  else {
	    //echo 'az crypt';
        $cp = new AzDGCrypt('1234567890abcdefdgklm#$%^&');
	    $outvar = $cp->decrypt($var);
	    unset($cp);
	  }
	  	
	  if ($outvar) return ($outvar);
		  
	}
	
	return ($var);
} 

////////////////////////////////////////////////////////////////
//   SYSTEM FUNCTIONS
////////////////////////////////////////////////////////////////
//new line character
function __nl() {
  //global $__USERAGENT;
  $__USERAGENT = GetGlobal('__USERAGENT');
  
  switch ($__USERAGENT) {
	       case 'XML'  : break;
           case 'XUL'  :
		   case 'GTK'  : break;
		   case 'CLI'  :
		   case 'TEXT' : $out = "\n";
		                 break;		 
		   case 'HTML' :
           default     : $out = "<BR/>"; //none
  }
} 

function _without($data) {
  $out = str_replace ("_", " ", $data);  
  return ($out);
}

function _with($data) {
  $out = str_replace (" ", "_", $data);  
  return ($out);
}

function setInfo($text) {
  //global $info;
  //global $xerror;
  $xerror = GetGlobal('xerror');  
   
  $info = ": " . $text ;
  if ($xerror) $info = ": " . $xerror;
  
  SetGlobal('info',$info); 
}

function setTitle($title,$allign='center') {

  if ($title) { 
  
    $data0[] = "<B>" . $title  . "</B>";
    $attr0[] = $allign; //"center";
    $out = _PRAGMA(0,0,"center","100%",0,"group_form_headtitle",$data0,$attr0);  
  }	
  return ($out);
}

function setError($error) {

  if ($error) { 
  
    $data0[] = "<B>" . $error  . "</B>";
    $attr0[] = "center";
    $out = _PRAGMA(0,0,"center","100%",0,"group_dir_headtitle",$data0,$attr0);  
  }	
  return ($out);
}

function browse_alphabetical($command=null) {
	
	  $preparam = GetReq('alpha');
	  
	  $ret .= seturl("t=$command","Home") . "&nbsp;|";
	
	  for ($c=$preparam.'a';$c<$preparam.'z';$c++) {
	    $ret .= seturl("t=$command&alpha=$c",$c) . "&nbsp;|";
	  }
	  //the last z !!!!!
	  $ret .= seturl("t=$command&alpha=".$preparam."z",$preparam."z");
	  
      //$mywin = new window('',$ret);
      //$out = $mywin->render();	  
	  
	  return ($ret);
}

function setNavigator($navtitle,$navtitle2='') {
  $GRX = GetGlobal('GRX'); 
  $__USERAGENT = GetGlobal('__USERAGENT');   
  
  //if locale file is allready in utf-8 the the conversion is utf8 to utf8 else auto
  //$home = localize(paramload('SHELL','rootalias'),getlocal(),'UTF-8','UTF-8');
  $home = localize(paramload('SHELL','rootalias'),getlocal());
  //echo $home;
  
  if (paramload('SHELL','navigator')) {
  
    //if in cp and nitobi ON...no navigation
    if ((stristr($_SERVER['PHP_SELF'],'/cp/')) && (paramload('RCCONTROLPANEL','nav')=='disable'))
		  return; //if cp .. no navigation.. 
  
    //if defined redirection of Home and is not remote app...
    if (($myurl = paramload('SHELL','rootredir')) && (!GetSessionParam('REMOTEAPPSITE')))
	  $gotohome =  url($myurl,$home);
	else
	  $gotohome =  seturl("t=" ,$home);//default 
	
	switch ($__USERAGENT) {
	       case 'XML'  : break;
           case 'XUL'  :
		   case 'GTK'  : break;
		   case 'CLI'  :
		   case 'TEXT' : $out = $navtitle."\n"; break;		 
		   case 'HTML' :
           default     : if ($GRX) $rightarrow = "&nbsp;" . loadTheme('rarrow') . "&nbsp;";			 
                         else $rightarrow = " > "; 
		 
                         if (!$navtitle2) { 
                           $data0[] = $gotohome . $rightarrow . $navtitle; 
                           $attr0[] = "left";
                           $out = _PRAGMA(0,0,"center","100%",0,"group_dir_headtitle",$data0,$attr0); 
                         }
	                     else {
                           $data0[] = $gotohome . $rightarrow . $navtitle . $rightarrow . $navtitle2; 
                           $attr0[] = "left";	
                           $out = _PRAGMA(0,0,"center","100%",0,"group_dir_headtitle",$data0,$attr0); 	  
	                     };	
	}
  }		
  return ($out);
}
	
//create links

//generic url set...
function url($url,$title,$jscript=null) {

  $out = "<A href=\"" . $url . "\" $jscript>" . $title . "</A>";
  return ($out);
}


function seturl($query='',$title='',$ssl=0,$jscript='',$sid=1,$rewrite=null) {
  $__USERAGENT = GetGlobal('__USERAGENT');   
  
  $rewrite = $rewrite?$rewrite:paramload('SHELL','rewrite');
  $session_use_cookie = paramload('SHELL','sessionusecookie');
  if ($session_use_cookie) $sid=0;  
  
  $subpath = pathinfo($_SERVER['PHP_SELF'],PATHINFO_DIRNAME);  
  
  $query_p = explode("|",$query);//holds path and ?pama=... in the form of xz/z/|t=1
  //print_r($query_p);
  if (isset($query_p[1])) {
    $query = $query_p[1];
	$subpath = $query_p[0];
  }	
  else 
    $query = $query_p[0];
  //echo $query,">>>";	
 
  if ($subpath=="\\") $subpath = null;  
  
  $protocol = paramload('SHELL','protocol');
  $secprotocol = paramload('SHELL','secureprotocol');  
  $sslpath  = paramload('SHELL','sslpath');
  
  //look if ip is in ip pool
  //due to session of app web name must set to caller host
  if (GetSessionParam('REMOTEAPPSITE'))//remote app
    $ip = $_SERVER['HTTP_HOST'];
  else {//usual site	
    $ipool = arrayload('SHELL','ip'); //print_r($ipool);
    if (in_array($_SERVER['HTTP_HOST'],$ipool)) 
      $ip = $_SERVER['HTTP_HOST']; //remote user call
    else 
      $ip = $ipool[0]; //default
  }	  
  
  switch ($__USERAGENT) {
	       case 'XML'  : break;
           case 'XUL'  :
		   case 'GTK'  : break;
		   case 'CLI'  :
		   case 'TEXT' : if ((isset($query)) && ($query!='#')) {
		                   $p = explode("=",$query);
						   $out = $p[1];
		                 } 
		                 break;		 
		   case 'HTML' :
           default     :
                         $activeSSL = paramload('SHELL','ssl');
                         $encURLparam = paramload('SHELL','encodeurl');  //echo '>>>',$encURLparam,'<<<';

                         if (($activeSSL) && ($ssl)) 
						   $name = $secprotocol . $ip . $sslpath; 
                         else 
						   $name = $protocol . $ip; 
                         
						 //mv controller or page controller caller???
						 $xurl = "/".pathinfo($_SERVER['PHP_SELF'],PATHINFO_BASENAME);

						 //fun called by mv cntrl
						 if (paramload('SHELL','filename')==$xurl) {
						   //get page if exist..(t=page)!!!!!!!!!!!!!!!!!!!!!!!!!!
                           if ($page = getpurl($query,$title,$ssl,$jscript,$ssl)) {
						     $name .= "/" . $page;//page cntrl
						     //echo "[",$name,"]<br>";
						   }
						   else						 
						     $name .= paramload('SHELL','filename');				
						 }  		   
						 else {//fun called by page cntrl  
						   $mysubpath = $subpath<>'/' ? $subpath.'/': $subpath;
						   $name .= $mysubpath . pathinfo($_SERVER['PHP_SELF'],PATHINFO_BASENAME);  //double slash //....solved
						   //echo $mysubpath,'>',pathinfo($_SERVER['PHP_SELF'],PATHINFO_BASENAME),'>';
						 }  
						 
						 //echo $name,"<br>";
						 
                         if (isset($query)) {
                           if ($query!="#") {
						     if ($rewrite) {
							   //$url = $name . "/"; 
							   //$url.= str_replace("&","/",str_replace("=","/",$query));
							   ////////////////////////////////////////////////////////////////// make arg's value dirs...
							   $aquery = explode('&',$query);
							   foreach ($aquery as $a=>$q) {
							     $aparam = explode('=',$q);
								 //if (($aparam[0])=='t')
								   $url .=  $aparam[1] .'/';//value = dpc command = like dir
							   }
							   //print_r($aquery);
							   //echo $url,'<br>';
							 }
							 else {
	                           $url = $name . "?"; //. $query;
	                           (($encURLparam) ? $url .= encode_url($query,$encURLparam) : $url .= $query);
	                           if ($sid) $url .=  "&" . SID;
							 }
	                       }  
	                       else 
	                         $url = "#"; 
                         }				
                         else  
                           (isset($sid) ? $url = $name . '?' . SID : $url = $name); 
                         
						 //echo $url,"<br>";
                         if ($title) $out = "<A href=\"" . $url . "\" $jscript>" . $title . "</A>";
                         else $out = $url;
  }//switch
  return ($out);
}
    
	//page cntrl logic url creator
	function getpurl($query='',$title='',$ssl=0,$jscript='',$sid=1) {
	
	  parse_str($query,$parts);
	  
	  if (is_array($parts)) {
	  
	    if ($parts['t']) {//default
	      $pagename = $parts['t'];
	    }	
		else
		  return false;
	    /*else {//first param
	      $m = array_reverse($parts,true);
		  $pagename = array_pop($m);
	    }*/
		  
	    //echo $pagename,'+++++++++++'; 
		$url = paramload('SHELL','urlpath');
		if ((paramload('SHELL','ssl')) && ($ssl))
		  $url .= paramload('SHELL','sslpath');
		$url .= "/" . $pagename . ".php"; 

		if (file_exists($url))
	      return ($pagename . ".php");
	  }	
	  
      return false;
	  
      /*if (GetGlobal('controller')->get_attribute($dpc_name,$command,12))
									   $out .= "http://".
									           $_SERVER['HTTP_HOST'] . 
											   str_replace($command.".php",paramload('SHELL','filename'),$_SERVER['URL']) .
											   $lf; 
									 else
									   $out .= seturl("t=$command",$alias) . $lf;		*/
	} 

    //javascript url 
	function setjsurl($title,$jscript,$id=null,$attributes=null) {
	
	   if ($id) $out = "<A id='$id' href=\"$jscript\" $attributes>" . $title . "</A>";
	       else $out = "<A href=\"$jscript\" $attributes>" . $title . "</A>";
	   
	   return ($out);
	}
	
	//ajax url
	function setajaxurl($query='',$title='',$ssl=0,$jscript='',$sid=1) {
	
	  if ((defined('AJAX_DPC')) && (defined('JAVASCRIPT_DPC'))) {
	     
		 $url = seturl($query,null,$ssl,null,null);
		 $ret = "<a href=\"javascript:sndReqArg('$url')\">$title</a>";
		 
		 return ($ret);
	  }
	  else
	    return 'Ajax! What is this!';
    }
//ENCODED URL
	function encode_url($url,$param='prm'){ 
	    
		if ($url) {
		  $ret = $param . "=" . base64_encode($url);
		}  
	    return ($ret);
	} // methode return ?aku=dfgdfgdgdfgdgdfhgjdfhjghj all parameter are hide in one
	
	function decode_url ($param='prm'){ // methode to unhide
		
		if($_REQUEST[$param]){ 
			$decode_url=base64_decode($_REQUEST[$param]); 
			parse_str($decode_url, $tbl); 
			
			foreach($tbl as $k=>$v){
				$_REQUEST[$k]=$v;
				//global $$k; //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
				SetGlobal($k,$v);
				$$k=$v;
			}
		} 
	}
	
// error handler function
function XErrorHandler ($errno, $errstr, $errfile, $errline) {
  //global $xerror; 
  
  switch ($errno) {
  case FATAL:
    $out = "<b>FATAL</b> [$errno] $errstr<br>\n";
    $out .= "  Fatal error in line ".$errline." of file ".$errfile;
    $out .= ", PHP ".PHP_VERSION." (".PHP_OS.")<br>\n";
    $out .= "Aborting...<br>\n";
    exit();
    break;
  case ERROR:
  case XERROR:  
    $out = "<b>ERROR</b> [$errno] $errstr\n";
    break;
  case WARNING:
  case XWARNING:  
    $out = "<b>WARNING</b> [$errno] $errstr\n";
    break;
  }
  $xerror = $out;
  SetGlobal('xerror',$xerror);
  
  echo $out;
}



//MAIL RELATED...

function checkmail($data) {

  if( !eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*" . "@([a-z0-9]+([\.-][a-z0-9]{1,})+)*$",
   $data, $regs) )  {

   SetInfo("Error: '$mail' isn't a valid mail address!");
   return false;
  }
  /*elseif( gethostbyname($regs[2]) == $regs[2] )  {

   SetInfo("Error: Can't find the host '$regs[2]'!");
   return false;
  }*/

  return true;
  
/*  if (ereg("^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$", $data,$regs)) {
     return (TRUE);
  }
  else {
     return (FALSE);
  }*/
  
/*  if( ereg( ".*<(.+)>", $data, $regs ) ) {
      $data = $regs[1];
  }
  if(ereg( "^[^@  ]+@([a-zA-Z0-9\-]+\.)+([a-zA-Z0-9\-]{2}|net|com|gov|mil|org|edu|int)\$",$data) )
    return true;
  else
    return false;*/
  
}

function checkmail_mx($email) {

   $exp = "^[a-z\'0-9]+([._-][a-z\'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$";

   if(eregi($exp,$email)){

     if (strstr(PHP_OS, 'WIN')) {//win..  
   
       if (checkdnsrr_winNT(array_pop(explode("@",$email)),"MX")) {
         return true;
       }
	   else{
         return false;
       }
     }
	 else {//unix*...
       if (checkdnsrr(array_pop(explode("@",$email)),"MX")) {
         return true;
       }
	   else{
         return false;
       }	 
	 
	 }
   }else{

     return false;

   }   
}

/******************************************************

These functions can be used on WindowsNT to replace
their built-in counterparts that do not work as
expected.

checkdnsrr_winNT() works just the same, returning true
or false

getmxrr_winNT() returns true or false and provides a
list of MX hosts in order of preference.

*******************************************************/

function checkdnsrr_winNT( $host, $type = '' )
{

   if( !empty( $host ) )
   {

       # Set Default Type:
       if( $type == '' ) $type = "MX";

       @exec( "nslookup -type=$type $host", $output );

       while( list( $k, $line ) = each( $output ) )
       {
           //echo $line,'<br>';
           # Valid records begin with host name:
           if( eregi( "^$host", $line ) )
           {
               # record found:
               return true;
           }

       }

       return false;

   }

}

function getmxrr_winNT( $hostname, &$mxhosts )
{

   if( !is_array( $mxhosts ) ) $mxhosts = array();

   if( !empty( $hostname ) )
   {

       @exec( "nslookup -type=MX $hostname", $output, $ret );

       while( list( $k, $line ) = each( $output ) )
       {

           # Valid records begin with hostname:
           if( ereg( "^$hostname\tMX preference = ([0-9]+), mail exchanger = (.*)$", $line, $parts ) )
           {

               $mxhosts[ $parts[1] ] = $parts[2];

           }

       }

       if( count( $mxhosts ) )
       {

           reset( $mxhosts );

           ksort( $mxhosts );

           $i = 0;

           while( list( $pref, $host ) = each( $mxhosts ) )
           {
               $mxhosts2[$i] = $host;
               $i++;
           }

           $mxhosts = $mxhosts2;

           return true;

       }
       else
       {

           return false;

       }

   }

}
//test.......
/*if ( getmxrr_winNT( "microsoft.com", $hosts ) )
{
echo count($hosts)."<br>";
for ($i=0; $i<=count($hosts); $i++){
echo $hosts[$i];}
}*/


//reverse datetime strings
//ex. 2003-01-10 02:20:31 - 10-01-2003 02:20:31 or the opposite
function reverse_datetime($dtime,$ds='-',$ts=':') {
	
	   $parts = explode(" ",$dtime);
	   
	   $date = $parts[0];
	   
	   $dparts = explode("-",$date);
	   
	   return ($dparts[2].$ds.$dparts[1].$ds.$dparts[0]." ".$parts[1]);
}

// get date    
function get_date ($format) {

   $today = getdate(); 
   $M = $today[month]; 
   $m = $today[mon];  
   $D  = $today[weekday]; 
   $d  = $today[mday];  
   $Y  = $today[year]; 
   $y  = $today[year];
   $hr = $today[hours];
   $mn = $today[minutes];
   $sc = $today[seconds];    
   
   switch ($format) {
     case "h:n" : $my_date = "$hr:$mn"; break;
     case "h:n:c" : $my_date = "$hr:$mn:$sc"; break;
     case "d/m/y,h:n" : $my_date = "$d/$m/$y,$hr:$mn"; break;
     case "MD,Y"  : $my_date = "$M $D, $Y"; break;
     case "DM,Y"  : $my_date = "$D $M, $Y"; break;	 
     case "m/d/y" : $my_date = "$m/$d/$y"; break;
     case "m-d-y" : $my_date = "$m-$d-$y"; break;
     case "d/m/y" : $my_date = "$d/$m/$y"; break;	 	 	 
     case "d-m-y" : $my_date = "$d-$m-$y"; break;
     case "DdMY"  : $my_date = localize($D,getlocal()) . " $d ". localize($M,getlocal()) ." $Y"; break;		 
     default      : $my_date = localize($D,getlocal()) . " $d ". localize($M,getlocal()) ." $Y";
   }
   
   return ($my_date);
}


// get date time     
function get_datetime () {

   $today = getdate(); 
   $month = $today[mon]; 
   $mday  = $today[mday]; 
   $year  = $today[year]; 
   $hour  = $today[hours];
   $min   = $today[minutes];
   $sec   = $today[seconds];   
   
   $my_date = "$mday-$month-$year:$hour:$min:$sec";
   
   return ($my_date);
}

//return array of text named days
function getdaysarray($startday=0,$blank=0,$blankalias="-----") {
  
  //if ($blank) $days[] = $blankalias;  
  
  $days[] = localize('Sunday',getlocal());  
  $days[] = localize('Monday',getlocal());
  $days[] = localize('Tuesday',getlocal());
  $days[] = localize('Wednesday',getlocal());
  $days[] = localize('Thursday',getlocal());
  $days[] = localize('Friday',getlocal());
  $days[] = localize('Saturday',getlocal());	
  
  if (($startday) && ($startday<7)) {
    for ($i=1;$i<=$startday;$i++) {
	  $sh = array_shift($days);
	  $days[] = $sh;
	}  
  }	
  
  if ($blank) array_unshift($days,$blankalias);
				 
  return ($days);				 
}

// Get date difference between two given dates
// $returntype: s = seconds, m = minutes, h = hours, d = days
// int date_diff(int start_date, int end_date[, string return_type])
function date_diff_2($start_date, $end_date, $returntype="d") { //<<<<<<<<<<<<<<<<<<<<<<<<<<<
   if ($returntype == "s")
       $calc = 1;
   if ($returntype == "m")
       $calc = 60;
   if ($returntype == "h")
       $calc = (60*60);
   if ($returntype == "d")
       $calc = (60*60*24);   
       
   $_d1 = explode(" ", $start_date);
   $_d11 = explode('-',$_d1[0]); //print_r($_d11);
   $_d12 = explode(':',$_d1[1]); //print_r($_d12);  
   $d1 = $_d11[0]; //echo '<br>',$d1;
   $m1 = $_d11[1];//echo '<br>',$m1;
   $y1 = $_d11[2];//echo '<br>',$y1;
   $h1 = $_d12[0]?$_d12[0]:0;
   $n1 = $_d12[1]?$_d12[1]:0;
   $s1 = $_d12[2]?$_d12[2]:0; 
   //echo $h1,':',$n1,':',$s1,'<br>'; 
   
   $_d2 = explode(" ", $end_date);
   $_d21 = explode('-',$_d2[0]);
   $_d22 = explode(':',$_d2[1]);   
   $d2 = $_d21[0];//echo '<br>',$d2;
   $m2 = $_d21[1];//echo '<br>',$m2;
   $y2 = $_d21[2];//echo '<br>',$y2;
   $h2 = $_d22[0]?$_d22[0]:0;
   $n2 = $_d22[1]?$_d22[1]:0;
   $s2 = $_d22[2]?$_d22[2]:0;
   //echo $h2,':',$n2,':',$s2,'<br>'; 
   
  
   if (($y1 < 1970 || $y1 > 2037) || ($y2 < 1970 || $y2 > 2037)) {
       return 0;
   } 
   else {
       $start_date_stamp    = mktime($h1,$n1,$s1,$m1,$d1,$y1); 
//echo $start_date_stamp,'<br>';
       $end_date_stamp    = mktime($h2,$n2,$s2,$m2,$d2,$y2);
//echo $end_date_stamp,'<br>';
	   
	   
       $difference = round(($end_date_stamp-$start_date_stamp)/$calc);
	   //echo $difference,"LLLLLLLL<br>";
		  
	   return $difference;  
   }
}

	function convert_date($date,$format,$zero=null) {
		  
	        $parts = explode("-",$date);
		    $day = ($zero ? sprintf("%02d",$parts[0]):$parts[0]);
		    $month = ($zero ? sprintf("%02d",$parts[1]):$parts[1]);
		    $parts2 = explode(" ",$parts[2]);
		    $year = $parts2[0];
		    $time = $parts2[1];
			
			$s = substr($format,0,1);
			$f = substr($format,1);
			switch ($f) {
			  case 'DMYT' : $ret = $day . $s . $month . $s . $year . " " . $time; break;
			  case 'DMY'  : $ret = $day . $s . $month . $s . $year ; break; 
			  case 'MDYT' : $ret = $month . $s . $day . $s . $year . " " . $time; break; 
			  case 'MDYT' : $ret = $month . $s . $day . $s . $year; break; 
			  case 'YMDT' : $ret = $year . $s . $month . $s . $day . " " . $time; break;  
			  case 'YMD'  : $ret = $year . $s . $month . $s . $day; break; 
			  default     : $ret = $day . $s . $month . $s . $year . " " . $time;
			}
			
		  //echo $ret;	
		  return ($ret); 	
	}	

function get_options_file($lookupfile,$is_search,$is_required,$selected_value) {
  
  $options_str="";
  if ($is_search)
    $options_str.="<option value=\"\">All</option>";
  else
  {
    if (!$is_required)
    {
      $options_str.="<option value=\"\"></option>";
    }
  }
  
  $lfile = paramload('SHELL','prpath') . $lookupfile . '.opt';
  //echo $lfile;
  if (is_file($lfile)) {
  
    $fd = fopen($lfile, 'r');
    $ret = fread($fd, filesize($lfile));
    fclose($fd); 	   
	
    $result = explode(',',$ret);  //print_r($result);
    $lan = getlocal();
  
    if ($result) {
	
      foreach ($result as $id=>$value)  {
	  
	    //language selection
	    $lan_value = explode(";",$value);
		$val = $lan_value[$lan];
		if ($val) $option = $val;
		     else $option = $lan_value[0];
	  
        $selected="";
        if ($id == $selected_value)  {
          $selected = "SELECTED";
        }
        $options_str.= "<option value='".$id."' ".$selected.">".$option."</option>";
	
      }
    }

  }  
  return $options_str;
}


function get_selected_option_fromfile($option,$lookupfile,$languange=null,$filetype=null) {

  $ftype = $filetype?'.'.$filetype:'.opt';

  $lfile = paramload('SHELL','prpath') . $lookupfile . $ftype;
  
  if (is_file($lfile)) {
  
    $fd = fopen($lfile, 'r');
    $ret = fread($fd, filesize($lfile));
    fclose($fd); 	   
	
    $result = explode(',',$ret);  //print_r($result);
	
	if (isset($languange))
	  $lan = $languange;
	else  
      $lan = getlocal();
  
    if ($result) {
	
	  $d = explode(";",$result[$option]);  
	  $ret = trim($d[$lan]);
	  
	  return ($ret);
	}  
  }	
  
  return null;
}

function get_selected_id_fromfile($option,$lookupfile,$filetype=null) {

  $ftype = $filetype?'.'.$filetype:'.opt';

  $lfile = paramload('SHELL','prpath') . $lookupfile . $ftype;
  if (is_file($lfile)) {
  
    $fd = fopen($lfile, 'r');
    $ret = fread($fd, filesize($lfile));
    fclose($fd); 	   
	
    $result = explode(',',$ret);  //print_r($result);
	
	if (count($result)>0) {
	 
	  foreach  ($result as $id=>$title) {
	    
		if (@stristr($title,$option))
		  return ($id);
	  }
	}
  }	
  
  return -1;//nothing find  
}

///////////////////////////////////////////////////
// transformers
//////////////////////////////////////////////////
function ext2ascii($str,$offset=128) {
    $out = '';

	for ($i=0;$i<strlen($str);$i++) {
		//print ord($str[$i]);
		$ch = chr(ord($str[$i])-$offset);
		$out .= $ch;
		//print $ch;
	}
	
	return ($out);
}

function ascii2ext($str,$offset=128) {
    $out = '';

	for ($i=0;$i<strlen($str);$i++) {
		//print ord($str[$i]);
		$ch = chr(ord($str[$i])+$offset);
		$out .= $ch;
		//print $ch;
	}
	
	return ($out);
}

function summarize($maxchar,$text) {

    if (strlen($text)>$maxchar) $res = substr($text,0,$maxchar) . "...";
	                       else $res = $text;
	return ($res);
}

//replace html session param with $param
//mode 1=replace all SESSID=xdsjfslkf35345jsl345kjflks with $param
//mode 2=replace sjkah234hlk3qwih234jhr string with $param
function replace_htmlsestext($param,$htmltext,$mode=1) { //<<<<<<<<<<<<<<<<<< to delete after cache class
    
	switch ($mode) {
       case 1 :	//replace all sesid string
	            $out = str_replace("&SESSID=" . session_id(),$param,$htmltext);	
	            break;
	   case 2 : //replace current sessid
	            $out = str_replace(session_id(),$param,$htmltext);	
	            break;
	}			
	return ($out);
}	

//refill htmlsestext with current session id when htmltext has saved with
//replace_htmlsestext mode=same as this mode & param = same as this param
function refill_htmlsestext($param,$htmltext,$mode=1) {  //<<<<<<<<<<<<<<<<<< to delete after cache class
    
	switch ($mode) {
       case 1 :	//replace all sesid string
	            $out = str_replace($param,"&SESSID=" . session_id(),$htmltext);	
	            break;
	   case 2 : //replace current sessid
	            $out = str_replace($param,session_id(),$htmltext);	
	            break;
	}			
	return ($out);
}	

/////////////////////////////////////////////////////
// convert txt files to html format stream
// ( actually replace the empty lines with <br> tag )
/////////////////////////////////////////////////////
function txt2html($txtfile) {

  $mytxtfile = file (trim("$txtfile"));

  //parse file
  //while (list ($line_num, $line) = each ($mytxtfile)) {
  foreach ($mytxtfile as $line_num => $line) {    
    if ($line)
       $to_print .= $line;    //read line
    else $to_print .= "<br>"; //empty line = cr
    
    $to_print .= "<br>";      //cr
  }

  return $to_print; 
}


/////////////////////////////////////////////////////
// convert txt files to summary format txt 
// (words length)
/////////////////////////////////////////////////////
function txt2sumtxt($txtfile,$words) {

  $mytxtfile = file ("$txtfile");

  //parse file
  //while (list ($line_num, $line) = each ($mytxtfile)) {
  foreach ($mytxtfile as $line_num => $line) {  
    if ($line) {
      $split = explode (" ", $line);
      $i=0;
      while ($split[$i]) {
        if ($i < $words)
        {
          $to_print .= $split[$i]." ";
          $i+=1;
        }
        else
          break;
      }
    }
    else
       break;
  }
  $to_print .= "...";       

  return $to_print; 
}


/////////////////////////////////////////////////////
// simple conversion of html files to text format 
// ( wihout any html tag removal )
/////////////////////////////////////////////////////
function html2txt($htmlfile) {

  $myhtmlfile = file ("$htmlfile");

  //parse file
  //while (list ($line_num, $line) = each ($myhtmlfile)) {
  foreach ($myhtmlfile as $line_num => $line) {  
    
    if ($line) {
       $html_print .= $line;    //read line
    }
 
  }

  return $html_print; 
}

function html2cleartext($htmlfile) {

  $myhtmlfile = file ("$htmlfile");

  //parse file
  //while (list ($line_num, $line) = each ($myhtmlfile)) {
  foreach ($myhtmlfile as $line_num => $line) {    
    if ($line) {
       $html_print .= strip_tags($line);    //read line without tags
    }
 
  }

  return $html_print; 
}

function html_select_control($id,$num,$zero=0,$val=0) {
   
    if ($zero) $st = 0; else $st = 1;
	 
	$out = "<SELECT name=\"$id\">";
	for ($j=$st;$j<=$num;$j++) { 
		 if (($val) && ($j==$val)) $out .= "<OPTION selected>$j";
		                      else $out .= "<OPTION>$j";
	}  
	$out .= "</OPTION></SELECT>";
	
	return ($out);
}


// check for file  
function myfile_exists ($extn, $dire, $myfile) {

  $d = dir($dire); //get directory path 

  if ($d) {
    $mmeter=0;
    while ($fn = $d->read ()) {
    
      //read files
      if (stristr ($fn,$extn)) { 
        $mmeter+=1; 
        if ($fn==$myfile) {
          $d->close ();
          return 1;
        }   
      }  
    }
    $d->close ();
    unset ($mmeter);
    unset ($d);
    unset ($fn);
  }

  return 0;
}

function readmyfile($file) {

  $fp = fopen ($file,"r");
  
  $ret = fread($fp,filesize($file));
  
  fclose($fp);
  
  return ($ret);
}

//echoing to specifiend client only
function _echo($client='HTML',$text=null) {

  $ccl = GetGlobal('__USERAGENT');
  if ($ccl!=$client) return 0;
  
  switch ($client) {
  
	         case 'HTML' : echo str_replace("\n","</br>",$text);
	         case 'HDML' : break;				 
	         case 'GTK'  : 			 
	         case 'GTKXUL': break;
			 case 'SH'   :
			 case 'SHGTK': 
	         case 'TEXT' : 
	         case 'CLI'  : 		 
	         case 'WAP'  : 			 
	         case 'XML'  : echo $text;
						   break;	
		     case 'SOAP' :
			               break;	    
  }
  
}


////////////////////////////////////////////////////////////////
//   HTML FUNCTIONS
////////////////////////////////////////////////////////////////


// get meta description from html file
function getMetaDesc($htmlfile) {
	
	$lowBuffer = html2txt($htmlfile);
      echo $lowbuffer;

	/* Locate where <META is located in html file. */
	$lBound = strpos($lowBuffer, '<meta');

	if ($lBound < 1)
		return false;

	/* Locate where </HEAD is located in html file. */
	$uBound = strpos($lowBuffer, '</head', $lBound);

	if ($uBound < $lBound)
		return false;

	/* Clean HTML and PHP tags out of $desc with the madness below. */
	$desc = preg_replace("[\t\r\n]", '', substr($content, $lBound, $uBound - $lBound));
	$desc = eregi_replace('^.*<META[[:space:]]+NAME[[:space:]]*=[[:space:]]*\"?description\"?[[:space:]]+CONTENT[[:space:]]*=[[:space:]]*\"?([^\">]*).*$', '\\1', $desc);
	$desc = trim(strip_tags($desc));

	if (strlen($desc) < 1) //A blank desc is worthless.
		return false;

	return $desc;
}


////////////////////////////////////////////////////////////////
//   HTTP FUNCTIONS
////////////////////////////////////////////////////////////////

// This function returns the any of the parameter from Request object.
// Function will return Empty if parameter = "".
// It's highly recommended to use Request.QueryString or Request.Form or 
// Request.ServerVariables for improve the security and speed of your site!

function GetParam($ParamName) {

  $ParamValue = "";
  if(isset($_POST[$ParamName]))
    $ParamValue = $_POST[$ParamName];
  else if(isset($_GET[$ParamName]))
    $ParamValue = $_GET[$ParamName];

  return (stripslashes($ParamValue));
}

function SetParam($ParamName,$ParamValue=null) {

  $_POST[$ParamName] = $ParamValue;
}

function GetReq($ParamName) {
  //echo $ParamName,'+';
  
  $ParamValue = "";
  if(isset($_REQUEST[$ParamName]))
    $ParamValue = $_REQUEST[$ParamName];
  else 
    $ParamValue = null;

  //echo '=',$ParamValue;	 
  return (stripslashes($ParamValue));
}

function SetReq($ParamName,$ParamValue) {
 
  $_REQUEST[$ParamName] = $ParamValue;
  //print_r($_REQUEST);
}

function DelReq($ParamName) {

  if (isset($_REQUEST[$ParamName])) 
    $_REQUEST[$ParamName] = null;
}

function is_number($string_value) {

  if(is_numeric($string_value) || !strlen($string_value))
    return true;
  else 
    return false;
}


function IsParam($ParamValue) {

  if($ParamValue)
    return 1;
  else
    return 0;
}



function ToHTML($strValue) {
  return htmlspecialchars($strValue);
}

function ToURL($strValue) {
  return urlencode($strValue);
}

function writecl($text,$forcolor='#000000',$backcolor='#FFFFFF',$border=null)  {

      $pragma = "\n"; //<!-- start pragma -->\n";
      $pragma .= "<TABLE>";		  
	  $pragma .= "<TR>";	  
      $pragma .= "<TD style='";	  
	  $pragma .= " color:$forcolor;";				   
	  $pragma .= " background-color:$backcolor;";
	  if ($border) $pragma .= " border=" . $border;
	  $pragma .= "'>";	
	  $pragma .= $text;
	  $pragma .= "</TD>";	  	  
      $pragma .= "\n</TR></TABLE>\n";	
	  
	  return ($pragma);  
}

//////////////////////////////////////////////////////////////
//  PRAGMA reads data array and put them in a table 
//////////////////////////////////////////////////////////////
function _PRAGMA($cp,$cs,$al,$wd,$bd,$cl,$content,$attributes,$style=null) {

      $pragma .= "\n"; //<!-- start pragma -->\n";
      $pragma .= "<TABLE";
	  $pragma .= " cellpadding=" . $cp;
	  $pragma .= " cellspacing=" . $cs;
	  $pragma .= " align=\"" . $al . "\"";
	  $pragma .= " width=\"" . $wd . "\"";  
	  $pragma .= " border=" . $bd;
	  $pragma .= " class=\"" . $cl . "\"";
      $pragma .= ">";		  
	  $pragma .= "<TR $style>";
	  $pragma .= "\n";	  
	  
      //read data array 
	  reset ($content); 
      //while (list ($data_num, $data) = each ($content)) {
	  foreach ($content as $data_num => $data) {
	    if ($data) {
		  $pragma .= "<TD";
		  //read attributes
		  $atr = $attributes[$data_num];
		  if ($atr!="hidden") {
		    $spattr = explode (";", $atr);
			
			if ($spattr[0]) $pragma .= " align=\"$spattr[0]\"";
				       else $pragma .= " align=\"left\"";         
			
		    if ($spattr[1]) $pragma .= " width=\"$spattr[1]\"";
		               else $pragma .= " width=\"99%\"";

		    if ($spattr[2]) $pragma .= " valign=\"$spattr[2]\"";
		               else $pragma .= " valign=\"top\"";
					    
		    if ($spattr[3]) $pragma .= " bgcolor=\"$spattr[3]\"";
						   
		    if ($spattr[4]) $pragma .= " background=\"$spattr[4]\"";					   
			
		    if ($spattr[5]) $pragma .= " class=\"$spattr[5]\""; 
		   
            $pragma .= ">";	
		    $pragma .= $data;
		    $pragma .= "</TD>";
		  }
		}  
	  }
	  
      $pragma .= "\n</TR></TABLE>\n";
	  //$pragma .= "\n<!-- end  pragma -->\n";
	  
	  return ($pragma);
}

//draged window
function _D_PRAGMA($cp,$cs,$al,$wd,$bd,$cl,$content,$attributes) {

      $pragma .= "\n";//<!-- start pragma -->\n";
      $pragma .= "<div class=\"drag\">";
      $pragma .= "<TABLE";	  
	  $pragma .= " cellpadding=" . $cp;
	  $pragma .= " cellspacing=" . $cs;
	  $pragma .= " align=\"" . $al . "\"";
	  $pragma .= " width=\"" . $wd . "\"";
	  $pragma .= " border=" . $bd;
	  $pragma .= " class=\"" . $cl . "\"";
      $pragma .= ">";	  
	  $pragma .= "<TR>";
      $pragma .= "</div>";	  
	  $pragma .= "\n";	  
	  
      //read data array 
	  reset ($content); 
      //while (list ($data_num, $data) = each ($content)) {
	  foreach ($content as $data_num => $data) {
	    if ($data) {
		  $pragma .= "<TD";
		  //read attributes
		  $atr = $attributes[$data_num];
		  if ($atr!="hidden") {
		    $spattr = explode (";", $atr);
			
			if ($spattr[0]) $pragma .= " align=\"$spattr[0]\"";
				       else $pragma .= " align=\"left\"";         
			
		    if ($spattr[1]) $pragma .= " width=\"$spattr[1]\"";
		               else $pragma .= " width=\"99%\"";

		    if ($spattr[2]) $pragma .= " valign=\"$spattr[2]\"";
		               else $pragma .= " valign=\"top\"";
					    
		    if ($spattr[3]) $pragma .= " bgcolor=\"$spattr[3]\"";
						   
		    if ($spattr[4]) $pragma .= " background=\"$spattr[4]\"";					   
		   
            $pragma .= ">";	
		    $pragma .= $data;
		    $pragma .= "</TD>";
		  }
		}  
	  }
	  
      $pragma .= "\n</TR></TABLE>\n";
	  //$pragma .= "\n<!-- end  pragma -->\n";
	  
	  return ($pragma);
}

//////////////////////////////////////////////////////////////
//  WPRAGMA reads data array and put them in cards system (wap) 
//////////////////////////////////////////////////////////////
function _WPRAGMA($id,$title,$cl,$content,$attributes) {

      $pragma = "\n<card id=\"$id\" title=\"$title\">"; 
	  
      //read data array 
	  reset ($content); 
      //while (list ($data_num, $data) = each ($content)) {
	  foreach ($content as $data_num => $data) {	  
	    if ($data) {
		  $atr = $attributes[$data_num];
	      $pragma .= "<p";
          //if ($atr) $pragma .= " align=\"$atr\"";
          //if ($cl)  $pragma .= " class=\"$cl\"";
	      $pragma .= ">"; 
		  $pragma .= $data;
          $pragma .= "</p>";
		}  
	  }
	  
      $pragma .= "</card>\n";
	  
	  return ($pragma);
}


//////////////////////////////////////////////////////////////
//  LAYER represents a set of data in a defined layer 
//////////////////////////////////////////////////////////////
function _LAYER($id,$pos,$vis,$state,$overf,$left,$top,$width,$height,$data,$attr='',$level=0) {
    static $zlevel;
	
	$zindex = (++$zlevel)+$level; //calc z index pre group based on $level start
	//echo ">>>>",$zindex,"---";
	
    $ldata = "<DIV id=\"$id\"" . 
             " style=\"". 
		     " BACKGROUND-COLOR: #" . paramload('HTML','f2_bcol') . "; " .
			 " BORDER-BOTTOM: #000000 1px; " .
			 " BORDER-LEFT: #000000 1px; " .
			 " BORDER-RIGHT: #000000 1px; " .
			 " BORDER-TOP: #000000 1px; " .
			 " HEIGHT: $height; " .
			 " LEFT: $left; " .
			 " POSITION: $pos; " .
			 " TOP: $top; " .
			 " VISIBILITY: $vis ; " .
			 " overflow: $overf; " .
			 " WIDTH: $width; " .
			 " Z-INDEX: " . $zindex. "; " .
			 " layer-background-color: #000066 \"";
			 
	if ($state) {
	   switch ($state) {
	     case 1 : $ldata .= " onClick=\"MM_showHideLayers('$id','','hide')\" "; break;
	     case 2 : $ldata .= " onMouseOver=\"MM_showHideLayers('$id','','hide')\" "; break;		 
	   }
	}   	 
			 
	$ldata .= ">";   
			  
    if ($attr) {
      $param = explode ("::", $attr);	
	  
	  $dout[] = $data;
	  $aout[] = "left";
	  $ldata .= _PRAGMA($param[5],$param[6],$param[0],$$param[1],$param[2],$param[3],$dout,$aout);
	}
	else 
	  $ldata .= $data;	  
	  
    $ldata .= "</DIV>\n";
	
	return ($ldata);
}

//////////////////////////////////////////////////////////////
//  PRAGMA reads data array and create xml compatible xhtml 
//////////////////////////////////////////////////////////////
function _XPRAGMA($cp,$cs,$al,$wd,$bd,$cl,$content,$attributes) {

      $pragma .= "\n"; //<!-- start pragma -->\n";
      $pragma .= "<TABLE";
	  $pragma .= " cellpadding=\"" . $cp . "\"";
	  $pragma .= " cellspacing=\"" . $cs . "\"";
	  $pragma .= " align=\"" . $al . "\"";
	  $pragma .= " width=\"" . $wd . "\"";  
	  $pragma .= " border=\"" . $bd . "\"";
	  $pragma .= " class=\"" . $cl . "\"";
	  $pargma .= ">";
	  $pragma .= "\n<TR>";
	  $pragma .= "\n";	  
	  
      //read data array 
	  reset ($content); 
      //while (list ($data_num, $data) = each ($content)) {
	  foreach ($content as $data_num => $data) {
	    if ($data) {
		  $pragma .= "\n<TD";
		  //read attributes
		  $atr = $attributes[$data_num];
		  if ($atr!="hidden") {
		    $spattr = explode (";", $atr);
			
			if ($spattr[0]) $pragma .= " align=\"$spattr[0]\"";
				       else $pragma .= " align=\"left\"";         
			
		    if ($spattr[1]) $pragma .= " width=\"$spattr[1]\"";
		               else $pragma .= " width=\"99%\"";

		    if ($spattr[2]) $pragma .= " valign=\"$spattr[2]\"";
		               else $pragma .= " valign=\"top\"";
					    
		    if ($spattr[3]) $pragma .= " bgcolor=\"$spattr[3]\"";
						   
		    if ($spattr[4]) $pragma .= " background=\"$spattr[4]\"";					   
			
		    if ($spattr[5]) $pragma .= " class=\"$spattr[5]\""; 
		   
            $pragma .= ">";	
		    $pragma .= $data;
		    $pragma .= "\n</TD>";
		  }
		}  
	  }
	  
      $pragma .= "\n</TR>\n</TABLE>";
	  //$pragma .= "\n<!-- end  pragma -->\n";
	  
	  return ($pragma);
}

//////////////////////////////////////////////////////////////
//  LAYER represents a set of data in xml mode 
//////////////////////////////////////////////////////////////
function _XLAYER($id,$pos,$vis,$state,$overf,$left,$top,$width,$height,$data,$attr='',$level=0) {
    static $zlevel;
	
	$zindex = (++$zlevel)+$level; //calc z index pre group based on $level start
	//echo ">>>>",$zindex,"---";
	
    $ldata = "<DIV id=\"$id\"" . 
             " style=\"". 
		     " BACKGROUND-COLOR: #" . paramload('HTML','f2_bcol') . "; " .
			 " BORDER-BOTTOM: #000000 1px; " .
			 " BORDER-LEFT: #000000 1px; " .
			 " BORDER-RIGHT: #000000 1px; " .
			 " BORDER-TOP: #000000 1px; " .
			 " HEIGHT: $height; " .
			 " LEFT: $left; " .
			 " POSITION: $pos; " .
			 " TOP: $top; " .
			 " VISIBILITY: $vis ; " .
			 " overflow: $overf; " .
			 " WIDTH: $width; " .
			 " Z-INDEX: " . $zindex. "; " .
			 " layer-background-color: #000066 \"";
			 
	if ($state) {
	   switch ($state) {
	     case 1 : $ldata .= " onClick=\"MM_showHideLayers('$id','','hide')\" "; break;
	     case 2 : $ldata .= " onMouseOver=\"MM_showHideLayers('$id','','hide')\" "; break;		 
	   }
	}   	 
			 
	$ldata .= ">";   
			  
    if ($attr) {
      $param = explode ("::", $attr);	
	  
	  $dout[] = $data;
	  $aout[] = "left";
	  $ldata .= _XPRAGMA($param[5],$param[6],$param[0],$$param[1],$param[2],$param[3],$dout,$aout);
	}
	else 
	  $ldata .= $data;	  
	  
    $ldata .= "</DIV>\n";
	
	return ($ldata);
}





class sysgui {

  
}

?>