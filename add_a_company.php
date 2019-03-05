<?php
require_once 'login.php';
require_once 'connect.php';
require_once 'db_tools.php';

$myresult = "";
$state = "";
$compname = "";

if (isset($_POST['AddCompany'])) {
	$compname = "\"" . $_POST['compname'] . "\"";
	$compstreet1 = "\"" . $_POST['compstreet1'] . "\"";
	$compstreet2 = "\"" . $_POST['compstreet2'] . "\"";
	$compcitystatezip = "\"" . $_POST['compcitystatezip'] . "\"";
	$comptype = "\"" . $_POST['comptype'] . "\"";
	$compwebsite = "\"" . $_POST['compwebsite'] . "\"";
	$compemail = "\"" . $_POST['compemail'] . "\"";
	$compphone = "\"" . $_POST['compphone'] . "\"";
	$compfax = "\"" . $_POST['compfax'] . "\"";
	$comptext = "\"" . "\"";
	$compactive = "\"" . $_POST['compactive'] . "\"";

	if (empty($_POST['compname'])) {
		$state = "No company to add (company name blank) - try again.";
	} else {
        	$comptext = "\"" . "\"";
		$sql = "INSERT INTO companies (compname, compstreet1, compstreet2, compcitystatezip, comptype, compwebsite, compemail, compphone, compfax, comptext, compactive)
			VALUES ($compname, $compstreet1, $compstreet2, $compcitystatezip, $comptype, $compwebsite, $compemail, $compphone, $compfax, $comptext, $compactive)";

		$myresult = mysqli_query($db, $sql);

		if (!$myresult) die ("Database access failed: " . mysqli_error($db) . $myresult);
		$state = "$compname added.";
		$compname = "";
		$compstreet1 = "";
		$compstreet2 = "";
		$compcitystatezip = "";
		$comptype = "";
        	$compwebsite = "";
		$compemail = "";
		$compphone = "";
		$compfax = "";
        	$comptext = "";
		$compactive = "";
	}
}

if (isset($_POST['CancelCompany'])) {
	$state = "Cancelled.";
	$compname = "";
	$compstreet1 = "";
	$compstreet2 = "";
	$compcitystatezip = "";
	$comptype = "";
	$compwebsite = "";
	$compemail = "";
	$compphone = "";
	$compfax = "";
	$comptext = "";
	$compactive = "1";
}

if (isset($_POST['NewCompany'])) {
	$compname = "";
	$compstreet1 = "";
	$compstreet2 = "";
	$compcitystatezip = "";
	$comptype = "";
	$compwebsite = "";
	$compemail = "";
	$compphone = "";
	$compfax = "";
	$comptext = "";
	$compactive = "";
}

echo <<<_END
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"> 
	<title>Add new Company</title>
</head>

<body>
	<H1>Add a Company</H1>
	<form method="post" action="add_a_company.php">
		Company Name <input type="text" name="compname" text size=30 value = $compname>
		<br>
		Street <input type="text" name="compstreet1" text size=30 value = $compstreet1 >
		<br>
		Street <input type="text" name="compstreet2" text size=30 value = $compstreet2 >
        	<br>
		City, ST Zip <input type="text" name="compcitystatezip"  text size=40 value = $compcitystatezip >
		<br><br>
		Employer <input type="radio" name="comptype" value="1" checked="checked" >
		HH firm <input type="radio" name="comptype" value="2" value =  >
		<br><br>
		Email <input type="text" name="compemail"  text size=30 value = $compemail >
		<br>
		Web Site <input type="text" name="compwebsite"  text size=40 value = $compwebsite >
		<br>Main Phone: <input type="text" name="compphone"  text size=25 value = $compphone >
		Fax: <input type="text" name="compfax"  text size=25 value = $compfax >
		<br>

		Active: <input type="checkbox" checked="yes" name="compactive" value="1" >

		<br><br>
		<input type="submit" value = "Add Company to database" name="AddCompany" >
		<hr>
	</form>
	<form method="post" action="mainmenu.php">
		<input type="submit" value = "Main Menu" name="MainMenu" >
		<input type="submit" value = "Cancel" name="CancelCompany" >
		<hr>
	</form>
	$state
</body>
</html>
_END;
?>