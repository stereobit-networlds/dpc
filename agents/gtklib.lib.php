<?php
if( !class_exists('gtk')) {
    die('Please load the php-gtk2 module in your php.ini' . "\r\n");
}

//used by agentds
class gtkds_connector {

   private $code_sep;
   
   function __construct() {
   
	 $this->code_sep = "\r\n<-->\r\n";   
   }
   
   function set_async_code($code) {
   
     if (is_file("code.txt")) { //means executed code remains
	   $fname = "code.bee";
	 }
	 else
	   $fname = "code.txt";
	   
	 if ($fp=fopen($fname,"a+")) {
	 
	   fwrite($fp,$code.$this->code_sep);
	   fclose($fp);
	 }  
	 
   }
   
   function set_async_data($data) {
   
	 $fname = "data.txt"; 
	 $mode = "a+";  
	   
	 if ($fp=fopen($fname,$mode)) {
	 
	   fwrite($fp,$data.$this->code_sep);
	   fclose($fp);
	 }  	          
   }
   
   function get_async_data() {
   
     if (is_file("data.txt")) { //means values exists
	   $fname = "data.txt"; 
	   
	   //read 1st part of data
	   if ($fp=fopen($fname,"rb")) {
	     $data_chunk = fread($fp,filesize($fname));
	     fclose($fp);
		 
		 $parts = explode($this->code_sep,$data_chunk);
		 $ret = array_shift($parts);
		 unlink($fname); //delete it
	   }
	   //if data leaved ... create again ..writing the rest...
	   if ((trim($parts[0])) && ($fp=fopen($fname,"wb"))) {
	     fwrite($fp,implode($this->code_sep,$parts));
	     fclose($fp);	   
	   }	   
	   
	   return $ret;	    
	 }  	     
   }     
}

class EntryCompletion extends GtkWindow
{
    function __construct($parent = null)
    {
        parent::__construct();

        if (@$GLOBALS['framework']) {
            return;
        }

        if ($parent)
            $this->set_screen($parent->get_screen());
        else
            $this->connect_simple('destroy', array('gtk', 'main_quit'));

        $this->set_title(__CLASS__);
        $this->set_position(Gtk::WIN_POS_CENTER);
        $this->set_default_size(-1, -1);
        $this->set_border_width(8);
        
        $this->add($this->__create_box());
        $this->show_all();
    }

    
    
    function __create_box()
    {
        $vbox = new GtkVBox(false, 5);
        $vbox->set_border_width(5);
        
        $label = new GtkLabel();
        $label->set_markup('Completion demo, try writing <b>total</b> or <b>gnome</b> for example.');
        
        $vbox->pack_start($label, false, false, 0);
        
        $entry = new GtkEntry();
        $vbox->pack_start($entry, false, false, 0);
        
        $completion = new GtkEntryCompletion();
        $completion_model = $this->__create_completion_model();
        $completion->set_model($completion_model);
        $completion->set_text_column(0);
        
        $entry->set_completion($completion);
        
        
        return $vbox;
    }
    
    
    
    function __create_completion_model()
    {
        $store = new GtkListStore(Gtk::TYPE_STRING);
        
        $iter = $store->append();
        $store->set($iter, 0, 'GNOME');
        
        $iter = $store->append();
        $store->set($iter, 0, 'total');
        
        $iter = $store->append();
        $store->set($iter, 0, 'totally');
        
        $iter = $store->append();
        $store->set($iter, 0, 'PHP');
        
        $iter = $store->append();
        $store->set($iter, 0, 'PHP-Gtk2');
        
        return $store;
    }
}
?>