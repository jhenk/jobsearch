<?php
require_once 'login.php';
require_once 'connect.php';
require_once 'db_tools.php';

$myresult = "";
$state = "";
$compname = "";
$db = new mysqli($db_hostname, $db_username, $db_password, $db_database);
$companies = array();
$companiesidxmax = 0;
$j = 0;

session_start();
if (!empty($_SESSION['compname'])) {
	$compname = $_SESSION['compname'];
} else {
	$compname = "";
}

$_POST['AllCompanies'] = '1';
$companies = getcompaniestable($db, $compname);
$companiesidxmax = count($companies) - 1;

if (isset($_POST['AddContactSubmit'])) {
	$contactname = "\"" . $_POST['contactname'] . "\"";
	$contactcomp = "\"" . $_POST['company'] . "\"";
	$contactjobtype = "\"" . $_POST['contactjobtype'] . "\"";
	$contactemail = "\"" . $_POST['contactemail'] . "\"";
	$contactphone = "\"" . $_POST['contactphone'] . "\"";
	$contactfax = "\"" . $_POST['contactfax'] . "\"";

	if (empty($_POST['contactname'])) {
		$state = "No contact to add (contact name blank) - try again.";
	} else {
		$sql = "INSERT INTO contacts (contactname, contactcomp, contactjobtype, contactemail, contactphone, contactfax)
			VALUES ($contactname, $contactcomp, $contactjobtype, $contactemail, $contactphone, $contactfax)";

		$myresult = mysqli_query($db, $sql);

		if (!$myresult) die ("Database access failed: " . mysql_error());
		$state = "$contactname added.";
		$contactname = "";
		$contactcomp = "";
		$contactjobtype = "";
		$contactemail = "";
		$contactphone = "";
		$contactfax = "";
	}
}

if (isset($_POST['CancelContact'])) {
	$state = "Cancelled.";
	$contactname = "";
	$contactcomp = "";
	$contactjobtype = "";
	$contactemail = "";
	$contactphone = "";
	$contactfax = "";
}

if (isset($_POST['NewContact'])) {
	$contactname = "";
	if (empty($compname)) {
		$contactcomp = "";
	} else {
		$contact_comp = $compname;
	}
	$contactjobtype = "";
	$contactemail = "";
	$contactphone = "";
	$contactfax = "";
}

echo <<<_END
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"> 
	<title>Add new Contact</title>
</head>

<body>
	<H1>Add a Contact</H1>
	<form method="post" action="add_a_contact.php">
		Contact Name <input type="text" name="contactname" text size=30 value = $contactname>
		<br>
		Company 
_END;

$company_status = "\t\t<select name='company'>\n";
while($j <= $companiesidxmax) {
	$company_status .= "\t\t\t<option value='" . $companies[$j] . "'";
	if ($companies[$j] == $compname) {
		$company_status .= " selected";
	}
	$company_status .= ">" . $companies[$j] . "</option>\n";
	$j++;
}
$company_status .= "\t\t</select>\n";
		
// now insert the <select> list control into the page

echo <<<_END2
		$company_status
		<br><br>
		Recruiter <input type="radio" name="contactjobtype" value="1" checked="checked" >
		Manager <input type="radio" name="contactjobtype" value="2" value =  >
		<br><br>
		Email: <input type="text" name="contactemail"  text size=30 value = $contactemail >
		<br><br>
		Phone: <input type="text" name="contactphone"  text size=25 value = $contactphone >
		Fax: <input type="text" name="contactfax"  text size=25 value = $contactfax >
		<br><br>
		<input type="submit" value = "Add Contact to database" name="AddContactSubmit" >
		<input type="submit" value = "Cancel" name="CancelContact" >
		<br><hr><br>
		$state
	</form>
	<form method="post" action="mainmenu.php">
		<input type="submit" value = "Main Menu" name="MainMenu" >
	</form>
</body>
</html>
_END2;

?>