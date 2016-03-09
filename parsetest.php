<?php

require_once 'login.php';
require_once 'connect.php';
require_once 'db_tools.php';

$$myresult = "";
$state = "";
$mainSearchKey = "";
$r = "";
$j = 0;
$companies = array();
$text_array = array();

if (! $argv[1]) {
   print "Missing parameter\n";
   Usage($argv[0]);
}

$today = date("m/d/Y");

if ($argv[1] == "today") {
    $beginDate = $today;
    $endDate = $today;
    print "*** Setting Begin date to $beginDate and Ending date to $endDate\n\n";
    if ($argv[2]) {
        print "*** Ignoring subsequent arguments\n\n";
    }
} else {
    $beginDate = $argv[1];
}

if (! $endDate) {               // not 'today'
    if ($argv[2]) {
        $endDate = $argv[2];    // date range
    } else {
        $endDate = $argv[1];    // single date
    }
}

if (! checkIsAValidDate($beginDate)) {
    print "*** " . $beginDate . " is not a valid Date\n";
    $oops = true;
}

if (! checkIsAValidDate($endDate)) {
    print "*** " . $endDate . " is not a valid date\n";
    $oops = true;
}

if (strtotime($endDate) < strtotime($beginDate)) {
    print "*** " . $beginDate . " is *after* $endDate\n";
    $oops = true;
}

if ($oops) {
    Usage($argv[0]);
    exit;
}

print "\n";

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

function checkIsAValidDate($myDateString){
    return (bool)strtotime($myDateString);
}

function Usage($script_name) {
    print "Usage: php " . $script_name . " today
       php " . $script_name . " date                  (for single date reporting)
       php " . $script_name . " begin_date end_date   (for date range reporting)\n";
    print "date format example - 03/10/2016\n\n";
    exit;
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
