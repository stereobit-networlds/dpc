<?php
$__DPCSEC['SHSEARCH_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("SHSEARCH_DPC")) && (seclevel('SHSEARCH_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHSEARCH_DPC",true);

$__DPC['SHSEARCH_DPC'] = 'shsearch';

$d = GetGlobal('controller')->require_dpc('search/search.dpc.php');
require_once($d);

$__EVENTS['SHSEARCH_DPC'][0]='search';
$__EVENTS['SHSEARCH_DPC'][1]='addtocart';     //continue with ..cart
$__EVENTS['SHSEARCH_DPC'][2]='removefromcart';//continue with ..cart
$__EVENTS['SHSEARCH_DPC'][3]='searchtopic';   //continue with ..browser

$__ACTIONS['SHSEARCH_DPC'][0]='search';
$__ACTIONS['SHSEARCH_DPC'][1]='addtocart';     //continue with ..from cart
$__ACTIONS['SHSEARCH_DPC'][2]='removefromcart';//continue with ..from cart
$__ACTIONS['SHSEARCH_DPC'][3]='searchtopic';   //continue with ..from browser

$__LOCALE['SHSEARCH_DPC'][0]='SHSEARCH_DPC;Search;Αναζήτηση';
$__LOCALE['SHSEARCH_DPC'][1]='_founded;found;εγγραφές βρέθηκαν';
$__LOCALE['SHSEARCH_DPC'][2]='SEARCH_DPC;Search;Αναζήτηση';
$__LOCALE['SHSEARCH_DPC'][3]='_MSG3;Advance Search;Σύνθετη Αναζήτηση';
$__LOCALE['SHSEARCH_DPC'][4]='_ASPHRASE;As a Phrase;Ως Φράση';
$__LOCALE['SHSEARCH_DPC'][5]='_ANYTERMS;Any Terms;Κάποιο απο τα συνθετικά';
$__LOCALE['SHSEARCH_DPC'][6]='_ALLTERMS;All Terms;Όλα τα συνθετικά';
$__LOCALE['SHSEARCH_DPC'][7]='_SEARCHTYPE;Type;Τύπος';
$__LOCALE['SHSEARCH_DPC'][8]='_CSENSE;Case Sensitive;Κεφαλαία/Μικρά';
$__LOCALE['SHSEARCH_DPC'][9]='_TTIME;Total time;Συνολικός Χρόνος';
$__LOCALE['SHSEARCH_DPC'][10]='_SEARCHR;Search Results;Αποτελέσματα Αναζήτησης';
$__LOCALE['SHSEARCH_DPC'][11]='_SEARCH;Search;Αναζήτηση';
$__LOCALE['SHSEARCH_DPC'][12]='_ALL;All;Σε Όλα;';

class shsearch extends search {

    var $title,$msg;
	var $queuelist;
	var $post,$result,$meter,$pager,$text2find;
	var $path, $urlpath, $inpath;

	function shsearch() {
	
	  search::search();	
	
	  $this->urlpath = paramload('SHELL','urlpath');
	  $this->inpath = paramload('ID','hostinpath');		
	  	
      $this->title = localize('SHSEARCH_DPC',getlocal());
	  $this->path = paramload('SHELL','prpath');	  
	  $this->post = false;
	  $this->msg = null;	
	  //$this->result = null;	
	  
	  //overwrite
	  //$this->imgpath = 'images/uphotos/';  	  
	  //$this->restype = '.jpg';	  	  
	  //$this->thubpath = 'images/thub/';	  	 
	  
	  $this->meter = 0; 
	  $this->pager = 10;
	
	  $this->path = paramload('SHELL','prpath');	
	  $this->text2find = GetParam('Input');	 
	  $this->imageclick = remote_paramload('SHSEARCH','imageclick',$this->path);	     
	}
	
	function event($event=null) {
	
	  $this->text2find = GetParam('Input')?GetParam('Input'):GetReq('input'); //echo '>>>',$text2find;
	  //$marka2find = GetParam('marka'); //echo '>>>',$marka2find;	  
	
	  switch ($event) {
	  
		//cart
	     case 'searchtopic'   :
	     case 'addtocart'     :
		 case 'removefromcart':		
		// 	  
	  
	    case 'search' :
		default       : if ($this->text2find)
		                  $this->do_quick_search($this->text2find,$marka2find);
		                else
		                  $this->do_search();
	  }
	}
	
	function action($action=null) {
	
	  switch ($action) {
	  
		//cart
	     case 'searchtopic'   :
	     case 'addtocart'     :
		 case 'removefromcart':		
		// 	  
	  
	    case 'search' :
		default       : $out = $this->form_search();
	  }	
	  
	  return ($out);
	}
	
	function form_search() {
	   $typos = trim(GetParam('typos'));	
	   $extras = trim(GetParam('extras'));
	   $price = trim(GetParam('price'));
	   $price2 = trim(GetParam('price2'));	   
	
       $out =  setNavigator($this->title . "&nbsp;(" . $this->msg . ")");	
	   
	   //KATEGORIES SEARCH
	   $out .= $this->search_categories($this->text2find);
	   
	   $this->msg = null;//reset not to re-show in list_vehicles functions
	   //$out .= $this->msg;
	   
	   $myimageclick = $this->imageclick?$this->imageclick:null;

		 	   
	   //$out .= $this->list_katalog_table(2,null,null,0,1);
	   $out .= $this->list_catalog($myimageclick,'search&input='.$this->text2find);
       if ($this->meter) $out .= "<hr>";
	   
	   $out .= $this->form(null,'search');
	
/*	   $myform[] = 'category';
	   $myform[] = '';	   
	   $myform[] = 'marka';
	   $myform[] = '';
	   $myform[] = 'typos';
	   $myform[] = $typos?$typos:'';
	   $myform[] = 'color';
	   $myform[] = '';	   
	   $myform[] = 'pdate';
	   $myform[] = '';
	   $myform[] = 'extras';
	   $myform[] = $extras?$extras:'';   
	   $myform[] = 'price';
	   $myform[] = $price?$price:'';		   
	   $myform[] = 'price2';
	   $myform[] = $price2?$price2:'';		   
	   $myform[] = 'FormAction';
	   $myform[] = 'search';	   
	   $myform[] = 'Submit';
	   $myform[] = '  Ok  ';
	   $form_array['form'] = (array)$myform;
	   $form_array['action'] = seturl('t=search');*/
	   
	   return ($out);//.'<@PHPCHUNK>'.serialize($form_array));   	
	}
	
	function do_search() {
      $db = GetGlobal('db');	
	  $page = GetReq('page')?GetReq('page'):0;	  
	  $asc = GetReq('asc');
	  $order = GetReq('order');		
	  $lan = getlocal();
	  $mylan = $lan?$lan:'0';
	  $itmname = $mylan?'itmname':'itmfname';
	  $itmdescr = $mylan?'itmdescr':'itmfdescr';			    
	  
	  if (GetReq('cat')!=null)
		 $cat = GetReq('cat');	  
	  
	  $dataerror = null;
	
	 /* $cat = trim(GetParam('category'));
	  $marka = trim(GetParam('marka')); //echo $marka,'-><br>';
	  $typos = trim(GetParam('typos'));
	  $color = trim(GetParam('color'));	  
	  $pdate = trim(GetParam('pdate'));
	  $extras = trim(GetParam('extras'));
	  $price = trim(GetParam('price'));
	  $price2 = trim(GetParam('price2'));*/
	 
	  //print_r($_POST);
	  if (empty($_POST)) return -1;
	  //if ($this->check_fields()===true) return; 	  
	  
	  if (isset($cat) || isset($marka) || isset($typos) || 
	      isset($color) || isset($pdate) || isset($extras) || isset($price) || isset($price2)) {
	    //echo $cat,"<br>";
		//echo $marka,"<br>";
		
	    $sSQL = "select id,sysins,code1,pricepc,price2,sysins,itmname,uniname1,uniname2,active,code4," .// from abcproducts";// .
	            "price0,price1,cat0,cat1,cat2,cat3,cat4,itmremark,ypoloipo1,".$this->getmapf('code')." from products ";
		  
		//$id_cat = (int) get_selected_id_fromfile($cat,'kategories',0);
		if ($id_cat>=0) {
		  $sSQL .= " WHERE ";		
		
		   $cat_tree = explode('^',str_replace('_',' ',$cat)); 
			
           //$whereClause .= '( cat0=' . $db->qstr(str_replace('_',' ',$cat_tree[0]));		  
		   if ($cat_tree[0])
			    $whereClause .= ' cat1=' . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[0])));		  
		   if ($cat_tree[1])	
		 	    $whereClause .= 'and cat2=' . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[1])));		 
		   if ($cat_tree[2])	
			    $whereClause .= 'and cat3=' . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[2])));		   
		   if ($cat_tree[3])	
			    $whereClause .= 'and cat4=' . $db->qstr(rawurldecode(str_replace('_',' ',$cat_tree[3])));
			
		  $sSQL .= $whereClause;				  
		  	  		  	  
		}
		
/*		
	    $id_marka = (int) get_selected_id_fromfile(GetParam('marka'),'marka',0);
	  	if ($id_marka>=0) {
		   if ($id_cat>=0) $sSQL.=' and ';
		              else $sSQL.=' where ';		  
            //$sSQL .= ' marka LIKE ' . $db->qstr('%'.$marka.'%');	
	        //$sSQL .= " ( marka like '%" . strtolower($marka) . "%' or " .
		    //       " marka like '%" . strtoupper($marka) . "%')";					  
          $sSQL .= ' marka=' . $db->qstr($id_marka);
		}			  
		
	    if ($typos!=null) {
		    if (($id_cat>=0) || ($id_marka>=0)) $sSQL.=' and ';
		                                   else $sSQL.=' where ';		  
	        $sSQL .= " ( model like '%" . strtolower($typos) . "%' or " .
		             " model like '%" . strtoupper($typos) . "%')";				 	
		}
		
		$id_color = (int) get_selected_id_fromfile($color,'colors',0);
		if ($id_color>=0) {
		    if (($typos!=null) || ($id_marka>=0) || ($id_cat>=0)) $sSQL.=' and ';
		                                                     else $sSQL.=' where ';
		    $sSQL.= "color=" . $db->qstr($id_color);
		}			
		
		if (($pdate!=null) && (is_numeric($pdate))) {
		    if (($typos!=null) || ($id_marka>=0) || ($id_cat>=0)) $sSQL.=' and ';
		                                                     else $sSQL.=' where ';
		    $sSQL.= "etosk>=" . $pdate . " ";
		}
		
	    if ($extras!=null) {
		    if (($id_cat>=0) || ($id_marka>=0) || ($typos!=null) || ($id_color>=0) || (is_numeric($pdate))) 
			  $sSQL.=' and ';
		    else 
			  $sSQL.=' where ';		  
	        $sSQL .= " ( itmremark like '%" . strtolower($extras) . "%' or " .
		             " itmremark like '%" . strtoupper($extras) . "%')";					 	
		}						
		
		if (($price!=null) && (is_numeric($price))) {
		    if (($id_cat>=0) || ($id_marka>=0) || ($typos!=null) || ($id_color>=0) || (is_numeric($pdate)) || ($extras!=null)) 
			  $sSQL.=' and ';
		    else 
			  $sSQL.=' where ';		
		    $sSQL.= "price0>=" . $price . " ";
		}		
		
		if (($price2!=null) && (is_numeric($price2))) {
		    if (($id_cat>=0) || ($id_marka>=0) || ($typos!=null) || ($id_color>=0) || (is_numeric($pdate)) || ($extras!=null) || (is_numeric($price))) 
			  $sSQL.=' and ';
		    else 
			  $sSQL.=' where ';	
		    $sSQL.= "price0<=" . $price2 . " ";
		}
		
*/		
		
		$sSQL .= " and itmactive>0 and active>0";					
		
		  $sSQL .= ' ORDER BY';
		  
		  switch ($order) {
		    case 1  : $sSQL .=  ' '.$itmname.','.$itmdescr; break;
			case 2  : $sSQL .= ' price0';break;  
		    case 3  : $sSQL .= ' '.$this->getmapf('code'); break;//must be converted to the text equal????
			case 4  : $sSQL .= ' cat1';break;			
			case 5  : $sSQL .= ' cat2';break;
			case 6  : $sSQL .= ' cat3';break;			
			case 9  : $sSQL .= ' cat4';break;						
		    default : $sSQL .=  ' '.$itmname.','.$itmdescr;
		  }
		  
		  switch ($asc) {
		    case 1  : $sSQL .= ' ASC'; break;
			case 2  : $sSQL .= ' DESC';break;
		    default : $sSQL .= ' ASC';
		  }
		  
		  if ($this->pager) {
		    $p = $page * $this->pager;
		    $sSQL .= " LIMIT $p,".$this->pager; //page element count
		  }
		  		
		//echo $sSQL;		
		
		//if (($id_marka>=0) || ($id_color>=0) || ($typos!=null) || (is_numeric($pdate)) || ($extras!=null) || (is_numeric($price)) || (is_numeric($price2))) { 
				
		  //echo $sSQL;
		  if ($dataerror==null) {
	        $resultset = $db->Execute($sSQL,2);
	        $ret = $db->fetch_array_all($resultset);	 
	   	   
	        $this->result = $ret; 
			$this->meter = $db->Affected_Rows();
			$this->msg = $this->meter . ' ' . localize('_founded',getlocal());																		
	      }
		  else
		    $this->msg = $dataerror;		
		//}
		
	  } 
	}
	
	function do_quick_search($text2find,$comboselection=null) {
	
		  if (defined("SHCATALOG_DPC")) {
		      GetGlobal('controller')->calldpc_method('shcatalog.do_quick_search use '.$text2find.'+'.$comboselection);
	      }		
          elseif (defined("SHKATALOG_DPC"))
              GetGlobal('controller')->calldpc_method('shkatalog.do_quick_search use '.$text2find.'+'.$comboselection);
			  
	}
	
	function search_categories($text2find=null,$template=null) {
	
		  if (defined("SHCATEGORIES_DPC")) {//text based cats
		      $ret = GetGlobal('controller')->calldpc_method('shcategories.search_tree use '.$text2find."+search&searchtype=$this->asphrase&input=".$text2find.'+'.$template);//klist or search in cat, =+search		
	      }		
          elseif (defined("SHKATEGORIES_DPC"))//sql based cats			
              $ret = GetGlobal('controller')->calldpc_method('shkategories.search_tree use ' . $text2find ."+search&searchtype=$this->asphrase&input=".$text2find.'+'.$template);//klist or seach in cat,= +search
			  
		  return ($ret);	  
	}
	
	function list_catalog($imageclick=null,$cmd=null) {
	
		  if (defined("SHCATALOG_DPC")) {
		      $ret = GetGlobal('controller')->calldpc_method('shcatalog.list_katalog use '.$imageclick.'+'.$cmd);
	      }		
          elseif (defined("SHKATALOG_DPC"))
              $ret = GetGlobal('controller')->calldpc_method('shkatalog.list_katalog use '.$imageclick.'+'.$cmd);
			  
		  return ($ret);	  
	}	
	
	function getmapf($name) {
	
	  if (defined("SHCATALOG_DPC"))
	    $module = 'SHCATALOG';
      elseif (defined("SHKATALOG_DPC"))
	    $module = 'SHKATALOG';
	  
	
	  $this->map_t = remote_arrayload($module,'maptitle',$this->path);	
	  $this->map_f = remote_arrayload($module,'mapfields',$this->path);		
	
	  if (empty($this->map_t)) return 0;	
	  
	  foreach ($this->map_t as $id=>$elm)
	    if ($elm==$name) break;
				
	  //$id = key($this->map_t[$name]) ;
	  $ret = $this->map_f[$id];
	  return ($ret);
	}	
	
	//override
    function form ($entry="",$cmd=null)  {
      $mycmd = $cmd?$cmd:'search';
      $filename = seturl("t=$mycmd");//&a=$a&g=$g");  
	  $lan = getlocal()?getlocal():'0';
      //template
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
	    $tokens[] = $this->stime;
	  else		  
	    $out = $this->stime;

	  if ($template) { 
	    $tokens[] = "<FORM action=". $filename . " method=post>" . 
		            "<INPUT type=\"text\" name=\"input\" value=\"$entry\" size=25>"; 
					
					
        if ($this->stype) {
	      switch ($this->stype) {
		    case $this->anyterms   : $tokens[] = "<SELECT name=searchtype> <OPTION selected>$this->anyterms<OPTION>$this->allterms<OPTION>$this->asphrase</OPTION></SELECT>"; break;
		    case $this->allterms   : $tokens[] = "<SELECT name=searchtype> <OPTION>$this->anyterms<OPTION selected>$this->allterms<OPTION>$this->asphrase</OPTION></SELECT>";break;
		    case $this->asphrase   : $tokens[] = "<SELECT name=searchtype> <OPTION>$this->anyterms<OPTION>$this->allterms<OPTION selected>$this->asphrase</OPTION></SELECT>";break;
	      }
	    }
	    else
		   $tokens[] = "<SELECT name=searchtype> <OPTION>$this->anyterms<OPTION>$this->allterms<OPTION selected>$this->asphrase</OPTION></SELECT>";					
		   
        if ($this->scase) $check = "checked"; else $check = "";
        $tokens[] = "<input type=\"checkbox\" name=\"searchcase\" value=\"$check \"". $check . ">";		   
		
        $tokens[] = $this->searchin();
		
		$tokens[] = "<input type=\"submit\" name=\"Submit\" value=\"$this->t_searchtitle\">" .
                    "<input type=\"hidden\" name=\"FormAction\" value=\"$mycmd\">" .
                    "</FORM>";				
	  }	
	  else {			
        $toprint  = "<FORM action=". $filename . " method=post>";
	  
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
	  }	

	  if ($template) {	 		 	      
		$tokout = $this->combine_tokens($contents,$tokens);
		return ($tokout);    
	  }
	  else 		  
        return ($out);
    }	
	
	//tokens method
	function combine_tokens($template_contents,$tokens) {
	
	    if (!is_array($tokens)) return;
		
		if (defined('FRONTHTMLPAGE_DPC')) {
		  $fp = new fronthtmlpage(null);
		  $ret = $fp->process_commands($template_contents);
		  unset ($fp);
          //$ret = GetGlobal('controller')->calldpc_method("fronthtmlpage.process_commands use ".$template_contents);		  		
		}		  		
		else
		  $ret = $template_contents;
		  
		//echo $ret;
	    foreach ($tokens as $i=>$tok) {
            //echo $tok,'<br>';
		    $ret = str_replace("$".$i,$tok,$ret);
	    }
		//clean unused token marks
		for ($x=$i;$x<10;$x++)
		  $ret = str_replace('$'.$x,'',$ret);
		//echo $ret;
		return ($ret);
	} 			
};
}	
?>