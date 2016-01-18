<?php

$myresult = "";
$state = "";
$mainSearchKey = "";
$r = "";
$j = 0;
$companies = array();
$contacts = array();

session_start();

require_once 'login.php';
require_once 'connect.php';
require_once 'db_tools.php';

#print_r($_POST);

if (isset($_POST['DeleteCompanySubmit'])) {
	$compname = "\"" . $_SESSION['compname'] . "\"";
    if ($compname != "\"\"") {
        if (delete_company($db, $compname) == 1) {
            $state = "Access Denied.  May not delete \"No Company\" company name.";
        } else {
            $state = "Company $compname deleted. - ";
            deletecompanycontacts($db, $compname);
            $state .= "All $compname contacts deleted.";
        }
    } else {
        $state = "No company chosen to be deleted - ignored";
    }
}

if (isset($_POST['DeleteContactSubmit'])) {
	$contactname = "\"" . $_SESSION['contactname'] . "\"";	
    if ($contactname != "\"\"") {
        if (delete_contact($db, $contactname) == 1) {
            $state = "Access Denied.  May not delete \"No Contact\" contact name.";
        } else {
            $state = "$contactname deleted.";
        }
    } else {
        $state = "No contact chosen to be deleted - ignored";
    }
}

if (isset($_POST['SearchIt'])) {
    $mainSearchKey = $_POST['SearchTerm'];
}

if (isset($_POST['SearchContact'])) {
    $contactSearchKey = $_POST['ContactSearchTerm'];
}


$_SESSION['compname'] = "";
$_SESSION['contactname'] = "";

$companies = getcompaniestable($db, $mainSearchKey);
$contacts = getcontactstable($db, $contactSearchKey);

$companiesidxmax = count($companies) - 1;
$contactsidxmax = count($contacts) - 1;

print "\n";
?>
<head>
	<link href="style.css" rel="stylesheet" type="text/css">
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"> 
	<title>Main Menu</title>
</head>

<body>
<div id="container">
<div id="header">
	<H1>Main Menu</H1>
</div>

	<H3>Company</H3>

<!-- **********************************************************************-->
	<form method="post" action="add_a_company.php">
		<input type="submit" value = "Add Company to database" name="NewCompany"/>
	</form>
<!-- **********************************************************************-->
	<br>

    <form method="post" action="mainmenu.php">
        <input type="submit" value = "Display Active Companies Only" name="ActiveOnly">
        <input type="submit" value = "Display All Companies" name="AllCompanies">
    </form>
    
    <form method="post" action="show_a_company.php">	

<?php
		$company_status = "\t\t<select name='company_to_show'>\n";
		while($j <= $companiesidxmax) {
			$company_status .= "\t\t\t<option value=\"" . $companies[$j] . "\"";
			$company_status .= ">" . $companies[$j] . "</option>\n";
			$j++;
		}
		$company_status .= "\t\t</select>\n";
		
		// now insert the <select> list control into the page
		echo $company_status;

		if ($companiesidxmax > -1) {
			$showcompaniessubmit = "\t\t" . '<input type="submit" value="Show Company" name="ShowCompany">' . "\n";
		} else {
			$showcompaniessubmit = "\t\t" . '<input type="submit" value="Show Company" name="ShowCompany" disabled>' . "\n";
		}
		echo "$showcompaniessubmit";
?>
    </form>


    <form method="post" action="mainmenu.php">	

<?php		
        if (isset($mainSearchKey)) {
            $searchsubmit = "Search string: <input type=\"text\" name=\"SearchTerm\" value=" . $mainSearchKey . ">";
        } else {
            $searchsubmit = "Search string: <input type=\"text\" name=\"SearchTerm\" value=\"\">";
        }
        echo "$searchsubmit";

        $searchtermsubmit = "\t\t" . '<input type="submit" value = "Search" name="SearchIt">' . "\n";
        echo "$searchtermsubmit";
?>

	</form>

<!-- **********************************************************************-->
	
	<hr>
	<H3>Contacts</H3>

<!-- **********************************************************************-->
 	<form method="post" action="add_a_contact.php">
 		<input type="submit" value = "Add a Contact" name="NewContact"/>
 	</form>
<!-- **********************************************************************-->

	<br>
	<form method="post" action="edit_a_contact.php">
<?php
		$j = 0;
		$contact_status = "\t\t<select name='contact_to_edit'>\n";
		while($j <= $contactsidxmax) {
			$contact_status .= "\t\t\t<option value=\"" . $contacts[$j] . "\"";
			$contact_status .= ">" . $contacts[$j] . "</option>\n";
			$j++;
		}
		$contact_status .= "\t\t</select>\n";
		
		// now insert the <select> list control into the page
		echo $contact_status;
		if ($contactsidxmax > -1) {
			$editcontactsubmit = "\t\t" . '<input type="submit" value = "Edit Contact" name="EditContact">' . "\n";
		} else {
			$editcontactsubmit = "\t\t" . '<input type="submit" value = "Edit Contact" name="EditContact" disabled>' . "\n";
		}
		echo "$editcontactsubmit";
?>
	</form>
	
	<form method="post" action="mainmenu.php">	

<?php		
        if (isset($mainSearchKey)) {
            $searchsubmit = "Search string: <input type=\"text\" name=\"ContactSearchTerm\" value=" . $contactSearchKey . ">";
        } else {
            $searchsubmit = "Search string: <input type=\"text\" name=\"ContactSearchTerm\" value=\"\">";
        }
        echo "$searchsubmit";

        		// now insert the <select> list control into the page
        $searchtermsubmit = "\t\t" . '<input type="submit" value = "Search" name="SearchContact">' . "\n";
        echo "$searchtermsubmit";
?>

	</form>
	<br>
	<hr>
<!-- **********************************************************************-->
	
<!-- **********************************************************************-->
	<br>
	<hr>

<?php
	echo $state;
?>
</div>
</body>
</html>
