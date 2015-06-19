<?php
if (defined("SMTPMAIL_DPC")) {

//ini_set("session.gc_maxlifetime", "18000"); to extend the session ?????

$__DPCSEC['RCSHSUBSCRIBERS_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("RCSHSUBSCRIBERS_DPC")) && (seclevel('RCSHSUBSCRIBERS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCSHSUBSCRIBERS_DPC",true);

$__DPC['RCSHSUBSCRIBERS_DPC'] = 'rcshsubscribers';

//$a = GetGlobal('controller')->require_dpc('gui/tinyMCE.dpc.php');
//require_once($a);

if (!GetReq('editmode')) {	
  $b = GetGlobal('controller')->require_dpc('nitobi/nhandler.lib.php');
  require_once($b);
}

$d = GetGlobal('controller')->require_dpc('rc/rcsubscribers.dpc.php');
require_once($d);

GetGlobal('controller')->get_parent('RCSUBSCRIBERS_DPC','RCSHSUBSCRIBERS_DPC');

$__EVENTS['RCSHSUBSCRIBERS_DPC'][4]='cpsubsend';
$__EVENTS['RCSHSUBSCRIBERS_DPC'][5]="cpsubloadhtmlmail";
$__EVENTS['RCSHSUBSCRIBERS_DPC'][6]="cpscampreset";
$__EVENTS['RCSHSUBSCRIBERS_DPC'][7]="cpsubloadimage";
$__EVENTS['RCSHSUBSCRIBERS_DPC'][8]="cpsubattach";
$__EVENTS['RCSHSUBSCRIBERS_DPC'][9]="cpsubuploadtemplate";
$__EVENTS['RCSHSUBSCRIBERS_DPC'][10]="cpsubuploadimage";
$__EVENTS['RCSHSUBSCRIBERS_DPC'][11]="cpsubuploaddocument";
$__EVENTS['RCSHSUBSCRIBERS_DPC'][12]="cpngetsubs";
$__EVENTS['RCSHSUBSCRIBERS_DPC'][13]="cpnsetsubs";
$__EVENTS['RCSHSUBSCRIBERS_DPC'][14]='searchtopic';
$__EVENTS['RCSHSUBSCRIBERS_DPC'][15]='cpviewsubsqueue';
$__EVENTS['RCSHSUBSCRIBERS_DPC'][16]='cpdeactivatequeuerec';

$__ACTIONS['RCSHSUBSCRIBERS_DPC'][4]='cpsubsend';
$__ACTIONS['RCSHSUBSCRIBERS_DPC'][5]="cpsubloadhtmlmail";
$__ACTIONS['RCSHSUBSCRIBERS_DPC'][6]="cpscampreset";
$__ACTIONS['RCSHSUBSCRIBERS_DPC'][7]="cpsubloadimage";
$__ACTIONS['RCSHSUBSCRIBERS_DPC'][8]="cpsubattach";
$__ACTIONS['RCSHSUBSCRIBERS_DPC'][9]="cpsubuploadtemplate";
$__ACTIONS['RCSHSUBSCRIBERS_DPC'][10]="cpsubuploadimage";
$__ACTIONS['RCSHSUBSCRIBERS_DPC'][11]="cpsubuploaddocument";
$__ACTIONS['RCSHSUBSCRIBERS_DPC'][12]="cpngetsubs";
$__ACTIONS['RCSHSUBSCRIBERS_DPC'][13]="cpnsetsubs";
$__ACTIONS['RCSHSUBSCRIBERS_DPC'][14]='searchtopic';
$__ACTIONS['RCSHSUBSCRIBERS_DPC'][15]='cpviewsubsqueue';
$__ACTIONS['RCSHSUBSCRIBERS_DPC'][16]='cpdeactivatequeuerec';

$__LOCALE['RCSHSUBSCRIBERS_DPC'][0]='RCSHSUBSCRIBERS_DPC;Subscribers;Subscribers';
$__LOCALE['RCSHSUBSCRIBERS_DPC'][1]='_SMTPDEBUG;SMTP Monitor;SMTP Monitor';
$__LOCALE['RCSHSUBSCRIBERS_DPC'][2]='_SMTPSENDFORM;Mail Form;Mail Form';
$__LOCALE['RCSHSUBSCRIBERS_DPC'][3]='_INCLALL;or All;ή όλοι';
$__LOCALE['RCSHSUBSCRIBERS_DPC'][4]='_HTMLTEMPLATE;Html Template;Html Template';
$__LOCALE['RCSHSUBSCRIBERS_DPC'][5]='_CC;CC;cc';
$__LOCALE['RCSHSUBSCRIBERS_DPC'][6]='_BCC;BCC;bcc';
$__LOCALE['RCSHSUBSCRIBERS_DPC'][7]='_ISHTML;Html body;Html body';
$__LOCALE['RCSHSUBSCRIBERS_DPC'][8]='_ADDIMAGES;Embed Images;Embed Images';
$__LOCALE['RCSHSUBSCRIBERS_DPC'][9]='_ADDDOCS;Attachments;Attachments';
$__LOCALE['RCSHSUBSCRIBERS_DPC'][10]='_NOTVIEW;If you can\'t read this mail, please press here...;Αν δεν μπορειτε να διαβαστετε αυτο το email, παρακαλω πατηστε εδώ...';
$__LOCALE['RCSHSUBSCRIBERS_DPC'][11]='_NOTES;Notes;Σημειώσεις';
$__LOCALE['RCSHSUBSCRIBERS_DPC'][12]='_TIMEZONE;Timezone;Ζωνη Ωρας';
$__LOCALE['RCSHSUBSCRIBERS_DPC'][13]='_SEND;Send;Αποστολή';
$__LOCALE['RCSHSUBSCRIBERS_DPC'][14]='_subscribers;Subscribers;Συνδρομητές';

class rcshsubscribers extends rcsubscribers {

    var $mailmsg;
	var $subs_no, $subs_mail;
	var $batch, $current_batch_records, $auto_refresh, $timeout;
	var $prpath, $urlpath, $infolder, $newsletter_path;
	var $bypass_method;
	var $ishtml, $mcharset, $encoding;
	var $mailuser,$mailpass,$mailname,$mailserver;
	var $from,$to,$toname;
	var $url, $template, $dirdepth, $image, $mailbody;
	var $template_images_path, $imgpath, $docpath, $template_documents_path, $newsletter_htmlpath;
	var $imgtypes, $doctypes, $templatetypes;
	var $attachments;
	var $maxsize, $msg, $antispamlink, $textnewsletterlink;
	var $ckeditor;	

    function rcshsubscribers() {
	
	  rcsubscribers::rcsubscribers();
	  
	  $this->title = localize('RCSHSUBSCRIBERS_DPC',getlocal());	 
	  $this->mailmsg= null; 	
	 	
	  //$sitecharset = paramload('SHELL','charset');
	  //$this->charset = $sitecharset;//override smtp charset if needed
      $char_set  = arrayload('SHELL','char_set');	  
      $charset  = paramload('SHELL','charset');	  		
	  if (($charset=='utf-8') || ($charset=='utf8'))
	    $this->encoding = 'utf-8';
	  else  
	    $this->encoding = $char_set[getlocal()]; 		  
	  
	  $this->subs_no = 0;		
	  $this->subs_mail = null;	
	  $this->msg = null;	
	  
	  $this->batch = 400;  //per hours sends
	  $this->auto_refresh = GetParam('refresh')?GetParam('refresh'):0;
	  $this->timeout = 3601+1000;//one hour+1000 sec
	  
	  $this->maxsize = 999000000;	  
	  
	  $this->prpath = paramload('SHELL','prpath');
      $this->urlpath = paramload('SHELL','urlpath');
      $this->infolder = paramload('ID','hostinpath');	
	  $this->url = paramload('SHELL','urlbase');  
	  
	  $this->dirdepth = $this->infolder?2:1;

	  $handler = remote_paramload('RCSHSUBSCRIBERS','handler',$this->prpath);	
	  //if ($handler)
	    //define($handler,'TRUE');  //defined at the top of .php web file
		
	  $this->bypass_method = true;
	  $this->antispamlink = 'UNSUBSCRIBE';
	  $this->textnewseltterlink = 'NEWSLETTER';//not used	  
	  
	  $htmlformat = remote_paramload('RCSHSUBSCRIBERS','htmlformat',$this->prpath);
	  $this->ishtml = $htmlformat?$htmlformat:GetParam('ishtml');	
	  //echo $this->ishtml;
	  
	  $ns = remote_paramload('RCSHSUBSCRIBERS','newsletterpath',$this->prpath);
	  $this->newsletter_path = $ns?$ns:'/cp/html';
	  	  
	  $this->newsletter_htmlpath = $this->urlpath . $this->infolder . $this->newsletter_path;
	  $this->imgpath = $this->newsletter_path.'/images';
	  $this->template_images_path = $this->urlpath . $this->infolder . '/'. $this->imgpath .'/';	  
	  $this->docpath = $this->newsletter_path.'/attachments';	  
	  $this->template_documents_path = $this->urlpath . $this->infolder . '/'. $this->docpath .'/';
	  $this->templatetypes = array('.htm','.txt');	  
	  $this->imgtypes = array('.png','.jpg','.gif');
	  $this->doctypes = array('.doc','.pdf','.png','.jpg','.gif','.htm','.txt');	
	  	  
	  //override smtp settings
	  $this->mailname = remote_paramload('RCSHSUBSCRIBERS','realm',$this->prpath);
	  $this->mailuser = remote_paramload('RCSHSUBSCRIBERS','mailuser',$this->prpath);
	  $this->mailpass = remote_paramload('RCSHSUBSCRIBERS','mailpass',$this->prpath);
	  $this->mailserver = remote_paramload('RCSHSUBSCRIBERS','mailserver',$this->prpath);	
	  //echo '+',$this->mailuser,'+',$this->mailname,'+',$this->mailserver,'+';  
	  $this->from = remote_paramload('RCSHSUBSCRIBERS','from',$this->prpath);//echo '>',$defname;
		 	  
	  $this->to = remote_paramload('RCSHSUBSCRIBERS','to',$this->prpath);	  
	  $this->toname = remote_paramload('RCSHSUBSCRIBERS','toname',$this->prpath);	
	  
	  $this->template = GetSessionParam('template');  
	  $this->images = (array) unserialize(GetSessionParam('images')); 
	  //print_r($this->images);  	  	  	    
	  $this->attachments = (array) unserialize(GetSessionParam('attachments'));	  
	  $this->mailbody = GetSessionParam('mailbody');   
	  
	  if (GetReq('editmode')) {
		   $cke_js = remote_paramload('CKEDITOR','ckeditorjs' ,$this->prpath);
		   $ckeditor_js = $cke_js ? $cke_js : "http://www.stereobit.gr/ckeditor/ckeditor.js"; 	  
	  
		   $js = new jscript;
		   $js->load_js($ckeditor_js,null,null,null,1);		   			      		      
           //$js->load_js($code,null,1);		   			   
		   unset ($js);		  
	  }	 

	  $this->ckeditor = '/';//remote_paramload('RCSHSUBSCRIBERS','ckeditor',$this->prpath); //ckeditr ckfinder path = /	  
	}
	
    function event($event) {
	 $include_subs = GetParam('includesubs'); 
     $include_all = GetParam('includeall'); //from form ..all users	    
	 $subscribers = $include_subs?$include_subs:$include_all;//one or another	
	 
	 $this->subs_mail = $this->getmails($include_all); //,sep mails
  
     //rcsubscribers::event($event); 
	 
	 switch ($event) {
	    case 'cpngetsubs' : $this->get_subscribers_list(); break;		 
	    case 'cpnsetsubs' : $this->save_subscribers_list();  break;
			 
	    case 'cpsubuploadtemplate' : $this->msg = $this->upload_element();  break;		 
	    case 'cpsubuploadimage'    : $this->msg = $this->upload_element(2);  break;
	    case 'cpsubuploaddocument' : $this->msg = $this->upload_element(1);  break;				
	    case 'cpsubattach'         : break;		 
	    case 'cpsubloadimage'      : break;	
	 
	    case 'cpsubscribe': $this->dosubscribe();
		                    $this->mass_subscribe();
			                break;	 
 
        case "cpsubsend"  :$this->current_batch_records = $this->send_mails(); 
		                   if ($subscribers)
						     $this->msg = 'current batch records:'.$this->current_batch_records;
				           break; 	 
						   
        case "cpscampreset"  ://reset camapaign
		                   $rc = $this->reset_batch_state(); 
		                   $this->msg = 'Reset Campaign:';
						   $this->msg .= $rc?'Camapaign reset successfully!':'There is no active campaign!';
				           break; 						   
	 }
	
	 if (!GetReq('editmode')) {		 
       $this->nitobi_javascript();	
	 } 
    }
	
	//override
    function action($action=null)  { 

      if (!GetReq('editmode')) {		
	     if (GetSessionParam('REMOTELOGIN')) 
	       $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	     else  
           $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);	
	  }
	  
	     switch ($action) {
		   case 'cpadvsubscribe'      :$out .= $this->subscribeform();  break;
           case "cpscampreset"        :		 
   		                               //$out = $this->title();
		                               ///$out .= $this->form("cpsubscribe");
					                   $out .= $this->show_subscribers();  break;
	       case 'cpsubattach'         :								  
	       case 'cpsubloadimage'      :
	       case 'cpsubuploadtemplate' : 	 
	       case 'cpsubuploadimage'    : 
	       case 'cpsubuploaddocument' : 
	       case 'cpsubsend'           :			   
		   default                    :
		                               $out .= $this->show_subscribers();
		 }			
		 
	     //$out .=  $this->subs_no . ' subscribers';		  

	     return ($out);
	}		 	
	
	//continue batch form with data taken from cform of rcshmail
	function submit_batch_form($cmd=null,$encode=null) {
	   $mybatch_id = GetReq('batchid');
	   $bid = $mybatch_id?($mybatch_id+1):1;  
	   
	   $mail_text = $encode?encode(GetParam('mail_text')):GetParam('mail_text');
	   
       $mycmd = $cmd?$cmd:'cpsubsend';
	   
       $filename = seturl("t=$mycmd&batchid=".$bid);	   
	   
       $out .= setError($sFormErr . $this->mailmsg);	   
   
	   //params form
       $out  .= "<FORM action=". "$filename" . " method=post class=\"thin\">";
       $out .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>"; 			 	       
		  		 
       $out .= "<input type=\"hidden\" name=\"FormName\" value=\"$mycmd\">"; 	
       $out .= "<input type=\"hidden\" name=\"from\" value=\"".GetParam('from')."\">"; 			  
       $out .= "<input type=\"hidden\" name=\"to\" value=\"".GetParam('to')."\">"; 	
       $out .= "<input type=\"hidden\" name=\"subject\" value=\"".GetParam('subject')."\">";
       $out .= "<input type=\"hidden\" name=\"batchrestore\" value=\"".GetParam('batchrestore')."\">";	   
	      			  
       $out .= "<DIV class=\"monospace\"><TEXTAREA style=\"width:100%\" NAME=\"mail_text\" ROWS=10 cols=60 wrap=\"virtual\">";
	   $out .=  GetParam('mail_text');		 
       $out .= "</TEXTAREA></DIV>";
	   
       $out .= "<input type=\"hidden\" name=\"includesubs\" value=\"".GetParam('includesubs')."\">"; 			  
       $out .= "<input type=\"hidden\" name=\"includeall\" value=\"".GetParam('includeall')."\">"; 	
       $out .= "<input type=\"hidden\" name=\"smtpdebug\" value=\"".GetParam('smtpdebug')."\">";			   
	   	   
       $out .= "<input type=\"hidden\" name=\"batch\" value=\"".$this->batch."\">"; 			  		  
       $out .= "<input type=\"hidden\" name=\"refresh\" value=\"".$this->auto_refresh."\">";		  	   		  	   
	  
       $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"$mycmd\">&nbsp;";			
			
       $out .= "<input type=\"submit\" name=\"Submit\" value=\"Next batch....\">";		
       $out .= "</FONT></FORM>"; 	  	
		
	   /*$wina = new window($this->title,$out);
	   $winout .= $wina->render("center::$mywidth::0::group_dir_body::left::0::0::");
	   unset ($wina);*/
		
	   return ($out);  		   		
	}	
  
    //overide
	function show_grids($nowin=null) {
	   $bid = GetParam('batchid');
	   $incsubs = GetParam('includesubs');
	   $incall = GetParam('includeall');
	   //echo '-',$bid,'-',$incsubs,'-',$incall,'<br>';
	   
	   $include_subs = GetParam('includesubs');
	   $include_all = GetParam('includeall');	  
	   $bid = GetParam('bid')?GetParam('bid'):0; 
	   
	   $subscribers = $include_subs?$include_subs:$include_all;//one or another	   
	
       $sFormErr = GetGlobal('sFormErr');
	   
	   //echo $this->batch,':',$this->current_batch_records;
	   //batch
	   if (($subscribers) && ($bid) && ($this->batch==$this->current_batch_records)) {
	     //title
	     $wd = '<h1>'.GetParam('subject').'</h1><br>';
		 //html view
	     /*$pattern = "@<body.*?>(.*?)</body>@";
		 //echo GetParam('mail_text'),'>';
	     preg_match_all($pattern,GetParam('mail_text'),$matches);
		 //print_r($matches);
	     $wd .= $matches[0];*/  //problems
		 //form
	     $wd .= $this->submit_batch_form();
	     $datattr[] = $wd;
	     $viewattr[] = "left;100%";		 
	   }	 
	   else	{ //normal view
	
	     //gets
	     $alpha = GetReq('alpha');
	     //transformed posts !!!!
	     $apo = GetParam('apo');
	     $eos = GetParam('eos');	 
	   
	     if (!GetReq('editmode')) {		
		    
	       $grid0_get = seturl("t=cpngetsubs&alpha=$alpha&apo=$apo&eos=$eos");//"shhandler.php?t=shngetsubs&alpha=$alpha&apo=$apo&eos=$eos";	
	       $grid0_set = seturl("t=cpnsetsubs");//"shhandler.php?t=shnsetsubs";	
		
	       $this->_grids[0]->set_text_column("ID","id","50","true");		   
	       $this->_grids[0]->set_text_column(localize('_SUBMAIL',getlocal()),"email","150","true");		   
	       $this->_grids[0]->set_text_column(localize('_FNAME',getlocal()),"fname","150","true");		   
	       $this->_grids[0]->set_text_column(localize('_LNAME',getlocal()),"lname","150","true");		   
	       $this->_grids[0]->set_text_column(localize('_NOTES',getlocal()),"notes","150","true");
	       $this->_grids[0]->set_text_column(localize('_TIMEZONE',getlocal()),"timezone","50","true");	   
	       $this->_grids[0]->set_text_column(localize('_SUBDATE',getlocal()),"startdate","150","true","LOOKUP","list_type","type","type");//,"LISTBOX","list_type","type","type_id");
	   
           $datattr[] = $this->_grids[0]->set_grid_remote($grid0_get,$grid0_set,"400","460","livescrolling",17,"true","true");
	       $viewattr[] = "left;50%";		   		        
	      	   
	    
	       $wd .= $this->mailmsg . '<br>' . $sFormErr . '<br>';
	
       
	       //$wd .= GetGlobal('controller')->calldpc_method('rcshmail.form use cpsubsend+Send+10+cpsubloadhtmlmail');
	       //REDEFINED FORM in THIS
	       $wd .= $this->form('cpsubsend','Send',10);
			  
	       $datattr[] = $wd;
	       $viewattr[] = "left;50%";
	     }
		 else {//EDITMODE..NO NITOBI
	       $wd .= $this->mailmsg . '<br>' . $sFormErr . '<br>';
	       $wd .= $this->form('cpsubsend',localize('_SEND',getlocal()),10,null,GetReq('editmode'));
		   
		   if ($nowin) return ($wd);
			  
	       $datattr[] = $wd;
	       $viewattr[] = "left;100%";		 
		 }//else
		 	 
	   }//else
	   
	   $myw = new window('',$datattr,$viewattr);
	   $ret = $myw->render("center::100%::0::group_article_selected::left::3::3::");
	   unset ($datattr);
	   unset ($viewattr);		   	
	   	
	   return ($ret);	
	}
	
	//nitobi get	
	function get_subscribers_list() {
       $db = GetGlobal('db');	
	   //tranformed posts..
	   $apo = GetReq('apo'); //echo $apo;
	   $eos = GetReq('eos');	//echo $eos; 
       $filter = GetReq('filter');
	   
       $handler = new nhandler(17,'id','Desc');	   
       $handler->sortColumn = 'startdate';		
	   $handler->sortDirection= 'Desc';		   

	   if ($filter) { 
         $whereClause = " where (email like '%$filter%' or startdate like '%$filter%' or id like '%$filter%') and subscribe=1";
       }	
	   else
	     $whereClause = ' where subscribe=1 ';

	   	
	   
		  if (isset($_GET['id'])) {		
            $whereClause .= ' and id=' . $_GET['id'];				     
	   	  }
		  				    
	   
	      if ($letter=GetReq('alpha')) {  
	        $whereClause .= " and ( email like '" . strtolower($letter) . "%' or " .
		                    " email like '" . strtoupper($letter) . "%')";	
			//marka is lookup table...???		 
		  }			 
  
		  if ($apo) {
		    $whereClause.= " and startdate>='" . convert_date(trim($apo),"-DMY",1) . "'";
		  }  
		  
		  if ($eos) {
		    $whereClause .= "and startdate<='" . convert_date(trim($eos),"-DMY",1) . "'";						
		  } 				   	
   
	   $sSQL .="SELECT id,startdate,email,fname,lname,notes,timezone FROM users";	
	   $sSQL .= $whereClause;
	   $sSQL .= " ORDER BY " . $handler->sortColumn . " " . $handler->sortDirection ." LIMIT ". $handler->ordinalStart .",". ($handler->pageSize) .";";
	   //echo $sSQL;	die();
	   
       $result = $db->Execute($sSQL,2);	
	   
	   //$order = " ORDER BY " . $this->sortColumn . " " . $this->sortDirection ." LIMIT ". $this->ordinalStart .",". ($this->pageSize) .";";
	   //$result = GetGlobal('controller')->calldpc_method("rcvehicles.select use $order");	   
	   
	   $names = array('id','startdate','email','fname','lname','notes','timezone');			 			 
	   $handler->handle_output($db,$result,$names,'id',null,$this->encoding);	
	}
	
	//nitobi set	
	function save_subscribers_list() {
       $db = GetGlobal('db');		
	
       $handler = new nhandler(17,'id','Desc');		
	   $names = array('id','startdate','email','fname','lname','notes','timezone');		 
	   $sql2run = $handler->handle_input(null,'users',$names,'id');		
	
       $db->Execute($sql2run,3,null,1);
	   
	   if (($handler->debug_sql) && ($f = fopen($this->prpath . "nitobi.sql",'w+'))) {
	     fwrite($f,$sql2run,strlen($sql2run));
		 fclose($f);
	   }		
	}		

	function dosubscribe($mail=null,$notell=null) {
       $db = GetGlobal('db');
       $sFormErr = GetGlobal('sFormErr');		
	   $ret = null;
	   if (!$mail) 
	     $mail = GetParam('submail');
	   
       $dtime = date('Y-m-d h:i:s');	   	
	   //echo $mail,'+<br>';
	   if (checkmail($mail))  {
          //check if mail is in database
		  $sSQL = "SELECT email,subscribe FROM users where email=". $db->qstr($mail); 
	      $ret = $db->Execute($sSQL,2);
          //print_r($ret->fields);
		  //echo $sSQL;
		  
		  
          if (empty($ret->fields[0])) {

			   $sSQL = "insert into users (email,startdate,lname,fname,subscribe,notes) " .
			           "values (" .
					   $db->qstr(strtolower($mail)) . "," . $db->qstr($dtime) . "," .
					   $db->qstr('SUBSCRIBER') . "," . $db->qstr('UNKNOWN') . ",1," . $db->qstr('ACTIVE') .
		               ")";  
			   $db->Execute($sSQL,1);		    
			   
			   SetGlobal('sFormErr', localize('_MSG6',getlocal()));				   
			   //echo $sSQL;			   
			   if (!$notell) {
                 if ($this->tell_it) 
			       $this->mailto(/*$mail*/$this->tell_from,$this->tell_it,'New Subscription',$mail);			     							  
		       }		 
			   
			   $ret = 1;
		  }
		  else {//if (!$ret->fields[1]) {//is in db but disabled  ..enable subscription
			   $sSQL = "update users set subscribe=1 where email=" . $db->qstr(strtolower($mail));  
			   $db->Execute($sSQL,1);	
			   //echo $sSQL;		
			   if (!$notell) {			   	   	    
			     SetGlobal('sFormErr', localize('_MSG6',getlocal()));
               }
			   $ret = -1;	  
		  }
		  //else SetGlobal('sFormErr', localize('_MSG7',getlocal()));
	   }
	   else SetGlobal('sFormErr', localize('_MSG5',getlocal()));	
	   
	   return $ret;	   	
	}
	
	function dounsubscribe($mail=null) {
	
	   if (!$mail) 
	     $mail = GetParam('submail');
	   if (!$mail) 
	     $mail = GetReq('submail');	 
	   	
       $db = GetGlobal('db');
       $sFormErr = GetGlobal('sFormErr');	   

	   if (checkmail($mail))  {


		  $sSQL = "update users set subscribe=0 where email=" . $db->qstr($mail) ; 
		  $result = $db->Execute($sSQL,1);
		  
		  SetGlobal('sFormErr',localize('_MSG8',getlocal()));
		  setInfo(localize('_MSG8',getlocal()));
	   }
	   else { 
	     SetGlobal('sFormErr', localize('_MSG5',getlocal()));	  
		 setInfo(localize('_MSG5',getlocal()));
	   }	
	}
	
	function subscribe_extracting_name($token=null) {
        $db = GetGlobal('db'); 
		if (!$token) return;	
	    //echo '<br><br>doit2',$token,'<br>';
		$matches = array();
					
	    //method 1 name <mail>
	    $pattern = "@<(.*?)>@";
	    preg_match($pattern,$token,$matches);
	    //print_r($matches);
	    $extracted_mail = trim(strtolower($matches[1]));
        //echo 'extract:',$mail,'<br>';//,':',$mail,'<br>';
		//echo $mail;
		if ($s = $this->dosubscribe($extracted_mail,1)) {
		  //echo 'a';
		  $name = str_replace($extracted_mail,'',$token);		  
		  //echo $name,'<br>'
		  if ($name) {
		    $name = str_replace('"','',$name);
		    $name = str_replace("'",'',$name);
		    $name = str_replace('<>','',$name);			
		    $sSQL = "update users set fname=".$db->qstr($name)." where email=" . $db->qstr(strtolower($extracted_mail));  
		    $db->Execute($sSQL,1);		    
		    echo $sSQL,';<br>';
		  }
		  return ($s);	   
	    }
		else {
	      //method 2 name [mail]
	      $pattern2 = "@[(.*?)]@";
	      preg_match($pattern2,$token,$matches);
	      //print_r($matches);
	      $extracted_mail = trim(strtolower($matches[1]));
		
		  if ($s = $this->dosubscribe($extracted_mail,1)) {
		    //echo 'b';    
		    $name = str_replace($extracted_mail,'',$token);		  
		    //echo $name,'<br>';
		    if ($name) {		
		      $name = str_replace('"','',$name);
			  $name = str_replace("'",'',$name);
		      $name = str_replace('[]','',$name);			
		      $sSQL = "update users set fname=".$db->qstr($name)." where email=" . $db->qstr(strtolower($extracted_mail));  
		      $db->Execute($sSQL,1);		    
		      echo $sSQL,';<br>';
		    }
            return ($s);	   			   
	      }
		  else { //method 3 name mail
		    $mytokens = explode(' ',$token);
		    $name = trim($mytokens[0]);
		    $extracted_mail = trim(strtolower($mytokens[1])); 
		  
		    if ($s = $this->dosubscribe($extracted_mail,1)) {
			  //echo 'c';			
		      $name = str_replace($extracted_mail,'',$token);
		      //echo $name,'<br>';
		      if ($name) {
		        $name = str_replace('"','',$name);
			    $name = str_replace("'",'',$name);
		        $sSQL = "update users set fname=".$db->qstr($name)." where email=" . $db->qstr(strtolower($extracted_mail));  
		        $db->Execute($sSQL,1);		    
		        //echo $sSQL,'<br>';
			  }	
	          return ($s);	   
			}  
	      }		  
		}

        return null;
	}
	
	function mass_subscribe() {
	
	  $mailtext = GetParam('csvmails');
	  if (!$mailtext) return;
	  
	  $separator=GetParam('submail'); //separator on mail field
	  if (!$separator)
	    $separator = ';';
		
	  $mymails = explode($separator,$mailtext);
	  //print_r($mymails);
	  
	  $x=0; $x2=0;
	  $n=0;
	  $e=0;
	  set_time_limit(0);
	  foreach ($mymails as $i=>$tok) {
	  
	    $m = trim(strtolower($tok));	  
		$doit = $this->dosubscribe($m,1);
	    if ($doit) {

		  if ($doit>0) {
		    $x+=1;
		  }
		  elseif ($doit<0) {
		    $x2+=1;
		  }
		}  
		else {
		  $doit_2 = $this->subscribe_extracting_name($tok);
		  if ($doit_2) {
		  
		    $n+=1;
			
		    if ($doit_2>0) 
		      $x+=1;
		    elseif ($doit_2<0)
		      $x2+=1;			
			else
			  $e+=1;    
		  }
		  else		
		    $e+=1; 
	    }	
	  }
	  
	  set_time_limit(50);
	  $msg = $x . ' mails added, ';
	  $msg .= $x2 . ' mails updated from ' . count($mymails) . ', ';	  
	  $msg .= $n . ' names extracted,';	  
	  $msg .= $e . ' tokens not recognized.';	  
	  
	  SetGlobal('sFormErr', $msg);	  
	  setInfo($msg);	  
	}
	
	//ovweride for mysql	
    function isin($mail) {
	
       $db = GetGlobal('db');
	   
       $sSQL = "SELECT id,email,startdate FROM users";	
	   $sSQL .= " WHERE email=" . $db->qstr($mail) . " and subscribe>0"; 
		
	   $resultset = $db->Execute($sSQL,2);
	   //$ret = $db->fetch_array($resultset);	   
	   
	   //echo $mail,$sSQL;


	   if ($resultset->fields['email']==$mail) return (true);
	
       return (false);
    }	
	
	//ovweride for mysql
	function getmails($all_users=null, $batch=null,$batchid=null) {
	
       $db = GetGlobal('db');	
	   $batchid = GetParam('batchid');	   
	   
       //$resultset = $db->Execute("select email from rcsubscribers",2);   
	   $sSQL .="SELECT email FROM users";	
	   
	   if ($all_users==null)//select only subscriber
	     $sSQL .= " where lname='SUBSCRIBER' and subscribe=1";
		 
	   if ($batch) {
	     $bid = $batchid?$batchid:0;
		 //$bid = 1; //override for test
		  
         $startfrom = ($bid*$batch);
		 $limit = $batch; //$start + $batch - 1;
	     $sSQL .= " limit " . $startfrom	. ',' .  $limit;
	   }	 
	   //echo $sSQL;	
	   
       $result = $db->Execute($sSQL,2);
	   
	   $this->subs_no = $db->Affected_Rows();	
	   
	   if (count($result)>0) {		   
	     foreach ($result as $n=>$rec) {	     
		   $ret[] = $rec['email'];
		 }
	   }
	   //print_r($ret); echo 'a';	 
	   if (!empty($ret)) {  
	     $out = implode(';',$ret);
       }
	   //echo $out;
	   return $out;	
	}	
	
	
	function send_mails() {
	   //$myactivebatch = 10;//bypass other batches
	   //$myactivebatch = $this->load_saved_batch();//load direct from file
	   $myactivebatch = GetParam('batchrestore');//load in html form field and save as param
	   //echo '>',$myactivebatch;
	   
	   $batch = GetReq('batchid');
	   $bid = GetParam('bid')?GetParam('bid'):0; 
	   	   
	   $include_subs = GetParam('includesubs');
	   $include_all = GetParam('includeall');	  
	   $subscribers = $include_subs?$include_subs:$include_all;//one or another
	
	   $from = GetParam('from');
	   $to = GetParam('to');	   
	   $subject = GetParam('subject');
	   
	   if ($this->template) {
	     if ($this->dirdepth) {
		   for($i=0;$i<$this->dirdepth;$i++)
			  $backdir .= "../";
		   }
		   else
		     $backdir = "../";
		 //repalce viewable data to send data (absolute dir)	  
		 //echo $backdir;
		 if ($this->mailbody)//get session text with headers
		   $body = $this->mailbody;
		 else //get text area
	       $body = str_replace($backdir,$this->url.$this->infolder.'/',GetParam('mail_text'));  	   
	   }	 
	   else	{ //text area or session text with headers
	   
	     if (GetReq('editmode')) {
		   $mytext = $this->mailbody?$this->mailbody:GetParam('mail_text');
		   $body = $this->unload_spath($mytext);
		 }
		 else
	       $body = $this->mailbody?$this->mailbody:GetParam('mail_text');  
	   }	 	   
	   	 
		 
	   if ($subscribers) {// || ($batch)) {//if sybs checked or batch in process..
	   
	     //save the current mail campaign state
	     $this->save_current_batch($batch);	
	   	   	   
	     //$subs = $this->subs_mail;
		 
		 //workinh batch tosend
		 $subs = $this->getmails($include_all,$this->batch,$bid);
		 
	     if (($batch>=0) && ($batch>=$myactivebatch)) {

	       //if ($res = GetGlobal('controller')->calldpc_method('rcshmail.sendit use '.$from.'+'.$to.'+'.$subject.'+'.$body.'+'.$subs)) {
           if ($res = $this->sendit($from,$to,$subject,$body,$subs,$this->ishtml)) {
	         $this->mailmsg = $res . " mail(s) send!";
	       }	 
	       else 
	         $this->mailmsg = "Send failed";
		 }
		 else 	
		   $this->mailmsg = "Send bypassed"; 
	   }	 
	   else {//one receipent
	     //if ($res = GetGlobal('controller')->calldpc_method('rcshmail.sendit use '.$from.'+'.$to.'+'.$subject.'+'.$body))
		 if ($res = $this->sendit($from,$to,$subject,$body,null,$this->ishtml)) 
		   $this->mailmsg = $res . " mail(s) send!";
		 else
		   $this->mailmsg = "Send failed";	
	   }	   
		 
	   //auto refresh
	   $refresh = GetParam('refresh')?GetParam('refresh'):$this->auto_refresh;
	   if (($refresh>0) && ($res==$this->batch)) {
         $this->refresh_bulk_js(seturl('t=cpsubsend&batchid='.($bid+1).'&batch='.$this->batch.'&refresh='.$refresh),$refresh);			  
	     $this->mailmsg .= "Wait for next batch " . $this->timeout;		 
	   }	 
	  
	   //test
	   //return $this->batch;
       //return (400);	  	 //??????????????????????????????????????????????????
	   //echo '---------------------',$res,'---------------------------<br>';
	   
	   if ($res<$this->batch) //means end of campaign
	     $this->reset_batch_state();
	   
	   return ($res);//how many mails tried to send (batch...)	    
	}	
	
	//override to add combo
/*	function nitobi_javascript() {
      if (iniload('JAVASCRIPT')) {

		   //$template = $this->set_template();   		      
		   
	       $code = $this->init_grids();			
		   //$code .= $this->_grids[0]->OnClick(22,'QueueDetails',$template,'Vehicles','p_id',0);
	   
		   $js = new jscript;
		   $js->setloadparams("init()");
           $js->load_js('nitobi.grid.js');		   	   
           //$js->load_js('nitobi.toolkit.js');				   		   
           //$js->load_js('nitobi.combo.js');		   		   
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }		
	}*/
	
   function refresh_bulk_js($page,$timeout=null) {
   
      if (iniload('JAVASCRIPT')) {

	       $code = $this->javascript($page,$timeout);
	   
		   $js = new jscript;
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }   
   } 		
	
   //refresh to continue bulk proccess automaticaly
   function javascript($page,$timeout=null) {
   
     $mytimeout=$timeout?$timeout:$this->timeout; //1 hour plus 1 sec
   
     $ret = "
function neu()
{	
	top.frames.location.href = \"$page\"
}
window.setTimeout(\"neu()\",$mytimeout);
";
	 
	 return ($ret);
   }		
/*	
	//override to add combo	
	function init_grids() {
        //disable alert !!!!!!!!!!!!		
		$out = "
function alert() {}\r\n 

function photo_name() {
  var str = arguments[0];

  id = 1000+str; 
  ret = id.substr(str.length-1);
  
  return ret; 
}
			
function init()
{
";
       if (!empty($this->_grids)) {
        foreach ($this->_grids as $n=>$g) {
		  if (is_object($g))
		    $out .= $g->init_grid($n);
		}  
	   }  
	
       $out .= "\r\n}";
       return ($out);
	}
*/	
/*	function show_combo() {
		
		
	    $this->_combo[0]->set_combo_column_img('../../images/b_go.gif',16,1);	
	    $this->_combo[0]->set_combo_column('color',170,0);
	    $this->_combo[0]->set_combo_column('',200,2);
		
		
	    //STATIC MODE
	    $file = paramload('SHELL','prpath') . "colors.opt";
	    $names = array('color','image','email');	 
	    $data = explode(",",file_get_contents($file));	
	    foreach ($data as $id=>$rec) {
	     $mydata[] = array(trim($rec),'../../images/b_go.gif','xxx');
	    }
	    $this->_combo[0]->set_combo_data($mydata,implode("|",$names));					
	
	
	    //$ret = $this->_combo[0]->set_combo();
        $ret .= $this->_combo[0]->set_combo("175","360","300","",'unbound');			
		
		return ($ret);
	}	*/
	
	
	//save the bulk batch num of batch mails in case of session expire
	function save_current_batch($batch=null) {
	
	   if ($batch) {
	     $nextbatch = $batch+1;
	     $ret = file_put_contents($this->prpath.'batchmailnum.txt',$nextbatch);
	   }	 
		 
	   return ($ret);	 
	  
	}
	
	//load the batch num of previous (pre-expired session)sending)
	function load_saved_batch() {
	
	   $ret = @file_get_contents($this->prpath.'batchmailnum.txt');
	   //echo $ret;
	   
	   return ($ret);
	}	
	
	//reset the bulk batch when end of mails 
	function reset_batch_state() {
	
	   $ret = file_put_contents($this->prpath.'batchmailnum.txt','');
		 
	   return ($ret);	 
	  
	}	
	
	//override from rcshmail ... redefined form
    function form($action=null,$submit='Submit',$rows=null,$taction=null,$editmode=null) {
	     //print_r($_POST);
	     global $sFromErr;
		 $taction = 'cpsubloadhtmlmail';//templates
		 $iaction = 'cpsubloadimage';//add images (embed)		 
		 $daction = 'cpsubattach';//add attachments		 
		 
		 $activebatch = $this->load_saved_batch();
 
		 $sendto = GetParam('to')?GetParam('to'):$this->to;
		 $sendcc = GetParam('cc')?GetParam('cc'):null;		 
		 $sendbcc = GetParam('bcc')?GetParam('bcc'):null;			 
		 
		 $mailuser = $this->mailuser?$this->mailuser:$this->from;	//echo '>',$mailname;
	     $mymail = GetParam('from')?GetParam('from'):$mailuser;		// echo '>',$mymail;
		
		 
		 if (!$submit)
		   $submit = localize('_SUBMIT',getlocal());
  
         if (!$rows)
		   $rows = 16;
  
         if ($action)
		   $myaction = seturl("t=".$action."&editmode=".GetReq('editmode'));   
		 else  
           $myaction = seturl("t=cmail"."&editmode=".GetReq('editmode'));   
		   
		 //in case of activebatch ..go directly to batch num
		 if (($activebatch) && ($this->bypass_method==false))
		   $myaction .= '&batchid=' . $activebatch;   
		 		   
         $out .= "<FORM action=". "$myaction" . " method=post>"; 	
	 	 
         //error message
         $out .= setError($sFormErr);		  
	 
         $from[] = "<B>" . localize('_FROM',getlocal()) . ":</B>";
         $fromattr[] = "right;20;";
		 $from[] = "<input type=\"text\" name=\"from\" maxlenght=\"40\" readonly =\"readonly\" value=\"".$mymail."\">&nbsp;" . $this->mailname;
	     $fromattr[] = "left;80%;";		

	     $fwin = new window('',$from,$fromattr);
	     $winout .= $fwin->render("center::100%::0::group_article_selected::left::0::0::");	
	     unset ($fwin);	  
	 
         //TO..
         $to[] = "<B>" . localize('_TO',getlocal()) . ":</B>";
 	     $toattr[] = "right;20%;";	 
	     $to[] = "<input type=\"text\" name=\"to\" maxlenght=\"40\" value=\"$sendto\">&nbsp;" . $this->toname;	
		 $toattr[] = "left;80%;";
	     $twin = new window('',$to,$toattr);
	     $winout .= $twin->render("center::100%::0::group_article_selected::left::0::0::");	
	     unset ($twin);		 
		 
         //CC..
         if ((SMTP_PHPMAILER=='true')  || (SENDMAIL_PHPMAILER=='true')) {			 			 
           $cc[] = "<B>" . localize('_CC',getlocal()) . ":</B>";
 	       $toattr[] = "right;20%;";	 
	       $cc[] = "<input style=\"width:100%\" type=\"text\" name=\"cc\" maxlenght=\"80\" value=\"$sendcc\">";
		   $toattr[] = "left;80%;";		 
	       $twin = new window('',$cc,$toattr);
	       $winout .= $twin->render("center::100%::0::group_article_selected::left::0::0::");	
	       unset ($twin);		
		 
           $bcc[] = "<B>" . localize('_BCC',getlocal()) . ":</B>";
 	       $toattr[] = "right;20%;";	 
	       $bcc[] = "<input style=\"width:100%\" type=\"text\" name=\"bcc\" maxlenght=\"80\" value=\"$sendbcc\">";
		   $toattr[] = "left;80%;";		 
	       $twin = new window('',$bcc,$toattr);
	       $winout .= $twin->render("center::100%::0::group_article_selected::left::0::0::");	
	       unset ($twin);			 
		 } 			 
     
	     //SUBJECT..
		 $sbj = $subject?$subject:GetParam('subject');
         $subt[] = "<B>" . localize('_SUBJECT',getlocal()) . ":</B>";
 	     $subattr[] = "right;20%;";	 
         $subt[] = "<input style=\"width:100%\" type=\"text\" name=\"subject\" maxlenght=\"30\" value=\"".$sbj."\">"; 
 	     $subattr[] = "left;80%;";
	     $swin = new window('',$subt,$subattr);
	     $winout .= $swin->render("center::100%::0::group_article_selected::left::0::0::");	
	     unset ($swin);	 
		 
		 //OPTIONS 
         $opt[] = "&nbsp;";
 	     $toattr[] = "right;20%;";	
		 $check1 = $activebatch?'checked':null; 		 
	     $options = "<B>" . localize('_INCLSUBS',getlocal()) . "&nbsp;<input type=\"checkbox\" name=\"includesubs\" $check1>";		
         $options .= "<B>" . localize('_INCLALL',getlocal()) . "&nbsp;<input type=\"checkbox\" name=\"includeall\" ><br>";				   
         if ((SMTP_PHPMAILER=='true')  || (SENDMAIL_PHPMAILER=='true')) {	
		   $check2 = $this->ishtml?'checked':null;
           $options .= "<B>" . localize('_ISHTML',getlocal()) . "&nbsp;<input type=\"checkbox\" name=\"ishtml\" $check2><br>";		 
		 }	
		 else
		   $options .= "<B>" . localize('_SMTPDEBUG',getlocal()) . "&nbsp;<input type=\"checkbox\" name=\"smtpdebug\"><br>";//debug smtp
		   	 
	     $opt[] = $options;
 	     $toattr[] = "left;80%;";	
	     $twin = new window('',$opt,$toattr);
	     $winout .= $twin->render("center::100%::0::group_article_selected::left::0::0::");	
	     unset ($twin);		 
	 	       
	     //MAIL BODY..		   
         $mb[] = "&nbsp;";//"<B>" . localize('_MESSAGE',getlocal()) . ":</B>";
 	     $mbattr[] = "right;1%;";	
		 
		 if (GetReq('t')=='cpsubloadhtmlmail') { //LOAS A NEW TEMPLATE
		   $path = $this->urlpath.$this->infolder.$this->newsletter_path;//GetGlobal('controller')->calldpc_var('rctedit.htmlpath');		 
		   //echo $path; print_r($_POST);
		   if ($template = GetParam('template')) {
		     $data = GetGlobal('controller')->calldpc_method('rctedit.loadfromfile use '.$template.'+'.$path);
		   }
		   else 
		     $data = null;
			 
		   $conditions = $this->spam_conditions_text($this->antispamlink,0,$this->ishtml);
		   $this->mailbody = $data . $conditions; //str_replace('</html>',$conditions.'</html>',$data);
		   
		   //$this->htmleditor = new tinyMCE('textareas','ADVANCEDFULL',1,'images',$this->dirdepth);
		   //$mbody = $this->htmleditor->render('mail_text','100%',$rows+10,$data . $conditions);
		   $mbody = $this->render_textarea($data . $conditions, $rows);
		   
		   $this->template = $this->url . $this->infolder . $this->newsletter_path . $template; 

		   $this->ishtml = true;	
		   SetSessionParam('template',$this->template);
		   SetSessionParam('mailbody',$this->mailbody);		
		   //reset embeded images   
		   $this->images = array();		 
	       SetSessionParam('images',null);
		   //reset attachments   
		   $this->attachments = array();		 
	       SetSessionParam('attachments',null);		   
		 }
		 elseif (GetReq('t')=='cpsubloadimage') {//..OR ADD EMBEDED IMAGE
		 
		   if ($this->mailbody) 
		     $mailbody = $this->mailbody;
		   else 
		     $mailbody .= GetParam('mail_text')?GetParam('mail_text'):$this->get_mail_body();			 
		 
		   /*if ($this->ishtml == true) {
		     $this->htmleditor = new tinyMCE('textareas','ADVANCEDFULL',1,'images',$this->dirdepth);
		     $mbody = $this->htmleditor->render('mail_text','100%',$rows+10,$mailbody);		   
		   }			 
		   else {
             $mbody = "<DIV class=\"monospace\"><TEXTAREA style=\"width:100%\" NAME=\"mail_text\" ROWS=$rows cols=60 wrap=\"virtual\">";	 
             $mbody .= $mailbody; 
             $mbody .= "</TEXTAREA></DIV>";
		   }*/
		   $mbody = $this->render_textarea($mailbody,$rows);	 
		   	
		   if ($img = GetParam('image'))
	         $this->images[] = $img;	
		   //echo '>',GetParam('image');	 
	       SetSessionParam('images',serialize($this->images));		   		   
		 }		
		 elseif (GetReq('t')=='cpsubattach') {//..OR ATTACH A FILE
		   
		   if ($this->mailbody) 
		     $mailbody = $this->mailbody;
		   else 
		     $mailbody .= GetParam('mail_text')?GetParam('mail_text'):$this->get_mail_body();			 
		 
		   /*if ($this->ishtml == true) {
		     $this->htmleditor = new tinyMCE('textareas','ADVANCEDFULL',1,'images',$this->dirdepth);
		     $mbody = $this->htmleditor->render('mail_text','100%',$rows+10,$mailbody);		   
		   }			 
		   else {
             $mbody = "<DIV class=\"monospace\"><TEXTAREA style=\"width:100%\" NAME=\"mail_text\" ROWS=$rows cols=60 wrap=\"virtual\">";	 
             $mbody .= $mailbody; 
             $mbody .= "</TEXTAREA></DIV>";
		   }	*/	
		   
		   $mbody = $this->render_textarea($mailbody,$rows);
		   
		   if ($attachedfile = GetParam('attachment'))		   
	         $this->attachments[] = $attachedfile;	
		   //echo '>',GetParam('attachment');	 
	       SetSessionParam('attachments',serialize($this->attachments));		   		   
		 }		
		 elseif (GetReq('t')=='cpsubsend') {//..OR SEND A MAIL
		   if ($this->mailbody) 
		     $mailbody = $this->mailbody;
		   else 
		     $mailbody .= GetParam('mail_text')?GetParam('mail_text'):$this->get_mail_body();			 
		 
		   /*if ($this->ishtml == true) {
		     $this->htmleditor = new tinyMCE('textareas','ADVANCEDFULL',1,'images',$this->dirdepth);
		     $mbody = $this->htmleditor->render('mail_text','100%',$rows+10,$mailbody);		   
		   }			 
		   else {
             $mbody = "<DIV class=\"monospace\"><TEXTAREA style=\"width:100%\" NAME=\"mail_text\" ROWS=$rows cols=60 wrap=\"virtual\">";	 
             $mbody .= $mailbody; 
             $mbody .= "</TEXTAREA></DIV>";
		   }	*/
		   
		   $mbody = $this->render_textarea($mailbody,$rows);		 
		 } 	 
		 elseif ($this->ishtml = true) {//..OR JUST SHOW NEW HTML MAIL ..
		   //$this->htmleditor = new tinyMCE('textareas','ADVANCEDFULL',1,'images',$this->dirdepth);
		   
		   if ($this->mailbody) 
		     $mbody = $this->mailbody;
		   else {
		     $mbody = GetParam('mail_text')?GetParam('mail_text'):$this->get_mail_body();	
             $mbody .= $this->spam_conditions_text($this->antispamlink,0,$this->ishtml);			 
 		     $this->mailbody = $mbody;
			 SetSessionParam('mailbody',$mbody);
		   }	 
			 		   
		   //$mbody = $this->htmleditor->render('mail_text','100%',$rows+10,$mbody);
		   
		   $mbody = $this->render_textarea($mbody,$rows);
		   
		   //reset arrays
		   SetSessionParam('template',null);	
		   SetSessionParam('mailbody',null);	
		   //reset embeded images   
		   $this->images = array();		 
	       SetSessionParam('images',null);
		   //reset attachments   
		   $this->attachments = array();		 
	       SetSessionParam('attachments',null);			   		 		 
		 }	 
		 else { //OR IN TEXT MODE
		 
		   $this->mailbody = null;
		 
           //$mbody = "<DIV class=\"monospace\"><TEXTAREA style=\"width:100%\" NAME=\"mail_text\" ROWS=$rows cols=60 wrap=\"virtual\">";
		   if ($this->mailbody) 
		     $mbody .= $this->mailbody;
		   else {
		     $mbody .= GetParam('mail_text')?GetParam('mail_text'):$this->get_mail_body();		 
             $mbody .= $this->spam_conditions_text($this->antispamlink,0,0);
		   }	 
		   
           //$mbody .= "</TEXTAREA></DIV>";
		   
		   $mbody = $this->render_textarea($mbody,$rows);
		   
		   $this->ishtml = false;	
		   SetSessionParam('template',null);	
		   SetSessionParam('mailbody',null);	
		   //reset embeded images   
		   $this->images = array();		 
	       SetSessionParam('images',null);
		   //reset attachments   
		   $this->attachments = array();		 
	       SetSessionParam('attachments',null);			   		   	   
		 }	 
		 
	     $mb[] = $mbody;
	     $mbattr[] = "left;99%";
	     $mbwin = new window('',$mb,$mbattr);
	     $winout .= $mbwin->render("center::100%::0::group_win_body::left::0::0::");	
	     unset ($mbwin);	  
	 
	 
		 //alternate text body
		 if (!$editmode) {
		 
		   if (GetParam('alttext')) {
		     $defaulttext = GetParam('alttext');
		   }
		   else {
		     $defaulttext = localize('_NOTVIEW',0);
		     $defaulttext .= localize('_NOTVIEW',1);		   
		     $defaulttext .= $this->template ? $this->template :'Alternate Text message for non html viewers';
		     $defaulttext .= $this->spam_conditions_text($this->antispamlink,0,0);		   
		   }  
		 
           $alttext = "<DIV class=\"monospace\"><TEXTAREA style=\"width:100%\" NAME=\"alttext\" ROWS=5 cols=60 wrap=\"virtual\">";
		   $alttext .= $defaulttext;
		   $alttext .= "</TEXTAREA></DIV>";;		 
	       $alt[] = $alttext;
 	       $toattr[] = "left;80%;";	
	       $twin = new window('',$alt,$toattr);
	       $winout .= $twin->render("center::100%::0::group_article_selected::left::0::0::");	
	       unset ($twin);	
		 
		 }//editmode	
		 	 
	     //main window
	     $mywin = new window('',$winout);
	     $out .= $mywin->render();	
	     unset ($mywin);	 
	 
	 
	     //batch num restored
		 if ($activebatch) {
		   $cmd = "Previous campaign has stoped at batch num (reset or bypass)";
		   $cm = ($activebatch-1) * $this->batch; 
		   $cmd .= "<br>Campaign stopped at " . $cm . " mails from "  . $this->subs_no . ' subscribers!<br>'; 
		   
		   if ($this->bypass_method==true)			   
		     $cmd .= "<input style=\"width:30\" type=\"text\" name=\"batchrestore\" maxlenght=\"4\" value=\"".($activebatch-1) ."\">"; 	   
		   //$cmd .= "<br>";
		 }
		 else
		   $cmd = $this->subs_no . ' subscribers!<br>';
	 
	     //BUTTONS
		 if ($action) {
           $cmd .= "<input type=\"hidden\" name=\"FormName\" value=\"SendCMail\">"; 
           $cmd .= "<INPUT type=\"submit\" name=\"submit\" value=\"" . $submit . "\">&nbsp;";  
           $cmd .= "<INPUT type=\"hidden\" name=\"FormAction\" value=\"" . $action . "\">";			 
		 }
		 else {
           $cmd .= "<input type=\"hidden\" name=\"FormName\" value=\"SendCMail\">"; 
           $cmd .= "<INPUT type=\"submit\" name=\"submit\" value=\"" . localize('_SENDCMAIL',getlocal()) . "\">&nbsp;";  
           $cmd .= "<INPUT type=\"hidden\" name=\"FormAction\" value=\"" . "sendcmail" . "\">";	 
		 }  
		 
	     $but[] = $cmd;
	     $battr[] = "left";
	     $bwin = new window('',$but,$battr);
	     $out .= $bwin->render("center::100%::0::group_article_selected::left::0::0::");	
	     unset ($bwin);
	 	     
         $out .= "</FORM>"; 
		 
	     if (defined('RCTEDIT_DPC')) {
		   //html
		   $out1_title = "<B>" . localize('_HTMLTEMPLATE',getlocal()) . ":</B>&nbsp;" . $this->template;
	       $out1 = GetGlobal('controller')->calldpc_method('rctedit.show_files use template+Load Newsletter+'.$taction.'+'.$this->newsletter_path.'+'.implode(',',$this->templatetypes));		  
		   if (defined('RCUPLOAD_DPC')) 
		     $out1 .= GetGlobal('controller')->calldpc_method('rcupload.uploadform use cpsubuploadtemplate+/'.$this->newsletter_path.'+++'.$this->maxsize); 
			 
	       $awin = new window($out1_title,$out1);
	       $out .= $awin->render("center::100%::0::group_article_selected::left::0::0::");	
	       unset ($awin);	
		   			 
		   //embeded images
		   if ((is_array($this->images)) && (!empty($this->images))) {
		     //print_r($this->images);
		     $myimages = implode(',',$this->images);
		   }	 
		   else
		     $myimages = 'none';	 
		   $out2_title = "<B>" . localize('_ADDIMAGES',getlocal()) . ":</B>&nbsp;" . '<'.$myimages.'>';
	       $out2 = GetGlobal('controller')->calldpc_method('rctedit.show_files use image+Embed Image+'.$iaction.'+'.$this->imgpath.'+'.implode(',',$this->imgtypes));		   
		   if (defined('RCUPLOAD_DPC')) 
		     $out2 .= GetGlobal('controller')->calldpc_method('rcupload.uploadform use cpsubuploadimage+/'.$this->imgpath.'+++'.$this->maxsize);		   
			 
	       $bwin = new window($out2_title,$out2);
	       $out .= $bwin->render("center::100%::0::group_article_selected::left::0::0::");	
	       unset ($bwin);			 
			 
		   //attachments
		   if ((is_array($this->attachments)) && (!empty($this->attachments))) {
		     //print_r($this->images);
		     $mydocs = implode(',',$this->attachments);
		   }	 
		   else
		     $mydocs = 'none';		   
		   $out3_title = "<B>" . localize('_ATTACHMENTS',getlocal()) . ":</B>&nbsp;" . '<'.$mydocs.'>';
	       $out3 = GetGlobal('controller')->calldpc_method('rctedit.show_files use attachment+Attach File+'.$daction.'+'.$this->docpath.'+'.implode(',',$this->doctypes));		   
		   if (defined('RCUPLOAD_DPC')) 
		     $out3 .= GetGlobal('controller')->calldpc_method('rcupload.uploadform use cpsubuploaddocument+/'.$this->docpath.'+++'.$this->maxsize);	
			 
	       $cwin = new window($out3_title,$out3);
	       $out .= $cwin->render("center::100%::0::group_article_selected::left::0::0::");	
	       unset ($cwin);				 
		 } 		 
		 
	     $mywin = new window(localize('_SMTPSENDFORM',getlocal()) . ' ['.GetParam('id').']',$out);
	     $wout = $mywin->render();	
	     unset ($mywin);		 
		 
		 return ($wout);     
    }		
	
	//override
	function show_subscribers() {
	
	   if ($this->msg) $out = $this->msg;
	   
	   $commands = seturl("t=cpscampreset&editmode=".GetReq('editmode'),"Reset Campaign") . '|'.  seturl("t=cpadvsubscribe&editmode=".GetReq('editmode'),"Subscribe");
	   
	   $myadd = new window('',$commands);
	   $toprint .= $myadd->render("center::100%::0::group_article_selected::right::0::0::");	   
	   unset ($myadd);  
	
	   if (!GetReq('editmode')) {
	   
	     $toprint .= $this->show_grids(); 
			  	 
	     $toprint .= $this->alphabetical();	
	   
	     $dater = new datepicker("/MDYT");	
	     $toprint .= $dater->renderspace(seturl("t=cpsubscribers"),"cpsubscribers");		 
	     unset($dater);		   	 
		 
         $mywin = new window($this->title,$toprint);
         $out .= $mywin->render();	
	   }
	   else	 	
	     $out .= $this->show_grids(1);  
	  
	   return ($out);		
	}	
	
    function subscribeform()  { 		

       $filename = seturl("t=cpsubscribe&editmode=".GetReq('editmode'));      
    
       $toprint  = "<FORM action=". "$filename" . " method=post>";
       $toprint .= "<P><FONT face=\"Arial, Helvetica, sans-serif\" size=1><STRONG>";
	   $toprint .= $this->t_entermail;
	   $toprint .= "</STRONG><br><INPUT type=\"text\" name=submail maxlenght=\"64\" size=25><br>"; 
	   
       $toprint .= "<DIV class=\"monospace\"><TEXTAREA style=\"width:100%\" NAME=\"csvmails\" ROWS=20 cols=60 wrap=\"virtual\">";
	   $toprint .=  GetParam('csvmails');		 
       $toprint .= "</TEXTAREA></DIV><br>";	   
	   

	   //$toprint .= "<input type=\"submit\" name=\"FormAction\" value=\"cpsubscribe\">&nbsp;"; 
       //$toprint .= "<input type=\"submit\" name=\"FormAction\" value=\"cpunsubscribe\">";	  
       $toprint .= "<input type=\"hidden\" name=\"FormName\" value=\"cpsubscribe\">"; 
       $toprint .= "<INPUT type=\"submit\" name=\"submit\" value=\"" . localize('_SUBSCR',getlocal()) . "\">&nbsp;";  
       $toprint .= "<INPUT type=\"hidden\" name=\"FormAction\" value=\"" . "cpsubscribe" . "\">";	 	   
	   	    
       $toprint .= "</FONT></FORM>";
	   
	   $data2[] = $toprint; 
  	   $attr2[] = "left";

	   $swin = new window(localize('_SUBSCR',getlocal()),$data2,$attr2);
	   $out .= $swin->render("center::100%::0::group_dir_body::left::0::0::");	
	   unset ($swin);	 

       return ($out);
    }	

    function sendmail($from,$to,$subject,$mail_text='',$is_html=false) {
       $sFormErr = GetGlobal('sFormErr');
	   $err = null;
	   $ccs = GetParam('cc'); //echo $ccs;
	   
	   if ($ccs)
         $ccaddress = explode(';',$ccs);		      
	   $bccs = GetParam('bcc');	//echo $bccs;	 
	   if ($ccs)
         $bccaddress = explode(';',$bccs);			 
	   //global $info; //receives errors	 

       if ((checkmail($to)) && ($subject)) {//echo $to,'<br>';
	   
         $smtpm = new smtpmail($this->encoding,$this->mailuser,$this->mailpass,$this->mailname,$this->mailserver);
		   	   
         if ((SMTP_PHPMAILER=='true') || ($method=='SMTP')) {
		   //echo 'smtp';	
		   $smtpm->from($from,$this->mailname);		   
		   $smtpm->to($to);  
		   if (!empty($ccaddress)) {
		     foreach ($ccaddress as $cc) {
			   //echo $cc,'<br>';
			   if (trim($cc)) {
		         //$smtpm->cc($cc);//ONLY WIN32  
			     $smtpm->to($cc);
			   }
			 }  
		   }  	 
		   if (!empty($bccaddress)) {
		     foreach ($bccaddress as $bcc) {
			   //echo $bcc,'<br>';		
			   if (trim($bcc)) {	 
		         //$smtpm->bcc($bcc); //ONLY WIN32  
			     $smtpm->to($bcc);  
			   }	 
			 }  
		   }		   
		   $smtpm->subject($subject);
		   $smtpm->body($mail_text,$is_html);
		   
           # Optional alternate text-only body:
           $smtpm->smtp->AltBody = GetParam('alttext');		 
		   # url images to Embeded images replacement
		   if (!empty($this->images)) {
		     foreach ($this->images as $a=>$image) {
		       if ($image) {
			     foreach ($this->imgtypes as $ext) {		 
			       if (strstr($image,$ext)) {
				     $myext = str_replace('.','',$ext);//without dot
                     $err .= $smtpm->smtp->AddEmbeddedImage($this->template_images_path . $image, "1", $image, "base64", "image/$myext");		 
				   }  
			     }
			   }  
		     }	  
		   }
           # Attached file containing this source code:
		   if (!empty($this->attachments)) {
		     foreach ($this->attachments as $a=>$attachment) {
		       if ($attachment) {
			     foreach ($this->doctypes as $ext) {		 
			       if (strstr($attachment,$ext)) {		
				     $myext = str_replace('.','',$ext);//without dot???? switch	 
                     $err .= $smtpm->smtp->AddAttachment($this->template_document_path . $attachment, $attachment, "base64", "text/plain");
				   }  
			     }
			   }  
		     }
		   }		   			   	   
	     }
         elseif ((SENDMAIL_PHPMAILER=='true') || ($method=='SENDMAIL')) {	  	   
		   //echo 'phpmailer';
		   $smtpm->from($from,$this->mailname);		   
		   $smtpm->to($to);  
		   if (!empty($ccaddress)) {
		     foreach ($ccaddress as $cc) {
			   //echo $cc,'<br>';			 
			   if (trim($cc)) {			 
		         //$smtpm->cc($cc); //ONLY WIN32  
			     $smtpm->to($cc);
			   }	 
			 }  
		   }
		   if (!empty($bccaddress)) {
		     foreach ($bccaddress as $bcc) {
 			   //echo $bcc,'<br>';
			   if (trim($bcc)) {				   
		         //$smtpm->bcc($bcc);//ONLY WIN32   
			     $smtpm->to($bcc);
			   }	 
			 }  
		   }			    
		   $smtpm->subject($subject);
		   $smtpm->body($mail_text,$is_html);		
		   
           # Optional alternate text-only body:
           $smtpm->smtp->AltBody = GetParam('alttext');		 
		   # url images to Embeded images replacement
		   if (!empty($this->images)) {
		     foreach ($this->images as $a=>$image) {
		       if ($image) {
			     foreach ($this->imgtypes as $ext) {		 
			       if (strstr($image,$ext)) {
				     $myext = str_replace('.','',$ext);//without dot
                     $err .= $smtpm->smtp->AddEmbeddedImage($this->template_images_path . $image, "1", $image, "base64", "image/$myext");		 
				   }  
			     }
			   }  
		     }	  
		   }
           # Attached file containing this source code:
		   if (!empty($this->attachments)) {
		     foreach ($this->attachments as $a=>$attachment) {
		       if ($attachment) {
			     foreach ($this->doctypes as $ext) {		 
			       if (strstr($attachment,$ext)) {		
				     $myext = str_replace('.','',$ext);//without dot???? switch	 
                     $err .= $smtpm->smtp->AddAttachment($this->template_document_path . $attachment, $attachment, "base64", "text/plain");
				   }  
			     }
			   }  
		     }
		   }		      
		 } 
		 else {
		   //echo 'default';	
		   $smtpm->to($to); 
		   $smtpm->from($from); 
		   $smtpm->subject($subject);
		   $smtpm->body($mail_text);			   			   	    
		 }
			 
		 $err .= $smtpm->smtpsend();
		 unset($smtpm);				 
		  			     	  	
  	     if (!$err) {
		   SetGlobal('sFormErr',localize('_MLS2',getlocal()));	//send message ok
		   return true;
		 }         
		 else { 
		   SetGlobal('sFormErr',localize('_MLS9',getlocal()).'('.$err.')');	//error
		   setInfo($err);//$info); //smtp error = global info
		 }  
       }
       else 
	     SetGlobal('sFormErr',localize('_MLS4',getlocal()));
		 
	   return (false);	  	   
    }  	
	
	function sendit($from,$to,$subject,$mail_text='',$subscribers=null,$is_html=false) {

		 $i = 0;
		 $meter = 0;
	     //echo $subscribers,'>';
		 //echo $is_html,'>';
		 
		 $one_receipinet = $this->sendmail($from,$to,$subject,$mail_text,$is_html);  
		 
		 
         //if (defined('RCSHSUBSCRIBERS_DPC') && $subs)  {//bulk mail
		 if ($subscribers) {
		    //$mlist = GetGlobal('controller')->calldpc_method('rcshsubscribers.getmails');
			//echo $mlist;
			//send bulk mail
			$mails = explode(";",$subscribers);//$mlist);
			//print_r($mails);
			foreach ($mails as $z=>$m) {
			  //contactmail::sendit($from,$m,$subject,$mail_text); 
			  $meter += $this->sendmail($from,$m,$subject,$mail_text,$is_html);
				
			  $i+=1;
			}
			
			SetGlobal('sFormErr',($meter?$meter:0) . ' mail(s) send from ' . ($i) . ' mail(s) in queue');		 
		 }
		 else {
	       SetGlobal('sFormErr',($one_receipinet?$one_receipinet:0) . ' mail send from ' . '1 mail in queue');		 
		   $i = $one_receipinet;
		 }  
		 //echo '>>>>>>>>>>>>>>>>>>>>',$i,'<<<<<<<<<<<<<<<<<<<<<<<<<br>';
		 return ($i);	
    }	
	
	function upload_element($isdocorimage=null) {
      $sFormErr = GetGlobal('sFormErr');	
	
      if (defined('RCUPLOAD_DPC')) {
	     switch ($isdocorimage) { 
		   case 1  : $upath = $this->template_documents_path; break;
		   case 2  : $upath = $this->template_images_path; break;		   
	       default : $upath = $this->newsletter_htmlpath;
		 }

		 //$ret =GetGlobal('controller')->calldpc_method('rcupload.upload_file use +'.$upath.'+1');	
		 $ret = $this->upload_file(null,$upath,1);		 
	  }	 
	  else	 
        $ret = null;
	  
	  SetGlobal('sFormErr',$ret);
	  return ($ret);
	}
	
	//copied form rcupload
    function upload_file($myfilename=null, $mypath=null, $isrootpath=null) {
	     $topath = $mypath ? $mypath :GetParam("topath");
	   
	     //echo $myfilename,'>';
	     if ($_FILES['uploadfile'])  {

          $attachedfile = $_FILES['uploadfile'];
		  //print_r($attachedfile);
		  
		  if ($myfilename)
		    $filename = $myfilename;
		  else	
		    $filename = $attachedfile['name'];					   
		
		  if ($pp=$topath!='reset')  {//param at upload form   
		    $myfilepath = $this->tpath . '/'. $topath . "/" . $filename;
		  }	
		  else {
		    if ($isrootpath)
			  $myfilepath = $this->topath . "/" . $filename;
			else
		      $myfilepath = $this->tpath . "/" . $filename;
		  }	
			
          //echo $myfilepath;
		  //copy it to admin write-enabled directory				   
		  if (@copy($attachedfile['tmp_name'],$myfilepath)) {
		
    	   setInfo("Upload " .$attachedfile['name']. " ok!");
		   $this->msg = "Upload " .$attachedfile['name']. " ok!"; //MEANS ERROR
		   //echo $this->msg;
		  }
		  else {
		
		   setInfo("Failed to upload file " . $attachedfile['name'] . " ! (Size Error?)"); 				   
		   $this->msg = "Failed to upload file " . $attachedfile['name'] . " ! (Size Error?)";
		   //echo $this->msg;
		  }
		
		  $this->post = true;
        }
		else
		  $this->post = false;
		  
		return ($this->msg);  
	}	
	
	function spam_conditions_text($say='UNSUBSCRIBE',$isutf8=false,$ishtml=false) {
	
	  $br = $ishtml?'<br>':"\r\n";
	  $mylink = "<a href=\"" . $this->url . $this->infolder . '/?t=unsubscribe' ."\">".$say."</a>"; //seturl('t=unsubscribe',$say);
	  $mynakedlink = $this->url . $this->infolder . '/?t=unsubscribe';
	
	  $text = $br. $br . $br ."
GREEK
Αυτο το e-mail δεν μπορει να θεωρηθεί spam εφόσον αναγράφονται τα στοιχεία του αποστολέα και διαδικασίες διαγραφής απο την λίστα παραληπτών.  
Αν είσαστε σε αυτή τη λίστα κατα λάθος ή για οποιονδήποτε άλλο λογο θέλετε να διαγραφεί το e-mail απο αυτή τη λίστα παραληπτών e-mail απλά πατήστε εδώ <@>.   
Το μήνυμα πληρεί τις προυποθέσεις της Ευρωπαικής Νομοθεσίας περί διαφημιστικών μηνυμάτων. Κάθε μήνυμα θα πρέπει να φέρει τα πλήρη στοιχεια του αποστολέα ευκρινώς και θα πρέπει να δίνει στο δέκτη τη δυνατότητα διαγραφής. 
(Directiva 2002/31/CE του Ευρωπαικού Κοινοβουλίου). 

ENGLISH
This e-mail can not be considered spam as long as we include: Contact information & remove instructions. 
If you have somehow gotten on this list in error, or for any other reason would like to be removed,  please click here <@>. 
This email and any files transmitted with it are confidential and intended solely for the use of the individual or entity to whom they are addressed. Any unauthorized disclosure, use of dissemination, either whole or partial, is prohibited.
(Relative as A5-270/2001 of European Council).	  
	  ";
	  
	  $textutf8 = $br. $br . $br ."
GREEK
Αυτο το e-mail δεν μπορει να θεωρηθεί spam εφόσον αναγράφονται τα στοιχεία του αποστολέα και διαδικασίες διαγραφής απο την λίστα παραληπτών.  
Αν είσαστε σε αυτή τη λίστα κατα λάθος ή για οποιονδήποτε άλλο λογο θέλετε να διαγραφεί το e-mail απο αυτή τη λίστα παραληπτών e-mail απλά πατήστε εδώ <@>.   
Το μήνυμα πληρεί τις προυποθέσεις της Ευρωπαικής Νομοθεσίας περί διαφημιστικών μηνυμάτων. Κάθε μήνυμα θα πρέπει να φέρει τα πλήρη στοιχεια του αποστολέα ευκρινώς και θα πρέπει να δίνει στο δέκτη τη δυνατότητα διαγραφής. 
(Directiva 2002/31/CE του Ευρωπαικού Κοινοβουλίου). 

ENGLISH
This e-mail can not be considered spam as long as we include: Contact information & remove instructions. 
If you have somehow gotten on this list in error, or for any other reason would like to be removed,  please click here <@>. 
This email and any files transmitted with it are confidential and intended solely for the use of the individual or entity to whom they are addressed. Any unauthorized disclosure, use of dissemination, either whole or partial, is prohibited.
(Relative as A5-270/2001 of European Council).	  
	  ";
	  
	  if (($isutf8) || ($this->encoding='utf-8')) {
	    //echo 'utf8';
	    $out = $ishtml?str_replace('<@>',$mylink,$text):str_replace('<@>',$mynakedlink,iconv($this->encoding, "UTF-8",$textutf8));	
		$ret = $ishtml?nl2br($out):$out;
	  }
	  else {
	    $out = $ishtml?str_replace('<@>',$mylink,$text):str_replace('<@>',$mynakedlink,$text);	
		$ret = $ishtml?nl2br($out):$out;		  
	  }
	  
	  return ($ret);
	}	
	
	function get_mail_body() {
	       $mailbody = null;
	
		   if (GetReq('editmode')) {
		   
    		 $p = explode('/',$htmlfile);
			 $fa = array_pop($p);
    		 $publichtmlfile = getcwd() . '/html/' . $fa;
    		 $tempname = getcwd() . '/mail_body.tmp';//_'.time().'.tmp';
			 
			 if (strstr($fa,'.')) {//if has extension..is a file .... else is a dir=empty	
			   copy($publichtmlfile,$tempname);
			   $mailbody = file_get_contents($tempname);
			 }
			 else {//read html attachment from db
	           //echo 'A';
	  		   if ((defined('RCITEMS_DPC')) && ($attachtype = GetReq('type'))) {
	      		 $mailbody = GetGlobal('controller')->calldpc_method("rcitems.has_attachment2db use " . GetReq('id') ."+$attachtype+1");
	  		   }
			   else
			     $mailbody = null;
			 }			 		   
		   }
		   else
		     $mailbody = null;	
			 
		   return ($mailbody);	 
	}
	
	function render_textarea($mailbody=null,$rows=10) {
	
	  if (GetReq('editmode')) {	
	    $out = '<div>'; 
        $out .= "<textarea id='mail_text' name='mail_text'>".$this->load_spath($mailbody)."</textarea>";	
	    $out .= "<script type='text/javascript'>
	           CKEDITOR.replace('mail_text',
			   {
	           skin : 'office2003', 
			   fullpage : true, 
			   extraPlugins :'docprops',
               filebrowserBrowseUrl : '/cp/ckfinder/ckfinder.html',
	           filebrowserImageBrowseUrl : '/cp/ckfinder/ckfinder.html?type=Images',
	           filebrowserFlashBrowseUrl : '/cp/ckfinder/ckfinder.html?type=Flash',
	           filebrowserUploadUrl : '/cp/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	           filebrowserImageUploadUrl : '/cp/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
	           filebrowserFlashUploadUrl : '/cp/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
	           filebrowserWindowWidth : '1000',
 	           filebrowserWindowHeight : '700'			   
			   }		   
			   );
			   CKEDITOR.config.fullPage=true;
               CKEDITOR.config.entities = false;
               CKEDITOR.config.entities_greek = false;
               CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;			   		
		       </script>"; 
	    $out .= '</div>';	
	  }
	  else {
	       //tinymce
		   if ($this->ishtml == true) {
		     $this->htmleditor = new tinyMCE('textareas','ADVANCEDFULL',1,'images',$this->dirdepth);
		     $out = $this->htmleditor->render('mail_text','100%',$rows+10,$mailbody);		   
		   }			 
		   else {		
             $out = "<DIV class=\"monospace\"><TEXTAREA style=\"width:100%\" NAME=\"mail_text\" ROWS=$rows cols=60 wrap=\"virtual\">";	 
             $out .= $mailbody; 
             $out .= "</TEXTAREA></DIV>";	
		   }	   
	  }
	  
	  return ($out);
	}
	
    function load_spath($text=null) {
	
	   if ($this->ckeditor)	
	     return ($text);	

       $p1 = str_replace("images/","../images/",$text);

       $ret = $this->handle_phpdac_tags($p1);
	   //echo $ret;
       return ($ret);

    }

    function unload_spath($text=null) {
	
	   if ($this->ckeditor)	
	     $p1 = str_replace("/images/",$this->url.$this->infolder."/images/",$text);
       else
         $p1 = str_replace("../images/",$this->url.$this->infolder."/images/",$text);

       $ret = $this->handle_phpdac_tags($p1,1);
       return ($ret);

    }	
	
    function handle_phpdac_tags($text,$savemode=null) {

      if ($savemode==null) {//load
       $p1 = str_replace("<?","<phpdac5>",$text);
       $p2 = str_replace('?>','</phpdac5>',$p1);
      }
      else {//save mode
       $p1 = str_replace("<phpdac5>","<?",$text);
       $p2 = str_replace('</phpdac5>','?>',$p1);
      }

      return $p2;
    }		
     	
};
}	
}
else die("SMTPMAIL DPC REQUIRED!");
?>