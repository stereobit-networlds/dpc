<?php


$__DPCSEC['RCNEWS_DPC']='1;1;1;1;1;1;1;1;1';


if ((!defined("RCNEWS_DPC")) && (seclevel('RCNEWS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCNEWS_DPC",true);


$__DPC['RCNEWS_DPC'] = 'rcnews';


$d = GetGlobal('controller')->require_dpc('gui/tinyMCE.dpc.php');
require_once($d); 
 
$__EVENTS['RCNEWS_DPC'][0]='cpnews';
$__EVENTS['RCNEWS_DPC'][1]='cpsavenews';


$__ACTIONS['RCNEWS_DPC'][0]='cpnews';
$__ACTIONS['RCNEWS_DPC'][1]='cpsavenews';


$__DPCATTR['RCNEWS_DPC']['cpnews'] = 'cpnews,1,0,0,0,0,0,0,0,0,0,0,1';


$__LOCALE['RCNEWS_DPC'][0]='RCNEWS_DPC;Edit News;Edit News';


class rcnews {


	var $post;
	var $ext;
	var $news_path;
	var $newsfiles;
	var $standalone;	
		
	function rcnews() {
		
	    $this->title = localize('RCNEWS_DPC',getlocal());		
		$this->post = false; //hold successfull posting	
		
		//$this->path = paramload('SHELL','prpath');


		if ($remoteuser=GetSessionParam('REMOTELOGIN')) 
		  $this->path = paramload('SHELL','urlpath')."/$remoteuser/" . paramload('ID','hostinpath') .'/';	
		else {
		  $this->path = paramload('SHELL','urlpath')."/". paramload('ID','hostinpath') .'/';		
		  $this->standalone = true;//called by client in it own cp
		}  
		
	    $this->news_path = $this->path . paramload('NEWSRV','dirname');
		
		//echo $this->path,'>>>',$this->news_path;		
		$this->ext = arrayload('NEWSRV','extensions');	//print_r($this->ext);
		   
	}
	 	
	
    function event($sAction) {	
       $lansel = GetReq('lang');	
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////		
	
       $sFormErr = GetGlobal('sFormErr');	    	    		  			    
  
       if (!$sFormErr) {   
  
	   switch ($sAction) {	


		case "cpnews"      :     
		                         $this->read_directory(null,$lansel); 
		                         $this->post = false;
		                         break;
		case "cpsavenews"  :     
		                         $this->savenews();
								 $this->read_directory(null,$lansel); 
		                         $this->post = true;
		                         break;
       }
      }   
    }
  
    function action($action) {


	 $out = $this->newsform();
	 
	 //$out .= $this->htmleditor->render();
	 
	 return ($out);
    } 
		
	  
  
  function newsform($action=null) {


     $sFormErr = GetGlobal('sFormErr');
	 
     if ($action) {//=wizard	
	   $myaction = seturl("t=$action"); 
	 }
	 else {
       $myaction = seturl("t=cpsavenews");
	 
	   if (GetSessionParam('REMOTELOGIN')) 
	     $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	   else  
         $out = setNavigator(seturl("t=cp","Control Panel"),$this->title); 	 
	 }  	 	 
	 	 
	 if ($this->post==true) {   
	   
	   (isset($this->msg)? $msg=$this->msg : $msg = "Data submited!");
	   
	   $swin = new window("",$msg);
	   $out .= $swin->render("center::50%::0::group_win_body::center::0::0::");	
	   unset ($swin);
	   
	 }
	 //else { //show the form plus error if any
	 	 
       $out .= setError($sFormErr . $this->msg);
	   
	   if ($this->standalone)
	     $out .= $this->show_directory(null,"Load");	//CALLED BY CPSSYSTEM.CPWINHELP AT TEMPLATE	   
	   
       //lang dir selection
       $lans = arrayload('SHELL','languages');
       foreach ($lans as $id=>$landescr)
         $out .= seturl('t=cpnews&lang='.$id,$landescr) . '|';	   
	   
	   $out .= $this->form($action);


	   //$form->checkform();	   
	   //if ($this->standalone)
	     //$out .= $this->show_directory();	//CALLED BY CPSSYSTEM.CPWINHELP AT TEMPLATE   
	 //}
 
     return ($out);
  }  
  
  function form($action=null,$myfile=null) {
  
	   if (GetReq('t')=='cpwizard')//(defined('RCSRPWIZARD_DPC'))
		  $this->htmleditor = new tinyMCE('textareas','ADVANCEDSIMPLE',1,'news');	 
	   else
		  $this->htmleditor = new tinyMCE('textareas','ADVANCEDFULL',1,'news');    


       if (!$myfile)
	     $file = getReq('id');
	   else
	     $file = $myfile;	
		 
       if ($action) {//=wizard	
	     $ypsos = 10;
	     $myaction = seturl("t=$action"); 		  
	   }	 
	   else {
	     $ypsos = 20;
		 $myaction = seturl("t=cpsavenews&id=".GetReq('id')."&lang=".GetReq('lang'));
	     $action = 'cpsavenews';
	   }	 
	   
	  /* $form = new form(localize('_RCNEWS',getlocal()), "RCNEWS", FORM_METHOD_POST, $myaction);
	
	   if (!$myfile) {	
	     $form->addGroup			("title",			"Title.");  
         $form->addElement		("title", new form_element_text("Title",  "title",		$file,				"forminput",			50,				255,	0));	
	   }
	        	   
	   $form->addGroup			("body",			"Body.");	
	   if ($action)//=wizard
	        $form->addElement		("body",new form_element_textarea("",  "body",$this->loadfromfile($file),	"formtextarea",	"50%",	10));
	   else	
	        $form->addElement		("body",new form_element_textarea("",  "body",$this->loadfromfile($file),	"formtextarea",	"100%",	10));
	   
	   
	   // Adding a hidden field
	   if ($action)
		  $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "$action"));
	    else	   
	     $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "cpsavenews"));
 
	   // Showing the form
	   $fout = $form->getform();	*/	
	   
	   
	   $fout = "<form method=\"post\" name=\"RCNEWS\" action=\"$myaction\">";
	   	 
   	   $fout .= $this->htmleditor->render('body','100%',$ypsos,$this->loadfromfile($file));	  
	   $fout .= "<input type=\"text\" name=\"title\" value=\"$file\" size=\"50\" maxlength=\"255\">";	   


	   $fout .= "<input type=\"text\" name=\"lanselect\" value=\"\" size=\"20\" maxlength=\"2\">";	   
	     
       $fout .= "<input type=submit value=\"Submit\">";
	   
	   $fout .= "<input type=\"hidden\" name=\"FormAction\" value=\"$action\">";   
       $fout .= "</form>";  
	   
	   if ($action!='cpsavenews')
	     return ($fout);
	   else {	 
	     $fwin = new window("Edit news files...",$fout);
	     $winout = $fwin->render();	
	     unset ($fwin);		   
		 return ($winout); 
	   } 
  }
  
  function savenews() {
  
    if (($title=GetParam('title')) && ($body=GetParam('body'))) {


	  //if has no extension set extension
	  foreach ($this->ext as $num=>$ext) { 
		 if (stristr($title,$ext)) {			   
		   $extension = true;
 	     } 
	  }
	  if (!$extension) $title.= '.txt';//default
	
      $this->msg = $this->write2file($title,$body);
	}  
	else
	  $this->msg = "Data missing!";  
	  
	return ($this->msg);  
	
  }
  
  function loadfromfile($filename) {
     $lan_selection = GetReq('lang');
	 
	 // $file = $this->news_path ."/". $filename;
	 if ($lan_selection)
	   $file = $this->news_path . "_" . $lan_selection."/".$filename;
	 else
	   $file = $this->news_path . $filename;
	 
	 
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


  
  function write2file($filename,$data) {
     $lan_selection = GetParam('lanselect');
	 
	 if ($lan_selection)
	   $file = $this->news_path . "_" . $lan_selection."/".$filename;
	 else
	   $file = $this->news_path . "_0" ."/".$filename; //default


	 //echo $file,"<br>",$data;
			
     if ($fp = fopen ($file , "w")) {
                 fwrite ($fp, $data);
                 fclose ($fp);
     }
     else {
         $this->msg = "File creation error ($filename)!\n";
		 //echo "File creation error ($filename)!<br>";
     }	
	 
	 return ($this->msg);
  }
  
  function read_directory($dirname=null,$lang=null) {
  
     if (!$dirname) {
       if ($lang) 
	     $dirname = $this->news_path . "_$lang/";
       else
         $dirname = $this->news_path . "_0/"; //default
     }
     //echo $dirname;
     if (defined('RCFS_DPC')) {


	    $extensions = array(0=>'.spn',1=>'.txt');//$this->ext;
	 
	    $this->fs= new rcfs($dirname);
		
		////CHECK iF AND DELETE FILE
		$this->fs->delete_file();
		
		$ddir = $this->fs->read_directory($dirname,$extensions); 
	 }
     else {    
        //print_r($this->ext);
	    if (is_dir($dirname)) {//echo 'z';
          $mydir = dir($dirname);
		 
          while ($fileread = $mydir->read ()) {
	       //echo $fileread,'<br>';
           //read directories
		   if (($fileread!='.') && ($fileread!='..'))  {
             //echo $fileread,'<br>';
			 if (is_file($fileread)) {
			 foreach ($this->ext as $num=>$ext) { 
			        if (stristr($fileread,$ext)) {			   
					  //$parts = explode("-",$fileread);
		              $ddir[] = $fileread;						
					}
		     }
			 }
		   } 
	      }
	      $mydir->close ();
        }
	  }	
	  
	  $this->newsfiles = $ddir;
	  //echo 'A';
	  //print_r($this->newsfiles);
	  //return ($ddir);
  }
  
  function show_directory($action=null,$combo=null)  {
     $lansel = GetReq('lang');  
  
     if (!$title) 
	   $title = "News files";  


     if (defined('RCFS_DPC')) {
      
		if ($action)
		  $ret = $this->fs->show_directory($this->newsfiles,"t=$action&lang=".$lansel."&id=",$title); 
		else  
		  $ret = $this->fs->show_directory($this->newsfiles,"t=cpnews&lang=".$lansel."&id=",$title); 
	 }
     elseif ($combo){
		$myaction = seturl("t=cpnews");	 
	    $ret = "<form method=\"post\" name=\"RCNEWS\" action=\"$myaction\">";	 
		$ret .= "<select name=\"id\">"; 
	 
        foreach ($this->newsfiles as $id=>$name) {
		  $parts = explode(".",$name);
		  $title = $parts[0];
          $ret .= "<option value=\"$name\"".($value == GetReq('id') ? " selected" : "").">$title</option>";		
		}	
		
		$ret .= "</select>";	
        $ret .= "<input type=submit value=\"$combo\">";
	   
	    $ret .= "<input type=\"hidden\" name=\"FormAction\" value=\"cpnews\">";   
        $ret .= "</form>";  			    
	 }
	 else {   
      if (is_array($this->newsfiles)) {
        foreach ($this->newsfiles as $id=>$name) {
	
	      if ($action)
		    $ret .= seturl("t=$action&id=".$name,$name) ."<br>";
		  else	
	        $ret .= seturl("t=cpnews&id=".$name,$name) ."<br>"; 
	    }
	  }
	}


	return ($ret);
  }
  
};
}
?>