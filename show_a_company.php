<?php
require_once 'login.php';
require_once 'connect.php';
require_once 'db_tools.php';

$myresult = "";
$state = "";
$employer_status = "";
$headhunter_status = "";
$disabledflag = "";
$j = 0;
$k = 0;

session_start();

if ((isset($_POST['company_to_show'])) && (!is_null($_POST['company_to_show']))) {
	$compname = $_POST['company_to_show'];
    $_SESSION['oldcompname'] = $_POST['company_to_show'];
    $_SESSION['compname'] = $_POST['company_to_show'];
    $_SESSION['company_to_edit'] = $compname;
} elseif (isset($_POST['ShowCompanySubmit'])) {
	$compname = $_SESSION['compname'];
    $_SESSION['comptext'] = $_POST['comptext'];
}

$company = getcompany($db, $compname);
$contacts = getcompanycontacts($db, $compname);
$contactsidxmax = count($contacts);

$compstreet1 = $company[1];
$compstreet2 = $company[2];
$compcitystatezip = $company[3];
$comptype = $company[4];
$compwebsite = $company[5];
$compemail = $company[6];
$compphone = $company[7];
$compfax = $company[8];
if ((isset($_POST['company_to_show'])) && (!is_null($_POST['company_to_show']))) {
    $comptext = $company[9];
} elseif (isset($_POST['ShowCompanySubmit'])) {
    $comptext = $_POST['comptext'];
}

$compactive = $company[10];

if ($compname == "No Company") {
    $disabledflag = "disabled";
}

if ($comptype == "1") {
	$statustype = "Employer";
} elseif ($comptype == "2") {
	$statustype = "Contracting Agency";
}

if ($compactive == "1") {
	$activetype = "Yes";
} else {
	$activetype = "No";
}

if (isset($_POST['ShowCompanySubmit'])) {
    $comptext = $_POST['comptextedit'];
	rewrite_company($db, $compname, $compname, $compstreet1, $compstreet2, $compcitystatezip, $comptype, $compwebsite, $compemail, $compphone, $compfax, $comptext, $compactive);
	$state = "$compname edited.";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<div id="container">
<div id="header">
<head>
        <link href="style.css" rel="stylesheet" type="text/css">
       	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"> 
	<title>Company</title>
</head>
	<H1>Company</H1>
</div>
<script LANGUAGE="JavaScript">
function input(){
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();
    var weekday=new Array();
    weekday[0]="Sunday";
    weekday[1]="Monday";
    weekday[2]="Tuesday";
    weekday[3]="Wednesday";
    weekday[4]="Thursday";
    weekday[5]="Friday";
    weekday[6]="Saturday";

    var day = weekday[today.getDay()]; 

    if(dd<10) {
        dd='0'+dd;
    }
    if(mm<10) {
        mm='0'+mm;
    }
    today = day + ' -\> ' + mm + '/' + dd + '/'+yyyy;

    document.forms.textform.comptextedit.value = document.forms.textform.comptextedit.value + today + ": ";
    var elem = document.getElementById("comptextedit").focus();
    var val = this.elem.value; //store the value of the element
    this.elem.value = ''; //clear the value of the element
    this.elem.value = val; //set that value back.  
}    
</script>

<script LANGUAGE="JavaScript">
function todo(){
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();
    var weekday=new Array();
    weekday[0]="Sunday";
    weekday[1]="Monday";
    weekday[2]="Tuesday";
    weekday[3]="Wednesday";
    weekday[4]="Thursday";
    weekday[5]="Friday";
    weekday[6]="Saturday";

    var day = weekday[today.getDay()]; 

    if(dd<10) {
        dd='0'+dd;
    }
    if(mm<10) {
        mm='0'+mm;
    }
    today = day + ' -\> ' + mm + '/' + dd + '/'+yyyy;

    document.forms.textform.comptextedit.value = document.forms.textform.comptextedit.value + "[ToDo] " + today + ": ";
    document.getElementById("comptextedit").focus();
}    
</script>

<script LANGUAGE="JavaScript">
function setFocus() {
    document.getElementById("comptextedit").focus();
}
</script>


<script LANGUAGE="JavaScript">
<!--
function confirmPost() {
	var agree=confirm("Are you sure you want to also delete company and all associated company contacts?");
	if (agree)
		return true ;
	else
		return false ;
	}
// -->
</script>

<script LANGUAGE="JavaScript">
<!--
function ConfirmContactPost() {
	var agree=confirm("Are you sure you want to delete contact?");
	if (agree)
		return true ;
	else
		return false ;
	}
// -->
</script>


<body>
<?php
	echo "<H1>$compname</H1>\n";
?>
	<div span style="float:left;">
		<form method="post" action="/jobsearch/edit_a_company.php">
<?php
	echo "		<input type=\"submit\" value = \"Edit Company\" name=\"EditCompany\" $disabledflag>\n";
?>
		</form>
	</div>
	<div span style="float:left;">
		<form method="post" action="/jobsearch/mainmenu.php">
<?php
	echo "		<input type=\"submit\" value=\"Delete Company\" name=\"DeleteCompanySubmit\" onClick=\"return confirmPost()\" $disabledflag>\n";
?>
			<input type="submit" value = "Main Menu" name="MainMenu" >
		</form>
	</div>
		<b>
	<div span style="float:left;">
<?php
echo "\t&nbsp;&nbsp;Address:&nbsp;&nbsp" . $compstreet1 . "&nbsp;&nbsp" . $compstreet2 . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp" . $compcitystatezip . "<br>\n";
echo "\t&nbsp;&nbsp;Company Type:\t$statustype<br>\n";
echo "\t&nbsp;&nbsp;Web Site:\t$compwebsite<br>\n";
echo "\t&nbsp;&nbsp;Email:\t$compemail<br>\n";
echo "\t&nbsp;&nbsp;Phone:\t$compphone - Fax: $compfax<br>\n";
echo "\t&nbsp;&nbsp;Active:\t$activetype\n";

?>
	</div>
	</b>
	<div style="clear:both;"></div>
	<hr>
	<H3>Contacts</H3>
	<b>
 	<form method="post" action="/jobsearch/add_a_contact.php">
 		<input type="submit" value = "Add a Contact" name="NewContact"/>
 	</form>
<?php
    echo "<form method=\"post\" action=\"/jobsearch/edit_a_contact.php\">";
	while($j < $contactsidxmax) {
        $namescontrollist = "<input type=\"radio\" name=\"contact_to_edit\"";
        if (isset($choosecontact) && $choosecontact==$contacts[$j]['contactname']) {
            $namescontrollist .= "\"checked\"";
        }
        $namescontrollist .= "value=\"" . $contacts[$j]['contactname'] . "\">";
                
        $namescontrollist .= $contacts[$j]['contactname'];
        if ($contacts[$j]['contactjobtype'] == 1) {
            $namescontrollist .= " - Recruiter";
        } elseif ($contacts[$j]['contactjobtype'] == 2) {
            $namescontrollist .= " - Manager";
        }
        $namescontrollist .= " - " . $contacts[$j]['contactemail'];
        $namescontrollist .= " - " . $contacts[$j]['contactphone'];
        $namescontrollist .= " - " . $contacts[$j]['contactfax'];
        echo $namescontrollist . "<br>\n";
        $j++;
    }
 ?>
        <br>
<?php
        echo "<input type=\"submit\" value = \"Edit\" name=\"EditContact\" $disabledflag>\n";
?>
    </form>
    
    <form method="post" action="/jobsearch/show_a_company.php" name="textform" id="textform">
        <H3>Items - Remember to click 'Update Items'!!!</H3>
<?php
        echo "<input onclick='input()' type='button' value='Add Date' id='button'>\n";
        echo "<input onclick='todo()' type='button' value='Add ToDo' id='button'><br>\n";
        echo "<textarea rows=\"30\" cols=\"180\" name=\"comptextedit\" class=\"txtarea\" style=\"resize: none;\" data-role=\"none\" id=\"comptextedit\">$comptext</textarea>\n";
?>
                <br>
                <input type="submit" value = "Update Items" name="ShowCompanySubmit" >
    </form>

	</b>
	<hr>
<?php
    echo $state . "\n";
?>
</div>
</body>
</html>
