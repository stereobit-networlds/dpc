<?php 

class dpc_tree {

    function dpc_tree($dpcdir) {
	
	  $this->dpc_dir = $dpcdir;
	}

	//NOT USED !!! IT READS ALL THE TREE
    function read_dpc_filesystem($path) {

	  if (is_dir($path)) {
          $mydir = dir($path);
		 
          while ($fileread = $mydir->read ()) {
		  
		    if (($fileread!='.') && ($fileread!='..'))  {		  
			
			   $subpath = $path."/".$fileread;
			   
	           if (is_dir($subpath)) {//read directories
			   
			     $array2save[$fileread] = $this->read_dpc_filesystem($subpath);
               }	
			   else {//read files 
			         switch ($this->arg1) {	   

				       case '-dpc' :if (((stristr ($fileread,".dpc.php")) || 
					                     (stristr ($fileread,".lib.php"))) &&
				                         (!stristr ($fileread,"-")) && //= versioned file
										 (!stristr ($fileread,"~"))) { //=opened file
										 
				                       $array2save[$fileread] = $subpath;
			                        }	
									break;
					   default     :			   
  	                                if ((stristr ($subfileread,".dpc.php")) &&
				                        (!stristr ($subfileread,"~"))) { //=opened file
				                       $mydpc[$fileread][] = $subpath;
			                        }	
					 }			   
			         //$array2save[$fileread] = $subpath;
			   }  	  
		    }
		  }  
      }  
	  
	  return ($array2save); 
    }	

	//USED !!!!!!! IT READS 2 TREE LEVELS 
	function read_dpcs() {

	    if (is_dir($this->dpc_dir)) {
		
          $mydir = dir($this->dpc_dir);
		 
          while ($fileread = $mydir->read ()) {
	   
           //read directories
		   if (($fileread!='.') && ($fileread!='..'))  {

	          if (is_dir($this->dpc_dir."/".$fileread)) {

                 $mysubdir = dir($this->dpc_dir."/".$fileread);
                 while ($subfileread = $mysubdir->read ()) {	
				 
		           if (($subfileread!='.') && ($subfileread!='..'))  {
				   
			         switch ($this->arg1) {	   

				       case '-dpc' :if (((stristr ($subfileread,".dpc.php")) || 
					                     (stristr ($subfileread,".lib.php"))) &&
				                         (!stristr ($subfileread,"-")) && //= versioned file
										 (!stristr ($subfileread,"~"))) { //=opened file
				                       $mydpc[$fileread][] = $subfileread;
			                        }	
									break;
					   default     :			   
  	                                if ((stristr ($subfileread,".dpc.php")) &&
				                        (!stristr ($subfileread,"~"))) { //=opened file
				                       $mydpc[$fileread][] = $subfileread;
			                        }	
					 }							     
				   }
				 }
			  }
			  else {	
			     switch ($this->arg1) {	   
				   case '-dpc' :
  	                             if (((stristr ($fileread,".dpc.php")) || 
					                  (stristr ($fileread,".lib.php"))) &&
                                      (!stristr ($fileread,"-")) && //= versioned file
									  (!stristr ($fileread,"~"))) {							  
				                   $mydpc['\\'][] = $fileread;
			                     }
				   default :				 
				 }
			  }	
		   }
	      }
	      $mydir->close ();
        }
		
		return ($mydpc);
	}	

}

?>