<?php

$__DPCSEC['ADMIN_DPC']='8;1;1;1;1;1;1;8;9';

if ((!defined("ADMIN_DPC")) && (seclevel('ADMIN_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("ADMIN_DPC",true);

$__DPC['ADMIN_DPC'] = 'admin';

$__EVENTS['ADMIN_DPC'][999]='adminsysfile';

$__ACTIONS['ADMIN_DPC'][0]='admin';
$__ACTIONS['ADMIN_DPC'][1]='adminsysfile';

$__LOCALE['ADMIN_DPC'][0]='ADMIN_CNF;Content Managment;Διαχείρηση Περιεχομένου';

/**
 file:
 admin.dpc
 
 description:
 Content managment administration.
 
***/
class admin {

	var $datadir; // directory path
    var $userLevelID; //security id

/**
 Name:
 admin()
 
 description:
 constructor

 description2:
 constructor
  
 Typ: public
 
 Output:
 none
 
***/	
	function admin() {
		$UserSecID = GetGlobal('UserSecID');

        $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
		$this->datadir = paramload('SHELL','prpath');
	}
	
    function action($action) {
		 
		 switch ($action) {
		   case "adminsysfile"       :		 
		   case "admin"              : $out = $this->administrate(); break;													  					
		 }					

         return ($out);
    }
		
    function event($sAction) { 
     
	     switch ($sAction) {
             case "adminsysfile"      : $this->save_sysfile(); break;					
         } 
    }	

/**
 Name:
 show_databases()
 
 description:
 read and view database files
 
 description2:
 constructor
  
 Typ: public
 
 Output:
 $toprint = html code 
 
***/
    function show_databases() {

      $toprint .=  localize('_MODIFY',getlocal()) . ":";

      $d = dir ($this->datadir);
      while ($filename = $d->read()) {
	     if (strstr ($filename,".csv") || strstr ($filename,".xgi") || strstr ($filename,".root")) {
		    $databases[$filename] = $filename;
	     }
      }
		
      asort ($databases);	
      for(reset ($databases); $database = key ($databases); next ($databases)) {
	     $fdata = _with($database);	  
         $toprint .= "[" . seturl("t=admin&a=$fdata&g=" ,$database) . "] ";
      }
		
      $d->close();
	  
      return ($toprint);
    }

/**
 Name:
 update_sysfile()
 
 description:
 Update system file form
  
 description2:
 constructor
 
 Typ: public

 Input:
 $file = System file
   
 Output:
 $winout = html code of a form
 
***/
    function update_sysfile($file) {
       $replacement_text = GetGlobal('replacement_text');
	   $sFormErr = GetGlobal('sFormErr');
 
       $myfile = _with($file);
       $myaction = seturl("t=admin&a=$myfile&g=");    
  
       //error message
       $form .= setError($sFormErr); 
  
       $form .= "<FORM action=". "$myaction" . " method=post>";
       $form .= "<INPUT type=\"hidden\" name=\"database_file\" value=\"$file\">";
	   $form .= "<DIV class=\"monospace\"><TEXTAREA style=\"width:100%\" NAME=\"replacement_text\" ROWS=20 cols=50 wrap=\"virtual\">";
       if ($file) {
           $myfile = $this->datadir . $file;
           $form .= html2txt($myfile);
       }
       $form .= "</TEXTAREA></DIV><br>";
       $form .= "<input type=\"hidden\" name=\"FormName\" value=\"AdminSYSfile\">";
       $form .= "<input type=\"hidden\" name=\"FormAction\" value=\"adminsysfile\">";  
       $form .= "<INPUT type=\"submit\" value=\"Update\">";  
       $form .= "</FORM>";
  
       $data[] = $form;
       $attr[] = "left";
	   
	   $mwin = new window($file,$data,$attr);
	   $winout = $mwin->render();
	   unset ($mwin);
    
       return ($winout);
    }
	
/**
 Name:
 save_sysfile()
 
 description:
 Save system file
 
 description2:
 constructor
 
 Typ: public

 Input:
 none
   
 Output:
 none
 
***/	
	function save_sysfile() {
       $replacement_text = GetGlobal('replacement_text');
	   $sFormErr = GetGlobal('sFormErr');
	   $a = GetReq('a');	
	
       if ($replacement_text) {
	       $rt = str_replace( "\\\\", "", $replacement_text);
           SetGlobal('replacement_text',$rt);	
	
           // save file (if properties & security policy are ok)
	       $actfile = $this->datadir . _without($a); 
           if ($fp = fopen ($actfile , "w")) {
                                  fwrite ($fp, $rt);
                                  fclose ($fp);
								  SetGlobal('sFormErr',"Update successfully !");
								  setInfo("Update successfully !");
	       }
	       else {
	         SetGlobal('sFormErr', "File creation error !");
		     setInfo("File creation error !");
		   }
        }	
	}
/**
 Name:
 administrate()
 
 description:
 Administration console
 
 description2:
 constructor
  
 Typ: public

 Input:
 none
   
 Output:
 html code
 
***/	
    function administrate() {
	   $a = GetReq('a');	
	   $g = GetReq('g');		   

       $article = $a;
  
       //navigation status    	  
       $out = setNavigator(localize('ADMIN_CNF',getlocal()));

       //databases
       $data2[] = $this->show_databases();
       $attr2[] = "left";
	   $mwin = new window('',$data2,$attr2);
	   $out .= $mwin->render();
	   unset ($mwin);		 	 
	
	   if ($article) {
	       if ( (stristr ($article,'.csv')) || 
     	        (stristr ($article,'.xgi')) ||
				(stristr ($article,'.root')) ) {
	         $out .= $this->update_sysfile(_without($article));
	       }
	   }	
	   return ($out);
	}	

};
}
?>
