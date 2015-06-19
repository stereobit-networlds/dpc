<?php
$__DPCSEC['SHCUSTOMERS_DPC']='2;2;2;2;2;2;2;2;9';
$__DPCSEC['CUSTOMERSMNG_']='2;1;2;2;2;2;2;2;9';
$__DPCSEC['DELETECUSTOMER_']='2;1;1;1;1;1;1;2;9';
$__DPCSEC['UPDATECUSTOMER_']='2;1;2;2;2;2;2;2;9';
$__DPCSEC['INSERTCUSTOMER_']='1;1;1;1;1;1;2;2;9';//allow insert after user register
$__DPCSEC['SEARCHCUSTOMER_']='2;1;1;1;1;1;2;2;9';

if ((!defined("SHCUSTOMERS_DPC")) && (seclevel('SHCUSTOMERS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHCUSTOMERS_DPC",true);

$__DPC['SHCUSTOMERS_DPC'] = 'shcustomers';

$__EVENTS['SHCUSTOMERS_DPC'][0]='insert2';
$__EVENTS['SHCUSTOMERS_DPC'][1]='update2';
$__EVENTS['SHCUSTOMERS_DPC'][2]='delete2';
$__EVENTS['SHCUSTOMERS_DPC'][3]='custbulksubscribe';
$__EVENTS['SHCUSTOMERS_DPC'][4]='searchcustomer';
$__EVENTS['SHCUSTOMERS_DPC'][5]='addnewdeliv';
$__EVENTS['SHCUSTOMERS_DPC'][6]='removedeliv';
$__EVENTS['SHCUSTOMERS_DPC'][7]='savenewdeliv';
$__EVENTS['SHCUSTOMERS_DPC'][8]='addnewcus';
$__EVENTS['SHCUSTOMERS_DPC'][9]='removecus';
$__EVENTS['SHCUSTOMERS_DPC'][10]='savenewcus';
$__EVENTS['SHCUSTOMERS_DPC'][11]='selcus';
$__EVENTS['SHCUSTOMERS_DPC'][12]='inscus';
$__EVENTS['SHCUSTOMERS_DPC'][13]='mapcus';

$__ACTIONS['SHCUSTOMERS_DPC'][0]='signup2';
$__ACTIONS['SHCUSTOMERS_DPC'][1]='insert2';
$__ACTIONS['SHCUSTOMERS_DPC'][2]='update2';
$__ACTIONS['SHCUSTOMERS_DPC'][3]='delete2';
$__ACTIONS['SHCUSTOMERS_DPC'][4]='searchcustomer';
$__ACTIONS['SHCUSTOMERS_DPC'][5]='addnewdeliv';
$__ACTIONS['SHCUSTOMERS_DPC'][6]='removedeliv';
$__ACTIONS['SHCUSTOMERS_DPC'][7]='savenewdeliv';
$__ACTIONS['SHCUSTOMERS_DPC'][8]='addnewcus';
$__ACTIONS['SHCUSTOMERS_DPC'][9]='removecus';
$__ACTIONS['SHCUSTOMERS_DPC'][10]='savenewcus';
$__ACTIONS['SHCUSTOMERS_DPC'][11]='selcus';
$__ACTIONS['SHCUSTOMERS_DPC'][12]='inscus';
$__ACTIONS['SHCUSTOMERS_DPC'][13]='mapcus';

$__LOCALE['SHCUSTOMERS_DPC'][0]='_TITLE;Title;Επωνυμία';
$__LOCALE['SHCUSTOMERS_DPC'][1]='afm;VAT No;Α.Φ.Μ.;';
$__LOCALE['SHCUSTOMERS_DPC'][2]='prfdescr;Job Title;Επαγγελμα';
$__LOCALE['SHCUSTOMERS_DPC'][3]='address;Address 1;Διεύθυνση Λογιστηρίου';
$__LOCALE['SHCUSTOMERS_DPC'][4]='_POBOX1;Post code 1;Τ.Κ. Λογιστηρίου';
$__LOCALE['SHCUSTOMERS_DPC'][5]='_ADDR2;Address 2;Διεύθυνση Παράδοσης';
$__LOCALE['SHCUSTOMERS_DPC'][6]='_POBOX2;Post code 2;Τ.Κ. Παράδοσης';
$__LOCALE['SHCUSTOMERS_DPC'][7]='voice1;Voice;Αριθμός Τηλεφώνου';
$__LOCALE['SHCUSTOMERS_DPC'][8]='fax;Fax;Αριθμός Τηλεομοιότυπου';
$__LOCALE['SHCUSTOMERS_DPC'][9]='_SIGNUP;SignUp;Εγγραφή';
$__LOCALE['SHCUSTOMERS_DPC'][10]='_UPDATE;Update;Ενημέρωση';
$__LOCALE['SHCUSTOMERS_DPC'][11]='_DELETE;Delete;Διαγραφή';
$__LOCALE['SHCUSTOMERS_DPC'][12]='_MSG10;Successfull registration!;Επιτυχής καταχώρηση!';
$__LOCALE['SHCUSTOMERS_DPC'][13]='_MSG11;is required;είναι απαραίτητο';
$__LOCALE['SHCUSTOMERS_DPC'][14]='_MSG12;The value in field;Η τιμή στο πεδίο';
$__LOCALE['SHCUSTOMERS_DPC'][15]='_ACCDENIED;Access denied;Απαγορυμένη πρόσβαση';
$__LOCALE['SHCUSTOMERS_DPC'][16]='_EFORIA;Account Department;ΔΟΥ';
$__LOCALE['SHCUSTOMERS_DPC'][17]='_SEARCHCUST;Searchin;Ευρεση';
$__LOCALE['SHCUSTOMERS_DPC'][18]='_SEARCHRES;Results;Αποτελέσματα Αναζήτησης';
$__LOCALE['SHCUSTOMERS_DPC'][19]='_CUSTLIST;Customers;Πελάτες';
$__LOCALE['SHCUSTOMERS_DPC'][20]='code2;Code;Κωδικός';
$__LOCALE['SHCUSTOMERS_DPC'][21]='eforia;VAT area;ΔΟΥ';
$__LOCALE['SHCUSTOMERS_DPC'][22]='area;Area;Περιοχή';
$__LOCALE['SHCUSTOMERS_DPC'][23]='zip;Zip;Τ.Κ.';
$__LOCALE['SHCUSTOMERS_DPC'][24]='voice2;Alt. Voice;Αριθμός Τηλεφώνου';
$__LOCALE['SHCUSTOMERS_DPC'][25]='mail;e-mail;Ηλ. Ταχυδρομείο';
$__LOCALE['SHCUSTOMERS_DPC'][26]="_MSG20;Can't procced your request please try later!;Λαθος καταχώρησης. Παρακαλουμε δοκιμάστε αργότερα.";
$__LOCALE['SHCUSTOMERS_DPC'][27]='name;Name;Επωνυμια';
$__LOCALE['SHCUSTOMERS_DPC'][28]='_UNKNOWNENTRY;WARNING:You are not an official registrated customer, please insert your details below and we will contact you as soon as possible.<br> You can not use our site advance services !;ΠΡΟΣΟΧΗ: Δεν ειστε έγκυρος πελάτης της εταιρείας μας και δεν θα έχετε δικαίωμα παραγγελίας,αν είστε νέος πελατης συμπληρωστε τα παρακατω στοιχεια διαφορετικα επικοιωνήστε τηλεφωνικώς με την εταιρεια μας!';
$__LOCALE['SHCUSTOMERS_DPC'][29]='_UMAILSUBC;New customer;Νέος πελάτης';
$__LOCALE['SHCUSTOMERS_DPC'][30]='_INVOICE;Invoice;Τιμολόγιο';
$__LOCALE['SHCUSTOMERS_DPC'][31]='_APODEIXI;Receipt;Αποδειξη';
$__LOCALE['SHCUSTOMERS_DPC'][32]='_DELIVADDRESS;Delivery Address;Παράδοση σε αλλη διευθυνση...';
$__LOCALE['SHCUSTOMERS_DPC'][33]='_ADDDELIVADDRESS;Add Delivery Address;Προσθήκη διεύθυνσης...';
$__LOCALE['SHCUSTOMERS_DPC'][34]='_REMDELIVADDRESS;Remove Address;Διαγραφή διεύθυνσης';
$__LOCALE['SHCUSTOMERS_DPC'][35]='_SELDELIVADDRESS;Select Address;Επιλογή διεύθυνσης';
$__LOCALE['SHCUSTOMERS_DPC'][36]='_DEFDELIVADDRESS;Default;Βασική';//Default Address;Βασική διευθυνση';
$__LOCALE['SHCUSTOMERS_DPC'][37]='_INVALIDMAIL;Invalid e-mail address;Ακυρη ηλεκτρονικη διεύθυνση';
$__LOCALE['SHCUSTOMERS_DPC'][38]='_CUSTOMER;Pay Detail;Πελάτης';
$__LOCALE['SHCUSTOMERS_DPC'][39]='_CUSTOMERSLIST;Pay Details\'s;Στοιχεια τιμολογιου';
$__LOCALE['SHCUSTOMERS_DPC'][40]='_ADDCUSTOMER;Add Pay Details;Προσθήκη ...';
$__LOCALE['SHCUSTOMERS_DPC'][41]='_DEFCUSTOMER;Default Detail;Βασικός';
$__LOCALE['SHCUSTOMERS_DPC'][42]='_REMCUSTOMER;Remove Detail;Διαγραφή';
$__LOCALE['SHCUSTOMERS_DPC'][43]='_SELCUSTOMER;Select Detail;Επιλογή';
$__LOCALE['SHCUSTOMERS_DPC'][44]='_UPDCUSTOMER;Edit Detail;Μεταβολή';
$__LOCALE['SHCUSTOMERS_DPC'][45]='_CUSTEXISTS;Customer exists. Already registered!;Είστε ήδη καταχωρημενος!';

class shcustomers {
	var $userLevelID;
	var $username;
	var $userid;
	var $T_customers;
	var $selectedfields;
	var $fields;
	var $msg;

	var $recfields;
	var $maxcharfields;
	var $searchres;

	var $fkey; //=foreign key to second db
	var $mailkey; //mail key
	var $tellit, $it_sendfrom, $usemailasusername;
	var $urlpath, $inpath;
	var $cusform, $cusform2;
	var $unknown_customer_msg, $atok;
	var $invtype, $allow_inv_selection;
	var $mydelivery_address, $delivery_fields, $delivery_goto_url, $adddelivgoto, $delivok;
	var $is_reseller;
	var $error;
	var $checkuseasterisk,$asterisk;
	var $cusok, $addcusgoto;
	var $reseller, $reseller_attr;
	
	var $check_exist, $customer_exist_id;

	function shcustomers() {
	   $UserSecID = GetGlobal('UserSecID');
	   $sFormErr = GetGlobal('sFormErr');
	   $UserName = GetGlobal('UserName');
	   $UserID = GetGlobal('UserID');
       $this->path = paramload('SHELL','prpath');
	   
	   $this->urlpath = paramload('SHELL','urlpath');
	   $this->inpath = paramload('ID','hostinpath');			   

	   //$this->userLevelID = decode($UserSecID);
       $this->userLevelID = (((decode(GetSessionParam('UserSecID')))) ? (decode(GetSessionParam('UserSecID'))) : 0);
	   $this->username = decode($UserName);
	   $this->userid = decode($UserID);
	   $this->msg = $sFormErr;

       //select fields from table to get
	   $this->selectedfields = remote_arrayload('SHCUSTOMERS','select',$this->path);
	   $this->fieldalias = remote_arrayload('SHCUSTOMERS','selectalias',$this->path);
	   //print_r($this->selectedfields);

	   $this->fkey = remote_paramload('SHCUSTOMERS','fkey',$this->path);
	   $this->mkey = remote_paramload('SHCUSTOMERS','mailkey',$this->path);
	   $this->tellit = remote_paramload('SHCUSTOMERS','sendmailto',$this->path);
	   $this->it_sendfrom = remote_paramload('SHCUSTOMERS','sendfrom',$this->path);
	   $this->usemailasusername =  remote_paramload('SHCUSTOMERS','usemailasusername',$this->path);
	   
	   //subform fields array
	   $this->cusform =  remote_arrayload('SHCUSTOMERS','cusform',$this->path);
	   $this->cusform2 =  remote_arrayload('SHCUSTOMERS','cusform2',$this->path);	   
	   //unknown cus message 
	   $this->unknown_customer_msg = remote_paramload('SHCUSTOMERS','unknowncusmsg',$this->path);	 
	   //moved from users  
	   $this->atok = remote_paramload('SHCUSTOMERS','atok',$this->path);	
	   
	   $this->checkuseasterisk = remote_paramload('SHCUSTOMERS','checkasterisk',$this->path);	 
	   $this->asterisk = $this->checkuseasterisk?'&nbsp;':'*'; 	
	   $this->error = null;	   
	   
	   $this->is_reseller = GetSessionParam('RESELLER');	
	   $this->allow_inv_selection = remote_paramload('SHCUSTOMERS','invselect',$this->path);	   
	   $definvval = remote_paramload('SHCUSTOMERS','invtype',$this->path);
	   //echo $this->is_reseller,'>';
	   
       $invt = GetReq('invtype'); //echo $invt,'>';
	   $invtses = GetSessionParam('invway');	   
	   if ($invt) {
	     //echo 'a';
   	     //save in session for other procedures
	     SetSessionParam('invtype',$invt);
	     $this->invtype = $invt;
	   }	
	   elseif (isset($invtses)) {//cart selection is string???
	     //echo 'b';
	     //SetSessionParam('invtype',$invtses); //?????
	     $this->invtype = $definvval;//$invtses;//?????	   
	   }
	   else {
	     //echo 'c';
	     $this->invtype = $definvval; //no type of cyustomer 9reseller in this phase/($this->is_reseller)?1:1;//$definvval;
	   }	 
	   //echo '+',$this->invtype,'+';		 	 
	   
	   $delivaddress = remote_paramload('SHCUSTOMERS','deliveryaddress',$this->path);
	   $this->mydelivery_address = $delivaddress?$delivaddress:0;  
	   
	   $deliveryfields = remote_arrayload('SHCUSTOMERS','delivform',$this->path);	
	   if (empty($deliveryfields))
	     $this->delivery_fields = array('address','area','zip','voice1','voice2','fax','mail');	   
	   else
	   	 $this->delivery_fields = (array) $deliveryfields;
		 
	   $this->delivery_goto_url = "t=viewcart&status=1";
	   $this->adddelivgoto = remote_paramload('SHCUSTOMERS','adddelivgoto',$this->path);
	   $this->delivok = false;
	   
	   	   
	   $this->customer_goto_url = "t=viewcart&status=1";
	   $this->addcusgoto = remote_paramload('SHCUSTOMERS','adddelivgoto',$this->path);	   
	   $this->cusok = false;	
	   
	   $r_attr = remote_paramload('SHCUSTOMERS','reseller',$this->path);
	   if (stristr($r_attr,',')) {//multiple attr
	     $this->reseller_attr = (array) remote_arrayload('SHCUSTOMERS','reseller',$this->path);
	     //print_r($this->reseller_attr);		 	   
	   }	 
	   else	{   
	     $this->reseller_attr = $r_attr;
	     //echo '>',$this->reseller_attr;	 
	   }	 
	   $this->reseller = $this->is_reseller(); 
	   
	   $this->check_exist = remote_paramload('SHCUSTOMERS','checkexist',$this->path);	 
	   $this->customer_exist_id = null;       
	}

    function event($sAction) {

       if (!$this->msg) {

	     //$this->getFields();

         switch($sAction)   {

            case "mapcus" ://in case of subinsert set username post param
			               $this->cusok = $this->map_customer(GetParam('uname'));	 
                           break;
		 
            case "inscus" ://in case of subinsert set username post param
			               $this->cusok = $this->insert(GetParam('uname'));		 
                           break;
						   
            case "insert2":if ($this->check_exist) {
			                 if ($msg = $this->customer_exist()) {
							   if ($msg<>-1) {//remap-mail exist
			                     $this->js_map_customer();
							   }	 
							   else {
							     $this->cusok = false;
                                 SetGlobal(localize('_CUSTEXISTS',getlocal()));//'Customer exist!');	
								 $this->js_exist_mapped_customer();						   
							   }  	 
							 }  
							 else //save new cus
							   $this->cusok = $this->insert();						   
			               }
						   else
				             $this->cusok = $this->insert();
			               //}
				           break;
            case "update2"://moved into func
				           //if (!$this->error = $this->checkFields(null,$this->checkuseasterisk)) {
				           $this->cusok = $this->update(GetReq('a'),'id');
			               //}
				           break;
            case "delete2":
				           $this->_delete();
				           break;
			case 'custbulksubscribe' :
			               $this->bulk_subscribtion();
						   break;
            case "searchcustomer"   :
			               $this->searchres = $this->searchcustomer();
						   break;
						   
            case "addnewdeliv"      :
						              break;
            case "removedeliv"      : $this->delivok = $this->removedeliveryaddress();
						              break;
            case "savenewdeliv"     : $this->delivok = $this->savedeliveryaddress();
						              break;						   						   						   
            case "addnewus"         :
						              break;
		    case "selcus"           : $this->deactivatecustomers();//make all decative
		                              $this->activatecustomer(GetReq('id')); //activate selected
									  $this->is_reseller();//change type of customer
			                          break;							  								  
            case "removecus"        : $this->cusok = $this->remove_customer();
						              break;
									  
            case "savenewcus"       : if ($this->check_exist) {
			                            if ($msg = $this->customer_exist()) {
			                              if ($msg<>-1) {//remap-mail exist
			                                $this->js_map_customer();
							              }	 
							              else {
							                $this->cusok = false;
                                            SetGlobal(localize('_CUSTEXISTS',getlocal()));//'Customer exist!');							   
										    $this->js_exist_mapped_customer();
							              }
										}  
							            else //save new cus
							              $this->cusok = $this->save_customer();	  
			                          }
						              else
			                            $this->cusok = $this->save_customer();
			                          /*if (!$this->error = $this->checkFields(null,$this->checkuseasterisk)) {
				                        $this->insert();
			                          }*/
						              break;										  
          }
       }
	}

	function action($action) {

       switch ($action) {
          case "selcus"            :	   
          case "addnewcus"         :
          case "removecus"         :$out = setNavigator(localize('_CUSTOMERSLIST',getlocal()));
		                            $out .= $this->show_customers_list();
		                            //$out .= $this->addcustomerform();//moved into list
									//$out .= $this->makeform();
						            break;						
									
		  case "inscus"            :		  									
		  case "mapcus"            :			  
          case "savenewcus"        :if (($this->cusok) && ($goto=GetSessionParam('aftercusgoto'))) {
		                              //echo $goto,'>';
		                              $out = GetGlobal('controller')->calldpc_method($goto);
									}  
		                            else {
									  $out = setNavigator(localize('_CUSTOMERSLIST',getlocal()));
			                          $out .= $this->show_customers_list();
									  //$out .= $this->addcustomerform();//moved into list
									}  
						            break;		   
	   
          case "addnewdeliv"      :
          case "removedeliv"      : $out = setNavigator(localize('_SELDELIVADDRESS',getlocal()));
		                            $out .= $this->show_customer_delivery();
		                            //$out .= $this->adddeliveryform();//moved into list
						            break;	
          case "savenewdeliv"     : if (($this->delivok) && ($goto=GetSessionParam('afterdelivgoto'))) {
		                              //echo $goto,'>';
		                              $out = GetGlobal('controller')->calldpc_method($goto);
									}  
		                            else {
									  $out = setNavigator(localize('_SELDELIVADDRESS',getlocal()));
			                          $out .= $this->show_customer_delivery();
									  //$out .= $this->adddeliveryform();//moved into list
									}  
						            break;	   
          case "searchcustomer"   : $out = $this->searchresults(); break;

		  case "insert2"          :	  				  
		  default                 : $out = $this->register();
       }
       return ($out);
	}
	
	function js_map_customer() {	
	  $message = 'Customer found. Map?';	
	  $vars = $this->make_customerpost_get();  
	  $insertcustomerurl = seturl('t=inscus&'.$vars);	  
	  $mapcustomerurl = seturl('t=mapcus&id='.$this->customer_exist_id);	  
	
      if (iniload('JAVASCRIPT')) {
		
	   $code = "
	   
function map_alert() {

  if (confirm('$message'))
    window.location = '$mapcustomerurl';
   else  
    window.location = '$insertcustomerurl';
}	
window.onload=function(){
  map_alert();
}	
";	  
		   $js = new jscript;		   
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }
	  else
	    echo 'JAVASCRPIPT must be enabled!';		  	
	}	
	
	function js_exist_mapped_customer() {	
	  $message = localize('_CUSTEXISTS',getlocal());//'Customer exist.';	  
	
      if (iniload('JAVASCRIPT')) {
		
	   $code = "
window.onload=function(){
  alert('$message');	
} 
";	  
		   $js = new jscript;		   
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }	  	
	}		
	
	function make_customerpost_get() {
	
   	  $recfields = $this->get_cus_record(1,1);	

	  foreach ($recfields as $fn=>$fname) {
	    
	    $retdata[] = $fname . '=' . GetParam($fname); 
	  }	
	  
	  $ret = implode('&',$retdata);
	  return ($ret);
	}


    function register() {
	   $sFormErr = GetGlobal('sFormErr');
       $mycus = GetReq('a');

       if ($sFormErr=="ok") {
           
           //$msg = setError(localize('_MSG10',getlocal()));
           //$out .= $this->after_registration_goto();
		   
	       $myaction = GetGlobal('dispatcher')->getqueue(); //echo $myaction,"<><><><";
		   
		   switch ($myaction) {

            case "insert2": 
			               $out .= setError(localize('_MSG10',getlocal()));
						   $out .= $this->after_registration_goto();
						   break;
            case "update2": 
						   $out = setError(localize('_MSG10',getlocal()));
						   $out .= $this->after_update_goto();
						   break;
            case "delete2": $out = setError(localize('_MSG10',getlocal()));
			               $out .= $this->after_delete_goto();
			               break;
		   }		   
	   }
	   else {
           //$this->getFields();

   	       if (($mycus) && (seclevel('CUSTOMERSMNG_',$this->userLevelID))) {

   	          if (seclevel('UPDATECUSTOMER_',$this->userLevelID)) {
			   
                 $record = $this->getcustomer($mycus,'id');
	             $out .= $this->makeform($record); //update action
			  }
			  else
		         $out .= setError(localize('_ACCDENIED',getlocal()));
		   }
		   elseif (seclevel('INSERTCUSTOMER_',$this->userLevelID)) {
		         $out = $this->makeform();
		   }
		   else
		         $out .= setError(localize('_ACCDENIED',getlocal()));
	   }

	   return ($out);
	}
	
	function after_registration_goto() {
	
       if ( (defined('SHLOGIN_DPC')) && (seclevel('SHLOGIN_DPC',$this->userLevelID)) ) {
		      //already in...
		      if (GetSessionParam('UserID')) {
			    if (defined('SHCART_DPC')) { 
				  $cartitems = GetGlobal('controller')->calldpc_method('shcart.getcartItems');
				  if ($cartitems) 
			        $out = GetGlobal('controller')->calldpc_method('shcart.cartview');
				  else
				    $out = null; //goto fp	
				}  
				else  
			      $out = null; //goto fp
			  } 
			  else {
			    $out .= GetGlobal('controller')->calldpc_method('shlogin.html_form');
			    SetPreSessionParam('afterlogingoto','shcart.cartview');
			  }
		} 
			
		//$win = new window(localize('_MSG10',getlocal()),$msg);
		//$out .= $win->render("center::50%::0::group_win_body::left::0::0::");
		//unset($win);			 
			  
		return ($out);	
	}	
	
	function after_update_goto() {
	   $myaction = GetParam('FormAction');
	   //echo '>',$myaction;
	   //print_r($_POST);
	   if ((GetGlobal('UserID')) && ($myaction=='update2')) {//already in..modify account

		  $out .= $this->show_customers_list();//$this->register();   
	   }	
	   
	   return ($out);
	}
	
	function after_delete_goto() {
	
	  return ($out);
	}	

    function getcustomer($id="",$fkey=null) {
	   $db = GetGlobal('db');
	   $a = GetReq('a');
	   //$un = GetGlobal('UserName');
	   $myfkey = $fkey?$fkey:$this->fkey;

	   if ($id) 
	     $cid = $id;//param
	   elseif ($a) 
	     $cid = $a;//update
	   else {//insert
		   if ($this->usemailasusername)
		     $cid = "'".$this->username."'";
		   else
		     $cid = $this->userid;
	   }
	   
	   //$recfields = $this->cusform; not in this

	   if (!$recfields) { 
	     if ($this->usemailasusername)
           $recfields = array('name','afm','eforia','prfdescr','address','area','zip','voice1','voice2','fax','mail');
	     else
	       $recfields = array('code2','name','afm','eforia','prfdescr','address','area','zip','voice1','voice2','fax','mail');
       }
	   
       $maxc = (count($recfields)-1);
	   reset ($recfields);

       //$sSQL0 = " select code2 from users where username=".$db->qstr(decode($un));
	   //$result0 = $db->Execute($sSQL0,3);

	   if ($this->usemailasusername)
         $sSQL = "SELECT name,afm,eforia,prfdescr,address,area,zip,voice1,voice2,fax,mail";
	   else
         $sSQL = "SELECT code2,name,afm,eforia,prfdescr,address,area,zip,voice1,voice2,fax,mail";
	   //$sSQL .= ",code2";
       //foreach ($this->recfields as $field_num => $fieldname) {
	   /*foreach ($this->selectedfields as $field_num => $fieldname) {
	     $sSQL .= $fieldname;
		 if ($field_num<$maxc) $sSQL .= ",";
	   }	*/

	   $sSQL .= " FROM customers WHERE ". $myfkey . "=" . $cid;
	   //$sSQL .= " FROM customers,users WHERE users.username=" .$db->qstr(decode($un)).
	     //       " and users." . $this->fkey . "= customers." . $this->fkey;//$cid;
	   //echo 'customer:',$sSQL;

       $result = $db->Execute($sSQL,3);
	   //print_r($result->fields);

       reset($recfields);
       //foreach ($this->recfields as $field_num =>$fieldname) {
	   foreach ($recfields as $field_num=>$fieldname) {
//echo $fid,'><br>';
	      $record .= $result->fields[$field_num] . ";";
       }

       //print $record;
//echo $record;

	   return ($record);
	}

	//return formed record data as html
    function showcustomerdata($customer="",$fkey=null) {
	   $sFormErr = GetGlobal('sFormErr');
	   $data = array();
	   //template
	   $template= "showcustomerdata.htm";
	   $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$template) ;
	   if (is_readable($t)) {
	     $tokens=1;
		 $mytemplate = file_get_contents($t);
	   }	
	   
	   //$recfields = $this->cusform;//custom fields..NOT in this
	   
	   if (!$recfields) {
         //$this->getFields();
	     if ($this->usemailasusername)
           $recfields = array('name','afm','eforia','prfdescr','address','area','zip','voice1','voice2','fax','mail');
	     else
	       $recfields = array('code2','name','afm','eforia','prfdescr','address','area','zip','voice1','voice2','fax','mail');
       }
       reset($recfields);
	   
       //when show activate viewed customer so deactivat all other same user clients
	   $this->deactivatecustomers();	   
       //read data
	   if ($customerway = GetReq('customerway')) {
	     //echo 'z22';
	     $fields = $this->getcustomer($customerway,'id');
		 SetSessionParam('scustomer',$customerway);
		 $this->activatecustomer($customerway);		 
	   }	 
	   elseif ($selectedcustomer = GetSessionParam('scustomer')) {
	     //echo 'a22'; 
	     $fields = $this->getcustomer($selectedcustomer,'id');	
		 $this->activatecustomer($selectedcustomer);		 
	   }	    
	   else	{ 
	     //echo 'b22';
	     $fields = $this->getcustomer($customer,$fkey,1);
		 $this->activatecustomer();		 
	   }	 

       //in case of no customer data this must return null
	   if (strlen($fields)>11) { //if empty returns ';;;;;;;;;'

		   $myfields = explode(";",$fields);
		   array_pop($myfields); //get out the empty last element

           //while (list ($field_num, $fieldname) = each ($this->recfields)) {
           //print_r($myfields);
           foreach ($myfields as $id=>$rec) {
		       $data[$id] = ($rec?$rec:"&nbsp;");
		   }
		   
		   if ($tokens) {
		     if (seclevel('CUSTOMERSMNG_',$this->userLevelID)) {
               $uid = $this->userid;
			   //signup2 became signup to handle user and customer
               $data[] = seturl("t=signup&a=$uid&g=" , localize('_UPDATE',getlocal()) );			 
			 }
			 
		     $out = $this->combine_tokens($mytemplate,$data);
		   }
           else {
             $aligntitle = "right;40%;";
	         $alignfield = "left;60%;";

             $out .= setTitle(localize('_TRANSDATA',getlocal()));

             //show data
             reset ($recfields);
	         reset ($data);
             //print_r($data); 
             //while (list ($field_num, $fieldname) = each ($this->recfields)) {
             foreach ($data as $id=>$rec) {

               $field[] = localize($recfields[$id],getlocal()) . ":";
	           $attr[] = $aligntitle;
	           $field[] = $rec;
	           $attr[] = $alignfield;
	           $w = new window('',$field,$attr);
		       $out .= $w->render("center::100%::0::group_article_selected::left::0::0::");
		       unset ($field);  unset ($attr);
	         }

             if (seclevel('CUSTOMERSMNG_',$this->userLevelID)) {
               $uid = $this->userid;
			   //signup2 became signup to handle user and customer
               $d = seturl("t=signup&a=$uid&g=" , localize('_UPDATE',getlocal()) );
	           $w = new window('',$d);
	           $out .= $w->render(" ::100%::0::group_form_foottitle::center;100%;::");
	           unset($w);
		     }
		   }//template
       }

	   //$out = 'CUSTOMER';

	   return ($out);
	}

	//return leeid of customer based on ageneric where (suitable for pre register user procedure)
	function search_customer_id($where_statement) {
       $db = GetGlobal('db');

	   $sSQL = "SELECT CODE2 FROM customers WHERE " .$where_statement;
	   //echo $sSQL;
       $result = $db->Execute($sSQL,3);

	   //echo $result->fields[0];

	   return ($result->fields[0]);
	}

    function makeform($fields='',$notitle=null,$cmd=null,$isupdate=null,$go_to=null) {
	   $sFormErr = GetGlobal('sFormErr');
	   $goto = $go_to?$go_to:"t=signup2&a=".GetReq('a'); 
	   
	   if (!$invtype = GetSessionParam('invtype'))
	     $invtype = GetParam('invtype')?GetParam('invtype'):$this->invtype;
	   
       $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',/*$invtype .*/ getlocal().'.','cusregister.htm') ; 
	   //echo $t;
	   if (is_readable($t)) {
		 $mytemplate = file_get_contents($t);
		 $tokensout = 1;
       }	   

       //navigation status
       if (!$notitle)
  	     $winout = setNavigator(localize('_TRANSDATA',getlocal()));
	   
	   if ($this->usemailasusername) {
		  //do nothing
	   }
	   else {//predefined customer but customer not found
	      if ($unknown_customer_msg) {
		    if (stristr($unknown_customer_msg,'.tpl')) {//template
			  $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$unknown_customer_msg) ;
			  $unknown_entry = file_get_contents($t);
			}
			else
		    $unknown_entry = $unknown_customer_msg;
		  }	
		  else
		    $unknown_entry = localize('_UNKNOWNENTRY',getlocal());
			
		  $content = $unknown_entry . "<H3>".$this->atok."</H3>";							   
						   
		  $sFormErr .= '<br>' . $content;
	   }	   
							 
       //error message
	   if ($sFormErr!="ok") {
	     $winout .= setError($sFormErr);							 
	   }	 
	   
	   $data =  array();       //read data	  
	   $recfields = $this->get_cus_record(1,1);		 
	   reset($recfields);
	   
	   if ($fields) { //get record param
		   $myfields = explode(";",$fields);

           foreach ($recfields as $recid => $rec) {
                                       //btpass code by add +1
		       $data[$recid] = ($myfields[$recid]?$myfields[$recid]:"&nbsp;");
		   }
	   }
	   else { //read form data
           foreach ($recfields as $fnum => $fname) {
		       $data[$fnum] = ToHTML(GetParam(_with($fname)));
		   }
	   }

       $aligntitle = "right;40%;";
	   $alignfield = "left;60%;";

       $sFileName = seturl($goto,0,1);

	   
       if ($tokensout) {
	     if ($sFormErr!="ok") 
		   $err = $sFormErr;

	     //message at top
         $tokens[] = localize('_TRANSINFO',getlocal()) . '<br>' . $err .
		             "<form method=\"POST\" action=\"" . $sFileName . "\" name=\"Registration2\">";	   
	   }	   
	   else {
	     $warning = new window('',localize('_TRANSINFO',getlocal()));
	     $out .= $warning->render(" ::100%::0::group_article_selected::center;100%;::");
	     unset($warning);
	   	   
         $out .= "<form method=\"POST\" action=\"";
         $out .= "$sFileName";
         $out .= "\" name=\"Registration2\">";	   
	   }

       //show data
       reset ($recfields);
	   reset ($data);
       //while (list ($field_num, $fieldname) = each ($this->recfields)) {
       foreach ($recfields as $field_num => $fieldname) {
	   
         if ($tokensout) {
	       //inputs
           $tokens[] = "<input type=\"text\" name=\"" . _with($fieldname) . "\" maxlength=\"" . $maxcharfields[$field_num] . "\" value=\"" .
                       $data[$field_num] . "\" size=\"" . "25" . "\" >";
		 }
		 else {	   
           $field[] = localize($recfields[$field_num],getlocal()) . "*";
	       $attr[] = $aligntitle;
           $f  = "<input type=\"text\" name=\"" . _with($fieldname) . "\" maxlength=\"" . $maxcharfields[$field_num] . "\" value=\"";
           $f .= $data[$field_num];
           $f .= "\" size=\"" . "25" . "\" >";
	       $field[] = $f;
	       $attr[] = $alignfield;
	       $w = new window('',$field,$attr);
		   $out .= $w->render("center::100%::0::group_article_selected::left::0::0::");
		   unset ($field);  unset ($attr); unset ($f);
		 }
	   }

       if (GetReq('a') && ((seclevel('CUSTOMERSMNG_',$this->userLevelID)) || ($isupdate))) {

           //$out2 = "<input type=\"hidden\" value=\"update2\" name=\"FormAction\"/>";

           if ((seclevel('UPDATECUSTOMER_',$this->userLevelID)) || ($isupdate)) {
              $updcmd = $cmd?$cmd:'update2';
              $out2 = "<input type=\"hidden\" value=\"$updcmd\" name=\"FormAction\"/>";			  
              $out2 .= "<input type=\"submit\" value=\"" . localize('_UPDATE',getlocal()) . "\">";// onclick=\"document.forms('Registration2').FormAction.value = '$updcmd';\">";
		   }

           if (seclevel('DELETECUSTOMER_',$this->userLevelID)) {
              $out2 = "<input type=\"hidden\" value=\"delete2\" name=\"FormAction\"/>";		   
              $out2 .= "<input type=\"submit\" value=\"" . localize('_DELETE',getlocal()) . "\">";// onclick=\"document.forms('Registration2').FormAction.value = 'delete2';\">";
		   }
       }
       else {
           $dpccmd = $cmd?$cmd:'insert2';
           $out2 .= "<input type=\"submit\" value=\"" . localize('_SIGNUP',getlocal()) . "\" onclick=\"document.forms('Registration2').FormAction.value = '$dpccmd';\">";
           $out2 .= "<input type=\"hidden\" value=\"$dpccmd\" name=\"FormAction\"/>";
       }
       
	   if ($usermail)//hidden user mail to check
	     $out2 .= "<input type=\"hidden\" name=\"mail\" value=\"$usermail\">";
		 
       $out2 .= "<input type=\"hidden\" name=\"FormName\" value=\"insert2\">";
       $out2 .= "</form>";
	   
       if ($tokensout) {
	       //submit buttons
           $tokens[] = $out2;
		   
		   $ret = $this->combine_tokens($mytemplate,$tokens);
		   return ($ret);		   
	   }
	   else {	   
	     $out .= $out2;

	     $uwin = new window(localize('_TRANSDATA',getlocal()),$out);
	     $winout .= $uwin->render();
	     unset($uwin);

	     return ($winout);
	   }
	}
	
	//used by userform to combine form in one
	function makesubform($tokensout=null) {

	   if (!$invtype = GetSessionParam('invtype'))	   
	     $select_inv = GetParam('invtype')?GetParam('invtype'):$this->invtype;
	   
	   if ($this->allow_inv_selection) {//show invoice selection
         if ($tokensout) 
           $tokens[] = $this->select_invoice($select_inv,'signup');	 
		 else
		   $out = $this->select_invoice($select_inv,'signup');    
	   }
	   else {
         if ($tokensout) 
           $tokens[] = null; //first token null	 	   
		 else
		   $out = null;  
	   }  
	   
	   switch ($select_inv) {
	      case 1 : $recfields = $this->cusform;
		           break;  
		  case 0 : 
	     default : $recfields = $this->cusform2;
	   }

	   //$recfields = $this->cusform;//array('afm','eforia','prfdescr','address','area','zip','voice1');
	   
       $aligntitle = "right;40%;";
	   $alignfield = "left;60%;";
	   		 
	   reset($recfields);
	   
	   if ($fields) { //get record param
		   $myfields = explode(";",$fields);
//print_r($myfields);
           //while (list ($field_num, $fieldname) = each ($this->recfields)) {
           foreach ($recfields as $recid => $rec) {
                                       //btpass code by add +1
		       $data[$recid] = ($myfields[$recid]?$myfields[$recid]:"&nbsp;");
		   }
	   }
	   else { //read form data
        
           foreach ($recfields as $fnum => $fname) {
		       $data[$fnum] = ToHTML(GetParam(_with($fname)));
		   }
	   }
	   
       //show data
       reset ($recfields);
	   reset ($data);
       //while (list ($field_num, $fieldname) = each ($this->recfields)) {
       foreach ($recfields as $field_num => $fieldname) {  
	   
         if ($tokensout) {
	       //inputs
           $tokens[] = "<input type=\"text\" name=\"" . _with($fieldname) . "\" maxlength=\"" . $maxcharfields[$field_num] . "\" value=\"" .
                       $data[$field_num] . "\" size=\"" . "25" . "\" >";
		 }
		 else {	   
           $field[] = localize($recfields[$field_num],getlocal()) . "*";
	       $attr[] = $aligntitle;
           $f  = "<input type=\"text\" name=\"" . _with($fieldname) . "\" maxlength=\"" . $maxcharfields[$field_num] . "\" value=\"";
           $f .= $data[$field_num];
           $f .= "\" size=\"" . "25" . "\" >";
	       $field[] = $f;
	       $attr[] = $alignfield;
	       $w = new window('',$field,$attr);
		   $out .= $w->render("center::100%::0::group_article_selected::left::0::0::");
		   unset ($field);  unset ($attr); unset ($f);
		 }
	   }
	   
       if ($tokensout) {
		   //$ret = $this->combine_tokens($mytemplate,$tokens);
		   
		   if ($this->mydelivery_address) {
		     $extratokens = $this->makedelivsubform($tokensout);
			 $etokens = array_merge($tokens,$extratokens);
			 return ($etokens);
		   }	 
		   else
		     return ($tokens);		   
	   }
	   else {	   
	     $wout = "<br>";
	     $warning1 = new window('',localize('_TRANSDATA',getlocal()));
	     $wout .= $warning1->render(" ::100%::0::group_article_selected::center;100%;::");
	     unset($warning1);
	     $wout .= "<br>";
		 
		 $wout .= $out;
		 
		 if ($this->mydelivery_address)
		   $wout .= $this->makedelivsubform($tokensout);
		 
		 return ($wout); 
		 
	     //$uwin = new window(localize('_TRANSDATA',getlocal()),$out);
	     //$winout .= $uwin->render();
	     //unset($uwin);
	     //return ($winout);
	   }	   	   	
	}
	
	function select_invoice($itype=null,$cmd=null) {
	
	  $t = $cmd?$cmd:GetReq('t');
	  //in case of cart procedure
	  $status = GetParam('status');
	  
	  $ret = seturl('t='.$t.'&invtype=1',localize('_INVOICE',getlocal()));
	  $ret .= "&nbsp;/&nbsp;";
	  $ret .= seturl('t='.$t.'&invtype=0',localize('_APODEIXI',getlocal()));	
	    
	
	  return ($ret);
	}
	
	function get_invoice_type() {
	  
	  $ret = $this->invtype?$this->invtype:'0';
	  return ($ret);
	  //return (GetSessionParam('invtype'));
	}
	
	function customer_exist($returnid=false) {
	   $db = GetGlobal('db');
	   $param = $this->check_exist;//afm
	   $value = GetParam($param)?GetParam($param):null;
	   
	   if (!$value) return false;
	   
       $sSQL = "select $param,id,code2 from customers where $param=" . $db->qstr($value);
       //echo $sSQL;
       $result = $db->Execute($sSQL,2);
	   //print_r($result->fields);
       $paramret = $result->fields[0];
	   
	   //prevent to remap customer
	   if ($result->fields[2]) return (-1);//false; //-1??
	   
	   if ($paramret) {
	     $this->customer_exist_id = $result->fields[1];//true; //return id as map param
		 
		 if ($returnid)
		   return ($this->customer_exist_id);
		 else  
	       return 'Details with ' . $param . '=' . $paramret . ' exist!';//$true;    
	   }	 
		
	   return false;	
	  
	}
	
	//new map param comes form users (dpc call)
	function map_customer($userid=null,$id=null) {
	   $db = GetGlobal('db');
	   //if already in id is in requets else in param (from new user)
	   $id = $id?$id:GetReq('id');
	   //if already loged if get global userid else set userid=post field (called from func) 
	   $userid = $userid?$userid:decode(GetGlobal('UserID'));
	   //$myfkey = $fkey?$fkey:$this->fkey;	
	   
	   if ($uid = $userid) {   
	   
	     if ($id) {
		 
	       $this->deactivatecustomers($uid); //deactivate all		 
		 
           $sSQL = "update customers set active=1,code2=" . $db->qstr($uid) . ",mail=" . $db->qstr($uid) . " where id=" . $id;//$db->qstr($value);
		  
           //echo $sSQL;
           $result = $db->Execute($sSQL,1);  	
		 
           if ($db->Affected_Rows())    
             SetGlobal('sFormErr',"ok");	
			 	 
		   return true;
         }
	   }
	   return false;	 
	}

	function insert($userid=null) {
	   $db = GetGlobal('db');
	   
	   if ($error = $this->checkFields(null,$this->checkuseasterisk)) {
	       SetGlobal('sFormErr',$error);
	       return false;//($error);
	   }	   

       //echo '>',GetSessionParam('new_user_code');   
	   if ($userid) {//code inside
	     $uid = $userid;
	   }
	   elseif ($userid = GetSessionParam('new_user_code')) {//if user has pre-insert???????
	     $uid= $userid;
	   }
	   elseif ($userid = decode(GetSessionParam('UserID'))) {//has login already
	     $uid= $userid;
	   }	 
	   else {//never must happen
	     SetGlobal('sFormErr','Invalid user key!');
		 return null;

		 //$uid = $db->qstr(addslashes(GetParam('uname')));
	   }	 
	     	  
	   
	   $recfields = array('code2','name','afm','eforia','prfdescr','address','area','zip','voice1','voice2','fax','mail');

       $sSQL = "insert into customers (code2,active,name,afm,eforia,prfdescr,address,area,zip,voice1,voice2,fax,mail)";
	   $sSQL.= " values (" .
	           $db->qstr($uid) . ',1,' .
	           $db->qstr(addslashes(GetParam('name'))) . ',' .
	           $db->qstr(addslashes(GetParam('afm'))) . ',' .
	           $db->qstr(addslashes(GetParam('eforia'))) . ',' .
	           $db->qstr(addslashes(GetParam('prfdescr'))) . ',' .
	           $db->qstr(addslashes(GetParam('address'))) . ',' .
	           $db->qstr(addslashes(GetParam('area'))) . ',' .
	           $db->qstr(addslashes(GetParam('zip'))) . ',' .
	           $db->qstr(addslashes(GetParam('voice1'))) . ',' .
	           $db->qstr(addslashes(GetParam('voice2'))) . ',' .
	           $db->qstr(addslashes(GetParam('fax'))) . ',' .
	           $db->qstr(addslashes(GetParam('mail'))) .
	           ")";

       //echo $sSQL;
       $result = $db->Execute($sSQL,1);
	   //print_r($result->fields);
       if ($db->Affected_Rows()) {	   
         SetGlobal('sFormErr',"ok");
		 
	     if ($this->tellit) {
		 
	      $template= "customerinsert.htm";
	      $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$template) ;
		  //echo $t;
	      if (is_readable($t)) {
		    $mytemplate = file_get_contents($t);
			
		    foreach ($recfields as $field_num => $fieldname)
		      $tokens[] = GetParam($fieldname);			
			
			$mailbody = $this->combine_tokens($mytemplate,$tokens);
	      }	
	   	  else {	 
	        $mailcontent = "New customer:<br>";
			
		    foreach ($recfields as $field_num => $fieldname)
		      $mailcontent .= localize($recfields[$field_num],getlocal()) .' : '. GetParam($fieldname) . "<br>";

		    $template = paramload('SHELL','prpath') . "insertusrusr.tpl";
		    $mailbody = str_replace("##_LINK_##",$mailcontent,file_get_contents($template));
          }
		  
		  $from = $this->it_sendfrom?$this->it_sendfrom:GetParam('mail');
		  $subject = remote_paramload('SHCUSTOMERS','tellsubject',$this->path);
		  $mysubject = $subject?$subject:localize('_UMAILSUBC',getlocal());
	      $this->mailto($from,$this->tellit,$mysubject,$mailbody);
	     }	
		 
		 return true;	 
	   }
	   else {
		 echo $db->ErrorMsg();
		 SetGlobal('sFormErr',localize('_MSG20',getlocal()));
	   }	   	 

	   return false;//($result);

	}
	
	//used by users in one form combination (usercode must fill to connect)
	//..silent check existing customer
	function subinsert($userid=null,$bypasscheck=null) {
	   $db = GetGlobal('db');
	   
	   if ($error = $this->checkFields($bypasscheck,$this->checkuseasterisk)) {
	       SetGlobal('sFormErr',$error);
	       return ($error);
	   }
   
	   if ($userid) {//code inside
	     $uid = $userid;
	   } 
	   else {//never must happen
	     SetGlobal('sFormErr','Invalid user key!');
		 return null;

		 //$uid = $db->qstr(addslashes(GetParam('uname')));
	   }	 
	   
	   //what to store as mail ?  
       if ($usemailasusername=GetGlobal('controller')->calldpc_var('shusers.usemailasusername'))	
	     $email = $userid;//GetParam('uname');
	   else	 
	     $email = GetParam('eml');
		 
	   //before set active the current record deactive all others  
	   $d = $this->deactivatecustomers($uid);			 			 
		 
	  
       $sSQL = "insert into customers (code2,active,name,afm,eforia,prfdescr,address,area,zip,voice1,voice2,fax,mail)";
	   $sSQL.= " values (" .
	           $db->qstr($uid) . ',1,' .
	           $db->qstr(addslashes(GetParam('lname'))) . ',' . //user's name
	           $db->qstr(addslashes(GetParam('afm'))) . ',' .
	           $db->qstr(addslashes(GetParam('eforia'))) . ',' .
	           $db->qstr(addslashes(GetParam('prfdescr'))) . ',' .
	           $db->qstr(addslashes(GetParam('address'))) . ',' .
	           $db->qstr(addslashes(GetParam('area'))) . ',' .
	           $db->qstr(addslashes(GetParam('zip'))) . ',' .
	           $db->qstr(addslashes(GetParam('voice1'))) . ',' .
	           $db->qstr(addslashes(GetParam('voice2'))) . ',' .
	           $db->qstr(addslashes(GetParam('fax'))) . ',' .
	           $db->qstr(addslashes(strtolower($email))) . //user email
	           ")";

       //echo $sSQL;
       $result = $db->Execute($sSQL,1);
	   //print_r($result->fields);
       if ($ret = $db->Affected_Rows()) {	   
         SetGlobal('sFormErr',"ok");
		 
		 //delivery address
		 if (($this->mydelivery_address) && (!$error = $this->check_delivery_address(1))) {
		   //save at customer address book
		   $save=0;
		   foreach ($this->delivery_fields as $fn=>$fname) {
		     $data_entry = GetParam($fname.'_d');
		     $delivdata[] = $db->qstr($data_entry); //to separate from default address name fields  
			 if ($data_entry) {
			   //echo '>',$data_entry;
			   $save=1;
			 }  
		   }	 
		   if ($save) {	  
		     //before set active the current record deactive all others  
		     $d = $this->deactivatedeliveryaddress($uid);
		   		   
		     $addressfields = implode(',',$this->delivery_fields);
             $sSQL2 = "insert into custaddress (ccode,active,$addressfields)";
	         $sSQL2.= " values (". $db->qstr($uid). ",1,";
             $sSQL2.= implode(',',$delivdata);
	         $sSQL2.= ")";

             //echo $sSQL2;
             $result2 = $db->Execute($sSQL2,1);		   	   
		   }
		 }		 
		 
	     if ($this->tellit) {
	      $template= "customerinserttell.htm";
	      $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$template) ;
		  //echo $t;
	      if (is_readable($t)) {
		    $mytemplate = file_get_contents($t);
			
		    $tokens[] = GetParam('uname');			
		    $tokens[] = GetParam('lname');	
		    $tokens[] = GetParam('afm');						
		    $tokens[] = GetParam('eforia');			
		    $tokens[] = GetParam('prfdescr');
		    $tokens[] = GetParam('address');
		    $tokens[] = GetParam('area');														
		    $tokens[] = GetParam('zip');			
		    $tokens[] = GetParam('voice1');			
		    $tokens[] = GetParam('voice2');			
		    $tokens[] = GetParam('fax');			
		    $tokens[] = $email;			
			
			$mailbody = $this->combine_tokens($mytemplate,$tokens);
	      }	
	   	  else {			 
	       $mailcontent = "New customer:<br>" . GetParam('name');

		   $template = paramload('SHELL','prpath') . "insertusrusr.tpl";
		   $mailbody = str_replace("##_LINK_##",$mailcontent,file_get_contents($template));
          }
		  $from = $this->it_sendfrom?$this->it_sendfrom:$email;
		  $subject = remote_paramload('SHCUSTOMERS','tellsubject',$this->path);
		  $mysubject = $subject?$subject:localize('_UMAILSUBC',getlocal());
	      $this->mailto($from,$this->tellit,$mysubject,$mailbody);
	     }		 
	   }
	   else {
	     $ret = $db->ErrorMsg();
		 echo $ret;
		 SetGlobal('sFormErr',localize('_MSG20',getlocal()));
	   }	   	 

	   return (GetGlobal('sFormErr'));

	}	
	
	//rollabck subinsert from users
	function subdelete($userid=nul) {
	   $db = GetGlobal('db');	
	   
	   if ($userid) {
         $sSQL = "delete from customers";
	     $sSQL.= " where code2=". $db->qstr($userid);
	     //echo $sSQL;	   
         $result = $db->Execute($sSQL,1);	 
	   }
	     
       if ($ret = $db->Affected_Rows())
	     return true;		      
	   else
	     return false;		   
	}   	

	function update($id=null,$fkey=null) {;
	   $db = GetGlobal('db');
	   $myfkey = $fkey?$fkey:$this->fkey;
	   $key = decode(GetGlobal('UserName'));//security..foreign to user
	   
	   if ($error = $this->checkFields(null,$this->checkuseasterisk)) {
	       SetGlobal('sFormErr',$error);
	       return false;//($error);
	   }		   

       if ($this->usemailasusername) {
	     if (GetParam('uname')) //= mail
		   $recfields = array('name','afm','eforia','prfdescr','address','area','zip','voice1','voice2','fax');
		 else
	       $recfields = array('name','afm','eforia','prfdescr','address','area','zip','voice1','voice2','fax','mail');
	   }
	   else
	     $recfields = array('code2','name','afm','eforia','prfdescr','address','area','zip','voice1','voice2','fax','mail');

	   if (!$id) return (false);

       $sSQL = "update customers set ";
	   $sSQL.= /*'code2='.$db->qstr(GetParam('code2')) . ',' .*/
	           'name='.$db->qstr(addslashes(GetParam('name'))) . ',' .
	           'afm='.$db->qstr(addslashes(GetParam('afm'))) . ',' .
	           'eforia='.$db->qstr(addslashes(GetParam('eforia'))) . ',' .
	           'prfdescr='.$db->qstr(addslashes(GetParam('prfdescr'))) . ',' .
	           'address='.$db->qstr(addslashes(GetParam('address'))) . ',' .
	           'area='.$db->qstr(addslashes(GetParam('area'))) . ',' .
	           'zip='.$db->qstr(addslashes(GetParam('zip'))) . ',' .
	           'voice1='.$db->qstr(addslashes(GetParam('voice1'))) . ',' .
	           'voice2='.$db->qstr(addslashes(GetParam('voice2'))) . ',' .
	           'fax='.$db->qstr(addslashes(GetParam('fax'))) . ',' .
	           'mail='.$db->qstr(addslashes(GetParam('mail')))  .
	           " where $myfkey=".$id . " and " . "code2=" . $db->qstr($key);

       //echo $sSQL;
       $result = $db->Execute($sSQL,1);
	   //print_r($result->fields);
       if ($db->Affected_Rows()) {	   
         SetGlobal('sFormErr',"ok");
		 return true;
	   }
	   else {
		 echo $db->ErrorMsg();
		 SetGlobal('sFormErr',localize('_MSG20',getlocal()));
	   }	 

	   return false;//($result);
	}

	function _delete($id=null) {
	   $db = GetGlobal('db');

	   if (!$id) return (false);

       $sSQL = "delete from customers where id=" . $id;

       $result = $db->Execute($sSQL,1);
	   //print_r($result->fields);
       if ($db->Affected_Rows()) {	   
         SetGlobal('sFormErr',"ok");
	   }
	   else {
		 echo $db->ErrorMsg();
		 SetGlobal('sFormErr',localize('_MSG20',getlocal()));
	   }	

	   return ($result);
	}

	function getmaxid() {
	   $db = GetGlobal('db');

	   //if (!$id) return (false);

       $sSQL = "select max(id) from customers";

       $result = $db->Execute($sSQL,2);
	   //print_r($result->fields);

	   return ($result->fields[0]);
	}

    function checkFields($bypass=null,$checkasterisk=null) {
	   $sFormErr = GetGlobal('sFormErr');
	   //SetGlobal('sFormErr',"");		
	   if ($bypass) 
	     return null;		
	   
	   $recfields = $this->get_cus_record();
	
	   if ($checkasterisk) {
	     foreach ($recfields as $field_num => $fieldname) {
    		//$title = localize($recfields[$field_num],getlocal());
			$titles = explode('/',remote_paramload('SHCUSTOMERS',$fieldname,$this->path));
			$title = $titles[getlocal()];			
	     	if (strstr($title,'*')) { //check by titile using *

              if(!strlen(GetParam(_with($fieldname)))) {
                $sFormErr .= localize('_MSG12',getlocal()) . " <font color=\"red\">" .
		                     $title . "</font> " .
		                     localize('_MSG11',getlocal()) . "<br>";		  			
			  }
			}
		 }		 
	   }
	   else {	   
         //while (list ($field_num, $fieldname) = each ($this->recfields)) {
         foreach ($recfields as $field_num => $fieldname) {
           //echo $fieldname,'<br>';
           if(!strlen(GetParam(_with($fieldname)))) {
             $sFormErr .= localize('_MSG12',getlocal()) . " <font color=\"red\">" .
		                  localize($recfields[$field_num],getlocal()) . "</font> " .
		                  localize('_MSG11',getlocal()) . "<br>";
             //echo $fieldname;
           }
		 }  
       }
	   
	   //extra checks
	   if ((GetParam("mail")) && (checkmail(GetParam("mail"))==false))
		 $sFormErr .= localize('_INVALIDMAIL',getlocal()) . "<br>";			   
 
	   SetGlobal('sFormErr',$sFormErr);
       //echo 'sformerr:',$sFormErr;
	   
       return ($sFormErr);
    }
	
	function get_cus_type($id,$field=null,$istext=0) {
        $db = GetGlobal('db');
		$mycode = $field?$field:'code2';

		if ($id) { 
	      $sSQL = "select attr1 from customers where active=1 and $mycode=";
		  switch ($istext) {
		    case 1 : $sSQL .= $db->qstr($id); break;
		    case 0 :
		    default: $sSQL .= $id;
		  }
		  $res = $db->Execute($sSQL,2);
		  //echo $sSQL;
		  $ret = $res->fields[0];
		  //echo $ret;
		  return ($ret);	
		}
		
		return false;
	}
	
	function is_reseller($id=null) {
       $UserName = GetGlobal('UserName');		   
	
       if ($this->usemailasusername) {
	     $id = decode($UserName);	
	     $ret = $this->get_cus_type($id,null,1);
	   }	 
	   else	 
	     $ret = $this->get_cus_type($id);
	
	   if (!$ret) return;	 
		  
	   if (is_array($this->reseller_attr) && (!empty($this->reseller_attr))) {
	     //echo $ret,'-',$this->reseller_attr,'>';	 		   
	     foreach ($this->reseller_attr as $i=>$attr) {
	       if ($ret==$attr) {
		     //echo 'true';
	         SetSessionParam('RESELLER','true');
	         return true;
	       }		 
		 }
	   }
	   else {
	     //echo $ret,'-',$this->reseller_attr,'>';	   
	     if ($ret==$this->reseller_attr) {
		   //echo 'true';		 
	       SetSessionParam('RESELLER','true');
	       return true;
	     }
	   }	 	 
		 	 	 
	   return false;
	}	
	
	function get_cus_record($invtype=null,$default_records=null) {
	
	   $invtype = $invtype?$invtype:$this->invtype;
	   
	   if (!$default_records) {
	
	   if ($invtype==1)
	     $recfields = $this->cusform;//custom fields invoice
	   else	 
	     $recfields = $this->cusform2;//custom fields receipt	
	  }	    
	   
       if (!$recfields) {
         if ($this->usemailasusername) {
		 
	       if ($usermail = GetParam('uname')) {//= mail//????????????????????????
     	     if ($invtype==1)
		       $recfields = array('name','afm','eforia','prfdescr','address','area','zip','voice1','voice2','fax');
			 else
			   $recfields = array('name','address','area','zip','voice1','voice2','fax');  
		   } 	 
		   else {
    	     if ($invtype==1)
	           $recfields = array('name','afm','eforia','prfdescr','address','area','zip','voice1','voice2','fax','mail');
			 else
			   $recfields = array('name','address','area','zip','voice1','voice2','fax','mail');  
		   }	 
	     }
	     else {
    	   if ($invtype==1)		   
	         $recfields = array('code2','name','afm','eforia','prfdescr','address','area','zip','voice1','voice2','fax','mail');
		   else
		     $recfields = array('code2','name','address','area','zip','voice1','voice2','fax','mail');
		 }  
       }
	   
	   return ($recfields);	
	}	
	
	
	/////////////////////////////////////////////////DELIVERY ADDRESS
	
	function makedelivsubform($tokensout=null) {
	
	   if ($tokensout)
	     $tokens[] = localize('_DELIVADDRESS',getlocal());
	   else
	     $out = localize('_DELIVADDRESS',getlocal());
	  
       $aligntitle = "right;40%;";
	   $alignfield = "left;60%;";
	   		 
	   reset($this->delivery_fields);
        
       foreach ($this->delivery_fields as $fnum => $fname) {
		 $data[$fnum] = ToHTML(GetParam(_with($fname.'_d')));
	   }
	   
       //show data
       reset ($this->delivery_fields);
	   reset ($data);
       foreach ($this->delivery_fields as $field_num => $fieldname) {
	   
         if ($tokensout) {
	       //inputs .._d..to separate from default address name fields in the same form  
           $tokens[] = "<input type=\"text\" name=\"" . _with($fieldname.'_d') . "\" maxlength=\"" . $maxcharfields[$field_num] . "\" value=\"" .
                       $data[$field_num] . "\" size=\"" . "25" . "\" >";
		 }
		 else {	   
           $field[] = localize($this->delivery_fields[$field_num],getlocal()) . $this->asterisk;
	       $attr[] = $aligntitle;
           $f  = "<input type=\"text\" name=\"" . _with($fieldname.'_d') . "\" maxlength=\"" . $maxcharfields[$field_num] . "\" value=\"";
           $f .= $data[$field_num];
           $f .= "\" size=\"" . "25" . "\" >";
	       $field[] = $f;
	       $attr[] = $alignfield;
	       $w = new window('',$field,$attr);
		   $out .= $w->render("center::100%::0::group_article_selected::left::0::0::");
		   unset ($field);  unset ($attr); unset ($f);
		 }
	   }
	   
       if ($tokensout) {
		   //$ret = $this->combine_tokens($mytemplate,$tokens);
		   return ($tokens);		   
	   }
	   else {	   
	     $wout = "<br>";
	     $warning1 = new window('',localize('_TRANSDATA',getlocal()));
	     $wout .= $warning1->render(" ::100%::0::group_article_selected::center;100%;::");
	     unset($warning1);
	     $wout .= "<br>";
		 
		 $wout .= $out;
		 
		 return ($wout); 
		 
	   }		  
	}
	
	function check_delivery_address($bypass=null,$checkasterisk=null) {
	
	   if ($bypass) 
	     return null;	
	
	   if ($checkasterisk) {

	     foreach ($this->delivery_fields as $fn=>$fname) {	   
    		$title = localize($fname,getlocal());
	     	if (strstr($title,'*')) { //check by titile using *
			
              if(!strlen(GetParam(_with($fname.'_d')))) {
                $sFormErr .= localize('_MSG12',getlocal()) . " <font color=\"red\">" .
		                     $title . "</font> " .
		                     localize('_MSG11',getlocal()) . "<br>";		  			
			  }			
			}
		 }		 
	   }
	   else {  
	     //if any of fields is set...???
	     //_d ..to separate from default address name fields  
	     foreach ($this->delivery_fields as $fn=>$fname) {
	        //echo '>'.GetParam($fname.'_d');
		 
		    $title = localize($fname,getlocal());
		 
            if(!strlen(GetParam(_with($fname.'_d')))) {
              $sFormErr .= localize('_MSG12',getlocal()) . " <font color=\"red\">" .
		                   $title . "</font> " .
		                   localize('_MSG11',getlocal()) . "<br>";		  			
		    }	
	     }	
	   }
	   
	   SetGlobal('sFormErr',$sFormErr);

       return ($sFormErr);
	}
	
	function get_delivery_address() {
	
	  return ($this->mydelivery_address);	
	}	
	
	function showdeliveryaddress($name=null,$combo=null,$uid=null,$style=null) {
       $db = GetGlobal('db');	
	   $UserName = GetGlobal('UserName');
	   $myui=$uid?$uid:decode($UserName);
	   $addressway = GetReq('addressway');//predef..selected
	   $out = null; 
	   
       $out = setNavigator(localize('_SELDELIVADDRESS',getlocal()));		   
	      
	   $addressfields = implode(',',$this->delivery_fields);
       $sSQL = "select id,active,$addressfields from custaddress";
	   $sSQL.= " where ccode=". $db->qstr($myui);
	   if ($addressway)
	     $sSQL .= " and id=" . $addressway;
	   $sSQL .= " order by id DESC"; //last to first..selected last address inserted
	   	 
	   //echo $sSQL;	   
       $result = $db->Execute($sSQL,2);
	   //print_r($result);

       if ($combo) {//not used....
		   //$ret = localize('_DELIVADDRESS',getlocal());
		   $out .= "<select name=\"".$name."\" class=\"".$style."\">";		     
		   
		   foreach ($result as $n=>$na) {
            if (!empty($na[0])) {	 
				 		
		      $title = $na[0];
			  $value = $na[1];
		      $out .= "<option value=\"$value\"".($value == $addressway ? " selected" : "").">$title</option>";
			}  
		   }
		   
		   $out .= "</select>";		   
		 }
		 else {
		   $hr = '';//'<hr>';
		   
		   //$ret = localize('_DELIVADDRESS',getlocal()).';';
		   if (!$addressway) {//add default address to list as first option
		     $defaddr = localize('_DEFDELIVADDRESS',getlocal());	   		   
		     $ret = $defaddr . $hr . '<COMMA>';
		   }
		   
		   foreach ($result as $n=>$na) {	
		     if (!empty($na)) {
			   
			   $ret .= localize('_DELIVADDRESS',getlocal()) . ' #' . ($n+1);	
   			   $ret .= '<br>';//'<b>' . localize('_DELIVADDRESS',getlocal()) . ' ' . $n+1  . '</b><br>';//title
			 
			   foreach ($this->delivery_fields as $i=>$in) {
				 
				 $ret .= localize($in,getlocal()).':'.$na[$in].'<br>';
			   }	
			   
			   $ret .= $hr . '<COMMA>';
			 }  
		   }
		   
		   if ($addressway) {//add default address to list as last option
		     $defaddr =  localize('_DEFDELIVADDRESS',getlocal()) . $hr . '<COMMA>';	   		   
		     $ret .= $defaddr;
		   }	
		   
	       $out = substr($ret,0,-7);//7=<COMMA>		   	    	 
		 }  

	   
	   return ($out);		   
	}
	
	function addnewdeliverylink($dpc_after_goto=null) {
	   $mydpcgoto = $dpc_after_goto?$dpc_after_goto:$this->adddelivgoto;
	
       if ($mydpcgoto) {
       	  SetSessionParam('afterdelivgoto',str_replace('>','.',$mydpcgoto));
       }

	   $out = seturl('t=addnewdeliv',localize('_ADDDELIVADDRESS',getlocal()));	
	   return ($out);
	}
	
	function show_customer_delivery() {
       $db = GetGlobal('db');		   
	   $UserName = GetGlobal('UserName');
	   $myui=$uid?$uid:decode($UserName);
	   	   
	   
	   //template
	   $template= "showdeliverylist.htm";
	   $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$template) ;
	   if (is_readable($t)) {
	     $tokensout = 1;
		 $mytemplate = file_get_contents($t);
	   }		   
			  		   
	   $addressfields = implode(',',$this->delivery_fields);
       $sSQL = "select id,$addressfields from custaddress";
	   $sSQL.= " where ccode=". $db->qstr($myui);
	   $sSQL.= " order by id DESC"; //last to first..selected last address inserted	   
	   //echo $sSQL;	   
       $result = $db->Execute($sSQL,2);
	   
	   if ($UserName) {
	   
		   foreach ($result as $n=>$na) {	
		     if (!empty($na)) {	   
			 
			   $id = $na[0];
		       $deleteaddresslink = seturl('t=removedeliv&id='.$id,localize('_REMDELIVADDRESS',getlocal()));	
			   $selectaddresslink = seturl($this->delivery_goto_url . '&addressway='.$id,localize('_SELDELIVADDRESS',getlocal()));			 
			   			   

	           if ($mytemplate) {
			   
			     foreach ($this->delivery_fields as $i=>$in) 
			       $tokens[] = $na[$in];	
				   
				 $tokens[] = $selectaddresslink; 				   
				 $tokens[] = $deleteaddresslink; 		   
			   }
			   else {			   
			   
			     foreach ($this->delivery_fields as $i=>$in) {
			       $titles[] = localize($in,getlocal());
			       $data[] = $na[$in];
			     }				    			 
                 //$ret .= implode(" ",$data) . ' ' . $deleteaddresslink . '<br>';
			   				 

	             $data1[] = implode("<br>",$titles);
                 $attr1[] = "left;30%";
					  
	             $data1[] = implode("<br>",$data);
                 $attr1[] = "left;50%";
			   
	             $data1[] = $selectaddresslink.'<br><br>'.$deleteaddresslink;
                 $attr1[] = "left;20%";			   
			   }
			  
               /*$mydata = new window('',$data1,$attr1);
               if ($tokensout)		
				  $tokens[] = $mydata->render();	
			   else  		 
		         $ret .= $mydata->render(" ::100%::0::group_article_selected::center;100%;::");	
				*/ 
			   
	           if ($mytemplate) {
		         $out .= $this->combine_tokens($mytemplate,$tokens);	
				 
				 unset($tokens);		   
	           }
	           else {	   
                 $myret = new window(localize('_DELIVADDRESS',getlocal()),$data1,$attr1);
	             $out .= $myret->render(" ::100%::0::group_article_selected::center;100%;::");	   
			     $out .= "<hr>";	
			     unset($data1);
			     unset($attr1);		
			   
			     unset($data);
			     unset($titles);					 		 				 
	           }			      		   
			 }//if
		   }//foreach	    
	   }//if username
	   
       $out .= $this->adddeliveryform();	   
	   
	   return ($out);
	}
	
	
	function adddeliveryform($retokensout=null) {
	   $sFormErr = GetGlobal('sFormErr');		
	
	   //template
	   $template= "showdeliveryform.htm";
	   $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$template) ;
	   if (is_readable($t)) {
	     $tokensout = 1;
		 $mytemplate = file_get_contents($t);
	   }	
	   
       if ($tokensout) {	
	     $o = $sFormErr; 
         $o .= "<form method=\"POST\" action=\"";
         $o .= seturl('t=savenewdeliv');
         $o .= "\" name=\"savenewdeliveryaddress\">";	  	   	
		 $tokens[] = $o;		 
	   }
	   else {
	     $out = $sFormErr;
         $out .= "<form method=\"POST\" action=\"";
         $out .= seturl('t=savenewdeliv');
         $out .= "\" name=\"savenewdeliveryaddress\">";		   
	   }	 
	
       foreach ($this->delivery_fields as $field_num => $fieldname) {
	   
         if ($tokensout) {
	       //inputs .._d..to separate from default address name fields in the same form  
           $tokens[] = "<input type=\"text\" name=\"" . _with($fieldname).'_d' . "\" maxlength=\"" . $maxcharfields[$field_num] . "\" value=\"" .
                       GetParam($fieldname.'_d') . "\" size=\"" . "25" . "\" >";
		 }
		 else {	   
           $field[] = localize($this->delivery_fields[$field_num],getlocal()) . "*";
	       $attr[] = $aligntitle;
           $f  = "<input type=\"text\" name=\"" . _with($fieldname).'_d' . "\" maxlength=\"" . $maxcharfields[$field_num] . "\" value=\"";
           $f .= GetParam($fieldname.'_d');
           $f .= "\" size=\"" . "25" . "\" >";
	       $field[] = $f;
	       $attr[] = $alignfield;
	       $w = new window('',$field,$attr);
		   $out .= $w->render("center::100%::0::group_article_selected::left::0::0::");
		   unset ($field);  unset ($attr); unset ($f);
		 }
	   }
	   
       if ($tokensout) {	   
         $o = "<input type=\"submit\" value=\"" . 'OK' . "\">";	   
         $o .= "<input type=\"hidden\" name=\"FormName\" value=\"savenewdeliv\">";
         $o .= "</form>";	   
		 $tokens[] = $o;
	   }
	   else {
         $out .= "<input type=\"submit\" value=\"" . 'OK' . "\">";	   
         $out .= "<input type=\"hidden\" name=\"FormName\" value=\"savenewdeliv\">";
         $out .= "</form>";	 	   
	   }
	   
       if ($retokensout) {
		   //$ret = $this->combine_tokens($mytemplate,$tokens);
		   return ($tokens);		   
	   }
	   else {	 
	     if ($mytemplate) {
		   $wout = $this->combine_tokens($mytemplate,$tokens);			   
		 }
		 else {  
	       $dd = new window(localize('_ADDDELIVADDRESS',getlocal()),$out);
	       $wout .= $dd->render(" ::100%::0::group_article_selected::center;100%;::");
	       unset($dd);
		 }
		 
		 return ($wout); 
		 
	   }	
	}
	
	function savedeliveryaddress() {
       $db = GetGlobal('db');	
	   $UserName = GetGlobal('UserName');
	   $myui = decode($UserName);
	   
	   //delivery address
	   if (!$error = $this->check_delivery_address(null,$this->checkuseasterisk)) {
	   
	       //before set active the current record deactive all others  
		   $d = $this->deactivatedeliveryaddress();
	   
		   //save at customer address book
		   foreach ($this->delivery_fields as $fn=>$fname)
		     $delivdata[] = $db->qstr(GetParam($fname.'_d')); //to separate from default address name fields  
			  		   
		   $addressfields = implode(',',$this->delivery_fields);
           $sSQL2 = "insert into custaddress (ccode,active,$addressfields)";
	       $sSQL2.= " values (". $db->qstr($myui). ",1,";
           $sSQL2.= implode(',',$delivdata);
	       $sSQL2.= ")";

           //echo $sSQL2;
           $result2 = $db->Execute($sSQL2,1);	
           if ($ret = $db->Affected_Rows()) 
		     return true;			   	   	   
	    }
		else {
		   echo $db->ErrorMsg();
		   SetGlobal('sFormErr',$error);
		}
		
		return false;	   	
	}
	
	function removedeliveryaddress() {
	   $id = GetReq('id');
       $db = GetGlobal('db');	
	   $UserName = GetGlobal('UserName');
	   $myui=decode($UserName);	
	   
	   if ($id) {
         $sSQL = "delete from custaddress";
	     $sSQL.= " where ccode=". $db->qstr($myui) . ' and id=' . $id;
	     //echo $sSQL;	   
         $result = $db->Execute($sSQL,1);	 
	   }
	     
       if ($ret = $db->Affected_Rows()) 
	     return true;		     
	   else
	     return false;	 
	}
	
	//id = logon name
	function deactivatedeliveryaddress($id=null) {
       $db = GetGlobal('db');	
	   $UserName = GetGlobal('UserName');
	   $myui = $id?$id:decode($UserName);	   
	   
	   if ($id) {
         $sSQL = "update custaddress set active=0";
	     $sSQL.= " where ccode=". $db->qstr($myui);
	     //echo $sSQL;	   
         $result = $db->Execute($sSQL,1);	 
	   }
	     
       if ($ret = $db->Affected_Rows()) 
	     return true;		     
	   else
	     return false;		
	}
	
	
	
	/////////////////////////////////////////////////// CUSTOMER DETAILS
	
	function makecustomerform($tokensout=null) {
	
	   if ($tokensout)
	     $tokens[] = localize('_CUSTOMER',getlocal());
	   else
	     $out = localize('_CUSTOMER',getlocal());
	  
       $aligntitle = "right;40%;";
	   $alignfield = "left;60%;";
	   		 
	   $recfields = $this->get_cus_record(1);	
        
       foreach ($recfields as $fnum => $fname) {
		 $data[$fnum] = ToHTML(GetParam(_with($fname)));
	   }
	   
       //show data
       reset ($recfields);
	   reset ($data);
       foreach ($recfields as $field_num => $fieldname) {
	   
         if ($tokensout) {
	       //inputs .._d..to separate from default address name fields in the same form  
           $tokens[] = "<input type=\"text\" name=\"" . _with($fieldname) . "\" maxlength=\"" . $maxcharfields[$field_num] . "\" value=\"" .
                       $data[$field_num] . "\" size=\"" . "25" . "\" >";
		 }
		 else {	   
           $field[] = localize($recfields[$field_num],getlocal()) . $this->asterisk;
	       $attr[] = $aligntitle;
           $f  = "<input type=\"text\" name=\"" . _with($fieldname) . "\" maxlength=\"" . $maxcharfields[$field_num] . "\" value=\"";
           $f .= $data[$field_num];
           $f .= "\" size=\"" . "25" . "\" >";
	       $field[] = $f;
	       $attr[] = $alignfield;
	       $w = new window('',$field,$attr);
		   $out .= $w->render("center::100%::0::group_article_selected::left::0::0::");
		   unset ($field);  unset ($attr); unset ($f);
		 }
	   }
	   
       if ($tokensout) {
		   //$ret = $this->combine_tokens($mytemplate,$tokens);
		   return ($tokens);		   
	   }
	   else {	   
	     $wout = "<br>";
	     $warning1 = new window('',localize('_TRANSDATA',getlocal()));
	     $wout .= $warning1->render(" ::100%::0::group_article_selected::center;100%;::");
	     unset($warning1);
	     $wout .= "<br>";
		 
		 $wout .= $out;
		 
		 return ($wout); 
		 
	   }		  
	}	

	
	function showcustomers($name=null,$combo=null,$uid=null,$style=null) {
       $db = GetGlobal('db');	
	   $UserName = GetGlobal('UserName');
	   $myui=$uid?$uid:decode($UserName);
	   $customerway = GetReq('customerway');//predef..selected 
	   $out = null;
	   
	   $recfields = $this->get_cus_record(1);	   
			  		   
	   $record = implode(',',$recfields);
       $sSQL = "select id,name,$record,active from customers";
	   $sSQL.= " where code2=". $db->qstr($myui);
	   if ($this->fkey)
	     $sSQL .= " or " . $this->fkey . "=" . $db->qstr($myui);//<<<<compatibility
	   if ($customerway)
	     $sSQL .= " and id=" . $customerway;
	   $sSQL .= " order by active DESC"; //id = last to first..selected last address inserted, active first
	   	 
	   //echo $sSQL;	   
       $result = $db->Execute($sSQL,2);
	   //print_r($result);
	   $m = 0;    
       if ($db->Affected_Rows()) {
	     //echo '>',$combo;
         if ($combo) {
	       //$ret = localize('_CUSTOMER',getlocal());
		   $out .= "<select name=\"".$name."\" class=\"".$style."\">";		      
		   
		   foreach ($result as $n=>$na) {
            if (!empty($na[1])) {	 
				 		
		      $title = $na[1];
			  $value = $na[0];
		      $out .= "<option value=\"$value\"".($value == $customerway ? " selected" : "").">$title</option>";
			  $m+=1;
			}  
		   }
		   
		   $out .= "</select>";		
		   
		   //>>>ONLY IF CUSTOMERS IS MORE THAN ONE!!!
	       //if (($m>1) || ($customerway))
	         return ($out);				
		   //else	
		     //return ($result->fields['name']);       
	     }
	     else {
		   
		   foreach ($result as $n=>$na) {	
		     if (!empty($na)) {
			   
			   $ret .= localize('_CUSTOMER',getlocal()) . ' ' . $na[1];//($n+1);	
   			   $ret .= '<br>';
			 
			   foreach ($recfields as $i=>$in) {
				 
				 $ret .= localize($in,getlocal()).':'.$na[$in].'<br>';
			   }	
			   
			   $ret .= '<hr><COMMA>'; 
			   $m+=1;			       
			 }  	   
		   }
		   
		   
		   $out = substr($ret,0,-1);	
           //echo $m; 
	       if ($m>1)
	        return ($out);				   	    	 
	     }
       }//result 
	}	
	
	function addnewcustomerlink($dpc_after_goto=null) {
	   $mydpcgoto = $dpc_after_goto?$dpc_after_goto:$this->addcusgoto;
	
       if ($mydpcgoto) {
       	  SetSessionParam('aftercusgoto',str_replace('>','.',$mydpcgoto));
       }
        //echo $dpc_after_goto;

	   $out = seturl('t=addnewcus',localize('_ADDCUSTOMER',getlocal()));	
	   
	   return ($out);
	}	
	
	function show_customers_list() {
       $db = GetGlobal('db');		   
	   $UserName = GetGlobal('UserName');
	   $myui=$uid?$uid:decode($UserName);
	   $action = GetReq('t');
	   
	   //template
	   $template= "showcustomerlist.htm";
	   $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$template) ;
	   if (is_readable($t)) {
	     $tokensout = 1;
		 $mytemplate = file_get_contents($t);
		 //echo '>',$template;
	   }	
	   
	   //template 2
	   $template2 = "cusregister.htm";
	   $t2 = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$template2) ;
	   if (is_readable($t2)) {
		 $mytemplate2 = file_get_contents($t2);
	   }		   		   	   	
	   
	   $recfields = $this->get_cus_record(1,1);	      
			  		   
	   $record = implode(',',$recfields);
       $sSQL = "select id,$record,active from customers";
	   $sSQL.= " where code2=". $db->qstr($myui);
	   
	   ///// FETCH also mail=userid !!!!!!!!
	   if ($this->fkey)
	     $sSQL .= " or " . $this->fkey . "=" . $db->qstr($myui);//<<<<compatibility	  
		  
	   $sSQL.= " order by active DESC"; //last to first..selected last address inserted	   
	   //echo $sSQL;	   
       $result = $db->Execute($sSQL,2);
	   
	   if ($UserName) {
	   
		   foreach ($result as $n=>$na) {	
		   
		     if (!empty($na)) {	   
			 
			   $myactions = array();
			 
			   $id = $na[0];
			   
			   if ($action == 'addnewcus')//in cart change	
			     $myactions[] = null; 
			   else//just modify account
                 $myactions[] = seturl('t=signup2&a='.$id,localize('_UPDCUSTOMER',getlocal()));				   
			   
			   if ($action == 'addnewcus')//in cart change	
				 $myactions[] = seturl($this->customer_goto_url . '&customerway='.$id,localize('_SELCUSTOMER',getlocal()));			 
			   else
			     $myactions[] = seturl('t=selcus&id='.$id,localize('_SELCUSTOMER',getlocal()));			 			   
				 
			   if ($action == 'addnewcus')//in cart change
			     $myactions[] = null;//seturl('t=removecus&id='.$id.'&select='.$selectonly,localize('_REMCUSTOMER',getlocal()));	
			   else //just modify account
		         $myactions[] = seturl('t=removecus&id='.$id,localize('_REMCUSTOMER',getlocal()));	
							 
			   
               if ($mytemplate2) 			   
			     $data[] = implode('&nbsp;|&nbsp;',$myactions);//$selectaddresslink.'&nbsp;|&nbsp;'.$updateaddresslink.'&nbsp;|&nbsp;'.$deleteaddresslink;

			   foreach ($recfields as $i=>$in) {
			     $titles[] = localize($in,getlocal());
			     $data[] = $na[$in];				 
			   }	 			 
			   				 
               if ($mytemplate2) {
			     if ($tokensout) {
			       //$data[] = $selectaddresslink.'&nbsp;'.$deleteaddresslink;
		           $myt = $this->combine_tokens($mytemplate2,$data);					 
				   $tokens[] = $myt;
				 }
				 else {
			       //$data[] = $selectaddresslink.'&nbsp;'.$deleteaddresslink;
		           $ret .= $this->combine_tokens($mytemplate2,$data);					 
				 }  
				 unset($data);
			   }
			   else {		
	             $data1[] = implode("<br>",$titles);
                 $attr1[] = "left;30%";
					  
	             $data1[] = implode("<br>",$data);
                 $attr1[] = "left;50%";
			   
	             $data1[] = implode('<br><br>',$myactions);//$selectaddresslink.'<br><br>'.$updateaddresslink .'<br><br>'.$deleteaddresslink;
                 $attr1[] = "left;20%";			   
			   
			  
                 $mydata = new window('',$data1,$attr1);
                 if ($tokensout)		
				  $tokens[] = $mydata->render();	
			     else  		 
		         $ret .= $mydata->render(" ::100%::0::group_article_selected::center;100%;::");	
			     unset($data1);
			     unset($attr1);
			   
			     unset($data);
			     unset($titles);
			     $ret .= "<hr>";	
			   }   
			 }
		   }	    
	   }	
	      
	   
	   if ($mytemplate) {
		 $out .= $this->combine_tokens($mytemplate,$tokens);			   
	   }
	   elseif ($mytemplate2) {
	     $out .= $ret;
	   }
	   else {	   
         $myret = new window(localize('_CUSTOMERSLIST',getlocal()),$ret);
	     $out .= $myret->render(" ::100%::0::group_article_selected::center;100%;::");	   
	   }
	   
       $out .= $this->addcustomerform();	 
		 
	   return ($out);
	}	
	
	function addcustomerform($retokensout=null) {
	   $sFormErr = GetGlobal('sFormErr');		
	
	   //template
	   $template= "cusregister.htm";
	   $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$template) ;	   
       
	   if (is_readable($t)) {
	     $tokensout = 1;
		 $mytemplate = file_get_contents($t);
	   }	
	   
       if ($tokensout) {	
	     if ($sFormErr!="ok")    
	       $o = $sFormErr; 
         $o .= "<form method=\"POST\" action=\"";
         $o .= seturl('t=savenewcus');
         $o .= "\" name=\"savenewcustomer\">";	  	   	
		 $tokens[] = $o;		 
	   }
	   else {
	     if ($sFormErr!="ok")    
	       $out = $sFormErr;
         $out .= "<form method=\"POST\" action=\"";
         $out .= seturl('t=savenewcus');
         $out .= "\" name=\"savenewcustomer\">";		   
	   }	
	   
	   $recfields = $this->get_cus_record(1,1);		    
	
       foreach ($recfields as $field_num => $fieldname) {
	   
         if ($tokensout) {
	       //inputs .._d..to separate from default address name fields in the same form  
           $tokens[] = "<input type=\"text\" name=\"" . _with($fieldname) . "\" maxlength=\"" . $maxcharfields[$field_num] . "\" value=\"" .
                       GetParam($fieldname) . "\" size=\"" . "25" . "\" >";
		 }
		 else {	   
           $field[] = localize($recfields[$field_num],getlocal()) . $this->asterisk;
	       $attr[] = $aligntitle;
           $f  = "<input type=\"text\" name=\"" . _with($fieldname) . "\" maxlength=\"" . $maxcharfields[$field_num] . "\" value=\"";
           $f .= GetParam($fieldname);
           $f .= "\" size=\"" . "25" . "\" >";
	       $field[] = $f;
	       $attr[] = $alignfield;
	       $w = new window('',$field,$attr);
		   $out .= $w->render("center::100%::0::group_article_selected::left::0::0::");
		   unset ($field);  unset ($attr); unset ($f);
		 }
	   }
	   
       if ($tokensout) {	   
         $o = "<input type=\"submit\" value=\"" . localize('_SIGNUP',getlocal()) . "\">";	   
         $o .= "<input type=\"hidden\" name=\"FormName\" value=\"savenewcus\">";
         $o .= "</form>";	   
		 $tokens[] = $o;
	   }
	   else {
         $out .= "<input type=\"submit\" value=\"" . localize('_SIGNUP',getlocal()) . "\">";	   
         $out .= "<input type=\"hidden\" name=\"FormName\" value=\"savenewcus\">";
         $out .= "</form>";	 	   
	   }
	   
       if ($retokensout) {
		   //$ret = $this->combine_tokens($mytemplate,$tokens);
		   return ($tokens);		   
	   }
	   else {	 
	     if ($mytemplate) {
		   $wout = $this->combine_tokens($mytemplate,$tokens);			   
		 }
		 else {  
	       $dd = new window(localize('_ADDCUSTOMER',getlocal()),$out);
	       $wout .= $dd->render(" ::100%::0::group_article_selected::center;100%;::");
	       unset($dd);
		 }
		 
		 return ($wout); 
		 
	   }	
	}	
	
	
	function save_customer($userid=null) {
       $db = GetGlobal('db');	
	   $UserName = GetGlobal('UserName');
	   $myui = $userid?$userid:decode($UserName);
	   
	   if (!$error = $this->checkFields(null,$this->checkuseasterisk)) {
	   
		   //before set active the current record deactive all others  
		   $d = $this->deactivatecustomers();	   
	   
   	       $recfields = $this->get_cus_record(1,1);	

		   foreach ($recfields as $fn=>$fname) {
		     //echo $fname,':',$db->qstr(GetParam($fname)),'<br>';
		     $cusdata[] = $db->qstr(GetParam($fname)); 
		   }
		   //print_r($cusdata);	 
			  		   
		   $cusfields = implode(',',$recfields);
           $sSQL2 = "insert into customers (code2,active,$cusfields)";
	       $sSQL2.= " values (". $db->qstr($myui). ",1,";
           $sSQL2.= implode(',',$cusdata);
	       $sSQL2.= ")";

           //echo $sSQL2;
           $result2 = $db->Execute($sSQL2,1);	
           if ($ret = $db->Affected_Rows()) 
		     return true;			   	   	   
	    }
		else {
		   echo $db->ErrorMsg();
		   SetGlobal('sFormErr',$error);
		}
		
		return false;	   	
	}
	
	function remove_customer() {
	   $id = GetReq('id');
       $db = GetGlobal('db');	
	   $UserName = GetGlobal('UserName');
	   $myui=decode($UserName);	
	   
	   if ($id) {
	     //check if last record
         $sSQL = "select count(id) from customers";
	     $sSQL.= " where code2=". $db->qstr($myui);
         $res = $db->Execute($sSQL,2);	   	 	   
	     $counter = intval($res->fields[0]);
		 
	     if ($counter>1) {
           //$sSQL = "delete from customers"; //UNMAP
		   $sSQL = "update customers set code2='', mail=''";
	       $sSQL.= " where code2=". $db->qstr($myui) . ' and id=' . $id;
	       //echo $sSQL;	   
           $result = $db->Execute($sSQL,1);	 
		   
           if ($ret = $db->Affected_Rows()) { 
	   
	        $this->deactivatecustomers(); //deactivate all
		    $this->activatecustomer();//activate last before this deleted	   
			
			return true;
		   }
		 }
		 else {
		   $error = 'Last entry can not deleted!';
		   SetGlobal('sFormErr',$error);  
		 }  
	   }
	   
       return false;	 
	}	
	
	function deactivatecustomers($id=null) {
       $db = GetGlobal('db');	
	   $UserName = GetGlobal('UserName');
	   $myui = $id?$id:decode($UserName);	
	   
	   if ($myui) {
         $sSQL = "update customers set active=0";
	     $sSQL.= " where code2=". $db->qstr($myui);
	     //echo $sSQL;	   
         $result = $db->Execute($sSQL,1);	 
	   }
	     
       if ($ret = $db->Affected_Rows()) 
	     return true;		     
	   else
	     return false;		
	}	
	
	//id = id record of table
	function activatecustomer($id=null) {
       $db = GetGlobal('db');	
	   $UserName = GetGlobal('UserName');	   
	   $myui = decode($UserName);		   
	   
	   if (!$id) {
	     //find last entry to activate
         $sSQL = "select id from customers";
	     $sSQL.= " where code2=". $db->qstr($myui);
	     $sSQL .= " order by id "; //last to first..selected last address inserted
	   	 
	     //echo $sSQL;	   
         $result = $db->Execute($sSQL,2);	
		 foreach ($result as $n=>$na) {	  
	       $id = $na[0];
	       //echo '>',$id;
		 }
	   } 
	   
	   if ($id) {
         $sSQL = "update customers set active=1";
	     $sSQL.= " where id=". $id;
	     //echo $sSQL;	   
         $result = $db->Execute($sSQL,1);	 
	   }
	     
       if ($ret = $db->Affected_Rows()) 
	     return true;		     
	   else
	     return false;		
	}	
	
	function show_customer_title() {
       $db = GetGlobal('db');	
	   $UserName = GetGlobal('UserName');	   
	   $myui = decode($UserName);
	   
	   if ($myui) {
	     //find last entry to activate
         $sSQL = "select name from customers";
	     $sSQL.= " where code2=". $db->qstr($myui);
	     $sSQL .= " order by id "; //last to first..selected last address inserted
	   	 
	     //echo $sSQL;	   
         $result = $db->Execute($sSQL,2);	
		 foreach ($result as $n=>$na) {	  
	       $ret = $na[0];
	       //echo '>',$id;
		 }
		 
		 return ($ret);
	   }
	   
	   return null;	   		
	}
	
	
	function mailto($from,$to,$subject=null,$body=null,$ishtml=false,$instant=false) {

	    if ((defined('RCSSYSTEM_DPC')) && (!$instant)) { //no queue when no instant
		  //echo 'z',$to,'>',$from,'<br>';
		  $ret = GetGlobal('controller')->calldpc_method("rcssystem.sendit use $from+$to+$subject+$body++$ishtml");
        }
		else {
		     if ((defined('SMTPMAIL_DPC')) &&
				 (seclevel('SMTPMAIL_DPC',$this->UserLevelID)) ) {
		       $smtpm = new smtpmail;
		       /*$smtpm->to = $to;
			   $smtpm->from = $from;
			   $smtpm->subject = $subject;
			   $smtpm->body = $body ;*/
			   
		       $smtpm->to($to); 
		       $smtpm->from($from); 
		       $smtpm->subject($subject);
		       $smtpm->body($body);			   

			   $mailerror = $smtpm->smtpsend();

			   unset($smtpm);
			 }
		}	 
	}	
	
	function combine_tokens($template_contents,$tokens) {
	
	    if (!is_array($tokens)) return;
		
		if (defined('FRONTHTMLPAGE_DPC')) {
		  $fp = new fronthtmlpage(null);
		  $ret = $fp->process_commands($template_contents);
		  unset ($fp);
          //$ret = GetGlobal('controller')->calldpc_method("fronthtmlpage.process_commands use ".$template_contents);		  		
		}		  		
		else
		  $ret = $template_contents;
		  
		//echo $ret;
	    foreach ($tokens as $i=>$tok) {
            //echo $tok,'<br>';
		    $ret = str_replace("$".$i."$",$tok,$ret);
	    }
		//clean unused token marks
		for ($x=$i;$x<20;$x++)
		  $ret = str_replace("$".$x."$",'',$ret);
		//echo $ret;
		return ($ret);
	}	

};
}
?>