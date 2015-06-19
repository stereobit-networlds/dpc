<?php

$__DPCSEC['RCROOTS_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCROOTS_DPC")) && (seclevel('RCROOTS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCROOTS_DPC",true);

$__DPC['RCROOTS_DPC'] = 'rcroots';
 
$__EVENTS['RCROOTS_DPC'][0]='cproots';
$__EVENTS['RCROOTS_DPC'][1]='cpsaveroots';

$__ACTIONS['RCROOTS_DPC'][0]='cproots';
$__ACTIONS['RCROOTS_DPC'][1]='cpsaveroots';

$__DPCATTR['RCROOTS_DPC']['cproots'] = 'cproots,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCROOTS_DPC'][0]='RCROOTS_DPC;Edit root files;Edit root files';

class rcroots {

	var $post;
	var $title;
	var $rootfiles;
	var $path;
	var $standalone;	
	var $urlpath;	
		
	function rcroots() {
		
	    $this->title = localize('RCROOTS_DPC',getlocal());		
		$this->post = false; //hold successfull posting	
		
		if ($remoteuser=GetSessionParam('REMOTELOGIN')) {
		  $this->path = paramload('SHELL','prpath')."instances/$remoteuser/";		  
		  $this->urlpath = paramload('SHELL','urlpath')."/$remoteuser/".paramload('ID','hostinpath')."/cp/rfiles/";		  
		}  
		else {
		  $this->path = paramload('SHELL','prpath');	  
		  $this->urlpath = paramload('SHELL','urlpath')."/".paramload('ID','hostinpath')."/cp/rfiles/";				  
		  $this->standalone = true;//called by client in it own cp		  		  		
		}  
	}
	 	
	
    function event($sAction) {
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////	
	
       $sFormErr = GetGlobal('sFormErr');	    	    		  			    	   
  
       if (!$sFormErr) {   
  
	   switch ($sAction) {	

		case "cproots"          :
		                         $this->read_directory(); 
		                         $this->post = false;
		                         break;
		case "cpsaveroots"      :
		                         $this->saveroots();
								 $this->read_directory();
		                         $this->post = true;
		                         break;
								 
       }
      }   
    }
  
    function action($action) {

	 $out = $this->rootsform();
	 
	 return ($out);
    }
	
	  
  
  function rootsform() {

     $sFormErr = GetGlobal('sFormErr');
	 
     $myaction = seturl("t=cpsaveroots");
	 
	 if (GetSessionParam('REMOTELOGIN')) 
	     $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	 else  
         $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);  	 
	 	 
	 if ($this->post==true) {   
	   
	   (isset($this->msg)? $msg=$this->msg : $msg = "Data submited!");	   
	   
	   $swin = new window("",$msg);
	   $out .= $swin->render("center::50%::0::group_win_body::center::0::0::");	
	   unset ($swin);
	   
	 }
	 //else { //show the form plus error if any
	 	 
       $out .= setError($sFormErr . $this->msg);
	   
	   if ($this->standalone)   
	     $out .= $this->show_directory(null,"Load");		   
	   
	   $out .= $this->form();

	   //$form->checkform();	
	   //if ($this->standalone)   
	     //$out .= $this->show_directory();	   
	 //}
 
     return ($out);
  } 
  
  function form($action=null,$myfile=null,$title="Body.") {
		  	  
  
	   if (GetReq('t')=='cpwizard')//(defined('RCSRPWIZARD_DPC'))
		  $this->htmleditor = new tinyMCE('textareas','ADVANCEDSIMPLE',1);	 
	   else
		  $this->htmleditor = new tinyMCE('textareas','ADVANCEDFULL',1,'images',2);   
		  
	   //$this->htmleditor->create_image_list();	  
		   
  
       if (!$myfile)
	     $file = getReq('id');  
	   else
	     $file = $myfile;	
		 
       if ($action) {//=wizard	
	     $ypsos = 10;
         $myaction = seturl("t=".$action);	 		  
	   }	 
	   else {
	     $ypsos = 20;
         $myaction = seturl("t=cpsaveroots&id=".GetReq('id'));			 
	     $action = 'cpsaveroots';
	   }			 	  
	   
	   /*$form = new form(localize('_RCROOTS',getlocal()), "RCROOTS", FORM_METHOD_POST, $myaction);
	
	   if (!$myfile) {
	     $form->addGroup			("title",			"Name.");  
         $form->addElement		("title", new form_element_text("Name",  "title",		$file,				"forminput",			90,				255,	0));	
	   } 
	       	   
	   $form->addGroup			("body",			$title);	
	   if ($action)
	     $form->addElement		("body",new form_element_textarea("",  "body",$this->loadfromfile($file),	"formtextarea",	"50%",	10));
	   else	 
	     $form->addElement		("body",new form_element_textarea("",  "body",$this->loadfromfile($file),	"formtextarea",	"100%",	10));
	   
	   // Adding a hidden field
	   if ($action)
	     $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", $action));
	   else	 
	     $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "cpsaveroots"));
 
	   // Showing the form
	   $fout = $form->getform ();	*/
	   
	   $fout = "<form method=\"post\" name=\"RCROOTS\" action=\"$myaction\">";
	   
   	   $fout .= $this->htmleditor->render('body','100%',$ypsos,$this->loadfromfile($file));	     
	   
	   if (!$myfile) 	   
	     $fout .= "<input type=\"text\" name=\"title\" value=\"$file\" size=\"50\" maxlength=\"255\">";		   
	   	 	     
       $fout .= "<input type=submit value=\"Submit\">";
	   
	   $fout .= "<input type=\"hidden\" name=\"FormAction\" value=\"$action\">";   
       $fout .= "</form>"; 	   
	   
	   if ($action!='cpsaveroots')
	     return ($fout);
	   else {	 
	     $fwin = new window("Edit root files...",$fout);
	     $winout .= $fwin->render();	
	     unset ($fwin);		   
		 return ($winout); 
	   } 
  } 
  
  function saveroots($filename=null) {
  
    if ($filename)
	  $title = $filename;
	else  
	  $title = GetParam('title');

    if (($title) && ($body=GetParam('body'))) {

	  //if has no extension set extension
	  if (!stristr($title,".root")) 
	    $title.= '.root';//default
	
      $this->msg = $this->write2file($title,$body);
	}  
	else
	  $this->msg = "Data missing!";  
	  
	return ($this->msg); 	
	
  }
  
  function loadfromfile($filename) {
	 
	 $file = $this->urlpath . $filename;
	 //echo $file;
     if ($fp = @fopen ($file , "r")) {
                 $ret = fread ($fp, filesize($file));
                 fclose ($fp);
     }
     else {
         $this->msg = "File reading error ($filename)!\n";
		 //echo "File reading error ($filename)!<br>";
     }	
	 
	 return ($ret);
  }

  
  function write2file($filename,$data) {
	 
	 $file = $this->urlpath . $filename;
	 //echo $file;

     if ($fp = @fopen ($file , "w")) {
	    //echo $file,"<br>";
                 fwrite ($fp, $data);
                 fclose ($fp);
     }
     else {
         $this->msg = "File creation error ($filename)!\n";
		 //echo "File creation error ($filename)!<br>";
     }	
  }
  
  function read_directory($dirname=null) {
  
     if (!$dirname)
       $dirname = $this->urlpath;
  //echo $dirname;
     if (defined('RCFS_DPC')) {
	 
	    $extensions = array(0=>'.root');
	 
	    $this->fs= new rcfs($dirname);
		$ddir = $this->fs->read_directory($dirname,$extensions); 
	 }
     else { 
	    if (is_dir($dirname)) {
          $mydir = dir($dirname);
		 
          while ($fileread = $mydir->read ()) {
	   
           //read directories
		   if (($fileread!='.') && ($fileread!='..'))  {

  	             if (stristr ($fileread,".root")) {		   

		              $ddir[] = $fileread;						
					}
		   } 
	      }
	      $mydir->close ();
        }
     }		
	 
	 $this->rootfiles = $ddir;
	 //return ($ddir);
  }
  
  function show_directory($action=null,$combo=null)  {
  
     if (!$title) 
	   $title = "Root files";
  
     if (defined('RCFS_DPC')) {
        
		if ($action)
		  $ret = $this->fs->show_directory($this->rootfiles,"t=$action&id=",$title); 
		else  
		  $ret = $this->fs->show_directory($this->rootfiles,"t=cproots&id=",$title); 
	 }
     elseif ($combo){
		$myaction = seturl("t=cproots");	 
	    $ret = "<form method=\"post\" name=\"RCNEWS\" action=\"$myaction\">";	 
		$ret .= "<select name=\"id\">"; 
	 
        foreach ($this->rootfiles as $id=>$name) {
		  $parts = explode(".",$name);
		  $title = $parts[0];
          $ret .= "<option value=\"$name\"".($value == GetReq('id') ? " selected" : "").">$title</option>";		
		}	
		
		$ret .= "</select>";	
        $ret .= "<input type=submit value=\"$combo\">";
	   
	    $ret .= "<input type=\"hidden\" name=\"FormAction\" value=\"cproots\">";   
        $ret .= "</form>";  			    
	 }	 
     else {  
        if (is_array($this->rootfiles)) {
          foreach ($this->rootfiles as $id=>$name) {
	
		   if ($action)	
		     $ret .= seturl("t=$action&id=".$name,$name) ."<br>"; 
		   else	 
	         $ret .= seturl("t=cproots&id=".$name,$name) ."<br>"; 
	      }
	    }
	}
	return ($ret);
  }
  
};
}
?>