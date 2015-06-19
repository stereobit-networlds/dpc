<?php

$__DPCSEC['SHNSEARCH_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("SHNSEARCH_DPC")) && (seclevel('SHNSEARCH_DPC',decode(GetSessionParam('UserSecID')))) ) {

define("SHNSEARCH_DPC",true);

$__DPC['SHNSEARCH_DPC'] = 'shnsearch';

$d = GetGlobal('controller')->require_dpc('shop/shsearch.dpc.php');
require_once($d);

$e = GetGlobal('controller')->require_dpc('nitobi/nitobi.lib.php');
require_once($e);

GetGlobal('controller')->get_parent('SHSEARCH_DPC','SHNSEARCH_DPC');

$__EVENTS['SHNSEARCH_DPC'][4]='shnearch';

$__ACTIONS['SHSEARCH_DPC'][4]='shnearch';

$__LOCALE['SHNSEARCH_DPC'][0]='SHNSEARCH_DPC;Search;Αναζήτηση';
$__LOCALE['SHNSEARCH_DPC'][1]='_SEARCHIN;Search In:;Αναζήτηση σε:';
$__LOCALE['SHNSEARCH_DPC'][2]='_founded;items found;εγγραφές βρέθηκαν';

class shnsearch extends shsearch {

    var $_combo, $text2find;
	var $path, $imageclick;
	var $textsearch, $searchpath, $searchfiletypes;
	var $attachsearch;

	function shnsearch() {

	  shsearch::shsearch();

      $this->title = localize('SHNSEARCH_DPC',getlocal());
	  $this->path = paramload('SHELL','prpath');
	  $this->urlpath = paramload('SHELL','urlpath');
	  $this->inpath = paramload('ID','hostinpath');			  
	  
	  //$this->_combo[0] = new nitobi("SubscribersList");
      //$this->nitobi_javascript();	
	  $this->imageclick = remote_paramload('SHNSEARCH','imageclick',$this->path);	

	  $this->textsearch = remote_paramload('SHNSEARCH','textsearch',$this->path);	  
	  $this->searchpath = remote_paramload('SHNSEARCH','searchpath',$this->path);	
	  $this->attachsearch = remote_paramload('SHNSEARCH','attachsearch',$this->path);		  
	  $ft = remote_arrayload('SHNSEARCH','filetypes',$this->path);	
	  $fp = array('.htm','.txt');//htm includes html
	  $this->searchfiletypes = $ft?$ft:$fp;  
	}

	function event($event=null) {

	  $this->text2find = GetParam('Input')?GetParam('Input'):GetReq('input'); 
	//echo $this->text2find,'>';
	  switch ($event) {

		//cart
	     case 'searchtopic'   :
	     case 'addtocart'     :
		 case 'removefromcart':		break;
		// 	  
	  
	    case 'search' :	  
		default : $this->search_javascript();
		
		          if ($this->text2find)//always here
		            $this->do_quick_search($this->text2find,GetReq('cat'));
		          else
		            $this->do_search();//not used??.....
	  } 
	}


	function action($action=null) {
	
	  switch ($action) {
	  
		//cart
	     case 'searchtopic'   :
	     case 'addtocart'     :
		 case 'removefromcart':	break;	
		// 	  
	  
	    case 'search' :		
		default       : $out = $this->form_search();
	  }	
	  
	  return ($out);
	}
	
	function search_javascript() {
      if (iniload('JAVASCRIPT')) {	
	  
	       $code2 = $this->js_make_search_url();	
		   $js = new jscript;	
           $js->load_js($code2,"",1);			   
		   unset ($js);
	  }			   	   	  
		   
	}

	function nitobi_javascript() {

      if (iniload('JAVASCRIPT')) {

		   //$template = $this->set_template();   		      

	       $code = $this->init_combo();					   	

		   //$code .= $this->_grids[0]->OnClick(22,'QueueDetails',$template,'Vehicles','p_id',0);

		   $js = new jscript;

		   //$js->setloadparams("init()"); //added in html

           //$js->load_js('nitobi.grid.js');		   	   

           $js->load_js('nitobi.toolkit.js');				   		   
           $js->load_js('nitobi.combo.js');		   		   
           $js->load_js($code,"",1);			   			   

		   unset ($js);
	  }		
	}	


	function init_combo() {

        //disable alert !!!!!!!!!!!!		

		$out = "

function alert() {}\r\n 


function init()

{

";

	   if (!empty($this->_combo)) {	  
        foreach ($this->_combo as $n1=>$g1) {
		  if (is_object($g1))
		    $out .= $g1->init_combo($n1);		 
		}
	   }	   
       $out .= "\r\n}";

       return ($out);
	}		

	

	function show_combo($title=null,$preselcat=null,$isleaf=null) {
       //NITOBI

	   /* $this->_combo[0]->set_combo_column_img('images/b_go.gif',16,1);	
	    $this->_combo[0]->set_combo_column('color',170,0);
	    $this->_combo[0]->set_combo_column('',200,2);


	    //STATIC MODE

	    $file = paramload('SHELL','prpath') . "colors.opt";
	    $names = array('color','image','email');	 
	    $data = explode(",",file_get_contents($file));	

	    foreach ($data as $id=>$rec) {
	     $mydata[] = array(trim($rec),'images/b_go.gif','xxx');
	    }

	    $this->_combo[0]->set_combo_data($mydata,implode("|",$names));					

	    //$ret = $this->_combo[0]->set_combo();

        $ret .= $this->_combo[0]->set_combo("175","360","300","",'unbound');	*/
		
		
		//INTERNAL
		if (defined("SHKATEGORIES_DPC"))//sql based cats			
          $ret = GetGlobal('controller')->calldpc_method('shkategories.show_combo_results use '.$title.'+'.$preselcat.'+'.$isleaf);
			 
		
		//$ret .='zzzz';		

		return ($ret);

	}		
	
	//override
	function do_quick_search($text2find,$comboselection=null) {
	
		  if (defined("SHKATALOGMEDIA_DPC")) {
		      GetGlobal('controller')->calldpc_method('shkatalogmedia.do_quick_search use '.$text2find.'+'.$comboselection);
	      }		
          elseif (defined("SHKATALOG_DPC"))
              GetGlobal('controller')->calldpc_method('shkatalog.do_quick_search use '.$text2find.'+'.$comboselection);
			  
	}
	
	//override
	function search_categories($text2find=null,$template=null) {
	
		  if (defined("SHCATEGORIES_DPC")) {//text based cats
		      //search&searchtype=$this->asphrase&input=".$text2find.
		      $ret = GetGlobal('controller')->calldpc_method('shcategories.search_tree use '.$text2find."+klist+".$template);//klist or search in cat, =+search		
	      }		
          elseif (defined("SHKATEGORIES_DPC"))//sql based cats		
		      //search&searchtype=$this->asphrase&input=".$text2find.	
              $ret = GetGlobal('controller')->calldpc_method('shkategories.search_tree use ' . $text2find ."+klist+".$template);//klist or seach in cat,= +search
			  
		  return ($ret);	  
	}		
	
	//override
	function list_catalog($imageclick=null,$cmd=null,$template=null) {
	
		  if (defined("SHKATALOGMEDIA_DPC")) {
		      $ret = GetGlobal('controller')->calldpc_method('shkatalogmedia.list_katalog use '.$imageclick.'+'.$cmd.'+'.$template.'++1');
	      }		
          elseif (defined("SHKATALOG_DPC"))
              $ret = GetGlobal('controller')->calldpc_method('shkatalog.list_katalog use '.$imageclick.'+'.$cmd .'+'.$template . '++1');
			  
		  return ($ret);	  
	}		
	
	//override
    function form ($entry="",$cmd=null,$message=null)  {
	  $entry = GetParam('input');
	  $this->scase = GetParam('searchcase')?true:false;
	  $this->stype = GetParam('searchtype')?GetParam('searchtype'):null;
	  
      $mycmd = $cmd?$cmd:'search';
      $filename = seturl("t=$mycmd");//&a=$a&g=$g");  
	  $lan = getlocal()?getlocal():'0';
	  
	  
      //template form
	  $template_file='searchform.htm';	   
	  $tfile = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',$lan.'.',$template_file) ; 	

      //in thios case mytemplate disbled
	  //echo $tfile;
      if (is_readable($tfile)) {
		 $contents = file_get_contents($tfile);	   
	     $template = 1;	     
	  }	 
	        

      //print statistics
	  if ($template) 
	    $tokens[] = $this->stime . $message;
	  else		  
	    $out = $this->stime . $message;

	  if ($template) { 
	    $tokens[] = "<FORM name='searchform' action=". $filename . " method=POST>" . //post 
		            "<INPUT type=\"text\" name=\"input\" value=\"$entry\" size=25>"; 
					
					
        if ($this->stype) {
	      switch ($this->stype) {
		    case $this->anyterms   : $tokens[] = "<SELECT name=searchtype> <OPTION selected>$this->anyterms<OPTION>$this->allterms<OPTION>$this->asphrase</OPTION></SELECT>"; break;
		    case $this->allterms   : $tokens[] = "<SELECT name=searchtype> <OPTION>$this->anyterms<OPTION selected>$this->allterms<OPTION>$this->asphrase</OPTION></SELECT>";break;
		    case $this->asphrase   : 
			default                : $tokens[] = "<SELECT name=searchtype> <OPTION>$this->anyterms<OPTION>$this->allterms<OPTION selected>$this->asphrase</OPTION></SELECT>";break;
	      }
	    }
	    else {
		   //or//as a phrace
		   $tokens[] = "<SELECT name=searchtype> <OPTION selected>$this->anyterms<OPTION>$this->allterms<OPTION>$this->asphrase</OPTION></SELECT>";					
		}   
		   
        if ($this->scase) $check = "checked"; else $check = "";
        $tokens[] = "<input type=\"checkbox\" name=\"searchcase\" value=\"$check \"". $check . ">";		   
		
		$tokens[] = "<input type=\"submit\" name=\"Submit\" value=\"$this->t_searchtitle\">" .
                    "<input type=\"hidden\" name=\"FormAction\" value=\"$mycmd\">" .
                    "</FORM>";		
		
		//search in cat form			
        $tokens[] = $this->searchin();							
	  }	
	  else {			
        $toprint  = "<FORM name='searchform' action=". $filename . " method=POST>";//post
	  
        $field1[] = $this->t_searchtitle . ":";
	    $attr1[] = "right;50%";	  
        $field1[] = "<INPUT type=\"text\" name=\"input\" value=\"$entry\" size=25>";
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

       /* $field4[] = "&nbsp";	  
	    $attr4[] = "right;50%";		  		   
        $field4[] = $this->searchin();
	    $attr4[] = "left;50%";		  
	    $w4 = new window('',$field4,$attr4);  $toprint .= $w4->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field4);  unset ($attr4); unset ($w4);	 		
	  */
	    $toprint .= "<input type=\"submit\" name=\"Submit\" value=\"$this->t_searchtitle\">"; 
        $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"$mycmd\">";
        $toprint .= "</FORM>";
	   
	    $data2[] = $toprint; 
  	    $attr2[] = "left";

	    $swin = new window(localize('_SEARCH',getlocal()),$data2,$attr2);
	    $out .= $swin->render("center::100%::0::group_dir_body::left::0::0::");	
	    unset ($swin);
		
		//2nd form search in cats
        $catform[] = localize('_SEARCHIN',getlocal());//"&nbsp";	  
	    $attrform[] = "right;50%";		  		   
        $catform[] = $this->searchin();
	    $attrform[] = "left;50%";		  
	    $cform = new window(localize('_SEARCHIN',getlocal()),$catform,$attrform);  
		$out .= $cform->render("center::100%::0::group_dir_body::left::0::0::");   
		unset ($cform);  	 		
	  		
	  }	

	  if ($template) {	 		 	      
		$tokout = $this->combine_tokens($contents,$tokens);
		return ($tokout);    
	  }
	  else 		  
        return ($out);
    }
	
	//override
	function form_search() {
	   $typos = trim(GetParam('typos'));	
	   $extras = trim(GetParam('extras'));
	   $price = trim(GetParam('price'));
	   $price2 = trim(GetParam('price2'));	   

	   //else	 
		 //$msg = "&nbsp;(" . '0' .'&nbsp;'. localize('_founded',getlocal()) . ")";
		  
       //$out =  setNavigator($this->title . $msg);	
	   
	   //KATEGORIES SEARCH
	   $out .= $this->search_categories($this->text2find,'searchcatres.htm');
	   
	   $this->msg = null;//reset not to re-show in list_vehicles functions
	   //$out .= $this->msg;
	   
	   $myimageclick = $this->imageclick?$this->imageclick:null;

		 	   
	   //$out .= $this->list_katalog_table(2,null,null,0,1);
	   $out .= $this->list_catalog($myimageclick,'search&input='.$this->text2find,'searchres.htm');
	   
       //if ($this->meter) 
	     //$out .= "<hr>";
	   
	   if (defined('SHKATALOGMEDIA_DPC'))	 
	     $f1 = GetGlobal('controller')->calldpc_var('shkatalogmedia.max_selection');	   
	   elseif (defined('SHKATALOG_DPC'))
	     $f1 = GetGlobal('controller')->calldpc_var('shkatalog.max_selection');
		 
	   if (defined('SHKATEGORIES_DPC'))	 
	     $f2 = GetGlobal('controller')->calldpc_var('shkategories.max_selection');	   
	   elseif (defined('SHCATEGORIES_DPC'))
	     $f2 = GetGlobal('controller')->calldpc_var('shcategories.max_selection');		
		 
	   $f = $f1+$f2;	  
	   	
	   if ($f)	 	   
	     $msg = "&nbsp;(" . $f .'&nbsp;'. localize('_founded',getlocal()) . ")";
		 	   
	   $out .= $this->form(null,'search',$msg);
	

	   
	   return ($out);	
	}	
	
	/*
	function search_categories_js() {
	
	   $a = 'document.searchform.input.value';//'this.form.input.value';
	   $b = 'this.form.searchtype.value';
	   $c = 'this.form.searchcase.value';
	   $d = 'this.form.input.value';	   	   	   
	
	   $ret = " onChange=\"location=this.options[this.selectedIndex].value+'&input=$a&searchtype=$b&searchcase=$c'\"";    
	   
	   return ($ret);
	}	*/
	
	function js_make_search_url() {
		$out = "
function get_sinput()
{
  var ret = document.searchform.input.value;
  return ret;
}
function get_scase()
{
  var ret = document.searchform.searchcase.value;
  return ret;
}
function get_stype()
{
  var ret = document.searchform.searchtype.value;
  return ret;
}
";

      return ($out);	
	}
	
	//override
	function searchin() {
	
		//INTERNAL
		if (defined("SHKATEGORIES_DPC")) {//sql based cats			
          $ret = GetGlobal('controller')->calldpc_method('shkategories.show_combo_results use '.$title.'+'.$preselcat.'+'.$isleaf.'+search');
	    }		
		
		return ($ret);
	}	
	
	//fieldstosearchin = array of db field names..(csv)
    function findsql($terms,$fields2searchin,$appendsql=null,$stype=null,$scase=null) {
	  $st = $stype?$stype:$this->stype;
	  $sc = $scase?$scase:$this->scase;
	  $extra_sql = null;

	  if ($appendsql)//and /or
		 $ret = ' ' . $appendsql . ' ';	
		 
      $fields = explode ("<@>", $fields2searchin); 		 
		 
	  if (!empty($fields)) {	
	  

		   $extra_codes = (array) $this->search_additional_files($terms);
		   //print_r($extra_codes);
		   //echo count($extra_codes),"<br>";
		   
		   $extra2_codes = (array) $this->search_attachments($terms);
		   //print_r($extra2_codes);
		   //echo count($extra2_codes),"<br>";		
		   
		   if ((!empty($extra_codes)) || (!empty($extra2_codes)))
		     $extra_codes = array_merge($extra_codes,$extra2_codes);   
		   //echo count($extra_codes),"<br>";		   
		   
		   if (!empty($extra_codes)) {
		     foreach ($extra_codes as $i=>$c) {
		       $codesql[] = ' code3="'.$c.'"';
		     }		   
		     $extra_sql = ' or (';
			 $extra_sql .= implode (' or ',$codesql); 
		     $extra_sql .= ') ';
		   }
   
		  
		 switch ($st) {

           case $this->allterms : // AND
								  $ret .= $this->mysql_AND($terms,$fields,$sc); 		
	                              break;
           case $this->asphrase : // AS IS
                                  $ret .= $this->mysql_ASIS($terms,$fields,$sc); 							
	                              break;																														  
	       case $this->anyterms : // OR
	       default              :		   
								  $ret .= $this->mysql_OR($terms,$fields,$sc);
	                              break;								  
	     }
		 
		 if ($extra_sql) {
		   //echo $ret,$extra_sql;	
		   $ret .= $extra_sql;
		 }  	
		 return ($ret);
	   }//empty array
	   else
	     return null;	
	}
    
    ////////////////////////////////////////////////////////
    // this return true if all array of terms is in text
    ////////////////////////////////////////////////////////
    function mysql_AND($terms,$fields,$csence) {
	
      $words = explode (" ", $terms);	
      reset($fields);	  
	  
	  foreach ($fields as $fid=>$fieldname) {
	    reset($words);
        foreach ($words as $word_no => $word) {		
          if ($csence) 			  
		    $data[$word_no] = $fieldname . " like '%" . $word . "%'"; 
		  else
		    $data[$word_no] = '(' . $fieldname . " like '%" . strtolower($word) . "%' OR " . $fieldname . " like '%" . strtoupper($word) . "%')"; 	
		}
		
		$data2[$fid] = '(' . implode(' AND ',$data) . ')';
      }
	  
      $ret = '(' . implode(' OR ',$data2) . ')';
	  
	  //echo $ret;
      return $ret;
    }

    ////////////////////////////////////////////////////////
    // this return true if one term of terms is in text
    ////////////////////////////////////////////////////////
    function mysql_OR($terms,$fields,$csence) {
	
      $words = explode (" ", $terms);	
	  reset($words);  

      foreach ($words as $word_no => $word) {
        reset($fields);	  
	    foreach ($fields as $fid=>$fieldname) {
          if ($csence) 		  
		    $data[$fid] = $fieldname . " like '%" . $word . "%'";
		  else	
		    $data[$fid] = '(' . $fieldname . " like '%" . strtolower($word) . "%' OR " . $fieldname . " like '%" . strtoupper($word) . "%')";
		}
		
		$data2[$word_no] = '(' . implode(' OR ',$data) . ')';	
      }
  
      $ret = '(' . implode(' OR ',$data2) . ')';
	  //echo $ret;	    
      return ($ret);
    }

    ////////////////////////////////////////////////////////
    // this return true if one terms=text is in text as is
    ////////////////////////////////////////////////////////
    function mysql_ASIS($terms,$fields,$csence) {

	  foreach ($fields as $fid=>$fieldname) {
		  
        if ($csence)  
		  $data[] = $fieldname . " like %'" . $terms . "%'";
		else
		  $data[] = '(' . $fieldname . " like '%" . strtolower($terms) . "%' OR " . $fieldname . " like '%" . strtoupper($terms) . "%')";
		  
   	  }	  
  
      $ret = '(' . implode(' OR ',$data) . ')';
	  //echo $ret;	    
      return $ret;
    }  
	
	
	function search_additional_files($terms=null) {
	  $nullarray = array();	
	
	  if (!$this->textsearch) return;
	
	  if ($this->searchpath) {
	    $myspath = $this->urlpath.$this->inpath.'/cp/'.$this->searchpath;
		//echo $myspath,"<br>";
	    if (is_dir($myspath)) {
	
          $mydir = dir($myspath);
		
          while ($fileread = $mydir->read ()) { 
          
		   $first_letter = substr($fileread,0,1);
		   //cut no addtitional files 
		   //if (($first_letter!='_') && ($first_letter!='c')) {
		   if (is_numeric($first_letter)) { // only numbers
		   
	         foreach ($this->searchfiletypes as $TEMPLATE_FILETYPE ) {
               if (stristr ($fileread,$TEMPLATE_FILETYPE)) {//get type of file
			    
				$content = file_get_contents($myspath .'/' . $fileread);
                if (stristr ($content,$terms)) {//get type of file
                  //echo $fileread,"<br>";
				  $np = explode('.',$fileread);
				  $code = substr($np[0],0,-1); //extract language digit
				  $ret[$code] = $code; //use key the code to not allow double codes
			    } 
               }
             }
		   }//first letter check
		  }
          $mydir->close ();

          //print_r($this->dfiles);
		  if (empty($ret))
		    return $nullarray;
		  
          return ($ret);	
		}  
   	  }
	  else 
		return $nullarray; //no search
	}
	
	function search_attachments($terms=null) {
      $db = GetGlobal('db');	
	  $lan = getlocal();
	  $nullarray = array();
	
	  if (!$this->attachsearch) return;
	  
	  $sSQL = "select code from pattachments";	
	  $sSQL .= " where lan=" . $lan . " and ( type LIKE '%";
      foreach ($this->searchfiletypes as $TEMPLATE_FILETYPE ) {
	    $tf[] = $TEMPLATE_FILETYPE;
	    //$sSQL .= " and type='" . $TEMPLATE_FILETYPE . "' ";
	  }
	  $sSQL .= implode("%' OR type LIKE '%", $tf);
	  $sSQL .= "%') and data LIKE '%$terms%'";
	  //echo $sSQL;	  
	  
	  $result = $db->Execute($sSQL,2); 
	  
	  if (empty($result))
	    return $nullarray;
		
	  //print_r($result);
	  foreach ($result as $n=>$rec) {  
	    $ret[$rec[0]] = $rec[0];
	  }
	  
      return ($ret);	

	}	
	

};
}
?>