<?php
$__DPCSEC['SHHANDLER_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("SHHANDLER_DPC")) && (seclevel('SHHANDLER_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHHANDLER_DPC",true);

$__DPC['SHHANDLER_DPC'] = 'shhandler';

$z = GetGlobal('controller')->require_dpc('nitobi/nhandler.lib.php');
require_once($z);

//$e = GetGlobal('controller')->require_dpc('nitobi/nitobi.xml.php');
//require_once($e);

$__EVENTS['SHHANDLER_DPC'][0]='shhandler';
$__EVENTS['SHHANDLER_DPC'][1]='shngetitems';
$__EVENTS['SHHANDLER_DPC'][2]='shnsetitems';
$__EVENTS['SHHANDLER_DPC'][3]='shngetvcustomers';
$__EVENTS['SHHANDLER_DPC'][4]='shngetcustomerslist';
$__EVENTS['SHHANDLER_DPC'][5]='shnsetcustomers';
$__EVENTS['SHHANDLER_DPC'][6]='shngetuserslist';
$__EVENTS['SHHANDLER_DPC'][7]='shnsetusers';
$__EVENTS['SHHANDLER_DPC'][8]='shngetsubs';
$__EVENTS['SHHANDLER_DPC'][9]='shnsetsubs';
$__EVENTS['SHHANDLER_DPC'][10]='shngetposts';
$__EVENTS['SHHANDLER_DPC'][11]='shnsetposts';
$__EVENTS['SHHANDLER_DPC'][12]='shngetreplies';
$__EVENTS['SHHANDLER_DPC'][13]='shnsetreplies';
$__EVENTS['SHHANDLER_DPC'][14]='shngetstats';
$__EVENTS['SHHANDLER_DPC'][15]='shngettrans';
$__EVENTS['SHHANDLER_DPC'][16]='shngettranssql';
$__EVENTS['SHHANDLER_DPC'][17]='shnsettranssql';

$__ACTIONS['SHHANDLER_DPC'][0]='shhandler';
$__ACTIONS['SHHANDLER_DPC'][1]='shngetitems';
$__ACTIONS['SHHANDLER_DPC'][2]='shnsetitems';
$__ACTIONS['SHHANDLER_DPC'][3]='shngetvcustomers';
$__ACTIONS['SHHANDLER_DPC'][4]='shngetcustomerslist';
$__ACTIONS['SHHANDLER_DPC'][5]='shnsetcustomers';
$__ACTIONS['SHHANDLER_DPC'][6]='shngetuserslist';
$__ACTIONS['SHHANDLER_DPC'][7]='shnsetusers';
$__ACTIONS['SHHANDLER_DPC'][8]='shngetsubs';
$__ACTIONS['SHHANDLER_DPC'][9]='shnsetsubs';
$__ACTIONS['SHHANDLER_DPC'][10]='shngetposts';
$__ACTIONS['SHHANDLER_DPC'][11]='shnsetposts';
$__ACTIONS['SHHANDLER_DPC'][12]='shngetreplies';
$__ACTIONS['SHHANDLER_DPC'][13]='shnsetreplies';
$__ACTIONS['SHHANDLER_DPC'][14]='shngetstats';
$__ACTIONS['SHHANDLER_DPC'][15]='shngettrans';
$__ACTIONS['SHHANDLER_DPC'][16]='shngettranssql';
$__ACTIONS['SHHANDLER_DPC'][17]='shnsettranssql';

class shhandler extends nhandler  {

    var $debug_sql;
	var $encoding;
	var $map_t,$map_f;

    function shhandler() {
	
	  nhandler::nhandler(17,'id','Asc');
      $this->debug_sql = true;
	   
	  $this->path = paramload('SHELL','prpath');  
	  
	  //$this->encoding = 'ISO-8859-7';	  
      //choose encoding
      $char_set  = arrayload('SHELL','char_set');	  
      $charset  = paramload('SHELL','charset');	  		
	  if (($charset=='utf-8') || ($charset=='utf8'))
	    $this->encoding = 'utf-8';
	  else  
	    $this->encoding = $char_set[getlocal()]; 	 	  
	  
	  $this->map_t = remote_arrayload('RCITEMS','maptitle',$this->path);	
	  $this->map_f = remote_arrayload('RCITEMS','mapfields',$this->path);		  
    }
	
	function event($event=null) {
	
	  switch ($event) {
	  
        case 'shnsettranssql':		
		           $this->save_transsql_list();		   
		           break;		  

        case 'shngettranssql':		
		           $this->sortColumn = 'id';		
				   $this->sortDirection= 'Desc';
		           $this->get_transsql_list();		   
		           break;	
				   	  
        case 'shngettrans':		
		           $this->sortColumn = 'recid';		
				   $this->sortDirection= 'Desc';
		           $this->get_transactions_list();		   
		           break;	
	  
        case 'shngetstats':		
		           $this->sortColumn = 'date';		
				   $this->sortDirection= 'Desc';
		           $this->get_statistics_list();		   
		           break;		  

        case 'shnsetreplies':		
		           $this->save_replies_list();		   
		           break;		  
	  
        case 'shngetreplies':		
		           $this->sortColumn = 'date_posted';		
				   $this->sortDirection= 'Desc';
		           $this->get_replies_list();		   
		           break;	
				   
        case 'shnsetposts':		
		           $this->save_posts_list();		   
		           break;		  
	  
        case 'shngetposts':		
		           $this->sortColumn = 'date_posted';		
				   $this->sortDirection= 'Desc';
		           $this->get_posts_list();		   
		           break;					   	  
	  
        case 'shnsetsubs':		
		           $this->save_subscribers_list();		   
		           break;		  
	  
        case 'shngetsubs':		
		           $this->sortColumn = 'startdate';		
				   $this->sortDirection= 'Desc';
		           $this->get_subscribers_list();		   
		           break;		  
	  
        case 'shnsetusers':		
		           $this->save_users_list();		   
		           break;	  
	  
        case 'shngetuserslist':
		           $this->sortColumn = 'id';
	               $this->get_users_list();
		           break;	
	  
        case 'shnsetcustomers':		
		           $this->save_customers_list();		   
		           break;	  
	  
        case 'shngetcustomerslist':
		           $this->sortColumn = 'id';
	               $this->get_customers_list();
		           break;			  
	  
        case 'shngetvcustomers':
		           $this->sortColumn = 'id';
	               $this->get_item_customers_list();
		           break;		  
				   
        case 'shnsetitems':		
		           $this->save_items_list();		   
		           break;
	  
        case 'shngetitems':
		default       :
	               $this->get_items_list();
		           break;	  
	  }
	}
	
	function action($action=null) {
	
      //none
	}
	
	
	function get_items_list() {
       $db = GetGlobal('db');	
	   
	   //mysqli specific to get greek chars from utf-8 mysql db over adodb using sqli driver
       //$db->_connectionID->set_charset("greek");
	   //$db->query("SET NAMES 'utf8'");
	   $testcat = 'ΜΗΧΑΝΕΣ ΓΡΑΦΕΙΟΥ';
	   $c1 = iconv('','ISO-8859-7','ΑΝΑΛΩΣΙΜΑ ΜΗΧΑΝΩΝΩ');
	   $c2 = rawurldecode('ΜΗΧΑΝΕΣ ΓΡΑΦΕΙΟΥ');
	   $c3 = "ΑΝΑΛΩ%";
	   //tranformed posts..
	   $apo = GetReq('apo'); //echo $apo;
	   $eos = GetReq('eos');	//echo $eos; 
           $filter = GetReq('filter');
	   
	   //geta 	  
	   if (GetReq('cat')!=null)
		    $cat = GetReq('cat');	
	   //if (GetReq('sel')!=null)
		 //   $sel = GetReq('sel');		
		
	   $whereClause = '';
	   
		  if (isset($_GET['p_id'])) {		
            $whereClause .= ' where id=' . $_GET['p_id'];				     
	   	  }
	   elseif ($filter) {

             $whereClause = " where (id like '%$filter%' or sysins like '%$filter%' or ".$this->getmapf('code')." like '%$filter%' or pricepc like '%$filter%' or price2 like '%$filter%' or itmname like '%$filter%' or itmfname like '%$filter%' or code2 like '%$filter%' or code3 like '%$filter%' or code4 like '%$filter%' or code5 like '%$filter%' or price0 like '%$filter%' or price1 like '%$filter%' or itmdescr like '%$filter%' or itmfdescr like '%$filter%' or itmremark like '%$filter%')";
           }	   
		  		
		  if (isset($cat)) {//echo $cat;
		    if ( (isset($_GET['p_id'])) || (isset($_GET['filter'])) ) $whereClause.=' and ';
		                         else $whereClause.=' where ';			  
			
		    if (defined("RCCATEGORIES_DPC")) {//text based cats
           /*   $whereClause .= '( cat0=' . $db->qstr(str_replace('_',' ',$cat));		  
			  $whereClause .= 'or cat1=' . $db->qstr(str_replace('_',' ',$cat));		  
			  $whereClause .= 'or cat2=' . $db->qstr(str_replace('_',' ',$cat));		 
			  $whereClause .= 'or cat3=' . $db->qstr(str_replace('_',' ',$cat));		   
			  $whereClause .= 'or cat4=' . $db->qstr(str_replace('_',' ',$cat)) . ') ';	*/

			  $cat_tree = explode('^',str_replace('_',' ',$cat)); 
/*if ($sel=str_replace('_',' ',GetReq('sel'))) {
$max = count($cat_tree)-1;
if ($sel!=$cat_tree[$max])
  $cat_tree[]=$sel;
}*/
		
			  if ($cat_tree[0])
			    $whereClause .= ' cat0=' . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[0])));		  
			  if ($cat_tree[1])	
			    $whereClause .= ' and cat1=' . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[1])));		 
			  if ($cat_tree[2])	
			    $whereClause .= ' and cat2=' . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[2])));		   
			  if ($cat_tree[3])	
			    $whereClause .= ' and cat3=' . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[3])));
			  if ($cat_tree[4])	
			    $whereClause .= ' and cat4=' . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[4])));		  
		    }
            elseif (defined("RCKATEGORIES_DPC")) {
			
			  $cat_tree = explode('^',str_replace('_',' ',$cat)); 
			
              //$whereClause .= '( cat0=' . $db->qstr(str_replace('_',' ',$cat_tree[0]));		  
			  if ($cat_tree[0])
			    $whereClause .= ' cat0=' . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[0])));		  
			  if ($cat_tree[1])	
			    $whereClause .= ' and cat1=' . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[1])));		 
			  if ($cat_tree[2])	
			    $whereClause .= ' and cat2=' . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[2])));		   
			  if ($cat_tree[3])	
			    $whereClause .= ' and cat3=' . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[3])));
			  /*if ($cat_tree[4])	
			    $whereClause .= ' and cat4=' . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[4])));*/
								
			  //$whereClause .= ') ';		   
			  //$whereClause .= " cat1='".$c2."'";	
			}  
		  }		
		  
		  /*if (isset($sel)) {
		    if ((isset($cat)) || ((isset($_GET['p_id'])))) $whereClause.=' and ';
		                else $whereClause.=' where ';		  
            $whereClause .= ' cat1=' . $db->qstr($sel);		  
		  }	*/		    
	   
	      if ($letter=GetReq('alpha')) {
		    if (isset($cat) || isset($sel) || (isset($_GET['filter'])) || (isset($_GET['p_id']))) 
			  $whereClause.=' and ';
		    else $whereClause.=' where ';		  
	        $whereClause .= " ( itmname like '" . strtolower($letter) . "%' or " .
		                    " itmname like '" . strtoupper($letter) . "%')";	
			//marka is lookup table...???		 
		  }			 
				  
		  if ($apo) {
		    if (($letter) || (isset($cat)) || (isset($sel)) || (isset($_GET['filter'])) || (isset($_GET['p_id']))) 
			     $whereClause.=' and ';
		    else $whereClause.=' where ';
		    $whereClause.= "sysins>='" . convert_date(trim($apo),"-DMY",1) . "'";
		  }  
		  
		  if ($eos) {
		    if (($letter) || ($apo) || (isset($cat)) || isset($sel) || (isset($_GET['filter'])) || (isset($_GET['p_id']))) 
			     $whereClause.=' and ';
		    else $whereClause.=' where ';
		    $whereClause .= "sysins<='" . convert_date(trim($eos),"-DMY",1) . "'";						
		  } 				   
	     

	   /*if (isset($_GET['id'])) {
		 $whereClause=" WHERE p_id=".$_GET["id"]." ";
	   }*/ 
	   $lan = getlocal();	
       $name = $lan?'itmname':'itmfname';
       $descr = $lan?'itmdescr':'itmfdescr';	   
   
	   $sSQL .= "select id,sysins,code1,pricepc,price2,sysins,$name,uniname1,uniname2,active,code4," .
	            "price0,price1,cat0,cat1,cat2,cat3,cat4,$descr,".$this->getmapf('code')." from products ";
	   $sSQL .= $whereClause;
	   //$sSQL .= $this->datahandler->get_sql_order();
	   $sSQL .= " ORDER BY " . $this->sortColumn . " " . $this->sortDirection ." LIMIT ". $this->ordinalStart .",". ($this->pageSize) .";";
	   //echo $sSQL;	die();
	   
       $result = $db->Execute($sSQL,2);	
	   
	   //$order = " ORDER BY " . $this->sortColumn . " " . $this->sortDirection ." LIMIT ". $this->ordinalStart .",". ($this->pageSize) .";";
	   //$result = GetGlobal('controller')->calldpc_method("rcvehicles.select use $order");	   
	   
	   $names = array('id','sysins','code1','pricepc','price2','sysins',
	                  'itmname','uniname1','uniname2','active','code4',
					  'price0','price1','cat0','cat1','cat2','cat3','cat4','itmdescr',$this->getmapf('code'));			 			 
	   $this->handle_output($db,$result,$names,'id',null,$this->encoding);
	   
	   //$ret = $this->handle_output($db,$result,$names,'p_id',true);	   
	   //echo trim($ret);	 	   	   
	   	
	}
	
	function save_items_list() {
       $db = GetGlobal('db');		
	
	   //remove p_id=auto_inc field to insert a new rec
	   //no update after insert, if update done without refresh (id=null problem)
	   $names = array('id','sysins','code1','pricepc','price2','sysins',
	                  'itmname','uniname1','uniname2','active','code4',
					  'price0','price1','cat0','cat1','cat2','cat3','cat4','itmdescr',$this->getmapf('code'));			 			 
			 
	   $sql2run = $this->handle_input(null,'products',$names,'id');		
	
       $db->Execute($sql2run,3,null,1);
	   
	   if (($this->debug_sql) && ($f = fopen($this->path . "nitobi.sql",'w+'))) {
	     fwrite($f,$sql2run,strlen($sql2run));
		 fclose($f);
	   }	
	}
	
	function get_items_customers_list() {
       $db = GetGlobal('db');	
	     
	   $whereClause='';
	   if (isset($_GET['id'])) {
		 $whereClause=" WHERE csvlist=".$_GET["id"]." ";
	   }	 
	   else
	     $whereClause=" WHERE csvlist=-1";//fetch nothing	
   
	   $sSQL2 = "select id,insdate,fname,lname,address,tel,fax,mob,email from rccustomers";
	   $sSQL2 .= $whereClause;//"where csvlist=$vehicleid";
	   //$sSQL2 .= "or csvlist like '%," . $vehicleid . "%'"; //enclosed..started by comma
       $sSQL2 .= " ORDER BY " . $this->sortColumn . " " . $this->sortDirection ." LIMIT ". $this->ordinalStart .",". ($this->pageSize) .";";
	   //echo $sSQL2;
	   
	   /*if (($this->debug_sql) && ($f = fopen("c:/php/webos/projects/auctionsbazaar/log/nitobi.sql",'w+'))) {
	     fwrite($f,$sSQL,strlen($sSQL2));
		 fclose($f);
	   }*/		   
	   
       $result = $db->Execute($sSQL2,2);	
		   

	   $names = array('id','insdate','fname','lname','address','tel','fax','mob','email');			 			 
	   $this->handle_output($db,$result,$names,'id',null,$this->encoding); 	   	   
	   	
	}	
	
	function get_customers_list() {
       $db = GetGlobal('db');	
       $filter = GetReq('filter');

	   if ($filter) {
             
             $whereClause = " where (id like '%$filter%' or code2 like '%$filter%' or name like '%$filter%' or afm like '%$filter%' or address like '%$filter%' or area like '%$filter%' or voice1 like '%$filter%' or voice2 like '%$filter%' or fax like '%$filter%' or mail like '%$filter%' or prfdescr like '%$filter%')";
           }	
           else	   
	     $whereClause = '';	   
		  
		if ($letter=GetReq('alpha')) {
                  if ($filter) $whereClause .= ' and ';
                  else $whereClause .= " where ";
		  $whereClause .= "(name like '" . strtolower($letter) . "%' or " .
		                  "name like '" . strtoupper($letter) . "%')";
		}	
		
		if ($apo) {
		  if (($letter) || ($filter)) $whereClause.=' and ';
		          else $whereClause.=' where ';
		  $whereClause.= "insdate>='" . convert_date(trim($apo),"-YMD",1) . "'";
		}  
		  
		if ($eos) {
		  if ((!$letter) && (!$filter) && (!$apo)) $whereClause.=' where ';
	                            else $whereClause.=' and ';
		  $whereClause .= "insdate<='" . convert_date(trim($eos),"-YMD",1) . "'";						
		} 	   
	     
	   $sSQL2 = "select id,code2,name,afm,address,area,voice1,voice2,fax,mail,prfdescr from customers ";// where id<18 ";
	   $sSQL2 .= $whereClause;
       $sSQL2 .= " ORDER BY " . $this->sortColumn . " " . $this->sortDirection ." LIMIT ". $this->ordinalStart .",". ($this->pageSize) .";";
	   //echo $sSQL2;
	   
	   /*if (($this->debug_sql) && ($f = fopen("c:/php/webos/projects/auctionsbazaar/log/nitobi.sql",'w+'))) {
	     fwrite($f,$sSQL,strlen($sSQL2));
		 fclose($f);
	   }*/		   
	   
       $result = $db->Execute($sSQL2,2);	
		   

	   $names = array('id','code2','name','afm','address','area','voice1','voice2','fax','mail','prfdescr');			 			 
	   $this->handle_output($db,$result,$names,'id',null,$this->encoding); 	   	   
	   	
	}	
	
	function save_customers_list() {
       $db = GetGlobal('db');		
	
       $names = array('id','code2','name','afm','address','area','voice1','voice2','fax','mail','prfdescr');			 			 			 
	   $sql2run = $this->handle_input(null,'customers',$names,'id');		
	
       $db->Execute($sql2run,3,null,1);
	   
	   if (($this->debug_sql) && ($f = fopen($this->path . "nitobi.sql",'w+'))) {
	     fwrite($f,$sql2run,strlen($sql2run));
		 fclose($f);
	   }	
	}	
	
	function get_users_list() {
       $db = GetGlobal('db');	
       $filter = GetReq('filter');

	   if ($filter) {
             
             $whereClause = " where (id like '%$filter%' or code2 like '%$filter%' or fname like '%$filter%' or lname like '%$filter%' or notes like '%$filter%' or seclevid like '%$filter%' or secparam like '%$filter%' or subscribe like '%$filter%' or email like '%$filter%' or username like '%$filter%')";
           }	
	   else
	     $whereClause = '';	   
		  
		if ($letter=GetReq('alpha')) {
                  if ($filter) $whereClause .= ' and ';
                  else $whereClause .= " where ";
		  $whereClause .= "(username like '" . strtolower($letter) . "%' or " .
		                  "username like '" . strtoupper($letter) . "%')";
		}	
		
		if ($apo) {
		  if ($letter || $filter) $whereClause.=' and ';
		          else $whereClause.=' where ';
		  $whereClause.= "lastlogon>='" . convert_date(trim($apo),"-YMD",1) . "'";
		}  
		  
		if ($eos) {
		  if ((!$letter) && (!$filter) && (!$apo)) $whereClause.=' where ';
	                            else $whereClause.=' and ';
		  $whereClause .= "lastlogon<='" . convert_date(trim($eos),"-YMD",1) . "'";						
		} 	   
	     
	   $sSQL2 = "select id,code2,fname,lname,notes,seclevid,secparam,subscribe,email,username,password,timezone from users ";// where id<18 ";
	   $sSQL2 .= $whereClause;
       $sSQL2 .= " ORDER BY " . $this->sortColumn . " " . $this->sortDirection ." LIMIT ". $this->ordinalStart .",". ($this->pageSize) .";";
	   //echo $sSQL2;
	   
	   /*if (($this->debug_sql) && ($f = fopen("c:/php/webos/projects/auctionsbazaar/log/nitobi.sql",'w+'))) {
	     fwrite($f,$sSQL,strlen($sSQL2));
		 fclose($f);
	   }*/		   
	   
       $result = $db->Execute($sSQL2,2);	
		   

	   $names = array('id','code2','fname','lname','notes','seclevid','secparam','subscribe','email','username','password','timezone');			 			 
	   $this->handle_output($db,$result,$names,'id',null,$this->encoding); 	   	   
	   	
	}	
	
	function save_users_list() {
       $db = GetGlobal('db');		
	
	   $names = array('id','code2','fname','lname','notes','seclevid','secparam','subscribe','email','username','password','timezone');			 			 			 
	   $sql2run = $this->handle_input(null,'users',$names,'id');		
	
       $db->Execute($sql2run,3,null,1);
	   
	   if (($this->debug_sql) && ($f = fopen($this->path . "nitobi.sql",'w+'))) {
	     fwrite($f,$sql2run,strlen($sql2run));
		 fclose($f);
	   }	
	}		
	
	function get_subscribers_list() {
       $db = GetGlobal('db');	
	   //tranformed posts..
	   $apo = GetReq('apo'); //echo $apo;
	   $eos = GetReq('eos');	//echo $eos; 
       $filter = GetReq('filter');

	   if ($filter) {
             
             $whereClause = " where (email like '%$filter%' or startdate like '%$filter%' or id like '%$filter%') and subscribe=1";
           }	
	   else
	   $whereClause = ' where subscribe=1 ';

	   	
	   
		  if (isset($_GET['id'])) {		
            $whereClause .= ' and id=' . $_GET['id'];				     
	   	  }
		  				    
	   
	      if ($letter=GetReq('alpha')) {  
	        $whereClause .= " and ( email like '" . strtolower($letter) . "%' or " .
		                    " email like '" . strtoupper($letter) . "%')";	
			//marka is lookup table...???		 
		  }			 
  
		  if ($apo) {
		    $whereClause.= " and startdate>='" . convert_date(trim($apo),"-DMY",1) . "'";
		  }  
		  
		  if ($eos) {
		    $whereClause .= "and startdate<='" . convert_date(trim($eos),"-DMY",1) . "'";						
		  } 				   	
   
	   $sSQL .="SELECT id,startdate,email FROM users";	
	   $sSQL .= $whereClause;
	   $sSQL .= " ORDER BY " . $this->sortColumn . " " . $this->sortDirection ." LIMIT ". $this->ordinalStart .",". ($this->pageSize) .";";
	   //echo $sSQL;	die();
	   
       $result = $db->Execute($sSQL,2);	
	   
	   //$order = " ORDER BY " . $this->sortColumn . " " . $this->sortDirection ." LIMIT ". $this->ordinalStart .",". ($this->pageSize) .";";
	   //$result = GetGlobal('controller')->calldpc_method("rcvehicles.select use $order");	   
	   
	   $names = array('id','startdate','email');			 			 
	   $this->handle_output($db,$result,$names,'id',null,$this->encoding);	
	}
	
	function save_subscribers_list() {
       $db = GetGlobal('db');		
	
	   $names = array('id','startdate','email');		 
	   $sql2run = $this->handle_input(null,'users',$names,'id');		
	
       $db->Execute($sql2run,3,null,1);
	   
	   if (($this->debug_sql) && ($f = fopen($this->path . "nitobi.sql",'w+'))) {
	     fwrite($f,$sql2run,strlen($sql2run));
		 fclose($f);
	   }		
	}		
	
	function get_posts_list() {
       $db = GetGlobal('db');	

	   //tranformed posts..
	   $apo = GetReq('apo'); //echo $apo;
	   $eos = GetReq('eos');	//echo $eos; 
	   	
		
	   $whereClause = '';
	   
		  if (isset($_GET['id'])) {		
            $whereClause .= ' where id=' . $_GET['id'];				     
	   	  }
		  				    
	   
	      if ($letter=GetReq('alpha')) {
		    if (isset($_GET['id'])) 
			     $whereClause.=' and ';
		    else $whereClause.=' where ';		  
	        $whereClause .= " ( headline like '" . strtolower($letter) . "%' or " .
		                    " headline like '" . strtoupper($letter) . "%')";	
			//marka is lookup table...???		 
		  }			 
  
		  if ($apo) {
		    if (($letter) || (isset($_GET['p_id']))) 
			     $whereClause.=' and ';
		    else $whereClause.=' where ';
		    $whereClause.= "date_posted>='" . convert_date(trim($apo),"-DMY",1) . "'";
		  }  
		  
		  if ($eos) {
		    if (($letter) || ($apo)|| (isset($_GET['p_id']))) $whereClause.=' and ';
		                                                 else $whereClause.=' where ';
		    $whereClause .= "date_posted<='" . convert_date(trim($eos),"-DMY",1) . "'";						
		  } 				   	
   
	   $sSQL .="SELECT id,member,headline,body,date_posted,views FROM forum_posts";
	   $sSQL .= $whereClause;
	   $sSQL .= " ORDER BY " . $this->sortColumn . " " . $this->sortDirection ." LIMIT ". $this->ordinalStart .",". ($this->pageSize) .";";
	   
       $result = $db->Execute($sSQL,2);	 
	   
	   $names = array('id','member','headline','body','date_posted','views');			 			 
	   $this->handle_output($db,$result,$names,'id',null,$this->encoding);	
	}
	
	function save_posts_list() {
       $db = GetGlobal('db');		
	
	   $names = array('id','member','headline','body','date_posted','views');	 
	   $sql2run = $this->handle_input(null,'forum_posts',$names,'id');		
	
       $db->Execute($sql2run,3,null,1);
	   
	   if (($this->debug_sql) && ($f = fopen($this->path . "nitobi.sql",'w+'))) {
	     fwrite($f,$sql2run,strlen($sql2run));
		 fclose($f);
	   }		
	}	
	
	function get_replies_list() {
       $db = GetGlobal('db');	

	   $whereClause='';
	   if (isset($_GET['id'])) {
		 $whereClause=" WHERE id = ".$_GET["id"]." ";
	   }	 
	   else
	     $whereClause=" WHERE id=-1";//fetch nothing	   	
   
	   $sSQL .="SELECT id,member,headline,body,date_posted FROM forum_replies";	
	   $sSQL .= $whereClause;
	   $sSQL .= " ORDER BY " . $this->sortColumn . " " . $this->sortDirection ." LIMIT ". $this->ordinalStart .",". ($this->pageSize) .";";
	   
       $result = $db->Execute($sSQL,2);	
	   //echo $sSQL;
	   $names = array('id','member','headline','body','date_posted');			 			 
	   $this->handle_output($db,$result,$names,'body',null,$this->encoding);	
	}
	
	function save_replies_list() {
       $db = GetGlobal('db');		
	
	   $names = array('id','member','headline','body','date_posted');			 
	   $sql2run = $this->handle_input(null,'forum_replies',$names,'body');		
	
       $db->Execute($sql2run,3,null,1);
	   
	   if (($this->debug_sql) && ($f = fopen($this->path . "nitobi.sql",'w+'))) {
	     fwrite($f,$sql2run,strlen($sql2run));
		 fclose($f);
	   }		
	}	
	
	function get_statistics_list() {
       $db = GetGlobal('db');		
	   
	   $whereClause='';
	   if (isset($_GET['tid'])) {
		 $whereClause=" WHERE tid=".$_GET["tid"]." ";
	   }	 
	   else
	     $whereClause=" WHERE tid=-1";//fetch nothing	   
	
	   $sSQL .= "select id,date,day,month,year,tid,attr1,attr2,attr3 from stats ";
	   $sSQL .= $whereClause;
	   $sSQL .= " ORDER BY " . $this->sortColumn . " " . $this->sortDirection ." LIMIT ". $this->ordinalStart .",". ($this->pageSize) .";";
	   //echo $sSQL;	die();
	   
       $result = $db->Execute($sSQL,2);	
	   

	   $names = array('id','date','day','month','year','tid','attr1','attr2','attr3');			 			 
	   $this->handle_output($db,$result,$names,'id',null,$this->encoding);	
	}	
	

	function get_transactions_list() {
       $db = GetGlobal('db');	
       $filter = GetReq('filter');	
	   
	   $whereClause='';
	   if (isset($_GET['select'])) {
	     if (isset($_GET['cid'])) {
		   $whereClause=" WHERE cid=".$_GET["cid"]." ";
	     } 
	     else
	       $whereClause=" WHERE cid=-1";//fetch nothing	   
	   }
	   elseif ($filter) {
             
             $whereClause = " where (recid like '%$filter%' or tid like '%$filter%' or cid like '%$filter%' or tdate like '%$filter%' or ttime like '%$filter%' or tstatus like '%$filter%' or payway like '%$filter%' or roadway like '%$filter%' or cost like '%$filter%' or costpt like '%$filter%')";
           }	
           else	   
	     $whereClause = null;	   
	
	   $sSQL .= "select recid,tid,cid,tdate,ttime,tstatus,payway,roadway,cost,costpt from transactions ";
	   $sSQL .= $whereClause;
	   $sSQL .= " ORDER BY " . $this->sortColumn . " " . $this->sortDirection ." LIMIT ". $this->ordinalStart .",". ($this->pageSize) .";";
	   //echo $sSQL;	die();
	   
       $result = $db->Execute($sSQL,2);	
	   

	   $names = array('recid','tid','cid','tdate','ttime','tstatus','payway','roadway','cost','costpt');			 			 
	   $this->handle_output($db,$result,$names,'tid',null,$this->encoding);	
	}	
	
	function getmapf($name) {
	
	  if (empty($this->map_t)) return 0;
	  
	  foreach ($this->map_t as $id=>$elm)
	    if ($elm==$name) break;
				
	  //$id = key($this->map_t[$name]) ;
	  $ret = $this->map_f[$id];
	  return ($ret);
	}	
	
	function get_transsql_list() {
       $db = GetGlobal('db');	
       $filter = GetReq('filter');	
	   
	   $whereClause='';
	   if (isset($_GET['select'])) {
	     if (isset($_GET['cid'])) {
		   $whereClause=" WHERE id=".$_GET["cid"]." ";
	     } 
	     else
	       $whereClause=" WHERE id=-1";//fetch nothing	   
	   }
	   elseif ($filter) {
             
           $whereClause = " where (status like '%$filter%' or sqlquery like '%$filter%' or sqlres like '%$filter%' or date like '%$filter%' or execdate like '%$filter%' or reference like '%$filter%')";
       }	
       else	   
	     $whereClause = null;	   
	
	   $sSQL .= "select id,fid,time,date,execdate,status,sqlquery,sqlres,reference from syncsql ";
	   $sSQL .= $whereClause;
	   $sSQL .= " ORDER BY " . $this->sortColumn . " " . $this->sortDirection ." LIMIT ". $this->ordinalStart .",". ($this->pageSize) .";";
	   //echo $sSQL;	die();
	   
       $result = $db->Execute($sSQL,2);	
	   

	   $names = array('id','fid','time','date','execdate','status','sqlquery','sqlres','reference');			 			 
	   $this->handle_output($db,$result,$names,'id',null,$this->encoding);	
	}		
	
	function save_transsql_list() {
       $db = GetGlobal('db');		
	
	   $names = array('id','fid','time','date','execdate','status','sqlquery','sqlres','reference');
	   $sql2run = $this->handle_input(null,'syncsql',$names,'id');		
	
       $db->Execute($sql2run,3,null,1);
	   
	   if (($this->debug_sql) && ($f = fopen($this->path . "nitobi.sql",'w+'))) {
	     fwrite($f,$sql2run,strlen($sql2run));
		 fclose($f);
	   }	
	}				
 
};
}
?>