<?php
  /*
   * APrint.php
   *
   * Simple class for printing text
   *
   * $Header: d:\\cvs/classistd/aprint/aprint.php,v 1.1 2003/05/30 20:55:36 darvin Exp $
   *
   * TODO:
   * - orientamento foglio (landscape/portrate)
   * - struttura tabellare
   *   - definizione delle colonne
   *   - definizione degli header
   *
   * Copyright (C) 2003  Andrioli Darvin <darvin@andrioli.com>
   *
   *   This library is free software; you can redistribute it and/or
   *   modify it under the terms of the GNU Lesser General Public
   *   License as published by the Free Software Foundation; either
   *   version 2 of the License, or (at your option) any later version.
   *
   *   This library is distributed in the hope that it will be useful,
   *   but WITHOUT ANY WARRANTY; without even the implied warranty of
   *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
   *   Lesser General Public License for more details.
   *
   *   You should have received a copy of the GNU Lesser General Public
   *   License along with this library; if not, write to the Free Software
   *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307  USA
   *
   */

/**
 * Print using courier new, with predefined size, weight 400 (normal)
 * @const AP_NORMAL_FONT
 * @access public
 */
define('AP_NORMAL_FONT',0);   // print using NORMAL FONT
/**
 * Print using courier new, with predefined size, weight 800 (bold)
 * @const AP_BOLD_FONT
 * @access public
 */
define('AP_BOLD_FONT',1);   // print using bold font
/**
 * Small font used to print the page's footer
 * @const AP_FOOTER_FONT
 * @access public
 */
define('AP_FOOTER_FONT',2);
/**
 * left text align.
 * @const AP_LEFT
 * @access public
 * @see text()
 */
define('AP_LEFT',0);
/**
 * center text align.
 * @const AP_CENTER
 * @access public
 * @see text()
 */
define('AP_CENTER',1);
/**
 * right text align.
 * @const AP_RIGHT
 * @access public
 * @see text()
 */
define('AP_RIGHT',2);

/**
* class APrint
*
*/
class APrint {

/**
* Selected printer (Windows name)
*
* @var string $PrinterName
*/
var $PrinterName;
/**
 * I've already established a connection to the printer?
 *  (TRUE/FALSE)
 * @var bool $ConnectionOpened
 */
var $ConnectionOpened;
/**
* printer handler
*
*
*/
var $hPrinter;

/**
* Current row
*
*/
var $row;

/**
* Orizontal resolution
*/
var $Res_X;
/**
* Vertical resolution
*
*/
var $Res_Y;

/**
* Value for the font height
*/
var $FontH;
/**
* Value for the font width
*
*/
var $FontW;
/**
* Page counter
*
*/
var $NPag;

/**
* handler of current selected font
*/
var $CurrentFont;
/**
* Paper hight
*
*/
var $PaperDimX;
/**
* Paper width
*
*/
var $PaperDimY;
/**
* Margins, size of the top margin
*/
var $MarginTop;
/**
* Margins, size of the bottom margin
*/
var $MarginBottom;
/**
* Margins, size of the left margin
*/
var $MarginLeft;
/**
* Margins, size of the right margin
*/
var $MarginRight;

/**
* array with all created font
* @var array $FontTable
* @see CreateFont()
*/
var $FontTable;

/**
* Job name
* @var string $JobTitle
* @see _OpenPrinter()
*/
var $JobTitle;

var $ScriptId;
/**
* Initialize this class. It doesn not attempt to
* establish the connection to the printer
* This class will perform this job at the first time that you send any text
* @param string $TitleText document title
* @param string $ScriptId script identification
* @access public
*/
function APrint($TitleText="",$ScriptId="") {
$this->PrinterName=""; // If no choice, use the default printer
$this->ConnectionOpened=false;
$this->row=10;
$this->NPag=0;
// Margins default settings
$this->MarginTop=400;
$this->MarginBottom=400;
$this->MarginLeft=40;
$this->MarginRight=40;
// Start with an empty array
$this->FontTable=array();
// Initial value for paper size. I'll set the correct value after I'll open the connection
// to the printer
$this->PaperDimX=-1;
$this->PaperDimY=-1;
$this->JobTitle=$TitleText;
$this->ScriptId=$ScriptId;
}
/**
* inizialize all parameter about the page size
* Require a printer handler.
* Unit used: twip,
* 1 inch = 1440 twip
* 1 mm = 56.7 twip
* @access private
*/
function _DefinePageSize()
{
$PaperType=printer_get_option($this->hPrinter, PRINTER_PAPER_FORMAT);
switch($PaperType)
  {
  case PRINTER_FORMAT_CUSTOM:
       // custom paper width in mm
       $this->PaperDimX=printer_get_option($this->hPrinter, PRINTER_PAPER_WIDTH)*56.7;
       $this->PaperDimY=printer_get_option($this->hPrinter, PRINTER_PAPER_LENGTH)*56.7;
       break;
  case PRINTER_FORMAT_LETTER:
       $this->PaperDimX=8.5*1440;
       $this->PaperDimY=11*1440;
       break;
  case PRINTER_FORMAT_LEGAL:
       $this->PaperDimX=8.5*1440;
       $this->PaperDimY=14*1440;
       break;
  case PRINTER_FORMAT_A3:
       $this->PaperDimX=297*56.7;
       $this->PaperDimY=420*56.7;
       break;
  case PRINTER_FORMAT_A4:
       $this->PaperDimX=8.27*1440;
       $this->PaperDimY=11.69*1440;
       break;
  case PRINTER_FORMAT_A5:
       $this->PaperDimX=148*56.7;
       $this->PaperDimY=210*56.7;
       break;
  case PRINTER_FORMAT_B4:
       $this->PaperDimX=250*56.7;
       $this->PaperDimY=354*56.7;
       break;
  case PRINTER_FORMAT_B5:
       $this->PaperDimX=182*56.7;
       $this->PaperDimY=257*56.7;
       break;
  case PRINTER_FORMAT_FOLIO:
       $this->PaperDimX=8.5*1440;
       $this->PaperDimY=13*1440;
       break;

  default:
       trigger_error("Paper format type:".$PaperType." not supported",E_USER_ERROR);
  }
// Now that I know the size of the page, I check tha value for margins
$this->_CheckMarginValue();
}

/**
 * Check the value for the margin. They should be >0 and
 * less then the page size
 *
 * @access public
 */
function _CheckMarginValue()
{
if($this->MarginTop<0)
  trigger_error("Top margin is < 0. It should be greater or equal to 0",E_USER_ERROR);
if($this->PaperDimY>0 && $this->MarginTop>$this->PaperDimY)
   trigger_error("Top margin bigger then paper size",E_USER_ERROR);
  
if($this->MarginBottom<0)
  trigger_error("Bottom margin is < 0. It should be greater or equal to 0",E_USER_ERROR);
if($this->PaperDimY>0 && $this->MarginBottom>$this->PaperDimY)
   trigger_error("Bottom margin bigger then paper size",E_USER_ERROR);
if($this->PaperDimY>0 && ($this->MarginBottom+$this->MarginTop)>$this->PaperDimY)
   trigger_error("No rows to write left. Bottom margin + top margin bigger then paper size",E_USER_ERROR);

if($this->MarginLeft<0)
  trigger_error("Left margin is < 0. It should be greater or equal to 0",E_USER_ERROR);
if($this->PaperDimX>0 && $this->MarginLeft>$this->PaperDimX)
   trigger_error("Left margin bigger then paper width",E_USER_ERROR);

if($this->MarginRight<0)
  trigger_error("Right margin is < 0. It should be greater or equal to 0",E_USER_ERROR);
if($this->PaperDimX>0 && $this->MarginRight>$this->PaperDimX)
   trigger_error("Right margin bigger then paper width",E_USER_ERROR);
if($this->PaperDimX>0 && ($this->MarginLeft+$this->MarginRight)>$this->PaperDimX)
   trigger_error("No columns to write left. Left margin + right margin bigger then paper width",E_USER_ERROR);

   

}

/**
 * Useful function to setup all margins with one function
 * use -1 to skip the parameter
 *
 * @param integer $top top margin size
 * @param integer $bottom bottom margin size
 * @param integer $left left margiclass
 * @param integer $right right margin size
 * @access public
 */

function SetMargin($top,$bottom=-1,$left=-1,$right=-1)
{
if(!($top<0))
   $this->MarginTop=$top;
if(!($bottom<0))
   $this->MarginBottom=$bottom;
if(!($left<0))
   $this->MarginLeft=$left;
if(!($right<0))
   $this->MarginRight=$right;
$this->_CheckMarginValue();
}

/**
 * Set the size for the top margin
 * @param integer
 * @access public
 */
function SetTopMargin($value)
{
$this->MarginTop=$value;
$this->_CheckMarginValue();
}
/**
 * Set the size for the bottom margin
 * @param integer
 * @access public
 */
function SetBottomMargin($value)
{
$this->MarginBottom=$value;
$this->_CheckMarginValue();
}
/**
 * Set the size for the left margin
 * @param integer
 * @access public
 */
function SetLeftMargin($value)
{
$this->MarginLeft=$value;
$this->_CheckMarginValue();
}

/**
 * Set the size for the right margin
 * @param integer
 * @access public
 */
function SetRightMargin($value)
{
$this->MarginRight=$value;
$this->_CheckMarginValue();
}

/**
 * Specifiy which printer I'll use. If you don't give any printer, the default printer
 * will be used.
 * @param string $Name printer name as defined in Window
 * @access public
 */
function SetPrinter($Name)
{
if($this->ConnectionOpened)
  trigger_error("Set the printer name before send any text (or title)",E_USER_ERROR);
$this->PrinterName=$Name;
}

/**
 * Write an header at the top of the first page.
 * @param string Text to print
 * @param integer $hFont font handle returned by CreateFont
 * @access public
 * @see CreateFont();
 */
function HeaderText($TitleText,$hFont=-1)
{
if(!$this->ConnectionOpened) $this->_OpenPrinter();
$this->Text("----------------------------------------------------------",AP_NORMAL_FONT);
$font=($hFont==-1)?AP_BOLD_FONT:$hFont;
$this->Text($TitleText,$font);
$this->Text("----------------------------------------------------------",AP_NORMAL_FONT);
}

/**
 * Skip a row, left a blank row.
 * @access public
 *
 */
function BlankRow()
{
if(!$this->ConnectionOpened) $this->_OpenPrinter();
$this->Text(" ");
}

/**
 * printout the text
 * @param string $text text to printout
 * @param integer $hFont Handle to the font to use. Handle returned by CreateFont
 * @param integer $align text alignment
 * @access public
 * @see CreateFont()
 */
function Text($text,$hFont=-1,$align=AP_LEFT)
{
if(!$this->ConnectionOpened) $this->_OpenPrinter();
// Select new font?
if($hFont!=-1)
   {
   $this->_SetFont($hFont);
   }
$this->FontH=$this->FontTable[$this->CurrentFont]['fontHeight'];
$width=$this->FontTable[$this->CurrentFont]['fontWidth'];
$textWidth=strlen($text)*$width;
switch($align)
  {
  case AP_LEFT:
       $StartCol=$this->MarginLeft;
       break;
  case AP_CENTER:
       if(($this->MarginLeft+$this->MarginRight+$textWidth)>$this->PaperDimX)
         $StartCol=$this->MarginLeft;
       else
         $StartCol=$this->MarginLeft+floor(($this->PaperDimX-$this->MarginLeft-$this->MarginRight-$textWidth)/2);
       break;
  case AP_RIGHT:
       if(($this->MarginLeft+$this->MarginRight+$textWidth)>$this->PaperDimX)
         $StartCol=$this->MarginLeft;
       else
         $StartCol=$this->PaperDimX-$this->MarginLeft-$textWidth;
       break;
  }
printer_draw_text($this->hPrinter,$text,$this->ResConv_x($StartCol),$this->ResConv_y($this->row));
$this->row+=$this->FontH;
if($this->row>($this->PaperDimY-$this->MarginBottom-300-$this->FontH))
  {
  $this->NewPage();
  }
}

/**
* Draw a dash line.
* @access public
*/
function Line()
{
$this->Text("----------------------------------------------------------");
}

/**
* Close the output to the printer and start to print
* @access public
*/
function run()
{
$this->_FooterNote();
printer_end_doc($this->hPrinter);
printer_close($this->hPrinter);
}

/**
 * Close the current page and start a new one.
 *
 * @access public
 */
function NewPage()
{
if(!$this->ConnectionOpened)
  trigger_error("Open the connection to the printer before run the function NewPage",E_USER_ERROR);
if($this->NPag) {
     $this->_FooterNote();
     }
$this->NPag++;
printer_start_page($this->hPrinter);
$this->row=$this->MarginTop;
}

/**
 * Printout page number and script name as footer
 *
 * @access private
 */
function _FooterNote()
{
$oldFont=$this->_SetFont(AP_FOOTER_FONT);
printer_draw_text($this->hPrinter,$this->ScriptId,
                  $this->ResConv_x($this->MarginLeft),
                  $this->ResConv_y($this->PaperDimY-$this->MarginBottom-$this->FontTable[AP_FOOTER_FONT]['fontHeight']));
printer_draw_text($this->hPrinter,$this->NPag,
                  $this->ResConv_x($this->PaperDimX-$this->MarginRight-4000),
                  $this->ResConv_y($this->PaperDimY-$this->MarginBottom-$this->FontTable[AP_FOOTER_FONT]['fontHeight']));
printer_end_page($this->hPrinter);
$this->_SetFont($oldFont);
}

/**
* Create a new font
*
* @param string $fName Font Name, default: Courier New
* @param integer $fHeight Font height, default: 336
* @param integer $fWidth Font width, default: 168
* @param integer $fWeight Font weight, default: 400. 0 -> thin, 800 -> bold
* @param boolean $fItalic Italic script? True/False
* @param boolean $fUnderline Text with underline? True/False
* @param boolean $fStrikeout True/False
* @param integer $fOrientation Should be always 3 digits, i.e 020. See printer_create_font in the note to the manual
* @see _SetFont()
* @see Text()
* @access public
*
*/
function CreateFont($fName="Courier new",$fHeight=336,$fWidth=168,$fWeight=400,$fItalic=false,$fUnderline=false,$fStrikeout=false,$fOrientaton=0)
{
if(!$this->ConnectionOpened)
  trigger_error("Open the connection to the printer before define the font",E_USER_ERROR);
$LastFontId=count($this->FontTable);
$this->FontTable[$LastFontId]['fontName']=$fName;
$this->FontTable[$LastFontId]['fontHeight']=$fHeight;
$this->FontTable[$LastFontId]['fontWidth']=$fWidth;
$this->FontTable[$LastFontId]['fontWeight']=$fWeight;
$this->FontTable[$LastFontId]['fontItalic']=$fItalic;
$this->FontTable[$LastFontId]['fontUnderline']=$fUnderline;
$this->FontTable[$LastFontId]['fontStrikeout']=$fStrikeout;
$this->FontTable[$LastFontId]['fontOrientaton']=$fOrientaton;
// printer_create_font("Courier New",66,33,400,false,false,false,0);
$this->FontTable[$LastFontId]['fontHandler']=printer_create_font($fName,$this->ResConv_y($fHeight),$this->ResConv_x($fWidth),$fWeight,$fItalic,$fUnderline,$fStrikeout,$fOrientaton);
return($LastFontId);
}

/**
* Establish the connection to the printer
* @access private
*/
function _OpenPrinter()
{
if($this->PrinterName=="")
  $this->hPrinter=printer_open();
else
  $this->hPrinter=printer_open($this->PrinterName);

//
$this->ConnectionOpened=true;
$this->_DefinePageSize();    // inizialize all parameter relate the margins, and page size
if(!$this->Res_X)
   $this->Res_X=printer_get_option($this->hPrinter, PRINTER_RESOLUTION_X);
if(!$this->Res_Y)
   $this->Res_Y=printer_get_option($this->hPrinter, PRINTER_RESOLUTION_Y);
printer_start_doc($this->hPrinter, $this->JobTitle);
$this->_DefaultFont();
$this->_SetFont(AP_NORMAL_FONT);
$this->NewPage();
}

/**
 * Define some dafault font
 * @access private
 */
function _DefaultFont()
{
// create predefined font. Don't change the row order
$this->CreateFont("Courier New",336,168,400,false,false,false,0);
$this->CreateFont("Courier New",336,168,800,false,false,false,0);
// echo $this->CreateFont("arial",163,84,200,false,false,false,0);
$this->CreateFont("Courier new",163,50,200,false,false,false,0);
}

/**
* Convert the internal point size to the printer point size according to its resolution
* As internal unit I use the twip. 1 inch = 1440 twip, 1 mm = 56,7 twip
* @param integer
* @return integer
* @access private
* @see ResConv_y()
*/
function ResConv_x($value)
{
return($value*$this->Res_X/1440);
}

/**
 * @param integer
 * @return integer
 * @see ResConv_x()
 */
function ResConv_y($value)
{
return($value*$this->Res_Y/1440);
}


/**
* Activate the specified font
*
* @param int $fntHandler font handle returned by CreateFont
* @access private
* @see CreateFont()
*/
function _SetFont($fntHandler)
{
printer_select_font($this->hPrinter,$this->FontTable[$fntHandler]['fontHandler']);
$oldFont=$this->CurrentFont;
$this->CurrentFont=$fntHandler;
return($oldFont);
}

} // end class APrint

?>

