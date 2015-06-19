<?php
if (defined("DATABASE_DPC")) { 

$__DPCSEC['DPVS_DPC']='2;1;1;1;1;1;1;2;9';
$__DPCSEC['PRJVS_']='2;1;1;1;1;1;1;2;9';
$__DPCSEC['DPCVS_']='2;1;1;1;1;1;1;2;9';
$__DPCSEC['ALLVS_']='2;1;1;1;1;1;1;2;9';
$__DPCSEC['VIEWVS_']='2;1;1;1;1;1;1;2;9';

if ((!defined("DPVS_DPC")) && (seclevel('DPVS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("DPVS_DPC",true);

$__DPC['DPVS_DPC'] = 'dpvs';

$__EVENTS['DPVS_DPC'][0]='dpvs';
$__EVENTS['DPVS_DPC'][1]='reset_versions';
$__EVENTS['DPVS_DPC'][2]='addver';
$__EVENTS['DPVS_DPC'][3]='remver';
$__EVENTS['DPVS_DPC'][4]='updver';
$__EVENTS['DPVS_DPC'][5]='viewver';

$__ACTIONS['DPVS_DPC'][0]='dpvs';
$__ACTIONS['DPVS_DPC'][1]='reset_versions';
$__ACTIONS['DPVS_DPC'][2]='addver';
$__ACTIONS['DPVS_DPC'][3]='remver';
$__ACTIONS['DPVS_DPC'][4]='updver';
$__ACTIONS['DPVS_DPC'][5]='viewver';

/*
$__DPCATTR['DPVS_DPC'][localize('_HLIGHT1',getlocal())] = '_HLIGHT1,0,0,0,0,0,1,0';
$__DPCATTR['DPVS_DPC'][localize('_HLIGHT2',getlocal())] = '_HLIGHT2,0,0,0,0,0,1,0';
$__DPCATTR['DPVS_DPC'][localize('_HLIGHT3',getlocal())] = '_HLIGHT3,0,0,0,0,0,1,0';
$__DPCATTR['DPVS_DPC'][localize('_HLIGHT4',getlocal())] = '_HLIGHT4,0,0,0,0,0,1,0';
$__DPCATTR['DPVS_DPC']['hl'] = 'hl,0,0,0,0,0,0,1';*/


$__LOCALE['DPVS_DPC'][0]='DPVS_DPC;Versions;Εκδοσεις';


class dpvs {
 
     var $userLevelID;
     var $savepath;
	 var $syspath;

     function dpvs() {
	    $UserSecID = GetGlobal('UserSecID');
		
        $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
			
		$this->savepath = paramload('DPVS','path');	
		$this->syspath = "c:/webos/dpc";		  
	 }
	 
     function event($sAction=null) {
	   $param1 = GetGlobal('param1');

       switch($sAction) {
	     case 'reset_versions': $this->reset_versions(); break;		
		 case 'dpvs'          : break;
		 case 'addver'        : $this->addver($param1); break;
		 case 'remver'        : $this->remver(); break;
		 case 'updver'        : $this->updver(); break;												   
		 case 'viewver'       : break;		 		 
       }
 	}
	
	function action($act=null) {

	   switch ($act) {			 
		 case 'reset_versions': $out = 'Ok!'; break;		 
		 case 'dpvs'          : $out = $this->render(); break;		 
		 case 'addver'        : 
		 case 'remver'        : 
		 case 'updver'        : $out = 'Ok!'; break;		 		 		 
		 case 'viewver'       : $out = $this->view_version_content(); break;			 
	   }

	   return ($out);
	} 

    function render() {			 	
	   $db = GetGlobal('db');
  	
       //title
       $out = setNavigator(localize('DPVS_DPC',getlocal()));   	
	
	   $sSQL = "select id,section,type,ver from versions";
	   
       $browser = new browseSQL(localize('DPVS_DPC',getlocal()));
	   $out .= $browser->render($db,$sSQL,"versions","versions",30,$this,1,1,1,0);
	   unset ($browser);
	   
		 
	   return ($out);
     }
	 
   
   function reset_versions() {

        //delete table if exist
		calldpc_method('database.droptable use versions');
  	    //$sSQL = "drop table if exists versions";
        //$db->Execute($sSQL);
		$sSQL = "create table versions " .
                    "(" .
	                "recid integer auto_increment primary key," .
	                "id varchar(255)," .
	                "section varchar(64)," .					
	                "cdate varchar(64)," .					
	                "mkey varchar(255)," .					
	                "type varchar(64)," .
	                "ver varchar(64)," .											
	                "data text," .
	                "comments text," .																						
	                "UNIQUE (id)" .					
                    ")";
        //$db->Execute($sSQL);   
		calldpc_method_use_pointers('database.exesql',array(0=>&$sSQL));		
			
		setInfo(" Reset successfully!");
    } 
	
	function addver($param) {
	
	    $p = explode(",",$param);
		
		switch ($p[1]) {
		  
		   case 'FILE' :
		       default : $dpcf = explode(".",$p[0]);
		                 $filename = $this->syspath .'/'.$dpcf[0]."/".$dpcf[1].".".$dpcf[2].".php";
					     $id = $dpcf[1].".".$dpcf[2].".php"."_".$p[2];
						 $section = $dpcf[0];
					     $handle = @fopen ($filename, "rb");
                         $contents = @fread ($handle, filesize ($filename)); 
						 //echo $contents,filesize($filename);
                         @fclose ($handle);
					     $type = $dpcf[2];
					     $comments = 'no comments';
		}
	
	    $sSQL = "insert into versions (id,section,cdate,mkey,type,ver,data,comments) values (".
		$sSQL.= "'" . $id . "',";
		$sSQL.= "'" . $section . "',";		
		$sSQL.= "'" . gmdate("D, d M Y H:i:s", time()) . "',";	//GMT date	
		$sSQL.= "'" . uniqid("KEY") . "',";
		$sSQL.= "'" . $type . "',";						
		$sSQL.= "'" . $p[2] . "',";
		$sSQL.= "'" . base64_encode($contents) . "',";		
		$sSQL.= "'" . base64_encode($comments) . "'";			
		$sSQL.= ")";
		
        //$db->Execute($sSQL); 		
		calldpc_method_use_pointers('database.exesql',array(0=>&$sSQL));		
	} 
	
	function remver($param) {
	
	    $p = explode(",",$param);	
		
        $dpcf = explode(".",$p[0]);
		$id = $dpcf[1].".".$dpcf[2].".php"."_".$p[2];		
		$sec = $spcf[2];
	
	    $sSQL = "delete from versions where id='$id' and section='$sec'";
				
		calldpc_method_use_pointers('database.exesql',array(0=>&$sSQL));		
	}
	
	function updver() {
	}
	
	function view_version_content() {
	
	  $sSQL = "SELECT id,data from versions WHERE id='" . GetReq('a') . "'";
	  $ret = calldpc_method_use_pointers('database.exesql',array(0=>&$sSQL));	  
	
      $out = setNavigator(localize('DPVS_DPC',getlocal()));	

	  $w = new window($ret->fields['id'],base64_decode($ret->fields['data']));
	  $out .= $w->render();
	  unset($w);
	  
	  return ($out); 		   
	}
	
	function download_version() {
	}
	
	
	function browse($packdata,$view='') {
	  
	   $data = explode("||",$packdata);
	
	   switch ($view) {
	       case 'versions' : //versions view
		                    $out = $this->viewver($data[0],$data[1],$data[2],$data[3]);
		                    break;   
	   }
	   
	   return ($out);
	}
	
	function viewver($id,$section,$type,$ver) {
	  
	   $a = GetReq('a');
	   
	   $image = "<img src=\"" . $id . "\" width=\"100\" height=\"75\" border=\"0\" alt=\"". $title . "\">";
	
	   $data[] = seturl("t=viewver&a=$id",$id);
	   $datt[] = "left;40%";
	   $data[] = $section; 
	   $datt[] = "left;20%;middle;";
	   $data[] = $ver;
	   $datt[] = "center;20%;middle;";
	   $data[] = $type;
	   $datt[] = "center;20%;middle;";	   
	   
	   $myarticle = new window('',$data,$datt);
	      
       if (($a) && (stristr($a,$id)) )
	     $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::");	   
	   else		   
	     $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::");
	   
	   unset ($data);
	   unset ($datt);

	   return ($out);	   	   
	} 
		

	function headtitle() {
	}	 

};
}
}
else die("DATABASE DPC REQUIRED! (" .__FILE__ . ")");
?>