<?php
$__DPCSEC['RCKATEGORIES_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("RCKATEGORIES_DPC")) && (seclevel('RCKATEGORIES_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCKATEGORIES_DPC",true);

$__DPC['RCKATEGORIES_DPC'] = 'rckategories';

$a = GetGlobal('controller')->require_dpc('rc/rcbrowser.lib.php');
require_once($a);

$d = GetGlobal('controller')->require_dpc('shop/shkategories.dpc.php');
require_once($d);

$__EVENTS['RCKATEGORIES_DPC'][0]='cpkategories';
$__EVENTS['RCKATEGORIES_DPC'][1]='cats';
$__EVENTS['RCKATEGORIES_DPC'][2]='editcats';
$__EVENTS['RCKATEGORIES_DPC'][3]='openfolder';
$__EVENTS['RCKATEGORIES_DPC'][4]='savecats';
$__EVENTS['RCKATEGORIES_DPC'][5]='delcats';
$__EVENTS['RCKATEGORIES_DPC'][6]='addcats';
$__EVENTS['RCKATEGORIES_DPC'][7]='gencats';
$__EVENTS['RCKATEGORIES_DPC'][8]='disablecat';
$__EVENTS['RCKATEGORIES_DPC'][9]='cpread';
$__EVENTS['RCKATEGORIES_DPC'][10]='cpwrite';
$__EVENTS['RCKATEGORIES_DPC'][11]='cpbradd';
$__EVENTS['RCKATEGORIES_DPC'][12]='cpbredit';
$__EVENTS['RCKATEGORIES_DPC'][13]='cpbrdel';
$__EVENTS['RCKATEGORIES_DPC'][14]='cpaddcat';
$__EVENTS['RCKATEGORIES_DPC'][15]='cpeditcat';
$__EVENTS['RCKATEGORIES_DPC'][16]='cpeditframe';

$__ACTIONS['RCKATEGORIES_DPC'][0]='cpkategories';
$__ACTIONS['RCKATEGORIES_DPC'][1]='cats';
$__ACTIONS['RCKATEGORIES_DPC'][2]='editcats';
$__ACTIONS['RCKATEGORIES_DPC'][3]='openfolder';
$__ACTIONS['RCKATEGORIES_DPC'][4]='savecats';
$__ACTIONS['RCKATEGORIES_DPC'][5]='delcats';
$__ACTIONS['RCKATEGORIES_DPC'][6]='addcats';
$__ACTIONS['RCKATEGORIES_DPC'][7]='gencats';
$__ACTIONS['RCKATEGORIES_DPC'][8]='disablecat';
$__ACTIONS['RCKATEGORIES_DPC'][9]='cpread';
$__ACTIONS['RCKATEGORIES_DPC'][10]='cpwrite';
$__ACTIONS['RCKATEGORIES_DPC'][11]='cpbradd';
$__ACTIONS['RCKATEGORIES_DPC'][12]='cpbredit';
$__ACTIONS['RCKATEGORIES_DPC'][13]='cpbrdel';
$__ACTIONS['RCKATEGORIES_DPC'][14]='cpaddcat';
$__ACTIONS['RCKATEGORIES_DPC'][15]='cpeditcat';
$__ACTIONS['RCKATEGORIES_DPC'][16]='cpeditframe';

$__LOCALE['RCKATEGORIES_DPC'][0]='RCKATEGORIES_DPC;SQL Kategories;Κατηγορίες SQL';
$__LOCALE['RCKATEGORIES_DPC'][1]='_LEVEL1;Category Level 1;Κατηγορία επιπέδου 1';
$__LOCALE['RCKATEGORIES_DPC'][2]='_LEVEL2;Category Level 2;Κατηγορία επιπέδου 2';
$__LOCALE['RCKATEGORIES_DPC'][3]='_LEVEL3;Category Level 3;Κατηγορία επιπέδου 3';
$__LOCALE['RCKATEGORIES_DPC'][4]='_LEVEL4;Category Level 4;Κατηγορία επιπέδου 4';
$__LOCALE['RCKATEGORIES_DPC'][5]='_LEVEL5;Category Level 5;Κατηγορία επιπέδου 5';
$__LOCALE['RCKATEGORIES_DPC'][6]='_NEWLEVEL;<b>New category</b>;<b>Νέα κατηγορία</b>';
$__LOCALE['RCKATEGORIES_DPC'][7]='_FLEVEL1;Foreign Alias Level 1;Κατηγορία επιπέδου 1 (Ξενόγλωσση)';
$__LOCALE['RCKATEGORIES_DPC'][8]='_FLEVEL2;Foreign Alias Level 2;Κατηγορία επιπέδου 2 (Ξενόγλωσση)';
$__LOCALE['RCKATEGORIES_DPC'][9]='_FLEVEL3;Foreign Alias Level 3;Κατηγορία επιπέδου 3 (Ξενόγλωσση)';
$__LOCALE['RCKATEGORIES_DPC'][10]='_FLEVEL4;Foreign Alias Level 4;Κατηγορία επιπέδου 4 (Ξενόγλωσση)';
$__LOCALE['RCKATEGORIES_DPC'][11]='_FLEVEL5;Foreign Alias Level 5;Κατηγορία επιπέδου 5 (Ξενόγλωσση)';
$__LOCALE['RCKATEGORIES_DPC'][12]='_FNEWLEVEL;<b>New category alias</b>;<b>Νέα κατηγορία (Ξενόγλωσση)</b>';
$__LOCALE['RCKATEGORIES_DPC'][13]='_ctgid;Id;A/A';
$__LOCALE['RCKATEGORIES_DPC'][14]='_ctgoutline;Branch;Κλαδί';
$__LOCALE['RCKATEGORIES_DPC'][15]='_ctgoutlnorder;Branch order;Ταξινόμηση';
$__LOCALE['RCKATEGORIES_DPC'][16]='_search;Search;Αναζητήσιμο';
$__LOCALE['RCKATEGORIES_DPC'][17]='_active;Active;Ενεργό';
$__LOCALE['RCKATEGORIES_DPC'][18]='_view;Show;Εμφανές';
$__LOCALE['RCKATEGORIES_DPC'][19]='_OK;Success;Επιτυχώς';

class rckategories extends shkategories {
  
    var $title, $path, $catpath, $catfkey, $cext, $initfile;
	var $post, $msg;
	var $browser;
	var $editmode;
	var $charts;
	var $ajaxLink, $hasgraph, $urlbase;	

	function rckategories() {
	
      shkategories::shkategories();
	  
	  $this->editmode = GetReq('editmode');	  
	  	
	  $this->title = localize('RCKATEGORIES_DPC',getlocal());	
	  $this->post = false;  
	  $this->msg = null;
	  
	  $this->urlbase = paramload('SHELL','urlbase').'/';	  
	  
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
	  
	  //overrite csep...?
      $csep = remote_paramload('SHKATEGORIES','csep',$this->path); 
      $this->cseparator = $csep ? $csep : '^';	
	  
	  $this->charts = new swfcharts;	
	  $this->ajaxLink = seturl('t=cpvstatsshow&statsid=');		  
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
		  case 'cpaddcat'  :	break;
		  case 'cpeditcat' :	break;		
		
		  //rcbrowser hacks
		  case 'cpbradd'  :	break;
		  case 'cpbredit' :	break;
		  case 'cpbrdel'  : break; 				
		
          case 'disablecat' :   
		                        $this->disable_category(GetReq('cat'));
		                        break;				
		  case "savecats":      $this->save_category();
		                        $this->post = true;
		                        break;		
		  case "delcats" :      $this->delete_category();
		                        $this->post = true;
		                        break;										
          case 'openfolder' : 
		                        break;		
          case 'cats' : 
		                        break;	
		  case 'gencats'  :     
		                        break;									
		  case 'addcats'  :
		                        break;								
          case 'editcats' : 
		                        break;
		  case 'cpread'  :     //$this->browser->get_records(); 
		                        rcbrowser::getgrid_records();														
								break;
		  case 'cpwrite' :     //$this->browser->set_records(); 
		                        rcbrowser::setgrid_records();
		                        break;
		  case 'cpeditframe':   echo $this->loadframe('editcat');
								die();
		                        break;								
          case 'cpkategories' :
		  default             : //$this->browser->event($event);
		                       //if (!$this->editmode)
		                        //$this->browser->nitobi_javascript();
                               //else
							    $this->hasgraph = $this->charts->create_chart_data('statisticscat',"where year >=2000 and attr1='".urldecode(GetReq('cat'))."'");
                                $this->grid_javascript();							   
        }			
    }
	
	function action($action=null) {	
	
	   /*if (!$this->editmode) {
	     if (GetSessionParam('REMOTELOGIN')) 
	       $winout = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	     else  
           $winout = setNavigator(seturl("t=cp","Control Panel"),$this->title);		
	   }*/	 		 
	
	    switch ($action) {	
		  case 'cpaddcat'  :	$out .= $this->add_category2(null,null,null,null,'d',true); break;
		  case 'cpeditcat' :	$out .= $this->edit_category();break;			
		
		  //rcbrowser hacks
          case 'cpbradd'      : $out .= rcbrowser::add_record(); break;
          case 'cpbredit'     : $out .= rcbrowser::edit_record(); break;		  							
          case 'cpbrdel'      : $out .= rcbrowser::del_record('rckategories.toggle_category'); break;		
		  		
		  case "delcats" :      $title = $this->title;
		                        $out .= 'delete';
		                        break;		
		  case "savecats":      $title = $this->title;
		                        $out .= 'save';
		                        break;			
		
          case 'openfolder' :   $title = $this->tree_navigation('cats',null,0);
		                        $out .= 'openfolder';
		                        break;	
		  case 'gencats'  :     $title = 'Generate Categories';
		                        $out .= $this->generate_categories(GetReq('keys'));
		                        break;									
          case 'addcats' :      $add=true;								
          case 'editcats':      $title = $this->tree_navigation('cats',null,0);
		                        $out .= 'edit /add';
		                        break;								
          case 'cats'    :      $title = $this->title;
		                        $out = $this->show_menu('cpkategories',3,$this->treeview,GetReq('cat'),'',1);
		                        break;									
          case 'disablecat'   :  								
          case 'cpkategories' : 
		  default             :	//$title = $this->title;
								//$out = $this->menu();		  
		                        //$out .= $this->show_menu('cpkategories',3,$this->treeview,'ITEMS','',1);
								//if (!$this->editmode)
								  //$out .= $this->browser->render();	
                                //else
                                  $out .= $this->edit_kategories();								  
        }
		
        
		$win2 = new window(null/*$title*/,$out);
		$winout .= $win2->render();//"center::100%::0::group_dir_headtitle::left::0::0::");
		unset ($win2);
		  					
		
		return ($winout);
    }	
	
	
	function grid_javascript() {
	
      if (iniload('JAVASCRIPT'))  {  		      
		   
	       $code = $this->init_grids();	     		

		   $js = new jscript;			   
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }		
	}

	function init_grids() {
	    $editurl = seturl("t=cpeditframe&id=");
  		
		$out = "
function update_stats_id() {
  var str = arguments[0];
  var str1 = arguments[1];
  var str2 = arguments[2];
  
  
  statsid.value = str;
  //alert(statsid.value);
  sndReqArg('$this->ajaxLink'+statsid.value,'stats');
  
  return str1+' '+str2;
}

function edit_cat() {
  var str = arguments[0];
  var str1 = arguments[1];
  var str2 = arguments[2];  
  var taskid;
  var custid;
  
  taskid = str;  
  custid = str1;
  sndReqArg('$editurl'+taskid,'editcat');
}
";
        $out .= "\r\n";
        return ($out);
	}	
	
    function set_viewable() {
     
	 $g = GetReq('g');
	 
	 $group = explode("^",$g);
	 $cat = $group[count($group)-1];
	 //echo $cat.'>>>>>>>>>>>>>>>>'; 
	 
     $file = paramload('SHELL','prpath')."exclude_sen.txt";
	 	  
     if (file_exists($file)) {
	 
         $f = fopen($file,"a");
	     $data = fwrite($f,",".$g);
	     fclose($f);   
	 }	  
    }
  
    function menu($adv=null) {
       $g = GetReq('cat');
	   
	   $out .= $this->msg; 
  
       /*if ($adv) {
	   
         $cmd = seturl('t=addcats&cat='.GetReq('cat').'&sel='.GetReq('sel'),'Add Category') . '|';	   
         $cmd .= seturl('t=delcats&cat='.GetReq('cat').'&sel='.GetReq('sel'),'Delete Category') . '|';		 
	   }*/
  
       //$cmd .= seturl("t=gencats","Generate categories") . "|";
       $cmd .= seturl("t=disablecat&cat=$g","Disable category") . "|";   
       $cmd .= seturl("t=enablecat&cat=$g","Enable category");		   
  
	   $myadd = new window('',$cmd);
	   $out .= $myadd->render("center::100%::0::group_article_selected::right::0::0::");	   
	   unset ($myadd);   
	   
	   return ($out);
    }
  
    //export text ... bug in first sub tree (write main cat 2 times)
    function generate_categories($keys=false,$current=null,$leaf=null,$depth=0) {
  
  
        return ($out);
    }
  
    function disable_category($g) {
       $db = GetGlobal('db');	
	   
	   if (strlen(trim($g))>0) {
	     $group = explode("^",$g);   //print_r($group);
	     $mg = count($group);
	     $depth = ($mg ? $mg : 0); //echo 'DEPTH:',$depth;
	   }
	   else
	     $depth = 0;	    
	   
	   switch ($depth) {
	  /*     case 1 : $sSQL = "select cat3,cat2 from categories where "; break;
		   case 2 : $sSQL = "select cat4,cat3,cat2 from categories where "; break;
		   case 3 : $sSQL = "select cat5,cat4,cat3,cat2 from categories where "; break;
		   case 4 : $sSQL = "select null from categories"; break;*/
		   default: $sSQL = "update categories set ctgid=0 where "; 
	   }
	   //$sSQL .= ' where '; 
	   switch ($depth) {
	       case 4 : 
	       case 3 : $sSQL .= "cat4='" . str_replace('_',' ',$group[2]) . "' and ";
		   case 2 : $sSQL .= "cat3='" . str_replace('_',' ',$group[1]) . "' and ";
		   case 1 : $sSQL .= "cat2='" . str_replace('_',' ',$group[0]) . "' and "; //break;
		   default: $sSQL .= "ctgid>0";//"cat1='ΚΑΤΗΓΟΡΙΕΣ ΕΙΔΩΝ'";/*if (seclevel('SENPTREEADMIN_',$this->userLevelID)) {
/*		              $sSQL .= "CTGLEVEL1='ΚΑΤΗΓΟΡΙΕΣ ΕΙΔΩΝ'";
					}
					else {*/
					  //$sSQL .= " and cat2 not like 'LOST+FOUND')"; 
					//}  
	   }	
	   //echo $sSQL;   
	   
	    
	   $result = $db->Execute($sSQL,2);
   	   if ($result) {  
	     $this->msg = str_replace('^','>',$group) . 'disabled!';
	   }
						   		     
    }
  
    //2nd version..handle inside from rcbrowser
    function disable($id=null) {
       $db = GetGlobal('db');	
	   $id = GetReq('id');//is the recid of rcbrowser call
	   
	   //get ctgid (positive number)
	   $sSQL = "select ctgid from categories where id=" . $id;     
	   $result = $db->Execute($sSQL,2);	
	   if ($result) {      
	     while(!$result->EOF) {	      
		   $ctgid = $result->fields[0];
		   $result->MoveNext();			 		 
		 }
       }	
	   //make ctgid negative number	
	   $sSQL = "update categories set ctgid=-" . $ctgid . " where id=" . $id;  
	   $result = $db->Execute($sSQL,2);
   	   if ($result) {  
	     $ret = ' disabled!';
	   }
	   
	   return ($ret);  						   		     
    }  
  
    //handle inside from rcbrowser
    function enable($id=null) {
       $db = GetGlobal('db');	
	   $id = GetReq('id');//is the recid of rcbrowser call
	   
	   //get ctgid (negative number)
	   $sSQL = "select ctgid from categories where id=" . $id;     
	   $result = $db->Execute($sSQL,2);	
	   if ($result) {      
	     while(!$result->EOF) {	      
		   $ctgid = $result->fields[0];
		   $result->MoveNext();			 		 
		 }
       }	
	   //make ctgid negative number	
	   $sSQL = "update categories set ctgid=" . abs($ctgid) . " where id=" . $id;  
	   $result = $db->Execute($sSQL,2);
   	   if ($result) {  
	     $ret = ' enabled!';
	   }
	   
	   return ($ret);   
						   		     
    }  
  
    function toggle_category() {
       $db = GetGlobal('db');	
	   $id = GetReq('id');//is the recid of rcbrowser call 
	   
	   
	   //get ctgid (positive number)
	   $sSQL = "select ctgid from categories where id=" . $id;     
	   $result = $db->Execute($sSQL,2);	
	   if ($result) {      
	     while(!$result->EOF) {	      
		   $ctgid = $result->fields[0];
		   $result->MoveNext();			 		 
		 }
       }
	   
	   if ($ctgid>0)		    
	     $ret = $this->disable($id);
	   else	 
	     $ret = $this->enable($id);
		 
	   return ($ret);  
    } 
  
    //import to categories table from text cats (called from rccategories)...disabled
    function generate_from_text($cat,$ctgid) {
    $db = GetGlobal('db');	  

    //echo $cat;
	$tcat = explode('^',str_replace('<br>','',$cat));//br came from text processing at the end of string
	
    $sSQL = "insert into categories (ctgid,cat1";
    foreach ($tcat as $id=>$c) {
	  $cid = $id+2;
	  $sSQL .= ',cat'.$cid;	
	}  
	$sSQL.= ") values (";
	$sSQL.= $ctgid . ",'ΚΑΤΗΓΟΡΙΕΣ ΕΙΔΩΝ'";
	
	reset($tcat);
	foreach ($tcat as $id=>$c)
	  $sSQL .= ",'".trim($c)."'";
	$sSQL .= ')';
	
	//echo $sSQL,'<br>';  
	
	$result = $db->Execute($sSQL,2);
   	if (!$result) {  
	   echo $sSQL,' failed!<br>';
	}	
    }
  
    function add_category2($width=null, $height=null, $rows=null, $editlink=null, $mode=null, $noctrl=false) {
	    $height = $height ? $height : 800;
        $rows = $rows ? $rows : 36;
        $width = $width ? $width : null; //wide
        $mode = $mode ? $mode : 'd';
		$noctrl = $noctrl ? 0 : 1;
        $editlink = $editlink ?
		            $editlink :
		            seturl("t=cpeditcat&editmode=1&cat={cat2}");
							
	
	    if (!defined('MYGRID_DPC')) 
		   return ($this->add_category()); 
		   
        $lan = getlocal()?getlocal():0;
        $root_name = 'ITEMS';	 
	    $selected_cat = GetReq('cat') ? $root_name . '^' . str_replace('_',' ',GetReq('cat')) : $root_name;	
	    if (stristr($selected_cat ,'^'))
	     $cats = explode('^',$selected_cat);  
	    else
	     $cats[] = $selected_cat; 

		$myfields = 'id,ctgid,';//ctgoutline,ctgoutlnorder,';
		$mytitles = localize('id',getlocal()) . ',' . localize('_ctgid',getlocal()) . ',';// . localize('_ctgoutline',getlocal()) . ',' . localize('_ctgoutlnorder',getlocal()) . ',';				 		
		$myfields .= 'active,view,search,';
		$mytitles .= localize('_active',getlocal()) . ',' . localize('_view',getlocal()) . ',' . localize('_search',getlocal()) . ',';
        $myfields .= "cat1,cat2,cat3,cat4,cat{$lan}1,cat{$lan}2,cat{$lan}3,cat{$lan}4";
        $mytitles .= localize('_LEVEL1',getlocal()).','.localize('_LEVEL2',getlocal()) .','.localize('_LEVEL3',getlocal()) .','.
		             localize('_LEVEL4',getlocal()).','.localize('_LEVEL1',getlocal()).','.localize('_LEVEL2',getlocal()) .','.
		   		     localize('_LEVEL3',getlocal()).','.localize('_LEVEL4',getlocal());			
						 		 
		
		$xsSQL = 'select * from (select '.$myfields . ' from categories) as o';
        //$xsSQL = "select id,ctgid,ctgoutline,ctgoutlnorder,cat1,cat2,cat3,cat4,cat5,cat01,cat02,cat03,cat04,cat05,cat11,cat12,cat13,cat14,cat15 from categories ";

		$farr = explode(',',$myfields);
		$tarr = explode(',',$mytitles);
	    foreach ($farr as $i=>$t) {
		   if ($t=='id') {
		      $type = 6;
			  $edit = null;	
              $options = null;					    
		   }
		   elseif ($t=='ctgid') {
		      if ((($mode=='e')||($mode=='r')) &&($editlink)) {//only edit/read mode
				$type = 'link';
				$edit = 6;			  
				$options = $editlink ;
			  }
			  else {
		        $type = 6;
			    $edit = 1;	
                $options = null;	
              }				
		   }		   
		   elseif (stristr($t,'active') || stristr($t,'search') || stristr($t,'view')) {
		      $type = 'boolean';
			  $edit = 1;
			  $options = "1:0";
		   }
		   elseif (stristr($t,'cat')) {
		      $type = 10;//'select';//if jqgrid is not paid, not applicable
			  $edit = 1;
			  /*switch ($t) {
			    case 'cat4'  : $options = str_replace('_',' ',$cats[4]);break;////$options = $cats[3] ? $cats[3].":".$cats[3] :null; break;
				case 'cat3'  : $options = str_replace('_',' ',$cats[3]);break;////$options = $cats[2] ? $cats[2].":".$cats[2] :null; break;
			    case 'cat2'  : $options = str_replace('_',' ',$cats[2]);break;////$options = $cats[1] ? $cats[1].":".$cats[1] :null; break;
			    case 'cat1'  : //$options = $cat1.':Male;F:Female;D:'.str_replace('_',' ',$cats[1]);break;
				               //$options = $cats[0] ? $cats[0].":".$cats[0] :null; break;
				               $options = GetGlobal('controller')->calldpc_method("mygrid.mylookup use select distinct cat3 from categories");
				               break;
			    case 'cat0'  : $options = str_replace('_',' ',$cats[0]);break;////break;
                default      : $options = null;			  
			  }*/
		   }
		   else {
		      $type = 20;
			  $edit = 1;
			  $options = null; 
		   }
	       $title = $tarr[$i];//localize('_'.$t,getlocal());
		   GetGlobal('controller')->calldpc_method("mygrid.column use grid1+$t|".$title."|$type|$edit|$options");
		} 		
			
		$out .= GetGlobal('controller')->calldpc_method("mygrid.grid use grid1+categories+$xsSQL+$mode++id+$noctrl+1+$rows+$height+$width");
	    return ($out);
	
    }
  
    function add_category() {
       $db = GetGlobal('db'); 
 	   
       $root_name = 'ITEMS';	 
	   $selected_cat = GetReq('cat') ? $root_name . '^' . str_replace('_',' ',GetReq('cat')) : $root_name;	
	   if (stristr($selected_cat ,'^'))
	     $cats = explode('^',$selected_cat);  
	   else
	     $cats[] = $selected_cat;	 
	   //print_r($cats);	


       //SQL CATEGORIE WITHOUT BRANCH EXIST...
       $sSQL = "select id from categories ";
	   $sSQL .= " WHERE ";

	   foreach ($cats as $i=>$c) {
	     $idx = $i+1;//+2;...root_name added
	     $where[] = "cat$idx='" . str_replace('_',' ',$c) . "'";	
	   }	 
	   $sSQL .= implode(' and ',$where);
	   
	   $nextbranch = $idx+1;
	   $sSQL .= ' and (cat' . $nextbranch . ' is NULL' . ' or cat' . $nextbranch . "='')";
	   
	   $sSQL .= ' LIMIT 1';	 //in case of many recs...???'
	   //echo $sSQL;
	  
	   $resultset = $db->Execute($sSQL,2);	
	   //print_r($resultset->fields);
	   $id = $resultset->fields['id']	; 	
	   //echo $id;
       //SQL...................	   
	   
	   if ($id) { //exist category with empty leaf (last catN)
	   
	     $out = $this->edit_category(1);
	   }
       else {	 

         //SQL CATEGORIE BRANCH EXIST SO MAKE A NEW...GETTING CTG DATA...
         $sSQL = "select id,ctgid,ctgoutline,ctgoutlnorder,cat1,cat2,cat3,cat4,cat5,cat01,cat02,cat03,cat04,cat05,cat11,cat12,cat13,cat14,cat15 from categories ";
	     $sSQL .= " WHERE ";

	     foreach ($cats as $iz=>$cz) {
	       $idy = $iz+1;
	       $where2[] = "cat$idy='" . str_replace('_',' ',$cz) . "'";	
	     }	 

	     $sSQL .= implode(' and ',$where2);   
	     //$sSQL .= ' LIMIT 1';	 //in case of many recs...???'

	     $resultset = $db->Execute($sSQL,2);	
	     //print_r($resultset->fields);
		 
		 $nextbranchid = $db->Affected_Rows() + 1;		 
	     //echo $sSQL,'>', $nextbranchid;	
		 //PRINT_R($resultset->fields);
		 //echo $resultset->fields['ctgid'];
         //SQL...................			 
  
	     if ($editmode = GetReq('editmode')) {//default form colors	
		    global $config;
			$config['FORM']['element_bgcolor1'] = 'EEEEEE';
			$config['FORM']['element_bgcolor2'] = 'DDDDDD';				
			
			$myfields = 'ctgid,ctgoutline,ctgoutlnorder,';
			$mytitles = localize('_ctgid',getlocal()) . ',' . localize('_ctgoutline',getlocal()) . ',' . localize('_ctgoutlnorder',getlocal()) . ',';				

			if (GetReq('cat')) {//($selected_cat) { //..selected_cat has 'ITEM'..but works fine!
 	          SetParam('ctgid', $resultset->fields['ctgid']+1);    
 	          SetParam('ctgoutline', $resultset->fields['ctgoutline']?$resultset->fields['ctgoutline']. '.'.$nextbranchid : $nextbranchid);  
 	          SetParam('ctgoutlnorder', $resultset->fields['ctgoutlnorder']+1);  			
			
              // STANDART DESCR ....			
              $fields = 'cat1,cat2,cat3,cat4,cat5';
			  $catfields = explode(',',$fields);
              $titles = '_LEVEL1,_LEVEL2,_LEVEL3,_LEVEL4,_LEVEL5';				
			  $cattitles = explode(',',$titles);
		  		
			  foreach ($cats as $i=>$mycat) {
				if ($catlevel = $mycat) {
				  $_fields[] = $catfields[$i];
				  //SetParam($catfields[$i],$catlevel); //set predef cat level
				  SetParam($catfields[$i],$resultset->fields[$catfields[$i]]); 
				  $localized_cattitles[] = localize($cattitles[$i],getlocal());
			    }
			  }
              $_fields[] = $catfields[$i+1]; //plus 1 cat			  
			  $localized_cattitles[] = localize('_NEWLEVEL',getlocal());	
			  
              // FOREIGN DESCR ....0			  
              $fields0 = 'cat01,cat02,cat03,cat04,cat05';
			  $catfields0 = explode(',',$fields0);
              $titles0 = '_FLEVEL1,_FLEVEL2,_FLEVEL3,_FLEVEL4,_FLEVEL5';				
			  $cattitles0 = explode(',',$titles0);
			
			  reset($cats);
			  foreach ($cats as $i=>$mycat) {
			    //echo $i,$mycat;
			    if ($catlevel = $mycat) {
				  $_fields0[] = $catfields0[$i];
				  SetParam($catfields0[$i],$resultset->fields[$catfields0[$i]]); 
				  $localized_cattitles0[] = localize($cattitles0[$i],getlocal());
			    }
			  }	
              $_fields0[] = $catfields0[$i+1]; //plus 1 cat			  
			  $localized_cattitles0[] = localize('_FNEWLEVEL',getlocal());	
			  
              // FOREIGN DESCR ....1			  
              $fields1 = 'cat11,cat12,cat13,cat14,cat15';
			  $catfields1 = explode(',',$fields1);
              $titles1 = '_FLEVEL1,_FLEVEL2,_FLEVEL3,_FLEVEL4,_FLEVEL5';				
			  $cattitles1 = explode(',',$titles1);
			
			  reset($cats);
			  foreach ($cats as $i=>$mycat) {
			    //echo $i,$mycat;
			    if ($catlevel = $mycat) {
				  $_fields1[] = $catfields1[$i];
				  SetParam($catfields1[$i],$resultset->fields[$catfields1[$i]]);
				  $localized_cattitles1[] = localize($cattitles1[$i],getlocal());
			    }
			  }				  

              $_fields1[] = $catfields1[$i+1]; //plus 1 cat			  
			  $localized_cattitles1[] = localize('_FNEWLEVEL',getlocal());
			  
			  $myfields .= implode(',',$_fields) . ',' . implode(',',$_fields0) . ',' . implode(',',$_fields1);
			  $mytitles .= implode(',',$localized_cattitles) . ',' . implode(',',$localized_cattitles0) . ',' . implode(',',$localized_cattitles1);			  
			}
			else {//root cat
              //echo 'ROOT:';
 	          SetParam('ctgid', $nextbranchid);   	 
 	          SetParam('ctgoutline', $nextbranchid);  
 	          SetParam('ctgoutlnorder', $nextbranchid); 
			  
              $myfields .= 'cat1,cat2,cat01,cat02,cat11,cat12';
              $mytitles .= localize('_LEVEL1',getlocal()).','.localize('_NEWLEVEL',getlocal()) .','.
			               localize('_FLEVEL1',getlocal()).','.localize('_FNEWLEVEL',getlocal()).','.
						   localize('_FLEVEL1',getlocal()).','.localize('_FNEWLEVEL',getlocal());			
			 
			  SetParam('cat1', $resultset->fields['cat1']?$resultset->fields['cat1']:$root_name); 
              SetParam('cat01', $resultset->fields['cat01']?$resultset->fields['cat01']:$root_name); 
              SetParam('cat11', $resultset->fields['cat11']?$resultset->fields['cat11']:$root_name); 			  			  
			}
			
			$myfields .= ',active,view,search';
			$mytitles .= ','. localize('_active',getlocal()) . ',' . localize('_view',getlocal()) . ',' . localize('_search',getlocal());
			
			SetParam('active',1);
			SetParam('view',1);
			SetParam('search',1);
			//echo $myfields,'-',$mytitles;
			
	     } //editmode
		 
	   
	     $gocat = GetReq('cat'); 
	     GetGlobal('controller')->calldpc_method('dataforms.setform use myform+myform+5+5+50+100+0+0');
	     GetGlobal('controller')->calldpc_method('dataforms.setformadv use 0+0+50+10+id');	  	   
		 //GetGlobal('controller')->calldpc_method("dataforms.setformgoto use PHPDAC:rcupload.editmode_upload_files:".localize('_OK',getlocal()));
	     //GetGlobal('controller')->calldpc_method('dataforms.setformtemplate use cpitemsadd'); //use template		   
				 				 
         $post = 	localize('_POST',getlocal());
	     $clear = localize('_CLEAR',getlocal());
	     $out .= GetGlobal('controller')->calldpc_method("dataforms.getform use insert.categories+dataformsinsert&cat=$gocat&editmode=$editmode+$post+$clear+$myfields+$mytitles++dummy+dummy");	  
		 
	   } //id
	   
	   if (defined('MYGRID_DPC')) {
		
           $xsSQL = "select id,ctgid,ctgoutline,ctgoutlnorder,cat1,cat2,cat3,cat4,cat5,cat01,cat02,cat03,cat04,cat05,cat11,cat12,cat13,cat14,cat15 from categories ";
		   $out .= GetGlobal('controller')->calldpc_method("mygrid.grid use grid1+categories+$xsSQL+d++id+1+1+20+400");
	   }		   
	   
       return ($out);  
    }
  
    function edit_category($addcat=null) {
       $db = GetGlobal('db'); 
	   
       $root_name = 'ITEMS';	   
	   if ($root_name)	   
	     $selected_cat = $root_name . '^' .  GetReq('cat');	   
	   //$selected_cat = GetReq('cat');
	   //echo $selected_cat;	   
	   
	   if (stristr($selected_cat ,'^'))
	     $cats = explode('^',$selected_cat);  
	   else
	     $cats[] = $selected_cat;	
	   //print_r($cats);	  
  
       $sSQL = "select id from categories ";
	   $sSQL .= " WHERE ";
	   
	   foreach ($cats as $i=>$c) {
	     $id = $i+1;//+2;...root_name added
	     $where[] = "cat$id='" . str_replace('_',' ',$c) . "'";	
	   }	 
	   $sSQL .= implode(' and ',$where);
	   $sSQL .= ' LIMIT 1';	 //in case of many recs...???'
	   //echo $sSQL;
	  
	   $resultset = $db->Execute($sSQL,2);	
	   //print_r($resultset->fields);
	   $id = $resultset->fields['id']	; 	   
	   
	   
	   if ($editmode = GetReq('editmode')) {//default form colors	
		    global $config;
			$config['FORM']['element_bgcolor1'] = 'EEEEEE';
			$config['FORM']['element_bgcolor2'] = 'DDDDDD';	

			$myfields = 'ctgid,ctgoutline,ctgoutlnorder,';	
			$mytitles = localize('_ctgid',getlocal()) . ',' . localize('_ctgoutline',getlocal()) . ',' . localize('_ctgoutlnorder',getlocal()) . ',';			
						
            $fields = 'cat1,cat2,cat3,cat4,cat5';
			$catfields = explode(',',$fields);
            $titles = '_LEVEL1,_LEVEL2,_LEVEL3,_LEVEL4,_LEVEL5';				
			$cattitles = explode(',',$titles);	
			
            reset($cats);
			foreach ($cats as $i=>$mycat) {
			  //echo $i,$mycat;
			  if ($catlevel = $mycat) {
				  $_fields[] = $catfields[$i];
				  $localized_cattitles[] = localize($cattitles[$i],getlocal());
			  }
			}	

			//when add a category..comming from add_category procedure  ...modify an existing rec with empty leaf
            if ($addcat) {
              $_fields[] = $catfields[$i+1]; //plus 1 cat		
			  $localized_cattitles[] = localize('_NEWLEVEL',getlocal());			  
            } 			
			
            $fields0 = 'cat01,cat02,cat03,cat04,cat05';
			$catfields0 = explode(',',$fields0);
            $titles0 = '_FLEVEL1,_FLEVEL2,_FLEVEL3,_FLEVEL4,_FLEVEL5';				
			$cattitles0 = explode(',',$titles0);
			
			reset($cats);
			foreach ($cats as $i=>$mycat) {
			  //echo $i,$mycat;
			  if ($catlevel = $mycat) {
				  $_fields0[] = $catfields0[$i];
				  $localized_cattitles0[] = localize($cattitles0[$i],getlocal());
			  }
			}	

			//when add a category..comming from add_category procedure  ...modify an existing rec with empty leaf
            if ($addcat) {
              $_fields0[] = $catfields0[$i+1]; //plus 1 cat		
			  $localized_cattitles0[] = localize('_FNEWLEVEL',getlocal());			  
            } 			

            $fields1 = 'cat11,cat12,cat13,cat14,cat15';
			$catfields1 = explode(',',$fields1);
            $titles1 = '_FLEVEL1,_FLEVEL2,_FLEVEL3,_FLEVEL4,_FLEVEL5';				
			$cattitles1 = explode(',',$titles1);
			
			reset($cats);
			foreach ($cats as $i=>$mycat) {
			  //echo $i,$mycat;
			  if ($catlevel = $mycat) {
				  $_fields1[] = $catfields1[$i];
				  $localized_cattitles1[] = localize($cattitles1[$i],getlocal());
			  }
			}	

			//when add a category..comming from add_category procedure  ...modify an existing rec with empty leaf
            if ($addcat) {
              $_fields1[] = $catfields1[$i+1]; //plus 1 cat		
			  $localized_cattitles1[] = localize('_FNEWLEVEL',getlocal());			  
            } 			
			
					  
			$myfields .= implode(',',$_fields) . ',' . implode(',',$_fields0) . ',' . implode(',',$_fields1);
			$mytitles .= implode(',',$localized_cattitles) . ',' . implode(',',$localized_cattitles0) . ',' . implode(',',$localized_cattitles1);
			
			$myfields .= ',active,view,search';
			$mytitles .= ','. localize('_active',getlocal()) . ',' . localize('_view',getlocal()) . ',' . localize('_search',getlocal());	

            //echo $myfields,'-',$mytitles;			
	   }	   
	    
	   $gocat = GetReq('cat');
	   
	   GetGlobal('controller')->calldpc_method('dataforms.setform use myform+myform+5+5+50+100+0+0');
	   GetGlobal('controller')->calldpc_method('dataforms.setformadv use 0+0+50+10');	  

	   //GetGlobal('controller')->calldpc_method("dataforms.setformgoto use PHPDAC:rcupload.editmode_upload_files:".localize('_OK',getlocal()));  
	   //GetGlobal('controller')->calldpc_method('dataforms.setformtemplate use cpitemsmod');	   
	   
       /*$fields = "code1,code2,code3,code4,code5,itmname,itmactive,itmfname,itmremark,itmdescr,itmfdescr,sysins,sysupd,uniida,uniname1,uniname2" .
                 ",uni1uni2,uni2uni1,ypoloipo1,ypoloipo2,price0,price1,cat0,cat1,cat2,cat3,cat4,active,price2,pricepc,p1,p2,p3,p4,p5";
				 
	   $farr = explode(',',$fields);
	   foreach ($farr as $t)
	     $title[] = localize($t,getlocal());
	   $titles = implode(',',$title);	*/ 
				 	                    
       $post = 	localize('_POST',getlocal());
	   $clear = localize('_CLEAR',getlocal());
	   $out .= GetGlobal('controller')->calldpc_method("dataforms.getform use update.categories+dataformsupdate&cat=$gocat&editmode=$editmode+$post+$clear+$myfields+$mytitles++id=$id+dummy");	  
	   	
	   return ($out);		 
    }
	
	function loadframe($ajaxdiv=null) {
	    $db = GetGlobal('db');
		$id = GetReq('id');
		//due to str id of code2..transform from rec id
	    $sSQL = 'select cat2,cat3,cat4,cat5 from categories where id='.$id;
		$ret = $db->Execute($sSQL,2);	 			
		$cats[] = $ret->fields['cat2'];
		$cats[] = $ret->fields['cat3'];
		$cats[] = $ret->fields['cat4'];
		$cats[] = $ret->fields['cat5'];
		
		//$cat = implode($this->cseparator,$cats);
		//$editurl = seturl("t=cpeditcat&editmode=1&cat=".$cat);//$id;
		$frame = '';
		$editurl = null;
		//get all/last cat
		foreach ($cats as $c) {
		   if (trim($c)) {//$cat = str_replace(' ','_',$c);
		       $cat = str_replace(' ','_',$c);
		       $editurl = $this->urlbase . "cp/cpmhtmleditor.php?htmlfile=&type=.html&editmode=1&id=".$cat;
               $frame .= "<iframe src =\"$editurl\" width=\"100%\" height=\"450px\"><p>Your browser does not support iframes</p></iframe>";    		   
		   }
		}   
		//$editurl = $this->urlbase . "cp/cpmhtmleditor.php?htmlfile=&type=.html&editmode=1&id=".$cat;
		//$frame = "<iframe src =\"$editurl\" width=\"100%\" height=\"750px\"><p>Your browser does not support iframes</p></iframe>";    

		if ($ajaxdiv)
			return $ajaxdiv.'|'.$frame;
		else
			return ($frame);
	}	
	
	function show_graph($xmlfile,$title,$url=null,$ajaxid=null,$xmax=null,$ymax=null) {
	  $gx = $this->graphx?$this->graphx:$xmax?$xmax:550;
	  $gy = $this->graphy?$this->graphy:$ymax?$ymax:250;	
	  
	  $ret = $title; 
	  $ret .= $this->charts->show_chart($xmlfile,$gx,$gy,$url,$ajaxid);
	  return ($ret);
	}	
	
	function edit_kategories() {
	   $cat = rawurlencode(GetReq('cat'));
	   $id = 'id';//$this->getmapf('code');
	   $editlink = "javascript:edit_cat({".$id."})";
	   
	   $rd = $this->add_category2(800,440,20, $editlink, 'e', true);
	   $rd .= GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use stats");
       if ($this->hasgraph) {	   
		  $rd .= $this->show_graph('statisticscat',null,seturl('t=cpkategories&cat='.$cat.'&p='.$p));
	   }	  
	   else
	      $rd .= "<h3>".localize('_GNAVAL',0)."</h3>";		   
	   
	   $datattr[] = $rd;
	   $viewattr[] = "left;50%";

	   if ($cat) {//preselected cat
		 //$editurl = seturl("t=cpeditcat&editmode=1&cat=".$cat);//$id;
		 $editurl = $this->urlbase . "cp/cpmhtmleditor.php?htmlfile=&type=.html&editmode=1&id=".$cat;
		 $init_content = "<iframe src =\"$editurl\" width=\"100%\" height=\"450px\"><p>Your browser does not support iframes</p></iframe>";    
	   }
	   else
	     $init_content = null; 
	   $wd .= GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use editcat+".$init_content);	   	   
	   
	   /*$wd .= GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use stats");
       if ($this->hasgraph) {	   
		  $wd .= $this->show_graph('statisticscat','Category statistics',seturl('t=cpkategories&cat='.$cat.'&p='.$p));
	   }	  
	   else
	      $wd .= "<h3>".localize('_GNAVAL',0)."</h3>";*/
		  
	   $datattr[] = $wd;
	   $viewattr[] = "left;50%";		  

	   $myw = new window('',$datattr,$viewattr);
	   $ret = $myw->render();//"center::100%::0::group_article_selected::left::3::3::");
	   unset ($datattr);
	   unset ($viewattr);	

	   //moved aside 
       //$ret .= GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use editcat");	   	   
	   		
	   return ($ret);			  
	   
	}
  
};
}
?>