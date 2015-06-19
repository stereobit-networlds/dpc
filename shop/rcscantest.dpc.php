<?php
$__DPCSEC['RCSCANTEST_DPC']='1;1;1;1;1;1;2;2;9';
$__DPCSEC['RCSCANTEST_CART']='1;1;1;1;1;1;2;2;9';

if ( (!defined("RCSCANTEST_DPC")) && (seclevel('RCSCANTEST_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCSCANTEST_DPC",true);

$__DPC['RCSCANTEST_DPC'] = 'rcscantest';

$d = GetGlobal('controller')->require_dpc('shop/shkatalog.dpc.php');
require_once($d);


$__EVENTS['RCSCANTEST_DPC'][0]='cpscantest';

$__ACTIONS['RCSCANTEST_DPC'][0]='cpscantest';

$__LOCALE['RCSCANTEST_DPC'][0]='RCSCANTEST_DPC;Scan test;Scan test';

class rcscantest extends shkatalog {

    var $userLevelID;	
	var $result, $pc;
	var $path;

	function rcscantest() {
	  $UserSecID = GetGlobal('UserSecID');	
	
	  shkatalog::shkatalog();
	  
      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);	  

      $this->path = paramload('SHELL','prpath');	
	  $this->title = localize('RCSCANTEST_DPC',getlocal());	
	  $this->pc = remote_paramload('SHMK1200','pc',$this->path);
	}
	
	function event($event=null) {
	
	   //ALLOW EXPRIRED APPS
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////			
	
	    switch ($event) {

		  case 'kshow'        : 
		  default             : //$this->read_item(); 							  
        }	
	}
	
	function action($action=null) {
	
	    if (GetSessionParam('REMOTELOGIN')) 
	      $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	    else  
          $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);	  		
	  
	    switch ($action) {
	  
		  case 'cpscantest'       : 
		  default             : //$out .= $this->show_item();
		                       
        }	  
	  
	    return ($out);
	}	
	
	//override
	function read_item($direction=null) {
        $db = GetGlobal('db');	
		//$item = GetReq('id');	 
		$item = GetReq('listm')?GetReq('listm'):GetReq('id');
		if (GetReq('cat')!=null)
		  $cat = GetReq('cat');			
		
	    $sSQL = "select id,sysins,code1,pricepc,price2,sysins,itmname,uniname1,uniname2,active,code4," .// from abcproducts";// .
	            "price0,price1,cat0,cat1,cat2,cat3,cat4,itmremark,".$this->getmapf('code')." from products ";
				
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
		  break;
		   
		  default : 
		  $sSQL .= " WHERE ".$this->getmapf('code')."=";
		  $sSQL .= $this->codetype=='string'?$db->qstr($item):$item;
		  
		  //extend to barcodes
		  $sSQL .= " or code3=" . $db->qstr($item);
		  $sSQL .= " or code4=" . $db->qstr($item);		  
		} 	  
	   
	   //echo $sSQL;
	   
	   $resultset = $db->Execute($sSQL,2);
	   //$ret = $db->fetch_array_all($resultset);	 
	   //print_r($ret);  
	   $this->result = $resultset; 	
	   
	}	
	
	//override
	function show_item($template=null) {
	   global $current_item;//use global becouse of same page info transfer
	
	   $page = GetReq('page')?GetReq('page'):0;			
	   $cat = GetReq('cat');
	   $id = GetReq('id');
	   $listm = $this->list_item?$this->list_item:GetReq('id');	  
	   $xdist = (remote_paramload('SHMK1200','itemimagex',$this->path)?remote_paramload('SHMK1200','itemimagex',$this->path):200);
	   $ydist = (remote_paramload('SHMK1200','itemimagey',$this->path)?remote_paramload('SHMK1200','itemimagey',$this->path):150);		  	    
	   
	   
	   $template='fpitem.htm';	   
	   $t = $this->urlpath .'/' . $this->inpath . '/'. str_replace('.',getlocal().'.',$template) ; 
	   //echo $t,'>';
	   if (($template) && is_readable($t)) {
	     //echo $template,'>';
		 $mytemplate = file_get_contents($t);
		 //$html = $this->combine_template($template,'a','b','c','d','e');
		 //echo $html;
	   }
	   
	   if (!$this->result) return "<h1>Item not found!</h1>";	   
	   
	   //print_r($this->result); echo 'z';
	   if (count($this->result)>0) {	
	   
		$pp = $this->read_policy();	   
	   
	    foreach ($this->result as $n=>$rec) {	
		   
					 
		   if ($rec[$pp]>0) {
		   
		     if ($this->pc) {
			   $p = $rec[$pp] + $rec[$pp] * $this->pc / 100; 
			   $price = ($p?number_format($p,2,',','.'):"&nbsp;") . "&#8364<br>";
			 }  
			 else
		       $price = ($rec[$pp]?number_format($rec[$pp],2,',','.'):"&nbsp;") . "&#8364<br>";
			 // . $this->zeroprice_msg;
		   }	 
		   else 	 
		     $price = $this->zeroprice_msg;	
			 
		   $mk1200photo = $this->list_photo($rec[$this->getmapf('code')],$xdist,$ydist);	
		   $mk1200item = $rec['itmname'];   						 
		   
           $recar[localize('_code',getlocal())]=($rec[$this->getmapf('code')]!=null?$rec[$this->getmapf('code')]:"&nbsp;");		   
		   $recar[localize('_item',getlocal())]=($rec['itmname']!=null?$rec['itmname']:"&nbsp;");
		   $recar[localize('_descr',getlocal())]=($rec['itmremark']?$rec['itmremark']:"&nbsp;");
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
		   if ((GetGlobal('UserID')) || (seclevel('SHKATALOG_CART',$this->userLevelID))) {//logged in or sec ok
		     $cart_code = $rec[$this->getmapf('code')];
			 $cart_title = $rec['itmname'];
			 $cart_group = $cat;
			 $cart_page = GetReq('page')?GetReq('page'):0;
			 $cart_descr = $rec['itmremark'];
			 $cart_photo = $this->get_photo_url($rec[$this->getmapf('code')]);
			 $cart_price = $price;
			 $cart_qty = 1;//???
		     $icon_cart = GetGlobal('controller')->calldpc_method("shcart.showsymbol use $cart_code;$cart_title;$path;$MYtemplate;$cart_group;$cart_page;$cart_descr;$cart_photo;$cart_price;$cart_qty;+$cat+$cart_page",1);//'cart';
		   }
           else
		     $icon_cart = null;
			 
		   if ($mytemplate) {	 
		      $toprn = $this->combine_template($mytemplate,
			                                     $rec['itmname'],
			                                     $rec['itmremark'],
			                                     $this->list_photo($rec[$this->getmapf('code')],$xdist,$ydist),
			                                     $rec['uniname1'] , 
												 $rec[$this->getmapf('code')], 
												 $price,
												 $icon_cart);
												 
             $toprn .= $this->show_aditional_files($rec[$this->getmapf('code')],1);
			 
             $toprint .= $toprn; //..copy print ver to toprint flow..
			 //add buttons			 
			 $toprint .= seturl('t=klist&cat='.$cat.'&page='.$page,$icon_back) .
						 seturl('t=kprev&cat='.$cat.'&page='. $page . '&direction=prev&id='.$id.'&listm='.$listm,$icon_prev) .						 
						 seturl('t=knext&cat='.$cat.'&page='. $page. '&direction=next&id='.$id.'&listm='.$listm,$icon_next) .						 
						 //seturl('t=qbuy&id='.$id,$icon_getit) .
						 $this->printlink($icon_print);
						  
           }
		   else {
		   $viewdata[] = $table2show .
						 seturl('t=klist&cat='.$cat.'&page='.$page,$icon_back) .
						 seturl('t=kprev&cat='.$cat.'&page='. $page . '&direction=prev&id='.$id.'&listm='.$listm,$icon_prev) .						 
						 seturl('t=knext&cat='.$cat.'&page='. $page. '&direction=next&id='.$id.'&listm='.$listm,$icon_next) .						 
						 //seturl('t=qbuy&id='.$id,$icon_getit) .
						 $this->printlink($icon_print) .
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
			 
	       $viewdata[] = $pphoto . $this->show_aditional_files($rec[$this->getmapf('code')],1); 
	       $viewattr[] = "left;50%";
		   
		   //printout...
		   $printdata[] = $pphoto . $this->show_aditional_files($rec[$this->getmapf('code')]);
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
	   
	   $out = '<table><tr><td width=\'99%\'><h2>' . $price .'</h2><br><h5><font face="Arial, Helvetica, sans-serif" size=-2>'.$mk1200item.'</font></h5></td><td>'. $mk1200photo . '</td></tr></table>';
	   $out .= '<hr>'.mb_convert_encoding($mk1200item, "UTF-7");
		  	   
	   return ($out);	
	}		   

};
}
?>