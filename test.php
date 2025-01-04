<?php
// File Name & Content Header For Download
$file_name = "customers_data.xls";
header("Content-Disposition: attachment; filename=\"$file_name\"");
header("Content-Type: application/vnd.ms-excel");

$customers_data = array(
	array(
		'customers_id' => '1',
		'customers_firstname' => 'Chris',
		'customers_lastname' => '"Cavagin',
		'customers_email' => 'chriscavagin@gmail.com',
		'customers_telephone' => '9911223388'
	),
	array(
		'customers_id' => '2',
		'customers_firstname' => 'Richard',
		'customers_lastname' => 'Simmons',
		'customers_email' => 'rsimmons@media.com',
		'customers_telephone' => '9911224455'
	),
	array(
		'customers_id' => '3',
		'customers_firstname' => 'Steve',
		'customers_lastname' => 'Beaven',
		'customers_email' => 'ateavebeaven@gmail.com',
		'customers_telephone' => '8855223388'
	),
	array(
		'customers_id' => '4',
		'customers_firstname' => 'Howard',
		'customers_lastname' => 'Rawson',
		'customers_email' => 'howardraw@gmail.com',
		'customers_telephone' => '9911334488'
	),
	array(
		'customers_id' => '5',
		'customers_firstname' => 'Rachel',
		'customers_lastname' => 'Dyson',
		'customers_email' => 'racheldyson@gmail.com',
		'customers_telephone' => '9912345388'
	)
);



//To define column name in first row.
$column_names = false;
// run loop through each row in $customers_data
foreach($customers_data as $row) {
	if(!$column_names) {
		echo implode("\t", array_keys($row)) . "\n";
		$column_names = true;
	}
	// The array_walk() function runs each array element in a user-defined function.
	// 
	// array_walk($row, function(&$str){		
	// 	$str = preg_replace("/\t/", "\\t", $str);
	// 	$str = preg_replace("/\r?\n/", "\\n", $str);
	// 	if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
	// });
	echo implode("\t", array_values($row)) . "\n";
}

