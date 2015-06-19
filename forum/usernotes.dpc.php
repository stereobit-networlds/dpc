<?php
if (defined("DATABASE_DPC")) {

$__DPCSEC['USERNOTES_DPC']='1;2;2;2;2;2;2;2;9';

if ((!defined("USERNOTES_DPC")) && (seclevel('USERNOTES_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("USERNOTES_DPC",true);

$__DPC['USERNOTES_DPC'] = 'usernotes';

//$__DPCATTR['USERNOTES_DPC']['forum'] = 'forum,0,0,0,0,0,0,0';

$d = GetGlobal('controller')->require_dpc('forum/forum.dpc.php');
require_once($d);

GetGlobal('controller')->get_parent('FORUM_DPC','USERNOTES_DPC');

//add event-action for senvp cmd
GetGlobal('controller')->set_command('senvp','0,0,0,0,0,0,0,0,1','USERNOTES_DPC');

//overwrite for cmd line purpose
$__LOCALE['USERNOTES_DPC'][0]='USERNOTES_DPC;User forum;Forum χρηστών';	 
$__LOCALE['USERNOTES_DPC'][1]='_POSTANOTE;Post a note;Καταχωρείστε μια σημείωση';  

class usernotes extends forum {

   var $UserName;
   var $UserSecID;
   
   var $forumid;

   function usernotes() {
		
		forum::forum();
		
  	    //$this->welcome_msg = paramload('USERNOTES','welcome');
	    //$this->site_administrator = paramload('USERNOTES','admin');			
		
		//add usernotes topic
		$this->forumid = 'Usernotes';
		$this->addtopic('Usernotes','Εδω μπορειτε να σημειωσετε οποιοδηποτε θέμα σας απασχολεί, σχετικα με τα προιόντα της εταιρίας μας.',null);	
		
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
		  case 'senvp' : break;
		  
		  default      : forum::event($event); 
		}
   }
   
   function action($action=null) {
		
		switch ($action) {
		  case 'senvp' : $out .= $this->add_note($this->forumid);
		                 break;
		  //overwrite				 
		  case 'postfthread' : $forumlink = seturl("t=showforums",localize("_FORUMTOPICS"));
		                       $out = setNavigator($forumlink,localize('_THREAD'));
							   $callback = seturl('t=senvp&g='.getreq('g'),localize("_BACK"));
							   
							   if ($this->is_member())
                                 $out .= $this->post_new_thread($callback);					   
                               else
							     $out .= $this->no_right();								 
							   break;
							   
          default : $out = forum::action($action);	//default action						   
		}		
		
		return ($out); 
   } 
   
   function addtopic($id,$topic,$image=null,$table_post='usernotes_posts',$table_replies='usernotes_replies') {
	
	   $mytopic = array($table_post,$table_replies,$topic);
	   $this->topic_tables[$id] = $mytopic;
	   
	   if (isset($image))
	     $this->forum_icon[$id] = $image;
		 
	   /*if (($table_post) && ($table_replies)) {
	    
		  $this->create_tables($table_post,$table_replies);
	   }*/	 
   }
  
   function removetopic($id) {
	
	  unset($this->topic_tables[$id]);
	  unset($this->forum_icon[$id]);
   }	
   
   function add_note($forumid) {
   
      $headline2find = str_replace("^",",",GetReq('g')); 
   
	  //post
      $link = seturl("t=postfthread&forumid=$forumid&position=0&sort_by=date_posted&order=asc&g=".
	                 getreq('g'),localize('_POSTANOTE',getlocal()).'!');		  
	  $header[] = $link;
	  $hattr[] = "left;30%";
      //sort form 
	  $header[] = "&nbsp;";//$sort;
	  $hattr[] = "left;70%";
	  //header win
      $hwin = new window("",$header,$hattr);
	  $wout = $hwin->render("center::100%::0::group_article_selected::left::0::0::");
	  unset($hwin);	  
   	
      $wout .= $this->find_notes($forumid,$headline2find);
	 
      $ewin = new window(localize('_POSTANOTE',getlocal()).'...',$wout);
	  $out .= $ewin->render();
	  unset($ewin);
	
	  return ($out);
   }
   
   function find_notes($forumid,$note) {
      $db = GetGlobal('db'); 
   
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

	   $plink = seturl("t=showfthread&forumid=$forumid&msgid=$msgid&position=$this->int_position&sort_by=$this->int_sort_by&order=$this->int_order",$headline);
	   
	   $threads[] = $has_icon . $member . "||" .
	           "<b>" . $plink . "</b><br>" . $body . "||" .
			   $date_posted . "||" .
			   $views . "||" .
			   $number_of_replies;	
			   
	   $current_msg_count++;			     
	  }
	  
	 
      $mydbrowser = new browse($threads,null,null,null);	   
	  $out = $mydbrowser->render('threads',0,$this); 
  	  unset ($mydbrowser);	
	  
	  return ($out);
   }
   
   function create_tables($posts_t,$replies_t) {
        $db = GetGlobal('db'); 

        //delete table if exist
  	    //$sSQL1 = "drop table " . $posts_t;
        //$db->Execute($sSQL1,1);
		$sSQL2 = "create table " . $posts_t . " " .
                    "(id integer auto_increment primary key,
					 member VARCHAR(20),
					 headline VARCHAR(15),
					 body TEXT,
                     date_posted DATETIME,
					 views INTEGER)";																	
        $db->Execute($sSQL2,1);   
		echo $sSQL2;
        //delete table if exist
  	    //$sSQL3 = "drop table " . $replies_t;
        //$db->Execute($sSQL3,1);
		$sSQL4 = "create table " . $replies_t . " " .
                "(id INTEGER,
				  member VARCHAR(20),
				  headline VARCHAR(15),
				  body TEXT,
				  date_posted DATETIME)";
        $db->Execute($sSQL4,1);  
		echo $sSQL4;				
   }
      
	
   //overwrite
   function view_replies() {
      $db = GetGlobal('db');

	  $date_posted_column=$this->table_struct["table_replies"][4];
	  $member_column=$this->table_struct["table_replies"][1];

	  !empty($_POST["sort_by"]) || $_POST["sort_by"]="$date_posted_column";
	  !empty($_POST["order"]) || $_POST["order"]="desc";
	  $sort_by=$_POST["sort_by"];
	  $order=$_POST["order"];
		
	  $msgid = GetReq("msgid");//$_GET["msgid"];
	  $url=urlencode($this->int_forumid);
	
      $action = seturl('t='. GetReq('t') . "&forumid=$url&msgid=$msgid&position=$this->int_position&sort_by=$this->int_sort_by&order=$this->int_order");	
	
	  $sSQL = "select * from $this->int_replies_table where $this->int_id=$msgid order by $sort_by $order";
	  $resultset = $db->Execute($sSQL,2);
	  if ($db->num_Rows($resultset)!=0) {
	
	    $sorting = "<form method=\"post\" action=\"$action\" type=\"multipart/form-data\">";
		$sorting .= $this->sortform("Sort by",$sort_by,$order);

        $lwin = new window(null,$sorting);
        $out .= $lwin->render();	
	    unset($lwin);		
	
	    while ($row = $db->fetch_array($resultset)) {
         
         $current_stylesheet=$this->table_body_section[$stylesheet_number];
         $date_posted=$row[$this->int_date_posted];
         $headline=strip_tags(trim(stripslashes($row[$this->int_headline])));
         $member=$row[$this->int_member];
         $message_body=nl2br(trim(stripslashes($row[$this->int_body])));
		 
		 $win = new window("[".$date_posted."]from:".$member.">".$headline,$message_body);
		 $out .= $win->render();
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
	  $headline = str_replace("^",",",GetReq('g')) . ":"; //=group of products
	
	$date = date("D d M Y");
	$headline_name= $action . "_headline";
	$form_name = $action . '_form';
	$body_name = $action . "_body";	

	$menu = $this->create_menu();

    $wout = "<form name=\"$form_name\" method=\"post\" action=\"$urlaction\" type=\"multipart/form-data\">\n";
		

    $one[] = localize("_SUBJECT").":";
	$one_attr[] = "left;20%";
	$one[] = "<input type=\"text\" name=\"$headline_name\" size=\"$this->headline_length_limit\" maxlength=\"$this->headline_length_limit\" value=\"$headline\">";
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
	$four[] = "<textarea name=\"$body_name\" cols=\"70\" rows=\"20\" wrap=\"physical\"></textarea>";
	$four_attr[] = "left;80%";	
	$win4 = new window('',$four,$four_attr);
	$wout .= $win4->render("center::100%::0::group_article::left::0::0::");
	unset($win4);	
	

	$buttons = "<input type=\"submit\" name=\"$action\" value=\"$postlabel\">";
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
};
}
}
else die("DATABASE DPC REQUIRED! (" .__FILE__ . ")");
?>