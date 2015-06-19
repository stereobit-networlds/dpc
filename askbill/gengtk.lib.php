<?php
class GTK_window {

   var $parent;
   var $popwin;

   function GTK_window($xmltree,$title,$modal=false,
                                $winx=400,$winy=150,$border=10,
								$shrink=false,$grow=false,$auto_shrink=false,
								$noclose=false,$reconnect=false) {
						
	 global $cligtk;
	 							
	 $this->parent = $cligtk;							
   
     $this->_gtkwindow($xmltree,$title,$modal,
                                $winx,$winy,$border,
								$shrink,$grow,$auto_shrink,
								$noclose,$reconnect);   
   }
   
   //GTKWINDOW /////////////////////////////////////////////////////////////// 
   //noclose allow close window by X when false and don't allow when true'
   //reconnect : re-open the window aften any submit when true else remain closed for ever (until exit)     
   function _gtkwindow($xmltree,$title,$modal=false,
                                $winx=400,$winy=150,$border=10,
								$shrink=false,$grow=false,$auto_shrink=false,
								$noclose=false,$reconnect=false) {
   
	 
    // if ($this->{$title}==null) {
	 
       /* Creation of the window */ 
       $this->popwin = &new GtkWindow(); 
	   //$this->{$title} = & $this->popwin;
	   //$this->parent->__WINDOW[$title] = $this->popwin; 	   
	 
       $this->popwin->set_name($title); 
       $this->popwin->set_title($title); 
       $this->popwin->set_usize($winx, $winy);   
       $this->popwin->set_border_width($border); 
       $this->popwin->set_position(GTK_WIN_POS_CENTER); 
	   if ($modal==true) 
	     $this->popwin->set_modal($modal);
	   $this->popwin->set_default_size(180, 100);
	   $this->popwin->set_policy($shrink, $grow, $auto_shrink);	 
	   if ($noclose) //dont close window
	     $this->popwin->connect("delete-event",array( &$this, "OnDelete"));
	   else //close window and make null the pointer 		 	 		 	 
	     $this->popwin->connect("delete-event",array( &$this, "OnHide"),$reconnect);
	   
       $box = &new GtkVBox();
	   $this->popwin->add($box);		 
	 	
	   foreach ($xmltree as $id=>$child)
	     $this->parent->gtkxslt($box,$child);
     
       /* Tells the window to show all elements */ 
       $this->popwin->show_all();  	 
	// }
	// else {
	//   $this->popwin = & $this->{$title}; //point to current	 	 
	// }  
	 	   
   }
   
	//Don't allow the user to close the window with the (x)
	//called by the gtk dialog
	function OnDelete() {
	
		return true;	
	} 
	
	//close the window and delete the instance
	//called by gtk windows
	function OnHide($objwindow,$nWindow,$reconnect) {
	
	    $pointer = $objwindow->get_name(); //echo $pointer;    
		
		//if reconnect  is true make win pointer null to reopenit else let it hiding until end
		if ($reconnect) {
		  $this->{$pointer} = null;
	      //$this->__WINDOW[$pointer] = null;	//out of manager  
		}  
		
		$objwindow->hide(); //HIDE		
		//return false;	//DESTROY
	}	
  
}

class GTK_dialog extends GTK_window {

    var $parent;
	var $popwin;
   
    function GTK_dialog($xmltree,$title,$modal=false,
                                $winx=400,$winy=150,$border=10,
								$shrink=false,$grow=false,$auto_shrink=false) {
								
	 global $cligtk;
	 							
	 $this->parent = $cligtk;							
   
     $this->_gtkwindow($xmltree,$title,$modal,
                                $winx,$winy,$border,
								$shrink,$grow,$auto_shrink,
								true,false); 								
	}
}

class statusbar {
  
   var $sbar; //statusbar
   var $sctx; //statusbar contexts
  
   function statusbar($container) {

     $this->sbar = &new GtkStatusbar();
     $this->sctx = $this->sbar->get_context_id('foo');
     $this->sbar->push($this->sctx,'Initialize Connection ...');
     $container->pack_start($this->sbar);
     $this->sbar->show();	  
   }
   
   //add to bar stuck
   function push($context) {

     $this->sbar->push($this->sctx,$context);
   }

   // extranct from bar stuck
   function pop() {

     $this->sbar->pop($this->sctx);
   }   
   
   
}


?>