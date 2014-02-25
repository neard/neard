<?php

/*******************************************************************************

 WINBINDER - The native Windows binding for PHP for PHP

 Copyright © Hypervisual - see LICENSE.TXT for details
 Author: Rubem Pechansky (http://winbinder.org/contact.php)

 Some functions to call the FreeImage library directly

*******************************************************************************/

/*

This software uses the FreeImage open source image library. See
http://freeimage.sourceforge.net for details. FreeImage is used under the
FIPL version 1.0.

Go to the FreeImage web site (http://freeimage.sourceforge.net) to download
the FreeImage DLL and documentation.

These functions were successfully tested with the following FreeImage versions:

2.3.1 (600 kB, 260 kB zipped)
2.5.4 (670 kB, 290 kB zipped)
3.4.0 (744 kB, 350 kB zipped)
3.5.3 (888 kB, 413 kB zipped)

*/

//-------------------------------------------------------------------- CONSTANTS

// These were taken from FreeImage.h (version 3.5.3)

define("FIF_UNKNOWN",	-1);
define("FIF_BMP",		0);
define("FIF_ICO",		1);
define("FIF_JPEG",		2);
define("FIF_JNG",		3);
define("FIF_KOALA",		4);
define("FIF_LBM",		5);
define("FIF_IFF", FIF_LBM);
define("FIF_MNG",		6);
define("FIF_PBM",		7);
define("FIF_PBMRAW",	8);
define("FIF_PCD",		9);
define("FIF_PCX",		10);
define("FIF_PGM",		11);
define("FIF_PGMRAW",	12);
define("FIF_PNG",		13);
define("FIF_PPM",		14);
define("FIF_PPMRAW",	15);
define("FIF_RAS",		16);
define("FIF_TARGA",		17);
define("FIF_TIFF",		18);
define("FIF_WBMP",		19);
define("FIF_PSD",		20);
define("FIF_CUT",		21);
define("FIF_XBM",		22);
define("FIF_XPM",		23);
define("FIF_DDS",		24);
define("FIF_GIF",		25);

//------------------------------------------------------------- GLOBAL VARIABLES

if(!isset($FI)) {
	$FI = wb_load_library("ext\\freeimage");
	if(!$FI) {
		wb_message_box(null, "FreeImage extension could not be loaded.", "Error", WBC_STOP);
		die();
	}
}
//-------------------------------------------------------------------- FUNCTIONS

function FreeImage_GetVersion()
{
	global $FI;
	static $pfn = null;

	if($pfn === null)
		$pfn = wb_get_function_address("FreeImage_GetVersion", $FI);

	// Must use wb_peek because this function returns a string pointer

	$version = wb_peek(wb_call_function($pfn));
	return $version;
}

function FreeImage_GetInfoHeader($dib)
{
	global $FI;
	static $pfn = null;

	if($pfn === null)
		$pfn = wb_get_function_address("FreeImage_GetInfoHeader", $FI);
	return wb_call_function($pfn, array($dib));
}

function FreeImage_GetBits($dib)
{
	global $FI;
	static $pfn = null;

	if($pfn === null)
		$pfn = wb_get_function_address("FreeImage_GetBits", $FI);
	return wb_call_function($pfn, array($dib));
}

function FreeImage_Allocate($width, $height, $bpp)
{
	global $FI;
	static $pfn = null;

	if($pfn === null)
		$pfn = wb_get_function_address("FreeImage_Allocate@24", $FI);
	return wb_call_function($pfn, array($width, $height, $bpp, 0, 0, 0));
}

function FreeImage_Unload($bmp)
{
	global $FI;
	static $pfn = null;

	if($pfn === null)
		$pfn = wb_get_function_address("FreeImage_Unload", $FI);
	return wb_call_function($pfn, array($bmp));
}

function FreeImage_GetWidth($bmp)
{
	global $FI;
	static $pfn = null;

	if($pfn === null)
		$pfn = wb_get_function_address("FreeImage_GetWidth", $FI);
	return wb_call_function($pfn, array($bmp));
}

function FreeImage_GetHeight($bmp)
{
	global $FI;
	static $pfn = null;

	if($pfn === null)
		$pfn = wb_get_function_address("FreeImage_GetHeight", $FI);
	return wb_call_function($pfn, array($bmp));
}

function FreeImage_Load($type, $filename, $flags=0)
{
	global $FI;
	static $pfn = null;

	if($pfn === null)
		$pfn = wb_get_function_address("FreeImage_Load", $FI);
	return wb_call_function($pfn, array($type, $filename, $flags));
}

function FreeImage_Save($type, $dib, $filename, $flags=0)
{
	global $FI;
	static $pfn = null;

	if($pfn === null)
		$pfn = wb_get_function_address("FreeImage_Save", $FI);
	return wb_call_function($pfn, array($type, $dib, $filename, $flags));
}

function FreeImage_Rescale($dib, $dst_width, $dst_height, $filter=0)
{
	global $FI;
	static $pfn = null;

	if($pfn === null)
		$pfn = wb_get_function_address("FreeImage_Rescale", $FI);
	return wb_call_function($pfn, array($dib, $dst_width, $dst_height, $filter));
}

//-------------------------------------------------------------------------- END

?>