<?php

$__DPCSEC['RCSUBSCRIBERS_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("RCSUBSCRIBERS_DPC")) && (seclevel('RCSUBSCRIBERS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCSUBSCRIBERS_DPC",true);

$__DPC['RCSUBSCRIBERS_DPC'] = 'rcsubscribers';

$a = GetGlobal('controller')->require_dpc('nitobi/nitobi.lib.php');
require_once($a);

$d = GetGlobal('controller')->require_dpc('subscribe/rcsubscribe.dpc.php');
require_once($d);


$__EVENTS['RCSUBSCRIBERS_DPC'][0]='cpsubscribers';
$__EVENTS['RCSUBSCRIBERS_DPC'][1]='cpunsubscribe';
$__EVENTS['RCSUBSCRIBERS_DPC'][2]='cpsubscribe';
$__EVENTS['RCSUBSCRIBERS_DPC'][3]='cpadvsubscribe';

$__ACTIONS['RCSUBSCRIBERS_DPC'][0]='cpsubscribers';
$__ACTIONS['RCSUBSCRIBERS_DPC'][1]='cpunsubscribe';
$__ACTIONS['RCSUBSCRIBERS_DPC'][2]='cpsubscribe';
$__ACTIONS['RCSUBSCRIBERS_DPC'][3]='cpadvsubscribe';

$__LOCALE['RCSUBSCRIBERS_DPC'][0]='RCSUBSCRIBERS_DPC;Subscribers;Subscribers';

class rcsubscribers extends rcsubscribe {

    var $subscriberlist;
	var $_grids;	

    function rcsubscribers() {
	
	  rcsubscribe::rcsubscribe();
	  
	  $this->title = localize('RCSUBSCRIBERS_DPC',getlocal());	  
	  
	  if ($remoteuser=GetSessionParam('REMOTELOGIN')) {
		  //must re connect to remote db and set global db to this
		  $this->path = paramload('SHELL','prpath')."instances/$remoteuser/";	
		  $remotedb = new sqlite($this->path . "mysqlitedb");
		  
		  SetGlobal('db',$remotedb->dbp);
	  }	 	
	  
	  $this->_grids[] = new nitobi("Subscribers");		    
	}
	
    function event($event=null) {
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////			

       if (!$this->msg) {
  
	     switch ($event) {
		 
		    case 'cpsubscribe'        : $this->dosubscribe();
			                        //$this->read_subscribers();				
	                                break;
		    case 'cpunsubscribe'      : $this->dounsubscribe();
			                        //$this->read_subscribers();				
	                                break;									
	        case 'cpsubscribers'  :
			case 'cpadvsubscribe' :
			default               :	$this->nitobi_javascript();		
			                        //$this->read_subscribers();						  
         }
      }
    }	

    function action($action=null)  { 

	     if (GetSessionParam('REMOTELOGIN')) 
	       $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	     else  
           $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);	
	  
	     switch ($action) {
		   case 'cpadvsubscribe' :
   		            //$out = $this->title();
		            $out .= $this->form("cpsubscribe");
		            break;
		   default :
		             $out .= $this->show_subscribers();
		 }			 

	     return ($out);
	}	
	
	function nitobi_javascript() {
      if (iniload('JAVASCRIPT')) {

		   //$template = $this->set_template();   		      
		   
	       $code = $this->init_grids();			
		   //$code .= $this->_grids[0]->OnClick(22,'QueueDetails',$template,'Vehicles','p_id',0);
	   
		   $js = new jscript;
		   $js->setloadparams("init()");
           $js->load_js('nitobi.grid.js');		   
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }		
	}		
	
    function read_subscribers() {
       $db = GetGlobal('db');
	   
	   //$this->reset_db();
	   
	   $apo = GetParam('apo'); //echo $apo;
	   $eos = GetParam('eos');	//echo $eos; 		   
	   
       $sSQL = "SELECT subscriber_id,email,cdate FROM rcsubscribers";	
	   
	   if ($letter=GetReq('alpha'))
	     $sSQL .= " where ( email like '" . strtolower($letter) . "%' or " .
		          " email like '" . strtoupper($letter) . "%')";	
				  
		if ($apo) {
		  if ($letter) $sSQL.=' and ';
		          else $sSQL.=' where ';
		  $sSQL.= "cdate>='" . convert_date(trim($apo),"-YMD",1) . "'";
		}  
		  
		if ($eos) {
		  if ((!$letter) && (!$apo)) $sSQL.=' where ';
	                            else $sSQL.=' and ';
		  $sSQL .= "cdate<='" . convert_date(trim($eos),"-YMD",1) . "'";						
		} 
		
		if ((!$letter) && (!$eos) && (!$apo))  //default view
		  $sSQL.= " where cdate>='" . date('Y-m-d') . "'"; 				     
	   
	   //echo $sSQL;
	   
	   $resultset = $db->Execute($sSQL,2);
	   $ret = $db->fetch_array_all($resultset);	 
	   	   
	   $this->subscriberslist = $ret; 
		 
       /*$browser = new browseSQL(localize('_USERS',getlocal()));
	   $out .= $browser->render($db,$sSQL,"subscribers","subscribe",30,$this,1,1,1,0);
	   unset ($browser);	   
	*/
	
  //USE OF DATAGRID LIB....	
	
  //ob_start();
  //$db_conn=mysql_connect("localhost","thebutto_test","12345");
  //mysql_select_db("thebutto_test",$db_conn);
  /*** put primary key in first place */
  //$sql="SELECT * FROM tableName ";       
  //$dgrid = new DataGrid();
  //$dgrid->dataSourse($db->connect_id, $sSQL);	    
  /*** other settings here ***/
  //$dgrid->bind($debug_mode, $messaging);        
  //ob_end_flush();
	
       return ($out);	
    }
	
	function show_subscribers() {
	
	   if ($this->msg) $out = $this->msg;
	   
	   $myadd = new window('',seturl("t=cpadvsubscribe","Subscribe"));
	   $toprint .= $myadd->render("center::100%::0::group_article_selected::right::0::0::");	   
	   unset ($myadd);  
	   
       $toprint .= $this->show_grids();
	
	  /* if ($this->subscriberslist) {	
	   
	    $max  = count($this->subscriberslist[0])-1;
	    $prc = 96/$max;		
	
	    foreach ($this->subscriberslist as $n=>$rec) {
		
		   $viewdata[] = $n+1;
		   $viewattr[] = "right;4%";
		   
           $name = "customer_".$rec[0]; //echo $name."<br>";		 
		   $viewdata[] = "<input type=\"checkbox\" name=\"$name\" value=\"0\">";
		   $viewattr[] = "left;5%";		
		   
		   $viewdata[] = seturl("t=cpunsubscribe&submail=".$rec[1],"X");
		   $viewattr[] = "left;15%";		      
		   
		   $viewdata[] = ($rec[1]?$rec[1]:"&nbsp;");
		   $viewattr[] = "left;50%";		   
		   
		   $viewdata[] = ($rec[2]?$rec[2]:"&nbsp;");
		   $viewattr[] = "left;30%";		   	   
		   
	       $myrec = new window('',$viewdata,$viewattr);
	       $toprint .= $myrec->render("center::100%::0::group_article_selected::left::0::0::");
	       unset ($viewdata);
	       unset ($viewattr);			
		}			
	   }
	   else
	     $toprint .= "No subscribers !<br>";//$this->bulkiform();*/
		 
	   $toprint .= $this->alphabetical();	
	   
	   $dater = new datepicker("/MDYT");	
	   $toprint .= $dater->renderspace(seturl("t=cpsubscribers"),"cpsubscribers");		 
	   unset($dater);		   	 
		 
       $mywin = new window($this->title,$toprint);
       $out .= $mywin->render();		 
	  
	   return ($out);		
	}	
	
	function alphabetical($command='cpsubscribers') {
	
	  $preparam = GetReq('alpha');
	  
	  $ret .= seturl("t=$command","Home") . "&nbsp;|";
	
	  for ($c=$preparam.'a';$c<$preparam.'z';$c++) {
	    $ret .= seturl("t=$command&alpha=$c",$c) . "&nbsp;|";
	  }
	  //the last z !!!!!
	  $ret .= seturl("t=$command&alpha=".$preparam."z",$preparam."z");
	  
      //$mywin = new window('',$ret);
      //$out = $mywin->render();	  
	  
	  return ($ret);
	}	
	
    function browse($packdata,$view) {
	
	   $data = explode("||",$packdata);
	
       $out = $this->viewsubusers($data[0],$data[1],$data[2],$data[3]);

	   return ($out);
	}		
	
    function viewsubusers($id,$fname,$lname,$uname) {
	   $a = GetReq('a');
							  
	   $data[] = $id;
	   $attr[] = "left;10%";
	   $data[] = $fname;   
	   $attr[] = "left;30%";
	   $data[] = $lname;   
	   $attr[] = "left;30%";
	   $data[] = $lname;   
	   $attr[] = "left;30%";

	   $myarticle = new window('',$data,$attr);
	   
       if (($a) && (stristr($fname,$a))) $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::");
	                                else $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::");
	   unset ($data);
	   unset ($attr);

	   return ($out);
	}	
	
	function headtitle() {
	   $t = GetReq('t');
	   $p = GetReq('p');
	   $sort = GetReq('sort');	   	   
	
	   $data[] = seturl("t=$t&a=&g=&p=$p&sort=$sort&col=0",  localize('_SUBID',getlocal()) );
	   $attr[] = "left;10%";							  
	   $data[] = seturl("t=$t&a=&g=&p=$p&sort=$sort&col=1" , localize('_SUBMAIL',getlocal()) );
	   $attr[] = "left;30%";
  	   $data[] = seturl("t=$t&a=&g=&p=$p&sort=$sort&col=2" , localize('_SUBDATE',getlocal()) );
	   $attr[] = "left;30%";
	   $data[] = seturl("t=$t&a=&g=&p=$p&sort=$sort&col=3" , localize('_SUBDATE',getlocal()) );
	   $attr[] = "left;30%";

 	   $mytitle = new window('',$data,$attr);
	   $out = $mytitle->render(" ::100%::0::group_form_headtitle::center;100%;::");
	   unset ($data);
	   unset ($attr);	
	   
	   return ($out);
	}	
	
	function init_grids() {
        //disable alert !!!!!!!!!!!!		
		$out = "
function alert() {}\r\n 

function photo_name() {
  var str = arguments[0];

  id = 1000+str; 
  ret = id.substr(str.length-1);
  
  return ret; 
}
			
function init()
{
";
        foreach ($this->_grids as $n=>$g)
		  $out .= $g->init_grid($n);
	
        $out .= "\r\n}";
        return ($out);
	}	
	
	function show_grids() {
	   //gets
	   $alpha = GetReq('alpha');
	   //transformed posts !!!!
	   $apo = GetParam('apo');
	   $eos = GetParam('eos');	 
	   
	   
	   $grid0_get = "abchandler.php?t=abcngetsubs&alpha=$alpha&apo=$apo&eos=$eos";	
	   $grid0_set = null;	
		
	   $this->_grids[0]->set_text_column("ID","subscriber_id","50","true");		   
	   $this->_grids[0]->set_text_column(localize('_SUBMAIL',getlocal()),"email","350","true");		   
	   $this->_grids[0]->set_text_column(localize('_SUBDATE',getlocal()),"cdate","150","true","LOOKUP","list_type","type","type");//,"LISTBOX","list_type","type","type_id");

       $datattr[] = $this->_grids[0]->set_grid_remote($grid0_get,$grid0_set,"800","460","livescrolling",17);
	   $viewattr[] = "left;99%";		   		        
	      	   
	    
	   //$wd .= $this->_grids[1]->set_detail_div("QueueDetails",400,360,'F0F0FF',$message);
	   $datattr[] = "&nbsp;";//$wd;
	   $viewattr[] = "left;1%";
	   
	   $myw = new window('',$datattr,$viewattr);
	   $ret = $myw->render("center::100%::0::group_article_selected::left::3::3::");
	   unset ($datattr);
	   unset ($viewattr);		   	
	   	
	   return ($ret);	
	}					
};
}
?>