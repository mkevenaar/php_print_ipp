<?php

require_once('../../../../vendor/autoload.php');

use Stiwl\PhpPrintIpp\Lib\CupsPrintIPP;

/* printing an utf-8 file, two-sided, two pages per sheet */
$host = "172.16.1.232"; // set serve'rs host here
$ssl = false; // enable ssl if true
$printer_uri = "ipp://172.1.16.64/l_general_mb780_01"; // set printer uri here
$language = "es_es";
$user = "lsanchez"; // valid user from lpadmin group
$password = "1111"; // his password
$document_uri = 'C:\\WT-NMP\\WWW\\php_print_ipp_master\\legacy\\testfiles\\test.txt';
//$document_uri = 'C:\\Users\\Public\\Documents\\ANEXOS HUNTER.pdf';

$debug_level = 5; // 5: silent; 0: very verbose
error_reporting(E_ALL|E_STRICT);
/******************
 *
 * END OF SETUP
 *
 *******************/
//function __autoload($class_name) {
//    require_once "../../lib/$class_name.php";
//}

$ipp = new CupsPrintIPP();

$ipp->setHost($host);
$ipp->setPrinterURI($printer_uri);

$ipp->getPrinterAttributes();
//var_dump($ipp->printer_attributes);


$ipp->debug_level = 5; // Debugging very verbose
$ipp->setUserName($user); // setting user name for server
$ipp->setDocumentName("UTF-8 characters");
//$ipp->setAuthentication($user, $password);
$ipp->setSides(); //     by default:  2 = two-sided-long-edge
//  other choices:  1 = one-sided
//                  2CE = two-sided-short-edge


$ipp->setData($document_uri);//Path to file.

//var_dump($ipp->getDebug());
printf(_("Job status: %s"), $ipp->printJob()); // Print job, display job status
$ipp->printDebug(); // display debugging output

