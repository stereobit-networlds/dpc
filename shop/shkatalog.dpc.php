<?php
$__DPCSEC['SHKATALOG_DPC']='1;1;1;1;1;1;2;2;9';
$__DPCSEC['SHKATALOG_CART']='1;1;1;1;1;1;2;2;9';

if ( (!defined("SHKATALOG_DPC")) && (seclevel('SHKATALOG_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHKATALOG_DPC",true);

$__DPC['SHKATALOG_DPC'] = 'shkatalog';

$d = GetGlobal('controller')->require_dpc('shop/shkategories.dpc.php');
require_once($d);


$__EVENTS['SHKATALOG_DPC'][0]='katalog';
$__EVENTS['SHKATALOG_DPC'][1]='klist';
$__EVENTS['SHKATALOG_DPC'][2]='kshow';
$__EVENTS['SHKATALOG_DPC'][3]='knext';
$__EVENTS['SHKATALOG_DPC'][4]='kprev';
$__EVENTS['SHKATALOG_DPC'][5]='kprint';
$__EVENTS['SHKATALOG_DPC'][6]='addtocart';     //continue with ..cart
$__EVENTS['SHKATALOG_DPC'][7]='removefromcart';//continue with ..cart
$__EVENTS['SHKATALOG_DPC'][8]='searchtopic';   //continue with ..browser
$__EVENTS['SHKATALOG_DPC'][9]='lastentries';
//$__EVENTS['SHKATALOG_DPC'][10]= "fastpick";

$__ACTIONS['SHKATALOG_DPC'][0]='katalog';
$__ACTIONS['SHKATALOG_DPC'][1]='klist';
$__ACTIONS['SHKATALOG_DPC'][2]='kshow';
$__ACTIONS['SHKATALOG_DPC'][3]='knext';
$__ACTIONS['SHKATALOG_DPC'][4]='kprev';
$__ACTIONS['SHKATALOG_DPC'][5]='kprint';
$__ACTIONS['SHKATALOG_DPC'][6]='addtocart';     //continue with ..from cart
$__ACTIONS['SHKATALOG_DPC'][7]='removefromcart';//continue with ..from cart
$__ACTIONS['SHKATALOG_DPC'][8]='searchtopic';   //continue with ..from browser
$__ACTIONS['SHKATALOG_DPC'][9]='lastentries';
//$__ACTIONS['SHKATALOG_DPC'][10]= "fastpick";
//$__ACTIONS['SHKATALOG_DPC'][10]='index';//dummy for index.php purposes

$__LOCALE['SHKATALOG_DPC'][0]='SHKATALOG_DPC;Catalogue;Καταλογος';
$__LOCALE['SHKATALOG_DPC'][1]='_code;Code;Κωδικός';
$__LOCALE['SHKATALOG_DPC'][2]='_descr;Description;Περιγραφή';
$__LOCALE['SHKATALOG_DPC'][3]='_axia;Cost;Τιμή';
$__LOCALE['SHKATALOG_DPC'][4]='_uniname1;MM;ΜΜ';
$__LOCALE['SHKATALOG_DPC'][5]='_order;Order by:;Ταξινόμηση:';
$__LOCALE['SHKATALOG_DPC'][6]='_item;Item;Προιόν';
$__LOCALE['SHKATALOG_DPC'][7]='_cat1;Detail 1;Χαρακτηριστικό 1';
$__LOCALE['SHKATALOG_DPC'][8]='_next;Next;Επόμενο';
$__LOCALE['SHKATALOG_DPC'][9]='_prev;Previous;Προηγούμενο';
$__LOCALE['SHKATALOG_DPC'][10]='_offers;Offers;Προσφορές';
$__LOCALE['SHKATALOG_DPC'][11]='_lastitems;New arrivals;Νέες αφίξεις';
$__LOCALE['SHKATALOG_DPC'][12]='_gallery;Additional files;Συνημένα αρχεία';
$__LOCALE['SHKATALOG_DPC'][13]='_availabillity;Product Availabillity;Διαθεσιμότητα προιόντων';
$__LOCALE['SHKATALOG_DPC'][13]='_items;Items;Προιόντα';
$__LOCALE['SHKATALOG_DPC'][14]='_asc;Asc;Αυξουσα';
$__LOCALE['SHKATALOG_DPC'][15]='_desc;Desc;Φθινουσα';
$__LOCALE['SHKATALOG_DPC'][16]='_first;First;Πρώτα';
$__LOCALE['SHKATALOG_DPC'][17]='_all;All;Ολα';
$__LOCALE['SHKATALOG_DPC'][18]='_nofound;Items not found;Δεν βρέθηκαν εγγραφές';
$__LOCALE['SHKATALOG_DPC'][19]='_title;Title;Περιγραφή';
$__LOCALE['SHKATALOG_DPC'][20]='_norecs;Record set is empty;Οι εγγραφές δεν υπάρχουν';
$__LOCALE['SHKATALOG_DPC'][21]='_norec;Record not exist;Η εγγραφή δεν υπάρχει';
$__LOCALE['SHKATALOG_DPC'][22]='_lockrec;Title;Η εγγραφή είναι κλειδωμένη';

class shkatalog extends shkategories {

    var $max_items, $result, $path, $urlpath, $direction, $inpath;
	var $map_t, $map_f;
	var $pprice, $codetype;
	var $availability, $availability_colors, $avail_point,$availability_remark;
	
    var $userLevelID;	
	var $is_reseller, $buttons_OFF, $htmlpath;
	var $listcontrols;
	var $usetablelocales;
	var $carthandler;	
	var $details_button;
	
	var $max_selection;
	var $lightbox;
	
	var $itemfimagex,$itemfimagey,$imagex,$imagey, $itemimagex, $itemimagey;	
	var $imageclick, $linemax;
	var $thubpath_large, $thubpath_medium, $thubpath_small;
	var $treebrowser, $treeviewtype;
	var $myorderby, $asc, $deforder, $defasc;
    var $global_hide_asceding;
	var $addfx, $addfy;	
	var $asccombostyle;
	var $decimals;
	var $toggler, $catbanner, $itemlockparam, $itemlockgoto, $isitemlocked;

	function shkatalog() {
	  $GRX = GetGlobal('GRX');		
	  $UserSecID = GetGlobal('UserSecID');	
	
	  shkategories::shkategories();
	  
      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);	  
	
	  $this->title = localize('SHKATALOG_DPC',getlocal());	
	  $this->msg = null;
	  $this->post = null;		  
	  $this->path = paramload('SHELL','prpath');	//echo $this->path;
	  $this->urlpath = paramload('SHELL','urlpath');
	  $this->inpath = paramload('ID','hostinpath');		  
	  $this->result = null;
	  
	  
	  $this->imgpath = $this->inpath . '/images/uphotos/';  	  
	  $this->thubpath = $this->inpath . '/images/thub/';
      $photo_bg = remote_paramload('SHKATALOG','photobgpath',$this->path);		  
      $this->thubpath_large = $photo_bg?$this->inpath . "/images/$photo_bg/":$this->inpath . '/images/thub/';	  	  
      $photo_md = remote_paramload('SHKATALOG','photomdpath',$this->path);		  
      $this->thubpath_medium = $photo_md?$this->inpath . "/images/$photo_md/":$this->inpath . '/images/thub/';	  	  
      $photo_sm = remote_paramload('SHKATALOG','photosmpath',$this->path);		  
      $this->thubpath_small = $photo_sm?$this->inpath . "/images/$photo_sm/":$this->inpath . '/images/thub/';	  	  	  	  
	  
	  $rt = remote_paramload('SHKATALOG','restype',$this->path);
	  $this->restype = $rt?$rt:'.jpg';
	  //fp img xy
	  $this->itemfimagex = remote_paramload('SHKATALOG','itemfimagex',$this->path);	
	  $this->itemfimagey = remote_paramload('SHKATALOG','itemfimagey',$this->path); 
	  //thumb xy
	  $this->imagex = remote_paramload('SHKATALOG','imagex',$this->path);	
	  $this->imagey = remote_paramload('SHKATALOG','imagey',$this->path);	  
	  //item xy
      $this->itemimagex = remote_paramload('SHKATALOG','itemimagex',$this->path);	
	  $this->itemimagey = remote_paramload('SHKATALOG','itemimagey',$this->path);
	  //item additional files..thumbnail xy
      $this->addfx = remote_paramload('SHKATALOG','addimagex',$this->path);	
	  $this->addfy = remote_paramload('SHKATALOG','addimagey',$this->path);	  	  
		// echo $addfx,$addfy;	
      $this->imageclick = remote_paramload('SHKATALOG','imageclick',$this->path);
      $this->linemax = remote_paramload('SHKATALOG','itemsperline',$this->path); 	
      //echo '>',$this->linemax;   	  
	  
	  $this->htmlpath = $this->urlpath . $this->inpath . '/cp/html/'; 	  
	  
	  $this->pager = GetReq('pager')?GetReq('pager'): (GetSessionParam('pager')?GetSessionParam('pager') : remote_paramload('SHKATALOG','pager',$this->path));
	  $this->zeroprice_msg = remote_paramload('SHKATALOG','zeroprice',$this->path);	 
	  
	  $this->map_t = remote_arrayload('SHKATALOG','maptitle',$this->path);	
	  $this->map_f = remote_arrayload('SHKATALOG','mapfields',$this->path);	
	  
	  $this->pprice = remote_arrayload('SHKATALOG','pricepolicy',$this->path);
	  if (empty($this->pprice)){//default
	    $this->pprice[0]='price0';
		$this->pprice[1]='price1';
	  }
	  
	  $this->codetype = remote_paramload('SHKATALOG','codetype',$this->path);	  	
	  $this->availability = remote_arrayload('SHKATALOG','qtyavail',$this->path);		  
	  $this->availability_colors = remote_arrayload('SHKATALOG','qtyavailcolors',$this->path);		
	  $this->availability_remark = remote_arrayload('SHKATALOG','qtyavailremark',$this->path);		
	  
	  $this->buttons_OFF = remote_arrayload('SHKATALOG','buttonsOFF',$this->path);	  	  
	  
      if ($GRX)    
         $this->avail_point  = loadTheme('apoint','');
	  else
	  	 $this->avail_point  = '[+]';	 
		 
	  $this->is_reseller = GetSessionParam('RESELLER'); 
	  
	  $this->listcontrols = remote_arrayload('SHKATALOG','listcontrols',$this->path);	
	  $this->usetablelocales = remote_paramload('SHKATALOG','locales',$this->path);		     		    
	  $this->carthandler = remote_paramload('SHKATALOG','carthandler',$this->path);		  
	  //no pre languange attachment 0,1 at end of file...
	  $this->one_attachment = remote_paramload('SHKATALOG','oneattach',$this->path);	
	  
      $this->details_button= loadTheme('details_b',"");	  	  	  	   	  
	  
	  $this->max_selection = null;
	  $this->lightbox = remote_paramload('SHKATALOG','lightbox',$this->path);		  
	  $this->treebrowser = remote_paramload('SHKATALOG','treebrowser',$this->path);			
	  $this->treeviewtype = remote_paramload('SHKATALOG','treeviewtype',$this->path);	
	  
	  $this->deforder = remote_paramload('SHKATALOG','deforder',$this->path);	
	  $this->defasc = remote_paramload('SHKATALOG','defasc',$this->path); 
	  
	  $this->global_hide_asceding = remote_paramload('SHKATALOG','hideasc',$this->path);  
	  $this->asccombostyle = remote_paramload('SHKATALOG','asccombostyle',$this->path);
	  	  
	  $cb = remote_arrayload('SHKATALOG','categorybanner',$this->path);	 
	  $this->catbanner = is_array($cb)?(array)$cb:array();
	  
	  $this->itemlockparam = remote_paramload('SHKATALOG','itemlockparam',$this->path);
	  $this->itemlockgoto = remote_paramload('SHKATALOG','itemlockgoto',$this->path);	
	  $this->isitemlocked = false;  
	  	  	  
	  $this->set_order(); //reset ..init 
	  
	  $dec_num = remote_paramload('SHKATALOG','decimals',$this->path);
	  $this->decimals = $dec_num?$dec_num:2;   
	   
	  $toggle = remote_arrayload('SHKATALOG','toggler',$this->path);  
	  //print_r($toggle);
	  $deftoggle = array(0=>'no',1=>'yes');
	  if (!empty($toggle))
	    $this->toggler = $toggle;
	  else
	    $this->toggler = $deftoggle;	
	  //print_r($this->toggler);
	}

	function event($event=null) {
				
	    switch ($event) {

		  case "kprint"      :  $pp = GetSessionParam('printpage');
		                        if ($pp) {
			                        $prn = $this->printitem($pp); 
                                    //SetSessionParam('printpage',null); //not reset for re-print...
									echo $prn; 
									exit;
		                         }
								 
		//cart
	     case 'searchtopic'   :
	     case 'addtocart'     :
		 case 'removefromcart':/*$this->read_list();*/ break;		
		// 								 
		  case 'klist'        : $this->read_list(); //moved in func
		                        $this->lightbox_js();
		                        GetGlobal('controller')->calldpc_method("rcvstats.update_category_statistics use ".GetReq('cat'));		  
		                        break;
		  case "knext"        : $this->direction = GetReq('direction');
		  case "kprev"        :	$this->direction = GetReq('direction');
		  	  
		  case 'kshow'        : $this->read_item($this->direction); 
		                        $this->lightbox_js();
		  		                //update statistics
	                            GetGlobal('controller')->calldpc_method("rcvstats.update_item_statistics use ".GetReq('id'));		  
	   	  case 'lastentries'  : break;
		                        break;		  
          case 'katalog'      :
		  default             :	$this->lightbox_js();
		                        //update statistics
	                            GetGlobal('controller')->calldpc_method("rcvstats.update_item_statistics use ".GetReq('id'));								  
        }	
	}
	
	function action($action=null) {
        $db = GetGlobal('db');	
		$order = GetReq('order')?GetReq('order'):GetSessionParam('order');
		$asc = GetReq('asc')?GetReq('asc'):GetSessionParam('asc');
		$page = GetReq('page')?GetReq('page'):0;	
	    $lan = getlocal();
	    $mylan = $lan?$lan:'0';
	    $itmname = $mylan?'itmname':'itmfname';
	    $itmdescr = $mylan?'itmdescr':'itmfdescr';			
		
		$myctitle = GetReq('ctitle')?GetReq('ctitle'):localize('_items',getlocal());
	
		if (($c=GetReq('cat')) && ($c{0}!='-'))
		  $out = $this->tree_navigation('klist','',1);  
		else
		  $out .= setNavigator($this->title,$myctitle);	
		    		
	  
	    switch ($action) {
		
		  case 'klist'        : //$out .= $this->list_katalog_table(2,null,null,0,1); break;
		                     	//$out .= $this->show_menu('klist');
								$out .= $this->tree_browser();
								if (in_array('beforeitemslist',$this->catbanner))//before
								  $out .= $this->show_category_banner();	
								  								
		                        $out .= $this->list_katalog(0);		
								
								if (in_array('afteritemslist',$this->catbanner))//after
								  $out .= $this->show_category_banner();														 
								break;
		  case "knext"        :
		  case "kprev"        :		  
		  case 'kshow'        : 
		  						if (in_array('beforeitem',$this->catbanner))
		                          $out .= $this->show_category_banner();	
								  
		                        $out .= $this->show_item();
								
								if (in_array('afteritem',$this->catbanner))
		                          $out .= $this->show_category_banner();	
		                        break;		  
				
		 
          case 'lastentries'  : $out .= $this->show_lastentries(20,12,2,160,120);		 
	    	                    break; 
		//cart
	     case 'searchtopic'   :
	     case 'addtocart'     :
		 case 'removefromcart':	if (($this->carthandler) || (GetSessionParam('fastpick')=='on'))
		                          $out .= $this->list_katalog(0);
								else
								  $out = GetGlobal('controller')->calldpc_method("shcart.cartview");   
		                        break;	
          case 'katalog'      : 
		  default             :	//$out .= $this->show_lastentries(20,12,2,160,120);							  
		                        //--$out .= $this->show_menu('klist');
		                        //$out .= $this->list_katalog();
								//--$out .= $this->show_offers(20,12,2,160,120);
								//if (!GetReq('t'))
								
	                            //$out .= $this->list_katalog(0); 								
								//break;  
								/*else {//....handled by page
								  $out .= $this->show_offers(20,12,2,160,120);
                                  $out .= $this->show_lastentries(20,12,2,160,120);							  								  								
								} */ 
								
	                            $sSQL = "select id,sysins,code1,pricepc,price2,sysins,itmname,itmfname,uniname1,uniname2,active,code4," .// from abcproducts";// .
	                            "price0,price1,cat0,cat1,cat2,cat3,cat4,itmdescr,itmfdescr,itmremark,ypoloipo1,".$this->getmapf('code')." from products ";
		                        $sSQL .= " WHERE itmactive>0 and active>0";			 
		                        $sSQL .= ' ORDER BY';
		  
		                        switch ($order) {
		                          case 1  : $sSQL .= ' '.$itmname; break;
			                      case 2  : $sSQL .= ' price0';break;  
		                          case 3  : $sSQL .= ' '.$this->getmapf('code'); break;//must be converted to the text equal????
			                      case 4  : $sSQL .= ' cat0';break;			
			                      case 5  : $sSQL .= ' cat1';break;
			                      case 6  : $sSQL .= ' cat2';break;			
			                      case 9  : $sSQL .= ' cat3';break;						
		                          default : $sSQL .= ' ' . $this->get_order();
		                        }
		  
		                        switch ($asc) {
		                          case 1  : $sSQL .= ' ASC'; break;
			                      case 2  : $sSQL .= ' DESC';break;
		                          default : $sSQL .= $this->get_asc();//' ASC';
		                        }
		  
		                        /*if ($this->pager) {
		                          $p = $page * $this->pager;
		                          $sSQL .= " LIMIT $p,".$this->pager; //page element count
		                        }*/
				                $sSQL .= " LIMIT 100";
								 
	                            $this->result = $db->Execute($sSQL,2);
								$this->max_items = $db->Affected_Rows();
	                            $this->max_selection = $this->get_max_result();								
                                //echo $sSQL;
								//print_r($resultset);

								$out .= $this->show_category_banner();  								
		                        $out .= $this->list_katalog(0);//null,'katalog',null,null,1);             
        }	  
	  
	    return ($out);
	}	
	
	//handle cat/sub cat navigation
	function tree_browser() {
	  $cat = GetReq('cat');
	  $tbrowse = $this->treebrowser;
	  $tvtype = $this->treeviewtype;	  
	
	  switch ($tbrowse) {
	    case 9  : break;//none to view 
		
	    case 5  : $out .= $this->show_submenu('klist',$tvtype); break;  //submenu only	  
	    case 4  : $out .= $this->show_navigator('klist'); break; //nav only	  
	    case 3  : $out .= $this->show_menu('klist',$tvtype,0,$cat,$cat,1); break;
		case 2  : $out .= $this->show_menu('klist',$tvtype,0,$cat,$cat,1); break;
		case 1  : $out .= $this->show_menu('klist',$tvtype,0,$cat); break;
		case 0  :
	    default : $out .= $this->show_menu('klist'); //tree nav + subcats
	  }
	  
	  return ($out);   
	}
	
	function lightbox_js() {
	
        if ((iniload('JAVASCRIPT')) && ($this->lightbox)) {	
		
         /* $mycss = $css?$css:'lightbox.css';
  
          $ret = "
<style type=\"text/css\"
@import url($mycss);
</style>";		*/
	   
	      //$code = $this->javascript();
		  $js = new jscript;
		  
		  $js->load_js("lightbox/prototype.js");	      		      
		  $js->load_js("lightbox/scriptaculous.js?load=effects,builder");
		  $js->load_js("lightbox/lightbox.js");		  		  
          $js->load_js($code,null,1);		   			   
		  unset ($js);
	    }		
	}	
	
	function read_list() {
        $db = GetGlobal('db');	
		$order = GetReq('order')?GetReq('order'):GetSessionParam('order');
		$asc = GetReq('asc')?GetReq('asc'):GetSessionParam('asc');
		$page = GetReq('page')?GetReq('page'):0;
		$negative = false;
	    $lan = getlocal();
	    $mylan = $lan?$lan:'0';
	    $itmname = $mylan?'itmname':'itmfname';
	    $itmdescr = $mylan?'itmdescr':'itmfdescr';			
	   
	    if ($this->usetablelocales)
	      $f = $mylan; 
	    else
	      $f = null;		
		

		$cat = GetReq('cat');	
		if ($cat{0}=='-') {
		    $negative = true;
			$cat = substr($cat,1);//drop -
		}	 
		
		//echo $negative,'>';
		
		$oper = $negative?' not like ':'='; 			
		
		if ($cat!=null) {		   
		  
		  $cat_tree = explode('^',str_replace('_',' ',$cat)); 
			
           //$whereClause .= '( cat0=' . $db->qstr(str_replace('_',' ',$cat_tree[0]));	

		   
		   /*if ($this->usetablelocales) {
		   
	         $sSQL = "select id,sysins,code1,pricepc,price2,sysins,itmname,itmfname,uniname1,uniname2,active,code4," .// from abcproducts";// .
	              "price0,price1,cat0,cat1,cat2,cat3,cat4,itmdescr,itmfdescr,itmremark,ypoloipo1,".$this->getmapf('code')." from products ";
		     $sSQL .= " WHERE ";		   
		   
		     if ($cat_tree[0])
			    $whereClause .= " (cat0".$oper . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[0]))) . " or cat{$f}0".$oper . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[0]))) . ")";		  
		     if ($cat_tree[1])	
		 	    $whereClause .= " and" . " (cat1".$oper . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[1]))) . " or cat{$f}1".$oper . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[1]))) . ")";		 
		     if ($cat_tree[2])	
			    $whereClause .= " and" . " (cat2".$oper . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[2]))). " or cat{$f}2".$oper . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[2]))) . ")";		   
		     if ($cat_tree[3])	
			    $whereClause .= " and" . " (cat3".$oper . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[3]))). " or cat{$f}3".$oper . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[3]))).")";
		   		
		    
		     $sSQL .= $whereClause;				 
		     $sSQL .= " and itmactive>0 and active>0";			   
		     $sSQL .= ' ORDER BY';
		  
		     switch ($order) {
		      case 1  : $sSQL .= ' itmname'; break;
			  case 2  : $sSQL .= ' price0';break;  
		      case 3  : $sSQL .= ' '.$this->getmapf('code'); break;//must be converted to the text equal????
			  case 4  : $sSQL .= " cat{$f}0";break;			
			  case 5  : $sSQL .= " cat{$f}1";break;
			  case 6  : $sSQL .= " cat{$f}2";break;			
			  case 9  : $sSQL .= " cat{$f}3";break;						
		      default : $sSQL .= ' itmname';//.$this->getmapf('code');
		    }
			echo $sSQL;		   	
		   }
		   else {	*/
		   
	         $sSQL = "select id,sysins,code1,pricepc,price2,sysins,itmname,itmfname,uniname1,uniname2,active,code4," .// from abcproducts";// .
	              "price0,price1,cat0,cat1,cat2,cat3,cat4,itmdescr,itmfdescr,itmremark,ypoloipo1,".$this->getmapf('code')." from products ";
		     $sSQL .= " WHERE ";		   
		      	  
		     if ($cat_tree[0])
			    $whereClause .= ' cat0'.$oper . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[0])));		  
		     if ($cat_tree[1])	
		 	    $whereClause .= 'and cat1'.$oper . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[1])));		 
		     if ($cat_tree[2])	
			    $whereClause .= 'and cat2'.$oper . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[2])));		   
		     if ($cat_tree[3])	
			    $whereClause .= 'and cat3'.$oper . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[3])));
		   		
		    
		     $sSQL .= $whereClause;				 
		     $sSQL .= " and itmactive>0 and active>0";			   
		     $sSQL .= ' ORDER BY';
		  
		     switch ($order) {
		      case 1  : $sSQL .= ' '.$itmname; break;
			  case 2  : $sSQL .= ' price0';break;  
		      case 3  : $sSQL .= ' '.$this->getmapf('code'); break;//must be converted to the text equal????
			  case 4  : $sSQL .= ' cat0';break;			
			  case 5  : $sSQL .= ' cat1';break;
			  case 6  : $sSQL .= ' cat2';break;			
			  case 9  : $sSQL .= ' cat3';break;						
		      default : $sSQL .= ' ' . $this->get_order();
		    }
		  //}
		  
		  switch ($asc) {
		    case 1  : $sSQL .= ' ASC'; break;
			case 2  : $sSQL .= ' DESC';break;
		    default : $sSQL .= $this->get_asc();//' ASC';
		  }
		  
		  if ($this->pager) {
		    $p = $page * $this->pager;
		    $sSQL .= " LIMIT $p,".$this->pager; //page element count
		  }
			
	      //echo $sSQL;
	   
	      $resultset = $db->Execute($sSQL,2);
	      //$ret = $db->fetch_array_all($resultset);	 
		  //if ($this->usetablelocales) 
	   	    //print_r($resultset);  
	      $this->result = $resultset; 
 	      $this->max_items = $db->Affected_Rows();//count($this->result);
	      
	      if ($this->max_items==1) {
		    //echo $this->result->fields[$this->getmapf('code')];
			return ($this->result->fields[$this->getmapf('code')]); //to view the item without click on dir
		  }
		  else { 
		    //echo '>',$this->max_items;
	        $this->max_selection = $this->get_max_result();			
			return (null);
		  }	
		}
		
	}
	
	function show_asceding($cmd=null,$mytemplate=null,$style=null,$notview=null) {
	
	   if ($this->global_hide_asceding) return; 
	   if ($notview) return;
	
	   $cat = rawurlencode(GetReq('cat'));//encoded????
	   $t = GetReq('t');   
	   $cmd=$cmd?$cmd:'klist';
	   //print_r($_SESSION);
	   $asc = GetReq('asc') ? SetSessionParam('asc',GetReq('asc')) : GetSessionParam('asc');
	   $order = GetReq('order') ? SetSessionParam('order',GetReq('order') ) : GetSessionParam('order');	
	   $pager = GetReq('pager') ? SetSessionParam('pager',GetReq('pager')) : GetSessionParam('pager');		   	   
	   
	   //code/item/axia
	   $a = localize('_title',getlocal());
	   $b = localize('_axia',getlocal());
	   $c = localize('_code',getlocal());	   
	   $data = array(1=>$a,2=>$b,3=>$c);
	   if ($this->deforder)
	     $do = 3;
	   else
	     $do = 1;	 
	   $selected_order = GetReq('order')?GetReq('order'):(GetSessionParam('order') ? GetSessionParam('order') : $do);
	   $combo_char = $this->make_combo(seturl('t='.$cmd.'&cat='.$cat.'&order=#'),$data,null,$selected_order,$style);
	   	      	   		   
	   //asc/desc
	   $a = localize('_asc',getlocal());
	   $b = localize('_desc',getlocal());
	   $data = array(1=>$a,2=>$b);
	   if ($this->defasc<0)
	     $da = 2;
	   else
	     $da = 1;	 
	   $selected_asc = GetReq('asc')?GetReq('asc') : (GetSessionParam('asc') ? GetSessionParam('asc') : $do);   
	   $combo_asceding = $this->make_combo(seturl('t='.$cmd.'&cat='.$cat.'&asc=#'),$data,null,$selected_asc,$style);
	   
	   //pager
	   $max = $this->max_selection;
	   $data = array(10=>localize('_first',getlocal()).' 10',20=>localize('_first',getlocal()).' 20',
	                 30=>localize('_first',getlocal()).' 30',50=>localize('_first',getlocal()).' 50',
	                 100=>localize('_first',getlocal()).' 100',$max=>localize('_all',getlocal()));
	   $combo_pager = $this->make_combo(seturl('t='.$cmd.'&cat='.$cat.'&pager=#'),$data,null,$this->pager,$style);
	   	  		   
	   //template	   
	   $template_file='fpsort.htm';	   
	   $tfile = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$template_file) ; 	
       
       //in thios case mytemplate disbled
       if (is_readable($tfile)) {
		 $contents = file_get_contents($tfile);	 	   
	     $template = 1;	 		 
	   }	   
		   
	   
	   if ($template) {	 
	   		 	      
	      $out = $this->combine_template($contents,localize('_order',getlocal()),$combo_char,$combo_asceding,$combo_pager);	    
	   }
	   else {	   
	   
	     $viewdata[] = localize('_order',getlocal()) . $combo_char . $combo_asceding . $combo_pager;//$ascending;
	     $viewattr[] = "left;100%";//"right;20%";	   
	   
	     $mywin = new window('',$viewdata,$viewattr);	   
		 
	     if ($mytemplate)
	   	   $out = $mywin->render("center::100%::0::::left::0::0::");   
	     else	 
	       $out = $mywin->render("center::100%::0::group_article_selected::left::5::5::");
	     unset ($viewdata);
	     unset ($viewattr);	
	   }	 
	   
	   return ($out);	      
	}
	
	function make_combo($url2go,$values,$title=null,$selection=null,$style=null) {
	    $mystyle = $style?$style:$this->asccombostyle;
	
		$r = "<select name=\"".$name."\" class=\"".$mystyle."\"".( $size != 0 ? "size=\"".$size."\"" : "").
		      //" onChange=\"sndReqArg('index.php?t=$t&s1=CANON','combo2')\">";
			  " onChange=\"location=this.options[this.selectedIndex].value\">";
			  
		if (!empty($values) && ($title)) 	  
		  $r .= "<option value=''>---$name---</option>";	
		  
		foreach ($values as $i=>$v) {
		    $myvalue = str_replace('#',$i,$url2go); 
			$r .= "<option value=\"$myvalue\"".($i == $selection ? " selected" : "").">$v</option>";		
		}  
		
		$r .= "</select>";
				
		return ($r);  
	}
	
	function get_max_result($text2find=null) {
        $db = GetGlobal('db');
		$cat = GetReq('cat');	
		if ($cat{0}=='-') {
		    $negative = true;
			$cat = substr($cat,1);//drop -
		}			
		$cat_tree = explode('^',str_replace('_',' ',$cat));		
		$oper = $negative?' not like ':'='; 
		
	    $lan = getlocal();
	    $itmname = $lan?'itmname':'itmfname';
	    $itmdescr = $lan?'itmdescr':'itmfdescr';	
		$stype = GetParam('searchtype'); //echo $stype;
		$scase = GetParam('searchcase'); //echo $scase;					
				
		$sSQL = "select count(id) from products where ";
		
		if ($text2find) {
		
		  if (defined("SHNSEARCH_DPC")) {
              $whereClause = GetGlobal('controller')->calldpc_method('shnsearch.findsql use '.$text2find.'+'.$this->getmapf('code').'<@>'.$itmname.'<@>'.$itmdescr.'<@>itmremark++'.$stype.'+'.$scase);		  
          }
		  else {		
						  
	         $whereClause = " ( $itmname like '%" . strtolower($text2find) . "%' or " .
		               " $itmname like '%" . strtoupper($text2find) . "%')";	
		     $whereClause .= " or ";		   
	         $whereClause .= " ( $itmdescr like '%" . strtolower($text2find) . "%' or " .
		               " $itmdescr like '%" . strtoupper($text2find) . "%')";				 
		     $whereClause .= " or ";		   
	         $whereClause .= " ( itmremark like '%" . strtolower($text2find) . "%' or " .
		               " itmremark like '%" . strtoupper($text2find) . "%')";				 					 
		     $whereClause .= " or ";		   			 
	         $whereClause .= " ( ".$this->getmapf('code')." like '%" . strtolower($text2find) . "%' or " .
		               " ".$this->getmapf('code')." like '%" . strtoupper($text2find) . "%')";								  		
		  }	
		  //search in cat...				  
          if ($cat_tree[0])
			    $whereClause .= ' and cat0'.$oper . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[0])));		  
		  if ($cat_tree[1])	
		 	    $whereClause .= 'and cat1'.$oper . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[1])));		 
		  if ($cat_tree[2])	
			    $whereClause .= 'and cat2'.$oper . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[2])));		   
		  if ($cat_tree[3])	
			    $whereClause .= 'and cat3'.$oper . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[3])));
		   						  
		}
		else {//katalog page
		      	  
		     if ($cat_tree[0])
			    $whereClause .= ' cat0'.$oper . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[0])));		  
		     if ($cat_tree[1])	
		 	    $whereClause .= 'and cat1'.$oper . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[1])));		 
		     if ($cat_tree[2])	
			    $whereClause .= 'and cat2'.$oper . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[2])));		   
		     if ($cat_tree[3])	
			    $whereClause .= 'and cat3'.$oper . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[3])));
		   		
		}
		    
		$sSQL .= $whereClause;				 
		$sSQL .= " and itmactive>0 and active>0";
		//echo $sSQL;	 
	    $resultset = $db->Execute($sSQL,2);
		//print_r($resultset);		
 	    $this->max_cat_items = $resultset->fields[0];//$db->Affected_Rows();			 					
		
		return ($this->max_cat_items);
	}
	
	function show_paging($pagecmd=null,$mytemplate=null,$nopager=null) {
	   //echo 'nopager:',$nopager;
	   if ($nopager) return;
		
	   $cat = rawurlencode(GetReq('cat'));
	   $order = GetReq('order')?GetReq('order'):GetSessionParam('order');
	   $asc = GetReq('asc')?GetReq('asc'):GetSessionParam('asc');	
	   $t = GetReq('t'); 	
	   $page = GetReq('page')?GetReq('page'):0;
	   $pager = GetReq('pager')?GetReq('pager'):$this->pager;//has already get the val ftom session
	   
	   $pcmd = $pagecmd?$pagecmd:'klist';
		  
	   //echo '>',$this->max_items,':',
	   $mp = $this->max_selection;//get_max_result();
	   $max_page = ($mp/$this->pager)-1;	   
	   $cutter = 5;	 
	   
	   if ($mp<=$pager) return;  
	   	   
	   
	   //if ($this->max_items==$this->pager) {	 
	   if ($page<$max_page) {//&& ($mp<$pager)) { 
	       //(($pager==10)||($pager==20)||($pager==30)||($pager==50)||($pager==100))) {//pager has max items val when show all selected	 
	   
	     //next pages
		 $m = 0;
		 for($p=$page+1;$p<$max_page;$p++) {
		   if ($m<$cutter) {
 		     $next_page_no = seturl('t='.$pcmd.'&cat='.$cat./*'&asc='.$asc.'&order='.$order.'&pager='.$pager.*/'&page='.$p,$p+1,null,null,null,$this->rewrite);
		     $next .= "|" . $next_page_no;
		   }
		   $m+=1;
		 }	   
	   	 if ($next) $next .= "|";
	     $page_next = $page + 1;
	     $next .= seturl('t='.$pcmd.'&cat='.$cat./*'&asc='.$asc.'&order='.$order.'&pager='.$pager.*/'&page='.$page_next,localize('_next',getlocal()),null,null,null,$this->rewrite);
	   }
	    
	   if ($page>0) {
	     $page_prev = $page - 1;
	     $prev = seturl('t='.$pcmd.'&cat='.$cat./*'&asc='.$asc.'&order='.$order.'&pager='.$pager.*/'&page='.$page_prev,localize('_prev',getlocal()),null,null,null,$this->rewrite);
		 
         //prev pages
		 $m = $page-$cutter;
		 for($p=0;$p<$page;$p++) {
		   if ($p>=$m) {
 		     $prev_page_no = seturl('t='.$pcmd.'&cat='.$cat./*'&asc='.$asc.'&order='.$order.'&pager='.$pager.*/'&page='.$p,$p+1,null,null,null,$this->rewrite);
		     $prev .= "|" . $prev_page_no;
		   }
		 }  
		 
		 if ($next) $prev .= "|";
	   }	 
	   $cp = $page+1;
	   $page_titles_big = '<h3>'. $prev ."[$cp]". $next .'</h3>';	
	   $page_titles = $prev ."[$cp]". $next;	   
	   
	   //template
	   $template_file='fppager.htm';	   
	   $tfile = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$template_file) ; 	

       //in thios case mytemplate disbled
       if (is_readable($tfile)) {
		 $contents = file_get_contents($tfile);	   
	     $template = 1;	     
	   }	 
	   
	   if ($template) {	 		 	      
	      $ret = $this->combine_template($contents,$page_titles);	    
	   }
	   else {
	     $mywin = new window('',$page_titles_big);
	     if ($mytemplate)
	       $ret = $mywin->render("center::100%::0::::right::0::0::");	   
	     else
	       $ret = $mywin->render("center::100%::0::group_article_selected::right::5::5::");	   
	   } 
	   return ($ret);
	}
	
	function get_item_url($code,$cat=null) {
	
	  $pfile = "index.php?t=kshow&id=".$code.'&cat='.$cat;//seturl('t=kshow&cat='.$cat.'&id='.$code);
      $ret = $pfile;
	  
	  return ($ret);		
	}	
	
	function get_photo_url($code, $photosize=null) {
	
	   //when we have 3 type of scale image
	   switch ($photosize) {
	      case 3  : $tpath = $this->thubpath_large; break;	   
	      case 2  : $tpath = $this->thubpath_medium; break;	   
	      case 1  : $tpath = $this->thubpath_small; break;
	      default : $tpath = $this->thubpath;
	   }
	
	   $pfile = $code;//sprintf("%05s",$code); //echo $pfile,"<br>";
	   $photo_file = $this->urlpath . '/' .$tpath . $pfile . $this->restype;	  
	   //echo $photo_file,'<br>'; 
	   if (!file_exists($photo_file))
	     $photo = $tpath . 'nopic' . $this->restype;	
	   else
	     $photo = $tpath . $pfile . $this->restype;	
		 
	   return ($photo);	 	
	}
	
	function list_photo($code,$x=100,$y=null,$imageclick=1,$mycat=null,$photosize=null,$clickphotosize=null) {
	   $page = GetReq('page')?GetReq('page'):0;		
	   $cat = $mycat?$mycat:GetReq('cat');
	   $xfulldist = $this->itemfimagex?$this->itemfimagex:640;
	   $yfulldist = $this->itemfimagey?$this->itemfimagey:null; //free y 480;
	   
	   $photo = $this->get_photo_url($code,$photosize);//define size
	   
	   //echo $imageclick,'<br>';	   
	   if (($imageclick==null) || ((is_numeric($imageclick)) && ($imageclick>=0))) {
	    
	     if ($imageclick==1) {//phot url	
	   
           $clickphoto = $clickphotosize?$this->get_photo_url($code,$clickphotosize):
		                                 $this->get_photo_url($code,$photosize);
		   
           if (iniload('JAVASCRIPT')) {	
				   
		     if ($this->lightbox) {
                $plink = "<A href=\"$clickphoto\" rel='lightbox' title='$code'>";			 
			 }
			 else {
  	           $plink = "<a href=\"#\" ";	   
	           //call javascript for opening a new browser win for the img		   
	           //$params = $photo . ";Image;width=300,height=200;";
	           $params = "$clickphoto;$xfulldist;$yfulldist;<B>$title</B><br>$descr;";			 

			   $js = new jscript;
	           $plink .= $js->JS_function("js_popimage",$params); 
			   unset ($js); 

	           $plink .= ">";
			 }
	       }
	       else
             $plink = "<A href=\"$photo\">";

			$lo = "<img src=\"" . $photo . "\" width=\"$x\"";
			$lo.= $y ? "height=\"$y\"" : null;
			$lo.= "border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";  
	        $ret = $plink . $lo;
		  }
		  elseif ($imageclick==2) {//product url
		    $purl = seturl("t=kshow"."&cat=".$cat."&id=".$code,null,null,null,null,$this->rewrite); 
		    $plink = "<A href=\"$purl\">";
			$lo = "<img src=\"" . $photo . "\" width=\"$x\"";
			$lo.= $y ? "height=\"$y\"" : null;
			$lo.= "border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";           
            $ret = $plink . $lo;
		  }
		  elseif ($imageclick==0) {//item link
		    $resource = "<img src=\"" . $photo . "\" width=\"$x\"";
			$resource.= $y ? "height=\"$y\"" : null;
			$resource.= "border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">";
		    $ret = seturl('t=kshow&cat='.$cat.'&page='.$page.'&id='.$code,$resource,null,null,null,$this->rewrite);// . "</A>";
		  } 
		  else {//item link
		    $resource = "<img src=\"" . $photo . "\" width=\"$x\"";
			$resource.= $y ? "height=\"$y\"" : null;
			$resource.= "border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">";		  
		    $ret = seturl('t=kshow&cat='.$cat.'&page='.$page.'&id='.$code,$resource,null,null,null,$this->rewrite);// . "</A>";
		  } 
		}
		else {
		  $plink = "<A href=\"$imageclick\">";
		  $lo = "<img src=\"" . $photo . "\" width=\"$x\"";
		  $lo.= $y ? "height=\"$y\"" : null; 
		  $lo.= "border=\"0\" " . "></A>";
          $ret = $plink . $lo;           		
	    } 	   		
	
	    return ($ret);
	}
	
	function list_katalog($imageclick=null,$cmd=null,$template=null,$no_additional_info=null,$external_read=null,$photosize=null,$nopager=null,$nolinemax=null) {
	   $cmd = $cmd?$cmd:'klist';
	   $lan = getlocal();
	   $itmname = $lan?'itmname':'itmfname';
	   $itmdescr = $lan?'itmdescr':'itmfdescr';
	   //echo $lan,':'.$itmname;
	   $pz = $photosize?$photosize:1;		   
	   $xdist = $this->imagex?$this->imagex:100;
	   $ydist = $this->imagey?$this->imagey:null; //free y 75;	
   	   $cat = GetReq('cat');		
	      
	   if ($nolinemax)
	     $mylinemax = null;
	   else
	     $mylinemax = $this->linemax;   
	   
	   if ($this->imageclick>0)
	     $myimageclick = 1;
	   else	 
	     $myimageclick = $imageclick;
		   
	   /*$template='fpkatalog.htm';	   
	   $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$template) ; 
	   //echo $t,'>';
	   if (($template) && is_readable($t)) {
	     //echo $template,'>';
		 $mytemplate = file_get_contents($t);
		 //$html = $this->combine_template($template,'a','b','c','d','e');
		 //echo $html;
	   }	*/
       if ($template) {
	     $tmpl = explode('.',$template);
	     $mytemplate = $this->select_template($tmpl[0],$cat);		
	   }
	   else	   
  	     $mytemplate = $this->select_template('fpkatalog',$cat);		   
	   
	   //if ($external_read==null) {
   	   if (!$this->result->sql) { //AUTOMATED...when sql exist by prev query dont read a new

	     $is_one_item = $this->read_list(); //read records
	     if ($is_one_item) { 
	       //echo $is_one_item,'>';
		   $this->read_item($this->direction,$is_one_item);
		   $out = $this->show_item();
		   return ($out);
	     }
	   } 
	   
	   
	   if ($this->msg) $out = $this->msg;	   
	   
	   //set if show this up and down (2) or only up (1) or nothing 0
	   //if ((!$this->listcontrols)||($this->listcontrols>1))	   

	   if (!empty($this->result)) {	
	   
		$pp = $this->read_policy();
			 	   
	    //$max  = count($this->result[0])-1;
	    //$prc = 96/$max;		
		if ($this->max_cat_items>0)
	      $out .= $this->show_asceding($cmd,$mytemplate,null,$nopager);	

		$records = $this->result;  //echo 'B';  
	
	    foreach ($this->result as $n=>$rec) {
		
		   //memory limit prevention
		   //echo 'mem limit 33554432:',memory_get_peak_usage(true);//memory_get_usage();
		   $mem = memory_get_peak_usage(true);//memory_get_usage();
		   /*if ($mem>16000000) {
		     $mem_msg = '<br><h2>WARNING:Memory allocation failed, reduce page view limit!</h2>';
		     break;
		   }*/	 
		
           //if ($createcats) {  //...disabled usualy needs the item cat
             $cat = $this->getkategories($rec);
			 $ucat = urlencode($cat);
			 //echo $cat,'<br>';
           //}
				   
		   if ($rec[$pp]>0) { 
		     $myp = $this->spt($rec[$pp]);
		     $price = $myp?$myp:'&nbsp;';//($myp?number_format($myp,$this->decimals,',','.'):"&nbsp;");
		   }	 
		   else 	 
		     $price = $this->zeroprice_msg;				   
				   
		   if ((GetGlobal('UserID')) || (seclevel('SHKATALOG_CART',$this->userLevelID))) {//logged in or sec ok
		     $cart_code = $rec[$this->getmapf('code')];
			 $cart_title = $this->replace_spchars($rec[$itmname]);
			 $cart_group = $cat;
			 $cart_page = GetReq('page')?GetReq('page'):0;
			 $cart_descr = $this->replace_spchars($rec[$itmdescr]);
			 $cart_photo = $this->get_photo_url($rec[$this->getmapf('code')],$pz);
			 $cart_price = $price;
			 $cart_qty = 1;//???
		     $cart = GetGlobal('controller')->calldpc_method("shcart.showsymbol use $cart_code;$cart_title;$path;$MYtemplate;$cart_group;$cart_page;$cart_descr;$cart_photo;$cart_price;$cart_qty;+$cat+$cart_page",1);//'cart';
			 $array_cart = $this->read_array_policy($rec[$this->getmapf('code')],$price,"$cart_code;$cart_title;$path;$MYtemplate;$cart_group;$cart_page;$cart_descr;$cart_photo;$cart_price;$cart_qty");	   
		   }
		   
		   $availability = $this->show_availability($rec['ypoloipo1']);			   
		   $details = seturl('t=kshow&cat='.$ucat.'&page='.$page.'&id='.$rec[$this->getmapf('code')].'#DETAILS',$this->details_button,null,null,null,$this->rewrite);	   
           $detailink = seturl('t=kshow&cat='.$ucat.'&page='.$page.'&id='.$rec[$this->getmapf('code')].'#DETAILS',$this->details_button,null,null,null,$this->rewrite);		   
		   $itemlink = seturl('t=kshow&cat='.$ucat.'&page='.$page.'&id='.$rec[$this->getmapf('code')],null,null,null,null,$this->rewrite);
		   $itemlinkname = seturl('t=kshow&cat='.$ucat.'&page='.$page.'&id='.$rec[$this->getmapf('code')],$rec[$itmname],null,null,null,$this->rewrite);		   
		   
		   if ($mytemplate) {
		   
              //// tokens method												 
		      $tokens[] = $itemlinkname;//$rec[$itmname];
			  $tokens[] = $rec[$itmdescr];
			  $tokens[] = $this->list_photo($rec[$this->getmapf('code')],$xdist,$ydist,$myimageclick,$ucat,$pz);
			  
			  //units + qty
			  if (defined("SHCART_DPC"))
			    $in_cart = GetGlobal('controller')->calldpc_method("shcart.getCartItemQty use ".$rec[$this->getmapf('code')]);
			  $incart = $in_cart?':<B>'.$in_cart.'</B>':null;
			  $units = $rec['uniname2']?$rec['uniname1'].'/'.$rec['uniname2']:$rec['uniname1'];  
			  $tokens[] = $units;// . $incart;
			 
			  $tokens[] = $rec['itmremark'];//$this->getmapf('code')], 
			  $tokens[] = number_format($price,$this->decimals,',','.');//$price;
			  $tokens[] = $cart;
			  $tokens[] = $availability;
			  $tokens[] = $details;
			  $tokens[] = $detailink;
			  $tokens[] = $rec[$this->getmapf('code')];
			  $tokens[] = $itemlink;	
			  
			  $tokens[] = $in_cart?$in_cart:null;
			  $tokens[] = $array_cart;
			  
		      
			  if ($mylinemax>1) {//table view	
			    $items[] = $this->combine_tokens($mytemplate, $tokens);		
				unset($tokens);	
			  }													 
			  else {								 			   		
			    $toprint .= $this->combine_tokens($mytemplate, $tokens);
				unset($tokens);		
			 }													 
		   }	 				   	   	   
		   else {		   				   	

		   //$viewdata[] = $rec['code5'];//$n+1;
		   //$viewattr[] = "left;5%";   
	       $viewdata[] = $this->list_photo($rec[$this->getmapf('code')],$xdist,$ydist,$myimageclick,$ucat,$pz);
	       $viewattr[] = "left;5%";		   					   	   	   
		   
		   $viewdata[] = "<b>" . /*($rec[$itmname]?$rec[$itmname]:"&nbsp;")*/($rec[$itmname]?$itemlinkname:"&nbsp;") . "</b><br>" . 
						 ($rec[$itmdescr]?$rec[$itmdescr]:"&nbsp;");
		   $viewattr[] = "left;70%";	
		   
		   
	       $viewdata[] = "<b>" . $rec[$this->getmapf('code')] . "</b>" .//. "<br>" . $rec['uniname1']."<br>".
		                 "<h2>". number_format($price,$this->decimals,',','.') . "&#8364"/*writecl($price,'#FF0000','#FFFFFF')*/."</h2><br>" .
						 $cart . $availability;
	       $viewattr[] = "center;25%";			   		   
		   	   		   	   		   	   		   
		   
	       $myrec = new window('',$viewdata,$viewattr);
	       $toprint .= $myrec->render("center::100%::0::group_article_selected::left::5::5::");
	       unset ($viewdata);
	       unset ($viewattr);	
		   
		   $toprint .= "<hr>";
		   }//template	
		  } 
		  
		  if ((is_array($items)) && ($mylinemax>1)) {
	        //make table
	        $itemscount = count($items);
	        $timestoloop = floor($itemscount/$mylinemax)+1;
	        $meter = 0;
	        for ($i=0;$i<$timestoloop;$i++) {
	          //echo $i,"---<br>";
	          for ($j=0;$j<$mylinemax;$j++) {
	            //echo $i*$j,"<br>";
		        $viewdata[] = (isset($items[$meter])? $items[$meter] : "&nbsp");
		        $viewattr[] = "center;10%";	
		        $meter+=1;	 
	          }
	  
	          $myrec = new window('',$viewdata,$viewattr);
		      if ($mytemplate) 
                $toprint .= $myrec->render("center::100%::0::::left::0::0::");		  
		      else				  
	            $toprint .= $myrec->render();//"center::100%::0::group_article_selected::left::4::4::");
	          unset ($viewdata);
	          unset ($viewattr);		  
	        }		  
	      }
		  
	      //set if show this up and down (2) or only up (1) or nothing 0
	      //if ((!$this->listcontrols)||($this->listcontrols>2))
		  if ($this->max_cat_items>0) {
	        $toprint .= $this->show_asceding($cmd,$mytemplate,null,$nopager);
			
			//if (!$nopager)...func attr
	          $toprint .= $this->show_paging($cmd,$mytemplate,$nopager);							   	
		  }	
					
	   }
	   else
		  //$toprint .= $this->show_lastentries();	
		  $toprint .= "<h2>" .localize('_nofound',getlocal()). "</h2><br>";
	
	
	   if ($mytemplate) {
	     $out .= $toprint . $mem_msg;
	   }
	   else {
         $mywin = new window(/*$this->title*/'',$toprint . $mem_msg);
         $out .= $mywin->render();	
	   }	 
	   
	   
	   if ((count($this->result)>0) && ($no_additional_info==null)) {
	     if ($this->max_cat_items>0)
	       $out .= $this->show_availabillity_table(null,1);	      	 
	   }	   	 		
	
	   return ($out);	
	}
	
    function list_katalog_table($linemax=2,$imgx=null,$imgy=null,$imageclick=0,$showasc=0,$cmd=null,$template=null,$no_additional_info=null,$lang=null,$external_read=null,$photosize=null,$nopager=null) {
	   $cmd = $cmd?$cmd:'klist';	
	   $page = GetReq('page')?GetReq('page'):0;			   
	   $lan = $lang?$lang:getlocal();
	   $itmname = $lan?'itmname':'itmfname';
	   $itmdescr = $lan?'itmdescr':'itmfdescr';	   
	   $pz = $photosize?$photosize:1; 
	   $xdist = ($imgx?$imgx:160);
	   $ydist = ($imgy?$imgy:120);
   	   $cat = GetReq('cat');	
       /*if (!$cat) 
	     $createcats=1; 
	   else 
	     $createcats=$catcrt;	   */
	   
	   if (remote_paramload('SHKATALOG','imageclick',$this->path)>0)
	     $myimageclick = 1;
	   else	 
	     $myimageclick = $imageclick;	   
	   
	   
       if ($template) {
	     $tmpl = explode('.',$template);
	     $mytemplate = $this->select_template($tmpl[0],$cat);		
	   }
	   else		   
	     $mytemplate = $this->select_template($template,$cat,1);
		   
	   //echo '###',$external_read,'>>>>>';
	   //if ($external_read==null) {	  print_r($this->result); 
	   if (!$this->result->sql) { //AUTOMATED...when sql exist by prev query dont read a new
	   	       	
	     $is_one_item = $this->read_list(); 
		 //SERVER ERROR 500 when on table view beside list view..disable it
	     /*if ($is_one_item) {
	       //echo $is_one_item,'>';
		   $this->read_item($this->direction,$is_one_item);
		   $out = $this->show_item();
		   return ($out);
	     }*/		   
	   }
		
	   if ($this->msg) $ret = $this->msg;

	   if (!empty($this->result)) {	
	   
        $pp = $this->read_policy();		
	    //$ret .= $this->show_asceding();		
	
	    foreach ($this->result as $n=>$rec) {
		   
		     
		   $mem = memory_get_peak_usage(true);//memory_get_usage();
		   /*if ($mem>16000000) {
		     $mem_msg = '<br><h2>WARNING:Memory allocation failed, reduce page view limit!</h2>';
		     break;
		   }*/			
		
           //if ($createcats) {//...........disbaled usualy needs the items cat
              $cat = $this->getkategories($rec);
			  $ucat = urlencode($cat);
           //}	
		
		   if ($rec[$pp]>0) { 
		     $myp = $this->spt($rec[$pp]);
		     $price = $myp?$myp:'&nbsp';//($myp?number_format($myp,$this->decimals,',','.'):"&nbsp;");
		   }	
		   else 	 
		     $price = $this->zeroprice_msg;
			 
		   $pfile = sprintf("%05s",$rec[$this->getmapf('code')]); //echo $pfile,"<br>";
		   
		   //photo
/*		   $photo = $this->imgpath . $pfile . $this->restype;	   
		   if (!file_exists($photo))
		     $photo = $this->imgpath . 'nopic' . $this->restype;	
			 
		  //thubnail photo	 
		   $thub_photo = $this->thubpath . $pfile . $this->restype;	//echo $thub_photo,"!<br>";   
		   if (!file_exists($thub_photo))
		     $thub_photo = $this->thubpath . 'nopic' . $this->restype;
*/			 
		   if (($user=GetGlobal('UserID')) || (seclevel('SHKATALOG_CART',$this->userLevelID))) {//logged in or sec ok		   
		     $cart_code = $rec[$this->getmapf('code')];
			 $cart_title = $this->replace_spchars($rec[$itmname]);
			 $cart_group = $cat;
			 $cart_page = GetReq('page')?GetReq('page'):0;
			 $cart_descr = $this->replace_spchars($rec[$itmdescr]);
			 $cart_photo = $this->get_photo_url($rec[$this->getmapf('code')],$pz);
			 $cart_price = $price;
			 $cart_qty = 1;//???
		     $icon_cart = GetGlobal('controller')->calldpc_method("shcart.showsymbol use $cart_code;$cart_title;$path;$MYtemplate;$cart_group;$cart_page;$cart_descr;$cart_photo;$cart_price;$cart_qty;+$cat+$cart_page",1);//'cart'; 
			 $array_cart = $this->read_array_policy($rec[$this->getmapf('code')],$price,"$cart_code;$cart_title;$path;$MYtemplate;$cart_group;$cart_page;$cart_descr;$cart_photo;$cart_price;$cart_qty");	   
		   }
           else
		     $icon_cart = null;			 			 
		     //echo $user,'>',$icon_cart;
		   
		   $availability = $this->show_availability($rec['ypoloipo1']);		
		   $details = seturl('t=kshow&cat='.$ucat.'&page='.$page.'&id='.$rec[$this->getmapf('code')].'#DETAILS',$this->details_button,null,null,null,$this->rewrite);	   
           $detailink = seturl('t=kshow&cat='.$ucat.'&page='.$page.'&id='.$rec[$this->getmapf('code')].'#DETAILS',$this->details_button,null,null,null,$this->rewrite);		   
		   $itemlink = seturl('t=kshow&cat='.$ucat.'&page='.$page.'&id='.$rec[$this->getmapf('code')],null,null,null,null,$this->rewrite);
		   $itemlinkname = seturl('t=kshow&cat='.$ucat.'&page='.$page.'&id='.$rec[$this->getmapf('code')],$rec[$itmname],null,null,null,$this->rewrite);			   

		   if ($mytemplate) {
												 
             //// tokens method												 
		     $tokens[] = $itemlinkname;//$rec[$itmname];
			 $tokens[] = $rec[$itmdescr];
			 $tokens[] = $this->list_photo($rec[$this->getmapf('code')],$xdist,$ydist,$myimageclick,$ucat,$pz);
			 
			 //units + qty
			 if (defined("SHCART_DPC"))			 
			   $in_cart = GetGlobal('controller')->calldpc_method("shcart.getCartItemQty use ".$rec[$this->getmapf('code')]);
			 $incart = $in_cart?':<B>'.$in_cart.'</B>':null;
			 $units = $rec['uniname2']?$rec['uniname1'].'/'.$rec['uniname2']:$rec['uniname1'];  
			 $tokens[] = $units;// . $incart;
			 
			 $tokens[] = $rec['itmremark'];//$this->getmapf('code')], 
			 $tokens[] = number_format($price,$this->decimals,',','.');//$price;
			 $tokens[] = $icon_cart;
			 $tokens[] = $availability;
			 $tokens[] = $details;
			 $tokens[] = $detailink;
			 $tokens[] = $rec[$this->getmapf('code')];
			 $tokens[] = $itemlink;
			 
			 $tokens[] = $in_cart?$in_cart:null;
			 $tokens[] = $array_cart;
			  			 
			 
			 $items[] = $this->combine_tokens($mytemplate, $tokens);	
			 unset($tokens);													 
		   }	 				   	   	   
		   else {
		       $viewdata[] = "<b>"./*($rec[$itmname]?$rec[$itmname]:"&nbsp;")*/($rec[$itmname]?$itemlinkname:"&nbsp;") . "</b><br>" . 
						 /*localize('_descr',getlocal()) . ":" .*/ ($rec[$itmdescr]?$rec[$itmdescr]:"&nbsp;") . "<br>" . 
		                 localize('_uniname1',getlocal()) . ":" . ($rec['uniname1']?$rec['uniname1']:"&nbsp;") . "<br>" .
                         localize('_code',getlocal()) . ":" . $rec[$this->getmapf('code')] . "<br>" .						 
						 /*localize('_axia',getlocal()) . ":" .*/ 
						 "<b>". writecl(number_format($price,$this->decimals,',','.').'&nbsp;&#8364','#FFFFFF','#FF0000')."</b>";/* . "<br><br>" .
						 seturl('t=kshow&cat='.$rec[1].'&id='.$rec['id'].'&page='.$page,'Περισσότερα...');*/
		       $viewattr[] = "left;60%";					 		   
		      
		       $viewdata[] = $this->list_photo($rec[$this->getmapf('code')],$xdist,$ydist,$myimageclick,$ucat,$pz).
		                 '<br>' . $icon_cart . $availability;
	           $viewattr[] = "left;40%";
		   
	           //$viewdata[] = "&nbsp;";
	           //$viewattr[] = "left;10%";			   		   
		   	   		   	   		   	   		   
		   
	           $myrec = new window('',$viewdata,$viewattr);
	           $items[] = $myrec->render("center::100%::0::group_article_table::left::3::3::");
	           unset ($viewdata);
	           unset ($viewattr);	
		   }//if template
		   
		}//foreach	
		
		  
	    if ($showasc) {
		  //set if show this up and down (2) or only up (1) or nothing 0
	      //if ((!$this->listcontrols)||($this->listcontrols>1))
	        $ret .= $this->show_asceding($cmd,null,null,$nopager);
	      //$ret .= "<hr>";		 
	    }
	   
	    //make table
	    $itemscount = count($items);
	    $timestoloop = floor($itemscount/$linemax)+1;
	    $meter = 0;
	    for ($i=0;$i<$timestoloop;$i++) {
	      //echo $i,"---<br>";
	      for ($j=0;$j<$linemax;$j++) {
	        //echo $i*$j,"<br>";
		    $viewdata[] = (isset($items[$meter])? $items[$meter] : "&nbsp");
		    $viewattr[] = "center;10%";	
		    $meter+=1;	 
	      }
	    
	      $myrec = new window('',$viewdata,$viewattr);
		  if ($mytemplate) 
            $ret .= $myrec->render("center::100%::0::::left::0::0::");		  
		  else		  
	        $ret .= $myrec->render("center::100%::0::group_article_selected::left::4::4::");
	      unset ($viewdata);
	      unset ($viewattr);		  
	    }
		
	   
	    if ($showasc) {
	      //$ret .= "<hr>";
		  //set if show this up and down (2) or only up (1) or nothing 0
	      //if ((!$this->listcontrols)||($this->listcontrols>2))
	        $ret .= $this->show_asceding($cmd,$mytemplate,null,$nopager);
	    }
			
		if ($this->pager){
		  $ret .= $this->show_paging($cmd,$mytemplate,$nopager);					
	    }
		
	   }
	   else //count	
		  $toprint .= "<h2>" .localize('_nofound',getlocal()). "</h2><br>";	   	

	   if ((count($this->result)>0) && ($no_additional_info==null))   
	     $ret .= $this->show_availabillity_table(null,1);	   
	
	   return ($ret);	
    } 
	
	function read_item($direction=null,$item_id=null) {
        $db = GetGlobal('db');	
		//$item = GetReq('id');	
	    if ($item_id) {
		  $item = $item_id;
		  //SetReq('id',$item_id);//for edit purposes
		  $_GET['id'] = $item_id; //for edit purposes
		}  
		else
		  $item = GetReq('listm')?GetReq('listm'):GetReq('id');
		
		if (GetReq('cat')!=null)
		  $cat = GetReq('cat');			
		
	    $sSQL = "select id,sysins,code1,pricepc,price2,sysins,itmname,itmfname,uniname1,uniname2,active,code4," .// from abcproducts";// .
	            "price0,price1,cat0,cat1,cat2,cat3,cat4,itmdescr,itmfdescr,itmremark,ypoloipo1,".$this->getmapf('code')." from products ";
				
		switch ($direction) {
		
		  case 'next':
		  $next_sql = "select id from products where ".$this->getmapf('code').">";
		  $next_sql.= $this->codetype=='string'?$db->qstr($item):$item;$item;
	      //if (isset($cat)) $next_sql .= " and type=" . $db->qstr($cat);
		  //if (isset($subcat)) $next_sql .= ' and type2=' . $db->qstr($subcat);		
		  $next_sql .= " and active=1";
		  		  
	      $rset = $db->Execute($next_sql,2);
	      //$nret = $db->fetch_array($rset);	 //only one set
	      $this->list_item = $next_item = $nret[0]?$nret[0]:$item; 			  
		  $sSQL .= " WHERE ".$this->getmapf('code')."=";
		  $sSQL .= $this->codetype=='string'?$db->qstr($next_item):$next_item;
		  
		  if (($lock = $this->itemlockparam) && (!GetGlobal('UserID')))
		    $sSQL .=  ' and ' . $lock . ' is null';		  
		  break;
		  
		  case 'prev':
		  $prev_sql = "select ".$this->getmapf('code')." from products where ".$this->getmapf('code')."<";
		  $prev_sql.= $this->codetype=='string'?$db->qstr($item):$item;$item;
	      //if (isset($cat)) $prev_sql .= " and type=" . $db->qstr($cat);
		  //if (isset($subcat)) $prev_sql .= ' and type2=' . $db->qstr($subcat);		
		  $prev_sql .= " and active=1";
		  		  		  
	      $rset = $db->Execute($prev_sql,2);
	      //$nret = $db->fetch_array($rset);	 //only one set
	      $this->list_item = $prev_item = $nret[0]?$nret[0]:$item; 		  		  
		  $sSQL .= " WHERE ".$this->getmapf('code')."=";
  		  $sSQL .= $this->codetype=='string'?$db->qstr($prev_item):$prev_item;
		  
		  if (($lock = $this->itemlockparam) && (!GetGlobal('UserID')))
		    $sSQL .=  ' and ' . $lock . ' is null';		  
		  break;
		   
		  default : 
		  $sSQL .= " WHERE ".$this->getmapf('code')."=";
		  $sSQL .= $this->codetype=='string'?$db->qstr($item):$item;
		  
		  if (($lock = $this->itemlockparam) && (!GetGlobal('UserID')))
		    $sSQL .=  ' and ' . $lock . ' is null';
		} 	  
	   
	   //echo $sSQL;
	   
	   $resultset = $db->Execute($sSQL,2);
	   //$ret = $db->fetch_array_all($resultset);	 
	   //print_r($ret);  
	   $this->result = $resultset; 	
	   //print_r($this->result);
	   
	}	
	
	function show_item($template=null,$no_additional_info=null,$lang=null,$lnktype=1,$pcat=null,$boff=null,$tax=null) {
	   global $current_item;//use global becouse of same page info transfer
	   
	   $lan = $lang?$lang:getlocal();
	   $itmname = $lan?'itmname':'itmfname';
	   $itmdescr = $lan?'itmdescr':'itmfdescr';
	   $page = GetReq('page')?GetReq('page'):0;			
	   $cat = $pcat?$pcat:GetReq('cat');
	   $id = GetReq('id');
	   $listm = $this->list_item?$this->list_item:GetReq('id');	  
	   $xdist = $this->itemimagex?$this->itemimagex:200;
	   $ydist = $this->itemimagey?$this->itemimagey:null; //free y 100;	
	   $buttons_OFF = $boff?$boff:$this->buttons_OFF;
	   //echo $buttons_OFF,'>';
	   
	   $mytemplate = $this->select_template('fpitem',$cat);	      
	   
	   //print_r($this->result); echo 'z';
	   //if (count($this->result)>0) {	
	   if (count($this->result->fields)>1) {		   
	   
		$pp = $this->read_policy();	   
	   
	    foreach ($this->result as $n=>$rec) {	
		   
           $cat = $this->getkategories($rec);					 
		   
		   if ($rec[$pp]>0) { 
		     $myp = $this->spt($rec[$pp],$tax);
			 //echo $tax,':',$myp,'<br>';
		     $price = $myp?$myp:'&nbsp;';//($myp?number_format($myp,$this->decimals,',','.'):"&nbsp;");
		   }	
		   else 	 
		     $price = $this->zeroprice_msg;	  						 
		   
           $recar[localize('_code',getlocal())]=($rec[$this->getmapf('code')]!=null?$rec[$this->getmapf('code')]:"&nbsp;");		   
		   $recar[localize('_item',getlocal())]=($rec[$itmname]!=null?$rec[$itmname]:"&nbsp;");
		   $recar[localize('_descr',getlocal())]=($rec[$itmdescr]?$rec[$itmdescr]:"&nbsp;");
		   $recar[localize('_uniname1',getlocal())]=($rec['uniname1']!=null?$rec['uniname1']:"&nbsp;"); 
		   $recar[localize('_code',getlocal())]=($rec[$this->getmapf('code')]?$rec[$this->getmapf('code')]:"&nbsp;"); 
		   $recar[localize('_axia',getlocal())]= number_format($price,$this->decimals,',','.')/*$price*/ . "<br><br>";
		   //$recar[]=;
		   //set links as elements...
		   //$recar[seturl('t=klist&cat='.$rec[0].'&subcat='.$scat,'Επιστροφή...')] =  seturl('t=qbuy&id='.$id,'Εκδήλωση ενδιαφέροντος...');	   		   		   
		   $table2show = $this->make_table_to_show($recar);	   
		   
		   //SAVE TO RETRIEVE BY SPONSORS WHEN HAVE SPONSORAS BY TYPE, MODEL etc.
		   $this->datarecord = (array) $recar;			   
		   
	       //save vehicle for aditional services as forum
	       $current_item = $rec['itmname']. " " . " (" . $rec[$this->getmapf('code')] . ")";
		   //echo $current_vehicle;
		   SetSessionParam('CURRENT_ITEM',$current_item);

		   
		   $icon_back  = loadTheme('icon_back','Επιστροφή...');
		   $icon_prev  = loadTheme('icon_prev','Προηγούμενο...');
		   $icon_next  = loadTheme('icon_next','Επόμενο...');		   		   
		   //$icon_getit = loadTheme('icon_getit','Εκδήλωση ενδιαφέροντος...');
		   $icon_print = loadTheme('icon_print',localize('_PRINT',getlocal())); 
/*		   
		   if ($rec[11]<0) {//sold
		     $icon_auction = loadTheme('auction_out');
		   }
		   elseif ($rec[13]) 
		     $icon_auction = loadTheme('auction_in'); 
*/		

		   $mybuttons = seturl('t=klist&cat='.$cat.'&page='.$page,$icon_back) .
				 	    seturl('t=kprev&cat='.$cat.'&page='. $page . '&direction=prev&id='.$id.'&listm='.$listm,$icon_prev) .						 
					    seturl('t=knext&cat='.$cat.'&page='. $page. '&direction=next&id='.$id.'&listm='.$listm,$icon_next) .						 
					    $this->printlink($icon_print);
						 

		   if ((GetGlobal('UserID')) || (seclevel('SHKATALOG_CART',$this->userLevelID))) {//logged in or sec ok
		     $cart_code = $rec[$this->getmapf('code')];
			 $cart_title = $this->replace_spchars($rec[$itmname]);
			 $cart_group = $cat;
			 $cart_page = GetReq('page')?GetReq('page'):0;
			 $cart_descr = $this->replace_spchars($rec[$itmdescr]);
			 $cart_photo = $this->get_photo_url($rec[$this->getmapf('code')],1);
			 $cart_price = $price;
			 $cart_qty = 1;//???
		     $icon_cart = GetGlobal('controller')->calldpc_method("shcart.showsymbol use $cart_code;$cart_title;$path;$MYtemplate;$cart_group;$cart_page;$cart_descr;$cart_photo;$cart_price;$cart_qty;+$cat+$cart_page",1);//'cart';
             $array_cart = $this->read_array_policy($rec[$this->getmapf('code')],$price,"$cart_code;$cart_title;$path;$MYtemplate;$cart_group;$cart_page;$cart_descr;$cart_photo;$cart_price;$cart_qty");	   			 
		   }
           else
		     $icon_cart = null;
			
		   $availability = $this->show_availability($rec['ypoloipo1']);	 
		   $detailink = seturl("t=kshow&id=".$rec[$this->getmapf('code')]."&cat=$cat&page=$page".'#DETAILS',$this->details_button);		   
			 
	       $linkphoto = $this->list_photo($rec[$this->getmapf('code')],$xdist,$ydist,$lnktype,$cat,2,3);	
		   //echo $lnktype,'>';			 
		   if ($mytemplate) {	 		 	      
             $details = "<a name=\"DETAILS\"><p>";
             //$details .= $this->read_array_policy($rec[$this->getmapf('code')],$price,"$cart_code;$cart_title;$path;$MYtemplate;$cart_group;$cart_page;$cart_descr;$cart_photo;$cart_price;$cart_qty");			 		   			 
			 $details .= $this->show_aditional_files($rec[$this->getmapf('code')],1);
             $details .= $this->show_aditional_html_files($rec[$this->getmapf('code')]);			 
             $details .= $this->show_aditional_txt_files($rec[$this->getmapf('code')]);
			 $details .= "</p>";	
	
		   
												 
             //// tokens method												 
		     $tokens[] = $rec[$itmname];
			 $tokens[] = $rec[$itmdescr];
			 $tokens[] = $linkphoto;
			 
			 //units + qty
			 if (defined("SHCART_DPC"))
			   $in_cart = GetGlobal('controller')->calldpc_method("shcart.getCartItemQty use ".$rec[$this->getmapf('code')]);
			 $incart = $in_cart?':<B>'.$in_cart.'</B>':null;
			 $units = $rec['uniname2']?$rec['uniname1'].'/'.$rec['uniname2']:$rec['uniname1'];  
			 $tokens[] = $units;// . $incart;
			 
			 $tokens[] = $rec['itmremark'];//$this->getmapf('code')], 
			 $tokens[] = number_format($price,$this->decimals,',','.');//$price;
			 $tokens[] = $icon_cart;
			 $tokens[] = $availability;
			 $tokens[] = $detailink;
			 $tokens[] = $details;
			 $tokens[] = $rec[$this->getmapf('code')];
			 
			 $tokens[] = $in_cart?$in_cart:null;
			 $tokens[] = $array_cart;
			  			 			 
			 $toprn .= $this->combine_tokens($mytemplate, $tokens);
			 unset($tokens);													 
												 
			 //$toprn .= $details;	//in template moved inside tokens								 
			 
             $toprint .= $toprn; //..copy print ver to toprint flow..
			 //add buttons			 
			 $toprint .= $buttons_OFF?"&nbsp;":$mybuttons;					  
           }
		   else {
		     $bb = $buttons_OFF?"&nbsp;":$mybuttons;
		     $viewdata[] = $table2show .
			  			   $bb .
						   $icon_cart .
						   $availability;
		     $viewattr[] = "left;50%";	
		   
		     //printout...
		     $printdata[] = $table2show;
		     $printattr[] = "left;50%";			   
           
		     //photo in window
		     $pwin = new window('Φωτογραφία',$linkphoto);
		     $pphoto = $pwin->render();
		     unset ($pwin);	 
			 
	         $viewdata[] = $pphoto .
			               $this->read_array_policy($rec[$this->getmapf('code')],$price,"$cart_code;$cart_title;$path;$MYtemplate;$cart_group;$cart_page;$cart_descr;$cart_photo;$cart_price;$cart_qty") . 
		                   $this->show_aditional_files($rec[$this->getmapf('code')],1) .
						   $this->show_aditional_html_files($rec[$this->getmapf('code')]) .
						   $this->show_aditional_txt_files($rec[$this->getmapf('code')]); 
	         $viewattr[] = "left;50%";
		   
		     //printout...
		     $printdata[] = $pphoto . 
			                $this->read_array_policy($rec[$this->getmapf('code')],$price,"$cart_code;$cart_title;$path;$MYtemplate;$cart_group;$cart_page;$cart_descr;$cart_photo;$cart_price;$cart_qty") .
		                    $this->show_aditional_files($rec[$this->getmapf('code')]) .
			 			    $this->show_aditional_html_files($rec[$this->getmapf('code')]) . 
							$this->show_aditional_txt_files($rec[$this->getmapf('code')]);
		     $printattr[] = "left;50%";			   		   
		   	   		   	   		
		     $toprint .= $this->set_anchor('photo');									   	   		   
		   
	         $myrec = new window('',$viewdata,$viewattr);
		     if ($mytemplate) 
               $toprint .= $myrec->render("center::100%::0::::left::0::0::");		  
		     else			   
	           $toprint .= $myrec->render("center::100%::0::group_article_selected::left::3::3::");
	         unset ($viewdata);
	         unset ($viewattr);	
		   
		     //printout
	         $myprn = new window('',$printdata,$printattr);
		     if ($mytemplate) 
               $toprn .= $myrec->render("center::100%::0::::left::0::0::");		  
		     else			   
	           $toprn = $myprn->render("center::100%::0::group_article_selected::left::3::3::");
	         unset ($printdata);
	         unset ($printattr);		
		   
		   }//mytemplate 		   
		   
		   SetSessionParam('printpage',$toprn);	 
		   
		   //AUTOMATED....
		   /*if (defined('ABCFORUM_DPC')) {
		   
		     $frm = GetGlobal('controller')->calldpc_method('abcforum.display_forums');
		     $toprint .= $frm;//'Forum';
		   }*/
		   
	       if ($mytemplate) {		  
	         $out = $toprint;
	       }
	       else {
             $mywin = new window(/*$this->title*/'',$toprint);
             $out = $mywin->render("center::100%::0::group_article_selected::left::2::2::");	
	       }
	   	 
           if ($no_additional_info==null)	   
	         $out .= $this->show_availabillity_table(null,1); 			    		
	 	}	   
	   }
	   else {
		  if ($this->itemlockparam) {
		    if ($goto = $this->itemlockgoto)
			  $out = GetGlobal('controller')->calldpc_method($goto);
			else
			  $out = "<h2>".localize('_lockrec',getlocal())."</h2>";
		  }
		  else 
		    $out = "<h2>".localize('_norec',getlocal())."</h2>";
	   }		
		  		  	   
		  	   
	   return ($out);	
	}		
	
	function show_aditional_files($id,$nojs=null) {
	     $addfx = $this->addfx?$this->addfx:100;
	     $addfy = $this->addfy?$this->addfy:null;//free y size //75;	
	     //main
		 $mainimg = $this->urlpath .'/'. $this->imgpath .  $id . $this->restype; 
		 $mainimg_url = $this->imgpath . $id . $this->restype;
		 
	     $template='fpitemaddfiles.htm';	   
	     $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$template) ; 
	     //echo $t,'>';
	     if (($template) && is_readable($t)) {
		   $mytemplate = file_get_contents($t);
	     }		 	
		 	 
         if (file_exists($mainimg)) {	
             if (iniload('JAVASCRIPT')) {	
			 
		       if ($this->lightbox) {
                 $plink = "<A href=\"$mainimg_url\" rel='lightbox' title='$title'>";			 
			   }
			   else {			 
  	             $plink = "<a href=\"#\" ";	   
	             //call javascript for opening a new browser win for the img		   
	             //$params = $photo . ";Image;width=300,height=200;";
	             $params = "$mainimg_url;640;480;<B>$title</B><br>$descr;";			 

			     $js = new jscript;
	             $plink .= $js->JS_function("js_popimage",$params); 
			     unset ($js); 

	             $plink .= ">";
			   }	 
	         }
	         else {
			   $addtional_photo_link = seturl('t=kshow&cat='.GetReq('cat').'&id='.GetReq('id').'&thub='.$i.'#photo');
			   $plink = "<A href=\"$addtional_photo_link\">";				 
               //$plink = "<A href=\"$photo\">";			 
			 }  
			 
			 $lo = "<img src=\"" . $mainimg_url . "\"  width=\"$addfx\" ";
			 $lo.= $addfy ? "height=\"$addfy\"":null; //free y size
			 $lo.= "border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";		 
			 $items[] = $plink . $lo;
		 }	
	
		 //multiple photos
		 for($i='A';$i<='I';$i++) {
		   //work with uphoto path only......
		   //$ad_photo_small = $this->thubpath .  $id . $i . $this->restype;
		   $ad_photo_big = $this->imgpath .  $id . $i . $this->restype;
		   
		   $aditional_pic_file = $this->urlpath .'/'. $this->imgpath . $id . $i . $this->restype;
           // echo $aditional_pic_file,'<br>';
		   if (file_exists($aditional_pic_file)) {
   		     //echo $aditional_pic_file,'<br>';
		     //$photos .= "<br><br><img src=\"" . $ad_photo . "\"  alt=\"". localize('_IMAGE',getlocal()) . "\">";

             if (iniload('JAVASCRIPT')) {	
		       if ($this->lightbox) {
                 $plink = "<A href=\"$ad_photo_big\" rel='lightbox' title='$title'>";			 
			   }
			   else {				 
  	             $plink = "<a href=\"#\" ";	   
	             //call javascript for opening a new browser win for the img		   
	             //$params = $photo . ";Image;width=300,height=200;";
	             $params = "$ad_photo_big;640;480;<B>$title</B><br>$descr;";			 

			     $js = new jscript;
	             $plink .= $js->JS_function("js_popimage",$params); 
			     unset ($js); 

	             $plink .= ">";
			   }	 
	         }
	         else {
			   $addtional_photo_link = seturl('t=kshow&cat='.GetReq('cat').'&id='.GetReq('id').'&thub='.$i.'#photo');
			   $plink = "<A href=\"$addtional_photo_link\">";				 
               //$plink = "<A href=\"$photo\">";			 
			 }  
			 
			 $adnphoto = $plink . "<img src=\"" . $ad_photo_big . "\"  width=\"$addfx\" ";
			 $adnphoto .= $addfy ? "height=\"$addfy\"": null; //free y size
			 $adnphoto .= "border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";
			 
			 if ($mytemplate) { 
			   $remarks = $this->show_aditional_txt_files($id);			 
               $items[] =  $this->combine_template($mytemplate,$id.$i,'',$adnphoto,$remarks);
			 }
			 else									 			 
			   $items[] = $adnphoto;			 
		   }	 
		 }
		 //print_r($items);
	
	     $itemscount = count($items);
		 		 
		 if ($itemscount>0)	 {

		    
		   $linemax=3;	
		   //echo $itemscount,'-',$linemax;
	       $timestoloop = floor($itemscount/$linemax)+1;
		   //echo $timestoloop;
		   //if ($timestoloop==0) $timestoloop+=1;
		   
	       $meter = 0;
	       for ($i=0;$i<$timestoloop;$i++) {
	         //echo $i,"---<br>";
	         for ($j=0;$j<$linemax;$j++) {
	           //echo $i*$j,"<br>";
		       $viewdata[] = (isset($items[$meter])? $items[$meter] : "&nbsp");
		       $viewattr[] = "center;10%";	
		       $meter+=1;	 
	         }
	  
	         $myrec = new window('',$viewdata,$viewattr);
		     if ($mytemplate) 
               $gallery .= $myrec->render("center::100%::0::::left::0::0::");		  
		     else		  		   			 
	           $gallery .= $myrec->render("center::100%::1::group_article_selected::left::2::2::");
	         unset ($viewdata);
	         unset ($viewattr);		  
	       }
		   		 
		   if ($mytemplate)
		     $title = null;
		   else
		     $title = localize('_gallery',getlocal());	
			 		 
		   $gall = new window($title,$gallery); 
		   $out = $gall->render();
		   unset($gall);
		   
		   return ($out);
		 }
	}
	
	function show_aditional_html_files($id) {	
         $db = GetGlobal('db');	
	     $lan = getlocal();
		 
		 if ($this->one_attachment)
		   $slan = null;
		 else
		   $slan = $lan?$lan:'0';
		 //echo $slan,'>';  
	     $code = $this->getmapf('code');	  
         $sSQL = "select data from pattachments ";
	     $sSQL .= " WHERE (type='.html' or type='.htm') and code='" . $id . "'";
	     if (isset($slan))
	       $sSQL .= " and lan=" . $slan;	
	     //echo $sSQL;
	  
	     $resultset = $db->Execute($sSQL,2);	
	     $result = $resultset;
	     //print_r($result);	
	     if ($exist = $db->Affected_Rows()) {
		   //echo 'sql';
		   $ret = $result->fields['data'];
		 }		   
         else {		   

		   $mainhtml = $this->htmlpath . $id . $slan;		 
           if (file_exists($mainhtml.'.html')) {		
		     $ret = file_get_contents($mainhtml.'.html');	
		   }
		   elseif (file_exists($mainhtml. '.htm')) {		
		     $ret = file_get_contents($mainhtml.'.htm');	
		   }
		 }
		 //multiple html
		 for($i='A';$i<='I';$i++) {
		   
		   $aditional_html_file = $this->htmlpath . $id . $slan . $i;
		   if (file_exists($aditional_html_file.',html')) {
		     $ret .= file_get_contents($aditional_html_file);
		   }
		   elseif (file_exists($aditional_html_file.',htm')) {
		     $ret .= file_get_contents($aditional_html_file.'.htm');
		   }		   
		 }
		 
		 return ($ret);  		 
	}	
	
	function show_aditional_txt_files($id) {	
         $db = GetGlobal('db');	
	
	     $lan = getlocal();
		 if ($this->one_attachment)
		   $slan = null;
		 else		 
		   $slan = $lan?$lan:'0';
		   
	     $code = $this->getmapf('code');	  
         $sSQL = "select data from pattachments ";
	     $sSQL .= " WHERE code='" . $id . "' and type='.txt'";
	     if (isset($slan))
	       $sSQL .= " and lan=" . $slan;	
	     //echo $sSQL;
	  
	     $resultset = $db->Execute($sSQL,2);	
	     $result = $resultset;
	     //print_r($result);	
	     if ($exist = $db->Affected_Rows()) {
		   //echo 'sql';
		   $ret = $result->fields['data'];
		 }		   
         else {
		   $maintxt = $this->htmlpath . $id . $slan . '.txt';	
		   //echo '>'.$maintxt; 
           if (is_readable($maintxt)) {		
		     $ret = file_get_contents($maintxt);	
		   }
		 }
		 //multiple html
		 for($i='A';$i<='I';$i++) {
		   
		   $aditional_txt_file = $this->htmlpath . $id . $slan . $i . '.txt';
		   if (file_exists($aditional_txt_file)) {
		   
		     $ret .= file_get_contents($aditional_txt_file);
		   }
		 }
		 //convert \n to br for texts
		 $out = str_replace("\n","<br/>",$ret);
		 //echo $out; 
		 return ($out);  		 
	}		
		
	function show_lastentries($items=10,$days=12,$linemax=null,$imgx=100,$imgy=null,$imageclick=0,$template=null,$ainfo=null,$photosize=null,$nopager=null) {
        $db = GetGlobal('db');		
		//echo $template,'>';
		$mydays = $days?$days:12;
	    $date2check = time() - ($mydays * 24 * 60 * 60);
	    $entrydate = date('Y-m-d',$date2check);
		$pz = $photosize?$photosize:1;			
	                                                                                //,date1
        $sSQL = "select id,sysins,code1,pricepc,price2,sysins,itmname,itmfname,uniname1,uniname2,active,code4," .// from abcproducts";// .
	            "price0,price1,cat0,cat1,cat2,cat3,cat4,itmdescr,itmfdescr,itmremark,ypoloipo1,".$this->getmapf('code')." from products ";
		$sSQL .= " WHERE ";	
		$sSQL .= "sysins>='" . convert_date(trim($entrydate),"-DMY",1) . "' and ";
		
		if ($selected_item = GetReq('id')) 
		  $sSQL .= $this->getmapf('code') . " not like '" . $selected_item ."' and ";
		  		
		$sSQL .= "itmactive>0 and active>0";	
		$sSQL .= " ORDER BY id desc LIMIT " . $items;			
	    //echo $sSQL;
		
	    $resultset = $db->Execute($sSQL,2);	
		$this->result = $resultset;
		
		$xmax = $imgx?$imgx:100;
		$ymax = $imgy?$imgy:null;//free y 75;		
		
		if ($linemax>1)
		  $out = $this->list_katalog_table($linemax,$xmax,$ymax,$imageclick,0,null,$template,$ainfo,null,$external_read,$pz,$nopager);
		else  	
          $out = $this->list_katalog(null,null,$template,$ainfo,$external_read,$pz,$nopager,$linemax);
		  
		return ($out);	
	}	
	
	
	function show_offers($items=10,$days=12,$linemax=null,$imgx=100,$imgy=null,$imageclick=0,$template=null,$ainfo=null,$external_read=null,$photosize=null,$nopager=null) {
        $db = GetGlobal('db');
	    $lan = $lang?$lang:getlocal();
	    $itmname = $lan?'itmname':'itmfname';
	    $itmdescr = $lan?'itmdescr':'itmfdescr';				
		
	    $date2check = time() - ($days * 24 * 60 * 60);
	    $entrydate = date('Y-m-d',$date2check);
		$pz = $photosize?$photosize:1;			
	                                                                                //,date1
        $sSQL = "select id,sysins,code1,pricepc,price2,sysins,itmname,itmfname,uniname1,uniname2,active,code4," .// from abcproducts";// .
	            "price0,price1,cat0,cat1,cat2,cat3,cat4,itmdescr,itmfdescr,itmremark,ypoloipo1,".$this->getmapf('code')." from products ";
		$sSQL .= " WHERE ";	
		
		if ($selected_item = GetReq('id')) 
		  $sSQL .= $this->getmapf('code') . " not like '" . $selected_item ."' and ";
		  		
		$sSQL .= $this->getmapf('offer')."='".$this->toggler[1]."' and itmactive>0 and active>0";	
		$sSQL .= " ORDER BY $itmname asc LIMIT " . $items;			
	    //echo $sSQL;
		
	    $resultset = $db->Execute($sSQL,2);	
		$this->result = $resultset;
		
		$xmax = $imgx?$imgx:100;
		$ymax = $imgy?$imgy:null;// free y 75;		
		
		if ($linemax>1)
		  $out = $this->list_katalog_table($linemax,$xmax,$ymax,$imageclick,0,null,$template,$ainfo,null,$external_read,$pz,$nopager);
		else  	
          $out = $this->list_katalog(null,null,$template,$ainfo,$external_read,$pz,$nopager,$linemax);
		  
		return ($out);	
	}	
	
	function show_kategory_offers($category=null,$items=10,$linemax=null,$imgx=100,$imgy=null,$imageclick=0,$template=null,$ainfo=null,$external_read=null,$photosize=null,$nopager=null) {
        $db = GetGlobal('db');
	    $lan = $lang?$lang:getlocal();
	    $itmname = $lan?'itmname':'itmfname';
	    $itmdescr = $lan?'itmdescr':'itmfdescr';				
		$c = $category?$category:GetReq('cat');	//auto browse current cat
		$cat = explode('^',$c);		
	    $date2check = time() - ($days * 24 * 60 * 60);
	    $entrydate = date('Y-m-d',$date2check);		
		$pz = $photosize?$photosize:1;		
	                                                                                //,date1
        $sSQL = "select id,sysins,code1,pricepc,price2,sysins,itmname,itmfname,uniname1,uniname2,active,code4," .// from abcproducts";// .
	            "price0,price1,cat0,cat1,cat2,cat3,cat4,itmdescr,itmfdescr,itmremark,ypoloipo1,".$this->getmapf('code')." from products ";
		$sSQL .= " WHERE ";	
		foreach ($cat as $i=>$c) {
		  $myc = str_replace('_',' ',$c);
		  $sSQL .= " cat{$i}='$myc' and ";	
		}   
		
		if ($selected_item = GetReq('id')) 
		  $sSQL .= $this->getmapf('code') . " not like '" . $selected_item ."' and ";
		  		
		$sSQL .= $this->getmapf('offer')."='".$this->toggler[1]."' and itmactive>0 and active>0";	
		$sSQL .= " ORDER BY $itmname asc LIMIT " . $items;			
	    //echo $sSQL;
		
	    $resultset = $db->Execute($sSQL,2);	
		$this->result = $resultset;
		
		$xmax = $imgx?$imgx:100;
		$ymax = $imgy?$imgy:null;// free y 75;		
		
		if ($linemax>1)
		  $out = $this->list_katalog_table($linemax,$xmax,$ymax,$imageclick,0,null,$template,$ainfo,null,$external_read,$pz,$nopager);
		else  	
          $out = $this->list_katalog(null,null,$template,$ainfo,$external_read,$pz,$nopager,$linemax);
		  
		return ($out);	
	}	
	
	function show_kategory_items($category=null,$items=10,$linemax=null,$imgx=100,$imgy=null,$imageclick=0,$template=null,$ainfo=null,$external_read=null,$photosize=null,$nopager=null) {
        $db = GetGlobal('db');
	    $lan = $lang?$lang:getlocal();
	    $itmname = $lan?'itmname':'itmfname';
	    $itmdescr = $lan?'itmdescr':'itmfdescr';			
		$mycat = $category?$category:GetReq('cat');	//auto browse current cat
		$cat = explode('^',$mycat);		
		$pz = $photosize?$photosize:1;
				
		//auto browse current cat
		$fields = $this->result->fields; //prev query exclude cat		
	    //echo 'z'; print_r($fields);                                                                            //,date1
        $sSQL = "select id,sysins,code1,pricepc,price2,sysins,itmname,itmfname,uniname1,uniname2,active,code4," .// from abcproducts";// .
	            "price0,price1,cat0,cat1,cat2,cat3,cat4,itmdescr,itmfdescr,itmremark,ypoloipo1,".$this->getmapf('code')." from products ";
		$sSQL .= " WHERE ";	
		foreach ($cat as $i=>$c) {
		  $myc = str_replace('_',' ',$c);		
		  $sSQL .= " cat{$i}='$myc' and ";	
		}  
		
		if ($selected_item = GetReq('id')) 
		  $sSQL .= $this->getmapf('code') . " not like '" . $selected_item ."' and ";
		//MULTIPLE CHECKS  !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		if ($selected_cat0 = $fields['cat0']) 
		  $sSQL .= "cat0 not like '" . $selected_cat0 . "' and ";		
		if ($selected_cat1 = $fields['cat1']) 
		  $sSQL .= "cat2 not like '" . $selected_cat1 . "' and ";		  
		if ($selected_cat2 = $fields['cat2']) 
		  $sSQL .= "cat2 not like '" . $selected_cat2 . "' and ";		  
		  
		$sSQL .= "itmactive>0 and active>0";	
		$sSQL .= " ORDER BY $this->myorderby $this->myasc LIMIT " . $items;			
	    //echo $sSQL;
		
	    $resultset = $db->Execute($sSQL,2);	
		$this->result = $resultset;
		
		$xmax = $imgx?$imgx:100;
		$ymax = $imgy?$imgy:null; //free y 75;	
		
		if ($linemax>1) 
		  $out = $this->list_katalog_table($linemax,$xmax,$ymax,$imageclick,0,null,$template,$ainfo,null,$external_read,$pz,$nopager);
		else  	
          $out = $this->list_katalog(null,null,$template,$ainfo,$external_read,$pz,$nopager,$linemax); 
		  
		return ($out);	
	}
	
    function show_last_viewed_items($items=10,$linemax=null,$imgx=100,$imgy=null,$imageclick=0,$template=null,$ainfo=null,$external_read=null,$photosize=null,$nopager=null) {
        $db = GetGlobal('db');
        $UserName = GetGlobal('UserName');			
	    $lan = $lang?$lang:getlocal();
	    $itmname = $lan?'itmname':'itmfname';
	    $itmdescr = $lan?'itmdescr':'itmfdescr';				
		$c = $category?$category:GetReq('cat');	//auto browse current cat
		$cat = explode('^',$c);		
	    $date2check = time() - ($days * 24 * 60 * 60);
	    $entrydate = date('Y-m-d',$date2check);		
		$pz = $photosize?$photosize:1;
			
		
        $sSQL = "select products.id,sysins,code1,pricepc,price2,sysins,itmname,itmfname,uniname1,uniname2,active,code4,".
	            "price0,price1,cat0,cat1,cat2,cat3,cat4,itmdescr,itmfdescr,itmremark,ypoloipo1,".$this->getmapf('code').
				",stats.id,stats.tid from products, stats ";
		$sSQL .= " WHERE stats.tid=products.".$this->getmapf('code')." and products.itmactive>0 and products.active>0";				
		
		if ($UserName)
		  $sSQL .= " and (attr2='" . decode($UserName) . "' or attr2='". session_id() . "')";
		else  
		  $sSQL .= " and attr2='" . session_id() . "'";
		  
		$sSQL .= " GROUP BY stats.tid ORDER BY stats.id DESC limit " . $items;
		
		//echo $sSQL;	
	    $resultset = $db->Execute($sSQL,2);	
		//print_r($resultset);
		$this->result = $resultset;
		
		$xmax = $imgx?$imgx:100;
		$ymax = $imgy?$imgy:null;//free y 75;		
		
		//echo $nopager,'>',$template;

		if ($linemax>1)
		  $out = $this->list_katalog_table($linemax,$xmax,$ymax,$imageclick,0,null,$template,$ainfo,null,$external_read,$pz,$nopager);
		else  	
          $out = $this->list_katalog(null,null,$template,$ainfo,$external_read,$pz,$nopager,$linemax);
		  
		return ($out);				
	}	
	
	//set the order based on default conf params
	function get_order() {
	   $mylan = $lang?$lang:getlocal();	
	   $itmname = $mylan?'itmname':'itmfname';
	   $itmdescr = $mylan?'itmdescr':'itmfdescr';	
	
	   switch ($this->deforder) {
	   
	     case 1  : $ret = $this->getmapf('code'); break;
		 default : $ret = $itmname;
	   }
	   
	   return ($ret);
	}
	
	//set the asc based on default conf params
	function get_asc() {	
	
	   switch ($this->defasc) {
	     case -1 :  $ret = ' DESC '; break;
	     case 1  :
		 default : $ret = ' ASC ';
	   }
	   
	   return ($ret);
	}	
	
	//set ordersing online using <phpdac>
	function set_order($orderby=null,$asc=null) {
	   $lan = $lang?$lang:getlocal();
	   $itmname = $lan?'itmname':'itmfname';	
	
	   //order by
	   if ($orderby) {
		 $this->myorderby = $orderby?$this->getmapf('code'):$itmname;	   
	   }
	   else //reset
	     $this->myorderby = $itmname;
	   
	   //desc asc
	   if ($asc) {
		 $this->myasc =	$asc;
	   }
	   else //reset
	   	 $this->myasc =	'asc';   
		 
	   //echo '>',$this->myorderby,'+',$this->myasc,'<br>';	 
	}	
	
	function do_quick_search($text2find,$incategory=null) {
        $db = GetGlobal('db');	
		$page = GetReq('page')?GetReq('page'):0;
	    $asc = GetReq('asc')?GetReq('asc'):GetSessionParam('asc');
	    $order = GetReq('order')?GetReq('order'):GetSessionParam('order');
		$stype = GetParam('searchtype'); //echo $stype;
		$scase = GetParam('searchcase'); //echo $scase;
		$incategory = $incategory?$incategory:GetReq('cat');		
		
	    $lan = getlocal();
	    $itmname = $lan?'itmname':'itmfname';
	    $itmdescr = $lan?'itmdescr':'itmfdescr';						
	  
	    $dataerror = null;	
		
		/*if ($incategory) {
		  $cats = explode('^',$incategory);
		  foreach ($cats as $c=>$mycat) {
		  
		    $sql_incategory .= ' and cat'.$c ." ='" . $mycat . "'";
			//define sql which terms
		    if (defined("SHNSEARCH_DPC")) {	
              $sql_incategory .= GetGlobal('controller')->calldpc_method('shnsearch.findsql use '.$text2find.'+'.$this->getmapf('code').'<@>'.$itmname.'<@>'.$itmdescr.'<@>itmremark+and+'.$stype.'+'.$scase);
	        }	
		  }
		  //echo $sql_incategory;
		}
		echo '<br>';*/
		if ($text2find) {
		
		  $parts = explode(" ",$text2find);//get special words in text like code:  
	
	      $sSQL = "select id,sysins,code1,pricepc,price2,sysins,itmname,itmfname,uniname1,uniname2,active,code4," .// from abcproducts";// .
	            "price0,price1,cat0,cat1,cat2,cat3,cat4,itmdescr,itmfdescr,itmremark,ypoloipo1,".$this->getmapf('code')." from products ";
		  	
		  $sSQL .= " where ";
		  
		  switch ($parts[0]) {
		  
		    case 'code:' :  $sSQL .= " ( ".$this->getmapf('code')." like '%" . $this->decodeit($parts[1]) . "%')";
			                break;
		  
		  default://normal search
		  
		    if (defined("SHNSEARCH_DPC")) {
              $sSQL .= '('. GetGlobal('controller')->calldpc_method('shnsearch.findsql use '.$text2find.'+'.$this->getmapf('code').'<@>'.$itmname.'<@>'.$itmdescr.'<@>itmremark++'.$stype.'+'.$scase);		  
            }
			else { 			  	
	          $sSQL .= '(' . " ( $itmname like '%" . strtolower($text2find) . "%' or " .
		               " $itmname like '%" . strtoupper($text2find) . "%')";	
		      $sSQL .= " or ";		   
	          $sSQL .= " ( $itmdescr like '%" . strtolower($text2find) . "%' or " .
		               " $itmdescr like '%" . strtoupper($text2find) . "%')";				 
		      $sSQL .= " or ";		   
	          $sSQL .= " ( itmremark like '%" . strtolower($text2find) . "%' or " .
		               " itmremark like '%" . strtoupper($text2find) . "%')";				 					 
		      $sSQL .= " or ";		   			 
	          $sSQL .= " ( ".$this->getmapf('code')." like '%" . strtolower($text2find) . "%' or " .
		               " ".$this->getmapf('code')." like '%" . strtoupper($text2find) . "%')";						 
		    }			   
	   				 
		  }//switch....................................................					
		  $sSQL .= ')' ;
		  
          if ($incategory) {	
		    $cats = explode('^',$incategory);
		    foreach ($cats as $c=>$mycat)
		      $sSQL .= ' and cat'.$c ." ='" . $mycat . "'";		  	  
		  }
		   							  
		  $sSQL .= /*$sql_incategory . */ " and itmactive>0 and active>0";	
		  $search_sql = $sSQL;	
		  $sSQL .= ' ORDER BY';
		  
		  switch ($order) {
		    case 1  : $sSQL .= ' '.$itmname; break;
			case 2  : $sSQL .= ' price0';break;  
		    case 3  : $sSQL .= ' '.$this->getmapf('code'); break;//must be converted to the text equal????
			case 4  : $sSQL .= ' cat1';break;			
			case 5  : $sSQL .= ' cat2';break;
			case 6  : $sSQL .= ' cat3';break;			
			case 9  : $sSQL .= ' cat4';break;						
		    default : $sSQL .= ' '.$itmname;
		  }
		  
		  switch ($asc) {
		    case 1  : $sSQL .= ' ASC'; break;
			case 2  : $sSQL .= ' DESC';break;
		    default : $sSQL .= ' ASC';
		  }
		  
		  //echo $sSQL;
		  
		  //LIMITED SEARCH
		  if ($this->pager) {
		    $p = $page * $this->pager;
		    $sSQL .= " LIMIT $p,".$this->pager; //page element count
		  }
		  	
		  if ($dataerror==null) {
		    //echo $sSQL;		  
	        $resultset = $db->Execute($sSQL,2); 
	   	    //print_r($resultset);
	        $this->result = $resultset; 
			//print_r($this->result);
			$this->meter = $db->Affected_Rows();
			$this->max_items = $db->Affected_Rows();
	        $this->max_selection = $this->get_max_result($text2find);	
			//$this->msg = '<hr>' . $this->max_selection . ' ' . localize('_founded',getlocal());
			//echo $this->msg,'>';																		
	      }
		  else {
		    $this->msg = $dataerror;		
			//echo $dataerror;
		  }	
	   	}
	}	
	
		
	function alphabetical($command='klist') {
	
	  $preparam = GetReq('alpha');
	  $cat = GetParam('cat');
	  
	  $ret .= seturl("t=$command&cat=$cat","Αρχή") . "&nbsp;|";
	
	  for ($c=$preparam.'a';$c<$preparam.'z';$c++) {
	    $ret .= seturl("t=$command&cat=$cat&alpha=$c",$c) . "&nbsp;|";
	    //$ret .= seturl("t=$command&cat=$cat&alpha=$c",strtoupper(chr(ord($c)+128))) . "&nbsp;|";
	  }
	  //the last z !!!!!
	  $ret .= seturl("t=$command&cat=$cat&alpha=".$preparam."z",$preparam."z");
	  
      //$mywin = new window('',$ret);
      //$out = $mywin->render();	  
	  
	  return ($ret);
	}	
	
	function printlink($icon=null) {
	
			 //print option
             if (iniload('JAVASCRIPT')) {	
  	            $plink = "<A href=\"" . /*seturl("") .*/ "#\"";	   
	            //call javascript for opening a new browser win for the img		   
	            $params = seturl("t=kprint") . ";Print;scrollbars=yes,width=680,height=580;";

				$js = new jscript;
	            $plink .= GetGlobal('controller')->calldpc_method('javascript.JS_function use js_openwin+'.$params);
				          //comma values includes at params ?????
				          //$js->JS_function("js_openwin",$params); 
                unset ($js);

	            $plink .= ">"; 	
			}
	        else
                $plink = "<A href=\"" . seturl("t=kprint") . ">";	
				
			if ($icon)				
			  return ($plink . $icon . "</A>");	
			else	
			  return ($plink . localize('_PRINT',getlocal()) . "</A>");	
	}
	
    function printitem($data) {
	
        if (iniload('JAVASCRIPT')) {	
		  //$js = new jscript;
	      $bclose = GetGlobal('controller')->calldpc_method('javascript.JS_function use js_closewin+'.localize('_CLOSE',getlocal()));
		            //$js->JS_function("js_closewin",localize('_CLOSE',getlocal())); 
	      $bprint = GetGlobal('controller')->calldpc_method('javascript.JS_function use js_printwin+'.localize('_PRINT',getlocal()));
		            //$js->JS_function("js_printwin",localize('_PRINT',getlocal()));									 
          //unset ($js);
	   	  $data.= '<br>' . $bclose . '&nbsp;' . $bprint;
		}
	    $headtitle = paramload('SHELL','urltitle');			
		$printpage = new phtml('style.css',$data,"<B><h1>$headtitle</h1></B>");
		$out = $printpage->render();
		unset($printpage);

		return ($out);
	}	
	
	function make_table_to_show($dataarray,$header=1) {
	
	  $i=0;
	  foreach ($dataarray as $title=>$value) {
	  
	    if ($i<$header) {
		  $head .= "<b>" . '&nbsp;' . $value . "<b>";
		}
		else {
		  
	      $viewdata[] = $title . ':';
	      $viewattr[] = "left;35%";
		   
	      $viewdata[] = $value;
	      $viewattr[] = "right;60%";	
		  
	      //$viewdata[] = '&nbsp;';//dummy
	      //$viewattr[] = "left;5%";			  	
		  
	      $myline = new window('',$viewdata,$viewattr);
	      $body .= $myline->render("center::100%::1::group_article_high::left::2::2::");
	      unset ($viewdata); unset($viewattr);		  		   		
		  
		} 
		$i++;
	  }
	  
	  $mytitle = new window($head,'');
	  $out = $mytitle->render("center::100%::0::group_article_high::left::0::0::");
	  
	  $out .= $body;
	  
	  return ($out);
	  
	}		
	
	function set_anchor($name) {
	
	  $ret = "<a name=\"$name\"></a>";
	  return ($ret);
	}	
	
	function getmapf($name) {
	  $ch = null;
	
	  if (empty($this->map_t)) return 0;	
	  
	  foreach ($this->map_t as $id=>$elm) {	    
	    if ($elm==$name) {
		  $ch = $id;
		  break;
		}  
	  }			
	  //$id = key($this->map_t[$name]) ;
	  $ret = $this->map_f[$ch];
	  return ($ret);
	}		
		
	function read_policy($leeid=null) {
	   //$db = GetGlobal('db');
	   $reseller = GetSessionParam('RESELLER');
		 
	   if ($this->is_reseller)
	     $v = $this->pprice[0];
	   else
   	     $v = $this->pprice[1];
		 
	   
	   return ($v);
	}	
	
	//multiple prices based on file array
	function read_array_policy($itemcode=null,$price=null,$cart_details=null,$policyqty=null) {
	  $cat = $pcat?$pcat:GetReq('cat');
	  $cart_page = GetReq('page')?GetReq('page'):0;	  	
	  $file = $this->path . $itemcode . '.txt'; //echo $file;
	  $cartd = explode(';',$cart_details);
	
	  if (is_readable($file)) {
	
	    $data_array = parse_ini_file($file,1);
	    //print_r($data_array);
		if ($policyqty) {
		  if (is_array($data_array['QTY'])) {
		    foreach ($data_array['QTY'] as $ix=>$ax) {
			  if ($policyqty>=$ax) {
			    //echo $ax,'>';
			    $pc = intval($data_array['PRICE'][$ix]);
			    $retprice = $price?$price+($price*$pc/100):$pc;
				//print_r($data_arra
			  }
			}
			return ($retprice);
		  }
		}
		else {
		  $style = $data_array['style'];
		  $titlesON = $data_array['titles'];
		  $elements = $titlesON?1:0;	
		  $template = $data_array['template']?$data_array['template']:'fpitempolicy';
		  
	      //loop template 
	      $loop_template= $template.".htm";
	      $tpl = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$loop_template) ;
	      if (is_readable($tpl)) {//file temaplate
		    $mylooptemplate = file_get_contents($tpl);
			
			foreach ($data_array['PRICE'] as $ix=>$ax) {
			  $data[] = $data_array['QTY'][$ix];
	 
			  $cartd[8] = $price?$price+($price*$ax/100):$ax;//'22.23';
			  $cartd[9] = intval($data_array['QTY'][$ix]);//prev line //'12';
			  
		      $data[] =	number_format($cartd[8],$this->decimals,',','.');		  
			  
			  $cartout = implode(';',$cartd);
			  $data[] = GetGlobal('controller')->calldpc_method("shcart.showsymbol use $cartout;+$cat+$cart_page+0+".$cartd[9],1);
			 
			  $body .= $this->combine_tokens($mylooptemplate,$data);	
			  //echo $body,'>';
			  unset($data);			  
			}		
	      }			  	  
          else { //standart template 
		  if (is_array($data_array['QTY'])) {
			$percent = intval(100/(count($data_array['QTY'])+$elements)); //echo $percent;	
			if ($titlesON) {				  
		      $viewdata[] = localize('_QTY',getlocal());//$i;
		      $viewattr[] = "right;$percent%";		  
			}
		    foreach ($data_array['QTY'] as $ix=>$ax) {
				
		      $viewdata[] = $ax;
		      $viewattr[] = "right;$percent%";
			} 
	        $myline = new window('',$viewdata,$viewattr);
	        $body = $myline->render($style);
	        unset ($viewdata); unset($viewattr);				 
		  }		
		
		  if (is_array($data_array['PRICE'])) {
			$percent = intval(100/(count($data_array['PRICE'])+$elements)); //echo $percent;
			if ($titlesON) {		  
		      $viewdata[] = localize('_PRICE',getlocal());//$i;
		      $viewattr[] = "right;$percent%";		  
			}
		    foreach ($data_array['PRICE'] as $ix=>$ax) {
			    
			    $cartd[8] = $price?$price+($price*$ax/100):$ax;//'22.23';
				$cartd[9] = intval($data_array['QTY'][$ix]);//prev line //'12';
				//echo $cartd[9],'>';
				$cartout = implode(';',$cartd);
			    $icon_cart = GetGlobal('controller')->calldpc_method("shcart.showsymbol use $cartout;+$cat+$cart_page+0+".$cartd[9],1);

			    $val = number_format($cartd[8],$this->decimals,',','.');

		        $viewdata[] = /*'<del>'.$price.'</del><br/>'.*/$val . $icon_cart;
		        $viewattr[] = "right;$percent%";
			} 
	        $myline = new window('',$viewdata,$viewattr);
	        $body .= $myline->render($style);
	        unset ($viewdata); unset($viewattr);				 
		  }
		  }//template
        }
		//echo $body;
		return ($body);  
	  }	
	}

	function show_availability($qty) {
	
	       if (!$this->availability) return;
		   	
	       $GRX = GetGlobal('GRX');			   
		   $scale_colors = (array)$this->availability_colors; //print_r($scale_colors);
		   $scale_remark = (array)$this->availability_remark;
		   $scale = (array)$this->availability;
		   $size = count($scale)*15;
		   
           $r_scale = array_reverse($scale,1);
           //print_r($r_scale);
		   $x=0;		   
		   foreach($r_scale as $i=>$sc) {

			 if ($qty >= $sc) {
			   $x = $i; 
			   break;
			 }  
			 
		   }	 
			 
		   //echo $x;
		   if ($GRX) {
		     $remark = $scale_remark[$x];
		     $this->avail_point  = loadTheme('apoint'.$x,$remark3);
		   }	 
		   
		   $ret .= "<font color='#".$scale_colors[$x]."'>";
		   for ($z=0;$z<=$x;$z++) {
		     $ret .= $this->avail_point;//'[-]';//'&#8218;';
		   }
		   //$ret .= ($qty?$qty:strval(0));
		   $ret .= "</font>";// . $qty . '>>'.$x;
		   
		   
	       $myav = new window('',$ret);
	       $out = $myav->render("center::$size::0::group_win_body::left::0::0::");
		   
		   return ($out); 	
	}
	
	function show_availabillity_table($title=null,$plaisio=null) {
	
	       if (!$this->availability) return;
	
	       $GRX = GetGlobal('GRX');
		   $scale_colors = (array)$this->availability_colors; //print_r($scale_colors);
		   $scale_remark = (array)$this->availability_remark;
		   $scale = (array)$this->availability;
		   $size = count($scale)*15;
		   //$title = localize('_availabillity',getlocal());
		   
           $r_scale = array_reverse($scale,1);
           
		   //$x=0;		    
			 
		   //echo $x;
		   if ($GRX) {
		     $remark = $scale_remark[$i];
		     $this->avail_point  = loadTheme('apoint'.$i);//),$remark3);
		   }
		   
		   foreach($r_scale as $i=>$sc) {
             $this->avail_point  = loadTheme('apoint'.$i);//),$remark3);		   
		   
		     $ss = null;
		     $ss .= "<font color='#".$scale_colors[$i]."'>";
	
		     for ($z=0;$z<=$i;$z++) {
		       $ss .= $this->avail_point;//'[-]';//'&#8218;';
		     }
		     //$ret .= ($qty?$qty:strval(0));
		     $ss .= "</font>";
			 
	         $myline = new window('',$ss);
	         $line = $myline->render("center::100%::0::group_win_body::left::0::0::");
		     unset($myline);			 
			 
			 $data[] = $line;
	         $attr[] = "left;30%";
				 
			 $data[] = "&nbsp;" . $scale_remark[$i];;
	         $attr[] = "left;70%";				 			 
		   
	         $myav = new window('',$data,$attr);
	         $lines .= $myav->render("center::100%::0::group_article_selected::left::0::0::");
		     unset($myav);
			 
			 $data = null;
			 $attr = null;			 
		   }
		   
	       $myav = new window($title,$lines);
		   if ($plaisio)
	         $out = $myav->render("center::100%::0::group_win_body::left::2::2::");
		   else
		     $out = $myav->render("center::100%::0::group_article_selected::left::2::2::");	 
		   unset($myav);		   			   
		   
		   return ($out); 			   		   		
	}	
	
	function pricewithtax($price,$tax=null) {
	
	  //echo $price;
	
	  if (defined('SHCART_DPC')) {
		  //select based on customer type
		  $tax = GetGlobal('controller')->calldpc_var('shcart.tax'); //echo $tax;
          $mytax = (($price*$tax)/100);	
	      $value = ($price+$mytax);		  
		  //echo $mytax,':',$value,':',$price,'<br>';
	  }
	  elseif ($tax) {
          $mytax = (($price*$tax)/100);	
	      $value = ($price+$mytax);		  
	  }
	  else
	     $value = $price;
	
	  
	  return ($value);
	}	
	
	//select price type
	function spt($price,$tax=null) {
      //echo $tax;
	  
	  if ($tax) {
        $p = $this->pricewithtax($price,$tax);	  
	  }
	  elseif ($this->is_reseller) {
	    $p = $price;
	  }	
	  elseif (GetGlobal('controller')->calldpc_var('shcart.showtaxretail')) {//retal handl
	    $p = $this->pricewithtax($price,$tax);
	  }
	  else
	    $p = $price;		
	  //echo '>',$p;
	  return ($p);
	}
	
	//used by shcart to reupdate prices in login
	function update_prices($cartitems) {
	   $db = GetGlobal('db');
	   $p_ret = null;
	   
	   $items = unserialize($cartitems);
	   $pfield = $this->read_policy();
	   
	   if (is_array($items)) {
	    //print_r($items);
        foreach ($items as $prod_id => $product) {
		  if (($product) && ($product!='x')) {	   
		  
            $param = explode(";",$product);		  
	   
            $sSQL = "select ".$pfield." from products ";
	        $sSQL .= " WHERE ".$this->getmapf('code')."='".$param[0]."'";	   	   
		    //echo $sSQL,'<br>';
		    $result = $db->Execute($sSQL,2);	
			
			$p_ret[$param[0]] = $this->spt($result->fields[0]);
		  }
		}
		return ($p_ret);  
	   }
	   else
	     return null;
	
	}	
	
	function replace_spchars($string) {
	
	  $r1 = str_replace('"',"'",$string);
	  $r2 = str_replace('+',"plus",$r1);	  
	  //$r3 = str_replace(')',"-",$r2);
	  
	  return ($r2);
	}	
	
	function combine_template($template_contents,$p0=null,$p1=null,$p2=null,$p3=null,$p4=null,$p5=null,$p6=null,$p7=null,$p8=null,$p9=null) {
	
		$params = explode('<#>',"$p0<#>$p1<#>$p2<#>$p3<#>$p4<#>$p5<#>$p6<#>$p7<#>$p8<#>$p9");
	    //print_r($params);
		
		if (defined('FRONTHTMLPAGE_DPC')) {
		  $fp = new fronthtmlpage(null);
		  $ret = $fp->process_commands($template_contents);
		  unset ($fp);
          //$ret = GetGlobal('controller')->calldpc_method("fronthtmlpage.process_commands use ".$template_contents);		  		
		}		  		
		else
		  $ret = $template_contents;
		//echo $ret;
	    foreach ($params as $p=>$pp) {
		  if ($pp)
	        $ret = str_replace("$".$p,$pp,$ret);
		  else	
		    $ret = str_replace("$".$p,'',$ret);
	    }
		//echo $ret;
		return ($ret);
	}				
	
};			  
}
?>