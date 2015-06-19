<?php

$__DPCSEC['NEWSRV_DPC']='1;1;1;1;1;1;1;1;1';
$__DPCSEC['NEWSRV_UPLOAD']='2;1;1;1;1;1;1;1;2';

if ( (!defined("NEWSRV_DPC")) && (seclevel('NEWSRV_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("NEWSRV_DPC",true);

$__DPC['NEWSRV_DPC'] = 'newsrv';

$__EVENTS['NEWSRV_DPC'][0]='newsrv';
$__EVENTS['NEWSRV_DPC'][1]='news';
$__EVENTS['NEWSRV_DPC'][2]='upload2srv';

$__ACTIONS['NEWSRV_DPC'][0]='newsrv';
$__ACTIONS['NEWSRV_DPC'][1]='news';
$__ACTIONS['NEWSRV_DPC'][2]='upload2srv';
//$__ACTIONS['NEWSRV_DPC'][3]='index';//dummy moved to pcntl !!!!

$__LOCALE['NEWSRV_DPC'][0]='NEWSRV_DPC;News;Νεα;';
$__LOCALE['NEWSRV_DPC'][1]='_MORE;More;Περισσότερα;';

$__PARSECOM['NEWSRV_DPC']['render']='_NEWS_';


class newsrv {

    var $prpath,$urlpath;
	var $path;
	var $nbuffer;
	var $newsfiles,$newstimes,$photofiles,$newstitles;
	var $extensions,$iext;
	var $preordered;
	var $apppath;
	var $tstyle;

	function newsrv() {

       if ($application=GetSessionParam('REALREMOTEAPPSITE'))
	     $this->apppath = $application . "/";
	   
	   $this->prpath = paramload('SHELL','prpath');
	   $this->urlpath = paramload('SHELL','urlpath');	   
	   
	   $current_lang = GetReq('langsel')?GetReq('langsel'):getlocal();
       $news_dir = $this->urlpath . "/" . paramload('ID','hostinpath') . '/' . paramload('NEWSRV','dirname')."_".$current_lang.'/'; 	   
	   //echo $news_dir,'>>>';
	   if (is_dir($news_dir))
	     $this->path = $news_dir;
	   else	 
	     $this->path = $this->urlpath . "/" . paramload('ID','hostinpath') . '/' .paramload('NEWSRV','dirname'); 
	//echo $this->path,'>>>';	 
	   //echo $this->path;  
	   $this->nbuffer = paramload('NEWSRV','buffer');
	   $this->extensions = arrayload('NEWSRV','extensions');
	   //print_r($this->extensions);
	   $this->iext = paramload('NEWSRV','imagetype');
	   $this->preordered = paramload('NEWSRV','preordered');	   
	   
	   
	   $greenwitch = paramload('NEWSRV','greenwitch');
	   if ($greenwitch)
	     $this->tstyle = "d-m-Y h:sO";
	   else
	     $this->tstyle = "d-m-Y h:s";		   
	}

    function event($evn=null) {

       switch ($evn) {		
          case "newsrv"     : $this->readnews($this->nbuffer,$this->preordered);
		                      break;	
          case "news"       : break;							  
          case "upload2srv" : $this->upload_file(); 
		                      //$this->read();
							  break;	
							  

       }							  	  	  	   
	}	

	function action($action=null) {
	
	   $out = setNavigator(localize("NEWSRV_DPC",getlocal()));

       //browse
       /*$browser = new browse($this->dfiles,"News");
	   $out .= $browser->render("news",30,$this,1,1,1,0);
	   unset ($browser);		   
	   
	   $mynews = new window('News',$this->render());
	   $out .= $mynews->render();//" ::100%::0::group_form_headtitle::center;100%;::");	   
	   $out .= $this->uploadform();*/

       switch ($action) {		
          case "newsrv"     : //$out = $this->newsarray($this->nbuffer,1);//test for phpchunk
		                      $out .= $this->shownews($this->nbuffer);				 	  
          case "news"       : break;	
          case "upload2srv" : break;		 	  
       }	   
	   
	   return ($out);
	}
	
	function readnews($nbuffer=null) {
	   $this->newsfiles = array();
	   $this->newstimes = array();	   
	   $this->photofiles = array();	 
	   $this->newstitles = array();  
       //echo $this->path;
	   
	   if (is_dir($this->path)) {
	     $offset = 1;   
         $d = @opendir($this->path);
		 $meter = 0; 
         while($file = @readdir($d)) {   
            if ($file != "." && $file != "..") {
			  //echo $file;
		      //if (!is_dir($this->path.$file)) { 
			 foreach ($this->extensions as $num=>$ext) { 
			  if (strstr($file,$ext)) {
	  
			    $s = stat($this->path.$file); 
				$localeparts = explode("_",$file);
				$localefile = $localeparts[0];
				$locale = $localeparts[1];
				$showparts = explode("-",$localefile);

				if (($locale==getlocal()) || (!isset($locale))) {
				  //print_r($d);echo "<br>";
				  //$date = date("Ymd",$d[9]); echo $date;
				  $ss = $s[9]; //echo $ss;
                  //echo $ss,'-',$file,'<br>';						  
				  if (array_key_exists($ss,$this->newstitles)) {
				    $ss1 = $ss + $offset;
					$offset+=1;
					//echo "<br>-----$ss1------<br>";
				  }	
				  else
				    $ss1 = $ss;
				  //echo $ss1,'<br>';	
				  $timein = strval(time() - $ss); //echo $timein;
				  $this->newstimes[$ss1] = $timein;
			      $this->newsfiles[$ss1] = $file;
				  
				  if ($showparts[1]) 
				    $this->newstitles[$ss1] = str_replace($ext,"",$showparts[1]);
				  else 
				    $this->newstitles[$ss1] = str_replace($ext,"",$showparts[0]);				  
				  	
				  //print_r($this->newstitles);				  
				  $pfile = $this->path.str_replace($ext,$this->iext,$localefile);				  
				  if (file_exists($pfile))
				    $this->photofiles[$ss1]= str_replace($ext,$this->iext,$localefile);
					
   	              $meter+=1;  
				  //MUST ALL BE NAMED BY DATE ex.20050228-name.extension_lang
				  if (($this->preordered) &&	
				      ($nbuffer) && ($nbuffer<=$meter)) {
			  		  @closedir($d);
	                  krsort($this->newsfiles);	
					  //print_r($this->newsfiles);			  
					  return 0;
				  } //else..it reads alphabetically not by date..
			    }		
			  }	
			 } 		  
			}		   
		 }
		 @closedir($d);
	   }
	   //print_r($this->newsfiles);	   
	   krsort($this->newsfiles);	
	   //print_r($this->newsfiles);
	   //print_r($this->newsfiles);
	   //print_r($this->newstitles);
	}
	
	function shownews($nbuffer=null,$head=null,$class='',$trim=null) {
	
	   if (!$class) $class = 'group_article_body';

       if ($head) $out = setNavigator(localize("NEWSRV_DPC",getlocal()));
	   
	   $maxc = count($this->newsfiles);
	   
	   if ($nbuffer) $this->nbuffer = $nbuffer;   
	   //if ($maxc<$this->nbuffer) $this->nbuffer = $maxc;
	
	   //for ($i=0;$i<$this->nbuffer;$i++) {
	   $i=0;
	   if (is_array($this->newsfiles)) {
	   foreach ($this->newsfiles as $times=>$file) {
	   
	     $f = $this->path.$file; //echo $f;
	     $contents = file_get_contents($f);

		 reset($this->extensions);
		 /*foreach ($this->extensions as $num=>$ext) {
		   if (stristr($file,$ext)) 
		     $title = str_replace($ext,'',$file);
			 //added locale '_locale' replace
			 $locext = '_'.getlocal();
			 $title = str_replace($locext,'',$title);
		 }  */
		 $title = seturl("t=newsrv#".$times,$this->newstitles[$times]);

		 $article = '<b>' . $title . "</b><br><br>" . 
		           '[<b>' . date($this->tstyle,floatval($times)) . '</b>] ';
		 if ($trim) {	
		    //echo $contents,"<br>";	   
			$article .= substr($contents,0,$trim). '...<br>';
			$article .= seturl('t=newsrv#'.$times,localize('_MORE',getlocal()));
		 }		   
		 else
		   $article .= $this->set_anchor($times) . $contents;
				   
		 $data[] = $article;		   
		 $attr[] = "left;99%";
		 
		 if ($p=$this->photofiles[$times]) {
		 
		   $mysellang = GetReq('langsel')?GetReq('langsel'):getlocal();
		   $selected_lang = $mysellang?$mysellang:0;
		   $news_file = $this->urlpath . "/".paramload('ID','hostinpath')."/" . paramload('NEWSRV','dirname') ."_".$selected_lang."/".$p;
		   //echo $news_file,'>>>';
	       if (is_readable($news_file))
		     $url = $this->apppath . paramload('NEWSRV','dirname') .$selected_lang."/".$p;	 
		   else	 
		     $url = $this->apppath . paramload('NEWSRV','dirname') . $p;
			 
		   $data[] = "<img src=\"$url\">";// width=\"100\" height=\"100\" alt=\"\">";
		   $attr[] = "right;1%";
		 } 		 
				 
	     $mynews = new window(null,$data,$attr);
	     $out .= $mynews->render(" ::100%::0::$class::left;100%;::");	   	 	
		 unset ($mynews);
		 unset ($data); unset ($attr);
		 
		 $out .= "<br>";//"<hr>";
		 $i+=1;		 
		 if (($nbuffer) && ($i>=$nbuffer)) break;//local call
		 elseif ($i>$this->nbuffer) break;//global call
		 //else echo '>',$i; 
	   }
	   }
	   return ($out);
	}
	
	function render($nbuffer=10,$title=null,$class='',$trim=null) {
	   //echo '>>>',$nbuffer,'>>>>';
	   $this->readnews($nbuffer);
	   $out = $this->shownews($nbuffer,$title,$class,$trim);
	   
	   return ($out);
	}
	
	function set_anchor($name) {
	
	  $ret = "<a name=\"$name\"></a>";
	  return ($ret);
	}
	
	function search($text) {
	   $this->newsfiles = array();
	   $this->newstimes = array();	   
	   $this->photofiles = array();	   
       //echo $this->path;
	   
	   if (is_dir($this->path)) {
	   
         $d = @opendir($this->path);
		 
         while($file = @readdir($d)) {   
            if ($file != "." && $file != "..") {
			  //echo $file;
		      //if (!is_dir($this->path.$file)) { 
			 foreach ($this->extensions as $num=>$ext) { 
			  if (strstr($file,$ext)) {
			  
	            $f = $this->path.$file; //echo $f;
				$title = str_replace($ext,'',$file);
	            $contents = file_get_contents($f);			  
				
                //search			
				if ((GetGlobal('controller')->calldpc_method('search.find use '.$title.'+'.$text)) ||  
			        (GetGlobal('controller')->calldpc_method('search.find use '.$contents.'+'.$text))) { 
					
			      $s = stat($this->path.$file); 
				  //print_r($d);echo "<br>";
				  //$date = date("Ymd",$d[9]); echo $date;
				  $ss = $s[9]; //echo $ss;
				  $timein = strval(time() - $ss); //echo $timein;
				  $this->newstimes[$ss] = $timein;
			      $this->newsfiles[$ss] = $file;
				
				  $pfile = $this->path.str_replace($ext,$this->iext,$file);
				  if (file_exists($pfile))
				    $this->photofiles[$ss]= str_replace($ext,$this->iext,$file);; 
				}  
			  }	
			 } 			  
			}   
		 }
		 @closedir($d);
	   }
	   krsort($this->newsfiles);	
	   //print_r($this->newsfiles);	
	}
	
	function search2($text,$group=null,$type=null,$case=null) {
	
       $terms = explode (" ", $text, 5); //5 = terms limit	
	
	   switch ($type) {
	       case $this->anyterms : // OR
                                  reset($terms);
						          foreach ($terms as $word_no => $word) {

	                              }								  
	                              break;
           case $this->allterms : // AND
                                  reset($terms);							   
						          foreach ($terms as $word_no => $word) {	
								  
	                              }							   		
	                              break;
		   default              :						  
           //case $this->asphrase : // AS IS = default
		                          $this->search_news($text);
			   
	   }	
	}
	
	///////////////////////////////////////////////////////////////////
	// web upload
	function uploadform() {
		  $a = GetReq('a');
		  $g = GetReq('g');
		  $t = GetReq('t');
		  $p = GetReq('p');

        //if (seclevel('ADMINFL_',$this->userLevelID)) {		
 	      $gr=urlencode($g);
	      $ar=urlencode($a);	   

          $filename = seturl("t=news&a=$ar&g=$gr&p=$p");		
		
	      //upload file(s) form
          $out  = "<FORM action=". "$filename" . " method=post ENCTYPE=\"multipart/form-data\" class=\"thin\">";
          $out .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" VALUE=\"9024\">"; //max file size option in bytes
          $out .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>"; 			 	   
          $out .= "Upload: <input type=FILE name=\"uploadfile\">";		    
          $out .= "<input type=\"hidden\" name=\"FormName\" value=\"UploadFiles\">"; 	
          $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"upload2srv\">&nbsp;";		
          $out .= "<input type=\"submit\" name=\"Submit2\" value=\"Upload\">";		
          $out .= "</FONT></FORM>"; 	
		
		  $wina = new window('',$out);
		  $winout = $wina->render();//"center::100%::0::group_dir_title::right::0::0::");
		  unset ($wina);		  
		//}			  
		
		return ($winout);
	}		
	
	function upload_file() {
        $uploadfile = GetGlobal('uploadfile');
        $uploadfile_size = GetGlobal('uploadfile_size');
	    $uploadfile_name = GetGlobal('uploadfile_name');
	    $uploadfile_type = GetGlobal('uploadfile_type'); //get uploaded file form data & file's attrs 
	
		$location = $this->path;
		$myfile = $uploadfile_name;
		$myfilepath = $location . "/" . $myfile; //echo $myfilepath;

		//copy it to admin write-enabled directory				   
		if (copy($uploadfile,$myfilepath)) {
    	   setInfo("File $uploadfile_name successfully uploaded !<br> Size : $uploadfile_size <br> Type : $uploadfile_type <br> Temporary file : $uploadfile"); 
		}
		else {
		   setInfo("Failed to upload file $uploadfile_name !"); 				   
		}
		unlink($uploadfile); 
	}	
	
	
	function browse($packdata,$view='') {
	  
	   $data = explode("||",$packdata);
	
	   switch ($view) {
					      case "news"   :
								   $out = $this->view1($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9]);		  
						           break;

						  case "newsrv" :
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
	   
   	     $unselectname = seturl("t=news&a=$ar&g=$gr&p=$page",$selectname); 
	   	
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
	
	
	//RETURN ARRAY TO BROWSER AND HANDLED BY CNTL + HTML OF PAGE
	function newsarray($nbuffer=null,$head=null,$class='') {
	
	   $returned_array = array();
	
	   if (!$class) $class = 'group_article_body';

       if ($head) $out = setNavigator(localize("NEWSRV_DPC",getlocal()));
	   
	   $maxc = count($this->newsfiles);
	   
	   if ($nbuffer) $this->nbuffer = $nbuffer;   
	   //if ($maxc<$this->nbuffer) $this->nbuffer = $maxc;
	
	   //for ($i=0;$i<$this->nbuffer;$i++) {
	   $i=0;
	   if (is_array($this->newsfiles)) {
	   //print_r($this->newsfiles);
	   foreach ($this->newsfiles as $times=>$file) {
	   
	     $f = $this->path.$file; //echo $f;
	     $contents = file_get_contents($f);

		 reset($this->extensions);

		 $title = $this->newstitles[$times];

		 $data[] = '<b>' . $title . "</b><br><br>" . 
		           '[<b>' . date($this->tstyle,$times) . '</b>] ' .
				   $contents;
		 //$attr[] = "left;99%";
		 
		 if ($p=$this->photofiles[$times]) {
		   $url = $this->apppath . paramload('NEWSRV','dirname') . $p;
		   $data[] = "<img src=\"$url\">";// width=\"100\" height=\"100\" alt=\"\">";
		   //$attr[] = "right;1%";
		 } 		 
				 
	     /*$mynews = new window(null,$data,$attr);
	     $out .= $mynews->render(" ::100%::0::$class::left;100%;::");	*/   	 	

		 $returned_array[] = $data;
		 unset ($mynews);
		 unset ($data); unset ($attr);
		 //$out .= "<hr>";
		 $i+=1;		 
		 if (($nbuffer) && ($i>=$nbuffer)) break;//local call
		 elseif ($i>$this->nbuffer) break;//global call
		 //else echo '>',$i; 
	   }
	   }
	   //return ($out);
	   //print "<pre>";
	   //print_r($returned_array);
	   //print "</pre>";	
	   
	   /*$myform[] = 'User';	   
	   $myform[] = 'a';
	   $myform[] = 'Pass';
	   $myform[] = 'b';  	   	   	   	   	   	   	   	   	   	   
	   $myform[] = 'Submit';
	   $myform[] = 'Login';
	   $form_array['form'] = (array)$myform;
	   $form_array['action'] = 'http://www.stereobit.com';
	   return ($out.'<@PHPCHUNK>'.serialize($returned_array).'<@PHPCHUNK>'.serialize($form_array));*/
	   
	   return ($out);
	}	

};
}
?>