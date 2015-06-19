<?php
if (defined("SEN_DPC")) {

$__DPCSEC['SENCUSTOMERS_DPC']='2;2;2;2;2;2;2;2;9';
$__DPCSEC['CUSTOMERSMNG_']='2;1;1;2;2;2;2;2;9';
$__DPCSEC['DELETECUSTOMER_']='2;1;1;1;1;1;1;2;9';
$__DPCSEC['UPDATECUSTOMER_']='2;1;1;2;2;2;2;2;9';
$__DPCSEC['INSERTCUSTOMER_']='2;1;1;1;1;1;2;2;9';
$__DPCSEC['SEARCHCUSTOMER_']='2;1;1;1;1;1;2;2;9';

if ((!defined("SENCUSTOMERS_DPC")) && (seclevel('SENCUSTOMERS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SENCUSTOMERS_DPC",true);

$__DPC['SENCUSTOMERS_DPC'] = 'sencustomers';

$__EVENTS['SENCUSTOMERS_DPC'][0]='insert2';
$__EVENTS['SENCUSTOMERS_DPC'][1]='update2';
$__EVENTS['SENCUSTOMERS_DPC'][2]='delete2';
$__EVENTS['SENCUSTOMERS_DPC'][3]='custbulksubscribe';
$__EVENTS['SENCUSTOMERS_DPC'][4]='searchcustomer';

$__ACTIONS['SENCUSTOMERS_DPC'][0]='signup2';
$__ACTIONS['SENCUSTOMERS_DPC'][1]='insert2';
$__ACTIONS['SENCUSTOMERS_DPC'][2]='update2';
$__ACTIONS['SENCUSTOMERS_DPC'][3]='delete2';
$__ACTIONS['SENCUSTOMERS_DPC'][4]='searchcustomer';

$__LOCALE['SENCUSTOMERS_DPC'][0]='_TITLE;Title;Επωνυμία';
$__LOCALE['SENCUSTOMERS_DPC'][1]='_AFM;VAT No;Α.Φ.Μ.;';
$__LOCALE['SENCUSTOMERS_DPC'][2]='_JOBTITLE;Job Title;Επαγγελμα';
$__LOCALE['SENCUSTOMERS_DPC'][3]='_ADDR1;Address 1;Διεύθυνση Λογιστηρίου';
$__LOCALE['SENCUSTOMERS_DPC'][4]='_POBOX1;Post code 1;Τ.Κ. Λογιστηρίου';
$__LOCALE['SENCUSTOMERS_DPC'][5]='_ADDR2;Address 2;Διεύθυνση Παράδοσης';
$__LOCALE['SENCUSTOMERS_DPC'][6]='_POBOX2;Post code 2;Τ.Κ. Παράδοσης';
$__LOCALE['SENCUSTOMERS_DPC'][7]='_TEL;Voice;Αριθμός Τηλεφώνου';
$__LOCALE['SENCUSTOMERS_DPC'][8]='_FAX;Fax;Αριθμός Τηλεομοιότυπου';
$__LOCALE['SENCUSTOMERS_DPC'][9]='_SIGNUP;SignUp;Εγγραφή';
$__LOCALE['SENCUSTOMERS_DPC'][10]='_UPDATE;Update;Ενημέρωση';
$__LOCALE['SENCUSTOMERS_DPC'][11]='_DELETE;Delete;Διαγραφή';
$__LOCALE['SENCUSTOMERS_DPC'][12]='_MSG10;Successfull registration!;Επιτυχής καταχώρηση!';
$__LOCALE['SENCUSTOMERS_DPC'][13]='_MSG11;is required;είναι απαραίτητο';
$__LOCALE['SENCUSTOMERS_DPC'][14]='_MSG12;The value in field;Η τιμή στο πεδίο';
$__LOCALE['SENCUSTOMERS_DPC'][15]='_ACCDENIED;Access denied;Απαγορυμένη πρόσβαση';
$__LOCALE['SENCUSTOMERS_DPC'][16]='_EFORIA;Account Department;ΔΟΥ';
$__LOCALE['SENCUSTOMERS_DPC'][17]='_SEARCHCUST;Searchin;Ευρεση';
$__LOCALE['SENCUSTOMERS_DPC'][18]='_SEARCHRES;Results;Αποτελέσματα Αναζήτησης';
$__LOCALE['SENCUSTOMERS_DPC'][19]='_CUSTLIST;Customers;Πελάτες';


//require_once("sen.dpc.php");
//GetGlobal('controller')->include_dpc('sen/sen.dpc.php');
require_once(GetGlobal('controller')->require_dpc('sen/sen.dpc.php'));

class sencustomers extends sen {

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

	function sencustomers() {
	   $UserSecID = GetGlobal('UserSecID');   
	   $sFormErr = GetGlobal('sFormErr'); 
	   $UserName = GetGlobal('UserName');   
	   $UserID = GetGlobal('UserID'); 
	   
	   sen::sen();	   

	   $this->userLevelID = decode($UserSecID);
	   $this->username = decode($UserName);
	   $this->userid = decode($UserID);
	   $this->msg = $sFormErr;
	   	   
       //select fields from table to get
	   $this->selectedfields = arrayload('SENCUSTOMERS','select'); 
	   $this->fieldalias = arrayload('SENCUSTOMERS','selectalias'); 
	   //print_r($this->selectedfields);	   
	   
	   $this->fkey = paramload('SENCUSTOMERS','fkey');
	   $this->mkey = paramload('SENCUSTOMERS','mailkey');	   
	}
	
	
	function getFields() {	
		 	
         $this->get_MetaTable();
		 
	     $this->fields = $this->get_MetaColumns($this->T_customers); 
		 //print_r($this->fields);

         //get table data
		 $this->recfields = null;
		 $this->maxcharfields[] = null;
         foreach ($this->selectedfields as $field_num => $fieldname) {
		   if ($this->fieldalias[$field_num]) {	   
	         $this->recfields[] = $this->fields[$fieldname]->name;
	         $this->maxcharfields[] = $this->fields[$fieldname]->max_length;
		   }
	     }
	     //print_r($this->selectedfields);	 
		 //print_r($this->recfields);
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

    function getcustomer($id="") {
	   $a = GetReq('a');  
	   
	   if ($id) $cid = $id;
	   elseif ($a) $cid = $a;
	       else $cid = $this->userid;
	   
       $maxc = (count($this->recfields)-1);	      
	   reset ($this->recfields);
	   
       $sSQL = "SELECT ";
       //foreach ($this->recfields as $field_num => $fieldname) {	   
	   foreach ($this->selectedfields as $field_num => $fieldname) {
	     $sSQL .= $fieldname;
		 if ($field_num<$maxc) $sSQL .= ",";  
	   }	   
	   $sSQL .= " FROM " . $this->T_customers . " WHERE " . $this->fkey . "=" . $cid;
	   //echo 'customer:'.$sSQL;

       $result = $this->sen_db->Execute($sSQL);
	   //print_r($result->fields);

       //reset($this->recfields);
       //foreach ($this->recfields as $field_num => $fieldname) {	   
	   foreach ($this->selectedfields as $field_num => $fieldname) {
	      $record .= $result->fields[$field_num] . ";"; 
       }

       //print $record;
	   return ($record);
	}
	
	//return leeid of customer based on ageneric where (suitable for pre register user procedure)
	function search_customer_id($where_statement) {
	
	   $sSQL = "SELECT LEEID FROM " . $this->T_customers . " WHERE " .$where_statement;
	   //echo $sSQL;
       $result = $this->sen_db->Execute($sSQL);
	   
	   //echo $result->fields[0];
	   
	   return ($result->fields[0]);	   
	}	

	//return array of record
    function getcustomerdata($what=null) {
	
	   $data = array();
       $this->getFields();
	   	   
       //read data
	   $fields = $this->getcustomer(); 

       reset($this->recfields); 

       //in case of no customer data this must return null
	   if (strlen($fields)>9) { //if empty returns ';;;;;;;;;'

		   $myfields = explode(";",$fields);

           //while (list ($field_num, $fieldname) = each ($this->recfields)) {
           foreach ($this->recfields as $field_num => $fieldname) {
		       if ($myfields[$field_num]) $data[$field_num] = $myfields[$field_num];
			                         else $data[$field_num] = ""; 
		   }
       }
	   
       //print_r($data);	   
	   if (isset($what)) {
	     //echo $data[$what]; 
	     return ($data[$what]);
	   }
	   else {
	     return ($data);
	   }
	}	
	
	//return formed record data as html
    function showcustomerdata($customer="") {
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
	   
	   //$out = 'CUSTOMER';

	   return ($out);
	}
	

    function makeform($fields='') {
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


	function insert() {

		SetGlobal('sFormErr',"ok");
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

		  $sSQL = "select " . $this->mkey . " from " . $this->T_customers; //ΕΝΑΛΛ_ΕΠΩΝΥΜΊΑ
		  //print $sSQL;
          $table = $this->sen_db->Execute($sSQL);

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
	   $sres_sql = GetSessionParam('sres_sql');
	   
	   $g = GetReq('g');	   
	   
	   if (!$sres_sql) {
       $maxc = (count($this->selectedfields)-1);	      
	   reset ($this->recfields);
	   
       $sSQL = "SELECT ";
       //while (list ($field_num, $fieldname) = each ($this->recfields)) {
       foreach ($this->selectedfields as $field_num => $fieldname) {	   
	     $sSQL .= $fieldname;
		 if ($field_num<$maxc) $sSQL .= ",";  
	   }	   
	   $sSQL .= " FROM " . $this->T_customers;
	   
       foreach ($this->recfields as $field_num => $fieldname) {	
		 if (($whereSQL) && (GetParam(_with($fieldname)))) $whereSQL .= " AND ";	      
	     if (GetParam(_with($fieldname))) $whereSQL .= $fieldname . " LIKE '" . GetParam(_with($fieldname)) . "%'"; 
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
	     $out .= $browser->render($this->sen_db,$this->searchres,$this->T_customers,"searchcustomer",30,$this,1,0,1,0); //do not search internal because of form conflict
	     unset ($browser);		 
		 
		 return ($out);
	}
	
    function browse($packdata,$view) {
	
	   $data = explode("||",$packdata);
	
       $out = $this->viewcustomers($data[0],$data[1],$data[2],$data[3],$data[4]);

	   return ($out);
	}		
	
    function viewcustomers($nameid,$afm,$eforia,$addr,$fkey) {
	   
	   $p = GetReq('p');
	   $a = GetReq('a');	   	   
	   
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
else die("SEN DPC REQUIRED!");
?>