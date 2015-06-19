<?php
if (defined("DATABASE_DPC")) {

$__DPCSEC['FORUM_DPC']='1;2;2;2;2;2;2;2;9';
$__DPCSEC['USMEMBER_']='2;1;2;2;2;2;2;2;9';
$__DPCSEC['USRESET_']='9;1;2;2;2;2;2;2;9';

if ((!defined("FORUM_DPC")) && (seclevel('FORUM_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("FORUM_DPC",true);

$__DPC['FORUM_DPC'] = 'forum';

$__EVENTS['FORUM_DPC'][0]= 'showforums';
$__EVENTS['FORUM_DPC'][1]= 'displayforum';
$__EVENTS['FORUM_DPC'][2]= 'showfthread';
$__EVENTS['FORUM_DPC'][3]= 'postfthread';
$__EVENTS['FORUM_DPC'][4]= 'replyfthread';
$__EVENTS['FORUM_DPC'][5]= 'reset_fr';

$__ACTIONS['FORUM_DPC'][0]='showforums';
$__ACTIONS['FORUM_DPC'][1]='displayforum';
$__ACTIONS['FORUM_DPC'][2]='showfthread';
$__ACTIONS['FORUM_DPC'][3]='postfthread';
$__ACTIONS['FORUM_DPC'][4]='replyfthread';
$__ACTIONS['FORUM_DPC'][5]='reset_fr';

//$__DPCATTR['FORUM_DPC']['forum'] = 'forum,0,0,0,0,0,0,0';

$__LOCALE['FORUM_DPC'][0]='FORUM_DPC;Forum;Forum';
$__LOCALE['FORUM_DPC'][1]='_FORUMTOPICS;Forum Topics;Forum Topics';
$__LOCALE['FORUM_DPC'][2]='_THREADS;Threads;Σημειώσεις';
$__LOCALE['FORUM_DPC'][3]='_THREAD;Thread;Σημείωση';
$__LOCALE['FORUM_DPC'][4]='_POST;New Post;Νεα Ανακοινωση';
$__LOCALE['FORUM_DPC'][5]='_POSTS;Posts;Ανακοινώσεις';
$__LOCALE['FORUM_DPC'][6]='_REPLY;New Reply;Νεα Απαντηση';
$__LOCALE['FORUM_DPC'][7]='_REPLIES;Replies;Απαντήσεις';
$__LOCALE['FORUM_DPC'][8]='_VIEWS;Views;Views';
$__LOCALE['FORUM_DPC'][9]='_MEMBER;Member;Μέλος';
$__LOCALE['FORUM_DPC'][10]='_DATEP;Date posted;Ημ/νια';
$__LOCALE['FORUM_DPC'][11]='_SUBJECT;Subject;Θέμα';
$__LOCALE['FORUM_DPC'][12]='_BACK;Back;Πίσω';
$__LOCALE['FORUM_DPC'][13]='_NEXT;Next;Επόμενο';
$__LOCALE['FORUM_DPC'][14]='_SORT;Sort;Ταξινόμηση';
$__LOCALE['FORUM_DPC'][15]='_GOTOPAGE;Goto page;Σελίδα';
$__LOCALE['FORUM_DPC'][16]='_DISTHREADS;Dispaly Threads;Εμφάνιση Θεμάτων';
$__LOCALE['FORUM_DPC'][17]='_TO;to;εως';
$__LOCALE['FORUM_DPC'][18]='_OF;of;απο';
$__LOCALE['FORUM_DPC'][19]='_SORTTHREADSBY;Sorts threads by ;Ταξινόμηση ';
$__LOCALE['FORUM_DPC'][20]='_SORTREPLIESBY;Sorts replies by ;Ταξινόμηση ';
$__LOCALE['FORUM_DPC'][21]='_ASCE;Ascending;A-Z';
$__LOCALE['FORUM_DPC'][22]='_DESC;Descending;Z-A';
$__LOCALE['FORUM_DPC'][23]='_MESSAGE;Message;Κείμενο';
$__LOCALE['FORUM_DPC'][24]='_UNKNOWN;Unknown;Αγνωστο';
$__LOCALE['FORUM_DPC'][25]='_NOSUBJECT;No subject;Δεν υπάρχει τίτλος';
$__LOCALE['FORUM_DPC'][26]='_NONE;None;Κενο';
$__LOCALE['FORUM_DPC'][27]='_CHOOSE;Choose;Επιλεξτε';
$__LOCALE['FORUM_DPC'][28]='_MSGEMPTY;Empty Subject or message body not permitted.;Προβλημα κατα την καταχωρηση.Ελεγξτε τα στοιχεια σας.';
$__LOCALE['FORUM_DPC'][29]='_MSGSEND;Your message has been send;Το μυνημα σας στάλθηκε με επιτυχία';
$__LOCALE['FORUM_DPC'][30]='_TPOSTS;Total posts;Σημειώσεις';
$__LOCALE['FORUM_DPC'][31]='_LPOST;Last post;Τελευταια';

//require_once("forum.lib.php");
$d = GetGlobal('controller')->require_dpc('forum/forum.lib.php');
require_once($d);


class forum extends phpforumPlus {

   var $UserName;
   var $UserSecID;
   
   var $member;   
   var $threads_limit_per_page=10;
   var $thread_length_limit=1000;
   var $reply_length_limit=1000;
   var $headline_length_limit=20;  
   
   var $topic_tables=array("panikidis.com"=>array("forum_posts","forum_replies","General forum.Here you can discuss about all-things. Must be valid member to post new threads"));
   var $table_struct=array("table_posts"=>array("id","member","headline","body","date_posted","views"),
	                        "table_replies"=>array("id","member","headline","body","date_posted"));    
							
   var $forum_icon=array("panikidis.com"=>"images/sample_php.gif");
   var $thread_icon="images/thread_icon.gif";
   var $hot_thread_icon="images/hot_thread_icon.gif";
   var $hot_thread_limit=10;		
   
   var $welcome_msg = "Welcome to our community";
   var $site_administrator = "webmaster@sth.somewhere";   	
   var $path;				

   function forum($clear=null) {
	    $UserSecID = GetGlobal('UserSecID');
		$UserName = GetGlobal('UserName');
	    $GRX = GetGlobal('GRX');
		
        $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);   
        $this->userName = (((decode($UserName))) ? (decode($UserName)) : localize("_UNKNOWN",getlocal()));
		
		if ($clear) $this->topic_tables = array();//reset default forum
		
		$this->set_member($this->userName);	//set member
		
		$this->path = paramload('SHELL','prpath');

		//set topics
		$mytopics = remote_arrayload('FORUM','topics',$this->path);
		$mytexp = remote_arrayload('FORUM','topicsexps',$this->path);
		$mytimg = remote_arrayload('FORUM','topicicons',$this->path);
		$mytables = remote_arrayload('FORUM','topictables',$this->path);// print_r($mytables);
		
		$i=0;
		foreach ($mytopics as $id) {
		
		  $f_tables = $mytables[$i];
		  $f_table = explode(":",$f_tables);
		  //echo $f_table[0].":".$f_table[1]."<br>";
		  
		  if (!$f_table[0]) $t1 = "forum_posts"; //default
		               else $t1 = $f_table[0];
		  if (!$f_table[1]) $t2 = "forum_replies"; //default	
		               else $t2 = $f_table[1];	  
		
		  $topics[$id] = array($t1,$t2,$mytexp[$i]);
		  $icons[$id] = $mytimg[$i];
		  $i++;
		}
		if (is_array($topics)) {
		  $this->topic_tables = $topics;
		  //print_r($this->topic_tables);
		  $this->forum_icon = $icons;
		}  
		//set attrs
  	    $this->welcome_msg = remote_paramload('FORUM','welcome',$this->path);
	    $this->site_administrator = remote_paramload('FORUM','admin',$this->path);	
        $this->thread_icon = remote_paramload('FORUM','threadicon',$this->path);
        $this->hot_thread_icon = remote_paramload('FORUM','hothreadicon',$this->path);
        $this->hot_thread_limit = remote_paramload('FORUM','hothreadlimit',$this->path);			
   }
   
   //overwrite
   function set_member($mymember) {
   
        if (seclevel('USMEMBER_',$this->userLevelID))    
		  phpforumPlus::set_member($mymember);
   }
   
   function is_member() {
    
	    if (paramload('FORUM','access2public'))//if anonymous permited
		  return true;
        elseif (seclevel('USMEMBER_',$this->userLevelID)) //only reg users
		  return true;
		
		return false;  
   }
   
   function no_right() {
      
	   //$ret = "No right!";
       $w = new msgBox("No right...","OKOnly",localize('FORUM_DPC',getlocal())); 
       $links = array(seturl(''));
       $w->makeLinks($links);			 
       $ret = $w->render();				 
	   unset($w);		   
	   
	   return ($ret);
   }
   
   function event($event=null) {
     
	    switch ($event) {
		  case 'showforums'   : break;
		  case 'displayforum' : break;
		  case 'showfthread'  : break;
		  case 'postfthread'  : if (iniload('JAVASCRIPT')) {
		                          //$myforum=new myphpforum( );
		                          $code = $this->insert_javascript('for_post_page');
								  
		                          $js = new jscript;
                                  $js->load_js($code,"",1);	
		                          unset ($js);										  
								} 
		                        break;		  
		  case 'replyfthread' : if (iniload('JAVASCRIPT')) {
		                          //$myforum=new myphpforum( );
		                          $code = $this->insert_javascript('for_replies_page');
								  
		                          $js = new jscript;
                                  $js->load_js($code,"",1);	
		                          unset ($js);										  
								}  
		                        break;			  
		  case 'reset_fr'     : 
		                        if (seclevel('USRESET_',$this->userLevelID))  
		                          $this->reset_db(); 
		                        break;
		} 
   }
   
   function action($action=null) {
   
        $forumlink = seturl("t=showforums",'Forum Topics');
		$forumid = seturl("t=displayforum&forumid=".getreq('forumid').
		                  "&position=0&sort_by=date_posted&order=asc",
		                  "Forum : " .getreq('forumid'));
   
        switch ($action) {
		  case 'showforums'  : 
		                       $out = setNavigator(localize('_FORUMTOPICS',getlocal()));
                               $out .= $this->display_forums();
							   break;
		  case 'displayforum': 
		                       $out = setNavigator($forumlink,localize('_THREADS',getlocal()));
                               $out .= $this->display();					  
							   break;
		  case 'showfthread' : 
		                       $out = setNavigator($forumid,localize('_THREAD',getlocal()));
                               $out .= $this->display_thread();					   
							   break;
		  case 'postfthread' : 
		                       $out = setNavigator($forumlink,localize('_POST',getlocal()));
							   if ($this->is_member())
                                 $out .= $this->post_new_thread();					   
							   else
							     $out .= $this->no_right(); 	 
							   break;							   
		  case 'replyfthread': 
		                       $out = setNavigator($forumid,localize('_REPLY',getlocal()));
							   if ($this->is_member())							   
                                 $out .= $this->reply_to_thread();	
							   else
							     $out .= $this->no_right();								 				   
							   break;								   
		  case 'reset_fr'    : $out = setNavigator(localize('_FORUMTOPICS',getlocal()));
	                           if (seclevel('USMEMBER_',$this->userLevelID))   
		                         $out .= 'Forum reset successfully!!!!'; 
							   else
							     $out .= $this->no_right();									 
		                       break;
		}
		
		return ($out); 
   }
   
   function reset_db() {
        $db = GetGlobal('db'); 

        //delete table if exist
  	    $sSQL1 = "drop table forum_posts";
        $db->Execute($sSQL1,1);
		$sSQL2 = "create table forum_posts " .
                    "(id integer auto_increment primary key,
					 member VARCHAR(20),
					 headline VARCHAR(15),
					 body TEXT,
                     date_posted DATETIME,
					 views INTEGER)";																	
        $db->Execute($sSQL2,1);   
		//echo $sSQL2;
        //delete table if exist
  	    $sSQL3 = "drop table forum_replies";
        $db->Execute($sSQL3,1);
		$sSQL4 = "create table forum_replies " .
                "(id INTEGER,
				  member VARCHAR(20),
				  headline VARCHAR(15),
				  body TEXT,
				  date_posted DATETIME)";
        $db->Execute($sSQL4,1);  
		//echo $sSQL4;				
			
		setInfo(" Reset successfully!");
    }  
	
	
	function free() {
	}  
	
};
}
}
else die("DATABASE DPC REQUIRED! (" .__FILE__ . ")");
?>