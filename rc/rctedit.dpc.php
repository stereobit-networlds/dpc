<?php
$__DPCSEC['RCTEDIT_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCTEDIT_DPC")) && (seclevel('RCTEDIT_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCTEDIT_DPC",true);

$__DPC['RCTEDIT_DPC'] = 'rctedit';

$d = GetGlobal('controller')->require_dpc('gui/tinyMCE.dpc.php');
require_once($d);

$__EVENTS['RCTEDIT_DPC'][0]='cptedit';
$__EVENTS['RCTEDIT_DPC'][1]='cptsave';
$__EVENTS['RCTEDIT_DPC'][2]='cptnew';
$__EVENTS['RCTEDIT_DPC'][3]='cptnewpage';
$__EVENTS['RCTEDIT_DPC'][4]='cptnewcopy';
$__EVENTS['RCTEDIT_DPC'][5]='cptsavecopy';
 
$__ACTIONS['RCTEDIT_DPC'][0]='cptedit';
$__ACTIONS['RCTEDIT_DPC'][1]='cptsave';
$__ACTIONS['RCTEDIT_DPC'][2]='cptnew';
$__ACTIONS['RCTEDIT_DPC'][3]='cptnewpage';
$__ACTIONS['RCTEDIT_DPC'][4]='cptnewcopy';
$__ACTIONS['RCTEDIT_DPC'][5]='cptsavecopy';

$__DPCATTR['RCTEDIT_DPC']['cptedit'] = 'cptedit,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCTEDIT_DPC'][0]='RCTEDIT_DPC;Add/Edit html files;Add/Edit html files';

class rctedit  {

    var $path,$title,$appname;
	var $MAXSIZE;	
	var $msg,$post;
	var $htmlpath,$scriptpath,$publicpath;
	var $standalone, $urlpath, $infolder;
	var $depth;
	var $editmode, $url, $encoding;
	var $htmleditor, $prpath, $ckeditor;	

    function rctedit($maxsize=null) {
	
	    //$this->url = paramload('SHELL','url');
	    $murl = arrayload('SHELL','ip');
        $this->url = $murl[0]; 

        $char_set  = arrayload('SHELL','char_set');	  
        $charset  = paramload('SHELL','charset');	  		
	    if (($charset=='utf-8') || ($charset=='utf8'))
	      $this->encoding = 'utf-8';
	    else  
	      $this->encoding = $char_set[getlocal()]; 	
	
	    $this->title = localize("RCTEDIT_DPC",getlocal());	
	    $this->baseurl = paramload('SHELL','urlbase');					
        $this->urlpath = paramload('SHELL','urlpath');
        $this->infolder = paramload('ID','hostinpath');
	   
		if ($remoteuser=GetSessionParam('REMOTELOGIN')) {
		  //$this->path = paramload('SHELL','prpath')."instances/$remoteuser/";		
          $this->path = $this->urlpath."/$remoteuser/" . $this->infolder."/cp/";		  
		  $this->appname = $remoteuser;
		}  
		else {
		  //$this->path = paramload('SHELL','prpath');
          $this->path = $this->urlpath.$this->infolder.'/cp/';
		  $this->standalone = true;//called by client in it own cp
		}  
		  
		if (isset($maxsize))
		  $this->MAXSIZE = $maxsize;
		else
		  $this->MAXSIZE = 59024; 		  		   
		  
		$this->htmlpath  = $this->path . paramload('RCTEDIT','path'); 
		$this->scriptpath = $this->path . paramload('RCTEDIT','spath');
		
		$this->publicpath = $this->urlpath.$this->infolder.'/'; //$this->path . "public/"; 
		$this->depth = 2; //used to replace images of MCedit with ../ depth n times
		
		$this->editmode = GetReq('editmode');
	    if ($this->editmode) {
		   
		   $cke_js = remote_paramload('CKEDITOR','ckeditorjs' ,$this->prpath);
		   $ckeditor_js = $cke_js ? $cke_js : "http://www.stereobit.gr/ckeditor/ckeditor.js"; 
		   
		   $js = new jscript;
		   $js->load_js($ckeditor_js,null,null,null,1);		   			      		      
           //$js->load_js($code,null,1);		   			   
		   unset ($js);		  
	    }	 

	    $this->ckeditor = '/';			
	}
	
	function event($event=null) {
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////		
	
	   switch ($event) {
	   
		 case 'cptsavecopy': 
		                  //create init files 
						  $this->new_php_file(GetParam('title'), GetParam('copyfile'));
						  $this->new_html_file(GetParam('title'), GetParam('copyfile'));
							
						  //$this->open_tools();	
											
		                  $this->post = true;
		                  break;	   

	     case 'cptnewcopy': 
						  $this->open_dialog(); 	
		                  break;						  
	   
	     case 'cptnewpage': 
						  $this->open_dialog(); 	
		                  break;		   
	   
	     case 'cptnew'  : //if ($this->editmode) 
							//$this->open_tools();
						  //$this->open_dialog(); 	
		                  break;	   
	    
		 case 'cptsave' : if ($this->editmode) {
		                    //create init files 
							$this->new_php_file(GetParam('title'));
							$this->new_html_file(GetParam('title'));
							
							//$this->open_tools();	
		                  }
						  else
		                    $this->save_file($this->htmlpath,$this->depth/*,$this->appname.'/'*/); 
											
		                  $this->post = true;
		                  break;
						  
	     default :        //if ($this->editmode)
							//$this->open_tools();
	   }
	}
	
	function action($action=null) {
	 
	   if ($newfile = GetReq('newname'))//as came from new submit form
	     $file = '/'.$newfile;
	   else	{ 
	     $file = GetReq('f')?GetReq('f'):GetParam('id');//selected
	   }	 
	   //echo $file,'>';
	   //print_r($_POST);	 
	   
	   if (!$this->editmode) {
	     if (GetSessionParam('REMOTELOGIN')) 
	       $ret = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	     else  
           $ret = setNavigator(seturl("t=cp","Control Panel"),$this->title);		    	 
       }	
	
	   switch ($action) {
	   
		 case 'cptsavecopy': //goto htmleditor
		                     $ret .= $this->new_dialog(true);
                             break;		 
	   
	     case 'cptnewcopy': //null	
		                    //$ret .= "<div id=\"dialog\" style=\"display:none\">";
                            $ret .= $this->new_dialog(true);
                            //$ret .= "</div>";	
                            //$this->open_dialog(); 								
		                  break;		   
	   
	     case 'cptnewpage': //null	
		                    //$ret .= "<div id=\"dialog\" style=\"display:none\">";
                            $ret .= $this->new_dialog();
                            //$ret .= "</div>";	
                            //$this->open_dialog(); 								
		                  break;	   
	   
	     case 'cptnew'  : if ($this->editmode) {
		                    //render cpmctrl...
							//echo 'new page dialog';
							$ret .= $this->new_dialog();
		                  }
						  else 
		                    $ret .= $this->editform($file,$this->htmlpath,"New...",/*$this->appname."/images/"*/null,1);
		                  break;
		 case 'cptsave' :  		 
	     default        : 
		                  if ($this->editmode) {
		                    
							$ret .= $this->new_dialog();
							
							//redirect frame pages
							/*$htmlpage = strtolower(str_replace('.html',getlocal().'.html',GetParam('title').'.html')); 							
							$p = urlencode(base64_encode($htmlpage));
							header("location:cpmhtmleditor.php?encoding=".$this->encoding."&htmlfile=" . $p);*/
		                  }
						  else {
		                    $ret .= $this->tasks($action); 
							
				            if ($this->standalone)
				              $ret .= $this->show_template_files("Load");//CALLED BY CPSSYSTEM.CPWINHELP AT TEMPLATE
							  
		                    $ret .= $this->editform($file,$this->htmlpath,"Edit..."/*,$this->appname."/images/"*/);
				            //if ($this->standalone)
				              //$ret .= $this->show_template_files();//CALLED BY CPSSYSTEM.CPWINHELP AT TEMPLATE
						  }	
	   }	   
	   
	   return ($ret);
	}
	
	function dhtml_javascript($code, $returnastext=false) {
	
      if (iniload('JAVASCRIPT')) {
	  
	       if ($returnastext) {
		        $ret = '<script language="JavaScript">'.$code.'</script>';
				return ($ret);
		   }

		   $js = new jscript;		   
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }		
	}	
	
    function editform($tfile,$tpath=null,$title=null,$treplace=null,$isnew=null) {  

		  //$this->htmleditor = new tinyMCE('textareas','ADVANCEDFULL',1,'images',$this->depth);			  
		  
		  if ($name = GetParam('newname'))
		    $file = '/' . $name;
		  else
		    $file = GetReq('f');
			
	      $ypsos = 20;
          $myaction = seturl("t=cptsave&f=".$file);	 	
	      $action = 'cptsave';				

	      if ($this->post==true) {	   
	   
	        $swin = new window("",$this->msg);
	        $winout = $swin->render("center::70%::0::group_article_body::center::0::0::");	
	        unset ($swin);
	      }		
		  
		  $source = $this->loadfromfile($tfile,$tpath);//,$this->depth);
		  if ($treplace) 
		     $data = str_replace("images/",$treplace,$source);
		  else
		     $data = $source;	 			  
          //echo $source,'>';
	      $out .= "<form method=\"post\" name=\"RCEDITTEMPLATES\" action=\"$myaction\">";
	   
   	      //$out .= $this->htmleditor->render('body','100%',$ypsos,$data);	
		  $out .= $this->render_textarea($data,$ypsos);
		  
		  if ($isnew)     
		    $out .= "<input type=text name='newname' value=\"noname.html\">";
	      
          $out .= "<input type=submit value=\"Save\">";
	   
          //default name to save
		  $myfilename = str_replace('.php','.html',$tfile);
	      $out .= "<input type=\"hidden\" name=\"tosave\" value=\"".$tfile."\">"; 		  	 
 	   
	   
	      $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"$action\">";   
          $out .= "</form>"; 			  
		
		  $wina = new window($title,$out);
		  $winout .= $wina->render();//"center::100%::0::group_dir_title::right::0::0::");
		  unset ($wina);	
		
		  return ($winout);
    }	
	
    function loadfromfile($tfile,$tpath,$treplace=null) {
	 
	 $file = $tpath . '/'.str_replace('.php','.html',$tfile);
	 //echo $file,'>';  
	 
	 
     if ((is_file($file)) && ($fp = @fopen ($file , "r"))) {//load the file
                 $ret = fread ($fp, filesize($file));
                 fclose ($fp);
     }
     else {//is a new html page... return init html code <<<<<< NOT LOADED iN MCEDIT
	             $PARAM = strtoupper(str_replace('.php','',$tfile)); //NO PARAM NAME EXIST YET!
                 $ret = "
<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<title>Title</title>
<LINK REL=StyleSheet HREF=\"styles.css\">
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . $this->encoding . "\">
</head>
<body text=\"#333333\" link=\"#FF0000\" vlink=\"#FF6600\" alink=\"#FF0000\">

</body>
</html>				 
";
     }
	 
	 //transform
	 //$a	= str_replace('<','[',$ret); 
	 //$b	= str_replace('>',']',$a);	 
	 
	 return ($ret);
    }	
	
    function save_file($tpath=null,$treplace=null) {
	
		 $data = GetParam('body');
		 //echo substr($data,1,50);
		 
		 if ($newname = GetParam('newname')) {//new file 
		 
                 $basichtml = "
<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<title>Title</title>
<LINK REL=StyleSheet HREF=\"styles.css\">
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . $this->encoding . "\">
</head>
<body text=\"#333333\" link=\"#FF0000\" vlink=\"#FF6600\" alink=\"#FF0000\">
<@>
</body>
</html>				 
";		 
           //only for .html exclude root files
		   if (stristr($newname,'.html') or stristr($newname,'.htm'))
             $data = str_replace('<@>',$data,$basichtml);
		 
		   // / added here due to /name.ext call of f param
		   $name2save = '/' . str_replace('.php','.html',GetParam('newname'));
		   
		   //create script and config as needed
		   $this->new_script_file('/'.$name2save);// / added here due to /name.ext call of f param
           $this->new_config_file('/'.$name2save);		
		   $this->new_php_file('/'.$name2save);		   
		 }  
		 else
		   $name2save = str_replace('.php','.html',GetParam('tosave'));
		 
         if ($treplace) {
	       $source = str_repeat('../',$treplace);
	       $abspath = paramload('SHELL','urlbase') . paramload('ID','hostinpath') .'/';		 
		   
		   $data = str_replace($source,$abspath,$data);		
		 }  	     
	
	     if ($data)  {
         
		  if (strlen($data)<=$this->MAXSIZE) {
		  
		    $file = $tpath . '/' . $name2save; //echo $file,'>';
	        
            if ($fp = @fopen ($file , "w")) {
	        //echo $file,"<br>";
                 fwrite ($fp, $data);
                 fclose ($fp);
            }
            else {
              $this->msg = "File creation error ($file)!\n";
		      //echo "File creation error ($filename)!<br>";
            }	
			  			
		  }
		  else 
		    $this->msg = "File size error (Maximum size ". $this->MAXSIZE ." bytes)!\n";
			
		  $this->post = true;	
        }
		else
		  $this->post = false;
		  
		return ($this->msg);  
	}
	
	//create a new script bundled to html 
	function new_script_file($name) {
	
		    $file = $this->scriptpath . str_replace('.html','.prj',$name);
			
			$data = "
super cache,log;
super javascript;

#---------------------------------load and create libs
use xwindow.window;

#---------------------------------load not create dpc (internal use)
include dpcmodules.clientdpc;
include frontpage.fronthtmlpage;
	
#---------------------------------load not create extensions (internal use)	

#---------------------------------load all and create after dpc objects

rc.rcbasichtml;		
";	
	        
            if ($fp = @fopen ($file , "w")) {
	        //echo $file,"<br>";
                 fwrite ($fp, $data);
                 fclose ($fp);
            }
            else {
              $this->msg .= "File creation error ($file)!\n";
		      //echo "File creation error ($filename)!<br>";
            }	
	}
	
	//create a new script bundled to html 
	function new_config_file($name) {
	
		    $file = $this->scriptpath . str_replace('.html','.conf',$name);
			
			$data = "
theme=
lan=
cl=
fp=$name	
";	
	        
            if ($fp = @fopen ($file , "w")) {
	        //echo $file,"<br>";
                 fwrite ($fp, $data);
                 fclose ($fp);
            }
            else {
              $this->msg .= "File creation error ($file)!\n";
		      //echo "File creation error ($filename)!<br>";
            }	
	}	
	
	function new_php_file($name, $copyfrom=null) {
	        $myname = $name?strtolower($name).'.php':'new.php';
		    $file = $this->publicpath . str_replace('.html','.php',$myname);
			$html_page = $name . getlocal() .'.html';
			
			if ($copyfrom) {
			 
			    $sfile = $this->publicpath.'/'.$copyfrom;//.'.php';
				//echo $sfile,'#<br>';
				if (is_readable($sfile)) {
				  $tfile = $this->publicpath.'/'.$myname;
			      $ret = @copy($sfile, $tfile);
				}
				return ($ret);
			}
			
			$syspath = GetParam('syspath');
			$pagetype = GetParam('pagetype');
			$dconn = GetParam('dconnect');
			
			$data = "<?php
";
            $cntrltype = $pagetype?$pagetype:'pcntl';
			$systempath = $syspath?'cgi-bin/system':'cp/dpc/system';
            $data .=			
"require_once('$systempath/$cntrltype.lib.php'); 
";
            $classname = ($cntrltype=='pcntlajax'?'pcntlajax':'pcntl');
            $data .=
"\$page = &new $classname('			
super javascript;
super rcserver.rcssystem;
";
            if ($dconn)
            $data .= 
"load_extension adodb refby _ADODB_; 
super database;
";
            $data .= 
"#---------------------------------load and create libs
use xwindow.window;

#---------------------------------load not create dpc (internal use)
include networlds.clientdpc;
	
#---------------------------------load not create extensions (internal use)	

#---------------------------------load all and create after dpc objects
frontpage.fronthtmlpage;
"; 
		    $privarea = $syspath?'/cgi-bin':null;
			$private = $syspath?'private':null;
            $data .= 
"$private rc.rcbasichtml $privarea;	
',0);
echo \$page->render(null,getlocal(),null,'$html_page');
?>	
";	
	        
            if ($fp = @fopen ($file , "w")) {
	             //echo $file,"<br>";
                 fwrite ($fp, $data);
                 fclose ($fp);
            }
            else {
              $this->msg .= "File creation error ($file)!\n";
		      //echo "File creation error ($filename)!<br>";
            }

            if (defined('RCSCRIPTS_DPC'))
              $ret = GetGlobal('controller')->calldpc_method('rcscripts.use_script use rc.rcbasichtml');	
			  
            //echo $ret,'>';  
            return $ret;			  
					
	}
	
	function new_html_file($name, $copyfrom=null) {
	        $myname = $name?strtolower($name).'.html':'new.html';
		    $file = $this->publicpath .'cp/html/'. (str_replace('.html',getlocal().'.html',$myname));
			$html_id = '<' . strtoupper(str_replace('.html',getlocal(),$myname)) . '>';
			
			if ($copyfrom) {
			 
			    $sfile = $this->publicpath.'/cp/html/'.strtolower(str_replace('.php','.html',$copyfrom));
				//echo $sfile,'#<br>';
				if (is_readable($sfile)) {
				  $tfile = $this->publicpath.'/cp/html/'.strtolower($name). getlocal() . '.html';
				  //echo $tfile,'#<br>';
			      $ret = @copy($sfile, $tfile);
				}
				return ($ret);
			}			
			
			$data = "
<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<title>Title</title>
<LINK REL=StyleSheet HREF=\"styles.css\">
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . $this->encoding . "\">
</head>
<body>
$html_id
</body>
</html>	
";	
	        
            if ($fp = @fopen ($file , "w")) {
	             //echo $file,"<br>";
                 fwrite ($fp, $data);
                 fclose ($fp);
            }
            else {
              $this->msg .= "File creation error ($file)!\n";
		      //echo "File creation error ($filename)!<br>";
            }	
					
	}	
	
	//modified for editmode cmpctrl file
	function show_template_files($combo=null,$taction=null,$template_path=null,$editmode=null,$inframe=null) {
	
	   if ($taction)
	     $myact = $taction;
	   else
	     $myact = 'cptedit';
	   //echo $myact,'>';
       if (defined('RCFS_DPC')) {
	    //if template path defined the ask for path (not inside cp)
	    $path = $template_path?$this->urlpath.$this->infolder.'/' . $template_path : $this->htmlpath;// . 'public/themes/basic.theme'; 
	    $extensions = array(0=>".htm");//,1=>".html");//$this->ext;htm is sub of html->double list
	    //echo '<br><br>',$path;
		if (is_dir($path)) {
		
	      $this->fs= new rcfs($path);
		  $ddir = $this->fs->read_directory($path,$extensions); 
		  //$ddir = GetGlobal('controller')->calldpc_method('rcfs.read_directory use '.$path.'+'.$extensions);

		  if (!$combo) {
            $ret = $this->fs->show_directory($ddir,"t=$myact&f=/","Files");
		    //$ret = $ddir = GetGlobal('controller')->calldpc_method('rcfs.show_directory use '.$ddir'+t=cptedit&f=/+Files');		
	      }
          else {
		  	if (!$editmode) {
		      $myaction = seturl("t=$myact");	 
	          $ret = "<form method=\"post\" name=\"RCTEDIT\" action=\"$myaction\">";	 
			}
			
		    $ret .= "<select name=\"id\"";
			if ($editmode) {
			  if ($inframe)
			    $ret .= " onChange=\"top.$inframe.location='$editmode'+this.options[this.selectedIndex].value+'&phpfile='+this.options[this.selectedIndex].text+'.php'\">";
			  else
                $ret .= " onChange=\"location='$editmode'+this.options[this.selectedIndex].value+'&phpfile='+this.options[this.selectedIndex].text+'.php'\">";			  
			  //$selected = urldecode(base64_decode($_GET['htmlfile'])); 
			}  
			else
			  $ret .= ">"; 
	 
	        sort($ddir);
			//print_r($ddir);
			if (editmode) {
			  $clan = getlocal();
			  foreach ($ddir as $f=>$efile) {
			    if (stristr($efile,$clan.'.htm')) 
				  $editfiles[$f] = $efile;
			  }
			}
			else
			  $editfiles = (array) $ddir;
			  
			
            foreach ($editfiles as $id=>$name) {
		      $parts = explode(".",$name);
		      $title = $parts[0];
			  if ($editmode) {
			    $value = urlencode(base64_encode($name)); 
			    $ret .= "<option value=\"$value\"".($value == $_GET['htmlfile'] ? " selected" : "").">$title</option>";		
			  }	
			  else
                $ret .= "<option value=\"$name\"".($value == GetReq('id') ? " selected" : "").">$title</option>";		
		    }	
		
		    $ret .= "</select>";	
			if (!$editmode) {
              $ret .= "<input type=submit value=\"$combo\">";
	          $ret .= "<input type=\"hidden\" name=\"FormAction\" value=\"$myact\">";   
              $ret .= "</form>";  			    
			}  
	      }
	    }  
	    else
	      $ret = 'Invalid directory!';		   
	   }	  
	    
	   return ($ret);		
	}	
	
	function show_image_files($combo=null,$taction=null,$image_path=null) {
	
	   if ($taction)
	     $myact = $taction;
	   else
	     $myact = 'cptedit';
	   //echo $myact,'>';
       if (defined('RCFS_DPC')) {
	    //if template path defined the ask for path (not inside cp)
	    $path = $image_path?$this->urlpath.$this->infolder.'/' . $image_path : $this->htmlpath;// . 'public/themes/basic.theme'; 
	    $extensions = array(0=>".png",1=>".gif",2=>".jpg");//,1=>".html");//$this->ext;htm is sub of html->double list
	    //echo '<br><br>',$path;
		if (is_dir($path)) {
		
	      $this->fs= new rcfs($path);
		  $ddir = $this->fs->read_directory($path,$extensions); 
		  //$ddir = GetGlobal('controller')->calldpc_method('rcfs.read_directory use '.$path.'+'.$extensions);
		  
		  if (!empty($ddir)) {
		  
		  sort($ddir);

		  if (!$combo) {
            $ret = $this->fs->show_directory($ddir,"t=$myact&f=/","Files");
		    //$ret = $ddir = GetGlobal('controller')->calldpc_method('rcfs.show_directory use '.$ddir'+t=cptedit&f=/+Files');		
	      }
          else {
		    $myaction = seturl("t=$myact");	 
	        $ret = "<form method=\"post\" name=\"RCTEDIT\" action=\"$myaction\">";	 
		    $ret .= "<select name=\"id\">"; 
	 
            foreach ($ddir as $id=>$name) {
		      $parts = explode(".",$name);
		      $title = $parts[0];
              $ret .= "<option value=\"$name\"".($value == GetReq('id') ? " selected" : "").">$title</option>";		
		    }	
		
		    $ret .= "</select>";	
            $ret .= "<input type=submit value=\"$combo\">";
	   
	        $ret .= "<input type=\"hidden\" name=\"FormAction\" value=\"$myact\">";   
            $ret .= "</form>";  			    
	      }
		  }//empty dir
	    }  
	    else
	      $ret = 'Invalid directory!';		   
	   }	  
	    
	   return ($ret);		
	}		
	
	function show_files($id='id',$combo=null,$taction=null,$file_path=null,$ext=null) {
	
	   if ($taction)
	     $myact = $taction;
	   else
	     $myact = 'cptedit';
	   //echo $myact,'>';
       if (defined('RCFS_DPC')) {
	    //if template path defined the ask for path (not inside cp)
	    $path = $file_path?$this->urlpath.$this->infolder.'/' . $file_path : $this->htmlpath;
		$myext = explode(',',$ext);
	    $extensions = is_array($myext) ? $myext : array(0=>".png",1=>".gif",2=>".jpg");
	    //echo '<br><br>',$path;
		if (is_dir($path)) {
		
	      $this->fs= new rcfs($path);
		  $ddir = $this->fs->read_directory($path,$extensions); 
		  //$ddir = GetGlobal('controller')->calldpc_method('rcfs.read_directory use '.$path.'+'.$extensions);
		  
		  if (!empty($ddir)) {
		  
		  sort($ddir);

		  if (!$combo) {
            $ret = $this->fs->show_directory($ddir,"t=$myact&f=/","Files");
		    //$ret = $ddir = GetGlobal('controller')->calldpc_method('rcfs.show_directory use '.$ddir'+t=cptedit&f=/+Files');		
	      }
          else {
		    $myaction = seturl("t=$myact");	 
	        $ret = "<form method=\"post\" name=\"RCTEDIT\" action=\"$myaction\">";	 
		    $ret .= "<select name=\"$id\">"; 
	 
            foreach ($ddir as $id=>$name) {
		      $parts = explode(".",$name);
		      $title = $parts[0];
              $ret .= "<option value=\"$name\"".($value == GetReq('id') ? " selected" : "").">$title</option>";		
		    }	
		
		    $ret .= "</select>";	
            $ret .= "<input type=submit value=\"$combo\">";
	   
	        $ret .= "<input type=\"hidden\" name=\"FormAction\" value=\"$myact\">";   
            $ret .= "</form>";  			    
	      }
		  }//empty dir
	    }  
	    else
	      $ret = 'Invalid directory!';		   
	   }	  
	    
	   return ($ret);		
	}			
	
	function show_php_files($id='id',$combo=null,$taction=null, $isurl=false) {
	
	   if ($taction)
	     $myact = $taction;

	   
       if (defined('RCFS_DPC')) {

	    $path = $this->urlpath.$this->infolder.'/';
	    $extensions = array(0=>".php",1=>".phtml",2=>".php3");
	    //echo '<br><br>',$path;
		if (is_dir($path)) {

	      $this->fs= new rcfs($path);
		  $ddir = $this->fs->read_directory($path,$extensions); 
		  //$ddir = GetGlobal('controller')->calldpc_method('rcfs.read_directory use '.$path.'+'.$extensions);
		  
		  if (!empty($ddir)) {
		  
		  sort($ddir);

		  if (!$combo) {
            $ret = $this->fs->show_directory($ddir,"t=$myact&f=/","Files");
		    //$ret = $ddir = GetGlobal('controller')->calldpc_method('rcfs.show_directory use '.$ddir'+t=cptedit&f=/+Files');		
	      }
          else {
		    if ($myact) {
			  if (!$isurl) 
		        $myaction = seturl("t=$myact");	 
			  else
                $myaction = $mycat;			  
				
	          $ret = "<form method=\"post\" name=\"RCTEDIT\" action=\"$myaction\">";	 
			}
		    $ret .= "<select name=\"$id\">"; 
	 
            foreach ($ddir as $id=>$name) {
		      $parts = explode(".",$name);
		      $title = $parts[0];
              $ret .= "<option value=\"$name\"".($value == GetReq('id') ? " selected" : "").">$title</option>";		
		    }	
		
		    $ret .= "</select>";	
			
			if ($myact) {
              $ret .= "<input type=submit value=\"$combo\">";
			  if (!$isurl)//no phpdac call cmd
	            $ret .= "<input type=\"hidden\" name=\"FormAction\" value=\"$myact\">";   
              $ret .= "</form>";  			    
			}  
	      }
		  }//empty dir
	    }  
	    else
	      $ret = 'Invalid directory!';		   
	   }	  
	    
	   return ($ret);		
	}		
	
	function tasks($action=null) {	
	
	   $new = seturl("t=cptnew","New!");	
	
	   if ((defined('RCPREVIEW_DPC')) && ($action=='cptsave')) {
	     $preview = GetGlobal('controller')->calldpc_method('rcpreview.preview_button use '.$this->appname);   	   	   	 	 		 
		 $links = $preview . '|' . $new;
	   }
	   else
		 $links = $new;
	   
	   $myadd = new window('',$links);
	   $ret .= $myadd->render("center::100%::0::group_article_selected::right::0::0::");	   
	   unset ($myadd);  		   
 		 
	   return ($ret);		 
	}

  function new_dialog($iscopy=false) {

     $sFormErr = GetGlobal('sFormErr');
     $myaction = $iscopy ? seturl("t=cptsavecopy&editmode=" . $this->editmode) 
                         : seturl("t=cptsave&editmode=" . $this->editmode); 
	 
	 //$dpceditor = "cpmdpceditor.php?turl=";// . urlencode(base64_encode($turl));
	 //$jsgoto = "top.bottomFrame.location='$dpceditor'"; //dpc editor
	 	 
	 if (($this->post==true) && ($name=GetParam('title')) && (!$this->msg)) {

	   
	   /*if (isset($this->msg)) {//show msg...
	     $msg = $this->msg;
	   
	     $swin = new window("Post",$msg);
	     $out .= $swin->render("center::50%::0::group_win_body::center::0::0::");	
	     unset ($swin);
	   }
	   else {*///redirect edit html
	     //html editor
         $htmlpage = strtolower(str_replace('.html',getlocal().'.html',$name.'.html')); 							
	     $p1 = urlencode(base64_encode($htmlpage));
		 //echo '<br>',$htmlpage;
		 //dpc editor ..redirect cmpdpceditor->cpmhtmleditor 
		 $phppage = strtolower($name.'.php');
		 $p2 = urlencode(base64_encode($phppage));
		 //echo '<br>',$phppage;
		 //REDIRECT FOR EDIT....
	     header("location:cpmhtmleditor.php?encoding=".$this->encoding."&htmlfile=".$p1."&phpfile=".$p2);	   		 
	     die();
		 //INTERNAL EDITING...
         //$out .= $this->editform($htmlpage,$this->htmlpath,"Edit..."/*,$this->appname."/images/"*/);		 
	   //}
	 }
	 else { //show the form plus error if any
	   if (!GetParam('title'))	 
	     $this->msg = "Name required";
		 
       $out .= setError($sFormErr . $this->msg);	   
	   
	   if ($iscopy) {
	        $out .= "<FORM action='". $myaction . "' method=post >";
	        $out .= "Name:<input type=\"text\" name=\"title\" value=\"\" size=\"32\" maxlength=\"64\">";
			
			$out .= "Copy from file:"; 
			$out .= $this->show_php_files('copyfile','Copyfile');//,'cptsavecopy',null,0);
			
			$out .= "<input type=\"hidden\" name=\"FormAction\" value=\"cptsavecopy\">"; 
	        $out .= "<input type=\"submit\" name=\"Submit\" value=\"Submit\">" .
                    "</FORM>";				
	   }
	   else {
	   $file = getReq('id');
	   
	   $form = new form(localize('_RCSCRIPTS',getlocal()), "RCSCRIPTS", FORM_METHOD_POST, $myaction, true);
	
	   $form->addGroup			("title",			"Name");       	   
	   $form->addGroup		    ("options",			"Options");	  	   
	   $form->addGroup			("body",			"Body");	

       $form->addElement		("title", new form_element_text("Title",  "title",$file,"forminput",			25,				25,	0));	 
       $form->addElement		("options",	new form_element_radio("Main system",   "syspath",      GetParam('syspath'),             "",   2, array ("0" => "Public", "1" => "Private")));	   
       $form->addElement		("options",new form_element_radio("Page type",   "pagetype",      GetParam('pagetype'),             "",   2, array ("pcntl" => "pcntl", "pcntlhtml" => "cntlhtml","pcntlajax" => "pcntlajax","pcntlcmd" => "cntlcmd")));	   
       $form->addElement		("options",	new form_element_radio("Database connection",   "dconnect",      GetParam('dconnect'),             "",   2, array ("0" => "No", "1" => "Yes")));		 

	   $form->addElement		("body",new form_element_textarea($file,  "nobody","","formtextarea",	80,	20));
	   
	   // Adding a hidden field
	   $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "cptsave"));
 
	   // Showing the form
	   $fout = $form->getform ();//0,0,null,null,null,$jsgoto);not a form element submited in this state	
	   
	   //$fwin = new window(localize('AMAIL_DPC',getlocal()),$fout);
	   //$out .= $fwin->render();	
	   //unset ($fwin);	
	   
	   $out .= $fout;
	   
	   
	   $out .= "<div id='somediv' style='display:none'>
<p style='height: 400px'>This is some content within a DIV, shown inside this window instead</p>
</div>";	
       //$out .=  $this->dhtml_javascript("divwin=dhtmlwindow.open('divbox', 'div', 'somediv', '#4: DIV Window Title', 'width=450px,height=300px,left=200px,top=150px,resize=1,scrolling=1'); return false", true);  
	   
       }//iscopy
	 }
 
     return ($out);
  } 
  
  function open_dialog() {
  
           $turl = GetReq('turl');//urldecode(decode(GetReq('turl')));
           //echo $turl;		   
		   $code_url = "cpmctrl.php?t=cptnew&editmode=1&turl=" . urlencode(base64_encode($turl));
		/*   $this->dhtml_javascript("
var dialog=dhtmlwindow.open(\"addpage\", \"iframe\", \"$code_url\", \"Add Page\", \"width=640px,height=500px,resize=1,scrolling=1,center=0\", \"recal\")
//var dialog=dhtmlwindow.open(\"addpage\", \"div\", \"dialog\", \"Add Page\", \"width=640px,height=500px,resize=1,scrolling=1,center=0\", \"recal\")
dialog.moveTo('middle', 'middle'); return false;
dialog.onclose=function(){ 
return window.confirm(\"Close window?\")
}		
");	 */   

			$this->dhtml_javascript("divwin=dhtmlwindow.open('divbox', 'div', 'somediv', '#4: DIV Window Title', 'width=450px,height=300px,left=200px,top=150px,resize=1,scrolling=1', 'recal');");
			//$this->dhtml_javascript("divwin=dhtmlwindow.open('divbox2', 'inline', 'aaaa', '#5: DIV Window Title', 'width=450px,height=300px,left=200px,top=150px,resize=1,scrolling=1', 'recal');");
  }	
  
  function open_tools() {
          return true;//DISABLED
  
 		  /* $this->dhtml_javascript("function openlink1(){ 
codewin=dhtmlwindow.open(\"codewin\", \"ajax\", \"$ajaxlink1\", \"Code editor\", \"width=600px,height=200px,left=300px,top=100px,resize=1,scrolling=1\")
ajaxwin.onclose=function(){return window.confirm(\"Close window ?\")} 
}");*/		
           //$commands = "<a href=\"#\" onClick=\"openlink1(); return false\">".$title1."</a>";
		  
           $turl = GetReq('turl');//urldecode(decode(GetReq('turl')));
           //echo $turl;		   
		   $code_url = "cpmdpceditor.php?turl=" . urlencode(base64_encode($turl));
		   $this->dhtml_javascript("
var mycodewin=dhtmlwindow.open(\"codewin\", \"iframe\", \"$code_url\", \"Console\", \"width=600px,height=200px,resize=1,scrolling=1,center=0\", \"recal\")
mycodewin.moveTo('middle', 'middle'); return false;
mycodewin.onclose=function(){ 
return window.confirm(\"Close window?\")
}		
");	  
           //$argument = GetGlobal('controller')->calldpc_var('fronthtmlpage.argument');
		   $htmlext = GetGlobal('controller')->calldpc_var('fronthtmlpage.htmlext');
		   //echo '>',$argument,':',$htmlext;
		   $argument = str_replace('.php', '', $turl);
		   $htmlfile = urlencode(base64_encode(strtolower($argument).$htmlext));

		   $cmd_url = "cpmctrl.php?turl=" . urlencode(base64_encode($turl)) ."&encoding=". $this->encoding . '&htmlfile=' . $htmlfile;
		   $this->dhtml_javascript("
var mycmdwin=dhtmlwindow.open(\"cmdwin\", \"iframe\", \"$cmd_url\", \"Cmd\", \"width=600px,height=30px,resize=1,scrolling=1,center=0\", \"recal\")
//mycmdwin.moveTo('middle', 'middle'); return false;
mycmdwin.onclose=function(){ 
return window.confirm(\"Close window?\")
}		
");	

          return true;
    } 	 
  
  
	function render_textarea($mailbody=null,$rows=10) {
	
	  if (GetReq('editmode')) {	
	    $out = '<div>'; 
        $out .= "<textarea id='mail_text' name='mail_text'>".$this->load_spath($mailbody)."</textarea>";	
	    $out .= "<script type='text/javascript'>
	           CKEDITOR.replace('mail_text',
			   {
	           skin : 'office2003', 
			   fullpage : true, 
			   extraPlugins :'docprops',
               filebrowserBrowseUrl : '/cp/ckfinder/ckfinder.html',
	           filebrowserImageBrowseUrl : '/cp/ckfinder/ckfinder.html?type=Images',
	           filebrowserFlashBrowseUrl : '/cp/ckfinder/ckfinder.html?type=Flash',
	           filebrowserUploadUrl : '/cp/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	           filebrowserImageUploadUrl : '/cp/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
	           filebrowserFlashUploadUrl : '/cp/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
	           filebrowserWindowWidth : '1000',
 	           filebrowserWindowHeight : '700'			   
			   }		   
			   );
			   CKEDITOR.config.fullPage=true;
               CKEDITOR.config.entities = false;
               CKEDITOR.config.entities_greek = false;
               CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;			   		
		       </script>"; 
	    $out .= '</div>';	
	  }
	  else {
	       //tinymce
		   if ($this->ishtml == true) {
		     $this->htmleditor = new tinyMCE('textareas','ADVANCEDFULL',1,'images',$this->dirdepth);
		     $out = $this->htmleditor->render('mail_text','100%',$rows+10,$mailbody);		   
		   }			 
		   else {		
             $out = "<DIV class=\"monospace\"><TEXTAREA style=\"width:100%\" NAME=\"mail_text\" ROWS=$rows cols=60 wrap=\"virtual\">";	 
             $out .= $mailbody; 
             $out .= "</TEXTAREA></DIV>";	
		   }	   
	  }
	  
	  return ($out);
	}

    function load_spath($text=null) {
	
	   if ($this->ckeditor)	
	     return ($text);	

       $p1 = str_replace("images/","../images/",$text);

       $ret = $this->handle_phpdac_tags($p1);
	   //echo $ret;
       return ($ret);

    }

    function unload_spath($text=null) {
	
	   if ($this->ckeditor)	
	     $p1 = str_replace("/images/",$this->url.$this->infolder."/images/",$text);
       else
         $p1 = str_replace("../images/",$this->url.$this->infolder."/images/",$text);

       $ret = $this->handle_phpdac_tags($p1,1);
       return ($ret);

    }	
	
    function handle_phpdac_tags($text,$savemode=null) {

      if ($savemode==null) {//load
       $p1 = str_replace("<?","<phpdac5>",$text);
       $p2 = str_replace('?>','</phpdac5>',$p1);
      }
      else {//save mode
       $p1 = str_replace("<phpdac5>","<?",$text);
       $p2 = str_replace('</phpdac5>','?>',$p1);
      }

      return $p2;
    }	  

};
}
?>
