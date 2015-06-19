<?php
/*

	Paypal Button generator by JC21  		jc@jc21.com				www.jc21.com
		
	Creates a Paypal button for Checkout, Donate or Pay now circumstances. Supports Cart, more than 1 item. Supports Tax, postage and handling,
	css styles, form targets etc. Fully customizable.

	
	Paypal button details:  https://www.paypal.com/au/cgi-bin/webscr?cmd=p/pdn/howto_checkout-outside#methodone


	USAGE:
	
		require_once('paypal.inc.php');		//require the class include file

		$button = new PayPalButton;														//initiate the class instance
		$button->accountemail = 'jason@almost-anything.com.au';							//the account that is registered with paypal where money will be sent to
		$button->custom = 'my custom passthrough variable'; 							//a custom string that gets passed through paypals pages, back to your IPN page and Return URL as $_POST['custom'] . useful for database id's or invoice numbers. WARNING: does have a max string limit, don't go over 150 chars to be safe
		$button->currencycode = 'AUD';													//currency code
		$button->target = '_blank';														//Frame Name, usually '_blank','_self','_top' . Comment out to use current frame.
		$button->class = 'paypalbutton';												//CSS class to apply to the button. Comes in very handy
		$button->width = '150';															//button width in pixels. Will apply am Inline CSS Style to the button. Comment if not needed.
		$button->image = 'http://www.jc21.com.au/paypal/logo.jpg';						//image 150px x 50px that can be displayed on your paypal pages.
		$button->buttonimage = '/paypal/purchase.jpg';									//img to use for this button
		$button->buttontext = 'I agree, proceed to Payment';							//text to use if image not found or not specified
		$button->askforaddress = false;													//wether to ask for mailing address or not
		$button->return_url = 'http://www.aussiehorsebrokers.com.au/paypal.php';		//url of the page users are sent to after successful payment
		$button->ipn_url = 'http://www.aussiehorsebrokers.com.au/paypal/ipn.php';		//url of the IPN page (this overrides account settings, IF IPN has been setup at all.
		$button->cancel_url = 'http://www.aussiehorsebrokers.com.au/paypal_cancel.php'; //url of the page users are sent to if they cancel through the paypal process

		//ITEMS
		//Paypal buttons are different when you're selling 1 item and anything more than 1 item. My class takes care of this for you.
		//Syntax: $button->AddItem(item_name,quantity,price,item_code,shipping,shipping2,handling,tax);
		//Here are a few examples:
		$button->AddItem('Item Name','1','100.00','wsc001');							//1 quantity, no shipping, no handling, default tax.
		$button->AddItem('Item Name','1','100.00','wsc001','','','','0.00');			//1 quantity, no shipping, no handling, NO TAX
		$button->AddItem('Item Name','3','100.00','wsc001','10.00');					//3 quantities, $10.00 shipping, no handling, default tax.


		$button->OutputButton();														//output the button!
	
	




*/

class PayPalButton {
	
	var $accountemail;      // email of paypal seller account 
	var $currencycode;      // currencycode USD or AUD etc
	var $amount;			// total amount. not sure wether to calculate this myself yet.
	var $custom;			// custom passthrough field
	var $items;		 	 	// array of items
	var $target;			// window target: _blank, _top, _self
	var $image;				// paypal image, 150 x 50px
	var $buttonimage;		// button image.
	var $buttontext;		// button text.
	var $askforaddress;		// true or false
	var $ipn_url;			// IPN url
	var $return_url;		// after successful payment url
	var $cancel_url;		// after cancel payment url
	var $class;				//for styling
	var $width;				//in pixels.
	
	//for enc
	var $scramble1;      // 1st string of ASCII characters 
	var $scramble2;      // 2nd string of ASCII characters 
	var $errors;         // array of error messages 
	var $adj;            // 1st adjustment value (optional) 
	var $mod;            // 2nd adjustment value (optional)
	
	var $paypal_url;
	
	function PayPalButton() {
		//set up some defaults
		$this->accountemail = '';
		$this->currencycode = 'AUD';
		$this->amount = '0.00';
		$this->custom = '';
		$this->items = array();
		$this->target = '';
		$this->target_text = '';
		$this->image = '';
		$this->buttonimage = '';
		$this->buttontext = 'Purchase';
		$this->askforaddress = true;
		$this->ipn_url = '';
		$this->return_url = '';
		$this->cancel_url = '';
		$this->class = '';
		$this->width = '';
		
		$this->paypal_url = '';
		
		
		//for enc
		$this->errors = array();        
      	// Each of these two strings must contain the same characters, but in a different order. 
      	// Use only printable characters from the ASCII table. 
      	// Each character can only appear once in each string EXCEPT for the first character 
      	// which must be duplicated at the end (this gets round a bijou problemette when the 
      	// first character of the password is also the first character in $scramble1) 
      	$this->scramble1 = '! "#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~!'; 
      	$this->scramble2 = 'f^jAE]okI\OzU[2&q1{3`h5w_794p@6s8?BgP>dFV=m D<TcS%Ze|r:lGK/uCy.Jx)HiQ!"#$\'~(;Lt-R}Ma,NvW+Ynb*0Xf'; 
       
      	if (strlen($this->scramble1) <> strlen($this->scramble2)) { 
         	$this->errors[] = '** SCRAMBLE1 is not same length as SCRAMBLE2 **'; 
      	} // if 
       
      	$this->adj = 1.75;    // this value is added to the rolling fudgefactors 
      	$this->mod = 3;       // if divisible by this the adjustment is made negative 
       
		
	}
	
	function AddItem($item_name,$quantity,$price,$item_no='',$shipping='',$shipping2='',$handling='',$tax='') {
		//add item
		$this->items[] = array('name'=>$item_name,'qty'=>$quantity,'price'=>$price,'item_no'=>$item_no,'shipping'=>$shipping,'shipping2'=>$shipping2,'handling'=>$handling,'tax'=>$tax);
	}
	
	function OutputButton() {
		if (strlen($this->accountemail) > 0 && count($this->items) > 0) {
			//ok
			if (strlen($this->target) > 0) {
				$this->target_text = ' target="' . $this->target . '"';
			}
			$ret .=  '<form action="'.$this->paypal_url.'" method="post"' . $this->target_text . '>' . "\n";
			
			//ITEMS
			if (count($this->items) == 1) {
				//only 1 item!
				$ret .=  '<input type="hidden" name="cmd" value="_xclick" />' . "\n";
				$ret .=  '<input type="hidden" name="item_name" value="' . $this->items[0]['name'] . '" />' . "\n";
				$ret .=  '<input type="hidden" name="quantity" value="' . $this->items[0]['qty'] . '" />' . "\n";
				$ret .=  '<input type="hidden" name="amount" value="' . $this->items[0]['price'] . '" />' . "\n";
				//item no
				if (strlen($this->items[0]['item_no']) > 0) {
					$ret .=  '<input type="hidden" name="item_number" value="' . $this->items[0]['item_no'] . '" />' . "\n";
				}
				//shipping
				if (strlen($this->items[0]['shipping']) > 0) {
					$ret .=  '<input type="hidden" name="shipping" value="' . $this->items[0]['shipping'] . '" />' . "\n";
				}
				//shipping2
				if (strlen($this->items[0]['shipping2']) > 0) {
					$ret .=  '<input type="hidden" name="shipping2" value="' . $this->items[0]['shipping2'] . '" />' . "\n";
				}
				//handling
				if (strlen($this->items[0]['handling']) > 0) {
					$ret .=  '<input type="hidden" name="handling" value="' . $this->items[0]['handling'] . '" />' . "\n";
				}
				
				


			} else {
				//more than 1 item
				$ret .=  '<input type="hidden" name="cmd" value="_cart" />' . "\n";
				$ret .=  '<input type="hidden" name="upload" value="1" />' . "\n";
				$ret .=  '<input type="hidden" name="item_name" value="Shopping Cart" />' . "\n";
				$totalamount = 0;
				for ($x=0;$x<count($this->items);$x++) {
					//for each item
					$ret .=  '<input type="hidden" name="item_name_' . ($x+1) . '" value="' . $this->items[$x]['name'] . '" />' . "\n";
					$ret .=  '<input type="hidden" name="quantity_' . ($x+1) . '" value="' . $this->items[$x]['qty'] . '" />' . "\n";
					$ret .=  '<input type="hidden" name="amount_' . ($x+1) . '" value="' . $this->items[$x]['price'] . '" />' . "\n";
					//item no
					if (strlen($this->items[$x]['item_no']) > 0) {
						$ret .=  '<input type="hidden" name="item_number_' . ($x+1) . '" value="' . $this->items[$x]['item_no'] . '" />' . "\n";
					}
					//shipping
					if (strlen($this->items[$x]['shipping']) > 0) {
						$ret .=  '<input type="hidden" name="shipping_' . ($x+1) . '" value="' . $this->items[$x]['shipping'] . '" />' . "\n";
					}
					//shipping2
					if (strlen($this->items[$x]['shipping2']) > 0) {
						$ret .=  '<input type="hidden" name="shipping2_' . ($x+1) . '" value="' . $this->items[$x]['shipping2'] . '" />' . "\n";
					}
					//handling
					if (strlen($this->items[$x]['handling']) > 0) {
						$ret .=  '<input type="hidden" name="handling_' . ($x+1) . '" value="' . $this->items[$x]['handling'] . '" />' . "\n";
					}
					//tax
					if (strlen($this->items[$x]['tax']) > 0) {
						$ret .=  '<input type="hidden" name="tax_' . ($x+1) . '" value="' . $this->items[$x]['tax'] . '" />' . "\n";
					}
					
					$totalamount = $totalamount + ($this->items[$x]['price'] * $this->items[$x]['qty']);
					
				}
				
				//generate total amount
				$ret .=  '<input type="hidden" name="amount" value="' . number_format($totalamount,2,".","") . '" />' . "\n";
			}
			//END ITEMS
			
			
			$ret .=  '<input type="hidden" name="business" value="' . $this->accountemail . '" />' . "\n";
			$ret .=  '<input type="hidden" name="currency_code" value="' . $this->currencycode . '" />' . "\n";

			//custom
			if (strlen($this->custom) > 0) {
				$ret .=  '<input type="hidden" name="custom" value="' . $this->custom . '" />' . "\n";
			}
			
			//image
			if (strlen($this->image) > 0) {
				$ret .=  '<input type="hidden" name="image_url" value="' . $this->image . '" />' . "\n";
			}		
		
			//askforaddress
			if ($this->askforaddress == true) {
				//ask for address
				$ret .=  '<input type="hidden" name="no_shipping" value="0" />' . "\n";
			} else {
				//don't ask for address
				$ret .=  '<input type="hidden" name="no_shipping" value="1" />' . "\n";
			}
			
			
			//reutn url
			if (strlen($this->return_url) > 0) {
				$ret .=  '<input type="hidden" name="return" value="' . $this->return_url . '" />' . "\n";
			}
			//ipn url
			if (strlen($this->ipn_url) > 0) {
				$ret .=  '<input type="hidden" name="notify_url" value="' . $this->ipn_url . '" />' . "\n";
			}
			//cancel url
			if (strlen($this->cancel_url) > 0) {
				$ret .=  '<input type="hidden" name="cancel_return" value="' . $this->cancel_url . '" />' . "\n";
			}
			//class css
			if (strlen($this->class) > 0) {
				$class = ' class="' . $this->class . '"';
			} else {
				$class = '';
			}
			//css width
			$width = '';
			if (strlen($this->width) > 0) {
				$width = ' style="width:' . $this->width . 'px;"';
			}
			//button!
			if (strlen($this->buttonimage) > 0) {
				$ret .=  '<input type="image" src="' . $this->buttonimage . '" name="submit" alt="' . $this->buttontext . '"' . $class . width . ' />' . "\n";
			} else {
				$ret .=  '<input type="submit" value="' . $this->buttontext . '" name="submit"' . $class . $width . ' />' . "\n";
			}
			$ret .=  '</form>';
		} else {
			//not ok
			$ret .=  'Error: $accountemail directive not set, or no items to sell!';
		}
		
		return ($ret);
	}
	
	
	
}
?>