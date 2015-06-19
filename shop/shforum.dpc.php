<?php
if (defined("DATABASE_DPC")) {

$__DPCSEC['SHFORUM_DPC']='1;2;2;2;2;2;2;2;9';
$__DPCSEC['USMEMBER_']='2;1;2;2;2;2;2;2;9';
$__DPCSEC['USRESET_']='9;1;2;2;2;2;2;2;9';

if ((!defined("SHFORUM_DPC")) && (seclevel('SHFORUM_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHFORUM_DPC",true);

$__DPC['SHFORUM_DPC'] = 'shforum';


$d = GetGlobal('controller')->require_dpc('forum/forum.dpc.php');
require_once($d);

GetGlobal('controller')->get_parent('FORUM_DPC','SHFORUM_DPC');

//add event-action for kshow` cmd
GetGlobal('controller')->set_command('kshow','0,0,0,0,0,0,0,0,1','SHFORUM_DPC');

//overwrite for cmd line purpose
$__LOCALE['SHFORUM_DPC'][0]='SHFORUM_DPC;User Forum;Σχολιάστε...';	 
$__LOCALE['SHFORUM_DPC'][1]='_POSTANOTE;Post a note;Καταχωρείστε μια σημείωση';
$__LOCALE['SHFORUM_DPC'][2]='_FROM;From;απο';	
$__LOCALE['SHFORUM_DPC'][3]='_UNKNOWN;Unknown;Αγνωστος';
$__LOCALE['SHFORUM_DPC'][4]='_CANCEL;Cancel;Ακυρο';

class shforum extends forum  {

   
   var $forumid;   				

   function shforum($clear=null) {  
		
		forum::forum();
		   	
		$this->forumid = 'Δημοπρασίες'; 
		
        $this->int_post_table=$this->topic_tables[$this->forumid][0];
        $this->int_replies_table=$this->topic_tables[$this->forumid][1];
		
        $this->int_id=$this->table_struct["table_replies"][0];
	    $this->int_member=$this->table_struct["table_replies"][1];
	    $this->int_headline=$this->table_struct["table_replies"][2];
	    $this->int_body=$this->table_struct["table_replies"][3];
        $this->int_date_posted=$this->table_struct["table_replies"][4];		
		
        $this->int_post_member=$this->table_struct["table_posts"][1];
        $this->int_post_headline=$this->table_struct["table_posts"][2];
        $this->int_post_body=$this->table_struct["table_posts"][3];
        $this->int_post_date_posted=$this->table_struct["table_posts"][4];
        $this->int_post_views=$this->table_struct["table_posts"][5];	
		
		//overwrite
        $this->headline_length_limit = 100;	
			
								
   }
   
   function event($event=null) {
		
		switch ($event) {
		  case 'kshow' : break;
		  
		  default      : forum::event($event); 
		}
   }
   
   function action($action=null) {
   
	    $cat = GetReq('cat');
	    $id = GetReq('id');   
		
		switch ($action) {
		  case 'kshow' : $out .= $this->add_note($this->forumid);
		                 //$out .= $this->display_forums();
						 //$out .= $this->post_new_thread($callback);
		                 break;
						 
		  //overwrite				 
		  case 'postfthread' : $forumlink = seturl("t=showforums",localize("_FORUMTOPICS"));
		                       $out = setNavigator($forumlink,localize('_THREAD'));
							   $callback = seturl("t=kshow&cat=$cat&id=$id",localize("_CANCEL"));
							   
							   if ($this->is_member())
                                 $out .= $this->post_new_thread($callback);					   
                               else
							     $out .= $this->no_right();								 
							   break;	
		  //overwrite					   
		  case 'replyfthread': 
		                       $out = setNavigator($forumid,localize('_REPLY',getlocal()));
							   $callback = seturl("t=kshow&cat=$cat&id=$id",localize("_CANCEL"));
							   
							   if ($this->is_member())							   
                                 $out .= $this->reply_to_thread($callback);	
							   else
							     $out .= $this->no_right();								 				   
							   break;								   					 
							   
          default : $out = forum::action($action);	//default action						   
		}		
		
		return ($out); 
   }    
  
	
   function add_note($forumid) {
      global $current_item;
	  $cat = GetReq('cat');
	  $id = GetReq('id');   	  
	  
      //print_r($_SESSION);
      $headline2find = $current_item;//GetSessionParam('CURRENT_VEHILCE');
	  //echo $headline2find; 
   
	  //post
      $link = seturl("t=postfthread&forumid=$forumid&position=0&sort_by=date_posted&order=asc&g=".
	                 $current_item . "&cat=$cat&id=$id",
					 localize('_POSTANOTE',getlocal()).'!');		  
	  $header[] = $link;
	  $hattr[] = "left;30%";
      //sort form 
	  $header[] = "&nbsp;";//$sort;
	  $hattr[] = "left;70%";
	  //header win
      $hwin = new window("",$header,$hattr);
	  $postout = $hwin->render("center::100%::0::group_article_selected::left::10::0::");
	  unset($hwin);	  
   	
      $notesout .= $this->find_notes($forumid,$headline2find,$istrue);
	  
	  if ($istrue) {//view replies...
	    $wout .= $postout; //add a new post...
	    $wout .= $notesout;
	    $wout .= $this->view_replies(); 
	    //$wout .= $this->reply_to_thread();
		
        $ewin = new window(localize('SHFORUM_DPC',getlocal()).'...',$wout);
	    $out .= $ewin->render();
	    unset($ewin);			  
	  }
	  else {//post a new note (one only))
	    $wout .= $postout;
	 
        $ewin = new window(localize('SHFORUM_DPC',getlocal()).'...',$wout);
	    $out .= $ewin->render();
	    unset($ewin);
	  } 
	
	  return ($out);
   }	
   
   function find_notes($forumid,$note,&$istrue) {
      $db = GetGlobal('db'); 
	  $istrue = false;
	  $cat = GetReq('cat');
	  $id = GetReq('id'); 	  
   
	  $query = "select * from $this->int_post_table" .
	           " where $this->int_post_headline like '%$note%'" .   
               " order by date_posted desc" . //desc because of fifo
               " limit 0,$this->threads_limit_per_page";	   
		 
      $resultset = $db->Execute($query,2);   
	  //echo $query;
	  
      while ($row = $db->fetch_array($resultset)) {		
	    
	   !empty($this->thread_icon) && $has_icon=loadimagexy($this->thread_icon,18,18);
	   		
	   $msgid=$row[$this->table_struct["table_posts"][0]];   
	   	  
       $member=$row[$this->int_post_member];
	   $headline=$row[$this->int_post_headline];
	   $date_posted=$row[$this->int_post_date_posted];
	   $views=$row[$this->int_post_views];
	   $body=$row[$this->int_post_body];

	   //ghet replies 
	   $replies_number="select count(*) as msg_count from $this->int_replies_table where $this->int_id=$msgid";
	   $replies_fetch = $db->Execute($replies_number,2); 
	   $replies_number_result = $db->fetch_array($replies_fetch);  		 
       $number_of_replies=$replies_number_result["msg_count"];	
	   
       if ($number_of_replies>$this->hot_thread_limit) 
	     $has_icon = loadimagexy($this->hot_thread_icon,18,18);	   

	   //show thread	  
	   //$plink = seturl("t=showfthread&forumid=$forumid&msgid=$msgid&position=0&sort_by=$this->int_post_date_posted&order=desc" 
	     //              . "&cat=$cat&id=$id",$headline);
	   //reply to thread				   
	   $plink = seturl("t=replyfthread&forumid=$forumid&msgid=$msgid&position=0&sort_by=$this->int_post_date_posted&order=desc" 
	                   . "&cat=$cat&id=$id",$headline);					   
	   
	   $threads[] = $has_icon . $member . "||" .
	           "<b>" . $plink . "</b><br>" . $body . "||" .
			   $date_posted . "||" .
			   $views . "||" .
			   $number_of_replies;	
			   
	   $current_msg_count++;			     
	  }
	  
	  //if post has replies return true for showing replies anf reply form...
	  if (count($threads)>0) 
	    $istrue = true;
	  else 
	    $istrue = false;
	  //echo $istrue,'>>>';	
	  
	 
      $mydbrowser = new browse($threads,null,null,null);	   
	  $out = $mydbrowser->render('threads',0,$this); 
  	  unset ($mydbrowser);	
	  
	  return ($out);
   }   
   
   //overwrite
   function view_replies() {
      $db = GetGlobal('db'); 
      global $current_item;	
	  $cat = GetReq('cat');
	  $id = GetReq('id'); 		     

	  $date_posted_column=$this->table_struct["table_replies"][4];
	  $member_column=$this->table_struct["table_replies"][1];

	  !empty($_POST["sort_by"]) || $_POST["sort_by"]="$date_posted_column";
	  !empty($_POST["order"]) || $_POST["order"]="desc";
	  $sort_by=$_POST["sort_by"];
	  $order=$_POST["order"];
		
	  $msgid = GetReq("msgid");//$_GET["msgid"];
	  $url=urlencode($this->int_forumid);
	
      $action = seturl('t='. GetReq('t') . "&forumid=$url&msgid=$msgid&position=$this->int_position&sort_by=$this->int_sort_by&order=$this->int_order"
	                   . "&cat=$cat&id=$id");	
	
	  if ($current_item) {
	    $headline2find = 'Re:'.$current_item;
	    $sSQL = "select * from $this->int_replies_table where $this->int_headline='$headline2find' order by $sort_by $order";
	  }	
	  else
	    $sSQL = "select * from $this->int_replies_table where $this->int_id=$msgid order by $sort_by $order";
	  //echo $sSQL;
	  
	  $resultset = $db->Execute($sSQL,2);
	  if ($db->Affected_Rows) {
	
	    $sorting = "<form method=\"post\" action=\"$action\" type=\"multipart/form-data\">";
		$sorting .= $this->sortform("Sort by",$sort_by,$order);

        $lwin = new window(null,$sorting);
        $out .= $lwin->render("center::100%::0::group_article_selected::left::0::0::");	
	    unset($lwin);		
	
	    while ($row = $db->fetch_array($resultset)) {
         
         $current_stylesheet=$this->table_body_section[$stylesheet_number];
         $date_posted=$row[$this->int_date_posted];
         $headline=strip_tags(trim(stripslashes($row[$this->int_headline])));
         $member=($row[$this->int_member]?$row[$this->int_member]:localize("_UNKNOWN",getlocal()));
		 
		 //$message_body=nl2br(trim(stripslashes($row[$this->int_body])));
		 $bwin = new window('',"<h4>" . nl2br(trim(stripslashes($row[$this->int_body]))) . "<h4>" );
         $message_body = $bwin->render("center::100%::0::group_article_selected::left::10::0::");
		 unset($bwin);
		 
		 $win = new window("[".$date_posted."] ".localize("_FROM",getlocal()).": ".$member." > ".$headline,$message_body);
		 $out .= $win->render("center::100%::0::group_article_selected::left::0::0::");
		 unset($win);
        }		 	
	  }
	
	  return ($out);
   }    
   
   //overwrite
   function reply_post_table($action,$headline,$title,$urlaction,$label=null,$callback=null) {    
   
    if ($label) $postlabel = $label;
	       else $postlabel = $action;		   
		     
    if ($action=='post')
	  $headline = GetReq('g');
  
	$cat = GetReq('cat');
	$id = GetReq('id');   
	$urlaction .= "&cat=$cat&id=$id";    
	
	$date = date("D d M Y");
	$headline_name= $action . "_headline";
	$form_name = $action . '_form';
	$body_name = $action . "_body";	

	$menu = $this->create_menu();
		

    $wout = "<form name=\"$form_name\" method=\"post\" action=\"$urlaction\" type=\"multipart/form-data\">\n";
		

    $one[] = localize("_SUBJECT").":";
	$one_attr[] = "left;20%";
	$one[] = "<input type=\"text\" name=\"$headline_name\" size=\"$this->headline_length_limit\" maxlength=\"$this->headline_length_limit\" value=\"$headline\" " . ($headline?'readonly':'') .">";
	$one_attr[] = "left;80%";	
	$win1 = new window('',$one,$one_attr);
	$wout .= $win1->render("center::100%::0::group_article_selected::left::0::0::");
	unset($win1);
	
    $two[] = localize("_DATEP").":";
	$two_attr[] = "left;20%";
	$two[] = $date;
	$two_attr[] = "left;80%";		
	$win2 = new window('',$two,$two_attr);
	$wout .= $win2->render("center::100%::0::group_article_selected::left::0::0::");
	unset($win2);	
	
    $three[] = "HTML Options:";
	$three_attr[] = "left;20%";
	$three[] = $menu;
	$three_attr[] = "left;80%";		
	$win3 = new window('',$three,$three_attr);
	$wout .= $win3->render("center::100%::0::group_article_selected::left::0::0::");
	unset($win3);
	
    $four[] = localize("_MESSAGE").":";
	$four_attr[] = "left;20%";
	$four[] = "<textarea name=\"$body_name\" cols=\"70\" rows=\"10\" wrap=\"physical\"></textarea>";
	$four_attr[] = "left;80%";	
	$win4 = new window('',$four,$four_attr);
	$wout .= $win4->render("center::100%::0::group_article::left::0::0::");
	unset($win4);	
	

	$buttons = "<input type=\"submit\" name=\"$action\" value=\"$postlabel\">&nbsp;";
	if ($callback) {
	  $buttons .= $callback; 
	}
	$five[] = $buttons;	
	$five_attr[] = "center;100%";	
	$win5 = new window('',$five,$five_attr);
	$wout .= $win5->render("center::100%::0::group_article_selected::left::0::0::");
	unset($win5);	
	
	$wout .= "</form>";
	
    $ewin = new window($title,$wout);
	$out .= $ewin->render();//"center::80%::0::window::left::0::0::");
	unset($ewin);		
	
	
	
	//SHOW VEHICLE PAGE	!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	if ($headline)	
	  $out .= GetSessionParam('printpage'); 		
	
	

    return ($out);
   }        
   
  //overwrite
   function post_new_thread($callback=null) {
    $db = GetGlobal('db');
	//echo 'CALLBACK:',$callback;

	$this->init_variables();

    if (!empty($_POST["post"])) {
	
	  if ($callback)
	    $backbutton = $callback;
	  else	
        $backbutton = seturl("t=displayforum&forumid=".urlencode($this->int_forumid)."&position=$this->int_position&sort_by=$this->int_sort_by&order=$this->int_order",localize("_BACK"));	
	
	  $message_body=$this->validate($_POST["post_body"]);
	  $message_headline=addslashes(strip_tags($_POST["post_headline"]));
	  if (empty($message_headline) || empty($message_body)) {
	    $out .= $this->display_error(localize("_MSGEMPTY").$backbutton);
      }
	  else {
	    $date = get_datetime();//now();!!!	
        $insert_query="insert into $this->int_post_table ($this->int_post_member,$this->int_post_headline,$this->int_post_body,$this->int_post_date_posted) values ('$this->member','$message_headline','$message_body','$date')";
        //echo $insert_query;
	    $db->Execute($insert_query,2);
	   
	    $out .= $this->message(localize("_POST"),"<h3>".localize("_MSGSEND")."</h3>".$backbutton);
	  }	
	}
    else {
	
	  if ($callback)  
        $backbutton = $callback;	  		
	  else
        $backbutton = seturl("t=displayforum&forumid=".urlencode($this->int_forumid)."&position=$this->int_position&sort_by=$this->int_sort_by&order=$this->int_order","Go Back!")."<br/><br/>";	  	  

	  //add g to action....	 
      $action = seturl("t=".getreq('t')."&forumid=".urlencode($this->int_forumid)."&position=$this->int_position&sort_by=$this->int_sort_by&order=$this->int_order&g=".getreq('g'));  
      $out .= $this->reply_post_table("post","",localize("_THREAD").": $this->int_forumid Forum $link",$action,localize("_POST"),$backbutton);
    }

    return ($out);	
   }      
	
  function reply_to_thread($callback=null) {
    $db = GetGlobal('db');
	
	//$this->connect_to_database();
	$this->init_variables();

	$msgid=$this->validate(GetReq("msgid"));//$_GET["msgid"]);
	$post_id=$this->table_struct["table_posts"][0];

	if ($callback)  
      $back = $callback;	  		
	else	
      $back = seturl("t=showfthread&forumid=".urlencode($this->int_forumid)."&msgid=$msgid&position=$this->int_position&sort_by=$this->int_sort_by&order=$this->int_order",localize("_BACK"));
	
    if (!empty($_POST["reply"])) {
	 
	    $message_body=$this->validate($_POST["reply_body"]);
	    $message_headline=addslashes(strip_tags($_POST["reply_headline"]));	
	    if (empty($message_headline) || empty($message_body)) {
		  $out .= $this->display_error(localize("_MSGEMPTY").$back);
		}  
        else {
          $date = get_datetime();//now();      
          $sSQL = "insert into $this->int_replies_table ($this->int_id,$this->int_member,$this->int_headline,$this->int_body,$this->int_date_posted) values ($msgid,'$this->member','$message_headline','$message_body','$date')";
          //echo $sSQL;		 
		  $res = $db->Execute($sSQL,2);
		
	      $out .= $this->message(localize("_REPLY"),"<h3>".localize("_MSGSEND")."</h3>".$back);
		}
		
    } 
    else {
        $sSQL = "select $this->int_post_headline from $this->int_post_table where $post_id=$msgid";
		$resultset = $db->Execute($sSQL,2);		
		$headline = $db->fetch_array($resultset);
		
	    $action = seturl('t='.GetReq('t')."&forumid=".urlencode($this->int_forumid)."&msgid=$msgid&position=$this->int_position&sort_by=$this->int_sort_by&order=$this->int_order");		
        $out .= $this->reply_post_table("reply","Re:".$headline[0],localize("_POST")." : ".$headline[0],$action,localize("_REPLY"),$back);
    }

    return ($out);
  }	
	
};
}
}
else die("DATABASE DPC REQUIRED! (" .__FILE__ . ")");
?>

