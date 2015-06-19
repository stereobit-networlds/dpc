<?php

class gtkds {

   private $window, $agentbox;
   private $errors;
   private $code_sep;
   
   private $gtk_conn;
   private $standalone;//mode 
   
   private $env;

   function __construct($env=null,$standalone=null) {
   
	 $this->errors = array();
	 $this->code_sep = "\r\n<-->\r\n";   
	 $this->standalone = $standalone;
	 if ($env) $this->env = $env;

	 //initialize GTk connector .. for inside this proc call and standalone purposes
     echo("GTK connector loaded!\n");	  
	 $this->gtkds_conn = new gtkds_connector(); 
	  
   
	 $this->window = &new GtkWindow();	
	 //$window->set_policy(false, false, false);
	 $this->window->set_title('phpDAC5-'.$this->env->env['name']);
	 $this->window->set_size_request(640, 480);
	 $this->window->set_position(GTK_WIN_POS_CENTER);
	
     $basebox = &new GtkVBox();
	 $this->window->add($basebox);		
	
     $this->agentbox = &new GtkHBox(false,5);
	 $this->agentbox->set_border_width(5);		
	 $basebox->pack_start($this->agentbox,false);			
	
     $this->window->realize();
	
	 $this->window->connect('destroy', array(&$this,'destroy'));    
	
	 $this->window->show_all();		
   
	 $this->there_is_code(); //first time code search
	 
	 
     Gtk::timeout_add(1000, array(&$this,'there_is_code'));			 
	 
   }
   
  function destroy() {
	Gtk::main_quit();
  }
  
  function close_window($widget) {
	$window = $widget->get_toplevel();
	$window->hide();
  }     
   
   function there_is_code() {
   
     //$code = @file_get_contents("code.txt");
	 
	 $window = $this->window;
   
     if ($code = @file_get_contents("code.txt")) {
	   //echo $code,'zzz';
	   $parts = explode("\r\n<-->\r\n",$code);
	   //print_r($parts);
	   foreach ($parts as $chunk) {
	   
	     if (trim($chunk)) {
           $this->errors = array();//reset errors
           $orig_hndl = set_error_handler(array(&$this,"error_hndl"));
           eval($chunk);
           restore_error_handler();		 

		   /*if (count($this->errors)>0)  {
             echo 'Caught GTK CODE error ', "\n";
             $dialog = new GtkMessageDialog($this->window, Gtk::DIALOG_MODAL | Gtk::DIALOG_DESTROY_WITH_PARENT,
                        Gtk::MESSAGE_INFO, Gtk::BUTTONS_OK,
                        sprintf(var_dump($this->errors)));
             $dialog->run();
             $dialog->destroy();
		   }*/
         }			   	 
	   }  
	 }	
	 
	 //after code running... get new posted code or delete runned code
	 if (is_file("code.bee")) {
	   copy("code.bee","code.txt");
	   unlink("code.bee");
	 }
	 else
	   @unlink("code.txt");		      
	 
	/* eval("new EntryCompletion();
         Gtk::main();	");*/
		 
	 return true;//for timeout hack purpose	 
   }
   
   function error_hndl($errno, $errstr) {
     $this->errors[] = array("errno"=>$errno, "errstr"=>$errstr);
   } 
   
   function event_queue($event,$param="") {   
   
     echo $event,$param,"\n";
	 
	 if ($this->standalone) {
	   //communicate with local files (writing...) reading from server
	   echo 'files mode',"\n";
	 }
	 else {
	   //execute directly
	   echo 'direct mode',"\n";
	   $this->env->get_agent($param)->$event();
	 }
   }
}
?>