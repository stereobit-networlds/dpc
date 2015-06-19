<?php

$__DPCSEC['SEARCH_DPC']='2;2;2;2;2;2;2;2;9';

if ( (!defined("SEARCH_DPC")) && (seclevel('SEARCH_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SEARCH_DPC",true);

$__DPC['SEARCH_DPC'] = 'search';

$__EVENTS['SEARCH_DPC'][0]='searchin';

$__ACTIONS['SEARCH_DPC'][0]='searchin';
$__ACTIONS['SEARCH_DPC'][1]='ARes';
$__ACTIONS['SEARCH_DPC'][2]='BRes';

$__LOCALE['SEARCH_DPC'][0]='SEARCH_DPC;Search;Αναζήτηση';
$__LOCALE['SEARCH_DPC'][1]='_MSG3;Advance Search;Σύνθετη Αναζήτηση';
$__LOCALE['SEARCH_DPC'][2]='_ASPHRASE;As a Phrase;Ως Φράση';
$__LOCALE['SEARCH_DPC'][3]='_ANYTERMS;Any Terms;Κάποιο απο αυτά';
$__LOCALE['SEARCH_DPC'][4]='_ALLTERMS;All Terms;Όλα';
$__LOCALE['SEARCH_DPC'][5]='_SEARCHTYPE;Type;Τύπος';
$__LOCALE['SEARCH_DPC'][6]='_CSENSE;Case Sensitive;Κεφαλαία/Μικρά';
$__LOCALE['SEARCH_DPC'][7]='_TTIME;Total time;Συνολικός Χρόνος';
$__LOCALE['SEARCH_DPC'][8]='_SEARCHR;Search Results;Αποτελέσματα Αναζήτησης';
$__LOCALE['SEARCH_DPC'][9]='_SEARCH;Search;Αναζήτηση';
$__LOCALE['SEARCH_DPC'][10]='_ALL;All;Σε Όλα;';

$__PARSECOM['SEARCH_DPC']['searchbar']='_SEARCHBAR_';
$__PARSECOM['SEARCH_DPC']['quickform']='_QUICKSEARCH_';

class search {

	var $stext;
	var $stype;
	var $scase;
	var $scat;
	var $sin;
	var $stime;
  	var $allterms;
	var $anyterms;
	var $asphrase;
	var $t_searchtitle;
	var $t_sttype;
	var $t_casesence;
	var $t_advsearch;
	var $all;
	var $deflan;	
	
	var $moneysymbol;	

	var $userlevelID;

	function search() {
	  $GRX = GetGlobal('GRX');	
	  $UserSecID = GetGlobal('UserSecID');  

      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);	  	  

      if (((GetReq('t')=='ARes') || (GetReq('t')=='BRes')) || 
	      ((GetReq('p')) || (GetReq('a')))) {  
	                        //if page exist means pages of previous search action
	                        //or $a exist (it means sord or head selection 
	                        //..so get stored data
	    $this->stext = GetSessionParam('s_terms');//$s_terms; 
	    $this->scase = GetSessionParam('s_case');//$s_case;
        $this->stype = GetSessionParam('s_stype');//$s_stype;	 
	    $this->scat  = GetSessionParam('s_categ');//$s_categ;
		$this->sin   = GetSessionParam('s_sin');//$s_sin;
	  }
	  elseif (GetReq('text')) {  //get current url data to search
	    $this->stext = GetReq('text'); 
	    $this->scase = GetReq('case');//GetParam("searchcase");
        $this->stype = GetReq('type');//GetParam("searchtype");	 
	    $this->scat  = GetReq('g');//GetParam("category"); //NOT USED !!!!
		$this->sin   = GetReq('in');//GetParam("searchindir");		

                  // and save them
	    SetPreSessionParam('s_terms',$this->stext);
	    SetPreSessionParam('s_case',$this->scase);
	    SetPreSessionParam('s_stype',$this->stype);
	    SetPreSessionParam('s_categ',$this->scat);
		SetPreSessionParam('s_sin',$this->sin);		
	  }
	  else {      //get current form data to search
	    $this->stext = GetParam("terms"); 
	    $this->scase = GetParam("searchcase");
        $this->stype = GetParam("searchtype");	 
	    $this->scat  = GetParam("category");
		$this->sin   = GetParam("searchindir");		

                  // and save them
	    SetPreSessionParam('s_terms',$this->stext);
	    SetPreSessionParam('s_case',$this->scase);
	    SetPreSessionParam('s_stype',$this->stype);
	    SetPreSessionParam('s_categ',$this->scat);
		SetPreSessionParam('s_sin',$this->sin);
	  }

	  $this->stime = "";
 
  	  $this->allterms = localize('_ALLTERMS',getlocal());
	  $this->anyterms = localize('_ANYTERMS',getlocal());
	  $this->asphrase = localize('_ASPHRASE',getlocal());
      $this->t_searchtitle = localize('_SEARCH',getlocal());
	  $this->t_sttype = localize('_SEARCHTYPE',getlocal());
	  $this->t_casesence = localize('_CSENSE',getlocal());
	  $this->t_advsearch = localize('_MSG3',getlocal());
  	  $this->all = localize('_ALL',getlocal());

      if ($GRX) {   	 
        $this->search_button  = loadTheme('gosearch_b');
        //$this->submit_button  = "<input type=\"image\" src=\" " . loadTheme('gosearch_b','search',1) . "\">";		 	 
        $this->submit_button  = "<input type=\"image\" src=\"". loadTheme('gosearch_b','search',1) ."\" width=\"21\" height=\"21\" border=\"0\">";
      }
	  else {
        $this->submit_button  = "<input type=\"submit\" name=\"Submit\" value=\"Ok\">";
	  }
	  
      $this->moneysymbol = "&" . paramload('CART','cursymbol') . ";";	
	  $this->deflan = paramload('SHELL','dlang');	  	    
	}

    function event($event=null) { 
    }	
	
    function action($act=null) {

	   /*if ($this->sin=="Google") $out.= $this->search_google();	   
	                      else { */
						         $out  = $this->title();
							     $out .= $this->results($act);
							 //  } 
	   $out .= $this->form();

   	   return ($out);
	}

	function title() {

       if ($this->stext) { //if a word has specified 
         $out = setNavigator(localize('_SEARCHR',getlocal()) . " (" . $this->stext . ")");		
	   }
	   else {
         $out = setNavigator(localize('_SEARCH',getlocal()));
	   }  

	   return ($out);
	}
	
	function search_content($currentdir) {
	
        if ( (defined('FILESYSTEM_DPC')) && (seclevel('FILESYSTEM_DPC',$this->userLevelID)) ) {
           $contents = new filesystem;
		   $resout = $contents->search($this->stext,$this->sin);
		   unset ($contents);
		}
        if ( (defined('PRODUCTS_DPC')) && (seclevel('PRODUCTS_DPC',$this->userLevelID)) ) {
           $mysearchp = new products($currentdir);
           $resout = $mysearchp->search($this->stext,$this->sin,$this->stype); 
           unset($mysearchp);
		}	

		return ($resout);
	}
	
	
	function search_google() {
	
        if ( (defined('GOOGLE_DPC')) && (seclevel('GOOGLE_DPC',$this->userLevelID)) ) {
           $mygoogle = new google();
           $res = $mygoogle->search($this->stext); 
		   $resout = $mygoogle->show_search_results2($res);
           unset($mysearchp);
		   
      /*     $url = "http://www.google.com.gr?hl=el&q=$this->text&meta=";
	 
           if (iniload('JAVASCRIPT')) {	
  	         $toprint .= "<A href=\"#\" ";	   
	         //call javascript for opening a new browser win for the img		   
	         $params = $url . ";Google;width=600,height=400;scrollbar=yes";
			 
			 $jj = new jscript;
	         $toprint .= $jj->JS_function("js_openwin",$params); 
			 unset ($jj);
			 
	         $toprint .= ">"; 
	       }
	       else
             $toprint .= "<A href=\"$url\">";
	 
	       $toprint .= "PRESS HERE";
           $toprint .= "</A>";	
		   
		   $resout = $toprint;	*/   
		}		
		return ($resout);	
	}

	function results($view) {

	   if ($this->stext) {

          //start timer
          $t = new ktimer;
		  $t->start('search');
		  
	      switch ($this->sin) {
		  
		  case 'Google' : $out.= $this->search_google();
		            break;
		  
		  case (localize('NEWSRV_DPC',getlocal())) :
		            //search news
                    if ( (defined('NEWSRV_DPC')) && (seclevel('NEWSRV_DPC',$this->userLevelID)) ) {		  
		     
			         $resd = calldpc_method_use_pointers("newsrv.search",array(0=>&$this->stext,1=>&$this->sin,2=>&$this->stype,3=>&$this->scase));
                     $out = calldpc_method("newsrv.shownews");
			         $out .= "<hr>";			 
		            }		  
		            break;
		  default :		  

          //search categories(dirs)
          if ( (defined('DIRECTORY_DPC')) && (seclevel('DIRECTORY_DPC',$this->userLevelID)) ) {
            $categories = new _directory(GetReq('g'));
		    $resd = $categories->search($this->stext,$this->sin);		
		    if ($resd) {
              $findedcategories = new _directory($resd);
		      $out = $findedcategories->render1(3);
  		      unset ($findedcategories);
		    }
			//get some info 
		    $currentdir = $categories->getaliasinfo($this->sin,2);			
		    unset ($categories);
          
		    //get data (cached or not)
			$cacheid = urlencode($this->stext) . "." . $this->sin . "." . $this->scase . "." . $this->stype;
            //$resout = getcache($cacheid,'shr','search_content',$this,$currentdir);
			
	        $ext = 'shr';
	        $thisclass = 'search_content'; 
	        $resout = GetGlobal('controller')->calldpc_method_use_pointers('cache.getcache',  //<<SLOWEST!!!!!!!!!!!???
				                                array(0=>&$cacheid,
									            1=>&$ext,
											    2=>&$thisclass,
												3=>&$this,
												4=>&$currentdir)  
											   );
											   
	 
			//print results
            if ($resout) {
                  $mydbrowser = new browse($resout,0,0,"ARes,BRes");
                  $out .= $mydbrowser->render($view,0,$this,1,0,0,0,1,1,1,1);
                  unset ($mydbrowser);
            }			
		  }  
		  
          //search sen tree
          if ( (defined('SENPTREE_DPC')) && (seclevel('SENPTREE_DPC',$this->userLevelID)) ) {
		  
		    $resd = GetGlobal('controller')->calldpc_method_use_pointers("senptree.search",array(0=>&$this->stext,1=>&$this->sin,2=>&$this->stype,3=>&$this->scase));
            if ($resd) {
			  $a = 3;
			  $b = 0;
			  $c = $resd;
			  $out = GetGlobal('controller')->calldpc_method_use_pointers("senptree.render1_searchresults",array(0=>&$a,1=>&$b,2=>&$c));
			  $out .= "<hr>";
			}
		  }	
			/*
			//$resout = $this->search_sen_content();
		    //get data (cached or not)
			$cacheid = urlencode($this->stext) . "." . $this->sin . "." . $this->scase . "." . $this->stype;
	        $ext = 'shs';			
            //$resout = getcache($cacheid,$ext,'search_sen_content',$this);
			//PROBLEM!!!!
	        $thisclass = 'search_sen_content'; 
	        $resout = calldpc_method_use_pointers('cache.getcache',  //<<SLOWEST!!!!!!!!!!!???
				                                array(0=>&$cacheid,
									            1=>&$ext,
											    2=>&$thisclass,
												3=>&$this)  
											   );
		 }	*/
         if ( (defined('SENPRODUCTS_DPC')) && (seclevel('SENPRODUCTS_DPC',$this->userLevelID)) ) {
			
 	       $resout = GetGlobal('controller')->calldpc_method_use_pointers('senproducts.search',array(0=>&$this->stext,1=>&$this->sin,2=>&$this->stype,3=>&$this->scase)); 
		   //print results
           if ($resout) {
			      $mydbrowser = new browse($resout,0,0,"ARes,BRes");
                  $out .= $mydbrowser->render($view,0,$this,1,0,0,0,1,1,1,1);
                  unset ($mydbrowser);
           }
	  	 }
		 
		 }//switch   			  
		  
         $t->stop('search');
          //$this->stime = sprintf(localize('_TTIME',getlocal()).": %.1f ''\n", $t->value('search')); 	//print $this->stime;	  
	
	     if ($out) {
	       $wintitle = localize('_SEARCHR',getlocal()) . " (" . $this->stext . ") " . $t->value('search');
	       $swin = new window($wintitle,$out);
	       $winout = $swin->render();
	       unset ($swin);
	     } 		  
	   }
	   
	   return ($winout);
	}

    function find($param1,$param2) {

       $terms = explode (" ", $param2, 5); //5 = terms limit
	   //print_r($terms);

		switch ($this->stype) {

	       case $this->anyterms : // OR
								  if ($this->mystrstr_OR($param1,$terms,$this->scase)) return (1);
	                              break;
           case $this->allterms : // AND
								  if ($this->mystrstr_AND($param1,$terms,$this->scase)) return (1); 		
	                              break;
           case $this->asphrase : // AS IS
	       default              : if ($this->mystrstr_ASIS($param1,$param2,$this->scase)) return (1); 							
	                              break;																								
	    }
	}
    
    ////////////////////////////////////////////////////////
    // this return true if all array of terms is in text
    ////////////////////////////////////////////////////////
    function mystrstr_AND($text,$terms,$csence) {

      reset($terms);
      //while (list($word_no, $word) = each($terms)) { 
      foreach ($terms as $word_no => $word) {
        if ($csence) {
	      if ((strstr($text,$word))) continue;
	                            else return 0;
        }
        else {
	      if ((stristr($text,$word))) continue;
	                             else return 0;	
	    }
      }
  
      return 1;
    }

    ////////////////////////////////////////////////////////
    // this return true if one term of terms is in text
    ////////////////////////////////////////////////////////
    function mystrstr_OR($text,$terms,$csence) {
  
      reset($terms);
      //while (list($word_no, $word) = each($terms)) {
      foreach ($terms as $word_no => $word) {	    
  
        if ($csence) {
	      if ((strstr($text,$word))) return 1;
	                            else continue;
        }
        else {
	      if ((stristr($text,$word))) return 1;
	                             else continue;	
	    }
      }
  
      return 0;
    }

    ////////////////////////////////////////////////////////
    // this return true if one terms=text is in text as is
    ////////////////////////////////////////////////////////
    function mystrstr_ASIS($text,$terms,$csence) {
      if ($csence) {
	    if (strstr($text,$terms)) return (1);
      }
      else { 
        if (stristr($text,$terms)) return (1);
      }
  
      return 0;
    }  		


    ///////////////////////////////////////////////////////////////////
    //search form
    ////////////////////////////////////////////////////////////////////
    function form ($entry="",$cmd=null)  {
      $mycmd = $cmd?$cmd:'searchin';
      $filename = seturl("t=$mycmd");//&a=$a&g=$g");     

      //print statistics
	  $out = $this->stime;

      $toprint  = "<FORM action=". $filename . " method=post>";
	  
      $field1[] = $this->t_searchtitle . ":";
	  $attr1[] = "right;50%";	  
      $field1[] = "<INPUT type=\"text\" name=\"terms\" value=\"$entry\" size=25>";
	  $attr1[] = "left;50%";
	  $w1 = new window('',$field1,$attr1);  $toprint .= $w1->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field1);  unset ($attr1); unset ($w1);
	  
      $field2[] = $this->t_sttype . ":";
	  $attr2[] = "right;50%";	  
      if ($this->stype) {
	    switch ($this->stype) {
		  case $this->anyterms   : $field2[] = "<SELECT name=searchtype> <OPTION selected>$this->anyterms<OPTION>$this->allterms<OPTION>$this->asphrase</OPTION></SELECT>"; break;
		  case $this->allterms   : $field2[] = "<SELECT name=searchtype> <OPTION>$this->anyterms<OPTION selected>$this->allterms<OPTION>$this->asphrase</OPTION></SELECT>";break;
		  case $this->asphrase   : $field2[] = "<SELECT name=searchtype> <OPTION>$this->anyterms<OPTION>$this->allterms<OPTION selected>$this->asphrase</OPTION></SELECT>";break;
	    }
	  }
	  else
		  $field2[] = "<SELECT name=searchtype> <OPTION>$this->anyterms<OPTION>$this->allterms<OPTION selected>$this->asphrase</OPTION></SELECT>";
	  $attr2[] = "left;50%";
	  $w2 = new window('',$field2,$attr2);  $toprint .= $w2->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field2);  unset ($attr2); unset ($w2);
		  	      
      //check case sencitive param
      $field3[] = $this->t_casesence . ":";	  
	  $attr3[] = "right;50%";		  
      if ($this->scase) $check = "checked"; else $check = "";
      $field3[] = "<input type=\"checkbox\" name=\"searchcase\" value=\"$check \"". $check . ">";
	  $attr3[] = "left;50%";		  
	  $w3 = new window('',$field3,$attr3);  $toprint .= $w3->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field3);  unset ($attr3); unset ($w3);

      $field4[] = "&nbsp";	  
	  $attr4[] = "right;50%";		  		   
      $field4[] = $this->searchin();
	  $attr4[] = "left;50%";		  
	  $w4 = new window('',$field4,$attr4);  $toprint .= $w4->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field4);  unset ($attr4); unset ($w4);
	  
	  $toprint .= "<input type=\"submit\" name=\"Submit\" value=\"$this->t_searchtitle\">"; 
      $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"$mycmd\">";
      $toprint .= "</FORM>";
	   
	  $data2[] = $toprint; 
  	  $attr2[] = "left";

	  $swin = new window(localize('_SEARCH',getlocal()),$data2,$attr2);
	  $out .= $swin->render("center::100%::0::group_dir_body::left::0::0::");	
	  unset ($swin);

      return ($out);
    }


    //////////////////////////////////////////////////////////
    // view quick search
    // called by front page script
    //////////////////////////////////////////////////////////
    function quickform() {
	    $__USERAGENT = GetGlobal('__USERAGENT');
		
	    switch ($__USERAGENT) {
		  case 'WAP' : break;
		  case 'XML' : 
		  case 'GTK' :
		  case 'XUL' : break; 
		  case 'HTML': 
		  default    :	

        $filename = seturl("t=searchin&a=&g=");    
  
        $out = "<FORM action=" . $filename . " method=post class=\"thin\">";
        $out .= "<P><FONT face=\"Arial, Helvetica, sans-serif\" size=1>";
	    $out .= "<STRONG>" . $this->t_searchtitle . ":</STRONG><br>";
		$out .= "<LABEL for=\"searchquick\" accesskey=\"S\">";
	    $out .= "<INPUT type=\"text\" name=terms size=11 id=\"searchquick\">"; 	
        $out .= $this->submit_button;//"<input type=\"submit\" name=\"Submit\" value=\"Ok\"><br>";   
        $out .="<input type=\"hidden\" name=\"FormAction\" value=\"searchin\">";
		
		//$out .= $this->searchin();
		
        $out .= "<br>" . seturl("t=searchin&a=&g=",$this->t_advsearch); 		 
        $out .= "</FONT></FORM>";	
		break;
	  }		

      return ($out);
    }

	function searchin($staticmenu=0) {
	   
	   $cats = array();
	   $category = GetReq('g');
	
       $out ="<SELECT name=searchindir><OPTION selected>" . $this->all;
	   
       if ( (defined('DIRECTORY_DPC')) && (seclevel('DIRECTORY_DPC',$this->userLevelID)) ) {
	      if ($staticmenu) $mdir = new _directory();
		              else $mdir = new _directory($category);
		  $cats = $mdir->read_dirs();
		  unset ($mdir);
		  
		  if ($cats) {   
		    reset($cats);
            //while (list ($num, $cc) = each ($cats)) {
	        foreach ($cats as $num => $cc) {
			
			  //localization............................
			  if (($clanguage=getlocal())!=$this->deflan)
			    $loc_cc = localize($cc,$clanguage);
			  else  
			    $loc_cc = $cc;				
				
		      $out .= "<OPTION>" . $loc_cc;//summarize(13,$cc);
		    }
	  	  }
	   }
	   
       if ( (defined('SENPTREE_DPC')) && (seclevel('SENPTREE_DPC',$this->userLevelID)) ) {	
	   
	      if ($staticmenu) 	   
	        $cats = GetGlobal('controller')->calldpc_method("senptree.read_sentree");
		  else 	
		    $cats = GetGlobal('controller')->calldpc_method("senptree.read_sentree use ".$category);
		  //print_r($cats); 
		    
		  if ($cats) {   
		    reset($cats);
	        foreach ($cats as $id => $cc) {
			
			  //localization............................
			  if (($clanguage=getlocal())!=$this->deflan)
			    $loc_cc = localize($cc,$clanguage);
			  else  
			    $loc_cc = $cc;
							
		      $out .= "<OPTION>" . $loc_cc;//summarize(13,$cc);
		    }
	  	  }		  
	   }
	   
       if ( (defined('GOOGLE_DPC')) && (seclevel('GOOGLE_DPC',$this->userLevelID)) ) {
	      $out .= "<OPTION>" . "------------";
	      $out .= "<OPTION>" . "Google";
	   }
       if ( (defined('NEWSRV_DPC')) && (seclevel('NEWSRV_DPC',$this->userLevelID)) ) {
	      $out .= "<OPTION>" . localize('NEWSRV_DPC',getlocal());
	   }	   
	   
	   $out .= "</OPTION></SELECT>";	
	   
	   return ($out);
	}
	
    //////////////////////////////////////////////////////////
    // view search bar
    // called by shell
    //////////////////////////////////////////////////////////
    function searchbar($pw='100%',$pal='') {
	    $__USERAGENT = GetGlobal('__USERAGENT');
		
	    switch ($__USERAGENT) {
		  case 'WAP' : break;
		  case 'XML' : 
		  case 'GTK' :
		  case 'XUL' : break; 
		  case 'HTML': 
		  default    : 		

        $filename = seturl("t=searchin&a=&g=");    
  
        $out = "<FORM action=" . $filename . " method=post class=\"thin\">";
        $out .="<P><FONT face=\"Arial, Helvetica, sans-serif\" size=1>";
	    $out .= "<STRONG>" . $this->t_searchtitle . ":</STRONG>";
		$out .= "<LABEL for=\"searchquick\" accesskey=\"S\">";
	    $out .= "<INPUT type=\"text\" name=terms size=15 id='searchquick'>";		  
        $out .="<input type=\"hidden\" name=\"FormAction\" value=\"searchin\">";
		
		$out .= $this->searchin(1);
		
        //$out .= "<input type=\"submit\" name=\"Submit\" value=\"Ok\">"; 		
        $out .= $this->submit_button; 				 
        $out .= "</FONT></FORM>";
		
	    $data[] = $out; 
  	    $attr[] = "right";

	    $swin = new window('',$data,$attr);
	    $ret = $swin->render("$pal::$pw::0::group_searchbar::left::0::0::");	
	    unset ($swin);	
		break;
		
	  }					

      return ($ret);
    }	
	
	
	function browse($packdata,$view='') {
	  
	   $data = explode("||",$packdata); 
	     
	   switch ($view) {
	     case "searchin" : //$out = $this->viewsearch($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9],$data[10]); break;
                           if ( (defined('FILESYSTEM_DPC')) && (seclevel('FILESYSTEM_DPC',$this->userLevelID)) ) 
                             $out = GetGlobal('controller')->calldpc_method_use_pointers('filesystem.view1',$data);
                           if ( (defined('PRODUCTS_DPC')) && (seclevel('PRODUCTS_DPC',$this->userLevelID)) ) {
						     $myview = 'senproducts.view' . GetGlobal('controller')->calldpc_var('products.view'); 
						     $out = calldpc_method_use_pointers($myview,$data);  
						   }	 
						   if ( (defined('SENPRODUCTS_DPC')) && (seclevel('SENPRODUCTS_DPC',$this->userLevelID)) ) {		 	 
						     if ($this->userLevelID)
							   $myview = 'senproducts.view' . GetGlobal('controller')->calldpc_var('senproducts.view');//support cart add..no product link supported!!!
							 else  
						       $myview = 'senproducts.view_search';//simple product view with link
							 //echo ">>>>>",$myview;
						     $out = GetGlobal('controller')->calldpc_method_use_pointers($myview,$data); 
						   }	 
							 
						   //$out = $this->viewsearch($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9],$data[10]); break;		 
						   break;
	     case "ARes"     : $out = $this->viewsearch($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9],$data[10]); break;		 
	     case "BRes"     : $out = $this->viewsearch2($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9],$data[10]); break;		 
	   }
	   return($out);
	}	
	
	
    function viewsearch($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1,$boxtype='') {

	   $gr = urlencode($group);
	   $ar = urlencode($title);	

	   $link = seturl("t=$this->view&a=$ar&g=$gr&p=$page" , $title);

       if (iniload('JAVASCRIPT')) {		
  	         $plink = "<A href=\"#\" ";	   
	         //call javascript for opening a new browser win for the img		   
	         //$params = $photo . ";Image;width=300,height=200;";
             $params = "$photo;280;340;<B>$title</B><br>$descr;";
			 
			 $js = new jscript;
	         $plink .= $js->JS_function("js_popimage",$params); 
			 unset ($js);

	         $plink .= ">"; 
	   }
	   else
             $plink = "<A href=\"$photo\">";

	   $data[] = $chkbox . $plink . "<img src=\"" . $photo . "\" width=\"100\" height=\"75\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">" . "</A>";
	   $attr[] = "left;10%";
	   
	   $data[] = "<B>$title<br></B>" . $descr . "<B>";
	   $attr[] = "left;50%";
	   
	   if ($price>0) {	   
	      $data[] = "$id<br>" .
		            localize('_PRICE',getlocal()) . " :<B>" . str_replace(".",",",$price) . $this->moneysymbol . "</B>" .
				    "<br>" . localize('_BOXTYPE',getlocal()) . " :" . $boxtype;	 	  
	      $attr[] = "center;20%";
		  	
	      //$data[] = dpc_extensions("$id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;",$group,$page);  
	      $data[] = GetGlobal('controller')->calldpc_method("metacache.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;+$group+$page",1) .
	                GetGlobal('controller')->calldpc_method("cart.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;+$group+$page",1) .	   
	                GetGlobal('controller')->calldpc_method("neworder.showsymbol use $id;$title;$path;$template;$group;$page;$descr;$photo;$price;$quant;+$group+$page",1);			  
	      $attr[] = "center;20%";			  		
	   }		  
       else {
	      $data[] = localize('_NOTAVAL',getlocal()); 
	      $attr[] = "center;40%";		  
	   }	   
	   
	   $myarticle = new window('',$data,$attr);
	   $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::") . "<hr>";
	   unset ($data);
	   unset ($attr);

	   return ($out);
	}
	
    function viewsearch2($id,$title,$path,$template,$group,$page=1,$descr='',$photo='',$price=0,$quant=1,$boxtype='') {

	   $data[] = "<img src=\"" . $photo . "\" width=\"100\" height=\"75\" border=\"0\" alt=\"". localize('_IMAGE',getlocal()) . "\">";
	   $attr[] = "left;10%";
	   
	   $data[] = "<B>$title<br></B>" . $descr . "<B>";
	   $attr[] = "left;50%";
	   	   
	   
	   $myarticle = new window('',$data,$attr);
	   $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::") . "<hr>";
	   unset ($data);
	   unset ($attr);

	   return ($out);	
	}	
		
	
	function headtitle() {
	}		

};
}
?>