<?php

$__DPCSEC['SHPIRAEUS_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("SHPIRAEUS_DPC")) && (seclevel('SHPIRAEUS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHPIRAEUS_DPC",true);

$__DPC['SHPIRAEUS_DPC'] = 'shpiraeus';

$d = GetGlobal('controller')->require_dpc('libs/sha256.lib.php');
require_once($d); 

//GetGlobal('controller')->get_parent('SHPAYPAL_DPC','PAYPAL_DPC');

$__EVENTS['SHPIRAEUS_DPC'][0]='piraeus';
$__EVENTS['SHPIRAEUS_DPC'][1]='process';
$__EVENTS['SHPIRAEUS_DPC'][2]='payreturn';
$__EVENTS['SHPIRAEUS_DPC'][3]='paycancel';
$__EVENTS['SHPIRAEUS_DPC'][4]='payticket';
 
$__ACTIONS['SHPIRAEUS_DPC'][0]='piraeus';
$__ACTIONS['SHPIRAEUS_DPC'][1]='process';
$__ACTIONS['SHPIRAEUS_DPC'][2]='payreturn';
$__ACTIONS['SHPIRAEUS_DPC'][3]='paycancel';
$__ACTIONS['SHPIRAEUS_DPC'][4]='payticket';


$__DPCATTR['SHPIRAEUS_DPC']['shpiraeus'] = 'piraeus,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['SHPIRAEUS_DPC'][0]='SHPIRAEUS_DPC;Piraeus Pay Center;Τράπεζα Πειραιώς'; 
$__LOCALE['SHPIRAEUS_DPC'][1]='_PLEASEWAIT;Please wait, your order is being processed...;Παρακαλώ περιμένετε...';  
$__LOCALE['SHPIRAEUS_DPC'][2]='_PAYCANCEL;Transaction canceled.;Ακυρωση συναλλαγης.';  
$__LOCALE['SHPIRAEUS_DPC'][3]='_PAYERROR;Error during payment. Please try later.;Λανθασμένη συναλαγή. Παρακαλώ δοκιμάστε αργότερα.';  
$__LOCALE['SHPIRAEUS_DPC'][4]='_SRVERROR;Unknown error. Please try later.;Άγνωστο σφάλμα. Παρακαλώ δοκιμαστε αργότερα.';  
 
class shpiraeus  {

   var $title;
   var $reset_database;
   var $paypal_data;
   
   var $transaction, $amount;
   var $error;
   var $path, $title2show;
   var $paypal_mail,$inform_ipn_mail;
   
   var $fields, $method;   
   
   //urls
   var $piraeus_url, $piraeus_ticket_url, $piraeus_wsdl_url;   
   //pay form
   var $acquierid, $merchantid, $posid, $piraeus_user, $piraeus_pass, $lancode, $merchantref, $parambacklink;
   //pay answer
   var $responsecode, $responsedescr,$transdatetime, $piraeus_transid, $cardtype, $supportrefid, $resultcode, $resultdescr;
   var $approvecode, $retrievalref, $authstatus, $pkgno, $parameters, $hkey, $statusflag;   
  
   //ticket answer 
   var $tranticket, $tickettime, $ticketexpires;
   var $soapaction;
   var $msg, $debug, $debug_return;
   
   var $piraeus_payment;

   function shpiraeus() {
	  $UserSecID = GetGlobal('UserSecID');
	  $UserName = GetGlobal('UserName');
	  $UserID = GetGlobal('UserID');

      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
	  $this->username = decode($UserName);
	  $this->userid = decode($UserID);   
	 
	  $this->path = paramload('SHELL','prpath'); 
	  $inpath = paramload('ID','hostinpath');	 	 
 	  $this->title = localize('SHPIRAEUS_DPC',getlocal());
	  
      $this->msg = null;
      $this->debug = 0;//1;	  
      $this->debug_return = 0;//1;	  		  
	  
      //$this->title2show = GetParam('title');
      $this->title2show =  remote_paramload('SHRORAEUS','title',$this->path);	  
      $this->amount = GetSessionParam('amount')?GetSessionParam('amount'):10.00;	  
	  
	  /*if (($this->debug) || ($this->debug_return))
	       //test transaction (already in trnas table)
	    $this->transaction = 1073;
	  else*/ //normal cart process
	  	$this->transaction = GetSessionParam('piraeusID')?GetSessionParam('piraeusID'):GetReq('tid');	 

	  
	  $sandbox = remote_paramload('SHPIRAEUS','sandbox',$this->path);
	  //echo '>',$sandbox;
	  if ($sandbox)
       //$this->piraeus_url = 'https://paycenter.winbank.gr/certification/redirection/pay.aspx';   // testing piraeus url
	   //v 1.3
       $this->piraeus_url = 'https://paycenter.winbank.gr/redirection/pay.aspx';     // piraeus url
	  else  
       $this->piraeus_url = 'https://paycenter.winbank.gr/redirection/pay.aspx';     // piraeus url
           
      $this->piraeus_ticket_url = 'https://paycenter.winbank.gr/services/tickets/issuer.asmx';

      $this->piraeus_payment = false; //init

      //$wsdlURL = "https://paycenter.winbank.gr/services/tickets/issuer.asmx?WSDL";
      //$namespace = "http://piraeusbank.gr/paycenter/redirection";

      $this->soapaction = "http://piraeusbank.gr/paycenter/redirection/IssueNewTicket";
 		   
	  

	  
	  $this->fields = array();//empty data to send
	  $this->method = 'POST';//no need	  
	  
      $this->acquierid =  remote_paramload('SHPIRAEUS','acquierid',$this->path);	
      $this->merchantid =  remote_paramload('SHPIRAEUS','merchantid',$this->path);	
      $this->posid =  remote_paramload('SHPIRAEUS','posid',$this->path);	
      $this->piraeus_user =  remote_paramload('SHPIRAEUS','user',$this->path);	
      $this->piraeus_pass =  md5(remote_paramload('SHPIRAEUS','pass',$this->path));		  
	  $this->merchantref = $this->transaction?$this->username.'#'.$this->transaction:'test'.time();
	  $this->responsecode = null;	  
	  $this->responsedescr = null;		  
	  $this->transdatetime = null;		  
	  $this->piraeus_transid = null;
	  $this->suppoerrefid = null;	
	  $this->resultcode =  null;	  
	  $this->resultdescr =  null;	  	
	  $this->cardtype =  null;	  
	  $this->pkgno =  null;
	  $this->approvecode =  null;	
	  $this->parameters =  $this->transaction?$this->username.'#'.$this->transaction:null;  //session id..????
	  $this->retrievalref =  null;	
	  $this->authstatus =  null;	
	  $this->statusflag =  null;	  	 
	  $this->tranticket =  null;		  
	  $this->tickettime =  null;		 
	  $this->ticketepires =  null;	
	  	  
	  switch (getlocal()) {
        case 1  : $this->lancode =  'el_GR'; break;
		case 0  : 
		default : $this->lancode =  'en_US'; 
	  } 	  		  
	  
      $this->parambacklink = 't=paycancel&tid='.$this->transaction;//remote_paramload('SHPIRAEUS','parambacklink',$this->path);       
	  
      //$page = $this->parambacklink?$this->parambacklink:$_SERVER['PHP_SELF'].'?t=paycancel&tid='.$this->transaction;
      //$this->this_script = 'http://'.$_SERVER['HTTP_HOST'].$inpath.'/'.$page;	  	  
	  		
   }
   
   function event($event=null) {
   
     switch ($event) {  
       case 'payreturn': $this->piraeus_payment = $this->piraeus_receive_post();
	                     if ($this->piraeus_payment) {
	                       // Order was successful...
	                       $this->handle_Transaction('success');
						   if (defined('SHCART_DPC')) {
						     $tid = $this->piraeus_get_post_params(1);
					         GetGlobal('controller')->calldpc_method("shcart.goto_mailer use ".$tid);
					       }
						 }
						 else 
						   $this->handle_Transaction('error');
	                     break;
       case 'paycancel' : //$this->savelog("PIRAEUS PAYMENT:CANCELED");	
	     	           $this->handle_Transaction('cancel');
					   if (defined('SHCART_DPC')) {
					     GetGlobal('controller')->calldpc_method("shcart.cancel_order");
					   }
	                   break;
       case 'payticket' : //not used ...procedure executes on process/default                 
	                   break;
					   
       default       : 
	   case 'piraeus':
	   case 'process':    
					   		   					   					   				   
					   					   
	                   if ($this->amount>0) {
		         			   	
                         if ($this->piraeus_create_ticket()) {
						 
						     $this->handle_Transaction('ticket');	
							 
						     $this->piraeus_create_payment();	 
							 
                             die();
                         }  
                         //else
                         // goto cart or fp with message  
                         
					   }
					   //else goto fp	 
	                   
					   break;		   
	 }
   }
   
   function action($action=null) {
   
     switch ($action) {
		case 'payreturn' ://return from bank.. have to get params..there is no known info
		                  $tid = $this->piraeus_get_post_params(1); 
						  //echo '>'.$tid;
		
		                  if ($this->piraeus_payment) {
		                     $ret = $this->set_message('success',null);
		                     if (defined('SHCART_DPC')) {
		                       //$ret = $this->set_message('success'); //NO
							   //NO NEED...called into cartview tid+3
		                       //$ret .= GetGlobal('controller')->calldpc_method("shcart.finalize_cart use ".$tid);
							   
							   $ok = GetGlobal('controller')->calldpc_method("shcart.loadcart use ".$tid);
							    if ($ok) {
		                          $ret .= GetGlobal('controller')->calldpc_method("shcart.cartview use $tid+3");
								  GetGlobal('controller')->calldpc_method("shcart.clear");
								}  
						     }	 
						   }
						   else {
						      $ret = $this->set_message('error','_PAYERROR');
		                      if (defined('SHCART_DPC')) {							  
							    //reload cart from error transaction
							    $ok = GetGlobal('controller')->calldpc_method("shcart.loadcart use ".$tid);//GetReq('tid'));
							    if ($ok)
		                          $ret .= GetGlobal('controller')->calldpc_method("shcart.cartview use ".$tid);//GetReq('tid'));
							  }	  
						   }	 
		                   break;
		case 'paycancel'  : 
		                   $ret = $this->set_message('cancel','_PAYCANCEL');   
						   if (defined('SHCART_DPC')) {
							 //reload cart from canceled transaction
							 $ok = GetGlobal('controller')->calldpc_method("shcart.loadcart use ".GetReq('tid'));
							 if ($ok)
		                       $ret .= GetGlobal('controller')->calldpc_method("shcart.cartview use ".GetReq('tid'));
							 //else goto fp  
						   }	 
		                   break;
		case 'payticket' : $ret = null;
		                   break; 
		case 'piraeus':				 
	    case 'process' : //$ret = $this->error;//null;//must not have action, if error goto frontpage'Please wait processing...'; 
	    default        : //in case of no amount (error?)  or ticket error goto cart 		
		                 $ret = $this->set_message('error','_SRVERROR');   
						 if (defined('SHCART_DPC')) {
							 //reload cart from error ... no need
							 //GetGlobal('controller')->calldpc_method("shcart.loadcart use ".GetReq('tid'));
				             if (GetGlobal('controller')->calldpc_var("shcart.qtytotal")>0)			 
		                       $ret .= GetGlobal('controller')->calldpc_method("shcart.cartview use ".GetReq('tid'));
		                       //else goto fp  
						 }	 
	 }
	 
	 return ($ret);
   }
   
   function piraeus_create_ticket() {
      $ret = false;
	  
      //create the client
      $client = new nusoap_client($this->piraeus_ticket_url, 'wsdl');
	  
      //check for errors
      $err = $client->getError();
      if ($err) {
          if ($this->debug) {
	    echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
	    echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
	  }  
	  exit();
      }
	  
      //configure client to use curl (if it is needed)
      //$client->setUseCurl(true);

      $soapmsg = $client->serializeEnvelope('
    <IssueNewTicket xmlns="http://piraeusbank.gr/paycenter/redirection">
      <Request>
        <Username>' . $this->piraeus_user . '</Username>
        <Password>' . $this->piraeus_pass . '</Password>
        <MerchantId>' . $this->merchantid . '</MerchantId>
        <PosId>' . $this->posid . '</PosId>
        <AcquirerId>' . $this->acquierid . '</AcquirerId>
        <MerchantReference>' . $this->merchantref . '</MerchantReference>
        <RequestType>' . '02' . '</RequestType>
        <ExpirePreauth>' . '0' . '</ExpirePreauth>
        <Amount>' . $this->amount . '</Amount>
        <CurrencyCode>' . '978' . '</CurrencyCode>
        <Installments>' . '0' . '</Installments>
        <Bnpl>' . '0' . '</Bnpl>
        <Parameters>' . $this->parameters . '</Parameters>
      </Request>
    </IssueNewTicket>',
    '', array(), 'document', 'literal');

     /* Send the SOAP message and specify the soapaction  */
     $result = $client->send($soapmsg, $this->soapaction);

     //check if call was fault
     if ($client->fault) {
	   echo '<h2>Client Fault</h2><pre>'; print_r($result); echo '</pre>';
	   //$this->msg = '<h2>Fault</h2><pre>'; print_r($result); echo '</pre>';
	   $ret = false;
     } else {
        //if not check for errors and print the result if no errors occured
	   $err = $client->getError();
	   if ($err) {
		   echo '<h2>Client Error</h2><pre>' . $err . '</pre>';	 
	       $ret = false;
	   } else {
           $ret = true;	   
	       if ($this->debug) {
	         echo 'OK!'; 		   
		     echo '<h2>Result</h2><pre>'; print_r($result); echo '</pre>';			 
	       }	 
	   }
     }

     //print the request, response and debug information (if it is needed)
     if ($this->debug) {
       echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
       echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
       echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
     } 
	 
     
     if ($ret==true) {//true soap call 
	   //echo 'Check...';
	   //print_r($result);
       $issue_new_ticket = $result['IssueNewTicketResult'];	 	 
	   
       $ret = $this->piraeus_receive_ticket($issue_new_ticket); //must be true 
	 }  
     else
	   $ret = false; //error;
	 
	 //$res = $ret?$ret:'false';  
	 //echo '<br>Check ticket:'.$res;
	 
     return ($ret);	  	  	  	  
   }
   
   function piraeus_receive_ticket($ticket) {
   
      $this->resultcode = $ticket['ResultCode'];	   
      $this->resultdescr = $ticket['ResultDescription'];		
	  
	  if ($f = fopen($this->path."piraeus_last_ticket.txt",'w+')) {
		 $pstr = '\rInitialize Transaction...'.$this->transaction;
	     fwrite($f,$pstr,strlen($pstr));
	     fclose($f);
	  }	   
	  
	  //echo 'a';	 					   
      if ($this->resultcode==0) {//success
	  
	     if ($f = fopen($this->path."piraeus_last_ticket.txt",'a+')) {
		      $pstr = '\rSend Ticket...Success';
	          fwrite($f,$pstr,strlen($pstr));
		      fclose($f);
	     }
		 
		 //echo 'b';			   
	     if ($this->verify_piraeus_ticket($ticket)) {
					   
					       if ($f = fopen($this->path."piraeus_last_ticket.txt",'a+')) {
					         $pstr = '\rSend Ticket...Verified';
                             foreach ($ticket as $id=>$val)
						       $pstr .= "[".$id."]=".$val."\r";							 
	                         fwrite($f,$pstr,strlen($pstr));
		                     fclose($f);
	                       }		
						   
						   return true;				   					      	   
		 }		 
	  }
	  else {//error
	    if ($f = fopen($this->path."piraeus_last_ticket.txt",'a+')) {
		      $pstr = '\rError...'.$this->resultcode.':'.$this->resultdescr;
	          fwrite($f,$pstr,strlen($pstr));
		      fclose($f);
	    }	  
	    $this->msg = $resultdescr;
		return false;
	  }		  	 	   
   }
   
   function verify_piraeus_ticket($ticket) {
      $id = $this->transaction;   
	  
      if ($this->is_unique_ticket($ticket)) {
	  
	    //echo 'Verify...';
	    $this->tranticket = $ticket['TranTicket'];	//must store!!!!   
	    $this->tickettime = $ticket['Timestamp'];	  
	    $this->ticketexpires = $ticket['MinutesToExpiration'];
		
        GetGlobal('controller')->calldpc_method("shtransactions.setTransactionStoreData use $id+costpt+".$this->amount);
        GetGlobal('controller')->calldpc_method("shtransactions.setTransactionStoreData use $id+type1+".$this->tranticket);		 		
		
	    if ($f = fopen($this->path."piraeus_last_ticket.txt",'a+')) {
		      $pstr = '\rTranTicket...'.$ticket['TranTicket'];
		      $pstr .= '\rTime...'.$ticket['Timestamp'];
		      $pstr .= '\rExpires...'.$ticket['MinutesToExpiration'];			  			  
	          fwrite($f,$pstr,strlen($pstr));
		      fclose($f);
	    }			  	  
		
		return true;
	  }

	  return false;   
   }     
   
   function is_unique_ticket($ticket) {
   
     //print_r($ticket); 
	 $isunique = false;
   
     if ($myticket = $ticket['TranTicket']) {
	 
       if ( (defined('SHTRANSACTIONS_DPC')) && (seclevel('SHTRANSACTIONS_DPC',decode(GetSessionParam('UserSecID')))) ) {
	 	  
         //$isunique = GetGlobal('controller')->calldpc_method("shtransactions.checkPiraeusTicket use ".$myticket);	 
		 $isunique = GetGlobal('controller')->calldpc_method("shtransactions.is_unique use ".$myticket );	 
	   }
	   //test
	   //$isunique = 1;
	   
	   return ($isunique);	
	 }

     //echo 'zzzzz';
	 
	 return false;     
   } 
   
   function piraeus_create_payment() {
   
	  $this->add_field('AcquirerId', $this->acquierid);
	  $this->add_field('MerchantId', $this->merchantid);	
	  $this->add_field('PosId', $this->posid);					   				   
	  $this->add_field('User', $this->piraeus_user);	
	  $this->add_field('LanguageCode', $this->lancode);
	  $this->add_field('MerchantReference', $this->merchantref);
	  $this->add_field('ParamBackLink', $this->parambacklink);	
	  
      $this->submit_data(); // submit the payment fields to piraeus		  	   
   }
   
   function piraeus_receive_post() {
      $ret = false;
	  
	  if ($this->debug_return)
	    print_r($_POST);
   
      $this->supportrefid = GetParam('SupportReferenceID');
      $this->resultcode = GetParam('ResultCode');	  
      $this->resultdescr = GetParam('ResultDescription');	  
   
	  if ($f = fopen($this->path."piraeus_last_pay.txt",'w+')) {
		 $pstr = $this->supportrefid.'\rInitialize...';
	     fwrite($f,$pstr,strlen($pstr));
	     fclose($f);
	  }
	  //echo 'a';	 					   
      if ($this->resultcode==0) {//success
	  
	     if ($f = fopen($this->path."piraeus_last_pay.txt",'a+')) {
		      $pstr = '\rValidated...'.$this->resultcode;
	          fwrite($f,$pstr,strlen($pstr));
		      fclose($f);
	     }		
		 //echo 'b';			   
	     if ($this->verify_piraeus_payment()) {
					   
					       if ($f = fopen($this->path."piraeus_last_pay.txt",'a+')) {
					         $pstr = '\rVerified...'.$this->supportrefid;
							 foreach ($_POST as $id=>$val)
						       $pstr .= "[".$id."]=".$val."\r";
	                         fwrite($f,$pstr,strlen($pstr));
		                     fclose($f);
	                       }
					       //echo 'c';
						   
						   $ret = true;;						   					      	   
		 }	 
	  }
	  else {//error
	    $this->savetransaction('RESULTCODE');
		
	    if ($f = fopen($this->path."piraeus_last_pay.txt",'a+')) {
		      $pstr = '\rError...'.$this->resultcode.':'.$this->resultdescr;
	          fwrite($f,$pstr,strlen($pstr));
		      fclose($f);
	    }
	  }	
					   
	  return ($ret);				   
   
   }
   
   
   function verify_piraeus_payment() {
 
	 $this->responsecode = GetParam('ResponseCode');//$_POST['ResponseCode'];
	 //echo '>responsecode:',$this->responsecode;
	 $this->responsedescr = GetParam('ResponseDescription');
     $this->statusflag = GetParam('StatusFlag');	 	
	    
	 $languagecode = GetParam('LanguageCode');//no need
	 $merchantref = GetParam('MerchantReference'); //compare
	 	
	 //v 1.2
	 //asynchronous statusflag must be handled	 
     if (($this->statusflag=='Success') && ($merchantref==$this->merchantref)) { 
	   
	   /*if ($this->debug_return) {
	     //test values 
		 $this->posid = 99999999;
		 $this->aquierid = 14;
		 $this->merchantref ='Test';
		 $this->approvecode = 389700;
		 $this->parameters = 'MyParam';
		 //$this->responsecode = '00'; ....remark whin no check hash
		 $this->supportrefid = 364629;	   
		 $this->authstatus = '02';
		 $this->pkgno = 1;
		 $this->statusflag = 'Success';
	     $this->hkey =  $this->make_test_hkey();		 
	   }
	   else {*/
	     $this->transdatetime = GetParam('TransactionDateTime');
	     $this->piraeus_transid = GetParam('TransactionID');	
	     $this->cardtype =  GetParam('CardType');	  
	     $this->pkgno =  GetParam('PackageNo');	
	     $this->approvecode =  GetParam('ApprovalCode');	
	     $this->retrievalref =  GetParam('RetrievalRef');	
	     $this->authstatus =  GetParam('AuthStatus');		   	   	   
	     $this->parameters =  GetParam('Parameters'); //can be check
	     $this->hkey =  GetParam('HashKey');
	   //}
	   
	   if ($this->responsecode==11) {// re-pay ...false
	   
	     $this->savetransaction('REPAY');	   
		 if ($this->debug_return)
	       echo '<br>'.$this->responsedescr;
		   
	     //return false;	
		 //$this->tell_by_mail("Recharge Attempt",'orders@audiophile-sounds.eu','orders@audiophile-sounds.eu',"Recharge Attempt on ".$this->transaction . "transaction!"); 
		 if (defined('SHCART_DPC')) 
		    GetGlobal('controller')->calldpc_method("shcart.cart_mailto use +Recharge Attempt+Recharge Attempt on ".$this->transaction . " transaction!");		 
			
		 return true;   
	   }	   
	   else { //no re-pay ...true
	     if ($this->check_hkey()) {   	    	   
	       $this->savetransaction('SUCCESS');
		   if ($this->debug_return)
	         echo '<br>'.$this->responsedescr;
			 		   		 
	       return true;	  
		 }  
	   }	 
	 }
	 
	 $this->savetransaction('STATUSFLAG');	   
	 if ($this->debug_return)
	   echo '<br>'.$this->responsedescr;
	 return false;
   } 
   
   //return the ticket number when a cust comes from bank pay (must be retrieve from db)
   function get_ticket() {
     $params = $this->parameters; //get post data back
	 $p = explode('#',$params);
	 $trid = $p[1]; 
	 //echo '>'.$trid;
	 
     $ticket = GetGlobal('controller')->calldpc_method("shtransactions.getTransactionStoreData use type1+".$trid);	 
	 
	 return ($ticket);
   }   
   
   function check_hkey() {
   
     //return true;////////////////////////////////////////////////////////// bypass check
     //when no test this is the tranticket form ticket mechanism .. must be reloaded when return from pay
	 $tranticket = $this->get_ticket();//'4236ece6142b4639925eb6f80217122f';	 
   
     $n1 = $tranticket.$this->posid.$this->acquierid.$this->merchantref.$this->approvecode.$this->parameters;
	 $n2 = $this->responsecode.$this->supportrefid.$this->authstatus.$this->pkgno.$this->statusflag;
	 
	 $n = $n1.$n2;
	 //$shaval = sha1($n);//md5($n);	 
	 //$hash = $this->mybin2hex($shaval);//dechex($hashn);	 
	 //using sha256 lib
	 $hash = sha256($n);
	 
	 //echo $hash.':'.$this->hkey;
	 
	 if (stristr($this->hkey,$hash)) {
	 
	   if ($this->debug_return)  
	     echo '<br> check hkey ok:<br>'.$n.'<br>'.$hash.'<br>'.$this->hkey.'<br>';   	 
	 
	   return true;
	 }  
	   
	 if ($this->debug_return)  
	   echo '<br> check hkey failed:<br>'.$n.'<br>'.$hash.'<br>'.$this->hkey.'<br>';  
	    
	 return false;  
   } 
   
   function make_test_hkey() {
   
     //when no test this is the tranticket form ticket mechanism .. must be reloaded when return from pay
	 $tranticket = '4236ece6142b4639925eb6f80217122f';
   
     $n1 = $tranticket.$this->posid.$this->acquierid.$this->merchantref.$this->approvecode.$this->parameters;
	 $n2 = $this->responsecode.$this->supportrefid.$this->authstatus.$this->pkgno.$this->statusflag; 
	 
	 $n = $n1.$n2;
	 //$shaval = sha1($n);//md5($n);	 
	 //$hash = $this->mybin2hex($shaval);//dechex($hashn);	 
	 //using sha256 lib
	 $hash = sha256($n);	 
	 
	 if ($this->debug_return)  
	   echo '<br> Test hkey:<br>'.$hash.'<br>'; 
	   	 
	 return ($hash);	   
   } 
   
   function mybin2hex($str) { 
   
     $strl = strlen($str); 
     $fin = ''; 
     for($i =0; $i < $strl; $i++) {
		 
         $fin .= dechex(ord($str[$i])); 
		 
         if ($this->debug_return) {
		   echo '<br>'.$str[$i].'<<<>>>'.ord($str[$i]).'<<<>>>'.dechex(ord($str[$i])); 
		 }
     } 
     return $fin; 
     
   }     
   
   function piraeus_get_post_params($getparam=null,$postfield=null) {
   
      $p = $postfield?$_POST[$postfield]:$_POST['Parameters'];
	  //echo 'parameters',$_POST['Parameters'];
	  
	  $pw = explode ('#',$p);
	  $ret = $pw[$getparam];
	  
	  return ($ret);
   }  
   
   function handle_Transaction($status,$ticket=null) {

           if ( (defined('SHTRANSACTIONS_DPC')) && (seclevel('SHTRANSACTIONS_DPC',decode(GetSessionParam('UserSecID')))) ) {
                
                $id = $this->transaction?$this->transaction:GetReq('tid');

                switch ($status) {
                    case 'ticket' : GetGlobal('controller')->calldpc_method("shtransactions.setTransactionStatus use $id+1");
                                    break;
                    case 'success': GetGlobal('controller')->calldpc_method("shtransactions.setTransactionStatus use $id+2");
                                    break;													
                    case 'cancel' : GetGlobal('controller')->calldpc_method("shtransactions.setTransactionStatus use $id+-1");
                                    break;
                    case 'error'  : GetGlobal('controller')->calldpc_method("shtransactions.setTransactionStatus use $id+-2");
                                    break;									
                }
           }
   }    
   
   function add_field($field, $value) {
      
      // adds a key=>value pair to the fields array, which is what will be 
      // sent to paypal as POST variables.  If the value is already in the 
      // array, it will be overwritten.
      
      $this->fields["$field"] = $value;
   }
   
   function submit_data() {
 
      // this function actually generates an entire HTML page consisting of
      // a form with hidden elements which is submitted to paypal via the 
      // BODY element's onLoad attribute.  We do this so that you can validate
      // any POST vars from you custom form before submitting to paypal.  So 
      // basically, you'll have your own form which is submitted to your script
      // to validate the data, which in turn calls this function to create
      // another hidden form and submit to paypal.
 
      // The user will briefly see a message on the screen that reads:
      // "Please wait, your order is being processed..." and then immediately
      // is redirected to paypal.
	  
      $url = $this->piraeus_url;
	  

      echo "<html>\n";
      echo "<head><title>Processing Payment...</title></head>\n";
	  if ($this->debug) {//dont send the request just print form
	    echo "<body>\n";
		echo '<br>' . $this->piraeus_url;
	  }
	  else //goto bank  	  
        echo "<body onLoad=\"document.form.submit();\">\n";
	  
      echo "<center><h3>".localize('_PLEASEWAIT',getlocal())."</h3></center>\n";
      echo "<form method=\"post\" name=\"form\" action=\"".$url."\">\n";

      foreach ($this->fields as $name => $value) {
         echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">";
 	     if ($this->debug) {
		   echo '<br>'. $name . '=>'.$value;
		 }
      }
 
      echo "</form>\n";
      echo "</body></html>\n";
    
   } 
   
   function set_message($case,$errorcode=null) {
   
	    $m = remote_paramload('SHPIRAEUS',$case,$this->path);
			
	    $ff = $this->path.$m;
	    if (is_file($ff)) {
	      $ret = file_get_contents($ff);
	    }
	    else
	      $ret = $m?$m:localize($errorcode,getlocal()); //plain text	
		  
		//if ($errorcode)
		  //$ret .= "[ERRORCODE:".$errorcode."]";
		 
		if ($this->msg)
		  $ret .= "<br>( $this->msg )"; 
		  
		$ret .= "<br/><h2>";  
		
		//PIRAEUS response code
		switch ($this->responsecode) {
		
		  case '12' : $ret .= "Transaction Denied! Please check your credit card."; break;
		  case '11' : if ($this->authstatus=='03') {
		                $ret .= "Approved Transaction.";//"<br>Recharge Attempt!"; //...allowed by piraeus just send a info mail..above
					  }	
		              break;		
		  case '12' : if ($this->authstatus=='02')
		                $ret .= "Transaction Declined by the bank! Please use another credit card!"; 
		              break;
		  case '00' : if ($this->authstatus=='01')
		                $ret .= "Approved Transaction.";//VISA 
					  elseif ($this->authstatus=='02')
					    $ret .= "Approved Transaction.";//MASTERCARD 
					  else
					    $ret .= "Approved Transaction.";//anyway		
					  break;
		  default   : if ($this->authstatus=='01') {
		                if ($this->resultcode =='1072')
						  $ret .= "Package is closing! Please try later.";
						elseif ($this->resultcode =='501')
		                  $ret .= "Communication Error! Please try later.";
					  }	
					  elseif ($this->authstatus=='02')	
					    $ret .= "Invalid Card Number! Please check your credit card details or use a different card.";
                      elseif ($this->authstatus=='03')	
					    $ret .= "Under procees transaction was re-send! Please try later.";						
		}
		
		$ret .= "</h2>";    
		  
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
		 
		 if ($mailerror) echo "Error sending mail:",$mailerror;
		 return ($mailerror);   
   }  
   
   function savetransaction($status) {
   
     $actfile = paramload('SHELL','prpath') . "transactions" . ".txt";							
	 //echo $actfile;
	 if ((is_file($actfile)) && (is_writable($actfile))) 
	   $mode='a+';
	 else 
	   $mode='w';
	 
	 switch ($status) {
	   case 'RESULTCODE' : $data = "$this->supportrefid,$this->merchantref,$this->resultcode,$this->resultdescr"; break;
	   case 'STATUSFLAG' : $data = "$this->supportrefid,$this->merchantref,$this->resultcode,$this->responsecode,$this->responsedescr";	break;
       case 'REPAY'      : $data = "$this->supportrefid,$this->merchantref,$this->statusflag,$this->responsecode,$this->pkgno,$this->approvecode,$this->authstatus"; break;   
       case 'SUCCESS'    : $data = "$this->supportrefid,$this->merchantref,$this->statusflag,$this->responsecode,$this->pkgno,$this->approvecode,$this->authstatus"; break;   	   
	   default           : $data = null;
	 }
	   
     $record = date('Y-m-d h:m:s') .'>'. $status.':'.$data."\n";
	 
	 //save in db
     $params = $this->parameters; //get post data back
	 $p = explode('@',$params);
	 $trid = $p[1]; 
	 //echo '>'.$trid;	 
	 $id = $this->transaction?$this->transaction:$trid; 
     GetGlobal('controller')->calldpc_method("shtransactions.setTransactionStoreData use $id+type2+".$record);	 	
				 	 
     if ($fp = @fopen ($actfile , $mode)) {	 
                 fwrite ($fp,$record);
                 fclose ($fp);
     }
     else 
		 echo "File creation error!(".$record.')';
   }              
      	
}; 
} 
?>