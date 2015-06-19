<?php
if (class_exists('phtml')) {
  if (defined('SHKATALOG_DPC'))
    define('IS_STANDALONE', 'false');
  else
    define('IS_STANDALONE', 'true');
	
    $a = GetGlobal('controller')->require_dpc('shell/pxml.lib.php');
    require_once($a);		
}  
else {
  define('IS_STANDALONE', 'true');

  require_once('system.lib.php');	 
  require_once('sysdb.lib.php');	  
  require_once('session.lib.php');  
  require_once('pxml.lib.php');
  require_once('form.dpc.php');  
//  require_once('database.dpc.php');	   
//  require_once('shkatalog.dpc.php');	  
  //$config['SHELL']['ip'] = 'http://www.stereobit.com/cgi-bin';
}  
//echo '>',IS_STANDALONE;

$__DPCSEC['RCXMLEXP_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("RCXMLEXP_DPC")) && (seclevel('RCXMLEXP_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCXMLEXP_DPC",true);

$__DPC['RCXMLEXP_DPC'] = 'rcxmlexp';

/*if (IS_STANDALONE=='false') {
  $a = GetGlobal('controller')->require_dpc('shell/pxml.lib.php');
  require_once($a);
  //$b = GetGlobal('controller')->require_dpc('shop/shkatalog.dpc.php');
  //require_once($b);
  //$c = GetGlobal('controller')->require_dpc('rss/rssgenerator.lib.php');
  //require_once($c);  
}*/

$__EVENTS['RCXMLEXP_DPC'][0]='cpxmlexp';
$__EVENTS['RCXMLEXP_DPC'][1]='doxmlexp';

$__ACTIONS['RCXMLEXP_DPC'][0]='cpxmlexp';
$__ACTIONS['RCXMLEXP_DPC'][1]='doxmlexp';

$__LOCALE['RCXMLEXP_DPC'][0]='RCXMLEXP_DPC;XML Exporter;XML Exporter';
$__LOCALE['RCXMLEXP_DPC'][1]='_XMLTYPE;XML Type;Τυπος XML';
$__LOCALE['RCXMLEXP_DPC'][2]='_XMLFILENAME;XML filename;Ονομα XML αρχειου';
$__LOCALE['RCXMLEXP_DPC'][3]='_XMLOUTPATH;XML path;Διαδρομη XML';
$__LOCALE['RCXMLEXP_DPC'][4]='_XMLLANG;XML Language;Γλωσσα XML';
$__LOCALE['RCXMLEXP_DPC'][5]='_CRTLANDPG;Create LP;Δημιουργια LP';
$__LOCALE['RCXMLEXP_DPC'][6]='_LPOUTPATH;LP Path;Διαδρομη LP';
$__LOCALE['RCXMLEXP_DPC'][7]='_MERGEFILES;XML Merging;Συνενωση XML αρχειων';
$__LOCALE['RCXMLEXP_DPC'][8]='_XMLHEAD;XML Headers;Ετικέτα XML';
$__LOCALE['RCXMLEXP_DPC'][9]='_QRYWHERE;Has query options;Εχει φιλτρο';
$__LOCALE['RCXMLEXP_DPC'][10]='_QRYFILTER;Query option;Φιλτρο ερωτηματος';
$__LOCALE['RCXMLEXP_DPC'][11]='_DOWNLOAD;Download XML;Ληωη XML';
$__LOCALE['RCXMLEXP_DPC'][12]='_LPREDIR;Redirect LP;Επανακατευθυνση LP';
$__LOCALE['RCXMLEXP_DPC'][13]='_DBCONN;Connection;Συνδεση';
$__LOCALE['RCXMLEXP_DPC'][14]='_DBUSER;User;Χρησητης';
$__LOCALE['RCXMLEXP_DPC'][15]='_DBPASS;Pass;Κωδικος';
$__LOCALE['RCXMLEXP_DPC'][16]='_DBHOST;Host;Host';
$__LOCALE['RCXMLEXP_DPC'][17]='_DBNAME;Name;Ονομα';
$__LOCALE['RCXMLEXP_DPC'][18]='_DBUPLOAD;Upload DB data(.txt,.csv);Φόρτωση δεδομένων (.txt,.csv)';
$__LOCALE['RCXMLEXP_DPC'][19]='_SEOLINKS;SEO Links;SEO Links';
$__LOCALE['RCXMLEXP_DPC'][20]='_LPNAME;LP titles;Τιτλοι LP';
$__LOCALE['RCXMLEXP_DPC'][21]='_PRICEWITHTAX;Prices with tax;Αξιες με ΦΠΑ';
$__LOCALE['RCXMLEXP_DPC'][22]='_DBUPDSEP;Separator;Διαχωριστικο συμβολο';
$__LOCALE['RCXMLEXP_DPC'][23]='_EXISTS;Exist;Εχουν δημιουργηθεί';
$__LOCALE['RCXMLEXP_DPC'][24]='_SEOHIDDEN;Hidden SEO Links;Κρυμμενα συνδεσεις';
$__LOCALE['RCXMLEXP_DPC'][25]='_SUCCESS;Procedure terminated successfully!;Η διαδικασία ολοκληρωθηκε σωστά!';
$__LOCALE['RCXMLEXP_DPC'][26]='_ERROR;Procedure terminated with errors!;Η διαδικασία απέτυχε!';
$__LOCALE['RCXMLEXP_DPC'][27]='_PAYOUT;record(s) exceed the limit. Please continue by pressing;εγγραφές ειναι εκτός ορίων. Παρακαλώ πατήστε';
$__LOCALE['RCXMLEXP_DPC'][28]='_HERE;here.;εδώ.';
$__LOCALE['RCXMLEXP_DPC'][29]='_ERRXMLTYPE;Please select XML type.;Παρακαλώ επιλέξτε τύπο XML.';

class rcxmlexp {

    var $userLevelID;	
	var $result, $pc;
	var $path, $post, $msg, $urlpath, $url;
	var $current_batch_records, $process_error;
	var $getmapf,$xmlopt;
	var $in_cp_path;
	var $sql_limit, $enctype;
	var $goto_pay;
	var $records_done;
	var $target,$source, $key, $ekey, $records_paid, $fn, $say, $autobatch, $utmpfile;

	function rcxmlexp() {
	  //global $config;
	  $UserSecID = GetGlobal('UserSecID'); 	
      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);	  
	  
      //check if it is in cp dir..so it is no standalone	  
	  $this->in_cp_path = strstr(getcwd(),'/cp/');
	  //echo $this->in_cp_path;   
      //in case of phtml when out of cp..is standalone app
      /*if ((class_exists('phtml')) && (!$this->in_cp_path)) {
	    echo 'a';
        define('IS_STANDALONE', 'true');//override standalone
	  }	*/
	  //else remain as is 
	  // a/in cp is a cp app, 
	  // b/directly exec this php file..is a standalone app	

      $this->post = false;
      $this->msg = null;
	  $this->process_error = false;	
	  $this->goto_pay = false;
	  
      if (IS_STANDALONE=='false') {
         $this->path = paramload('SHELL','prpath');	
         $this->urlpath = paramload('SHELL','urlpath');
		 $murl = arrayload('SHELL','ip');
         $this->url = $murl[0]; 
	     $this->inpath = paramload('ID','hostinpath');		   
	     $this->title = $this->localize('RCXMLEXP_DPC',getlocal());		  
	    
	     $this->languages = remote_arrayload('SHELL','languages',$this->path);
	     $this->default_lang = remote_paramload('SHELL','dlang',$this->path);
		  
		 $this->xmlopt = null;//not used	
		 $this->enctype = null;		
		 
	     $this->sql_limit = 100000;		   
	  }
	  else { 
		 //echo $config['SHELL']->ip,'>',paramload('SHELL','ip');
         $this->path = paramload('SHELL','prpath');//getcwd();	//private dir to create file
         $this->urlpath = getcwd();//remote_paramload('SHELL','urlpath',$this->path);
         $this->url = 'http://www.stereobit.com';//remote_paramload('SHELL','ip',$this->path);	 
	     $this->inpath = null;//paramload('ID','hostinpath');		   
	     $this->title = $this->localize('RCXMLEXP_DPC',getlocal());	
		 	  
         $this->getmapf = null;//not used
	     $this->languages = array(0=>'Default');
	     $this->default_lang = 0;	
		 
		 $this->xmlopt = array (0=>"XML Types","skroutz" => "Skroutz","Default XML"=>"Default XML");  
		 $this->dbtype = array (0 =>"Select Database" ,1 =>"Internal Database",2 => "MySQL",3 => "Oracle",4 => "Oci8",5 => "MsSQL-SyBase",6 => "Informix",7 => "Postgres",8 => "SQLite",9 => "iBase",10 => "ODBC",11=>"Upload text file");  
		 //echo $this->path,$this->url,$this->urlpath;
		 
	     $this->autobatch = -1000;		 
		 
		 $this->enctype = 1;//multipart form for uploading files	
		 $this->ekey = 'stereobit.networlds-phpdac2.0';//not used..mcrypt
	     if ($this->key=GetParam('key')) {//return after a cart process and/or batch
	       $k = urldecode(decode($this->key));
           //echo "$k<br>";
		   $k1 = explode('(',$k);
		   //print_r($k1);
		   foreach ($k1 as $p=>$part) {
		     switch ($p) {
		       case 1 : $k2 = explode(')',$part); $this->source = $k2[0]; break;
			   case 2 : $k2 = explode(')',$part); $this->target = $k2[0]; break;
			   case 3 : $k2 = explode(')',$part); $this->records_paid = $k2[0]; break;
               case 4 : $k2 = explode(')',$part); $this->fn = $k2[0]; break; //get filename created before pay
               case 5 : $k2 = explode(')',$part); $this->utmpfile = $k2[0]; break; //get tmp (not to reload)			   
			   case 0 : $this->source = null;
			            $this->target = null;
				   	    $this->records_paid = null;
						$this->fn = null;//init
						$this->utmpfile = null;
						break;
			   default :			
		     }
		   }
	       $this->sql_limit = $this->records_paid + 10;	//also a uploaded rec file limit plus paid recs
		   //echo $this->source,$this->target,$this->records_paid;
	     }
         else {//a new filename
		   $this->fn = 'xmlout_' . mktime() . '.xml';//xml filename default val 		 		 
	       $this->sql_limit = 10; //limit		 
		 }  
		 
		 //echo $this->sql_limit,'+',$this->records_paid;
	  }
	  
	  $senc = arrayload('SHELL','char_set'); 
	  $c = getlocal()?getlocal():0; //echo $c;
	  $this->s_enc = $senc[$c]; //echo $this->s_enc; 
      $this->t_enc = paramload('SHELL','charset');	//echo $this->t_enc;
	  
	  $this->records_done = 0;
	  $this->say = null; 
	  
	  if (GetReq('editmode')) {//default form colors	
		    global $config;
			$config['FORM']['element_bgcolor1'] = 'EEEEEE';
			$config['FORM']['element_bgcolor2'] = 'DDDDDD';			
	  }	  
	}
	
	function event($event=null) {		
	
		  	  
	   /////////////////////////////////////////////////////////////	
	   if (($this->in_cp_path) && 
	       (IS_STANDALONE=='false') && 
		   (GetSessionParam('LOGIN')!='yes')) die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////			
	      
	
	    switch ($event) {

		  case 'doxmlexp'     :     
		                            $this->post = $this->checkform();//true;
									if ($this->post==true) {
									  set_time_limit(0); //unlimited
		                              $this->msg .= $this->read_data();								
                                      $this->msg .= $this->do_xml_process();
									  set_time_limit(50); 
									}
                                    break;
		  default             : 
        }	
	}
	
	function action($action=null) {
	
   	    if (!GetReq('editmode')) {
	
        if (IS_STANDALONE=='false') {	
	      if (GetSessionParam('REMOTELOGIN')) 
	        $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	      elseif ($this->in_cp_path)  
            $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);	 
		  else
		    $out = setNavigator($this->title);	 
		}
		else   
		  $out = setNavigator($this->title);//out of cp	 		
	    }
		  
	    switch ($action) {
		
		  case 'doxmlexp'     : 	  
		  case 'cpxmlexp'     : 
		  default             : $out .= $this->show_form();
		                       
        }	  
	  
	    return ($out);
	}	
	
	function show_form() {
	
       if ($this->post==true) { 
	
	     if ((!$this->process_error) && ($b=GetParam('batch')) && ($this->current_batch_records==abs($b))) 
		    $out = $this->form_next_batch("Export continue ...",'doxmlexp','100%');		
		 else {
		    if (GetParam('mergefiles'))
			   $this->merge_xml_files();
			
		    $out = $this->form();; //end of batch proccesing
	     }		
	   }	 
	   else  //startup form
		    $out = $this->form();
		 
		return ($out);
	  
	}
	
	function form($action=null) {
	   global $sFormErr;
	   
	   //print_r($_POST);

       $myaction = seturl("t=doxmlexp"); //echo '<br>+',$myaction;
       $xmlfnval = GetParam("xmlfilename")?GetParam("xmlfilename"):'xmlout.xml';
	   $lang = GetParam('xmllang')?GetParam('xmllang'):$default_lang;
     
	   
       if (IS_STANDALONE=='true') {	   
	   
	     /////////////////////////////
	     if (($this->post==true) && ($this->records_done>0)) {
           
		   if (($this->records_todo) && ($this->goto_pay)) {
		     //pay redirection
			 //$link = seturl('t=addtocart&a=111;;;;;;;;;;'.$this->records_todo,'Procced to pay');
			 //$link = "<A href='index.php?t=addtocart&a=111;;;;;;;;;;$this->records_todo'>procced</A>";
             if ( (defined('SHCART_DPC')) && (seclevel('SHCART_DPC',decode(GetSessionParam('UserSecID')))) ) { 
			   //$cmd = GetGlobal('controller')->calldpc_var("shcart.checkout");
			   //GetGlobal('controller')->calldpc_method("shcart.addtocart use XMLfile");
			   //$link = GetGlobal('controller')->calldpc_method("shcart.showsymbol use 111");
			   
			   //upload file priority .. then db name
			   $source = GetParam('dbupload')?GetParam('dbupload'):GetParam('dbname');//:GetParam('dbupload');			   
			   $target = GetParam('xmltype');
			   
		       $cart_code = '111';
			   $cart_title = 'XML output of ('.$source.') for ('.$target.'), ('.$this->records_todo.') records';
			   $cart_group = '';
			   $cart_page = 0;
			   $cart_descr = 'XML output of ('.$source.') for ('.$target.'), ('.$this->records_todo.') records';
			   $cart_photo = null;
			   $cart_price = '0.10';
			   $cart_qty = $this->records_todo;//echo $this->records_todo;
		       //$link = GetGlobal('controller')->calldpc_method("shcart.showsymbol use $cart_code;$cart_title;$path;$MYtemplate;$cart_group;$cart_page;$cart_descr;$cart_photo;$cart_price;$cart_qty;+$cat+$cart_page",1);//'cart';		
			   $item = "$cart_code;$cart_title;$path;$MYtemplate;$cart_group;$cart_page;$cart_descr;$cart_photo;$cart_price;$cart_qty;";
			   GetGlobal('controller')->calldpc_method("shcart.addtocart use " . $item.'+'.$cart_qty);
			   
			   $key = urlencode(encode($cart_descr.'('.$this->fn.')('.$this->utmpfile.')'));//hash('md5',$card_descr,false);
			   
			   SetSessionParam('aftersubmitgoto','cpxmlexp&xmltype='.$target.'&key='.$key);
			   
			   $link = "<A href='index.php?t=viewcart'>".$this->localize('_HERE',getlocal())."</A>";			   
			   //$payout = '<br>You have to pay to get '. ($this->records_todo) . ' extra outputs.';
			   //$payout .= '<br>Please follow this link ('. $link . ')';
			   $payout = $this->records_todo . ' ' . $this->localize('_PAYOUT',getlocal()) .' '. $link;
			 }
			 //win message
			 $msg = setError($sFormErr . $this->msg);
			 //download file
			 $out .= $this->download_link(null,$msg,$payout);
			 
             /*$this->say = 'Type:'.$this->target.' from '.$this->source . ', ';
	         $this->say .= ($this->records_done?$this->records_done:0) . ' records done' . ', '. 
			     	       ($this->records_todo?$this->records_todo:0) . ' records todo';	   			 */
		   }
		   else {//paid records translated. reset params
						   		   
			 $this->source = null;
             $this->target = null;
	    	 $this->records_paid = GetParam('records_paid');//null;
			 $this->key = null;
			 //$this->fn = 'xmlout_' . mktime() . '.xml';//reinit fn ///no..!!!!error at new file
			 //win message
			 $msg = setError($sFormErr . $this->msg);
			 $out .= $this->download_link(null,$msg);			 
		   }
		   
	     }//post ...error during translation or init of form
	     else {////////////////////////////help texts
	   	   //win message
		   $out .= setError($sFormErr . $this->msg);
		
           $out .= $this->show_help();
		 }		   		  
       }//not standalone
	   else //simple message
	     $out .= setError($sFormErr . $this->msg);					 
	
	   
	   $form = new form($this->localize('RCXMLEXP_DPC',getlocal()), "doxmlexp", FORM_METHOD_POST, $myaction, true,$this->enctype);

	   $form->addGroup			("ep",				"XML Export Params.");				         
       if (IS_STANDALONE=='true') {
	     //$form->addElement		("ep",			new form_element_radio		($this->localize('_XMLHEAD',getlocal()),   "xmlhead",      GetParam('xmlhead'),             "",   2, $this->xmlopt));	   	 	   	   	   
		 if ($this->target)
		   $form->addElement		("ep",			new form_element_text		($this->localize('_XMLTYPE',getlocal()),      "xmltype",	        $this->target,				"forminput",	        20,				255,	1));	   	   
		 else
		   $form->addElement		("ep",		    new form_element_combo ($this->localize('_XMLTYPE',getlocal()),     "xmltype",	    GetParam('xmltype'),				"forminput",	        0,$this->xmlopt,0));	 	   
	   }	 
	   else {	   
	     $form->addElement		("ep",		    new form_element_combo_file ($this->localize('_XMLTYPE',getlocal()),     "xmltype",	    GetParam('xmltype'),				"forminput",	        0,0,	'xmlexptypes',1));
		 
		 if ($forcategory=GetReq('cat')) {
		  	$form->addElement		("ep",		    new form_element_text	    ($this->localize('_FORCATEGORY',getlocal()),  "forcategory",	        $forcategory,				"forminput",	        20,				255,	1)); 
			$form->addElement		("ep",		    new form_element_text	    ($this->localize('_XMLFILENAME',getlocal()),  "xmlfilename",	        $forcategory.'.xml',		"forminput",	        20,				255,	0));
		 }	 
		 else	   
	       $form->addElement		("ep",		    new form_element_text	    ($this->localize('_XMLFILENAME',getlocal()),  "xmlfilename",	        $xmlfnval,				"forminput",	        20,				255,	0));
		   
	     $form->addElement		("ep",			new form_element_text		($this->localize('_XMLOUTPATH',getlocal()),      "xmloutpath",	        GetParam("xmloutpath"),				"forminput",	        20,				255,	0));	   	   
	     //$form->addElement		("ep",		    new form_element_combo ($this->localize('_XMLLANG',getlocal()),     "xmllang",	    $lang,				"forminput",	        0,0,	$languages,1));
         $form->addElement		("ep",		    new form_element_radio		($this->localize('_XMLLANG',getlocal()),   "xmllang",      $this->lang,             "",   2, $this->languages));	   
         $form->addElement		("ep",			new form_element_radio		($this->localize('_XMLHEAD',getlocal()),   "xmlhead",      GetParam('xmlhead'),             "",   2, array ("0" => localize('_OXI',getlocal()), "1" => localize('_NAI',getlocal()))));	   	 	   	   	   
       }
	   $form->addGroup		("db",		"Data entry.");		   
       if (IS_STANDALONE=='true') {//true	   
		 
	     if (defined('SHKATALOG_DPC')) {//sql where
		   //sql where
           $form->addElement		("db",			new form_element_radio		($this->localize('_QRYWHERE',getlocal()),   "qrywhere",      GetParam('qrywhere'),             "",   2, array ("0" => localize('_OXI',getlocal()), "1" => localize('_NAI',getlocal()))));	   	 	   	   	   	   
	       $form->addElement		("db",			new form_element_text		($this->localize('_QRYFILTER',getlocal()),      "qryfilter",	        GetParam("qryfilter"),				"forminput",	        40,				255,	0));	   	   	    
		 }
		 elseif (defined('DATABASE_DPC')) {//new conn	
		 
	       //$form->addGroup			("Oracle",				"oracle.",1,'HIDE');		 	 
		   
		   //new connection
	       
		   if (($this->source) && (!$this->is_uploaded_db($this->source))) {//hide db conn when upload
  		     $form->addElement		("db",		    new form_element_combo      ($this->localize('_DBCONN',getlocal()),  "dbconn",	        GetParam('dbconn'),				"forminput",	        0,$this->dbtype,0));			   
	         $form->addElement		("db",		    new form_element_text	    ($this->localize('_DBHOST',getlocal()),  "dbhost",	        GetParam("dbhost"),				"forminput",	        20,				255,	0));		   		   
		     $form->addElement		("db",			new form_element_text		($this->localize('_DBNAME',getlocal()),  "dbname",	        $this->source,				"forminput",	        20,				255,	1));	   	   
	         $form->addElement		("db",			new form_element_text		($this->localize('_DBUSER',getlocal()),  "dbuser",	        GetParam("dbuser"),				"forminput",	        20,				255,	0));	   	   
	         $form->addElement		("db",		    new form_element_password   ($this->localize('_DBPASS',getlocal()),  "dbpass",	        GetParam("dbpass"),				"forminput",	        20,				255));//,	0)); //text element 
			 $form->addElement		(FORM_GROUP_HIDDEN,	new form_element_hidden ("records_paid", $this->records_paid));
			 $form->addElement		(FORM_GROUP_HIDDEN,	new form_element_hidden ("key", $this->key));			 
		   }	 
		   elseif (!$this->source) {		   
  		     $form->addElement		("db",		    new form_element_combo      ($this->localize('_DBCONN',getlocal()),  "dbconn",	        GetParam('dbconn'),				"forminput",	        0,$this->dbtype,0));			   
	         $form->addElement		("db",		    new form_element_text	    ($this->localize('_DBHOST',getlocal()),  "dbhost",	        GetParam("dbhost"),				"forminput",	        20,				255,	0));		   		   
	         $form->addElement		("db",		    new form_element_text	    ($this->localize('_DBNAME',getlocal()),  "dbname",	        GetParam("dbname"),				"forminput",	        20,				255,	0));			   
	         $form->addElement		("db",			new form_element_text		($this->localize('_DBUSER',getlocal()),  "dbuser",	        GetParam("dbuser"),				"forminput",	        20,				255,	0));	   	   
	         $form->addElement		("db",		    new form_element_password   ($this->localize('_DBPASS',getlocal()),  "dbpass",	        GetParam("dbpass"),				"forminput",	        20,				255));//,	0)); //text element
		   }
		   
		   if (($this->source) && ($this->is_uploaded_db($this->source))) {//hide upload when db conn
		     //echo $this->utmpfile,'>',$this->source;
		     if (is_readable($this->utmpfile)) {//if upload tmp file exist and has saved ok
               $form->addElement		("db",	    new form_element_text	    ($this->localize('_DBUPLOAD',getlocal()),  "dbupload",	        $this->source,				"forminput",	        20,				255,	1));	   		 		   		   			   
               $form->addElement		(FORM_GROUP_HIDDEN,	new form_element_hidden ("utmpfile", $this->utmpfile));			 			   
			 }  
			 else
               $form->addElement		("db",		    new form_element_upload	    ($this->localize('_DBUPLOAD',getlocal()),  "dbupload",	        GetParam("dbupload"),				"forminput",	        20,				255,	1000000));		   		 		   
			   
             $form->addElement		("db",		    new form_element_text	    ($this->localize('_DBUPDSEP',getlocal()),  "dbupdsep",	        GetParam("dbupdsep"),				"forminput",	        20,				255,	0));	   		 		   		   
			 $form->addElement		(FORM_GROUP_HIDDEN,	new form_element_hidden ("records_paid", $this->records_paid));
			 $form->addElement		(FORM_GROUP_HIDDEN,	new form_element_hidden ("key", $this->key));			 
		   }
		   elseif (!$this->source) {
             $form->addElement		("db",		    new form_element_upload	    ($this->localize('_DBUPLOAD',getlocal()),  "dbupload",	        GetParam("dbupload"),				"forminput",	        20,				255,	1000000));		   		 		   
             $form->addElement		("db",		    new form_element_text	    ($this->localize('_DBUPDSEP',getlocal()),  "dbupdsep",	        GetParam("dbupdsep"),				"forminput",	        20,				255,	0));	   		 		   		   		   
		   }
		 }
		 else {//upload file
		   if (($this->source) && ($this->is_uploaded_db($this->source))) {
		     $form->addElement		("db",			new form_element_text		($this->localize('_DBUPLOAD',getlocal()),      "dbupload",	        $this->source,				"forminput",	        20,				255,	1));	   	   
             $form->addElement		("db",		    new form_element_text	    ($this->localize('_DBUPDSEP',getlocal()),  "dbupdsep",	        GetParam("dbupdsep"),				"forminput",	        20,				255,	0));			 		   			 
			 $form->addElement		(FORM_GROUP_HIDDEN,	new form_element_hidden ("records_paid", $this->records_paid));
			 $form->addElement		(FORM_GROUP_HIDDEN,	new form_element_hidden ("key", $this->key));
		   }	 
		   else {		 
             $form->addElement		("db",		    new form_element_upload	    ($this->localize('_DBUPLOAD',getlocal()),  "dbupload",	        GetParam("dbupload"),				"forminput",	        20,				255,	1000000));		   		 
             $form->addElement		("db",		    new form_element_text	    ($this->localize('_DBUPDSEP',getlocal()),  "dbupdsep",	        GetParam("dbupdsep"),				"forminput",	        20,				255,	0));			 		   
		   }	 
		 }		 
	   }
	   else {//sql where & land pages
         $form->addElement		("db",			new form_element_radio		($this->localize('_QRYWHERE',getlocal()),   "qrywhere",      GetParam('qrywhere'),             "",   2, array ("0" => localize('_OXI',getlocal()), "1" => localize('_NAI',getlocal()))));	   	 	   	   	   	   
	     $form->addElement		("db",			new form_element_text		($this->localize('_QRYFILTER',getlocal()),      "qryfilter",	        GetParam("qryfilter"),				"forminput",	        40,				255,	0));	   	   	   
	 
	     //land pages
	     $form->addGroup		("lp",				"Land pages.");	   
         $form->addElement		("lp",			new form_element_radio		($this->localize('_CRTLANDPG',getlocal()),   "crtlandpg",      GetParam('crtlandpg'),             "",   2, array ("0" => localize('_OXI',getlocal()), "1" => localize('_NAI',getlocal()),localize('_EXISTS',getlocal()))));	   
         $form->addElement		("lp",			new form_element_radio		($this->localize('_LPNAME',getlocal()),   "lpname",      GetParam('lpname'),             "",   2, array ("0" => localize('Code',getlocal()), "1" => localize('Title',getlocal()))));		 
	     $form->addElement		("lp",			new form_element_text		($this->localize('_LPOUTPATH',getlocal()),      "lpoutpath",	        GetParam("lpoutpath"),				"forminput",	        20,				255,	0));	   
         $form->addElement		("lp",			new form_element_radio		($this->localize('_LPREDIR',getlocal()),   "lpredir",      GetParam('lpredir'),             "",   2, array ("0" => localize('_OXI',getlocal()), "1" => localize('_NAI',getlocal()))));
		 $form->addElement		("lp",			new form_element_radio		($this->localize('_SEOLINKS',getlocal()),   "seolinks",      GetParam('seolinks'),             "",   2, array ("0" => localize('_OXI',getlocal()), "1" => localize('_NAI',getlocal()))));
         $form->addElement		("lp",   		new form_element_radio		($this->localize('_SEOHIDDEN',getlocal()),   "seohidden",      GetParam('seohidden'),             "",   2, array ("0" => localize('_OXI',getlocal()), "1" => localize('_NAI',getlocal()))));		 
	     $form->addElement		("lp",			new form_element_text		($this->localize('_PRICEWITHTAX',getlocal()),      "pricewithtax",	        GetParam("pricewithtax"),				"forminput",	        20,				255,	0));	
	   }
	   
       if (IS_STANDALONE=='true') {//true	   	   
	     if (($this->source) && ($this->records_paid>abs($this->autobatch))) {//process limit bas in case -
           //$form->addGroup			("options",		"Process Options.");		   
	       //$form->addElement		("options",			new form_element_text		("Batch",     "batch",		GetParam('batch'),				"forminput",	        10,				50,	0));	   
           //$form->addElement		("options",			new form_element_text		("Refresh",     "refresh",		GetParam('refresh'),				"forminput",	        10,				50,	0));	   	   
           //$form->addElement		("options",		    new form_element_radio		($this->localize('_MERGEFILES',getlocal()),   "mergefiles",      GetParam('mergefiles'),             "",   2, array ("0" => localize('_OXI',getlocal()), "1" => localize('_NAI',getlocal()))));	   	   		 
		   $form->addElement		(FORM_GROUP_HIDDEN,	new form_element_hidden ("batch", $this->autobatch));//batch =1000		   
		   $form->addElement		(FORM_GROUP_HIDDEN,	new form_element_hidden ("refresh", 0));//no refresh		   
		   $form->addElement		(FORM_GROUP_HIDDEN,	new form_element_hidden ("mergefiles", 1));//no merging
		 }
	   }//false
	   else {
	     $form->addGroup		("options",		"Process Options.");		   
	     $form->addElement		("options",			new form_element_text		("Batch",     "batch",		GetParam('batch'),				"forminput",	        10,				50,	0));	   
         $form->addElement		("options",			new form_element_text		("Refresh",     "refresh",		GetParam('refresh'),				"forminput",	        10,				50,	0));	   	   
         $form->addElement		("options",		    new form_element_radio		($this->localize('_MERGEFILES',getlocal()),   "mergefiles",      GetParam('mergefiles'),             "",   2, array ("0" => localize('_OXI',getlocal()), "1" => localize('_NAI',getlocal()))));	   	   
         $form->addElement		("options",			new form_element_radio		($this->localize('_DOWNLOAD',getlocal()),   "download",      GetParam('download'),             "",   2, array ("0" => localize('_OXI',getlocal()), "1" => localize('_NAI',getlocal()))));	   	   
	   }
	   
	   if (defined('RECAPTCHA_DPC')) {
	     $form->addGroup		("recaptcha",		"Recaptcha.");		   
	     $form->addElement		("recaptcha",   	new form_element_recaptcha	("6Lf9BQgAAAAAAJSpMOxEcpSKCM9NAJa2KeCfHpvW"));
	   }	 


	   if ($action)
	     $form->addElement		(FORM_GROUP_HIDDEN,	new form_element_hidden ("FormAction", $action));
	   else
	     $form->addElement		(FORM_GROUP_HIDDEN,	new form_element_hidden ("FormAction", "doxmlexp"));

	   // Showing the form
	   $fout = $form->getform();


	   $out .= $fout;

       return ($out);
	 }	
	 
	function checkform() {
	      global $sFormErr;
	      $mybatch_id = GetReq('batchid');		  
		  
		  if ($mybatch_id)//means batch form
		    return (true);
	
	      $error = null;
		  $ret = true;//default true if recaptcha class miss
		  
		  //if no recaptcha return true by default
	      if (!defined('RECAPTCHA_DPC')) return true;
		  
          if ($_POST["recaptcha_response_field"]) {
            $resp = recaptcha_check_answer ("6Lf9BQgAAAAAANQJPxUpWq22D6x40bVgG7W_eC4u",
                                            $_SERVER["REMOTE_ADDR"],
                                            $_POST["recaptcha_challenge_field"],
                                            $_POST["recaptcha_response_field"]);
											
		    //if (is_array($resp)) {
			  											
              //print_r($resp);
              if ($resp->is_valid) {
                $error = null;//echo "You got it!";
				$ret = true;
              } else {
                # set the error code so that we can display it
                $error = $resp->error;
				$ret = false;
		        $this->msg .= '<br>' . "Incorrect recaptcha entry!";				
              }
			/*}
			else {
			  $ret = false;
		      $this->msg .= '<br>' . "Incorrect recaptcha entry!";			  			  
			} */ 
		  }
		  else {
		    $ret = false;
		    $this->msg .= '<br>' . "Recaptcha entry required!";			  
		  }
		  //$sFormErr = $error;
		  //echo '>',$error,'-',$ret;
		  return ($ret);		
	} 	 
	 
    function form_next_batch($title=null,$cmd=null,$width=null) {
	   $mybatch_id = GetReq('batchid');
	   $bid = $mybatch_id?($mybatch_id+1):1;  
	   
       $mycmd = $cmd?$cmd:'doxmlexp';
	   $mywidth = $width?$width:'70%';	   
	   
       $filename = seturl("t=$mycmd&batchid=".$bid);	   
	   
       $out .= setError($sFormErr . $this->msg);	   
   
	   //params form
       $out  .= "<FORM action=". "$filename" . " method=post class=\"thin\">";
       $out .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>"; 			 	       
		  		 
       $out .= "<input type=\"hidden\" name=\"FormName\" value=\"$mycmd\">"; 	
       $out .= "<input type=\"hidden\" name=\"xmlfilename\" value=\"".GetParam('xmlfilename')."\">";
       $out .= "<input type=\"hidden\" name=\"xmllang\" value=\"".GetParam('xmllang')."\">";	    			  
       $out .= "<input type=\"hidden\" name=\"xmltype\" value=\"".GetParam('xmltype')."\">"; 			  
       $out .= "<input type=\"hidden\" name=\"xmloutpath\" value=\"".GetParam('xmloutpath')."\">"; 	
       $out .= "<input type=\"hidden\" name=\"xmlhead\" value=\"".GetParam('xmlhead')."\">";		   		  	   
       if (IS_STANDALONE=='true') {	   	   
   	     if (defined('SHKATALOG_DPC')) {//sql where
           $out .= "<input type=\"hidden\" name=\"qrywhere\" value=\"".GetParam('qrywhere')."\">"; 			  		  
           $out .= "<input type=\"hidden\" name=\"qryfilter\" value=\"".GetParam('qryfilter')."\">";			 
	     }
		 elseif (defined('DATABASE_DPC')) {//new conn
           if (($this->source) && (!$this->is_uploaded_db($this->source))) {//only db conn		 
             $out .= "<input type=\"hidden\" name=\"dbconn\" value=\"".GetParam('dbconn')."\">"; 
             $out .= "<input type=\"hidden\" name=\"dbhost\" value=\"".GetParam('dbhost')."\">"; 			  		  
             $out .= "<input type=\"hidden\" name=\"dbname\" value=\"".GetParam('dbname')."\">";			   			  	   	   	   	   
             $out .= "<input type=\"hidden\" name=\"dbuser\" value=\"".GetParam('dbuser')."\">"; 			  		  
             $out .= "<input type=\"hidden\" name=\"dbpass\" value=\"".GetParam('dbpass')."\">";
		     $out .= "<input type=\"hidden\" name=\"key\" value=\"".$this->key."\">";			   
             $out .= "<input type=\"hidden\" name=\"records_paid\" value=\"".$this->records_paid."\">";			 
		   }	 		   
		   elseif (($this->source) && ($this->is_uploaded_db($this->source))) {	 //only upload data
             $out .= "<input type=\"hidden\" name=\"dbupload\" value=\"".GetParam('dbupload')."\">";			   
             $out .= "<input type=\"hidden\" name=\"dbupdsep\" value=\"".GetParam('dbupdsep')."\">";		   
		     $out .= "<input type=\"hidden\" name=\"key\" value=\"".$this->key."\">";			   
             $out .= "<input type=\"hidden\" name=\"records_paid\" value=\"".$this->records_paid."\">";			 
             $out .= "<input type=\"hidden\" name=\"utmpfile\" value=\"".$this->utmpfile."\">";			 			 
		   }
		   else {//all
             $out .= "<input type=\"hidden\" name=\"dbconn\" value=\"".GetParam('dbconn')."\">"; 
             $out .= "<input type=\"hidden\" name=\"dbhost\" value=\"".GetParam('dbhost')."\">"; 			  		  
             $out .= "<input type=\"hidden\" name=\"dbname\" value=\"".GetParam('dbname')."\">";			   			  	   	   	   	   
             $out .= "<input type=\"hidden\" name=\"dbuser\" value=\"".GetParam('dbuser')."\">"; 			  		  
             $out .= "<input type=\"hidden\" name=\"dbpass\" value=\"".GetParam('dbpass')."\">";		   
             $out .= "<input type=\"hidden\" name=\"dbupload\" value=\"".GetParam('dbupload')."\">";			   
             $out .= "<input type=\"hidden\" name=\"dbupdsep\" value=\"".GetParam('dbupdsep')."\">";		   			 		   
		   }	 
		 }  
		 else {//upload file
           $out .= "<input type=\"hidden\" name=\"dbupload\" value=\"".GetParam('dbupload')."\">";	
           $out .= "<input type=\"hidden\" name=\"dbupdsep\" value=\"".GetParam('dbupdsep')."\">";			   		 
		   $out .= "<input type=\"hidden\" name=\"key\" value=\"".$this->key."\">";			   
           $out .= "<input type=\"hidden\" name=\"records_paid\" value=\"".$this->records_paid."\">";		   			 		   
		 }
	   }
	   else {//landp and sql where
         $out .= "<input type=\"hidden\" name=\"qrywhere\" value=\"".GetParam('qrywhere')."\">"; 			  		  
         $out .= "<input type=\"hidden\" name=\"qryfilter\" value=\"".GetParam('qryfilter')."\">";		   
         $out .= "<input type=\"hidden\" name=\"crtlandpg\" value=\"".GetParam('crtlandpg')."\">"; 			  
         $out .= "<input type=\"hidden\" name=\"lpname\" value=\"".GetParam('lpname')."\">"; 				 
         $out .= "<input type=\"hidden\" name=\"lpoutpath\" value=\"".GetParam('lpoutpath')."\">"; 			  	   	   	   	   
         $out .= "<input type=\"hidden\" name=\"lpredir\" value=\"".GetParam('lpredir')."\">"; 	   
         $out .= "<input type=\"hidden\" name=\"seolinks\" value=\"".GetParam('seolinks')."\">"; 		 
         $out .= "<input type=\"hidden\" name=\"seohidden\" value=\"".GetParam('seohidden')."\">"; 			 
         $out .= "<input type=\"hidden\" name=\"pricewithtax\" value=\"".GetParam('pricewithtax')."\">";		 
	   }
       $out .= "<input type=\"hidden\" name=\"batch\" value=\"".GetParam('batch')."\">"; 			  		  
       $out .= "<input type=\"hidden\" name=\"refresh\" value=\"".GetParam('refresh')."\">";		  
       $out .= "<input type=\"hidden\" name=\"mergefiles\" value=\"".GetParam('mergefiles')."\">";
       $out .= "<input type=\"hidden\" name=\"download\" value=\"".GetParam('download')."\">";		   		  	   
		  
       $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"$mycmd\">&nbsp;";			
			
       $out .= "<input type=\"submit\" name=\"Submit\" value=\"Next batch....\">";		
       $out .= "</FONT></FORM>"; 	  	
		
	   $wina = new window($this->title,$out);
	   $winout .= $wina->render("center::$mywidth::0::group_dir_body::left::0::0::");
	   unset ($wina);		  		  
		
	   return ($winout);  	   
    }	 

    function do_xml_process() {
          //print_r($_POST);
          $xmltype = GetParam('xmltype');
		  
		  if (!$xmltype) {
		    $ret .= "<br><br>".$this->localize('_ERRXMLTYPE',getlocal());
			return ($ret);
		  }	
		  
          switch ($xmltype) {
            case 'Skroutz' : $ret = '<br>Skroutz.gr format';
                             if ($r = $this->create_xml('skroutz'))
                               $ret .= "<br><br>".$this->localize('_SUCCESS',getlocal());
                             else
                               $ret .= "<br><br>".$this->localize('_ERROR',getlocal()); 
                             break;
			case 'RSS 1.0' : //echo 'rss1';
	   					     break; 								
			case 'RSS 2.0' : $ret = '<br>RSS 2.0 format';
                             if ($r = $this->create_xml('rss2','utf-8'))
                               $ret .= "<br><br>".$this->localize('_SUCCESS',getlocal());
                             else
                               $ret .= "<br><br>".$this->localize('_ERROR',getlocal()); 
	   					     break; 
			case 'ATOM'    : //echo 'atom';
                             $ret = '<br>ATOM format';
                             if ($r = $this->create_xml('atom','utf-8'))
                               $ret .= "<br><br>".$this->localize('_SUCCESS',getlocal());
                             else
                               $ret .= "<br><br>".$this->localize('_ERROR',getlocal()); 
	   					     break; 			
	   					     break; 							 
            default : $ret = '<br>Default XML format';
			                 if ($r = $this->create_xml())
                               $ret .= "<br><br>".$this->localize('_SUCCESS',getlocal());
                             else
                               $ret .= "<br><br>".$this->localize('_ERROR',getlocal());
          }

          return ($ret);
    }

    function create_xml($format=null,$encoding=null,$decimal_point=null) {
	      $headers = GetParam('xmlhead');		   
          //batch proccesing
	      $batch = GetParam('batch');	   
          $bid = GetReq('batchid')?GetReq('batchid'):0;		  
		  //lang
	      $lan = GetParam('xmllang');
	      $itmname = $lan?'itmname':'itmfname';
	      $itmdescr = $lan?'itmdescr':'itmfdescr';		  
		 
	      $create_landpage = GetParam('crtlandpg')==1?true:false;		 
	      $exist_landpage = GetParam('crtlandpg')==2?true:false;		  
	      $landpage_redir = GetParam('lpredir');	
		  $ptax = GetParam('pricewithtax'); //echo $ptax;		  

		  
          $xml = new pxml();//'test');
		  if ($encoding)
		    $xml->encoding = $encoding;

	      //$xml->addtag('skroutzstore',null,null,"url=$xml->urlbase|name=$xml->urltitle|encoding=$xml->charset");							
	      //$xml->addtag('products','skroutzstore',null,null);
		  $this->xml_formater($xml,$format,1);
		  
		  //$xml->dumpxml();
		  
		  //if ($this->result) {
	      if (count($this->result)>0) {	
		  
	        if ($batch>0) {//read all sql data every time
			   //echo $bid,':';		  
			   //fetch all data
   			   if (!empty($this->result)) {
			     foreach ($this->result as $n=>$rec) 
			       $records[] = $rec;
			   }	 
               $datalines = array_chunk($records,$batch,true);
	           $current_batch = $datalines[$bid];
	           //print_r($current_batch);
	           $this->current_batch_records = count($current_batch);			
			   
	           //auto refresh
	           $refresh = GetParam('refresh');
	           if (($refresh>0) && ($this->current_batch_records==$batch))
                 $this->refresh_bulk_js(seturl('t=doxmlexp&batchid='.($bid+1).'&batch='.$batch.'&refresh='.$refresh),$refresh);				  	  			   
		    }
	        elseif ($batch<0) {//read limited sql data	
			   //fetch all data
   			   if (!empty($this->result)) {
			     foreach ($this->result as $n=>$rec) 
			       $current_batch[] = $rec;			
			   }	   
			
	           //$current_batch = & $this->result;	//one batch (sql limit)			
	           //print_r($current_batch);
	           $this->current_batch_records = count($current_batch);//			$this->max_items;//count($current_batch);			
			   
	           //auto refresh
	           $refresh = GetParam('refresh');
	           if (($refresh>0) && ($this->current_batch_records==abs($batch)))
                 $this->refresh_bulk_js(seturl('t=doxmlexp&batchid='.($bid+1).'&batch='.$batch.'&refresh='.$refresh),$refresh);				  	  			   					
			}
			else {
	           //$current_batch =  & $this->result;	//one batch			
			   //fetch all data
			   if (!empty($this->result)) {
			     foreach ($this->result as $n=>$rec) 
			       $current_batch[] = $rec;
			   }	   			   
	           //print_r($current_batch);			   
			}
			
			
			//DISABLED DUE TO NON INHERITANCE FROM SHLATALOG
		    /*if ($create_landpage) {//all in one page..never redir
			   //$this->read_item(null,$rec[$this->getmapf('code')]);//...use current result?????
			   $htmldata = $this->list_katalog_table(2,null,null,null,null,null,null,null,$_POST['xmllang']);//parent class procedure to create html data
			   $lp = $this->save_landpage(null,$htmldata);
			}*/			
	        
			$i = 0;
			$j = 0;
			
            if ((IS_STANDALONE=='false') && (defined('SHKATALOG_DPC'))) {				
		      //$pp = $this->read_policy();	
			  $pp = GetGlobal('controller')->calldpc_method("shkatalog.read_policy");   	
			  
			  //clear seolinks if any...
			  if (defined('RCLANDPAGE_DPC'))
			    GetGlobal('controller')->calldpc_method("rclandpage.delete_seo_links");  
			  else
			    $this->delete_seo_links();				
			}  
		    
			if (is_array($current_batch)) {
	          foreach ($current_batch as $n=>$rec) {
			 
               //if (IS_STANDALONE=='false') {				 
               if ((IS_STANDALONE=='false') && (defined('SHKATALOG_DPC'))) {				   
                 //$cat = $this->getkategories($rec);		
				 //print_r($rec);
			     //$cat = GetGlobal('controller')->calldpc_method("shkatalog.getkategories use ".$rec); //!!! DIDNT WORK
				 /////////////////////////////////////////////////
                 if ($rec['cat0'])
                      $ck[0] = str_replace(' ','_',$rec['cat0']);
                 if ($rec['cat1'])
                      $ck[1] = str_replace(' ','_',$rec['cat1']);
                 if ($rec['cat2'])
                      $ck[2] = str_replace(' ','_',$rec['cat2']);
                 if ($rec['cat3'])
                      $ck[3] = str_replace(' ','_',$rec['cat3']);
                 if ($rec['cat4'])
                      $ck[4] = str_replace(' ','_',$rec['cat4']);
                 if (!empty($ck))
                   $cat = implode('^',$ck);		
				 //echo $cat,'<br>';  		 
				 ///////////////////////////////////////////////////  
			   
			     //echo $rec['price0'],'>';
		         if ($rec[$pp]>0) { //check price...
		           //$myp = $this->spt($rec[$pp]);
				   $myp = GetGlobal('controller')->calldpc_method("shkatalog.spt use ".$rec[$pp]."+".$ptax);   
				   //echo $ptax,$myp;
				   if ($decimal_point)
		             $price = number_format($myp,2,$decimal_point,'.');
				   else
				     $price = number_format($myp,2); 	 
			       //echo $price,'>';
				   
				   $lpname = str_replace('^','-',str_replace('_',' ',$cat)) . '-' . $rec[$itmname];
				   $title = GetParam('lpname')?$lpname:$rec[$this->getmapf];				   
				   $item_lp_url = 'http://' . $this->url . $this->get_xml_item_url($title,$cat,$rec);	
				   
				   $myiurl = GetGlobal('controller')->calldpc_method("shkatalog.get_item_url use ".$rec[$this->getmapf].'+'.$cat);	   			      		   
				   $item_url = 'http://' . $this->url . $this->inpath . '/' . $myiurl;//$this->get_item_url($rec[$this->getmapf],$cat);
				   $mypurl = GetGlobal('controller')->calldpc_method("shkatalog.get_photo_url use ".$rec[$this->getmapf]);	   			      		   				   
				   $item_photo_url = 'http://' . $this->url .  $mypurl;//$this->get_photo_url($rec[$this->getmapf]);
				   
				   
		           $p[] = $rec[$this->getmapf];
			       $p[] = $rec[$itmname];
                   $p[] = $item_lp_url;			   
			       $p[] = $cat;
			       $p[] = $rec[$itmdescr];
			       $p[] = $item_photo_url;
			       $p[] = $price;
				   $p[] = $rec['cat0'];
				   $p[] = $rec['cat1'];
				   $p[] = $rec['cat2'];
				   $p[] = $rec['cat3'];
				   $p[] = $rec['cat4'];				   				   				   				   
				   
			       $this->xml_formater($xml,$format,null,$p);
				   unset($p);	//holds record data to pass it at xml formater				  
				    
				   //echo $rec['itmname'],$title,'?<br>';
				   //print_r($rec);
				   if ($create_landpage) {//for every page
				     //$this->read_item(null,$rec[$this->getmapf]);//...use cutrrent result if inheritance
				     //$htmldata = $this->show_item(null,1,$_POST['xmllang']);//parent class procedure to create html data
					 
					 GetGlobal('controller')->calldpc_method("shkatalog.read_item use +".$rec[$this->getmapf]);//non inheritance	
					 //echo $item_url,' ',$cat,'<br>';				 
					 $htmldata = GetGlobal('controller')->calldpc_method("shkatalog.show_item use +1+".$_POST['xmllang'].'+'.$item_url.'+'.$cat.'+1+'.$ptax);
					 
			         if (defined('RCLANDPAGE_DPC')) {
					   $mydata = serialize(encode($htmldata));
			           GetGlobal('controller')->calldpc_method("rclandpage.save_landpage use $title+$mydata+$landpage_redir+".$rec[$itmname].'+'.$rec[$itmdescr].'+'.$item_url.'+'.GetParam('xmllang').'+'.GetParam('lpoutpath'));  
			           GetGlobal('controller')->calldpc_method("rclandpage.save_seo use $title++".$create_landpage.'+'.GetParam('xmllang').'+'.GetParam('lpoutpath')); 					   
					 }  
			         else {
				     $lp = $this->save_landpage($title,
					                            $htmldata,
					                            $landpage_redir,
					                            $rec[$itmname],
												$rec[$itmdescr],
												$item_url);
					 $this->start_seo_hidden();							
			         $this->save_seo_links($title, null, $create_landpage);//url2go = landpage												
					 $this->end_seo_hidden();
					 }					 
				   }
				   else {
			         if (defined('RCLANDPAGE_DPC')) {
			           GetGlobal('controller')->calldpc_method("rclandpage.delete_seo_links");  
					 }  
			         else {				   
					 $this->start_seo_hidden();					   
				     $this->save_seo_links($title, $item_url, $exist_landpage);//url2go = php page	
					 $this->end_seo_hidden();							 		   	 
					 }
				   }	 
			 
			       $i+=1;	
				 }//price check  		   				   
		       }
			   elseif (defined('DATABASE_DPC')) {//stand alone external record set
			     if (is_array($rec)) {
				   foreach ($rec as $r_id=>$r_data) {
				     if (is_numeric($r_id))
				       $p[] = $r_data;
				   }
			       $this->xml_formater($xml,$format,null,$p);
				   unset($p);
				   				   
				   $i+=1;
				 }
			   }
			   else {//uploaded file
			     //...no need
			   }
			   $j+=1; 
		      }//foreach
			}//if
			$this->msg .= '<br>' . $j . ' record(s) procceded!'; 		   		  
			$this->msg .= '<br>' . $i . ' record(s) exported!';
			$retdata = true;
		    $this->records_done = $j;
			
			//landpage msg
		    if (($create_landpage) && ($lp==false))
		      $this->msg .= '<br> Landpage creation error.';
			elseif (($create_landpage) && ($lp==true))
			  $this->msg .= '<br> Landpage creation terminated successfully.';
			   		   		  
		  }
          else { //example
            //product loop xml tags 
			//$this->xml_formater($xml,$format);
			$retdata = false;
		  }
          //echo '<pre>';
		  //$xml->dumpxml();
		  //print_r($xml->index);
		  //echo '</pre>';
			 
		  
		  if ($retdata) {					
	        $xml_ret = $xml->getxml($headers);
            //$this->msg .= '<br>' . $xml_ret;
            //echo $xml_ret;

	        if ($ret = $this->save2disk($format,$xml_ret,$bid)) {
              return true;
			}  
            else
              return false;
		  }
		  
		  return false;	  
    }   
	   
	function xml_formater(& $xml,$format=null,$init=null,$params=null,$encoding=null) {
	
	      $date = date('m-d-Y');
	   
	      if ($init) {
		  
		     if ($encoding)
			   $enc = $encoding;
			 else
			   $enc = $xml->charset;

             switch ($format) {
	           case 'skroutz' : $enc ='utf8';
			                    $xml->addtag('skroutzstore',null,null,"url=$this->url|name=$xml->urltitle|encoding=$enc");							
	                            $xml->addtag('products','skroutzstore',null,null);
								break;
			   case 'rss1'    : echo 'rss1';
	   					        break; 								
			   case 'rss2'    : $enc ='utf-8';
			                    $xml->addtag('rss',null,null,"version=2.0");							
	                            $xml->addtag('channel','rss',$xml->urltitle,null);
	                            $xml->addtag('title','channel',$xml->urltitle,null);								
	                            $xml->addtag('link','channel',null,null);									
	                            $xml->addtag('description','channel',null,null);									
	                            $xml->addtag('language','channel',null,null);									
	                            $xml->addtag('pubDate','channel',null,null);									
	                            $xml->addtag('lastBuildDate','channel',null,null);	
	                            $xml->addtag('docs','channel',null,null);																	
	                            $xml->addtag('generator','channel','stereobit.networlds PHPDAC 2.0',null);									
	                            $xml->addtag('managingEditor','channel',$xml->urltitle,null);									
	                            $xml->addtag('webMaster','channel',null,null);									
	                            $xml->addtag('ttl','channel',null,null);									
	   					        break; 
			   case 'atom'    : $enc ='utf-8';
			                    $xml->addtag('feed',null,null,"xmlns=http//www.w3.org/2005/Atom");							
	                            $xml->addtag('title','feed',$xml->urltitle,null);
	                            $xml->addtag('subtitle','feed',null,null);								
	                            $xml->addtag('link','feed','/',"href=http://".$this->url."/".$_POST['xmloutpath']."/|rel=self");									
	                            $xml->addtag('link','feed','/',"href=http://".$this->url);									
	                            $xml->addtag('id','feed',null,null);									
	                            $xml->addtag('updated','feed',null,null);									
	                            $xml->addtag('author','feed',$xml->urltitle,null);	
	                            $xml->addtag('name','author',$xml->urltitle,null);																	
	                            $xml->addtag('email','author',null,null);									
	   					        break; 								
               default        : $xml->addtag('default-xml',null,null,"url=$this->url|name=$xml->urltitle|encoding=$enc");							
	                            $xml->addtag('products','default-xml',null,null);
												
		     }		  
		     return 1;
		  }
		  else {
             //product loop xml tags 		  
             switch ($format) {
	           case 'skroutz' : 
			   $cats = explode('^',$params[3]);	
			   $manufacturer = str_replace('_',' ',array_shift($cats));
			   $category = str_replace('_',' ',$params[3]);
			   $category = str_replace('^','/',$category);
	           $xf = ($this->itemfimagex?$this->itemfimagex:640);
	           $yf = ($this->itemfimagey?$this->itemfimagey:480);	
	           $xt = ($this->imagex?$this->imagex:160);
	           $yt = ($this->imagey?$this->imagey:120);				   		   
			   	  
               $xml->addtag('product','products',null,"id=".$params[0]);
			   
               $xml->addtag('name','product',$xml->cdata($params[1]),null);  //cdata val
               $xml->addtag('link','product',$params[2],null);  //http://... val
               $xml->addtag('price_with_vat','product',$params[6],null);  //price 11.11
               $xml->addtag('category','product',$xml->cdata($category),"id=".$params[3]); //cdata val = descr, id=code
               $xml->addtag('image','product',$params[5],"width=$xf|height=$yf");  //http://... image
               $xml->addtag('thumbnail','product',$params[5],"width=$xt|height=$yt");  //http://... thumbnail
               $xml->addtag('manufacturer','product',$xml->cdata($manufacturer),null);  //cdata val
               $xml->addtag('shipping','product',null,"type=accurate|currency=euro");  //ship cost 11.11
               $xml->addtag('sku','product','/',null);  //...
               $xml->addtag('ssku','product','/',null);  //...
               $xml->addtag('description','product',$xml->cdata($params[4]),null);  //cdata val		  
		       break;
			   case 'rss1'    : //echo 'rss1';
	   		   break; 								
			   case 'rss2'    : //echo 'rss2';
	           $xml->addtag('item','channel',null,null);				   
			   
               $xml->addtag('title','item',$params[1],null);				   
               $xml->addtag('link','item',$params[2],null);
               $xml->addtag('description','item',$params[4],null);				   			   				   			   
               $xml->addtag('pubDate','item',$date,null);				   			   
               $xml->addtag('guid','item',$params[0],null);			   
	           break; 
			   case 'atom'    : //echo 'atom';
	           $xml->addtag('entry','feed',null,null);				   
			   
               $xml->addtag('title','entry',$params[1],null);				   
               $xml->addtag('link','entry',$params[2],null);
               $xml->addtag('id','entry',$params[0],null);				   			   				   			   
               $xml->addtag('updated','entry',$date,null);				   			   
               $xml->addtag('summary','entry',$params[4],null);				   
	   		   break; 
			   default ://seo links
               $xml->addtag('product','products',null,"id=");
			   
               $xml->addtag('name','product',$xml->cdata('name'),null);  //cdata val
               $xml->addtag('link','product',null,null);  //http://... val
               $xml->addtag('price_with_vat','product',null,null);  //price 11.11
               $xml->addtag('category','product',$xml->cdata('category'),"id=\"\""); //cdata val = descr, id=code
               $xml->addtag('image','product',null,"width=|height=");  //http://... image
               $xml->addtag('thumbnail','product',null,"width=|height=");  //http://... thumbnail
               $xml->addtag('manufacturer','product',$xml->cdata('manufacturer'),null);  //cdata val
               $xml->addtag('shipping','product',null,"type=|currency=");  //ship cost 11.11
               $xml->addtag('description','product',$xml->cdata('description'),null);  //cdata val		  			   
			 }
			 //dump xml  
			 //echo '<pre>';
			 //$xml->dumpxml();
			 //print_r($xml->index);
			 //echo '</pre>';
			 
		     return 1;
		  }
		  
		  return 0;
	}
	   
	function merge_xml_files() {
	      $bid = GetParam('batchid');
		  
          if (IS_STANDALONE=='false') {	
		     $fpath = $this->urlpath . '/' . $_POST['xmloutpath'];
             $filename = $_POST['xmlfilename']?$_POST['xmlfilename']:'xmlout.xml'; 
             $file = $fpath . '/' . $filename; 		  			  		  			 		
		  }
		  else {//true
		     $fpath = $this->path;
             $filename = $this->fn; 
             $file = $fpath . '/' . $filename; 		  			  		  			 					 
		  }	 

          $this->msg .= '<br> Merging files...';		  
		  
          if ($fd = @fopen($file, 'a+')) {		  		  
	     
		    for($i=1;$i<=$bid;$i++) {
		      $xmlfile2merge = str_replace('.xml',$i.'.xml',$filename);
              $xmlfile = $fpath . '/' . $xmlfile2merge; 		  			  
			  $data = file_get_contents($xmlfile);
			  
              fwrite($fd, $data);			  
		    }
            fclose($fd);
            $this->msg .= ' success!';			
            return true;			 
		  }
          $this->msg .= ' (can\'t open file for append)';		  
		  return false;
	}

    function save2disk($format,$data,$bid=null) {
         $download = GetParam('download');
	     $batch = GetParam('batch');		
		 
         if (IS_STANDALONE=='false') 
           $filename = $_POST['xmlfilename']?$_POST['xmlfilename']:'xmlout.xml'; 
		 else
		   $filename = $this->fn;  
		 
         //batch processing...many xml files
	     if ($bid) 
		   $fn = str_replace('.xml',$bid.'.xml',$filename);
	     else	//one file .. as is
		   $fn = $filename;
		   
         switch ($format) {
           case 'rss1'    : $xmlhead = "<?xml version=\"1.0\"?>"; break;  
		   case 'rss2'    : $xmlhead = "<?xml version=\"1.0\"?>"; break;  
		   case 'atom'    : $xmlhead = "<?xml version=\"1.0\" encoding=\"utf-8\"?>"; break;		   
	       case 'skroutz' : 
		   default        : $xmlhead = null;
         }
		 
		 $xmldata = $xmlhead . $data;
		 
		 if ($download) {
		   if (!$batch) {
             header('Content-Type: text/xml');
             echo $xmldata;
		     die();
		   }
		   else {//batch
	         if ((!$this->process_error) && ($batch) && ($this->current_batch_records==abs($batch)))	{
			   //in batch process
			   $ret = $this->save_file($fn,$xmldata);	   
			 }
			 else {
			   //last chunk of data
               $ret = $this->save_file($fn,$xmldata);			   
			   if (!$this->process_error) {
                 header('Content-Type: text/xml');
                 echo $xmldata;
		         die();			
			   }
			   //else nothing..goto form   	   
			 }  
		   }//batch
		 }
		 else //no download
           $ret = $this->save_file($fn,$xmldata);  
		   
		 return ($ret);     
    }
	   
	function save_file($fn,$xmldata=null) {
	
           if (IS_STANDALONE=='false') {	
			 $xmlpath = $this->urlpath . '/' . $_POST['xmloutpath'];		   
             $file = $xmlpath. '/' . $fn; 
             $this->msg .= '<br>' . $this->url . '/' . $_POST['xmloutpath'] . '/' . $fn; ;		
		   }
		   else {//true
			 $xmlpath = $this->path;		   
             $file = $xmlpath . '/'  . $fn; 
             $this->msg .= '<br>' .  $fn; ;				   
		   }	 
		 
		   if (is_dir($xmlpath)) {
	         //echo $file,$data;

             if ($fd = @fopen($file, 'w')) {
               fwrite($fd, $xmldata);
               fclose($fd);
               return true;
             }
		     $this->process_error = true;
		     $this->msg .= ' (can\'t open file)';
		     return false;
           }
		   else {  
		     $this->process_error = true;
		     $this->msg .= ' (invalid directory)';		
             return false;
		   }
		   
		   return 0;	   
	}
	   
	function read_data() {

	     if ((IS_STANDALONE=='false') && (defined('SHKATALOG_DPC'))) {			   
		   $ret = $this->read_db_data();   
		 }
		 elseif (defined('DATABASE_DPC')) {//optional upload file else db conn 
		   //print_r($_FILES);
		   if ( (($myupd = $_FILES['dbupload']) && ($myupd['name'])) || 
		        ((GetParam('dbupload')) && (GetParam('utmpfile'))) ) //batch upd file transfer 
		     $ret = $this->read_uploaded_data();  
		   else
		     $ret = $this->read_ext_db_data();
		 }  
		 else {//upload file
		   $ret = $this->read_uploaded_data();
		 }
		 
		 return $ret;
	}	   
	   
	function read_db_data() {
	      $db = GetGlobal('db');
		 
	      //$this->getmapf('code')
		  //get map f from shkatalog
		  $this->getmapf = GetGlobal('controller')->calldpc_method("shkatalog.getmapf use code");		
		  
		  //lang
	      $lan = GetParam('xmllang');
	      $itmname = $lan?'itmname':'itmfname';
	      $itmdescr = $lan?'itmdescr':'itmfdescr';
          $haswhere = GetParam('qrywhere');
		  $where = GetParam('qryfilter');
		  
		  $bid = GetParam('batchid')?GetParam('batchid'):0;
		  //echo '>',$bid;
		  
	      $sSQL = "select id,sysins,code1,pricepc,price2,sysins,$itmname,uniname1,uniname2,active,code4," .
	              "price0,price1,cat0,cat1,cat2,cat3,cat4,$itmdescr,itmremark,".$this->getmapf." from products ";
		  
		  if (($batch = GetParam('batch')) && ($batch<0)) {//limited data..>0 goes to read all data every time
		    $start = $bid * abs($batch);
			if ($haswhere)
			  $sSQL .= " WHERE ($where) and (itmactive>0 and active>0) limit $start," . abs($batch);
			else
		      $sSQL .= " WHERE itmactive>0 and active>0 limit $start," . abs($batch);
		  }
	      else {//all data
			if ($haswhere)
			  $sSQL .= " WHERE ($where) and (itmactive>0 and active>0) limit " . $this->sql_limit;
			else		  
		      $sSQL .= " WHERE itmactive>0 and active>0 limit " . $this->sql_limit;
		  }	
		 
		  //echo $sSQL;
		  
	      $resultset = $db->Execute($sSQL,2);	 
	   	  //print_r($resultset);  
	      $this->result = $resultset; 

          $m = $db->Affected_Rows();
 	      $this->max_items = $m?$m:0;
		  
		  return '<br>' . $this->max_items . ' record(s) readed successfully!';		  	   
	}
	   
	//read external db source
	function read_ext_db_data() {
		  
		  //lang
	      $lan = GetParam('xmllang');
	      $itmname = $lan?'itmname':'itmfname';
	      $itmdescr = $lan?'itmdescr':'itmfdescr';
          $haswhere = GetParam('qrywhere');
		  $where = GetParam('qryfilter');		  
		  
		  $bid = GetParam('batchid')?GetParam('batchid'):0;		  
		  
          //other mysql db at localhost
   	      if (defined('DATABASE_DPC')) {
	        if (defined(_ADODB_)) {
              $_Host = GetParam('dbhost'); 			
              $_Dbname = GetParam('dbname'); 
              $_User = GetParam('dbuser'); 
              $_Password = GetParam('dbpass'); 			  			  			  
	          $dbconn = GetParam('dbconn');		
			  
			  if (($_User) && ($_Dbname)) {
			  
			  switch ($dbconn) {				  
                case 9       : $db = ADONewConnection('odbc'); break;				  			  
			    case 8       : $db = ADONewConnection('ibase'); break;			  
			    case 7       : $db = ADONewConnection('sqlite'); break;			  
			    case 6       : $db = ADONewConnection('postgress'); break;				  
			    case 5       : $db = ADONewConnection('informix'); break;			  
			    case 4       : $db = ADONewConnection('mssql'); break;			  
			    case 3       : $db = ADONewConnection('oci8'); break;			  
			    case 2       : $db = ADONewConnection('oracle'); break;
			    case 1       : $db = ADONewConnection('mysql'); break;					
			    case 0       :
                default : $db = @ADONewConnection($_Dbtype);
				
			  }
			  
              @$db->PConnect($_Host, $_User, $_Password, $_Dbname);
		
		      ///////////////find extra records (limit plus)	  
			  $fsql = "select * from products";
		   	  if ($haswhere)
			    $fsql .= " WHERE ($where) and (itmactive>0 and active>0)";
			  else		  
		        $fsql .= " WHERE itmactive>0 and active>0";
				
	          $resultset = $db->Execute($fsql,2);	
              $set = $db->Affected_Rows(); //echo $fsql,$set;			   		   				
			  $this->records_todo = $set-$this->sql_limit;	
			  $this->goto_pay = true;
			  //////////////
			  			  
              //$sSQL = "SELECT * from products limit 10";
	          $sSQL = "select code5,$itmname,code1,cat1,$itmdescr,code1,price0,cat0,cat1,cat2,cat3,cat4" .
	                  " from products ";			  
			  //echo $sSQL;		  
		      if (($batch = GetParam('batch')) && ($batch<0)) {//limited data..>0 goes to read all data every time
		        $start = $bid * abs($batch);
			    if ($haswhere)
			      $sSQL .= " WHERE ($where) and (itmactive>0 and active>0) limit $start," . abs($batch);
			    else
		          $sSQL .= " WHERE itmactive>0 and active>0 limit $start," . abs($batch);
		      }
	          else {//all data
		   	    if ($haswhere)
			      $sSQL .= " WHERE ($where) and (itmactive>0 and active>0) limit " . $this->sql_limit;
			    else		  
		          $sSQL .= " WHERE itmactive>0 and active>0 limit " . $this->sql_limit;
		      }	
	          $resultset = $db->Execute($sSQL,2);	 
	   	      //print_r($resultset);  
	          $this->result = $resultset;			  
              $m = $db->Affected_Rows();	
 	          $this->max_items = $m?$m:0;		
			  if (!$m)
		        return '<br> Empty dataset. Database connection failed!';	
			  else	
		        return '<br>' . $this->max_items . ' record(s) readed successfully!';		
			  }
			  else 
			    return '<br>' . ' Invalid database connection!';			    	  		  		    	 		  
			}
			else
			  return '<br>' . ' Database driver not loaded!';

		  } 
		        	   
	}
	   
	function read_uploaded_data() {
		  $bid = GetParam('batchid')?GetParam('batchid'):0;		  		  	
          $sep = GetParam('dbupdsep');
		  $separator = $sep?$sep:',';
          $attachedfile = $_FILES['dbupload'];
		  //print_r($attachedfile);
		  
		  if ((!$attachedfile) && (GetParam('utmpfile'))) {//stored file
		    $filename = GetParam('dbupload');
			$tempfile = GetParam('utmpfile');
			$filetype = 'approved';
		  }
		  else {//normal upload
		    $filename = $attachedfile['name']; 
		    SetParam('dbupload',$filename);	
		  
		    $filetype = $attachedfile['type'];	
		    $filesize = $attachedfile['size'];		  
		    $tempfile = $attachedfile['tmp_name'];
			
            // save temp file
			$w = file_put_contents($this->path . 'xmltmp_'.mktime().'.tmp', file_get_contents($tempfile));			
			if ($w)
		      $this->utmpfile = $this->path . 'xmltmp_'.mktime().'.tmp';//real tmp file erase itself after script end?   $tempfile;//save param
			else
             $ret .= '<br>' . 'failed to store temporary file!';   			  							
		  }
		  
		  $fpart = explode('.',$filename);
		  $fext = strtolower(array_pop($fpart)); 
		  //echo $fext;				   
				   
		  //echo $tempfile,'>',is_file($tempfile);		   
		  if (@is_readable($tempfile)) {
		  
		    if (($filetype=='approved') || ($filetype=='text/plain') || ($filetype=='text/comma-separated-values') || ($filetype='application/vnd.ms-excel')) {
		      switch ($fext) {
			    case 'csv' : 
			    case 'txt' : //approved and .tmp for temp file
			    default    : $source = file($tempfile); //echo count($source),'>';
			  }	
			}
			elseif ($filetype=='text/xml') {
		      switch ($fext) {
			    case 'xml' :
			    default    : $source = file($tempfile);//...read xml   			  			  
			  }				   
			}
			else  
			  $source = null;
		    
		    if (!empty($source)) { 
			
			  foreach ($source as $irec=>$f) {
			  
			    if (GetParam('utmpfile')) {//or bid...means read payed recs...batch read limit calculate..batch always negative!!!
				   $start = $bid * abs($this->autobatch);//or sqllimit
				   $end = $start + abs($this->autobatch);
				   //echo $start,'-',$end;
				   if (($irec>=$start) && ($irec<$end)) {//0...999,1000...1999 etc
                     $this->result[] = explode($separator,$f);//$resultset;			  
                     $m += 1;				   
				   }
				}//default free recs
				else {//until limit read
	              $this->result[$irec] = explode($separator,$f);//$resultset;			  
                  $m += 1;//$db->Affected_Rows();	
				
				  if ($m>=$this->sql_limit) {//break limit
				    $this->goto_pay = true;
				    $this->records_todo = count($source)-$this->sql_limit;
				    break; 
				  }  
				}  
			  }
			  //print_r($this->result);	
 	          $this->max_items = $m?$m:0;			   
			  //echo '-----',$m;
    	      //$ret = '<br>' . "Upload " .$attachedfile['name']. " ok!";			
			}
			else
			  $ret = '<br>' . "Upload invalid file " .$attachedfile['name'];			
		  }
		  else 
		    $ret = '<br>' . "Failed to upload file " . $attachedfile['name'] . " ! (Size Error?)";
		  
		  return ($ret); 		    
	}
	
	function is_uploaded_db($source) {
	
	     if ( (strstr($source,'.xml')) || (strstr($source,'.csv')) || (strstr($source,'.txt')) )
		   return true;
		  
		 return false;   
	}
	   
	   
    function save_landpage($name=null,$data=null,$redirpage=null,$itmname=null,$itmdescr=null,$url=null) {
	     $lan = GetParam('xmllang');
         $fn = $name?str_replace('/','-',$name).$lan.'.html':'items.html'; 	   
	     $template="landpage$lan.htm";	   
		 //echo $redirpage,'?';
		 if ($redirpage>0) {
	       $hfp = new fronthtmlpage(str_replace("$lan.htm","_redir$lan.htm",$template));  
	       $html_data = $hfp->render($data);
	       unset($hfp);		
		   
		   $name = $itmname;
		   $keywrd = str_replace(' ',',',$itmname);
		   $htmllayer = $itmname . ' ' . $itmdescr;
		   $url2go = $url;
		   
		   //inhertiance func
		   //$html = $this->combine_template($html_data,$name,$keywrd,$htmllayer,$url2go);			   
		   //$html = GetGlobal('controller')->calldpc_method("shkatalog.combine_template use $html_data+$name+$keywrd+$htmllayer+$url2go");	  
		   //local func
           $html = $this->combine_template($html_data,$name,$keywrd,$htmllayer,$url2go);			   		   
		 }
		 else {	 
	       $hfp = new fronthtmlpage($template);  
	       $html = $hfp->render($data);
	       unset($hfp);		   
		 }
		   
         $file = $this->urlpath . '/' . $_POST['lpoutpath'] . '/' . $fn; 
	 
         //$this->msg .= '<br>' . $this->url . '/' . $_POST['xmloutpath'] . '/' . $fn; ;		
		 
		 if (is_dir($this->urlpath . '/' . $_POST['lpoutpath'])) {
	         //echo $file,'<br>';//,$data;

             if ($fd = @fopen($file, 'w')) {
               fwrite($fd, $html);
               fclose($fd);
			   
               return true;
             }
		     $this->process_error = true;
		     //$this->msg .= '<br> Landpage error. (can\'t open file)';
		     return false;
         }
		 else {  
		     $this->process_error = true;		 
		     //$this->msg .= '<br> Landpage error. (invalid directory)';		
             return false;
		 } 		     
    }	
	   
	function delete_seo_links() {
	      $lan = GetParam('xmllang');	   
          $seofile = $this->urlpath . '/' . $_POST['lpoutpath'] . '/seolinks'.$lan.'.txt';		
		  	   
		  if ((GetParam('seolinks')) && (is_readable($seofile))) {
		    @unlink($seofile);
			return true;
		  }	   
		  
		  return false;
	}
	
	function start_seo_hidden() {
		  $hidden = GetParam('seohidden');	
	      $lan = GetParam('xmllang');	   
          $seofile = $this->urlpath . '/' . $_POST['lpoutpath'] . '/seolinks'.$lan.'.txt';			  	
		  
		  if (($hidden) && ($fseo = @fopen($seofile, 'a+'))) {
                 fwrite($fseo, "<DIV id='seolinks' style='Z-INDEX:999; VISIBILITY:hidden; POSISION:absolut; TOP:-500px; LEFT:-500px; HEIGHT:500px; WIDTH:500px;'><p>");
                 fclose($fseo);			     
				 //echo $url2go,'<br>';
				 return true;		  
		  }
	}
	
	function end_seo_hidden() {
		  $hidden = GetParam('seohidden');		
	      $lan = GetParam('xmllang');	   
          $seofile = $this->urlpath . '/' . $_POST['lpoutpath'] . '/seolinks'.$lan.'.txt';	
		  
		  if (($hidden) && ($fseo = @fopen($seofile, 'a+'))) {
                 fwrite($fseo, "</p></DIV>");
                 fclose($fseo);			     
				 //echo $url2go,'<br>';
				 return true;		  
		  }		  		  
	}
	   
	function save_seo_links($name, $url2go=null, $is_lp_page=null) {
	      $lan = GetParam('xmllang');	   
          $seofile = $this->urlpath . '/' . $_POST['lpoutpath'] . '/seolinks'.$lan.'.txt';			        
	   
		  //LP SEO LINKS
		  if ((GetParam('seolinks')) && ($fseo = @fopen($seofile, 'a+'))) {
		  
		         if ($is_lp_page) {
				   //override url
				   $url2go = /*$this->url . '/' .*/ $_POST['lpoutpath'] . '/' . str_replace('/','-',$name) . $lan . '.html';	
				   $seolnk = "<A href=\"$url2go\">".$name."</A>&nbsp;";
				 }  
				 else
			       $seolnk = "<A href=\"$url2go\">".$name."</A>&nbsp;";
				   
                 fwrite($fseo, $seolnk);
                 fclose($fseo);			     
				 //echo $url2go,'<br>';
				 return true;
		   }
			   
		   return false;	   
	} 
	   
	function make_combo($values,$title=null,$selection=null,$size=null,$addtitle=null) {
	
        $mysize = $size != 0 ? "size=\"".$size."\"" : "" ;
		$r = "<select name=\"".$name."\" class=\"".$style."\"". $mysize .
		//" onChange=\"sndReqArg('index.php?t=$t&s1=CANON','combo2')\">";
			  " onChange=\"location=this.options[this.selectedIndex].value\">";
			  
		if (!empty($values) && ($title)) 	  
		  $r .= "<option value=''>$title</option>";	
		  
		  
		foreach ($values as $i=>$v) {
		
            $hide_url = "javascript:expand('show_$v');contract('hide_$v');contract('$v')";
            $show_url = "javascript:expand('$v');expand('hide_$v');contract('show_$v')";
			
			if ($i!=$selection)
			  $hide_all .= str_replace('$v',$v,"expand('show_$v');contract('hide_$v');contract('$v')");		
		
		    $myvalue = $show_url;// . $hide_all; 
			$r .= "<option value=\"$myvalue\"".($i == $selection ? " selected" : "").">$v</option>";		
		}  
		
		$r .= "</select>";
				
		return ($r);  
	}	   
	   
	function combine_template($template_contents,$p0=null,$p1=null,$p2=null,$p3=null,$p4=null,$p5=null,$p6=null,$p7=null,$p8=null,$p9=null) {
	
		$params = explode('<#>',"$p0<#>$p1<#>$p2<#>$p3<#>$p4<#>$p5<#>$p6<#>$p7<#>$p8<#>$p9");
	    //print_r($params);
		
		if (defined('FRONTHTMLPAGE_DPC')) {
		  $fp = new fronthtmlpage(null);
		  $ret = $fp->process_commands($template_contents);
		  unset ($fp);
          //$ret = GetGlobal('controller')->calldpc_method("fronthtmlpage.process_commands use ".$template_contents);		  		
		}		  		
		else
		  $ret = $template_contents;
		//echo $ret;
	    foreach ($params as $p=>$pp) {
		  if ($pp)
	        $ret = str_replace("$".$p,$pp,$ret);
		  else	
		    $ret = str_replace("$".$p,'',$ret);
	    }
		//echo $ret;
		return ($ret);
	}		   
	   
	   
   function get_xml_item_url($code,$cat=null,$rec=null) {
	 $create_landpage = GetParam('crtlandpg')==1?true:false;		 
	 $exist_landpage = GetParam('crtlandpg')==2?true:false;	    
	 $lan = GetParam('xmllang');
     
	 if (($create_landpage) || ($exist_landpage)) {
	 
	   $ret = $_POST['lpoutpath'] . '/' . str_replace('/','-',$code) . $lan . '.html';
	 }
     else {
       if ((IS_STANDALONE=='false') && (defined('SHKATALOG_DPC')))  
         //$ret = $this->inpath . '/' . $this->get_item_url($rec[$this->getmapf],$cat);	
		 $ret = GetGlobal('controller')->calldpc_method("shkatalog.get_item_url use ".$rec[$this->getmapf].'+'.$cat);	   			      
	  	 
	 }  
	   
	 return ($ret);  
   }	      
	   
   //refresh to continue bulk proccess automaticaly
   function javascript($page,$timeout=null) {
   
     $mytimeout=$timeout?$timeout:100;
   
     $ret = "
function neu()
{	
	top.frames.location.href = \"$page\"
}
window.setTimeout(\"neu()\",$mytimeout);
";
	 
	 return ($ret);
   }		   
	   
   function refresh_bulk_js($page,$timeout=null) {
   
      if (iniload('JAVASCRIPT')) {

	       $code = $this->javascript($page,$timeout);
	   
		   $js = new jscript;
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }   
   } 
   
  //override global func for standalone 
  function localize($param,$lan) {

    if (IS_STANDALONE=='false') {
	  $ret = localize($param,$lan);
	  return $ret;
	}  
	else {
      //$locale =  GetGlobal('__LOCALE');	
	  global $__LOCALE;
	  //echo '<pre>';
	  //print_r($__LOCALE);
	  //echo '</pre>';
	  foreach ($__LOCALE['RCXMLEXP_DPC'] as $i=>$word) {
	    //echo $i,'<br>';
	    //if (is_array($dpc)) {
        //foreach ($dpc as $j->$word) {
		  $r = explode(';',$word);
		  if ($r[0]==$param) { 
		   $ret = $r[$lan+1];
		   $r = @mb_convert_encoding($ret,$this->t_enc,$this->s_enc);//iconv
		   //return (utf8_encode($ret));
		   return $r;
		  } 
		//}
		//} 
	  }	
	  return $param;//as is
	}    
  }
  
  
  function instand_download() {
  
       $name = $_POST['xmlfilename']?$_POST['xmlfilename']:'xmlout.xml';   
       $file = $file = $this->urlpath . '/' . $_POST['xmloutpath'] . '/' . $name;	   
	   //$file = "c:\\php\\webos2\\projects\\re-coding-official\\demo\\delphi2java_shareware.zip";
	   //echo "DOWNLOAD:",$file;
	   //die();
       $downloadfile = new DOWNLOADFILE($file);
	   

		 
	   //inform bt mail and sms
	   //$this->send_downloaded_mail($product_id);
	   
       if (!$downloadfile->df_download()) {
	     //echo "Sorry, we are experiencing technical difficulties downloading this file. Please report this error to Technical Support.";	   	   
		$m = remote_paramload('RCXMLEXP','filednerror',$this->path);	
		$ff = $this->path.$m;
		if (is_file($ff)) {
		  $ret = file_get_contents($ff);
		}
		else
		  $ret = $m; //plain text		 
	   }
	   
	   return $ret;	   	
  }  
  
  function download_link($wintitle=null,$wintext1=null,$wintext2=null) {
       $bid = GetParam('batchid');
	   $mergefiles = GetParam('mergefiles');
  
       if (IS_STANDALONE=='false') {	
          $name = $_POST['xmlfilename']?$_POST['xmlfilename']:'xmlout.xml';   
          $file = $this->urlpath . '/' . $_POST['xmloutpath'] . '/' . $name;			   
	   }
	   else {//true
          $name = $this->fn;   
          $file = $this->path . '/' . $name; //echo $file;
       }
	   
	   $title = $wintitle?$wintitle:'Download file';		      
	   
	   if (($bid) && (!$mergefiles)) {
	     //..multiple files must download
		 for ($i=1;$i<=$bid;$i++) {
		   $file_i = str_replace('.xml',$i.'.xml',$file);
		   SetSessionParam('HTTPDOWNLOADFILE'.$i,$file);   
		   $ret .= GetGlobal('controller')->calldpc_method('httpdownload.set_download');// use NRSPEED');
		   $ret .= '<br>';
		 }
	   }
	   else {
         SetSessionParam('HTTPDOWNLOADFILE',$file);//one file???	   
  
         if (is_readable($file)) {  
           //$d = new httpdownload($file);
	       //$ret = $d->select_download_type();	   
		   //$ret = GetGlobal('controller')->calldpc_method('httpdownload.select_download_type');
		   $ret = GetGlobal('controller')->calldpc_method('httpdownload.set_download');// use NRSPEED');
	     }
	     else
	       $ret = "ERROR:file not exist!";	 
	   }
	   
	   //message to say	 
       $this->say = 'Type:'.$this->target.' from '.$this->source . ', ';
	   if ($this->records_todo) 
	      $this->say .= ($this->records_done?$this->records_done:0) . ' records done' . ', '. 
					    ($this->records_todo?$this->records_todo:0) . ' records todo';	   
	   else
	      $this->say .= ($this->records_done?$this->records_done:0) . ' records done' . ', '. 
					    ($this->records_paid?$this->records_paid:0) . ' records paid';
							   
       //inform by mail and sms
	   $this->tell_by_mail('XML file downloaded','orders@stereobit.networlds.org','sales@stereobit.com',$this->say);	   
	   
	   $w = new window($title,$wintext1."<h2>".$ret."</h2>".$wintext2);//$link);
	   $out = $w->render();
	   unset($w);	

	   return ($out);
  }  
  
  function tell_by_mail($subject,$from,$to,$body) {
         

         $smtpm = new smtpmail;
         $smtpm->to = $to; 
         $smtpm->from = $from; 
         $smtpm->subject = $subject;
         $smtpm->body = $body;
         $mailerror = $smtpm->smtpsend();
         unset($smtpm);	
		 
		 if ($mailerror) echo "Error sending mail! ($mailerror)";
		 return ($mailerror);   
  } 
  
  function tell_by_sms($message) {
	
	    if (defined('SMSGUI_DPC'))
	      $ret = GetGlobal('controller')->calldpc_method('smsgui.sendsms use '.$message);		
  }   	      	   
  
  function show_help() {
  
		   if ($this->source) {//means return from a payment	 
              if (is_readable($this->path . 'paid_instr.html'))
			     $text = @file_get_contents($this->path . 'paid_instr.html');
			  else
			     $text = 'after pay instructions';
				 	 
              $win = new window2('return from a pay',$text,null,1,null,'SHOW',1);//,1);
	          $out .= $win->render("center::95%::0::group_article_selected::left::0::0::");
	          unset ($win);		   
		   }
		/*   else {//no source = first time
	         $out .= $this->make_combo($this->dbtype);
           
             $win = new window2("Select Database",'Default instructions',null,1,null,'SHOW',1);//,1);
	         $out .= $win->render("center::95%::0::group_article_selected::left::0::0::");
	         unset ($win);
		   
		     foreach ($this->dbtype as $dbt=>$dbn) {		   
		       if ($dbt>0) {//bypass default =select db
			     if (is_readable($this->path . $dbn . '_instr.html'))
			       $text = @file_get_contents($this->path . $dbn . '_instr.html');
			     else
			       $text = $dbn.' instructions';
				 	 
                 $win = new window2($dbn,$text,null,1,null,'HIDE',1);//,1);
	             $out .= $win->render("center::95%::0::group_article_selected::left::0::0::");
	             unset ($win);
			   }
		     }
		   }//no source
		   */
		   return ($out);  
  }

};

if ((IS_STANDALONE=='true') && (!class_exists('phtml'))) {

  function remote_paramload($section,$param,$path2find=null) {
    if (IS_STANDALONE=='false') {
	  $ret = remote_paramload($section,$param,$path3find);
	  return $ret;
	}  
	else {
	  return 0;
	}    
  }
  
  $c = new rcxmlexp();
  $c->event(GetParam('t'));
  $ret = $c->action(GetParam('t'));  
  unset($c);
  
  echo $ret;
}

}
?>