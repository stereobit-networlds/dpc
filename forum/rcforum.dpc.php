<?php

$__DPCSEC['RCFORUM_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCFORUM_DPC")) && (seclevel('RCFORUM_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCFORUM_DPC",true);

$__DPC['RCFORUM_DPC'] = 'rcforum';

$a = GetGlobal('controller')->require_dpc('nitobi/nitobi.lib.php');
require_once($a);

$d = GetGlobal('controller')->require_dpc('forum/forum.dpc.php');
require_once($d);

$__EVENTS['RCFORUM_DPC'][0]='cpforum';
$__EVENTS['RCFORUM_DPC'][1]='delpost';
$__EVENTS['RCFORUM_DPC'][2]='delthread';
$__EVENTS['RCFORUM_DPC'][3]='delforum';
$__EVENTS['RCFORUM_DPC'][4]='chforum';
$__EVENTS['RCFORUM_DPC'][5]='chfreset';
 
$__ACTIONS['RCFORUM_DPC'][0]='cpforum';
$__ACTIONS['RCFORUM_DPC'][1]='delpost';
$__ACTIONS['RCFORUM_DPC'][2]='delthread';
$__ACTIONS['RCFORUM_DPC'][3]='delforum';
$__ACTIONS['RCFORUM_DPC'][4]='chforum';
$__ACTIONS['RCFORUM_DPC'][5]='chfreset';

$__DPCATTR['RCFORUM_DPC']['cpforum'] = 'cpforum,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCFORUM_DPC'][0]='RCFORUM_DPC;Forum Administration;Forum Administration';
$__LOCALE['RCFORUM_DPC'][1]='_MEMBER;Member;Μέλος';
$__LOCALE['RCFORUM_DPC'][2]='_DATEP;Date posted;Ημ/νια';
$__LOCALE['RCFORUM_DPC'][3]='_POSTS;Posts;Ανακοινώσεις';
$__LOCALE['RCFORUM_DPC'][4]='_VIEWS;Views;Views';
$__LOCALE['RCFORUM_DPC'][5]='_BODY;Body;Θέμα';

class rcforum extends forum {

    var $title;
	var $carr, $carreplies;
	var $msg;
	var $path;
	var $post;
	var $remotedb;
	var $remoteuser;
	
	var $posts_table, $replies_table;
	
	var $_grids;	
	var $cmd_get,$cmd_set,$cmd2_get,$cmd2_set;
	
	function rcforum() {
	
	  forum::forum();
	  
	  if (!$this->posts_table = GetSessionParam('FORUM_POSTS'))
	    $this->posts_table = "forum_posts"; //default
      if (!$this->replies_table = GetSessionParam('FORUM_REPLIES'))		
	    $this->replies_table = "forum_replies"; //default	
	  
	
	  $this->title = localize('RCFORUM_DPC',getlocal());	//echo $this->title;
	  $this->carr = $this->carreplies = null;
	  $this->msg = null;
	  
	  if ($this->remoteuser=GetSessionParam('REMOTELOGIN')) {//remote parent cp
		  //must re connect to remote db and set global db to this
		  $this->path = paramload('SHELL','prpath')."instances/". $this->remoteuser ."/";	
		  //echo "<br>>",$this->path;
		  //if (defined(_ADODB_)) //????????????????
		    //$this->remotedb = GetGlobal('db');
		  //else
		    $this->remotedb = new sqlite($this->path . "mysqlitedb");			  

		  SetGlobal('db',&$this->remotedb->dbp);	
	  }	
	  elseif ($this->remoteapp = GetSessionParam('REMOTEAPPSITE')) {//remote app cp
		  //must re connect to remote db and set global db to this
		  $this->path = paramload('SHELL','prpath');	
		  //echo "<br>>>",$this->path;
		  //if (defined(_ADODB_)) //???????????????
		    //$this->remotedb = GetGlobal('db');
		  //else		  
		    $this->remotedb = new sqlite($this->path . "mysqlitedb");			  

		  SetGlobal('db',&$this->remotedb->dbp);		    
	  }
	  else {
	     $this->path = paramload('SHELL','prpath');  
	     //echo "<br>>>>",$this->path;
	  }	 
	  //echo '>',$this->path;	
	  
	  $this->cmd_get = str_replace('<iso>','=',remote_paramload('RCFORUM','cmdget',$this->path));		
	  $this->cmd_set = str_replace('<iso>','=',remote_paramload('RCFORUM','cmdset',$this->path));	  
	  $this->cmd2_get = str_replace('<iso>','=',remote_paramload('RCFORUM','cmd2get',$this->path));		
	  $this->cmd2_set = str_replace('<iso>','=',remote_paramload('RCFORUM','cmd2set',$this->path));		  
	    	  
	  
      $this->_grids[] = new nitobi("Posts");	
      $this->_grids[] = new nitobi("Replies");		    
	}
	
    function event($event=null) {
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////		
	
	   switch ($event) {
	     
		 case 'chforum' :     $this->change_forum(); 
                       		  $this->carr = $this->select_posts('all',null,GetReq('alpha'));
							  $this->nitobi_javascript(); 
		                      break;  
	     
	     case 'delpost' :     $this->delete_post(GetReq('id'),'id');
		                      $this->carr = $this->select_posts('all',null,GetReq('alpha'));
							  $this->nitobi_javascript(); 
		                      break;
	     case 'delthread' :   $this->delete_thread(GetReq('id'));
		                      $this->carr = $this->select_posts('all',null,GetReq('alpha'));
							  $this->nitobi_javascript(); 
							  break;
	     case 'chfreset':     $this->reset_db();							  
	     case 'cpforum' :
		 default            : $this->nitobi_javascript(); 
		                      $this->carr = $this->select_posts('all',null,GetReq('alpha'));//dummy param
	   }
			
    }
  
    function action($action=null) {
	 
	  if (GetSessionParam('REMOTELOGIN')) 
	     $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	  else  
         $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);	 	 
	 
	  switch ($action) {
	     case 'chfreset'    :
	     case 'chforum'     :   
	     case 'delpost'     : 
		 case 'delthread'   :
	     case 'cpforum'     :
		 default            : $out .= $this->show_posts();
	 }	 
	 
	 return ($out);
    }
	
	function nitobi_javascript() {
	
      if (iniload('JAVASCRIPT'))  {
	  
		   $template = $this->set_template();   		      
		   $template2 = $this->set_template2(); 
		   
	       $code = $this->init_grids();			
		   $code .= $this->_grids[0]->OnClick(6,'ThreadDetails',$template,'Replies','id',0);
		   $code .= $this->_grids[1]->OnClick(5,'ThreadDetails',$template2,null,null,null,'OnClickThread');		   
	   
		   $js = new jscript;
		   $js->setloadparams("init()");
           $js->load_js('nitobi.grid.js');//javascript folder	 
           //$js->load_js('nitobi.grid.js',null,null,null,1); //local			   
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }		
	}	
	
	function set_template() {
	
		   $del =  seturl("t=delpost&id=");	   

		   $template .= "<A href=\"$del'+i0+'\">Delete Post</A>";		   		   			   
		   $template .= "<br>";
	  						   			   
		   $template .= "<table width=\"100%\" class=\"group_win_body\"><tr><td>";	   	
		   $template .= localize('ID',getlocal()).":</br>";		
		   $template .= localize('_DATEP',getlocal()).":</br>";		   
		   $template .= localize('_POSTS',getlocal()).":</br>";		
		   $template .= localize('_VIEWS',getlocal()).":</br>";		
		   $template .= localize('_MEMBER',getlocal()).":</br>";				   		   	   	   
		   $template .= "</td><td>";
		   $template .= "'+i0+'<br>" . "'+i1+'<br>" . "'+i2+'<br>" . "'+i3+'<br>" .
		                "'+show_member(i4)+'";
		   $template .= "</td></tr></table>";
		   
		   $template .= "'+i5+'<br>"; 
		   
		   return ($template);	
	}		
	
	function set_template2() {
	
		   $dtt =  seturl("t=delthread&id=");   

		   $template .= "<A href=\"$dtt'+i4+'\">Delete Thread</A>";	   		   			   
		   $template .= "<br>";
	  						   			   
		   $template .= "<table width=\"100%\" class=\"group_win_body\"><tr><td>";	   	
		   $template .= localize('ID',getlocal()).":</br>";		
		   $template .= localize('_DATEP',getlocal()).":</br>";		   
		   $template .= localize('_POSTS',getlocal()).":</br>";		
		   //$template .= localize('_VIEWS',getlocal()).":</br>";		
		   $template .= localize('_MEMBER',getlocal()).":</br>";				   		   	   	   
		   $template .= "</td><td>";
		   $template .= "'+i0+'<br>" . "'+i1+'<br>" . "'+i2+'<br>" . "'+show_member(i3)+'";
		   $template .= "</td></tr></table>";
		   
		   $template .= "'+i4+'<br>"; 
		   
		   return ($template);	
	}		
	
	function show_forum_topics() {
	
	  //print_r($this->topic_tables);
	  foreach ($this->topic_tables as $topic=>$tables) {
	    
		$ret .= seturl("t=chforum&t1=".$tables[0]."&t2=".$tables[1],$topic) . "&nbsp;|&nbsp;";
	  }
	  
	  return ($ret);
	}
	
	function change_forum() {
	
	  $t1 = GetReq('t1');
	  $t2 = GetReq('t2');
	  
	  if ((isset($t1)) && (isset($t2))) {
	  
	    SetSessionParam('FORUM_POSTS',$t1);
		SetSessionParam('FORUM_REPLIES',$t2);
		
	    $this->posts_table = $t1;
	    $this->replies_table = $t2;
	  }	
	}
	
	function delete_post($id,$key=null) {
        $db = GetGlobal('db'); 
		
		$sSQL = "delete from $this->posts_table where ";
		
		if (!$key) $key = 'id';
		 
		$sSQL .= $key . "=" . $id;
		  
        $db->Execute($sSQL,1); 		  
	    //echo $sSQL;
		
		$this->msg = "Post with $key=$id deleted!";	
		
		//DELETE REPLIES...ALL WITH ID=ID
		$this->delete_thread($id,'id');
	}
	
	
	function delete_thread($id,$key=null) {
        $db = GetGlobal('db'); 
		
		$sSQL = "delete from $this->replies_table where ";
		
		if (!$key) {
		  $key = 'body'; 
		  $val = $db->qstr(urldecode($id));
		}
		else
		  $val = $id;  
		
		$sSQL .= $key . "=" . $val;

		  
        $db->Execute($sSQL,1); 		  
	    //echo $sSQL;
		
		$this->msg = "Thread with $key=$id deleted!";
	}
	
	function select_posts($id,$key=null,$letter=null) {
	 
		$db = GetGlobal('db'); 
		  
	    $apo = GetParam('apo'); //echo $apo;
	    $eos = GetParam('eos');	//echo $eos; 		
		
		$sSQL = "select id,date_posted,headline,member,body from " . $this->posts_table;
		
		if ($key) 
		  $sSQL .= " where ". $key . "=" . $db->qstr($id); 
		  
		if ($letter) {
		  if ($key) $sSQL .= " and ";
		       else $sSQL .= " where ";
		  $sSQL .= "(headline like '" . strtolower($letter) . "%' or " .
		            "headline like '" . strtoupper($letter) . "%')";
		}	
		
		if ($apo) {
		  if ($key) $sSQL.=' and ';
		       else $sSQL.=' where ';
		  $sSQL.= "date_posted>='" . convert_date(trim($apo),"-YMD",1) . "'";
		}  
		  
		if ($eos) {
		  if ((!$key) && (!$apo)) $sSQL.=' where ';
	                         else $sSQL.=' and ';
		  $sSQL .= "date_posted<='" . convert_date(trim($eos),"-YMD",1) . "'";						
		} 
		
		//limit....
		//if ((!$key) && (!$letter) && (!$eos) && (!$apo))  //default view
		  //$sSQL.= " where dateposted>='" . date('Y-m-d') . "'"; 
		  
		$sSQL .= " order by id desc";  
		  
		//echo $sSQL;	
	    $resultset = $db->Execute($sSQL,2);  	
		if (!defined(_ADODB_)) //no adodb fetch...
	      $ret = $db->fetch_array_all($resultset);			   
		else
		  $ret = $resultset; //as is  
		
		//print_r($ret); 
	  
	    return ($ret);	 		
	}
	
	
	function show_threads($id,$bodyOFpost=null) {
	   $db = GetGlobal('db'); 	
	
	   $sSQL = "select id,date_posted,headline,member,body from " . $this->replies_table;
	   $sSQL .= " where id=" . $id;
	   //$sSQL .= " order by id desc";  	   
	   //echo $sSQL;	
	   $resultset = $db->Execute($sSQL,2);  			 
	   $this->carreplies = $db->fetch_array_all($resultset);		   	
	
	   //show post body
 	   $mytitle = new window('',$bodyOFpost);
	   $toprint .= $mytitle->render(" ::100%::0::group_article_head::left::5::0");
	   unset ($mytitle);	 	
	   
	   if ($this->carreplies) {	
	  	
	    foreach ($this->carreplies as $n=>$rec) {
		
		   $viewdata[] = $n+1;
		   $viewattr[] = "right;4%";
		   
           $name = "reply_".urlencode($rec[1]); //echo $name."<br>";		 
		   $viewdata[] = "<input type=\"checkbox\" name=\"$name\" value=\"0\">";
		   $viewattr[] = "left;1%";		
		   
		   $xrec1 = urlencode($rec[4]);
		   $viewdata[] = seturl("t=delthread&id=".$xrec1,"X");
		   $viewattr[] = "left;5%";		      

		   $viewdata[] = ($rec[1]?$rec[1]:"&nbsp;");
		   $viewattr[] = "left;20%";	
		   
		   $title = $rec[1];
		   $hide_url = setjsurl($rec[2],"javascript:expand('show_$title');contract('hide_$title');contract('$title')","hide_$title","style=\"display:none\"");        
           $show_url = setjsurl($rec[2],"javascript:expand('$title');expand('hide_$title');contract('show_$title')","show_$title");			   		   
		   
		   $viewdata[] = $hide_url.$show_url;//$rec[2]?$rec[2]:"&nbsp;");
		   $viewattr[] = "left;50%";		   
		   
		   $viewdata[] = ($rec[3]?$rec[3]:"&nbsp;");
		   $viewattr[] = "left;20%";	
		   	    	   	   
		   
	       $myrec = new window('',$viewdata,$viewattr);
	       $toprint .= $myrec->render("center::100%::0::group_article_head::left::0::0::");
	       unset ($viewdata);
	       unset ($viewattr);	
		   
		   //show/hide window = thread body
	       $bodywin = new window2($rec[1],$rec[4],null,1,null,'HIDE',1);
	       $toprint .= $bodywin->render("center::100%::0::group_dir_body::left::10::0::");
	       unset ($bodywin);		   		   
		      	   		
		}			
	   }
	   else
	     $toprint .= "No replies !<br>";//$this->bulkiform();
		
		//echo $toprint;
	    return ($toprint);
	}	
	
	function show_posts() { 
	
	   if ($this->msg) $out = $this->msg;
	   
	   $myadd = new window('',$this->show_forum_topics());
	   $toprint .= $myadd->render("center::100%::0::group_article_selected::right::0::0::");	   
	   unset ($myadd); 
	   
	   $toprint .= $this->show_grids($this->cmd_get,$this->cmd_set,$this->cmd2_get,$this->cmd2_set);
		 
	   $toprint .= $this->alphabetical();
	   
	   $dater = new datepicker("/MDYT");	
	   $toprint .= $dater->renderspace(seturl("t=cpcustomers"),"cpcustomers");		 
	   unset($dater);	 
	   
	   $reset_url =  seturl('t=chfreset','Reset');
	   $myres = new window('',$reset_url);
	   $toprint .= $myres->render("center::100%::0::group_article_selected::right::0::0::");	   
	   unset ($myres); 	   	   		 
		 
       $mywin = new window($this->title,$toprint);
       $out .= $mywin->render();		 
	  
	   return ($out);			
	}	
	
	function alphabetical($command='cpforum') {
	
	  $preparam = GetReq('alpha');
	  
	  $ret .= seturl("t=$command","Home") . "&nbsp;|";
	
	  for ($c=$preparam.'a';$c<$preparam.'z';$c++) {
	    $ret .= seturl("t=$command&alpha=$c",$c) . "&nbsp;|";
	  }
	  //the last z !!!!!
	  $ret .= seturl("t=$command&alpha=".$preparam."z",$preparam."z");
	  
      //$mywin = new window('',$ret);
      //$out = $mywin->render();	  
	  
	  return ($ret);
	}
	
	
	function headtitle() {
	   $t = GetReq('t');
	   $p = GetReq('p');
	   $sort = GetReq('sort');
	   $cat = GetReq('cat');	
	   $alpha = GetReq('alpha');   	   

	   $data[] = "[v]";
	   $attr[] = "left;4%";	 	
	   $data[] = seturl("t=$t&cat=$cat&alpha=$alpha&p=$p&sort=$sort&col=p_id",  'id' );
	   $attr[] = "left;1%";
	   $data[] = "del";
	   $attr[] = "left;5%";	   							  
	   $data[] = seturl("t=$t&cat=$cat&alpha=$alpha&p=$p&sort=$sort&col=datep" , localize('_DATEP',getlocal()) );
	   $attr[] = "left;20%";
	   $data[] = seturl("t=$t&cat=$cat&alpha=$alpha&p=$p&sort=$sort&col=posts" , localize('_POSTS',getlocal()) );
	   $attr[] = "left;50%";
	   $data[] = seturl("t=$t&cat=$cat&alpha=$alpha&p=$p&sort=$sort&col=name2",  localize('_MEMBER',getlocal()) );
	   $attr[] = "left;20%";
   
	   
 	   $mytitle = new window('',$data,$attr);
	   $out = $mytitle->render(" ::100%::0::group_article_head::center;100%;::");
	   unset ($data);
	   unset ($attr);	
	   
	   return ($out);
	}
	
	function init_grids() {
        //disable alert !!!!!!!!!!!!		
		$out = "
function alert() {}\r\n

function show_member() {
  var str = arguments[0];
  var unknown = 'unknown';

  if (str)
    return str;
  else
    return unknown; 
} 
			
function init()
{
";
        foreach ($this->_grids as $n=>$g)
		  $out .= $g->init_grid($n);
	
        $out .= "\r\n}";
        return ($out);
	}
	
	function show_grids($cmd_get=null,$cmd_set=null,$cmd2_get=null,$cmd2_set=null) {
	   //gets
	   $cat = GetReq('cat');	
	   $subcat = GetReq('subcat');
	   $alpha = GetReq('alpha');
	   //transformed posts !!!!
	   $apo = GetParam('apo');
	   $eos = GetParam('eos');	   
	
	   $grid0_get = $cmd_get?$cmd_get:"abchandler.php?t=abcngetposts";
	   $grid0_set = $cmd_set?$cmd_set:"abchandler.php?t=abcnsetposts";   
	
	   //grid 0
	   $this->_grids[0]->set_text_column("AA","id","50","true");
	   $this->_grids[0]->set_text_column(localize('_DATEP',getlocal()),"date_posted","200","true");	   
	   $this->_grids[0]->set_text_column(localize('_POSTS',getlocal()),"headline","150","true","TEXTAREA");		   
	   $this->_grids[0]->set_text_column(localize('_VIEWS',getlocal()),"views","100","true");  	   
	   $this->_grids[0]->set_text_column(localize('_MEMBER',getlocal()),"member","100","true");   
	   $this->_grids[0]->set_text_column(localize('_BODY',getlocal()),"body","150","true","TEXTAREA");		   

	   $datattr[] = $this->_grids[0]->set_grid_remote($grid0_get,$grid0_set,"400","460","livescrolling",17);							  
	   $viewattr[] = "left;50%";	   
	   
	   //grid 1 
	   $grid1_get = $cmd2_get?$cmd2_get:'abchandler.php?t=abcngetreplies';	   
	   $grid1_set = $cmd2_set?$cmd2_set:'abchandler.php?t=abcnsetreplies';		   
	   
	   $this->_grids[1]->set_text_column("AA","id","50","true");
	   $this->_grids[1]->set_text_column(localize('_DATEP',getlocal()),"date_posted","200","true");	   
	   $this->_grids[1]->set_text_column(localize('_POSTS',getlocal()),"headline","150","true","TEXTAREA");		   		   
	   $this->_grids[1]->set_text_column(localize('_MEMBER',getlocal()),"member","100","true");   
	   $this->_grids[1]->set_text_column(localize('_BODY',getlocal()),"body","150","true","TEXTAREA");		   	   
   	      	   
       $wd = $this->_grids[1]->set_grid_remote($grid1_get,$grid1_set,"400","200","livescrolling",null,"true");   	   
	    
	   $message = '...';	
	   $wd .= $this->_grids[0]->set_detail_div("ThreadDetails",400,260,'F0F0FF',$message);
	   $datattr[] = $wd;
	   $viewattr[] = "left;50%";
	   
	   $myw = new window('',$datattr,$viewattr);
	   $ret = $myw->render("center::100%::0::group_article_selected::left::3::3::");
	   unset ($datattr);
	   unset ($viewattr);		   	
	   	
	   return ($ret);	
	}	
  
  
};
}
?>