<?php

////////////////////////////////////////////////////////////////
//   SECURITY LEVEL FUNCTIONS
////////////////////////////////////////////////////////////////
function seclevel($modulename,$levelofsec) {

   //static $i=0;
   $db = GetGlobal('db');
   
   ///////////////////////////////////////////////////memory in!!!!
   $sec = GetGlobal('__DPCSEC');
   $sec2 = GetGlobal('__DPCSEC2');	 //print_r($sec2);
   
   $ret = 1; //default
	 
   if ($sec2[$modulename]) {//firts the runtime command 'security'into pcntl
       
       $parts = explode(";",$sec2[$modulename]);
	   //echo "<br>",$modulename,"<br>",implode(',',$parts);   
	   if ($parts[$levelofsec+1] >= $parts[0])
	     //$ret =  1;//allow
		 return 1;
	   else
	     //$ret = 0; //deny	 
		 return 0;
   }
   elseif (isset($sec[$modulename])) {//last the code itself
     //echo "<br>",$modulename,"<br>";   
     $parts = explode(";",$sec[$modulename]);
	 
	 if ($parts[$levelofsec+1] >= $parts[0])
	   //$ret =  1;
	   return 1;
	 else
	   //$ret = 0;  
	   return 0;
   }
   /*else
     echo $modulename,'<br>';
	 
   return 1;	 */
   
   //return ($ret);	   //disable db and text!!!!!!!!!!!!!!!
   
   //echo $i++,'.',$modulename,'<br>';
   
   if ((!paramload('SHELL','modsdb')) || (!$db)) { //text file  
	   //echo "$modulename>";
       //$level_file = file (paramload('SHELL','prpath') . "modules.csv");
       //echo paramload('SHELL','prpath') . "modules.csv";
	   
	  if ($seslevelmodules = GetSessionParam('levelmodules')) {
	   //in memory
	   $level_file = unserialize($seslevelmodules);
	  }
	  else {	   
	   
       if (is_readable("modules.csv")) //in root	  
	     $level_file = file("modules.csv");
	   elseif (is_readable("cp/modules.csv")) //in cp
         $level_file = file("cp/modules.csv");  
	   elseif (is_readable("../modules.csv")) //in subdir in cp
         $level_file = file("../modules.csv");  	
	   else
         echo "Configuration warning, modules.csv not exist!";

       SetSessionParam('levelmodules',serialize($level_file));		
	   //echo '>'; print_r($level_file);		 
	  }
	  
	  if (!empty($level_file)) {
         foreach ($level_file as $line_num => $line) {	 
		   $split = explode (";", $line);

		   if ($split[0] == $modulename) {
               $userlevel = $levelofsec+2; 
			   //print $split[1];
			   //print $split[$userlevel];
			   if ($split[$userlevel] >= $split[1]) return 1;
           }
         }
	  }
   }
   else { //db table
      //echo "SQL--$modulename>";
      $sSQL = "select * from modules WHERE name=" . $db->qstr($modulename);
      //echo $sSQL;
      $result = @$db->Execute($sSQL,null,1); //print_r ($result);

      if ($result)  {		  
         $num = ($levelofsec+1); //print $num;
         $fld = "usr".$num; //print $fld;

	     if ($db->model=='ADODB') {				 
             if ($result->fields[$fld]) { 

				 if ($result->fields[$fld] >= $result->fields['secid']) {
				   //print $result->fields[$fld];
				   return 1;	  
				 }
	 	     }
         }
		 else {
             if ($result[$fld]) { 

				 if ($result[$fld] >= $result['secid']) {
				   //print $result->fields[$fld];
				   return 1;	  
				 }
	 	     }		   
         }
	  }	   	 
   }

   return 0;
}

function get_seclevels() { //MOVED TO USERS AND SENUSERS

   $levels = explode(",",paramload('SENUSERS','groups'));
   return ($levels);

}




////////////////////////////////////////////////////////////////
//   LOCALE FUNCTIONS
////////////////////////////////////////////////////////////////

function localize($codename,$lang=null,$enc=null,$encto=null,$debug=null) {
   $db = GetGlobal('db');
   //static $ltime;
   //static $i=0;
   //$mytime = getmymicrotime();
   
   if (!isset($lang)) $lang = getlocal();
   
   $encodingsperlan = arrayload('SHELL','char_set'); //print_r($encodingsperlan);
   $enc = ($enc?$enc:$encodingsperlan[$lang]);
   //echo $enc,'>>>>><br>';
   //$encto = ($encto?$encto:paramload('SHELL','charset')); //SOS:disable DEFAULT charset to ENCODE char_set
   //echo $encto,'>>>>><br>';   
   
   ///////////////////////////////////////////////////memory in!!!!
   $loc = GetGlobal('__DPCLOCALE');
   //echo count($loc),'>>>';
   if (isset($loc[$codename])) {
     //if ($debug) echo $loc[$codename],"<br>",$codename,"<br>";   
     $parts = explode(";",$loc[$codename]);
	 if ($parts[$lang]) {
	   if ($encto) 
	     return iconv($enc,$encto,$parts[$lang]);
	   else	
	     return ($parts[$lang]);
	 }  
   }	
   //if (stristr($codename,'_UNK')) return (null);//UNK restict !!!!!!!!
   //echo $i++,'.',$codename,'<br>';
   //print_r($loc[$codename]);	  
	  
   if ((!paramload('SHELL','langdb')) || (!$db)) { //text file
     //echo "$codename><br>";   
	 //if (file_exists(paramload('SHELL','prpath') . "locale.csv")) {
	 //echo paramload('SHELL','prpath') . "locale.csv";   
     //$locale_file = file (paramload('SHELL','prpath') . "locale.csv");
	 
	 if ($seslocales = GetSessionParam('locales')) {
	   //in memory
	   $locale_file = unserialize($seslocales);
	 }
	 else {
       if (is_readable("locale.csv")) //in root	  
	     $locale_file = file("locale.csv");
	   elseif (is_readable("cp/locale.csv")) //in cp
         $locale_file = file("cp/locale.csv");  
	   elseif (is_readable("../locale.csv")) //in subdir in cp
         $locale_file = file("../locale.csv");  	
	   else
         echo "Configuration warning, locale.csv not exist!";	
		
       SetSessionParam('locales',serialize($locale_file));		
	   //echo '>'; print_r($locale_file);
	 }  
	    
	 if (!empty($locale_file)) {	 
       //while (list ($line_num, $line) = each ($locale_file)) {
	   foreach ($locale_file as $line_num => $line) {	 
		   $split = explode (";", $line);
           //echo $line,'<br>';  
		   if ($split[0] == $codename) {
	         if ($encto)
	           return iconv($enc,$encto,trim($split[$lang+1]));
	         else	
			   return (trim($split[$lang+1]));
           }
       }
	 }
   }
   else {   //dbase table
      //echo "SQL--->$codename>";
      $sSQL = "select * from locale WHERE id=" . $db->qstr($codename);

      $result = @$db->Execute($sSQL,null,1); 

      if ($result)  {	
	  	  
             $num1 = ($lang+1); //print $num1;
			 $fld = "lan".$num1; //print $fld;

		     if ($db->model=='ADODB') {			 
			   $ret = $result->fields[$fld];
			 }
			 else {
			   //$res = $db->fetch_array($result); 
			   $ret = $result[$fld];
			 }
			 //echo '>>>>>>>>>>>>>>>>>>>'.$ret.'<br>';
			 //encoding in db must be set before ?....??!!!!!!
			 return ($ret);
			 
             /*if ($result->fields[$fld]) 
				 return (trim($result->fields[$fld]));	  */
	  }	 
   }

   //echo '>>>',getmymicrotime() - $mytime;
   //echo '>>>',"<br>";
   
   return ($codename);
}

//unlocalize used in search in diff lans and 
//do convertion from source text current lan to a specified
function unlocalize($text,$fromlan,$tolan,$like=0) {
   $db = GetGlobal('db');

   if ((!paramload('SHELL','langdb')) || (!$db)) { //text file
      //not supported....
	  return ($text);
   }
   else {   //dbase table
   
      $clan = $fromlan+1;
	  $tlan = $tolan+1;
   
      //echo "SQL--->$codename>";
      $sSQL = "select * from locale WHERE ";
	  $fld1 = "lan".$clan;
	  if ($like)
	    $sSQL .= "$fld1 like " . $db->qstr("%".$text."%");
	  else
	    $sSQL .= "$fld1=" . $db->qstr($text);
	  //echo $sSQL;	  	  

      $result = $db->Execute($sSQL,null,1); 

      if ($result)  {	
	  	  
			 $fld2 = "lan".$tlan; //print $fld;

		     if ($db->model=='ADODB') {			 
			   $ret = $result->fields[$fld2];
			 }
			 else {
			   //$res = $db->fetch_array($result); 
			   $ret = $result[$fld2];
			 }
			 //echo '>>>>>>>>>>>>>>>>>>>'.$ret.'<br>';
			 return ($ret);
			 
             /*if ($result->fields[$fld]) 
				 return (trim($result->fields[$fld]));	  */
	  }	 
   }

   return ($text);
}


function getlans() {

   $db = GetGlobal('db');
   $mylans = arrayload('SHELL','languages'); //titles of lans
   //print_r($mylans);
   if (count($mylans)>0) return ($mylans); //get the info from config  
   
   $lans = array();

   if ((!paramload('SHELL','langdb')) || (!$db)) { //text file 
     //echo "TEXT2>";   

     //$locale_file = file (paramload('SHELL','prpath') . "locale.csv");
	 
	 if ($seslocales = GetSessionParam('locales')) {
	   //in memory
	   $locale_file = unserialize($seslocales);
	 }
	 else {	 
	 
       if (is_readable("locale.csv")) //in root	  
	     $locale_file = file("locale.csv");
	   elseif (is_readable("cp/locale.csv")) //in cp
         $locale_file = file("cp/locale.csv");  
	   elseif (is_readable("../locale.csv")) //in subdir in cp
         $locale_file = file("../locale.csv");  	
	   else
         echo "Configuration warning, locale.csv not exist!";

	   SetSessionParam('locales',serialize($locale_file));		
	   //echo '>'; print_r($locale_file);
     }		 
		 
	 if (!empty($locale_file)) {	 

	   foreach ($locale_file as $line_num => $line) {	 
		   $split = explode (";", $line);

		   if ($split[0] == "_LANG") {
             $i = 1;
              while (trim($split[$i])!='') {
				 $lans[] = $split[$i];
				 $i+=1;
			 }
			 return ($lans);
           }
	   }	   
     }
   }
   else {   //dbase table
      //echo "SQL2>";
      $sSQL = "select * from locale WHERE id='_LANG'";

      $result = $db->Execute($sSQL,null,1); //print_r ($result);
	  
      if($result)  {	
			 
			  if ($db->model=='ADODB') {	

                $fields = $db->MetaColumns(locale); //print count($fields);			  
			   
		        for ($i=2;$i<=count($fields);$i++) {

                  $num1 = ($i-2); //print $num1;
			      $num2 = ($i-1); 
			      $fld = "lan".$num2; //print $fld;			  
                  if (trim($result->fields[$fld])!='') 
				    $lans[$num1] = $result->fields[$fld];
			    }		
			  }
			  else {
			    for ($i=2;$i<10;$i++) { 
				
                  if (trim($result[$i])) 
				    $lans[] = $result[$i];			  
			    }		
			  }	  

           return ($lans); 	  
	  }	  
   }

   return 0;
}
?>