<?php
$__DPCSEC['RCTAGS_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("RCTAGS_DPC")) && (seclevel('RCTAGS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCTAGS_DPC",true);

$__DPC['RCTAGS_DPC'] = 'rctags';

$a = GetGlobal('controller')->require_dpc('rc/rcbrowser.lib.php');
require_once($a);

$d = GetGlobal('controller')->require_dpc('shop/shtags.dpc.php');
require_once($d);

$__EVENTS['RCTAGS_DPC'][0]='cptags';
$__EVENTS['RCTAGS_DPC'][1]='tags';
$__EVENTS['RCTAGS_DPC'][2]='cpaddtag';
$__EVENTS['RCTAGS_DPC'][3]='cpedittag';
$__EVENTS['RCTAGS_DPC'][4]='cpeditctag';
$__EVENTS['RCTAGS_DPC'][5]='cpedititag';

$__ACTIONS['RCTAGS_DPC'][0]='cptags';
$__ACTIONS['RCTAGS_DPC'][1]='tags';
$__ACTIONS['RCTAGS_DPC'][2]='cpaddtag';
$__ACTIONS['RCTAGS_DPC'][3]='cpedittag';
$__ACTIONS['RCTAGS_DPC'][4]='cpeditctag';
$__ACTIONS['RCTAGS_DPC'][5]='cpedititag';

$__LOCALE['RCTAGS_DPC'][0]='RCTAGS_DPC;SQL tags;Tags SQL';
$__LOCALE['RCTAGS_DPC'][1]='_LEVEL1;Category Level 1;Κατηγορία επιπέδου 1';
$__LOCALE['RCTAGS_DPC'][2]='_LEVEL2;Category Level 2;Κατηγορία επιπέδου 2';
$__LOCALE['RCTAGS_DPC'][3]='_LEVEL3;Category Level 3;Κατηγορία επιπέδου 3';
$__LOCALE['RCTAGS_DPC'][4]='_LEVEL4;Category Level 4;Κατηγορία επιπέδου 4';
$__LOCALE['RCTAGS_DPC'][5]='_LEVEL5;Category Level 5;Κατηγορία επιπέδου 5';
$__LOCALE['RCTAGS_DPC'][6]='_NEWLEVEL;<b>New category</b>;<b>Νέα κατηγορία</b>';
$__LOCALE['RCTAGS_DPC'][7]='_FLEVEL1;Foreign Alias Level 1;Κατηγορία επιπέδου 1 (Ξενόγλωσση)';
$__LOCALE['RCTAGS_DPC'][8]='_FLEVEL2;Foreign Alias Level 2;Κατηγορία επιπέδου 2 (Ξενόγλωσση)';
$__LOCALE['RCTAGS_DPC'][9]='_FLEVEL3;Foreign Alias Level 3;Κατηγορία επιπέδου 3 (Ξενόγλωσση)';
$__LOCALE['RCTAGS_DPC'][10]='_FLEVEL4;Foreign Alias Level 4;Κατηγορία επιπέδου 4 (Ξενόγλωσση)';
$__LOCALE['RCTAGS_DPC'][11]='_FLEVEL5;Foreign Alias Level 5;Κατηγορία επιπέδου 5 (Ξενόγλωσση)';
$__LOCALE['RCTAGS_DPC'][12]='_FNEWLEVEL;<b>New category alias</b>;<b>Νέα κατηγορία (Ξενόγλωσση)</b>';
$__LOCALE['RCTAGS_DPC'][13]='_code;Id;Id';
$__LOCALE['RCTAGS_DPC'][14]='_tag;Tag;Tag';
$__LOCALE['RCTAGS_DPC'][15]='_keywords;Keywords;Λέξεις κλειδιά';
$__LOCALE['RCTAGS_DPC'][16]='_descr;Description;Περιγραφή';
$__LOCALE['RCTAGS_DPC'][17]='_CLEAR;Clear;Καθαρισμός';
$__LOCALE['RCTAGS_DPC'][18]='_POST;Post;Αποστολή';
$__LOCALE['RCTAGS_DPC'][19]='_OK;Success;Επιτυχώς';
$__LOCALE['RCTAGS_DPC'][20]='_title;Title;Τίτλος';

class rctags extends shtags {
  
    var $title, $path, $catpath, $catfkey, $cext, $initfile;
	var $post, $msg;
	var $browser;
	var $editmode;

	function rctags() {
	
      shtags::shtags();
	  
	  $this->editmode = GetReq('editmode');	  
	  	
	  $this->title = localize('RCTAGS_DPC',getlocal());	
	  $this->post = false;  
	  $this->msg = null;
	  
	  if (!$this->editmode) {
	  //$this->browser = null;
	   $db = GetGlobal('db');
	   $this->browser = new rcbrowser('vertical',$db,'Kategories','cpkategories','categories','id','id;ctgid;ctgoutline;ctgoutlnorder;active;view;search;cat1;cat2;cat3;cat4;cat5;id',1);	 
	   
	   //$this->browser = new rcbrowser($db,'Sqltrans','','syncsql','id','id;fid;time;date;execdate;status;sqlquery;sqlres;reference;id',1);
	   
	  /* $this->browser4 = new rcbrowser($db,'Customers','cpcustomers','customers','mail','id;code2;name;afm;address;area;attr1;voice1;voice2;fax;mail;prfdescr;attr1',0);
	   $this->browser3 = new rcbrowser($db,'Users','cpusers','users','code2','id;code2;email;fname;startdate;subscribe;username;password',0,$this->browser4,'mail',1);
	   $this->browser2 = new rcbrowser($db,'Stats','cpkategories','stats','tid','id;date;day;month;year;tid;attr1;attr2;vid;id',0,$this->browser3,'code2',7);
	   $this->browser = new rcbrowser($db,'Items','cpkategories','products','id','code5;active;sysins;itmname;uniname1;uniname2;price0;price1;price2;pricepc;itmdescr;cat0;cat1;cat2;cat3;cat4;id',1,$this->browser2,'tid',0);	  */
	  }   
	}
	
	function event($event=null) {
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') {//die("Not logged in!");//	
	     if (!GetReq('editmode'))		 
	       die("Not logged in!");//	
		 else
     	   //header("Location: cp.php?editmode=1&encoding=" . GetReq('encoding'));  
   	       die("Not logged in! <A href='../cp.php?editmode=1&encoding=".GetReq('encoding')."'>LOGIN</A>");//
	   }		
	   /////////////////////////////////////////////////////////////		
	
	    switch ($event) {

		  case 'cpeditctag' :	break;	
		  case 'cpedititag' :	break;			  							
          case 'cptags' :
		  default             : //$this->browser->event($event);
		                       if (!$this->editmode)
		                        $this->browser->nitobi_javascript();  								  
        }			
    }
	
	function action($action=null) {	
	
	   if (!$this->editmode) {
	     if (GetSessionParam('REMOTELOGIN')) 
	       $winout = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	     else  
           $winout = setNavigator(seturl("t=cp","Control Panel"),$this->title);		
	   }	 		 
	
	    switch ($action) {	
			
		  case 'cpeditctag' :	 
		  case 'cpedititag' :									
          case 'cptags'     : 
		  default           :   $out .= $this->show_tags();
                                //break; 
		  
		                        $title = $this->title;
								//$out = $this->menu();		  
		                        //$out .= $this->show_menu('cpkategories',3,$this->treeview,'ITEMS','',1);
								if (!$this->editmode)
								  $out .= $this->browser->render();							  
        }
		
        
		$win2 = new window($title,$out);
		$winout .= $win2->render("center::100%::0::group_dir_headtitle::left::0::0::");
		unset ($win2);
		 
        $winout .= '<hr>'.$this->show_tag_list();		 
		
		return ($winout);
    }	

	function show_tags() {
        $db = GetGlobal('db');
		$item = GetReq('id');	
		$cat = GetReq('cat'); 
		$greek_cat = iconv("UTF-8", "ISO-8859-7", $cat);
		$greek_item = iconv("UTF-8", "ISO-8859-7", $id);		
	    $lan = getlocal();
	    $itmkeywords = $lan?'keywords'.$lan:'keywords0';
	    $itmdescr = $lan?'descr'.$lan:'descr0';  
		$itmtitle = $lan?'title'.$lan:'title0';
	
		$code = $item ? $item : $cat;
		
        $tcode = $greek_item ? $greek_item : $greek_cat;
        if ($c = $this->is_tag($tcode))
            $code = $c;		
		//echo $code,'>',$tcode;
		
        $sSQL = "select code,tag,$itmkeywords,$itmdescr,$itmtitle from ptags ";
	    $sSQL .= " WHERE code='" . $code . "'";
	    /*$sSQL .= " and type='". $this->restype ."'";
	    if (isset($stype))
	      $sSQL .= " and stype='". $stype ."'";*/	
		//echo 'a',$code,':';  
		//echo $sSQL;
	    $resultset = $db->Execute($sSQL,2);	
	    $result = $resultset;		
		
		if ($itmcode = $result->fields[0]) {
		    $ret = $this->edit_tags($code);
		}	
		else
            $ret = $this->add_tags($code);	

			
        return ($ret);        		
    }	
  
  function add_tags($code=null) {
       $db = GetGlobal('db'); 
	   $lan = getlocal();
	   $itmkeywords = $lan?'keywords'.$lan:'keywords0';
	   $itmdescr = $lan?'descr'.$lan:'descr0'; 	
       $itmtitle = $lan?'title'.$lan:'title0';	   
 	   	 
	   if (stristr($code ,'^'))
	     $cc = explode('^',$code);  
	   else
	     $cc[] = $code;	 
	   //print_r($cc);
	   
	   $i = array_pop($cc);

       //SQL CATEGORIE WITHOUT BRANCH EXIST...
       $sSQL = "select code,tag,$itmkeywords,$itmdescr,$itmtitle from ptags ";
	   $sSQL .= " WHERE code='" . $code . "'";
	   
	   //$sSQL .= ' LIMIT 1';	 //in case of many recs...???'
	   //echo 'b',$code,':';
	   //echo $sSQL;
	  
	   $resultset = $db->Execute($sSQL,2);	
	   //print_r($resultset->fields);
	   $id = $resultset->fields['code']	; 	
	   //echo $id;
       //SQL...................	   
	   
	   if ($id) { 
	   
	     $out = $this->edit_tags($code);
	   }
       else {	 		 
  
	     if ($editmode = GetReq('editmode')) {//default form colors	
		    global $config;
			$config['FORM']['element_bgcolor1'] = 'EEEEEE';
			$config['FORM']['element_bgcolor2'] = 'DDDDDD';				
			
			$myfields = "code,tag,$itmkeywords,$itmdescr,$itmtitle,"; //'ctgid,ctgoutline,ctgoutlnorder,';
			$mytitles = localize('_code',getlocal()) . ',' . localize('_tag',getlocal()) . ',' . localize('_keywords',getlocal()) . ','. localize('_descr',getlocal()) . ','. localize('_title',getlocal());				

			//$myfields .= ',active,view,search';
			//$mytitles .= ','. localize('_active',getlocal()) . ',' . localize('_view',getlocal()) . ',' . localize('_search',getlocal());
			
			SetParam('code',$code);
			
	     } //editmode
		 
	     $gocat = GetReq('cat');	   
	     GetGlobal('controller')->calldpc_method('dataforms.setform use myform+myform+5+5+80+100+0+0');
	     GetGlobal('controller')->calldpc_method('dataforms.setformadv use 0+0+120+10+id');	  	   
	 				 
         $post = 	localize('_POST',getlocal());
	     $clear = localize('_CLEAR',getlocal());
	     $out .= GetGlobal('controller')->calldpc_method("dataforms.getform use insert.ptags+dataformsinsert&cat=$gocat&editmode=$editmode+$post+$clear+$myfields+$mytitles+++dummy");	  
		 
	   } //id
	   
       return ($out);  
  }
  
  function edit_tags($code=null) {
       $db = GetGlobal('db'); 
	   $lan = getlocal();
	   $itmkeywords = $lan?'keywords'.$lan:'keywords0';
	   $itmdescr = $lan?'descr'.$lan:'descr0'; 
       $itmtitle = $lan?'title'.$lan:'title0';  	   
	   //$cc = urlencode($code);
	   
	   $out = $code.':'.$cc;
	   
	   if ($editmode = GetReq('editmode')) {//default form colors	
		    global $config;
			$config['FORM']['element_bgcolor1'] = 'EEEEEE';
			$config['FORM']['element_bgcolor2'] = 'DDDDDD';	

			$myfields = "tag,$itmkeywords,$itmdescr,$itmtitle,"; //'ctgid,ctgoutline,ctgoutlnorder,';
			$mytitles = localize('_tag',getlocal()) . ',' . localize('_keywords',getlocal()) . ','. localize('_descr',getlocal()) . ','. localize('_title',getlocal());				
			
			//SetParam('code',$code);			
	   }	   
	    
	   $gocat = GetReq('cat');
	   
	   GetGlobal('controller')->calldpc_method('dataforms.setform use myform+myform+5+5+80+100+0+0');
	   GetGlobal('controller')->calldpc_method('dataforms.setformadv use 0+0+120+10');	  

				 	                    
       $post = 	localize('_POST',getlocal());
	   $clear = localize('_CLEAR',getlocal());
	   $out .= GetGlobal('controller')->calldpc_method("dataforms.getform use update.ptags+dataformsupdate&cat=$gocat&editmode=$editmode+$post+$clear+$myfields+$mytitles++code=$code+dummy");	  
	   	
	   return ($out);		 
  }
  
  //check if code is a tag..so sent back original code
  function is_tag($code=null) {
        $db = GetGlobal('db');	
		
		if (!$code)
		    return false;
		
        $sSQL = "select code,tag from ptags ";
	    $sSQL .= " WHERE tag='" . $code . "'";
		//echo $sSQL;
	    $resultset = $db->Execute($sSQL,2);	
	    $result = $resultset;  
		
        if ($istag = $result->fields[1]) 
            return ($result->fields[0]);		
		//else	
            //return ($code);//as-is..no tag
		return false;	
  }
  
  function show_tag_list() {
       $db = GetGlobal('db'); 
	   $lan = getlocal();
	   $itmkeywords = $lan?'keywords'.$lan:'keywords0';
	   $itmdescr = $lan?'descr'.$lan:'descr0'; 
       $itmtitle = $lan?'title'.$lan:'title0';
	   $ret = array();

	   //title head
	   $viewdata[] = localize('_code',getlocal()); 
       $viewattr[] = "left;5%";		  
	   $viewdata[] = localize('_tag',getlocal()); 
       $viewattr[] = "left;20%";	
	   $viewdata[] = localize('_title',getlocal());
       $viewattr[] = "left;25%";	
	   $viewdata[] = localize('_descr',getlocal()); 
       $viewattr[] = "left;25%";	
	   $viewdata[] = localize('_keywords',getlocal());
       $viewattr[] = "left;25%";			  
		  
	   $myrec = new window('',$viewdata,$viewattr);
	   $title = $myrec->render("center::100%::0::group_article_table::left::3::3::");
	   unset ($myrec);
	   unset ($viewdata);
	   unset ($viewattr);	 	   

       $sSQL = "select code,tag,$itmkeywords,$itmdescr,$itmtitle from ptags ";
	   
	   if ($id = GetReq('id')) {//item
	     $first_letter = $id{0}; //echo '>',$first_letter;
		 $length = strlen($id);
	     //$sSQL .= " WHERE code like '$first_leter%'";	
		 $sSQL .= " WHERE length(code)=$length";
	   }	 
	   elseif ($cat = GetReq('cat')) {
	     $root = array_shift(explode('^',$cat));
	     $sSQL .= " WHERE code like '$root%'";
	   }	 
       //else all	   
	   
	   $resultset = $db->Execute($sSQL,2);	
	   //print_r($resultset->fields);
	   
	   foreach ($resultset as $n=>$rec) {
	      
          $cc = $id ? $rec['code'] : ($n+1);	  
          $link = $id ? seturl('t=cpedittag&editmode=1&id='.$rec['code'],$cc) :
                  ($cat ? seturl('t=cpedittag&editmode=1&cat='.$rec['code'],$cc) : 'null'); 			  
		  
	      $viewdata[] = $link;//$cc;//$rec['code']; 
          $viewattr[] = "left;5%";		  
	      $viewdata[] = $rec['tag'] ? $rec['tag'] : "null"; 
          $viewattr[] = "left;20%";	
	      $viewdata[] = $rec[$itmtitle] ? $rec[$itmtitle] : "null"; 
          $viewattr[] = "left;25%";	
	      $viewdata[] = $rec[$itmdescr] ? $rec[$itmdescr] : "null"; 
          $viewattr[] = "left;25%";	
	      $viewdata[] = $rec[$itmkeywords] ? $rec[$itmkeywords] : "null"; 
          $viewattr[] = "left;25%";			  
		  
	      $myrec = new window('',$viewdata,$viewattr);
	      $ret[] = $myrec->render();
		  unset ($myrec);
	      unset ($viewdata);
	      unset ($viewattr);	   
	   }
	   	   
	   
	   $myrec = new window($title,$ret);
	   $out .= $myrec->render("center::100%::0::group_article_table::left::3::3::");
	   return ($out); 		  
  }
  
};
}
?>