<?php
if (defined("BACKOFFICE_DPC")) {

$__DPCSEC['CUSTOMERS_DPC']='2;2;2;2;2;2;2;2;9';
$__DPCSEC['CUSTOMERSMNG_']='2;1;1;2;2;2;2;2;9';
$__DPCSEC['DELETECUSTOMER_']='2;1;1;1;1;1;1;2;9';
$__DPCSEC['UPDATECUSTOMER_']='2;1;1;2;2;2;2;2;9';
$__DPCSEC['INSERTCUSTOMER_']='2;1;1;1;1;1;2;2;9';
$__DPCSEC['SEARCHCUSTOMER_']='2;1;1;1;1;1;2;2;9';

if ((!defined("CUSTOMERS_DPC")) && (seclevel('CUSTOMERS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("CUSTOMERS_DPC",true);

$__DPC['CUSTOMERS_DPC'] = 'customers';

$__EVENTS['CUSTOMERS_DPC'][0]='insert2';
$__EVENTS['CUSTOMERS_DPC'][1]='update2';
$__EVENTS['CUSTOMERS_DPC'][2]='delete2';
$__EVENTS['CUSTOMERS_DPC'][3]='custbulksubscribe';
$__EVENTS['CUSTOMERS_DPC'][4]='searchcustomer';

$__ACTIONS['CUSTOMERS_DPC'][0]='signup2';
$__ACTIONS['CUSTOMERS_DPC'][1]='insert2';
$__ACTIONS['CUSTOMERS_DPC'][2]='update2';
$__ACTIONS['CUSTOMERS_DPC'][3]='delete2';
$__ACTIONS['CUSTOMERS_DPC'][4]='searchcustomer';

$__LOCALE['CUSTOMERS_DPC'][0]='_TITLE;Title;Επωνυμία';
$__LOCALE['CUSTOMERS_DPC'][1]='_AFM;VAT No;Α.Φ.Μ.;';
$__LOCALE['CUSTOMERS_DPC'][2]='_CONTACTNAME;Contact Person;Υπεύθυνος Συναλλαγών';
$__LOCALE['CUSTOMERS_DPC'][3]='_ADDR1;Address 1;Διεύθυνση Λογιστηρίου';
$__LOCALE['CUSTOMERS_DPC'][4]='_POBOX1;Post code 1;Τ.Κ. Λογιστηρίου';
$__LOCALE['CUSTOMERS_DPC'][5]='_ADDR2;Address 2;Διεύθυνση Παράδοσης';
$__LOCALE['CUSTOMERS_DPC'][6]='_POBOX2;Post code 2;Τ.Κ. Παράδοσης';
$__LOCALE['CUSTOMERS_DPC'][7]='_TEL;Voice;Αριθμός Τηλεφώνου';
$__LOCALE['CUSTOMERS_DPC'][8]='_FAX;Fax;Αριθμός Τηλεομοιότυπου';
$__LOCALE['CUSTOMERS_DPC'][9]='_SIGNUP;SignUp;Εγγραφή';
$__LOCALE['CUSTOMERS_DPC'][10]='_UPDATE;Update;Ανημέρωση';
$__LOCALE['CUSTOMERS_DPC'][11]='_DELETE;Delete;Διαγραφή';
$__LOCALE['CUSTOMERS_DPC'][12]='_MSG10;Successfull registration!;Επιτυχής καταχώρηση!';
$__LOCALE['CUSTOMERS_DPC'][13]='_MSG11;is required;είναι απαραίτητο';
$__LOCALE['CUSTOMERS_DPC'][14]='_MSG12;The value in field;Η τιμή στο πεδίο';
$__LOCALE['CUSTOMERS_DPC'][15]='_ACCDENIED;Access denied;Απαγορυμένη πρόσβαση';
$__LOCALE['CUSTOMERS_DPC'][16]='_EFORIA;Account Department;ΔΟΥ';
$__LOCALE['CUSTOMERS_DPC'][17]='_SEARCHCUST;Searchin;Ευρεση';
$__LOCALE['CUSTOMERS_DPC'][18]='_SEARCHRES;Results;Αποτελέσματα Αναζήτησης';
$__LOCALE['CUSTOMERS_DPC'][19]='_CUSTLIST;Customers;Πελάτες';

require_once("backoffice.dpc.php");

class customers extends backoffice {

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
	
	var $db_con;
	
	var $fkey; //=foreign key to second db
	var $mailkey; //mail key

	function customers() {
	   $UserSecID = GetGlobal('UserSecID');
	   $sFormErr = GetGlobal('sFormErr');
	   $UserName = GetGlobal('UserName');
	   $UserID = GetGlobal('UserID');	   	   	   	   
	   
	   backoffice::backoffice();	   

	   $this->userLevelID = decode($UserSecID);
	   $this->username = decode($UserName);
	   $this->userid = decode($UserID);
	   $this->msg = $sFormErr;
	   	   
       //select fields from table to get
	   $this->selectedfields = arrayload('CUSTOMERS','select'); //print_r($this->selectedfields);
	                      //array('ΕΠΩΝΥΜΊΑ','Α_Φ_Μ','ΟΙΚ_ΕΦΟΡΊΑ','ΥΠΕΎΘΥΝΟΣ','Δ/ΝΣΗ 1 ΛΟΓ','ΤΑΧ_ΚΏΔ_ΛΟΓ','Δ/ΝΣΗ 1 ΠΑΡ’Δ','ΤΑΧ_ΚΏΔ_ΠΑΡ’Δ','ΤΗΛΈΦΩΝΟ 1','FAX','ΕΝΑΛΛ_ΕΠΩΝΥΜΊΑ','ΕΝΑΛΛ_ΚΩΔΙΚΌΣ'); 
	   $this->fieldalias = arrayload('CUSTOMERS','selectalias'); //print_r($this->fieldalias);
	                      //array('_TITLE','_AFM','_EFORIA','_CONTACTNAME','_ADDR1','_POBOX1','_ADDR2','_POBOX2','_TEL','_FAX','_EMAIL');
	   //print_r($this->selectedfields);	   
	   
	   $this->fkey = paramload('CUSTOMERS','fkey');
	   $this->mkey = paramload('CUSTOMERS','mailkey');	   
	}
	
	
	function getFields() {	
		 	
         $this->get_MetaTable();
		 
	     $this->fields = $this->get_MetaColumns($this->T_customers); //print_r($this->fields);

         //get table data
         //while (list ($field_num, $fieldname) = each ($this->selectedfields)) {
         foreach ($this->selectedfields as $field_num => $fieldname) {
		   if ($this->fieldalias[$field_num]) {	   
	         $this->recfields[] = $this->fields[$fieldname]->name; 
	         $this->maxcharfields[] = $this->fields[$fieldname]->max_length;
		   }
	     }
	     //print_r($this->selectedfields);	 
		 //print_r($this->fieldsalias);
	}
	
    function event($sAction) {

       if (!$this->msg) {
	   
	     $this->getFields();

         switch($sAction)   {

            case "insert2":  
				           if (!$this->checkFields()) {
				              $this->insert();
			               }
				           break;
            case "update2":  
				           if (!$this->checkFields()) {
				              $this->update();
			               }
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
          }
       }
	}	

	function action($action) {
	   
       switch ($action) {		
          case "searchcustomer"   : $out = $this->searchresults(); break;					 	  
		  default                 : $out = $this->register();
       }	   
       return ($out);
	}

    function register() {
	   //global $sFormErr;
	   $sFormErr = GetGlobal('sFormErr');	   

       if ($sFormErr=="ok") {

           $out .= setError(localize('_MSG10',getlocal()));
	   }
	   else {
           $this->getFields();
		   
   	       if (seclevel('CUSTOMERSMNG_',$this->userLevelID)) {

   	          if (seclevel('UPDATECUSTOMER_',$this->userLevelID)) {
                 $record = $this->getcustomer();
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


    function showcustomerdata($customer="") {
       //global $sFormErr;
	   $sFormErr = GetGlobal('sFormErr');	   
	   $data = array();

       $this->getFields();
	   	   
       //read data
	   $fields = $this->getcustomer($customer); 

       reset($this->recfields);

       //in case of no customer data this must return null
	   if (strlen($fields)>9) { //if empty returns ';;;;;;;;;'

		   $myfields = explode(";",$fields);

           //while (list ($field_num, $fieldname) = each ($this->recfields)) {
           foreach ($this->recfields as $field_num => $fieldname) {
		       if ($myfields[$field_num]) $data[$field_num] = $myfields[$field_num];
			                         else $data[$field_num] = "&nbsp;"; 
		   }

           $aligntitle = "right;40%;";
	       $alignfield = "left;60%;";

           $out .= setTitle(localize('_TRANSDATA',getlocal()));

           //show data
           reset ($this->recfields);
	       reset ($data);
           //while (list ($field_num, $fieldname) = each ($this->recfields)) {
           foreach ($this->recfields as $field_num => $fieldname) {

             $field[] = localize($this->fieldalias[$field_num],getlocal()) . ":";
	         $attr[] = $aligntitle;
	         $field[] = $data[$field_num];
	         $attr[] = $alignfield;
	         $w = new window('',$field,$attr);  
		     $out .= $w->render("center::100%::0::group_article_selected::left::0::0::");   
		     unset ($field);  unset ($attr);
	       }

           if (seclevel('CUSTOMERSMNG_',$this->userLevelID)) { 
             $uid = $this->userid;
             $d = seturl("t=signup2&a=$uid&g=" , localize('_UPDATE',getlocal()) ); 
	         $w = new window('',$d);
	         $out .= $w->render(" ::100%::0::group_form_foottitle::center;100%;::");
	         unset($w);
		   }
       }

	   return ($out);
	}

    function makeform($fields='') {
       //global $sFormErr;
	   $sFormErr = GetGlobal('sFormErr');	   
	   
       //navigation status
  	   $winout = setNavigator(localize('_TRANSDATA',getlocal())); 
       //error message
	   $winout .= setError($sFormErr);	   
	   
	   $data = array();	   
       //read data
	   reset($this->recfields);
	   if ($fields) { //get record param
		   $myfields = explode(";",$fields); 

           //while (list ($field_num, $fieldname) = each ($this->recfields)) {
           foreach ($this->recfields as $field_num => $fieldname) {		   
		       $data[$field_num] = $myfields[$field_num];
		   }
	   }
	   else { //read form data
           //while (list ($field_num, $fieldname) = each ($this->recfields)) {
           foreach ($this->recfields as $field_num => $fieldname) {		   
		       $data[$field_num] = ToHTML(GetParam(_with($fieldname)));
		   }
	   }

       $aligntitle = "right;40%;";
	   $alignfield = "left;60%;";

       $sFileName = seturl("t=signup2&a=&g=",0,1);

	   $warning = new window('',localize('_TRANSINFO',getlocal()));
	   $out .= $warning->render(" ::100%::0::group_article_selected::center;100%;::");
	   unset($warning);

       $out .= "<form method=\"POST\" action=\"";
       $out .= "$sFileName";
       $out .= "\" name=\"Registration2\">";

       //show data
       reset ($this->recfields);
	   reset ($data);
       //while (list ($field_num, $fieldname) = each ($this->recfields)) {
       foreach ($this->recfields as $field_num => $fieldname) {	   

         $field[] = localize($this->fieldalias[$field_num],getlocal()) . "*";
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

       if (seclevel('CUSTOMERSMNG_',$this->userLevelID)) { 
 
           $out .= "<input type=\"hidden\" value=\"update2\" name=\"FormAction\"/>";

           if (seclevel('UPDATECUSTOMER_',$this->userLevelID)) {
              $out .= "<input type=\"submit\" value=\"" . localize('_UPDATE',getlocal()) . "\" onclick=\"document.forms('Registration2').FormAction.value = 'update2';\">";   
		   }

           if (seclevel('DELETECUSTOMER_',$this->userLevelID)) {
              $out .= "<input type=\"submit\" value=\"" . localize('_DELETE',getlocal()) . "\" onclick=\"document.forms('Registration2').FormAction.value = 'delete2';\">";
		   }
       }
       else {
           $out .= "<input type=\"submit\" value=\"" . localize('_SIGNUP',getlocal()) . "\" onclick=\"document.forms('Registration2').FormAction.value = 'insert2';\">";
           $out .= "<input type=\"hidden\" value=\"insert2\" name=\"FormAction\"/>";
       } 

       $out .= "<input type=\"hidden\" name=\"FormName\" value=\"Registration2\">";
       $out .= "</form>";

	   $uwin = new window(localize('_TRANSDATA',getlocal()),$out);
	   $winout = $uwin->render();
	   unset($uwin);	   
	   
	   return ($winout);
	}

    function checkFields() {
	   //global $sFormErr;
	   $sFormErr = GetGlobal('sFormErr');	   

	   $sFormErr="";
	   SetGlobal('_sFormErr',"");

       //while (list ($field_num, $fieldname) = each ($this->recfields)) {
       foreach ($this->recfields as $field_num => $fieldname) {	   

         if(!strlen(GetParam(_with($fieldname)))) 
           $sFormErr .= localize('_MSG12',getlocal()) . " <font color=\"red\">" . 
		                localize($this->fieldalias[$field_num],getlocal()) . "</font> " . 
		                localize('_MSG11',getlocal()) . "<br>";
       }

	   SetGlobal('sFormErr',$sFormErr); 
       return ($sFormErr);
    }

    function getcustomer($id="") {
	   //global $a;   
	   $a = GetReq('a');
	   
	   if ($id) $cid = $id;
	   elseif ($a) $cid = $a;
	       else $cid = $this->userid;
	   
       $maxc = (count($this->recfields)-1);	      
	   reset ($this->recfields);
	   
       $sSQL = "SELECT ";
       //while (list ($field_num, $fieldname) = each ($this->recfields)) {
       foreach ($this->recfields as $field_num => $fieldname) {	   
	     $sSQL .= "[" . $fieldname . "]";
		 if ($field_num<$maxc) $sSQL .= ",";  
	   }	   
	   $sSQL .= " FROM " . $this->T_customers . " WHERE " . $this->fkey . "=";//ΕΝΑΛΛ_ΚΩΔΙΚΌΣ =

       //if (!$a) $sSQL .= $this->db_con->qstr($this->userid);
	   //    else $sSQL .= $this->db_con->qstr($a);
	   $sSQL .= $this->db_con->qstr($cid); //<<<<<<<<<
	   //print $sSQL;

       $result = $this->db_con->Execute($sSQL);
	   //print_r($result->fields);

       reset($this->recfields);
       //while (list ($field_num, $fieldname) = each ($this->recfields)) {
       foreach ($this->recfields as $field_num => $fieldname) {	   
	      $record .= $result->fields[$field_num] . ";"; 
       }

       //print $record;
	   return ($record);
	}


	function insert() {
	   //global $sFormErr;
	   $sFormErr = GetGlobal('sFormErr');	   

       $sSQL = "insert into ". $this->T_customers . " (RecID,Ενάλλ_κωδικός,";
	   
	   reset($this->recfields);
       //while (list ($field_num, $fieldname) = each ($this->recfields)) {
       foreach ($this->recfields as $field_num => $fieldname) {	   
	     $sSQL .= $fieldname;
		 if ($field_num<(count($this->recfields)-1)) $sSQL .= ",";  
	   }
											  
       $customerid = 10000; //+mysql id 
	   $customercode = 'INTER-'; // . mysql id 
       $sSQL .= ")" .  " values (" . ToSQL($customerid,"Number") . "," . ToSQL($customercode,"Text") . ",";
	   
	   reset($this->recfields);
       //while (list ($field_num, $fieldname) = each ($this->recfields)) {
       foreach ($this->recfields as $field_num => $fieldname) {	   
          $sSQL .=  ToSQL(GetParam(_with($fieldname)), "Text"); 
		 if ($field_num<(count($this->recfields)-1)) $sSQL .= ","; 
	   }
							   
	   $sSQL .= ")";					
	   
	   //print $sSQL;

       $result = $this->db_con->Execute($sSQL);
       if ($result) SetGlobal('sFormErr',"ok");//$sFormErr = "ok";
	           else SetGlobal('sFormErr',localize('_MSG17',getlocal()));//$sFormErr = localize('_MSG17',getlocal());

	}

	function update() {
		
       SetGlobal('sFormErr',"ok");
	}

	function _delete() {
	
       SetGlobal('sFormErr',"ok");
	}
	
	function bulk_subscribtion() {
			
   	    if (seclevel('CUSTOMERSMNG_',$this->userLevelID)) {	    
		  
          $subs = new subscriber;

		  $sSQL = "select [" . $this->mkey . "] from " . $this->T_customers; //ΕΝΑΛΛ_ΕΠΩΝΥΜΊΑ
		  //print $sSQL;
          $table = $this->db_con->Execute($sSQL);

          $i=1;
          while(!$table->EOF) {
            //print $table->fields[0];
			$subs->dosubscribe($table->fields[0]); 
			
	        $table->MoveNext();
		    $i+=1;
	      }
		  
		  setInfo("$i records scanned for subscribtion !!");		  
		}   	
	}
	
    function searchform($fields='') {
       //global $sFormErr;	
	   $sFormErr = GetGlobal('sFormErr');
	   
	   SetSessionParam("sres_sql",""); //reset selected customers
	   
       $this->getFields();	     
	   
       //navigation status
  	   $winout = setNavigator(localize('_TRANSDATA',getlocal())); 
	   	   
       //error message
	   $winout .= setError($sFormErr);	   
	   
	   $data = array();	   
       //read data
	   reset($this->recfields);
	   if ($fields) { //get record param
		   $myfields = explode(";",$fields); 

           //while (list ($field_num, $fieldname) = each ($this->recfields)) {
           foreach ($this->recfields as $field_num => $fieldname) {		   
		       $data[$field_num] = $myfields[$field_num];
		   }
	   }
	   else { //read form data
           //while (list ($field_num, $fieldname) = each ($this->recfields)) {
           foreach ($this->recfields as $field_num => $fieldname) {		   
		       $data[$field_num] = ToHTML(GetParam(_with($fieldname)));
		   }
	   }

       $aligntitle = "right;40%;";
	   $alignfield = "left;60%;";

       $sFileName = seturl("t=searchcustomer&a=&g=",0,1);

       $out .= "<form method=\"POST\" action=\"";
       $out .= "$sFileName";
       $out .= "\" name=\"SearchCustomer\">";

       //show data
       reset ($this->recfields);
	   reset ($data);
       //while (list ($field_num, $fieldname) = each ($this->recfields)) {
       foreach ($this->recfields as $field_num => $fieldname) {	   

         $field[] = localize($this->fieldalias[$field_num],getlocal()) . "*";
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

       if (seclevel('SEARCHCUSTOMER_',$this->userLevelID)) { 
           $out .= "<input type=\"submit\" value=\"" . trim(localize('_SEARCHCUST',getlocal())) . "\">";
           $out .= "<input type=\"hidden\" value=\"searchcustomer\" name=\"FormAction\"/>";
       } 

       $out .= "<input type=\"hidden\" name=\"FormName\" value=\"SearchCustomer\">";
       $out .= "</form>";   
	   
		
	   $uwin = new window(localize('_TRANSDATA',getlocal()),$out);
	   $winout = $uwin->render();
	   unset($uwin);	
	   		 
	   return ($winout);		 
	}
	
    function searchcustomer() {
	   $sres_sql = GetSessionParam("sres_sql");//GetGlobal('sres_sql');
	   $g = GetReq('g');
	   
	   if (!$sres_sql) {
         $maxc = (count($this->selectedfields)-1);	      
	     reset ($this->recfields);
	   
         $sSQL = "SELECT ";
         foreach ($this->selectedfields as $field_num => $fieldname) {	   
	       $sSQL .= "[" . $fieldname . "]";
		   if ($field_num<$maxc) $sSQL .= ",";  
	     }	   
	     $sSQL .= " FROM " . $this->T_customers;
	   
         foreach ($this->recfields as $field_num => $fieldname) {	
		   if (($whereSQL) && (GetParam(_with($fieldname)))) $whereSQL .= " AND ";	      
	       if (GetParam(_with($fieldname))) $whereSQL .= "[" . $fieldname . "] LIKE " . $this->db_con->qstr(GetParam(_with($fieldname)) . "%"); 
	     }	
	   
	     if ($whereSQL) $sSQL .= " WHERE " . $whereSQL;   
	   
  	     SetSessionParam("sres_sql",$sSQL); //save selected customers for page purposes
	   }
	   else	 
	     $sSQL = GetSessionParam("sres_sql"); //get selected customers  
	  
	   
	   return ($sSQL);
	}	
	
	
	function searchresults() {
	
	     //print_r($this->searchres);
	
         $out = setNavigator(localize('_SEARCHCUST',getlocal()));

         /*$browser = new browse($this->searchres,localize('_CUSTLIST',getlocal()));
	     $out .= $browser->render("searchcustomer",30,$this,1,1,1,0);
	     unset ($browser);	*/
		 
         $browser = new browseSQL(localize('_CUSTLIST',getlocal()));
	     $out .= $browser->render($this->db_con,$this->searchres,$this->T_customers,"searchcustomer",30,$this,1,0,1,0); //do not search internal because of form conflict
	     unset ($browser);		 
		 
		 return ($out);
	}
	
    function browse($packdata,$view) {
	
	   $data = explode("||",$packdata);
	
       $out = $this->viewcustomers($data[0],$data[1],$data[2],$data[3],$data[4]);

	   return ($out);
	}		
	
    function viewcustomers($nameid,$afm,$eforia,$addr,$fkey) {
	   $a = GetReq('a');
	   $p = GetReq('p');	   
	   
	   $link = seturl("t=loadcustomer&a=$nameid;$afm;$eforia;$addr;$fkey&g=" , $nameid);
							  
	   $data[] = $link;
	   $attr[] = "left;40%";
	   if ($afm) $data[] = $afm;   
	        else $data[] = "&nbsp;";
	   $attr[] = "left;10%";
	   if ($eforia) $data[] = $eforia;   
	           else $data[] = "&nbsp;";
	   $attr[] = "left;20%";
	   if ($addr) $data[] = $addr;   
	         else $data[] = "&nbsp;";	   
	   $attr[] = "left;30%";

	   $myarticle = new window('',$data,$attr);
	   
	   if ($a == $fname) $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::");
                    else $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::");
	   unset ($data);
	   unset ($attr);

	   return ($out);
	}	
	
	function headtitle() {
	   $t = GetReq('t');
	   $p = GetReq('p');
	   $sort = GetReq('sort');
	
       $data[] = seturl("t=$t&a=&g=1&p=$p&sort=$sort&col=0" , localize('_TITLE',getlocal()) ); 
	   $attr[] = "left;40%";							  
	   $data[] = seturl("t=$t&a=&g=2&p=$p&sort=$sort&col=1" , localize('_AFM',getlocal()) );
	   $attr[] = "left;10%";
	   $data[] = seturl("t=$t&a=&g=3&p=$p&sort=$sort&col=2" , localize('_EFORIA',getlocal()) );
	   $attr[] = "left;20%";
	   $data[] = seturl("t=$t&a=&g=4&p=$p&sort=$sort&col=3" , localize('_ADDR1',getlocal()) );
	   $attr[] = "left;30%";

  	   $mytitle = new window('',$data,$attr);
	   $out = $mytitle->render(" ::100%::0::group_form_headtitle::center;100%;::");
	   unset ($data);
	   unset ($attr);	
	   
	   return ($out);
	}	

};
}
}
else die("BACKOFFICE DPC REQUIRED!");
?>