<?php

$__DPCSEC['SHWISHLIST_DPC']='1;1;1;2;2;2;2;2;9';

if ((!defined("SHWISHLIST_DPC")) && (seclevel('SHWISHLIST_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHWISHLIST_DPC",true);

$__DPC['SHWISHLIST_DPC'] = 'shwishlist';


$d = GetGlobal('controller')->require_dpc('transactions/wishlist.dpc.php');
require_once($d);


GetGlobal('controller')->get_parent('WISHLIST_DPC','SHWISHLIST_DPC');
//print_r($__ACTIONS['SHWISHLIST_DPC']);

$__EVENTS['SHWISHLIST_DPC'][6]='wsviewhtml';
$__EVENTS['SHWISHLIST_DPC'][7]='wsaddlist';
$__EVENTS['SHWISHLIST_DPC'][8]='wslist';
$__EVENTS['SHWISHLIST_DPC'][9]='wsdellist';
$__EVENTS['SHWISHLIST_DPC'][10]='wsadditem';
$__EVENTS['SHWISHLIST_DPC'][11]='wsdelitem';

$__ACTIONS['SHWISHLIST_DPC'][6]='wsviewhtml';
$__ACTIONS['SHWISHLIST_DPC'][7]='wsaddlist';
$__ACTIONS['SHWISHLIST_DPC'][8]='wslist';
$__ACTIONS['SHWISHLIST_DPC'][9]='wsdellist';
$__ACTIONS['SHWISHLIST_DPC'][10]='wsadditem';
$__ACTIONS['SHWISHLIST_DPC'][11]='wsdelitem';

//overwrite for cmd line purpose
$__LOCALE['SHWISHLIST_DPC'][0]='SHWISHLIST_CNF;Wish List;Wish List';	   
$__LOCALE['SHWISHLIST_DPC'][1]='_COST;Cost;Κόστος';	
$__LOCALE['SHWISHLIST_DPC'][2]='_ID;List Id;List Id';
$__LOCALE['SHWISHLIST_DPC'][3]='_TIME;Time;Ώρα';
$__LOCALE['SHWISHLIST_DPC'][4]='_NAME;List name;Όνομα Λίστας';
$__LOCALE['SHWISHLIST_DPC'][5]='_ADDWSTITLE;Add to Wish List;Εισαγωγή σε Wish List';
	   
class shwishlist extends wishlist {

   var $initial_word;

   function __construct() {
   
       wishlist::__construct();	

       $this->initial_word = remote_paramload('SHWISHLIST','trid',paramload('SHELL','prpath'));  
       $this->details = 1; 
   }
   
   //override
   function event($event=null) {
   
       switch ($event) {
	   
	     case 'wsadditem'     : break;
		 case 'wsdelitem'     : break;
	     
	     case 'wsviewhtml'    : //$this->viewTransactionHtml();
		                        die();
		                        break;

	     case 'wsdellist'     : $this->delete_wslist(); break;								
	     case 'wsaddlist'     : break;
		 case 'wslist'        :
		 default              : wishlist::event($event);						
	   }
   }
   
   //override
   function action($action=null) {

       switch ($action) {
	     case 'wsadditem'     : break;
		 case 'wsdelitem'     : break;	   
	   
	     case 'wsviewhtml'    : //$this->viewTransactionHtml();
		                        die();
		                        break;
								
	     case 'wsaddlist'     : $out = setNavigator(localize('SHWISHLIST_CNF',getlocal()));
		                        $ok = $this->save_wish_list();
		                         
                                if ($ok) 
								   $mywin = new window('Wish List','Ok');
								else
                                   $mywin = new window('Wish List','Error');								
                                $out .= $mywin->render();	
								
								$out .= $this->viewWishList();
								break;
		 case 'wsdellist'     :						
		 case 'wslist'        :						
		 default              : //$out = wishlist::action($action);						
		                        $out = setNavigator(localize('SHWISHLIST_CNF',getlocal()));
								
								if (GetReq('add')) {
								   $myws = new window(localize('_ADDWSTITLE',getlocal()),$this->add_selected_wslist('wsaddlist'));
	                               $out .= $myws->render("center::100%::0::group_article_body::left::0::0::") . "<hr>";

								   //$out .= $this->add_selected_wslist('wsaddlist');
								}
								
                                $out .= $this->viewWishList();
	   }
	   
	   return ($out);
   } 
   
   function add_selected_wslist($cmd=null) {
        $db = GetGlobal('db');
		$mycmd = $cmd ? $cmd : 'wslist';
	    $UserName = GetGlobal('UserName');	
	    $name = $UserName?decode($UserName):null;
		$title_addws = localize('_ADDWSTITLE',getlocal());
		$filename = seturl('t='.$mycmd);
	    
		/* SELECT WLIST
	    $sSQL = "select id,listname from wishlist where where cid=" . $db->qstr($name);
	    $res = $db->Execute($sSQL);
	    if ($res) { 
		  foreach ($res as $n=>$rec) {
	        $n[] = $rec[1].'-'.$rec[0]; 
          }
	    }  */ 
		
	    $out = "<FORM name='wsform' action=". $filename . " method=POST>" . 
               localize('_NAME',getlocal()) . ':' .		
		       "<INPUT type=\"text\" name=\"ws_name\" value=\"\" size=25>"; 	

		$out.= "<input type=\"submit\" name=\"Submit\" value=\"$title_addws\">" .
               "<input type=\"hidden\" name=\"FormAction\" value=\"$mycmd\">" .
               "</FORM>";							
		
		return ($out);		
   }

   function delete_wslist() {
         $trid = GetReq('tid');
         $db = GetGlobal('db');
	     $UserName = GetGlobal('UserName');	
	     $name = $UserName?decode($UserName):null;		 
		 
		 if (!$trid)
		    return false;
	   
	   	 $sSQL = "delete from wishlist" .
	             " where tid='" . $this->initial_word. $trid ."' and" .
				 " cid=" . $db->qstr($name);
         $result = $db->Execute($sSQL);
		
	     //print $sSQL.'>';
	     //print $db->Affected_Rows() . ">>>>";
         if ($db->Affected_Rows()) return true;
	                          else return false; 	   
   }   
   			

   function save_wish_list() {

       if (defined('SHCART_DPC'))  {

		 $buffer = GetGlobal('controller')->calldpc_var('shcart.buffer');
		 
		 if ($buffer) {

		    $data = serialize($buffer);
		 
            $ret = $this->saveWishList($data);		 
	        return ($ret);
         }	
         else
            return false;		 
	   }
	   else
	     return false;       
   }  
   
   //override
	function generate_id() {
       $db = GetGlobal('db');
	   $ses = session_id();
	   
	   $sSQL = "select tid from wishlist where tid=" . $db->qstr($ses);
	   $res = $db->Execute($sSQL,null,1);
	   //echo $sSQL; 

       $id = $res->fields[0];
		
       $ret = $id ? $id : null;		
	   //echo 'GID:',$ret; 

	   return ($ret);
	}   

    //override
	function _details($id,$storebuffer='sencart') {

	   //if (defined('SHCART_DPC')) { 
	     
	     $ret = $this->previewcart($id,'wishview');
	   //}	 
		 
	   return ($ret);	   
	} 
	
	//override
	function getWishList($tid=null) {
       $db = GetGlobal('db');
	   
	     if (!$tid)
		    return;
	   
	     $sSQL = "select tdata from wishlist where tid=" . $db->qstr($tid);
	     $res = $db->Execute($sSQL);
		 //echo $sSQL;
	     if ($res) { 
	       $out = $res->fields[0]; 
		   return ($out);
	     }
	} 	

	function previewcart($id,$callback=null,$cmd=null) {
        //echo 'PREVEWCART:',$id;
        $pview=$cmd?$cmd:'kshow';

		//if (is_number($id)) {
		if ($id) {

		   $transdata = $this->getWishList($id);
           //unserialize data
		   $buffer = unserialize($transdata);
           if (!empty($buffer)) {
           foreach ($buffer as $prod_id => $product) {

		     if (($product) && ($product!='x')) {
               $aa+=1;
		       $param = explode(";",$product);

		       $gr = urlencode($param[4]);
			   $ar = urlencode($param[1]);
			   $page = $param[5];
			   $id = $param[0];
	           $link = seturl("t=$pview&id=$id&cat=$gr&page=" , $param[1]);

			   $data[] = "<img src=\"" . $param[7] . "\" width=\"100\" height=\"75\" alt=\"\">";
	           $attr[] = "left;10%";

			   //expand
			   for ($i=0;$i<30;$i++) $expander .= "&nbsp;";
               $data[] = $param[0] . "<br>" . $link . $expander;
	           $attr[] = "left;80%";

			   $data[] = $param[9];
	           $attr[] = "center;10%";


	           $myproduct = new window('',$data,$attr);
	           $out .= $myproduct->render("center::100%::0::group_article_body::left::0::0::") . "<hr>";

	           unset ($data);
	           unset ($attr);
		       unset ($param);
		     }
	       }
           } //empty
		}
		else {
           //empty message
	       $w = new window(localize('_CART',getlocal()),localize('_EMPTY',getlocal()));
	       $out .= $w->render("center::40%::0::group_win_body::left::0::0::");//" ::100%::0::group_form_headtitle::center;100%;::");
	       unset($w);

		}

		return ($out);
	}	
	
	//override
	function saveWishList($data=null,$user=null) {
       $db = GetGlobal('db');

       $ret = 0;
	   
	   $myuser = $user?$user:$this->userid; 
	   //echo $myuser,'>>';
	   $listname = GetParam('ws_name') ? GetParam('ws_name') : 'noname';	   
			 
       $theid   = $this->generate_id();

	   if ($myuser) {
          //$id = $theid + $this->tcounter;
		  //$myid = $this->initial_word . $id;  
	      //$mydate = date('d/m/Y');//get_date("d/m/y");
          $mydate = date('Y/m/d'); //mysql...
	      $mytime = date('h:i:s A');//get_date("h:n");
	      $mydata = $data;
		  
	      if ($tid = $theid) { //exist ..update
		  
             $sSQL = "update wishlist set " .
		         'cid=' . $db->qstr($myuser) . "," .
				 'listname=' . $db->qstr($listname) . "," .
		         'tdate=' . $db->qstr($mydate) . "," .
		         'ttime=' . $db->qstr($mytime) . "," .
		         'tdata=' . $db->qstr($mydata) . "," . 
		         'tstatus=1'.
				 ' where tid=' .$db->qstr($tid);				 				 				 
	 
          }
          else {
             $tid = session_id();
			 
             $sSQL = "insert into wishlist (tid,cid,listname,tdate,ttime,tdata,tstatus) values " .
                 "(" .
		         $db->qstr($tid) . "," .
		         $db->qstr($myuser) . "," .
				 $db->qstr($listname) . "," .
		         $db->qstr($mydate) . "," .
		         $db->qstr($mytime) . "," .
		         $db->qstr($mydata) . "," . 
		         "0" . ")";				 				 				 

	       }
	       $res = $db->Execute($sSQL,1);

		   //echo $sSQL;
		   //print $db->Affected_Rows();
		   //echo '>>>>',$res;

           if ($db->Affected_Rows()) $ret = true;
	                            else $ret = false;   
	   }
	   //print $ret;

	   return ($ret);
	}	
	
	//override
	function getWSLists() {
       $db = GetGlobal('db');
       $UserName = GetGlobal('UserName');	
	   $name = $UserName?decode($UserName):null;		   
	   
	   if (!$name) return;
	   	
	     $sSQL = "select tid,tdate,ttime,tstatus,listname,recid from wishlist where cid=" . $db->qstr($name) . 
		         "order by recid DESC";				 
		 //echo $sSQL;
		 
	     $res = $db->Execute($sSQL,2);
	     //print_r ($res->fields[5]);
		 $i=0;
	     if (!empty($res)) { 
	       foreach ($res as $n=>$rec) {
		    $i+=1;		
			
            $transtbl[] = $rec[5] . ";" .$rec[0] . ";" .$rec[4] . ";" .$rec[1] . ";" .$rec[3];
		   
		   }
		   
           //browse
		   //print_r($transtbl); 
		   $ppager = GetReq('pl')?GetReq('pl'):10;
           $browser = new browse($transtbl,null,$this->getpage($transtbl,$this->searchtext));
	       $out .= $browser->render("wishview",$ppager,$this,1,0,0,0);
	       unset ($browser);	
		      
	     }
		 else {
           //empty message
	       $w = new window(null,localize('_EMPTY',getlocal()));
	       $out .= $w->render("center::40%::0::group_win_body::left::0::0::");//" ::100%::0::group_form_headtitle::center;100%;::");
	       unset($w);

		 }		 
	   
	   return ($out);
	} 	
	
	//override
    function view_ws($id,$did,$dname,$ddate,$status) {
	   $p = GetReq('p');
	   $a = GetReq('a');
	   
	   
       if ($this->admint>0) {//==1) {
			   //print checkbox 
			   $data[] = "<input type=\"checkbox\" name=\"" . $id . 
			                                  "\" value=\"" . $id . "\">"; 
	           $attr[] = "left;5%";											  
	   }										  	   
	   					  
	   $link = seturl("t=wsloadcart&tid=$did" , $id);
	   $data[] = $id;//$link;   
	   $attr[] = "left;20%";
	   
	   $link2 = seturl("t=wsloadcart&tid=$did" , $dname);
	   $data[] = $link2;   
	   $attr[] = "left;25%";   
	   
	   $data[] = $ddate;   
	   $attr[] = "left;25%";	      
	   
	   $link_del = seturl("t=wsdellist&tid=$did" , 'Delete');
	   $st = $status?$status.'-':'Active-';
	   $data[] = $st .$link_del ;  
	   $attr[] = "left;25%";		   
	   
	   
	   $myarticle = new window('',$data,$attr);
       $line = $myarticle->render("center::100%::0::group_dir_body::left::0::0::");
	   unset ($data);
	   unset ($attr);
	   
       if ($this->details) {//disable cancel and delete form buttons due to form elements in details????
	   
	     $mydata = $line . '<br/>' . $this->_details($did);
		 
		 if (defined('WINDOW2_DPC')) {
	       $cartwin = new window2($id .'-' . $dname, $mydata,null,1,null,'HIDE',null,1);
	       $out = $cartwin->render();//"center::100%::0::group_article_body::left::0::0::"
	       unset ($cartwin);		   
		 }
		 else {
	       $cartwin = new window($id . '/' . $status,$mydata);
	       $out = $cartwin->render();//"center::100%::0::group_article_body::left::0::0::"
	       unset ($cartwin);		 
		 }   
	   }	
	   else {   
		 $out .= $line . '<hr>';
	   }	   
	   

	   return ($out);
	}
    //override
	function headtitle() {
	   $p = GetReq('p');
	   $t = GetReq('t');
	   $sort = GetReq('sort');  
	
       $data[] = '&nbsp;';
	   $attr[] = "left;5%";							  
	   $data[] = seturl("t=$t&a=&g=2&p=$p&sort=$sort&col=1" , localize('_ID',getlocal()) );
	   $attr[] = "left;20%";
	   $data[] = seturl("t=$t&a=&g=3&p=$p&sort=$sort&col=2" , localize('_NAME',getlocal()) );
	   $attr[] = "left;25%";
	   $data[] = seturl("t=$t&a=&g=4&p=$p&sort=$sort&col=3" , localize('_DATE',getlocal()) );
	   $attr[] = "left;25%";
	   $data[] = seturl("t=$t&a=&g=4&p=$p&sort=$sort&col=4" , localize('_WSSTAT',getlocal()) );
	   $attr[] = "left;25%";	   

  	   $mytitle = new window('',$data,$attr);
	   $out = $mytitle->render(" ::100%::0::group_form_headtitle::center;100%;::");
	   unset ($data);
	   unset ($attr);	
	   
	   return ($out);
	}		
};
}
?>