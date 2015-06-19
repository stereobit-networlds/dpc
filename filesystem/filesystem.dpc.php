<?php

$__DPCSEC['FILESYSTEM_DPC']='2;2;2;2;2;2;2;2;9';
$__DPCSEC['ADMINFL_']='2;1;1;1;1;1;1;2;9';

if ((!defined("FILESYSTEM_DPC")) && (seclevel('FILESYSTEM_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("FILESYSTEM_DPC",true);

$__DPC['FILESYSTEM_DPC'] = 'filesystem';

$__EVENTS['FILESYSTEM_DPC'][0]='deletefiles';
$__EVENTS['FILESYSTEM_DPC'][1]='movefiles';
$__EVENTS['FILESYSTEM_DPC'][2]='uploadfiles';
//$__EVENTS['FILESYSTEM_DPC'][3]=1;
//$__EVENTS['FILESYSTEM_DPC'][4]=2;

//$__ACTIONS['FILESYSTEM_DPC'][0]=1;
//$__ACTIONS['FILESYSTEM_DPC'][1]=2;

$__DPCATTR['FILESYSTEM_DPC']['deletefiles'] = 'deletefiles,0,0,0,0,0,1,0'; 
$__DPCATTR['FILESYSTEM_DPC']['movefiles'] = 'movefiles,0,0,0,0,0,1,0'; 
$__DPCATTR['FILESYSTEM_DPC']['uploadfiles'] = 'uploadfiles,0,0,0,0,0,1,0'; 

$__BROWSECOM['FILESYSTEM_DPC'] = 'commandbar';
$__BROWSEACT['FILESYSTEM_DPC'] = 'uploadform';

class filesystem {

    var $prpath;
	var $urlpath;
	var $dfiles;
	var $fmeter;
	var $path;
	var $homedir;
	var $diralias;
	var $dfres;
	var $pathres;
	var $home;
	var $aliasfile;
	var $userLevelID;	
	
	var $audio_button;
	var $video_button;
	var $image_button;
	var $mp3_button;
	var $addmc_button;
	var $remmc_button;
	
    var $VIDEO_FILETYPE;
    var $AUDIO_FILETYPE;
    var $IMAGE_FILETYPE;
	var $USE_WINAMP;		
	
	var $view;
	var $pagenum;

	function filesystem($dirinfo='') {
	    $UserSecID = GetGlobal('UserSecID');
	    $GRX = GetGlobal('GRX'); 

        $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);	
		
	    $this->prpath = paramload('SHELL','prpath');	
		$this->urlpath = paramload('SHELL','urlbase');	
			
 	    $viewperclient = arrayload('FILESYSTEM','viewperclient');				
	    $this->view  = $viewperclient[$this->userLevelID];
	    if (!$this->view) $this->view = paramload('FILESYSTEM','dview');	
	   
	    //save view if not set
        if (!GetSessionParam("PViewStyle")) SetSessionParam("PViewStyle", $this->view);				
		
	    $this->pagenum = 20;		
        $this->TEMPLATE_FILETYPE = ".html"; //default
		$this->fmeter = 0;
		$this->dfiles = array();
		$this->homedir = paramload('FILESYSTEM','dirname');//paramload('DIRECTORY','dirname');
		$this->dfres = paramload('FILESYSTEM','dfres'); 
        $this->home  = localize(paramload('FILESYSTEM','diralias'),getlocal());//localize(paramload('DIRECTORY','diralias'),getlocal());
        $this->aliasfile = @file (paramload('SHELL','prpath') . "directories.csv");		

		if ($dirinfo) {
			$this->diralias = $dirinfo[2];
			$this->path = $dirinfo[0] . $dirinfo[1];
		}
		else { 
			$this->diralias = $this->home;
			$this->path = paramload('FILESYSTEM','dirname');//paramload('DIRECTORY','dirname');
		}

        if ($this->dfres) $this->pathres = $this->dfres;
		             else $this->pathres = $this->path;
					 
        $this->VIDEO_FILETYPE = paramload('FILESYSTEM','video');
        $this->AUDIO_FILETYPE = paramload('FILESYSTEM','audio');
        $this->IMAGE_FILETYPE = paramload('FILESYSTEM','image');

        $this->USE_WINAMP = paramload('FILESYSTEM','winamp');

        if ($GRX) {   
             $this->audio_button  = loadTheme('audio_b');
             $this->video_button  = loadTheme('video_b');  
             $this->image_button  = loadTheme('image_b');            
             $this->mp3_button    = loadTheme('mp3_b'); 
        }
		else {
             $this->video_button  = '[V]';
             $this->audio_button  = '[A]'; 
             $this->image_button  = '[P]';
             $this->mp3_button    = '[MP3]';
		}					 
	}

	function action($act) {

  	 if ($this->homedir) {	
	    //re-save view
	    SetSessionParam("PViewStyle", $act);	
	
	    switch ($act) {	
		  case 1      : $this->pagenum = 20; break;
		  case 2      : $this->pagenum = 10; break;
	    }		   
	
		$this->read();
		
		return ($this->render());
	  } 	
	}
	
    function event($sAction) {

       switch($sAction) {
         case "movefiles" : //move file  
				              break;
         case "deletefiles" : //delete file  
				              $this->delete_file();	  
	                          break;
				   
         case "uploadfiles": //upload file in selected directory
				             $this->upload_file();
				             break; 
       }
	}
	
    function render() { 
	    $g = GetReq('g');		
	
        if ($this->dfiles) {

             //while (list ($file_num, $filename) = each ($this->dfiles)) {
             foreach ($this->dfiles as $file_num => $filename) {			 	
				$out .= "$filename<br>";
             } 
			
			 /*$mydbrowser = new browse($this->dfiles,$g,1,'1,2');	   
	         $out = $mydbrowser->render(GetSessionParam("PViewStyle"),$this->pagenum,$this,1,1,1,1); 
  	         unset ($mydbrowser);	   */
		 
	    }
		else 
			$out = "&nbsp";

        return ($out);
    }	
	
	function uploadform() {
	    $a = GetReq('a');
	    $g = GetReq('g');
	    $t = GetReq('t');
	    $p = GetReq('p');	   	   	   		

        if (seclevel('ADMINFL_',$this->userLevelID)) {		
 	      $gr=urlencode($g);
	      $ar=urlencode($a);	   

          $filename = seturl("t=$t&a=$ar&g=$gr&p=$p");		
		
	      //upload file(s) form
          $out  = "<FORM action=". "$filename" . " method=post ENCTYPE=\"multipart/form-data\" class=\"thin\">";
          $out .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" VALUE=\"9024\">"; //max file size option in bytes
          $out .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>"; 			 	   
          $out .= "Upload: <input type=FILE name=\"uploadfile\">";		    
          $out .= "<input type=\"hidden\" name=\"FormName\" value=\"UploadFiles\">"; 	
          $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"uploadfiles\">&nbsp;";		
          $out .= "<input type=\"submit\" name=\"Submit2\" value=\"Upload\">";		
          $out .= "</FONT></FORM>"; 	
		
		  $wina = new window('',$out);
		  $winout = $wina->render();//"center::100%::0::group_dir_title::right::0::0::");
		  unset ($wina);		  
		}			  
		
		return ($winout);
	}	
	
    function commandbar() {
	
        if (seclevel('ADMINFL_',$this->userLevelID)) {	
          $out  = "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>"; 			 	   
          $out .= "Delete<input type=\"radio\" name=\"FormAction\" value=\"deletefiles\">";		    
	      $out .= "Move<input type=\"radio\" name=\"FormAction\" value=\"movefiles\" checked>";
          $out .= " to: "; 
          $out .= "<input type=\"hidden\" name=\"FormName\" value=\"AdminFiles\">"; 	
          $out .= "<input type=\"submit\" name=\"FormAction\" value=\"Update\">";		
          $out .= "</FONT>";  
		  	
		  $wina = new window('',$out);
		  $winout = $wina->render();//"center::100%::0::group_dir_title::right::0::0::");
		  unset ($wina);		  
		}			  
		
		return ($winout);
	}	

    function preparestr($str) {

		$preout = str_replace("\"","'",$str);
		//$out = str_replace("+","-",$preout);

		return ($preout);
	}

    function read() { 

        $mydir = dir($this->path);
        while ($fileread = $mydir->read ()) { 
           $TEMPLATE_FILETYPE = $this->template_filetype($fileread);
	
           if (stristr ($fileread,$TEMPLATE_FILETYPE)) {

              $title = _without(str_replace ($TEMPLATE_FILETYPE, "", $fileread));
              //array format : id,title,path,template,group,page 
              $this->dfiles[$this->preparestr($title)] = $fileread . ";" . $this->preparestr($title) . ";" . $this->pathres . ";" . $TEMPLATE_FILETYPE . ";" . $this->diralias . ";" . "0" . ";";
              $this->fmeter+=1; 
           }
        }
        $mydir->close ();
			   
        reset ($this->dfiles); //reset

        //print_r($this->dfiles);
        return ($this->dfiles);
    }

    ////////////////////////////////////////////////////////////
    // check if a file is an approval template file
    ////////////////////////////////////////////////////////////
    function template_filetype ($tfile) {

         $ft = array();
         $ft = arrayload('FILESYSTEM','templates');

         reset($ft);
         //while (list($template_no, $template_ext) = each($ft)) {
         foreach ($ft as $template_no => $template_ext) {		 
           //print "$template_ext";
           if (stristr ($tfile,$template_ext)) return $template_ext;
         }
         return false;
    }
	
    //return clear (without tags) content of article
    function getcontent($id,$title,$path,$template) {

        $fullname = $path . "/" . $id;

        switch ($template) {
          case ".html" : 
          case ".htm"  : $out = html2txt($fullname);
                         break;
          case ".txt"  : $out = txt2html($fullname);
                         break;  
          case ".xgi"  : $pr = new parser($fullname,1);
		                 $out = $pr->translate(); 
						 unset($pr);
                         break;
        }

		return ($out);
	}	

	function search($text,$group='') {

        $usedatabase = paramload('DIRECTORY','dirdb');

        if ($usedatabase) {
			//DATABASE USE
		}
		else {
          if ($this->aliasfile) {
			  $mysearch = new search;
              //while (list ($dline_num, $dline) = each ($this->aliasfile)) {
              foreach ($this->aliasfile as $dline_num => $dline) {			  

                 $dsplit = explode (";", $dline);
				 
				 //search specific
	     	     if (($group) && ($group!=localize('_ALL',getlocal())) && ($dsplit[2]!=$group)) {
				 				 
				 $mypath = $this->homedir . $dsplit[0] . $dsplit[1];

                 $mydir = dir($mypath); 
                 while ($fileread = $mydir->read ()) { 
                   $TEMPLATE_FILETYPE = $this->template_filetype($fileread);
	
                   if (stristr ($fileread,$TEMPLATE_FILETYPE)) {
                      //get title
                      $title = _without(str_replace ($TEMPLATE_FILETYPE, "", $fileread));
					  //get content
					  $content = $this->getcontent($fileread,$title,$mypath,$TEMPLATE_FILETYPE);

                      //search
                      if (($mysearch->find($title,$text)) || 
						  ($mysearch->find($content,$text))) { 

				        //find the parent dir = this group
						$mydirs = new _directory;
                        $info = $mydirs->getaliasinfo($dsplit[1],1);
						unset($mydirs);
						
				        $mygroup = $info[2];
                        //array format : id,title,path,template,extrafield 
                        $result[] = $fileread . ";" . $title . ";" . $mypath . ";" . $TEMPLATE_FILETYPE . ";" . $mygroup . ";"; 
					  }
                   }
                 }
				 }//search specific
                 $mydir->close ();
				 unset ($mydir);
              }
			  unset ($dsplit);
			  unset ($mysearch);
          }
		}
        //print_r($result);
		return ($result);
	}

	function upload_file() {
	    /*global $uploadfile,  //<<<<<<<<<<<<<<<<<<<<<<<<<<<< GLOBALS !!!!
		       $uploadfile_size,
			   $uploadfile_name,
			   $uploadfile_type; //get uploaded file form data & file's attrs*/ 
        $uploadfile = GetGlobal('uploadfile');
		$uploadfile_size = GetGlobal('uploadfile_size');
		$uploadfile_name = GetGlobal('uploadfile_name');
		$uploadfile_type = GetGlobal('uploadfile_type');			   
			   
	
	    $tdir = new _directory;
        $mydir = $tdir->getaliasinfo($this->alias,2);
		unset($tdir);
		$currentdir = $mydir[0] . $mydir[1]; 				   
	    $abspath = paramload('SHELL','urlpath');
				   
	    $location = $abspath . "/" . $currentdir . "/"; 
		$myfile = $uploadfile_name;
		$myfilepath = $location . $myfile;

		//copy it to admin write-enabled directory				   
		if (copy($uploadfile,$myfilepath)) {
    	   setInfo("File $uploadfile_name successfully uploaded !<br> Size : $uploadfile_size <br> Type : $uploadfile_type <br> Temporary file : $uploadfile"); 
		}
		else {
		   setInfo("Failed to upload file $uploadfile_name !"); 				   
		}
		unlink($uploadfile); 
	}

    function delete_file() { 

        $i = 0;
        $mydir = $this->getaliasinfo($this->alias,2); 
		$currentdir = $mydir[0] . $mydir[1]; 
	    $abspath = paramload('SHELL','urlpath');
	    $sourcedir = $abspath . "/" . $currentdir . "/";				   			   				   
				   
        $rd = dir($currentdir);				   
        while ($fileread = $rd->read ()) {
           $TEMPLATE_FILETYPE = $this->template_filetype($fileread);
           if ($TEMPLATE_FILETYPE) {
		      $myname = $fileread; //str_replace ($TEMPLATE_FILETYPE, "", $fileread);	
					 		   
		      $ischecked = GetParam($myname); //print $ischecked;??????
			  //if getparam return checkbox with name = filename on 	
			  //then do action with file
					 					 
			  if ($ischecked) { 						 
				   $mysourcefile = $sourcedir . $fileread;					 				   
				   if (unlink($mysourcefile)) $i += 1;
			  }
		    } 
		 }	
   	     $rd->close ();
		 setInfo("Deleted successfully $i files !"); 	
	}

	
	
	
	
	
	
	
	
	
	
	
    /////////////////////////////////////////////////////////////
    // create m3u winamp file in cache for winamp reading
    /////////////////////////////////////////////////////////////
    function GetMP3file($id,$title,$group,$ppath) { 

       $sitebaseurl = $this->urlpath;
	   
       $MP3url = $sitebaseurl . $ppath . "/" . $title . $this->AUDIO_FILETYPE;
       if (paramload('SHELL','debug')) echo $ppath ,"...",$MP3url;

       //create cache filename
	   $m3ufile = "cache/" . $title . $group . ".m3u";
       $MP3m3u = paramload('SHELL','prpath') . $m3ufile; 

       //if is in cash get it..
       $fp = fopen ($MP3m3u, "r");
       if ($fp) {
         //if (paramload('SHELL','debug')) echo "CACHED";
         fclose($fp);   
         return ($sitebaseurl.$m3ufile);
       }

       //else create it
       $fp = fopen ($MP3m3u, "w");
       if ($fp) { 
    
         fwrite($fp, $MP3url);
         fclose($fp);   
       } 
       else
         if (paramload('SHELL','debug')) echo "ERROR:Unable to open file."; 
 
       return ($sitebaseurl.$m3ufile);
    }	
	
	
	function showsymbol($packdata,$group,$page) {
	
	     $params = explode(";",$packdata);
		 
		 $id = $params[0];
		 $title = $params[1];
		 $path = $params[2];
	
         $activedir = $this->prpath . $path . "/";	
	
         $image = $title.$this->IMAGE_FILETYPE;  
         if (myfile_exists($this->IMAGE_FILETYPE,$activedir,$image)) {   
           $showimage = $this->urlpath . $path . "/" . $image;
	 
           if (iniload('JAVASCRIPT')) {	
  	         $toprint .= "<A href=\"#\" ";	   
	         //call javascript for opening a new browser win for the img		   
	         $params = $showimage . ";Image;width=100,height=100;";
			 
			 $jj = new jscript;
	         $toprint .= $jj->JS_function("js_openwin",$params); 
			 unset ($jj);
			 
	         $toprint .= ">"; 
	       }
	       else
             $toprint .= "<A href=\"$showimage\">";
	 
	       $toprint .= $this->image_button;
           $toprint .= "</A>";
         }

         $audio = $title.$this->AUDIO_FILETYPE;
         if (myfile_exists($this->AUDIO_FILETYPE,$activedir,$audio)) { 
           $showaudio = $path;//$activedir . $audio;
                       
           if ($this->USE_WINAMP) {
              $winampfile = $this->GetMP3file($id,$title,$group,$showaudio);

              $toprint .= "<A href=\"$winampfile\">";
  	          $toprint .= $this->mp3_button;
              $toprint .= "</A>";
           }
           else  
              $toprint .= "<A href=\"$showaudio\">";
			  
   	       $toprint .= $this->audio_button;
           $toprint .= "</A>";
           
         }

         $video = $title.$this->VIDEO_FILETYPE; 
         if (myfile_exists($this->VIDEO_FILETYPE,$activedir,$video)) {
           //print " $VIDEO_FILETYPE";
           $showvideo = $this->urlpath . $path . "/" . $video;

           if (iniload('JAVASCRIPT')) {	
  	         $toprint .= "<A href=\"#\" ";	   
	         //call javascript for opening a new browser win for the img		   
	         $params = $showvideo . ";Video;width=400,height=400;";
			 
			 $jj = new jscript;			 
	         $toprint .= $jj->JS_function("js_openwin",$params); 
			 unset ($jj);			 
			 
	         $toprint .= ">"; 
	       }
	       else
             $toprint .= "<A href=\"$showvideo\">";
			 
           $toprint .= $this->video_button;
           $toprint .= "</A>";
		   	 
         }										   
   
         return ($toprint);
	}
	
	
    ///////////////////////////////////////////////////////////
    // full view files option
    ///////////////////////////////////////////////////////////
    function fullviewfile($id,$title,$path,$template,$group,$descr='',$photo='',$price=0,$quant=1) {

       $fullname = $this->prpath . $path . "/" . $id; 
	   //$fullimagename = $path . "/" . $id;

       //template type selection
       if ((stristr ($template,".html")) || (stristr ($template,".htm")))  {  
          //html template  
          $htmldesc = getMetaDesc($fullname); //?????????????????????
          $toprint .= "<b>"."$htmldesc"."</b>"; 
          $toprint .= html2txt("$fullname");
	  
          $data[] = $toprint; 
          $attr[] = "left";
       }
       elseif (stristr ($template,".txt")) {  //txt file shows the same name image      
          //text template
          $htmltext = txt2html("$fullname");
	      $showimage = $this->urlpath . $path . "/" . str_replace (".txt", $this->IMAGE_FILETYPE , $id);
          $toprint .= "$htmltext";  
	  	
          $data[] = $toprint; 
          $attr[] = "left";
		  
          $data[] = "<img src=\"$showimage\" width=\"100\" height=\"150\" alt=\"\">"; 
          $attr[] = "right";
       }
       elseif (stristr ($template,".xgi")) {      
          //xgi script template
          $p = new parser("$fullname",1);
	      $scriptext = $p->translate();
	      unset ($p);
          $toprint .= $scriptext; 	  	  
	  
          $data[] = $toprint; 
          $attr[] = "left";	     
       }
       elseif (stristr ($template,".mp3")) {      
          //mp3
                       
          if ($this->USE_WINAMP) {
              $winampfile = $this->GetMP3file($id,$title,$group,$path);

              $toprint .= "<A href=\"$winampfile\">";
  	          $toprint .= "Play";
              $toprint .= "</A>";
          }
          else { 
              $toprint .= "<A href=\"$fullname\">";
   	          $toprint .= "Play";
              $toprint .= "</A>";
          }  	  
	  
          $data[] = $toprint; 
          $attr[] = "left";	 		  
       }
       elseif (stristr ($template,"dba")) {
          //database record

	      $showimage = paramload('SHELL','urlbase') . paramload('FILESYSTEM','dbres') . $photo . ".jpg";
	  	
		  $data[] = $id;
		  $attr[] = "left;20%";							  
		  $data[] = $descr;
		  $attr[] = "left;30%";
		  $data[] = "<img src=\"$photo\" width=\"125\" height=\"100\" alt=\"\">"; 
		  $attr[] = "left;25%";
		  $data[] = $price;
		  $attr[] = "left;15%";
		  $data[] = "Cart";
		  $attr[] = "left;10%";
       }  
	   
	   $win = new window('',$data,$attr);
	   $out = $win->render();
	   unset ($win);
	   
       return ($out);
    } 

    ///////////////////////////////////////////////////////////
    // summary view files option
    ///////////////////////////////////////////////////////////
    function sumviewfile($id,$title,$path,$template,$group,$descr='',$photo='',$price=0,$quant=1) {
	   $p = GetReq('p');

	   $gr = urlencode($group);
	   $ar = urlencode($title);	   
       $name = $title;
       $fullname = $this->prpath . $path . "/" . $id;

       //template type selection
       if ((stristr ($template,".html")) || (stristr ($template,".htm")))  {   
          //html template  
          $htmldesc = getMetaDesc($fullname); //?????????????????????
          $toprint .= "<b>"."$htmldesc"."</b>"; 
		  
		  $toprint .= seturl("t=3&a=$ar&g=$gr",$this->more);
          //$toprint .= "<A href=\"$PHP_SELF?t=3&a=$ar&g=$gr\">";			          
          //$toprint .= $this->more;			          
          //$toprint .= "</A>";

          $data[] = $toprint; 
          $attr[] = "left";
       }
       elseif (stristr ($template,".txt")) {  //txt file shows the same name image       
          //text template
          $htmltext = txt2sumtxt("$fullname",5);
	      $showimage = $this->urlpath . $path . "/" . str_replace (".txt", $this->IMAGE_FILETYPE , $id);
          $toprint .= "$htmltext";  
		 
		  $toprint .= seturl("t=3&a=$ar&g=$gr",$this->more);		  
          //$toprint .= "<A href=\"$PHP_SELF?t=3&a=$ar&g=$gr\">";				          
          //$toprint .= $this->more;			          
          //$toprint .= "</A>";

          $data[] = $toprint; 
          $attr[] = "left";
		  
          $data[] = "<img src=\"$showimage\" width=\"100\" height=\"75\" alt=\"\">"; 
          $attr[] = "right";
       }
       elseif (stristr ($template,".xgi")) {        
          //xgi script template
          $p = new parser("$fullname",1);
	      $scriptext = $p->translate(1);
	      unset ($p);
          $toprint .= "$scriptext"; 
		  
		  $toprint .= seturl("t=3&a=$ar&g=$gr",$this->more);		  
          //$toprint .= "<A href=\"$PHP_SELF?t=3&a=$ar&g=$gr\">";				          
          //$toprint .= $this->more;			          
          //$toprint .= "</A>";

          $data[] = $toprint; 
          $attr[] = "left";	  
       }
       elseif (stristr ($template,".mp3")) {      
          //mp3
                       
          if ($this->USE_WINAMP) {
              $winampfile = $this->GetMP3file($id,$title,$group,$path);

              $toprint .= "<A href=\"$winampfile\">";
  	          $toprint .= "Play";
              $toprint .= "</A>";
          }
          else { 
              $toprint .= "<A href=\"$fullname\">";
   	          $toprint .= "Play";
              $toprint .= "</A>";
          }  	  
	  
          $data[] = $toprint; 
          $attr[] = "left";	 		  
       }
       elseif (stristr ($template,"dba")) {    
          //database record
          $dbtext = $title;
	      $showimage = paramload('SHELL','urlbase') . paramload('FILESYSTEM','dbres') . $photo . ".jpg";
	  	
          $toprint = "<A href=\"$PHP_SELF?t=3&a=$ar&g=$gr\">";				          
          $toprint .= $this->more;			          
          $toprint .= "</A>";

		  $data[] = $id;
		  $attr[] = "left;20%";							  
		  $data[] = $title . $toprint;
		  $attr[] = "left;30%";
		  $data[] = "<img src=\"$photo\" width=\"75\" height=\"50\" alt=\"\">"; 
		  $attr[] = "left;25%";
		  $data[] = $price;
		  $attr[] = "left;15%";
		  $data[] = "Cart";
		  $attr[] = "left;10%";	  
       } 
	   
	   $win = new window('',$data,$attr);
	   $out = $win->render();
	   unset ($win);

       return ($out);
    } 
	
	
	
	function browse($packdata,$view='') {
	  
	   $data = explode("||",$packdata);
	
	   switch ($view) {
					      case 1 :
								   $out = $this->view1($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9]);		  
						           break;

						  case 2 :
								   $out = $this->view2($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9],$data[10]);
                                   break;		   
	   }
	   
	   return ($out);
	} 	
	
    ///////////////////////////////////////////////////////////
    // view 1 
    ///////////////////////////////////////////////////////////
    function view1($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1) {
	   
	   $a = GetReq('a');
	   
	   $gr = urlencode($group);
	   $ar = urlencode($title);
       $selectname = _without($title);	   

       if ($a == $title) {	
			   
         $articledata = $this->fullviewfile($id,$title,$path,$template,$group,$descr,$photo,$price,$quant);
         $articledata .= $this->showsymbol("$id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant",$group,$page);

         $out = $this->render($title,$articledata);
	     $myarticle = new window($title,$articledata);
	     $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::"); 	
		 unset($myarticle);	 
	   }
	   else {
	   
   	     $unselectname = seturl("t=$this->view&a=$ar&g=$gr&p=$page",$selectname); 
	   	
	     $myarticle = new window($unselectname,'');
	     $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::"); 	
		 unset($myarticle);		   

	   }   
	   return ($out);	   
	}
    ///////////////////////////////////////////////////////////
    // view 2
    ///////////////////////////////////////////////////////////
    function view2($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1) {
		
       $articledata = $this->sumviewfile($id,$title,$path,$template,$group,$descr,$photo,$price,$quant);
       $articledata .= $this->showsymbol("$id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant",$group,$page);

	   $myarticle = new window($title,$articledata);
	   $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::");
	   unset($myarticle);		      

	   return ($out);
	}	
	
	function headtitle() {
	}		

};
}
?>