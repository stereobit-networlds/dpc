<?php

class excel {

   function excel() {
   }
   
   /***
   * Grouping Rows optically in Excel Using a COM Object
   *
   * That was not easy, I have spent several hours of trial and error to get
   * this thing to work!!!
   *
   * @author Kulikov Alexey <a.kulikov@gmail.com>
   * @since  13.03.2006
   *
   * @see    Open Excel, Hit Alt+F11, thne Hit F2 -- this is your COM bible
   ***/
   function group() {
   //starting excel
   $excel = new COM("excel.application") or die("Unable to instanciate excel");
   print "Loaded excel, version {$excel->Version}\n";

   //bring it to front
   #$excel->Visible = 1;//NOT

   //dont want alerts ... run silent
   $excel->DisplayAlerts = 0;

   //create a new workbook
   $wkb = $excel->Workbooks->Add();

   //select the default sheet
   $sheet=$wkb->Worksheets(1);

   //make it the active sheet
   $sheet->activate;

   //fill it with some bogus data
   for($row=1;$row<=7;$row++){
       for ($col=1;$col<=5;$col++){

         $sheet->activate;
         $cell=$sheet->Cells($row,$col);
         $cell->Activate;
         $cell->value = 'pool4tool 4eva ' . $row . ' ' . $col . ' ak';
       }//end of colcount for loop
   }

   ///////////
   // Select Rows 2 to 5
//   $r = $sheet->Range("2:5")->Rows; //????????

   // group them baby, yeah
   $r->Cells->Group;

   // save the new file
   $strPath = 'tfile.xls';
   if (file_exists($strPath)) {unlink($strPath);}
   $wkb->SaveAs($strPath);

   //close the book
   $wkb->Close(false);
   $excel->Workbooks->Close();

   //free up the RAM
   unset($sheet);

   //closing excel
   $excel->Quit();

   //free the object
   $excel = null;   
   }
   
   function xls2cvs() {
   
// starting excel 
$excel = new COM("excel.application") or die("Unable to instanciate excel"); 
print "Loaded excel, version {$excel->Version}\n"; 

//bring it to front 
#$excel->Visible = 1;//NOT
//dont want alerts ... run silent 
$excel->DisplayAlerts = 0; 

//open  document 
$excel->Workbooks->Open("C:\\mydir\\myfile.xls"); 
//XlFileFormat.xlcsv file format is 6
//saveas command (file,format ......)
$excel->Workbooks[1]->SaveAs("c:\\mydir\\myfile.csv",6); 

//closing excel 
$excel->Quit(); 

//free the object 
$excel->Release(); 
$excel = null;    
   }
   
   
/**
 * @param $file string The excel file path
 * @param $keyword string The keyword
 * @param $result array The search result
 */

function searchEXL($file, $keyword, &$result) {
       $exlObj = new COM("Excel.Application") or Die ("Did not connect");
       $exlObj->Workbooks->Open($file);
       $exlBook = $exlObj->ActiveWorkBook;
       $exlSheets = $exlBook->Sheets;
   
       for($i = 1; $i <= $exlSheets->Count; $i++) {
           $exlSheet = $exlBook->WorkSheets($i);
       
           $sheetName = $exlSheet->Name;
       
           if($exlRange = $exlSheet->Cells->Find($keyword)) {
           $col = 1;
           while($fields = $exlSheet->Cells(1, $col)) {
                       if ($fields->Text == "") 
                   break;
                   
               $result[$sheetName]["FIELD"][] = $fields->Text;
               $col++;            
           } 
       
           $firstAddress = $exlRange->Address;
           $finding = 1;            
           $result[$sheetName]["TEXT"][] = $exlRange->Text;
           
           for($j = 1; $j <= count($result[$sheetName]["FIELD"]); $j++) {
               $cell = $exlSheet->Cells($exlRange->Row ,$j);
               $result[$sheetName]["ROW"][$finding - 1][$j - 1] = $cell->Text;
           }

           while($exlRange = $exlRange->Cells->Find($keyword)) {
                   if($exlRange->Address == $firstAddress)
                   break;            
           
               $finding++;
               $result[$sheetName]["TEXT"][] = $exlRange->Text;
               
               for($j = 1; $j <= count($result[$sheetName]["FIELD"]); $j++) {
                   $cell = $exlSheet->Cells($exlRange->Row ,$j);
                   $result[$sheetName]["ROW"][$finding - 1][$j - 1] = $cell->Text;
               }
               
           }
                     
           }
       
   }
   
   $exlBook->Close(false);
   unset($exlSheets);
   $exlObj->Workbooks->Close();
   unset($exlBook);
   $exlObj->Quit;
   unset($exlObj);
}

  function search($xlsfile,$keyword,&$record,&$xsheet) {
  
// The example of print out the result
$result = array();
$this->searchEXL($xlsfile, $keyword, $result);
//print_r($result);
foreach($result as $sheet => $rs){
   $ret = "Found at $sheet ....\n";
   $xsheet = $sheet;
   
   //echo "<table width=\"100%\" border=\"1\"><tr>";
   
   for($i = 0; $i < count($rs["FIELD"]); $i++) {
       //$ret .= $rs["FIELD"][$i] . "\n";
	   $datahead[] = $rs["FIELD"][$i];
   }	   
   
   //echo "</tr>";
   //$ret .= $rs["TEXT"][0]."\n";
   
   for($i = 0; $i < count($rs["TEXT"]); $i++) {
       //echo "<tr>";
       
       for($j = 0; $j < count($rs["FIELD"]); $j++) {
           $ret .= $rs["ROW"][$i][$j] . "\n";
		   $data[] = $rs["ROW"][$i][$j];
       }     
	   $record = $data;   
       //echo "</tr>";
	   $ret .= "--------------------------------------------\n";
   }
   //echo "</table>";  
  }
  //print_r($datahead);
  //print_r($data);
  return ($ret);   
}
}
?>