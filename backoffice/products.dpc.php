<?php
if (defined("BACKOFFICE_DPC")) {

$__DPCSEC['PRODUCTS_DPC']='2;2;2;2;2;2;2;2;9';
$__DPCSEC['ADMPRODS_']='2;1;1;1;1;1;1;2;9';

if ((!defined("PRODUCTS_DPC")) && (seclevel('PRODUCTS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("PRODUCTS_DPC",true);

$__DPC['PRODUCTS_DPC'] = 'products';

$__EVENTS['PRODUCTS_DPC'][0]= "update_pcode";
$__EVENTS['PRODUCTS_DPC'][1]=1;
$__EVENTS['PRODUCTS_DPC'][2]=2;
$__EVENTS['PRODUCTS_DPC'][3]=3;
$__EVENTS['PRODUCTS_DPC'][4]=4;
$__EVENTS['PRODUCTS_DPC'][5]=5;
$__EVENTS['PRODUCTS_DPC'][6]=6;
$__EVENTS['PRODUCTS_DPC'][7]=7;
$__EVENTS['PRODUCTS_DPC'][8]=8;
$__EVENTS['PRODUCTS_DPC'][9]=9;
$__EVENTS['PRODUCTS_DPC'][10]='pdf';

$__ACTIONS['PRODUCTS_DPC'][0]=1;
$__ACTIONS['PRODUCTS_DPC'][1]=2;
$__ACTIONS['PRODUCTS_DPC'][2]=3;
$__ACTIONS['PRODUCTS_DPC'][3]=4;
$__ACTIONS['PRODUCTS_DPC'][4]=5;
$__ACTIONS['PRODUCTS_DPC'][5]=6;
$__ACTIONS['PRODUCTS_DPC'][6]=7;
$__ACTIONS['PRODUCTS_DPC'][7]=8;
$__ACTIONS['PRODUCTS_DPC'][8]=9;
$__ACTIONS['PRODUCTS_DPC'][9]='pdf';

$__DPCATTR['PRODUCTS_DPC']['update_pcode'] = 'update_pcode,0,0,0,0,0,1,0,0,0';
$__DPCATTR['PRODUCTS_DPC']['pdf'] = 'pdf,0,0,0,0,0,1,0,0,0';
$__DPCATTR['PRODUCTS_DPC']['1'] = '1,0,0,0,0,0,0,1,0,1';
$__DPCATTR['PRODUCTS_DPC']['2'] = '2,0,0,0,0,0,0,1,0,1';
$__DPCATTR['PRODUCTS_DPC']['3'] = '3,0,0,0,0,0,0,1,0,1';
$__DPCATTR['PRODUCTS_DPC']['4'] = '4,0,0,0,0,0,0,1,0,1';
$__DPCATTR['PRODUCTS_DPC']['5'] = '5,0,0,0,0,0,0,1,0,1';
$__DPCATTR['PRODUCTS_DPC']['6'] = '6,0,0,0,0,0,0,1,0,1';
$__DPCATTR['PRODUCTS_DPC']['7'] = '7,0,0,0,0,0,0,1,0,1';
$__DPCATTR['PRODUCTS_DPC']['8'] = '8,0,0,0,0,0,0,1,0,1';
$__DPCATTR['PRODUCTS_DPC']['9'] = '9,0,0,0,0,0,0,1,0,1';

require_once("barcode.lib.php");
require_once("backoffice.dpc.php");

class products extends backoffice {

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
	
	var $abspicpath;
	var $restype;
	var $nopic;
	
	var $checkprd;
	
	var $timeout;

	function products($dirinfo='',$depth=0) {
	   $UserSecID = GetGlobal('UserSecID');
	   $GRX = GetGlobal('GRX');	   
	 
	   $this->t_dbtable = new ktimer;
	   $this->t_dbtable->start('products');  	   
	   
       backoffice::backoffice();	   

       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
	   
       if (GetSessionParam("PViewStyle")!=null)  {
	     $this->view = GetSessionParam("PViewStyle");
	   }
	   else {
 	     $viewperclient = arrayload('PRODUCTS','viewperclient');				
	     $this->view  = $viewperclient[$this->userLevelID];
	     if (!$this->view) $this->view = paramload('PRODUCTS','dview');	
	     //echo $this->view,">>>>>>";
	   }
	   //save view if not set
       //if (!GetSessionParam("PViewStyle")) SetSessionParam("PViewStyle", $this->view);	        

       $this->homedir = localize(paramload('DIRECTORY','diralias'),getlocal());
       $this->directory_mark = paramload('DIRECTORY','dirmark');
	   $this->querydepth = $depth;
	   $this->numResult = 0;
	   $this->retable = 0;
	   $this->pagenum = 20;
	   //$this->resourcepicpath = paramload('SHELL','urlbase') . paramload('PRODUCTS','dbres');	   
       $ip = $_SERVER['HTTP_HOST'];
       $pr = paramload('SHELL','protocol');		   
	   $this->resourcepicpath = $pr . $ip . paramload('PRODUCTS','dbres');	   
	   $this->timeout = paramload('SHELL','timeout');
	   	   
       $this->moneysymbol = "&" . paramload('CART','cursymbol') . ";";

		if ($dirinfo) {
	        $this->rname = str_replace($this->directory_mark,"",$dirinfo[1]);
			$this->alias = $dirinfo[2];
			$this->path = $dirinfo[0] . $dirinfo[1];
		}
		else {
	        $this->rname = str_replace($this->directory_mark,"",$dirinfo[1]);
			$this->alias = $this->home;
			$this->path = paramload('DIRECTORY','dirname');
		}

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
	    
	   $this->select = paramload('PRODUCTS','select');
	   $this->criteria = explode("+",paramload('PRODUCTS','where'));//print paramload('PRODUCTS','where');
	   $this->criteriaOR = explode(",",$this->criteria[0]); //print_r($this->criteriaOR);	   
	   $this->criteriaAND = explode(",",$this->criteria[1]); //print_r($this->criteriaAND);   	   
	   $this->searchcriteria = explode(",",paramload('PRODUCTS','swhere')); //print_r($this->searchcriteria);	   
	   
	   //get abs path
	   $this->abspicpath = paramload('SHELL','prpath') . paramload('SHELL','picpath')  . paramload('PRODUCTS','dbres');
	   $this->restype = paramload('PRODUCTS','restype');
	   $this->nopic = paramload('PRODUCTS','nopic');
	   
	   $this->checkprd = paramload('PRODUCTS','checkproduct');	   
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
	   $param1 = GetGlobal('param1');//parameters by command line
	   $param2 = GetGlobal('param2');
	   	  
	   switch ($evn) {	
	     case 2               :
		 case 3               : if (iniload('JAVASCRIPT')) {
	                                    $code = $this->javascript();	   	
		                                $js = new jscript;	 	      
                                        $js->load_js($code,"",1);		   			   
		                                unset ($js);
	                            }
								break; 
	    
	     case "update_pcode"  : $this->update_productcode($param1,$param2); break;	
		 case "pdf"           : $this->pdfdoc = new ppdf(); //start pdf document 
	                            //$this->pdfdoc->render();		 
		                        break; 
	   }
	}
	
	function action($act) {
	   $param1 = GetGlobal('param1');//parameters by command line
	   
	   $g= GetReq('g');	   
	   
	   if ($param1) $g = $param1;
	   
	   //re-save view
	   SetSessionParam("PViewStyle", $act);		   
	   
	   //create a dir alias object with $g param
       $mydir = new _directory($g); 											 
	   $currentdir = $mydir->getaliasinfo($g,2);//print_r($currentdir);
	   $depth = $mydir->getdirdepth();
       $parentdir = $mydir->isparent();	   
	   unset($mydir);	    
	   	  
	   switch ($act) {	
		 case 1      : $this->products($currentdir,$depth); $this->pagenum = 20; break;
		 case 2      : $this->products($currentdir,$depth); $this->pagenum = 10; break;
		 case 3      : $this->products($currentdir,$depth); $this->pagenum = 10; break;
		 case 4      : $this->products($currentdir,$depth); $this->pagenum = 10; break;
         case 5      : $this->products($currentdir,$depth); $this->pagenum = 20; break;
		 case 6      : $this->products($currentdir,$depth); $this->pagenum = 10; break;
         case 7      : $this->products($currentdir,$depth); $this->pagenum = 20; break;
		 case 8      : $this->products($currentdir,$depth); $this->pagenum = 10; break;
		 case 9      : $this->products($currentdir,$depth); $this->pagenum = 10; break;			 	      	 	 	      	 		 
		 case 'pdf'  : $this->products($currentdir,$depth); $this->pagenum = 10; 
		 
		               //$pdfdata = getcache(urlencode($g),'dbr','read',$this);
	                   $id = urlencode($g);
	                   $ext = 'dbr';
	                   $thisclass = 'read'; 
	                   $pdfdata = GetGlobal('controller')->calldpc_method_use_pointers('cache.getcache',  
				                                               array(0=>&$id,
									                                 1=>&$ext,
											                         2=>&$thisclass,
												                     3=>&$this)  
										                     );					   
		               $this->create_pdf($pdfdata,595,842);
						
	                   $this->pdfdoc->render();	
			  	       $this->pdfdoc->end_ppdf(); //and exiting
		               break;		 
	   }	   

       if ((!$parentdir) || ($this->querydepth>=3)) {
	     //$this->read(); 	   	   
         //$this->retable = getcache(urlencode($g),'dbr','read',$this);	   
	     $id = urlencode($g);
	     $ext = 'dbr';
	     $thisclass = 'read'; 
	     $this->retable = GetGlobal('controller')->calldpc_method_use_pointers('cache.getcache',  
				                                       array(0=>&$id,
									                         1=>&$ext,
											                 2=>&$thisclass,
												             3=>&$this)  
										              );
													  
		 
		 switch ($act) {
		   case 2  :  //VIEW 2,3 NEED THIS...
		   case 3  :  $out .= "<div id=\"masterdiv\"".$this->render()."</div>";
		              break;	
		   default :  $out .= $this->render();
	     }
	   }
	   
	   return ($out); 
	}	
	
	///////////////////////////////////////////////////
	//PDF action
	function create_pdf($data,$xpage,$ypage) {
	   $g = GetReq('g');   
	
	   static $page;// = 0;
	   static $pageY = 700;// = $ypage;
	
	   $this->pdfdoc->page_start($xpage,$ypage,0);
	   
   	   $font = PDF_findfont($this->pdfdoc->doc,"Helvetica-Bold","host",0);
       pdf_add_outline($this->pdfdoc->doc, "Page ".++$page);
	   
       PDF_setfont($this->pdfdoc->doc,$font,22.0);
       PDF_set_text_pos($this->pdfdoc->doc,450,750);
       PDF_show($this->pdfdoc->doc,$g);		   
  	   $this->pdfdoc->getimage("logo.jpg","jpeg",50,750);
	   
	   foreach ($data as $id=>$rec) {
	     $field = explode(";",$rec);
		 
	     if ($pageY>=150) $pageY-=70; 
		            else {
 			    	    $this->pdfdoc->page_end();
					    $this->pdfdoc->page_start($xpage,$ypage,0);
   	                    $font = PDF_findfont($this->pdfdoc->doc,"Helvetica-Bold","host",0);
                        pdf_add_outline($this->pdfdoc->doc, "Page ".++$page);
	                    PDF_setfont($this->pdfdoc->doc,$font,18.0);							
						$pageY=700;
						
 				        PDF_setfont($this->pdfdoc->doc,$font,22.0);
                        PDF_set_text_pos($this->pdfdoc->doc,450,750);
                        PDF_show($this->pdfdoc->doc,$g);
						$this->pdfdoc->getimage("logo.jpg","jpeg",50,750);						
					}
	
	     $this->pdfdoc->getimage("e:/webos/projects/panikidis/images/resources/". $field[0] .".jpg","jpeg",50,$pageY-40,0.3);				
	     PDF_setfont($this->pdfdoc->doc,$font,9.0);	   	 
 	     PDF_set_text_pos($this->pdfdoc->doc,200,$pageY);
 	     PDF_show($this->pdfdoc->doc,$field[0]);
         PDF_setfont($this->pdfdoc->doc,$font,9.0);
 	     PDF_set_text_pos($this->pdfdoc->doc,300,$pageY);
 	     PDF_show($this->pdfdoc->doc,$field[1]);	
  	     PDF_set_text_pos($this->pdfdoc->doc,500,$pageY);
 	     PDF_show($this->pdfdoc->doc,$field[8]);		 	 
 	     //PDF_set_text_pos($this->pdfdoc->doc,200,$pageY-10);
 	     PDF_show_boxed($this->pdfdoc->doc,$field[6],200,$pageY-60,300,60,'left');		 
  	     //PDF_set_text_pos($this->pdfdoc->doc,50,$pageY-10);
 	     //PDF_show($this->pdfdoc->doc,$field[7]);
	   }
	   
	   //$this->pdfdoc->end_template();
	   $this->pdfdoc->page_end();
	}
		

    function render() {
	    $g = GetReq('g');	
		
        if ($this->retable) {
			     $mydbrowser = new browse($this->retable,$g,1,'1,2,3,4,5,6,7,8,9','a,b,c,d,e,f,g,h,i,k,l,m');	   
	             $out .= $mydbrowser->render($this->view,$this->pagenum,$this,1,1,1,1,1,1,1,1); 
  	             unset ($mydbrowser);	   
		}
		else {
			     $swin = new window('',localize('_EMPTYDIR',getlocal()));
	             $out .= $swin->render();
	             unset ($swin);	
		}	

        return ($out);
    }

    function read($criteria='') {
	   
	       $this->get_MetaTable();
	   
           //$sSQL = "SELECT Κωδικός,Περιγραφή,'path','dba','group','0',Παρατήρηση,Κωδικός,[Τιμή λιανικής],'1'" .
		   //    ",Οικογένεια,Υποοικογένεια,Ομάδα,Κατηγορία,[Τίτλος Μ_Μέτρ],[Ειδ_κωδικός]" . 
		   //	   " from " . $this->T_products;
		   	   
           $sSQL = "SELECT " . $this->select . " FROM " . $this->T_products;			   

           if ($criteria) {
		     /* $crit = explode(";",$criteria);
		      $maxcrit = (count($crit) - 2);			  
			  $sSQL .= " WHERE"; 
              //while (list ($cr_num, $cr) = each ($crit)) {
	          foreach ($crit as $cr_num => $cr) {	
			    if (trim($cr)!='') {			  
				  $sSQL .= " Κωδικός=" . $this->db_con->qstr($cr);
			      if ($cr_num<$maxcrit) $sSQL .= " or";
		  	    }
			  }*/
			  $sSQL .= " WHERE" . $this->SQLchoice($criteria,'OR','=','Κωδικός',';');
		   }
		   else {		   
		     switch ($this->querydepth) {
			   //case 1 : $sSQL .= " where Οικογένεια=" . $this->db_con->qstr($this->rname); break;
			   //case 2 : $sSQL .= " where Υποοικογένεια=" . $this->db_con->qstr($this->rname); break;
			   //case 3 : $sSQL .= " where Ομάδα=" . $this->db_con->qstr($this->rname); break;
			   //case 4 : $sSQL .= " where Κατηγορία=" . $this->db_con->qstr($this->rname); break;
			   case 1 :
			   case 2 :
			   case 3 :
			   case 4 : $sSQL .= " WHERE " . $this->criteriaOR[($this->querydepth-1)] . "=" . $this->db_con->qstr($this->rname);
			            break;
			  default : //$sSQL .= " WHERE Οικογένεια=" . $this->db_con->qstr($this->rname) . " or Υποοικογένεια=" . $this->db_con->qstr($this->rname) .
                          //               " or Ομάδα=" . $this->db_con->qstr($this->rname) . " or Κατηγορία=" . $this->db_con->qstr($this->rname);
                        $sSQL .= " WHERE " . $this->choiceSQL($this->criteriaOR,'OR','=',$this->db_con->qstr($this->rname));
		     }
		   }
		   
		   //$sSQL .= " AND [Ειδών.Internet]=TRUE";      //<<<<<<<<<<<<<<<<<<<<<<
		   $sSQL .=  " AND " .$this->choiceSQL($this->criteriaAND,'AND','=','TRUE');
           //echo $sSQL;

		   //cache queries 
		   if ($this->cachequeries) $result = $this->db_con->CacheExecute($this->cachetime,$sSQL);
                               else $result = $this->db_con->Execute($sSQL);

           //print_r($result);							   
		   $this->retable = (array) $this->prepare($result);
		   
	       //print_r($this->retable);
	   
           if (seclevel('_TIMERS',$this->userLevelID)) {	  
	 	    $this->t_dbtable->stop('products');
   	        //echo "products " . $this->t_dbtable->value('products');
	       }
	   	   
	       return ($this->retable);
	}

    function search($text,$group,$type) {
	   
	   $this->get_MetaTable();	   
	   
       $terms = explode (" ", $text, 5); //5 = terms limit
	   //print_r($terms);print $type;
	   
	   switch ($type) {
	       case $this->anyterms : // OR
                                  reset($terms);
                                  //while (list($word_no, $word) = each($terms)) {
						          foreach ($terms as $word_no => $word) {	
								    $mytext = '%'.$word.'%'; 
                                    /*$search_SQL .= "( Κωδικός LIKE " . $this->db_con->qstr($mytext) .
		                                           " or Περιγραφή LIKE " . $this->db_con->qstr($mytext) .
		                                           " or Παρατήρηση LIKE " . $this->db_con->qstr($mytext) .
												   " or [Ειδ_κωδικός] LIKE " . $this->db_con->qstr($mytext) . ")";
									 */  
									$search_SQL .= "(" . $this->choiceSQL($this->searchcriteria,'OR','LIKE',$this->db_con->qstr($mytext)) . ")";			   
												   
									if ($terms[$word_no+1]) $search_SQL .= " OR ";
	                              }								  
	                              break;
           case $this->allterms : // AND
                                  reset($terms);							  
                                  //while (list($word_no, $word) = each($terms)) { 
						          foreach ($terms as $word_no => $word) {	
								    $mytext = '%'.$word.'%';								  
                                    /*$search_SQL .= "( Κωδικός LIKE " . $this->db_con->qstr($mytext) .
		                                           " or Περιγραφή LIKE " . $this->db_con->qstr($mytext) .
		                                           " or Παρατήρηση LIKE " . $this->db_con->qstr($mytext) .
												   " or [Ειδ_κωδικός] LIKE " . $this->db_con->qstr($mytext) . ")";												   
									*/
									$search_SQL .= "(" . $this->choiceSQL($this->searchcriteria,'OR','LIKE',$this->db_con->qstr($mytext)) . ")";			   
																						   
									if ($terms[$word_no+1]) $search_SQL .= " AND ";
	                              }							   		
	                              break;
		   default              :						  
           //case $this->asphrase : // AS IS = default
	                              $mytext = '%'.$text.'%';
                                  /*$search_SQL = "Κωδικός LIKE " . $this->db_con->qstr($mytext) .
		                                        " or Περιγραφή LIKE " . $this->db_con->qstr($mytext) .
		                                        " or Παρατήρηση LIKE " . $this->db_con->qstr($mytext) .
												" or [Ειδ_κωδικός] LIKE " . $this->db_con->qstr($mytext);		   
								  */
								  $search_SQL = $this->choiceSQL($this->searchcriteria,'OR','LIKE',$this->db_con->qstr($mytext));			   
																		   
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
			   
	   //$sSQL .= " AND [Ειδών.Internet]=TRUE";			   //<<<<<<<<<<<<< 
       $sSQL .=  " AND " .$this->choiceSQL($this->criteriaAND,'AND','=','TRUE');	   
	   		      
			   
	   if (($group) && ($group!=localize('_ALL',getlocal()))) {
			$sSQL .= " and (Οικογένεια=" . $this->db_con->qstr($this->rname) . " or Υποοικογένεια=" . $this->db_con->qstr($this->rname) .
                                         " or Ομάδα=" . $this->db_con->qstr($this->rname) . " or Κατηγορία=" . $this->db_con->qstr($this->rname) .")";
	   }			   
 
	   //echo $sSQL;

       if ($sSQL) $result = $this->db_con->Execute($sSQL);
	   	   
	   //return ($this->prepare_search($result));
	   return ($this->prepare($result));	   
	}

    //used from search function to define group of finded product
    function prepare_group($category,$omada,$ypofamily,$family) {

		if ($category) $out = $category;
		  elseif ($omada) $out = $omada;
		    elseif ($ypofamily) $out = $ypofamily;
		      else $out = $family;

        $mycat = new _directory;
		$res = $mycat->getaliasinfo($out . $this->directory_mark,1);
		unset ($mycat);

        return ($res[2]);
	}


    function prepare($table) {
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
			           $this->prepare_group($table->fields[13],
			                                $table->fields[12],
			                                $table->fields[11],
			                                $table->fields[10]) . ";" .
			           $table->fields[5] . ";" .
			           $this->preparestr($table->fields[6]) . ";" .
			           //$this->resourcepicpath . $table->fields[7] . $this->restype . ";" .
					   (file_exists($this->abspicpath . $table->fields[7] . $this->restype) ? $this->resourcepicpath . $table->fields[7] . $this->restype : $this->resourcepicpath . $this->nopic . $this->restype) . ";" .
			           $table->fields[8] . ";" .
			           $table->fields[9] . ";" .
					   $this->lookup($this->T_boxtype,$table->fields[14],'Κωδικός',1) . ";" .
					   $table->fields[15];

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
	
   //update enallaktiko kodiko sthn access 
   //query = current product list viewed by browser
   function update_productcode($param1,$param2) {
	 $g = GetReq('g');	 

     $this->get_MetaTable();	 //get table info
	 
     $i=0;
	 $res = array();
	 
     if ( (defined('DIRECTORY_DPC')) && (seclevel('DIRECTORY_DPC',decode(GetSessionParam('UserSecID')))) ) {
	     $mydir = new _directory($g); 
		 $currentdir = $mydir->getaliasinfo($g,2);

	     $mt = new products($currentdir,$mydir->depth);
	     $res = $mt->read(); //print_r($res);
	     unset($mt);
		 unset ($mydir);

		 $i=0;  
         //while (list ($prod_code, $prod_data) = each ($res)) {
         foreach ($res as $prod_code => $prod_data) {	
		 
             set_time_limit(5);
											 		 
			 $enall_code = (($param1*1000) + ($param2*100) + $i);

             $sSQL = "UPDATE ". $this->T_products . " SET [Εναλλ_κωδικός]=" . $this->db_con->qstr($enall_code) .
		             " WHERE [Κωδικός]=" . $this->db_con->qstr($prod_code);

	         $this->db_con->Execute($sSQL);

			 if ($this->db_con->Affected_Rows()) $i+=1;
			 //print $sSQL; 
			 //echo $i;
		 }
		 //print $sSQL; 		 
		 //set default time limit
	     set_time_limit($this->timeout);		 

         setInfo($i . " Records affected");
	 }
   }	
   
   
	function browse($packdata,$view='') {
	   $p = GetReq('p');	   
	  
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
								   $out = $this->view6($data[0],$data[1],$data[2],$data[3],$data[4],$p,$data[6],$data[7],$data[8],$data[9],$data[10]);
                                   break;

                          case 7 : $out = $this->view7($data[0],$data[1],$data[2],$data[3],$data[4],$p,$data[6],$data[7],$data[8],$data[9]);
                                   break; 

						  case 8 : //barcode view
								   $out = $this->view8($data[0],$data[1],$data[2],$data[3],$data[4],$p,$data[6],$data[7],$data[8],$data[9],$data[10],$data[11]);
                                   break;
					
                          case 9 : $out = $this->view9($data[0],$data[1],$data[2],$data[3],$data[4],$p,$data[6],$data[7],$data[8],$data[9],$data[10]);
						           break; 		   
								   
                      case 'pdf' : $out = $this->pdfview($data[0],$data[1],$data[2],$data[3],$data[4],$p,$data[6],$data[7],$data[8],$data[9],$data[10]);
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
				 "<H4>" .  str_replace(".",",",$price) . $this->moneysymbol . "</H4>";
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
	                GetGlobal('controller')->calldpc_method("cart.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype+$group+$page",1) .	   
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
	   //if ($price>0) {		  			  
	      //$data[] = dpc_extensions("$id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;",$group,$page);
	      $data[] = GetGlobal('controller')->calldpc_method("metacache.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;+$group+$page",1) .
	                GetGlobal('controller')->calldpc_method("cart.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;+$group+$page",1) .	   
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
	
	   $link = seturl("t=$this->view&a=$ar&g=$gr&p=$p" ,$title);
	   
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
	   //if ($price>0) {			  		  
	      //$data[] = dpc_extensions("$id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;",$group,$page);
	      $data[] = GetGlobal('controller')->calldpc_method("metacache.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;+$group+$page",1) .
	                GetGlobal('controller')->calldpc_method("cart.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;+$group+$page",1) .	   
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

    function view6($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1,$boxtype='') {
	   $a = GetReq('a');
	   $p = GetReq('p');
	   $page = $p; //var page allways is 1	   

	   $gr = urlencode($group);
	   $ar = urlencode($title);	
	   	   
       if ($a == $title) {

	   $link = seturl("t=$this->view&a=$ar&g=$gr&p=$p" , $title);
	   
       if (seclevel('ADMPRODS_',$this->userLevelID)) $chkbox = $this->admin_checkbox($id);

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
	   
	   $data[] = "<B>$title<br></B>" . $descr . "<B>";
	   $attr[] = "left;50%";
	   
	   
	   $data[] = $id . "<br>" . $boxtype .
		         //localize('_PRICE',getlocal()) . " :" .
				 "<H4>" .  str_replace(".",",",$price) . $this->moneysymbol . "</H4>";
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
	                GetGlobal('controller')->calldpc_method("cart.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype+$group+$page",1) .	   
	                GetGlobal('controller')->calldpc_method("neworder.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype+$group+$page",1);			  
	      $attr[] = "center;20%";			  		
	   }		  
       else {
	      $data[] = $this->notavail;//localize('_NOTAVAL',getlocal()); 
	      $attr[] = "center;20%";		  
	   }	   
	   
	   $myarticle = new window('',$data,$attr);
	   $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::") . "<hr>";
	   unset ($data);
	   unset ($attr);

	   return ($out);
	   
       }
	   else {	

	   $link = seturl("t=$this->view&a=$ar&g=$gr&p=$p" , $title);
	   
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
				 "<H4>" .  str_replace(".",",",$price) . $this->moneysymbol . "</H4>";
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
	                GetGlobal('controller')->calldpc_method("cart.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype+$group+$page",1) .	   
	                GetGlobal('controller')->calldpc_method("neworder.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype+$group+$page",1);				  
	      $attr[] = "center;20%";			  		 		
	   }		  
       else {
	      $data[] = $this->notavail;//localize('_NOTAVAL',getlocal()); 
	      $attr[] = "center;20%";		  
	   }	   
	   
	   $myarticle = new window('',$data,$attr);
	   $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::") . "<hr>";
	   unset ($data);
	   unset ($attr);

	   return ($out);
    }	
	}
	
    ///////////////////////////////////////////////////////////
    // view 7 
    ///////////////////////////////////////////////////////////
    function view7($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1) {

	   $page = GetReq('p'); //var page allways is 1
	   	   	
	   $gr = urlencode($group);
	   $ar = urlencode($title);
	     

	   $data[] = $id; 
	   $attr[] = "left;20%";							  
	   $data[] = $title;
	   $attr[] = "left;55%";
	   
	   $data[] = $price . $this->moneysymbol;   
	   $attr[] = "right;15%";
	   
	   //get this function args to select the comparision
	   $fargs = func_get_args();
	   if ((!$this->checkprd) || ($fargs[$this->checkprd]>0)) {
	   //if ($price>0) {		  	
	      //$data[] = dpc_extensions("$id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;",$group,$page);
	      $data[] = GetGlobal('controller')->calldpc_method("metacache.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;+$group+$page",1) .
	                GetGlobal('controller')->calldpc_method("cart.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;+$group+$page",1) .	   
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
	
	
    function view8($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1,$boxtype='',$barcode=null) {	   	
	   $a = GetReq('a');
	   $p = GetReq('p');	
	   $page = $p; //var page allways is 1	      

	   $gr = urlencode($group);
	   $ar = urlencode($title);	
	   	   
       if ($a == $title) {

	   $link = seturl("t=$this->view&a=$ar&g=$gr&p=$p" , $title);
	   
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
		         "<br>" . localize('_BOXTYPE',getlocal()) . " :" . $boxtype;	 	  
	   $attr[] = "center;20%";

       $bc = new BarcodeI25();		  
       $bc->SetCode($barcode);
		  	   
	   $data[] = $bc->Generate(); 		  
	   $attr[] = "center;20%";	   
	   
	   $myarticle = new window('',$data,$attr);
	   $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::") . "<hr>";
	   unset ($data);
	   unset ($attr);

	   return ($out);
	   
       }
	   else {	

	   $link = seturl("t=$this->view&a=$ar&g=$gr&p=$p" , $title);
	   
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
		      	 "<br>" . localize('_BOXTYPE',getlocal()) . " :" . $boxtype;	  
	   $attr[] = "center;20%";
	   		  			  
       $bc = new BarcodeI25();		  
       $bc->SetCode($barcode);//"4901681133581");
		  	   
	   $data[] = $bc->Generate(); 		  
	   $attr[] = "center;20%";			  		 		   
	   
	   $myarticle = new window('',$data,$attr);
	   $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::") . "<hr>";
	   unset ($data);
	   unset ($attr);

	   return ($out);
    }	
	}	
	
	
    function view9($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1,$boxtype='') {	   	

	   $a = GetReq('a');
	   $p = GetReq('p');	   
	   $page = $p; //var page allways is 1	   

	   $gr = urlencode($group);
	   $ar = urlencode($title);	
	   	   
       if ($a == $title) {

	   $link = seturl("t=$this->view&a=$ar&g=$gr&p=$p" , $title);
	   
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
		         localize('_PRICE',getlocal()) . " :<B>" . $price . $this->moneysymbol . "</B>" .
		         "<br>" . localize('_BOXTYPE',getlocal()) . " :" . $boxtype;	 	  
	   $attr[] = "center;20%";

	   //get this function args to select the comparision
	   $fargs = func_get_args();
	   if ((!$this->checkprd) || ($fargs[$this->checkprd]>0)) {
	   //if ($price>0) {			  	
	      //$data[] = dpc_extensions("$id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype",$group,$page);
	      $data[] = GetGlobal('controller')->calldpc_method("metacache.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype+$group+$page",1) .
	                GetGlobal('controller')->calldpc_method("cart.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype+$group+$page",1) .	   
	                GetGlobal('controller')->calldpc_method("neworder.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype+$group+$page",1);			  
	      $attr[] = "center;20%";			  		
	   }		  
       else {
	      $data[] = $this->notavail;//localize('_NOTAVAL',getlocal()); 
	      $attr[] = "center;20%";		  
	   }	   
	   
	   $myarticle = new window('',$data,$attr);
	   $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::") . "<hr>";
	   unset ($data);
	   unset ($attr);

	   return ($out);
	   
       }
	   else {	

	   $link = seturl("t=$this->view&a=$ar&g=$gr&p=$p" , $title);
	   
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
		         localize('_PRICE',getlocal()) . " :<B>" . $price . $this->moneysymbol . "</B>" .
		      	 "<br>" . localize('_BOXTYPE',getlocal()) . " :" . $boxtype;	  
	   $attr[] = "center;20%";
	   
	   //get this function args to select the comparision
	   $fargs = func_get_args();
	   if ((!$this->checkprd) || ($fargs[$this->checkprd]>0)) {
	   //if ($price>0) {			  			     
	      //$data[] = dpc_extensions("$id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype",$group,$page); 		  
	      $data[] = GetGlobal('controller')->calldpc_method("metacache.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype+$group+$page",1) .
	                GetGlobal('controller')->calldpc_method("cart.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype+$group+$page",1) .	   
	                GetGlobal('controller')->calldpc_method("neworder.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;$boxtype+$group+$page",1);			  
	      $attr[] = "center;20%";			  		 		
	   }		  
       else {
	      $data[] = $this->notavail;//localize('_NOTAVAL',getlocal()); 
	      $attr[] = "center;20%";		  
	   }	   
	   
	   $myarticle = new window('',$data,$attr);
	   $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::") . "<hr>";
	   unset ($data);
	   unset ($attr);

	   return ($out);
    }	
	}	
	
    function pdfview($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1,$boxtype='') {	   	
	   
	   
	   return "pdf";	
	}	
	
    function admin_checkbox($id) {
	
	   $myid = $id . ';';

       return ("<input type=\"checkbox\" name=\"$id\" value=\"$myid\">");
    }	


	function headtitle() {
	    $a = GetReq('a');
	    $p = GetReq('p');
	    $g = GetReq('g');
	    $t = GetReq('t');	   		
		
	    $gr = urlencode($g);
	    $ar = urlencode($a);		

        switch ($this->view) {   		

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
		             $data[] = seturl("t=$t&a=1&g=$gr&p=$p" , localize('_CODE',getlocal()) );
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
					 unset ($attr);
				     break;
			case 8 : break;					 
		}
		return ($out);					 	
	}			
	
	
};
}
}
else die("BACKOFFICE DPC REQUIRED!");
?>