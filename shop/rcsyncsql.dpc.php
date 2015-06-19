<?php
$__DPCSEC['RCSYNCSQL_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCSYNCSQL_DPC")) && (seclevel('RCSYNCSQL_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCSYNCSQL_DPC",true);

$__DPC['RCSYNCSQL_DPC'] = 'rcsyncsql';

$d = GetGlobal('controller')->require_dpc('shop/rcimportdb.dpc.php');
require_once($d);
 
$__EVENTS['RCSYNCSQL_DPC'][0]='cpsyncsql';
$__EVENTS['RCSYNCSQL_DPC'][1]='cpdosync';
$__EVENTS['RCSYNCSQL_DPC'][3]= "cpdoloaddb";
$__EVENTS['RCSYNCSQL_DPC'][4]= "cpdodatedb";
$__EVENTS['RCSYNCSQL_DPC'][5]= "cpexesqlerr";

$__ACTIONS['RCSYNCSQL_DPC'][0]='cpsyncsql';
$__ACTIONS['RCSYNCSQL_DPC'][1]='cpdosync';
$__ACTIONS['RCSYNCSQL_DPC'][3]= "cpdoloaddb";
$__ACTIONS['RCSYNCSQL_DPC'][4]= "cpdodatedb";
$__ACTIONS['RCSYNCSQL_DPC'][5]= "cpexesqlerr";

$__DPCATTR['RCSYNCSQL_DPC']['cpsyncsql'] = 'cpsyncsql,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCSYNCSQL_DPC'][0]='RCSYNCSQL_DPC;Sync SQL;Sync SQL';
$__LOCALE['RCSYNCSQL_DPC'][1]='_RCIMPORTFILE;Import SQL file;Import SQL file';
$__LOCALE['RCSYNCSQL_DPC'][2]='RCIMPORTDB_DPC;Import file;Import file';

class rcsyncsql extends rcimportdb {

   var $address, $title, $path, $result, $syncfiles;
   var $current_batch_records;
   var $resource_path, $target_path, $resourcetypes;   

   function rcsyncsql() {
   
     rcimportdb::rcimportdb();
   
	 $this->title = localize('RCSYNCSQL_DPC',getlocal());   
	 
	 if ($remoteuser=GetSessionParam('REMOTELOGIN')) 
	    $this->path = paramload('SHELL','prpath')."instances/$remoteuser/";	
	 else
	    $this->path = paramload('SHELL','prpath');	
		
	 $this->urlpath = paramload('SHELL','urlpath') .'/'. paramload('ID','hostinpath');		
			 
     $this->address = remote_paramload('RCSYNCSQL','url',$this->path);
     $this->syncfiles = remote_arrayload('RCSYNCSQL','files',$this->path);	 
	 
	 //override maxsize 
	 $ms = remote_paramload('RCSYNCSQL','uploadsize',$this->path);
	 $this->maxsize = $ms?$ms:1000000;//$this->MAXSIZE;			                  
	 //echo $ms,'>'; 
	 
	 $attresource = remote_paramload('RCSYNCSQL','attachresourcepath',$this->path);
     $this->resource_path = $attresource?$attresource:$this->address;					  
	 $this->target_path = remote_paramload('RCSYNCSQL','attachtargetpath',$this->path);		
	 $this->resourcetypes = remote_arrayload('RCSYNCSQL','attachtypes',$this->path);
	 			 
	 $this->result = null;	 
	 
   }
   
    function event($sAction) {
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////		

       $sFormErr = GetGlobal('sFormErr');	  	    		  			    
  
       if (!$sFormErr) {   
  
	   switch ($sAction) {
	    case "cpexesqlerr" : set_time_limit(0);
		                    $this->msg = $this->sync_upload_sql_file('syncsql.err',null,'cpexesqlerr'); 
		                    $this->post = true;
							set_time_limit(50);
		                    break; 	   	
	    case "cpdodatedb" : set_time_limit(0);
		                    $this->msg = $this->sync_upload_sql_file(); 
		                    $this->post = true;
							set_time_limit(50);
		                    break;    
	    case "cpdoloaddb" : $this->upload_sql_file(); 
		                    $this->post = true;
		                    break;
		case "cpsyncsql"  : break;
		case "cpdosync"   : set_time_limit(0);
		                    $this->result = $this->sync_sql();
		                    $this->post = true;
							set_time_limit(50);
		                    break;
       }
      }   
    }
  
    function action($action) {

	 $out = $this->syncform();
	 
	 return ($out);
    }
	
  function syncform() {
     $sFormErr = GetGlobal('sFormErr');
	 
	 if (!GetReq('editmode')) {
	   if (GetSessionParam('REMOTELOGIN')) 
	     $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	   else  
         $out = setNavigator(seturl("t=cp","Control Panel"),$this->title); 	 
	 }  	 
	 	 
	 if ($this->post==true) {   	   	   
	   
	   if (($_POST['FormAction']=='cpdosync')||(GetReq('t')=='cpdosync')) {
	   
	     if (($b=GetParam('batch')) && ($this->current_batch_records==$b)) {
		 
		   $msg = $this->result . "<br>Sync continue...";
		   
	       $swin = new window("Sync SQL",$msg);
	       $out .= $swin->render("center::100%::0::group_win_body::left::0::0::");	
	       unset ($swin);
		   		   
		   $out .= $this->form_next_sync_batch("Batch Sync ...",'cpdosync','100%');	   
		 }  
		 else {	
	       $msg = $this->result . "<br>Sync submited!";
	   
	       $swin = new window("Sync SQL",$msg);
	       $out .= $swin->render("center::100%::0::group_win_body::left::0::0::");	
	       unset ($swin);
		   
    	   $out .= $this->form_error_file(); //errors form at end 		 
    	   $out .= $this->form(); //form at end 
		 }  		 
	   }
	   elseif (($_POST['FormAction']=='cpdoloaddb')||(GetReq('t')=='cpdoloaddb')) {
	     //upload 2nd step
		 $out .= $this->msg; 
         $out .= $this->load_import_scenario("Execute SQL file...",'cpdodatedb','100%');	   
	   }
	   elseif (($_POST['FormAction']=='cpdodatedb')||(GetReq('t')=='cpdodatedb')) {
	     //upload 3nd step
		 $out .= $this->msg; //sql message	 
		 if (($b=GetParam('batch')) && ($this->current_batch_records==$b)) {
		   $out .= $this->form_next_batch("Batch SQL file...",'cpdodatedb','100%');	   
		 }  
		 else {		 
		   //?????????
		   //$out .= $this->form_next_file("Next SQL file...",'cpdodatedb','100%');	//next fuploaded file
    	   $out .= $this->form_error_file(); //errors form at end 		   	 
    	   $out .= $this->form(); //form at end 
		 }  
	   }
	   elseif (($_POST['FormAction']=='cpexesqlerr')||(GetReq('t')=='cpexesqlerr')) {
	     //upload 3nd step
		 $out .= $this->msg; //sql message	 
		 if (($b=GetParam('batch')) && ($this->current_batch_records==$b)) {
		   $out .= $this->form_next_batch("Batch SQL file...",'cpexesqlerr','100%');	   
		 }  
		 else {		 		   	 
    	   $out .= $this->form(); //form at end 
		 }  
	   }	   
	 }	 
	 else { //show the form plus error if any
	 	 
       $out .= setError($sFormErr . $this->msg);
       $out .= $this->form_error_file(); //errors form at end 	   
	   $out .= $this->form();

	   //$form->checkform();	   
	 }
 
     return ($out);
  } 
  
  function form($file=null,$cmd=null,$msg=null) {
       $myaction = $cmd?seturl("t=".$cmd):seturl("t=cpdosync");  
	   $mycmd = $cmd?$cmd:"cpdosync";
	   $mymsg = $msg?$msg:"Remote SQL File Options.";
  
	   $form = new form(localize('_RCSYNCSQL',getlocal()), "RCSYNCSQL", FORM_METHOD_POST, $myaction, true);
	
	   $form->addGroup			("options",			$mymsg);       	   

	   if ($file)
	     $form->addElement		("options",			new form_element_text		("Error file",     "spparam",		$file,				"forminput",	        50,				255,	0,1));
	   else		
	     $form->addElement		("options",			new form_element_text		("Remote filename",     "spparam",		"",				"forminput",	        50,				255,	0));
	   $form->addElement		("options",			new form_element_text		("Batch",     "batch",		"",				"forminput",	        10,				50,	0));	   
       $form->addElement		("options",			new form_element_text		("Refresh",     "refresh",		"",				"forminput",	        10,				50,	0));	   	   
	   // Adding a hidden field
	   $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", $mycmd));
 
	   // Showing the form
	   $fout = $form->getform ();		
	   
	   //$fwin = new window(localize('AMAIL_DPC',getlocal()),$fout);
	   //$out .= $fwin->render();	
	   //unset ($fwin);	
	   
	   $out .= $fout;
	   
	   if (!$file) { //bypass for err file
	     //extend to upload the file forst
	     $out .= $this->upload_database_form("Upload SQL file...",'cpdoloaddb','100%');
	   }
	   
	   return ($out);  
  }
  
  function form_error_file() {
      
	   if (is_readable($this->path . 'syncsql.err')) {	   
	   
	     //$errsql = file_get_contents($this->path . 'syncsql.err');
	     //$errform = str_replace('\r\n','<br>',$errsql);
	   
	   	 $msg = "Sql statements that has not executed found.";  
         $errform .= $this->form('syncsql.err','cpexesqlerr',$msg); 		  		  
		
	     return ($errform);  	   
	   }
	   
	   return false;
  }
  
  function form_next_sync_batch($title=null,$cmd=null,$width=null) {
	   $mybatch_id = GetReq('batchid');
	   $bid = $mybatch_id?($mybatch_id+1):1;  
	   
       $mytitle = $title?$title:$this->filename;
       $mycmd = $cmd?$cmd:'cpdosync';
	   $mywidth = $width?$width:'70%';	   
	   
       $filename = seturl("t=$mycmd&batchid=".$bid);	   
   
	   //params form
       $out  = "<FORM action=". "$filename" . " method=post class=\"thin\">";
       $out .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>"; 			 	       
		  		 
       $out .= "<input type=\"hidden\" name=\"FormName\" value=\"$mycmd\">"; 	
       $out .= "<input type=\"hidden\" name=\"spparam\" value=\"".GetParam('spparam')."\">"; 			  
       $out .= "<input type=\"hidden\" name=\"batch\" value=\"".GetParam('batch')."\">"; 			  		  
       $out .= "<input type=\"hidden\" name=\"refresh\" value=\"".GetParam('refresh')."\">";		  
		  
       $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"$mycmd\">&nbsp;";			
			
       $out .= "<input type=\"submit\" name=\"Submit\" value=\"Next batch....\">";		
       $out .= "</FONT></FORM>"; 	  	
		
	   $wina = new window($this->filename,$out);
	   $winout .= $wina->render("center::$mywidth::0::group_dir_body::left::0::0::");
	   unset ($wina);		  		  
		
	   return ($winout);  	   
  }
  
  function sync_sql($localexec=null) {
       $link = GetGlobal('db');
	 
	   if ($localexec!=null) {//bin file get in
	     //html out to mail
	     $html_start = null;//'<html><body>';
		 $html_end = null;//'</body></html>';	   
	     $execbin = remote_paramload('RCSYNCSQL','binsyncfile',$this->path);
	     if (!$execbin) 
	       return($html_start.'<h2>Sevice Deactivated!</h2>'.$html_end);
       }
	   else { 
	     //no html web exec
	     $html_start = null;
		 $html_end = null;
	   }	 	 
  
	   //echo '>',GetParam('spparam');
	   if ($params = GetParam('spparam')) {
	     $mysyncfiles = explode(';',$params);//print_r($mysyncfiles);
		 
	     foreach ($mysyncfiles as $f=>$sfile) {
		   $ret .=  '<br><h2>'.$sfile.'</h2><br>';		 
           $ret .=  $this->remote_execute_sql($link,$this->address.'/'.$sfile);
           $ret .=  '---------------------------<br><br>';		 
		 }	 	   
	   }
	   elseif (is_array($this->syncfiles)) {
	   
	     foreach ($this->syncfiles as $f=>$sfile) {
		   $ret .=  '<br><h2>'.$sfile.'</h2><br>';		 
           $ret .=  $this->remote_execute_sql($link,$this->address.'/'.$sfile);
           $ret .=  '---------------------------<br><br>';		 
		 }
	   }
	   
	   return ($html_start.$ret.$html_end);
	 //}  
  }
  
  function remote_execute_sql($link,$file) {
    //$link = GetGlobal('db');
	$bid = GetReq('batchid')?GetReq('batchid'):0;
	   
	//batch proccesing
	$batch = GetParam('batch');
	
	if ($batch>0) {

      if ($bid==0) {	
        $myfile = @file_get_contents($file);
        if (!$myfile) $ret = "No file ($file)!";
        else 
          $ret = '<br>---------------------------------------------------------------------------'.$file."<br>";
		  
	    //save temp file
		$mytempsqlfile = file_put_contents($this->path.'syncsql.tmp',$myfile);	
		   
		//read stored data (1st batch)..DIDN'T WORK first batch error!!!!!!!
		//$myfile = @file_get_contents($this->path.'syncsql.tmp');  
	  }
	  else {
	    //load temp file- read stored data (2nd.. batch)
		$file = $this->path.'syncsql.tmp';
        $myfile = @file_get_contents($file);
        if (!$myfile) $ret = "No file ($file)!";
        else
          $ret = '<br>----------------------------------------------------------------------------'.$file."<br>";
		  
	  }

      $sql = explode(";",$myfile);	
	
	  $datalines = array_chunk($sql,$batch,true);
	  $current_batch = $datalines[$bid];
	  //print_r($current_batch);
	  $this->current_batch_records = count($current_batch);
	  //echo $this->current_batch_records;
			  
	  //auto refresh
	  $refresh = GetParam('refresh');
	  if (($refresh>0) && ($this->current_batch_records==$batch))
        $this->refresh_bulk_js(seturl('t=cpdosync&batchid='.($bid+1).'&batch='.$batch.'&refresh='.$refresh.'&spparam='.GetParam('spparam')),$refresh);				  	  
    }
	elseif ($batch<0) {//read partial
	  //echo 'partial',$batch;
	  
      if ($bid==0) { 
	    $bytes = abs($batch);
	    $bytes2skip = 0; 
	  }	
	  else {
	    $bytes = abs($batch);
	    $bytes2skip = ($bid*$batch);
	  } 
	  echo $bytes,':',$bytes2skip,'<br>';
	  	
	  $buffer = file_get_contents($file, null,null,$bytes2skip, $bytes);
	  echo '+',$buffer,'+';
	  //fake..current batch records = bytes 2 read = batch  
	  $this->current_batch_records = $batch;	 	  
		
      $sql = explode(";",$buffer);	
	  $current_batch = (array) $sql;
	  
	  //auto refresh
	  $refresh = GetParam('refresh');
	  if (($refresh>0) && ($this->current_batch_records==$batch))
        $this->refresh_bulk_js(seturl('t=cpdosync&batchid='.($bid+1).'&batch='.$batch.'&refresh='.$refresh.'&spparam='.GetParam('spparam')),$refresh);	  			
	}
	else {
      $myfile = @file_get_contents($file);
      if (!$myfile) $ret = "No file ($file)!";
      else
       $ret = '<br>----------------------------------------------------------------------------'.$file."<br>";

      $sql = explode(";",$myfile);	
	  $current_batch = (array) $sql;	//one batch			
	}  

    if (is_array($sql)) {

     $i=0;
     $ix=0;	 
     foreach ($current_batch as $s=>$stm) {

       $ms = str_replace("\r\n","",$stm);
       $ret .= $ms.'<br>';

       if ($result = $link->Execute(trim($ms),1) ) {
        $i+=1;
		//echo '>',$link->Affected_Rows(),'<br>';
		if ($x = $link->Affected_Rows()) 
		  $ix+=1;
		else {
		  $error_sql .= $ms . "<br>" . sprintf("Error: %s\n", $link->error) . "<br>";  
		  $errfile .= $ms .';\r\n';
		}  
	   }	
       else 
         $ret .= sprintf("Error: %s\n", $link->error); 
     }
	 
	 //save error file
	 if (strlen(trim($errfile))>0)
	   $myerrsqlfile = file_put_contents($this->path.'syncsql.err',$errfile,FILE_APPEND);
 
	 $ret .= '<br>'. $i.' sql queries executed!';
     $ret .= '<br>'.$ix. ' records affected!';	 
	 $ret .= '<br>-----------------------Errors';
	 $ret .= '<br>' . $error_sql;
     return ($ret);
    }
    $ret = 'No data!<br>'; 
	
    return $ret; 
  } 
  
	function upload_sql_file() {
	
	    //if ($_FILES['uploadfile'])  {
		//multiple files
		if (!empty($_FILES))  {
		  //print_r($_FILES);
   	      $tpath = paramload('SHELL','prpath') . 'uploads';		
		  

		  $upload_dir = $tpath;
		  
          foreach ($_FILES as $fid => $file) {
            if (!$file['error']) {
              $tmp_name = $_FILES[$fid]["tmp_name"];
              $name = $_FILES[$fid]["name"];
              move_uploaded_file($tmp_name, $upload_dir.'/'.$name);
		      $filename[] = $_FILES[$fid]['name'];				  
			  
    	      setInfo("Upload " .$_FILES[$fid]['name']. " ok!");
		      $this->msg .= "Upload " .$_FILES[$fid]['name']. " ok!<br>";
		      //echo $msg;
		      $this->post = true;				  
            }
			else {
		      setInfo("Failed to upload file " . $_FILES[$fid]['name'] . " ! (Size Error?)"); 				   
		      $this->msg .= "Failed to upload file " . $_FILES[$fid]['name'] . " ! (Size Error?)<br>";
		      $this->post = false;			
			}
          }	
		  
		  if ($filename)
		    $this->filename = implode(';',$filename); //multiple uploads with semicolon	
		  
          //$attachedfile = $_FILES['uploadfile'];
		  //print_r($attachedfile);
		  //$this->filename = $attachedfile['name'];					   		
		
		  //$myfilepath = $tpath . "/" . $this->filename;		  	
          //echo $myfilepath;
		  //copy it to admin write-enabled directory				   
		  /*if (@copy($attachedfile['tmp_name'],$myfilepath)) {
		
    	   setInfo("Upload " .$attachedfile['name']. " ok!");
		   $this->msg = "Upload " .$attachedfile['name']. " ok!"; //MEANS ERROR
		   //echo $msg;
		   $this->post = true;	
		   		   	   
		  }
		  else {
		
		   setInfo("Failed to upload file " . $attachedfile['name'] . " ! (Size Error?)"); 				   
		   $this->msg = "Failed to upload file " . $attachedfile['name'] . " ! (Size Error?)";
		   $this->post = false;
		  }*/
		
        }
		/*else {
		  $this->msg = "Failed to upload file " . $attachedfile['name'] . " ! (Type Error?)";
		  $this->post = false;
		} */ 
		  
		return ($this->msg);  	
	
	}    
	
  function sync_upload_sql_file($filename=null,$path=null,$cmd=null) {
     $link = GetGlobal('db');
	 $mycmd = $cmd?$cmd:'cpdodatedb';
     //echo $_POST['filedata'];
	 
	 $myupfiles = explode(';',GetParam('filedata'));//multiple files
	 //foreach ($myupfiles as $file) {
	   
	   if ($filename) {
	     $extrapath = $path?$path.'/':null;
	     $myufile = $this->path . $extrapath . $filename;
	   }
	   else //upload file
	     $myufile = $this->path . 'uploads/' . $myupfiles[0];//$file;//$_POST['filedata'];
		 
	   $ffname = $filename?$filename:$myupfiles[0];		 
		 
	   $bid = GetReq('batchid')?GetReq('batchid'):0;
	   //print_r($_POST);
	   //echo $myufile;
	 
	   if (is_readable($myufile)) {   
           
		   //$sql = str_replace("\r\n","",file_get_contents($myufile)); 
		   $sql = file_get_contents($myufile); 
		   //echo $sql;
		   $sql_array = explode(';',$sql);
		   //echo '<pre>';
		   //print_r($sql_array);
		   //echo '</pre>';		   
		   //$sql_array = file($myufile); 
		   //$ret .=  '<br><h2>'.$myufile.'</h2><br>';		 
           //$ret .=  $this->remote_execute_sql($link,$myufile);
			//batch proccesing
		   $batch = GetParam('batch');
		   //print_r($_POST);
		   if ($batch>0) { 		   
		   
			  $datalines = array_chunk($sql_array,$batch,true);
			  //echo '<pre>';
			  //print_r($datalines);
			  //echo '</pre>';
			  $current_batch = $datalines[$bid];
			  //print_r($current_batch);
			  $this->current_batch_records = count($current_batch);
			  //echo $this->current_batch_records;
			  
		      //auto refresh
	  	      $refresh = GetParam('refresh');
	          if (($refresh>0) && ($this->current_batch_records==$batch))
                $this->refresh_bulk_js(seturl('t='.$mycmd.'&batchid='.($bid+1).'&batch='.$batch.'&refresh='.$refresh.'&filedata='.$ffname),$refresh);				  
		   }
		   else
			  $current_batch = (array) $sql_array;	//one batch		   
		   
		   
		   foreach ($current_batch as $s=>$stm) {
		   
             $ms = str_replace("\r\n","",$stm);
             $ret .= $ms.'<br>';   
		   
		     if ($result = $link->Execute(trim($ms),1) ) {
               $i+=1;
		       if ($x = $link->Affected_Rows()) 
		         $ix+=1;
			   else {
			     $error_sql .= $ms . "<br>" . sprintf("Error: %s\n", $link->error) . "<br>";
		         $errfile .= $ms .';\r\n';
		       }				 			
	         }	
             else 
               $ret .= sprintf("Error: %s\n", $link->error);			   	 
		   }
		   
		   if ((!$filename) || ($filename!='syncsql.err')) { //if exe erros not append errors
	         //save error file
	         if (strlen(trim($errfile))>0)			 
	           $myerrsqlfile = file_put_contents($this->path.'syncsql.err',$errfile,FILE_APPEND);		   	
		   }
		   
	       $ret .= '<br>'. $i.' sql queries executed!';		   
           $ret .= '<br>'.$ix. ' records affected!';
	       $ret .= '<br>-----------------------Errors';
	       $ret .= '<br>' . $error_sql;		   	    
	   }
	   else 
         $ret .= '<br>No data!';  
	   ;	 
	   $wina = new window($ffname,$ret);
	   $out .= $wina->render("center::100%::0::group_dir_body::left::0::0::");
	   unset ($wina);		 
		 
	   //$ret .= '<br>-----------------------------------------'.$file;
	   //$bid ????????	   
	 //}//foreach    
	   
	 return ($out);
  }
  
   function attach_remote_file($id2load,$id2save=null,$tpath=null) {
   
          $m = 0;
		  
          if ($rp = $this->resource_path) {
		  
		  if (!is_array($this->resourcetypes)) 
		    $this->resourcetypes[] = '.jpg';
			
		  foreach ($this->resourcetypes as $n=>$ext) {		  
						   
			$afile = $rp.'/'.$id2load.$ext;
			
		    if ($filedata = file_get_contents($afile)) {
			
			  $tp = $tpath ? $this->urlpath . '/' .$tpath :
				             $this->urlpath . '/' .$this->target_path;			
			  
			  if (isset($id2save)) { 
							   
			    if ($tp) {				  
			      if (file_put_contents($tp.'/'.$id2save.$ext,$filedata)) 
				    $m+=1; 
			    }			  
			  }
			  else {
			    if ($tp) {
			      if (file_put_contents($tp.'/'.$id2load.$ext,$filedata)) 
				    $m+=1;
				 
			    }			  		  
			  }
			  	
			}  
		  }
		  } 
		  return ($m);
   }	  
  		 
    
};
}
?>