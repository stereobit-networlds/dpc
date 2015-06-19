<?php

class PHP_XML_Dumper {

    function PHP_XML_Dumper($root_element) {

        // set up where our data is going to live.
        $this->phpdata      = array();
        $this->xml          = array();
        $this->debug        = 0;
        
        ($root_element) ? $this->root_element = $root_element : $this->root_element = 'phpdata';
       
    }


    // this is the control method - all this does is set up some variables/files
    // and calls dump, which recursively calls itself until all xml is procesed.
    function php2xml($ref, $file = '') {

        $xml = "<{$this->root_element}>" . $this->dump($ref, 1) . "\n</{$this->root_element}>";
        ($this->debug) ? print $xml : '';

        if ($file) {

            if (preg_match('/\.xml\.gz$/i', $file)) {
                if (have_zlib_support()) {
                    $fd = gzopen($file, 'w');
                    gzwrite($fd, $xml); 
                    gzclose($fd);
                }

                else {
                    print "\n\nWarning - zlib compression not installed - not compressing xml.\n\n";
                    $file = preg_replace('/\.gz$/i', '', $file);
                    $fd = fopen($file, 'w');
                    fwrite($fd, $xml);
                    fclose($fd);
                }
            }

            else {
 
                $fd = fopen($file, 'w');
                fwrite($fd, $xml);
                fclose($fd);
            }
        }

        else {
            return $xml;
        }
    }


    function dump($ref, $indent) {

        $string = '';
        if (is_array($ref)) {
        
            // There is no distinction between arrays and hashes in php, so
            // the best check we can make is to test the key for 'stringness'
           
            $its_a_hash = 0;
            $keys = array_keys($ref);
            foreach ($keys as $k) {
                if (is_string($k)) {
                    $its_a_hash = 1;
                    break;
                }
            }

            if ($its_a_hash) {
                // ok, we found a key that was a string.  We have to
                // treat it like a hash.

                $type = "<hashref>"; 
                ksort($ref);
 
                $string = "\n" . str_repeat(" ", $indent) . $type;
                $indent++;

                // go through each key (reuse variable $type))
                foreach ($ref as $k => $v) {

                    // make the tag
                    $type = "<item " . "key=\"" . $k . "\"";
                    ($v) ? '' : $type .= " defined=\"false\"";
                    $type .= ">";
   
                    // add it to $string
                    $string .= "\n" . str_repeat(" ", $indent) . $type;

                    // if the value is an array, recursively call dump on it.
                    if (is_array($v)) {
                        $string .= $this->dump( $v, $indent + 1);
                        $string .= "\n" . str_repeat(" ", $indent). "</item>";
                    } 
 
                    // otherwise, just use the value of the scalar as a text 
                    // element inside of the 'item' tag.
                    else {
                        $string .= quote_xml_chars($v) . "</item>";
                    }
                }   

                $indent--;
                $string .= "\n" . str_repeat(" ", $indent) . "</hashref>";
            }

            // we seem to be able to treat this data structure as a perl array.
            else {

                // set the type
                $type = "<arrayref>";
 
                // add the type to string
                $string = "\n" . str_repeat(" ", $indent) . $type;
                $indent++;

                for ($i = 0; $i < count($ref); $i++) {

                    // reuse $type
                    $type = "<item " . "key=\"" . $i . "\"";
                    ($ref[$i]) ? '' : $type .= " defined=\"false\"";
                    $type .= ">";

                    // add it to $string
                    $string .= "\n" . str_repeat(" ", $indent) . $type;
  
                    // if the value is an array, recursively call dump on it.
                    if (is_array($ref[$i])) {
                        $string .= $this->dump( $ref[$i], $indent + 1);
                        $string .= "\n" . str_repeat(" ", $indent) . "</item>";
                    }

                    // otherwise, just use the value of the scalar as a text  
                    // element inside of the 'item' tag.
                    else {
                        $string .= quote_xml_chars($ref[$i]) . "</item>";
                    }
                }

                $indent--;
                $string .= "\n" . str_repeat(" ", $indent) . "</arrayref>";
            }
        }

        // its a scalar, our stopping point.
        else {
            $type = "<scalar";
            preg_match("/\S+/", $ref) ? "" : $type .= " defined=\"false\""; 
            $type .= ">";
 
            $string .= "\n" . str_repeat(" ", $indent) . $type . quote_xml_chars($ref) . "</scalar>";

        }

        return $string;
        
    }
    
    
    function xml2php ($xml, $callback = '') {
        
        // if this doesn't match, its a filename
        if (!preg_match('/\</', $xml)) {
    
            // open the file, and shove the contents into $xml;
            if (!file_exists($xml)) {
                die("File $xml was unable to be opened for reading.");
            }

            if (preg_match('/\.gz$/i', $xml)) {
            
                if (have_zlib_support()) {
                    $fd = gzopen($xml, 'r');
                    $xml = gzread($fd, 1000000); // some obscene number
                    gzclose($fd);
                }

                else {
                    die("Error - zlib compression not installed - unable to continue");
                }
                 
            }

            else {
                $fd = fopen($xml, 'r');
                $xml = fread($fd, filesize($xml));
                fclose($fd);
            }
        }
    
        $p = xml_parser_create();
    
        $index = array();  // an index of $VALS, by tag, and position of tag.
        $vals = array();   // an in order list of the tags that occur in the xml
    
        // parse the xml into a struct we can recursively walk over
        // turn case folding off - we don't want to munge varible names ...
        xml_parser_set_option ( $p, XML_OPTION_CASE_FOLDING, 0);
        // xml_parser_set_option ( $p, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($p,$xml,$VALS,$INDEX);
        // echo xml_error_string(xml_get_error_code($p));
        xml_parser_free($p);
        
        $GLOBALS['VALS'] = $VALS;
        $GLOBALS['INDEX'] = $INDEX;

        //print_r($VALS);
        //print_r($INDEX);

        $ref = $this->undump(0, count($VALS) - 1, $callback);
        ($this->debug) ? print_r($ref) : '';

        return $ref;
    
    }
  
    function undump($START, $STOP, $callback = '') {
    
        global $INDEX, $VALS;

        $ref = array();

        // walk through each item in the xml value list
        for ($i = $START; $i <= $STOP; $i++) {

            if ($VALS[$i]['tag'] == $this->root_element || $VALS[$i]['type'] == 'cdata') {
                ($this->debug) ? print "$i: continuing on cdata/root_element\n" : '';
                continue;
            }

            if ($VALS[$i]['tag'] == 'item' && $VALS[$i]['type'] == 'complete') {

                ($this->debug) ? print "$i: found discrete value\n" : '';

                $ref[$VALS[$i]['attributes']['key']] = $VALS[$i]['value'];
                continue;
            }

            if ($VALS[$i]['tag'] == 'item' && $VALS[$i]['type'] == 'open') {
                ($this->debug) ? print "$i: found open item tag - recursing with\n" : '';
                $close_tag = find_close_tag_index($i+1, 'item', $VALS[$i]['level']); 

                ($this->debug) ? print "new boundaries: " . ($i+1) . ", $close_tag \n" : '';
                $ref[$VALS[$i]['attributes']['key']] = $this->undump($i+1, $close_tag, '');

                // set i to follow boundaries.
                $i = $close_tag; 
                ($this->debug) ? print "returned.  next i should be " .  ($i + 1) . "\n" : '';
                
                continue;
            }

            ($this->debug) ? print "$i: array/hash ref open or close tag\n" : '';

        }

        return $ref;

    }
  
} // end of class def
    

// this function find the closing tag location of 

function find_close_tag_index($start_search_at, $tagname, $level) {

    global $INDEX, $VALS;

    // get the list of indices in $vals where $tagname occurs.
    $index_list = $INDEX[$tagname];

    for ($j = 0; $j < count($index_list); $j++) {
        if ($index_list[$j] < $start_search_at) {
            continue;
        }

        if ($VALS[$index_list[$j]]['type'] == 'close' && $VALS[$index_list[$j]]['level'] == $level) {
            return $index_list[$j];
        }
    }
}

function quote_xml_chars($text) {
    
    $text = preg_replace('/&/', '&amp;', $text);
    $text = preg_replace('/</', '&lt;', $text);
    $text = preg_replace('/>/', '&gt;', $text);
    $text = preg_replace("/'/", '&apos;', $text);
    $text = preg_replace('/"/', '&quot;', $text);
 
    // not sure about this one yet ...
    // s/([\x80-\xFF])/&XmlUtf8Encode(ord($1))/ge;
    return $text;

}

function have_zlib_support() {
    $list = get_loaded_extensions();
    foreach ($list as $ext) {
        if ($ext == 'zlib') { 
            return 1; 
        }
    }

    return 0;

}


/*******************************************************

Copyright 2003 Matt J. Avitable.  All rights reserved.

All files in this distribution are licensed 
under the Perl artistic license, which you may find at 
http://www.perl.com/language/misc/Artistic.html

*******************************************************/

?>
