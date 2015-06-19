<?php

//$__DPCSEC['SEARCHTOPIC_']='2;1;1;1;2;2;2;2;9';

if (!defined("BROWSERSQL_DPC")) {
define("BROWSERSQL_DPC",true);

$__DPC['BROWSERSQL_DPC'] = 'browseSQL';

//$__EVENTS['BROWSERSQL_DPC'][0]='searchsql'; //OUT or error!!! (no need)

$__ACTIONS['BROWSERSQL_DPC'][0]='searchsql';

$__DPCATTR['BROWSERSQL_DPC']['searchsql'] = 'searchsql,0,0,0,0,0,1,0'; 

//$__LOCALE['BROWSERSQL_DPC'][0]='';
$__LOCALE['BROWSESQL_DPC'][0]='_SEARCH;Search;Αναζήτηση';

//require_once("browser.lib.php");
//GetGlobal('controller')->include_dpc('libs/browser.lib.php');
require_once(GetGlobal('controller')->require_dpc('libs/browser.lib.php'));

class browseSQL extends browse {

    var $pagedata2render;
	var $searchsqltext;
	var $selpage;

	function browseSQL($title='',$selpage=1,$styler="",$columns="") {
 	
	    browse::browse("",$title,$selpage,$styler,$columns);	
		
		$this->pagedata2render = null;
	    $this->searchsqltext = trim(GetParam("searchsqltable"));	
		$this->selpage = 1;		 
    }
	
    function event($sAction=null) {
	
       switch($sAction) {
          case "searchsql" : $this->dosearch = 1; break; //has not effect
       }
	}	
	
	function action($action=null) {
	}	
	
	//overwtitten
	function render(&$db,$sSQL,$stable,$viewtype=0,$pager,$class,$pagemaker=0,$topic=0,$sorter=0,$adminform=0) {
        $db = GetGlobal('db');	
		$p = GetReq('p');
		$a = GetReq('a');
		$sort = GetReq('sort');
		$col = GetReq('col');						
		  
		//get select query fields
		$self = explode(" ",$sSQL);
		$selfields = $self[1]; //echo $selfields; //get comma separated fields string
		$tabf = explode(",",$selfields);
        //print_r($tabf); //get table fields 
	
	    if ($col) {
		  $sSQL .= " ORDER BY " . $tabf[$col];
		  if ($sort==-1) $sSQL .= " DESC"; //not work!
		}  
		//echo $sSQL;	
	
	    //SEARCH SQL TABLE
		if ($this->searchsqltext) {
	      $record2find = $this->getpage($this->searchsqltext,$tabf,$stable,$db);	
		  $this->selpage = floor(($record2find+1) / $pager)+1;
		  //echo $this->selpage , ">>>>";	
		  
		  $a = $this->searchsqltext; //browsing index purpose 
		  SetReq('a',$this->searchsqltext); 
		}
		else //GET PAGE 
		  if ($p) $this->selpage = $p; else $p=1;	 
	
	  
	    //$sqldata = $db->PageExecute($sSQL,$pager,$this->selpage,null,null); 
		$sqldata = $db->Execute($sSQL,2);
		print_r($sqldata);//->fields);
		
		if ($sqldata) {
	     if ($db->model=='ADODB') {		
		 
		  $this->page =  $sqldata->_lastPageNo;
		  $maxfield = count($sqldata->fields);//  /2;//CHANGED<<<<<<<<<<<<< over limit of clist because of rec name + id meter =*2
		  //echo ">>>>>>>>>>>>>",$maxfield;		 
          $i=1;
          //while(!$sqldata->EOF) {
		  foreach ($sqldata as $n=>$rec) {
		  
		    $rectable = null;
		    for ($x=0;$x<$maxfield;$x++) {
		       //$rectable .= $sqldata->fields[$x];
			   $rectable .= $rec[$x];
			   if ($x<$maxfield-1) $rectable .= "||";
			}   
			  
            $this->pagedata2render[] = $rectable; 

            //print_r($sqldata->fields);
	        //$sqldata->MoveNext();
		    $i+=1;
 	      }		
		  //print_r($this->pagedata2render);
		 }
		 else {//sqlite
          $this->page =  1;//$sqldata->_lastPageNo;		 
		  
          /*while ($record = $sqldata->fetch_array()) {	

			  $maxf = count($record);
		      $rectable = null;
		      for ($x=0;$x<$maxf;$x++) {
		        $rectable .= $record[$x];
			    if ($x<$maxf-1) $rectable .= "||";
			  }   
			  
              $this->pagedata2render[] = $rectable; 
		  }*/
		  //upgdate to mysql 
		  foreach ($sqldata as $n=>$rec) {
		  
		    $rectable = null;
		    for ($x=0;$x<$maxf;$x++) {
		       //$rectable .= $sqldata->fields[$x];
			   $rectable .= $rec[$x];
			  if ($x<$maxf-1) $rectable .= "||";
			  }   
			  
            $this->pagedata2render[] = $rectable; 

            //print_r($sqldata->fields);
	        //$sqldata->MoveNext();
		    $i+=1;
 	      }		  
		 }
         $out = $this->browse_files($viewtype,$class,$pagemaker,$topic,$sorter,$adminform);			
		}
		//else
		  //$out = 'Empty';
		return ($out);
	}
	
	//overwtitten	
    function browse_files($view,$class,$pagemaker,$topic,$sorter,$adminform) { 
		$p = GetReq('p');
		$g = GetReq('g');
		$t = GetReq('t');
		
        if (($adminform) && (seclevel('ADMBROWSE_',$this->userLevelID))) $out = $this->admin_start();		

        if ($this->pagedata2render) {

			 //HEAD
	         switch ($this->agent) {
			 
		   case 'CLI'  :
		   case 'TEXT' : $out = str_replace(",","|",$this->columns) . "\n";
		                 foreach ($this->pagedata2render as $pack_num => $packdata)
					       $out .= str_replace("||","|",$packdata) . "\n";
		                 break;
	       case 'XML'  : 
           case 'XUL'  :
		   case 'GTK'  : $xml = new pxml('XUL');
		                 $xml->addtag('GTKLIST','XUL',null,"columns=$this->columns|autoresize=true");
					     foreach ($this->pagedata2render as $pack_num => $packdata)
					       $xml->addtag('GTKLISTITEM','GTKLIST',str_replace("||",";",$packdata),"id=$pack_num");
					     $out = $xml->getxml();
					     unset($xml);	
		                 break;
		   case 'HTML' :
           default     : //INVOLVE CLASS METHODS headtitle() & browse() 	!!!			
                         // view styles and sort method line
                         if (count($this->stylearray)>1)  $vs = $this->viewStyles();
			             if ($sorter) $sv = $this->sortStyles();				  

	                     $data[] = $vs . $sv; 
	                     $attr[] = "right;50%;";
                         if ($data)  { 
		                   $win1 = new window('',$data,$attr);
		                   $winout .= $win1->render("center::100%::0::group_form_headtitle::left::0::0::");
		                   unset ($win1);
		                 }
			  
                         //print title head			 
                         if (method_exists($class,'headtitle')) $winout .= $class->headtitle($view); 
		
		                 if (method_exists($class,'browse')) {
	                       foreach ($this->pagedata2render as $pack_num => $packdata) {	
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
			}//switch
						 		 			 
	    } //if exist
        else { 
           $out = ''; //setTitle(localize('_EMPTYDIR',getlocal()));//"&nbsp";
        }
        if (($adminform) && (seclevel('ADMBROWSE_',$this->userLevelID))) $out .= $this->admin_end();
						
		//search topic				
 	    if (($topic) && (seclevel('SEARCHTOPIC_',$this->userLevelID))) $out .= $this->searchTopic();
		
		//view page browser
	    if ($pagemaker) $out .= $this->pageBrowser($view);	
				 			 
		
        return ($out);
    }

	
    ///////////////////////////////////////////////////////////
    //view page browser
    ///////////////////////////////////////////////////////////
    function pageBrowser($view) {
		$p = GetReq('p');
		$a = GetReq('a');
		$g = GetReq('g');
		$sort = GetReq('sort');
		$col = GetReq('col');			
		
		$gr = urlencode($g);		
		$ar = urlencode($a);		

		$grouppager = 2;
        $ptext = localize('_PAGE',getlocal()) . " :";

        if ($this->page>1) {

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
          $data .= seturl("t=$view&a=$ar&g=$gr&p=1&sort=$sort&col=$col", $this->start_b) . "&nbsp;";
		  if ($prevpage>0) $data .= seturl("t=$view&a=$ar&g=$gr&p=$prevpage&sort=$sort&col=$col", $this->prev_b);
		              else $data .= $this->prev_b;

          $data .= $markstart;
		  $data .= " " . $this->outpoint . " ";

		  for ($i=$groupprev; $i<$groupnext; $i++) {
             $pp = $i+1;
             if ($pp==$p) {
			     $data .= seturl("t=$view&a=$ar&g=$gr&p=$pp&sort=$sort&col=$col","<B>" . $pp . "</B>") . "&nbsp;" . $this->outpoint . "&nbsp;";
		     }
		     else {
                 $data .= seturl("t=$view&a=$ar&g=$gr&p=$pp&sort=$sort&col=$col",$pp) . "&nbsp;" . $this->outpoint . "&nbsp;";			 
		     }
          }
		  $data .= $markend;
 
          //next buttons
		  $nextpage = $p+1;
		  if ($nextpage<=$this->page) $data .= seturl("t=$view&a=$ar&g=$gr&p=$nextpage&sort=$sort&col=$col" , $this->next_b);	
		                           else $data .= $this->next_b; 
          $data .= "&nbsp;" . seturl("t=$view&a=$ar&g=$gr&p=" . ($this->page) . "&sort=$sort&col=$col"  , $this->end_b );

          //buttons browser
		  $mydata[] = $data;
		  $myattr[] = "left;70%;";

          //page number
		  $mydata[] = "$ptext $p / " . ($this->page);
		  $myattr[] = "right;30%;";
		
		  $winb = new window('',$mydata,$myattr);
		  $out = $winb->render("center::100%::0::group_dir_title::right::0::0::");
		  unset ($winb);
        }

		return ($out);
    }

	function sortStyles() {
		$p = GetReq('p');
		$a = GetReq('a');
		$g = GetReq('g');
		$t = GetReq('t');			
		
		$gr = urlencode($g);		
		$ar = urlencode($a);		

        //$vprint .= localize('_SORT',getlocal()) . " :";

		//$t = $this->viewstyle;
        if ($this->sortmethod==1) $vprint .= seturl("t=$t&a=$ar&g=$gr&p=$p&sort=1", "<B>A</B>"); 
                             else $vprint .= seturl("t=$t&a=$ar&g=$gr&p=$p&sort=1","A"); 
        if ($this->sortmethod==-1) $vprint .= seturl("t=$t&a=$ar&g=$gr&p=$p&sort=-1", "<B>Z</B>"); 
    	                      else $vprint .= seturl("t=$t&a=$ar&g=$gr&p=$p&sort=-1","Z"); 			  
        return ($vprint);
	}

	/*function sort_files() {
		global $sort;
	
        //sorting (article=selected sort method, saved as session param)
        //first sort by saved method (if saved)
        switch ($this->sortmethod) {
	       case "1"  : ksort ($this->pagedata2render,SORT_REGULAR); break; //asc
	       case "-1" : krsort ($this->pagedata2render,SORT_REGULAR); break; //desc
           default : ksort ($this->pagedata2render); //asc
        }					
        //secont sort by selected method (if selected) and save sort
        switch ($sort) {
	       case 1  : ksort ($this->pagedata2render,SORT_STRING); SetSessionParam("sort", "1"); break; //asc
	       case -1 : krsort ($this->pagedata2render,SORT_STRING); SetSessionParam("sort", "-1"); break; //desc
           default : ksort ($this->pagedata2render); //asc		   
        }  
   		$this->sortmethod = GetSessionParam('sort');

		//print_r($this->dfiles);
	}*/
 
    function searchTopic()  {
	  $g = GetReq('p');
	  $t = GetReq('a');		  
	    
	  $gr = urlencode($g); 

      $filename = seturl("t=$t&a=&g=$gr");      

      $toprint  = "<FORM action=". $filename . " method=post class=\"thin\">";
      $toprint .= "<P><FONT face=\"Arial, Helvetica, sans-serif\" size=1><STRONG>";
      $toprint .= localize('_SEARCH',getlocal()) . ":";
	  $toprint .= "</STRONG> <INPUT name=searchsqltable size=15></FONT>";
      $toprint .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>";

      $toprint .= "<input type=\"submit\" name=\"Submit\" value=\"Ok\">"; 
      $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"searchsql\">";
      $toprint .= "</FONT></FORM>";
	   
	  $data2[] = $toprint; 
  	  $attr2[] = "left";

      $swin = new window('',$data2,$attr2);
	  $out .= $swin->render("center::100%::0::group_dir_body::left::0::0::");	
	  unset ($swin);

      return ($out);
    }	
	
	function getpage($stext,$tablefields,$stable,&$dbase){
	  $a = GetReq('a');
	
      //print_r($array);
	  if ($stext) {
	     $i=0;
		 
		 $searchSQL = "SELECT " . $tablefields[0] . " FROM " . $stable .  
		              " WHERE (";
					  
		 $maxfield = (count($tablefields)-1); 
		 for ($x=0;$x<=($maxfield-1);$x++)
		     $searchSQL .= $tablefields[$x] . " LIKE " . $dbase->qstr("%".$stext."%") . " OR ";					     
			 
	     $searchSQL .= $tablefields[$maxfield] . " LIKE " . $dbase->qstr("%".$stext."%") . ")";
		 
		 //echo $searchSQL;
		 
	     $mysqldata = $dbase->Execute($searchSQL);
		 //print $mysqldata->fields[$tablefields[0]];
		 
		 if ($mysqldata) return ($mysqldata->fields[$tablefields[0]]);		 
		   
	   }	 
	   return 1;
	}	
	

   
};
}
?>