<?php

$__DPCSEC['CART_DPC']='2;1;1;2;2;2;2;2;9';

if ((!defined("CART_DPC")) && (seclevel('CART_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("CART_DPC",true);

$__DPC['CART_DPC'] = 'cart'; 

$__EVENTS['CART_DPC'][0]= localize('_CHKOUT',getlocal());
$__EVENTS['CART_DPC'][1]= localize('_ORDER',getlocal());
$__EVENTS['CART_DPC'][2]= localize('_RECALC',getlocal());
$__EVENTS['CART_DPC'][3]= localize('_SUBMITORDER',getlocal());	
$__EVENTS['CART_DPC'][4]= localize('_CANCELORDER',getlocal());
$__EVENTS['CART_DPC'][5]= "addtocart"; 					 	
$__EVENTS['CART_DPC'][6]= "removefromcart"; 
$__EVENTS['CART_DPC'][7]= "clearcart";
$__EVENTS['CART_DPC'][8]= "loadcart";
$__EVENTS['CART_DPC'][9]= "printcart";
$__EVENTS['CART_DPC'][10]= "transcart";

$__ACTIONS['CART_DPC'][0]= "viewcart";
$__ACTIONS['CART_DPC'][1]= "clearcart";
$__ACTIONS['CART_DPC'][2]= "loadcart"; 
$__ACTIONS['CART_DPC'][3]= "removefromcart";
$__ACTIONS['CART_DPC'][4]= "printcart";
$__ACTIONS['CART_DPC'][5]= localize('_CHKOUT',getlocal());
$__ACTIONS['CART_DPC'][6]= localize('_ORDER',getlocal());
$__ACTIONS['CART_DPC'][7]= localize('_RECALC',getlocal());
$__ACTIONS['CART_DPC'][8]= localize('_SUBMITORDER',getlocal());	
$__ACTIONS['CART_DPC'][9]= localize('_CANCELORDER',getlocal());	
$__ACTIONS['CART_DPC'][10]= 'transcart';

$__DPCATTR['CART_DPC']['viewcart'] = 'viewcart,1,0,1,0,0,0,0,0,0'; 
$__DPCATTR['CART_DPC']['loadcart'] = 'loadcart,1,0,1,0,0,0,0,0,0'; 
$__DPCATTR['CART_DPC'][localize('_RECALC',getlocal())] = '_RECALC,1,0,1,0,0,0,0,0,0';
$__DPCATTR['CART_DPC'][localize('_CANCELORDER',getlocal())] = '_CANCELORDER,1,0,1,0,0,0,0,0,0';
$__DPCATTR['CART_DPC'][localize('_ORDER',getlocal())] = '_ORDER,1,0,1,0,0,0,0,0,0'; 
$__DPCATTR['CART_DPC'][localize('_SUBMITORDER',getlocal())] = '_SUBMITORDER,1,0,1,0,0,0,0,0,0';
$__DPCATTR['CART_DPC'][localize('_CHKOUT',getlocal())] = '_CHKOUT,1,0,1,0,0,0,0,0,0';
$__DPCATTR['CART_DPC']['addtocart'] = 'addtocart,0,0,0,0,0,1,0,0,1';
$__DPCATTR['CART_DPC']['removefromcart'] = 'removefromcart,0,0,0,0,0,1,0,0,1';
	
$__LOCALE['CART_DPC'][0]='CART_DPC;My Cart;Καλάθι Αγορών';
$__LOCALE['CART_DPC'][1]='_RESET;Reset;Καθαρισμός';
$__LOCALE['CART_DPC'][2]='_EMPTY;Empty;Αδειο';
$__LOCALE['CART_DPC'][3]='_BLN3;Clear Cart;Αδειασμα Καλαθιού';
$__LOCALE['CART_DPC'][4]='_BLN2;Remove from Cart;Αφαίρεση απο το Καλάθι';
$__LOCALE['CART_DPC'][5]='_BLN1;Add to Cart;Προσθήκη στο Καλάθι';
$__LOCALE['CART_DPC'][6]='_MSG16;Prices does not include taxes;Οι τιμές δεν συμπεριλαμβάνουν ΦΠΑ 19%';
$__LOCALE['CART_DPC'][7]='_MSG15;Your cart is full ! ! !;Το καλάθι σας είναι γεμάτο ! ! !';
$__LOCALE['CART_DPC'][8]='_MSG14;Your order submited successfully! Thank you!;Η παραγγελία σας εκτελέστηκε επιτυχώς !';
$__LOCALE['CART_DPC'][9]='_QTY;Qty;Τεμ.';
$__LOCALE['CART_DPC'][10]='_PRICE;Price;Τιμή';
$__LOCALE['CART_DPC'][11]='_DESCR;Description;Περιγραφή';
$__LOCALE['CART_DPC'][12]='_STOTAL;sTotal;Σύνολο';
$__LOCALE['CART_DPC'][13]='_TOTAL;Total;Σύνολο';
$__LOCALE['CART_DPC'][14]='_RECALC;Recalculate;Υπολογισμός';
$__LOCALE['CART_DPC'][15]='_CHKOUT;Check Out;Ταμείο';
$__LOCALE['CART_DPC'][16]='_ORDER;Order;Επιβεβαίωση';
$__LOCALE['CART_DPC'][17]='_CANCELORDER;Cancel;Ακύρωση';
$__LOCALE['CART_DPC'][18]='_SUBMITORDER;Submit Order;Ολοκλήρωση Συναλλαγής';
$__LOCALE['CART_DPC'][19]='_PRINT;Print;Εκτύπωση';
$__LOCALE['CART_DPC'][20]='_CLOSE;Close;Κλείσιμο';
$__LOCALE['CART_DPC'][21]="_ACCDENIED;Sorry you don't have the appropriate priviliges.;Δέν έχετε το απαραίτητο δικαίωμα.";
$__LOCALE['CART_DPC'][22]='_CONTENTS;Contents;Περιεχόμενα';
$__LOCALE['CART_DPC'][23]='_NOTAVAL;Not available;Μη διαθέσιμο';
$__LOCALE['CART_DPC'][24]='_TAX;Tax;Φόρος';
$__LOCALE['CART_DPC'][25]='_SHIPCOST;Shipping Cost;Έξοδα αποστολής';
$__LOCALE['CART_DPC'][26]='_DISCOUNT;Discount;Εκπτωση';
$__LOCALE['CART_DPC'][27]='_FCOST;Final cost;Πληρωτέο';
$__LOCALE['CART_DPC'][28]='_BOXTYPE;Box;Συσκ.';
$__LOCALE['CART_DPC'][29]='_CART;Shop Cart;Καλάθι Αγορών';
$__LOCALE['CART_DPC'][30]='_RWAY;Shipping:;Τρόπος Αποστολής';
$__LOCALE['CART_DPC'][31]='_RWAY1;By Car;Οδικώς';
$__LOCALE['CART_DPC'][32]='_RWAY2;Courier;Courier';
$__LOCALE['CART_DPC'][33]='_RWAY3;ELTA;ΕΛΤΑ';
$__LOCALE['CART_DPC'][34]='_PWAY;Pay with:;Τρόπος Πληρωμής';
$__LOCALE['CART_DPC'][35]='_PWAY1;Trust my account;Χρέωση του λογαριασμου μου';
$__LOCALE['CART_DPC'][36]='_PWAY2;Cash on delivery;Αντικαταβολή';
$__LOCALE['CART_DPC'][37]='_PWAY3;VISA;VISA';
$__LOCALE['CART_DPC'][38]='_ENDOK;Thank you! Your order submited successfully with Order No :;Ευχαριστούμε ! Η παραγγελία σας εκτελέστηκε επιτυχώς με αριθμό Παραγγελίας :';
$__LOCALE['CART_DPC'][39]='_SXOLIA;Comments;Σχόλια;';
$__LOCALE['CART_DPC'][40]='_CARTERROR;Error during transaction;Λαθος εκτελεσης;';
$__LOCALE['CART_DPC'][41]='_STOCKOUT; is out of stock!; δεν υπαρχει απόθεμα!;';
$__LOCALE['CART_DPC'][42]='_INPUTERR;Invalid entry!;Λανθασμένη ποσότητα!;';

$__PARSECOM['CART_DPC']['quickview']='_VIEWCART_';

$__DPCEXT['CART_DPC']='showsymbol';

//require_once("storebuffer.lib.php");
GetGlobal('controller')->include_dpc('storebuffer/storebuffer.lib.php');
//require_once(GetGlobal('controller')->require_dpc('storebuffer/storebuffer.lib.php'));

class cart extends storebuffer {

    var $userLevelID;
    var $url;
	var $addcart_button;
	var $remcart_button;
	var $status;
	var $maxqty;
	var $total;
	var $qtytotal;
	var $shippingcost;
	var $moneysymbol;
	var $discount;
	var $maxcart;
	var $tax;	
	var $carterror_mail;
	var $transaction_id;
	var $printer;
	var $prn_device;

    var $checkout;				 	
    var $order;
	var $submit;
	var $cancel;
	var $recalc;

	var $confini;
	var $view;
	
	var $mailerror;
	var $submit_qty_button;
	var $bypass_qty;
	var $dec_num;
	var $notavail;
	
	var $agent;

	function cart($sesname='cart') {
	    $GRX = GetGlobal('GRX');	
	    $UserSecID = GetGlobal('UserSecID');
		$__USERAGENT = GetGlobal('__USERAGENT');
		
		$TransactionID = GetSessionParam('TransactionID');		
		
        $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
		$this->agent = $__USERAGENT;

		storebuffer::storebuffer($sesname);

        if ($GRX) {   
             $this->addcart_button  = loadTheme('addcart_b',localize('_BLN1',getlocal()));
             $this->remcart_button  = loadTheme('remcart_b',localize('_BLN2',getlocal()));
             $this->resetcart_button= loadTheme('resetcart_b',localize('_BLN3',getlocal()));
             $this->rightarrow = loadTheme('rarrow');	
			 
             $this->submit_qty_button  = "<input type=\"image\" src=\"". loadTheme('addcart_b',localize('_BLN1',getlocal()),1) ."\">";
             $this->notavail = loadTheme('notavail',localize('_NOTAVAL',getlocal()));	     			 		 
        }
		else {
             $this->addcart_button  = '[+]';
             $this->remcart_button  = '[-]';
             $this->resetcart_button= localize('_RESET',getlocal());
             $this->rightarrow = ">";
			 
             $this->submit_qty_button  = "<input type=\"submit\" name=\"Submit\" value=\"[+]\">";			 
             $this->notavail = localize('_NOTAVAL',getlocal());				 
		}	

		
        //set max qty per user
		$maxarray = arrayload('CART','maxqtypercl');
		$this->maxqty = $maxarray[$this->userLevelID];
		if (!$this->maxqty) $this->maxqty = paramload('CART','maxqty');		
		
		//set qty method selection per user (admin must be off)
		$qtymethod = arrayload('CART','bypassqty');
		$this->bypass_qty = $qtymethod[$this->userLevelID];
		
		$this->status = GetSessionParam('cartstatus');
		$this->total = (double) 0.0;
  	    $this->qtytotal = 0;
        $this->dec_num = paramload('CART','decimals');		
		$this->tax = paramload('CART','taxcostpercent');		
        $this->shippingcost = (double) paramload('CART','shipcost'); 
        $this->discount = paramload('CART','discount');     
        $this->moneysymbol = "&" . paramload('CART','cursymbol') . ";";  
		$this->maxcart = paramload('CART','maxcart');
		$this->carterror_mail = paramload('CART','carterr');		
		$this->cartsend_mail = paramload('CART','cartsender');	
		$this->cartreceive_mail = paramload('CART','cartreceiver');					
		$this->transaction_id = GetSessionParam('TransactionID');
		$this->prn_device = paramload('CART','printer');
		$this->view = paramload('CART','dview');		
		$this->mailerror = 0;
		$this->sxolia = null;

        $this->checkout    = trim(localize('_CHKOUT',getlocal()));				 	
        $this->order       = trim(localize('_ORDER',getlocal()));
	    $this->submit      = trim(localize('_SUBMITORDER',getlocal()));
	    $this->cancel      = trim(localize('_CANCELORDER',getlocal()));
	    $this->recalc      = trim(localize('_RECALC',getlocal()));
		
		$this->url = paramload('SHELL','urlbase');
		
	    if ((defined('TRANSFORM_DPC')) && (seclevel('TRANSFORM_DPC',decode(GetSessionParam('UserSecID'))))) {		
		   $this->transformer = new transbuffer('transcart',$this->buffer);	
		}  
		
	}

    function event($com) { 
       $orderdataprint = GetSessionParam('orderdataprint');
	   $a = GetReq('a');
	   
       switch ($com) {		
          case "addtocart"     : $this->addtocart();
								 break;					 	
          case "removefromcart": $this->remove($a); 
		                         SetSessionParam('cartstatus',0); 
								 $this->status = 0; 
								 break;
          case "clearcart"     : $this->clear(); 
		                         SetSessionParam('cartstatus',0); 
								 $this->status = 0; 
								 break;	
          case "loadcart"      : $this->loadcart(); 
		                         SetSessionParam('cartstatus',0); 
								 $this->status = 0; 
								 break;			  
		  case "printcart"     : if ($orderdataprint) {
			                        $prn = $this->printorder($orderdataprint); 
                                    SetSessionParam('orderdataprint',0); //???? re-print
									echo $prn; 
									exit;
		                         }

          case $this->checkout : SetSessionParam('cartstatus',1); 
		                         $this->status = 1; 
								 $this->recalculate(); 
								 break;					 	
          case $this->order    : SetSessionParam('cartstatus',2); 
		                         $this->status = 2; 
								 break;
		  case $this->submit   : SetSessionParam('cartstatus',3); 
		                         $this->status = 3; 
								 $this->submit_order(); 
								 break;
		  case $this->cancel   : SetSessionParam('cartstatus',0); 
		                         $this->status = 0; 
								 $this->cancel_order(); 
								 break;
		  case $this->recalc   : SetSessionParam('cartstatus',0); 
		                         $this->recalculate(); 
								 break;
       }
	}
 
    function action($act=null) {
	
	   switch ($act) {
		 case "transcart" : $out = setNavigator(localize('_CART',getlocal())); 
		                    if (is_object($this->transformer))	
		                        $out .= $this->transformer->transform();
		                    break;     
	     default          : $out .= $this->cartview();
       }
	   
	   
	   $cfp = new frontpage('cart',0);
	   $cpout = $cfp->render($out);
	   unset($cfp);	  
	   	   
       return ($cpout);
    }
	
	function addtocart() {
	   $a = GetReq('a');
	   
       if ($this->_count() < $this->maxcart) { //check cart maximum items
								 
			//get selected quantity number
			$preqty = GetParam("PRESELQTY");
			$preuni = GetParam("PRESELUNI");
			
			if ((is_number($preqty)) && ($preqty>0)) {
			    //echo $a;
			    $params = explode(";",$a);
									 
				$params[9]= $preqty;
				$b = implode(";",$params);
				//echo $b;
				$this->addto($b);
		     }  
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
			 }	
				
		     SetSessionParam('cartstatus',0); 
			 $this->status = 0; 
		 }
		 else 
		   setInfo(localize('_MSG15',getlocal()));	
		   	   
	}

    //re-defined function
    function isin($id) {

        reset ($this->buffer); 
        //while (list ($buffer_num, $buffer_data) = each ($this->buffer)) { 
        foreach ($this->buffer as $buffer_num => $buffer_data) {

		   $param = explode(";",$buffer_data);	
           if ($param[0] == $id) return true;                                    
        }                       
        return false;
    } 
	
    //re-defined function
    function remove($id) {

        $myid = explode(";",$id);

        reset ($this->buffer);
        //while (list ($buffer_num, $buffer_data) = each ($this->buffer)) {             
        foreach ($this->buffer as $buffer_num => $buffer_data) {		
			
		   $param = explode(";",$buffer_data);
		   
           if ($param[0] == $myid[0]) { 
                 $this->buffer[$buffer_num] = "x";  
                 break;
           }                                   
        }                    
		$this->setStore();
    }
	
	function loadcart() {
	    $a = GetReq('a');
		
		$transdata = array();
		
		if (is_number($a)) {
         if ( (defined('TRANSACTIONS_DPC')) && (seclevel('TRANSACTIONS_DPC',decode(GetSessionParam('UserSecID')))) ) {

		    $transdata = GetGlobal('controller')->calldpc_method('transactions.getTransaction use '.$a);
			
			//unserialize data
			$decodetrans = unserialize($transdata);
			//replace cart buffer
			$this->buffer = $decodetrans;
			$this->setStore();
		  }
		}
	}
	
    function showsymbol($id,$group,$page) {
	  
	   $param = explode(";",$id);

	   $gr = urlencode($group);
	   $ar = urlencode($id);	   
	   
	   if (!($this->isin($param[0]))) {
	
	       if ($this->bypass_qty) {   
             $myaction = seturl("t=addtocart&a=$ar&g=$gr&p=$page");	   								 
	   
	         $out = "<FORM method=\"POST\" action=\"";
             $out .= "$myaction";
             $out .= "\" name=\"PreSelectQty\">";
		     $out .= $this->setquantity('PRESELQTY',1);
				 
             $out .= $this->submit_qty_button;//"<input type=\"submit\" name=\"Ok\" value=\"Ok\">";
		     $out .= "</FORM>";
		   }
		   else		   
             $out .= seturl("t=addtocart&a=$ar&g=$gr&p=$page",$this->addcart_button); 
	   }
	   else {
           $out = seturl("t=removefromcart&a=$ar&g=$gr&p=$page",$this->remcart_button); 
	   }									 		   

	   return ($out);
	}

	function quickview() {

	   switch ($this->agent) {
	   
	     case 'XML'  : break;
         case 'XUL'  :
		 case 'GTK'  : 
		               $xml = new pxml('');
		               $xml->addtag('GTKLIST',null,null,"columns=".localize('_DESCR',getlocal()).",".localize('_BOXTYPE',getlocal())."|autoresize=true");
					   //foreach ($this->buffer as $line=>$data)
					     //$xml->addtag('GTKLISTITEM','GTKLIST',$data,"id=$line");
					   $out = $xml->getxml();
					   unset($xml);	
		               break;
		 case 'CLI'  :
		 case 'TEXT' : break;
         case 'HTML' :
		 default     : 
                       if ($this->notempty()) {
                         $mycart = new browse($this->buffer);
	                     $out = $mycart->render(2002,0,$this);
	                     unset ($mycart);

		                 //$out .= $this->foot();
		                 $data = seturl("t=viewcart&a=&g=",localize('_CART',getlocal()) );
                         $mytitle = new window('',$data);
                         $out .= $mytitle->render(" ::100%::0::group_form_foottitle::right;100%;::");
                         unset ($mytitle);
	                   }
  	                   else 
		                 $out = localize('_EMPTY',getlocal()); 
					   break;
	   }			   
	   
	   return ($out);
	}

	function cartview() {
       $orderdataprint = GetSessionParam('orderdataprint');
	   
		//get current product view 	   
	   $pview = $this->view;//GetSessionParam("PViewStyle");	   

       if (defined(_CURRENCYF_)) $cf = new CurrencyFormatter();

       $out=''; 
	   $printout='';
       $aa = 0; 
       $myaction = seturl("t=viewcart",0,1); 	   

       switch ($this->status) {
		   case 1 : $out = setNavigator(localize('_CART',getlocal()) . $this->rightarrow . "&nbsp;" . 
			                             localize('_CHKOUT',getlocal())); 
										 
				           $myaction = seturl("t=viewcart",0,1);	//use SSL										 
						   break;
		   case 2 : $out = setNavigator(localize('_CART',getlocal())  . $this->rightarrow . "&nbsp;" .
			                             localize('_CHKOUT',getlocal())  . $this->rightarrow . "&nbsp;" .
			                             localize('_ORDER',getlocal())); 
										 
				           $myaction = seturl("t=viewcart",0,1);	//use SSL 			 
						   break;
		  default : $out = setNavigator(localize('_CART',getlocal())); 
	   }

	   if ($this->status<3) {

   	     if ($this->notempty()) {
		 
		   $out .= $this->stock_msg;

           $out .= "<form method=\"POST\" action=\"";
           $out .= "$myaction";
           $out .= "\" name=\"Cartview\">";

		   if ($this->status==2) {
         
               //get customer data or register new customer
               if ( (defined('CUSTOMERS_DPC')) && (seclevel('CUSTOMERS_DPC',decode(GetSessionParam('UserSecID')))) ) {
	             $winout .= setTitle(date('d/m/Y h:i:s A'),'right');
				 $printout .= setTitle(date('d/m/Y h:i:s A'),'right');

		         $ret = GetGlobal('controller')->calldpc_method('customers.showcustomerdata'); 

		         if ($ret) {
					         $winout .= $ret;
					         $printout .= $ret;
				 }
		         else {
					         //in case of no customer data register now
						     $out = $mycustomer->register();

                             SetSessionParam('cartstatus',0); 
	                         $this->status = 0;
 			                 return ($out); //exit now
				 }
		       }		   	   
		   } 

		   $winout .= $this->head();
		   $printout .= $this->head();

           reset ($this->buffer);
           //while (list ($prod_id, $product) = each ($this->buffer)) {
           foreach ($this->buffer as $prod_id => $product) { 
		 
		     if (($product) && ($product!='x')) { 
               $aa+=1;
		       $param = explode(";",$product);  
         
		       $gr = urlencode($param[4]); 
			   $ar = urlencode($param[1]);
	           $link = seturl("t=$pview&a=$ar&g=$gr" , $param[1]);

	           if (!$this->status) {
			     $data[] = "<img src=\"" . $param[7] . "\" width=\"100\" height=\"75\" alt=\"\">";
	             $attr[] = "left;10%";				 	
		       }
		       else {
				 $data[] = $aa . "&nbsp;" . $param[0];
	             $attr[] = "left;20%";				 
		       }

			   switch ($this->status) {
				   default :
				   case 0 : $data[] = $param[0] . "<br>" . $link . "<br>" . $param[6];  break;
				   case 1 :
                   case 2 : $data[] = $param[1]; break;
			   }

               if (!$this->status) {
	              $attr[] = "left;40%";
			      $data[] = $this->showsymbol($product,$param[4],$param[5]); 
	              $attr[] = "center;10%";				  
		       }
		       else {
	              $attr[] = "left;40%";
		       }

	           //if (defined(_CURRENCYF_)) $data[] =  $cf->format($param[8],"GRD") . $this->moneysymbol;
			   //else 
			     //$data[] =  str_replace(".",",",$param[8]) . $this->moneysymbol;  
			   $price = floatval(str_replace(",",".",$param[8]));//$param[8];	 
			   $data[] = number_format($price,$this->dec_num,',','.') . $this->moneysymbol;	 
	           $attr[] = "right;15%";
			   
	           $options = $this->setquantity("Product$aa",$param[9]);
			   /*///////////////////////////////////////////////////
			   if (($this->uniname2) && ($param[11])) 
			     $options .= "<br>" . $this->setuniname("Uniname$aa",$param[10],$param[10],$param[11]);
			   /////////////////////////////////////////////////////*/	 
			   $data[] = $options;			 
	           $attr[] = "right;10%";

	           //if (defined(_CURRENCYF_)) $data[] = $cf->format($this->settotal("Product$aa",$param[8],$param[9]),"GRD") . $this->moneysymbol;
			   //else 
			   $data[] = $this->settotal("Product$aa",$price,$param[9]) . $this->moneysymbol; 
	           $attr[] = "right;15%";

	           $myproduct = new window('',$data,$attr);
			   if ($this->overitem[$prod_id])
			     $winout .= $myproduct->render("center::100%::0::group_article_selected::left::0::0::") . "<hr>";
			   else
	             $winout .= $myproduct->render("center::100%::0::group_article_body::left::0::0::") . "<hr>";
			   $printout .= $myproduct->render();
			   
	           unset ($data);
	           unset ($attr);
		       unset ($param);
		     }
	       }
	 
           //footer
	       $winout .= $this->foot();
		   $printout .= $this->foot();
	   

	       //main window 1
	       $mywin = new window(localize('_CART',getlocal()),$winout);
	       $out .= $mywin->render();	
	       unset ($mywin);
		   		   
           //recalculate & checkout submit buttons
           switch ($this->status) {
			    case 1 : 
						 $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->order\">&nbsp;";
				         $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->cancel\">";
						 break;
                case 2 : 
					     SetSessionParam('orderdataprint',$printout);
   				         $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->submit\">&nbsp;";
				         $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->cancel\">";
					     break;
			   default : 
				         $buttons =  "<input type=\"submit\" name=\"FormAction\" value=\"$this->recalc\">&nbsp;";
						 $buttons .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->checkout\">";
				         $resetb = seturl("t=clearcart&a=&g=" , $this->resetcart_button) . "&nbsp;";
						 
						 if (is_object($this->transformer))	
						   $transb = $this->transformer->showlink();						 
						 
	                     $data[] = $buttons;
	                     $attr[] = "left;50%";
	                     $data[] = $resetb . $transb;
	                     $attr[] = "right;50%";

	                     $w = new window('',$data,$attr);
	                     $out .= $w->render(" ::100%::0::null::right;100%;::");
	                     unset ($data);
	                     unset ($attr);
						 unset ($w);
		   }

           $out .= "<input type=\"hidden\" name=\"FormName\" value=\"Cartview\">";
           $out .= "</FORM>";
         }
		 else {
           //empty message
	       $w = new window(localize('_CART',getlocal()),localize('_EMPTY',getlocal()));
	       $out .= $w->render("center::40%::0::group_win_body::left::0::0::");//" ::100%::0::group_form_headtitle::center;100%;::");
	       unset($w);
		 }
	   }
	   else {

          //print $this->transaction_id;
		  if (($this->transaction_id) && (!$this->mailerror)) {

             //send message
		     //$out .= setTitle(localize('_ENDOK',getlocal()) . $this->transaction_id);
			 $msg = "<H3>".localize('_ENDOK',getlocal()) . $this->transaction_id . "</H3>";
			 $printout .=  setTitle(localize('_TRANSNUM',getlocal()) . ":" . $this->transaction_id);

		     //print option
             if (iniload('JAVASCRIPT')) {		
  	            $plink = "<A href=\"" . seturl("") . "\"";	   
	            //call javascript for opening a new browser win for the img		   
	            $params = seturl("t=printcart&a=&g=") . ";Order;scrollbars=yes,width=640,height=480;";

				$js = new jscript;
	            $plink .= GetGlobal('controller')->calldpc_method('javascript.JS_function use js_openwin+'.$params);
				          //comma values includes at params ?????
				          //$js->JS_function("js_openwin",$params); 
                unset ($js);

	            $plink .= ">"; 
	         }
	         else
                $plink = "<A href=\"" . seturl("t=printcart&a=&g=") . ">";

			 //links.....	
             //$out .= setTitle($plink . localize('_TRANSPRINT',getlocal()) . "</A>");
			 $msg .= "<br /><H3>" . $plink . localize('_TRANSPRINT',getlocal()) . "</A><br/>" .
			         seturl("",localize('_HOME',getlocal())) . "</H3>";
			 
             if ( (defined('TRANSACTIONS_DPC')) && (seclevel('TRANSACTIONS_DPC',decode(GetSessionParam('UserSecID')))) ) 
			   $msg .= "<br/><H3>" . seturl("t=transview",localize('TRANSACTIONS_CNF',getlocal()),1) . "</H3>";
			 		 
			 $win = new window('',$msg);
			 $out .= $win->render("center::40%::0::group_win_body::center::0::0::");		
			 unset($win);				 
		  }
		  else {
			  /*$out .= setTitle(localize('_TRANSERROR',getlocal()) . 
				               " <A href=\"mailto:" . $this->carterror_mail . "\">" .
				               $this->carterror_mail . "</A>");*/
			
			  //get error message
			  if ($this->mailerror) {
			    //change status of transaction
                if ( (defined('TRANSACTIONS_DPC')) && 
				     (seclevel('TRANSACTIONS_DPC',decode(GetSessionParam('UserSecID')))) ) {
		          GetGlobal('controller')->calldpc_method('transactions.setTransactionStatus use '.$this->transaction_id."+3");		
				}   	    
			    $error = $this->mailerror;
			  }	
			  if (!$this->transaction_id) $error = "Invalid transaction id.";
			   				   
			  $msg = localize('_TRANSERROR',getlocal()) . "&nbsp;" .
			         seturl("t=cmail&department=3&subject=transaction&body=".urlencode($error),$this->carterror_mail);
			         //old way is just a mailto 
				     // " <A href=\"mailto:" . $this->carterror_mail . "\">" .
				     // $this->carterror_mail . "</A>";				   
			  $win = new window(localize('_CARTERROR',getlocal()),"<H2>".$msg."</H2>");
			  $out .= $win->render("center::40%::0::group_win_body::left::0::0::");		
			  unset($win);		   
		  }

		  //reset global params 
          SetSessionParam('TransactionID',0); 
          SetSessionParam('cartstatus',0); 
	      $this->status = 0;
	   } 
	   
	   return ($out);
	}

	function submit_order() {
      
	   //changed to sentransaction ......
       if ( (defined('TRANSACTIONS_DPC')) && (seclevel('TRANSACTIONS_DPC',decode(GetSessionParam('UserSecID')))) ) {

         //SAVE THE TRANSACTION
         $this->transaction_id = GetGlobal('controller')->calldpc_method('transactions.saveTransaction use '.serialize($this->buffer));		 		 	 
		 
		 SetSessionParam('TransactionID',$this->transaction_id);

		 if ($this->transaction_id) {	 

             $this->goto_mailer();
					 
             if (!$this->mailerror) {
			   //print action]
			   $this->goto_printer();
               //finaly clear cart
               $this->clear();
			 }
		 }
	   }
	   else setInfo(localize('_ACCDENIED',getlocal()));
	}

	function cancel_order() {

	   //setInfo(localize('_ACCDENIED',getlocal()));
	}

    function head() {	
       $data[] = localize('_DESCR',getlocal());
       $attr[] = "left;60%";
       $data[] = localize('_PRICE',getlocal());
       $attr[] = "right;15%";
       $data[] = localize('_QTY',getlocal());
       $attr[] = "right;10%";
       $data[] = localize('_STOTAL',getlocal());
       $attr[] = "right;15%";

       $mytitle = new window('',$data,$attr);
       $out = $mytitle->render(" ::100%::0::group_form_headtitle::center;100%;::");
       unset ($data);
       unset ($attr);

	   return ($out);
	}

	function foot() {

       if (defined(_CURRENCYF_)) $cf = new CurrencyFormatter();

       $data[] = "<B>" . localize('_TOTAL',getlocal()) . " :</B>";
       $attr[] = "right;75%";
       $data[] = $this->qtytotal;
       $attr[] = "right;10%";

       if (defined(_CURRENCYF_)) $data[] = $cf->format($this->total,"GRD") . $this->moneysymbol;
	                        else $data[] = number_format($this->total,$this->dec_num,',','.') . $this->moneysymbol;
       $attr[] = "right;15%";

       $mytitle = new window('',$data,$attr);
       $out = $mytitle->render(" ::100%::0::group_form_body::center;100%;::");
       unset ($data);
       unset ($attr);

       if (!$this->status) {	   
         //tax message
	     $out .= "<br>";
	     $warning1 = new window('',localize('_MSG16',getlocal()));
	     $out .= $warning1->render(" ::100%::0::group_form_foottitle::center;100%;::");
	     unset($warning1);
	   }
	   else {
	     if ($this->shippingcost) {
           $data2[] = "<B>" . localize('_SHIPCOST',getlocal()) . " :</B>";
           $attr2[] = "right;75%";
           $data2[] = "&nbsp;";
           $attr2[] = "right;10%";
           if (defined(_CURRENCYF_)) $data2[] = $cf->format($this->shippingcost,"GRD") . $this->moneysymbol;
	                            else $data2[] = number_format($this->shippingcost,$this->dec_num,',','.') . $this->moneysymbol;
           $attr2[] = "right;15%";	
           $mytitle = new window('',$data2,$attr2);
           $out .= $mytitle->render(" ::100%::0::group_form_body::center;100%;::");
           unset ($data2);
           unset ($attr2);		 	 		 	   
		 }
	     if ($this->discount) {
           $data2[] = "<B>" . localize('_DISCOUNT',getlocal()) . " :</B>";
           $attr2[] = "right;75%";
           $data2[] = "<B>" . $this->discount ."%</B>";
           $attr2[] = "right;10%";
		   $discount = ($this->total*$this->discount)/100;
           if (defined(_CURRENCYF_)) $data2[] = $cf->format($discount,"GRD") . $this->moneysymbol;
	                            else $data2[] = number_format($discount,$this->dec_num,',','.') . $this->moneysymbol;
           $attr2[] = "right;15%";	
           $mytitle = new window('',$data2,$attr2);
           $out .= $mytitle->render(" ::100%::0::group_form_body::center;100%;::");
           unset ($data2);
           unset ($attr2);
		 }  		 	 		 	   
	     if ($this->tax) {
		   $taxcost = ((($this->total+$this->shippingcost)-$discount)*$this->tax)/100;//($this->total*$this->tax)/100;
           $data2[] = "<B>" . localize('_TAX',getlocal()) . " :</B>";
           $attr2[] = "right;75%";
           $data2[] = "<B>" . $this->tax ."%</B>";
           $attr2[] = "right;10%";
           if (defined(_CURRENCYF_)) $data2[] = $cf->format($taxcost,"GRD") . $this->moneysymbol;
	                            else $data2[] = number_format($taxcost,$this->dec_num,',','.') . $this->moneysymbol;
           $attr2[] = "right;15%";	
           $mytitle = new window('',$data2,$attr2);
           $out .= $mytitle->render(" ::100%::0::group_form_body::center;100%;::");
           unset ($data2);
           unset ($attr2);		 	 		 	   		   
		 }		 		 
		 //final cost
         $data3[] = "<B>" . localize('_FCOST',getlocal()) . " :</B>";
         $attr3[] = "right;75%";
         $data3[] = "&nbsp;";
         $attr3[] = "right;10%";
	     $finalcost = ($this->total+$taxcost+$this->shippingcost)-$discount;
         if (defined(_CURRENCYF_)) $data3[] = $cf->format($finalcost,"GRD") . $this->moneysymbol;
	                          else $data3[] = number_format($finalcost,$this->dec_num,',','.') . $this->moneysymbol;
         $attr3[] = "right;15%";

         $mytitle = new window('',$data3,$attr3);
         $out .= $mytitle->render(" ::100%::0::group_form_body::center;100%;::");
         unset ($data3);
         unset ($attr3);		 
		 
         //order parameters
         $out .= $this->roadway();
		 $out .= "<hr>";			 
         $out .= $this->payway();			 
		 $out .= "<hr>";			 
         $out .= $this->comments();		 
	   } 	     

	   return ($out);
	}

    function setquantity($id,$qty) {

  	  $qtyname = $id ;
      $selectedqty = GetParam($qtyname);
	  if (!$selectedqty) $selectedqty = $qty;
      //print $id.">".$selectedqty."()";

	  if (!$this->status) { //only if status=0 else when cart status > 0 qty change
	  
	      if ($this->maxqty<0) { //free style
		  
            $out = "<input name=\"$qtyname\" value=\"$selectedqty\" size=\"3\" maxlength=\"3\">";		  
		  }
		  else { //combo style
		    $out = "<SELECT name=\"$qtyname\">";
		    for ($j=1;$j<=$this->maxqty;$j++) {
		      if (($selectedqty) && ($selectedqty==$j)) 
                   $out .= "<OPTION selected>$j";
		      else $out .= "<OPTION>$j";
		    }  
		    $out .= "</OPTION></SELECT>";
		  }	
	  }
	  else
	    $out = $qty; 
		  
       return ($out);
	}

    function recalculate() {
	   $this->stock_msg = null;
	   $this->overitem = null;
	   $jcode = null;

       //while (list ($prod_id, $product) = each ($this->buffer)) {
	   //print_r($this->buffer);
       foreach ($this->buffer as $prod_id => $product) {
		 
		 if (($product) && ($product!='x')) {
		    
           $param = explode(";",$product);
		   $aa = $prod_id+1;// ???? echo $aa,"+++";	  		   
		   
		   //selected quantity
           $selectedqty = GetParam("Product$aa"); //echo $selectedqty,">>";
		   			       
		   if ($selectedqty) {//change qty 
		      $this->buffer[$prod_id] = "$param[0];$param[1];$param[2];$param[3];$param[4];$param[5];$param[6];$param[7];$param[8];$selectedqty;$param[10];$param[11];$param[12];$param[13];$param[14];$param[15];";
		   }	 
		   else {
		      if ($this->rejectqty)	 
		        $this->buffer[$prod_id] = 'x';//=0 so delete it from list 
		   }
		   	 
		 }
	   }
	   $this->setStore();
	   
       if ((iniload('JAVASCRIPT')) && ($jcode)) {
		      $js = new jscript;	 	      
              $js->load_js($jcode,"",1);		   			   
		      unset ($js);	   
	   }
       //calldpc_method('javascript.load_js use alert(\'cccc\')+null+1');	       
	}

    function settotal($id,$price,$qty) {	

	  if (!$qty) $qty = 1;
      
	  if ($price!=0) {
		  $result = ($price*$qty);
          $this->total += $result;
		  $this->qtytotal += $qty;
	  }
	  else
		 $result = "--&nbsp;";
     
      return (number_format($result,$this->dec_num,',','.'));
	  //return ($result);
	}

    function printorder($data) {
	
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
		$printpage = new phtml('',$data,"<B><h1>$headtitle</h1></B>");
		$out = $printpage->render();
		unset($printpage);

		return ($out);
	}
	
	function goto_printer() {
         $orderdataprint = GetSessionParam('orderdataprint');	
	
         //PRINT ORDER TO SERVER PRINTER 
		 //$x = printer_list(5,PRINTER_ENUM_NETWORK | PRINTER_ENUM_REMOTE); print_r($x);
         //var_dump( printer_list(PRINTER_ENUM_NETWORK | PRINTER_ENUM_REMOTE) );
		 //$this->prn_device = "\\\\heracles\\HPLaserJ2300"; //print $printer;
		 
		 
         if ($this->prn_device) { 		 
		 
		    if (defined(_PRINTLPR_)) $pr = new printLPR($this->prn_device,'',$orderdataprint);		 
		    else {
		      $pr = printer_open($this->prn_device); //print $this->prn_device;
	
			  if ($pr) {
			  
                 printer_set_option($pr, PRINTER_MODE, 'RAW');
			  
			     printer_set_option($pr, PRINTER_TITLE, 'Interner Order No'.$this->transaction_id);			   
			     //printer_start_doc($pr,"Internet Order");
		         printer_write($pr,$orderdataprint."\n\r"); //print $orderdataprint;
		         //printer_draw_text($pr,$orderdataprint,10,10);
			     //printer_end_doc($pr);
		         printer_close($pr);	
				 
				 
			  }
			  else {
			     SetInfo("Printer error !!!!!");
			  }
			}  	 
	    }
	}
	
	function goto_mailer() {
          $orderdataprint = GetSessionParam('orderdataprint');
		  //echo $orderdataprint;
	   	
	      //mail("vasilis@panikidis.gr","app@panikikdis.gr","xxxyyyzzz");
		  // MAIL THE ORDER
		    
          //if ( (defined('MAIL_DPC')) && (seclevel('ORDERMAIL_',$this->userLevelID)) ) { //adv mail class
          if ( (defined('CMAIL_DPC')) && (seclevel('SENDCMAIL_',$this->userLevelID)) ) {  //contact mail class		  
		  
		       $template = paramload('SHELL','prpath') . "cartorder.tpl";
		       $mailout = str_replace("##_LINK_##",$orderdataprint,file_get_contents($template));		  
			   
			   /*$headtitle = paramload('SHELL','urltitle');
		       $mailpage = new phtml(paramload('SHELL','protocol').$_SERVER['HTTP_HOST'].paramload('SHELL','css'),
			                         $orderdataprint,
									 "<B><h1>$headtitle</h1>$this->transaction_id</B>");
		       $mailout = $mailpage->render();//ERROR!!!!interuption
		       unset($mailpage);	
			   */
			   /*$omail = new contactmail; //mailbox;			   		   
			   $this->mailerror = $omail->send_smtpmail($this->cartsend_mail,$this->cartreceive_mail,
			                                        'Internet Order No'.$this->transaction_id,$mailout);
			   unset ($omail);						 */
			   $smtpm = new smtpmail;
			   $smtpm->to = $this->cartreceive_mail;//"balexiou@panikidis.gr"; 
			   $smtpm->from = $this->cartsend_mail ;//"orders@panikidis.gr"; 
			   $smtpm->subject = 'Internet Order No'.$this->transaction_id;
			   $smtpm->body = $mailout;
			   
			   $this->mailerror = $smtpm->smtpsend();
			   
			   unset($smtpm);
			   //calldpc_method("smtpmail.smtpsend");			   
		 }	
	}
	
	function roadway() {
	       $a = localize('_RWAY1',getlocal());
	       $b = localize('_RWAY2',getlocal());
	       $c = localize('_RWAY3',getlocal());		   		   
	
           switch ($this->status) {
			 case 1 :	
					 $pp = new multichoice('roadway',"$a,$b,$c",1);
					 $radios = $pp->render();
					 unset($pp);
	
	                 $data1[] = localize('_RWAY',getlocal());
                     $attr1[] = "left;20%";	
	                 $data1[] = $radios;
                     $attr1[] = "left;80%";		   
                     $myway = new window('',$data1,$attr1);
                     $out = $myway->render(" ::100%::0::group_article_body::center;100%;::");
		             unset ($myway);
                     unset ($data1);
                     unset ($attr1);	 
		             break;
					 
		     case 2 :$out = localize('_RWAY',getlocal()) . " : " . GetParam("roadway"); 
			         break;		     
					 
			 default : $out = null;
		   }	 
		   
		   return ($out); 
	}
	
	function payway() {
	       $a = localize('_PWAY1',getlocal());
	       $b = localize('_PWAY2',getlocal());
	       $c = localize('_PWAY3',getlocal());		   		   
	
           switch ($this->status) {
			 case 1 :		   
					 $pp = new multichoice('payway',"$a,$b,$c",1);
					 $radios = $pp->render();
					 unset($pp);		   
	
	                 $data1[] = localize('_PWAY',getlocal());
                     $attr1[] = "left;20%";	
	                 $data1[] = $radios;
                     $attr1[] = "left;80%";		   
                     $myway = new window('',$data1,$attr1);
                     $out = $myway->render(" ::100%::0::group_article_body::center;100%;::");
		             unset ($myway);
                     unset ($data1);
                     unset ($attr1);	 
		             break;
					 
		     case 2 :$out = localize('_PWAY',getlocal()) . " : " . GetParam("payway"); 
			         break;		     
					 
			 default : $out = null;
		   }	 
		   
		   return ($out); 
	}	
	
	
	function comments() {
	       $a = localize('_SXOLIA',getlocal());	   		   
	
           switch ($this->status) {
			 case 1 :		   
                     $sxolia = "<input style=\"width:100%\" type=\"text\" name=\"sxolia\" maxlenght=\"255\" value=\"$this->sxolia\">"; 		   
	
	                 $data1[] = localize('_SXOLIA',getlocal());
                     $attr1[] = "left;20%";	
	                 $data1[] = $sxolia;
                     $attr1[] = "left;80%";		   
                     $myway = new window('',$data1,$attr1);
                     $out = $myway->render(" ::100%::0::group_article_body::center;100%;::");
		             unset ($sxolia);	 
		             break;
					 
		     case 2 :$out = localize('_SXOLIA',getlocal()) . " : " . GetParam("sxolia"); 
			         break;		     
					 
			 default : $out = null;
		   }	 
		   
		   return ($out); 	
	}
	
	
	
	
    function browse($packdata,$view) {
	
       if (($packdata) && ($packdata!='x')) { 	
	     $data = explode("||",$packdata);
	
	     switch ($view) {
           case 2002 : $out = $this->viewcart($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9],$data[10],$data[11],$data[12],$data[13],$data[14],$data[15]); break;
	     }
       }
	   return ($out);
	}	
	
    function viewcart($id,$title,$path,$template,$group,$page,$descr='',$photo='',$price=0,$quant=1,$uninameA=null,$uninameB=null) {
	 	   	
		//get current product view 			
	   $pview = $this->view; //GetSessionParam("PViewStyle");		
	   
	   $gr = urlencode($group);
	   $ar = urlencode($title);	

       $item = summarize(10,$title);
	   $link = seturl("t=$pview&a=$ar&g=$gr" ,$item);
							  
	   $data[] = $link;
	   $attr[] = "left;70%";
	   //$data[] = $price;   
	   //$attr[] = "right;30%";
	   $data[] = $quant;   
	   $attr[] = "right;30%";

	   $myarticle = new window('',$data,$attr);
	   $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::");
	   unset ($data);
	   unset ($attr);

	   return ($out);
    }	
	
	function headtitle() {
	   $t = GetReq('t');
	   $p = GetReq('p');
	
	                 $data[] = localize('_ITEM',getlocal());
	                 $attr[] = "left;70%";
	                 //$data[] = localize('_PRICE',getlocal());   
	                 //$attr[] = "right;30%";
	                 $data[] = localize('_QTY',getlocal());
	                 $attr[] = "right;30%";

					 $mytitle = new window('',$data,$attr);
					 $out = $mytitle->render(" ::100%::0::group_form_headtitle::center;100%;::");
					 unset ($data);
					 unset ($attr);	
	   
	   return ($out);
	}
	
	function free() {
	
       if (is_object($this->transformer))	
	     $this->transformer->free();
	}			
};
}
?>