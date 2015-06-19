<?php 	   
if (defined("JAVASCRIPT_DPC")) {
	   
$__DPCSEC['HTMLAREA_DPC']='1;1;1;1;1;1;2;2;2';

if ((!defined("HTMLAREA_DPC")) && (seclevel('HTMLAREA_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("HTMLAREA_DPC",true);

$__DPC['HTMLAREA_DPC'] = 'htmlarea';

$__EVENTS['HTMLAREA_DPC'][0] = 'edithtml';
$__EVENTS['HTMLAREA_DPC'][1] = 'gethtml';
$__EVENTS['HTMLAREA_DPC'][2] = 'savehtml';

$__ACTIONS['HTMLAREA_DPC'][0] = 'edithtml';
$__ACTIONS['HTMLAREA_DPC'][1] = 'gethtml';
$__ACTIONS['HTMLAREA_DPC'][2] = 'savehtml';

$__LOCALE['HTMLAREA_DPC'][0]='_HTMLAREA;HTML Editor;HTML Κειμενογράφος';

class htmlarea {  

   var $userLeveID;
   
   var $name;
   var $width;
   var $rows;
   var $wrap;
   var $data;
   var $path;
   
   var $editfile;

   function htmlarea($name='htmlarea',$rows=20,$width='100%',$data='') {	
	   $GRX = GetGlobal('GRX');	    
	   $UserSecID = GetGlobal('UserSecID');
	   $__USERAGENT = GetGlobal('__USERAGENT');	   
	   
       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);	
	   
	   $this->name = $name;
       $this->rows = $rows;
       $this->width= $width;	   
	   $this->data = $data;
	   
       $ip = $_SERVER['HTTP_HOST'];
       $pr = paramload('SHELL','protocol');			   
	   $this->path = $pr . $ip . '/' . paramload('HTMLAREA','path');//"/htmlarea/";
	   
	   $this->editfile = null;

       if (iniload('JAVASCRIPT')) {
	   
	       $code = $this->javascript_new();	   	
	   
		   $js = new jscript;
		   $js->setLoadParams("initEditor()");   
		   $js->load_js("htmlarea2/htmlarea.js");		   			   
		   $js->load_js("htmlarea2/htmlarea-lang-en.js");
		   $js->load_js("htmlarea2/dialog.js");		   		   
		   	 	      
           $js->load_js($code,null,1);		   			   
		   unset ($js);
	   }		       
	   
   }  

   function event($event=null) {
       $param1 = GetGlobal('param1');
	   
	   switch ($event) {
	     case 'edithtml' : if ($param1) $this->readhtml($param1);
		                   break;
	     case 'savehtml' : //if ($param1) 
		                   $this->savehtml($param1);
		                   break;						   
		 case 'gethtml'  : break;
	   }
   }
   
   function action($action=null) {
	  
	  $contents = null;		  
	  $out = setNavigator(localize('_HTMLAREA',getlocal()));	   
	  
	  //preview content
	  if ($data=getParam("$this->name")) {
	    //$winres = new window('Preview',$data);
	    //$out .= $winres->render();
	    //unset($winres);	  
	    $contents = $data;//getParam("$this->name");
	  }	  
	  
	  $contents .= $this->render($data); 	  
	  $win = new window(localize('_HTMLAREA',getlocal()),$contents);
	  $out .= $win->render();
	  unset($win);
	  
	  return ($out);    
   }  
   
   function javascript() {
   
	  $jscript = "
_editor_url = \"$this->path\";                     // URL to htmlarea files
var win_ie_ver = parseFloat(navigator.appVersion.split(\"MSIE\")[1]);
if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
if (win_ie_ver >= 5.5) {
  document.write('<scr' + 'ipt src=\"' +_editor_url+ 'editor.js\"');
  document.write(' language=\"Javascript1.2\"></scr' + 'ipt>');  
} else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
";		
		return ($jscript);
   }   
   
   function javascript_new()  {
   
      $jscript = <<<EOF
	  
var editor = null;
function initEditor() {
  editor = new HTMLArea("body");
  editor.generate();
}
function insertHTML() {
  var html = prompt("Enter some HTML code here");
  if (html) {
    editor.insertHTML(html);
  }
}
function highlight() {
  editor.surroundHTML('<span style="background:yellow">', '</span>');
}
EOF;

      return ($jscript);
   }
  	 
   function render($data='') {
   
     $filename = seturl("t=gethtml"); 
	 $this->data = ($data ? $data : $this->data);  

     $out = "\n<form action=\"$filename\" method=\"post\">";  
     //$out ="\n <textarea id='$this->name' name='$this->name' style='width: $this->width' rows='$this->rows' wrap='$this->wrap'>$this->data</textarea>";
     $out .= "\n <textarea id='$this->name' name='$this->name' style='width: $this->width' rows='$this->rows'>$this->data</textarea>";
     $out .= "<script language=\"javascript1.2\">editor_generate('$this->name');</script>";	 
	 $out .= "<input type=\"submit\" name=\"ok\" value=\"  submit  \" />";
	 $out .= "<input type=\"submit\" name=\"FormAction\" value=\"savehtml\" />";	 
	 $out .= "<input type=\"button\" name=\"ins\" value=\"  insert html  \" onclick=\"return insertHTML();\" />";
	 $out .= "<input type=\"button\" name=\"hil\" value=\"  highlight text  \" onclick=\"return highlight();\" />";
	 $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"gethtml\" />";	 
	 $out .= "</form>";
      
	 return ($out); 
   }	
   
   function readhtml($htmlfile) {
   
     $pathfile = paramload('SHELL','prpath') . $htmlfile;
	 
	 $this->editfile = $pathfile;
     
	 $mydata = file ($pathfile);
	 
	 $this->data = implode("\n",$mydata); 
   } 
   
   //under construction
   function savehtml($htmlfile) {
   
     $pathfile = paramload('SHELL','prpath') . 'test.root';//$htmlfile;   
	 
	 $mydata = getParam($this->name);
	 
	 $fd = @fopen( $pathfile, "w" );

	 if ((!$fd) && (!$mydata)) {
       setInfo("File ($pathfile) NOT saved !!!!");
	 }
     else { 
 	   fwrite($fd, $mydata);
  	   fclose($fd);	 
       setInfo("File ($pathfile) saved successfully!!!!");	   
	 }  
   }
   
   
   function show($id,$val='',$rows=10,$cols=60,$wrap='virtual') {
   
       $out = "<DIV class=\"monospace\"><TEXTAREA style=\"width:100%\" NAME=\"$id\" ROWS=$rows cols=$cols wrap=\"$wrap\">";
       $out .= $val; 
       $out .= "</TEXTAREA></DIV>";
	   $out .= "<script language=\"javascript1.2\">editor_generate('$id');</script>";      
	   
	   return ($out);
   }
  
};
}
}
else die("JAVASCRIPT DPC REQUIRED!");
?>