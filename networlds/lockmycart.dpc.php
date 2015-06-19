<?php

$__DPCSEC['LOCKMYCART_DPC']='2;1;1;2;2;2;2;2;9';

if ((!defined("LOCKMYCART_DPC")) && (seclevel('LOCKMYCART_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("LOCKMYCART_DPC",true);

$__DPC['LOCKMYCART_DPC'] = 'lockmycart';

$d = GetGlobal('controller')->require_dpc('shop/shcart.dpc.php');
require_once($d);

GetGlobal('controller')->get_parent('SHCART_DPC','LOCKMYCART_DPC');

$__EVENTS['LOCKMYCART_DPC'][11]= "pricelist";
$__EVENTS['LOCKMYCART_DPC'][12]= "addpack";
$__EVENTS['LOCKMYCART_DPC'][13]= "rrenew";//user pay page

$__ACTIONS['LOCKMYCART_DPC'][11]= 'pricelist';
$__ACTIONS['LOCKMYCART_DPC'][12]= 'addpack';
$__ACTIONS['LOCKMYCART_DPC'][13]= 'rrenew';//user pay page

//print_r($__ACTIONS['LOCKMYCART_DPC']);

//overwrite for cmd line purpose
/*$__LOCALE['LOCKMYCART_DPC'][0]='LOCKMYCART_DPC;My Cart;Καλάθι Αγορών';
$__LOCALE['LOCKMYCART_DPC'][1]='_GRANDTOTAL;Grand Total;Γενικό Σύνολο';
$__LOCALE['LOCKMYCART_DPC'][2]='loginorregister;Login or Register for a new account;Παρακαλώ προχωρείστε στις απαιτούμενες ενέργειες!';
$__LOCALE['LOCKMYCART_DPC'][3]='_IWAY;Notice of pay;Τυπος Παραστατικού';
$__LOCALE['LOCKMYCART_DPC'][4]='_INVOICE;Invoice;Τιμολόγιο';
$__LOCALE['LOCKMYCART_DPC'][5]='_APODEIXI;Receipt;Αποδειξη';
$__LOCALE['LOCKMYCART_DPC'][6]='_DELIVADDRESS;Delivery Address;Παράδοση σε αλλη διευθυνση';
$__LOCALE['LOCKMYCART_DPC'][7]='_TAX;Tax;ΦΠΑ';*/


class lockmycart extends shcart {

    var $costperrecord;
	var $ispaid;
	
	//user pay
	var $appname, $apptype, $userpay;

    function lockmycart() {
       $UserName = GetGlobal('UserName');		

       shcart::shcart();
	   
	   if ((defined('LOCKMYV2_DPC')) && ($c = GetReq('c'))) { //user pay
	   
	     //echo 'z';
	     $dkey = GetGlobal('controller')->calldpc_method('lockmy.my_decode use '.$c);
		 $p = explode('<DML>',$dkey);
		 $this->appname = $p[0]; //domain or page
		 $this->apptype = $p[1]; //user
		 $pragma = $p[2];  //pragma		 
	   }
	   else {
	     $cpr = remote_paramload('LOCKMYCART','costperrecord',$this->path);
	     $this->costperrecord = $cpr?floatval($cpr):1.3;	 
	     //echo '>',$this->myfinalcost;			  
	   }
	   
	   $this->ispaid = GetSessionParam('ispaid')?true:false;
	   $this->userpay = (array)  GetSessionParam('userpay');	   
    }

    //override
    function event($event) {	

	  switch ($event) {  
	  
	    case 'addpack' : //SetParam("PRESELQTY",12);//?????
		                 $this->addtocart(GetReq('a'),$records);
		                 break;
						 
	    default        : if (GetGlobal('UserID')) {
	                       $this->auto_add2cart_service();//$this->ispaid);	
	                     }
						 elseif ($this->apptype) { //user pay
						   $this->auto_add2cart_service();
						 }
		                 shcart::event($event);
	  } 	   
    }

	//override
    function action($act=null) {	

	   switch ($act) {
	    case 'addpack' :	   
	    default        : $out = shcart::action($act);	   
       }

	   return ($out);
    }
	
	function auto_add2cart_service($ispaid=null,$paycode=null,$payitem=null,$payval=0,$payqty=1) {

	   if ($p = GetReq('p')) {
	      //echo $p,'>';
	   
	      $params = decode($p);
		  $parts = explode(';',$params);
		  
		  $records = intval($parts[1]);
		  
		  if ($records>0) {
		    $this->ispaid = false;
		    $cost = $this->costperrecord;
			$this->addtocart("365;Lockmy url records;path;template;group;page;url records for mylock;photo;$cost;$records;",$records,0);
		  }	
	      elseif ($records<0) {
		    $this->ispaid = true;
		    $cost = 0;		  
		    $this->addtocart("365;Lockmy url already paid records;path;template;group;page;url records for mylock;photo;$cost;$records;",$records,1);		  
		  }
		  //save
		  SetSessionParam('ispaid',$this->ispaid);			
	   }
	   elseif ($this->appname) { //user pay
	   
	     $dpay = GetGlobal('controller')->calldpc_method('lockmy.get_pay_details use '.$this->appname);
	     $pd = explode('<@>',$dpay);
		 //print_r($pd); 
		 
		 $records = $payqty;
		 $cost = $pd[3]?floatval($pd[3]):$payval;
		 $code = $pd[4]?$pd[4]:$paycode;
		 $title = $pd[5]?$pd[5]:payitem;
		 $this->addtocart("$code;$title;path;template;group;page;$title;photo;$cost;$records;",$records,1);
		 SetSessionParam('userpay',$pd);		 		 
	   }
	}
	
	function addpack($records=null,$cost=null) {
	
		$ret = $this->showsymbol("365;Lockmy url records;path;template;group;page;url records for mylock;photo;$cost;$records;",'',0);	
		return ($ret);
	}
	
	function addfrominventory($records=null,$cost=null) {
	
		$ret = $this->showsymbol("365;Lockmy url already paid records;path;template;group;page;url records for mylock;photo;$cost;$records;",'',0);	
		return ($ret);
	}		
	
	//override
	function addtocart($item=null,$qty=null,$cartstatus=null) {
	   $a = $item?$item:GetReq('a');
	   $qty = $qty?$qty:GetReq('qty');
	   $status = $cartstatus?cartstatus:0;
	   //echo '>',$status;
	   
	   $this->clear();//no refresh add..only one item 
	   
       if ($this->_count() < $this->maxcart) { //check cart maximum items
								 
			//get selected quantity number
			$preqty = $qty?$qty:GetParam("PRESELQTY");
			//$preuni = GetParam("PRESELUNI");
			
			//if ((is_number($preqty)) && ($preqty>0)) {
			    //echo $a;
			    $params = explode(";",$a);
									 
				$params[9]= $preqty;
				$b = implode(";",$params);
				//echo $b;
				$this->addto($b);
		     /*}  
			 else {
			    //$this->addto($a); 
			    $input_message = localize('_INPUTERR',getlocal());
                if (iniload('JAVASCRIPT')) {
	               $code = "alert('$input_message')";	   	
		           $js = new jscript;	 	      
                   $js->load_js($code,"",1);		   			   
		           unset ($js);
	            }
				else
				   setInfo($input_message);				
			 }*/	
				
			 if (GetGlobal('UserID'))
			   $st = $status;
			 else 
			   $st = 0;  
			   	
		     SetSessionParam('cartstatus',$st); 
			 $this->status = $st; 
		 }
		 else 
		   setInfo(localize('_MSG15',getlocal()));
		   
		 //reset paid
		 SetSessionParam('ispaid',null);		   	
		   	   
	}
		
	//overwrite
    function showsymbol($id,$group,$page,$allowremove=null,$qty=null,$paydays=null) {
	  $gr = urlencode($group);
	  $ar = urlencode($id);
	  $param = explode(";",$id);
	  $paydays = $paydays?intval($paydays):0;
      // print_r($id);
	  //echo $id;	  
	  $qty = $qty?$qty:$param[9];
	  $price = $param[8];
	  $ypoA = $param[14];
	  
      if (floatval(str_replace(",",".",$price))>0.001) {//check price
	   //if ((!$this->ignoreqtyzero) && ($ypoA>0)) {//check availability..NOT WORK

	     //if (!($this->isin($param[0]))) {

	       if ($this->bypass_qty) {
             $myaction = seturl("t=addtocart&a=$ar&cat=$gr&page=$page");

	         $out = "<FORM method=\"POST\" action=\"";
             $out .= "$myaction";
             $out .= "\" name=\"PreSelectQty\">";
		     $out .= $this->setquantity('PRESELQTY',1);

			 if (($this->uniname2) && ($param[11]))
			   $out .= "<br>" . $this->setuniname('PRESELUNI',$param[10],$param[10],$param[11]);

             $out .= $this->submit_qty_button;//"<input type=\"submit\" name=\"Ok\" value=\"Ok\">";
		     $out .= "</FORM>";
		   }
		   else
		     ///////////////////////////////////////////////////////////////// change cmd
             $out .= seturl("t=addpack&a=$ar&qty=".$qty,$this->addcart_button);
	     /*}
	     else {
		   
		   if (($this->notallowremove)&&(!$allowremove))//add again 		   	 		   
		     $out .= seturl("t=addtocart&a=$ar&cat=$gr&page=$page",$this->addcart_button);	
           else//add again 		   	 		   
             $out = seturl("t=removefromcart&a=$ar&cat=$gr&page=$page",$this->remcart_button); 
	     }*/
	   //}
	   //else $out = $this->notavail;
	  }
	  else {
	    //echo '<br>|',$price;
		//print_r($param);
	    $out = $this->notavail;
	  }	

      return ($out);
	}
	
    //overwrite..adding db data for records 
    /*function submit_order() {
       $orderdataprint = GetSessionParam('orderdataprint');
	   $myuser = GetGlobal('UserID');	
       $user = decode($myuser);
       $pways = remote_arrayload('SHCART','payways',paramload('SHELL','prpath'));
       $rways = remote_arrayload('SHCART','roadways',paramload('SHELL','prpath'));
       $payway = GetParam('payway')?GetParam('payway'):GetSessionParam('payway');
       $roadway = GetParam('roadway')?GetParam('roadway'):GetSessionParam('roadway');
       $invway = GetParam('invway')?GetParam('invway'):GetSessionParam('invway');	
	   
	   if (!empty($pways))   
         $p = array_keys($pways,$payway);//print_r($p);
	   if (!empty($rways))   	 
         $r = array_keys($rways,$roadway);//print_r($r); echo ' >';
	   
	   $this->quick_recalculate();//re-update prices and totals
	   	   
	   $qty = $this->qty_total;//getcartItems();
	   $cost = str_replace(',','.',$this->total);//getcartTotal());
	   $costpt = str_replace(',','.',$this->myfinalcost);//getcartSubtotal());
       //echo $this->qtytotal,'-',$this->total;

       if ( (defined('LOCKMYTRANSACT_DPC')) && (seclevel('LOCKMYTRANSACT_DPC',decode(GetSessionParam('UserSecID')))) ) {
	   
	     $this->transaction_id = GetGlobal('controller')->calldpc_method('lockmytransact.saveTransaction use '.serialize($this->buffer)."+$user+$payway+$roadway+$qty+$cost+$costpt+$tmap");
	     GetGlobal('controller')->calldpc_method('lockmytransact.saveTransactionHtml use '.$this->transaction_id.'+'.serialize($orderdataprint));
	     		 
	   }
	   else
	     $this->transaction_id = '9999999';

	   SetSessionParam('TransactionID',$this->transaction_id);

   	   if ($this->transaction_id) {
             $this->goto_mailer();
			 
             if (!$this->mailerror) {
			   //print action
			   $this->goto_printer();
               //finaly clear cart
               $this->clear();
			 }
	   }
    }*/
	
    //override
	function finalize_cart_success($transno=null) {
	    $myuser = GetGlobal('UserID');	
        $user = decode($myuser);	
        //echo '>',$user;
		$transno = $transno ? $transno : $this->transaction_id;
		
	    if (defined('LOCKMYTRANSACT_DPC'))  {
		
		  if (!empty($this->userpay)) { //user pay
		    print_r($this->userpay);
		  }
		  else {
		
	      /////////////////////////////////////////////////// lockmytransact hack
	      $qty = GetGlobal('controller')->calldpc_method('lockmytransact.get_qty use '.$transno);
		  $paydays = GetGlobal('controller')->calldpc_method('lockmytransact.get_days use '.$transno);		
          //echo '>',$qty;
		  //echo '>',$paydays;
	      /////////////////////////////////////////////////// lockmy hack
	      if (defined('LOCKMY_DPC')) { 
	        $tmap = GetGlobal('controller')->calldpc_method('lockmy.update_paid_extra_records use '.$user.'+'.$qty.'+'.$paydays);
		  }  
	      else
	        die('LOCKMY_DPC required!');
					  
	      /////////////////////////////////////////////////// lockmytransact hack
	      $ret = GetGlobal('controller')->calldpc_method('lockmytransact.set_map_status use '.$transno.'+'.$tmap);
		  //echo '>',$ret;		 	 	 	 
		  }
		}  
	    else
	      die('LOCKMYTRANSACT_DPC required!');		  
		   
		   
		//call parent method as is   
        $out = shcart::finalize_cart_success($transno);

        return ($out);
    }	
	
	//override paywway to handle it depend on inventory records or pay records
	function payway($token=null) {

	       $pways = remote_arrayload('SHCART','payways',$this->path);
		   if (!$pways) return null;
		   //print_r($pways);
		   $defpay = remote_arrayload('SHCART','payway_default',$this->path);
		   $default_pay = $defpay?$defpay:0; 
		   		   
		   foreach ($pways as $i=>$w) {
		     $lans_titles = explode('/',$w);
		     $choices[] = $lans_titles[getlocal()];
		   }
		   //print_r($choices);
		   $params = implode(',',$choices);
		   ////////////////////////////////////////////////// choice to pay or to order from inventory
		   if ($this->ispaid===true) {
		     $default_pay = 0; //when two choices exists
		     //$params = $choices[0]; //order
		   }	 
		   else {
		     $default_pay = 1; //when two choices exists
		   	 //$params = $choices[1];// 1  //paypal
		   }	 
		   //echo $params;	 
           /////////////////////////////////////////////////////////////////////////////////////////

           switch ($this->status) {
			 case 1 ://reset session param /////////////////////////////////
		             SetSessionParam('payway',null);
					 $pp = new multichoice('payway',$params,$default_pay,false);
					 $radios = $pp->render();
					 unset($pp);

	                 $data1[] = localize('_PWAY',getlocal());
                     $attr1[] = "left;20%";
	                 $data1[] = $radios;
                     $attr1[] = "left;80%";
                     $myway = new window('',$data1,$attr1);
					 if ($token)
					   $out = $myway->render();
					 else					 
                       $out = $myway->render(" ::100%::0::group_article_body::center;100%;::");
		             unset ($myway);
                     unset ($data1);
                     unset ($attr1);
					 
		             $out .= "<hr>";						 
		             break;

		     case 2 :$mypway = GetParam("payway")?GetParam("payway"):GetSessionParam("payway");
                     $out = localize('_PWAY',getlocal()) . " : " . $mypway;
		 	         //hold param
                     SetSessionParam('payway',$mypway);	
		             $out .= "<hr>";						 				 
			         break;

			 default : //$out = null;
			 	       //reset session param /////////////////////////////////
		               SetSessionParam('payway',null);
		   }

		   return ($out);
	}		
		
};
}
?>