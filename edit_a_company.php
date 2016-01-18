<?php
require_once 'login.php';
require_once 'connect.php';
require_once 'db_tools.php';

$myresult = "";
$state = "";
$employer_status = "";
$headhunter_status = "";
$disabledflag = "";

session_start();

if (isset($_POST['EditCompany'])) {
	$compname = $_SESSION['company_to_edit'];
	$_SESSION['oldcompname'] = $compname;
	$_SESSION['compname'] = $compname;
	$company = getcompany($db, $compname);
	$compstreet1 = $company[1];
	$compstreet2 = $company[2];
	$compcitystatezip = $company[3];
    $comptype = $company[4];
    $compwebsite = $company[5];
	$compemail = $company[6];
	$compphone = $company[7];
	$compfax = $company[8];
	$comptext = $company[9];
    $compactive = $company[10];

	$state = "";
} elseif (isset($_POST['EditCompanySubmit'])) {
	$oldcompname = $_SESSION['oldcompname'];
	$compname = $_POST['compname'];
	$compstreet1 = $_POST['compstreet1'];
	$compstreet2 = $_POST['compstreet2'];
	$compcitystatezip = $_POST['compcitystatezip'];
    $comptype = $_POST['comptype'];
    $compwebsite = $_POST['compwebsite'];
	$compemail = $_POST['compemail'];
	$compphone = $_POST['compphone'];
	$compfax = $_POST['compfax'];
    $comptext = $_POST['comptext'];
    $compactive = $_POST['compactive'];
	rewrite_company($db, $oldcompname, $compname, $compstreet1, $compstreet2, $compcitystatezip, $comptype, $compwebsite, $compemail, $compphone, $compfax, $comptext, $compactive);
    $_SESSION['oldcompname'] = $compname;
    if ($oldcompname != $compname) {
        rewrite_company_contacts($db, $oldcompname, $compname);
        $state = "$compname and all associated contacts edited";
    } else {
        $state = "$compname edited.";
    }
}

if ($compname == "No Company") {
    $disabledflag = "disabled";
}

if (isset($_POST['CancelCompany'])) {
	$state = "Cancelled.";
}

if ($comptype == "1") {
	$employer_status = "checked=\"checked\"";
} elseif ($comptype == "2") {
	$headhunter_status = "checked=\"checked\"";
}

if ($compactive == "1") {
	$active_status = "checked=\"checked\"";
} else {
    $active_status = "\"\"";
}

echo <<<_END
<html>
<head>
        <link href="style.css" rel="stylesheet" type="text/css">
        <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<title>Edit Company</title>
</head>

<body>
<script LANGUAGE="JavaScript">
<!--
function confirmPost()
{
var agree=confirm("Are you sure you want to also delete company and all associated company contacts?");
if (agree)
return true ;
else
return false ;
}
// -->
</script>
<div id="container">
<div id="header">

	<H1>Edit Company</H1>
</div>
	<form method="post" action="edit_a_company.php">
		Company Name <input type="text" name="compname" text size=30 value="$compname">
		<br><br>
		Street <input type="text" name="compstreet1" text size=30 value="$compstreet1">
		<br>
        Street <input type="text" name="compstreet2" text size=30 value="$compstreet2">
        <br>
		City, ST Zip <input type="text" name="compcitystatezip"  text size=40 value="$compcitystatezip">
        <br><br>
		Employer <input type="radio" name="comptype" value="1" $employer_status>
		HH firm <input type="radio" name="comptype" value="2" $headhunter_status>
        <br><br>
        Active <input type="checkbox" name="compactive" value="1" $active_status>
        <br>
		<input type="hidden" name="comptext" value="$comptext">
		<br>
_END;



echo <<<_END2
        Web Site <input type="text" name="compwebsite"  text size=40 value="$compwebsite">
        <br>
        Email: <input type="text" name="compemail"  text size=30 value="$compemail">
		<br><br>
		Main Phone: <input type="text" name="compphone"  text size=25 value="$compphone">
		Fax: <input type="text" name="compfax"  text size=25 value="$compfax">

        <br><br>
        <input type="submit" value = "Submit Changes" name="EditCompanySubmit" $disabledflag>
		<hr>

	</form>
	<form method="post" action="mainmenu.php">
		<input type="submit" value = "Delete Company" name="DeleteCompanySubmit" onClick="return confirmPost()" $disabledflag>
		<hr>
		<input type="submit" value = "Cancel" name="CancelCompanyEdit" $disabledflag>
		<input type="submit" value = "Main Menu" name="MainMenu" >
		<hr>
	</form>
	$state
</div>	
</body>
</html>
_END2;



?>
