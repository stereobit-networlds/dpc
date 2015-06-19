<?php
if (defined("DATABASE_DPC")) {

$__DPCSEC['RCIMPORTDB_DPC']='1;1;1;1;1;1;1;8;9';

if ((!defined("RCIMPORTDB_DPC")) && (seclevel('RCIMPORTDB_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCIMPORTDB_DPC",true);

$__DPC['RCIMPORTDB_DPC'] = 'rcimportdb';

$__EVENTS['RCIMPORTDB_DPC'][0]= "cpimportdb";
$__EVENTS['RCIMPORTDB_DPC'][1]= "cpuploaddb";
$__EVENTS['RCIMPORTDB_DPC'][2]= "cpupdatedb";

$__ACTIONS['RCIMPORTDB_DPC'][0]= "cpimportdb";
$__ACTIONS['RCIMPORTDB_DPC'][1]= "cpuploaddb";
$__ACTIONS['RCIMPORTDB_DPC'][2]= "cpupdatedb";

$__LOCALE['RCIMPORTDB_DPC'][0]='RCIMPORTDB_DPC;Import database;Import database';
$__LOCALE['RCIMPORTDB_DPC'][1]='_RCIMPORTFILE;Import file;Import file';
$__LOCALE['RCIMPORTDB_DPC'][2]='_RCUPLOADDB;Upload;Μεταφόρτωση';

class rcimportdb {

    var $userLevelID;
	var $rec_counter;
	var $message;
	
	var $savepath;
	var $tables;
	var $title;
	
	var $post,$msg,$filename,$path;
	var $displaysql;
	
	//params
	var $datetime,$i;
    var $current_batch_records;	
	var $maxsize;
	var $resource_path, $target_path, $resourcetypes;
	
	var $errormsg, $checkmsg;
	var $synvfiles, $address;

	function rcimportdb() {
	    $UserSecID = GetGlobal('UserSecID');

        $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
			
		$this->path = paramload('SHELL','prpath');		
		$this->urlpath = paramload('SHELL','urlpath') .'/'. paramload('ID','hostinpath');
						
		$this->rec_counter = 0;
		$this->message = null;	
		$this->title = localize('RCIMPORTDB_DPC',getlocal());		
		
		$this->tables = array(0=>'categories',
		                      1=>'customers',
							  2=>'products',
							  3=>'forum_posts',
							  4=>'forum_replies',
							  5=>'paypal',
							  6=>'ppolicy',
							  7=>'products',
							  8=>'stats',
							  9=>'transactions',							  							  							  
							  10=>'users');
		$this->displaysql = null;	
		
		$ms = remote_paramload('RCIMPORTDB','uploadsize',$this->path);	
		$this->maxsize = $ms?$ms:1000000;//$this->MAXSIZE;				
		
		$this->resource_path = remote_paramload('RCIMPORTDB','attachresourcepath',$this->path);					  
		$this->target_path = remote_paramload('RCIMPORTDB','attachtargetpath',$this->path);		
	    $this->resourcetypes = remote_arrayload('RCIMPORTDB','attachtypes',$this->path);		
		
		$this->errormsg = null;
		$this->checkmsg = null;
		
        $this->address = remote_paramload('RCIMPORTDB','url',$this->path);		
        $this->syncfiles = remote_arrayload('RCIMPORTDB','files',$this->path);			
	}
	
    function event($sAction) {
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') {
	     
	     if (!GetReq('editmode'))		 
	       die("Not logged in!");//	
		 else
     	   //header("Location: cp.php?editmode=1&encoding=" . GetReq('encoding'));  
   	       die("Not logged in! <A href='../cp.php?editmode=1&encoding=".GetReq('encoding')."'>LOGIN</A>");//	
	   }	 
	   /////////////////////////////////////////////////////////////		
 
	   switch ($sAction) {
	   
		  case "cpupdatedb" :  $this->import_table(); break;									 						
		  case "cpuploaddb" :  $this->upload_csv_file(); break;	
		  case "cpimportdb" :  
		  default : 		  
       }
    }	
	
    function action($action) {
	     $__USERAGENT = GetGlobal('__USERAGENT');
		
	     if (!GetReq('editmode')) 
		   $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);	
		 
		 switch ($action) {	 	
 		   case "cpupdatedb"          : $out .= $this->done_import_scenario(); 
		                                if (($b=GetParam('batch')) && ($this->current_batch_records==abs($b)))
										  $out .= $this->form_next_batch();
										else 
		                                  $out .= $this->upload_database_form();
										//echo $b,'>>>',$this->current_batch_records;	  
		                                break;		 
 		   case "cpuploaddb"          : if ($this->post==true) {	 
		                                  $out .= $this->load_import_scenario();
									    }
										else
										  $out .= $this->upload_database_form();
										break;
										    
 		   case "cpimportdb"          : $out .= $this->upload_database_form();

		   default :    										  						  									  			
		 }							  

         return ($out);
    }
	
   function refresh_bulk_js($page,$timeout=null) {
   
      if (iniload('JAVASCRIPT')) {

	       $code = $this->javascript($page,$timeout);
	   
		   $js = new jscript;
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }   
   } 	
	
   //refresh to continue bulk proccess automaticaly
   function javascript($page,$timeout=null) {
   
     $mytimeout=$timeout?$timeout:100;
   
     $ret = "
function neu()
{	
	top.frames.location.href = \"$page\"
}
window.setTimeout(\"neu()\",$mytimeout);
";
	 
	 return ($ret);
   }	
	
	function upload_csv_file() {
	
	     if (GetParam('formdata')) {
		    $this->post = true;	
			return;
		 }
	
	     if ($_FILES['uploadfile'])  {

          $attachedfile = $_FILES['uploadfile'];
		  //print_r($attachedfile);
		  
		  //$myfilename = 'products.txt'; //echo $myfilename,"<br>";
		  
		  if ($myfilename)
		    $this->filename = $myfilename;
		  else	
		    $this->filename = $attachedfile['name'];					   
			
   	      $tpath = paramload('SHELL','prpath') . 'uploads';				
		
		  if (GetParam("topath")) //param at upload form   
		    $myfilepath = $tpath . GetParam("topath") . "/" . $this->filename;
		  else
		    $myfilepath = $tpath . "/" . $this->filename;
			
          //echo $myfilepath;
		  //copy it to admin write-enabled directory				   
		  if (@copy($attachedfile['tmp_name'],$myfilepath)) {
		
    	   setInfo("Upload " .$attachedfile['name']. " ok!");
		   $this->msg = "Upload " .$attachedfile['name']. " ok!"; //MEANS ERROR
		   //echo $msg;
		   $this->post = true;	
		   		   	   
		  }
		  else {
		
		   setInfo("Failed to upload file " . $attachedfile['name'] . " ! (Size Error?)"); 				   
		   $this->msg = "Failed to upload file " . $attachedfile['name'] . " ! (Size Error?)";
		   
		   $this->post = false;
		  }
		
        }
		else {
		  $this->msg = "Failed to upload file " . $attachedfile['name'] . " ! (Type Error?)";
		  $this->post = false;
		}  
		  
		return ($this->msg);  	
	
	}

	
   function upload_database_form($title=null,$cmd=null,$width=null) {
   
	      //echo $this->maxsize;
		  $mycmd = $cmd?$cmd:'cpuploaddb';
		  $mytitle = $title?$title:localize('_RCIMPORTFILE',getlocal());
		  
		  if ($editmode = GetReq('editmode'))
		    $mywidth = '100%';
		  else	
		    $mywidth = $width?$width:'100%';
		  
	      if ($this->post==false) {	   
	   
	        $swin = new window("",$this->msg);
	        $winout = $swin->render("center::70%::0::group_article_body::center::0::0::");	
	        unset ($swin);
	      }			  
		  
          $filename = seturl("t=$mycmd&editmode=".GetReq('editmode'));			  

          //manual data form
          $out  = "<FORM action=". "$filename" . " method=post class=\"thin\">";		  
  	      $out .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>Formated data :</FONT>"; 
          $out .= "<textarea name=\"formdata\" rows=\"20\" cols=\"80\">";
		  //$out .= $scenario;
		  $out .= "</textarea><br>";		  
  	      $out .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>Delimiter :</FONT>"; 		  
		  $out .= "<input type=\"input\" name=\"delimiter\" value=\"\">"; 	
		  
          $out .= "<input type=\"hidden\" name=\"editmode\" value=\"$editmode\">";		  
          $out .= "<input type=\"hidden\" name=\"FormName\" value=\"$mycmd\">"; 	 			  
          $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"$mycmd\">&nbsp;";		
		  	
			
          $out .= "<input type=\"submit\" name=\"Submit\" value=\"Submit\">";		
          $out .= "</FORM>"; 	  	
		  

		
	      //upload file(s) form
          $out  .= "<FORM action=". "$filename" . " method=post ENCTYPE=\"multipart/form-data\" class=\"thin\">";
		  
          $out .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" VALUE=\"".$this->maxsize."\">"; //max file size option in bytes
          $out .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>"; 			 	   
          $out .= localize('_RCIMPORTFILE',getlocal()) . ": <input type=FILE name=\"uploadfile\">";		    
		  
          $out .= "<input type=\"hidden\" name=\"editmode\" value=\"$editmode\">";		  
          $out .= "<input type=\"hidden\" name=\"FormName\" value=\"$mycmd\">"; 	  			  
          $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"$mycmd\">&nbsp;";			
			
          $out .= "<input type=\"submit\" name=\"Submit\" value=\"".localize('_RCUPLOADDB',getlocal())."\">";		
          $out .= "</FONT></FORM>"; 	  	
		
		  $wina = new window($mytitle,$out);
		  $winout .= $wina->render("center::$mywidth::0::group_dir_body::left::0::0::");
		  unset ($wina);		  		  
		
		  return ($winout);   
   }
   
   function load_import_scenario($title=null,$cmd=null,$width=null,$auto_scenario=null) {
   
      if ($auto_scenario) {//sync task purpose
	      $myscenariofilename = 'import_'.$auto_scenario .'.ini';
          if (file_exists($this->path.$myscenariofilename)) {
		    
  	        $scenario = file_get_contents($this->path.$myscenariofilename);
			return true;
		  }				
		  
		  return false;  
	  }
      else {
          $mytitle = $title?$title:$this->filename;
          $mycmd = $cmd?$cmd:'cpupdatedb';
		  
		  if ($editmode = GetParam('editmode'))
		    $mywidth = '100%';
		  else	
		    $mywidth = $width?$width:'100%';
   
	      if ($this->post==true) {	   
		  
	          $swin = new window("",$this->msg);
	          $winout = $swin->render("center::70%::0::group_article_body::center::0::0::");	
	          unset ($swin);
			  
	      }	
		  
		  if ($data=GetParam('formdata')) {
		    $d = explode(GetParam('delimiter'),$data);
			$myscenario = 'import_'.$d[0].'.ini'; //1st line is the file name = scenario name  
		  }
		  else {
		    $sfile = $_FILES['uploadfile'];
            $myscenario = 'import_'.$sfile['name'].'.ini';
		  }	  
		  //echo $this->path . $myscenario;   
          if (file_exists($this->path.$myscenario)) 
  	        $scenario = file_get_contents($this->path.$myscenario);		  
			
          $filename = seturl("t=" . $mycmd . "&editmode=" . GetParam('editmode'));			  		  
		  
	      //params form
          $out  = "<FORM action=". "$filename" . " method=post class=\"thin\">";
          $out .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>"; 			 	       
  	      $out .= "Scenario :"; 
          $out .= "<textarea name=\"scenario\" rows=\"20\" cols=\"80\">";
		  $out .= $scenario;
		  $out .= "</textarea><br>";		  
  	      $out .= "Batch :"; 
          $out .= "<input type=text name=\"batch\" value=\"\">";
  	      $out .= "Refresh :"; 
          $out .= "<input type=text name=\"refresh\" value=\"\">";		  
		  		  
          $out .= "<input type=\"hidden\" name=\"FormName\" value=\"$mycmd\">"; 
		  if ($fdata=GetParam('formdata')) {//manual entry
            $out .= "<input type=\"hidden\" name=\"formdata\" value=\"".$fdata."\">";
			$out .= "<input type=\"hidden\" name=\"delimiter\" value=\"".GetParam('delimiter')."\">"; 			  		  
		  }	
		  else			  	
            $out .= "<input type=\"hidden\" name=\"filedata\" value=\"$this->filename\">"; 			  
		  
          $out .= "<input type=\"hidden\" name=\"editmode\" value=\"$editmode\">";		  
          $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"$mycmd\">&nbsp;";			
			
          $out .= "<input type=\"submit\" name=\"Submit\" value=\"Update database\">";		
          $out .= "</FONT></FORM>"; 	  	
		
		  $wina = new window($this->filename,$out);
		  $winout .= $wina->render("center::$mywidth::0::group_dir_body::left::0::0::");
		  unset ($wina);	  		  
		
		  return ($winout);  
	  }	  
   }
   
   function done_import_scenario($auto_scenario=null) {
   
      if ($auto_scenario) {//sync task purpose
	  
          $ret = $this->msg;
		  $ret .= "<br><br>" . $this->displaysql;	  
	  }
      else {   
	      if (($_POST['FormAction']=='cpupdatedb')||(GetReq('t')=='cpupdatedb')) {		  
			  //.....
	      }
		  else {   
          }
		  
          $ret = $this->msg;
		  $ret .= "<br><br>" . $this->displaysql;
		  
	  }	  
	  return $ret;   
   }	
   
   function import_table($auto_scenario=null,$remote_source_file=null,$line_delimiter=null) {
      $db = GetGlobal('db');
	  $t=getdate();
	  $this->datetime = date('Y-m-d h:m:s',$t[0]);
	  $bid = GetReq('batchid')?GetReq('batchid'):0;		  
		  
      if ($auto_scenario) {//sync task purpose		
	      $scenario = 'import_'.$auto_scenario .'.ini';
  	      $approved = file_exists($this->path.$scenario);
      }
	  else {  
		  if ($data=GetParam('formdata')) {
		    $d = explode(GetParam('delimiter'),$data);
			$scenario = 'import_'.$d[0].'.ini'; //1st line is the file name = scenario name  
			$approved = true;
		  }
		  else {	  
	        $scenario = 'import_'.GetParam('filedata').'.ini';
			$approved = file_exists($this->path.$scenario);
		  }	
	   }	  
		  //echo $this->path . $scenario;   
      
       if ($approved) {
	   
	     //READ SCENARIO  
	   
         /*if ($myscenario = GetParam('scenario'))
		   $sc = parse_ini_string($myscenario);//post data ///////////////////////////////////////////////>php 5.3.0
	  	 else*/
         $sc = parse_ini_file($this->path.$scenario);
		 //echo '<pre>';
         //print_r($sc);
		 //echo '</pre>';
			
		 if ($sc['cleardb']) {
		   $this->reset_db($db,$sc['table']);
		   if ($sc['verbose'])
		     $this->displaysql = '<br>table ['.$sc['table'].'] reset successfuly';
		 }
		 else {  
		   if ($sc['verbose'])
		     $this->displaysql = '<br>table ['.$sc['table'].'] bypass reset';
		 }		
			
		 if ($attachf = $sc['attachfiles']) {
			  $find_id = $sc['attachfind'];
			  $save_id = $sc['attachsave'];
			  $rpath = $sc['attachresourcepath'];
			  $tpath = $sc['attachtargetpath'];			  
		 }

		 //$source = explode('\r\n',file_get_contents($this->path . 'uploads/' . GetParam('filedata')));
         if ($auto_scenario) {//sync task purpose			
		 
            $source = explode($line_delimiter,file_get_contents($this->address . $remote_source_file));
	     }
	 	 else {		
			if ($data=GetParam('formdata')) {//manual entry
			  $source = explode(GetParam('delimiter'),$data); //make array from form data using sep or \r\n 
			  array_shift($source); //extract 1st line = scenario
			}  
			else   
              $source = file($this->path . 'uploads/' . GetParam('filedata'));
		 }	
         //echo '<pre>';
         //print_r($source);  
         //echo '</pre>';
			
		 //batch proccesing
		 $batch = GetParam('batch')?GetParam('batch'):trim($sc['batch']);

  		 if ($batch>0) {
			  $datalines = array_chunk($source,$batch,true);
	          $current_batch = $datalines[$bid];
	          //print_r($current_batch);
	          $this->current_batch_records = count($current_batch);
	          //echo $this->current_batch_records;
			  
	          //auto refresh
	          $refresh = GetParam('refresh')?GetParam('refresh'):trim($sc['refresh']);
	          if (($refresh>0) && ($this->current_batch_records==$batch))
                $this->refresh_bulk_js(seturl('t=cpupdatedb&batchid='.($bid+1).'&batch='.$batch.'&refresh='.$refresh),$refresh);				  	  
		 }
		 else
		   $current_batch = (array) $source;	//one batch
			
		 if (trim($sc['bulkdata'])=='1') {//means extst sc[bulksql] + datafile=only data
			
			  $sSQL = str_replace('<@>','=?',$sc['bulksql']);
  			  //echo $sSQL,$sc['bulksql'];
			  $delimiter = $sc['delimiter']?$sc['delimiter']:';';			  
			  
			  $myi = (int) $sc['i'];
			  $this->i=$myi?$myi:0;
			
              foreach ($current_batch as $lineno=>$record) {	
			    $mydata[] = explode($delimiter,$record); 
			    $this->i+=1;  
			  }	
			  if ($sSQL) 
				$dbres = $db->Execute($sSQL,$mydata);
				
              $this->msg = $sSQL."(". $db->Affected_Rows() . " records affected!)";	
		  }
		  else {//no bulk sql
			  //echo $sc['getrec'],'-';
			  $getrec = explode(',',$sc['getrec']);
			  //print_r($getrec);

			  $attrrec = explode(',',$sc['attrrec']);
			
			  $passtitles = $bid?true:($sc['titles']?false:true); //false when titles exists..make it true after 1 ttime of loop
			  //echo $passtitles;
			  $delimiter = $sc['delimiter']?$sc['delimiter']:';';
              //echo '>',$delimiter;
			  $myi = (int) $sc['i'];
			  $this->i=$myi?$myi:0;
		      $this->ix=0;
			  $i = 0;//local meter				
			  $titlelines = $sc['titles']?$sc['titles']:0;
			  
              foreach ($current_batch as $lineno=>$record) {	
			  
                $field = explode($delimiter,$record);
			    //print_r($field);
				//echo $record,'<br>';
			    if (trim($field[0]))  {
				
			      if (($passtitles==false) && ($i>=$titlelines)) {
  			        //activate passtitles anyway
			        $passtitles = true;			  
				  }  
				  
			      $this->i+=1;  
				  $i+=1;
				  
			      if ($passtitles===true) {
			        //print_r($field); echo "<br>$i<br>";
			  
                    switch (trim($sc['mode'])) {
				  
				      case 'update' :
                                 $sSQL = $this->update_cmd($sc,$field,$getrec,$attrrec);
								 //echo $sSQL;							 				
					             break;
				  
                      case 'insert' :
                                 $sSQL = $this->insert_cmd($sc,$field,$getrec,$attrrec);
								 //echo $sSQL;
                      default       :								 
                                  
                    }//switch
			      }//if
				  
				  //echo $sSQL;
                  //if ($sc['verbose']) $this->displaysql .= $sSQL.'<br>';

				  if ($sSQL) {
				    if ($checktable=$sc['checktable']) {
					  $check = $this->check_sql($record,$checktable,$sc);
					  if ($sc['ontruecheck']) {
					    if ($check)//if the check is criticla to procced...
					      $dbres = $db->Execute($sSQL,2);
						else
						  $this->error = 'Invalid contition.';  
					  }
					  else
					   $dbres = $db->Execute($sSQL,2);	
					}  
					else  {
				      $dbres = $db->Execute($sSQL,2);
					} 
				    //print_r($dbres);				  					 
                    if ($x = $db->Affected_Rows()) { 
			           $this->ix+=1;	
				
				       if ($attach) {
				         //null must be executed data from sql.....?????
				         $a = $this->attach_file(null.null,$rpath,$tpath);
				       }  
		            }		
			        else {
				      $this->errormsg .= '<br>' . $sSQL . $db->error;
			          //$error_sql .= $ms . "<br>" . sprintf("Error: %s<br>", $this->errormsg) . "<br>";
			        }					
				  }//if sql	
			    }//if trim	
              }//foreach
		 }//no bulksql			

         $recs_readed = ($this->i-$myi);

         //$this->msg .= "Last SQL statement:" . $sSQL."<br>(". $this->ix . " records affected!)";				
         $this->msg .= "<br>(".$recs_readed." records readed!)";
	 	 $this->msg .= "<br>Import done!";
	     //$this->msg .= '<br>-----------------------Params';
		 //$this->msg .= '<br><pre>'.print_r($sc,true).'</pre>';
	     $this->msg .= '<br>-----------------------Checks';			
	     $this->msg .= '<br>' . $this->checkmsg;			 			
	     $this->msg .= '<br>-----------------------Errors';			
	     $this->msg .= '<br>' . $this->errormsg;			 
		 
		 $this->post = true;	
		 	  		  
		 return ($recs_readed);
	  }
	  else {//not aproved
	    $this->msg = "Import scenario file missing! (" . $scenario . ")";
	    $this->post = false;		  
	  }  
   }
   
   function check_sql($current_record,$checktable,$sc) {
      $db = GetGlobal('db');   
	  $delimiter = $sc['delimiter']?$sc['delimiter']:';';			  
	  $currec = explode($delimiter,$current_record);
      //print_r($currec);
	  $checkrec = explode(',',$sc['checkrec']);
	  $checkval = explode(',',$sc['checkval']);
	  $checkattr = explode(',',$sc['checkattr']);
	  $onerror = $sc['onerror'];
	  $onsuccess = $sc['onsuccess'];
	  $checkmode = $sc['checkmode'];
	  $ontruerec = $sc['ontruerec'];
	  $ontrueval = explode(',',$sc['ontrueval']);
	  $ontrueattr = explode(',',$sc['ontrueattr']);
	  
	  $checksql = 'select ' . implode(',',$checkrec) . ' from ' . $checktable . ' where ';
	  //print_r($checkval);

	  foreach ($checkrec as $ir=>$irec) {
	    $z = $checkval[$ir];
        $myval = $currec[$z]; //echo '<br>',$myval;
		switch ($checkattr[$ir]) {
		  case 's' : $vv = '"' . $this->make_replaces($myval) .'"'; break;
		  case 'd' : $vv = '"' . $myval .'"'; break;
		  case 'n' : $vv = 0 + str_replace($sc['sdecimal'],$sc['tdecimal'],trim($myval)); break;//casting to num  break;
		  default  : $vv = '"' . $this->make_replaces($z) .'"'; //fixed val
		}
		
		$vvt[] = $irec . '=' . $vv;
	  }
	  $checksql .= implode(' and ',$vvt);	
	  //echo $checksql;
	  $dbres = $db->Execute($checksql,1);
	  
	  $aff = $db->Affected_Rows();
	  //echo $aff?$aff:0,'affected>';
	  //true or false on t?
	  if ($onerror>0)
	    $result = !$aff?true:false;
	  elseif ($onsuccess>0)
	    $result = $aff?true:false;		
		
	  //echo $result?$result:0,'result>';
	  
	  if ($result>0) {
	     //run sub sql
		 switch ($checkmode) {
		   case 'update' : $runsql = 'update '.$checktable. ' set '; 
		                   break;//....to be implement
		   case 'insert' : 
		   default       : $runsql = 'insert into '.$checktable. ' ('; 
		 	               //print_r($ontrueval);
		                   $runsql .= $ontruerec . ') values (';
		                   foreach ($ontrueval as $tr=>$trec) {
						     $myval = $currec[$trec];
						     //echo '<br>',$trec;//,$myval;
                             switch ($ontrueattr[$tr]) {
		                       case 's' : $tvv = '"' . $this->make_replaces($myval) .'"'; break;
		                       case 'd' : $tvv = '"' . $myval .'"'; break;
		                       case 'n' : $tvv = 0 + str_replace($sc['sdecimal'],$sc['tdecimal'],trim($myval)); break;//casting to num  break;
		                       default  : $tvv = '"' . $this->make_replaces($trec) .'"'; //fixed val  
		                     }		      
		                     $tvvt[] = $tvv;		   
		                   }
						   
		                   $runsql.= implode(',',$tvvt);
		                   $runsql.= ')';
		 }				   
		 //echo '>',$runsql;
		 $dbres2 = $db->Execute($runsql,2);
		 $t2 = $db->Affected_Rows();
		 //echo $t2,'>';
		 $this->checkmsg .= '<br>' . $runsql;
		 
		 return ($t2);//true sql exec
	  }
	  
	  return ($result); 	
   }
   
   function insert_cmd($sc,$field,$getrec,$attrrec) {
   
         $sSQL = "insert into ".$sc['table']." (";
         $sSQL.= $sc['setrec'] .$sc['setrec2']/*. ',sysins'*/ . ') values (';
	     $datasql = null;
		 foreach ($field as $fid=>$fdata) {
		   //echo $fdata,'+';
		   if (in_array($fid,$getrec))
		     $datasql[] = $fdata;
		   //else
		     //echo 'zzzz',$getrec,'<br>';	 
		 }
		 //echo '<br>';
		 $datasqltype = null;
		 foreach ($datasql as $fr=>$fd) {
		    if ($attrrec[$fr]=='s')
			  $datasqltype[] = '"' . $this->make_replaces($fd) .'"';//$db->qstr($fd);
			elseif ($attrrec[$fr]=='n')
			  $datasqltype[] = 0 + str_replace($sc['sdecimal'],$sc['tdecimal'],trim($fd));//casting to num  
			elseif ($attrrec[$fr]=='d') //date
			  $datasqltype[] = '"' . $datetime .'"';									  
			else   
			  $datasqltype[] = trim($fd);
		 }  
         $sSQL.= implode(',',$datasqltype); 
		 //echo implode(',',$datasqltype),'<br>';
		 $sSQL.= $this->replace_params($sc['getrec2']);//str_replace('^datetime',"'".$this->datetime."'",$sc['getrec2'])/* . ",'$this->datetime'"*/ . 
		 $sSQL.= ');';   
		 
		 return $sSQL;
   }
   
   function update_cmd($sc,$field,$getrec,$attrrec) {
   
         $sSQL = "update ".$sc['table']." set";
         $field_names = explode(',',$sc['setrec']);
         $extra_field_names = explode(',',$sc['setrec2']);
         $extra_field_values = explode(',',$sc['getrec2']);								 
		 $datasql = null;
		 foreach ($field as $fid=>$fdata) {
		   if (in_array($fid,$getrec))
		     $datasql[] = $fdata;
		 }	
		 $datasqltype = null;
		 foreach ($datasql as $fr=>$fd) {
		    if ($attrrec[$fr]=='s')
			  $datasqltype[] = '"' . $this->make_replaces($fd) .'"';//$db->qstr($fd);
			elseif ($attrrec[$fr]=='n')
			  $datasqltype[] = 0 + str_replace($sc['sdecimal'],$sc['tdecimal'],trim($fd));//casting to num  
			elseif ($attrrec[$fr]=='d') //date
			  $datasqltype[] = '"' . $datetime .'"';									  
			else   
			  $datasqltype[] = trim($fd);
		 }  
								 
		 foreach ($field_names as $fn=>$name) {
								 
		   $sqlupadate[] = $name.'='.$datasqltype[$fn];
		 }
         $sSQL.= implode(',',$sqlupdate);
								 
		 foreach ($extra_field_names as $fne=>$namee) {
		   $value = $this->replace_params($extra_field_values[$fne]);
		   $sqlupadate2[] = $namee.'='.$value;
		 }
								 
         $sSQL.= implode(',',$sqlupdate2); 								  
		 $sSQL.= ';';	   
		 
		 return ($sSQL);
   }   
   
   function make_replaces($str=null) {
   
     if ($str) {
	   $ret = str_replace('"','\"',trim($str)); 
	   return ($ret);
	 }
	 
	 return null;
   }
   
   function replace_params($rec=null) {
   
      if (!$rec) return;
   
      //echo $rec,':';
      //fix '' error 
	  $f = explode(',',$rec);
	  foreach ($f as $i=>$field) {
	    switch ($field) {
		   case '^datetime' : $rf[] = "'".$this->datetime."'"; break;
		   case '^i'        : $rf[] = "'".$this->i."'"; break;
		   default          : if (is_numeric($field))
		                        $rf[] = $field;
							  elseif ($field)
                                $rf[] = "'".$field."'";							  
							  else
                                $rf[] = null;							  
		}
	  }
	  
	  $ret = implode(',',$rf);
   
      //fix '' error 
      //$ret1 = str_replace('^datetime',"'".$this->datetime."'",$myrec);
      //$ret = str_replace('^i',"'".$this->i."'",$ret1);
	  
	  //echo $ret,'<br>';
	  return $ret;	  
   }

	
	function reset_db(&$db,$tablename) {
	    //$db = GetGlobal($db);
		
		$sSQL = "delete from " . $tablename;
        $db->Execute($sSQL,2);	
	}
	
   function form_next_batch($title=null,$cmd=null,$width=null) {
          $editmode = GetParam('editmode');
      
	      $mybatch_id = GetReq('batchid');
		  $bid = $mybatch_id?($mybatch_id+1):1;
   
          $mytitle = $title?$title:$this->filename;
          $mycmd = $cmd?$cmd:'cpupdatedb';
		  $mywidth = $width?$width:'70%';
		  
          $filename = seturl("t=$mycmd&batchid=".$bid);	   
   
	      //params form
          $out  = "<FORM action=". "$filename" . " method=post class=\"thin\">";
          $out .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>"; 			 	       
		  		 
          $out .= "<input type=\"hidden\" name=\"FormName\" value=\"$mycmd\">"; 
		  
          $out .= "<input type=\"hidden\" name=\"filedata\" value=\"".GetParam('formdata')."\">"; 			  		  		  	
          $out .= "<input type=\"hidden\" name=\"filedata\" value=\"".GetParam('filedata')."\">"; 			  
		  
          $out .= "<input type=\"hidden\" name=\"batch\" value=\"".GetParam('batch')."\">"; 			  		  
          $out .= "<input type=\"hidden\" name=\"refresh\" value=\"".GetParam('refresh')."\">";		  
		  
          $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"$mycmd\">&nbsp;";			
          $out .= "<input type=\"hidden\" name=\"editmode\" value=\"$editmode\">";		  
			
          $out .= "<input type=\"submit\" name=\"Submit\" value=\"Next batch....\">";		
          $out .= "</FONT></FORM>"; 	  	
		
		  $wina = new window($this->filename,$out);
		  $winout .= $wina->render("center::$mywidth::0::group_dir_body::left::0::0::");
		  unset ($wina);		  		  
		
		  return ($winout);  
   }	
   
   function form_next_file($title=null,$cmd=null,$width=null) {
          $editmode = GetParam('editmode');   
      
	      $mybatch_id = 0;//GetReq('batchid');
		  $bid = 0;//$mybatch_id?($mybatch_id+1):1;
	      $myfile_id = GetReq('nfile');
		  $bid = $myfile_id?($myfile_id+1):1;		  
   
          $mytitle = $title?$title:$this->filename;
          $mycmd = $cmd?$cmd:'cpupdatedb';
		  $mywidth = $width?$width:'70%';
		  
          $filename = seturl("t=$mycmd&batchid=".$bid);	   
   
	      //params form
          $out  = "<FORM action=". "$filename" . " method=post class=\"thin\">";
          $out .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>"; 			 	       
		  		 
          $out .= "<input type=\"hidden\" name=\"FormName\" value=\"$mycmd\">"; 	
          $out .= "<input type=\"hidden\" name=\"filedata\" value=\"".GetParam('filedata')."\">"; 			  
          $out .= "<input type=\"hidden\" name=\"batch\" value=\"".GetParam('batch')."\">"; 			  		  
          $out .= "<input type=\"hidden\" name=\"refresh\" value=\"".GetParam('refresh')."\">";		
		    
          $out .= "<input type=\"hidden\" name=\"nfile\" value=\"".GetParam('nfile')."\">";//?????
		  
          $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"$mycmd\">&nbsp;";		
          $out .= "<input type=\"hidden\" name=\"editmode\" value=\"$editmode\">";				  	
			
          $out .= "<input type=\"submit\" name=\"Submit\" value=\"Next file....\">";		
          $out .= "</FONT></FORM>"; 	  	
		
		  $wina = new window($this->filename,$out);
		  $winout .= $wina->render("center::$mywidth::0::group_dir_body::left::0::0::");
		  unset ($wina);		  		  
		
		  return ($winout);  
   }
   
   function attach_file($id2load,$id2save=null,$rpath=null,$tpath=null) {
   
            $m = 0;
		    
			$rp = $rpath ? $this->urlpath . '/' .$rpath :
			               $this->urlpath . '/' .$this->resource_path;
				
		    $tp = $tpath ? $this->urlpath . '/' .$tpath :
		                   $this->urlpath . '/' .$this->target_path;				
			
		    if (!is_array($this->resourcetypes)) 
		      $this->resourcetypes[] = '.jpg';
			
		    foreach ($this->resourcetypes as $n=>$ext) {
		  						   
			$afile = $rp.'/'.$id2load.$ext;
			
		    if (is_readable($afile)) {
			  
			  if (isset($id2save)) { 
			    if ($tp) {
			      if (copy($afile,$tp.'/'.$id2save.$ext)) {
				    unlink($afile);
				    $m+=1;
				  }  
			    }			  
			    else {
			      if (copy($afile,$rp.'/'.$id2save.$ext)) {
				    unlink($afile);
				    $m+=1;
				  }  
			    } 
			  }
			  else {
			    if ($tp) {	 
			      if (copy($afile,$tp.'/'.$id2load.$ext)) {
				    unlink($afile);
				    $m+=1;
				  }  
			    }			  		  
			  }
			  	
			} 
			} 
  		    return ($m);
   }	   

   
   //called by task scheduler
   function import_sync($localexec=null) {
       $link = GetGlobal('db');
	 
	   if ($localexec!=null) {//bin file get in
	     //html out to mail
	     $html_start = null;//'<html><body>';
		 $html_end = null;//'</body></html>';	   
	     $execbin = remote_paramload('RCIMPORTDB','binsyncfile',$this->path);
	     if (!$execbin) 
	       return($html_start.'<h2>Sevice Deactivated!</h2>'.$html_end);
       }
	   else { 
	     //no html web exec
	     $html_start = null;
		 $html_end = null;
	   }	
	   
       if (is_array($this->syncfiles)) {
	   
	     foreach ($this->syncfiles as $f=>$sfile) {
		   $ret .=  '<br><h2>'.$sfile.'</h2><br>';
		   
		   $fparts = explode('.',$sfile);
		    		 
           $ret .= $this->import_table($fparts[0],$sfile,'<@>');
		   
           $ret .=  '---------------------------<br><br>';		 
		 }
	   }
	   
	   return ($html_start.$ret.$html_end);	    	 
	   
   }

};
}	
}
else die("DATABASE DPC REQUIRED!");
?>