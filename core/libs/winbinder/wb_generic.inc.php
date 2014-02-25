<?php

/*******************************************************************************

 WINBINDER - The native Windows binding for PHP for PHP

 Copyright © Hypervisual - see LICENSE.TXT for details
 Author: Rubem Pechansky (http://winbinder.org/contact.php)

 General-purpose supporting functions

*******************************************************************************/

//-------------------------------------------------------------------- FUNCTIONS

/*

Mimics function file_put_contents from PHP 5

int file_put_contents (string filename, string data [, int flags [, resource context]])

*/

if(PHP_VERSION < "5.0.0") {

function file_put_contents($filename, $data, $flags=0, $zcontext=null)
{
	if($zcontext)
		$fp = @fopen($filename, "w+b", $flags, $zcontext);
	else
		$fp = @fopen($filename, "w+b", $flags);
	if(!$fp)
		return FALSE;
	fwrite($fp, $data);
	fclose($fp);
	return TRUE;
}

}	// PHP_VERSION < "5.0.0"


/* Returns an array with all files of subdirectory $path. If $subdirs is TRUE,
  includes subdirectories recursively. $mask is a PCRE regular expression.
*/

function get_folder_files($path, $subdirs=false, $fullname=true, $mask="", $forcelowercase=TRUE)
{
	// Correct path name, if needed

	$path = str_replace('/', '\\', $path);
	if(substr($path, -1) != '\\')
		$path .= "\\";
	if(!$path || !@is_dir($path))
		return array();

	// Browse the subdiretory list recursively

	$dir = array();
	if($handle = opendir($path)) {
		while(($file = readdir($handle)) !== false) {
			if(!is_dir($path.$file)) {	// No directories / subdirectories
				if($forcelowercase)
					$file = strtolower($file);
				if(!$mask) {
					$dir[] = $fullname ? $path.$file : $file;
				} else if($mask && preg_match($mask, $file)) {
					$dir[] = $fullname ? $path.$file : $file;
				}
			} else if($subdirs && $file[0] != ".") {	// Exclude "." and ".."
				$dir = array_merge($dir, get_folder_files($path.$file, $subdirs, $fullname, $mask));
			}
		}
	}
	closedir($handle);
	return $dir;
}

//-------------------------------------------------------------------- INI FILES

/* Transforms the array $data in a text that can be saved as an INI file.
  Escapes double-quotes as (\") */

function generate_ini($data, $comments="")
{
	if(!is_array($data)) {
		trigger_error(__FUNCTION__ . ": Cannot save INI file.");
		return null;
	}
	$text = $comments;
	foreach($data as $name=>$section) {
		$text .= "\r\n[$name]\r\n";

		foreach($section as $key=>$value) {
			$value = trim($value);
			if((string)((int)$value) == (string)$value)			// Integer: does nothing
				;
			elseif((string)((float)$value) == (string)$value)	// Floating point: does nothing
				;
			elseif($value === "")								// Empty string
				$value = '""';
			elseif(strstr($value, '"'))							// Escape double-quotes
				$value = '"' . str_replace('"', '\"', $value) . '"';
			else
				$value = '"' . $value . '"';

			$text .= "$key = " . $value . "\r\n";
		}
	}
	return $text;
}

/*

Replaces function parse_ini_file() so INI files may be processed more similarly to Windows.
Replaces escaped double-quotes (\") with double-quotes ("). See manual for details.

*/

function parse_ini($initext, $changecase=TRUE, $convertwords=TRUE)
{
	$ini = preg_split("/\r\n|\n/", $initext);
	$secpattern = "/^\[(.[^\]]*)\]/i";
//	$entrypattern = "/^([a-z_0-9]*)\s*=\s*\"?([^\"]*)?\"?" . '$' . "/i";
//	$strpattern = "/^\"?(.[^\"]*)\"?" . '$' . "/i";
	$entrypattern = "/^([a-z_0-9]*)\s*=\s*\"?([^\"]*)?\"?\$/i";
	$strpattern = "/^\"?(.[^\"]*)\"?\$/i";

	$section = array();
	$sec = "";

	// Predefined words

	static $words  = array("yes", "on", "true", "no", "off", "false", "null");
	static $values = array(   1,    1,      1,    0,     0,       0,   null);

	// Lines loop

	for($i = 0; $i < count($ini); $i++) {

		$line = trim($ini[$i]);

		// Replaces escaped double-quotes (\") with special signal /%quote%/

		if(strstr($line, '\"'))
			$line = str_replace('\"', '/%quote%/', $line);

		// Skips blank lines and comments

		if($line == "" || preg_match("/^;/i", $line))
			continue;

		if(preg_match($secpattern, $line, $matches)) {

			// It's a section

			$sec = $matches[1];

			if($changecase)
				$sec = ucfirst(strtolower($sec));

			$section[$sec] = array();

		} elseif(preg_match($entrypattern, $line, $matches)) {

			// It's an entry

			$entry = $matches[1];

			if($changecase)
				$entry = strtolower($entry);

			$value = preg_replace($entrypattern, "\\2", $line);

			// Restores double-quotes (")

			$value = str_replace('/%quote%/', '"', $value);

			// Convert some special words to their respective values

			if($convertwords) {
				$index = array_search(strtolower($value), $words);
				if($index !== false)
					$value = $values[$index];
			}

			$section[$sec][$entry] = $value;

		} else {

			// It's a normal string

			$section[$sec][] = preg_replace($strpattern, "\\1", $line);

		}
	}
	return $section;
}

//------------------------------------------------------------------ END OF FILE

?>