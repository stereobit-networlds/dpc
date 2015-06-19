<?php

//calldpc_extension('nusoap.nusoap','_NUSOAP_'); loaded at prj file

if (defined("_NUSOAP_")) {

$__DPCSEC['GOOGLE_DPC']='2;1;1;1;1;1;1;1;2';

if ( (!defined("GOOGLE_DPC")) && (seclevel('GOOGLE_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("GOOGLE_DPC",true);

$__DPC['GOOGLE_DPC'] = 'google';

$__EVENTS['GOOGLE_DPC'][0]='google_search';
$__EVENTS['GOOGLE_DPC'][1]='google_spell';
$__EVENTS['GOOGLE_DPC'][2]='google_cache';

$__ACTIONS['GOOGLE_DPC'][0]='google_search';
$__ACTIONS['GOOGLE_DPC'][1]='google_spell';
$__ACTIONS['GOOGLE_DPC'][2]='google_cache';


require_once 'GoogleSearch.lib.php';

class google {
   
      var $GoogleKey;
	  var $maxRes;
	  var $safeSearch;
	  var $result;
	  var $error;

      function google() {
	  
	     $this->GoogleKey = paramload('GOOGLE','key');
	     $this->maxRes = paramload('GOOGLE','maxres');
	     $this->safeSearch = paramload('GOOGLE','safesearch');	
		 
		 $this->error = false;	 		 
	  }
	  
      function event($evn) {
	     $param1 = GetGlobal('param1');
		 $param2 = GetGlobal('param2');
	  
         switch ($evn) {		
          case "google_search"   : $this->search($param1." ".$param2); break;	   
          case "google_spell"    : $this->getspell($param1); break;					 	  
          case "google_cache"    : $this->getcache($param1); break;		  
         }	
	  }	

	  function action($act) {
        $__USERAGENT = GetGlobal('__USERAGENT');	
	
        switch ($act) {	
          case "google_search" : if ($this->error==false)
		                           $out = $this->show_search_results2(); 
								 break;		
          case "google_cache"  : if ($this->error==false)
		                           $out = $this->show_cache(); 
								 break;		
          case "google_spell"  : if ($this->error==false)
		                           $out = $this->show_spell(); 
								 break;									 						   						
        }		
	    
	    return ($out);
	  }	  
	  
	  function search($text2find) {
		 
         $gs = new GoogleSearch();	  
         //set Google licensing key
         $gs->setKey($this->GoogleKey);

         $gs->setQueryString($text2find);	//set query string to search.

         //set few other parameters (optional)
         $gs->setMaxResults($this->maxRes);	//set max. number of results to be returned.
         $gs->setSafeSearch($this->safeSearch);	//set Google "SafeSearch" feature.

         //call search method on GoogleSearch object
         $this->result = $gs->doSearch();

         //check for errors
         if(!$this->result) {
		 
	        if($err = $gs->getError()) {
		      setError("Error: " . $err);
		      //exit("Exiting...");
			  $this->error = true;
	        }
         }
		 
		 return $this->result;
      }
	   
	  function show_search_results($result='') { 

	     if ($result) $this->result = $result;
	     
         //title
         $out = setNavigator("Google Search Results"); 		 

         //output info common to the whole search
         $res = "Document Filtering: " . $this->result->getDocumentFiltering() . "<br>";
         $res .= "Search Comments: " . $this->result->getSearchComments() . "<br>";
         $res .= "EstimatedTotalResultsCount: " . $this->result->getEstimatedTotalResultsCount() . "<br>";
         $res .= "Is Estimate Exact: " . $this->result->getEstimateIsExact() . "<br>";
         $res .= "Search Query: " . $this->result->getSearchQuery() . "<br>";
         $res .= "Start Index: " . $this->result->getStartIndex() . "<br>";
         $res .= "End Index: " . $this->result->getEndIndex() . "<br>";
         $res .= "Search Tips: " . $this->result->getSearchTips() . "<br>";
         $res .= "Search Time: " . $this->result->getSearchTime() . "<br>";


         $res .= "<br><b>Directory Categories: </b>";

         $dcat = $this->result->getDirectoryCategories();
         foreach($dcat as $direlement) {
		 
	       $res .= "<br>Full Viewable Name: " . $direlement->getFullViewableName();
	       $res .= " Special Encoding: " . $direlement->getSpecialEncoding();
         }

         //output individual components of each result
         $res .= "<br><b>Result Elements: </b>";

         $re = $this->result->getResultElements();

         foreach($re as $element) {
		 
	       $res .= "<p>";
	       $res .= "<br>Title: " . $element->getTitle();
	       $res .= " URL: " . $element->getURL();
	       $res .= "<br>Snippet: " . $element->getSnippet();
	       $res .= "<br>Summary: " . $element->getSummary();
	       $res .= "<br>Host Name: " . $element->getHostName();
	       $res .= " Related Information Present?: " . $element->getRelatedInformationPresent();
	       $res .= " Cached Size: " . $element->getCachedSize();
	       $res .= "<br>Directory Title: " . $element->getDirectoryTitle();

	       $dircat = $element->getDirectoryCategory();

           $res .= "<br>Full Viewable Name: " . $dircat->getFullViewableName();
	       $res .= " Special Encoding: " . $dircat->getSpecialEncoding();
        }
		
	    $fwin = new window("Google",$res);
	    $out .= $fwin->render();	
	    unset ($fwin);				
		
		return ($out);
	  }
	  
	  //secont method
	  function show_search_results2() { 

         //title
         $out = setNavigator("Google Search Results"); 		 

         //output info common to the whole search
         $res = "Document Filtering: " . $this->result->getDocumentFiltering() . "<br>";
         $res .= "Search Comments: " . $this->result->getSearchComments() . "<br>";
         $res .= "EstimatedTotalResultsCount: " . $this->result->getEstimatedTotalResultsCount() . "<br>";
         $res .= "Is Estimate Exact: " . $this->result->getEstimateIsExact() . "<br>";
         $res .= "Search Query: " . $this->result->getSearchQuery() . "<br>";
         $res .= "Start Index: " . $this->result->getStartIndex() . "<br>";
         $res .= "End Index: " . $this->result->getEndIndex() . "<br>";
         $res .= "Search Tips: " . $this->result->getSearchTips() . "<br>";
         $res .= "Search Time: " . $this->result->getSearchTime() . "<br>";


         $res .= "<br><b>Directory Categories: </b>";

         $dcat = $this->result->getDirectoryCategories();
		 if (is_array($dcat)) {
           foreach($dcat as $direlement) {
		 
	         $res .= "<br>Full Viewable Name: " . $direlement->getFullViewableName();
	         $res .= " Special Encoding: " . $direlement->getSpecialEncoding();
           }
         }
         //output individual components of each result
         $res .= "<br><b>Result Elements: </b>";
		 
		 $out .=  $res;
		 
         $re = $this->result->getResultElements();
		 if (is_array($re)) {
          foreach($re as $element) {
		 
	       $res = "<p>"; 
	       $res .= "<br>Title: " . $element->getTitle();
	       $res .= " URL: " . $element->getURL();
	       $res .= "<br>Snippet: " . $element->getSnippet();
	       $res .= "<br>Summary: " . $element->getSummary();
	       $res .= "<br>Host Name: " . $element->getHostName();
	       $res .= " Related Information Present?: " . $element->getRelatedInformationPresent();
	       $res .= " Cached Size: " . $element->getCachedSize();
	       $res .= "<br>Directory Title: " . $element->getDirectoryTitle();

	       $dircat = $element->getDirectoryCategory();

           $res .= "<br>Full Viewable Name: " . $dircat->getFullViewableName();
	       $res .= " Special Encoding: " . $dircat->getSpecialEncoding();
		   
		   $gres[] = $res;		   
         }
		}
		$mydbrowser = new browse($gres,"Google Results",1);	   
	    $out .= $mydbrowser->render("google_search",$this->maxres,$this,1,0,0,0); 
  	    unset ($mydbrowser);				
		
		return ($out);
	  }	  
	  
	  
	  
	  function getcache($url) {

        $gs = new GoogleSearch();

        //set Google licensing key
        $gs->setKey($this->GoogleKey);

        $this->result = $gs->doGetCachedPage($url);

        if(!$this->result) {
	      if($err = $gs->getError()) {
		      setError("Error: " . $err);
			  $this->error = true;
	      }
        }	  
	  }
	  
	  function show_cache() {
	    
		return ($this->result);
	  }
	  
	  
	  function getspell($spelltext) {
	  
        $gs = new GoogleSearch();

        //set Google licensing key
        $gs->setKey($this->GoogleKey);

        $gs->setSpellingSuggestionPhrase($spelltext);

        //perform spelling suggestion
        $this->result = $gs->doSpellingSuggestion();

        //check for errors
        if (!$this->result) {
		
	      if (!$err = $gs->getError())	{		  
		      $this->result = "No spelling suggestions necessary.";
	      }
	      else {
		      setError("Error: " . $err);
			  $this->error = true;		  
	      }
        }	  
	  }
	  
	  function show_spell() {
	  
		return ($this->result);	  
	  }
	  
	  
	  
	  function browse($packdata,$view='') {
	  
	   $data = explode("||",$packdata);
	 
	   //switch ($view) {
	     //case "google" : 
		 $out = $this->viewgooglelist($packdata); //break;		 
	   //}
	   return($out);
	  }	  
	  
      function viewgooglelist($mydata) {
	

       /*if (iniload('JAVASCRIPT')) {		
  	         $plink = "<A href=\"#\" ";	   
	         //call javascript for opening a new browser win for the img		   
	         $params = $photo . ";Image;width=300,height=200;";

			 $js = new jscript;
	         $plink .= $js->JS_function("js_openwin",$params); 
			 unset ($js);

	         $plink .= ">"; 
	   }
	   else
             $plink = "<A href=\"$photo\">";*/

	   $data[] = $mydata;
	   $attr[] = "left;100%";   
	   
	   $myarticle = new window('',$data,$attr);
	   $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::") . "<hr>";
	   unset ($data);
	   unset ($attr);

	   return ($out);
	  }	  
	  
	  
	  function headtitle() {
	  }	  

}
}
}
else die("NUSOAP DPC REQUIRED!");
?>