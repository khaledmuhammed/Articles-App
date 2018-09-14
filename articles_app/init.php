<?php


	// Error Reporting

	ini_set('display_errors', 'On');
	error_reporting(E_ALL);

	include 'connection.php';

	
	// Routes

	$tpl 	= 'includes/templates/'; // Template Directory
	$func	= 'includes/functions/'; // Functions Directory
	$css 	= 'layout/css/'; // Css Directory
	$js 	= 'layout/js/'; // Js Directory

	// Include The Important Files

	include $func . 'functions.php';
	include $tpl . 'header.php';
	

	