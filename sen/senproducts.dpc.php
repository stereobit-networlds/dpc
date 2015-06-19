<?php
if (defined("SEN_DPC")) {

$__DPCSEC['SENPRODUCTS_DPC']='2;2;2;2;2;2;2;2;9';
$__DPCSEC['ADMSENPRODUCTS_']='2;1;1;1;1;1;1;2;9';

if ((!defined("SENPRODUCTS_DPC")) && (seclevel('SENPRODUCTS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SENPRODUCTS_DPC",true);

$__DPC['SENPRODUCTS_DPC'] = 'senproducts';


$__EVENTS['SENPRODUCTS_DPC'][0]='senvp';
$__EVENTS['SENPRODUCTS_DPC'][1]='addtocart';     //continue with ..cart
$__EVENTS['SENPRODUCTS_DPC'][2]='removefromcart';//continue with ..cart
$__EVENTS['SENPRODUCTS_DPC'][3]='searchtopic';   //continue with ..browser

$__ACTIONS['SENPRODUCTS_DPC'][0]='senvp';
$__ACTIONS['SENPRODUCTS_DPC'][1]='addtocart';     //continue with ..from cart
$__ACTIONS['SENPRODUCTS_DPC'][2]='removefromcart';//continue with ..from cart
$__ACTIONS['SENPRODUCTS_DPC'][3]='searchtopic';   //continue with ..from browser

$__LOCALE['SENPRODUCTS_DPC'][0]='_BESTPRICE;Best Price;Φθηνότερα...';

//$__DPCATTR['SENPRODUCTS_DPC']['senvp'] = 'senvp,0,0,0,0,0,0,0,0,0';//[7]=supercache disabled due to the price policy 
GetGlobal('controller')->set_command('senvp','0,0,0,0,0,0,0,0,0',__FILE__);//[7]=supercache disabled due to the price policy
$__DPCATTR['SENPRODUCTS_DPC']['addtocart'] = 'addtocart,0,0,0,0,0,1,0,0,1';
$__DPCATTR['SENPRODUCTS_DPC']['removefromcart'] = 'removefromcart,0,0,0,0,0,1,0,0,1';
$__DPCATTR['SENPRODUCTS_DPC']['searchtopic'] = 'searchtopic,0,0,0,0,0,1,0,0,0';

//echo 'SET:',__FILE__;

//require_once("barcode.lib.php");
//GetGlobal('controller')->include_dpc('sen/barcode.lib.php');
require_once(GetGlobal('controller')->require_dpc('sen/barcode.lib.php'));

//require_once("sen.dpc.php");

class senproducts extends sen {

	var $userLevelID;
	
	var $alias;
	var $numResult;
	var $querydepth;
	var $homedir;
	var $directory_mark;
	var $retable;
	var $resourcepicpath;
	var $t_dbtable;
	var $pagenum;
	var $view;	
	
	var $allterms;
	var $anyterms;
	var $asphrase;
	
	var $notavail;
	
	var $pdfdoc;
	
	var $select;
	var $criteria;
	var $criteriaOR;
	var $criteriaAND;
	var $searchcriteria;
	var $searchcriteriaOR;
	var $searchcriteriaAND;
	var $prn_select;	
	var $prn_searchcriteria;
	var $prn_searchcriteriaOR;
	var $prn_searchcriteriaAND;	
	
	var $abspicpath;
	var $restype;
	var $nopic;
	
	var $checkprd;
	var $percentoff;
	var $senpercentoff;
	
	var $timeout;
	var $dec_num;
	
	function senproducts() {
	   $GRX = GetGlobal('GRX');	
	   $UserSecID = GetGlobal('UserSecID');
	   $UserID = GetGlobal('UserID');  
	 
	   $this->t_dbtable = new ktimer;
	   $this->t_dbtable->start('senproducts');  	   
	   
       sen::sen();	   

       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
	   $this->userid = (((decode($UserID))) ? (decode($UserID)) : '');	
	   
	   //view per client
 	   $viewperclient = arrayload('SENPRODUCTS','viewperclient');				
	   $this->view  = $viewperclient[$this->userLevelID]; 
	   if (!$this->view) $this->view = paramload('SENPRODUCTS','dview');	

	   //price per client
 	   $percentoffperclient = arrayload('SENPRODUCTS','priceoffperclient');				
	   $this->percentoff  = $percentoffperclient[$this->userLevelID]; 
	   //$this->senpercentoff = $this->get_user_price_policy($this->userid);
	   //echo $this->senpercentoff,">>>";	
	   
	   //in case of admin view is sekectable in t
	   //if (is_number(GetReq('t'))) $this->view = GetReq('t');
	   
       $this->dec_num = paramload('SENPRODUCTS','decimals');//echo $this->dec_num;//3; //decimal digits
	   $this->numResult = 0;
	   $this->retable = 0;
	   $this->pagenum = 20;
	   //$this->resourcepicpath = paramload('SHELL','urlbase') . paramload('PRODUCTS','dbres');	   
       $ip = paramload('SHELL','ip');//$_SERVER['HTTP_HOST'];
       $pr = paramload('SHELL','protocol');		   
	   $this->resourcepicpath = $pr . $ip . paramload('SENPRODUCTS','dbres');	   
	   $this->timeout = paramload('SHELL','timeout');
	   	   
       $this->moneysymbol = "&" . paramload('CART','cursymbol') . ";";

	   //search params (must be declared in search dpc)
   	   $this->allterms = localize('_ALLTERMS',getlocal());
	   $this->anyterms = localize('_ANYTERMS',getlocal());
	   $this->asphrase = localize('_ASPHRASE',getlocal()); 
	   
	   if ($GRX) {
         $this->notavail = loadTheme('notavail',localize('_NOTAVAL',getlocal()));	   
	   }
	   else {
         $this->notavail = localize('_NOTAVAL',getlocal());	   
	   } 
	   
	   //SELECT CRITERIA BASED ON SELECTED LANGUAGE
	   $current_language = getlocal(); //echo $current_language,'>>>>';
	   //$lan = getlans(); print_r($lan);
	   $this->select = paramload('SENPRODUCTS','select'.$current_language); 
	   $this->criteria = explode("+",paramload('SENPRODUCTS','where'.$current_language));//print paramload('PRODUCTS','where');
	   $this->criteriaOR = explode(",",$this->criteria[0]); //print_r($this->criteriaOR);	   
	   $this->criteriaAND = explode(",",$this->criteria[1]); //print_r($this->criteriaAND);   	   
	   $this->searchcriteria = explode("+",paramload('SENPRODUCTS','swhere'.$current_language)); //print_r($this->searchcriteria);	   
	   $this->searchcriteriaOR = explode(",",$this->searchcriteria[0]);
	   $this->searchcriteriaAND = explode(",",$this->searchcriteria[1]); 
	   //SELECT CRITERIA BASED ON PRINTER MODEL	   
	   $this->prn_select = paramload('SENPRODUCTS','pselect'.$current_language);    	   
	   $this->prn_searchcriteria = explode("+",paramload('SENPRODUCTS','pswhere'.$current_language)); //print_r($this->searchcriteria);	   
	   $this->prn_searchcriteriaOR = explode(",",$this->prn_searchcriteria[0]);
	   $this->prn_searchcriteriaAND = explode(",",$this->prn_searchcriteria[1]);	   
	     
	   //get abs path
	   $this->abspicpath = paramload('SENPRODUCTS','dbresabs');
	                      //paramload('SHELL','prpath') . paramload('SHELL','picpath')  . paramload('SENPRODUCTS','dbres');
	   $this->restype = paramload('SENPRODUCTS','restype');
	   $this->nopic = paramload('SENPRODUCTS','nopic');
	   
	   $this->checkprd = paramload('SENPRODUCTS','checkproduct');	
	}	
	
	function javascript() {
       $out = <<<EOF
/***********************************************
* Switch Menu script- by Martial B of http://getElementById.com/
* Modified by Dynamic Drive for format & NS4/IE4 compatibility
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/

if (document.getElementById){ //DynamicDrive.com change
document.write('<style type="text/css">')
document.write('.submenu{display: none;}')
document.write('</style>')
}

function SwitchMenu(obj){
	if(document.getElementById){
	var el = document.getElementById(obj);
	var ar = document.getElementById("masterdiv").getElementsByTagName("span"); //DynamicDrive.com change
		if(el.style.display != "block"){ //DynamicDrive.com change
			for (var i=0; i<ar.length; i++){
				if (ar[i].className=="submenu") //DynamicDrive.com change
				ar[i].style.display = "none";
			}
			el.style.display = "block";
		}else{
			el.style.display = "none";
		}
	}
}	
EOF;
    return ($out);
	}

	function event($evn) {      
	   	  
	   switch ($evn) {	
	     case 'searchtopic'   :
	     case 'addtocart'     :
		 case 'removefromcart':
	     case 'senvp'         : 		
	                            $this->read_products();
		                 break;
	   }
	   
	}
	
	function action($act) {
	   
	   switch ($act) {
	     case 'searchtopic'   ://echo'ZZZZZZZZZZz';
	     case 'addtocart'     :
		 case 'removefromcart':	   
	     case 'senvp'         : $out = $this->render(); break;
	   }
	   
	   return ($out); 
	}	
		

    function render() {
	    $g = GetReq('g');			
		
        if ($this->retable) {
			     $mydbrowser = new browse($this->retable,$this->getgroup(),1,'1,2,3,4,5,6,7,8,9','a,b,c,d,e,f,g,h,i,k,l,m');	   
	             $out .= $mydbrowser->render($this->view,$this->pagenum,$this,1,1,1,1,1,1,1,1); 
  	             unset ($mydbrowser);	   
		}
		/*else {
			     $swin = new window('',localize('_EMPTYDIR',getlocal()));
	             $out .= $swin->render();
	             unset ($swin);	
		}	*/
		
        //tax message
	    $out .= "<br>";
	    $warning1 = new window('',localize('_MSG16',getlocal()));
	    $out .= $warning1->render(" ::100%::0::group_form_foottitle::center;100%;::");
	    unset($warning1);		

        return ($out);
    }
	
	function read_policy() {
		 
	   $this->senpercentoff = $this->get_user_price_policy($this->userid);
	   //SetInfo($this->senpercentoff."% off");			
	}
	
	function read_products($group=null,$userlevel=null) {
	
	   //if ($userlevel!=null) echo $userlevel,">>"; //called from cache bot
	
	   if ($group!=null) {
	     $g = $group;
	   }
	   else {
	     $param1 = GetGlobal('param1');
	     $g = GetReq('g');	   
	     if ($param1) $g = $param1;
	   }
	   
	   if ($userlevel!=null) {
	     $myuserl = intval($userlevel);
		 $cacheyes = 1; //cmd autocache where execute as admin
		 //echo "CACHEYES";
	   }	
	   else {
	  	 $myuserl = $this->userLevelID;
		 $cacheyes = 0; //cache handled by current user 
		 //echo "CACHENO";
	   }		   		 
	   	  
	   switch ($this->view) {	
		 case 1      : $this->pagenum = 20; break;
		 case 2      : $this->pagenum = 10; break;
		 case 3      : $this->pagenum = 10; break;
		 case 4      : $this->pagenum = 10; break;
         case 5      : $this->pagenum = 20; break;
		 case 6      : $this->pagenum = 10; break;
         case 7      : $this->pagenum = 10; break;
		 case 8      : $this->pagenum = 10; break;
		 case 9      : $this->pagenum = 10; break;	
		 case 'test_sen' : $out = $this->sen_test(); break; 
	   }
	   //page length select
	   //echo '>>>',GetReq('pl');
	   if ($pl = GetReq('pl')) $this->pagenum = $pl;	
	   
	   //get sen tree info
	   $depth = GetGlobal('controller')->calldpc_method("senptree.get_treedepth use $g");  //echo 'DEPTH:',$depth;
       $parentdir = GetGlobal('controller')->calldpc_method("senptree.isparent use $g");   //echo 'PARENT:',$parentdir;	      

       if ((!$parentdir) || ($depth>2)) {//}($this->querydepth>=3)) {
	   
	     $this->t_sprod = new ktimer;
	     $this->t_sprod->start('sprod');		   
		 
	     //$this->read(); 	   	   
         //$this->retable = getcache(urlencode($g),'dbr','read',$this);	   
	     $id = urlencode($g);
	     $ext = 'db'.$myuserl;//'dbr';
	     $thisclass = 'read_sen_products'; 
	     /*$this->*/$myretable = GetGlobal('controller')->calldpc_method_use_pointers('cache.getcache',  
				                                       array(0=>&$id,
									                         1=>&$ext,
											                 2=>&$thisclass,
												             3=>&$this,
															 4=>&$g,
															 5=>null,
												             6=>null,
												             7=>null,
												             8=>null,
												             9=>null,
												            10=>null,
												            11=>&$cacheyes)   
										              );
         //print_r($myretable);
		 //I MUST REPLACE PRICES FOR BEST PERFORMANCE
	     $this->read_policy(); //get price policy		 
		 $this->retable = $this->replace_price($myretable);
		 //echo "<pre>";
		 //print_r($this->retable);
		 //echo "</pre>"; 
		 								  
	     $this->t_sprod->stop('sprod');
	     //if (paramload('SHELL','debug')) echo "<br>sprod " , $this->t_sprod->value('sprod'); 													  
		 
	     $__USERAGENT = GetGlobal('__USERAGENT');
		
         switch ($__USERAGENT) {		  
	     
		   case 'CLI'  : echo "SENPRODUCTS : " , $this->t_sprod->value('sprod') , "\n";
		                 break;
	       case 'HTML' :
	       default     : if (paramload('SHELL','debug')) {
	                       echo "<br>sprod " , $this->t_sprod->value('sprod'); 											  
	                     }	
	     } 		 
													  
	   }
	   else
	     $this->retable = null;	
		 		 
	}

	
	//return sql script
	function read_sen_products($g=null) { 
	   
	   if (strlen(trim($g))>0) {
	     $group = explode("^",$g);   //print_r($group);
	     $mg = count($group);
	     $depth = ($mg ? $mg : 0); //echo 'DEPTH:',$depth;
	   }
	   else
	     $depth = 0;	 
	   
       $sSQL = "SELECT " . $this->select . " FROM " . $this->T_products;
	   $sSQL .= " WHERE "; 
	   switch ($depth) {
	       case 4 : $sSQL .= "CTGLEVEL5='" . $group[3] . "' and ";
	       case 3 : $sSQL .= "CTGLEVEL4='" . $group[2] . "' and ";
		   case 2 : $sSQL .= "CTGLEVEL3='" . $group[1] . "' and ";
		   case 1 : $sSQL .= "CTGLEVEL2='" . $group[0] . "' and ";
		   default: $sSQL .= "CTGLEVEL1='ΚΑΤΗΓΟΡΙΕΣ ΕΙΔΩΝ'";
					if (!seclevel('SENPTREEADMIN_',$this->userLevelID)) {
					  $sSQL .= " and CTGLEVEL2 not like 'LOST+FOUND'"; 
					}
	   }	
       if (trim($this->criteriaAND[0])!=null) //first array obj not null 
	     $sSQL .=  " AND " . $this->choiceSQL($this->criteriaAND,'AND','=','\'ΕΜΦΑΝΙΖΕΤΑΙ\'',1);		   
	   //echo $sSQL;
	
	   //cache queries 
	   if ($this->cachequeries) $result = $this->sen_db->CacheExecute($this->cachetime,$sSQL);
                           else $result = $this->sen_db->Execute($sSQL);

       //print_r($result); echo ">>>>>>";							   
	   $this->retable = (array) $this->prepare($result,1);//bypass price policy..
	                                                      //prices cached as is and
										                  //replaced after cache read	
		   
       //print_r($this->retable);
	   	   
	   return ($this->retable);
	}		
	
	//cache search
	function search($text,$group=null,$type=null,$case=null) {
	
	  $uealias = $text . "." . $group . "." . $case . "." . $type;	  
	  $ext = 'ss'.$this->userLevelID;
	  $thisclass = 'senproducts.search_sen';	
  	  $ar = array(0=>&$text,1=>&$group,2=>&$type,3=>&$case); 
	  
	  $myretable = GetGlobal('controller')->calldpc_method_use_pointers('cache.getcache_method_use_pointers',  
				                                array(0=>$uealias,
									                  1=>$ext,
											          2=>$thisclass,
												      3=>$ar));
												
	  //I MUST REPLACE PRICES FOR BEST PERFORMANCE
	  $this->read_policy(); //get price policy		 
	  $out = $this->replace_price($myretable);													
												
	  return ($out);														
	}

    function search_sen($text,$group=null,$type=null,$case=null) { 
	   
       $terms = explode (" ", $text, 5); //5 = terms limit
	   //print_r($terms);print $type;
	   
	   switch ($type) {
	       case $this->anyterms : // OR
                                  reset($terms);
						          foreach ($terms as $word_no => $word) {	
								    $mytext = '%'.$word.'%';  
									$search_SQL .= "(" . $this->choiceSQL($this->searchcriteriaOR,'OR','LIKE',$this->sen_db->qstr($mytext),$case) . ")";			   
												   
									if ($terms[$word_no+1]) $search_SQL .= " OR ";
	                              }								  
	                              break;
           case $this->allterms : // AND
                                  reset($terms);							   
						          foreach ($terms as $word_no => $word) {	
								    $mytext = '%'.$word.'%';								  
									$search_SQL .= "(" . $this->choiceSQL($this->searchcriteriaOR,'OR','LIKE',$this->sen_db->qstr($mytext),$case) . ")";			   
																						   
									if ($terms[$word_no+1]) $search_SQL .= " AND ";
	                              }							   		
	                              break;
		   default              :						  
           //case $this->asphrase : // AS IS = default
	                              $mytext = '%'.$text.'%';
								  $search_SQL = $this->choiceSQL($this->searchcriteriaOR,'OR','LIKE',$this->sen_db->qstr($mytext),$case);			   
																		   
		                          //break; 
	   }
  	   //print $search_SQL;	   
	   
       //query
	   //print $group;
       /*$sSQL = "SELECT Κωδικός,Περιγραφή,'path','dba','group','0',Παρατήρηση,Κωδικός,[Τιμή λιανικής],'1'" .
	       ",Οικογένεια,Υποοικογένεια,Ομάδα,Κατηγορία,[Τίτλος Μ_Μέτρ],[Ειδ_κωδικός] from " . 
		       $this->T_products . */
       $sSQL = "SELECT " . $this->select . " FROM " . $this->T_products;				   
	   $sSQL .=" WHERE ( " .  $search_SQL . ")";	
	   			    
       if (trim($this->searchcriteriaAND[0])!=null) //first array obj not null 
	     $sSQL .=  " AND " .$this->choiceSQL($this->criteriaAND,'AND','=','\'ΕΜΦΑΝΙΖΕΤΑΙ\'',1);	   	   
	   		      
			   
	   if (($group) && ($group!=localize('_ALL',getlocal()))) {
		      $sSQL .= " AND CTGLEVEL2='" . $group . "'"; //only CTGLEVEL2 !!!
	   }			   
 
	   //echo '       SEN_SEARCH:',$sSQL;

	   //cache queries 
	   if ($this->cachequeries) $result = $this->sen_db->CacheExecute($this->cachetime,$sSQL);
                           else $result = $this->sen_db->Execute($sSQL);
	   //echo 'fields';					   
	   //print_r($result->fields);					   
	   	   
	   return ($this->prepare($result,1)); //bypass price policy..
	                                       //prices cached as is and
										   //replaced after cache read	   
	}
	
	//cache search based on printers = paratirisi
	function searchdetails($text,$group=null,$type=null,$case=null) {
	
	  $uealias = $text . "." . $group . "." . $case . "." . $type;	  
	  $ext = 'sp'.$this->userLevelID;
	  $thisclass = 'senproducts.search_sen_details';	
  	  $ar = array(0=>&$text,1=>&$group,2=>&$type,3=>&$case); 
	  
	  $myretable = GetGlobal('controller')->calldpc_method_use_pointers('cache.getcache_method_use_pointers',  
				                                array(0=>$uealias,
									                  1=>$ext,
											          2=>$thisclass,
												      3=>$ar));
												
	  //I MUST REPLACE PRICES FOR BEST PERFORMANCE
	  $this->read_policy(); //get price policy		 
	  $out = $this->replace_price($myretable);													
												
	  return ($out);														
	}	
	
    function search_sen_details($text,$group=null,$type=null,$case=null) { 
	   
       $terms = explode (" ", $text, 5); //5 = terms limit
	   //print_r($terms);print $type;
	   
	   switch ($type) {
	       case $this->anyterms : // OR
                                  reset($terms);
						          foreach ($terms as $word_no => $word) {	
								    $mytext = '%'.$word.'%';  
									$search_SQL .= "(" . $this->choiceSQL($this->prn_searchcriteriaOR,'OR','LIKE',$this->sen_db->qstr($mytext),$case) . ")";			   
												   
									if ($terms[$word_no+1]) $search_SQL .= " OR ";
	                              }								  
	                              break;
           case $this->allterms : // AND
                                  reset($terms);							   
						          foreach ($terms as $word_no => $word) {	
								    $mytext = '%'.$word.'%';								  
									$search_SQL .= "(" . $this->choiceSQL($this->prn_searchcriteriaOR,'OR','LIKE',$this->sen_db->qstr($mytext),$case) . ")";			   
																						   
									if ($terms[$word_no+1]) $search_SQL .= " AND ";
	                              }							   		
	                              break;
		   default              :						  
           //case $this->asphrase : // AS IS = default
	                              $mytext = '%'.$text.'%';
								  $search_SQL = $this->choiceSQL($this->prn_searchcriteriaOR,'OR','LIKE',$this->sen_db->qstr($mytext),$case);			   
																		   
		                          //break; 
	   }
  	   //print $search_SQL;	   
	   
       //query
	   //print $group;
       /*$sSQL = "SELECT Κωδικός,Περιγραφή,'path','dba','group','0',Παρατήρηση,Κωδικός,[Τιμή λιανικής],'1'" .
	       ",Οικογένεια,Υποοικογένεια,Ομάδα,Κατηγορία,[Τίτλος Μ_Μέτρ],[Ειδ_κωδικός] from " . 
		       $this->T_products . */
       $sSQL = "SELECT " . $this->prn_select . " FROM " . $this->T_products;				   
	   $sSQL .=" WHERE ( " .  $search_SQL . ")";	
	   			    
       if (trim($this->prn_searchcriteriaAND[0])!=null) //first array obj not null 
	     $sSQL .=  " AND " .$this->choiceSQL($this->prn_searchcriteriaAND,'AND','=','\'ΕΜΦΑΝΙΖΕΤΑΙ\'',1);	   	   
	   		      
			   
	   if (($group) && ($group!=localize('_ALL',getlocal()))) {
		      $sSQL .= " AND CTGLEVEL2='" . $group . "'"; //only CTGLEVEL2 !!!
	   }			   
 
	   //echo '       SEN_SEARCH_printers:',$sSQL;

	   //cache queries 
	   if ($this->cachequeries) $result = $this->sen_db->CacheExecute($this->cachetime,$sSQL);
                           else $result = $this->sen_db->Execute($sSQL);
	   //echo 'fields';					   
	   //print_r($result->fields);					   
	   	   
	   return ($this->prepare($result,1)); //bypass price policy..
	                                       //prices cached as is and
										   //replaced after cache read	   
	}	
	
	function searchprice() {
	
	  /*$uealias = $text . "." . $group . "." . $case . "." . $type;	  
	  $ext = 'sr'.$this->userLevelID;
	  $thisclass = 'senproducts.search_sen_price';	
  	  $ar = array(0=>&$text,1=>&$group,2=>&$type,3=>&$case); 
	  
	  $myretable = GetGlobal('controller')->calldpc_method_use_pointers('cache.getcache_method_use_pointers',  
				                                array(0=>$uealias,
									                  1=>$ext,
											          2=>$thisclass,
												      3=>$ar));*/
	  $myretable = $this->search_sen_price();												  
												
	  //I MUST REPLACE PRICES FOR BEST PERFORMANCE
	  $this->read_policy(); //get price policy		 
	  $bestprice = $this->replace_price($myretable);	
	  
	  //preview
	  if (is_array($bestprice)) {
	    foreach ($bestprice as $code=>$datarec) {
	     //$out .= $code."<br>";	  		
		 $param = explode(";",$datarec); 
		 
		 //get current product view 		
	     $pview = 'senvp';//$this->view;//GetSessionParam("PViewStyle");	//get saved view from products dpc !?	
	     $gr = urlencode($param[4]);
	     $ar = urlencode($param[1]);
	   
	     $description = $param[1] . "\n". $param[6] . "\n" . $param[8] . $this->moneysymbol;		  

	     $data[] = seturl("t=$pview&a=$ar&g=$gr&p=" , 
	                      "<img src=\"" . $param[7] . "\" width=\"145\" height=\"95\" border=\"0\" alt=\"". $description . "\">" );
	     $attr[] = "left";

	     $myarticle = new window('',$data,$attr);
	     $out .= $myarticle->render("center::100%::0::group_article_high::left::0::0::");
	     unset ($data);
	     unset ($attr);		 
	    }	
	  
	    $swin = new window(localize('_BESTPRICE',getlocal()),$out);
	    $wout = $swin->render("center::100%::0::group_dir_body::left::0::0::");	
	    unset ($swin);	   										
												
	    return ($wout);		
	  }	
	}
	
	function search_sen_price($max=5) {
	   
	   if (is_array($this->retable)) {
	     //print_r($this->retable);	
	     $i=0;
	     $price=0;
	     foreach ($this->retable as $code=>$data) {
	   
	       $rec = explode(";",$data);
		   $price = $rec[8];
		   $prices[$code] = $price; 
	     }	
	   
	     if (is_array($prices)) { 
	       asort($prices);
	       reset($prices);
	       //print_r($prices);
	       foreach ($prices as $code=>$price) {
	         $coolprice[$code] = $this->retable[$code];
		     $i+=1;
		     if ($i>$max) break;
	       }
	     }
	   }
	   //print_r($coolprice);	 	   
	   return ($coolprice);	   
	}
	

    //used from search function to define group of finded product
    function prepare_group($family,$ypofamily,$omada,$category) {

		if ($family) {
		
		  $out = $family;
		  
		  if ($ypofamily)  $out .= "^" . $ypofamily;
		  if ($omada)      $out .= "^" . $omada;
		  if ($category)   $out .= "^" . $category;
        }
		
		return ($out);
	}
	
	function get_user_price_policy($leeid=null) {
	
	   $reseller = GetSessionParam('RESELLER');
	
	   if ($this->leeid!=null) 
	     $id = $leeid;
	   else
	     $id = decode(GetSessionParam('UserID'));
	   
	   if ($id) {
	
	     $sSQL = "select EKPTOSH from PANIK_VIEW_PPOLICY where LEEID=" . $id ;
		 //echo $sSQL;
         $result = $this->sen_db->Execute($sSQL);
		 //print_r($result->fields); 
		 
		 if ($percent = $result->fields[0]) {

		   return ($percent);		 
		 } 
		 else { //get default price policy
		 
		   if ($reseller=='true') {
		   
		     /*$sSQL = "select EKPTOSH from PANIK_VIEW_PPOLICY where LEEID='' AND LEENAME=''" . $id;
		     echo $sSQL;
             $result = $this->sen_db->Execute($sSQL);
			 print_r($result); 		   
		     return ($result->fields[0]);*/
			 
			 return ($this->percentoff);//price from config file
		   }
		   else
		     return 0;	 
		 }
		     
	   }
	   
	   return false;	
	}	
	
	function replace_price($myarray) {
	
	   //if no offer product.....
	   
	   if (is_array($myarray)) {
	   
	     $narray = array();
	     foreach ($myarray as $code=>$data) {	 
		 
		   $varray = array();
	       $varray = explode(";",$data);
		   $offerprice = null;
		   
	       if ((defined("SENHL_DPC") && (seclevel('SENHL_DPC',$this->userLevelID)))) {
		     $offerprice = GetGlobal('controller')->calldpc_method("senhl.is_highlighted use ".$varray[0]);
			 //echo $offerprice,">>>>>>>>>>>>>>>>>";
		   }			   
		   if (isset($offerprice))
		     $varray[8] = $this->prepare_price($offerprice,1); 
		   else	 
		     $varray[8] = $this->prepare_price($varray[8]); 
			 
		   $narray[$code] = implode(";",$varray);		   
	     }
         return ($narray);
	   }
	   else
	     return null;  
	}	
	
	function prepare_price($price,$bypass=null) {
	   //echo $price,"-";
	   
	   //$price = str_replace(".",",",$price); //problem in thousands ..remove thousands separator='.'   
	   //echo $price,"-";
	   //in cache cases price is a , separated value MUST BE convert as float . separated		   
	   $price = floatval(str_replace(",",".",$price)); 
	   //echo $price,"<br>";
	   
	   if (isset($bypass)) {//for offer reasons dont get price policy
	   
	     $ret = floatval($price);
		 //echo $ret,"<br>";	
	   }
	   else {//echo 'zzzz';
	     //$reseller = GetSessionParam('RESELLER'); //echo $reseller,">>>>";
	     //if (($this->percentoff>0) && ($reseller=='true')) {
 	     //$this->senpercentoff = $this->get_user_price_policy(); moved to constructor
	     if ($this->senpercentoff>0) {//echo 'xxxx';
	       //echo $price,"-";
		   $sq1 = floatval($price * $this->senpercentoff); //echo $sq1," ";
		   $sq2 = floatval($sq1/100); //echo $sq2," ";
		   //echo $this->senpercentoff," ";
	       $ret = floatval($price - $sq2);  //off
		   //echo $ret,"<br>";
	     }	 
	     else
	       $ret = floatval($price);	 
	
	   }
	   $out = number_format($ret,$this->dec_num,',','');//not . in thousands dur e to convertion problems (see above))		
	   
	   //echo $out,"<br>"; 
	   return ($out);	 	
	}


    function prepare($table,$nopreprice=null) {
	   
	   $a = GetReq('a');   

       $i=0;

       //select array key
	   switch ($a) {
		   case 1 : $index = 0; break;
		   case 2 : $index = 1; break;
		   case 3 : $index = 8; break;  
		   default : $index = 0;
	   }
	   
       while(!$table->EOF) {

		  $key = $table->fields[$index];

          $dataout[$key] = $table->fields[0] . ";" . 
                       $this->preparestr($table->fields[1]) . ";" .
			           $table->fields[2] . ";" .
			           $table->fields[3] . ";" .
			           $this->prepare_group($table->fields[10],
			                                $table->fields[11],
			                                $table->fields[12],
			                                $table->fields[13]) . ";" .
			           $table->fields[5] . ";" .
			           $this->preparestr($table->fields[6]) . ";" .
			           //echo $this->abspicpath . $table->fields[7] . $this->restype; 
					   (file_exists($this->abspicpath . $table->fields[7] . $this->restype) ? 
					     $this->resourcepicpath . $table->fields[7] . $this->restype : 
						 $this->resourcepicpath . $this->nopic . $this->restype) 
					   . ";" .
			           $this->prepare_price(floatval($table->fields[8]),$nopreprice) . ";" .
			           $table->fields[9] . ";" .
					   $table->fields[14] . ";" .//$this->lookup($this->T_boxtype,$table->fields[14],'Κωδικός',1) . ";" .
					   $table->fields[15] . ";" .
					   $table->fields[16] . ";" .
					   $table->fields[17] . ";" .
					   $table->fields[18] . ";" .
					   $table->fields[19];
					   
          //print_r($table->fields);
	      $table->MoveNext();
		  $i+=1;
	   }
	   //print_r($dataout);

       $this->numResult = $i;
       //DEBUG
       if (seclevel('_DEBUG',$this->userLevelID)) echo "Total Rec:" , $this->numResult;

	   //print_r($dataout);
	   return ($dataout);
	   
	}
	
	//general purpose selection for live update on other dpcs(eg. cart recalculation)
    function search_product_record($select,$where) {
	
       $sSQL = "SELECT " . $select . " FROM " . $this->T_products;
	   $sSQL .= " WHERE " . $where; 	
	   //echo $sSQL;
	   
       $result = $this->sen_db->Execute($sSQL);	   
	   	   
	   $ret = implode(";",$result->fields);
	   //echo $ret;
	   //echo $ret;
	   //print_r($result->images);   
	   
	   return ($ret);
	}	
	

    function test_sen() {
       return ('SEn');
    }	
   
   
	function browse($packdata,$view='') { 
	   //$p = GetReq('p');	   
	   //$t = GetReq('t');
	   
	   //if (!$view) $view = $t;
	  
	   $data = explode("||",$packdata);
	
	   switch ($view) {
					      case 1 : 
								   $out = $this->view1($data[0],$data[1],$data[2],$data[3],$data[4],$p,$data[6],$data[7],$data[8],$data[9]);		  
						           break;

						  case 2 : 
								   $out = $this->view2($data[0],$data[1],$data[2],$data[3],$data[4],$p,$data[6],$data[7],$data[8],$data[9],$data[10]);
                                   break;

                          case 3 : $out = $this->view3($data[0],$data[1],$data[2],$data[3],$data[4],$p,$data[6],$data[7],$data[8],$data[9]);
                                   break; 

						  case 4 : $out = $this->view4($data[0],$data[1],$data[2],$data[3],$data[4],$p,$data[6],$data[7],$data[8],$data[9]);
						           break;	
								      
					      case 5 : //product view
								   $out = $this->view5($data[0],$data[1],$data[2],$data[3],$data[4],$p,$data[6],$data[7],$data[8],$data[9]);		  
						           break;

						  case 6 : //photo view
								   $out = $this->view6($data[0],$data[1],$data[2],$data[3],$data[4],$p,$data[6],$data[7],$data[8],$data[9],$data[10],$data[11],$data[12],$data[13],$data[14],$data[15]);
                                   break;

                          case 7 : $out = $this->view7($data[0],$data[1],$data[2],$data[3],$data[4],$p,$data[6],$data[7],$data[8],$data[9],$data[10],$data[11],$data[12],$data[13],$data[14],$data[15]);
                                   break; 

						  case 8 : //barcode view
								   $out = $this->view8($data[0],$data[1],$data[2],$data[3],$data[4],$p,$data[6],$data[7],$data[8],$data[9],$data[10],$data[11],$data[12],$data[13],$data[14],$data[15]);
                                   break;
					
                          case 9 : $out = $this->view9($data[0],$data[1],$data[2],$data[3],$data[4],$p,$data[6],$data[7],$data[8],$data[9],$data[10],$data[11],$data[12],$data[13],$data[14],$data[15]);
						           break; 		   
								   							   
	   }
	   
	   return ($out);
	}     

    function view1($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1) {
	   $a = GetReq('a');   
	   
	   return ($title."<br>");	   
	}

    function view2($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1) {
	   $a = GetReq('a');	
	   
	   $out = "<div class=\"menutitle\" onclick=\"SwitchMenu('$id')\">$title</div>";
	   $out .= "<span class=\"submenu\" id=\"$id\">";
	   $out .= $descr;	   
	   $out .= "</span>";	      
	   
	   return ($out);		      
	}
	
    function view3($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1) {
	   $a = GetReq('a');
	   $p = GetReq('p');
	   $page = $p; //var page allways is 1	   

	   $gr = urlencode($group);
	   $ar = urlencode($title);		
	 
	   $link = seturl("#" , $title);   
	   
	   $out = "<div class=\"menutitle\" onclick=\"SwitchMenu('$id')\">$link</div>";
	   $out .= "<span class=\"submenu\" id=\"$id\">";
	   //$out .= $descr;
	   
       if (seclevel('ADMPRODS_',$this->userLevelID)) $chkbox = $this->admin_checkbox($id);

       if (iniload('JAVASCRIPT')) {		
  	         $plink = "<A href=\"#\" ";	   
	         //call javascript for opening a new browser win for the img		   
	         $params = "$photo;280;340;<B>$title</B><br>$descr;";

			 $js = new jscript;
	         $plink .= $js->JS_function("js_popimage",$params); 
			 unset ($js);

	         $plink .= ">"; 
	   }
	   else
             $plink = "<A href=\"$photo\">";

	   $data[] = $chkbox . $plink . "<img src=\"" . $photo . "\" width=\"100\" height=\"75\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";
	   $attr[] = "left;10%";
	   
	   $data[] = "<B>$title<br></B>" . $descr . "<B>";
	   $attr[] = "left;50%";
	   	   
	   $data[] = $id . "<br>" . $boxtype . 
		         //localize('_PRICE',getlocal()) . " :" .
				 "<H4>" .  $price . $this->moneysymbol . "</H4>";
		      	 //"<br>" . 
				 //localize('_BOXTYPE',getlocal()) . " :" . 
				 //"<B>" . $boxtype . "</B>";	  
	   $attr[] = "center;20%";
	   
	   //get this function args to select the comparision
	   $fargs = func_get_args();
	   if ((!$this->checkprd) || ($fargs[$this->checkprd]>0)) {
	   //if ($price>0) {		  			     
	      //$data[] = dpc_extensions("$id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype",$group,$page); 		  
	      $data[] = GetGlobal('controller')->calldpc_method("metacache.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype+$group+$page",1) .
	                GetGlobal('controller')->calldpc_method("sencart.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype+$group+$page",1) .	   
	                GetGlobal('controller')->calldpc_method("neworder.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype+$group+$page",1);				  
	      $attr[] = "center;20%";			  		 		
	   }		  
       else {
	      $data[] = $this->notavail;//localize('_NOTAVAL',getlocal()); 
	      $attr[] = "center;20%";		  
	   }	   
	   
	   $myarticle = new window('',$data,$attr);
	   $out .= $myarticle->render("center::100%::0::group_article_body::left::0::0::") . "<hr>";
	   unset ($data);
	   unset ($attr);	   
	   
	   
	   $out .= "</span>";	      
	   
	   return ($out);		   
	}
	
    function view4($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1) {		
	
       $plink = "<A href=\"$photo\">";

	   $data[] = $chkbox . $plink . "<img src=\"" . $photo . "\" width=\"100\" height=\"75\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";
	   $attr[] = "left;10%";
	   
	   $data[] = "<B>$title<br></B>" . $descr . "<B>";
	   $attr[] = "left;50%";
	   	   
	   
	   $myarticle = new window('',$data,$attr);
	   $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::") . "<hr>";
	   unset ($data);
	   unset ($attr);

	   return ($out);
	}			   	
	
    function view5($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1) {
	   $a = GetReq('a');
	   $p = GetReq('p');	   
	   $page = $p; //var page allways is 1
	
       if ($a == $title) {
	   
         if (seclevel('ADMPRODS_',$this->userLevelID)) $chkbox = $this->admin_checkbox($id);

	     $data[] = $chkbox . $id; 
	     $attr[] = "left;25%";							  
	     $data[] = "<B>$title</B><br>" . $descr;
	     $attr[] = "left;25%";

         if (iniload('JAVASCRIPT')) {	
  	         $plink = "<a href=\"#\" ";	   
	         //call javascript for opening a new browser win for the img		   
	         //$params = $photo . ";Image;width=300,height=200;";
	         $params = "$photo;280;340;<B>$title</B><br>$descr;";			 

			 $js = new jscript;
	         $plink .= $js->JS_function("js_popimage",$params); 
			 unset ($js); 

	         $plink .= ">";
	     }
	     else
             $plink = "<A href=\"$photo\">";

	     $data[] = $plink . "<img src=\"" . $photo . "\" width=\"100\" height=\"75\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";
	     $attr[] = "left;25%";
	   
	     $data[] = $price . $this->moneysymbol;
	     $attr[] = "right;15%";
	   
	     //get this function args to select the comparision
	     $fargs = func_get_args();
	     if ((!$this->checkprd) || ($fargs[$this->checkprd]>0)) {
		 
	        $data[] = GetGlobal('controller')->calldpc_method("metacache.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;+$group+$page",1) .
	                  GetGlobal('controller')->calldpc_method("sencart.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;+$group+$page",1) .	   
	                  GetGlobal('controller')->calldpc_method("neworder.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;+$group+$page",1);		  
	        $attr[] = "right;10%";
	     }
         else {
	        $data[] = $this->notavail;//localize('_NOTAVAL',getlocal()); 
	        $attr[] = "right;10%";		  
	     }	   

	     $myarticle = new window('',$data,$attr);
	     $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::");
	     unset ($data);
	     unset ($attr);

	     return ($out);
	   
       }
	   else {

	     $gr = urlencode($group);
	     $ar = urlencode($title);
	
	     $link = seturl("t=senvp&a=$ar&g=$gr&p=$p" ,$title);
	   
         if (seclevel('ADMPRODS_',$this->userLevelID)) $chkbox = $this->admin_checkbox($id);

	     $data[] = $chkbox . $id; 
	     $attr[] = "left;25%";							  
	     $data[] = $link;
	     $attr[] = "left;50%";
	   
	     $data[] = $price . $this->moneysymbol;   
	     $attr[] = "right;15%";
	   
	     //get this function args to select the comparision
	     $fargs = func_get_args();
	     if ((!$this->checkprd) || ($fargs[$this->checkprd]>0)) {		  		  
	        //$data[] = dpc_extensions("$id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;",$group,$page);
	        $data[] = GetGlobal('controller')->calldpc_method("metacache.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;+$group+$page",1) .
	                  GetGlobal('controller')->calldpc_method("sencart.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;+$group+$page",1) .	   
	                  GetGlobal('controller')->calldpc_method("neworder.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;+$group+$page",1);			  
	        $attr[] = "right;10%";
	     }
         else {
	        $data[] = $this->notavail;//localize('_NOTAVAL',getlocal());
	        $attr[] = "right;10%";		  
	     }

	     $myarticle = new window('',$data,$attr);
	     $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::");
	     unset ($data);
	     unset ($attr);

	     return ($out);
      }
	}

    function view6($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1,$uninameA=null,$uninameB=null,$sxA=null,$sxB=null,$ypoA=null,$ypoB=null) {	   	
	     $a = GetReq('a');
	     $p = GetReq('p');
	     $page = $p; //var page allways is 1	   

	     $gr = urlencode($group);
	     $ar = urlencode($title);	

	     $link = seturl("t=senvp&a=$ar&g=$gr&p=$p" , $title);
	   
         //if (seclevel('ADMPRODS_',$this->userLevelID)) $chkbox = $this->admin_checkbox($id);

		 //photo  
         /*if (iniload('JAVASCRIPT')) {		
  	         $plink = "<A href=\"#\" ";	   
	         //call javascript for opening a new browser win for the img	
			 //$params = $photo . ";Image;width=300,height=200;";	   
	         $params = "$photo;280;340;<B>$title</B><br>$descr;";

			 $js = new jscript;
	         $plink .= $js->JS_function("js_popimage",$params); 
			 unset ($js);

	         $plink .= ">"; 
	     }
	     else
             $plink = "<A href=\"$photo\">";*/
			 
  	     $data[] = /*$chkbox . $plink .*/ "<img src=\"" . $photo . "\" width=\"100\" height=\"75\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";
	     $attr[] = "left;10%";
	   
	     //title
	     $data[] = "<B>$title<br></B>" . $descr . "<B>";
	     $attr[] = "left;50%";

		 
	     //unimames + ids + price 
	     $c2 = $id . "<br>";
		 $c2 .=	 "<H4>" .  $price . $this->moneysymbol . "</H4>"; 	  
	     $data[] = $c2; 			 
	     $attr[] = "center;20%";		 
			
	     /*if ((floatval(str_replace(",",".",$price))>0.001) && ($ypoA>0)) {			
            //symbols		 
	        $data[] = GetGlobal('controller')->calldpc_method("metacache.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$uninameA;$uninameB;$sxA;$sxB;$ypoA;$ypoB+$group+$page",1) .
	                  GetGlobal('controller')->calldpc_method("sencart.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$uninameA;$uninameB;$sxA;$sxB;$ypoA;$ypoB+$group+$page",1) .	   
	                  GetGlobal('controller')->calldpc_method("neworder.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$uninameA;$uninameB;$sxA;$sxB;$ypoA;$ypoB+$group+$page",1);			  
	        $attr[] = "center;20%";			  		
	     }		  
         else {
	        $data[] = $this->notavail;//localize('_NOTAVAL',getlocal()); 
	        $attr[] = "center;20%";		  
	     }	*/   

	     $myarticle = new window('',$data,$attr);
	   
         if ($a == $title)
           $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::") . "<hr>";		   
		 else
	       $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::") . "<hr>";
	   
	     unset ($data);
	     unset ($attr);

	     return ($out);
	}
	
    ///////////////////////////////////////////////////////////
    // view 7
    ///////////////////////////////////////////////////////////
    function view7($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1,$uninameA=null,$uninameB=null,$sxA=null,$sxB=null,$ypoA=null,$ypoB=null) {
	     $a = GetReq('a');
	     $p = GetReq('p');
	     $page = $p; //var page allways is 1	   

	     $gr = urlencode($group);
	     $ar = urlencode($title);	

	     $link = seturl("t=senvp&a=$ar&g=$gr&p=$p" , $title);
	   
         if (seclevel('ADMPRODS_',$this->userLevelID)) $chkbox = $this->admin_checkbox($id);

		 //photo  
         if (iniload('JAVASCRIPT')) {		
  	         $plink = "<A href=\"#\" ";	   
	         //call javascript for opening a new browser win for the img	
			 //$params = $photo . ";Image;width=300,height=200;";	   
	         $params = "$photo;280;340;<B>$title</B><br>$descr;";

			 $js = new jscript;
	         $plink .= $js->JS_function("js_popimage",$params); 
			 unset ($js);

	         $plink .= ">"; 
	     }
	     else
             $plink = "<A href=\"$photo\">";
  	     $data[] = $chkbox . $plink . "<img src=\"" . $photo . "\" width=\"100\" height=\"75\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";
	     $attr[] = "left;10%";
	   
	     //title
	     $data[] = "<B>$title<br></B>" . $descr . "<B>";
	     $attr[] = "left;50%";
	   
	     //unimames + ids + price 
	     $c2 = $id . "<br>";
		 /*if ($uninameB) {
		   $c2 .= $uninameA."<br>".$uninameB;
		 }
		 else
		  $c2 .= $uninameA;*/
		   	
		 $c2 .=	 "<H4>" . $price . $this->moneysymbol ."</H4>"; 	  
	     $data[] = $c2; 			 
	     $attr[] = "center;20%";

		 //symbols (THE CHECK IS INSIDE SHOWSYMBOL)
	     //if ((floatval(str_replace(",",".",$price))>0.001) && ($ypoA>0)) {
	        $data[] = GetGlobal('controller')->calldpc_method("metacache.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$uninameA;$uninameB;$sxA;$sxB;$ypoA;$ypoB+$group+$page",1) .
	                  GetGlobal('controller')->calldpc_method("sencart.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$uninameA;$uninameB;$sxA;$sxB;$ypoA;$ypoB+$group+$page",1) .	   
	                  GetGlobal('controller')->calldpc_method("neworder.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$uninameA;$uninameB;$sxA;$sxB;$ypoA;$ypoB+$group+$page",1);			  
	        $attr[] = "center;20%";			  		
	     /*}		  
         else {
	        $data[] = $this->notavail;//localize('_NOTAVAL',getlocal()); 
	        $attr[] = "center;20%";		  
	     }*/	

	     $myarticle = new window('',$data,$attr);
	   
         if ($a == $title)
           $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::") . "<hr>";		   
		 else
	       $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::") . "<hr>";
	   
	     unset ($data);
	     unset ($attr);

	     return ($out);
    }	
	
	
    function view8($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1,$uninameA=null,$uninameB=null,$sxA=null,$sxB=null,$ypoA=null,$ypoB=null) {	   	

	   $a = GetReq('a');
	   $p = GetReq('p');	
	   $page = $p; //var page allways is 1	      

	   $gr = urlencode($group);
	   $ar = urlencode($title);	
	   	   
	   $link = seturl("t=senvp&a=$ar&g=$gr&p=$p" , $title);
	   
       if (seclevel('ADMPRODS_',$this->userLevelID)) $chkbox = $this->admin_checkbox($id);

       if (iniload('JAVASCRIPT')) {		
  	         $plink = "<A href=\"#\" ";	   
	         //call javascript for opening a new browser win for the img		   
	         $params = "$photo;280;340;<B>$title</B><br>$descr;";

			 $js = new jscript;
	         $plink .= $js->JS_function("js_popimage",$params); 
			 unset ($js);

	         $plink .= ">"; 
	   }
	   else
             $plink = "<A href=\"$photo\">";

	   $data[] = $chkbox . $plink . "<img src=\"" . $photo . "\" width=\"100\" height=\"75\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";
	   $attr[] = "left;10%";
	   
	   $data[] = "<B>$title<br></B>" . $descr . "<B>";
	   $attr[] = "left;50%";
	   	   
	   $data[] = "$id<br>" .
		         localize('_PRICE',getlocal()) . " :<B>" . $price . $this->moneysymbol . "</B>" .
		      	 "<br>" . $uninameA . "<br>" . $uninameB;	  
	   $attr[] = "center;20%";
	   		  			  
       $bc = new BarcodeI25();		  
       $bc->SetCode("4901681133581");
		  	   
	   $data[] = $bc->Generate(); 		  
	   $attr[] = "center;20%";			  		 		   
	   
	   $myarticle = new window('',$data,$attr);
	   
       if ($a == $title) 	   
	     $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::") . "<hr>";	   
	   else
	     $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::") . "<hr>";
	   unset ($data);
	   unset ($attr);
	   

	   return ($out);
	}	
	
	
    function view9($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1,$boxtype='',$uninameA=null,$uninameB=null,$sxA=null,$sxB=null,$ypoA=null,$ypoB=null) {	   	

	   $a = GetReq('a');
	   $p = GetReq('p');	   
	   $page = $p; //var page allways is 1	   

	   $gr = urlencode($group);
	   $ar = urlencode($title);	

	   $link = seturl("t=senvp&a=$ar&g=$gr&p=$p" , $title);
	   
       if (seclevel('ADMPRODS_',$this->userLevelID)) $chkbox = $this->admin_checkbox($id);

       if (iniload('JAVASCRIPT')) {		
  	         $plink = "<A href=\"#\" ";	   
	         //call javascript for opening a new browser win for the img		   
	         //$params = $photo . ";Image;width=300,height=200;";
	         $params = "$photo;280;340;<B>$title</B><br>$descr;";
			 $js = new jscript;
	         //$plink .= $js->JS_function("js_openwin",$params); 
	         $plink .= $js->JS_function("js_popimage",$params);			 
			 unset ($js);

	         $plink .= ">"; 
	   }
	   else
             $plink = "<A href=\"$photo\">";

	   $data[] = $chkbox . $plink . "<img src=\"" . $photo . "\" width=\"100\" height=\"75\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";
	   $attr[] = "left;10%";
	   
	   $data[] = "<B>$title<br></B>" . $descr . "<B>";
	   $attr[] = "left;50%";
	   	   
       $data[] = "$id<br>" .
		         "<B>" . $price . $this->moneysymbol . "</B>" .
		         "<br>" . $uninameA . "<br>" . $uninameB;	 	  
	   $attr[] = "center;25%";
 
	   $data[] = $ypoA . "<br>" . $ypoB; 
	   $attr[] = "center;25%";		  	   
	   
	   $myarticle = new window('',$data,$attr);
       if ($a == $title)    
 	     $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::") . "<hr>";	   
	   else
	     $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::") . "<hr>";
	   unset ($data);
	   unset ($attr);

	   return ($out);
	}	
	
	
	
	
	
	///////////////////////////////////////////////////////////
    // view search result
    ///////////////////////////////////////////////////////////
    function view_search($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1,$uninameA=null,$uninameB=null,$sxA=null,$sxB=null,$ypoA=null,$ypoB=null) {
	     $a = GetReq('a');
	     $p = GetReq('p');
	     $page = $p; //var page allways is 1	   

	     $gr = urlencode($group);
	     $ar = urlencode($title);	

	     $link = seturl("t=senvp&a=$ar&g=$gr&p=$p" , $title);
	   
         //if (seclevel('ADMPRODS_',$this->userLevelID)) $chkbox = $this->admin_checkbox($id);

		 //photo  
         /*if (iniload('JAVASCRIPT')) {		
  	         $plink = "<A href=\"#\" ";	   
	         //call javascript for opening a new browser win for the img	
			 //$params = $photo . ";Image;width=300,height=200;";	   
	         $params = "$photo;280;340;<B>$title</B><br>$descr;";

			 $js = new jscript;
	         $plink .= $js->JS_function("js_popimage",$params); 
			 unset ($js);

	         $plink .= ">"; 
	     }
	     else
             $plink = "<A href=\"$photo\">";*/
  	     $data[] = /*$chkbox . $plink .*/ "<img src=\"" . $photo . "\" width=\"100\" height=\"75\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";
	     $attr[] = "left;10%";
	   
	     //title
	     $data[] = "<B>$link<br></B>" . $descr . "<B>";
	     $attr[] = "left;50%";
	   
	     //unimames + ids + price 
	     $c2 = $id . "<br>";
		 /*if ($uninameB) {
		   $c2 .= $uninameA."<br>".$uninameB;
		 }
		 else
		  $c2 .= $uninameA;*/
		   	
		 $c2 .=	 "<H4>" . $price . $this->moneysymbol . "</H4>"; 	  
	     $data[] = $c2; 			 
	     $attr[] = "center;20%";

		 //symbols
	     /*
	     $data[] = GetGlobal('controller')->calldpc_method("metacache.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$uninameA;$uninameB;$sxA;$sxB;$ypoA;$ypoB+$group+$page",1) .
	               GetGlobal('controller')->calldpc_method("sencart.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$uninameA;$uninameB;$sxA;$sxB;$ypoA;$ypoB+$group+$page",1) .	   
	               GetGlobal('controller')->calldpc_method("neworder.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$uninameA;$uninameB;$sxA;$sxB;$ypoA;$ypoB+$group+$page",1);			  
	     $attr[] = "center;20%";			  		
   	     */  

	     $myarticle = new window('',$data,$attr);
	   
         if ($a == $title)
           $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::") . "<hr>";		   
		 else
	       $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::") . "<hr>";
	   
	     unset ($data);
	     unset ($attr);

	     return ($out);
    }		
	
	
    function admin_checkbox($id) {
	
	   $myid = $id . ';';

       return ("<input type=\"checkbox\" name=\"$id\" value=\"$myid\">");
    }	


	function headtitle($view) {
	    $a = GetReq('a');
	    $p = GetReq('p');
	    $g = GetReq('g');
	    $t = 'senvp';//GetReq('t');	   		
		
	    $gr = urlencode($g);
	    $ar = urlencode($a);		

        switch ($view) {   		

			case 5 :
		             $data[] = seturl("t=$t&a=1&g=$gr&p=$p" , localize('_CODE',getlocal()) );
					 $attr[] = "left;25%";							  
					 $data[] = seturl("t=$t&a=2&g=$gr&p=$p" , localize('_DESCR',getlocal()) );
					 $attr[] = "left;50%";
					 $data[] = seturl("t=$t&a=3&g=$gr&p=$p" , localize('_PRICE',getlocal()) );
					 $attr[] = "right;15%";
					 $data[] = "&nbsp;";
					 $attr[] = "right;10%";

					 $mytitle = new window('',$data,$attr);
					 $out .= $mytitle->render(" ::100%::0::group_form_headtitle::center;100%;::");
					 unset ($data);
					 unset ($attr);
				     break;

			case 6 : break;
			case 7 :			
		             /*$data[] = seturl("t=$t&a=1&g=$gr&p=$p" , localize('_CODE',getlocal()) );
					 $attr[] = "left;20%";							  
					 $data[] = seturl("t=$t&a=2&g=$gr&p=$p" , localize('_DESCR',getlocal()) );
					 $attr[] = "left;55%";
					 $data[] = seturl("t=$t&a=3&g=$gr&p=$p" , localize('_PRICE',getlocal()) );
					 $attr[] = "right;15%";
					 $data[] = "&nbsp;";
					 $attr[] = "right;10%";

					 $mytitle = new window('',$data,$attr);
					 $out .= $mytitle->render(" ::100%::0::group_form_headtitle::center;100%;::");
					 unset ($data);
					 unset ($attr);*/
				     break;
			case 8 : break;					 
		}
		return ($out);					 	
	}			
	
	
};
}
}
else die("SEN DPC REQUIRED!");
?>