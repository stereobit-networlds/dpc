<?PHP

//!--------------------------------------------------------
// @class		translator
// @desc		This class is a simple translator.
//				A simple code to a good translation.
//				It uses the google translator to get the results
// @author		Israel de Souza Rocha
//!--------------------------------------------------------
class translator
{
	
	//!--------------------------------------------------------
	// @function	translator::translate
	// @desc		Method to translate the text
	// @param		expression string	Text to translate
	// @param		from string			Parameter that represent
	//									the source language of the text
	// @param		to string			Param that represent the
	//									language to translate
	// @note		
	//			The values can be:
	//				from
	//					en: English
	//					de: Gernan
	//					es: Spanish
	//					fr: French
	//					it: Italian
	//					pt: Portuguese
	//				to
	//				 	de: German
	//					es: Spanish
	//					fr: French
	//					it: Italian
	//					pt: Portuguese
	//					en: English
	//!--------------------------------------------------------
	function translate($expression, $from, $to) {
		$f = file("http://translate.google.com/translate_t?text=" . urlencode($expression) . "&langpair=$from|$to");
		foreach ( $f as $v ) {
			if (strstr($v, '<textarea')) {
				$x = strstr($v, '<textarea');
			}
		}
		$arr = explode('</textarea>', $x);
		$arr = explode('wrap=PHYSICAL>', $arr[0]);
		echo $arr[1];
	}
}

?>