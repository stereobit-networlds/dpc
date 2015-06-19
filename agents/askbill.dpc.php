<?php
if (!defined("ASKBILL_DPC")) {
define("ASKBILL_DPC",true);

$__DPC['ASKBILL_DPC'] = 'askbill';

require ("gengtk.gtk.php");
//$d = GetGlobal('controller')->require_dpc('askbill/gengtk.lib.php');
//require_once($d); 
require ("mbox.gtk.php");
//$e = GetGlobal('controller')->require_dpc('askbill/mbox.lib.php');
//require_once($e); 
require ("excel.lib.php");
//$z = GetGlobal('controller')->require_dpc('askbill/excel.lib.php');
//require_once($z); 

class askbill {  
   
   var $action;
   var $path;
   var $proj;


   function askbill() {	
      global $argv; 
	  
	  $this->proj = null;  
	  $this->path = null;
	  
    /*  if (!class_exists('gtk')) {	  
      if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') 
        dl('php_gtk.dll'); 
      else 
        dl('php_gtk.so');   
	  }	*/
   
      //define ('STDIN',fopen("php://stdin","r"));
   }  

   function render() {      	   
		  
       while (!0) {

          echo $this->proj."ASKBILL $>"; 

		  //$this->action = trim(fgets(STDIN,256)); //echo $action;
		  
		  $command = explode(" ",trim(fgets(STDIN,256)));
		  $this->action = $command[0]; 
  
          switch ($this->action) {
           case 'level'  : $ret = $this->userLevelID; break;
           case 'ver'    : $ret =  'shell script engine V0.01 on PHP'. phpversion(); break;
		   case 'time'   : 
           case 'date'   : $ret = date("d-M-Y H:i:s", time()); break;
           case 'foo'    : $ret = 'bar'; break;	
		   case 'quit'   :
		   case 'exit'   :			
           case 'q'      : $this->quit(); exit(); break;

		   case 'mis'    : $ret = $this->exportCrystalReportToPDF($command[1],$command[2]); break;		   
		   case 'explore': $ret = $this->iexplorer($command[1]); break;
		   case 'supply' : $ret = $this->search_excel($command[1]); break;	
		   	   					   
			   
           default       : //$ret = $this->exe_project($command[0]);
		                   $ret = $this->search_excel($command[0]);
          }		
		  
		  if ($ret) echo $ret . "\n";  
       }   
   } 
   
   function quit() {
   
       //$this->http_connection->Close(); 
	   //echo "Connection closed!\n";
   
       fclose(STDIN);
	   //die('Bye');
   }
   
   function use_project($path,$proj) {   
   
      if (is_dir($path.$proj)) {

        $this->path = $path;
		$this->proj = $proj;	
		
		//unset ($this->project); //if is any
		//$this->project = new startup($this->path,$this->proj); 
		
		$ret = 'Done!';
	  }
	  else
	    $ret = 'NOT Done!!!';
		
	  return ($ret);	
   }
   
   function exe_project($cmd) {
   
    /*  if ((isset($this->path) && isset($this->proj)) &&
	      (is_dir($this->path.$this->proj)) && ($cmd)) { 
	  
	    //echo $this->path,$this->proj,'<<<<<';
        $pr = new startup($this->path,$this->proj);
        $pr->render('SH',$cmd);   
		unset($pr);
		
	    $ret = 'ok!';
	  }
	  else 
	    $ret = '<>';*/
	  
	  return ($ret);
   }
   
   function search_excel($cmd) {
   
     $result = null;
	 $sheet = null;
   
     $x = new excel();
	 $ret = "----------- Searhing AANKAL...\n";
	 $ret .= $x->search('N:\ASKBILL\bin\aankal.xls',$cmd,$result['AANKAL'],$sheet['AANKAL']);
	 $ret .= "----------- Searhing KPG...\n";	 
	 $ret .= $x->search('N:\ASKBILL\bin\kpg.xls',$cmd,$result['KPG'],$sheet['KPG']);
	 $ret .= "----------- Searhing IASON...\n";	 
	 $ret .= $x->search('N:\ASKBILL\bin\iason.xls',$cmd,$result['IASON'],$sheet['IASON']);
	 
	 
	 //print_r ($result);
	 
	 $ret .= $this->search_best_price($result,$sheet);
	 	 
	 return $ret;
   } 
   
   function search_best_price($recarray,$sheet) {
   
     $offset['AANKAL'] = 3;
     $offset['KPG'] = 3;	 
     $offset['IASON'] = 4;	 
   
     $index=null;
	 $best=99999999.99;
     foreach ($recarray as $id=>$record) {
	
	   if (is_array($record)) {
	
         //reduce prise	   
         if ($id=='IASON') 
	         $percent = 20;  
         elseif ($id=='AANKAL') {
           if (($sheet[$id]=='hp') || ($sheet[$id]=='HP'))
	         $percent = 8; 
	       else		  
             $percent = 10;
	     }		 
		 elseif ($id=='KPG')
		   $percent = 2;  
		   	   
		   
		 $ret .= "PERSENT ($id):".$percent."\n";  

	     $current_offset = $offset[$id];
	     $p = trim($record[$current_offset]);//str_replace(',','.',trim($record[$current_offset]));		 
		 $_price = floatval($p);
		 $price = ($p - ($p*$percent)/100);
		 //echo $price,">>>>>>>\n";
		 if ($price<=$best) {
	         $index = $id;
		     $best = $price;
			 //echo 'best ',$best,' ',$price;		 
		 }
		/* 
	     //echo $id;
	     foreach ($record as $i=>$rec) {
	       //echo "\n\n",$rec,"\n\n";
		   if (is_float(str_replace(',','.',trim($rec)))) {//(strstr($rec,',')) {//has ,
		   
		   echo $rec, ' ';
		   $current = floatval(str_replace(',','.',$rec)); 
		   //echo $current;
	       if (is_float($current) && ($current<=$best)) {echo $current,'>>>>>>>>>>>>>>>>',"\n";
	         $index = $id;
		     $best = $current;
			 echo 'best ',$best;
	       }	 
		   }
	     }*/
	   }	   
	 }
	 
	 $ret .= $index;
	 
	 $msg = "The best price is " . $best . " from supplier " . $index;
     //$nAnswer = MessageBox( $msg, "Answer", MB_OK + MB_ICONQUESTION + MB_DEFBUTTON2 + MB_CENTER);
	    
     $ret = $msg;
	 

      /*  $dialog = new GtkMessageDialog(Gtk::GtkWindow, Gtk::DIALOG_MODAL | Gtk::DIALOG_DESTROY_WITH_PARENT,Gtk::MESSAGE_INFO, Gtk::BUTTONS_OK,$ret);

        $dialog->run();

        $dialog->destroy();*/
	 
	 return ($ret);
   }
   
   
   function iexplorer($url) {
   
     //open PDF Documents with Internet Explorer

     $browser = new COM("InternetExplorer.Application");
     $browser->Visible = true;
     $browser->Navigate($url);   
   }
   
function exportCrystalReportToPDF( $my_report, $my_pdf )
{
  $my_report = 'c:\\panik_b_ekptoseis.rpt';
  $my_pdf = 'c:\\ppdf.pdf';

  // by dfcp/mar/06
  $ObjectFactory= New COM("CrystalReports8.ObjectFactory.1");
  $crapp = $ObjectFactory->CreateObject("CrystalDesignRunTime.Application");
  $creport = $crapp->OpenReport($my_report, 1);

  $creport->ExportOptions->DiskFileName=$my_pdf;
  $creport->ExportOptions->PDFExportAllPages=true;
  $creport->ExportOptions->DestinationType=1; // Export to File
  $creport->ExportOptions->FormatType=31; // Type: PDF
  $creport->Export(false);
  
  
  
  $this->iexplorer($my_pdf);
}    

function cr() {

$crapp = new COM("CrystalDesignRunTime.Application");
$creport = $crapp->OpenReport("d:\\athermal\\reports\\backlog.rpt", 1);
$creport->SelectPrinter("winspool", "HP LaserJet 1200 Series PCL",
"Ne01:");
$creport->PaperOrientation = 0;
$creport->PrintOut(False);
}   
     
};
}

//$sh = new askbill();
//$sh->render();
//unset($sh);

?>