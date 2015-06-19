<?php

		/*
		
		
		Paypal Button generator by JC21  		jc@jc21.com				www.jc21.com
		
		Creates a Paypal button for Checkout, Donate or Pay now circumstances. Supports Cart, more than 1 item. Supports Tax, postage and handling,
		css styles, form targets etc. Fully customizable.
		
		Paypal button details:  https://www.paypal.com/au/cgi-bin/webscr?cmd=p/pdn/howto_checkout-outside#methodone
			
		Example button usage file
		
		*/

		//require_once('paypal.inc.php');		//require the class include file
													//output the button!

$__DPCSEC['PAYBUTTON_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("PAYBUTTON_DPC")) && (seclevel('PAYBUTTON_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCSENTICODES_DPC",true);

$__DPC['PAYBUTTON_DPC'] = 'paybutton';		

$d = GetGlobal('controller')->require_dpc('paypal/paybutton.lib.php');
require_once($d); 

class paybutton {

   var $button;
   var $paypal_url;

   function paybutton($item_name,$qnt,$price) {
   
	   $sandbox = paramload('PAYPAL','sandbox');
	   if ($sandbox)
         $this->button->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
	   else  
         $this->button->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url


        $ip = $_SERVER['HTTP_HOST'];
        $pr = paramload('SHELL','protocol');		   
        $resourcepath = $pr . $ip;// . paramload('DIRECTORY','resources');              
   
		$this->button = new PayPalButton;														//initiate the class instance
		$this->button->accountemail = paramload('PAYPAL','paypalmail');//'jason@almost-anything.com.au';							//the account that is registered with paypal where money will be sent to
		$this->button->custom = 'my custom passthrough variable'; 							//a custom string that gets passed through paypals pages, back to your IPN page and Return URL as $_POST['custom'] . useful for database id's or invoice numbers. WARNING: does have a max string limit, don't go over 150 chars to be safe
		$this->button->currencycode = 'USD';//'AUD';													//currency code
		$this->button->target = '_blank';														//Frame Name, usually '_blank','_self','_top' . Comment out to use current frame.
		$this->button->class = 'paypalbutton';												//CSS class to apply to the button. Comes in very handy
		$this->button->width = '150';															//button width in pixels. Will apply am Inline CSS Style to the button. Comment if not needed.
		$this->button->image = 'http://www.jc21.com.au/paypal/logo.jpg';						//image 150px x 50px that can be displayed on your paypal pages.
		$this->button->buttonimage = '/paypal/purchase.jpg';									//img to use for this button
		$this->button->buttontext = 'Proceed to Payment &gt; ';								//text to use if image not found or not specified
		$this->button->askforaddress = false;													//wether to ask for mailing address or not
		$this->button->return_url = seturl('t=paypal&action=success'.SID);//'http://www.jc21.com/home/';								//url of the page users are sent to after successful payment
		$this->button->ipn_url = seturl('t=paypal&action=ipn'.SID);//'http://www.jc21.com/paypal/';								//url of the IPN page (this overrides account settings, IF IPN has been setup at all.
		$this->button->cancel_url = seturl('t=paypal&action=cancel'.SID);//'http://www.jc21.com/'; 									//url of the page users are sent to if they cancel through the paypal process
   
		//ITEMS
		//Paypal buttons are different when you're selling 1 item and anything more than 1 item. My class takes care of this for you.
		//Syntax: $button->AddItem(item_name,quantity,price,item_code,shipping,shipping2,handling,tax);
		//Here are a few examples:
		//$this->button->AddItem('Item Name','1','100.00','wsc001');							//1 quantity, no shipping, no handling, default tax.
		//$this->button->AddItem('Item Name','1','100.00','wsc001','','','','0.00');			//1 quantity, no shipping, no handling, NO TAX
		//$this->button->AddItem('Item Name','3','100.00','wsc001','10.00');					//3 quantities, $10.00 shipping, no handling, default tax.

        $this->button->AddItem($irem_name,$qty,$price);							          
   }
   
   function render() {
   
		$ret = $this->button->OutputButton();	   
		
		return ($ret);
   }
};
}		
?>