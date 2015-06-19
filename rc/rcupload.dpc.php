<?php

$__DPCSEC['RCUPLOAD_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCUPLOAD_DPC")) && (seclevel('RCUPLOAD_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCUPLOAD_DPC",true);

$__DPC['RCUPLOAD_DPC'] = 'rcupload';
 
$__EVENTS['RCUPLOAD_DPC'][0]='cpupload';
$__EVENTS['RCUPLOAD_DPC'][1]='cpdoupload';
$__EVENTS['RCUPLOAD_DPC'][2]='cpchangeuloaddir';
$__EVENTS['RCUPLOAD_DPC'][3]='cpdouploadadv';

$__ACTIONS['RCUPLOAD_DPC'][0]='cpupload';
$__ACTIONS['RCUPLOAD_DPC'][1]='cpdoupload';
$__ACTIONS['RCUPLOAD_DPC'][2]='cpchangeuloaddir';
$__ACTIONS['RCUPLOAD_DPC'][3]='cpdouploadadv';

$__DPCATTR['RCUPLOAD_DPC']['cpupload'] = 'cpupload,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCUPLOAD_DPC'][0]='RCUPLOAD_DPC;Upload files;Upload files';
$__LOCALE['RCUPLOAD_DPC'][1]='_uploadfile;Select file;Επιλογή αρχείου';
$__LOCALE['RCUPLOAD_DPC'][2]='_isprivate;is private;Είναι ιδιωτικό';
$__LOCALE['RCUPLOAD_DPC'][3]='_upload;Upload;Μεταφόρτωση';
$__LOCALE['RCUPLOAD_DPC'][4]='_result;Result;Κατάσταση';
$__LOCALE['RCUPLOAD_DPC'][5]='_fileexist;File exist. Upload to overwrite.;Υπάρχει ήδη ένα αρχείο με αυτό το όνομα. Θα το ανικαταστήσει.';
$__LOCALE['RCUPLOAD_DPC'][6]='UPLOAD_ERR_EXTENSION;Upload error extension;Αποτυχία επέκτασης εφαρμογής';
$__LOCALE['RCUPLOAD_DPC'][7]='UPLOAD_ERR_CANT_WRITE;Upload error during writing;Αποτυχία κατα την εγγραφή';
$__LOCALE['RCUPLOAD_DPC'][8]='UPLOAD_ERR_NO_TMP_DIR;Upload error, no tmp folder;Αποτυχία, δεν υπαρχει φάκελος για προσωρινή αποθήκευση';
$__LOCALE['RCUPLOAD_DPC'][9]='UPLOAD_ERR_NO_FILE;No file selected;Δεν επιλέχθηκε αρχείο';
$__LOCALE['RCUPLOAD_DPC'][10]='UPLOAD_ERR_PARTIAL;Upload error, partial upload;Αποτυχία, μερική αποστολή του αρχείου';
$__LOCALE['RCUPLOAD_DPC'][11]='UPLOAD_ERR_FORM_SIZE;Upload error, size too big;Λάθος, επιλέξτε αρχείο μικρότερο μεγέθους';
$__LOCALE['RCUPLOAD_DPC'][12]='UPLOAD_ERR_INI_SIZE;Upload error, size is too big;Λάθος, δεν επιτρέπεται τοσο μεγαλο αρχείο να μεταφορτωθεί';
$__LOCALE['RCUPLOAD_DPC'][13]='_uploadok;Success;Επιτυχώς';
$__LOCALE['RCUPLOAD_DPC'][14]='_updmoverror;Upload error, unable to move file;Λάθος, μη επιτρεπτή μεταφορά του αρχείου';
$__LOCALE['RCUPLOAD_DPC'][15]='_incobtype;Upload error, incobatible type, please select the proper file type ;Αποτυχία, μη συμβατος τύπος αρχείου. Προσπαθήστε ξανά επιλέγοντας τον σωστο τύπο αρχείου.';
$__LOCALE['RCUPLOAD_DPC'][16]='_uploadcatimage;Category image;Εικόνα κατηγορίας';
$__LOCALE['RCUPLOAD_DPC'][17]='_uploadbannerhtml;Category banner;Μπάνερ κατηγορίας';
$__LOCALE['RCUPLOAD_DPC'][18]='_uploaditemimages_small;Item small image;Εικόνα είδους (μικρή)';
$__LOCALE['RCUPLOAD_DPC'][19]='_uploaditemimages_medium;Item medium image;Εικόνα είδους (μεσαία)';
$__LOCALE['RCUPLOAD_DPC'][20]='_uploaditemimages_large;Item large image;Εικόνα είδους (μεγάλη)';
$__LOCALE['RCUPLOAD_DPC'][21]='_uploaditemimages_thub;Item image;Εικόνα είδους';
$__LOCALE['RCUPLOAD_DPC'][22]='_uploaditemgallery;Item photo gallery;Γκαλερί είδους';
$__LOCALE['RCUPLOAD_DPC'][23]='_uploaditemresources;Item resources;Συνημμένα είδους';
$__LOCALE['RCUPLOAD_DPC'][24]='_uploadimages;Images;Εικόνες';
$__LOCALE['RCUPLOAD_DPC'][25]='_fpslider;Slider images;Εικόνες Slider';
$__LOCALE['RCUPLOAD_DPC'][26]='_uploadresources;Resources;Συνημμένα';
$__LOCALE['RCUPLOAD_DPC'][27]='_uploadfiletypes;Allowed filetypes;Επιτρεπόμενοι τύποι αρχείων';
$__LOCALE['RCUPLOAD_DPC'][28]='_updmovecopyerror;Upload error, unable to move file. Copy failed.;Λάθος, μη επιτρεπτή μεταφορά του αρχείου. Αποτυχία αντιγραφής.';
$__LOCALE['RCUPLOAD_DPC'][29]='_updcopyerror;Upload error, unable to copy file;Αποτυχία αντιγραφής αρχείου';
$__LOCALE['RCUPLOAD_DPC'][30]='_updfiles;Upload files;Αρχεία';
$__LOCALE['RCUPLOAD_DPC'][31]='_unlinkfile;Delete file;Διαγραφή αρχείου';
$__LOCALE['RCUPLOAD_DPC'][32]='_unlinked;Deleted;Διαγράφτηκε';
$__LOCALE['RCUPLOAD_DPC'][33]='_CKFINDER;CkFinder;CkFinder';

class rcupload {

    var $title,$path,$post,$msg,$tpath,$urlpath,$subdir;
	var $todirectory;
	var $MAXSIZE;
	var $uploadres, $allowed_filetypes;
	var $urlbase;	
	var $dhtml;

    function rcupload($path=null,$maxsize=null) {
	
	    $this->title = localize('RCUPLOAD_DPC',getlocal());		
		$this->post = false; //hold successfull posting	
		$this->msg = null;
		$this->todirectory = null;
		$this->path = $this->path = paramload('SHELL','prpath');
		
		if (isset($maxsize))
		  $this->MAXSIZE = $maxsize;
		else
		  $this->MAXSIZE = remote_paramload('RCUPLOAD','maxsize',$this->path);//1199024;
		//echo $this->MAXSIZE,'>';
		if ($remoteuser=GetSessionParam('REMOTELOGIN')) {		
		  $this->path = paramload('SHELL','prpath')."instances/$remoteuser/";	
	      $this->subdir = remote_paramload('ID','hostinpath',$this->path."instances/$remoteuser/");			  
          $this->urlpath = paramload('SHELL','urlpath') . '/' . $this->subdir;
		  $this->urlbase = paramload('SHELL','urlbase'). '/' . $this->subdir;				  
        }
		else {	
		  $this->path = paramload('SHELL','prpath');
	      $this->subdir = remote_paramload('ID','hostinpath',$this->path);				  
          $this->urlpath = paramload('SHELL','urlpath') . '/' . $this->subdir;
		  $this->urlbase = paramload('SHELL','urlbase'). '/' . $this->subdir;		  
        }
		//echo $this->path;  
		//$this->tpath = $this->path . $path;  	 	
		if ($p=GetSessionParam("upload_subdir"))
		  $this->tpath = $this->urlpath . $p;
		else  
		  $this->tpath = $this->urlpath . $path;	
		 
		//echo $this->tpath, ' ',$p,' ',$path;  

		$this->uploadres = null;
        $this->allowed_filetypes = array('.pdf','.doc','.xls'.'.pps','.txt','.zip','.rar','.gif','.png','.jpg');

	    //dynamic html loader
	    $this->dhtml = paramload('FRONTHTMLPAGE','dhtml');
	    //if ($this->dhtml)
          // $this->js = new jscript; 			
    }
	
    function event($event=null) {
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////
	   
	   switch ($event) {
	     case 'cpdouploadadv'    : $this->uploadres = $this->upload_files(); break;	   
	     case 'cpchangeuloaddir' : $this->set_dir(); break;	   
	     case 'cpdoupload'       : $this->upload_file(); 
		                           $this->post = true;
		                           break;
		 default                 :
	   }	 
	}	
  
    function action($action=null) {

	   if (!GetReq('editmode')) {
	     if (GetSessionParam('REMOTELOGIN')) 
	       $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	     else  
           $out = setNavigator(seturl("t=cp","Control Panel"),$this->title); 	 	
	   
	   
	     $out  .= $this->uploadform(null,null,"Upload files...",1);
	   }
	   else {//edit mode
	     //$out  = $this->uploadform(null,GetReq('path'));//null,null,"Upload files...",1);
		 
		 $out  = $this->show_cmd_line();
		 $out .= $this->editmode_upload_files();
	   }	 
	  
	   return ($out);
	}  
	
	function dhtml_javascript($code) {
      if (iniload('JAVASCRIPT')) {

		   $js = new jscript;		   
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }		
	}			
	
    function uploadform($action=null,$path=null,$title=null,$list=null,$maxsize=null) {  
	      if ($id = GetParam('id'))
		    $myfilename = $id;
	    
		  if ($maxsize) $this->MAXSIZE = $maxsize;
	      //echo $this->maxsize;
		  
	      if ($this->post==true) {	   
	   
	        $swin = new window("",$this->msg);
	        $winout = $swin->render("center::70%::0::group_article_body::center::0::0::");	
	        unset ($swin);
	      }		
          
		  if ($action)
		    $filename = seturl("t=".$action.'&editmode='.GetReq('editmode'));		
		  else {	
		    if (GetReq('editmode')) 
			  $filename = seturl("t=cpdoupload&editmode=1");		
			else
              $filename = seturl("t=cpdoupload");		
		  }	
		
	      //upload file(s) form
          $out  = "<FORM action=". "$filename" . " method=post ENCTYPE=\"multipart/form-data\" class=\"thin\">";
          $out .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" VALUE=\"".$this->MAXSIZE."\">"; //max file size option in bytes
          $out .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>"; 			 	   
          $out .= "Upload: <input type=FILE name=\"uploadfile\">";		    
		  
          $out .= "<input type=\"hidden\" name=\"FormName\" value=\"Cpdoupload\">"; 	
		  
		  if ($action)
		    $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"$action\">&nbsp;";		
		  else
            $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"cpdoupload\">&nbsp;";		
			
	      if ((isset($path)) && ($path!='reset')) {
             $out .= "<input type=\"hidden\" name=\"topath\" value=\"$path\">";		
          }
		  if ($myfilename) {//get id (without extension)  products attachments upload
		     $out .= "<input type=\"hidden\" name=\"myfilename\" value=\"$myfilename\">";
		  }
			
          $out .= "<input type=\"submit\" name=\"Submit2\" value=\"Upload\">";		
          $out .= "</FONT></FORM>"; 
		  
		  if ($list) $out .= $this->read_dir_list();		  	
		
		  $wina = new window($title,$out);
		  $winout .= $wina->render();//"center::100%::0::group_dir_title::right::0::0::");
		  unset ($wina);		  		  
		
		  return ($winout);
    }
  
    function upload_file($myfilename=null, $mypath=null, $isrootpath=null) {
	     $topath = $mypath ? $mypath :GetParam("topath");
		 $myfilename = $myfilename?$myfilename:GetParam('myfilename');
	   
	     //echo $myfilename,'>';
	     if ($_FILES['uploadfile'])  {

          $attachedfile = $_FILES['uploadfile'];
		  //print_r($attachedfile);
		  
		  if ($myfilename) {
		    if (stristr($myfilename,'.')) //has extension
		      $filename = $myfilename;
			else {//copy extension to item id
			  $pf = explode('.',$attachedfile['name']);
			  $ext = array_pop($pf); ///get last
			  $lan = getlocal()?getlocal():'0'; //languange ..if attachment
			  $filename = $myfilename . $lan . '.' . $ext;
			}  
		  }	
		  else	
		    $filename = $attachedfile['name'];					   
		
		  if ($pp=$topath!='reset')  {//param at upload form   
		    $myfilepath = $this->tpath . '/'. $topath . "/" . $filename;
		  }	
		  else {
		    if ($isrootpath)
			  $myfilepath = $this->topath . "/" . $filename;
			else
		      $myfilepath = $this->tpath . "/" . $filename;
		  }	
			
          //echo $myfilepath;
		  //copy it to admin write-enabled directory			
		  	   
		  //if (@copy($attachedfile['tmp_name'],$myfilepath)) {
		  if (move_uploaded_file($attachedfile['tmp_name'],$myfilepath)) { 
		
    	   setInfo("Upload " .$attachedfile['name']. " ok!");
		   //$this->msg = "Upload " .$attachedfile['name']. " ok!"; //MEANS ERROR
		   //echo $msg;
		   if (GetReq('editmode')) 
		    die("Upload " .$attachedfile['name']. ": success!");
		  
		  }
		  else {
		
		   setInfo("Failed to upload file " . $attachedfile['name'] . " ! (Size Error?)"); 				   
		   $this->msg = "Failed to upload file " . $attachedfile['name'] . " ! (Size Error?)";
		  }
		
		  $this->post = true;
        }
		else
		  $this->post = false;
		   
		return ($this->msg);  
	} 
	
	/*function read_dir_list() {
	
	    if (is_dir($this->path)) {
		
	      $toprint = "To directory :"; 
	      $toprint .= "<select name=\"todir\">";	
		  $toprint .= "<option value=\"\">---Select a directory---</option>";		
		
          $mydir = dir($this->path);
		  //echo $this->path;
          while ($fileread = $mydir->read ()) {
		     //echo $fileread;
		     if (($fileread!='.') && ($fileread!='..')) {
			   //echo $fileread;	   
			   if (is_dir($this->path.$fileread))
			     $toprint .= "<option value=\"$fileread\">$fileread</option>";
		     }	
		  }	
		  
	      $toprint .= "</select>";		   
		}
		return ($toprint);  
	}	*/
	
	function read_dir_list() {
	
		//echo $this->tpath;
		//echo '>',GetSessionParam('upload_subdir');
		  	
	    if (is_dir($this->tpath)) {
		
          $filename = seturl("t=cpchangeuloaddir");		
		
          $out  = "<FORM action=". "$filename" . " method=post class=\"thin\">";
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
		  
			     $out .= "<option value=\"reset\">---Home              ---</option>";
	      $out .= "</select>";	
		  
          $out .= "<input type=\"hidden\" name=\"FormName\" value=\"Cpchangeuloaddir\">"; 	
          $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"cpchangeuloaddir\">&nbsp;";			
			
          $out .= "<input type=\"submit\" name=\"Submit2\" value=\"Ok\">";		
          $out .= "</FONT></FORM>"; 			  	   
		}
		return ($out);  
	}
	
	function set_dir() {
  
                    if (GetParam('todir')=='reset') {
                      $this->tpath = $this->urlpath;
                      SetSessionParam('upload_subdir',null);
                      return;	    
                    }

	    if ($subdir = GetParam('todir')) {
		  if ($psubdir=GetSessionParam('upload_subdir')) {
		    $this->tpath = $this->urlpath . $psubdir . "/" . $subdir;
		    SetSessionParam('upload_subdir',$psubdir . "/" . $subdir);		  
		  }
		  else {
		    $this->tpath = $this->urlpath .$subdir;
		    SetSessionParam('upload_subdir',$subdir);
	      }		
		}  
		else {
		  $this->tpath = $this->urlpath . '/';
		  SetSessionParam('upload_subdir',null);
		}  
		    
	}	
	
	
  function replicate_file_to($path=null,$myfilename=null) {
  
    if (isset($myfilename)) {
		  $filename = $myfilename;
    }  
	elseif ($_FILES['uploadfile'])  {

          $attachedfile = $_FILES['uploadfile'];
		  $filename = $attachedfile['name'];			
	}
	
	$myreplicatedfile = $path . $filename;  
    
	if (isset($filename)) {  
	
	    //if ($path) 
		  //  $myfilepath = $path . "/" . $filename;
		//else
		if (GetParam("topath")) //param at upload form   
		    $myfilepath = $this->tpath . GetParam("topath") . "/" . $filename;
		else
		    $myfilepath = $this->path . $filename;
			  
        //echo $myfilepath,"<br>aaa<br>",$myreplicatedfile,"<br>";
        if (@copy($myfilepath,$myreplicatedfile)) {
		
    	   setInfo("Replicate " .$filename . " ok!");
		   //$this->msg = "Upload " .$filename . " ok!"; MEANS ERROR
		}
		else {
		   //echo $myfilepath;
		   setInfo("Failed to replicate file " . $filename); 				   
		   $this->msg = "Failed to replicate file " . $filename;
		}
	 }
	 else
	   $this->msg = "Filename not found!";
	   
	 return ($this->msg);  	
  }	

function advanced_uploadform($title=null,$upfiles=null,$atoz=null,$path=null,$private_area=null,$copytopath=null) { 
	      $lan = getlocal();
		  $ispriv = $private_area?$private_area:0;		
          $upath = $path?$path:'cp/uploads';		  
	      $id = GetReq('id');//item			  
	      $cat = GetReq('cat');//cat			  
		  
		  if ((is_array($upfiles)) && (!empty($upfiles))) {
		    $ufiles = (array) $upfiles;
		  }	
		  elseif (is_int($upfiles)) {//numeric no named
		    for ($i=0;$i<$upfiles;$i++)
			  $ufiles[] = 'noname'.$i;
		  } 				  
		  elseif ($upfiles) {//named with .ext (or without)
		    if (is_int($atoz)) {
		      for ($i=0;$i<$atoz;$i++)
			    $ufiles[] = strstr($upfiles,'.')?str_replace('.',$i.'.',$upfiles):$upfiles.$i;			
			}
		    elseif ($atoz) {
		      for ($i='A';$i<=$atoz;$i++)
			    $ufiles[] = strstr($upfiles,'.')?str_replace('.',$i.'.',$upfiles):$upfiles.$i;		  
			}  
			else
			  $ufiles[] = $upfiles; //one file
	      }
		  elseif (!$upfiles)  {//numeric no named based to A..Z
		    if (is_int($atoz)) {
		      for ($i=0;$i<$atoz;$i++)
			    $ufiles[] = 'noname'.$i;			
			}
		    elseif ($atoz) {
		      for ($i='A';$i<=$atoz;$i++)
			    $ufiles[] = 'noname'.$i;
			}  
			else
			  $ufiles[] = 'noname'; //one file ..noname
		  } 		  
          else	//empty...	test  
            //$ufiles = array('headimg01.jpg','headimg02.jpg','headimg03.jpg','headimg04.jpg','headimg05.jpg');		  
			return ('incorrect parameters');
		  
		  //print_r($ufiles);
		  
	      if ($this->post==true) {	   
	   
	        $swin = new window("",$this->msg);
	        $winout = $swin->render("center::70%::0::group_article_body::center::0::0::");	
	        unset ($swin);
	      }		
          
		  if (GetReq('editmode')) 
			$filename = seturl("t=cpdouploadadv&id=$id&cat=$cat&editmode=1");		
		  else
            $filename = seturl("t=cpdouploadadv");			
		
	      //upload file(s) form
          $out  = "<FORM action=". "$filename" . " method=post ENCTYPE=\"multipart/form-data\" class=\"thin\">";
		  $out .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" VALUE=\"".$this->MAXSIZE."\">"; //max file size option in bytes
		  $out .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>"; 			 	   
		  
		  foreach ($ufiles as $i=>$file) {
              $myfile = str_replace('.','|',$file);//replace . |
			  
		      if ($file) {
                $fout =  localize('_uploadfile',$lan) . ":<input type=FILE name=\"$myfile\">";
				if (GetParam($myfile.'_is_private'))
				  $check = 'checked';
				else  
                  $check = $ispriv ? 'checked' : null;
				  
	            $fout .= localize('_isprivate',$lan) . ":<input type=\"checkbox\" name=\"".$myfile."_is_private\"". $check . ">";
				
                if (($this->post==true) && ($title==GetParam('FormName'))) { //4 is not error ..not a selected file in form
				
				  switch ($this->uploadres[$myfile]) {
				    case 8 : $e = localize("UPLOAD_ERR_EXTENSION",$lan); break;
				    case 7 : $e = localize("UPLOAD_ERR_CANT_WRITE",$lan); break;
					case 6 : $e = localize("UPLOAD_ERR_NO_TMP_DIR",$lan); break;
				    case 4 : $e = localize("UPLOAD_ERR_NO_FILE",$lan); break; //is not an error...
					case 3 : $e = localize("UPLOAD_ERR_PARTIAL",$lan); break;
					case 2 : $e = localize("UPLOAD_ERR_FORM_SIZE",$lan); break;
					case 1 : $e = localize("UPLOAD_ERR_INI_SIZE",$lan); break;
					case '_error'           : $e = localize("_updmoverror",$lan); break;
					case '_error_copyerror' : $e = localize("_updmovecopyerror",$lan); break; //copy error, upload error					
					case '_incobatibletype' : $e = localize("_incobtype",$lan); break;					
					default: if (stristr($this->uploadres[$myfile],'_copyerror')) //error copying file to an other location
					           $e = localize('_updcopyerror',$lan);
							 else
					           $e = localize('_uploadok',$lan);
				  }
				
				  $subtitle = '<b>'.localize('_result',$lan) .':'. $e .'</b>';
                }		
				
                $fout .= $this->show_uploaded_file($file,$upath); 				
				
                if (stristr($file,'noname')) //has no target name				
				  $fout .= "<input type=\"hidden\" name=\"".$myfile."_name_is\" value=\"\">"; //empty
				else
                  $fout .= "<input type=\"hidden\" name=\"".$myfile."_name_is\" value=\"$myfile\">";				
				  
				$fout .= "<input type=\"hidden\" name=\"".$myfile."_path_is\" value=\"$upath\">"; 
				
				if ($copytopath) //replicate
				  $fout .= "<input type=\"hidden\" name=\"".$myfile."_copy_to\" value=\"$copytopath\">";
				
				if (stristr($file,'noname'))
				  $myfiletitle = localize('_uploadfiletypes',$lan) . ':' . implode(',',$this->allowed_filetypes);
				else
                  $myfiletitle = $file;		
				  
		        $wina = new window($myfiletitle.':'.$subtitle,$fout);
		        $out .= $wina->render();//"center::100%::0::group_dir_title::right::0::0::");
		        unset ($wina);				  
			  }
		  }
		  
          $out .= "<input type=\"hidden\" name=\"FormName\" value=\"$title\">"; 				
          $out .= "<input type=\"submit\" name=\"Submit2\" value=\"".localize('_upload',$lan)."\">";		
          $out .= "</FONT></FORM>"; 
		  	  	
		
		  $wina = new window('<h2>'.$title.'</h2>',$out);
		  $winout .= $wina->render();//"center::100%::0::group_dir_title::right::0::0::");
		  unset ($wina);		  		  
		
		  return ($winout);
    }  
	
	function show_uploaded_file($file=null,$path=null) {
	  if ($file) {
	    $mf = explode ('.',$file);
	  
	    if ($mf[1]) {//has extension
		  $out = $this->pick_file($file,$path);
	    }
		else {//no ext search for filetypes
		
		  foreach ($this->allowed_filetypes as $ext) {
		    $myfile = $file . $ext;
			if ($out = $this->pick_file($myfile,$path))
			  return ($out);
		  }
		}
		
	  }
      return ($out);	  
	}
	
	function pick_file($file=null,$upath=null) {
	  $lan = getlocal();
	  $id = GetReq('id');
	  $cat = GetReq('cat');	
	  $fout = null;
	  
	  //echo $upath,'<br>';
	
	  if ($file) {
	
				if (is_readable($this->urlpath.'/'.$upath.'/'.$file)) {
				  $fout .= localize('_fileexist',$lan) . '&nbsp;';
				  
				  $f = explode('.',$file);
				  $typef = array_pop($f);
	              $my_icon = '/images/icon_'.$typef.'.png';	
	              $icon = "<img src=\"" . $my_icon . "\"  width=\"25\" height=\"25\" border=\"0\">";  
                  
	              if ((defined('SHDOWNLOAD_DPC')) && ($id)) {	
	                if (GetGlobal('controller')->calldpc_var("shdownload.direct"))
		              $fout .= seturl("t=download&cat=$cat&id=".$id,$icon);//???? to be direct link 
		            else  
	                  $fout .= seturl("t=download&cat=$cat&id=".$id,$icon);
	              }
	              else {
		           $downloadfile = $this->urlbase . '/'. $upath .'/'.$file;					 
		           $fout .= "<A href=\"$downloadfile\" target='_blanc'>" . $icon . "</A>";		
	              }	
				  
				  //delete file link
				  $fout .= '&nbsp;' . $this->delete_uploaded_file($upath.'/'.$file); //link to unlink
                }
      }
      return ($fout); 	  
	}
	
	function delete_uploaded_file($source=null) {
	   $lan = getlocal();
	   //echo $this->urlpath . '/' . GetReq('source'),'<br>';
	   //echo $source,'<br>';
	   
	   if (($source==GetReq('source')) && (is_readable($this->urlpath . '/' . $source))) {
	     //echo 'unlink:',$source;
		 
		 @unlink(str_replace('//','/',$this->urlpath . '/' . $source));
		 $ret = localize('_unlinked',$lan);
		 $this->post = true;
	   }  	   
	   elseif ($source) {
	     $ret = seturl('t=cpupload&source='.$source.'&id='.GetReq('id').'&cat='.GetReq('cat').'&editmode=1',localize('_unlinkfile',$lan));
	   }
	   else {
	     $ret = 'Invalid source'; 
	   }
	   
	   return ($ret);
	}		
	
    function upload_files($debug=null) {
      
      if ($debug) {	  
	    echo '<pre>';
	    print_r($_FILES);
	    echo '</pre>';
	    print_r($_POST);
	  }	 
		 
	  foreach ($_FILES as $key=>$file) {
				
	    $mykey= str_replace('|','.',$key);//replace |

	    $b = explode('.',$file['name']);
		$source_extension = strtolower(array_pop($b));	//beware of uppercase extensions (NOT THE FILE, A,B,C,D,...)		
		
        if ((strstr($mykey,'.')) && ($targetname = str_replace('|','.',GetParam($key . '_name_is')))) {		
          $a = explode('.',$targetname);
          $target_extension = strtolower(array_pop($a)); //beware of uppercase extensions (NOT THE FILE, A,B,C,D,...)	
		}
		elseif (GetParam($key . '_name_is')) {//no extension
		  $target_extension = strtolower($source_extension);//copy source extension
		  $targetname = GetParam($key . '_name_is') . '.' . $source_extension; 	  
		}  
		
		$targetpath = GetParam($key . '_path_is');

		   
		if ($debug) 
		  echo $targetname,$key, $source_extension, $target_extension, $file['error'],$file['name'],$file['type'],'<br>';
		   
		if ($file['error']==0) {
		
		   
		     if (GetParam($key.'_is_private')) {
			   if ($debug) echo 'private:';
			   
			   switch ($file['type']) {
			     case 'text/html'         : 
				 case 'text/plain'        :$path2save = $targetpath ? $this->urlpath .'/'. $targetpath : $this->urlpath .'/cp/uploads/';
				                           $name2save = $targetname ? $targetname: strtolower($file['name']); 
				                     break; 
			     case 'video/x-ms-wm'     :
				 case 'application/x-rar-compressed':
				 case 'application/zip'   :
				 case 'application/vnd.ms-excel':			   
			     case 'application/msword':
				 case 'application/vnd.ms-powerpoint':
			     case 'application/pdf':$$path2save = $targetpath? $this->urlpath .'/'. $targetpath : $this->urlpath .'/cp/uploads/';
				                        $name2save = $targetname ? $targetname:  strtolower($file['name']); 
				                     break;  
			     case 'image/gif'  : 
			     case 'image/png'  : 
			     case 'image/jpeg' : $path2save = $targetpath ? $this->urlpath .'/'. $targetpath : $this->urlpath .'/cp/uploads/';
				                     $name2save = $targetname ? $targetname:  strtolower($file['name']); 
				                     break;
				 default           : $path2save = $this->urlpath . '/cp/uploads/';
                                     $name2save = $targetname? $targetname:  strtolower($file['name']);				 
			   }
			 }
			 else {
			   switch ($file['type']) {
			     case 'text/html'         :
				 case 'text/plain'        :	$path2save = $targetpath ? $this->urlpath .'/'. $targetpath : $this->urlpath .'/';
				                            $name2save = $targetname ? $targetname:  strtolower($file['name']); 
				                     break; 		     
			     case 'video/x-ms-wm'     :
				 case 'application/x-rar-compressed':
				 case 'application/zip'   :
				 case 'application/vnd.ms-excel':
			     case 'application/msword':
				 case 'application/vnd.ms-powerpoint':			   
			     case 'application/pdf':$path2save = $targetpath ? $this->urlpath .'/'. $targetpath : $this->urlpath .'/';
				                        $name2save = $targetname ? $targetname:  strtolower($file['name']); 
				                     break;  			   
			     case 'image/gif'  : 
			     case 'image/png'  : 
			     case 'image/jpeg' : $path2save = $targetpath ? $this->urlpath .'/'. $targetpath : $this->urlpath .'/images/';
				                     $name2save = $targetname ? $targetname:  strtolower($file['name']); 
				                     break;
				 default           : $path2save = $targetpath ? $this->urlpath .'/'. $targetpath : $this->urlpath .'/';
                                     $name2save = $targetname ? $targetname:  strtolower($file['name']);				 
			   }			 
			 }
			 
		     if (($targetname) && ($source_extension==$target_extension)) { 
			 
			   if ($copytopath = GetParam($key . '_copy_to')) {
			   
			      $copyto = $this->urlpath .'/'. $copytopath; 
				  if ($debug) echo 'copy to' , $copyto,'/',$name2save,'<br>';
				  
                  if (@copy($file['tmp_name'],$copyto.'/'.$name2save))	
			        $copyres = null;//'_ok';
			      else	 
			        $copyres = '_copyerror';					  
			   } 
			   
			   if ($debug) echo 'move to' , $path2save,'/',$name2save,'<br>';
			   
			   if (@move_uploaded_file($file['tmp_name'],$path2save.'/'.$name2save)) 
			     $res[$key] = $targetpath . $copyres;//'_ok';
			   else	 
			     $res[$key] = '_error' . $copyres;				 
             }		
		     else {
		       if (!$targetname) {
		         $path2save = $targetpath ? $this->urlpath .'/'. $targetpath : $this->urlpath .'/cp/uploads/';
			     $name2save = strtolower($file['name']);
				 
			     if ($copytopath = GetParam($key . '_copy_to')) {
				 
			       $copyto = $this->urlpath .'/'. $copytopath; 
				   if ($debug) echo 'copy to' , $copyto,'/',$name2save,'<br>';
				   
                   if (@copy($file['tmp_name'],$copyto.'/'.$name2save)) 	
			         $copyres = null;//'_ok';	 
			       else	 
			         $copyres = '_copyerror';					  
			     } 
				 
			     if ($debug) echo 'move to' , $path2save,'/',$name2save,'<br>';
				 
			     if (@move_uploaded_file($file['tmp_name'],$path2save.'/'.$name2save)) 
			       $res[$key] = GetParam($key . '_path_is');//'_ok';
			     else	 
			       $res[$key] = '_error';			  
		       }
		       else			 
		         $res[$key] = '_incobatibletype';
			 } 
        }
        else 
		    $res[$key] = $file['error'];
		
		
		   
	  }//foreach
		 
	  if ($debug) print_r($res);
	  $this->post = true;
		 
	  return ($res); 
	} 	
	
	function editmode_upload_files() {  
	  $id = GetReq('id');//item	
	  $ufiles = array('headimg01.jpg','headimg02.jpg','headimg03.jpg','headimg04.jpg','headimg05.jpg');	//frontpage slider images	  
	  $files = array('aaa.jpg','bbb.gif','ccc.png');//test	  
	  $cat = GetReq('cat');//category
	  $pcat = explode('^',$cat);
	  if ($mycat=array_pop($pcat))
	    $mycurcat = $mycat;
	  else
        $mycurcat = $cat;		  
	  
	  if ($cat) {
	     if (defined('RCKATEGORIES_DPC')) {
		   $restype = GetGlobal('controller')->calldpc_var("rckategories.restype");
		   
		   if ($catimage = GetGlobal('controller')->calldpc_var("rckategories.showcatimagepath")) {
             $out .= $this->advanced_uploadform(localize('_uploadcatimage',$lan),$mycurcat.$restype,null,$catimage,0); //category image
             $out .= '<hr>'; 		 
		   }		   
		   if ($catbanner = GetGlobal('controller')->calldpc_var("rckategories.showcatbannerpath")) {
             $out .= $this->advanced_uploadform(localize('_uploadbannerhtml',$lan),$mycurcat.'.htm',null,$catbanner,0); //category html
             $out .= '<hr>'; 		 
		   }		   
		 }
		 //common images
         $out .= $this->advanced_uploadform(localize('_uploadimages',$lan),null,6,'images',0);	//general image upload		 
	  }
	  elseif ($id) {	
         if (defined('RCITEMS_DPC')) {
		   $restype = GetGlobal('controller')->calldpc_var("rcitems.restype");
		   
           if ($img_small = GetGlobal('controller')->calldpc_var("rcitems.img_small")) {//small sized photo
             $out .= $this->advanced_uploadform(localize('_uploaditemimages_small',$lan),$id.$restype,null,$img_small,0); 			 
             $out .= '<hr>'; 	
		   }	 
		   if ($img_medium= GetGlobal('controller')->calldpc_var("rcitems.img_medium")) {//medium sized photo	 
             $out .= $this->advanced_uploadform(localize('_uploaditemimages_medium',$lan),$id.$restype,null,$img_medium,0); 			 
             $out .= '<hr>'; 
		   }	 
		   if ($img_large = GetGlobal('controller')->calldpc_var("rcitems.img_large")) {//large sized photo	 
             $out .= $this->advanced_uploadform(localize('_uploaditemimages_large',$lan),$id.$restype,null,$img_large,0); 			 
             $out .= '<hr>'; 		   
           }
		   //in case of no large..no 3type of img ..one img..thub
           elseif ($res_path = GetGlobal('controller')->calldpc_var("rcitems.ptp")) {//one photo
             $out .= $this->advanced_uploadform(localize('_uploaditemimages_thub',$lan),$id.$restype,null,$res_path,0); //thub			 
             $out .= '<hr>'; 	  
		   }
			 		 
           $out .= $this->advanced_uploadform(localize('_uploaditemgallery',$lan),$id.$restype,'Y','images/uphotos',0); //gallery A to Z		 
           $out .= '<hr>'; 		   
           $out .= $this->advanced_uploadform(localize('_uploaditemresources',$lan),$id/*.$restype*/,'D','images/uphotos',0,'cp/html'); //html folder in cp	 
           $out .= '<hr>'; 		   
		 }
		 //common resources
		 $out .= $this->advanced_uploadform(localize('_updfiles',$lan),null,6,null,0);	
		 
	  }
	  else {
	     //slider images
		 $out .= $this->advanced_uploadform(localize('_fpslider',$lan),$ufiles,null,'images',0);
         $out .= '<hr>'; 		 
         $out .= $this->advanced_uploadform(localize('_uploadimages',$lan),null,6,'images',0);		 
         $out .= '<hr>'; 		 
         $out .= $this->advanced_uploadform(localize('_uploadresources',$lan),null,6,null,1);	//cp/uploads
	 
		 //$out .= $this->advanced_uploadform('TEST',$ufiles,null,null,1);

		 //$out .= $this->advanced_uploadform('TEST 1',$files,null,null,1);
		 //$out .= $this->advanced_uploadform('TEST 2',null,3,null,1);	  
	  }	 
	  
	  return ($out); 
	}
	
	function show_cmd_line() {
	
	    if (is_readable($this->path .'ckfinder/ckfinder.html'))
	      $ckfinder = "<a href=\"ckfinder/ckfinder.html\" target=\"_blanc\">Ckfinder</a>";
	
	    //$commands = $ckfinder;
	
	   	//goto ckfinder.....
        //$ckfinder = "http://yoki1.stereobit.gr/cp/ckfinder/ckfinder.html";//?type=Images&CKEditor=mail_text&CKEditorFuncNum=2&langCode=el";		
	   
	    if ($this->dhtml) {
		   if ($ckfinder) {
				$link1 = 'ckfinder/ckfinder.html';//seturl("t=cpviewsubsqueue&editmode=".GetReq('editmode'));
				$title1 = localize('_CKFINDER',getlocal());	   
	   
				$this->dhtml_javascript("function openlink1(){ 
ajaxwin=dhtmlwindow.open(\"ckfinder\", \"iframe\", \"$link1\", \"$title1\", \"width=640px,height=480px,left=300px,top=100px,resize=1,scrolling=1\")
//ajaxwin.onclose=function(){return window.confirm(\"Close window ?\")} 
}");

				$commands.= '&nbsp;|&nbsp;';
				$commands .= "<a href=\"#\" onClick=\"openlink1(); return false\">".$title1."</a>";
		   
		   }
		   /*
		   $ajaxlink2 = seturl("t=cpadvsubscribe&editmode=".GetReq('editmode'));			   
           $title2 = localize('_MASSSUBSCRIBE',getlocal());		
		   
		  $this->dhtml_javascript("function openlink2(){ 
ajaxwin2=dhtmlwindow.open(\"ajaxbox2\", \"ajax\", \"$ajaxlink2\", \"$title2\", \"width=450px,height=300px,left=300px,top=100px,resize=1,scrolling=1\")
//ajaxwin2.onclose=function(){return window.confirm(\"Close window ?\")} 
}");
		   
		   //$commands.= "<a href=\"#\" onClick=\"openlink2(); return false\">".$title2."</a>";
		   
		   //DIV....
		   
		   $this->dhtml_javascript("function openlink3(){
divwin=dhtmlwindow.open('divbox', 'div', 'sitems', 'Select Items', 'width=450px,height=300px,left=200px,top=150px,resize=1,scrolling=1'); 
divwin.onclose=function(){return window.confirm(\"Close window ?\")} 
}");		
           $commands.= '&nbsp;|&nbsp;';  
           $commands.= "<a href=\"#\" onClick=\"openlink3(); return false\">"."Select Items"."</a>";

		   $this->dhtml_javascript("function openlink4(){
divwin2=dhtmlwindow.open('divbox2', 'div', 'mailqueue', 'Mail queue', 'width=450px,height=300px,left=200px,top=150px,resize=1,scrolling=1'); 
divwin2.onclose=function(){return window.confirm(\"Close window ?\")} 
}");		
           $commands.= '&nbsp;|&nbsp;';  
           $commands.= "<a href=\"#\" onClick=\"openlink4(); return false\">"."Mail Queue"."</a>";			   
		   
		   $this->dhtml_javascript("function openlink5(){
divwin2=dhtmlwindow.open('divbox3', 'div', 'foptions', 'Options', 'width=450px,height=300px,left=200px,top=150px,resize=1,scrolling=1'); 
divwin2.onclose=function(){return window.confirm(\"Close window ?\")} 
}");		
           $commands.= '&nbsp;|&nbsp;';  
           $commands.= "<a href=\"#\" onClick=\"openlink5(); return false\">"."Options"."</a>";
        */   		   
	    }
	    /*else
	       $commands = seturl("t=cpviewsubsqueue&editmode=".GetReq('editmode'),localize('_MAILCAMPAIGNS',getlocal())) . '|'.  
	                   seturl("t=cpadvsubscribe&editmode=".GetReq('editmode'),localize('_MASSSUBSCRIBE',getlocal()));
	    
	    */
	    if ($commands) {
	      $myadd = new window('',$commands);
	      $out .= $myadd->render("center::100%::0::group_article_selected::right::0::0::");	   
	      unset ($myadd); 
        }	   
	   
	    return ($out);
    }	   
  
};
}
?>