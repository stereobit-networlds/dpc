<?php
#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
# @title
#   Barcode
#
# @description
#   Barcode generation classes 
#
# @topics   contributions
#
# @created
#   2001/08/15
#
# @organisation
#   UNILASALLE
#
# @legal
#   UNILASALLE
#   CopyLeft (L) 2001-2002 UNILASALLE, Canoas/RS - Brasil
#   Licensed under GPL (see COPYING.TXT or FSF at www.fsf.org for
#   further details)
#
# @author
#   Rudinei Pereira Dias     [author] [rudinei@lasalle.tche.br]
# 
# @history
#   $Log: barcode.class,v 1.0
#
# @id $Id: barcode.class,v 1.1 2002/10/03 19:14:05 vgartner Exp $
#---------------------------------------------------------------------

#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
# Rotina para a gera��o de C�digo de Barra
# no padr�o Interleved 2 of 5 (Intercalado 2 de 5)
# utilizado para os documentos banc�rios conforme
# padr�o FEBRABAN.
#---------------------------------------------------------------------
class BarcodeI25
{    
    //Public properties
    var $codigo;       //SET: Code to transform in barcode / C�digo a converter em c�digo de barras
    var $ebf;          //SET: Width of slim bar / Espessura da barra fina: usar 1 at� 2.
    var $ebg;          //SET: Width of fat bar / Espessura da barra grossa: usar 2x a 3x da esp_barra_fn.
    var $altb;         //SET: Barcode heigth / altura do c�digo de barras
    var $ipp;          //SET: Black point url reference / Endere�o completo da imagem do ponto PRETO p/compor o c�digo de barras
    var $ipb;          //SET: White point url reference / Endere�o completo da imagem do ponto BRANCO p/compor o c�digo de barras
    var $tamanhoTotal; //RETURN: Field to return HTML image barcode total size / Propriedade de RETORNO do tamanho total da imagem do c�digo de barras
	var $ignoreTable;  //SET: if set to true, ignore table construction around barcode
	
    //Private properties
    var $mixed_code;
    var $bc = array();
    var $bc_string;
	var $errors;
	
	var $nbarcode;
    
    function BarcodeI25($code='')
    {
	
        //Construtor da classe
		$this->ignoreTable = false;
		$this->errors       = 0;
        $this->ebf          = 1;
        $this->ebg          = 3;
        $this->altb         = 50;
        $this->ipp          = "ponto_preto.gif";
        $this->ipb          = "ponto_branco.gif";
        $this->mixed_code   = "";
        $this->bc_string    = "";
        $this->tamanhoTotal = 0;
        
        if ( $code !== '' )
        {
            $this->SetCode($code);
	        $this->nbarcode = trim($code);			
        }
    }
    
    function SetCode($code)
    {   global $MIOLO;
	
		$code = trim($code);
		
	    $this->nbarcode = $code;		
        
        if (strlen($code)==0) { 
			//echo "C�digo de Barras n�o informado. (Barcode Undefined)";
			$this->errors = $this->errors + 1;
		}
        //if ((strlen($code) % 2)!=0) { 
			//echo "Tamanho inv�lido de c�digo. Deve ser m�ltiplo de 2. (Invalid barcode lenght)";
			//$this->errors = $this->errors + 1;
		//}

        if ($this->errors == 0) {
        	$this->codigo = $code;
        }
    }
    
    function GetCode()
    {
        return $this->codigo;
    }
    
    function Generate()
    {   
		if ($this->errors > 0) {
			return("ERROR!");
		}else{
	        $this->codigo = trim($this->codigo);
	        
	        $th = "";
	        $new_string = "";
	        $lbc = 0; $xi = 0; $k = 0;
	        $this->bc_string = $this->codigo;
	        
	        //define barcode patterns
	        //0 - Estreita    1 - Larga
	        //Dim bc(60) As String   Obj.DrawWidth = 1
	        
	        $this->bc[0]  = "00110";         //0 digit
	        $this->bc[1]  = "10001";         //1 digit
	        $this->bc[2]  = "01001";         //2 digit
	        $this->bc[3]  = "11000";         //3 digit
	        $this->bc[4]  = "00101";         //4 digit
	        $this->bc[5]  = "10100";         //5 digit
	        $this->bc[6]  = "01100";         //6 digit
	        $this->bc[7]  = "00011";         //7 digit
	        $this->bc[8]  = "10010";         //8 digit
	        $this->bc[9]  = "01010";         //9 digit
	        $this->bc[10] = "0000";          //pre-amble
	        $this->bc[11] = "100";           //post-amble
	        
	        $this->bc_string = strtoupper($this->bc_string);
	        
	        $lbc = strlen($this->bc_string) - 1;
	        
	        //Gera o c�digo com os patterns
	        for( $xi=0; $xi<= $lbc; $xi++ )
	        {
	            $k = (int) substr($this->bc_string,$xi,1);
	            $new_string = $new_string . $this->bc[$k];
	        }
	        
	        $this->bc_string = $new_string;
	        
	        //Faz a mixagem do C�digo
	        $this->MixCode();
	        
	        $this->bc_string = $this->bc[10] . $this->bc_string .$this->bc[11];  //Adding Start and Stop Pattern
	        
	        $lbc = strlen($this->bc_string) - 1;
	        
	        $barra_html="";
	        
	        for( $xi=0; $xi<= $lbc; $xi++ )
	        {
	            $imgBar = "";
	            $imgWid = 0;
	            
	            //barra preta, barra branca
	            
	            $imgBar = ( $xi % 2 == 0 ) ? $this->ipp : $this->ipb;
	            $imgWid = ( $this->bc_string[$xi]=="0" ) ? $this->ebf : $this->ebg;
	            
	            //criando as barras
	            $barra_html = $barra_html .
	                          "<img src=\"". $imgBar .
	                          "\" width=\"". $imgWid .
	                          "\" height=\"". $this->altb .
	                          "\" border=\"0\">";
	
	            $this->tamanhoTotal = $this->tamanhoTotal + $imgWid;
	        }
	        
	        $this->tamanhoTotal = (int) ($this->tamanhoTotal * 1.1);
			
			if (!$this->ignoreTable) {
	            $barra_html = "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=".$this->tamanhoTotal."><TR><TD WIDTH=100%>" .
							   $barra_html . "</TD></TR><TR><b>$this->nbarcode</b></TR></TABLE>";
			}
	        
	        //$out = "<div align=\"center\">$barra_html</div>\n";
			$out = $barra_html;
			
			return ($out);
		}
        
    }//End of drawBrar
    
    function MixCode()
    {
        //Faz a mixagem do valor a ser codificado pelo C�digo de Barras I25
        //Declara��o de Variaveis
        $i = 0; $l = 0; $k = 0;  //inteiro, inteiro, longo
        $s = "";                 //String
        
        $l = strlen( $this->bc_string );
        
        if ( ( $l % 5 ) != 0 || ( $l % 2 ) != 0 )
        {
            $this->barra_html = "<b> C�digo n�o pode ser intercalado: Comprimento inv�lido (mix).</b>";
        }
        else
        {
            $s = "";
            for ( $i = 0; $i< $l; $i += 10 )
            {
                $s = $s . $this->bc_string[$i]   .  $this->bc_string[$i+5];
                $s = $s . $this->bc_string[$i+1] .  $this->bc_string[$i+6];
                $s = $s . $this->bc_string[$i+2] .  $this->bc_string[$i+7];
                $s = $s . $this->bc_string[$i+3] .  $this->bc_string[$i+8];
                $s = $s . $this->bc_string[$i+4] .  $this->bc_string[$i+9];
            }
            $this->bc_string = $s;
        }
    }//End of mixCode
    
}//End of Class
?>
