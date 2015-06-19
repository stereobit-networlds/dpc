<?php
if ((defined("DATABASE_DPC")) && (defined("SMTPMAIL_DPC"))) {

$__DPCSEC['RCSHSUBSQUEUE_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("RCSHSUBSQUEUE_DPC")) && (seclevel('RCSHSUBSQUEUE_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCSHSUBSQUEUE_DPC",true);

$__DPC['RCSHSUBSQUEUE_DPC'] = 'rcshsubsqueue';

$v = GetGlobal('controller')->require_dpc('crypt/ciphersaber.lib.php');
require_once($v); 

$d = GetGlobal('controller')->require_dpc('shop/rcshsubscribers.dpc.php');
require_once($d);

GetGlobal('controller')->get_parent('RCSHSUBSCRIBERS_DPC','RCSHSUBSQUEUE_DPC');

$__EVENTS['RCSHSUBSQUEUE_DPC'][99]='cpactivatequeuerec';

$__ACTIONS['RCSHSUBSQUEUE_DPC'][99]='cpactivatequeuerec';

$__LOCALE['RCSHSUBSQUEUE_DPC'][0]='RCSHSUBSQUEUE_DPC;Subscribers mail queue;Subscribers mail queue';
$__LOCALE['RCSHSUBSQUEUE_DPC'][1]='_MASSSUBSCRIBE;Mass subscribe;Μαζική εγγραφή συνδρομητών';
$__LOCALE['RCSHSUBSQUEUE_DPC'][2]='_MAILCAMPAIGNS;Mail campaigns;Αποστολές σε συνδρομητές';
$__LOCALE['RCSHSUBSQUEUE_DPC'][3]='_active;Active;Ενεργό';
$__LOCALE['RCSHSUBSQUEUE_DPC'][4]='_sender;Sender;Αποστολέας';
$__LOCALE['RCSHSUBSQUEUE_DPC'][5]='_receiver;Receiver;Παραλήπτης';
$__LOCALE['RCSHSUBSQUEUE_DPC'][6]='_reply;Views;Εμφανίσεις';
$__LOCALE['RCSHSUBSQUEUE_DPC'][7]='_subject;Subject;Θέμα';
$__LOCALE['RCSHSUBSQUEUE_DPC'][8]='_id;Id;Α/Α';
$__LOCALE['RCSHSUBSQUEUE_DPC'][9]='_HTMLSELECTEDITEMS;Selected items;Επιλεγμένα αντικείμενα';
$__LOCALE['RCSHSUBSQUEUE_DPC'][10]='_inlist;List;Σε λίστα';
$__LOCALE['RCSHSUBSQUEUE_DPC'][11]='_sendtousers;Send to Users;Αποστολή σε χρήστες';
$__LOCALE['RCSHSUBSQUEUE_DPC'][12]='_sendtolists;Send to Lists;Αποστολη σε λίστες';
$__LOCALE['RCSHSUBSQUEUE_DPC'][13]='_savenewsletter;Save Newsletter;Αποθήκευση περιεχομένου';
$__LOCALE['RCSHSUBSQUEUE_DPC'][14]='_options;Options;Ρυθμίσεις';
$__LOCALE['RCSHSUBSQUEUE_DPC'][15]='_ACTIVE;Active;Ενεργό';

class rcshsubsqueue extends rcshsubscribers {

    var $userLevelID;	
	var $result;
	var $path, $post, $msg, $urlpath, $url;
	var $inpath, $languages, $title, $default_lang;
	
	var $trackmail, $overwrite_encoding;
	var $appname;
    var $dhtml, $js;

	function rcshsubsqueue() {
	  $UserSecID = GetGlobal('UserSecID'); 	
      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);	
	  
	  rcshsubscribers::rcshsubscribers();	  
	  	     
	  $this->title = localize('RCSHSUBSQUEUE_DPC',getlocal());		  
	    
	  $this->languages = remote_arrayload('SHELL','languages',$this->prpath);
	  $this->default_lang = remote_paramload('SHELL','dlang',$this->prpath);
	  
	  $this->batch = 400;  //mails in queue pre batch
	  $this->auto_refresh = GetParam('refresh')?GetParam('refresh'):0;
	  $this->timeout = 3601+1000;//one hour+1000 sec	  
	  
	  $this->appname = paramload('ID','instancename');
	  $track = remote_paramload('RCSHSUBSQUEUE','track',$this->prpath);
	  $this->trackmail = $track?true:false;	

      $this->overwrite_encoding = remote_paramload('RCSHSUBSQUEUE','sendencoding',$this->prpath);

	  //dynamic html loader
	  $this->dhtml = paramload('FRONTHTMLPAGE','dhtml');
	  //if ($this->dhtml)
       //  $this->js = new jscript;

	  //override smtp,rcshsubscribe settings
	  $_realm = remote_paramload('RCSHSUBSQUEUE','realm',$this->prpath);
	  $this->mailname = $_realm ? $_realm : 
	                   ($this->mailname ? $this->mailname : remote_paramload('SMTPMAIL','realm',$this->prpath));
	  $_user = remote_paramload('RCSHSUBSQUEUE','mailuser',$this->prpath);
	  $this->mailuser = $_user ? $_user : 
	                   ($this->mailuser ? $this->mailuser : remote_paramload('SMTPMAIL','user',$this->prpath));
	  $_pass = remote_paramload('RCSHSUBSQUEUE','mailpass',$this->prpath);
	  $this->mailpass = $_pass ? $_pass :
	                   ($this->mailpass?$this->mailpass:remote_paramload('SMTPMAIL','password',$this->prpath));
	  $_server = remote_paramload('RCSHSUBSQUEUE','mailserver',$this->prpath);	   
	  $this->mailserver = $_server ? $_server : 
	                    ($this->mailserver ? $this->mailserver : remote_paramload('SMTPMAIL','smtpserver',$this->prpath));	
	}
	
	
    function event($event) {
	
	 /////////////////////////////////////////////////////////////
	 if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//
	 /////////////////////////////////////////////////////////////	
	 
	 switch ($event) {
	    case 'cpactivatequeuerec'   : $this->activate_queue_rec(); break;
	    case 'cpdeactivatequeuerec' : $this->deactivate_queue_rec(); break;
	    case 'cpviewsubsqueue'      : break;
	 
	    case 'cpsubscribe'          : $this->dosubscribe();
		                              $this->mass_subscribe();
			                          break;	

		case 'cpunsubscribe'        : $this->dounsubscribe();				
	                                  break;								
 
        case "cpsubsend"            : //$include_all = GetParam('includeall'); //from form ..all users
	                                  //$this->subs_mail = $this->getmails($include_all); //,sep mails
		                              //$this->current_batch_records = $this->send_mails(); //all in db...
									  $this->send_mails();
				                      break; 	
						   						   
		default                     :  rcshsubscribers::event($event);	
		                               //if ($this->dhtml) 
                        		       //$this->dhtml_javascript("divwin=dhtmlwindow.open('divbox', 'div', 'somediv', '#4: DIV Window Title', 'width=450px,height=300px,left=200px,top=150px,resize=1,scrolling=1'); return false");
	 }
	 
	 if (!GetReq('editmode'))	 
       $this->nitobi_javascript();	 
    }
  
    function action($action) {
	 
	 //////////////////////////////////////////////// test
	 //$testret = $this->mail_limits_boost(null,20,400);
	 
	 
	 switch ($action) {
	 
		case 'cpunsubscribe'       :	 
	    case 'cpsubscribe'         :
        case 'cpadvsubscribe'      :$out .= $this->subscribeform();  break;		 
	 
	    case 'cpactivatequeuerec'  :
	    case 'cpdeactivatequeuerec':
	    case 'cpviewsubsqueue'     : $out = $this->viewMails(); break;
		case 'searchtopic'         : $out = $this->viewMails(); break;
	    case 'cpsubsend'           :		
	    default                    :
                                     $out = rcshsubscribers::action($action);
	 }

	 
	 if ($this->dhtml) {//id already in dev the whole content...not working
	 
       /*$testdiv = "<div id=\"somediv\" style=\"display:none\">
<p style=\"height: 400px\">This is some content within a DIV, shown inside this window instead</p>
</div>";
	 
	    $this->dhtml_javascript("divwin=dhtmlwindow.open('divbox', 'div', 'somediv', '#4: DIV Window Title', 'width=450px,height=300px,left=200px,top=150px,resize=1,scrolling=1'); return false");*/
		
		return ($out);//.$testdiv);
     }		
	 
	 return ($out);
    }  
	
	function dhtml_javascript($code) {
      if (iniload('JAVASCRIPT')) {

		   $js = new jscript;		   
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }		
	}		
	
	//override
	function show_subscribers() {
	
	   if ($this->msg) $out = $this->msg;
	   
	   if ($this->dhtml) {
	       $ajaxlink1 = seturl("t=cpviewsubsqueue&editmode=".GetReq('editmode'));
		   $ajaxlink2 = seturl("t=cpadvsubscribe&editmode=".GetReq('editmode'));	
           $title1 = localize('_MAILCAMPAIGNS',getlocal());	
           $title2 = localize('_MASSSUBSCRIBE',getlocal());		   
	   
		   $this->dhtml_javascript("function openlink1(){ 
ajaxwin=dhtmlwindow.open(\"ajaxbox1\", \"ajax\", \"$ajaxlink1\", \"$title1\", \"width=450px,height=300px,left=300px,top=100px,resize=1,scrolling=1\")
ajaxwin.onclose=function(){return window.confirm(\"Close window ?\")} 
}");
		   $this->dhtml_javascript("function openlink2(){ 
ajaxwin2=dhtmlwindow.open(\"ajaxbox2\", \"ajax\", \"$ajaxlink2\", \"$title2\", \"width=450px,height=300px,left=300px,top=100px,resize=1,scrolling=1\")
//ajaxwin2.onclose=function(){return window.confirm(\"Close window ?\")} 
}");
		   
		   $commands = "<a href=\"#\" onClick=\"openlink1(); return false\">".$title1."</a>";
		   $commands.= '&nbsp;|&nbsp;';
		   $commands.= "<a href=\"#\" onClick=\"openlink2(); return false\">".$title2."</a>";
		   
		   //test DIV....
		   $this->dhtml_javascript("function openlink3(){
divwin=dhtmlwindow.open('divbox', 'div', 'sitems', 'Select Items', 'width=450px,height=300px,left=200px,top=150px,resize=1,scrolling=1'); 
divwin.onclose=function(){return window.confirm(\"Close window ?\")} 
}");		
           $commands.= '&nbsp;|&nbsp;';  
           $commands.= "<a href=\"#\" onClick=\"openlink3(); return false\">"."Select Items"."</a>";

		   $this->dhtml_javascript("function openlink4(){
divwin2=dhtmlwindow.open('divbox2', 'div', 'mailqueue', 'Mail queue', 'width=450px,height=300px,left=200px,top=150px,resize=1,scrolling=1'); 
divwin2.onclose=function(){return window.confirm(\"Close window ?\")} 
}");		
           $commands.= '&nbsp;|&nbsp;';  
           $commands.= "<a href=\"#\" onClick=\"openlink4(); return false\">"."Mail Queue"."</a>";			   
		   
		   $this->dhtml_javascript("function openlink5(){
divwin2=dhtmlwindow.open('divbox3', 'div', 'foptions', 'Options', 'width=450px,height=300px,left=200px,top=150px,resize=1,scrolling=1'); 
divwin2.onclose=function(){return window.confirm(\"Close window ?\")} 
}");		
           $commands.= '&nbsp;|&nbsp;';  
           $commands.= "<a href=\"#\" onClick=\"openlink5(); return false\">"."Options"."</a>";		   
	   }
	   else
	       $commands = seturl("t=cpviewsubsqueue&editmode=".GetReq('editmode'),localize('_MAILCAMPAIGNS',getlocal())) . '|'.  
	                   seturl("t=cpadvsubscribe&editmode=".GetReq('editmode'),localize('_MASSSUBSCRIBE',getlocal()));
	   
	   $myadd = new window('',$commands);
	   $out .= $myadd->render("center::100%::0::group_article_selected::right::0::0::");	   
	   unset ($myadd);  
 	   
	   if (!GetParam('searcht')) //SEARCH TOPIC..hide mail body
	     $out .= $this->show_grids(1);  
	   
	   $out .= $this->viewMails(1);
	  
	   return ($out);		
	}	

	function getMailList($active=null) {
        $db = GetGlobal('db');
		$active = $active?$active:GetReq('active');//when search in...

	   	   
	     $sSQL = "select id,active,sender,receiver,subject,reply,status,mailstatus from mailqueue ";
		  
		 if ($active)
		   $sSQL .= "where active=1 ";
		
		 if ($s = GetParam('searcht')) {//SEARCH TOPIC   
		   if ($active)
		     $sSQL .= "and sender like '%$s%' or receiver like '%$s%' or subject like '%$s%' or body like '%$s%' ";
		   else
		     $sSQL .= "where sender like '%$s%' or receiver like '%$s%' or subject like '%$s%' or body like '%$s%' ";
		 }  
        		 
		 
		 if ($col = GetReq('col'))
		   $sSQL .= "order by " . $col;
		 else
		   $sSQL .= "order by id"; 
		   
		 if (GetReq('sort')<0)
		   $sSQL .= ' DESC';
		   
		 //echo $sSQL;		 
				 
				 
	     $res = $db->Execute($sSQL,2);
	     //print_r ($res);
		 $i=0;
	     if (!empty($res)) { 
	       foreach ($res as $n=>$rec) {
		    $i+=1;
				
			
            $transtbl[] = $i . ";" . 
                         $rec[0] . ";" . $rec[1] . ";" . $rec[2] . ";" . $rec[3] . ";" .
						 $rec[4] . ";" . $rec[5] . ";" . $rec[6] . ";" . $rec[7];						 					 	   
		   }
		   
           //browse
		   //print_r($transtbl); 
		   $ppager = GetReq('pl')?GetReq('pl'):100;
           $browser = new browse($transtbl,null,$this->getpage($transtbl,$this->searchtext));
	       //$out .= $browser->render("mailview",$ppager,$this,1,1,0,0,1,1,1,0);
		   $out .= $browser->render("cpviewsubsqueue",$ppager,$this,1,1,0,0,1,1,1,0);
	       unset ($browser);	
		      
	     }
		 else {
           //empty message
	       $w = new window(null,localize('_EMPTY',getlocal()));
	       $out .= $w->render("center::40%::0::group_win_body::left::0::0::");//" ::100%::0::group_form_headtitle::center;100%;::");
	       unset($w);

		 }		 	
	   
	   return ($out);
	} 
	
	function deactivate_queue_rec() {
         $db = GetGlobal('db');
         $rec = GetReq('rec'); 
	   	   
	     $sSQL = "update mailqueue set active=0,mailstatus='USER_CANCEL' where id=" . $rec;
		 //echo $sSQL;		 
				 
	     $res = $db->Execute($sSQL,1);	
	}	 
	
	function activate_queue_rec() {
         $db = GetGlobal('db');
         $rec = GetReq('rec'); 
	   	   
	     $sSQL = "update mailqueue set active=1,mailstatus='USER_ACTIV' where id=" . $rec;
		 //echo $sSQL;		 
				 
	     $res = $db->Execute($sSQL,1);	
	}	
	
	function getpage($array,$id){
	
	   if (count($array)>0) {
         //while(list ($num, $data) = each ($array)) {
         foreach ($array as $num => $data) {
		    $msplit = explode(";",$data);
			if ($msplit[1]==$id) return floor(($num+1) / $this->pagenum)+1;
		 }	  
		 
		 return 1;
	   }	 
	}		
		
	function viewMails($active=null) {  
	   
	   if (($active) && ($this->dhtml)) //include in div..only activem other div show send mails	
		   $out .= "<div id=\"mailqueue\" style=\"display:none\">";
		   
	   $out .= $this->getMailList($active);

	   if (($active) && ($this->dhtml)) //include in div	
		   $out .= "</div>";	   
		  	 					
	   return ($out);	
	
	}	
	
    function browse($packdata,$view) {
	
	   $data = explode("||",$packdata); //print_r($data);
	
       $out = $this->viewlist($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9]);

	   return ($out);
	}				
		
    function viewlist($i,$id,$a1,$a2,$a3,$a4,$a5,$a6,$a7) {
	   $p = GetReq('p');
	   $a = GetReq('a');
	   
	   $a_link = $a1 ? seturl("t=cpdeactivatequeuerec&rec=$id&editmode=1" , $i):
	                   seturl("t=cpactivatequeuerec&rec=$id&editmode=1" , $i);
	   $b = $a3; //$a2								  	   
	   $c = $a4;	
	   $d = $a1;
       $e = $a5;	   
	   	   
	   $data[] = $a_link?$a_link:'&nbsp;';   
	   $attr[] = "left;10%";	   
	   
	   $data[] = $d?$d:'-';   
	   $attr[] = "left;15%";   
	   
	   $data[] = $b?$b:'&nbsp;';   
	   $attr[] = "left;25%";	      
	   
	   $data[] = $c?$c:'&nbsp;'; /*. '/' . $dtime*/;   
	   $attr[] = "left;25%";	
	   
	   $data[] = $e?$e:'&nbsp;';   
	   $attr[] = "left;25%";		   
	   
	   
	   $myarticle = new window('',$data,$attr);
       $line = $myarticle->render();//"center::100%::0::group_dir_body::left::0::0::");
	   unset ($data);
	   unset ($attr);
	   
       if ($this->details) {//disable cancel and delete form buttons due to form elements in details????
	     $mydata = $line . '<br/>' . $this->details($id);
	     $cartwin = new window2($id . '/' . $status,$mydata,null,1,null,'HIDE',null,1);
	     $out = $cartwin->render();//"center::100%::0::group_article_body::left::0::0::"
	     unset ($cartwin);		   
	   }	
	   else {   
		 $out .= $line ;//. '<hr>';
	   }	   
	   

	   return ($out);
	}	
	
	function headtitle() {
	   $p = GetReq('p');
	   $t = GetReq('t')?GetReq('t'):'cpviewsubsqueue';
	   $sort = GetReq('sort')>0?-1:1; 
	   
	   if (GetReq('editmode'))
	     $edmode = '&editmode=1';
	   else
	     $edmode = null; 
	
       $data[] = seturl("t=$t&a=&g=1&p=$p&sort=$sort&col=id".$edmode ,  localize('_id',getlocal()) );
	   $attr[] = "left;10%";							  
	   $data[] = seturl("t=$t&a=&g=2&p=$p&sort=$sort&col=active".$edmode , localize('_active',getlocal()) );
	   $attr[] = "left;15%";
	   $data[] = seturl("t=$t&a=&g=3&p=$p&sort=$sort&col=receiver".$edmode , localize('_receiver',getlocal()) );
	   $attr[] = "left;25%";
	   $data[] = seturl("t=$t&a=&g=4&p=$p&sort=$sort&col=subject".$edmode , localize('_subject',getlocal()) );
	   $attr[] = "left;25%";
	   $data[] = seturl("t=$t&a=&g=5&p=$p&sort=$sort&col=reply".$edmode , localize('_reply',getlocal()) );
	   $attr[] = "left;25%";	   

  	   $mytitle = new window('',$data,$attr);
	   $out = $mytitle->render(" ::100%::0::group_form_headtitle::center;100%;::");
	   unset ($data);
	   unset ($attr);	
	   
	   return ($out);
	}	
	
	//override from rcshsubscribers ... redefined form
    function form($action=null,$submit='Submit',$rows=null,$taction=null,$editmode=null) {
	     //print_r($_POST);
	     global $sFromErr;
		 $id = GetReq('id');//when item selected bypass it when forms submited
		 $cat = GetReq('cat');//when cat selected bypass it when forms submited
		 $editmode = GetReq('editmode') ? 1 : $editmode ;
		 
		 $taction = 'cpsubloadhtmlmail&cat='.$cat.'&id='.$id.'&editmode='.$editmode ;//templates
		 $iaction = 'cpsubloadimage&cat='.$cat.'&id='.$id.'&editmode='.$editmode ;//add images (embed)		 
		 $daction = 'cpsubattach&cat='.$cat.'&id='.$id.'&editmode='.$editmode ;//add attachments		 
		 $saction = 'cpsubloadhtmlmail&cat='.$cat.'&id='.$id.'&editmode='.$editmode ;//reselect,design items
		 
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
		 
	     $options_subs = "<B>Subscriber only:" . /*localize('_INCLSUBS',getlocal()) .*/ "&nbsp;<input type=\"checkbox\" name=\"includesubs\" $check1>";		
         $options_subs .= "<br><B>All Users:" . /*localize('_INCLALL',getlocal()) .*/ "&nbsp;<input type=\"checkbox\" name=\"includeall\" >";
	     $twinsubs = new window2(localize('_sendtousers',getlocal()),$options_subs,null,1,null,'HIDE',null,1);
	     $winout .= $twinsubs->render("center::100%::0::group_article_selected::left::0::0::");	
	     unset ($twinsubs);	
         
         //send to ulists	 
         $options_ulist = /*localize('_inlist',getlocal()) .*/ "Send to lists:&nbsp;<input type=\"checkbox\" name=\"ulist\" >";
	     $options_ulist .= "Listname:<input type=\"text\" name=\"ulistname\" maxlenght=\"20\" value=\"\">";		 
	     $twinulist = new window2(localize('_sendtolists',getlocal()),$options_ulist,null,1,null,'HIDE',null,1);
	     $winout .= $twinulist->render("center::100%::0::group_article_selected::left::0::0::");	
	     unset ($twinulist);		 
		 
		 //save template as file... 
		 $save_opt = GetParam('tsave') ? 'checked' : null;
         $options_save .= "Save:". "&nbsp;<input type=\"checkbox\" name=\"tsave\" $save_opt>";			 
		 $options_save .= "Name:". "&nbsp;<input type=\"text\" name=\"tname\" maxlenght=\"80\" value=\"\">";
	     $twinsave = new window2(localize('_savenewsletter',getlocal()),$options_save,null,1,null,'HIDE',null,1);
	     $winout .= $twinsave->render("center::100%::0::group_article_selected::left::0::0::");	
	     unset ($twinsave);				 		 
		 
         if ((SMTP_PHPMAILER=='true')  || (SENDMAIL_PHPMAILER=='true')) {	
		   $check2 = $this->ishtml?'checked':null;
           $options .= "<B>" . localize('_ISHTML',getlocal()) . 
		               "&nbsp;<input type=\"checkbox\" name=\"ishtml\" $check2><br>";		 
		 }
         else
           $option .= "<br>";
		 //OVERRIDE REASON.....
		 //else
		   //$options .= "<B>" . localize('_SMTPDEBUG',getlocal()) . "&nbsp;<input type=\"checkbox\" name=\"smtpdebug\"><br>";//debug smtp
		   	 
	     //$opt[] = $options;
 	     //$toattr[] = "left;80%;";	
		 
		 //$options .= "No antispam text:". "&nbsp;<input type=\"checkbox\" name=\"noantispamcont\">";
		 
		 if ($options) {
	     $twin = new window2(localize('_options',getlocal()),$options,null,1,null,'HIDE',null,1);//window('',$opt,$toattr);
	     $winout .= $twin->render("center::100%::0::group_article_selected::left::0::0::");	
	     unset ($twin);	
         }		 
	 	       
	     //MAIL BODY..		   
         $mb[] = "&nbsp;";//"<B>" . localize('_MESSAGE',getlocal()) . ":</B>";
 	     $mbattr[] = "right;1%;";	
		 
		 if (GetReq('t')=='cpsubloadhtmlmail') { //LOAS A NEW TEMPLATE
		   $path = $this->urlpath.$this->infolder.$this->newsletter_path;//GetGlobal('controller')->calldpc_var('rctedit.htmlpath');		 
		   //echo $path; print_r($_POST);
		   
		   if ($template = GetParam('template')) {
		   
		     $data = GetGlobal('controller')->calldpc_method('rctedit.loadfromfile use '.$template.'+'.$path);
			 
			 $sub_template = str_replace('.htm','-sub.part',$template);
			 //echo $path.$sub_template,'>';
			 
			 //if sub template exist !!!!!!!!!!!!!!!!!!
			 if (is_readable($path.$sub_template)) { 
		        $sub_data = $this->get_mail_body($sub_template);//<<selected items sub-template !!!!!!!!!!!!!!!!!!!!!!!!
		        //echo $sub_data,'>';
				$data = str_replace('<?'.$sub_template.'?>',$sub_data,$data);		   
			 }
			 else {//as is..
			    $data = $this->get_mail_body();
				// only when no template...
				$data .= $this->spam_conditions_text($this->antispamlink,0,$this->ishtml);				
			 }	
				
		   }
		   else 
		     $data = $this->get_mail_body();//null;...fetch page as is..read items
			 
		   //$conditions = GetParam('noantispamcont') ? null: $this->spam_conditions_text($this->antispamlink,0,$this->ishtml);
		   //$conditions = $this->spam_conditions_text($this->antispamlink,0,$this->ishtml);		   
		   $this->mailbody = $data;// . $conditions; //str_replace('</html>',$conditions.'</html>',$data);
		   
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
		   
		   $mbody = $this->render_textarea($mailbody,$rows);
		   
		   if ($attachedfile = GetParam('attachment'))		   
	         $this->attachments[] = $attachedfile;	
		   //echo '>',GetParam('attachment');	 
	       SetSessionParam('attachments',serialize($this->attachments));		   		   
		 }		
		 elseif (GetReq('t')=='cpsubsend') {//..OR SEND A MAIL
		   if ($this->mailbody) 
		     //$mailbody = $this->mailbody;
		     //...restore mail body with overwritings !!!!
		     $mailbody = GetParam('mail_text')?GetParam('mail_text'):$this->mailbody;			 
		   else 
		     $mailbody .= GetParam('mail_text')?GetParam('mail_text'):$this->get_mail_body();			 
		   
		   $mbody = $this->render_textarea($mailbody,$rows);		 
		 } 	 
		 elseif ($this->ishtml = true) {//..OR JUST SHOW NEW HTML MAIL ..
		   //$this->htmleditor = new tinyMCE('textareas','ADVANCEDFULL',1,'images',$this->dirdepth);
		   
		   if ($this->mailbody) {
		     $mbody = $this->mailbody;
			 //..............................no template or other element selected..
			 if (defined('RCTEDITITEMS_DPC')) //...fetch page as is..read items...
			    GetGlobal('controller')->calldpc_method("rctedititems.read_selected_items");
		   }	 
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
		
		   if ($this->mailbody) {
		     $mbody .= $this->mailbody;
			 //..............................no template or other element selected..
			 if (defined('RCTEDITITEMS_DPC')) //...fetch page as is..read items...
			    GetGlobal('controller')->calldpc_method("rctedititems.read_selected_items");
		   }	 
		   else {
		     $mbody .= GetParam('mail_text')?GetParam('mail_text'):$this->get_mail_body();		 
             $mbody .= $this->spam_conditions_text($this->antispamlink,0,0);
		   }	 
		   
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
		   $cmd = $this->subs_no . ' ' . localize('_subscribers',getlocal()) .'<br>';
	 
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

		 //save template as file... 
         //$cmd .= "Save:". "&nbsp;<input type=\"checkbox\" name=\"tsave\" >";			 
		 //$cmd .= "Name:". "&nbsp;<input type=\"text\" name=\"tname\" maxlenght=\"80\" value=\"\">";
		 
	     $but[] = $cmd;
	     $battr[] = "left";
	     $bwin = new window('',$but,$battr);
	     $out .= $bwin->render("center::100%::0::group_article_selected::left::0::0::");	
	     unset ($bwin);
	 	     
         $out .= "</FORM>"; 
		 
	     if (defined('RCTEDIT_DPC')) {
		   //html
		   if ($this->dhtml) //include in div	
				$out .= "<div id=\"foptions\" style=\"display:none\">";
				
		   $out1_title = "<B>" . localize('_HTMLTEMPLATE',getlocal()) . ":</B>&nbsp;";//hide...// . $this->template;
	       $out1 = GetGlobal('controller')->calldpc_method('rctedit.show_files use template+Load Newsletter+'.$taction.'+'.$this->newsletter_path.'+'.implode(',',$this->templatetypes));		  
		   //if (defined('RCUPLOAD_DPC')) //disabled
		     //$out1 .= GetGlobal('controller')->calldpc_method('rcupload.uploadform use cpsubuploadtemplate+/'.$this->newsletter_path.'+++'.$this->maxsize); 
			 
	       $awin = new window($out1_title,$out1);
	       $out .= $awin->render("center::100%::0::group_article_selected::left::0::0::");	
	       unset ($awin);	
		   			 
		   //embeded images ...//disabled
		   /*if ((is_array($this->images)) && (!empty($this->images))) {
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
		   */
		   
		   //include in div
		   if ($this->dhtml) //win dhtml	
			   $out .= "</div>";		   
		 } 	

		 //AUTO ITEMS TEMPLATE ENGINE...BUILD MAIL...
		 if (defined('RCTEDITITEMS_DPC')) {
		   $selected_template = GetParam('template');
		   $out0_title = "<B>" . localize('_HTMLSELECTEDITEMS',getlocal()) . "</B>&nbsp;";
	       $out0 = GetGlobal('controller')->calldpc_method('rctedititems.show_selected_items use Reselect+cpsubloadhtmlmail+'.$saction.'+'.$selected_template);

		   $awin = new window($out0_title,$out0);
				   
		   if ($this->dhtml) { //test win dhtml	
		        $content = $awin->render("center::100%::0::group_article_selected::left::0::0::");
				
				$out .= "<div id=\"sitems\" style=\"display:none\">";		
				$out .= $content;//'test<br>"test2"';
				$out .= "</div>";
				
				//$a = str_replace("\n", "",$content);	
				//echo $a;
                //$this->dhtml_javascript("divwin=dhtmlwindow.open('mybox', 'inline', '$a', 'Title', 'width=450px,height=300px,left=200px,top=150px,resize=1,scrolling=1');");				
		   }
		   else {
				$out .= $awin->render("center::100%::0::group_article_selected::left::0::0::");	
		   }
	       unset ($awin);		 
		 }		 
		 
	     $mywin = new window(localize('_SMTPSENDFORM',getlocal()) . ' ['.GetParam('id').']',$out);
	     $wout = $mywin->render();	
	     unset ($mywin);		 
		 
		 return ($wout);     
    }					
	
	//send mail to db queue
	function sendmail_inqueue($from,$to,$subject,$mail_text='',$is_html=false,$user=null,$pass=null,$name=null,$server=null,$nomsg=null) {
       $db = GetGlobal('db');		
	   $ishtml = $is_html?$is_html:0;
       $sFormErr = GetGlobal('sFormErr');
	   $err = null;
	   $ccs = GetParam('cc'); //echo $ccs;		 	      
	   $bccs = GetParam('bcc');	//echo $bccs;	
	   $altbody = GetParam('alttext'); 
	   $origin = $this->prpath; 
	   $encoding = $this->overwrite_encoding ? $this->overwrite_encoding : $this->encoding;
	   
	   //client side (app depended) tracking var
	   if ($this->trackmail) {
	     		 
		 $trackid = $this->get_trackid($from,$to);
		 
		 if (!$ishtml) {
		   $ishtml = 1;
		   $html_mail_text = '<HTML><BODY>' . $mail_text . '</BODY></HTML>';
		   $body = $this->add_tracker_to_mailbody($html_mail_text,$trackid,$to,$ishtml);
		 }
		 else //already html body ...leave it as is		 
	       $body = $this->add_tracker_to_mailbody($mail_text,$trackid,$to,$ishtml);		 
	   }
	   else
	     $body = $mail_text;	   
	   
       //echo 'z';
       if ((checkmail($to)) && ($subject)) {//echo $to,'<br>';
	   
         //add to db...local table
		 $datetime = date('Y-m-d h:s:m');
		 $active = 1;
		 
         if (($this->trackmail) && (isset($trackid))) 
		   $sSQL = "insert into mailqueue (timein,active,sender,receiver,subject,body,altbody,cc,bcc,ishtml,encoding,origin,user,pass,name,server,trackid) ";
		 else  
		   $sSQL = "insert into mailqueue (timein,active,sender,receiver,subject,body,altbody,cc,bcc,ishtml,encoding,origin,user,pass,name,server) ";
		   
		 $sSQL .=  "values (" .
				 $db->qstr($datetime) . "," . $active . "," .
		 	     $db->qstr(strtolower($from)) . "," . $db->qstr(strtolower($to)) . "," .
			     $db->qstr($subject) . "," . 
				 $db->qstr($body) . "," .
				 $db->qstr($altbody) . "," .				 
				 $db->qstr($ccs) . "," .
				 $db->qstr($bccs) . "," .
				 $ishtml . "," .
				 $db->qstr($encoding) . "," .
				 $db->qstr($origin) . "," .			 
				 $db->qstr($user) . "," .
				 $db->qstr($pass) .	"," .	
				 $db->qstr($name) . "," .
				 $db->qstr($server);
				 
         if (($this->trackmail) && (isset($trackid))) {
		    $sSQL .= "," . $db->qstr($trackid) . ")";
		 }
		 else				 
		   $sSQL .= ")";  
	     //echo $sSQL,'<br>';			
	     $result = $db->Execute($sSQL,1);			 
		 $ret = $db->Affected_Rows();
					     	  	
  	     if ($ret) {
 		   if ($nomsg==null) //global call bypass
		     SetGlobal('sFormErr',localize('_MLS2',getlocal()));	//send message ok
		   return true;
		 }         
		 else { 
		   if ($nomsg==null) //global call bypass
		     SetGlobal('sFormErr',localize('_MLS9',getlocal()).'('.$err.')');	//error
		   setInfo($err);//$info); //smtp error = global info
		 }  
       }
       else { 
	     if ($nomsg==null) //global call bypass
	       SetGlobal('sFormErr',localize('_MLS4',getlocal()));
	   }	 
	   return false;			 
	}
		
	
	//override
	function sendit($from,$to,$subject,$mail_text='',$subscribers=null,$is_html=false,$nomsg=null) {
	
	     //save contents as newsletter
		 if (GetParam('tsave')) {
		    $name = GetParam('tname') ? GetParam('tname') : 'test.html';
			$wheretosave = $this->urlpath . $this->infolder . $this->newsletter_path . $name;
			//echo '>',$wheretosave;
			@file_put_contents($wheretosave,$mail_text);
		 }
	
         $mailuser = $this->mailuser; //?$this->mailuser:remote_paramload('SMTPMAIL','user',$this->prpath);
		 $mailpass = $this->mailpass; //?$this->mailpass:remote_paramload('SMTPMAIL','password',$this->prpath);
		 $mailname = $this->mailname; //?$this->mailname:remote_paramload('SMTPMAIL','realm',$this->prpath);
		 $mailserver = $this->mailserver; //?$this->mailserver:remote_paramload('SMTPMAIL','smtpserver',$this->prpath);	

		 $i = 0;
		 $meter = 0;
	     //echo $subscribers,'>';
		 //echo $is_html,'>';

		 $one_receipinet = $this->sendmail_inqueue($from,$to,$subject,$mail_text,$is_html,$mailuser,$mailpass,$mailname,$mailserver,$nomsg);  
		 //also send an instand mail copy...
		 $one_receipinet2 = $this->sendmail($from,$to,$subject,$mail_text,$is_html); 
		 
         //if (defined('RCSHSUBSCRIBERS_DPC') && $subs)  {//bulk mail
		 if ($subscribers) {

			$mails = explode(";",$subscribers);//$mlist);

			foreach ($mails as $z=>$m) {
			  //contactmail::sendit($from,$m,$subject,$mail_text); 
			  $meter += $this->sendmail_inqueue($from,$m,$subject,$mail_text,$is_html,$mailuser,$mailpass,$mailname,$mailserver,$nomsg);
				
			  $i+=1;
			}
			
			if ($nomsg==null) //global call bypass
			  SetGlobal('sFormErr',($meter?$meter:0) . ' mail(s) send from ' . ($i) . ' mail(s) in queue');		 
		 }
		 else {
		   if ($nomsg==null) //global call bypass
	         SetGlobal('sFormErr',($one_receipinet?$one_receipinet:0) . ' mail send from ' . '1 mail in queue');		 
			 
		   $i = $one_receipinet;
		 }  
		 //echo '>>>>>>>>>>>>>>>>>>>>',$i,'<<<<<<<<<<<<<<<<<<<<<<<<<br>';
		 return ($i);	
    }
	
	function get_mails_from_lists($listname=null) {
       $db = GetGlobal('db');	
	   $ulistname = $listname ? $listname : 'default';
	   
	   $sSQL .= "SELECT email FROM ulists where listname=" . $db->qstr($ulistname); 
	   $sSQL .= " and active=1";
	   //echo $sSQL;	
       $result = $db->Execute($sSQL,2);
	   
	   $this->subs_no += $db->Affected_Rows();	
	   
	   if (count($result)>0) {		   
	     foreach ($result as $n=>$rec) {	     
		   $ret[] = $rec['email'];
		 }
	   }
	   //print_r($ret); echo 'a';	 
	   if (!empty($ret)) {  
	     $out = implode(';',$ret);
       }

	   return $out;		   
	}
	
	//override
	function getmails($all_users=null/*, $batch=null,$batchid=null*/) {
       $db = GetGlobal('db');	
	   $out = false;
	   $include_subs = GetParam('includesubs');
	   $include_all = GetParam('includeall');	   
       $subscribers = $include_subs ? $include_subs : $include_all;	
	   
	   $inlist = GetParam('ulist');
	   $listname = GetParam('ulistname');
	   
	   if ($inlist) 
	       $listmails = $this->get_mails_from_lists($listname);	   
	   
	   if ($subscribers) {
			//$resultset = $db->Execute("select email from rcsubscribers",2);   
			$sSQL .="SELECT email FROM users where";	
			if ($all_users==null)//select only subscriber
				$sSQL .= " lname='SUBSCRIBER' and subscribe=1 and";
		    $sSQL .= " notes='ACTIVE'";	//NOT THE INACTIVE USERS   
			//echo $sSQL;	
			$result = $db->Execute($sSQL,2);
	   
			$this->subs_no += $db->Affected_Rows();	
	   
			if (count($result)>0) {		   
				foreach ($result as $n=>$rec) {	     
					$ret[] = $rec['email'];
				}
			}
			//print_r($ret); echo 'a';	 
			if (!empty($ret)) {  
				$subs = implode(';',$ret);
			}
	   }
	   
	   if (($subs) && ($listmails))
	       	$out = $subs .';'. $listmails; 
	   elseif ($subs)
            $out = $subs ;
	   elseif ($listmails)
            $out = $listmails;	
       else
            $out = false;	   
	   //echo $out;
	   return $out;	
	}	
	
	//override
	function send_mails() {	 	   

	   $from = GetParam('from');
	   $to = GetParam('to');	   
	   $subject = GetParam('subject');
	   
	   if ($this->template) { 
	        //echo 'template';
	        if ($this->dirdepth) {
		      for($i=0;$i<$this->dirdepth;$i++)
			      $backdir .= "../";
		    }
		    else
		      $backdir = "../";
            /*
		    $body = $this->mailbody ? 
		            $this->mailbody : 
				    str_replace($backdir,$this->url.$this->infolder.'/',GetParam('mail_text')); 
            */
			//not affect if template, manual editing may be added!!!!!!		
		    $body = GetParam('mail_text') ? 
			        str_replace($backdir,$this->url.$this->infolder.'/',GetParam('mail_text')) :
		            $this->mailbody; 						
	   }	 
	   else	{ //text area or session text with headers
	     //echo 'text area';
	     if (GetReq('editmode')) {
		   //echo 'editmode';
		   $mytext = $this->mailbody ? $this->mailbody : GetParam('mail_text');
		   $body = $this->unload_spath($mytext);
		 }
		 else {
		   //echo 'no editmode';
	       $body = $this->mailbody ? $this->mailbody : GetParam('mail_text');  
		 }  
	   }	 	   
	   	 
	   //check params for lists	of mail to fetch  
	   if ($subs = $this->getmails()) {
			   
	     if (($batch>=0) && ($batch>=$myactivebatch)) {
           //echo 'batch:',$batch;
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
	     //echo 'one receipient';
		 if ($res = $this->sendit($from,$to,$subject,$body,null,$this->ishtml)) 
		   $this->mailmsg = $res . " mail(s) send!";
		 else
		   $this->mailmsg = "Send failed";	
	   }	   
		 
	   return ($res);   
	}			
		
	//override..template based mail body (last viewed depended...)
	function get_mail_body($template=null,$imgw=null,$imgh=null) {
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
			 else {//read html attachment from db..or edit templates

	  		   if (defined('RCITEMS_DPC')) {

                if ($id=GetReq('id')) {//one item
				    $attachtype = GetReq('type');
					
                    if (defined('RCTEDITITEMS_DPC')) {//for one item...katalog build
				        if ($template) {
						    $template_file = $this->urlpath . $this->newsletter_path . $template;
						    $mailbody = GetGlobal('controller')->calldpc_method("rctedititems.create_page use ".$template_file."+$imgw+$imgh");
					    }
					    else
						    $mailbody = GetGlobal('controller')->calldpc_method("rctedititems.create_page use +$imgw+$imgh");					
                    }
					else //attachment data..
                        $mailbody = GetGlobal('controller')->calldpc_method("rcitems.has_attachment2db use " . $id ."+$attachtype+1");			   					
				}	
			    elseif (defined('RCTEDITITEMS_DPC')) {//..ADVANCED BUILD KATALOG TO MAIL...//template engine
				
				    if ($template) {
						$template_file = $this->urlpath . $this->newsletter_path . $template;
						$mailbody = GetGlobal('controller')->calldpc_method("rctedititems.create_page use ".$template_file."+$imgw+$imgh");
					}
					else
						$mailbody = GetGlobal('controller')->calldpc_method("rctedititems.create_page use +$imgw+$imgh");
				}					
			    elseif (defined('RCTEDIT_DPC')) {//..STANDART BUILD KATALOG TO MAIL...//template engine

				    if ($template) {
						$template_file = $this->urlpath . $this->newsletter_path . $template;
						$mailbody = GetGlobal('controller')->calldpc_method("rcitems.create_page use ".$template_file."+$imgw+$imgh");
					}
					else
						$mailbody = GetGlobal('controller')->calldpc_method("rcitems.create_page use +$imgw+$imgh");
				}				
				else	
		            $mailbody = null;
	  		   }
			   else
			     $mailbody = null;
			 }			 		   
		   }
		   else
		     $mailbody = null;	
			 
		   return ($mailbody);	 
	}	
	
	//test mass mail boost ...look at mail.maildbqueue
	function mail_limits_boost($mail_pool=null,$limit=null,$forcelimits=null) {

	  $mail_pool = array('demosoft'=>653,'wayoflife'=>0,'panikidis'=>345,'netko'=>5677,'stereobit'=>23,'demosoft1'=>63,'wayoflife1'=>0,'panikidis1'=>0,'netko1'=>5437,'stereobit1'=>0);
	  $x=0;
	  foreach ($mail_pool as $t=>$ti)
	    $x+=$ti;
	  if ($x==0) return; //no mails return	
	  	
	  
      $cpool = is_array($mail_pool)?count($mail_pool):null; //include root app	
	  //echo 'COUNT>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>',$cpool,'>>>><br>';	  
	  $maxinpool = 0;	
	  $loop = 0;
	  
	  if ($cpool) {
	  
	     //echo $maxinpool,'<br>mail_pool';
		 echo '<pre>';		 
		 print_r($mail_pool);
		 echo '</pre>';	  
	  
	     //make an array of mail limits ..app1=>0,app2=>20,app3=>0 
	     foreach ($mail_pool as $appname=>$mails2send) {
		   if ($mails2send>$limit) {
		     $ret[$appname] = $limit;
			 $maxinpool += $limit;
		   }	 
		   elseif ($mails2send<=$limit) {
		     $ret[$appname] = $mails2send;
			 $maxinpool += $mails2send;		   
		   }
		   else
		     $ret[$appname] = 0;
		 }		
		 
	     //echo $maxinpool,'ret<pre>';		 
		 //print_r($ret);
		 //echo '</pre>';
		 
		 if ($cpool>1) {	//root app plus 1
		   while (($maxinpool+$limit<=$forcelimits) && ($loop<100)) { //loop until forcelimit or loop...to not out of bound
		     $loop+=1;
		     reset($mail_pool); 
	         foreach ($mail_pool as $appname=>$mails2send) {
			 
			   //$prevappmails = prev($mail_pool);
			   $nextappmails = next($mail_pool);
			   //echo 'NEXT>>>>>>>>>>>>>>>>>>>>>>',$nextappmails,'<br>';
			   
               if ($nextappmails == 0)	{ //prev/next array element mails 2 send = 0 or false = 0 out of array...when 0 not exists		       	
			     if (($mails2send>=$limit) && ($ret[$appname]/*+$limit*/<=$mails2send) && ($maxinpool+$limit<=$forcelimits)) { //when have mails 2 send and not out of bound
			       $ret[$appname] += $limit;
				   $maxinpool += $limit;
				 } 
			   }
			   /*elseif ($nextappmails < $limit) { //prev/next array mails 2 send < limit
			     if (($mails2send>=($limit-$nextappmails)) && ($ret[$appname]+($limit-$nextappmails)<=$mails2send) && ($maxinpool+($limit-$nextappmails)<=$forcelimits)) { //when have mails 2 send and not out of bound
			       $ret[$appname] += ($limit-$nextappmails);
				   $maxinpool += ($limit-$nextappmails);
				 }
                 echo '++++++++++++++++++==',$nextappmails; 				 
			   }*/
		     }
		     $mail_pool = array_reverse($mail_pool);
			 
	         //echo $maxinpool,' ',$loop,' reverse:ret<pre>';		 
		     //print_r($ret);
		     //echo '</pre>';	
			 
		   } //while
		 }
         else {//only root app [0]
		   if ($mail_pool['demosoft']>$forcelimits) //more mails than forcelimit
		     $ret['demosoft'] = $forcelimits; //keep it in forcelimit
		   else	//<= small mails than forcelimit
		     $ret['demosoft'] = $mail_pool['demosoft']; //send all
         } 	
		 
	     //echo '<br>maxinpool',$maxinpool,'<br>>>>>>>>>>>>>>>>>>>>>>>>';
		 echo '<pre>';		 
		 print_r($ret);
	     echo '</pre>';		 
		 return ($ret); //array
	  }//mail_pool exist
	  
	  return null;
	}	
	
	function ulistform($ulistname) {
        $db = GetGlobal('db');	
		$ulistname = $ulistname ? $ulistname : 'default';		
		$sSQL = "SELECT startdate,active,lid,listname,name,email FROM ulists";
		$sSQL .= " where listname=" . $db->qstr($ulistname); 
				
		$result = $db->Execute($sSQL,2);	
		//echo $sSQL;
		if (count($result)>0) {		   
	        foreach ($result as $n=>$rec) {	     
		        $data[] = $rec['startdate'];
	            $attr[] = "left;20%";							  
	            $data[] = $rec['active'];
	            $attr[] = "left;5%";
	            $data[] = $rec['lid'];
	            $attr[] = "left;25%";
	            $data[] = seturl('t=cpunsubscribe&editmode=1&submail='.$rec['email'].'&ulistname='.$ulistname,$rec['name']);
	            $attr[] = "left;25%";
	            $data[] = $rec['email'];
	            $attr[] = "left;25%";	   

				$mytitle = new window('',$data,$attr);
				$out .= $mytitle->render(" ::100%::0::group_form_headtitle::center;100%;::");
				unset ($data);
				unset ($attr);
		    }
	   }
	
	    $myw = new window($ulistname,$out);
	    $ret = $myw->render("center::100%::0::group_article_selected::left::3::3::");	   	
	   	
	    return ($ret);	
	}
	
	//override ulist table
    function subscribeform()  { 		

       $filename = seturl("t=cpsubscribe&editmode=".GetReq('editmode'));      
    
       $toprint  = "<FORM action=". "$filename" . " method=post>"; 
	   $toprint .= "<STRONG>E-mail:</STRONG><INPUT type=\"text\" name=\"submail\" maxlenght=\"64\" size=25>";	   
	   $toprint .= "<STRONG>UList name:</STRONG><input type=\"text\" name=\"ulistname\" maxlenght=\"80\" value=\"\">";
	   $toprint .= "<STRONG>Separator:</STRONG><INPUT type=\"text\" name=\"separator\" maxlenght=\"3\" size=3><br>";	   
	   
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

	   //ulist form
       $out .= $this->ulistform(GetParam('ulistname'));	   

       return ($out);
    }	
	
	//override ulist table
	function dosubscribe($mail=null,$notell=null,$name=null) {
        $db = GetGlobal('db');
        $sFormErr = GetGlobal('sFormErr');	
        $name = $name ? $name : 'unknown'; 		
	    $ret = false;
	    $mail = $mail ? $mail : GetParam('submail');
		if (!$mail) return false;
	   
        $dtime = date('Y-m-d h:i:s');		
	
	    //if ($ulistname=GetParam('ulistname')) {
		//..only ulists..
		$ulistname = GetParam('ulistname') ? GetParam('ulistname') : 'default';
		    //ulist....
			if (checkmail($mail))  {
				//check if mail is in database or deleted (unsubscribed)
				$sSQL = "SELECT email FROM ulists where email=". $db->qstr($mail) . 
				        " and (listname='deleted' or listname=" . $db->qstr($ulistname) .")"; 
				$ret = $db->Execute($sSQL,2);
                if (empty($ret->fields[0])) {
					$sSQL = "insert into ulists (email,startdate,active,lid,listname,name) " .
							"values (" .
							$db->qstr(strtolower($mail)) . "," . $db->qstr($dtime) . "," .
							"1,1," . $db->qstr(strtolower($ulistname)) . "," .
							$db->qstr($name) . ")";  
					$db->Execute($sSQL,1);		    
			        //echo $sSQL;
					
					SetGlobal('sFormErr', localize('_MSG6',getlocal()));
			        if ((!$notell) && ($this->tell_it)) 
						$this->mailto($this->tell_from,$this->tell_it,'New Subscription',$mail);			     							  	 
			   
					$ret = true;					
                }				
			}
			else 
			    SetGlobal('sFormErr', localize('_MSG5',getlocal()));
	    //}
	    //else
		//..continue to unssubscribe from users table...
	    //parent::dounsubscribe($mail); 
	   
	    return $ret;	   	
	}
	
	//override ulist table
	function dounsubscribe($mail=null) {
        $db = GetGlobal('db');
        $sFormErr = GetGlobal('sFormErr');	
	    $mail = $mail ? $mail : GetParam('submail');
		$ulistname = GetParam('ulistname') ? GetParam('ulistname') : 'default';		
		if (!$mail) return false;  
		 
		//if ($ulistname=GetParam('ulistname')) { //no need...noexst when user unsubscribe himself
		
			if (checkmail($mail))  {

				$sSQL = "delete from ulists where email=" . $db->qstr($mail) . ' and listname=' . $db->qstr($ulistname); 
				$result = $db->Execute($sSQL,1);
		        //echo $sSQL;
				SetGlobal('sFormErr',localize('_MSG8',getlocal()));
				setInfo(localize('_MSG8',getlocal()));
			}	
			else { 
				SetGlobal('sFormErr', localize('_MSG5',getlocal()));	  
				setInfo(localize('_MSG5',getlocal()));
			}				
        //}	
	    //else
		//..continue to unssubscribe from users table...
	    //parent::dounsubscribe($mail); 		
	}
	
	//override ulist table...
	function mass_subscribe() {
	  //print_r($_POST);
	  $mailtext = GetParam('csvmails');	  
	  $separator = GetParam('separator') ? GetParam('separator') : ',';
	  if (!$mailtext) return;
	  
	  $mymails = explode($separator,$mailtext);
	  //print_r($mymails);
	  $x=0; $x2=0;
	  $n=0;
	  $e=0;
	  set_time_limit(0);
	  foreach ($mymails as $i=>$tok) {
	    if ($doit = $this->dosubscribe(trim(strtolower($tok)),1)) {//is a mail address...
		  if ($doit>0) 
		    $x+=1;
		  elseif ($doit<0) 
		    $x2+=1;
		}  
		else {//..is a combo mail/name
		
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
		
	//override ulist table
	function subscribe_extracting_name($token=null) {
        $db = GetGlobal('db'); 
		if (!$token) return;	
		$matches = array();
					
	    //method 1 name <mail>
	    $pattern = "@<(.*?)>@";
	    preg_match($pattern,$token,$matches);
	    $extracted_mail = trim(strtolower($matches[1]));

		if (checkmail($extracted_mail)) {	  
		  if ($name = str_replace($extracted_mail,'',$token)) {
		    //echo $name,'<br>'
		    $name = str_replace('"','',$name);
		    $name = str_replace("'",'',$name);
		    $name = str_replace('<>','',$name);			
		  }
		  $s = $this->dosubscribe($extracted_mail,1,$name);
		  return ($s);	   
	    }
		else { //method 2 name [mail]
	      $pattern2 = "@[(.*?)]@";
	      preg_match($pattern2,$token,$matches);
	      //print_r($matches);
	      $extracted_mail = trim(strtolower($matches[1]));
		
		  //if ($s = $this->dosubscribe($extracted_mail,1)) {  
		  if (checkmail($extracted_mail)) {	  
		    if ($name = str_replace($extracted_mail,'',$token)) {		
		      $name = str_replace('"','',$name);
			  $name = str_replace("'",'',$name);
		      $name = str_replace('[]','',$name);			
		    }
		    $s = $this->dosubscribe($extracted_mail,1,$name);
		    return ($s);		   			   
	      }
		  else { //method 3 name mail
		    $mytokens = explode(' ',$token);
		    $name = trim($mytokens[0]);
		    $extracted_mail = trim(strtolower($mytokens[1])); 
		  
		    if (checkmail($extracted_mail)) {		
		      if ($name = str_replace($extracted_mail,'',$token)) {
		        $name = str_replace('"','',$name);
			    $name = str_replace("'",'',$name);
			  }	
		      $s = $this->dosubscribe($extracted_mail,1,$name);
		      return ($s);	   
			}  
	      }		  
		}

        return false;
	}		
		
    //under construction... make a choice before send
    function preselect_subscribers($subscribers=null,$action=null) {
	
	  if (stristr($subscribers,';')) {
	     $mails = explode(";",$subscribers);
		 
         if ($action)
		   $myaction = seturl("t=".$action."&editmode=".GetReq('editmode'));   
		 else  
           $myaction = seturl("t=cmail"."&editmode=".GetReq('editmode'));   
		    
		 		   
         $ret = "<FORM action=". "$myaction" . " method=post>"; 		 
		 
		 foreach ($mails as $z=>$m) {
			  //contactmail::sendit($from,$m,$subject,$mail_text); 
			  //$meter += $this->sendmail($from,$m,$subject,$mail_text,$is_html);
			  
			  $ret .= "<input type=\"checkbox\" name=\"$m\" checked>$m<br>";	
			  
			  //$i+=1;
		 }	

	     //BUTTONS
		 if ($action) {
           $ret .= "<input type=\"hidden\" name=\"FormName\" value=\"SendCMail\">"; 
           $ret .= "<INPUT type=\"submit\" name=\"submit\" value=\"" . localize('_SUBMIT',getlocal()) . "\">&nbsp;";  
           $ret .= "<INPUT type=\"hidden\" name=\"FormAction\" value=\"" . $action . "\">";			 
		 }
		 else {
           $ret .= "<input type=\"hidden\" name=\"FormName\" value=\"SendCMail\">"; 
           $ret .= "<INPUT type=\"submit\" name=\"submit\" value=\"" . localize('_SENDCMAIL',getlocal()) . "\">&nbsp;";  
           $ret .= "<INPUT type=\"hidden\" name=\"FormAction\" value=\"" . "sendcmail" . "\">";	 
		 }  
		 
         $ret.= "</FORM>"; 		 
		 
	     $bout[] = $ret;
	     $battr[] = "left";
	     $bwin = new window('',$bout,$battr);
	     $out = $bwin->render();//"center::100%::0::group_article_selected::left::0::0::");	
	     unset ($bwin);
	 	  
		 return ($out); 
	  }
	  
	  return null;
    }	
	
	//override
	function spam_conditions_text($say='UNSUBSCRIBE',$fontsize=11,$ishtml=false) {
		$size = $fontsize ? $fontsize : 11;
		$lan = getlocal();
		$mylink = "<a href=\"" . $this->url . $this->infolder . '/?t=unsubscribe' ."\">".$say."</a>"; //seturl('t=unsubscribe',$say);
		$mynakedlink = $this->url . $this->infolder . '/?t=unsubscribe';	
        $unsubscribe_link = $ishtml ? $mylink : $mynakedlink;	

        $size_start = "<span style=\"font-size:{$size}px;\">";		
		$size_end = "</span>";
		
		$text0 = "
ENGLISH
This e-mail can not be considered spam as long as we include: Contact information & remove instructions. 
If you have somehow gotten on this list in error, or for any other reason would like to be removed,  please click here $unsubscribe_link. 
This email and any files transmitted with it are confidential and intended solely for the use of the individual or entity to whom they are addressed. Any unauthorized disclosure, use of dissemination, either whole or partial, is prohibited.
(Relative as A5-270/2001 of European Council).	  
	  ";
	  
		$text1 = "
GREEK
Αυτο το e-mail δεν μπορει να θεωρηθεί spam εφόσον αναγράφονται τα στοιχεία του αποστολέα και διαδικασίες διαγραφής απο την λίστα παραληπτών.  
Αν είσαστε σε αυτή τη λίστα κατα λάθος ή για οποιονδήποτε άλλο λογο θέλετε να διαγραφεί το e-mail απο αυτή τη λίστα παραληπτών e-mail απλά πατήστε εδώ $unsubscribe_link.   
Το μήνυμα πληρεί τις προυποθέσεις της Ευρωπαικής Νομοθεσίας περί διαφημιστικών μηνυμάτων. Κάθε μήνυμα θα πρέπει να φέρει τα πλήρη στοιχεια του αποστολέα ευκρινώς και θα πρέπει να δίνει στο δέκτη τη δυνατότητα διαγραφής. 
(Directiva 2002/31/CE του Ευρωπαικού Κοινοβουλίου). 
";	

        $ret = $lan ? $size_start . $text0 . '<br/>' . $text1 . $size_end: $size_start . $text0 . $size_end;	
		return ($ret);
    }		
	
	function add_tracker_to_mailbody($mailbody=null,$id=null,$receiver=null,$is_html=false) {
	
	   if (!$id) return;
	   
	   $i = $id;//rawurlencode(encode($id));
	
	   if ($receiver) {
	     $r = $receiver;//rawurlencode(encode($receiver));
	     $ret = "<img src=\"http://www.stereobit.gr/mtrack.php?i=$i&r=$r\" border=\"0\" width=\"1\" height=\"2\">";
	   }
	   else
	     $ret = "<img src=\"http://www.stereobit.gr/mtrack.php?i=$i\" border=\"0\" width=\"1\" height=\"2\">";
		 
	   if (($is_html) && (stristr($mailbody,'</BODY>'))) {
	     if (strstr($mailbody,'</BODY>'))
	       $out = str_replace('</BODY>',$ret.'</BODY>',$mailbody);
		 else  
		   $out = str_replace('</body>',$ret.'</body>',$mailbody);
	   }	 
	   else
	     $out = $mailbody . $ret;	 	 
		 
	   //echo '>',$is_html,$out;	 
	   @file_put_contents($this->prpath.'/trackcode.txt',$out);
		 
	   return ($out);	 
	}	
	
	function get_trackid($from,$to) {
	
		 $i = rand(1000,1999);//++$m;
		 	
		 /*$ta[] = encode(date('Ymd-H:m:s'));
		 $ta[] = $from;
		 $ta[] = $this->appname;
		 $tc = implode('<DLM>',$ta);
		 $tid = rawurlencode(encode($tc));*/
		 
		 //YmdHmsu u only at >5.2.2		 
		 $tid = date('YmdHms') .  $i . '@' . $this->appname;
		 
		 return ($tid);	
	}	

};
}	
}
else die("DATABASE DPC AND SMTPMAIL DPC REQUIRED!");  	
?>