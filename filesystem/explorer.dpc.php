<?php
$__DPCSEC['EXPLORER_DPC']='2;2;2;2;2;2;2;2;9';
$__DPCSEC['ADMINFL_']='2;1;1;1;1;1;1;2;9';

if ((!defined("EXPLORER_DPC")) && (seclevel('EXPLORER_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("EXPLORER_DPC",true);

$__DPC['EXPLORER_DPC'] = 'explorer';

$d = GetGlobal('controller')->require_dpc('filesystem/explorer.lib.php');
require_once($d);

$__EVENTS['EXPLORER_DPC'][0]='explorer';
$__EVENTS['EXPLORER_DPC'][1]='explore';

$__ACTIONS['EXPLORER_DPC'][0]='explorer';
$__ACTIONS['EXPLORER_DPC'][1]='explore';



$__LOCALE['EXPLORER_DPC'][0]='EXPLORER_DPC;File explorer;File explorer';

/** php Explorer is a directory browser. it is easy to use and have basic commands. 
 * <br> It make use of css, so you can edit the styles to change the look.
 * <br>Tested on IE6, Firefox 1.5 and Opera 9.
 * <br>Last update: October 24, 2006.
 * <br>Author: Marcelo Entraigas <m_entraigas at yahoo dot com>.
 * <br>Licence: BSD License.
 */
class explorer {

    var $fs, $init_path;
	var $root_path = 'c:/php';//basename(__FILE__);

	function explorer($path='') {	
	
	  /* if ($remoteuser=GetSessionParam('REMOTELOGIN')) 
		  $this->root_path = paramload('SHELL','prpath')."instances/$remoteuser/";	
	   else
		  $this->root_path = paramload('SHELL','prpath');		*/

       if (iniload('JAVASCRIPT')) {
	   
	       $code = $this->javascript();	   	
	   
		   $js = new jscript;
		   //$js->setLoadParams("initEditor()");   
		   //$js->load_js("ajax.js");		   		   
		   	 	      
           $js->load_js($code,null,1);		   			   
		   unset ($js);
	   }
	   else
	      die('JAVASCRIPT_DPC required!');		
	  	  
		  		  
		  
       if ($_POST['cmd']=='cd') {
	      
	     //check root path
		 if ($this->is_valid_path()) { 
		   $cmd = $_POST['val'];	   
		 }  
		 else    
		   $cmd = $this->root_path;
		   
		 SetSessionParam('fspath',$cmd);			     
         $this->fs = new file_utils($cmd);
	   }	 
       else {
	   
		 if ($path!='') 
		    $this->init_path = $path;
		 else {
            $this->init_path = $this->root_path;
		 }
		 
		 if (!$fspath = GetSessionParam('fspath')) {//NOT to delete cuurent path if cmd <> cd
		   SetSessionParam('fspath',$this->init_path);
		   //echo $this->init_path,':';
		   $this->fs = new file_utils($this->init_path);
		 }
		 else  
		  $this->fs = new file_utils($fspath);
	   }	  
	}
	
	function event($event=null) {

	
       switch ($_POST['cmd']) {
	     case 'get':
		   $this->fs->download($_POST['val']);
		   break;
	     case 'upload':
		   $path2browse = $path2up = GetSessionParam('fspath');// . $_POST['val'];
		   $this->fs->upload($path2up);//_path);;
		   break;
	     case 'mkdir':
		   $path2browse = $path2make = GetSessionParam('fspath') . $_POST['val'];
		   $this->fs->mkdir($path2make);
		   //echo $_POST['val'];
		   //echo GetSessionParam('fspath');
		   //echo _path;
		   break;
	     case 'chmod':
		   $path2browse = $path2chmode = GetSessionParam('fspath');
	       foreach ($_POST['files'] as $file) 
		     //echo $this->fs->chmod(_path._slash.$file,$_POST['val']);
		     echo $this->fs->chmod($path2chmode._slash.$file,$_POST['val']);			 
		   break;
	     case 'rm':
		   $path2browse = $path2del = GetSessionParam('fspath');
	       foreach ($_POST['files'] as $file) 
		     //$this->fs->rm(_path.$file);
			 $this->fs->rm($path2del.$file);
		   break;
	     default:
       }
	   
       $this->fs->ls($path2browse); //newlly created path or null	

	}
	
	function action($action=null) {
	
		$ret =  $this->render(); 
		
		return ($ret);  
	}	
	
	function render() {
	   $pagename = pathinfo($_SERVER['PHP_SELF'],PATHINFO_BASENAME);
	
       $pathconst = _path;	

	   $ret = '
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="top_table">
<tr><td colspan="2" class="bottom_table"><div class="title">'.$_pathconst.'</div></td></tr>
<tr valign="top">
 <td width="150" class="bottom_table">';
 
       foreach ($this->fs->folders as $folder => $attr) {
	   
	     $ret .= "<div class=\"row_dir\"><a href=\"#\" onclick=\"send('cd','" . $attr['filepath']._slash . "');\">$folder</a></div>";
	   }
	     
       $ret .= '
 </td>
 <td width="90%" class="bottom_table">
  <form name="go" method="POST" action="'.$pagename.'">
   <input type="hidden" name="cmd" value="" >
   <input type="hidden" name="val" value="" >';
   
       if(count($this->fs->files)) {
	     $ret .= '
   <table border="0" width="100%" cellspacing="0">
    <tr class="header">
      <th>File</th><th>Size</th><th width="50">Perms</th><th width="100">Modified</th>
    </tr>';
	  
	   
         foreach ($this->fs->files as $filename => $attr) {
	      $total+=$attr['size']; 
		  
          $flag=!$flag;
          $class=sprintf("row%b",$flag);
	   
	      $ret .= "<tr align=\"left\">
      <td class=\"$class\"><input type=\"checkbox\" id=\"files\" name=\"files[]\" value=\"$filename\"><a href=\"#\" title=\"Download!\" onclick=\"send('get','" . $attr['filepath'] . "');\">$filename</a></td>
      <td class=\"$class\" align=\"center\">" . $this->fs->get_size($attr['size']) . "</td>
      <td class=\"$class\">" . $attr['perms'] . "</td>
      <td class=\"$class\" align=\"center\">" . $attr['time'] . "</td>
    </tr>";
	     }
	    
         $ret .= "<tr align=\"left\" class=\"header\"><td>Select all <input type=\"checkbox\" onchange=\"if(this.checked)check_all(true); else check_all(false);\"></td><td colspan=\"4\" align=\"right\"><b>" . count($this->fs->files) . " files - " . $this->fs->get_size($total) . "</b></td></tr>
   </table>
   <div align=\"center\" style=\"width:70%; margin-top:15px;\">
    <input type=\"button\" value=\"chmod\" onclick=\"chmod();\" class=\"button\">
    <input type=\"button\" value=\"Delete\" onclick=\"rm();\" class=\"button\">
    <input type=\"button\" value=\"mkdir\" onclick=\"mkdir();\" class=\"button\">
   </div>";
     }
     else {
	   $ret .= "<div class=\"header\" style=\"padding-left:15px;\"><b>No Files on this directory!</b></div>
<div align=\"center\" style=\"width:70%; margin-top:15px;\">
    <input type=\"button\" value=\"mkdir\" onclick=\"mkdir();\" class=\"button\">
</div>";
     }

     $ret .= "</form><div align=\"center\" style=\"width:70%; margin-top:10px;\">
  <form name=\"upload\" method=\"POST\" action=\"" . $pagename . "\" enctype=\"multipart/form-data\">
    <input type=\"file\" name=\"upload\" class=\"button\">
    <input type=\"submit\" name=\"cmd\" value=\"upload\" class=\"button\">
  </form>
  </div>
 </td>
</tr>
</table>";

       return ($ret);
	}
	
	
    function javascript()  {
   
      $pathconst = _path;
   
      $jscript = <<<EOF
	  
function validate(){
 check = document.go.files;
  if(check.length){
    for(c=0; c<check.length; c++) {
      if(check[c].checked)
        return true;
    }
  } else { 
    if(check.checked)
      return true;
  }
  alert('No files selected!');
  return false;
}
function check_all(value){
 check = document.go.files;
  if(check.length){
    for(c=0; c<check.length; c++)
      check[c].checked = value;
  } else check.checked = value;
}
function mkdir(){
  var pattern = new RegExp(/^[^\s]\w+/);
  dir = prompt('Directory name:');
  if(dir.length>0 && pattern.test(dir)){
    send('mkdir','$_pathconst' + dir);
  }
}
function chmod(){
  perm = prompt('Change permissions to:');
  if(perm.length>0 && validate()){
    send('chmod',perm);
  }
}
function rm(){
  if(validate() && confirm('Delete those files?')){
    send('rm','');
  }
}
function send(cmd,val){
   document.forms.go.cmd.value = cmd;
   document.forms.go.val.value = val;
   document.forms.go.submit();
}
EOF;

      return ($jscript);
   }
   
   function css() {
   
     $css = "
<style type=\"text/css\">
body{
 font-family: Arial, Helvetica, sans-serif;
 font-size: 14px;
}
.bottom_table{
 font-family: Arial, Helvetica, sans-serif;
 border-left-style:solid;
 border-left-color:#CC6600;
 border-left-width:3px;
 border-bottom-style:solid;
 border-bottom-color:#CC6600;
 border-bottom-width:3px;
}
.top_table{
 font-family: Arial, Helvetica, sans-serif;
 border-right-style:solid;
 border-right-color:#CC6600;
 border-right-width:3px;
 border-top-style:solid;
 border-top-color:#CC6600;
 border-top-width:3px;
}
.title{
 font-family: Verdana, Arial, Helvetica, sans-serif;
 font-size: 12px;
 font-weight: bold;
 margin: 5px;
}
.header{
 background-color: #F1CD7A;
}
.row_dir{
 margin-bottom:3px; 
 background-color: #FFFFCC;
 border-top-width: 1px;
 border-top-style: solid;
 border-top-color: #FFFF99;
 border-bottom-width: 1px;
 border-bottom-style: solid;
 border-bottom-color: #FFFF99;
}
.row1{
 background-color: #FFFFFF;
}
.row0{
 background-color: #FFFFCC;
 border-top-width: 1px;
 border-top-style: solid;
 border-top-color: #FFFF99;
 border-bottom-width: 1px;
 border-bottom-style: solid;
 border-bottom-color: #FFFF99;
}
.button{
 font-family: Arial, Helvetica, sans-serif;
 background-color: #FFFFCC;
 border:solid;
 border-width: 1px;
 border-color:#660033;
 margin: 5px;
}
</style>	 
	 ";
	 
	 return $css;
   }
   
  function delimit($str) {
  
     $s1 = str_replace('/','',$str);
	 $s2 = str_replace('.','',$s1);
	 
	 return ($s1);
  }  
   
  function is_valid_path() {
     /* echo $this->delimit($_POST['val']),"|||",$this->delimit($this->root_path);
	  
      if ((strlen($this->delimit($_POST['val']))==strlen($this->delimit($this->root_path))) ||
	      ($this->delimit($_POST['val'])===$this->delimit($this->root_path.'..') ))
	 */
	 if (strstr($_POST['val'],$this->root_path)) 
	    return true;
	 return false;  	
  }   		
	
};
}
?>