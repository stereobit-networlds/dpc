<?php

class ftp {  

   var $ftp_server;
   var $ftp_uname;
   var $ftp_pwd;
   
   var $conn_id;
   var $msg;

   function ftp($messages=0) {	 
   
      $this->ftp_server;
	  $this->ftp_uname;
	  $this->ftp_pwd;
	  
	  $this->conn_id = null;
	  $this->msg = $messages;
   }  
   
   function connect($server='',$uname='',$pwd='') {
      
      $this->ftp_server = ((($server)) ? ($server) : $this->ftp_server); 
      $this->ftp_uname  = ((($uname)) ? ($uname) : $this->ftp_uname); 
      $this->ftp_pwd    = ((($pwd)) ? ($pwd) : $this->ftp_pwd); 	
	  
      // set up basic connection
      $this->conn_id = @ftp_connect($this->ftp_server);	    	  
	  
      // login with username and password
      $login_result = @ftp_login($this->conn_id, $this->ftp_uname, $this->ftp_pwd); 

      // check connection
      if ((!$this->conn_id) || (!$login_result)) { 
        if ($this->msg) {
		  echo "FTP connection has failed!";
          echo "Attempted to connect to $this->ftp_server for user $this->ftp_uname"; 
		}  
        //exit; 
      } 
	  else {
        if ($this->msg) echo "Connected to $this->ftp_server, for user $this->ftp_uname";
		return (true);
      }
	  
	  return (false);  
   }

   function disconnect() {
   
      ftp_close($this->conn_id);
   } 
   
   function upload($destination_file,$source_file,$mode=FTP_BINARY) {
      // upload the file
      $upload = @ftp_put($this->conn_id, $destination_file, $source_file, FTP_BINARY); 

      // check upload status
      if (!$upload) { 
        if ($this->msg) echo "FTP upload has failed!";
      } 
	  else {
        if ($this->msg) echo "Uploaded $source_file to $this->ftp_server as $destination_file";
		return (true);
      }
	  
	  return (false);
   }
   
   function download($source_file,$destination_file,$mode=FTP_BINARY) {
      // upload the file
      $dnload = @ftp_get($this->conn_id, $source_file, $destination_file, FTP_BINARY); 

      // check upload status
      if (!$dnload) { 
        if ($this->msg) echo "FTP download has failed!";
      } 
	  else {
        if ($this->msg) echo "Downloaded $destination_file to $this->ftp_server as $source_file";
		return (true);
      }
	  
	  return (false);
   }   
   
   function cd($dirname) {
   
      switch ($dirname) {
        case '..' : if (@ftp_cdup($this->conn_id)) return (true); 
		                                      else return (false);
		            break;
   
        default   : if (@ftp_chdir($this->conn_id, $dirname)) {  
                      return true; 
                    } 
	                else { 
                      return false; 
                    }
					break;      
	  }
   }
	
   function md($dirname) {
   
      if (@ftp_mkdir($this->conn_id,$dirname)) return (true);
	                                      else return (false);
   }	
   
   function currentdir() {
   
     return (ftp_pwd($this->conn_id)); 
   }
   
   function getsize($remotefile,$path='') {
   
     if ($path) {
	   $pathparts = explode("/",$path); 
	   //print_r($pathparts);
	   $maxc = count($pathparts);
	   
	   //goto path
	   //foreach ($pathpart as $part=>$name) 
	   for ($i=1;$i<$maxc;$i++)
	     $this->cd(trim($pathparts[$i]));
		 
	   $size = ftp_size($this->conn_id,$remotefile); 
	
	   //echo "\n",$this->currentdir(),":",$size;		   
	   
	   //go back
	   //foreach ($pathpart as $part=>$name) 
	   for ($i=1;$i<$maxc;$i++)	
	     $this->cd('..');	
		 
	   //echo "\n",$this->currentdir();	    
		 
	   return ($size);	 
	 }  
     //return (-1);
     return ftp_size($this->conn_id,$remotefile);
   }
   
   
   //copy whole dirs and subdirs
   function copydir($src_dir, $dst_dir,$echo=0,$server='',$mode='FULL') {
	   static $filenum;
	   static $errornum;

	   echo "\n\nSECTION :$dst_dir\n";
	   
	   if (is_dir($src_dir)) {
	   
       $d = dir($src_dir);
	    
       while($file = $d->read()) {

         if ($file != "." && $file != "..") {

           if (is_dir($src_dir."/".$file)) {

             if (!$this->cd($dst_dir."/".$file)) { //@ftp_chdir($this->conn_id, $dst_dir."/".$file)) {

                if ($this->md($dst_dir."/".$file)) {
				  if ($echo) echo "\n".$server."=>".$dst_dir."/".$file;
				}
				else {
			      if ($echo) echo "\n".$server."=>".$dst_dir."/".$file . " failed!";
				  $errornum+=1;
				}  
             }
             else
			   $this->cd('..'); //added by me???
			   
             $this->copydir($src_dir."/".$file, $dst_dir."/".$file,$echo,$server,$mode);
           }
           else {
             switch ($mode) { 
			 
			   case 'DIFF' : //echo "\nsize:",filesize($src_dir."/".$file)," ",$this->getsize($file,$dst_dir);
			                 if ($this->getsize($file,$dst_dir)!=filesize($src_dir."/".$file)) {
			   
                              if ($upload = $this->upload($dst_dir."/".$file, $src_dir."/".$file)) {
			                   if ($echo) echo "\n".$server."=>".$dst_dir."/".$file;
			                   $filenum+=1;
			                  }
			                  else {
			                   if ($echo) echo "\n".$server."=>".$dst_dir."/".$file . " failed!";			   
							   $errornum+=1;
							  } 
			                 }
							 else {
			                  if ($echo) echo "\n".$server."=>".$dst_dir."/".$file . " exist!";								 
							 }
			                 break;
			   case 'FULL' :
                             if ($upload = $this->upload($dst_dir."/".$file, $src_dir."/".$file)) {
			                   if ($echo) echo "\n".$server."=>".$dst_dir."/".$file;
			                   $filenum+=1;
			                 }
			                 else {
			                   if ($echo) echo "\n".$server."=>".$dst_dir."/".$file . " failed!";
							   $errornum+=1;
							 }  
							 break;  
			 }  
           }
         }
       }

       $d->close();
	   if ($echo) {
	     echo "\n".$server."=>Total files :".($filenum ? $filenum : 0);
         echo "\n".$server."=>Total errors:".($errornum ? $errornum :0);		 
	   }
	   }
	   else
	     if ($echo) echo "\n".$server."=>Invalid path.";
   }   
  
};
?>