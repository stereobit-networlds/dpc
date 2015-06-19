<?php
$__DPCSEC['RCEDITTEMPLATES_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCEDITTEMPLATES_DPC")) && (seclevel('RCEDITTEMPLATES_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCEDITTEMPLATES_DPC",true);

$__DPC['RCEDITTEMPLATES_DPC'] = 'rcedittemplates';
 
$__EVENTS['RCEDITTEMPLATES_DPC'][0]='cpedittemplates';
$__EVENTS['RCEDITTEMPLATES_DPC'][1]='cpsavetemplate';
$__EVENTS['RCEDITTEMPLATES_DPC'][2]='cpchangetemplate';

$__ACTIONS['RCEDITTEMPLATES_DPC'][0]='cpedittemplates';
$__ACTIONS['RCEDITTEMPLATES_DPC'][1]='cpsavetemplate';
$__ACTIONS['RCEDITTEMPLATES_DPC'][2]='cpchangetemplate';

$__DPCATTR['RCEDITTEMPLATES_DPC']['cpedittemplates'] = 'cpedittemplates,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCEDITTEMPLATES_DPC'][0]='RCEDITTEMPLATES_DPC;Edit Templates;Edit Templates';

class rcedittemplates {

    var $title,$path,$post,$msg;
	var $MAXSIZE;
	var $tfile,$tpath,$ext,$lines;
        var $urlpath, $infolder;

    function rcedittemplates($tfile=null,$tpath=null,$maxsize=null) {
	
	    $this->title = localize('RCEDITTEMPLATES_DPC',getlocal());		
		$this->post = false; //hold successfull posting	
		$this->msg = null;
            $this->urlpath = paramload('SHELL','urlpath');
            $this->infolder = paramload('ID','hostinpath');
		
		if (isset($maxsize))
		  $this->MAXSIZE = $maxsize;
		else
		  $this->MAXSIZE = 59024;
		
		if ($remoteuser=GetSessionParam('REMOTELOGIN')) 
		  $this->path = $this->urlpath."/$remoteuser/$this->infolder/cp/html/";	
		else
		  $this->path = $this->urlpath . $this->infolder . '/cp/html/';
		
		if ($p=GetSessionParam("template_subdir"))
		  $this->tpath = $this->path . $p;
		else  
		  $this->tpath = $this->path . $tpath;
		  
		$this->tfile = $tfile;  
		$this->ext = array(0=>".htm",1=>".html");		 	
    }
	
    function event($event=null) {
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////
	   
	   switch ($event) {
	     case 'cpchangetemplate' : $this->set_dir(); 
		                           break;
	     case 'cpsavetemplate'   : $this->save_file(); 
		                           break;
		 default                 : 
	   }	 
	   
       GetGlobal('controller')->calldpc_method("rcsidewin.set_show use ".$this->sidewin());	   
	}	
  
    function action($action=null) {

	   if (GetSessionParam('REMOTELOGIN')) 
	     $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	   else  
         $out = setNavigator(seturl("t=cp","Control Panel"),$this->title); 	 	
	
	   $out  .= $this->editform(null,null,null,"Edit Templates...",1);
	  
	   return ($out);
	}  
	
    function editform($action=null,$tfile=null,$tpath=null,$title=null,$list=null,$treplace=null) {  

	      if (GetReq('t')=='cpwizard')//(defined('RCSRPWIZARD_DPC'))
		    $this->htmleditor = new tinyMCE('textareas','ADVANCED');	 
	      else
		    $this->htmleditor = new tinyMCE('textareas','ADVANCEDFULL',1,'images',2);	
			
          if ($action) {//=wizard	
	        $ypsos = 20;
            $myaction = seturl("t=$action");	 		  
	      }	 
	      else {
	        $ypsos = ($this->lines?$this->lines:25);
            $myaction = seturl("t=cpsavetemplate");		 
	        $action = 'cpsavetemplate';
	      }					

	      if ($this->post==true) {	   
	   
	        $swin = new window("",$this->msg);
	        $winout = $swin->render("center::70%::0::group_article_body::center::0::0::");	
	        unset ($swin);
	      }		
		  
		  $source = $this->loadfromfile($tfile,$tpath);
		  if ($treplace) 
		     $data = str_replace("images/",$treplace,$source);
		  else
		     $data = $source;	 			  
		  
	      $out .= "<form method=\"post\" name=\"RCEDITTEMPLATES\" action=\"$myaction\">";
	   
   	      $out .= $this->htmleditor->render('body','100%',$ypsos,$data);	     
	      
          $out .= "<input type=submit value=\"Submit\">";
	   
	      if ($tfile) {//name to save
		    if ($tpath)
              $out .= "<input type=\"hidden\" name=\"tosave\" value=\"".$this->path.$tpath.$tfile."\">"; 
			else
			  $out .= "<input type=\"hidden\" name=\"tosave\" value=\"".$this->path.$tfile."\">"; 	  
	      }		
		  elseif ($myfile = GetReq('id'))
		    $out .= "<input type=\"hidden\" name=\"tosave\" value=\"".$this->tpath.$myfile."\">"; 		
		  else //default name to save
	        $out .= "<input type=\"hidden\" name=\"tosave\" value=\"".$this->tpath.$this->tfile."\">"; 		  	 
 	   
	   
	      $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"$action\">";   
          $out .= "</form>"; 			  
		
		  $wina = new window($title,$out);
		  $winout .= $wina->render();//"center::100%::0::group_dir_title::right::0::0::");
		  unset ($wina);	
		  
		  if ($list) {
		    $winout .= $this->read_dir_list();
		    $winout .= $this->show_templates();		  	  		  
		  }	
		
		  return ($winout);
    }
  
    function save_file($tfile=null,$tpath=null,$source=null,$treplace=null) {
	
		 if ($source) {
		   $data = file_get_contents($this->path . $source);
		   if ($treplace) 
		     //$data = str_replace("images/",$treplace,$data);
			 //do the opposite due to replace at reading....only appname	
			 $data = str_replace($treplace,"",$data);	
		 }  
		 else  {
		   $data = GetParam('body');
           if ($treplace) 
			 $data = str_replace($treplace,"",$data);			   
		 }  
	
	     if ($data)  {
         
		  if (strlen($data)<=$this->MAXSIZE) {
		  
		    if ($tfile) {
			  $file = $this->path . $tpath . $tfile;
			}
			else
		      $file = GetParam('tosave');
	 
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
	
    function loadfromfile($tfile=null,$path=null,$treplace=null) {
	 
	 if ($tfile) //manual set to form (called from wizard)
	    $file = $this->path . $path . $tfile;
	 elseif ($myfile = GetReq('id'))
	   $file = $this->tpath . "/" . $myfile;
	 else
	   $file = $this->tpath . "/" . $this->tfile;
	 //echo $file;  
	 
     if ($fp = @fopen ($file , "r")) {
                 $ret = fread ($fp, filesize($file));
                 fclose ($fp);
     }
     else {
         $this->msg = "File reading error !\n";
		 //echo "File reading error ($filename)!<br>";
     }	
	   
	 
	 return ($ret);
    }
	
	function read_dir_list() {
	
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
		     if (($fileread!='.') && ($fileread!='..')) {
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
	}	
	
	function set_dir() {
	
	    if ($subdir = GetParam('todir')) {
		  if ($psubdir=GetSessionParam('template_subdir')) {
		    $this->tpath = $this->path . $psubdir . "/" . $subdir;
		    SetSessionParam('template_subdir',$psubdir . "/" . $subdir);		  
		  }
		  else {
		    $this->tpath = $this->path . $subdir;
		    SetSessionParam('template_subdir',$subdir);
	      }		
		}  
		else {
		  $this->tpath = $this->path;
		  SetSessionParam('template_subdir',null);
		}  
		    
	}	
	
	function show_templates() {
	
       if (defined('RCFS_DPC')) {
	 
	    $this->fs= new rcfs($this->tpath);
		$ddir = $this->fs->read_directory($this->tpath,$this->ext); 
		
        $ret = $this->fs->show_directory($ddir,"t=cpedittemplates&id=","Templates");		
	   }
           else
               $ret = 'Load rcfs dpc module';
	    
	   return ($ret);		
	}	
	
    function copy_template_file($targetname,$sourcepathname) {  
    
	  if ((isset($sourcepathname)) && (isset($targetname))) {  
			  
		$targetfullpath = $this->tpath . $targetname;
		$sourcefullpath = $this->tpath . $sourcepathname;
		//echo $targetfullpath,">",$sourcefullpath;
		
        if (@copy($sourcefullpath,$targetfullpath)) {
		
    	   setInfo("Copy " .$targetname . " ok!");
		   //$this->msg = "Upload " .$filename . " ok!"; MEANS ERROR
		}
		else {
		
		   setInfo("Failed to copy file " . $targetname); 				   
		   $this->msg = "Failed to copy file " . $targetname;
		}
	   }
	   else
	     $this->msg = "Filename not found!";
		 
	   return ($this->msg);	 
    }	
	
	function render_files($slashp=null) {
	
       if (defined('RCFS_DPC')) {
	 
	     $this->fs= new rcfs($this->tpath);
		 $ddir = $this->fs->read_directory($this->tpath,$this->ext); 	
	     //print_r($ddir);
	   } 
       else {    

	    if (is_dir($this->tpath)) {
          $mydir = dir($this->tpath);
		 
          while ($fileread = $mydir->read ()) {
	   
           //read directories
		   if (($fileread!='.') && ($fileread!='..'))  {

			 foreach ($this->ext as $num=>$ext) { 
			        if (stristr($fileread,$ext)) {			   
					  //$parts = explode("-",$fileread);
		              $ddir[] = $fileread;						
					}
		     }
		   } 
	      }
	      $mydir->close ();
         }
	    }			 
		//print_r($ddir);echo $this->tpath;
		
		if (!empty($ddir)) {
	     if ($slashp) { // /p like dir/p
		   $c = count($ddir);
		   $st = $c/3;
		   $i=0;
           foreach ($ddir as $id => $file) {
		     $parts = explode('.',$file);
		     $list[$i] .= seturl("t=cpedittemplates&sidewin=0&id=".$file,$parts[0]) . "<br>";
			 $lstr[$i] = "left;33%";
			 if ($i==2)
			   $i=0;
			 else
			   $i+=1;  
	       }		
		   $n = new window("Template files...",$list,$lstr);
		   $ret = $n->render();
		   unset($n);
	     }
		 else {//list			
           foreach ($ddir as $id => $file) {
		     $parts = explode('.',$file);
		     $ret .= seturl("t=cpedittemplates&sidewin=0&id=".$file,$parts[0]) . "<br>";
	       }
		   
		   //in case of sided window of list grow editor as lines
		   $this->lines = count($ddir);		
		 } 
	    }
		 
	    return ($ret);	
	}	
	
	
	function sidewin() {
	
	   $out .= $this->render_files(1);
       $out .= $this->read_dir_list();	   
	   return ($out);
	}		  	 
  
};
}
?>