<?php

$__DPCSEC['RCFORM_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCFORM_DPC")) && (seclevel('RCFORM_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCFORM_DPC",true);

$__DPC['RCFORM_DPC'] = 'rcform';

$b = GetGlobal('controller')->require_dpc('shop/shform.dpc.php');
require_once($b);


$__EVENTS['RCFORM_DPC'][0]='cpform';
$__EVENTS['RCFORM_DPC'][1]='searchtopic';
$__EVENTS['RCFORM_DPC'][2]='cpviewframe';
$__EVENTS['RCFORM_DPC'][3]='cpviewsubmitedform';

$__ACTIONS['RCFORM_DPC'][0]='cpform';
$__ACTIONS['RCFORM_DPC'][1]='searchtopic';
$__ACTIONS['RCFORM_DPC'][2]='cpviewframe';
$__ACTIONS['RCFORM_DPC'][3]='cpviewsubmitedform';

$__DPCATTR['RCFORM_DPC']['cpform'] = 'cpform,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCFORM_DPC'][0]='RCFORM_DPC;Form data;Form data';
$__LOCALE['RCFORM_DPC'][1]='_id;Id;Id';
$__LOCALE['RCFORM_DPC'][2]='_date;Date;Ημ/νία';
$__LOCALE['RCFORM_DPC'][3]='_email;e-mail;e-mail';


class rcform extends shform {

    var $title;
	var $charts;
	var $ajaxLink, $hasgraph;	

	function rcform() {
	
	  shform::shform();
	
	  $GRX = GetGlobal('GRX');
	  $this->title = localize('SHFORM_DPC',getlocal());
	  $this->path = paramload('SHELL','prpath');
	  
	  $this->charts = new swfcharts;	
	  $this->ajaxLink = seturl('t=cpvstatsshow&statsid=');		  
	}

    function event($event=null) {

	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//
	   /////////////////////////////////////////////////////////////

	   switch ($event) {
	     case 'cpviewsubmitedform' : echo $this->view_submited_form(); 
		                             die(); 
									 break;
		 case 'cpviewframe' :  echo $this->loadframe('vform');
							   die();
		                       break;
		 case 'searchtopic' : 					 
	     case 'cpform'      : 
		 default            :   $this->hasgraph = $this->charts->create_chart_data('statisticscat',"where year >=2000 and attr1='".urldecode(GetReq('cat'))."'");
                                $this->grid_javascript();
	   }

    }

    function action($action=null) {

	   switch ($action) {
	   
		 case 'searchtopic' :			 
		 default            : $out .= $this->viewForms();	
	   }

	   return ($out);
    }

	
	function getFormsList() {
       $db = GetGlobal('db');
       $UserName = GetGlobal('UserName');	
	   //$name = $UserName?decode($UserName):null;		   
       //echo GetReq('col');
	   	   
	     $sSQL = "select id,date,email,postform from cform ";// .//where cid=" . $db->qstr($name) . 
		 
		 if ($s = GetParam('searcht')) {//SEARCH TOPIC   
		   $sSQL .= "where email like '%$s%' or postform like '%$s%'  ";
		 }  		 
		 
		 if ($col = GetReq('col'))
		   $sSQL .= "order by " . $col;
		 else
		   $sSQL .= "order by id"; 
		   
		 if (GetReq('sort')<0)
		   $sSQL .= ' DESC';
		   
		 //echo $sSQL;		 
				 
				 
	     $res = $db->Execute($sSQL,2);
	     //print_r ($res);
		 $i=0;
	     if (!empty($res)) { 
	       foreach ($res as $n=>$rec) {
		    $i+=1;
				
			
            $transtbl[] = $i . ";" . 
                         $rec[0] . ";" . $rec[1] . ";" . $rec[2] . ";" . str_replace(';','<@>',$rec[3]);// . ";" .
						 //$rec[4] . ";" . $rec[5] . ";" . $rec[6] . ";" . $rec[7];						 					 	   
		   }
		   
           //browse
		   //print_r($transtbl); 
		   $ppager = GetReq('pl')?GetReq('pl'):100;
           $browser = new browse($transtbl,null,$this->getpage($transtbl,$this->searchtext));
	       $out .= $browser->render("cpform",$ppager,$this,1,1,0,0,1,1,1,0);
	       unset ($browser);	
		      
	     }
		 else {
           //empty message
	       $w = new window(null,localize('_EMPTY',getlocal()));
	       $out .= $w->render("center::40%::0::group_win_body::left::0::0::");//" ::100%::0::group_form_headtitle::center;100%;::");
	       unset($w);

		 }		 	
	   
	   return ($out);
	} 
	
	function viewForms() {
       $db = GetGlobal('db');
	   $a = GetReq('a');
       $UserName = GetGlobal('UserName');

	   if (!defined('MYGRID_DPC')) 
		   return ($this->getFormsList());	   

	   $out = 	$this->viewGrid();	 
		 	 					
	   return ($out);	
	
	}		

    function searchinbrowser() {
            $ret = "
           <form name=\"searchinbrowser\" method=\"post\" action=\"\">
           <input name=\"filter\" type=\"Text\" value=\"\" size=\"56\" maxlength=\"64\">
           <input name=\"Image\" type=\"Image\" src=\"../images/b_go.gif\" alt=\"\"    align=\"absmiddle\" width=\"22\" height=\"28\" hspace=\"10\" border=\"0\">
           </form>";

          $ret .= "<br>Last search: " . GetParam('filter');

          return ($ret);
    }
		
	function getpage($array,$id){
	
	   if (count($array)>0) {
         //while(list ($num, $data) = each ($array)) {
         foreach ($array as $num => $data) {
		    $msplit = explode(";",$data);
			if ($msplit[1]==$id) return floor(($num+1) / $this->pagenum)+1;
		 }	  
		 
		 return 1;
	   }	 
	}		
		
    function browse($packdata,$view) {
	
	   $data = explode("||",$packdata); //print_r($data);
	
       $out = $this->viewfrm($data[0],$data[1],$data[2],$data[3],$data[4]);

	   return ($out);
	}				
		
    function viewfrm($i,$id,$date,$email,$postform) {
	   $p = GetReq('p');
	   $a = GetReq('a');
	   
	   $email_link = seturl("t=cpform&rec=$id&editmode=1" , $email); 			  
	   	   
	   $data[] = $i;   
	   $attr[] = "left;5%";	   
	   
	   $data[] = $id?$id:'&nbsp;';   
	   $attr[] = "left;5%";   
	   
	   $data[] = $date?$date:'&nbsp;';   
	   $attr[] = "left;20%";	      
	   
	   if ($id == GetReq('rec')) {
	   
		 $details = str_replace('<@>',';',$postform); 
		 //echo $details;
		 
         $data[] = $email?$email:'&nbsp;';    
	     $attr[] = "left;35%";			 
		 
         $data[] = $details?$details:'&nbsp;';    
	     $attr[] = "left;35%";		 
	   }
       else {
         $data[] = $email?$email_link:'&nbsp;';    
	     $attr[] = "left;70%";		   
       }	   
	   
	   
	   $myarticle = new window('',$data,$attr);
       $out .= $myarticle->render();//"center::100%::0::group_dir_body::left::0::0::");
	   unset ($data);
	   unset ($attr);
	   
       /*if ($details) {
	     $out .= $details;   
	   }	
	   else {   
		 $out .= $line ;//. '<hr>';
	   }*/	   
	   

	   return ($out);
	}			
		
	function headtitle() {
	   $p = GetReq('p');
	   $t = GetReq('t')?GetReq('t'):'cpusers';
	   $sort = GetReq('sort')>0?-1:1; 
	   
	   if (GetReq('editmode'))
	     $edmode = '&editmode=1';
	   else
	     $edmode = null; 
	
       $data[] = seturl("t=$t&a=&g=1&p=$p&sort=$sort&col=id".$edmode ,  "A/A" );
	   $attr[] = "left;5%";							  
	   $data[] = seturl("t=$t&a=&g=2&p=$p&sort=$sort&col=id".$edmode , localize('_id',getlocal()) );
	   $attr[] = "left;5%";
	   $data[] = seturl("t=$t&a=&g=3&p=$p&sort=$sort&col=date".$edmode , localize('_date',getlocal()) );
	   $attr[] = "left;20%";
	   $data[] = seturl("t=$t&a=&g=4&p=$p&sort=$sort&col=email".$edmode , localize('_email',getlocal()) );
	   $attr[] = "left;70%";
	   //$data[] = seturl("t=$t&a=&g=5&p=$p&sort=$sort&col=username".$edmode , localize('_username',getlocal()) );
	   //$attr[] = "left;25%";	   

  	   $mytitle = new window('',$data,$attr);
	   $out = $mytitle->render(" ::100%::0::group_form_headtitle::center;100%;::");
	   unset ($data);
	   unset ($attr);	
	   
	   return ($out);
	}
	
	function view_submited_form() {
	    $db = GetGlobal('db');
		$id = GetReq('id');
		
		$sSQL = 'select postform from cform where id='.$id;
		$ret = $db->Execute($sSQL,2);

        return ($ret->fields[0]);		
	}
	
	function loadframe($ajaxdiv=null) {
		$id = GetReq('id');

		$vurl = seturl('t=cpviewsubmitedform&id='.$id);
		$frame = "<iframe src =\"$vurl\" width=\"100%\" height=\"550px\"><p>Your browser does not support iframes</p></iframe>";    

		if ($ajaxdiv)
			return $ajaxdiv.'|'.$frame;
		else
			return ($frame);
	}	
	
	function show_graph($xmlfile,$title,$url=null,$ajaxid=null,$xmax=null,$ymax=null) {
	  $gx = $this->graphx?$this->graphx:$xmax?$xmax:550;
	  $gy = $this->graphy?$this->graphy:$ymax?$ymax:250;	
	  
	  $ret = $title; 
	  $ret .= $this->charts->show_chart($xmlfile,$gx,$gy,$url,$ajaxid);
	  return ($ret);
	}	
	
    function getFormsList2($width=null, $height=null, $rows=null, $editlink=null, $mode=null, $noctrl=false) {
	    $height = $height ? $height : 800;
        $rows = $rows ? $rows : 36;
        $width = $width ? $width : null; //wide
        $mode = $mode ? $mode : 'd';
		$noctrl = $noctrl ? 0 : 1;
        $editlink = $editlink ? $editlink : null;//seturl("t=cpeditcat&editmode=1&cat={cat2}");
							 
		$xsSQL = 'select id,date,email from cform';	
		
        GetGlobal('controller')->calldpc_method("mygrid.column use grid1+id|".localize('_id',getlocal())."|5");
        GetGlobal('controller')->calldpc_method("mygrid.column use grid1+date|".localize('_date',getlocal())."|15");
		if ($editlink)
			GetGlobal('controller')->calldpc_method("mygrid.column use grid1+email|".localize('_email',getlocal())."|link|20|".$editlink);		
		else
			GetGlobal('controller')->calldpc_method("mygrid.column use grid1+date|".localize('_date',getlocal())."|15");
			
		$out .= GetGlobal('controller')->calldpc_method("mygrid.grid use grid1+cform+$xsSQL+$mode++id+$noctrl+1+$rows+$height+$width+0+1+1");
	    return ($out);
	
    }
	
	function grid_javascript() {
	
      if (iniload('JAVASCRIPT'))  {  		      
		   
	       $code = $this->init_grids();	     		

		   $js = new jscript;			   
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }		
	}	

	function init_grids() {
	    $editurl = seturl("t=cpviewframe&id=");
  		
		$out = "
function update_stats_id() {
  var str = arguments[0];
  var str1 = arguments[1];
  var str2 = arguments[2];
  
  
  statsid.value = str;
  //alert(statsid.value);
  sndReqArg('$this->ajaxLink'+statsid.value,'stats');
  
  return str1+' '+str2;
}

function viewform() {
  var str = arguments[0];
  var str1 = arguments[1];
  var str2 = arguments[2];  
  var taskid;
  var custid;
  
  taskid = str;  
  custid = str1;
  sndReqArg('$editurl'+taskid,'editcat');
}
";
        $out .= "\r\n";
        return ($out);
	}	
	
	function viewGrid() {
	   //$cat = rawurlencode(GetReq('cat'));
	   $id = 'id';//$this->getmapf('code');
	   $editlink = "javascript:viewform({".$id."})";
	   
	   $rd = $this->getFormsList2(800,440,20, $editlink, 'r', true);
	   /*$rd .= GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use stats");
       if ($this->hasgraph) {	   
		  $rd .= $this->show_graph('statisticscat','Category statistics',seturl('t=cpkategories&cat='.$cat.'&p='.$p));
	   }	  
	   else
	      $rd .= "<h3>".localize('_GNAVAL',0)."</h3>";		   
	   */
	   $datattr[] = $rd;
	   $viewattr[] = "left;50%";

	   /*if ($cat) {//preselected cat
		 //$editurl = seturl("t=cpeditcat&editmode=1&cat=".$cat);//$id;
		 $editurl = $this->urlbase . "cp/cpmhtmleditor.php?htmlfile=&type=.html&editmode=1&id=".$cat;
		 $init_content = "<iframe src =\"$editurl\" width=\"100%\" height=\"450px\"><p>Your browser does not support iframes</p></iframe>";    
	   }
	   else*/
	   $init_content = null; 
	   $wd .= GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use vform+".$init_content);	   	   
	   
	   $datattr[] = $wd;
	   $viewattr[] = "left;50%";		  

	   $myw = new window('',$datattr,$viewattr);
	   $ret = $myw->render();
	   unset ($datattr);
	   unset ($viewattr);	

	   return ($ret);			  
	   
	}	
};
}
?>