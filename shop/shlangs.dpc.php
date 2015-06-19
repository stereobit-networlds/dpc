<?php
$__DPCSEC['SHLANGS_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("SHLANGS_DPC")) && (seclevel('SHLANGS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHLANGS_DPC",true);

$__DPC['SHLANGS_DPC'] = 'shlangs';

$d = GetGlobal('controller')->require_dpc('elements/confbar.dpc.php');
require_once($d);
 
$__EVENTS['SHLANGS_DPC'][0]='shlangs';
$__EVENTS['SHLANGS_DPC'][1]='lang';
$__EVENTS['SHLANGS_DPC'][2]='setlanguage';

$__ACTIONS['SHLANGS_DPC'][0]='shlangs';
$__ACTIONS['SHLANGS_DPC'][1]='lang';
$__ACTIONS['SHLANGS_DPC'][2]='setlanguage';

$__DPCATTR['SHLANGS_DPC']['shlangs'] = 'shlangs,1,0,0,0,0,0,0,0,0,0,0,1';
$__DPCATTR['SHLANGS_DPC']['lang'] = 'lang,0,0,0,0,0,0,0,0,0,0,1';
$__DPCATTR['SHLANGS_DPC']['setlanguage'] = 'setlanguage,0,0,0,0,0,0,0,0,0,0,0';

$__LOCALE['SHLANGS_DPC'][0]='SHLANGS_DPC;Languanges;Γλώσσα';

class shlangs extends confbar {

   var $submit_button;
   var $selected_lan;
   var $message;   
   var $url, $googletrans, $path;
   var $slans, $mixglans;

   function shlangs() {
	  $GRX = GetGlobal('GRX');   
	 
	  $this->path = paramload('SHELL','prpath');
	  $urls = arrayload('SHELL','ip');	 
	  $this->url = $urls[0]; 
	  
	  $this->googletrans = remote_paramload('SHLANGS','googletrans',$this->path);	 
      //echo '>',$this->googletrans;
	  $this->slans = (array) remote_arrayload('SHLANGS','selectedlans',$this->path);
	  //echo '>'; print_r($this->slans);
	  $this->mixglans = (array) remote_arrayload('SHLANGS','mixglans',$this->path);
	  //echo '>'; print_r($this->mixglans);
	  
      if ($GRX) { 
          $this->submit_button  = "<input type=\"image\" src=\"". loadTheme('gosearch_b','search',1) ."\" width=\"21\" height=\"21\" border=\"0\">";  
	  }	  
	  else { 
          $this->submit_button  = "<input type=\"submit\" name=\"Submit\" value=\"Ok\">";
	  } 	  
	  	  	
	  $m = remote_paramload('SHLANGS','message',$this->path);	
	  $ff = $this->path.$m;
	  
	  if (is_file($ff)) {
		 $this->message = file_get_contents($ff);
	  }
	  else
		 $this->message = $m; //plain text	  
		
		
	  $this->google_js();//always	
	  
	  //$this->show_languange();
   }
   
   function google_js() {
   
      $lan = getlocal();
      if ((iniload('JAVASCRIPT')) && (($this->googletrans) || ($this->mixglans[$lan]))) {
	  
		   $code = $this->js_google_translate();
	   
		   $js = new jscript;
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }   
   }     
   
   function refresh_page_js() {
   
      if (iniload('JAVASCRIPT')) {
	  
	       if ($this->googletrans)
		     $code = $this->js_google_translate();

	       $code .= $this->javascript();
	   
		   $js = new jscript;
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }   
   }   
   
   //refresh to set lang
   function javascript() {
   
     $ret = "
    	 
function neu()
{	
	top.frames.location.href = \"index.php\";
}
//window.setTimeout(\"neu()\",10);
function goBack()
{
  window.history.back()
}
goBack();
";
	 
	 return ($ret);
   }
   
   function js_google_translate() {
     
	 //select source lang
	 $activelan = getlocal();
	 switch ($activelan) {
	   case 2 :  $lang_pair = 'de|de'; break; //german
	   case 1 :  $lang_pair = 'el|el'; break; //greek	   
   	   case 0 :  
	   default : $lang_pair = 'en|en'; break; //english..default
     }
	 
     //in case of edit...no frame deletion 
	 if (defined('__EDITMODE')==false)
	 if ((!defined('__EDITMODE')) || (!GetReq('editmode')) || (GetReq('editmode')<0))	 
       $ret = "
    //<![CDATA[
    if(top.location!=self.location)top.location=self.location;
	 ";
   
   
     $ret .= "
    function doTranslate(lang_pair) {
	  if(lang_pair.value)
	    lang_pair=lang_pair.value;
	  if(location.hostname=='".$this->url."' && lang_pair=='".$lang_pair."')
	    return;
	  else 
	  if(location.hostname!='".$this->url."' && lang_pair=='".$lang_pair."')
	    location.href=unescape(gfg('u'));
	  else 
	  if(location.hostname=='".$this->url."' && lang_pair!='".$lang_pair."')
	    location.href='http://translate.google.com/translate?client=tmpg&hl=en&langpair='+lang_pair+'&u='+escape(location.href);
	  else 
	    location.href='http://translate.google.com/translate?client=tmpg&hl=en&langpair='+lang_pair+'&u='+unescape(gfg('u'));
	}
	
    function gfg(name) {
	  name=name.replace(/[\[]/,\"\\\[\").replace(/[\]]/,\"\\\]\");
	
	  var regexS=\"[\\?&]\"+name+\"=([^&#]*)\";
	  var regex=new RegExp(regexS);
	  var results=regex.exec(location.href);
	  
	  if(results==null)
	    return '';
		
	  return results[1];
	}
";
	 
	 return ($ret);   
   }
   
   function event($event=null) {
   
      $this->refresh_page_js();
      
	  switch ($event) {
	    case 'shlangs' : break;
		default           : confbar::event($event);
	  }
   }
   
   function action($action=null) {  
      
	  switch ($action) {
	    case 'shlangs' : $ret = null; break;
		default           :if ($this->message)
		                     $ret = $this->message;
						   else	  
		                     $ret = confbar::action($action);
		                   // $ret .= "Languange change to <" . $this->selected_lan . ">";
	  }
	  
	  return ($ret);
   }
   
   function show_languange() {
   
 	  $lan = getlocal();
	  echo '<',GetSessionParam('locale'),'!',$lan,'>';    
   }   
   
   //override
   function selectLocale($title=0,$flags=null,$ctitles=null, $sep=null) {

     if ((seclevel('LANGSEL_',$this->userLevelID)) && (paramload('SHELL','multilang'))) {   

      $lans = array();   //print_r($_SERVER);
	  $purl =parse_url($this->get_server_url());//echo $_SERVER['PHP_SELF'];
	  $query = $purl['query']; //echo '>',$query;
      $thisname = seturl($query);  
	
      $lans = getlans(); 
	  $lan = getlocal();

      if ($lans) { 
       reset ($lans);
       //asort ($lans);
	   
	   //override proc param 
	   $noflags = remote_paramload('SHLANGS','noflags',$this->path);
	   $flags = $noflags?null:$flags;
       //echo $flags,'>';
	   if ($flags==1) {
		 
		 if ($ctitles)
		    $ct = explode('|',$ctitles);
		 $separator = $sep ? $sep : '&nbsp;';		   
		 //echo $ctitles;		 
		 
		 if ($this->googletrans) {
		   //echo 'googletrans';
		   $toprint .= $this->show_google_translator(1);
		 }  
		 elseif ($transviewtype = $this->mixglans[$lan]) {
		   //echo 'trans>', $transviewtype;
		   //print_r($lans);
           foreach ($lans as $lan_num => $lan_descr) {	
		     
			 if ($lan_num == $lan) {
			   switch ($lan) {
			     case 2 : $sourcelang='de'; break; 
			     case 1 : $sourcelang='el'; break;
			     case 0 : 
				 default: $sourcelang='en'; break;
			   }
			   $flag  = isset($ct[$lan_num]) ? $ct[$lan_num] : loadTheme('flag_'.$lan_num,$lan_descr);
			   $lan_selector[] = "<a href=\"javascript:doTranslate('".$sourcelang."|$sourcelang')\" title=\"$lan_descr\">" . $flag . "</a>"; 	
			 }
			 else {
			   //echo $_SERVER['HTTP_VIA'];
			   //if (!$_SERVER['HTTP_VIA']) {
			   if (stristr($_SERVER['HTTP_VIA'],'translate')===FALSE) {
		         $flag  = isset($ct[$lan_num]) ? $ct[$lan_num] : loadTheme('flag_'.$lan_num,$lan_descr);
		         $lan_selector[] = seturl('t=lang'.'&langsel='.$lan_num,$flag);
			   }
			 }
		   }
		   //implode lans
		   $toprint .= implode($separator,$lan_selector);		   
            
           if ((remote_paramload('SHKATALOG','itemlockparam',$this->path)) && 
		       (GetGlobal('UserID')))// translate lock items		
		     $toprint .= $this->show_google_translator($transviewtype);
		 }
		 else {
		   //echo 'default>';
           /*foreach ($lans as $lan_num => $lan_descr) {	
		   
		     $flag  = isset($ct[$lan_num]) ? $ct[$lan_num] : loadTheme('flag_'.$lan_num,$lan_descr);
		     $toprint .= seturl('t=lang'.'&langsel='.$lan_num,$flag) . $separator;//"&nbsp;";
		   }*/
		   foreach ($lans as $lan_num => $lan_descr) {
		     $flag  = isset($ct[$lan_num]) ? $ct[$lan_num] : loadTheme('flag_'.$lan_num,$lan_descr);
			 $lan_selector[] = seturl('t=lang'.'&langsel='.$lan_num,$flag);
		   }	 
			 
           $toprint .= implode($separator,$lan_selector);		   
		 }  
	   }
	   elseif ($flags==2) {

	     if ($this->googletrans)
		   $toprint .= $this->show_google_translator(2);
		 else {  
           //print theme selection list
           $toprint .= "<form action=\"$thisname\" method=\"POST\" class=\"thin\">";
           if ($title) $toprint .= "<b>" . localize('_LOCAL',getlocal()) . " :</b>";
	       $toprint .= "<select name=\"langsel\">\n";

           //read theme array 
	       $selected = getlocal();
           foreach ($lans as $lan_num => $lan_descr) {	
	         if ($lan_num == $selected) $sel = "selected"; else $sel = "";
             $toprint .= "<OPTION $sel value=\"$lan_num\">$lan_descr</OPTION>\n";
           }
	 
           $toprint .= $this->submit_button ;//"<input type=\"submit\" value=\"Ok\">";   
           $toprint .= "<input type=\"hidden\" name=\"FormName\" value=\"Lang\">";	   
           $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"lang\">";	
	       $toprint .= "</form>\n";
	     }
      }
      else {
		 //$toprint = "No Langs"; 
	     return null;
      }
      }
	  
      return ($toprint);
	 } 
   }
   
   function show_google_translator($viewtype) {
   
	 //select source lang
	 $activelan = getlocal();
	 switch ($activelan) {
	   case 2 :  $sourcelang = 'de'; break; //german
	   case 1 :  $sourcelang = 'el'; break; //greek	   
   	   case 0 :  
	   default : $sourcelang = 'en'; break; //english..default
     }   
   
     if ($viewtype==1) {
	 
	   $flag  = loadTheme('flag_1','xxx');
	   //echo '>',$flag;
	   //print_r($this->slans);
	   if (!empty($this->slans)) {//selected flags by param
         foreach ($this->slans as $il=>$sl) {
	 
	      switch ($sl) {
	       case 'en' : $title = 'English'; break;
	       case 'fr' : $title = 'French'; break;
	       case 'de' : $title = 'German'; break;
	       case 'el' : $title = 'Greek'; break;
	       case 'it' : $title = 'Italian'; break;
	       case 'pt' : $title = 'Portugese'; break;
	       case 'ru' : $title = 'Rusian'; break;
	       case 'es' : $title = 'Spanish'; break;		 		 		 		 		 		 		 
	      }
	   
          $ret .= "<a href=\"javascript:doTranslate('".$sourcelang."|$sl')\" title=\"$title\">" . loadTheme('flag_'.$sl,$title) . "</a>"; 	   
	     }		   
	   }
	   else			 
         $ret = "
<a href=\"javascript:doTranslate('".$sourcelang."|en')\" title=\"English\">" . loadTheme('flag_en','English') . "</a> 
<a href=\"javascript:doTranslate('".$sourcelang."|fr')\" title=\"French\">" . loadTheme('flag_fr','French') . "</a> 
<a href=\"javascript:doTranslate('".$sourcelang."|de')\" title=\"German\">" . loadTheme('flag_de','German') . "</a> 
<a href=\"javascript:doTranslate('".$sourcelang."|el')\" title=\"Greek\">" . loadTheme('flag_el','Greek') . "</a> 
<a href=\"javascript:doTranslate('".$sourcelang."|it')\" title=\"Italian\">" . loadTheme('flag_it','Italian') . "</a> 
<a href=\"javascript:doTranslate('".$sourcelang."|pt')\" title=\"Portuguese\">" . loadTheme('flag_pt','Portuguese') . "</a> 
<a href=\"javascript:doTranslate('".$sourcelang."|ru')\" title=\"Russian\">" . loadTheme('flag_ru','Russian') . "</a> 
<a href=\"javascript:doTranslate('".$sourcelang."|es')\" title=\"Spanish\">" . loadTheme('flag_es','Spanish') . "</a> 
";			 
	 
     /*$ret = "
<a href=\"javascript:doTranslate('el|en')\" title=\"English\" class=\"flag\" style=\"font-size:16px;padding:1px 0;background-repeat:no-repeat;background-position:-0px -0px;\"><img src=\"/site3/modules/mod_gtranslate/tmpl/lang/blank.png\" height=\"16\" width=\"16\" style=\"border:0;vertical-align:top;\" alt=\"English\" /></a> 
<a href=\"javascript:doTranslate('el|fr')\" title=\"French\" class=\"flag\" style=\"font-size:16px;padding:1px 0;background-repeat:no-repeat;background-position:-200px -100px;\"><img src=\"/site3/modules/mod_gtranslate/tmpl/lang/blank.png\" height=\"16\" width=\"16\" style=\"border:0;vertical-align:top;\" alt=\"French\" /></a> 
<a href=\"javascript:doTranslate('el|de')\" title=\"German\" class=\"flag\" style=\"font-size:16px;padding:1px 0;background-repeat:no-repeat;background-position:-300px -100px;\"><img src=\"/site3/modules/mod_gtranslate/tmpl/lang/blank.png\" height=\"16\" width=\"16\" style=\"border:0;vertical-align:top;\" alt=\"German\" /></a> 
<a href=\"javascript:doTranslate('el|el')\" title=\"Greek\" class=\"flag\" style=\"font-size:16px;padding:1px 0;background-repeat:no-repeat;background-position:-400px -100px;\"><img src=\"/site3/modules/mod_gtranslate/tmpl/lang/blank.png\" height=\"16\" width=\"16\" style=\"border:0;vertical-align:top;\" alt=\"Greek\" /></a> 
<a href=\"javascript:doTranslate('el|it')\" title=\"Italian\" class=\"flag\" style=\"font-size:16px;padding:1px 0;background-repeat:no-repeat;background-position:-600px -100px;\"><img src=\"/site3/modules/mod_gtranslate/tmpl/lang/blank.png\" height=\"16\" width=\"16\" style=\"border:0;vertical-align:top;\" alt=\"Italian\" /></a> 
<a href=\"javascript:doTranslate('el|pt')\" title=\"Portuguese\" class=\"flag\" style=\"font-size:16px;padding:1px 0;background-repeat:no-repeat;background-position:-300px -200px;\"><img src=\"/site3/modules/mod_gtranslate/tmpl/lang/blank.png\" height=\"16\" width=\"16\" style=\"border:0;vertical-align:top;\" alt=\"Portuguese\" /></a> 
<a href=\"javascript:doTranslate('el|ru')\" title=\"Russian\" class=\"flag\" style=\"font-size:16px;padding:1px 0;background-repeat:no-repeat;background-position:-500px -200px;\"><img src=\"/site3/modules/mod_gtranslate/tmpl/lang/blank.png\" height=\"16\" width=\"16\" style=\"border:0;vertical-align:top;\" alt=\"Russian\" /></a> 
<a href=\"javascript:doTranslate('el|es')\" title=\"Spanish\" class=\"flag\" style=\"font-size:16px;padding:1px 0;background-repeat:no-repeat;background-position:-600px -200px;\"><img src=\"/site3/modules/mod_gtranslate/tmpl/lang/blank.png\" height=\"16\" width=\"16\" style=\"border:0;vertical-align:top;\" alt=\"Spanish\" /></a> 
";*/
     }
     elseif ($viewtype==2)
     $ret = "
<select onchange=\"doTranslate(this);\">
<option value=\"\">Select Language</option>
<option value=\"".$sourcelang."|en\">English</option>
<option value=\"".$sourcelang."|ar\">Arabic</option>
<option value=\"".$sourcelang."|bg\">Bulgarian</option>
<option value=\"".$sourcelang."|zh-CN\">Chinese (Simplified)</option>
<option value=\"".$sourcelang."|zh-TW\">Chinese (Traditional)</option>
<option value=\"".$sourcelang."|hr\">Croatian</option>
<option value=\"".$sourcelang."|cs\">Czech</option>
<option value=\"".$sourcelang."|da\">Danish</option>
<option value=\"".$sourcelang."|nl\">Dutch</option>
<option value=\"".$sourcelang."|fi\">Finnish</option>
<option value=\"".$sourcelang."|fr\">French</option>
<option value=\"".$sourcelang."|de\">German</option>
<option value=\"".$sourcelang."|el\">Greek</option>
<option value=\"".$sourcelang."|hi\">Hindi</option>
<option value=\"".$sourcelang."|it\">Italian</option>
<option value=\"".$sourcelang."|ja\">Japanese</option>
<option value=\"".$sourcelang."|ko\">Korean</option>
<option value=\"".$sourcelang."|no\">Norwegian</option>
<option value=\"".$sourcelang."|pl\">Polish</option>
<option value=\"".$sourcelang."|pt\">Portuguese</option>
<option value=\"".$sourcelang."|ro\">Romanian</option>
<option value=\"".$sourcelang."|ru\">Russian</option>
<option value=\"".$sourcelang."|es\">Spanish</option>
<option value=\"".$sourcelang."|sv\">Swedish</option>
<option value=\"".$sourcelang."|ca\">Catalan</option>
<option value=\"".$sourcelang."|tl\">Filipino</option>
<option value=\"".$sourcelang."|iw\">Hebrew</option>
<option value=\"".$sourcelang."|id\">Indonesian</option>
<option value=\"".$sourcelang."|lv\">Latvian</option>
<option value=\"".$sourcelang."|lt\">Lithuanian</option>
<option value=\"".$sourcelang."|sr\">Serbian</option>
<option value=\"".$sourcelang."|sk\">Slovak</option>
<option value=\"".$sourcelang."|sl\">Slovenian</option>
<option value=\"".$sourcelang."|uk\">Ukrainian</option>
<option value=\"".$sourcelang."|vi\">Vietnamese</option>
<option value=\"".$sourcelang."|sq\">Albanian</option>
<option value=\"".$sourcelang."|et\">Estonian</option>
<option value=\"".$sourcelang."|gl\">Galician</option>
<option value=\"".$sourcelang."|hu\">Hungarian</option>
<option value=\"".$sourcelang."|mt\">Maltese</option>
<option value=\"".$sourcelang."|th\">Thai</option>
<option value=\"".$sourcelang."|tr\">Turkish</option>
<option value=\"".$sourcelang."|fa\">Persian</option>
</select>
";

     return ($ret);	 
   }	   
};
}
?>