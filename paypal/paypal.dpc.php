<?php

/*  PHP Paypal IPN Integration Class Demonstration File
 *  4.16.2005 - Micah Carrick, email@micahcarrick.com
 *
 *  This file demonstrates the usage of paypal.class.php, a class designed  
 *  to aid in the interfacing between your website, paypal, and the instant
 *  payment notification (IPN) interface.  This single file serves as 4 
 *  virtual pages depending on the "action" varialble passed in the URL. It's
 *  the processing page which processes form data being submitted to paypal, it
 *  is the page paypal returns a user to upon success, it's the page paypal
 *  returns a user to upon canceling an order, and finally, it's the page that
 *  handles the IPN request from Paypal.
 *
 *  I tried to comment this file, aswell as the acutall class file, as well as
 *  I possibly could.  Please email me with questions, comments, and suggestions.
 *  See the header of paypal.class.php for additional resources and information.
*/

$__DPCSEC['PAYPAL_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("PAYPAL_DPC")) && (seclevel('PAYPAL_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("PAYPAL_DPC",true);

$__DPC['PAYPAL_DPC'] = 'paypal';

$d = GetGlobal('controller')->require_dpc('paypal/paypal.lib.php');
require_once($d); 
// Setup class
//require_once('paypal.class.php');  // include the class file

$__EVENTS['PAYPAL_DPC'][0]='paypal';
$__EVENTS['PAYPAL_DPC'][1]='process';//action used instead ...
$__EVENTS['PAYPAL_DPC'][2]='success';
$__EVENTS['PAYPAL_DPC'][3]='cancel';
$__EVENTS['PAYPAL_DPC'][4]='ipn';
 
$__ACTIONS['PAYPAL_DPC'][0]='paypal';
$__ACTIONS['PAYPAL_DPC'][1]='process';//action used instead
$__ACTIONS['PAYPAL_DPC'][2]='success';
$__ACTIONS['PAYPAL_DPC'][3]='cancel';
$__ACTIONS['PAYPAL_DPC'][4]='ipn';


$__DPCATTR['PAYPAL_DPC']['paypal'] = 'paypal,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['PAYPAL_DPC'][0]='PAYPAL_DPC;Paypal;Paypal';   
 
class paypal {

   var $title;
   var $p;
   var $inform_ipn_mail;
   var $prpath;
   var $this_script;
   var $paypal_post;

   function paypal() {
   
     $this->paypal_post = array();
   
	 $this->prpath = paramload('SHELL','prpath');   
 	 $this->title = localize('PAYPAL_DPC',getlocal());  
	 $this->paypal_mail = paramload('PAYPAL','paypalmail');	 
	 $this->inform_ipn_mail = paramload('PAYPAL','ipnmailto');
   
     $this->p = new paypal_class;             // initiate an instance of the class
	 
	 $sandbox = paramload('PAYPAL','sandbox');
	 if ($sandbox)
       $this->p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
	 else  
       $this->p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url
            
     // setup a variable for this script (ie: 'http://www.micahcarrick.com/paypal.php')
     $this->this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

     // if there is not action variable, set the default action of 'process'
     if (empty($_GET['action'])) $_GET['action'] = 'process';     
   }
   
   function event($event=null) {
   
     switch ($_GET['action']) {
    
       case 'process':      // Process and order...

      // There should be no output at this point.  To process the POST data,
      // the submit_paypal_post() function will output all the HTML tags which
      // contains a FORM which is submited instantaneously using the BODY onload
      // attribute.  In other words, don't echo or printf anything when you're
      // going to be calling the submit_paypal_post() function.
 
      // This is where you would have your form validation  and all that jazz.
      // You would take your POST vars and load them into the class like below,
      // only using the POST values instead of constant string expressions.
 
      // For example, after ensureing all the POST variables from your custom
      // order form are valid, you might have:
      //
      // $p->add_field('first_name', $_POST['first_name']);
      // $p->add_field('last_name', $_POST['last_name']);
      
      $this->p->add_field('business', $this->paypal_mail);//'YOUR PAYPAL (OR SANDBOX) EMAIL ADDRESS HERE!');
      $this->p->add_field('return', $this->this_script.'?action=success&'.SID);
      $this->p->add_field('cancel_return', $this->this_script.'?action=cancel&'.SID);
      $this->p->add_field('notify_url', $this->this_script.'?action=ipn&'.SID);
	  
      //$this->p->add_field('item_name', 'Paypal Test Transaction');
      //$this->p->add_field('amount', '1.99');   	  
      
	  if ($this->set_product_info()) {//set the product attributes

        $this->p->submit_paypal_post(); // submit the fields to paypal
        //$p->dump_fields();      // for debugging, output a table of all the fields
	  }
	  else {
	    echo "Product details error!";
	  }
	  die(); 	  
      break;
      
	  
       case 'success':      // Order was successful...
   
      // This is where you would probably want to thank the user for their order
      // or what have you.  The order information at this point is in POST 
      // variables.  However, you don't want to "process" the order until you
      // get validation from the IPN.  That's where you would have the code to
      // email an admin, update the database with payment status, activate a
      // membership, etc.  
 
      //echo "<html><head><title>Success</title></head><body><h3>Thank you for your order.</h3>";
      //foreach ($_POST as $key => $value) { echo "$key: $value<br>"; }
	  $this->get_paypal_posts(); //HAS NOT CONFIRMED BY IPN YET!!!!
      //echo "</body></html>";
      
      // You could also simply re-direct them to another page, or your own 
      // order status page which presents the user with the status of their
      // order based on a database (which can be modified with the IPN code 
      // below).
      break;
      
       case 'cancel':       // Order was canceled...

      // The order was canceled before being completed.
      //echo "<html><head><title>Canceled</title></head><body><h3>The order was canceled.</h3>";
      //echo "</body></html>";
      $this->savelog("PAYPAL PAYMENT:CANCELED");
      break;
      
       case 'ipn':          // Paypal is calling page for IPN validation...
   
      // It's important to remember that paypal calling this script.  There
      // is no output here.  This is where you validate the IPN data and if it's
      // valid, update your database to signify that the user has payed.  If
      // you try and use an echo or printf function here it's not going to do you
      // a bit of good.  This is on the "backend".  That is why, by default, the
      // class logs all IPN data to a text file.
      
      if ($this->p->validate_ipn()) {
          
         // Payment has been recieved and IPN is verified.  This is where you
         // update your database to activate or process the order, or setup
         // the database with the user's order details, email an administrator,
         // etc.  You can access a slew of information via the ipn_data() array.
  
         // Check the paypal documentation for specifics on what information
         // is available in the IPN POST variables.  Basically, all the POST vars
         // which paypal sends, which we send back for validation, are now stored
         // in the ipn_data() array.
		 $this->procced_on_payment();//backend operations
  
         // For this example, we'll just email ourselves ALL the data.
         $subject = 'Instant Payment Notification - Recieved Payment';
         $to = $this->inform_ipn_mail;//'YOUR EMAIL ADDRESS HERE';    //  your email
         $body =  "An instant payment notification was successfully recieved\n";
         $body .= "from ".$this->p->ipn_data['payer_email']." on ".date('m/d/Y');
         $body .= " at ".date('g:i A')."\n\nDetails:\n";
         
         foreach ($this->p->ipn_data as $key => $value) { $body .= "\n\r$key: $value"; }
		 
		 $this->tell_by_mail($subject,$this->p->ipn_data['payer_email'],$to,$body);
		 		 
		 $this->savelog("PAYPAL IPN:SUCCESS");
		 die();
      }
	  else {
		$this->savelog("PAYPAL IPN:FAILED");	  
	    $this->error = 'Error during ipn.';
	  } 
      break;
     }     
   }
   
   function action($action=null) {
   
     switch ($_GET['action']) {
	    case 'process' : $ret = 'Please wait processing...'; 
		                 break;
		case 'success' : //$ret = 'Thank you for your order.'; 
						 $ret = $this->procced_after_payment();  
		                 break;
		case 'cancel'  : //$ret = 'You cancel this transaction.'; 
		                 $ret = $this->set_message('cancel');			
		                 break;
		case 'ipn'     : $ret = $this->error; break; 
	    default ://no action
	 }
	 
	 return ($ret);
   }
   
   function procced_on_payment() {//based on ipn data received by paypal
   
     switch (strtolower($this->p->ipn_data['payment_status'])) {
	 
	   case 'completed' : $this->savetransaction($this->p->ipn_data);//ok...
	                      break;
	   case 'pending'   : $this->savetransaction($this->p->ipn_data);//pending...
	                      break;
	   case 'failed'    : //nothing
	                      break;
	   default          : //nothing
	                      break;						  						  						  
	 }
   }
   
   function procced_after_payment() {//based on post data of success execution by paypal
   
     //echo strtolower($this->paypal_post['payment_status']),">>>>";
     switch (strtolower($this->paypal_post['payment_status'])) {
	 
	   case 'completed' :   
	    $errorcode = null; 
		$this->savelog("PAYPAL PAYMENT:SUCCESS");
	    $ret = $this->set_message('success');
        $ret .= $this->sell();   
	   break;
	   
	   //pending is my paypal problem not my customer
	   case 'pending'   : 
	     $errorcode = "Pending:".$this->paypal_post['pending_reason'];	 
	     $this->savelog("PAYPAL PAYMENT:PENDING");
		 $ret = $this->set_message('success');//,$errorcode);//hide errorcode from customer
		 $ret .= $this->sell();
		 
		 $this->tell_by_mail("Pending payment",
		                     $this->paypal_post['payer_email'],
			 				 $this->inform_ipn_mail,
							 implode("\n",$this->paypal_post)); 
							 		 
	   break; 	
	      
	   //failed or other is my customer problem
	   case 'failed'    : 
	     $errorcode = "Failed";
	   default          : 
	     $this->savelog("PAYPAL PAYMENT:ERROR!!!");
	 	 $ret = $this->set_message('error');//,$errorcode);//hide errorcode from customer
		  
		 $this->tell_by_mail("Failed payment",
		                     $this->paypal_post['payer_email'],
			 				 $this->inform_ipn_mail,
							 implode("\n",$this->paypal_post));   	   				  						  						  
	 }   
	   
	 return ($ret);     
   }
   
   function set_product_info($product=null) {
   
      $selected_product = $product ? $product : GetReq('g');
	  
	  //read the attributes
      $actfile = paramload('SHELL','prpath') . "product_details" . ".ini";							
	  //echo $actfile;
	 
      if ($pdetails=@parse_ini_file($actfile,1)) {
         
		 //print_r($pdetails);
		 
		 $myproduct = $pdetails[$selected_product];
		 
		 if ((is_array($myproduct)) && 
		     (isset($myproduct['name'])) && (isset($myproduct['price'])) ) {
		 
           $this->p->add_field('item_name', $myproduct['name']);
           $this->p->add_field('amount', $myproduct['price']);   		 
		   return true;
		 }
      }
	  
      return false;	  
   }
   
   function get_paypal_posts() {
   
     foreach ($_POST as $key => $value) { 
	   echo "$key: $value<br>"; 
	   
	   $this->paypal_post[$key] = $value;
	 }   
   }   
   
   function savelog($data) {
   
     $newdata = date("F j, Y, g:i a") . " " . $data;
   
     $actfile = paramload('SHELL','prpath') . "paypal" . ".txt";							
	 //echo $actfile;
	 if ((is_file($actfile)) && (is_writable($actfile))) 
	   $mode='a+';
	 else 
	   $mode='w';
	 
     if ($fp = @fopen ($actfile , $mode)) {
                 fwrite ($fp, $newdata."\n");
                 fclose ($fp);
     }
     else {
         $this->msg = "File creation error !\n";
		 echo "File creation error!";
         //setInfo("File creation error !");
     }   
   }
   
   function savetransaction($data) {
   
     $actfile = paramload('SHELL','prpath') . "transactions" . ".txt";							
	 //echo $actfile;
	 if ((is_file($actfile)) && (is_writable($actfile))) 
	   $mode='a+';
	 else 
	   $mode='w';
	 
     if ($fp = @fopen ($actfile , $mode)) {
                 fwrite ($fp, implode(";",$data)."\n");
                 fclose ($fp);
     }
     else {
         $this->msg = "File creation error !\n";
		 echo "File creation error!";
         //setInfo("File creation error !");
     }   
   } 
   
   
   function set_message($case,$errorcode=null) {
   
	    $m = paramload('PAYPAL',$case);	
	    $ff = $this->prpath.$m;
	    if (is_file($ff)) {
	      $ret = file_get_contents($ff);
	    }
	    else
	      $ret = $m; //plain text	
		  
		if ($errorcode)
		  $ret .= "[ERRORCODE:".$errorcode."]";
		  
		return ($ret);    	   
   }
   
   function tell_by_mail($subject,$from,$to,$body) {
         

         $smtpm = new smtpmail;
         $smtpm->to = $to; 
         $smtpm->from = $from;
         $smtpm->subject = $subject;
         $smtpm->body = $body;
         $mailerror = $smtpm->smtpsend();
         unset($smtpm);	
		 
		 if ($mailerror) echo "$subject,$from,$to : Error sending mail:",$mailerror;
		 return ($mailerror);   
   } 
   
   function sell() {
   
	    $product_id = $this->paypal_post['item_name'];
	    $link = seturl("t=pickit&g=$product_id",$product_id); 
	    $w = new window("Download",$link);
	    $ret .= $w->render();
	    unset($w);	
		
		return ($ret);   
   }    
}; 
} 
?>