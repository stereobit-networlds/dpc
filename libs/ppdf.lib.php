<?php
if (!defined("PPDF_DPC")) {
define("PPDF_DPC",true);

class ppdf {

   var $auth;
   var $own;

   var $doc;
   var $url;
   var $cachepath;
   
   var $image;


   function ppdf($title="title",$subject="subject",$openaction="fitwidth") {
   
       $this->url = paramload('SHELL','urlbase');
       $this->cachepath = paramload('SHELL','cachepath');

       $this->auth = paramload('PDF','author');
       $this->own = paramload('PDF','owner');
	      
	   //////////////////////////////////////////
	   //LOAD LIBRARY
	   //load_dl('php_pdf',paramload('SHELL','os')); NOT DL LOAD AT MULTITHREADED SERVERS  
   
       $this->doc = PDF_new();
	   
	   PDF_open_file($this->doc);//,"dummy.pdf");
	   
	   PDF_set_info($this->doc,"Title",$title);
	   PDF_set_info($this->doc,"Subject",$subject);	   
	   PDF_set_info($this->doc,"Creator",$this->own);
	   PDF_set_info($this->doc,"Author",$this->auth);	   	   
	   
	   PDF_set_parameter($this->doc,'openaction',$openaction);
   }

   function action() {
   }

   function event() {
   }
   
   function render($data='',$fname='pdfile',$mode=0) {

	   $g = GetReq('g');
       
	   //$buffer = PDF_get_buffer($this->doc);
	   
	   //create cached pdf content (admin have no cache so it doesn't work for him')
       $buffer = getcache($g,"pdf","create_doc",$this,$data,0,0,0,0,0,0,1); //now cache for admin
	   //echo $buffer;
	   //REDIRECT to getcached pdf script
	   header("Location: " . $this->url . "showpdf.php?data=/webos/projects/panikidis/cache/".urlencode($g));
	   
	   
	   //DID'NT WORK .........?????'
	   //$len = strlen($buffer);
	   
	   //header("Content-type: application/pdf");
	   //header("Content-Length: $len");
	   //header("Content-Disposition: inline; filename=$fname.pdf");
	   	   
	   //$out = $buffer;
	   //$buffer=readfile("/webos/projects/panikidis/cache/test.pdf");
	   //print $buffer;
	   
	   //PDF_delete($this->doc);
	   //exit;
	   //return ($out);
   }
   
   function create_doc($data) { 
   
      //TEST CODE
	  
      /*pdf_begin_page($this->doc, 595, 842);

   	  $font = PDF_findfont($this->doc,"Helvetica-Bold","host",0);
      pdf_add_outline($this->doc, "Page 1");
	  PDF_setfont($this->doc,$font,18.0);
	  PDF_set_text_pos($this->doc,50,700);
	  
	  PDF_show($this->doc,$data);//"hello world");
   
      PDF_end_page($this->doc);*/
	  
     /* pdf_begin_page($this->doc, 595, 842);
	  $this->template();
      pdf_add_outline($this->doc, "Page 2");
      $font = pdf_findfont($this->doc, "Times New Roman", "winansi", 1);
      pdf_setfont($this->doc, $font, 10);
      pdf_set_value($this->doc, "textrendering", 1);
      pdf_show_xy($this->doc, "Times Roman outlined", 50, 750);
      pdf_moveto($this->doc, 50, 740);
      pdf_lineto($this->doc, 330, 740);
      pdf_stroke($this->doc);
     	  
      PDF_end_page($this->doc);*/
   
   
      PDF_close($this->doc);   
   
      return (PDF_get_buffer($this->doc));   
   }
   
   function page_start($x=500,$y=800,$template=1) {
   
     PDF_begin_page($this->doc,$x,$y);
	 if ($template) $this->template();
   }
   
   function page_end() {
   
     PDF_end_page($this->doc); 
   }
   
   function end_ppdf() {
   
	 PDF_delete($this->doc);
	 exit; 
   }
   
   function start_template() {
     
	 //PDF_begin_template($this->doc,50,50);
	 $this->image = PDF_open_image_file($this->doc,"png","logo.png","",0); 
	 
	 if ($this->image) {
	   PDF_place_image($this->doc,$this->image,50,750,1.0);
	   //PDF_close_image($this->doc,$this->image);
     }
	 else {
   	   $font = PDF_findfont($this->doc,"Helvetica-Bold","host",0);	 
	   PDF_setfont($this->doc,$font,18.0);
	   PDF_set_text_pos($this->doc,50,750);
	  
	   PDF_show($this->doc,"Template missing");	   
	 } 
	 
	 //PDF_stroke($this->doc);
	 //PDF_end_template($this->doc);
   }
   
   function end_template() {
     PDF_close_image($this->doc,$this->image);
   }
   
   function getimage($image,$type,$x,$y,$scale=1.0) {
     
	 //PDF_begin_template($this->doc,50,50);
	 if (!file_exists($image)) {
	 
	 $image = PDF_open_image_file($this->doc,$type,$image,"",0); 
	 
	 if ($image) {
	   PDF_place_image($this->doc,$image,$x,$y,$scale);
       PDF_close_image($this->doc,$image); //in case of re-use
     }
	 else {
   	   $font = PDF_findfont($this->doc,"Helvetica-Bold","host",0);	 
	   PDF_setfont($this->doc,$font,12.0);
	   PDF_set_text_pos($this->doc,$x,$y);
	  
	   PDF_show($this->doc,"Image missing");	   
	 } 
	 
	 }
   }  

};
}
?>