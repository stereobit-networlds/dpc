<?php

$__DPCSEC['RCFS_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCFS_DPC")) && (seclevel('RCFS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCFS_DPC",true);

$__DPC['RCFS_DPC'] = 'rcfs';
 
$__EVENTS['RCFS_DPC'][0]='cpfs';
$__EVENTS['RCFS_DPC'][1]='cpfsupload';

$__ACTIONS['RCFS_DPC'][0]='cpfs';
$__ACTIONS['RCFS_DPC'][1]='cpfsupload';

$__DPCATTR['RCFS_DPC']['cpfs'] = 'cpfs,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCFS_DPC'][0]='RCFS_DPC;File system;File system';

class rcfs {

    var $nfiles,$path,$init_path,$msg;
	var $dtype,$stats;
	var $dview;
	var $resources;
	var $user;
		
	function rcfs($path=null,$view=null) {
		
	    $this->title = localize('RCFS_DPC',getlocal());		
		$this->post = false; //hold successfull posting	
		$this->msg = null;
		
		if (isset($path)) 
		    $this->init_path = $path;
		else {
		    if ($remoteuser=GetSessionParam('REMOTELOGIN')) 
			  $this->init_path = paramload('SHELL','prpath')."instances/$remoteuser/";	
			else
		      $this->init_path = paramload('SHELL','prpath');	
		} 	
		
		$this->user = $remoteuser;
		
		//SetSessionParam('fspath',null);
		if ($p = GetSessionParam('fspath'))
		  $this->path = $p;
		else   
		  $this->path = $this->init_path;
		//echo $this->path,'  ',$this->init_path;   
		  
		$myview = GetReq('view');  
		if (isset($myview)) {
		  $this->dview = $myview;
		  SetSessionParam('dview',$this->dview);     
		}  
		else {
          $myview = GetSessionParam('dview');
		  if (isset($myview))
		    $this->sview = $myview;		
		  else {
		    $this->dview = $view;
		    SetSessionParam('dview',$this->dview); 		  
		  }	
		}
		
		//echo $this->dview,'>>>>',GetSessionParam('dview');
	}
	 	
	
    function event($sAction) {
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////
	
       $sFormErr = GetGlobal('sFormErr');	
  
       if (!$sFormErr) {   
  
	   switch ($sAction) {	

		case "cpfs"      :     if (($click = getParam('id'))/* &&
                            	   (stristr($this->path,$this->init_path))*/) {//clicked file or dir

								  if ($click=='..') {//one level down
									if ($this->is_root()===false) {  
								    $pp = explode("/",$this->path);
									$max = count($pp) - 2;
									for ($i=0;$i<$max;$i++)
									  $pp2[] = $pp[$i];
									$this->path = implode("/",$pp2) . "/";
									//echo $this->path;
									} 
								  }
		                          elseif (is_dir($this->path . $click))
								    $this->path = $this->path . $click . "/";
								  //else is a file opened by type
		                       }
		 
		                       $this->nfiles = $this->read_directory($this->path,null,1,1); 
		                       $this->post = false;
		                       break;
							   
		case "cpfsupload":     $this->upload_file();
		                       $this->nfiles = $this->read_directory($this->path,null,1,1); 
		                       $this->post = true;
		                       break;
       }
      }   
    }
  
    function action($action) {

	 $out = $this->fsshow();
	 $out.= $this->uploadform();
	 
	 return ($out);
    } 
	  
  
  function fsshow() {

     $sFormErr = GetGlobal('sFormErr');
	 
     $myaction = seturl("t=csfsupload");

	 if (GetSessionParam('REMOTELOGIN')) 
	   $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	 else  
       $out = setNavigator(seturl("t=cp","Control Panel"),$this->title); 	 
	   
	 if ($this->post==true) {	   
	   
	     $swin = new window("Post",$this->msg);
	     $out .= $swin->render("center::70%::0::group_win_body::center::0::0::");	
	     unset ($swin);
	   
	 }	   
   
	 $out .= $this->show_directory($this->nfiles);	   
 
     return ($out);
  }
  
  function delete_file() {
  
     $isdel = GetReq('del');
	 $file = GetReq('id');
	 $pfile = GetSessionParam('fspath') . $file;
	 //echo $pfile;
	 
	 if ((is_file($pfile)) && ($isdel=='1')) {
	 
	   unlink($pfile);
	 }
  }  
  
  
  function read_directory($dirname,$extensions=null,$up=null,$down=null) {
  
        $this->dtype = array(); //init
		$this->stats = array(); //init
		
	    //CHECK iF AND DELETE FILE    	    		  			    
	    $this->delete_file();		

	    if (is_dir($dirname)) {
          $mydir = dir($dirname);
		  
		  //save path
		  SetSessionParam('fspath',$dirname);  
	      //echo $this->init_path,'>>>>',GetSessionParam('fspath');		  
		  $root = $this->is_root();
		  
          while ($fileread = $mydir->read ()) {
		     //echo $fileread;
		     if ((!$root) && ($fileread=='..') && ($down) && (!$extensions)) {
			   $ddir[] = $fileread;
			   $this->dtype[] = "DIR";
			   $this->stats[] = null;
			   continue;		   
		     }
	   
             //read files and directories
		     if (($fileread!='.') && ($fileread!='..'))  {
		    
			   if ((is_dir($dirname."/".$fileread)) && ($up)/* && (!$extensions)*/) {
			     $ddir[] = $fileread;
				 $this->dtype[] = "DIR";
				 $this->stats[] = null;
			     continue;
			   }
               //elseif (is_file($dirname."/".$fileread)) {
			   if (is_array($extensions)) {//check extensions
			     foreach ($extensions as $num=>$ext) { 
			        if (stristr($fileread,$ext)) {			   
					  //$parts = explode("-",$fileread);
		              $ddir[] = $fileread;	
					  $this->dtype[] = "FILE";
					  
					  if ($this->dview==1) {
					  $fp=fopen($dirname."/".$fileread,"r");
					  if ($fp) {
                        $this->stats[]=fstat($fp);
                        fclose($fp);
					  }	
					  else
					    $this->stats[] = null;				
					  }	
					}
		         }
			   }
			   else {//without ext chacking =all		   
		         $ddir[] = $fileread;						
				 $this->dtype[] = "FILE";	
				 
				 if ($this->dview==1) {
				 $fp=fopen($dirname."/".$fileread,"r");
				 if ($fp) {
                   $this->stats[]=fstat($fp);
                   fclose($fp);
				 }
				 else
				   $this->stats[] = null;				 	
				 }  
			   }
			   //}
			 }
		     
	      }
	      $mydir->close ();
        }
		return ($ddir);
  }
  
  function show_directory($files,$url=null,$title=null,$length="100%")  {  
  
    //print_r($this->nfiles);
    if (is_array($files)) {
	
	  //views
	  $cmd = seturl("t=".GetReq('t')."&id=".GetReq('id')."&view=0","Standart") . "&nbsp;|&nbsp;";
	  $cmd .= seturl("t=".GetReq('t')."&id=".GetReq('id')."&view=1","Analytical");
	  $cmdwin = new window('',$cmd);
	  $wret = $cmdwin->render("center::100%::0::group_win_body::right::0::0::");	
	  unset($cmdwin);	

	  switch ($this->dview) { 
	    case 0 : $wret .= $this->view_dir_list($files,$url); break;
	    case 1 : $wret .= $this->view_dir_analytic_list($files,$url); break;
		default: $wret .= $this->view_dir_list($files,$url); 
	  }
	    	  
	  if (isset($title))
	    $swin = new window($title,$wret);
	  else	
	    $swin = new window($this->path,$wret);
		
	  $wout = $swin->render("center::$length::0::group_win_body::left::0::0::");	
	  unset ($swin);	  
	}
	
	return ($wout);
  }
  
  function view_dir_list($files,$url=null) {
  
      foreach ($files as $id=>$name) {
	
	    if ($url)
		  $ret = seturl($url.$name,$name) ."<br>"; 
		else
		  $ret = seturl("t=cpfs&id=".$name,$name) ."<br>"; 
		
		$data[] = $ret;
		$attr[] = "left;60%"; 
		
		$data[]= (isset($url)?seturl("$url$name&del=1","X"):seturl("t=cpfs&del=1&id=".$name,"X"));
		$attr[] = "left;30%"; 
		
		$data[] = $this->dtype[$id];
		$attr[] = "center;30%"; 
		
	    $swin = new window("",$data,$attr);
	    $wret .= $swin->render("center::100%::0::group_article_body::left::0::0::");	
	    unset ($swin);	
		unset($data);
		unset($attr);				
	  }
	  
	  return ($wret);
  }  
  
  function view_dir_analytic_list($files,$url=null) {
  
      foreach ($files as $id=>$name) {
	
	    if ($url)
		  $ret = seturl($url.$name,$name) ."<br>"; 
		else
		  $ret = seturl("t=cpfs&id=".$name,$name) ."<br>"; 
		
		$data[] = $ret;
		$attr[] = "left;60%"; 
		
		$data[]= (isset($url)?seturl("$url$name&del=1","X"):seturl("t=cpfs&del=1&id=".$name,"X"));
		$attr[] = "left;5%"; 		
		
		$data[] = $this->dtype[$id];
		$attr[] = "center;15%"; 		
		
		$data[] = (isset($this->stats[$id]['size'])?$this->stats[$id]['size']:"&nbsp;");
		$attr[] = "center;10%"; 
		
		$data[] = (isset($this->stats[$id]['ctime'])?$this->stats[$id]['ctime']:"&nbsp;");
		$attr[] = "center;10%"; 				
		
	    $swin = new window("",$data,$attr);
	    $wret .= $swin->render("center::100%::0::group_article_body::left::0::0::");	
	    unset ($swin);	
		unset($data);
		unset($attr);				
	  }
	  
	  return ($wret);
  }  
  
  function is_root() {
      //echo $this->path,"..",$this->init_path;
      if (strlen($this->path)==strlen($this->init_path))
	    return true;
	  
	  return false;  	
  }
  
  function uploadform() {   

          $filename = seturl("t=cpfsupload&id=".GetParam('id'));		
		
	      //upload file(s) form
          $out  = "<FORM action=". "$filename" . " method=post ENCTYPE=\"multipart/form-data\" class=\"thin\">";
          $out .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" VALUE=\"9024\">"; //max file size option in bytes
          $out .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>"; 			 	   
          $out .= "Upload: <input type=FILE name=\"uploadfile\">";		    
          $out .= "<input type=\"hidden\" name=\"FormName\" value=\"Cpfsupload\">"; 	
          $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"cpfsupload\">&nbsp;";		
          $out .= "<input type=\"submit\" name=\"Submit2\" value=\"Upload\">";		
          $out .= "</FONT></FORM>"; 	
		
		  $wina = new window('',$out);
		  $winout = $wina->render();//"center::100%::0::group_dir_title::right::0::0::");
		  unset ($wina);		  		  
		
		return ($winout);
  }
  
  function upload_file() {

        //$uploadfile = GetGlobal('uploadfile');
		//$uploadfile_size = GetGlobal('uploadfile_size');
		//$uploadfile_name = GetGlobal('uploadfile_name');
		//$uploadfile_type = GetGlobal('uploadfile_type');
		
        $attachedfile = $_FILES['uploadfile'];
		$filename = $attachedfile['name'];					   
			   
		$myfilepath = $this->path . "/" . $filename;
        //echo $myfilepath;
		//copy it to admin write-enabled directory				   
		if (copy($attachedfile['tmp_name'],$myfilepath)) {
		
    	   setInfo("Upload " .$attachedfile['name']. " ok!");
		   $this->msg = "Upload " .$attachedfile['name']. " ok!"; 
		}
		else {
		
		   setInfo("Failed to upload file " . $attachedfile['name'] . " ! (Size Error?)"); 				   
		   $this->msg = "Failed to upload file " . $attachedfile['name'] . " ! (Size Error?)";
		}
		//unlink($uploadfile); 
	} 
	
	
	function read_repository($path=null,$default=0) {
  
       if ($default) 
	     $mypath = $this->init_path . $path;
	   else
	     $mypath = $path;
  
       //echo $mypath;
  
	   if (is_dir($mypath)) {	
		
          $mydir = dir($mypath);
		  //echo $this->path;
          while ($fileread = $mydir->read ()) {
		     //echo $fileread;
		     if (($fileread!='.') && ($fileread!='..')) {
			   //echo $fileread;	   
			   if ((is_file($mypath.$fileread)) &&
			       ((stristr($fileread,".jpg")) ||
				    (stristr($fileread,".gif")) || 
				    (stristr($fileread,".png"))))
					
			     $this->resources[] = $fileread;
		     }	
		  }		   
	  }	
	}
	
    function show_repository($action=null,$title=null,$linemax=3) {
  
	  $itemscount = count($this->resources);
	  
	  $timestoloop = floor($itemscount/$linemax)+1;
	  $meter = 0;
	  for ($i=0;$i<$timestoloop;$i++) {
	    //echo $i,"---<br>";
	    for ($j=0;$j<$linemax;$j++) {
	     //echo $i*$j,"<br>";
		 $pfile = $this->resources[$meter];
		 $image = "<img src=\"$this->user/images/$pfile\" border=\"0\" alt=\"Delete $pfile\">";
		 $url = seturl("t=$action&id=$pfile",$image);
		 
		 if ($action)
		   $viewdata[] = (isset($pfile)? $url."<br>".$pfile  : "&nbsp;"); 
		 else  
		   $viewdata[] = (isset($pfile)? $image."<br>".$pfile  : "&nbsp;"); 
		 $viewattr[] = "center;10%";	
		 $meter+=1;	 
	    }
	  
	    $myrec = new window('',$viewdata,$viewattr);
	    $ret .= $myrec->render("center::100%::0::group_article_selected::left::0::0::");
	    unset ($viewdata);
	    unset ($viewattr);		  
	  }  
	
	  $swin2 = new window($title,$ret);     
	  $out = $swin2->render("center::100%::0::group_article_selected::center::0::0::");	
	  unset ($swin2);		
		
	  return ($out);
    }  	 	  
  
};
}
?>