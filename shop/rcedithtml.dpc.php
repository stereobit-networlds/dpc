<?php
$__DPCSEC['RCEDITHTML_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCEDITHTML_DPC")) && (seclevel('RCEDITHTML_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCEDITHTML_DPC",true);

$__DPC['RCEDITHTML_DPC'] = 'rcedithtml';

//$d = GetGlobal('controller')->require_dpc('rc/rcedittemplates.dpc.php');
$d = GetGlobal('controller')->require_dpc('rc/rctedit.dpc.php');
require_once($d);

//GetGlobal('controller')->get_parent('RCEDITTEMPLATE_DPC','RCEDITHTML_DPC');
GetGlobal('controller')->get_parent('RCTEDIT_DPC','RCEDITHTML_DPC');

$__EVENTS['RCEDITHTML_DPC'][0]='cpedithtml';

$__ACTIONS['RCEDITHTML_DPC'][0]='cpedithtml';

$__DPCATTR['RCEDITHTML_DPC']['cpedithtml'] = 'cpedithtml,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCEDITHTML_DPC'][0]='RCEDITHTML_DPC;Edit Html;Edit Html';

class rcedithtml extends /*rcedittemplates*/ rctedit {

    function rcedithtml(/*$tfile=null,$tpath=null,*/$maxsize=null) {
	
	  //rcedittemplates::rcedittemplates($tfile,$tpath,$maxsize);
	  rctedit::rctedit($maxsize);
	  $this->title = localize('RCEDITHTML_DPC',getlocal());	
	}
	
	function event($event=null) {
	
	  //rcedittemplates::event($event);
	  rctedit::event($event);	  
	}
	
	function action($action=null) {
	
	  //$ret = rcedittemplates::action($action);
	  $ret = rctedit::action($action);
	  
	  return ($ret);
	}	
	
	//override
	/*function read_dir_list() {
	
		//echo $this->tpath,'>>>>';
		//echo '>',GetSessionParam('template_subdir');
		  	
	    if (is_dir($this->tpath)) {//echo 'a';
		
          $filename = seturl("t=cpchangetemplate");		
		
          $out  = "<FORM action=". "$filename" . " method=post ENCTYPE=\"multipart/form-data\" class=\"thin\">";
          $out .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>"; 			 	   		    
	      $out .= "Select directory :"; 
	      $out .= "<select name=\"todir\">";	
		  $out .= "<option value=\"\">---Select a directory---</option>";		
		
          $mydir = dir($this->tpath);
		  
          while ($fileread = $mydir->read ()) {
		     //echo $fileread;
		     if (($fileread!='.') && ($fileread!='..') && (substr($fileread,0,2)!='cp') ) {
			   //echo $fileread;	   
			   if (is_dir($this->tpath."/".$fileread))
			     $out .= "<option value=\"$fileread\">$fileread</option>";
		     }	
		  }	
		  
	      $out .= "</select>";	
		  
          $out .= "<input type=\"hidden\" name=\"FormName\" value=\"Cpchangetemplate\">"; 	
          $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"cpchangetemplate\">&nbsp;";			
			
          $out .= "<input type=\"submit\" name=\"Submit2\" value=\"Ok\">";		
          $out .= "</FONT></FORM>"; 			  	   
		}
//echo $out;
		return ($out);  
	}	*/	
	
	//override
	function show_template_files($combo=null,$taction=null) {
	
	   if ($taction)
	     $myact = $taction;
	   else
	     $myact = 'cpedithtml';
	   //echo $myact,'>';
       if (defined('RCFS_DPC')) {
	
	    $path = $this->htmlpath;// . 'public/themes/basic.theme'; 
	    $extensions = array(0=>".htm");//,1=>".html");//$this->ext;htm is sub of html->double list
	 
	    $this->fs= new rcfs($path);
		$ddir = $this->fs->read_directory($path,$extensions); 
		//$ddir = GetGlobal('controller')->calldpc_method('rcfs.read_directory use '.$path.'+'.$extensions);

		sort($ddir,SORT_STRING);
		
		if (!$combo) {
		
          $ret = $this->fs->show_directory($ddir,"t=$myact&f=/","Files");
		  //$ret = $ddir = GetGlobal('controller')->calldpc_method('rcfs.show_directory use '.$ddir'+t=cptedit&f=/+Files');		
	    }
        else {
		  $myaction = seturl("t=$myact");	 
	      $ret = "<form method=\"post\" name=\"RCTEDIT\" action=\"$myaction\">";	 
		  $ret .= "<select name=\"id\">"; 
	 
          foreach ($ddir as $id=>$name) {
		    if (substr($name,0,2)!='cp') { //exclude cp files
		      $parts = explode(".",$name);
		      $title = $parts[0];
              $ret .= "<option value=\"$name\"".($value == GetReq('id') ? " selected" : "").">$title</option>";		
			}
		  }	
		
		  $ret .= "</select>";	
          $ret .= "<input type=submit value=\"$combo\">";
	   
	      $ret .= "<input type=\"hidden\" name=\"FormAction\" value=\"$myact\">";   
          $ret .= "</form>";  			    
	    } 
	   }	  
	    
	   return ($ret);		
	}	
	
	//override
    function editform($tfile,$tpath=null,$title=null,$treplace=null,$isnew=null) {  

		  $this->htmleditor = new tinyMCE('textareas','ADVANCEDFULL',1,'images',$this->depth);	
		  
		  if ($name = GetParam('newname'))
		    $file = '/' . $name;
		  else
		    $file = GetReq('f');
			
	      $ypsos = 36;
          $myaction = seturl("t=cptsave&f=".$file);	 	
	      $action = 'cptsave';				

	      if ($this->post==true) {	   
	   
	        $swin = new window("",$this->msg);
	        $winout = $swin->render("center::70%::0::group_article_body::center::0::0::");	
	        unset ($swin);
	      }		
		  
		  $source = $this->loadfromfile($tfile,$tpath);//,$this->depth);
		  if ($treplace) 
		     $data = str_replace("images/",$treplace,$source);
		  else
		     $data = $source;	 			  
          //echo $source,'>';
	      $out .= "<form method=\"post\" name=\"RCEDITTEMPLATES\" action=\"$myaction\">";
	   
   	      $out .= $this->htmleditor->render('body','100%',$ypsos,$data);	
		  
		  if ($isnew)     
		    $out .= "<input type=text name='newname' value=\"noname.html\">";
	      
          $out .= "<input type=submit value=\"Save\">";
	   
          //default name to save
		  $myfilename = str_replace('.php','.html',$tfile);
	      $out .= "<input type=\"hidden\" name=\"tosave\" value=\"".$tfile."\">"; 		  	 
 	   
	   
	      $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"$action\">";   
          $out .= "</form>"; 			  
		
		  $wina = new window($title,$out);
		  $winout .= $wina->render();//"center::100%::0::group_dir_title::right::0::0::");
		  unset ($wina);	
		
		  return ($winout);
    }	
	
	//override
    function save_file($tpath=null,$treplace=null) {
	
	
		 $data = GetParam('body');
		 //echo substr($data,1,50);
		 
		 if ($newname = GetParam('newname')) {//new file 
		 
                 $basichtml = "
<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<title>Title</title>
<LINK REL=StyleSheet HREF=\"styles.css\">
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
</head>
<body text=\"#333333\" link=\"#FF0000\" vlink=\"#FF6600\" alink=\"#FF0000\">
<@>
</body>
</html>				 
";		 
           //only for .html exclude root files
		   if (stristr($newname,'.html') or stristr($newname,'.htm'))
             $data = str_replace('<@>',$data,$basichtml);
		 
		   // / added here due to /name.ext call of f param
		   $name2save = '/' . str_replace('.php','.html',GetParam('newname'));
		   
		   //create script and config as needed
		   $this->new_script_file('/'.$name2save);// / added here due to /name.ext call of f param
           $this->new_config_file('/'.$name2save);		
		   $this->new_php_file('/'.$name2save);		   
		 }  
		 else
		   $name2save = str_replace('.php','.html',GetParam('tosave'));
		 
         if ($treplace) {
	       $source = str_repeat('../',$treplace);
	       $abspath = paramload('SHELL','urlbase') . paramload('ID','hostinpath') .'/';		 
		   
		   $data = str_replace($source,$abspath,$data);		
		 }  	     
	
	     if ($data)  {
         
		  if (strlen($data)<=$this->MAXSIZE) {
		  
		    $file = $tpath .'/'. $name2save; //echo $file,'>';
	        
            if ($fp = @fopen ($file , "w")) {
	        //echo $file,"<br>";
                 fwrite ($fp, $data);
                 fclose ($fp);
            }
            else {
              $this->msg = "File creation error ($file)!\n";
		      //echo "File creation error ($filename)!<br>";
            }	
			  			
		  }
		  else 
		    $this->msg = "File size error (Maximum size ". $this->MAXSIZE ." bytes)!\n";
			
		  $this->post = true;	
        }
		else
		  $this->post = false;
		  
		return ($this->msg);  
	}			
	
};
}	
?>