<?php 	   
if (defined("JAVASCRIPT_DPC")) {
	   
$__DPCSEC['TINYMCE_DPC']='1;1;1;1;1;1;2;2;2';

if ((!defined("TINYMCE_DPC")) && (seclevel('TINYMCE_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("TINYMCE_DPC",true);

$__DPC['TINYMCE_DPC'] = 'tinyMCE';

$__EVENTS['TINYMCE_DPC'][0] = 'tinyMCE';

$__ACTIONS['TINYMCE_DPC'][0] = 'tinyMCE';

$__LOCALE['TINYMCE_DPC'][0]='_TINYMCE;HTML Editor;HTML Editor';

class tinyMCE {  

   var $userLeveID;
   var $path;
   var $mode,$theme;
   
   var $editfile;
   var $remotepath;
   var $remoteuser;
   var $path2imagejs,$path2imagepreview,$path2imagesave,$path2imageread;
   var $backdir;

   function tinyMCE($mode='textareas',$theme='SIMPLE',$update_images=1,$imgpath='images',$dirdepth=0) {	
   
       //echo $imgpath;
   
	   if ($this->remoteuser=GetSessionParam('REMOTELOGIN')) {
	      
		  $this->path = paramload('SHELL','prpath')."instances/$this->remoteuser/";
		  $this->public_path = paramload('SHELL','urlpath')."/" . paramload('ID','hostinpath') . '/';
		   		
		  $this->path2imagepreview = $this->remoteuser . '/' . paramload('ID','hostinpath') . "/$imgpath/";		    
		  $this->path2imagejs = $this->path2imagepreview . "tinyMSE_images_list.js";
/*		  
		  $this->path2imagesave = paramload('SHELL','prpath') . "public/" . $this->path2imagejs;
		  $this->path2imageread = paramload('SHELL','prpath') . "public/" . $this->path2imagepreview;		  
*/
		  $this->path2imagesave = paramload('SHELL','urlpath') . '/' . $this->path2imagejs;
		  $this->path2imageread = paramload('SHELL','urlpath') . '/' . $this->path2imagepreview;		  
 
	   }	  
	   else {
	   
	      if ($remoteapp=GetSessionParam('REALREMOTEAPPSITE')) {
		    $app = $remoteapp . "/";
			
			$this->path = str_replace('instances','public',paramload('SHELL','prpath'));
		    $this->public_path = paramload('SHELL','urlpath')."/". paramload('ID','hostinpath') . '/';			
			
		    $this->path2imagepreview = "../" . $app . "$imgpath/";
		    $this->path2imagejs = $this->path2imagepreview  . "tinyMSE_images_list.js";	
		    $this->path2imagesave = $this->public_path . "$imgpath/" . "tinyMSE_images_list.js";		  
		    $this->path2imageread = $this->public_path . "$imgpath/";			
		  }	
		  else {	  
		    $this->path = paramload('SHELL','prpath');
	   	    $this->public_path = paramload('SHELL','urlpath')."/". paramload('ID','hostinpath') . '/';
			//echo '>',$dirdepth;
			if ($dirdepth) {
			  for($i=0;$i<$dirdepth;$i++)
			    $this->backdir .= "../";
			}
			else
			  $this->backdir = "../";//compatibility!!!!  
			
		    $this->path2imagepreview = $this->backdir . "$imgpath/";
		    $this->path2imagejs = $this->path2imagepreview  . "tinyMSE_images_list.js";	
		    $this->path2imagesave = $this->public_path . "/$imgpath/" . "tinyMSE_images_list.js";		  
		    $this->path2imageread = $this->public_path . "/$imgpath/";
			
			//if ($update_images)
			 // $this->replicate_image_list();
		  }	
	   }	
	   
	   if ($update_images) {
	     //READ IMGE LIST EVERY TIME DEPENDING ON TYPE OF LOADING 
	     //AS CP,AS REMOTE APP,AS REMOTE CP
		 
 	     /*echo "js:",$this->path2imagejs,
		        "<br>save:",$this->path2imagesave,
				"<br>read:",$this->path2imageread;     */

	     $this->create_image_list();
	   }
	   
	
	   $this->theme = $theme;
	   $this->mode = $mode;	       

	   $this->remotepath = paramload('TINYMCE','remotelocation');
	   //echo $this->remotepath;	   
	   
	   
	   if ($this->remotepath) {
	   
	       $code = $this->javascript($this->mode,$this->theme);	   	
	   
		   $js = new jscript;
		  
		   $js->load_js($this->remotepath."tiny_mce/tiny_mce.js",null,null,null,1);		   			      		      
           $js->load_js($code,null,1);		   			   
		   unset ($js);	   
	   }
       else {
	   
	       $code = $this->javascript($this->mode,$this->theme);	   	
	   
		   $js = new jscript;
		  
		   $js->load_js("tiny_mce/tiny_mce.js");//,null,null,null,1);		   			      		      
           $js->load_js($code,null,1);		   			   
		   unset ($js);
	   }		       
   }  

   function event($event=null) {
   
   }
   
   function action($action=null) {
	  
	  return ($out);    
   }  
   
   function javascript($mode,$theme=null) {
   
	   if ($this->remotepath) 
	     $jjpath = $this->remotepath;   
	   else
	     $jjpath = $this->backdir . "javascripts";	 
   
	  $jscript = "  
tinyMCE.init({
	mode : \"textareas\"
";
	
	  if ($theme=='SIMPLE') {
	     $jscript .= ",
    theme : \"simple\"
";
	  }
	  elseif ($theme=='ADVANCED') {
		 $jscript .= ",
    theme : \"advanced\"
";		 		  
	  }	
	  elseif ($theme=='ADVANCEDSIMPLE') {
	     $jscript .= ",
	theme : \"advanced\",
	theme_advanced_buttons1 : \"bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo,link,unlink\",
	theme_advanced_buttons2 : \"\",
	theme_advanced_buttons3 : \"\",
	theme_advanced_toolbar_location : \"top\",
	theme_advanced_toolbar_align : \"left\",
	theme_advanced_path_location : \"bottom\",
	extended_valid_elements : \"a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]\"
";		   
	  }	 
	  elseif ($theme=='ADVANCEDFULL') {
         
		 $jscript .= ",
	theme : \"advanced\",		 	  
	plugins : \"table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,flash,searchreplace,print,contextmenu\",
	theme_advanced_buttons1_add_before : \"save,separator\",
	theme_advanced_buttons1_add : \"fontselect,fontsizeselect\",
	theme_advanced_buttons2_add : \"separator,insertdate,inserttime,preview,zoom,separator,forecolor,backcolor\",
	theme_advanced_buttons2_add_before: \"cut,copy,paste,separator,search,replace,separator\",
	theme_advanced_buttons3_add_before : \"tablecontrols,separator\",
	theme_advanced_buttons3_add : \"emotions,iespell,flash,advhr,separator,print\",
	theme_advanced_toolbar_location : \"top\",
	theme_advanced_toolbar_align : \"left\",
	theme_advanced_path_location : \"bottom\",
	plugin_insertdate_dateFormat : \"%Y-%m-%d\",
	plugin_insertdate_timeFormat : \"%H:%M:%S\",
	extended_valid_elements : \"a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]\",
	external_link_list_url : \"".$jjpath."/tiny_mce/example_data/example_link_list.js\",
	external_image_list_url : \"".$this->path2imagejs."\",
	flash_external_list_url : \"".$jjpath."/tiny_mce/example_data/example_flash_list.js\"
";
	  }
	
      $jscript .= "
});";
		
		return ($jscript);
   }   
   
  	 
   function render($name='content',$width='100%',$rows=20,$data=null,$action=null) {
   
	 $mydata = (isset($data) ? $data : $this->data); 
	 
     if ($action) {
       $filename = seturl("t=$action"); 	 
	   $out = "\n<form action=\"$filename\" method=\"post\">"; 
	 }  
	 
	 $out .= '<div>'; 
     $out .= "\n <textarea wrap='virtual' id='$name' name='$name' style='width: $width' rows='$rows' autowrap>$mydata</textarea>";	 
	 $out .= '</div>';
	 
	 if ($action) {
	   $out .= "<input type=\"submit\" name=\"ok\" value=\"  submit  \" />";
	   $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"$action\" />";	  
	   $out .= "</form>";
	 }
      
	 return ($out); 
   }	
   
  function create_image_list() {
  
     $path  = $this->path2imageread;
  
	 if (is_dir($path)) {	
		
          $mydir = dir($path);
		  //echo $this->path;
          while ($fileread = $mydir->read ()) {
		     //echo $fileread;
		     if (($fileread!='.') && ($fileread!='..')) {
			   //echo $fileread;	   
			   if ((is_file($path."/".$fileread)) &&
			       ((stristr($fileread,".jpg")) ||
				    (stristr($fileread,".gif")) || 
				    (stristr($fileread,".png"))))
					
			     $picfiles[] = $fileread;
		     }	
		  }		   
	  }
	  
	  if (is_array($picfiles)) {
	  
	     $data = "
// This list may be created by a server logic page PHP/ASP/ASPX/JSP in some backend system.
// There images will be displayed as a dropdown in all image dialogs if the \"external_link_image_url\"
// option is defined in TinyMCE init.

var tinyMCEImageList = new Array(
// Name, URL
";
        $max = count($picfiles)-1;
        foreach ($picfiles as $p=>$pf) {
		  $data .= "[\"" . $pf . "\",\"" .  $this->path2imagepreview . $pf  . "\"]";
		  if ($p<$max)
		    $data .= ",
";
		}  

        $data .= ");
";


	   $file = $this->path2imagesave;//$this->path . "public/images/tinyMSE_images_list.js";
	   //echo $file;

       if ($fp = @fopen ($file , "w")) {
                 fwrite ($fp, $data);
                 fclose ($fp);
       }
       else {
         $this->msg = "File creation error ($file)!\n";
		 echo "File creation error ($file)!<br>";
       }

	  }
   }
   
   function replicate_image_list() {
   
      $source = $this->path2imagesave;
      $pdest = $this->path2imagesave;//str_replace('public/','',$this->path2imagesave);
	  $dest = str_replace('instances','public',$pdest);
	  
      //echo "<br>copyto:",$dest,"<br>from:",$source;
	  	  
      if (copy($source,$dest)) {
	     //echo "Replicate image list!";
	     return true;
	  }	 
	  else {
	     echo "Failed to replicate image list!";	 
	  }	 
		 
	  return false;	 
   }	  
  
};
}
}
else die("JAVASCRIPT DPC REQUIRED!");
?>