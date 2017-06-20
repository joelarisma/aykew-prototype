<?php

/**
 * Utilities Doc Comment
 *
 * PHP Version 5.5
 *
 * @category Utilities
 * @package  EyeQ
 * @author   Digital Trike <webmaster@digitaltrike.com>
 * @license  Digital Trike MSA
 * @link     http://eyeqadvantage.org
 */

/**
 * Utilities Class Comment
 * 
 * This class takes care of random functions that can be refactored across
 * the project.
 * 
 * @category Utilities
 * @package  EyeQ
 * @author   Digital Trike <webmaster@digitaltrike.com>
 * @license  Digital Trike MSA
 * @link     http://eyeqadvantage.org
 * 
 */
class Utilities
{
    /**
     * Takes a given file instance from Laravel and returns a string of the 
     * the content converted to HTML.
     * 
     * This only works currently for .txt, .doc, .docx, .epub, and .mobi.
     * 
     * @param file $file The file that was uploaded by the user
     * 
     * @return string The file content converted to basic HTML
     */
    
    public static function parseFile($file)
    {
        $striped_content = null;
        
        try {       
            // Tokenize filename
            $filename = $file->getClientOriginalName();
            $fileName = explode(".", $filename);
            
            // .txt ------------------------------------------------------------
            if (end($fileName) == 'txt') {
                //  Clean up content
                $striped_content = nl2br(trim(strip_tags(file_get_contents($_FILES["image"]["tmp_name"]))));
                
            } elseif (end($fileName) == 'doc') { // .doc -----------------------
                // Open file
                $fh = fopen($_FILES["image"]["tmp_name"], 'r');
            
                // Read headers
                $headers = fread($fh, 0xA00);
                $n1 = ( ord($headers[0x21C]) - 1 );
                $n2 = ( ( ord($headers[0x21D]) - 8 ) * 256 );
                $n3 = ( ( ord($headers[0x21E]) * 256 ) * 256 );
                $n4 = ( ( ( ord($headers[0x21F]) * 256 ) * 256 ) * 256 );
                $textLength = ($n1 + $n2 + $n3 + $n4);
                $extracted_plaintext = fread($fh, $textLength);
                
                // Clean up content
                //$striped_content = nl2br(trim(strip_tags($extracted_plaintext)));
                $striped_content = strip_tags($extracted_plaintext);
                $striped_content = trim($extracted_plaintext);
                $striped_content = nl2br($extracted_plaintext);
                $striped_content = iconv("ISO-8859-1", "UTF-8", $striped_content);
                
            } elseif (end($fileName) == 'docx') { // .docx ---------------------
                // Variables
                $filename = $_FILES["image"]["tmp_name"];
                $striped_content = '';
                $content = '';
            
                // Open file
                if (!$filename || !file_exists($filename)) {
                    return false;
                }
                $zip = zip_open($filename);
                if (!$zip || is_numeric($zip)) {
                    return false;
                }
             
                // Read file
                while ($zip_entry = zip_read($zip)) {
            
                    if (zip_entry_open($zip, $zip_entry) == false) {
                        continue;
                    }
            
                    if (zip_entry_name($zip_entry) != "word/document.xml") {
                        continue;
                    }
            
                    $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            
                    zip_entry_close($zip_entry);
                }
                
                // Close file
                zip_close($zip);
                
                // Clean up content
                $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
                $content = str_replace('</w:r></w:p>', "\r\n", $content);
                $striped_content = nl2br(trim(strip_tags($content)));
                
            } elseif (end($fileName) == 'epub') { // .epub ---------------------
                
                // Open file
                $zip = zip_open($_FILES["image"]["tmp_name"]);
                $striped_content="";
                
                // Read file
                if ($zip) {
                    while ($zip_entry = zip_read($zip)) {
            
                        if (zip_entry_open($zip, $zip_entry, "r")) {
            
                            $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                            $tsxt =  strip_tags("$buf\n");
                            $striped_content .= str_replace("application/epub+zip", "", $tsxt);
            
                            zip_entry_close($zip_entry);
                        }
                        echo "\n";
            
                    }
                    
                    // Close file
                    zip_close($zip);
                    
                    // Clean up content
                    $striped_content = nl2br(trim($striped_content));
                }
            } elseif (end($fileName) == 'mobi') { // .mobi ---------------------
                
                // Get temp file info
                $filefolder = $file->getPath();
                $filename = $file->getFilename();
                $filepath = $file->getRealPath();
                $newname = $filepath . ".txt";
                 
                 
                // Rename in same folder to add extension
                $file->move($filefolder, $filename . ".mobi");
                 
                // Convert .mobi to .txt via Calibre via command-line
                $command = "ebook-convert " . $filepath . ".mobi ". $newname;
                $error = shell_exec($command);
                 
                // Conversion successfull
                if ($error !== null && file_exists($newname)) {
            
                    // Read converted contents
                    $striped_content =  nl2br(trim(strip_tags(file_get_contents($newname))));
                }
            }
            
            // Return parsed content, otherwise null
            return $striped_content;
        }
        
        // Nom ur exceptions
        catch(\Exception $e) {
            return null;
        }
    }
}

