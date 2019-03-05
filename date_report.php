<?php

date_default_timezone_set('America/Los_Angeles');

// $myresult = "";
// $state = "";
// $mainSearchKey = "";
// $contactSearchKey = "";
// $r = "";
// $j = 0;
// $companies = array();
// $contacts = array();

 session_start();

// exit();


//while ((! validateDate($endingDate) && (! $endingDate == 'exit')) {
  // $endingDate = readline("Enter ending date, type exit, or default to 1 week ago... ");
//}

require_once 'login.php';
#print "$db_hostname\n";
#print "$db_database\n";
#print "$db_username\n";
#print "$db_password\n";
$db = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);

require_once 'connect.php';
require_once 'db_tools.php';

$final_ary = array();
$bigax = 0;

#print_r($_SESSION);

$compname = "";
$contactname = "";

$_POST['AllCompanies'] = 1;

$oDate = $_POST['FromDate'];
$eDate = $_POST['ToDate'];
$eReportDate = new DateTime($eDate);
$oReportDate = new DateTime($oDate);

$companies = getcompaniestable($db, "");
$contacts = getcontactstable($db, "");

$companiesidxmax = count($companies) - 1;
$contactsidxmax = count($contacts) - 1;

for ($i=0; $i <= $companiesidxmax; $i++) { 
  $company = getcompany($db, $companies[$i]);
  add_date_records_to_final_ary($company);
}

#print count($final_ary) . "\n";
usort($final_ary, "cmp");

// var_dump($final_ary);
$finalidxmax = count($final_ary);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<div id="container">
<div id="header">
<head>
        <link href="style.css" rel="stylesheet" type="text/css">
        <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"> 
  <title>Activity Report</title>
</head>
  <H1>Activity Report from <?php echo date_format($oReportDate, 'm/d/Y'); ?> to <?php echo date_format($eReportDate, 'm/d/Y'); ?></H1>
</div>

<div id="box">
<form method="post" action="mainmenu.php">
      <input type="submit" value = "Main Menu" name="MainMenu" >
</form>
<?php
  for ($k = 0; $k < $finalidxmax; $k++) {
    print $k+1 . ") " . convert_date_to_m_d_y($final_ary[$k][1]) . ": <u> " . $final_ary[$k][0] . "</u> - " . $final_ary[$k][2];
    print "<br>\n";
  }
?>
</div>
<form method="post" action="mainmenu.php">
      <input type="submit" value = "Main Menu" name="MainMenu" >
</form>


<?php

#echo "<textarea rows=\"60\" cols=\"180\" name=\"items\" class=\"txtarea\" style=\"resize: none;\" data-role=\"none\" id=\"items\">\n";
#for ($k = 0; $k < $finalidxmax; $k++) {
  // $little_ary = array($final_ary[$k]);
#  print convert_date_to_m_d_y($final_ary[$k][1]) . " - " . $final_ary[$k][0] . " - " . $final_ary[$k][2] . "<br>\n";
#   echo convert_date_to_m_d_y($final_ary[$k][1]);
#   print " <a href=\"show_a_company.php\">" . $final_ary[$k][0] . "</a>" . " - ";
#   echo  $final_ary[$k][2];
#   print " . <br>\n";
#}

echo "</textarea>\n";

?>

<?php
function add_date_records_to_final_ary($comp_record_ary) {
   global $final_ary;
   global $bigmax;
   global $oDate;
   global $eDate;

   $myitemary = array();
   $myitemary = explode("\n", $comp_record_ary[9]);
   // var_dump($myitemary);
   $max = sizeof($myitemary);
   for($j = 0; $j < $max; $j++) {
      if (strlen($myitemary[$j]) > 0) {
//         var_dump($myitemary[$j]);
         $bigmax = $bigmax + 1;
         $date_string = retrieve_date($myitemary[$j]);
         if ($date_string != "") {
            $min_date = "1969/12/31";
            # print "date_string - " . $date_string . "  - oDate - " . convert_date_to_y_m_d($oDate)  . " - eDate - " . convert_date_to_y_m_d($eDate) . "<br>\n";
            if (($date_string > $min_date) && ($date_string >= convert_date_to_y_m_d($oDate)) && ($date_string <= convert_date_to_y_m_d($eDate))) {
               $item_string = retrieve_item($myitemary[$j]);
               $element_ary = array($comp_record_ary[0], $date_string, $item_string);
               array_push($final_ary, $element_ary);
               // print $bigmax . ") " . $date_string . "  -  " . $comp_record_ary[0] . "\n";
            }
         }
      }
   }
   // print "bigmax is $bigmax\n";
}

function convert_date_to_y_m_d($in_date) {
  $final_date = date('Y/m/d', strtotime($in_date));
  return $final_date;
}

function convert_date_to_m_d_y($in_date) {
  $final_date = date('m/d/Y', strtotime($in_date));
  return $final_date;
}

function retrieve_date($in_string) {  
   $matches = array();
   $matches = preg_split("/(Sunday\s->\s\d\d\/\d\d\/\d\d\d\d:\s|Monday\s->\s\d\d\/\d\d\/\d\d\d\d:\s|Tuesday\s->\s\d\d\/\d\d\/\d\d\d\d:\s|Wednesday\s->\s\d\d\/\d\d\/\d\d\d\d:\s|Thursday\s->\s\d\d\/\d\d\/\d\d\d\d:\s|Friday\s->\s\\d\d\/\d\d\/\d\d\d\d:\s|Saturday\s->\s\d\d\/\d\d\/\d\d\d\d:\s)/", $in_string, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

   $date = preg_split("/(Sunday\s->\s|Monday\s->\s|Tuesday\s->\s|Wednesday\s->\s|Thursday\s->\s|Friday\s->\s|Saturday\s->\s)/", $matches[0]);
   if (isset($date[1])) {
      $date1 = preg_split("/(\d\d\/\d\d\/\d\d\d\d)/", $date[1], -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
      if (isset($date1[0])) {
         $convertedDate = convert_date_to_y_m_d($date1[0]);
         return $convertedDate;
      } else {
         return "";
      }
   } else {
      return "";
   }
} 

function retrieve_item($in_string) {  
   $matches = array();
   $matches = preg_split("/(Sunday\s->\s\d\d\/\d\d\/\d\d\d\d:\s|Monday\s->\s\d\d\/\d\d\/\d\d\d\d:\s|Tuesday\s->\s\d\d\/\d\d\/\d\d\d\d:\s|Wednesday\s->\s\d\d\/\d\d\/\d\d\d\d:\s|Thursday\s->\s\d\d\/\d\d\/\d\d\d\d:\s|Friday\s->\s\\d\d\/\d\d\/\d\d\d\d:\s|Saturday\s->\s\d\d\/\d\d\/\d\d\d\d:\s)/", $in_string);
   $item = str_replace("\r", "", $matches[1]);
   $item = str_replace("\n", "", $item);

   return $item;  
}

function cmp($a, $b){

    $a = strtotime($a[1]);
    $b = strtotime($b[1]);

    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}


?>
