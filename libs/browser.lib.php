<?php

$__DPCSEC['SEARCHTOPIC_']='1;1;1;1;2;2;2;2;9';
$__DPCSEC['VIEWSTLS_']='1;1;1;1;1;1;1;2;9';
$__DPCSEC['ADMBROWSE_']='1;1;1;1;1;1;1;2;9';
$__DPCSEC['ALLBROWSE_']='1;1;1;1;1;1;1;2;9';
$__DPCSEC['PDFBROWSE_']='1;1;1;1;1;1;1;2;9';
$__DPCSEC['EXCELBROWSE_']='1;1;1;1;1;1;1;2;9';
$__DPCSEC['MAILBROWSE_']='1;1;1;1;1;1;1;2;9';
$__DPCSEC['PAGELENGTH_']='1;1;1;1;1;1;1;2;9';

if (!defined("BROWSER_DPC")) {
define("BROWSER_DPC",true);

$__DPC['BROWSER_DPC'] = 'browse';

$__EVENTS['BROWSER_DPC'][0]='searchtopic';
$__ACTIONS['BROWSER_DPC'][0]='searchtopic';

$__DPCATTR['BROWSER_DPC']['searchtopic'] = 'searchtopic,0,0,0,0,0,1,0,0,1'; 

$__LOCALE['BROWSER_DPC'][0]='_VIEWSTYLES;View styles;Μορφή';
$__LOCALE['BROWSER_DPC'][1]='_SORT;Sort;Ταξ.';
$__LOCALE['BROWSER_DPC'][2]='_PAGE;Page;Σελίδα';
$__LOCALE['BROWSER_DPC'][3]='_EMPTYDIR;Not availbale products;Κανένα διαθέσιμο προϊόν';
$__LOCALE['BROWSER_DPC'][4]='_NEXT;Next;Επόμενο';
$__LOCALE['BROWSER_DPC'][5]='_PREV;Prev;Προηγούμενο';
$__LOCALE['BROWSER_DPC'][6]='_BEGIN;Begin;Αρχή';
$__LOCALE['BROWSER_DPC'][7]='_END;End;Τέλος';
$__LOCALE['BROWSER_DPC'][8]='_ALLBROWSE;Show All;Προβολή Όλων';
$__LOCALE['BROWSER_DPC'][9]='_SEARCH;Search;Αναζήτηση';
$__LOCALE['BROWSER_DPC'][10]='_GROUPOF;Group;Ομαδα';

class browse {

	var $userLevelID;

    var $outpoint;
    var $rightarrow;
    var $view_on;
	var $home;
	var $pagedfiles;
	var $page;
	var $pagenum;
	var $title;
	var $datalist;
	var $sortmethod;
	var $defaultview;

    var $start_b;
    var $end_b;
	var $next_b;
    var $prev_b;
	var $selpage;
	var $stylearray;
	var $styler;
	var $columns;	
	
	var $dosearch;
	var $searchtext;
	
	var $timeout;
	
	var $alltitle;
	var $agent;

	function browse($data='',$title='',$selpage=1,$styler="",$columns="") {
	    $UserSecID = GetGlobal('UserSecID');
	    $__USERAGENT = GetGlobal('__USERAGENT');
	    $GRX = GetGlobal('GRX');	
		$PL = GetReq('pl');

        $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
		$this->agent = $__USERAGENT;
		
	    $this->searchtext = trim(GetParam("searcht"));	
		if (!$this->searchtext) $this->searchtext = GetReq('a'); //set $a as search topic
        if ($this->searchtext) $this->dosearch = 1; 
		                  else $this->dosearch = 0; 
		
        $this->datalist     = $data;       
        $this->title        = $title;
		$this->sortmethod   = GetSessionParam('sort'); 
		$this->pagedfiles   = array();
		$this->page         = 0;
		$this->pagenum      = ($PL ? $PL:10); //default
		$this->selpage      = $selpage;
		$this->columns      = $columns; //added due to a xml support
				
		$this->stylearray   = explode(",",$styler);
		$this->styler       = $styler;	
		
	    $this->timeout = paramload('SHELL','timeout');				 
		
        if ($GRX) {
             $this->outpoint = loadTheme('point');      
             $this->rightarrow = loadTheme('rarrow');
             $this->start_b = loadTheme('start',localize('_BEGIN',getlocal()));
             $this->end_b = loadTheme('end',localize('_END',getlocal()));
             $this->next_b = loadTheme('next',localize('_NEXT',getlocal()));
             $this->prev_b = loadTheme('prev',localize('_PREV',getlocal()));
		}
		else {
             $this->outpoint = "|";
	         $this->rightarrow = ">";
             $this->start_b = "<<";
             $this->end_b = ">>";
			 $this->next_b = ">";
             $this->prev_b = "<";
		}	
		
   	    $this->alltitle = localize('_ALLBROWSE',getlocal());	
        	
    }
	
	
   /* function event($event=null) {
	
       switch($event) {
          case "searchtopic" : $this->dosearch = 1; break; //has not effect	  
       }
	}
	
	function action($action=null) {
	}*/

	function render($viewtype=0,$pager,$class,$pagemaker=0,$topic=0,$sorter=0,$adminform=0,$excel=0,$pdf=0,$mail=0,$all=0) {

        if (is_array($this->datalist)) {
		    //set page length
			if ($pager) $this->pagenum = $pager;
            //sort files
		    $this->sort_files();
			
	        switch ($this->agent) {			
			
		      case 'CLI'  :
		      case 'TEXT' : 
	          case 'XML'  : 
              case 'XUL'  :
		      case 'GTK'  : $out = $this->browse_all_files($viewtype,$class);
			                break;
		      case 'HTML' :
              default     :	//paging only in HTML	
			                switch (GetReq('v')) {
							  case 'excel':  $out = $this->generateExcel(); 
							                 break;
							  case 'pdf'  :  //make array of pages		 
		                                     $this->read_pagefiles();
											 //make pdf
							                 $out = $this->generatePdf(); 
											 break;
							  case 'mail' :  $out = $this->generateMail(); 
							                 break;
							  case 'all'  :  $out = $this->viewAll($viewtype,$class);
							                 $out .= $this->browse_advance($topic,$excel,$pdf,$mail);  
							                 break;
							
							  default     :							
							                 if (($pagemaker) && (GetReq('param1')!='-all')) {
			                                   //get searched data
			                                   $this->selpage = $this->getpage($this->searchtext);
			                                   //make pages		 
		                                       $this->read_pagefiles();
                                               //preview 
		                                       $out = $this->browse_files($viewtype,$class,$pagemaker,$topic,$sorter,$adminform,$excel,$pdf,$mail,$all);		
		                                     }
			                                 else {
		                                       $out = $this->browse_all_files($viewtype,$class);
											   $out .= $this->browse_advance($topic,$excel,$pdf,$mail,null);
											 }  
							}				   
            }
		    return($out);
    	}
     }

 
    function read_pagefiles() {

		$meter = 0;
        $pnum = $this->pagenum;
			   
	    reset($this->datalist); //print_r($this->datalist);
        foreach ($this->datalist as $file_num => $filename) {	

			if ($meter >= $pnum) {
				$meter=0;
				$this->page+=1;
			}

			$this->pagedfiles[$this->page][$meter] = $filename; 
			$meter+=1;
		}

        reset ($this->datalist); 
        reset ($this->pagedfiles);

		//print_r($this->pagedfiles);
		return ($this->pagedfiles);
	}

    function browse_files($view,$class,$pagemaker,$topic,$sorter,$adminform,$excel,$pdf,$mail,$all) { 
		
		$t = GetReq('t');
		$p = GetReq('p'); 
     	$gr = urlencode(GetReq('g'));
		
        if (($adminform) && (seclevel('ADMBROWSE_',$this->userLevelID))) $out = $this->admin_start();		

        if ($this->pagedfiles) {
		    
			 //HEAD
	         switch ($this->agent) {
			 
		       case 'CLI'  :
		       case 'TEXT' : 
	           case 'XML'  : 
               case 'XUL'  :
		       case 'GTK'  : break;
		       case 'HTML' :
               default     :		

                         // view styles and sort method line
                         if (count($this->stylearray)>1)  $vs = $this->viewStyles();
			             if ($sorter) $sv = $this->sortStyles();				  

                         /*if (seclevel('ALLBROWSE_',$this->userLevelID)) {			 
	                       $data[] = seturl("t=$t&a=&g=$gr&v=all",$this->alltitle); //all view
	                       $attr[] = "left;25%;";			 
			             }*/
			 
                         if ((defined('PPDF_DPC')) && (seclevel('PDFBROWSE_',$this->userLevelID))) {	
			 
                           if (iniload('JAVASCRIPT')) {		
  	                         $plink = "<A href=\"" . seturl("t=$t&a=&g=$gr&p=$p") . "\"";	   
	                         //call javascript for opening a new browser win for the pdf doc		   
	                         $params = seturl("t=pdf&a=&g=$gr","",0,0,1) . ";PDF;statusbar=yes,scrollbars=yes,resizable=yes,width=640,height=480;";

				             $js = new jscript;
	                         $plink .= $js->JS_function("js_openwin",$params); 
                             unset ($js);

	                         $plink .= ">PDF</A>"; 
				  
				             $data[] = $plink;
	                       }
			               else			 		 
	                         $data[] = seturl("t=pdf&a=&g=$gr",'PDF'); //pdf view
				 
	                       $attr[] = "left;25%;";			 
			             }			 
			 
	                     $data[] = $vs . $sv; 
	                     $attr[] = "right;50%;";
                         if ($data)  { 
		                   $win1 = new window('',$data,$attr);
		                   $winout .= $win1->render("center::100%::0::group_form_headtitle::left::0::0::");
		                   unset ($win1);
		                 } 
             }//switch
			 
             //read current page array 
             if ($pagemaker) { 
		       if (!$p) $mypage=$this->selpage;
		           else $mypage=$p; 
			   $realpage = ($mypage-1);				   
			 }
			 else 
			   $realpage=0;				 
			   
			 $currentpagefiles = (array)$this->pagedfiles[$realpage];
			 //print_r($currentpagefiles);
			 
			 //BODY 
	         switch ($this->agent) {
			 
		   case 'CLI'  :
		   case 'TEXT' : $out = str_replace(",","|",$this->columns) . "\n";
		                 foreach ($currentpagefiles as $file_num => $filename)
					       $out .= str_replace(";","|",$filename) . "\n";
		                 break;
	       case 'XML'  : 
           case 'XUL'  :
		   case 'GTK'  : $xml = new pxml('XUL');
		                 $xml->addtag('GTKLIST','XUL',null,"columns=$this->columns|autoresize=true");
					     foreach ($currentpagefiles as $file_num => $filename)
					       $xml->addtag('GTKLISTITEM','GTKLIST',$filename,"id=$file_nuline");
					     $out = $xml->getxml();
					     unset($xml);	
		                 break;
		   case 'HTML' :
           default     : //INVOLVE CLASS METHODS headtitle() & browse() 	!!!		  		  
                         //print title head			 
                         if (method_exists($class,'headtitle')) 
						   $winout .= $class->headtitle($view);
		
		                 if (method_exists($class,'browse')) {		
	                       foreach ($currentpagefiles as $file_num => $filename) {	
				             $packdata = str_replace(";","||",$filename);
		                     $winout .= $class->browse($packdata,$view);
                           }
                         }
			             else 
			               die("Method 'browse' required !");	
			   				
						 //draw result			 
                         if ($winout)  { 		 	
		                   $win2 = new window($this->title,$winout);
		                   $out .= $win2->render("center::100%::0::group_article_body::left::0::0::");
		                   unset ($win2);				
		                 }
			             break;	
			 
			 }	//switch 	 			 
			 
	    } //if exist
        else { 
           $out = ''; //setTitle(localize('_EMPTYDIR',getlocal()));//"&nbsp";
        }
        if (($adminform) && (seclevel('ADMBROWSE_',$this->userLevelID))) $out .= $this->admin_end();
						
        $out .= $this->browse_advance($topic,$excel,$pdf,$mail,$all);									
		
		//view page browser
	    if ($pagemaker) $out .= $this->pageBrowser($view);	
				 			 
		
        return ($out);
    }

	
    ///////////////////////////////////////////////////////////
    //view page browser
    ///////////////////////////////////////////////////////////
    function pageBrowser($view) {//view is disbled as command
	    if (GetReq('editmode'))
	      $edmode = '&editmode=1';
	    else
	      $edmode = null; 	
		
		$gr = urlencode(GetReq('g'));		
	    $p = GetReq('p');	
		$pl = GetReq('pl');			
		
		$view = $view?$view:GetReq('t');//view style is not a command anymore, so t=command		
		//echo '>',$view;  		

		$grouppager = 2;
        $ptext = localize('_PAGE',getlocal()) . " :";

        if ($this->page>0) {

          //initialize page
	      if (!$p) $p = $this->selpage;
          
          $groupprev = (($p-1) - $grouppager); 
		  if ($groupprev<=0) $groupprev = 0;
		                else $markstart = "..."; 
		  $groupnext = (($p-1) + $grouppager); 
		  if ($groupnext>$this->page) $groupnext = $this->page;
		                         else $markend = "...";

          //prev buttons
		  $prevpage = $p-1;
          $data .= seturl("t=$view&a=&g=$gr&p=1&pl=$pl".$edmode, $this->start_b) . "&nbsp;";
		  if ($prevpage>0) $data .= seturl("t=$view&a=&g=$gr&p=$prevpage&pl=$pl".$edmode, $this->prev_b);
		              else $data .= $this->prev_b;

          $data .= $markstart;
		  $data .= " " . $this->outpoint . " ";

		  for ($i=$groupprev; $i<=$groupnext; $i++) {
             $pp = $i+1;
             if ($pp==$p) {
			     $data .= seturl("t=$view&a=&g=$gr&p=$pp&pl=$pl".$edmode,"<B>" . $pp . "</B>") . "&nbsp;" . $this->outpoint . "&nbsp;";
		     }
		     else {
                 $data .= seturl("t=$view&a=&g=$gr&p=$pp&pl=$pl".$edmode,$pp) . "&nbsp;" . $this->outpoint . "&nbsp;";			 
		     }
          }
		  $data .= $markend;
 
          //next buttons
		  $nextpage = $p+1;
		  if ($nextpage<=$this->page+1) $data .= seturl("t=$view&a=&g=$gr&p=$nextpage&pl=$pl".$edmode , $this->next_b);	
		                           else $data .= $this->next_b; 
          $data .= "&nbsp;" . seturl("t=$view&a=&g=$gr&p=" . ($this->page+1) . "&pl=$pl".$edmode , $this->end_b );

          //buttons browser
		  $mydata[] = $data;
		  $myattr[] = "left;50%;";
		  
		  //page length
		  $mydata[] = $this->pagelength();
          $myattr[] = "center;30%;";
		  
          //page number
		  $mydata[] = "$ptext $p / " . ($this->page+1);
		  $myattr[] = "right;20%;";
		
		  $winb = new window('',$mydata,$myattr);
		  $out = $winb->render("center::100%::0::group_dir_title::right::0::0::");
		  unset ($winb);
        }

		return ($out);
    }
	
	function browse_all_files($view,$class) {
	
	    switch ($this->agent) {
			 
		   case 'CLI'  :
		   case 'TEXT' : $out = str_replace(",","|",$this->columns) . "\n";
		                 foreach ($this->datalist as $rec_num => $rec) 
					       $out .= str_replace(";","|",$rec) . "\n";
		                 break;
	       case 'XML'  : 
           case 'XUL'  :
		   case 'GTK'  : $xml = new pxml('XUL');
		                 $xml->addtag('GTKLIST','XUL',null,"columns=$this->columns|autoresize=true");
                         foreach ($this->datalist as $rec_num => $rec)
					       $xml->addtag('GTKLISTITEM','GTKLIST',$rec,"id=$rec_num");
					     $out = $xml->getxml();
					     unset($xml);	
		                 break;
		   case 'HTML' :
           default     : if (method_exists($class,'headtitle')) $winout .= $class->headtitle($view);	
	                     reset($this->datalist);
		                 if (method_exists($class,'browse')) {						 
                           foreach ($this->datalist as $rec_num => $rec) {
				             $packdata = str_replace(";","||",$rec);
		                     $winout .= $class->browse($packdata,$view);
		                   }
						 }  
                         if ($winout)  { 		 	
		                   $win2 = new window($this->title,$winout);
		                   $out = $win2->render("center::100%::0::group_article_body::left::0::0::");
		                   unset ($win2);				
		                 }		
		}
		return ($out);	
	}


    function viewStyles() {	
	    if (GetReq('editmode'))
	      $edmode = '&editmode=1';
	    else
	      $edmode = null; 		

	    $g = GetReq('g');		
	    $p = GetReq('p');
	    $t = GetReq('t'); 				
	    $a = GetReq('a');		
		
        if (seclevel('VIEWSTLS_',$this->userLevelID)) {

          $vprint = localize('_VIEWSTYLES',getlocal()) . " :";

          foreach ($this->stylearray as $stylenum => $style) {		  
            if ($t==$style) $vprint .= "(".$style.")"; 
	                   else $vprint .= seturl("t=$t&a=$a&g=$g&p=$p&s=$style".$edmode, $style); 
		  }
		}
  			
        return ($vprint);
    }
	//select page length (num of lines)
	function pagelength() {
	    if (GetReq('editmode'))
	      $edmode = '&editmode=1';
	    else
	      $edmode = null; 		
	    $g = GetReq('g');		
	    $p = GetReq('p');
	    $t = GetReq('t'); 				
	    $a = GetReq('a'); //not need??
	    $s = GetReq('s');
		$pl = GetReq('pl');		
		
        //if (seclevel('VIEWSTLS_',$this->userLevelID)) {

          $vprint = localize('_GROUPOF',getlocal()) . " :";

          for ($i=1;$i<6;$i++) {		  
            if ($pl==($i*10)) 
			  $vprint .= "(".$pl.")"; 
	        else 
			  $vprint .= seturl("t=$t&a=$a&g=$g&p=1&s=$s&pl=".($i*10).$edmode, "[".($i*10))."]"; 
		  }
		//}
  			
        return ($vprint);	
	}	

	function sortStyles() {
	    if (GetReq('editmode'))
	      $edmode = '&editmode=1';
	    else
	      $edmode = null; 			
		$gr = urlencode(GetReq('g'));
	    $p = GetReq('p');
	    $t = GetReq('t'); 				
	    $a = GetReq('a');
		$s = GetReq('s');		

        $vprint .= localize('_SORT',getlocal()) . " :";

        if ($this->sortmethod==1) $vprint .= seturl("t=$t&a=1&g=$gr&p=$p&s=$s".$edmode, "<B>A</B>"); 
                             else $vprint .= seturl("t=$t&a=1&g=$gr&p=$p&s=$s".$edmode,"A"); 
        if ($this->sortmethod==-1) $vprint .= seturl("t=$t&a=-1&g=$gr&p=$p&s=$s".$edmode, "<B>Z</B>"); 
    	                      else $vprint .= seturl("t=$t&a=-1&g=$gr&p=$p&s=$s".$edmode,"Z"); 			  
        return ($vprint);
	}

	function sort_files() {

        //sorting (article=selected sort method, saved as session param)
        //first sort by saved method (if saved)
        switch ($this->sortmethod) {
	       case "1"  : ksort ($this->datalist,SORT_REGULAR); break; //asc
	       case "-1" : krsort ($this->datalist,SORT_REGULAR); break; //desc
           default : ksort ($this->datalist); //asc
        }					
        //secont sort by selected method (if selected) and save sort
        switch (GetReq('a')) {
	       case 1  : ksort ($this->datalist,SORT_STRING); SetSessionParam("sort", "1"); break; //asc
	       case -1 : krsort ($this->datalist,SORT_STRING); SetSessionParam("sort", "-1"); break; //desc
           default : ksort ($this->datalist); //asc		   
        }  
   		$this->sortmethod = GetSessionParam('sort');

		//print_r($this->dfiles);
	}


    ///////////////////////////////////////////////////////////////
    // directory administration (starting code called by read_dir) 
    ///////////////////////////////////////////////////////////////
    function admin_start() {
	   if (GetReq('editmode'))
	      $edmode = '&editmode=1';
	   else
	      $edmode = null; 		   
	   $gr = urlencode(GetReq('g'));
	   $ar = urlencode(GetReq('a'));
	   $p = GetReq('p');
	   $t = GetReq('t');   
       $filename = seturl("t=$t&a=$ar&g=$gr&p=$p".$edmode);		   
 
       $out = "<FORM action=". "$filename" . " method=post class=\"thin\">"; 
  
       return ($out);
    }

    ///////////////////////////////////////////////////////////////
    // (ending code called by read_dir)
    ///////////////////////////////////////////////////////////////
    function admin_end() {
       //global $sFormErr;  	   
	   $sFormErr = GetGlobal('sFormErr');
	   
       //error message
       $toprint .= setError($sFormErr);
		   
	   $toprint .= $this->admin_commands();

	   $toprint .= "</FORM>"; //the end of the form

	   $toprint .= $this->admin_actions();		   
  
       return ($toprint);
     }
 
    function searchTopic()  {
	  if (GetReq('editmode'))
	      $edmode = '&editmode=1';
	  else
	      $edmode = null; 		    
	  $t = GetReq('t');	
      $pl = GetReq('pl');	
	  $gr = urlencode(getReq('g')); 

      $filename = seturl("t=$t&a=&g=$gr&pl=$pl".$edmode);      

      $toprint  = "<FORM action=". $filename . " method=post class=\"thin\">";
      $toprint .= "<P><FONT face=\"Arial, Helvetica, sans-serif\" size=1><STRONG>";
      $toprint .= localize('_SEARCH',getlocal()) . ":";
	  $toprint .= "</STRONG> <INPUT name=searcht size=15></FONT>";
      $toprint .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>";

      $toprint .= "<input type=\"submit\" name=\"Submit\" value=\"Ok\">"; 
      $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"searchtopic\">";
      $toprint .= "</FONT></FORM>";

      return ($toprint);	   
	  //$data2[] = $toprint; 
  	  //$attr2[] = "left";

      //$swin = new window('',$data2,$attr2);
	  //$out .= $swin->render("center::100%::0::group_dir_body::left::0::0::");	
	  //unset ($swin);

      //return ($out);
    }
	
	function generateExcel() {
	  if (GetReq('editmode'))
	      $edmode = '&editmode=1';
	  else
	      $edmode = null; 		
	  //check if extension exist
      if (defined(_EXCEL_)) {
	  
	    $t = GetReq('t');
		$a = GetReq('a');
		$g = GetReq('g');
		$p = GetReq('p');
	  
	    if (!GetReq('v')) {	//just generate link  
	      $out = seturl("t=$t&a=$a&g=$g&p=$p&v=excel".$edmode,'Excel') . "&nbsp;";
		}
		else { //generate excel

	      $excel = new ExcelGen($g,paramload('SHELL','urltitle'));
	      //initiate $row,$col variables
	      $row=0;
	      $col=0;
	
	      //write text in cell(0,0)
	      $excel->WriteText($row,$col,paramload('SHELL','urltitle').'-'.$g);
	      $row++;
		  
		  foreach ($this->datalist as $id=>$data) {
		  
		    set_time_limit(5);
		    
		    $field = explode(";",$data);
			
			$maxf = count($field);
			for ($i=0;$i<$maxf;$i++)
			  $excel->WriteText($row,$i,$field[$i]);			
			
			$row++;
		  }
		  set_time_limit($this->timeout);
	
	      //stream Excel for user to download or show on browser
	      $excel->SendFile();		
	      exit();	
		  DelReq('v'); //reset value for other browsing objects 
		}
	  }
	  
	  return ($out);
	}
	
	function generatePdf() {
	  if (GetReq('editmode'))
	      $edmode = '&editmode=1';
	  else
	      $edmode = null; 		
	
      if (defined(_PDF_)) {
	  
	    $t = GetReq('t');
		$a = GetReq('a');
		$g = GetReq('g');
		$p = GetReq('p');
	  
	    if (!GetReq('v')) {	//just generate link 	  
	      $out = seturl("t=$t&a=$a&g=$g&p=$p&v=pdf".$edmode,'Pdf') . "&nbsp;";
		}
		else { //generate pdf doc
		
		  $pdfdoc = & new Cezpdf();
		  $eiro_diff = array(33=>'euro'); //replace char 33 (!) to 'euro'
		  $pdfdoc->selectFont(paramload('SHELL','fonts').'Helvetica.afm',
		                      array('encoding'=>'WinAnsiEncoding'),
							  array('differences'=>$euro_diff));	  
		  
		  if (is_array($this->pagedfiles)) {
		    foreach ($this->pagedfiles as $page=>$pagedata) {
			
			  set_time_limit(5);
			  
			  //$pdfdoc->ezNewPage(); //new page by default created
		      $pdfdoc->ezText(paramload('SHELL','urltitle'),50);
		      $pdfdoc->ezText($g,20);				
			  $farray = array();
		      foreach ($pagedata as $id=>$record) {
		        //$pdfdoc->ezText($record,10);				   
				$fields = explode(';',$record);
				$farray[] = $fields;				
		      }
			  $pdfdoc->ezTable($farray,
			                   array(0=>'a',1=>'b',/*2=>'c',3=>'d',4=>'e',*/   //associate keys with col names
							         /*5=>'f',*/6=>'g',8=>'h',7=>'i'), //and present only these columns
							   $g,//title
							   array('fontSize'=>6)/*
							   array('showLines'=>2,'showHeadings'=>1,'shaded'=>1, //attributes
							         'shadeCol'=>0.8,0.8,0.8,'shadeCol2'=>0.7,0.7,0.7,
									 'fontSize'=>10,'textCol'=>0,'titleFontSize'=>12,'rowGap'=>2)
							   */);		 
			  unset($farray);		  
			  $pdfdoc->ezNewPage(); //no blank page at start but to end			  
		    }	 
		  }
		  set_time_limit($this->timeout);
		  
		  $pdfdoc->ezStream();		   
		  
		  exit();
		  DelReq('v'); //reset value for other browsing objects 		  
		}  
	  }
	  
	  return ($out);
	}	
	
	function generateMail() {
	  if (GetReq('editmode'))
	      $edmode = '&editmode=1';
	  else
	      $edmode = null; 		
	
      if ((defined("MAIL_DPC")) && (seclevel('MAIL_DPC',$this->userLevelID)) ) { 
	  
	    $t = GetReq('t');
		$a = GetReq('a');
		$g = GetReq('g');
		$p = GetReq('p');
	  
	    if (!GetReq('v')) {	//just generate link 	  
	      //$out = seturl("t=$t&a=$a&g=$g&p=$p&v=mail",'Mail') . "&nbsp;";
		  $out = seturl("t=mail&a=1".$edmode,'Mail') . "&nbsp;";//a=1 is the first window created (active)!!!!!
        }
		else { //create mail
		  DelReq('v'); //reset value for other browsing objects		
		}  
	  }
	  
	  return ($out);
	}	
	
	function viewall($viewtype,$class) {
	  if (GetReq('editmode'))
	      $edmode = '&editmode=1';
	  else
	      $edmode = null; 		
	
      if (seclevel('ALLBROWSE_',$this->userLevelID)) {	
	  	
	    $t = GetReq('t');
		$a = GetReq('a');
		$g = GetReq('g');
		$p = GetReq('p');
	  
	    if (!GetReq('v')) {	//just generate link  
	      $out = seturl("t=$t&a=$a&g=$g&p=$p&v=all".$edmode,$this->alltitle) . "&nbsp;";
		}
		else { //generate all list
          $out = $this->browse_all_files($viewtype,$class);
		  DelReq('v'); //reset value for other browsing objects 
		}	
	  }
	  
	  return ($out);	  
	}
	
	function browse_advance($topic=0,$excel=0,$pdf=0,$mail=0,$all=0) {
	
		//search topic				
 	    if (($topic) && (seclevel('SEARCHTOPIC_',$this->userLevelID))) {
		  $data[] = $this->searchTopic();
		  $attr[] = "left;50%";
		}  
		
		//excel view				
 	    if (($excel) && (seclevel('EXCELBROWSE_',$this->userLevelID))) 
		  $cmds = $this->generateExcel();
		//pdf view				
 	    if (($pdf) && (seclevel('PDFBROWSE_',$this->userLevelID))) 
		  $cmds .= $this->generatePdf();
		//send mail				
 	    if (($mail) && (seclevel('MAILBROWSE_',$this->userLevelID))) 
		  $cmds .= $this->generateMail();
		//all list mail				
 	    if (($all) && (seclevel('ALLBROWSE_',$this->userLevelID)))  
		  $cmds .= $this->viewAll(null,null);
		
		$data[] = $cmds;
		$attr[] = "right;50%";  
					
	
        $swin = new window('',$data,$attr);
	    $out .= $swin->render("center::100%::0::group_dir_body::left::0::0::");	
	    unset ($swin);	
	  
	    return ($out);
	}
	
	
	
	function getpage($id){

      //print_r($array);
	  if ($id) {
	     $i=1;
		 $page=1;
         //ksort ($this->datalist,SORT_REGULAR);			 
		 reset($this->datalist);
         //while(list ($num, $data) = each ($this->datalist)) {
         foreach ($this->datalist as $num => $data) {	
		    $msplit = explode(";",$data); 
			
			if ( (stristr($msplit[0],$id)) || //code
			     (stristr($msplit[1],$id)) || //title
				 (stristr($msplit[6],$id)) || //descr
				 (stristr($msplit[8],$id))) { //price
				 
			     $a = $msplit[1];
				 SetReq('a',$msplit[1]);//NOT WORK
				 //$ret = floor(($i) / $this->pagenum);//+1;
				 //echo '++++',$i,'>',$ret,'>',$this->pagenum,'++++';
                 //echo $page;
			     return $page;//ret;
			}	 
			$i+=1; 
		    if ($i>($this->pagenum*$page)) $page+=1;//echo $page;			
		 }	  
	   }	 
	   return 1;
	}	
	
	//in form actions
    function admin_commands() {
      //global $__BROWSECOM;
	  //global $__DPC;
	  $__BROWSECOM = GetGlobal('__BROWSECOM');
	  $__DPC = GetGlobal('__DPC');	  
	  
	  if (is_array($__BROWSECOM)) {
	  
	    reset($__BROWSECOM);
	    foreach ($__BROWSECOM as $dpc_name => $func) {//print $func;
           if (defined($dpc_name)) {
			  $theclass = new $__DPC[$dpc_name];
              $out .= $theclass->$func();
			  unset ($theclass);
			}  
	    }
	    return $out;	 
	  }
    }		
	
	//after </form> actions
    function admin_actions() {
      //global $__BROWSEACT;
	  //global $__DPC;
	  $__BROWSEACT = GetGlobal('__BROWSEACT');
	  $__DPC = GetGlobal('__DPC');		  
	  
	  if (is_array($__BROWSEACT)) {
	  
	    reset($__BROWSEACT);
	    foreach ($__BROWSEACT as $dpc_name => $func) {//print $func;
           if (defined($dpc_name)) {
			  $theclass = new $__DPC[$dpc_name];
              $out .= $theclass->$func();
			  unset ($theclass);
		   }  
	    }
	    return $out;	 
	  }
    }	
   
};
}
?>