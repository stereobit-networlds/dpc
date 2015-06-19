<?php


 /*
	Created by Silvio Rainoldi - 20.07.2006
	Contact: info@ianaz.ch
	If you find a mistake please contact me, I'm a newbie ^^
	www.ianaz.ch
 */
    class wateresize  { 
		var $thumb_w = 160;
		var $thumb_h = 120;
		
		var $max_w	 = 600;
		var $max_h	 = 450;
		
		var $max_size = 3000000;
		var $new_img;
        var $errore = array( 
                                "Errore sconosciuto", // 0 
                                "Errore nel caricamento dell immagine", // 1
                                "Errore nella cancellazione dell immagine", // 2
                                "L id non ι numerico", // 3
                                "L immagine ι giΰ stata cancellata", // 4
                                "Il file inviato non ι un immagine", // 5
                                "Il file inviato ι troppo grande" // 6
                        ); 
						
		var $quality;				
		
		//added by me				
		function wateresize() {
		
		  $this->quality = 80;
		}
						
        function loadImg($img_percorso, $posizione_x, $posizione_y, $estensione,$resize=null,$path2logo=null,$logo=null) 
        {
			/*$this->tipoo = $image['type'];
			if($image['type'] != 'image/pjpeg')
			{
			    echo $this->errore[5];
				return $this->errore[5];
			}
			else if($image['size'] >= $this->max_size)
			{
			    echo $this->errore[6];
				return $this->errore[6];
			}
			else 
			{*/
			
				$this->estensione 		= $estensione; //echo $this->stensione,"...";
				
				$this->im 				= imagecreatefromjpeg($img_percorso); 
				
				$this->im_w   			= imagesx($this->im); 
				$this->im_h   			= imagesy($this->im); 
				
				if ($resize) {
				  $this->resizeImg();
				}  
				else {//copy new image
			      $this->new_image = imagecreatetruecolor($this->im_w, $this->im_h);
				  
				  $this->new_im_w = $this->im_w;
				  $this->new_im_h = $this->im_h;					  
			      imagecopyresized($this->new_image, $this->im, 0, 0, 0, 0, $this->new_im_w, $this->new_im_h, $this->im_w, $this->im_h);			  
                }	 				
				
				if ($path2logo) { 
				  if ($logo==null)
				    $this->logoProperties($path2logo."logo.png");
				  else	
				    $this->logoProperties($path2logo.$logo);
				}  
				//echo $path2logo.$logo,'>';
				  
				//$this->resizeForThumb();////////////////////////////////////////
				
				if ($path2logo) {
				  $this->wt_x 			= $this->calc_pos_x($posizione_x);
				  $this->wt_y 			= $this->calc_pos_y($posizione_y);
				  //echo $this->wt_x,'x',$this->wt_y,'>';
				  
				  imagecopymerge($this->new_image, $this->watermark, $this->wt_x, $this->wt_y, 0, 0, $this->waterm_w, $this->waterm_h, 100);
				}  
				//$this->new_img 			= $this->saveImg(); //saved by calling class
				
			/*	return 2; 
            
			}*/
        } 
         
        function logoProperties($logoz="logo.png")
        {
			
            $this->watermark = imagecreatefrompng($logoz); 
            $this->waterm_w  = imagesx($this->watermark); 
            $this->waterm_h  = imagesy($this->watermark);  
			//echo $this->waterm_w,',',$this->waterm_h,'>';
        } 
        
        function resizeForThumb()
        {
        
			$this->thumb = imagecreatetruecolor($this->thumb_w, $this->thumb_h);
			imagecopyresized($this->thumb, $this->im, 0, 0, 0, 0, $this->thumb_w, $this->thumb_h, $this->im_w, $this->im_h);
        
        }
        
        function resizeImg()
        {
        
			if($this->im_w > $this->max_w && $this->im_h < $this->max_h)
			{
			
				$rapporto = $this->max_w / $this->im_w;
				
				$this->new_im_w = $this->im_w * $rapporto;
				$this->new_im_h = $this->im_h * $rapporto;
				
			}
			
			else if($this->im_w < $this->max_w && $this->im_h > $this->max_h)
			{
			
				$rapporto = $this->max_h / $this->im_h;
				
				$this->new_im_w = $this->im_w * $rapporto;
				$this->new_im_h = $this->im_h * $rapporto;
			
			}
			else if($this->im_w > $this->max_w && $this->im_h > $this->max_h)
			{
			
				$rapporto_1 = $this->max_w / $this->im_w;
				$rapporto_2 = $this->max_h / $this->im_h;
					if($rapporto_1 > $rapporto_2)
					{
						$rapporto = $rapporto_2;
					}
					else
					{
						$rapporto = $rapporto_1;
					}
						
						$this->new_im_w = $this->im_w * $rapporto;
						$this->new_im_h = $this->im_h * $rapporto;
			
			}
			
			else
			{
				$this->new_im_w = $this->im_w;
				$this->new_im_h = $this->im_h;
			}
			
			$this->new_image = imagecreatetruecolor($this->new_im_w, $this->new_im_h);
			imagecopyresized($this->new_image, $this->im, 0, 0, 0, 0, $this->new_im_w, $this->new_im_h, $this->im_w, $this->im_h);
			
			return $this->new_image;
        
        }
        
		function calc_pos_x($position_x='LEFT')
		{
			switch($position_x)
			{
				case 'LEFT':
					$x = 0;
					break;
				case 'CENTER':
					$x = $this->new_im_w / 2 - $this->waterm_w / 2;
					break;
				case 'RIGHT':
					$x = $this->new_im_w - $this->waterm_w;
					break;
				default:
					$x = 0;
			
			} 
			return $x;
		
		}
		
		function calc_pos_y($position_y='TOP')
		{
			switch($position_y)
			{
				case 'TOP':
					$y = 0;
					break;
				case 'MIDDLE':
					$y = $this->new_im_h / 2 - $this->waterm_h / 2;
					break;
				case 'BOTTOM':
					$y = $this->new_im_h - $this->waterm_h;
					break;
				default:
					$y = 0;
			
			}
			return $y;
		
		}
		
		//added by me...
		function set_jpg_quality($filesize) {
		
		   if ($filesize>2000000)//2mb 
		      $qlty = 20;
           elseif ($filesize>1000000)//1mb 			  
		      $qlty = 40;
           elseif ($filesize>500000)//0,5mb 	
		      $qlty = 60;		  
		   else 
		      $qlty = 80;
		
		   $this->quality = $qlty;
		   
		   return ($qlty);
		}
        
        function saveImg($fullname2save=null,$thub2save=null)
        {//echo $this->estensione,">>>";
		
		    //if (isset($fullname2save)) {//added by me
			
			  if ($this->estensione == 'jpeg' || $this->estensione == 'jpg') {
			    if ($fullname2save)
                  $ret = imagejpeg($this->new_image, $fullname2save, $this->quality); 
				if ($thub2save)
				  $ret = imagejpeg($this->thumb, $thub2save, $this->quality);			  
			  }
			  else {
			    if ($fullname2save)
				  $ret = imagepng($this->new_image, $fullname2save);
				if ($thub2save)
				  $ret = imagepng($this->thumb, $thub2save);			  
			  }	
			  
			  return ($ret);					
			  
			/*}
			else { //default
		
			$nome_img = md5($_FILES['image']['tmp_name']);
			$this->nome_new_img = $nome_img.".".$this->estensione;
			if($this->estensione == 'jpeg' || $this->estensione == 'jpg')
			{
				$this->estensione = 'jpg';
				imagejpeg($this->new_image, 'images/BIG__'.$this->nome_new_img, 80); 
				imagejpeg($this->thumb, 'thumbs/SMALL__'.$this->nome_new_img, 80); 
			}

			else 
			{
				imagepng($this->new_image, 'images/BIG__'.$this->nome_new_img);
				imagepng($this->thumb, 'thumbs/SMALL__'.$this->nome_new_img);
			}

				
			}	
			return $this->nome_new_img;*/
        }

        
        
        
    } 
    //$img = new img; 
    
?>