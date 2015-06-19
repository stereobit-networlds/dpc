<?php
$__DPCSEC['SHCATALOG_DPC']='1;1;1;1;1;1;2;2;9';
$__DPCSEC['SHCATALOG_CART']='1;1;1;1;1;1;2;2;9';

if ( (!defined("SHCATALOG_DPC")) && (seclevel('SHCATALOG_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHCATALOG_DPC",true);

$__DPC['SHCATALOG_DPC'] = 'shcatalog';

$d = GetGlobal('controller')->require_dpc('shop/shcategories.dpc.php');
require_once($d);

$__EVENTS['SHCATALOG_DPC'][0]='katalog';
$__EVENTS['SHCATALOG_DPC'][1]='klist';
$__EVENTS['SHCATALOG_DPC'][2]='kshow';
$__EVENTS['SHCATALOG_DPC'][3]='knext';
$__EVENTS['SHCATALOG_DPC'][4]='kprev';
$__EVENTS['SHCATALOG_DPC'][5]='kprint';
$__EVENTS['SHCATALOG_DPC'][6]='addtocart';     //continue with ..cart
$__EVENTS['SHCATALOG_DPC'][7]='removefromcart';//continue with ..cart
$__EVENTS['SHCATALOG_DPC'][8]='searchtopic';   //continue with ..browser
$__EVENTS['SHCATALOG_DPC'][9]='lastentries';
$__EVENTS['SHCATALOG_DPC'][10]='openfolder';//navigate tree

$__ACTIONS['SHCATALOG_DPC'][0]='katalog';
$__ACTIONS['SHCATALOG_DPC'][1]='klist';
$__ACTIONS['SHCATALOG_DPC'][2]='kshow';
$__ACTIONS['SHCATALOG_DPC'][3]='knext';
$__ACTIONS['SHCATALOG_DPC'][4]='kprev';
$__ACTIONS['SHCATALOG_DPC'][5]='kprint';
$__ACTIONS['SHCATALOG_DPC'][6]='addtocart';     //continue with ..from cart
$__ACTIONS['SHCATALOG_DPC'][7]='removefromcart';//continue with ..from cart
$__ACTIONS['SHCATALOG_DPC'][8]='searchtopic';   //continue with ..from browser
$__ACTIONS['SHCATALOG_DPC'][9]='lastentries';
$__ACTIONS['SHCATALOG_DPC'][10]='openfolder';//use it to navigate tree
//$__ACTIONS['SHCATALOG_DPC'][10]='index';//dummy for index.php purposes

$__LOCALE['SHCATALOG_DPC'][0]='SHCATALOG_DPC;Catalogue;Καταλογος';
$__LOCALE['SHCATALOG_DPC'][1]='_code;Code;Κωδικός';
$__LOCALE['SHCATALOG_DPC'][2]='_descr;Description;Περιγραφή';
$__LOCALE['SHCATALOG_DPC'][3]='_axia;Cost;Τιμή';
$__LOCALE['SHCATALOG_DPC'][4]='_uniname1;MM;ΜΜ';
$__LOCALE['SHCATALOG_DPC'][5]='_order;Order by:;Ταξινόμηση:';
$__LOCALE['SHCATALOG_DPC'][6]='_item;Item;Τίτλος';
$__LOCALE['SHCATALOG_DPC'][7]='_cat1;Detail 1;Χαρακτηριστικό 1';
$__LOCALE['SHCATALOG_DPC'][8]='_next;Next;Επόμενο';
$__LOCALE['SHCATALOG_DPC'][9]='_prev;Previous;Προηγούμενο';
$__LOCALE['SHCATALOG_DPC'][10]='_offers;Offers;Προσφορές';
$__LOCALE['SHCATALOG_DPC'][11]='_lastitems;New arrivals;Νέες αφίξεις';
$__LOCALE['SHCATALOG_DPC'][12]='_gallery;Additional files;Συνημένα αρχεία';

class shcatalog extends shcategories {

    var $max_items, $result, $path, $urlpath, $direction, $inpath;
	var $map_t, $map_f;	
	var $pprice;	
    var $userLevelID;
	var $is_reseller, $buttons_OFF,$htmlpath;	

	  
	function shcatalog() {
	  $UserSecID = GetGlobal('UserSecID');		
	
	  shcategories::shcategories();
	  
      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);		  
	
	  $this->title = localize('SHCATALOG_DPC',getlocal());	
	  $this->msg = null;
	  $this->post = null;		  
	  $this->path = paramload('SHELL','prpath');	
	  $this->urlpath = paramload('SHELL','urlpath');
	  $this->inpath = paramload('ID','hostinpath');	  		  
	  $this->result = null;
	  
	  $this->imgpath = $this->inpath . '/images/uphotos/';  	  
	  $this->thubpath = $this->inpath . '/images/thub/';	   
	  $this->restype = '.jpg';
	  
	  $this->htmlpath = $this->urlpath . $this->inpath . '/cp/html/'; 	  
	  
	  $this->pager = 10;//paramload('SHCATALOG','pager'); //10;//null;//6;
	  $this->zeroprice_msg = paramload('SHCATALOG','zeroprice');	
	  
	  $this->map_t = arrayload('SHCATALOG','maptitle');	
	  $this->map_f = arrayload('SHCATALOG','mapfields');	
	  
	  $this->buttons_OFF = remote_arrayload('SHKATALOG','buttonsOFF',$this->path);	  
	  
	  $this->pprice = remote_arrayload('SHCATALOG','pricepolicy',$this->path);
	  if (empty($this->pprice)){//default
	    $this->pprice[0]='price0';
		$this->pprice[1]='price1';
	  }	 
	  
	  $this->is_reseller = GetSessionParam('RESELLER');	 	    	      
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
								 		
		  case 'klist'        : //$this->read_list(); moved inside func
		                        GetGlobal('controller')->calldpc_method("rcvstats.update_category_statistics use ".GetReq('cat'));		  
		                        break;
		  case "knext"        : $this->direction = GetReq('direction');
		  case "kprev"        :	$this->direction = GetReq('direction');	  
		  case 'kshow'        : $this->read_item($this->direction); 
		  		                //update statistics
	                            GetGlobal('controller')->calldpc_method("rcvstats.update_item_statistics use ".GetReq('id'));		  
	   	  case 'lastentries'  : break;
		                        break;		  
          case 'katalog'      ://$this->read_list(); //moved inside func
		  default             :	
                                //update statistics
	                            GetGlobal('controller')->calldpc_method("rcvstats.update_item_statistics use ".GetReq('id'));		  							  
        }	
	}
	
	function action($action=null) {
	
		if (GetReq('cat')) 
		  $out = $this->tree_navigation('klist','',1);  
		else
		  $out .= setNavigator($this->title,localize('_item',getlocal()));	
		    		
	  
	    switch ($action) {

		  case 'klist'        : //$out .= $this->list_katalog_table(2,null,null,0,1); break;
		                     	//$out .= $this->show_sub_tree('klist');
								
								//$out .= $this->show_categories('klist',1,1,1);
								//$out .= $this->show_subcategories('klist',GetReq('cat'));
		                        $out .= $this->list_katalog(0); break;
		  case "knext"        :
		  case "kprev"        :		  
		  case 'kshow'        : $out .= $this->show_item();break;		  
				
		 
          case 'lastentries'  : $out .= $this->show_lastentries(20,12,2,160,120);		 
	    	                    break; 
		
		//cart
	     case 'searchtopic'   :
	     case 'addtocart'     :
		 case 'removefromcart':
          case 'katalog'      :
		  default             :	//$out .= $this->show_lastentries(20,12,2,160,120);							  
		                        //$out .= $this->show_sub_tree('klist');
								
								//$out .= $this->show_categories('klist',1,1,1);
								//$out .= $this->show_subcategories('klist',GetReq('cat'));
		                        //$out .= $this->list_katalog();
								//$out .= $this->show_offers(20,12,2,160,120);
								//if (!GetReq('t'))
	                              $out .= $this->list_katalog(0); 	                       
								//else let last entries and offers to show up build in web page  
        }	  
	  
	    return ($out);
	}	
	
	function read_list() {
        $db = GetGlobal('db');	
		$order = GetReq('order');
		$asc = GetReq('asc');
		$page = GetReq('page')?GetReq('page'):0;

		if (GetReq('cat')!=null)
		  $cat = GetReq('cat');			
		
		if ($cat!=null) {		   
	   
	      $sSQL = "select id,sysins,code1,pricepc,price2,sysins,itmname,itmfname,uniname1,uniname2,active,code4," .// from abcproducts";// .
	              "price0,price1,cat0,cat1,cat2,cat3,cat4,itmdescr,itmfdescr,itmremark,".$this->getmapf('code')." from products ";
		  $sSQL .= " WHERE ";
		  
		  $cat_tree = explode('^',str_replace('_',' ',$cat)); 
			
           //$whereClause .= '( cat0=' . $db->qstr(str_replace('_',' ',$cat_tree[0]));		  
		   if ($cat_tree[0])
			    $whereClause .= ' cat0=' . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[0])));		  
		   if ($cat_tree[1])	
		 	    $whereClause .= 'and cat1=' . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[1])));		 
		   if ($cat_tree[2])	
			    $whereClause .= 'and cat2=' . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[2])));		   
		   if ($cat_tree[3])	
			    $whereClause .= 'and cat3=' . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[3])));
		   if ($cat_tree[4])	
			    $whereClause .= 'and cat4=' . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[4])));
			
		  $sSQL .= $whereClause;				  
		  	  		  
	   	 
		  $sSQL .= " and itmactive>0 and active>0";			 
				  
		  $sSQL .= ' ORDER BY';
		  
		  switch ($order) {
		    case 1  : $sSQL .= ' itmname'; break;
			case 2  : $sSQL .= ' price0';break;  
		    case 3  : $sSQL .= ' '.$this->getmapf('code'); break;//must be converted to the text equal????
			case 4  : $sSQL .= ' cat1';break;			
			case 5  : $sSQL .= ' cat2';break;
			case 6  : $sSQL .= ' cat3';break;			
			case 9  : $sSQL .= ' cat4';break;						
		    default : $sSQL .= ' itmname';//.$this->getmapf('code');
		  }
		  
		  switch ($asc) {
		    case 1  : $sSQL .= ' ASC'; break;
			case 2  : $sSQL .= ' DESC';break;
		    default : $sSQL .= ' ASC';
		  }
		  
		  if ($this->pager) {
		    $p = $page * $this->pager;
		    $sSQL .= " LIMIT $p,".$this->pager; //page element count
		  }
			
	      //echo $sSQL;
	   
	      $resultset = $db->Execute($sSQL,2);
	      //$ret = $db->fetch_array_all($resultset);	 
	   	 // print_r($resultset);  
	      $this->result = $resultset; 

 	      $this->max_items = $db->Affected_Rows();//count($this->result);
	      //echo '>',$this->max_items;
	   
		}	
		
	}
	
	function show_asceding($cmd=null) {
	   $cat = rawurlencode(GetReq('cat'));//encoded????
	   $asc = GetReq('asc');
	   $order = GetReq('order');	
	   $t = GetReq('t');   
	   $cmd=$cmd?$cmd:'klist';
	
	   $characteristics = localize('_order',getlocal());
	   $characteristics .= seturl('t='.$cmd.'&cat='.$cat.'&asc='.$asc.'&order=1',localize('_item',getlocal())). "&nbsp;|&nbsp;";
	   $characteristics .= seturl('t='.$cmd.'&cat='.$cat.'&asc='.$asc.'&order=2',localize('_axia',getlocal())) . "&nbsp;|&nbsp;";
	   $characteristics .= seturl('t='.$cmd.'&cat='.$cat.'&asc='.$asc.'&order=3',localize('_code',getlocal())). "&nbsp;|&nbsp;";
	   /*$characteristics .= seturl('t='.$cmd.'&cat='.$cat.'&asc='.$asc.'&order=4',localize('_cat1',getlocal())). "&nbsp;|&nbsp;";
	   $characteristics .= seturl('t='.$cmd.'&cat='.$cat.'&asc='.$asc.'&order=5',localize('_cat2',getlocal())). "&nbsp;|&nbsp;";	   	   	   	   
	   $characteristics .= seturl('t='.$cmd.'&cat='.$cat.'&asc='.$asc.'&order=6',localize('_cat3',getlocal())). "&nbsp;|&nbsp;";	   
	   $characteristics .= seturl('t='.$cmd.'&cat='.$cat.'&asc='.$asc.'&order=9',localize('_cat4',getlocal()));	   
	   */
	   $asceding = seturl('t='.$cmd.'&cat='.$cat.'&asc=1'.'&order='.$order,'A..Z') . ' / '.
	               seturl('t='.$cmd.'&cat='.$cat.'&asc=2'.'&order='.$order,'Z..A');
				   
	   $viewdata[] = $characteristics;
	   $viewattr[] = "left;80%";				   
	   
	   $viewdata[] = $asceding;
	   $viewattr[] = "right;20%";	
	   
	   $mywin = new window('',$viewdata,$viewattr);
	   $out = $mywin->render("center::100%::0::group_article_selected::left::5::5::");
	   unset ($viewdata);
	   unset ($viewattr);	
	   
	   return ($out);	      
	}
	
	function show_paging($pagecmd=null) {
	   $cat = rawurlencode(GetReq('cat'));
	   $asc = GetReq('asc');
	   $order = GetReq('order');	
	   $t = GetReq('t'); 	
	   $page = GetReq('page')?GetReq('page'):0;
	   
	   $pcmd = $pagecmd?$pagecmd:'klist';
		  
 	   //$max = $db->Affected_Rows();//count($this->result);
	   //echo '>',$max;
	   
	   if ($this->max_items==$this->pager) {
	     $page_next = $page + 1;
	     $next = seturl('t='.$pcmd.'&cat='.$cat.'&asc='.$asc.'&order='.$order.'&page='.$page_next,localize('_next',getlocal()));
	   }
	    
	   if ($page>0) {
	     $page_prev = $page - 1;
	     $prev = seturl('t='.$pcmd.'&cat='.$cat.'&asc='.$asc.'&order='.$order.'&page='.$page_prev,localize('_prev',getlocal()));
		 if ($next) $prev .= " | ";
	   }	 
	   
	   $page_titles = '<h3>'. $prev . $next .'</h3>';	   
	   
	   $mywin = new window('',$page_titles);
	   $ret = $mywin->render("center::100%::0::group_article_selected::right::5::5::");	   
	   
	   return ($ret);
	}
	
	function get_item_url($code,$cat=null) {
	
	  $pfile = "index.php?t=kshow&cat=".$cat.'&id='.$code;//seturl('t=kshow&cat='.$cat.'&id='.$code);
      $ret = $pfile;
	  
	  return ($ret);		
	}
	
	function get_photo_url($code) {
	
	   $pfile = $code;//sprintf("%05s",$code); //echo $pfile,"<br>";
	   $photo_file = $this->urlpath . '/' .$this->thubpath . $pfile . $this->restype;	  
	   //echo $photo_file,'<br>'; 
	   if (!file_exists($photo_file))
	     $photo = $this->thubpath . 'nopic' . $this->restype;	
	   else
	     $photo = $this->thubpath . $pfile . $this->restype;	
		 
	   return ($photo);	 	
	}
	
	function list_photo($code,$x=100,$y=75,$imageclick=1,$mycat=null) {
	   $page = GetReq('page')?GetReq('page'):0;		
	   $cat = GetReq('cat');	
           if (!$cat) $cat = $mycat;
	
	   $photo = $this->get_photo_url($code);
			 
	   if ($imageclick) {	   
		   
           if (iniload('JAVASCRIPT')) {	
  	         $plink = "<a href=\"#\" ";	   
	         //call javascript for opening a new browser win for the img		   
	         //$params = $photo . ";Image;width=300,height=200;";
	         $params = "$photo;400;300;<B>$title</B><br>$descr;";			 

			 $js = new jscript;
	         $plink .= $js->JS_function("js_popimage",$params); 
			 unset ($js); 

	         $plink .= ">";
	       }
	       else
             $plink = "<A href=\"$photo\">";

	       $ret = $plink . "<img src=\"" . $photo . "\" width=\"$x\" height=\"$y\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";
		}
		else   
		  $ret = seturl('t=kshow&cat='.$cat.'&id='.$code.'&page='.$page,"<img src=\"" . $photo . "\" width=\"$x\" height=\"$y\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">");// . "</A>";
	
	    return ($ret);
	}
	
	function list_katalog($imageclick=null,$cmd=null,$template=null) {
	   $cmd = $cmd?$cmd:'klist';
	   $cat = GetReq('cat');
       if (!$cat) $createcats = 1; else $createcats = 0;
		   
	   $lan = getlocal();
	   $itmname = $lan?'itmname':'itmfname';
	   $itmdescr = $lan?'itmdescr':'itmfdescr';			   
		   
	   $linemax = remote_paramload('SHCATALOG','itemsperline',$this->path); 	   
		   
	   $xdist = (remote_paramload('SHCATALOG','imagex',$this->path)?remote_paramload('SHCATALOG','imagex',$this->path):100);
	   $ydist = (remote_paramload('SHCATALOG','imagey',$this->path)?remote_paramload('SHCATALOG','imagey',$this->path):75);		   
	   if (remote_paramload('SHCATALOG','imageclick',$this->path)>0)
	     $myimageclick = 1;
	   else	 
	     $myimageclick = $imageclick;
		   
	   $template='fpkatalog.htm';	   
	   $t = $this->urlpath .'/' . $this->inpath . '/'. str_replace('.',getlocal().'.',$template) ; 
	   //echo $t,'>';
	   if (($template) && is_readable($t)) {
	     //echo $template,'>';
		 $mytemplate = file_get_contents($t);
		 //$html = $this->combine_template($template,'a','b','c','d','e');
		 //echo $html;
	   }		   	   

       $this->read_list(); 	//read here to allow also las entries and offers to show in line	   
		
	   if ($this->msg) $out = $this->msg;
	   
	   $out .= $this->show_asceding($cmd);

	   if (count($this->result)>0) {	
	   
		$pp = $this->read_policy();	   

	    //$max  = count($this->result[0])-1;
	    //$prc = 96/$max;		
	
	    foreach ($this->result as $n=>$rec) {

           if ($createcats) {
             $cat = $this->getkategories($rec);
           }		


		   if ($rec[$pp]>0) { 
		     $myp = $this->spt($rec[$pp]);
		     $price = ($myp?number_format($myp,2,',','.'):"&nbsp;");
		   }	
		   else	 
		     $price = $this->zeroprice_msg;				   
				   
		   if ((GetGlobal('UserID')) || (seclevel('SHCATALOG_CART',$this->userLevelID))) {//logged in or sec ok
		     $cart_code = $rec[$this->getmapf('code')];
			 $cart_title = $rec[$itmname];
			 $cart_group = $cat;
			 $cart_page = GetReq('page')?GetReq('page'):0;
			 $cart_descr = $rec[$itmdescr];
			 $cart_photo = $this->get_photo_url($rec[$this->getmapf('code')]);
			 $cart_price = $price;
			 $cart_qty = 1;//???
		     $cart = GetGlobal('controller')->calldpc_method("shcart.showsymbol use $cart_code;$cart_title;$path;$MYtemplate;$cart_group;$cart_page;$cart_descr;$cart_photo;$cart_price;$cart_qty;+$cat+$cart_page",1);//'cart';
		   }
		   
		   if ($mytemplate) {
		      
			  if ($linemax>1) //table view
		      $items[] = $this->combine_template($mytemplate,
			                                     $rec[$itmname],
			                                     $rec[$itmdescr],
			                                     $this->list_photo($rec[$this->getmapf('code')],$xdist,$ydist,$myimageclick,$cat),
			                                     $rec['uniname1'] , 
												 $rec[$this->getmapf('code')], 
												 $price,
												 $cart);
			  else									 			   
		      $toprint .= $this->combine_template($mytemplate,
			                                     $rec[$itmname],
			                                     $rec[$itmdescr],
			                                     $this->list_photo($rec[$this->getmapf('code')],$xdist,$ydist,$myimageclick,$cat),
			                                     $rec['uniname1'] , 
												 $rec[$this->getmapf('code')], 
												 $price,
												 $cart);
		   }	 				   	   	   
		   else {		   				   	

		   //$viewdata[] = $rec['code5'];//$n+1;
		   //$viewattr[] = "left;5%";   
	       $viewdata[] = $this->list_photo($rec[$this->getmapf('code')],$xdist,$ydist,$myimageclick,$cat);
	       $viewattr[] = "left;5%";		   					   	   	   
		   
		   $viewdata[] = "<b>" . ($rec[$itmname]?$rec[$itmname]:"&nbsp;") . "</b><br>" . 
						 ($rec[$itmdescr]?$rec[$itmdescr]:"&nbsp;");
		   $viewattr[] = "left;70%";	
		   
		   
	       $viewdata[] = "<b>" . $rec[$this->getmapf('code')] . "</b>" .//. "<br>" . $rec['uniname1']."<br>".
		                 "<h2>". $price . "&#8364"/*writecl($price,'#FF0000','#FFFFFF')*/."</h2><br>" .
						 $cart;
	       $viewattr[] = "center;25%";			   		   
		   	   		   	   		   	   		   
		   
	       $myrec = new window('',$viewdata,$viewattr);
	       $toprint .= $myrec->render("center::100%::0::group_article_selected::left::5::5::");
	       unset ($viewdata);
	       unset ($viewattr);	
		   
		   $toprint .= "<hr>";
		   }//template	
		  } 
		  
		  if ((is_array($items)) && ($linemax>1)) {
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
	          $toprint .= $myrec->render();//"center::100%::0::group_article_selected::left::4::4::");
	          unset ($viewdata);
	          unset ($viewattr);		  
	        }		  
	      }
	      $toprint .= $this->show_asceding($cmd);
			
	      $toprint .= $this->show_paging($cmd);							   	
												   	
					
	   }
	   else
		  //$toprint .= $this->show_lastentries();	
		  $toprint .= "<h2>Δεν υπάρχουν εγγραφές!</h2>" . "<br>";
	
       $mywin = new window(/*$this->title*/'',$toprint);
       $out .= $mywin->render();	   	   	 		
	
	   return ($out);	
	}
	
    function list_katalog_table($linemax=2,$imgx=null,$imgy=null,$imageclick=0,$showasc=0,$cmd=null,$template=null) {
	   $cmd = $cmd?$cmd:'klist';	
	   $page = GetReq('page')?GetReq('page'):0;			
	   $cat = GetReq('cat');
       if (!$cat) $createcats = 1; else $createcats = 0;
		   
	   $lan = getlocal();
	   $itmname = $lan?'itmname':'itmfname';
	   $itmdescr = $lan?'itmdescr':'itmfdescr';			   
	   
	   $xdist = ($imgx?$imgx:160);
	   $ydist = ($imgy?$imgy:120);
	   
	   if (remote_paramload('SHCATALOG','imageclick',$this->path)>0)
	     $myimageclick = 1;
	   else	 
	     $myimageclick = $imageclick;	   
	   
	   $t = $this->urlpath .'/' . $this->inpath . '/'. str_replace('.',getlocal().'.',$template) ; 
	   //echo $t,'>';
	   if (($template) && is_readable($t)) {
	     //echo $template,'>';
		 $mytemplate = file_get_contents($t);
		 //$html = $this->combine_template($template,'a','b','c','d','e');
		 //echo $html;
	   }	   

       $this->read_list(); 	//read here to allow also las entries and offers to show in line
		
	   if ($this->msg) $ret = $this->msg;

	   if (count($this->result)>0) {	
		
		$pp = $this->read_policy();		
	    //$ret .= $this->show_asceding();		
	
	    foreach ($this->result as $n=>$rec) {

                   if ($createcats) {
                      $cat = $this->getkategories($rec);
                   }		

		   if ($rec[$pp]>0) { 
		     $myp = $this->spt($rec[$pp]);
		     $price = ($myp?number_format($myp,2,',','.'):"&nbsp;");
		   }	
		   else 	 
		     $price = $this->zeroprice_msg;				   	   	   
			 
		   $pfile = sprintf("%05s",$rec[$this->getmapf('code')]); //echo $pfile,"<br>";
		   
		   //photo
		   $photo = $this->imgpath . $pfile . $this->restype;	   
		   if (!file_exists($photo))
		     $photo = $this->imgpath . 'nopic' . $this->restype;	
			 
		  //thubnail photo	 
		   $thub_photo = $this->thubpath . $pfile . $this->restype;	//echo $thub_photo,"!<br>";   
		   if (!file_exists($thub_photo))
		     $thub_photo = $this->thubpath . 'nopic' . $this->restype;
			 
		   if ((GetGlobal('UserID')) || (seclevel('SHCATALOG_CART',$this->userLevelID))) {//logged in or sec ok
		     $cart_code = $rec[$this->getmapf('code')];
			 $cart_title = $rec[$itmname];
			 $cart_group = $cat;
			 $cart_page = GetReq('page')?GetReq('page'):0;
			 $cart_descr = $rec[$itmdescr];
			 $cart_photo = $this->get_photo_url($rec[$this->getmapf('code')]);
			 $cart_price = $price;
			 $cart_qty = 1;//???
		     $icon_cart = GetGlobal('controller')->calldpc_method("shcart.showsymbol use $cart_code;$cart_title;$path;$MYtemplate;$cart_group;$cart_page;$cart_descr;$cart_photo;$cart_price;$cart_qty;+$cat+$cart_page",1);//'cart';
		   }
           else
		     $icon_cart = null;			 
			 
		   if ($mytemplate) {
		   
		      $items[] = $this->combine_template($mytemplate,
			                                     $rec[$itmname],
			                                     $rec[$itmdescr],
			                                     $this->list_photo($rec[$this->getmapf('code')],$xdist,$ydist,$myimageclick,$cat),
			                                     $rec['uniname1'] , 
												 $rec[$this->getmapf('code')], 
												 $price,
												 $icon_cart);
		   }	 				   	   	   
		   else {			 			 
		   
		   $viewdata[] = "<b>".($rec[$itmname]?$rec[$itmname]:"&nbsp;") . "</b><br>" . 
						 /*localize('_descr',getlocal()) . ":" .*/ ($rec[$itmdescr]?$rec[$itmdescr]:"&nbsp;") . "<br>" . 
		                 localize('_uniname1',getlocal()) . ":" . ($rec['uniname1']?$rec['uniname1']:"&nbsp;") . "<br>" .
                         localize('_code',getlocal()) . ":" . $rec[$this->getmapf('code')] . "<br>" .						 
						 /*localize('_axia',getlocal()) . ":" .*/ 
						 "<b>". writecl($price,'#FFFFFF','#FF0000')."</b>";/* . "<br><br>" .
						 seturl('t=kshow&cat='.$rec[1].'&id='.$rec['id'].'&page='.$page,'Περισσότερα...');*/
		   $viewattr[] = "left;60%";				 		   
		      
		   $viewdata[] = $this->list_photo($rec[$this->getmapf('code')],$xdist,$ydist,$imageclick,$cat).
		                 '<br>' . $icon_cart;
	       $viewattr[] = "left;40%";
		   
	       //$viewdata[] = "&nbsp;";
	       //$viewattr[] = "left;10%";			   		   
		   	   		   	   		   	   		   
		   
	       $myrec = new window('',$viewdata,$viewattr);
	       $items[] = $myrec->render("center::100%::0::group_article_table::left::3::3::");
	       unset ($viewdata);
	       unset ($viewattr);	
		   }//mytemplate
		}	
		
		  
	    if ($showasc) {
	      $ret .= $this->show_asceding($cmd);
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
	      $ret .= $myrec->render("center::100%::0::group_article_selected::left::4::4::");
	      unset ($viewdata);
	      unset ($viewattr);		  
	    }
	   
	    if ($showasc) {
	      //$ret .= "<hr>";
	      $ret .= $this->show_asceding($cmd);
	    }
			
		if ($this->pager) {
		  $ret .= $this->show_paging($cmd);					
	    }
	   }	
	   /*else //no msg in search default...
		  //$ret .= $this->show_lastentries();	
		  $ret .= "<h2>Δεν υπάρχουν εγγραφές!</h2>" . "<br>";
       */
	   
	
	   return ($ret);	
    } 
	
	function read_item($direction=null) {
        $db = GetGlobal('db');	
		//$item = GetReq('id');	 
		$item = GetReq('listm')?GetReq('listm'):GetReq('id');
		if (GetReq('cat')!=null)
		  $cat = GetReq('cat');			
		
	    $sSQL = "select id,sysins,code1,pricepc,price2,sysins,itmname,itmfname,uniname1,uniname2,active,code4," .// from abcproducts";// .
	            "price0,price1,cat0,cat1,cat2,cat3,cat4,itmdescr,itmfdescr,itmremark,".$this->getmapf('code')." from products ";
				
		switch ($direction) {
		
		  case 'next':
		  $next_sql = "select id from products where ".$this->getmapf('code').">".$item;
	      //if (isset($cat)) $next_sql .= " and type=" . $db->qstr($cat);
		  //if (isset($subcat)) $next_sql .= ' and type2=' . $db->qstr($subcat);		
		  $next_sql .= " and active=1";
		  		  
	      $rset = $db->Execute($next_sql,2);
	      //$nret = $db->fetch_array($rset);	 //only one set
	      $this->list_item = $next_item = $nret[0]?$nret[0]:$item; 			  
		  $sSQL .= " WHERE ".$this->getmapf('code')."=" . $next_item;
		  break;
		  
		  case 'prev':
		  $prev_sql = "select ".$this->getmapf('code')." from products where ".$this->getmapf('code')."<".$item;
	      //if (isset($cat)) $prev_sql .= " and type=" . $db->qstr($cat);
		  //if (isset($subcat)) $prev_sql .= ' and type2=' . $db->qstr($subcat);		
		  $prev_sql .= " and active=1";
		  		  		  
	      $rset = $db->Execute($prev_sql,2);
	      //$nret = $db->fetch_array($rset);	 //only one set
	      $this->list_item = $prev_item = $nret[0]?$nret[0]:$item; 		  		  
		  $sSQL .= " WHERE ".$this->getmapf('code')."=" . $prev_item;
		  break;
		   
		  default : 
		  $sSQL .= " WHERE ".$this->getmapf('code')."=" . $item;
		} 	  
	   
	   //echo $sSQL;
	   
	   $resultset = $db->Execute($sSQL,2);
	   //$ret = $db->fetch_array_all($resultset);	 
	   //print_r($ret);  
	   $this->result = $resultset; 	
	   
	  /* if (count($this->result)>0) {
	   
		 $marka = get_selected_option_fromfile($ret[0][7],'marka',0);	//echo $marka;
		 $color = get_selected_option_fromfile($ret[0][9],'colors',0);		   
	     //save vehicle for aditional services as forum
	     $current_vehicle = $marka . " " . $ret[0][6] . " " . ($ret[0][5]+0) . " " . $color;
	     //echo $current_vehicle;
	     SetSessionParam('CURRENT_VEHICLE',$current_vehicle);	   
	   }*/ 
	   
	}	
	
	function show_item() {
	   global $current_item;//use global becouse of same page info transfer
	   
	   $lan = getlocal();
	   $itmname = $lan?'itmname':'itmfname';
	   $itmdescr = $lan?'itmdescr':'itmfdescr';		   
	
	   $page = GetReq('page')?GetReq('page'):0;			
	   $cat = GetReq('cat');
	   $id = GetReq('id');
	   $listm = $this->list_item?$this->list_item:GetReq('id');	
	   $xdist = (remote_paramload('SHCATALOG','itemimagex',$this->path)?remote_paramload('SHCATALOG','itemimagex',$this->path):400);
	   $ydist = (remote_paramload('SHCATALOG','itemimagey',$this->path)?remote_paramload('SHCATALOG','itemimagey',$this->path):300);		  	    
	   
	   
	   $template='fpitem.htm';	   
	   $t = $this->urlpath .'/' . $this->inpath . '/'. str_replace('.',getlocal().'.',$template) ; 
	   //echo $t,'>';
	   if (($template) && is_readable($t)) {
	     //echo $template,'>';
		 $mytemplate = file_get_contents($t);
		 //$html = $this->combine_template($template,'a','b','c','d','e');
		 //echo $html;
	   }	   	      
	
	   if (count($this->result)>0) {	
	   
		$pp = $this->read_policy();	   
	   
	    foreach ($this->result as $n=>$rec) {
		   if ($rec[$pp]>0) { 
		     $myp = $this->spt($rec[$pp]);
		     $price = ($myp?number_format($myp,2,',','.'):"&nbsp;");
		   }	
		   else 	 
		     $price = $this->zeroprice_msg;	  						 
		   
           $recar[localize('_code',getlocal())]=($rec[$this->getmapf('code')]!=null?$rec[$this->getmapf('code')]:"&nbsp;");		   
		   $recar[localize('_item',getlocal())]=($rec[$itmname]!=null?$rec[$itmname]:"&nbsp;");
		   $recar[localize('_descr',getlocal())]=($rec[$itmdescr]?$rec[$itmdescr]:"&nbsp;");
		   $recar[localize('_uniname1',getlocal())]=($rec['uniname1']!=null?$rec['uniname1']:"&nbsp;"); 
		   $recar[localize('_code',getlocal())]=($rec[$this->getmapf('code')]?$rec[$this->getmapf('code')]:"&nbsp;"); 
		   $recar[localize('_axia',getlocal())]= $price . "<br><br>";
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
		   if ((GetGlobal('UserID')) || (seclevel('SHCATALOG_CART',$this->userLevelID))) {//logged in or sec ok
		     $cart_code = $rec[$this->getmapf('code')];
			 $cart_title = $rec[$itmname];
			 $cart_group = $cat;
			 $cart_page = GetReq('page')?GetReq('page'):0;
			 $cart_descr = $rec[$itmdescr];
			 $cart_photo = $this->get_photo_url($rec[$this->getmapf('code')]);
			 $cart_price = $price;
			 $cart_qty = 1;//???
		     $icon_cart = GetGlobal('controller')->calldpc_method("shcart.showsymbol use $cart_code;$cart_title;$path;$MYtemplate;$cart_group;$cart_page;$cart_descr;$cart_photo;$cart_price;$cart_qty;+$cat+$cart_page",1);//'cart';
		   }
           else
		     $icon_cart = null;
			 
		   $mybuttons = seturl('t=klist&cat='.$cat.'&page='.$page,$icon_back) .
				 	    seturl('t=kprev&cat='.$cat.'&page='. $page . '&direction=prev&id='.$id.'&listm='.$listm,$icon_prev) .						 
					    seturl('t=knext&cat='.$cat.'&page='. $page. '&direction=next&id='.$id.'&listm='.$listm,$icon_next) .						 
					    $this->printlink($icon_print);			 
			 
		   if ($mytemplate) {	 
		      $toprn = $this->combine_template($mytemplate,
			                                     $rec[$itmname],
			                                     $rec[$itmdescr],
			                                     $this->list_photo($rec[$this->getmapf('code')],400,300),
			                                     $rec['uniname1'] , 
												 $rec[$this->getmapf('code')], 
												 $price,
												 $icon_cart);
												 
             $toprn .= $this->show_aditional_files($rec[$this->getmapf('code')],1);
             $toprn .= $this->show_aditional_html_files($rec[$this->getmapf('code')]);			 
			 
             $toprint .= $toprn; //..copy print ver to toprint flow..
			 //add buttons			 
			 $toprint .= $this->buttons_OFF?"&nbsp;":$mybuttons;
						  
           }
		   else {
		   $bb = $this->buttons_OFF?"&nbsp;":$mybuttons;
		   $viewdata[] = $table2show .
						 $bb .
						 $icon_cart;
		   $viewattr[] = "left;50%";	
		   
		   //printout...
		   $printdata[] = $table2show;
		   $printattr[] = "left;50%";			   
           
		   $linkphoto = $this->list_photo($rec[$this->getmapf('code')],400,300);//$plink . "<img src=\"" . $photo . "\" width=\"400\" height=\"300\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>"; 
		   //photo in window
		   $pwin = new window('Φωτογραφία',$linkphoto);
		   $pphoto = $pwin->render();
		   unset ($pwin);	 
			 
	       $viewdata[] = $pphoto . 
		                 $this->show_aditional_files($rec[$this->getmapf('code')],1) . 
				         $this->show_aditional_html_files($rec[$this->getmapf('code')]);
	       $viewattr[] = "left;50%";
		   
		   //printout...
		   $printdata[] = $pphoto . 
		                  $this->show_aditional_files($rec[$this->getmapf('code')]) .
						  $this->show_aditional_html_files($rec[$this->getmapf('code')]);
		   $printattr[] = "left;50%";			   		   
		   	   		   	   		
		   $toprint .= $this->set_anchor('photo');									   	   		   
		   
	       $myrec = new window('',$viewdata,$viewattr);
	       $toprint .= $myrec->render("center::100%::0::group_article_selected::left::3::3::");
	       unset ($viewdata);
	       unset ($viewattr);	
		   
		   //printout
	       $myprn = new window('',$printdata,$printattr);
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
	 	}	   
	   }
	   else
		  $toprint .= "<h2>Δεν υπάρχουν εγγραφές!</h2>" . "<br>";
		  
       $mywin = new window(/*$this->title*/'',$toprint);
       $out = $mywin->render("center::100%::0::group_article_selected::left::2::2::");	 		  
		  	   
	   return ($out);	
	}		
	
	function show_aditional_files($id,$nojs=null) {
	     //main
		 $mainimg = $this->urlpath .'/'. $this->imgpath .  $id . $this->restype; 
		 $mainimg_url = $this->imgpath . $id . $this->restype;		 
         if (file_exists($mainimg)) {	
             if (iniload('JAVASCRIPT')) {	
  	           $plink = "<a href=\"#\" ";	   
	           //call javascript for opening a new browser win for the img		   
	           //$params = $photo . ";Image;width=300,height=200;";
	           $params = "$mainimg_url;620;470;<B>$title</B><br>$descr;";			 

			   $js = new jscript;
	           $plink .= $js->JS_function("js_popimage",$params); 
			   unset ($js); 

	           $plink .= ">";
	         }
	         else {
			   $addtional_photo_link = seturl('t=kshow&cat='.GetReq('cat').'&id='.GetReq('id').'&thub='.$i.'#photo');
			   $plink = "<A href=\"$addtional_photo_link\">";				 
               //$plink = "<A href=\"$photo\">";			 
			 }  
			 
			 $items[] = $plink . "<img src=\"" . $mainimg_url . "\"  width=\"100\" height=\"75\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";		 
		 }
	
		 //multiple photos
		 for($i='A';$i<='I';$i++) {
		   //work with uphoto path only......		 
		   //$ad_photo_small = $this->thubpath .  $id . $i . $this->restype;
		   $ad_photo_big = $this->imgpath .  $id . $i . $this->restype;
		   
		   $aditional_pic_file = $this->urlpath .'/'. $this->imgpath . $id . $i . $this->restype;
		   if (file_exists($aditional_pic_file)) {
		     //$photos .= "<br><br><img src=\"" . $ad_photo . "\"  alt=\"". localize('_IMAGE',getlocal()) . "\">";

             if (iniload('JAVASCRIPT')) {	
  	           $plink = "<a href=\"#\" ";	   
	           //call javascript for opening a new browser win for the img		   
	           //$params = $photo . ";Image;width=300,height=200;";
	           $params = "$ad_photo_big;620;470;<B>$title</B><br>$descr;";			 

			   $js = new jscript;
	           $plink .= $js->JS_function("js_popimage",$params); 
			   unset ($js); 

	           $plink .= ">";
	         }
	         else {
			   $addtional_photo_link = seturl('t=kshow&cat='.GetReq('cat').'&id='.GetReq('id').'&thub='.$i.'#photo');
			   $plink = "<A href=\"$addtional_photo_link\">";				 
               //$plink = "<A href=\"$photo\">";			 
			 }  
			 
			 $items[] = $plink . "<img src=\"" . $ad_photo_big . "\"  width=\"100\" height=\"75\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";
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
	         $gallery .= $myrec->render("center::100%::1::group_article_selected::left::2::2::");
	         unset ($viewdata);
	         unset ($viewattr);		  
	       }		 
		 
		   $gall = new window(localize('_gallery',getlocal()),$gallery); 
		   $out = $gall->render();
		   unset($gall);
		   
		   return ($out);
		 }
	}	

	function show_aditional_html_files($id) {	
	
	     $lan = getlocal();
		 $slan = $lan?$lan:'0';	

		 $mainhtml = $this->htmlpath . $id . $slan . '.html';		 
         if (file_exists($mainhtml)) {		
		 
		   $ret = file_get_contents($mainhtml);	
		 }
		 
		 //multiple html
		 for($i='A';$i<='I';$i++) {
		   
		   $aditional_html_file = $this->htmlpath . $id . $slan . $i . '.html';
		   if (file_exists($aditional_html_file)) {
		   
		     $ret .= file_get_contents($aditional_html_file);
		   }
		 }
		 
		 return ($ret);  		 
	}			
		
	function show_lastentries($items=10,$days=12,$linemax=null,$imgx=100,$imgy=75,$imageclick=0,$template=null) {
        $db = GetGlobal('db');		
		
	    $date2check = time() - ($days * 24 * 60 * 60);
	    $entrydate = date('Y-m-d',$date2check);			
	                                                                                //,date1
        $sSQL = "select id,sysins,code1,pricepc,price2,sysins,itmname,itmfname,uniname1,uniname2,active,code4," .// from abcproducts";// .
	            "price0,price1,cat0,cat1,cat2,cat3,cat4,itmdescr,itmfdescr,itmremark,".$this->getmapf('code')." from products ";
		$sSQL .= " WHERE ";	
		//$sSQL .= "date1>='" . convert_date(trim($entrydate),"-YMD",1) . "' and ";
		$sSQL .= "active>0";	
		$sSQL .= " ORDER BY id desc LIMIT " . $items;			
	    //echo $sSQL;
		
	    $resultset = $db->Execute($sSQL,2);	
		$this->result = $resultset;
		
		$xmax = $imgx?$imgx:100;
		$ymax = $imgy?$imgy:75;		
		
		if ($linemax)
		  $out = $this->list_katalog_table($linemax,$xmax,$ymax,$imageclick,0,null,$template);
		else  	
          $out = $this->list_katalog();
		  
		return ($out);	
	}	
	
	
	function show_offers($items=10,$days=12,$linemax=null,$imgx=100,$imgy=75,$imageclick=0,$template=null) {
        $db = GetGlobal('db');		
		
	    $date2check = time() - ($days * 24 * 60 * 60);
	    $entrydate = date('Y-m-d',$date2check);		
		
		/*$updateSQL = "update products set p1='yes' where ";	
		$updateSQL .= "code5='50101' or code5='50102' or code5='50103'";		
	    $resultset = $db->Execute($updateSQL,1);
		echo $updateSQL;			*/
	                                                                                //,date1
        $sSQL = "select id,sysins,code1,pricepc,price2,sysins,itmname,itmfname,uniname1,uniname2,active,code4," .// from abcproducts";// .
	            "price0,price1,cat0,cat1,cat2,cat3,cat4,itmdescr,itmfdescr,itmremark,".$this->getmapf('code')." from products ";
		$sSQL .= " WHERE ";	
		//$sSQL .= "date1>='" . convert_date(trim($entrydate),"-YMD",1) . "' and ";
		$sSQL .= $this->getmapf('offer')."='yes' and active>0";	
		$sSQL .= " ORDER BY itmname asc LIMIT " . $items;			
	    //echo $sSQL;
		
	    $resultset = $db->Execute($sSQL,2);	
		$this->result = $resultset;
		
		$xmax = $imgx?$imgx:100;
		$ymax = $imgy?$imgy:75;		
		
		if ($linemax)
		  $out = $this->list_katalog_table($linemax,$xmax,$ymax,$imageclick,0,null,$template);
		else  	
          $out = $this->list_katalog();
		  
		return ($out);	
	}	
	
	function do_quick_search($text2find,$comboselection=null) {
        $db = GetGlobal('db');	
		$page = GetReq('page')?GetReq('page'):0;
	    $asc = GetReq('asc');
	    $order = GetReq('order');					
	  
	    $dataerror = null;	
		
		if ($text2find) {
		
		  $parts = explode(" ",$text2find);//get special words in text like code:  
	
	      $sSQL = "select id,sysins,code1,pricepc,price2,sysins,itmname,itmfname,uniname1,uniname2,active,code4," .// from abcproducts";// .
	            "price0,price1,cat0,cat1,cat2,cat3,cat4,itmdescr,itmfdescr,itmremark,".$this->getmapf('code')." from products ";
		  	
		  $sSQL .= " where ";
		  
		  switch ($parts[0]) {
		  
		    case 'code:' :  $sSQL .= " ( ".$this->getmapf('code')." like '%" . $this->decodeit($parts[1]) . "%')";
			                break;
		  
		  default://normal search
			  	
	      $sSQL .= " ( $itmname like '%" . strtolower($text2find) . "%' or " .
		             " $itmname like '%" . strtoupper($text2find) . "%')";	
		  $sSQL .= " or ";		   
	      $sSQL .= " ( $itmdescr like '%" . strtolower($text2find) . "%' or " .
		             " $itmdescr like '%" . strtoupper($text2find) . "%')";
		  $sSQL .= " or ";		   			 
	      $sSQL .= " ( ".$this->getmapf('code')." like '%" . strtolower($text2find) . "%' or " .
		           " ".$this->getmapf('code')." like '%" . strtoupper($text2find) . "%')";						 
					 
		  }//switch....................................................					 							  
	
		  $sSQL .= " and itmactive>0 and active>0";		
		  $sSQL .= ' ORDER BY';
		  
		  switch ($order) {
		    case 1  : $sSQL .= " $itmname"; break;
			case 2  : $sSQL .= ' price0';break;  
		    case 3  : $sSQL .= ' '.$this->getmapf('code'); break;//must be converted to the text equal????
			case 4  : $sSQL .= ' cat0';break;			
			case 5  : $sSQL .= ' cat1';break;
			case 6  : $sSQL .= ' cat2';break;			
			case 9  : $sSQL .= ' cat3';break;						
			case 10 : $sSQL .= ' cat4';break;									
		    default : $sSQL .= ' '.$this->getmapf('code');
		  }
		  
		  switch ($asc) {
		    case 1  : $sSQL .= ' ASC'; break;
			case 2  : $sSQL .= ' DESC';break;
		    default : $sSQL .= ' ASC';
		  }
		  
		  //LINITED SEARCH
		  if ($this->pager) {
		    $p = $page * $this->pager;
		    $sSQL .= " LIMIT $p,".$this->pager; //page element count
		  }
		  	
		  //echo $sSQL;
		  if ($dataerror==null) {
	        $resultset = $db->Execute($sSQL,2); 
	   	   
	        $this->result = $resultset; 
			$this->meter = $db->Affected_Rows();
			$this->max_items = $db->Affected_Rows();
			$this->msg = $this->meter . ' ' . localize('_founded',getlocal());																		
	      }
		  else
		    $this->msg = $dataerror;		
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
	
	  if (empty($this->map_t)) return 0;
	  
	  foreach ($this->map_t as $id=>$elm)
	    if ($elm==$name) break;
				
	  //$id = key($this->map_t[$name]) ;
	  $ret = $this->map_f[$id];
	  return ($ret);
	}	

    function getkategories($rec=null) {
                 
                 if ($rec['cat0'])
                      $ck[0] = str_replace(' ','_',$rec['cat0']);
                 if ($rec['cat1'])
                      $ck[1] = str_replace(' ','_',$rec['cat1']);
                 if ($rec['cat2'])
                      $ck[2] = str_replace(' ','_',$rec['cat2']);
                 if ($rec['cat3'])
                      $ck[3] = str_replace(' ','_',$rec['cat3']);
                 if ($rec['cat4'])
                      $ck[4] = str_replace(' ','_',$rec['cat4']);
        
                 if (!empty($ck))
                   $cat = implode('^',$ck);//print_r($ck);
                 //echo $cat,'<br>';
                 unset($ck); //reset ck

                 return ($cat);
    }
		
	function read_policy($leeid=null) {
	   //$db = GetGlobal('db');
	   $reseller = GetSessionParam('RESELLER');
           //echo '>',$reseller;
		 
	   if ($reseller=='true')
	     $v = $this->pprice[0];
	   else
   	     $v = $this->pprice[1];
	   
	   return ($v);
	   
	}	
	
	function combine_template($template_contents,$p0=null,$p1=null,$p2=null,$p3=null,$p4=null,$p5=null,$p6=null,$p7=null,$p8=null,$p9=null) {
	
		$params = explode('<#>',"$p0<#>$p1<#>$p2<#>$p3<#>$p4<#>$p5<#>$p6<#>$p7<#>$p8<#>$p9");
	    //print_r($params);
		
		if (defined('FRONTHTMLPAGE_DPC')) {
		  $fp = new fronthtmlpage(null);
		  $ret = $fp->process_commands($template_contents);
		  unset($fp);
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
	
	function pricewithtax($price) {
	
	  if (defined('SHCART_DPC')) {
		  //select based on customer type
		  $tax = GetGlobal('controller')->calldpc_var('shcart.tax');
          $mytax = (($price*$tax)/100);	
	      $value = ($price+$mytax);		  
	  }
	  else
	     $value = $price;
		 
	  return ($value);		 
	}
	
	//select price type
	function spt($price) {
	  
	  //2nd method
	  if ($this->is_reseller=='true')
	    $p = $price;
	  else
	    $p = $this->pricewithtax($price);	
	  
	  return ($p);
	}					
	
};			  
}
?>