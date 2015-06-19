<?php
//if (defined("DATABASE_DPC")) {

$__DPCSEC['TEST_DPC']='2;1;1;1;1;1;1;1;2';

if (!defined("TEST_DPC")) {//&& (seclevel('TEST_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("TEST_DPC",true);

$__DPC['TEST_DPC'] = 'test';

$__EVENTS['TEST_DPC'][0]='test';
$__EVENTS['TEST_DPC'][1]='reset_smdr';
$__EVENTS['TEST_DPC'][2]='read_smdr';
$__EVENTS['TEST_DPC'][3]='sync_smdr';

$__ACTIONS['TEST_DPC'][0]='test';
$__ACTIONS['TEST_DPC'][1]='reset_smdr';
$__ACTIONS['TEST_DPC'][2]='read_smdr';
$__ACTIONS['TEST_DPC'][3]='sync_smdr';
$__ACTIONS['TEST_DPC'][4]='smdr_datespace';

$__LOCALE['TEST_DPC'][0] = 'TEST_DPC;SMDR;SMDR';
$__LOCALE['TEST_DPC'][1] = '_SMDRLIST;SMDR List;SMDR List';
$__LOCALE['TEST_DPC'][2] = '_f0;A/A;A/A';
$__LOCALE['TEST_DPC'][3] = '_f1;Line;Εσωτερ.';
$__LOCALE['TEST_DPC'][4] = '_f2;L2;L2';
$__LOCALE['TEST_DPC'][5] = '_f3;Date;Ημ/νία';
$__LOCALE['TEST_DPC'][6] = '_f4;Time;Ωρα';
$__LOCALE['TEST_DPC'][7] = '_f5;Number;Νουμερο';
$__LOCALE['TEST_DPC'][8] = '_f6;Type;Τυπος';
$__LOCALE['TEST_DPC'][9] = '_f7;Type;Τυπος';
$__LOCALE['TEST_DPC'][10] = '_f8;Type;Τυπος';

class test {

    var $smdrfilename;
    var $smdrfile;
	var $ps;
	var $pl;
	var $message;
	var $year;

	function test() {
	
/*	  if (!paramload('SMDR','sourcetype')) {
	    $this->smdrfilename = paramload('SHELL','prpath') . paramload('SMDR','sourcefile');
        //$this->smdrfile = file (paramload('SHELL','prpath') . paramload('SMDR','sourcefile'));	
	  }
	  else {
	    $this->smdrfilename = paramload('SMDR','sourcefile');
        //$this->smdrfile = file (paramload('SMDR','sourcefile'));	  
	  }
	  $this->ps = arrayload('SMDR','paternstart');
	  $this->pl = arrayload('SMDR','paternlength');
	  
	  $this->t_smdrlist = localize('_SMDRLIST',getlocal());	  	  */
	  $this->message = null;	  
	  
	  $this->year = "2003";
	
	}

    function event($evn) {
	
       switch ($evn) {		
          case "reset_smdr"   : $this->reset_smdr(); break;	   
          case "read_smdr"    : $this->read_source_smdr(); break;					 	  
          case "sync_smdr"    : $this->sync_smdr(); break;		  
       }	
	}	

	function action($act) {
      $__USERAGENT = GetGlobal('__USERAGENT');	
	  $apo = GetParam('apo');
	  $eos = GetParam('eos');
	  
	  //print $apo.$eos;
	
      switch ($act) {	
          case "smdr_datespace":	  	
          case "smdr"         : $out = $this->show_smdr($apo,$eos); break;	   					 	  
          case "sync_smdr"    : switch ($__USERAGENT) {
	                              case 'HTML' : $out = "SMDR Synchronization\n" . $this->message; break;
	                              case 'GTK'  : $out = "SMDR Synchronization\n" . $this->message; break;
								  case 'CLI'  :
	                              case 'TEXT' : $out = "SMDR Synchronization\n" . $this->message; break;											   
	                            }	
								break;
								//$out = $this->show_smdr(); break;		  
          case "read_smdr"    : //$out = $this->show_smdr(); break;  								
      }		
	    
	    
	  return ($out);
	}
	
	
	function show_smdr($fromdt='',$todt='') {
	   $db = GetGlobal('db');
	   $a = GetReq('a');
	   $g = GetReq('g');
	   $p = GetReq('p');	 
  	
       //title
       $out = setNavigator($this->t_smdrlist);   	
	
	   //$sSQL = "select recid,smdr0,smdr1,smdr2,smdr3,smdr4,smdr5,smdr6,smdr7,entrydate from smdr";
	   $sSQL = "select recid,smdr0,smdr5,smdr6,entrydate from smdr";
	   	   
	   if (($a) && ($a!="all")) {
	     $sSQL .= " where (smdr0=" . $db->qstr($a) . ")";
	     if ($g) $sSQL .= " AND (smdr6=" . $db->qstr($g) . ")";	   
		 
 	     if ($fromdt) $sSQL .= " AND (entrydate>=" . $db->qstr(trim(reverse_datetime($fromdt))) .")";
 	     if ($todt) $sSQL .= " AND (entrydate<=" . $db->qstr(trim(reverse_datetime($todt))) .")";		 
	   }
	   else {
 	     if ($fromdt) $sSQL .= " where (entrydate>=" . $db->qstr(trim(reverse_datetime($fromdt))) .")";
 	     if ($todt) $sSQL .= " AND (entrydate<=" . $db->qstr(trim(reverse_datetime($todt))) .")";		   
	   }	
  
	   
       $browser = new browseSQL($this->t_smdrlist. " > " . $a,null,null,localize('_f0',getlocal()).",".
	                                                                    localize('_f1',getlocal()).",".
																		localize('_f3',getlocal()).",".
																		localize('_f6',getlocal()).",".
																		localize('_f7',getlocal()));
	   $out .= $browser->render($db,$sSQL,"smdr","smdr",30,$this,1,1,1,0);
	   unset ($browser);
	   
	   $out .= $this->getdatespace();	      
	     	 
	   return ($out);
	}
	
	
	function read_source_smdr($startfrom=0) {
	   $db = GetGlobal('db');
	   
	   $bytesreaded = ($startfrom*4096);
	   $maxps = count($this->ps);
	   
	   if ($fp = @fopen ($this->smdrfilename , "r")) {
	      
	     //dummy read to start from correct point
		 for ($i=0;$i<$startfrom;$i++) {
  	       $readed = fgets($fp,4096);		//echo ">>>>>",$readed; 
	     }		   
         //foreach ($this->smdrfile as $dline_num => $dline) {				  
	     while (!feof($fp)) {
		              
		   $dline = fgets($fp,4096);
		   if ($dline) {
		     $bytesreaded+=4096;		   
		   
		     for ($i=0;$i<$maxps;$i++) {
		       $record[$i] = substr($dline,$this->ps[$i],$this->pl[$i]);
		     }
			 
			 $rdate = explode(":",$record[2]);
			 $r2date = $this->year."-".$rdate[0]."-".$rdate[1]." ".$record[3];
		   
             $sSQL = "insert into smdr (smdr0,smdr1,smdr2,smdr3,smdr4,smdr5,smdr6,smdr7,smdr8,entrydate) values (" . 
		           $db->qstr($record[0]) . "," .
                   $db->qstr($record[1]) . "," .				   
                   $db->qstr($record[2]) . "," .
                   $db->qstr($record[3]) . "," .
                   $db->qstr($record[4]) . "," .
                   $db->qstr($record[5]) . "," .
                   $db->qstr($record[6]) . "," .
                   $db->qstr($record[7]) . "," .	
                   $db->qstr($record[8]) . "," .				  
                   $db->qstr($r2date) . ")";		
				   
		     $res = $db->Execute($sSQL); 
		   }	 		   
         }
		 fclose($fp); 
	   
 	     setInfo(" SMDR Source readed!"); 
		 $this->message .= ($bytesreaded/4096)." SMDR lines added successfully !\n"; 
	   }
	   else {
	     setInfo(" SMDR Source NOT readed!");  
 		 $this->message .= "SMDR file NOT readed successfully !\n";
	   }	 
		
	   return ($bytesreaded/4096);	
	}
	
	
   function reset_smdr() {
	    $db = GetGlobal('db'); 

        //delete table if exist
  	    $sSQL = "drop table if exists smdr";
        $db->Execute($sSQL);
		$sSQL = "create table smdr " .
                    "(" .
	                "recid integer auto_increment primary key," .
	                "smdr0 varchar(64)," .
	                "smdr1 varchar(64)," .
	                "smdr2 varchar(64)," .
	                "smdr3 varchar(64)," .
	                "smdr4 varchar(64)," .
	                "smdr5 varchar(64)," .	
	                "smdr6 varchar(64)," .
	                "smdr7 varchar(64)," .
	                "smdr8 varchar(64)," .															
	                "entrydate datetime" .					
                    ")";
        $db->Execute($sSQL); 
		
		//delete log file
	    $logfile = paramload('SHELL','prpath') . "smdr.log";		
		$delete = unlink($logfile);  
			
		setInfo(" SMDR Reset successfully!");
    }	
	
	
	
    function savelog($bytes) {
       
	  $logfile = paramload('SHELL','prpath') . "smdr.log";
	  
	  $sdate = date('d/m/Y');
	  $stime = date('h:i:s A');
	  $logline = "$sdate;$stime;$bytes;\n"; //save bytes readed so to open next time from this point of file
	  	   
	  if ($fp = fopen ($logfile , "a+")) {
               fwrite ($fp, $logline);
               fclose ($fp);
			   $this->message .= "Log file updated successfully !\n";							  
      }
      else {
               $this->message .= "Log file failed to update !\n";	
      }	   
    }
   
    function loadlog() {
	  $logfile = paramload('SHELL','prpath') . "smdr.log";
	  
	  if ($fp = fopen ($logfile , "r")) {
               $recs = fread ($fp, filesize($logfile));
               fclose ($fp);	
			   
	           $logline = explode(";",$recs);
	           $clog = count($logline);
	  
	           $out = ($logline[$clog-2]); //echo ">>>",$out,"<<<<<";
   			   $this->message .= "Log file opened at $out lines!\n";
	  
	           return $out;			   						  
      }

	  return 0; //start of database      
    }	
   

    function sync_smdr() {
	   
	   $this->message = null;
	
	   $startfrom = $this->loadlog();
	   $bytesreaded = $this->read_source_smdr($startfrom);
	   if ($bytesreaded) $this->savelog($bytesreaded);   
	}   
	
	
	function getdatespace() {
	      $a = GetReq('a');
		  $g = getReq('g');
	
		  $dater = new datepicker();	
		  
		  $toprint = $dater->renderspace(seturl("t=smdr&a=$a&g=$g"),"smdr_datespace");
		  return ($toprint);
	}
	
	function handle_date($dtime,$ds=":") {
	   $parts = explode(" ",$dtime);
	   
	   $date = $parts[0];
	   
	   $dparts = explode("-",$date);
	   //echo $dparts[1].$ds.$dparts[0];
	   
	   return ($dparts[1].$ds.$dparts[0]);	      
	}	

	
	
    function browse($packdata,$view) {
	
	   $data = explode("||",$packdata);
	
       $out = $this->viewsmdr($data[0],$data[1],$data[2],$data[3],$data[4]);//,$data[5],$data[6],$data[7],$data[8],$data[9]);

	   return ($out);
	}		
	
    function viewsmdr($id,$f1,/*$f2,$f3,$f4,$f5,*/$f6,$f7,/*$f8,*/$edate) {
	   $a = GetReq('a');
	   
	   $link = seturl("t=smdr&a=$f1&g=&p=$p" , $f1);
	   $link_disa = seturl("t=smdr&a=$f1&g=$f7&p=$p" , $f7);	   
							  
	   $data[] = $id;
	   $attr[] = "left;10%";
	   $data[] = $link;   
	   $attr[] = "left;12%";
	   $data[] = reverse_datetime($edate);//$f2;   
	   $attr[] = "left;36%";
	   //$data[] = str_replace(":","/",$f3);    
	   //$attr[] = "left;12%";
	   //$data[] = $f4;   
	   //$attr[] = "left;12%";
	   //$data[] = $f5;   
	   //$attr[] = "left;12%";	   
	   $data[] = $f6 . "&nbsp";   
	   $attr[] = "left;12%";	
	   
	   if (strstr($f7,"DISA")) $data[] = $link_disa;
	                      else $data[] = $f7 . "&nbsp";   
	   $attr[] = "left;12%";
	   
	   $myarticle = new window('',$data,$attr);
	   
	   $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::");
	   unset ($data);
	   unset ($attr);

	   return ($out);
	}	
	
	function headtitle() {
	   $a = GetReq('a');
	   $g = GetReq('g');
	   $p = GetReq('p');
	   $t = GetReq('t');
	   $sort = GetReq('sort');
	
		             $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=0",  localize('_f0',getlocal()) );
					 $attr[] = "left;10%";							  
					 $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=1" , localize('_f1',getlocal()) );
					 $attr[] = "left;12%";
					 //$data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=2" , localize('_f3',getlocal()) );
					 //$attr[] = "left;12%";
					 $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=2" , localize('_f3',getlocal()) . "-" . localize('_f4',getlocal()) );
					 $attr[] = "left;36%";
					 //$data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=4" , localize('_f4',getlocal()) );
					 //$attr[] = "left;12%";
					 //$data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=5" , localize('_f5',getlocal()) );
					 //$attr[] = "left;12%";
					 $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=3" , localize('_f6',getlocal()) );
					 $attr[] = "left;12%";					 
					 $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=4" , localize('_f7',getlocal()) );
					 $attr[] = "left;12%";					 

					 $mytitle = new window('',$data,$attr);
					 $out = $mytitle->render(" ::100%::0::group_form_headtitle::center;100%;::");
					 unset ($data);
					 unset ($attr);	
	   
	   return ($out);
	}	
	
	function iam() {
	
	  return 'my name is test';
	}	
	
};
}
//}
//else die("DATABASE DPC REQUIRED! (" .__FILE__ . ")");
?>