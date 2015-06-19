<?php
$__DPCSEC['RCLOGO_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCLOGO_DPC")) && (seclevel('RCLOGO_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCLOGO_DPC",true);

$__DPC['RCLOGO_DPC'] = 'rclogo';

$d = GetGlobal('controller')->require_dpc('rc/rcupload.dpc.php');
require_once($d);
 
//GetGlobal('controller')->get_parent('RCUPLOAD_DPC','RCLOGO_DPC');

$__EVENTS['RCLOGO_DPC'][0]='cplogo';
$__EVENTS['RCLOGO_DPC'][1]='cpchlogo';

$__ACTIONS['RCLOGO_DPC'][0]='cplogo';
$__ACTIONS['RCLOGO_DPC'][1]='cpchlogo';

$__DPCATTR['RCLOGO_DPC']['cplogo'] = 'cplogo,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCLOGO_DPC'][0]='RCLOGO_DPC;Set logo image;Set logo image';

class rclogo extends rcupload {

    var $path,$title,$logoname,$appname;
	var $virtual_directory;
	var $maxsize,$urlpath;

    function rclogo() {

	   $this->path = paramload('SHELL','prpath');   	   
	   $this->urlpath = paramload('SHELL','urlpath');
	   
	   $maxsize = remote_paramload('RCLOGO','maxsize',$this->path);
	   $this->maxsize = $maxsize?$maxsize:1000000;
	   
       rcupload::rcupload(null,$this->maxsize); 	
	   	   
	   //$this->subdir = paramload('ID','hostinpath');
       //$this->virtual_directory = $this->urlpath . $this->subdir . "public/";	
	   $this->tpath = $this->path;
	 		   
       if ($remoteuser=GetSessionParam('REMOTEAPPSITE')) {//REMOTEAPPSITE /REMOTELOGIN
		 $this->appname = $remoteuser;	
	     $this->subdir = remote_paramload('ID','hostinpath',$this->path."instances/$remoteuser/");		 
		 $this->virtual_directory .= $remoteuser . "/" . $this->subdir . "/images/";
	     $this->logoname = remote_paramload('RCLOGO','logoname',$this->path."instances/$remoteuser/");		 
	   }	
	   elseif ($remoteuser=GetSessionParam('REMOTELOGIN')) {
		 $this->appname = $remoteuser;
	     $this->subdir = remote_paramload('ID','hostinpath',$this->path."instances/$remoteuser/");			 	
		 $this->virtual_directory .= $remoteuser . "/" . $this->subdir . "/images/";
	     $this->logoname = remote_paramload('RCLOGO','logoname',$this->path."instances/$remoteuser/");	   
	   }
	   else {
		 $this->appname = null;	   
	     $this->subdir = paramload('ID','hostinpath');
         $this->virtual_directory = $this->urlpath .'/'. $this->subdir . "/images/";	
	     $this->logoname = remote_paramload('RCLOGO','logoname',$this->path);	
		 //print_r(GetGlobal('config')); 
	   }	 
	   //$this->logoname = remote_paramload('RCLOGO','logoname',$this->path);	   
       $this->title = localize('RCLOGO_DPC',getlocal());	
       $this->imgurl = $this->subdir  . '/images/'.$this->logoname;	
	   //echo $this->logoname;
	   //echo $this->virtual_directory;
	   
	}
	
	function event($event=null) {
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////
	   switch ($event) {
	   
	     case 'cpchlogo' : $this->msg = $this->upload_logo();
		                   $this->post = true; 
		                   break;
	     default :
	   }
	   	
	}
	
	function action($action=null) {
	
	   if (GetSessionParam('REMOTELOGIN')) 
	     $ret = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	   else  
         $ret = setNavigator(seturl("t=cp","Control Panel"),$this->title); 	
	
	   $ret .= $this->show_logo();	
       $ret .= $this->uploadform('cpchlogo','/images',null,null,$this->maxsize); 
	   
	   return ($ret);	
	}
	
    function show_logo() {
  
      $memo = null;
/*	  $mydir = $this->appname;
	  $myname = $this->logoname;
                  $hostinpath = paramload('ID','hostinpath');
	
      $ret = "<img src=\"$mydir/images/$myname\" border=\"0\" alt=\"$memo\">";
*/
      $ret = "<img src=\"$this->imgurl\" border=\"0\" alt=\"$memo\">";
	
	  //$out = $this->msg;
	
	  $swin2 = new window("Your logo",$ret);     
	  $out .= $swin2->render("center::100%::0::group_article_selected::center::0::0::");	
	  unset ($swin2);		
		
	  return ($out);
    }	
	
	function upload_logo() {
	
	  $ret = $this->upload_file();//$this->logoname);

      $attachedfile = $_FILES['uploadfile'];
	  //print_r($attachedfile);
	  $name = $attachedfile['name'];
	  //echo $name;
	  
	  if ((!$ret) && ($this->virtual_directory))//if success copy image to /APP/images
		 $ret = $this->replicate_file_to($this->virtual_directory);//,$this->logoname);
		 
	  GetGlobal('controller')->calldpc_method("rcconfig.paramset use RCLOGO+logoname+$name");	 
	  GetGlobal('controller')->calldpc_method("rcconfig.write_config");
			
	  return ($ret);				
	}
	
	function render() {
	  //echo '>',paramload('RCLOGO','logoname');
/*	  $mydir = $this->appname;
	  $myname = $this->logoname;
	  
	  //WARNING : in case of root app it show remote apps logo so...
	  $mydir = null;
	  //$myname = paramload('RCLOGO','logoname');//global var ... call loaded config param=remotelogin config
	  //ALLWAYS LOCAL ROOTAPP OR REMOTEAPP NOT REMOTELOGIN APP
	  $myname = remote_paramload('RCLOGO','logoname',$this->path);//."instances/$remoteuser/");
	  //echo $myname;	  
	
      $ret = "<img src=\"$mydir/images/$myname\">";
*/
	  
      $ret = "<img src=\"".$this->imgurl."\">";
      //echo $this->virtual_directory;
	  
	  return ($ret);
	}

};
}
?>