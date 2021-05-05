<?php
if(!defined("oklogs")){
        header('HTTP/2.0 503 Missing', TRUE, 503);

        die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>503 Missing</title></head><body><h1>An unexpected error occured</h1></body></html>');
}
function w_log($desc) {
	$file = "../logs.txt";
	touch($file);
	date_default_timezone_set('Europe/Paris');
	$line = date('Y-m-d H:i:s')."\t$desc"."\n";
	if($fp = fopen($file, "a+")) {
		fwrite($fp, $line, 1024);
		fclose($fp);
	}
}