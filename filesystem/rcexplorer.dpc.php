<?php
$__DPCSEC['RCEXPLORER_DPC']='2;2;2;2;2;2;2;2;9';


if ((!defined("RCEXPLORER_DPC")) && (seclevel('RCEXPLORER_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCEXPLORER_DPC",true);

$__DPC['RCEXPLORER_DPC'] = 'rcexplorer';

$d = GetGlobal('controller')->require_dpc('filesystem/explorer.dpc.php');
require_once($d);

$__EVENTS['RCEXPLORER_DPC'][0]='cpexplorer';
$__EVENTS['RCEXPLORER_DPC'][1]='explore';

$__ACTIONS['RCEXPLORER_DPC'][0]='cpexplorer';
$__ACTIONS['RCEXPLORER_DPC'][1]='explore';

//add event-action for kshow` cmd
GetGlobal('controller')->set_command('cpfs','0,0,0,0,0,0,0,0,1','RCFS_DPC');

$__LOCALE['RCEXPLORER_DPC'][0]='RCEXPLORER_DPC;File explorer;File explorer';

class rcexplorer extends explorer { 

    var $title;
	
	function rcexplorer($path='') {
	
	    $this->title = localize('RCEXPLORER_DPC',getlocal());			
	
	   if ($remoteuser=GetSessionParam('REMOTELOGIN')) 
		  $this->root_path = paramload('SHELL','prpath')."instances/$remoteuser/";	
	   else
		  $this->root_path = paramload('SHELL','prpath');	
	  		
	   explorer::explorer($path);
	}
	
	function event($event=null) {

	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////		
	   
		switch ($event) {
		  case 'cpexplorer' :
		  case 'cpfs' :	
		                explorer::event($event); 
						GetGlobal('controller')->calldpc_method("rcsidewin.set_show use ".$this->sidewin());
		}	   
	   
	}
	
	function action($action=null) {
	
		switch ($action) {
		  case 'cpexplorer' :		
		  case 'cpfs' :	$ret =  $this->render(null,true); 
		}
		
		return ($ret);  
	}	
	
	//override
	function render($url=null,$nofolders=null,$title=null) {
	 
	 if (!$title) {
	 if (GetSessionParam('REMOTELOGIN')) 
	   $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	 else  
       $out = setNavigator(seturl("t=cp","Control Panel"),$this->title); 	 
	 }  	
	
	   $pagename = 'cpexplorer.php';//pathinfo($_SERVER['PHP_SELF'],PATHINFO_BASENAME);
	
       $pathconst = _path;	
	
	   $ret = '
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="top_table">
<tr><td colspan="2" class="bottom_table"><div class="title">'.$_pathconst.'</div></td></tr>
<tr valign="top">';
      
	   if ($nofolders===null) {
	     $ret .= '<td width="150" class="bottom_table">';
         foreach ($this->fs->folders as $folder => $attr) {
	   
	       $ret .= "<div class=\"row_dir\"><a href=\"#\" onclick=\"send('cd','" . $attr['filepath']._slash . "');\">$folder</a></div>";
	     }
	     $ret .= '</td>';
	   }
	     
       $ret .= '
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
	   
	      $ret .= "<tr align=\"left\">";
		  
		  if ($url) {
		    $myurl = seturl($url.$filename,$filename);
		    $ret .= "<td class=\"$class\">$myurl</td>";
		  }	
		  else
            $ret .= "<td class=\"$class\"><input type=\"checkbox\" id=\"files\" name=\"files[]\" value=\"$filename\"><a href=\"#\" title=\"Download!\" onclick=\"send('get','" . $attr['filepath'] . "');\">$filename</a></td>";
	  
          $ret .= "<td class=\"$class\" align=\"center\">" . $this->fs->get_size($attr['size']) . "</td>
      <td class=\"$class\">" . $attr['perms'] . "</td>
      <td class=\"$class\" align=\"center\">" . $attr['time'] . "</td>
    </tr>";
	     }
	     if (!$title) { 
           $ret .= "<tr align=\"left\" class=\"header\"><td>Select all <input type=\"checkbox\" onchange=\"if(this.checked)check_all(true); else check_all(false);\"></td><td colspan=\"4\" align=\"right\"><b>" . count($this->fs->files) . " files - " . $this->fs->get_size($total) . "</b></td></tr>";
		 }  
         $ret .= "</table>";
   
   	   if (!$title) {
         $ret .= "<div align=\"center\" style=\"width:70%; margin-top:15px;\">
    <input type=\"button\" value=\"chmod\" onclick=\"chmod();\" class=\"button\">
    <input type=\"button\" value=\"Delete\" onclick=\"rm();\" class=\"button\">
    <input type=\"button\" value=\"mkdir\" onclick=\"mkdir();\" class=\"button\">
   </div>";
       }
     }
     else {
	   if (!$title) {
	     $ret .= "<div class=\"header\" style=\"padding-left:15px;\"><b>No Files on this directory!</b></div>
<div align=\"center\" style=\"width:70%; margin-top:15px;\">
    <input type=\"button\" value=\"mkdir\" onclick=\"mkdir();\" class=\"button\">
</div>";
       }
     }

     $ret .= "</form><div align=\"center\" style=\"width:70%; margin-top:10px;\">";
	 
	 if (!$title) {
       $ret .= "<form name=\"upload\" method=\"POST\" action=\"" . $pagename . "\" enctype=\"multipart/form-data\">
    <input type=\"file\" name=\"upload\" class=\"button\">
    <input type=\"submit\" name=\"cmd\" value=\"upload\" class=\"button\">
  </form>";
    }
    $ret .= "	
  </div>
 </td>
</tr>
</table>";

	  if (isset($title))
	    $swin = new window($title,$ret);
	  else	
	    $swin = new window(GetSessionParam('fspath'),$ret);
		
	  $out .= $swin->render("center::100%::0::group_win_body::left::0::0::");	
	  unset ($swin);	

       return ($out);
	}	
	
	function render_dirs() {
	
       foreach ($this->fs->folders as $folder => $attr) {
	   
	     $ret .= "<div class=\"row_dir\"><a href=\"#\" onclick=\"send('cd','" . $attr['filepath']._slash . "');\">$folder</a></div>";
	   }
	   
	   return ($ret);	
	}	
	
	function sidewin() {
	
	   return ($this->render_dirs());
	}
};
}	
?>