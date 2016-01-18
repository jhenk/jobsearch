<?php
require_once 'login.php';
require_once 'connect.php';
require_once 'db_tools.php';

$myresult = "";
$state = "";
$j = 0;
$recruiter_status = "";
$manager_status = "";
$disabledflag = "";

session_start();

$_POST['AllCompanies'] = "1";
$companies = getcompaniestable($db, "");
$companiesidxmax = count($companies) - 1;

if (isset($_POST['EditContact'])) {
	$contactname = $_POST['contact_to_edit'];
	$_SESSION['oldcontactname'] = $_POST['contact_to_edit'];
	$_SESSION['contactname'] = $_POST['contact_to_edit'];
	
	$contact = getcontact($db, $contactname);

	$contactcomp = $contact[1];
	$contactjobtype = $contact[2];
	$contactemail = $contact[3];
	$contactphone = $contact[4];
	$contactfax = $contact[5];

	$state = "";
} elseif (isset($_POST['EditContactSubmit'])) {
	$oldcontactname = $_SESSION['oldcontactname'];
	$contactname = $_POST['contactname'];
	$contactcomp = $_POST['contactcomp'];
	$contactjobtype = $_POST['contactjobtype'];
	$contactemail = $_POST['contactemail'];
	$contactphone = $_POST['contactphone'];
	$contactfax = $_POST['contactfax'];
	rewrite_contact($db, $oldcontactname, $contactname, $contactcomp, $contactjobtype, $contactemail, $contactphone, $contactfax);
	$state = "$contactname edited.";
}

if ($contactname == "No Contact") {
    $disabledflag = "disabled";
}

if (isset($_POST['CancelPerson'])) {
	$state = "Cancelled.";
}

echo <<<_END
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"> 
	<title>Edit Contact</title>
</head>

<script LANGUAGE="JavaScript">
function notEmpty() {
	var myTextField = document.getElementById('contactname');
	if(myTextField.value == "") {
		alert("Contact Name cannot be blank - ignoring.");
        return false;
    } else {
        return true;
    }
}
</script>

<script LANGUAGE="JavaScript">
<!--
function confirmPost() {
var agree=confirm("Are you sure you want to delete contact?");
if (agree)
return true ;
else
return false ;
}
// -->
</script><body>
	<H1>Edit Contact</H1>
    
	<div span style="float:left;">
	<form method="post" action="edit_a_contact.php">
		Contact Name <input type="text" id="contactname" name="contactname" text size=30 value="$contactname">
		<br>
		Company
_END;
$company_status = "\n\t\t<select name='contactcomp'>\n";

while($j <= $companiesidxmax) {
	$company_status .= "\t\t\t<option value=" . "\"" . $companies[$j] . "\"";
	if ($companies[$j] == $contactcomp) {
		$company_status .= " selected";
	}
	$company_status .= '>' . $companies[$j];
	$company_status .= "</option>\n";
	$j++;
}
$company_status .= "\t\t</select>\n";

if ($contactjobtype == 1) {
	$recruiter_status = "checked=\"checked\"";
} elseif ($contactjobtype == 2) {
	$manager_status = "checked=\"checked\"";
}

echo <<<_END2
		$company_status
		<br><br>
		Recruiter <input type="radio" name="contactjobtype" value="1" $recruiter_status/>
		Manager <input type="radio" name="contactjobtype" value="2" $manager_status>
		<br><br>
		Email<input type="text" name="contactemail" text size=30 value="$contactemail">
        <br>
		Phone: <input type="text" name="contactphone"  text size=25 value="$contactphone">
		Fax: <input type="text" name="contactfax"  text size=25 value="$contactfax">
		<br><br>
		<input type="submit" id="EditContactSubmit" onclick="return (notEmpty())" value="Submit Changes" name="EditContactSubmit" $disabledflag>
	</form>
	</div>
	<div style="clear:both;"></div>
	<hr><br>
	<div span style="float:left;">
	<form method="post" action="mainmenu.php">
		<input type="submit" value = "Delete Contact" name="DeleteContactSubmit"  onClick="return (confirmPost())" $disabledflag>
		<input type="submit" value = "Cancel" name="CancelContactEdit" $disabledflag>
		<input type="submit" value = "Main Menu" name="MainMenu" >
	</form>
	</div>
	<div style="clear:both;"></div>
	<br><hr><br>
	$state
	
</body>
</html>
_END2;

?>