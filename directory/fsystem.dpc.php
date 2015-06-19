<?php
$__DPCSEC['FSYSTEM_DPC']='2;1;1;1;1;1;2;2;9';

if ((!defined("FSYSTEM_DPC")) && (seclevel('FSYSTEM_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("FSYSTEM_DPC",true);

$__DPC['FSYSTEM_DPC'] = 'fsystem';

$__EVENTS['FSYSTEM_DPC'][0]='fs';

$__ACTIONS['FSYSTEM_DPC'][0]='fs';

$__DPCATTR['FSYSTEM_DPC']['fs'] = 'fs,0,0,0,0,0,0,1,0';

$__LOCALE['FSYSTEM_DPC'][0] = 'FSYSTEM_DPC;FSYSTEM;FSYSTEM';


class fsystem {
	
    var $userLevelID;

    var $outpoint;
	var $bullet;
	var $rightarrow;
	var $fspath;
	var $fs;
	var $agent;
    var $url;
	
	function fsystem() { 
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
	 
         case "fs" : break;			 		 
       }
	}

    function action($action) {	
       $fs = GetReq('fs');	

	   switch ($action) {
		  case "fs" :$out = setNavigator(localize('FSYSTEM_DPC',getlocal()));
		  
                     $data0 = $this->show_fs_path($fs);
	                 $swin = new window('',$data0);
	                 $out .= $swin->render("center::99%::0::group_win_body::left::0::0::");	
	                 unset ($swin);	
					 					 
                     $data1 = $this->show_fs(null,1,$fs);
                     $swin = new window('',$data1);
	                 $out .= $swin->render("center::99%::0::group_win_body::left::0::0::");	
	                 unset ($swin);					 
	  
                     $data2 = $this->show_fs_contents();	  
	                 $swin = new window('',$data2);
	                 $out .= $swin->render("center::99%::0::group_win_body::left::0::0::");	
	                 unset ($swin);					 
				     break;							
	   }

		return ($out);
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

function show_home($name) {
  $lan = GetReq('lan');
  $fs = GetReq('fs');

  $alias = getfs_alias();
   
  $ret = seturl("t=fs&lan=".$lan,$alias['home']) .
         //"<a href=\"home.phtml?lan=" . $lan . "\">" . $alias['home'] . "</a>" . 
         "&nbsp;$this->rightarrow&nbsp;" . $name;

  return ($ret);
}



function getfs($fs=null) {
  $lan = GetReq('lan');
  $fs = GetReq('fs');

  if ($lan) $path = $this->fspath . $lan . "/" . $this->fsalias . "/";
       else $path = $this->fspath . "/" . $this->fsalias . "/";
  if ($fs) $cpath = $path . $fs . "/";  
      else $cpath = $path;

  $alias = readini($path."fs.ini"); //print_r($alias);

  //print_r($alias);
  $fpath = $cpath;

  if (is_dir($fpath)) {

    $mydir = dir($fpath); //echo $fpath;

    while ($fileread = $mydir->read()) {//echo $fileread,"<br>";
      if ((is_dir($fpath.$fileread)) && ($fileread!='.') && ($fileread!='..'))

        if ($fs) $ret[$fileread]=$alias[$fs."/".$fileread];
            else $ret[$fileread]=$alias[$fileread];

    }

    $mydir->close();
  }
  //print_r($ret);
  return ($ret);
}

//viewtype = in fp or stand alone
//type = vertical - horizontal
//fs = specified fs
function show_fs($viewtype=null,$type=null,$fs=null) {
  $lan = GetReq('lan');

  $data = $this->getfs($fs);  //print_r($data);

  if (is_array($data)) {
    $maxc = count($data)-1; 
    $i=0;
    foreach ($data as $id=>$dl) {

     if (trim($dl)) { 
      if ($fs) $fs_link = $fs."/".$id;
          else $fs_link = $id;

      switch ($viewtype) {
        //case '1': $out .= "<a href=\"home.phtml?fs=" .$fs_link . "&lan=" . $lan . "\">" . $dl . "</a><br>"; break;
        //default : $out .= "<a href=\"fs.phtml?fs=". $fs_link . "&lan=" . $lan . "\">" . $dl . "</a><br>";
      
        case '1': if (!$type) $out .= "<li>";
                  $out .= seturl("t=fs&fs=".$fs_link."&lan=".$lan, $dl); 
				  //"<a href=\"home.phtml?fs=" .$fs_link . "&lan=" . $lan . "\">" . $dl . "</a>";
                  if ($i<$maxc) {
                    if ($type) $out .= "&nbsp;|&nbsp;";
                          else $out .= "<br>";
                  }
                  break;
        default : if (!$type) $out .= "<li>";
                  $out .= seturl("t=fs&fs=".$fs_link."&lan=".$lan, $dl);
				  //"<a href=\"fs.phtml?fs=". $fs_link . "&lan=" . $lan . "\">" . $dl . "</a>";
                  if ($i<$maxc) {
                    if ($type) $out .= "&nbsp;|&nbsp;";
                          else $out .= "<br>";
                  }
      }
     }
    }
    $i+=1;
  }


  return ($out);
}

function getfs_contents($fs=null,$template=null) {
  $lan = GetReq('lan');

  if ($lan) $path = $this->fspath . $lan . "/" . $this->fsalias . "/";
       else $path = $this->fspath . "/" . $this->fsalias . "/";
  if ($fs) $cpath = $path . $fs . "/";  
      else $cpath = $path; 
  //echo $cpath;

  $fpath = $cpath;

  if (is_dir($fpath)) {

    $mydir = dir($fpath); //echo 'PATH:',$fpath;

    while ($fileread = $mydir->read()) {//echo $fileread,"<br>";
      if ((!is_dir($fpath.$fileread)) && ($fileread!='.') && ($fileread!='..'))
        $ret[]=$fileread;
    }

    $mydir->close();
  }
  //print_r($ret);
  return ($ret);
}

function getfs_sql_contents($fs) {
  $lan = GetReq('lan');
  $fs = GetReq('fs');

  //get paths
  if ($lan) $path = $this->fspath . $lan . "/" . $this->fsalias . "/";
       else $path = $this->fspath . "/" . $this->fsalias . "/";
  if ($fs) $cpath = $path . $fs . "/";  
      else $cpath = $path;
  
  if ($fs) {
    $ret = $this->connect($fs,$cpath);
    if (is_array($ret)) {

      //echo "Connect OK!";
      //print_r($ret);

      return ($ret);
    }
    else {
      echo "Connection Error !!!";
      return (false);
    }
  }

  return (false);
}

function show_fs_contents($template=null) {
  $lan = GetReq('lan');
  $fs = GetReq('fs');

  //get paths
  if ($lan) $path = $this->fspath . $lan . "/" . $this->fsalias . "/";
       else $path = $this->fspath . "/" . $this->fsalias . "/";
  if ($fs) $cpath = $path . $fs . "/";  
      else $cpath = $path;
  $rpath = $this->url . $lan . "/$this->fsalias/$fs/"; //url

  //read dir config
  $conf = readini($path."config.ini"); //print_r($conf);
  $template = $conf[$fs]; //echo $template;  
  //read content config
  $conf2 = readini($cpath."config.ini"); //print_r($conf2);  

  switch ($template) {

     case 'SQL' : //get sql contents 
                  $data = $this->getfs_sql_contents($fs);  //print_r($data);  
                  break;


     case 'DIR' : //$data = getfs($fs); print_r($data);
                  break;
 
     case 'TXT' : //get dir contents
     case 'HTML': //.. 
     case 'PIC' : //.. 
     default    : $data = $this->getfs_contents($fs,$template);  //print_r($data);
  }

  //print contents
  //print_r($data);
  if (is_array($data)) {

    switch ($template) {
      case 'SQL'  : $out .= $this->show_fs_sql($data,$path,$url,$conf2); 
                    break;
      case 'DIR'  : $out .= "<BR><BR>" . $this->show_fs(null,null,$fs); 
                    break;
      case 'TXT'  :
      case 'HTML' : 
      case 'PIC'  :
      default     :
                    foreach ($data as $id=>$file) {
    
                      switch ($template) {
                        case 'PIC' : if ((stristr($file,'.jpg')) || (stristr($file,'.png')))
                                        $out .= $this->show_fs_pic($file,$cpath,$rpath,$conf2) . "<br>"; break;

                        case 'HTML': if (stristr($file,'.htm') || stristr($file,'.html')) 
                                        $out .= $this->readtfile($cpath.$file) . "<br>"; break;
                        case 'TXT' : if (stristr($file,'.txt')) 
                                        $out .= $this->show_fs_txt($file,$cpath,$rpath,$conf2) . "<br>"; break;
                        default    : $out .= $file . "<br>"; 
                      }
                    }
     }

  }
  else { //DIR??
      
     $out = "<BR><BR>" . $this->show_fs(null,null,$fs);
  }

  return ($out);
}

function show_fs_txt($file,$path,$url,$conf_array) {

  //print_r($conf_array);
  if (is_array($conf_array)) {
    
     $parts = explode(",",$conf_array[$file]);
     $title = $parts[0];
     $link  = $parts[1];
  }
  
  $out = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr> 
    <td width=\"78%\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"><font size=\"1\"><b>" . 
    $this->readtfile($path.$file) .
    "</b></font></font></td>
    <td width=\"22%\"><a href=\"$link\"><img border=0 height=80 src=\"". $url . str_replace(".txt",".jpg",$file). "\" width=109></a></td>
  </tr>
  </table>";

  return $out;
}

function show_fs_pic($file,$path,$url,$conf_array) {
  $lan = GetReq('lan');
  $fs = GetReq('fs');

  //print_r($conf_array);
  if (is_array($conf_array)) {
    
     $parts = explode(",",$conf_array[$file]);
     $title = $parts[0];
     (trim($parts[1]) ? $link = $parts[1] : $link = $this->url . $lan . "/fs/" . $fs . "/" . $file);
  }
  
  $out = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr> 
    <td width=\"78%\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"><font size=\"1\"><b>" . 
    $title .
    "</b></font></font></td>
    <td width=\"22%\"><a href=\"$link\" target=\"_blanc\"><img border=0 height=80 src=\"". $link . "\" width=109></a></td>
  </tr>
  </table>";

  return $out;
}


function show_fs_sql($sql_array,$path,$url,$conf_array) {
  $lan = GetReq('lan');
  $fs = GetReq('fs');
  //print_r($conf_array);
  //print_r($sql_array);

  if (is_array($conf_array)) {
    
    $fields = explode(",",$conf_array['fields']);
    $fwidth = explode(",",$conf_array['fwidth']);
    $alias = explode(",",$conf_array['alias']);
    $hidden = explode(",",$conf_array['hidden']);
    $attachments = $conf_array['attachments'];
    $attype = $conf_array['attachtype'];
    $atfield = $conf_array['attachfield'];
    $links = $conf_array['links'];

    $out = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">"; 

    //titles
    $out .= "<tr>"; 
    $i=0;
    foreach ($alias as $recid=>$alis) {

         $out .= "<td width=\"$fwidth[$i]\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"><font size=\"1\"><b>"; 
         $out .= $alis;
         $out .= "</b></font></font></td>";
         $i+=1;
    }
    $out .="</tr>";

    foreach ($sql_array as $recid=>$record) {

       $out .= "<tr>"; 
       $i=0;
       foreach ($fields as $nid=>$recname) {
         
         $out .= "<td width=\"$fwidth[$i]\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"><font size=\"1\">"; 

         if ($recname==$atfield) {
            $link = $this->url . $lan . "/fs/" . $fs . "/" . $record[$recname] . $attype;

            if ($links) {
              $out .= "<a href=\"$link\" target=\"_blanc\"><img border=0 height=80 src=\"". $link . "\" width=109></a>";
            }
            else
              $out .= "<img border=0 height=80 src=\"". $link . "\" >";
         }
         else 
           $out .= $record[$recname];
         $out .= "</font></font></td>";
         $i+=1;
       }

       $out .="</tr>";
    }
  }
  $out .= "</table>";

  return ($out);
}	

function readtfile($file) {

  $fp = fopen($file,"r");
  $fdata = fread($fp,filesize($file));

  fclose($fp);
  
  return ($fdata);
} 

//SQL
function connect($fs,$path,$search=null) {
   
  $cfile = $path."config.ini"; //echo $cfile;
  if (is_file($cfile)) {

    $conf = readini($cfile);

    if (is_array($conf)) {  //print_r($conf);

        $db = ADONewConnection($conf['type']);
        $db->PConnect($conf['host'], $conf['user'], $conf['pwd'], $conf['name']);
	
	  //test code
        $sSQL = "SELECT * from $fs"; 
        if ($search!=null) {

          $fields = explode(",",$conf['fields']);
          $type = explode(",",$conf['datatype']);
          $hidden = explode(",",$conf['hidden']);

          $maxf = count($fields) - count($hidden) - 1;

          foreach($fields as $id=>$f) {
             
             if (!in_array($f,$hidden)) {
                 
               switch ($type[$id]) {
                  case 'VAL' : break;
                  case 'TXT' :
                  default    : $searchSQL .= $f . " like '%" . $search . "%'";
                               if ($id<$maxf) $searchSQL .= " or ";
               }
             }
          }

          $sSQL .= " where " . $searchSQL;
        }
        //echo $sSQL;

        $result = $db->Execute($sSQL);
        //print_r($result);
        //echo $sSQL;

        //print_r($result->GetArray());

        $ret = $result->GetArray();
     }
     else {
       echo "Invalid Configuration!";
       return (false);
     }
   }
   else {
     echo "Configuration not defined!";
     return (false);   
   }

   return ($ret);
                  
}

};
}
?>