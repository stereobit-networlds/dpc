<?php

if ($_GET['editmode']>0) {
// Turn off all error reporting
//error_reporting(0);

// Report simple running errors
//error_reporting(E_ERROR | E_WARNING | E_PARSE);

// Reporting E_NOTICE can be good too (to report uninitialized
// variables or catch variable name misspellings ...)
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

// Report all errors except E_NOTICE
// This is the default value set in php.ini
//error_reporting(E_ALL ^ E_NOTICE);

// Report all PHP errors (see changelog)
//error_reporting(E_ALL);

// Report all PHP errors
//error_reporting(-1);

//echo nl2br(file_get_contents('./error_log'));
//debug_print_backtrace();
}

$__DPCSEC['FRONTHTMLPAGE_DPC']='1;1;1;1;1;1;1;1;9';

if (!defined("FRONTHTMLPAGE_DPC")) {//&& (seclevel('FRONTPAGE_DPC',decode(GetSessionParam('UserSecID')))) ){
define("FRONTHTMLPAGE_DPC",true);

$__DPC['FRONTHTMLPAGE_DPC'] = 'fronthtmlpage';

$__LOCALE['FRONTHTMLPAGE_DPC'][1]='_addspace;Limited space, add space;Πρόσθεσε χωρητικότητα';

class fronthtmlpage {

	var $t_fronthtmlpage;
	var $userLevelID;
    var $themepath,$themeurl;
	var $htmlfile,$htmlpage;
	
	var $agent;
	var $runas;
	
	var $argument;
	var $debug;
	
	var $forms,$arrays;
	
	var $runasapp;
	var $data;
	var $infolder, $urlpath, $url, $urltitle;
	var $modify, $adminhtmlfile, $adminhtml,$admindpc,$admincmd,$adminview;
	var $allow_edit;
	var $charset;
	var $verbose;
	var $editmode_point;
	var $editdpc, $edithtml, $prpath;
	var $dhtml, $htmlext;
	
	static $staticpath;
	 
	function fronthtmlpage($file=null,$runasuser=null,$runasapp=null) {	
	
        $UserSecID = GetGlobal('UserSecID');
	    $thema = GetGlobal('thema');
	    $theme = GetGlobal('theme');	
	    $__USERAGENT = GetGlobal('__USERAGENT');
	    $GRX = GetGlobal('GRX');				
	 
	    $this->t_fronthtmlpage = new ktimer;
	    $this->t_fronthtmlpage->start('fronthtmlpage'); 						

        $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);  
		$this->agent = $__USERAGENT;  	
		
		if (isset($runasuser)) $this->runas = $runasuser; //run fp as a diferent user (see newsletter)
		if (isset($runasapp)) $this->runasapp = $runasapp;
		//echo $this->runasapp,'>';
		
        $this->prpath = paramload('SHELL','prpath');		
		
		$this->htmlpage = paramload('FRONTHTMLPAGE','path')?paramload('FRONTHTMLPAGE','path'):'html'; 
		//echo '>',$this->htmlpage;
		//$this->htmlfile = paramload('SHELL','prpath'). $this->htmlpage . "/" . $file; 
		$this->urlpath = paramload('SHELL','urlpath');			
		$this->urltitle = paramload('SHELL','urltitle');					
		
		$murl = arrayload('SHELL','ip');
        $this->url = (!empty($murl)) ? $murl[0] : paramload('SHELL','urlbase'); 			
		//echo $this->url ,'>';
		$this->infolder = paramload('ID','hostinpath');		
		
		//check if html file name based on action exist
		$cmd = GetParam('FormAction')?GetParam('FormAction'):GetReq('t');
		if ($cmd) {
		  //check lan
		  $mylan = getlocal()?getlocal():'0';
		  //is logged in user
		  $autofile = GetGlobal('UserID')? $cmd . '_in' . $mylan . '.html' : $cmd . $mylan . '.html';
		  
		  $htmlfile = $this->urlpath . $this->infolder . '/cp/' . $this->htmlpage . "/" . $autofile;
		  if (!is_readable($htmlfile)) //default
		    $htmlfile = $this->urlpath . $this->infolder . '/cp/' . $this->htmlpage . "/" . $file;
		}
		else
		$htmlfile = $this->urlpath . $this->infolder . '/cp/' . $this->htmlpage . "/" . $file;
		//echo '>',$htmlfile;
		
		if (!is_readable($htmlfile)) {//check for parent file

		  $cphtmlpath = "/../cp/$this->htmlpage/";
			
		  $parentfile = $this->urlpath . $cphtmlpath . $file; 
		  //echo 'z'.$parentfile;
		  if (is_readable($parentfile)) 
			$this->htmlfile = $parentfile;
		}
		else {
		  $cphtmlpath = $this->infolder . '/cp/' . $this->htmlpage;		
		  $this->htmlfile = $htmlfile;//$this->urlpath . $this->infolder . '/cp/' . $this->htmlpage . "/" . $file; 
		}  
		
		//echo '>',$this->htmlfile;
		
		$this->adminhtmlfile = $this->urlpath . $cphtmlpath . "/cpmframework.htm"; 
		$this->adminhtml = $this->urlpath . $cphtmlpath . "/cpmhtmleditor.htm"; 
		$this->admindpc = $this->urlpath . $cphtmlpath . "/cpmdpcedtitor.htm"; 
		$this->admincmd = $this->urlpath . $cphtmlpath . "/cpmctrl.htm"; 						
		$this->adminview = $this->urlpath . $cphtmlpath . "/cpmhtmlviewer.htm"; 
		
		$p = explode(".",$file);
		//$this->argument = strtoupper($p[0]); //the name without ext		
		//in case of dot(.) in name
		$extension = array_pop($p);
		//now with the rest of array
		$pdotname = implode('.',$p);
		$this->argument = strtoupper($pdotname); //the name with dots and without ext		
		
		$this->debug = GetReq('debug')?GetReq('debug'):GetSessionParam('debug');//0;		
		$this->session_use_cookie = paramload('SHELL','sessionusecookie');	
		
		//embedded forms and/or arrays
		$this->forms = array();
		$this->arrays = array();
		$this->data = null;
		
		$this->modify = urldecode(base64_decode(GetReq('modify')))=='stereobit' ? true : false;

        //choose encoding
        $char_set  = arrayload('SHELL','char_set');	  
        $charset  = paramload('SHELL','charset');	  		
		if (($charset=='utf-8') || ($charset=='utf8'))
		  $this->charset = 'utf-8';
		else  
	      $this->charset = $char_set[getlocal()]; 	  				
		  
		$this->allow_edit = remote_paramload('FRONTHTMLPAGE','alloweditpage',$this->prpath);  
		$this->verbose = remote_paramload('FRONTHTMLPAGE','verbose',$this->prpath); 
		
        if ($GRX)    
         $this->editmode_point  = loadTheme('editmode','');
	    else
	  	 $this->editmode_point  = '[Edit Mode]';

        $this->editdpc = remote_paramload('FRONTHTMLPAGE','editdpc',$this->prpath); 		 
		$this->edithtml = remote_paramload('FRONTHTMLPAGE','edithtml',$this->prpath); 
		
	    //dynamic html loader
	    $this->dhtml = remote_paramload('FRONTHTMLPAGE','dhtml',$this->prpath); 
		
		$htmlfile_extension = remote_paramload('FRONTHTMLPAGE','htmlext',$this->prpath);
        $this->htmlext = $htmlfile_extension ? $htmlfile_extension :'.htm'; 		
		
		self::$staticpath = paramload('SHELL','urlpath');
	}	
	
    function render($actiondata) { 	

      switch ($this->agent) {
	      case 'WAP'  :
		  case 'XML'  :	
		  case 'GTK'  :			   
		  case 'XUL'  :					   			   
	      case 'HTML' :	  
		  default     :$out = $this->action($actiondata);
					   break;
	  }			  
	  		   	 
	  //timer
	  $this->t_fronthtmlpage->stop('fronthtmlpage');
   	  //if (seclevel('_TIMERS',$this->userLevelID))
	    //echo "fronthtmlpage " . $this->t_fronthtmlpage->value('fronthtmlpage');
	  	  
	  		  
	  return ($out);
    }
	
	//dummy event
    /*function event($event=null) {
	
        //switch($event)   {
        //}
    }*/		
	
	function action($actiondata) {
		
	   if ($this->modify) {
	   
	     $out = $this->modify_page();
	   }	 
       $out .= $this->process_html_file($actiondata, null, $this->modify);	
	   return ($out);
	}
	
	function process_html_file($data,$stage=null, $admin=null) {
	  //echo $data;
	  //echo $this->htmlfile;
	  if ($admin) { //echo 'zzzzz';
	  //  $this->htmlfile = $this->adminview; //override
	    if ($this->dhtml)
		    $htmldata = $this->fullpage_iframe();//ifwindows();
		else
	        $htmldata = $this->frameset();

	    return ($htmldata);
	  }
	  
	  //alternate html symbol to replace
	  //$nn = $this->argument;
	  //$pattern = "<$nn></$nn>"; 
	  
	  if (is_file($this->htmlfile)) {
		
        $htmdata = file_get_contents($this->htmlfile);	  
	    		
        $cssdata = $this->process_css($htmdata);
			
        $jhtmdata = $this->process_javascript($cssdata);		
		
        $chunks_data = $this->process_chunks($data,$jhtmdata,$pageout);				

		if (!$this->debug) { //echo $this->htmlfile,'>>',$chunks_data;
		  $ret = str_replace("<?". $this->argument ."?>",$chunks_data,$pageout);
		  //$ret = str_replace($pattern,$chunks_data,$pageout);
		}  
		else {
		  $ret = str_replace("<?". $this->argument ."?>",$this->argument,$pageout);
		  //$ret = str_replace($pattern,$this->argument,$pageout);
		}  
		  
		//recess app resources  
		if (isset($this->runasapp))  
		  $ret = str_replace("images/",$this->runasapp."/images/",$ret);
		  
		//::::update fpdata at pcntl to useit by advertisers,helpers etc  :::
		$this->data = GetGlobal('controller')->calldpc_var('pcntl.fpdata',$ret); 
		//echo $this->data;
		
		$ret = $this->process_commands($ret);
		
		if (!$this->session_use_cookie)
		  $ret = $this->propagate_session($ret);
		
		//set title if title of page = #TITLE#
		if ($pagetitle=GetReq('g')) {
		  $maintitle = paramload('SHELL','urltitle');
		  $ret = str_replace('#TITLE#',$maintitle.' > '.$pagetitle,$ret);
		}  
	  }
	  else {

		global $_html;//standart name .... 
		
	    if ($_html) {
		  $htmdata = $_html; 
		  //////////////////////////////////////////////////////////BYPASS....?
          //$cssdata = $this->process_css($htmdata);
          //$jhtmdata = $this->process_javascript($cssdata);		
          $chunks_data = $this->process_chunks($data,$htmdata,$pageout);				
          
		  if (!$this->debug) 
		    $ret = str_replace("<?_HTML?>",$chunks_data,$pageout);
		  else
		    $ret = str_replace("<?_HTML?>",$this->argument,$pageout);

		  $ret = $this->process_commands($ret);	
		  
		  if (!$this->session_use_cookie)
		    $ret = $this->propagate_session($ret);	
			
		  //set title if title of page = #TITLE#
		  if ($pagetitle=GetReq('g')) {
		    $maintitle = paramload('SHELL','urltitle');
		    $ret = str_replace('#TITLE#',$maintitle.' > '.$pagetitle,$ret);
		  }  			
		}
		else {
		  if (!GetReq('editmode'))
		    $admlink = $this->get_admin_link();

		  $hfile = $this->htmlfile ? $this->htmlfile : 'none';	
	      $ret = "Unknown html file (".$hfile.") or argument.\n" . $admlink;
		}  
	  }	
		
	  return ($ret);	
	}	
	
	//handle chains of data in action ret such as text<@PHPCHUNK>seialized_array<@...
	function process_chunks($actiondata=null,$htmldata,&$pageout) {
	
	  $pageout = $htmldata; //default as is
	  if (!$actiondata) return null; //fast return
	  
	  //find array elements
	  $pattern = "@<phpdacarray.*?>(.*?)</phpdacarray>@";
	  preg_match_all($pattern,$htmldata,$matches);
	  $_arrays = $matches[0];
	  $_arrays_id = 0;	  
	  
	  //find form elements
	  $pattern = "@<phpdacform.*?>(.*?)</phpdacform>@";
	  preg_match_all($pattern,$htmldata,$matches);
	  $_forms = $matches[1];	
	  $_forms_id = 0;  
	  	
      $chunks = explode('<@PHPCHUNK>',$actiondata);
	  //print_r($chunks);	
	  foreach ($chunks as $cid=>$cdata) {
	    
		$udata = unserialize($cdata);
	    if (is_array($udata)) {//array element or form element
		  
		  if (stristr(key($udata),'form')) {//is form element
			$result = $this->process_form($udata,$_forms[$_forms_id]);//get modified form
            $pageout = str_replace($_forms[$_forms_id],$result,$pageout);//replace prototype
		    $_forms_id+=1;//inc forms pointer				
		  }
		  else { //is array element
		    $ret .= $this->process_array($udata,$_arrays[$_arrays_id]);//build array based at proto
		    $pageout = str_replace($_arrays[$_arrays_id],'',$pageout);//remove proto(html) array		  
		    $_arrays_id+=1;//inc arrays pointer	
		   
		    //clean unused param of type %n
		    for ($i=0;$i<10;$i++)
		      $ret = str_replace('%'.$i,'&nbsp;',$ret);
		  }	  
		}
		else //text
		  $ret .= $cdata;	  
	  }
	  
	  return ($ret);		  
	}
	
	function process_array($action_array,$html_array) {
	
	  $udata = $action_array;
		
	  if (is_array($udata)) {

		  foreach ($udata as $i=>$line) {
		    $content = $html_array;
		    foreach ($line as $j=>$column) {
			  $ret = str_replace('%'.($j+1),$column,$content); 
			  $content = $ret;
			  //echo $ret,"<br>";
			}
		    //echo $ret,"<br>";	
			$arraydata .= $ret;
		  }
	  }	
	  
	  return ($arraydata);	
	}	
	
	function process_form($action_array,$html_form) {
	
	  $udata = $action_array['form'];
	
	  if (is_array($udata)) {
	   	//if (udata not artios!!!!!!!) die("Unbalanced form!");

	    //find form name's field value
	    $pattern = "@name=.*?\"(.*?)\"@";
	    preg_match_all($pattern,$html_form,$matches);
	    $names = $matches[1];
		//print_r($names);	
	  
	    //find form value's field value
	    $pattern = "@value=.*?\"(.*?)\"@";
	    preg_match_all($pattern,$html_form,$matches);
	    $values = $matches[1];	
		//$values_exp = $matches[1]; 	  	
		//print_r($values);	
		
		$max = count($names)-1; //echo $max-1;//BEWARE OR NULL VALUES..REPLACE MORE THAN ONE NULL
		$n = 0;
		for ($i=0;$i<$max;$i++) {
		  //replace names
		  $html_form = str_replace("\"".$names[$i]."\"","\"".$udata[$n]."\"",$html_form);
		  $n+=1;		  
		  //replace values
		  $html_form = str_replace("\"".$values[$i]."\"","\"".$udata[$n]."\"",$html_form);
		  $n+=1;		  
		}		
		
	    //find form action's field value
	    $pattern = "@action=.*?(.*?) @";
	    preg_match($pattern,$html_form,$matches);
	    $get = $matches[1];	//echo $get;//print_r($get);		
		//modify action=GET
		if (isset($action_array['action']))
		  $html_form = str_replace($get,$action_array['action'],$html_form);
	  }	
	  //echo $html_form;
	  $formdata = $html_form;
	  return ($formdata);		  
	}
	
	function process_commands($data,$is_serialized=null) {
	
	  if ($is_serialized) 
	    $data = unserialize($data);
	  
	  $pattern = "@<phpdac.*?>(.*?)</phpdac>@";
	  preg_match_all($pattern,$data,$matches);
	  //print_r($matches);
	  
      foreach ($matches[1] as $r=>$cmd) {
	    //echo $cmd,"<br>";
		if (!$this->debug) {
	      $ret = GetGlobal('controller')->calldpc_method($cmd,1); //no error stop 					 
		  $data = str_replace("<phpdac>".$cmd."</phpdac>",$ret,$data);
		}
	  }
	  
	  return ($data);//as is
	}
	
	function process_javascript($data) {
	
	  //call javascript 
      if (iniload('JAVASCRIPT')) {	
		 $js = new jscript;
		 $onload = $js->onLoad();//!!!!!!!!!!!!!!!MUST BE SET TO <BODY AT END>
		 $jret = $js->callJavaS() . "</HEAD>";	 
		 unset ($js);		 
		 
		 if ($jret) {
		   $ret = str_replace("</head>",$jret,$data);
		 }
		 
		 return ($ret);
	  }	
	  
	  return ($data);  	//as is
	}
	
	function process_css($data) {
	  //$thema = GetGlobal('thema');
/*	  $theme = GetGlobal('theme');	
	
	  $thema = GetGlobal('controller')->calldpc_var("pcntl.theme");
	  if (!$thema) 
		$thema = paramload('SHELL','deftheme');	
	
	  $prpath = paramload('SHELL','prpath');
	  
	  $themepath = $prpath . 'public/' .$theme['path'] . $thema . ".theme/";
	  //echo $themepath;
      */
    
	  /*
   	  //by replacing css	 
	   if ($this->runasapp) 
	    $newcss = $this->runasapp. '/' . $this->themeurl . 'style.css';
	  else 
	    $newcss = $this->themeurl . 'style.css';
	
	  $ret = str_ireplace('style.css',$newcss,$data);*/
	  
	  $path = paramload('SHELL','prpath') . $this->htmlpage;	  
	  //echo $path;
	  //by enclosing css file		  
	  //$enccss = $themepath . 'style.css';	  
	  $enccss = $path . '/style.css';	  
	  $cssdata = @file_get_contents($enccss);
	  //echo $cssdata;  
	  if ($cssdata) {
	    $translated_css = "<style type='text/css'>" . $cssdata . "</style>";
	    $ret = str_ireplace('</head>',$translated_css.'</head>',$data);	
	  }
	  else
	    $ret = $data; //as is	
	  
	  return ($ret);
	}
	
	function propagate_session($data,$ext='.php') {
	
	  $ret = str_replace($ext.'?',$ext."?".SID."&",$data);//.php with args	
	  $ret2 = str_replace($ext.'"',$ext."?".SID.'"',$ret);//.php without args
	  
	  return ($ret2);
	}
	
	function get_xml_links($mylan=null,$feed_id=null) {
	  $lan = $mylan?$mylan:getlocal();//by hand per htm 0,1 page
	  $lnk = array();
	  
      if ($id = GetReq('id'))
	    $feed_id = $id .'.xml';	  
	  elseif ($cat = GetReq('cat'))
	    $feed_id = $cat .'.xml';
			
	  $feed_file = $feed_id ? $feed_id : 'feed.xml';
	  $upath = $this->infolder ? $this->urlpath.'/'.$this->infolder : $this->urlpath;
	  
      //RSS	
	  if (($lan) && (is_readable($upath."/rss/$lan/".$feed_file))) {
	    $lnk['RSS'] = "/rss/$lan/".$feed_file;	  
	  }
	  elseif (is_readable($upath."/rss/".$feed_file)) {
	    $lnk['RSS'] = '/rss/'.$feed_file;
	  }
	  
	  //echo $upath."/rss/".$feed_file;	  
	  
	  //ATOM
	  if (($lan) && (is_readable($upath."/atom/$lan/".$feed_file))) {
	    $lnk['ATOM'] = "/atom/$lan/".$feed_file;	  
	  }	  
	  elseif (is_readable($upath.'/atom/'.$feed_file)) {
	    $lnk['ATOM'] = '/atom/'.$feed_file;
	  }
	  //print_r($lnk);	

	  if (is_array($lnk)) {
	    foreach ($lnk as $t=>$w) {
	      //echo $w,$t,'<br>';	    
		  if ($w) {
		    $icon_file = $this->urlpath.'/'.$this->infolder.'/images/'.strtolower($t).'.png';
			//echo $icon_file,'>';
		    if (is_readable($icon_file))
			  $mylink = "<img src=\"". $this->infolder.'/images/'.strtolower($t).'.png' ."\" border=\"0\" alt=\"$t\">";
			else
			  $mylink = $t;
			  
	        $ret .= "<A href=\"$w\">".$mylink."</A>&nbsp;";
		  }	
		  //echo $ret,'<br>';
	    }
	  }
	  
	  return $ret;  
	}
	
	function get_seo_links($mylan=null) {
	  $lan = $mylan?$mylan:getlocal();//auto languange
	
	  //xml seo links
	  if (is_readable($this->urlpath."/seo/feed$lan.xml")) {
	    $file = $this->urlpath."/seo/feed$lan.xml";
	    //read xml and ret the links
	    $ret = null;		
	  }
	  elseif (is_readable($this->urlpath.'/'.$this->infolder."/seo/feed$lan.xml")) {
	    $file = $this->urlpath.'/'.$this->infolder."/seo/feed$lan.xml";
	    //read xml and ret the links
	    $ret = null;		
	  }//txt seo links (plain html)
	  elseif (is_readable($this->urlpath."/seolinks$lan.txt")) {
	    $file = $this->urlpath."/seolinks$lan.txt";
	    $ret = file_get_contents($file);		
	  }	  
	  elseif (is_readable($this->urlpath.'/'.$this->infolder."/seolinks$lan.txt")) {
	    $file = $this->urlpath.'/'.$this->infolder."/seolinks$lan.txt";
	    $ret = file_get_contents($file);		
	  }	  

	  
	  return $ret;  
	}	
	
	function get_file_contents($myfile,$type=null,$mylan=null) {
	  $lan = $mylan?$mylan:getlocal();
	
	  switch ($type) {
	  case 'xml'  :
	              if (is_readable($this->urlpath."/$myfile")) {
	                $file = $this->urlpath."/$myfile";
	                //read xml and ret the links
	                $ret = null;		
	              }
	              break;
	  case  'txt':
	  case  'htm':
	  case 'html':
	  default    :
	             if (is_readable($this->urlpath."/$myfile")) {
	               $file = $this->urlpath."/$myfile";
	               $ret = file_get_contents($file);		
	             }	    
      }
	  
	  return $ret;  
	}
	
	function getencoding() {

	  return ($this->charset);
	}		
	
	function get_stereobit_link() {
		$icon_file = $this->urlpath.'/'.$this->infolder.'/images/stereobit.png';
				
	    if (is_readable($icon_file))
		  $mylink = "<img src=\"". $this->infolder.'/images/stereobit.png' ."\" border=\"0\" alt=\"stereobit.networlds\">";
		else
		  $mylink = 'stereobit.networlds';	
	    $ret .= "<A href=\"http://www.stereobit.com\">$mylink</A>";	  
		
		return ($ret);
	}
	
	function get_copyright($fromyear=null) {
	    $is_cropwiz = (GetSessionParam('LOGIN')=='yes') ? $this->app_crop_wizard() : null;
	    $t = paramload('SHELL','urltitle');
		$y = $fromyear?$fromyear.'-'.date('Y'):date('Y');
	    $ret .= "&copy; $y, $t &nbsp;";// - All Rights Reserved. ";	  
		
		//if ($this->allow_edit)
		if (!$is_cropwiz)
		  $ret .= $this->get_admin_link();
				
		return ($ret);	
	}

	function get_admin_link() {	

	    //print_r($_GET);
		
		//no edit when in editmode - modify...NOT WORK....
	    //if ((defined('__EDITMODE')) || (GetReq('editmode')>0) || (GetReq('modify'))) 
		  // return;//no edit at frame
	 
		if (is_array($_GET)) {
		  foreach ($_GET as $i=>$t) {
		    if ( ($i!='action') && ($i!='turl') ) 
		      $newquery .= '&'.$i.'='.urlencode($t);
	      }
		}
		else 
		  $newquery = '&t=';		
		  
		$mynewquery = $newquery?$newquery:null;//'&t=';  
			
		$current_page = pathinfo($_SERVER['PHP_SELF']);//parse_url($_SERVER['PHP_SELF'],PHP_URL_PAGE);				
		$target_url = urlencode(encode($current_page['basename'] . "?".$mynewquery));		
        $admin_url = $this->infolder . "/".$current_page['basename'] . "?modify=".encode('stereobit')."&turl=".$target_url;		
		$icon_file = $this->urlpath.'/'.$this->infolder.'/images/editpage.png';
		
        SetSessionParam('authverify',1);		
				
	    /*if (is_readable($icon_file))
		  //$mylink = "<img src=\"". $this->infolder.'/images/editpage.png' ."\" border=\"0\" alt=\"Edit this page\">";
		  $ret .= seturl("modify=".urlencode(base64_encode('stereobit'))."&turl=".$target_url.$mynewquery,$icon_file);//?? load theme 
		else
		  //$mylink = '[Edit]';
		  $ret .= seturl("modify=".urlencode(base64_encode('stereobit'))."&turl=".$target_url.$mynewquery,'[Edit]'); 
		*/ 
		$ret .= seturl("modify=".urlencode(base64_encode('stereobit'))."&turl=".$target_url.$mynewquery,$this->editmode_point); 	
	    //$ret .= "<A href=\"".$admin_url."\">$mylink</A>";	 		
		//$ret .= seturl("modify=".encode('stereobit')."turl=".$target_url.$mynewquery,'[Edit]'); 
		
		return ($ret);
	}
	
	function modify_page() {
	    //echo 'modify';
	}
	
	function frameset($query=null) {
		//fetch current size
		$is_oversized = $this->app_is_oversized();
        if ($is_oversized)
            die($this->self_addspace());//die('Oversized');		
			
			
	    $is_cpwizard = $this->app_cp_wizard();	
	    $encoding = $this->charset;
		
		/*$lan = getlocal(); //no need file come as is
        $lanfile = str_replace($this->htmlext,$lan.$this->htmlext,$this->file);
        if (!$mydata = @is_readbale($lanfile)) {//filename ending in $lan
         $myfile = $lanfile;
        }
		else
		  $myfile = $this->htmlfile;	
	    */
		if (is_array($_GET)) {
		  foreach ($_GET as $i=>$t) {
		    if ( ($i!=pcntladmin) && ($i!='action') ) //??action //bypass pcntladmin=.. param for repoladn same url...
		      $newquery .= '&'.$i.'='.$t;
	      }
		}
		else 
		  $newquery = '&t=';		
		  
	    $query = $query?$query:$newquery;
        $turl = urldecode(decode(GetReq('turl')));		
		
		$fu = $this->edithtml?30:1;
        $fd = $this->editdpc?200:1;
		$edit_html_url = $this->edithtml ? "cp/cpmhtmleditor.php?encoding=".$encoding."&htmlfile=" . urlencode(base64_encode(strtolower($this->argument).$this->htmlext)) : 'cp/cpside.html';
		$edit_page_url = $this->editdpc ? "cp/cpmctrl.php?turl=" . urlencode(base64_encode($turl)) ."&encoding=". $encoding . '&htmlfile=' . urlencode(base64_encode(strtolower($this->argument).$this->htmlext)) : 'cp/cpside.html';
	    $edit_dpc_url = $this->editdpc ? "cp/cpmdpceditor.php?turl=" . urlencode(base64_encode($turl)) : 'cp/cpside.html';
		
	    $ret = "
<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Frameset//EN\" \"http://www.w3.org/TR/html4/frameset.dtd\">
<html>
<head>
<title>".$this->urltitle." - Modify Page (".$turl.")</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$encoding\">
</head>		
<frameset rows=\"*\" cols=\"*,270\" framespacing=\"1\" frameborder=\"yes\" border=\"1\" bordercolor=\"#000000\">
  <frameset rows=\"$fu,*,$fd\" framespacing=\"1\" frameborder=\"yes\" border=\"1\" bordercolor=\"#000000\">
    <frame src=\"" . $edit_page_url . "\" name=\"topFrame\" scrolling=\"NO\"  >";
	
	//$ret .= "<frame src=\"cp/cpmhtmleditor.php?encoding=".$encoding."&htmlfile=" . urlencode(base64_encode($this->htmlfile)) . "\" name=\"mainFrame\" scrolling=\"YES\" >
	if (GetSessionParam('LOGIN')=='yes') {
	  if ($is_cpwizard) 
	    //wizard action 
	  $ret .= "<frame src=\"cp/cpmwiz.php?turl=".urlencode(base64_encode($turl)) ."&encoding=". $encoding . '&htmlfile=' . $htmlfile."\" name=\"mainFrame\" scrolling=\"YES\" >";
	  elseif (($this->argument) && ($this->edithtml))
	    //edit html...
		$ret .= "<frame src=\"" . $edit_html_url . "\" name=\"mainFrame\" scrolling=\"YES\" >";
	  else	
	    $ret .= "<frame src=\"cp/cp.php?editmode=1&encoding=$encoding&turl=" . urlencode(base64_encode($turl)) ."\" name=\"mainFrame\" scrolling=\"YES\" >";
	}  
	else 
	  $ret .= "<frame src=\"cp/cpside.html\" name=\"mainFrame\" scrolling=\"YES\" >";
	  
	
	//hide in 1 px //was 200 (30,*,200))
	$ret .= "<frame src=\"" . $edit_dpc_url . "\" name=\"bottomFrame\" scrolling=\"YES\" >
  </frameset>
  <frame src=\"cp/cpmdbrec.php?turl=" . urlencode(base64_encode($turl)) . '&encoding='.$encoding. "\" name=\"rightFrame\" scrolling=\"YES\">
</frameset>
<noframes>
<body>
<h1>frames not supported!</h1>
</body>
</noframes>
</html>
";
//  <frame src=\"cp/cpmhtmlviewer.php?turl=" . urlencode(base64_encode($turl)) . "\" name=\"rightFrame\" scrolling=\"YES\">
    
	//abort the above method..send to cp
    //header("Location: cp/cp.php");

    return ($ret);		
	}
	
	//dynamic html window system
    function fullpage_iframe($query=null) {
	    $encoding = $this->charset;
		$fu = $this->edithtml?30:1;
        $fd = $this->editdpc?200:1;			
		//fetch cuurent size
		$is_oversized = $this->app_is_oversized();
		$is_cpwizard = $this->app_cp_wizard();
		$is_cropwiz = (GetSessionParam('LOGIN')=='yes') ? $this->app_crop_wizard() : null;
		
		if (is_array($_GET)) {
		  foreach ($_GET as $i=>$t) {
		    if ( ($i!=pcntladmin) && ($i!='action') ) //??action //bypass pcntladmin=.. param for repoladn same url...
		      $newquery .= '&'.$i.'='.$t;
	      }
		}
		else 
		  $newquery = '&t=';		
		  
	    $query = $query?$query:$newquery;
        $turl = urldecode(decode(GetReq('turl')));	
	
		if (GetSessionParam('LOGIN')=='yes') {
			if (($this->argument) && ($this->edithtml)) {
				//edit html...
				if ($is_cpwizard)
				    $mainframe_url = "http://".$this->url;
				elseif ($is_cropwiz)	
					$mainframe_url = $turl; //$this->url;					
				else
				    $mainframe_url = $is_oversized ?
				                     $this->self_addspace(true) : //"http://stereobit.com/netpanel.php?t=addon&appsize=".$is_oversized : 
				                     "cp/cpmhtmleditor.php?cke4=1&encoding=".$encoding."&htmlfile=" . urlencode(base64_encode(strtolower($this->argument).$this->htmlext));
		    }						 
			else {
                if ($is_cpwizard)
				    $mainframe_url = "http://".$this->url;
				elseif ($is_cropwiz)	
					$mainframe_url = $turl; 					
				else			
				    $mainframe_url = $is_oversized ?
				                     $this->self_addspace(true) : //"http://stereobit.com/netpanel.php?t=addon&appsize=".$is_oversized :
						    		 "cp/cp.php?editmode=1&encoding=".$encoding."&turl=" . urlencode(base64_encode($turl));
			}					 
		}  
		else { 
		    if ($is_cpwizard)
				$mainframe_url = "http://".$this->url;
			else
			    $mainframe_url = $is_oversized ?
				                 $this->self_addspace(true) : //"http://stereobit.com/netpanel.php?t=addon&appsize=".$is_oversized :
							     "cp/cpside.html";
	  	}
		$exit_url       = $turl;//'../' . urldecode(base64_decode($_GET['turl'])) . "&editmode=-1";
		$htmlfile       = urlencode(base64_encode(strtolower($this->argument).$this->htmlext));
		$topframe_url   = "cp/cpmctrl.php?turl=" . urlencode(base64_encode($turl)) ."&encoding=". $encoding . '&htmlfile=' . $htmlfile;
		$bottomframe_url= "cp/cpmdpceditor.php?turl=" . urlencode(base64_encode($turl));
        $rightframe_url = "cp/cpmdbrec.php?turl=" . urlencode(base64_encode($turl)) . '&encoding='.$encoding . '&htmlfile=' . $htmlfile;		
        $wizframe_url   = "cp/cpmwiz.php?turl=" . urlencode(base64_encode($turl)) ."&encoding=". $encoding . '&htmlfile=' . $htmlfile;		
		$wizcrop_url    = "cp/cpmwizcrop.php?turl=" . urlencode(base64_encode($turl)) ."&encoding=". $encoding . '&htmlfile=' . $htmlfile;				
		
		//standart menu..
		if ((!$is_oversized) && (!$is_cpwizard) && (!$is_cropwiz)) {
			$js_menuwin = "
var menuwin=dhtmlwindow.open(\"rightframe\", \"iframe\", \"$rightframe_url\", \"Menu\", \"width=250px,height=450px,resize=1,scrolling=1,center=1\", \"recal\")
menuwin.onclose=function(){ 
//returns false if user clicks on \"No\" button:
var returnval=confirm(\"You are about to exit editmode. Are you sure?\");
//return returnval
if (returnval==true) { 
    //alert( \"$exit_url\");
	window.location = \"$exit_url\";
}
return returnval;	
}		
";
		}
		else
		    $js_menuwin = null;		
		
		if (($this->edithtml) && (GetSessionParam('LOGIN')=='yes') && 
		    (!$is_oversized) && (!$is_cpwizard) && (!$is_cropwiz)) {
			$js_cmdwin = "
var cmdwin=dhtmlwindow.open(\"topframe\", \"iframe\", \"$topframe_url\", \"Cmd\", \"width=600px,height=450px,resize=1,scrolling=1,center=0\", \"recal\")
cmdwin.onclose=function(){ 
return window.confirm(\"Close window?\")
}		
";
		}
		else
		    $js_cmdwin = null;
				
		/*if (($this->editdpc) && (GetSessionParam('LOGIN')=='yes')) {
			$js_codewin = "
var codewin=dhtmlwindow.open(\"bottomframe\", \"iframe\", \"$bottomframe_url\", \"Console\", \"width=600px,height=200px,resize=1,scrolling=1,center=0\", \"recal\")
codewin.onclose=function(){ 
return window.confirm(\"Close window?\")
}		
";		
	    }
		else */
		    $js_codewin = null;
			
		if ($is_cpwizard) {
			$js_wizard = "			
var wizwin=dhtmlwindow.open(\"bottomframe\", \"iframe\", \"$wizframe_url\", \"Wizard\", \"width=660px,height=400px,resize=1,scrolling=1,center=1\", \"recal\")
wizwin.onclose=function(){ 
var returnval=confirm(\"You are about to exit. Are you sure?\");
if (returnval==true) { 
	window.location = \"$exit_url\";
}
return returnval;	
}			
";		
	    }
		elseif ($is_cropwiz) {
			$js_wizard = "			
var wizwin=dhtmlwindow.open(\"bottomframe\", \"iframe\", \"$wizcrop_url\", \"Crop wizard\", \"width=660px,height=400px,resize=1,scrolling=1,center=1\", \"recal\")
wizwin.onclose=function(){ 
var returnval=confirm(\"You are about to exit. Are you sure?\");
if (returnval==true) { 
	window.location = \"$exit_url\";
}
return returnval;	
}			
";			
		}
		else 
		    $js_wizard = null;			
		
	    $fp = <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="EN"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=$encoding" />
<link rel="stylesheet" href="cp/dhtmlwindow2.css" type="text/css" />
<script type="text/javascript" src="cp/dhtmlwindow2.js"></script> 
<title>{$this->urltitle} - Modify Page ($turl)</title> 
<style type="text/css">
    body { margin: 0; overflow: hidden; }
   .mainframe { position: absolute; left: 0px; top: 0px; width: 100%; height: 100%;  }
</style>
</head> 
<body> 
<script type="text/javascript">
$js_menuwin
$js_codewin
$js_cmdwin
$js_wizard
</script>
<div class="mainframe">
<iframe id="mainFrame" name="mainFrame" src="$mainframe_url" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="100%" scrolling="auto"></iframe> 
</div>
</body> 
</html>
EOF;
        return ($fp);		
    }
	
	
	function app_cp_wizard() {
	    $wizfile = $this->prpath . 'cpwizard.ini';
		
	    if (is_readable($wizfile)) { 
		    //$ret = @file_get_contents($wizfile);
		    return true;   
		}	

		return false;
	}
	
	function app_crop_wizard() {
	    $cropfile = $this->prpath . 'crop.ini';
		
	    if ((is_readable($cropfile)) || (GetReq('cropwiz'))) { 
		    //$ret = @file_get_contents($wizfile);
		    return true;   
		}	

		return false;
	}	

    function get_app_size() {
  
		$tsize = $this->cached_disk_size();
		//$tsize2 = $this->bytesToSize1024($tsize,1);
        //$ret .= "<br>Folder size ". /*$tsize . '|' .*/ $tsize2;//." bytes";	

        $dsize = $this->cached_database_filesize();	
		//$dsize2 = $this->bytesToSize1024($dsize,1);	
        //$ret .= "<br>Database size ". /*$dsize . '|' .*/ $dsize2;//." bytes";

		$total_size = $tsize + $dsize;
		//$stotal = $this->bytesToSize1024($total_size,1);
		//$ret .= "<br>Total used size ". $stotal;  
		return ($total_size);
    }

    function app_is_oversized() {
  
        $allowed_size = is_readable($this->prpath .'/maxsize.conf.php') ?
	                    intval(@file_get_contents($this->prpath .'/maxsize.conf.php')) : 0;
						
		$current_size = $this->get_app_size();				
					   
		if ($allowed_size < $current_size)			   
		    return ($current_size);
		
        return false;		
    }  
	
	
    protected function bytesToSize1024($bytes, $precision = 2) {
        $unit = array('B','KB','MB','GB');
        return @round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision).' '.$unit[$i];
    }  
 
 
    function filesize_r($path){
		if (!file_exists($path)) return 0;
		
	    if (is_file($path)) return filesize($path);
		$ret = 0;
		
		$glob = glob($path."/*");
		
		if (is_array($glob)) {
			foreach(glob($path."/*") as $fn)
				$ret += $this->filesize_r($fn);
		}	
		return $ret;
    } 
  
    function cached_disk_size($path=null) {
  	   $path = $path ? $path : $this->urlpath; 
       $name = strval(date('Ymd'));
       $tsize = $this->prpath . $name . '-tsize.size';
	   $size = 0;

       if (is_readable($tsize)) {
	        //echo $tsize;
			$size = file_get_contents($tsize);

	   }
	   else {
            $size = $this->filesize_r($path);
			@file_put_contents($tsize, $size);
	   }
	   
	   return ($size);
    }
  
    function cached_database_filesize() {
    $db = GetGlobal('db'); 
	$size = 0;
	
	  if ($db) {
		$name = strval(date('Ymd'));
		$dsize = $this->prpath . $name . '-dsize.size';	
    
		if (is_readable($dsize)) {
			//echo $dsize;
			$size = file_get_contents($dsize);
		}
		else {
			$sSQL = "SHOW TABLE STATUS";
			$res = $db->Execute($sSQL,2);		
			$size = 0;

			if (!empty($res)) { 
				foreach ($res as $n=>$rec) {
					$size += $rec[ "Data_length" ] + $rec[ "Index_length" ];					
				}
			}
			@file_put_contents($dsize, $size);
		}	
	  }	
		
	  return ($size);
    }	
	
	
    function combine_tokens($template_contents,$tokens=null,$doublemark=null) {
	
	    if (!is_array($tokens)) return;
		
		$mark = '$';
		$dmark = $doublemark?'$':'';
		
		$ret = $this->process_commands($template_contents);
		unset ($fp);
		  
		//echo $ret;
	    foreach ($tokens as $i=>$tok) {
            //echo $tok,'<br>';
		    $ret = str_replace($mark.$i.$dmark,$tok,$ret);
	    }
		//clean unused token marks
		for ($x=$i;$x<30;$x++)
		  $ret = str_replace($mark.$x.$dmark,'',$ret);
		//echo $ret;
		return ($ret);
    }
  
  //encrypt tokess to be params
 /* function tokenizer($tokarray) {
  
    $a0 = explode('<TOKENS>',$tokarray);
	
	if (is_array($a0)) {
      //$a = serialize($tokarray);
	  //$b = str_replace('+','<@>',$a);
	  return $a0;
	}
	
	return null;
  }
  
  //decrypt tokess to be params
  function detokenizer($tok) {

    $a0 = explode('<TOKENS>',$tok);
	  
	if (is_array($a0)) {	  
	  //$c = str_replace('TOKENS:','',$tok);  
	  //$b = str_replace('<@>','+',$c);  
      //$a = unserialize($b);
	
	  return $a0;  
	}
	
	return null;
  } */
  
    function is_tokens_var($var) {
  
		if (stristr($var,'<TOKENS>'))
		return true;
	  
		return false;	  
    } 
  
    function subpage($tmpl,$dpc2call,$dmarks=null,$extra_attr=null,$verbose=null) {
         $dm = $dmarks?$dmarks:null;
		 $verb = $this->verbose ? $this->verbose : $verbose;
         
		 if ($extra_attr) {//string of val '0' or '1'
		   $tmpl_extra = str_replace($this->htmlext,$extra_attr.$this->htmlext,$tmpl);
		   $tfile = str_replace('.',getlocal().'.',$tmpl_extra);		   
		   $t = $this->urlpath . $this->infolder . '/cp/' . $this->htmlpage . "/" . $tfile ; 
		 }  
		 else {
		   $tfile = str_replace('.',getlocal().'.',$tmpl);
		   $t = $this->urlpath . $this->infolder . '/cp/' . $this->htmlpage . "/" . $tfile ; 
		 }  
		   
		 if ($verb)			
		   echo $t,'<br>';
		   
	     if (is_readable($t)) {
		    $mytemplate = file_get_contents($t);	
			if ($verbose)
			  echo $t;
	     }
		 else {//parent file
		  $cphtmlpath = "/../cp/$this->htmlpage/";
		  $parentfile = $this->urlpath . $cphtmlpath . str_replace('.',getlocal().'.',$tmpl); 
		  //echo 'z'.$parentfile;
		  if (is_readable($parentfile)) {
			$mytemplate = file_get_contents($parentfile);
			if ($verb)			
			  echo $parentfile,'<br>';
		  }		 
		 }  

		 //if var is encrypted tokens 
		 if ($this->is_tokens_var($dpc2call)) {//direct tokens val }
		   $tokens = explode('<TOKENS>',str_replace('<@>','+',$dpc2call));//(array) $this->detokenizer($dpc2call);
		 }
		 elseif (is_array($dpc2call)) {//direct call from calldpc_use_pointers
		   $tokens = (array) $dpc2call;
		 }
		 else {
		   $precall = str_replace('->',' use ',$dpc2call);
		   $call = str_replace('>','+',$precall);
 	       $tokens = GetGlobal('controller')->calldpc_method($call);		 
		 
		   if ($verb)
		     echo $call,'<br><hr>';
		 }  
		 //print_r($tokens);		 
		 $out = $this->combine_tokens($mytemplate,$tokens,$dm);		 
		 //echo '>',$mytemplate;
		 return ($out);
    }  
	
	
	public function included($fname=null, $uselans=null) {
	
	    if ($fname) {
		    if ($uselans) {
			    $lan = getlocal();
			    $name = str_replace('.',$lan.'.', $fname);
			}
			else
			    $name = $fname;
				
			$pathname = $this->urlpath . '/cp/'.$name;
			//echo $pathname;
			if (is_readable($pathname)) {
				$contents = @file_get_contents($pathname);
			
				//execute commands
				$ret = $this->process_commands($contents);
			
				return ($ret);
			}
        }	
        return null; 		
	}
	
	public function php_self($usedomain=null) {
	
	    if ($usedomain)
		  $ret = $this->url;
		  
	    $ret .= $_SERVER['REQUEST_URI'];// ? '/'.$_SERVER['REQUEST_URI'] : null;
		return ($ret);
	    //return $_SERVER['PHP_SELF'];
    }

	public function nvl($param=null,$state1=null,$state2=null,$value=null) {
	    global ${$param};
	    //echo $param;
		
	    if (stristr($param,'.')) //dpc var
		  $var = GetGlobal('controller')->calldpc_var($param);
        else
          $var =  ${$param} ? ${$param} : ($_SESSION[$param] ? $_SESSION[$param] : $this->{$param});		
		
        if ($value) 
		   $ret = ($value==$var) ? $state1 : $state2;   		
        else
           $ret = $var ? $state1 : $state2;
		   
        //echo '<hr>'.$ret;
		return ($ret);
    }
	
	public function nvltokens($token=null,$state1=null,$state2=null,$value=null) {
	    //always string compare...
		//echo '>',$token,':',$value,'<br/>';
		if ($value) 	
			$ret = ($token==$value) ? $state1 : $state2;  	
        else		
           $ret = $token ? $state1 : $state2;
		   
		return ($ret);

    }	
	
	public function nvldac($param=null,$state1=null,$state2=null,$value=null) {
	    //global ${$param};
	    
	    if (stristr($param,'.')) //dpc var
		  $var = GetGlobal('controller')->calldpc_var($param);
        else
          $var =  GetGlobal($param) ? GetGlobal($param) : (GetParam($param) ? GetParam($param) : $this->{$param});		
		
        if ($value) 
		                           
		   $ret = ($value==$var) ? 
		           GetGlobal('controller')->calldpc_method(str_replace('::','+',$state1)) : 
		           GetGlobal('controller')->calldpc_method(str_replace('::','+',$state2));   		
				   //in case of enfolded dpc cmd...
        else      
           $ret = $var ? 
		          GetGlobal('controller')->calldpc_method(str_replace('::','+',$state1)) : 
		          GetGlobal('controller')->calldpc_method(str_replace('::','+',$state2));
                  //in case of enfolded dpc cmd...  				  

		return ($ret);

    }	
	
	//dummy dpc exec
	public function nvlnull() {
	    return null; 
    }	
	
	protected function self_addspace($retlink=false) {
			
		$ret = $retlink ? 'cp/cp.php?t=cpupgrade&wf=addspace' :
			              "<h3><a href='cp/cp.php?t=cpupgrade&wf=addspace'>" . localize('_addspace', getlocal())."</a></h3>";				
			
		return ($ret);
	}
	
	//used in front page as login / logout
	public static function myf_button($title,$link=null,$image=null) {
	   //$browser = get_browser(null, true);
       //print_r($browser);	
       //echo $_SERVER['HTTP_USER_AGENT']; 
	   //echo '1';
	   $path = self::$staticpath;//$this->urlpath;//
	   
	   if (($image) && (is_readable($path."/images/".$image.".png"))) {
	      //echo 'a';
	      $imglink = "<a href=\"$link\" title='$title'><img src='images/".$image.".png'/></a>";
	   }
	   
	   if (preg_match('/MSIE/i',$_SERVER['HTTP_USER_AGENT'])) { 
	      //echo 'ie';
		  $_b = $imglink ? $imglink : "[$title]";
		  $ret = "&nbsp;<a href=\"$link\">$_b</a>&nbsp;";
		  return ($ret);
	   }	
	   
	   if ($imglink)
	       return ($imglink);
	
       //else button	
	   if ($link)
	      $ret = "<a href=\"$link\">";
		  
	   $ret .= "<input type=\"button\" class=\"myf_button\" value=\"".$title."\" />";
	   
	   if ($link)
          $ret .= "</a>";	   
		  
	   return ($ret);
	}	
	
};
}
?>