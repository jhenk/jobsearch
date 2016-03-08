<?php

require_once 'login.php';
require_once 'connect.php';
require_once 'db_tools.php';

if (! $argv[1]) {
    print "Usage: php today
       php date                  (for single date reporting)
       php begin_date end_date   (for date range reporting)\n";
    print "date format example - 03/10/2016\n\n";
    exit;
}

$$myresult = "";
$state = "";
$mainSearchKey = "";
$r = "";
$j = 0;
$companies = array();
$text_array = array();

$today = date("m/d/Y");

if ($argv[1] == "today") {
    $beginDate = $today;
} else {
   $beginDate = $argv[1];
}

if (! $argv[2]) {
   $endDate = $beginDate;
} else {
   $endDate = $argv[2];
}

$_POST['AllCompanies'] = "SET";
$companies = getcompaniestable($db, $mainSearchKey);
$companiesidxmax = count($companies) - 1;

while($j <= $companiesidxmax) {
    $text_array = array();
    $company = getcompany($db, $companies[$j]);
 
    $comptext = $company[9] . "\n";
    $text_array = process_text($comptext, $beginDate, $endDate);

    if (! empty($text_array)) {
        print $companies[$j] . "\n";
        for($i = 0; $i < sizeof($text_array); $i++) {
            print($text_array[$i]);
        }
        print "********************************************\n\n";
    }

    $j++;
}

function process_text($text_in, $openRange, $closingRange) {
    $text_array = array();
    $matches = preg_split("/((Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday)\s->\s)/", $text_in, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

    for ($loop = 0; $loop<sizeof($matches); $loop = $loop + 3) {
        if(preg_match(     "/^\d{2}\/\d{2}\/\d{4}/", $matches[$loop+2], $elements)) {
            if (in_range($elements[0], $openRange, $closingRange)) {
                 array_push($text_array, $matches[$loop+2]);
            }
        }
    }
    // print_r($text_array);
    return $text_array;
}

function in_range($testDate, $openRange, $closingRange) {
    $calendarDate = date('m/d/Y', strtotime($testDate));
    $contractDateBegin = date('m/d/Y', strtotime($openRange));
    $contractDateEnd = date('m/d/Y', strtotime($closingRange));

    if ($calendarDate >= $contractDateBegin && $calendarDate <= $contractDateEnd) {
    	  return true;
    }
}

?>
