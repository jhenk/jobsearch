<?php
function getcompaniestable_real($db) {
	$companiesidx = 0;
	$companies = array();
	$dataset = mysqli_query($db, "SELECT * FROM companies");
	while($row=mysqli_fetch_array($dataset)) {
		$companies[$companiesidx] = $row['compname'];
		$companiesidx++;
	}
	return $companies;
}

function getcompaniestable($db, $compsearchKey) {
	$companiesidx = 0;
	$companies = array();

	$compfieldsarray = array("compname", "compstreet1", "compstreet2", "compcitystatezip", "compblank", "compwebsite", "compemail", "compphone", "compfax", "comptext", "compactive");
	$compcount = count($compfieldsarray);
	$contactfieldsarray = array("contactname", "contactcomp", "contactemail", "contactphone", "contactfax");
	$contactcount = count($contactfieldsarray);

    

    if ((isset($_POST['AllCompanies'])) or (isset($_POST['SearchIt']))) {
        $dataset = mysqli_query($db, "SELECT * FROM companies");
    } else {
        $dataset = mysqli_query($db, "SELECT * FROM companies WHERE compactive = 1");
    }
    
	while($row=mysqli_fetch_array($dataset)) {
        if (empty($compsearchKey)) {
            $companies[$companiesidx] = $row['compname'];
            $companiesidx++;
        } else {
            for ($i = 0; $i < $compcount; $i++) {
                $pos = strpos(strtolower($row[$i]),strtolower($compsearchKey));
                if ($pos !== false) {
                    $already_in_list = "0";
                    for ($j = 0; $j < $companiesidx; $j++) {
                        if ($companies[$j] == $row['compname']) {
                            $already_in_list = "1";  // company name is already in list - never mind
                        }
                    }
                    if ($already_in_list == "0") {
                        $companies[$companiesidx] = $row['compname'];
                        $companiesidx++;
                    }
                }
            }
		}
	}
	return $companies;
}

function getCompany($db,$searchstring) {
	$searchstring = "\"" . $searchstring . "\"";
	$sql = "SELECT compname,compstreet1, compstreet2, compcitystatezip,comptype,compwebsite,compemail,compphone,compfax,comptext,compactive FROM companies WHERE compname = $searchstring";
	$myresult = mysqli_query($db, $sql);

	if (!$myresult) die ("Database access failed during getcompany: " . mysql_error());
	$row = mysqli_fetch_row($myresult);
	return $row;
}

function rewrite_company($db, $oldcompname, $compname, $compstreet1, $compstreet2, $compcitystatezip, $comptype, $compwebsite, $compemail, $compphone, $compfax, $comptext, $compactive) {
	$sql = "UPDATE companies SET	compname = \"$compname\",
									compstreet1= \"$compstreet1\",
                                    compstreet2= \"$compstreet2\",
									compcitystatezip = \"$compcitystatezip\",
									comptype = \"$comptype\",
                                    compwebsite = \"$compwebsite\",
                                    compemail = \"$compemail\",
									compphone = \"$compphone\",
									compfax = \"$compfax\",
                                    compactive = \"$compactive\",
                                    comptext = \"$comptext\"  WHERE compname = \"$oldcompname\"";
                                    
	$myresult = mysqli_query($db, $sql);

	if (!$myresult) die ("Database access failed during company update: " . mysql_error());
}

function delete_company($db, $compname) {
	if ($compname != "\"No Company\"") {
		$sql = "DELETE FROM companies WHERE compname = $compname";
		$myresult = mysqli_query($db, $sql);

		if (!$myresult) die ("Database access failed during company update: " . mysql_error());
		return 0;
	} else {
		return 1;
	}
}

function getcontactstable_real($db) {
	$contactsidx = 0;
	$contacts = array();
	$dataset = mysqli_query($db, "SELECT * FROM contacts");
	while($row=mysqli_fetch_array($dataset)) {
		$contacts[$contactsidx] = $row['contactname'];
		$contactsidx++;
	}
	return $contacts;
}

function getcontactstable($db, $contactsearchkey) {
	$contactsidx = 0;
	$contacts = array();
    
    $contactfieldsarray = array("contactname", "contactcomp", "contactemail", "contactjobtype", "contactphone", "contactfax");
	$contactcount = count($contactfieldsarray);

	$dataset = mysqli_query($db, "SELECT * FROM contacts");

	while($row=mysqli_fetch_array($dataset)) {
        if (empty($contactsearchkey)) {
            $contacts[$contactsidx] = $row['contactname'];
            $contactsidx++;
        } else {
            for ($i = 0; $i < $contactcount; $i++) {
                $pos = strpos($row[$i],$contactsearchkey);
                if ($pos !== false) {
                    $contacts[$contactsidx] = $row['contactname'];
                    $contactsidx++;
                }
            }
		}
	}

    return $contacts;
}

function getcompanycontacts($db, $searchstring) {
	$contactsidx = 0;
	$j = 0;
	$contacts = array();
	$dataset = mysqli_query($db, "SELECT * FROM contacts where contactcomp = '$searchstring'");
	if (!empty($dataset)) {
		while($row = mysqli_fetch_array($dataset)) {
			$contacts[$j] = $row;
			$j++;
		}
	}
	return $contacts;
}

function getContact($db, $searchstring) {
	$searchstring = "\"" . $searchstring . "\"";
	$sql = "SELECT contactname,contactcomp,contactjobtype,contactemail,contactphone,contactfax FROM contacts WHERE contactname = $searchstring";
	$myresult = mysqli_query($db, $sql);

	if (!$myresult) die ("Database access failed during getContact: " . mysql_error());
	$row = mysqli_fetch_row($myresult);
	return $row;
}

function rewrite_contact($db, $oldcontactname, $contactname, $contactcomp, $contactjobtype, $contactemail, $contactphone, $contactfax) {
	$sql = "UPDATE contacts SET	contactname = \"$contactname\",
									contactcomp= \"$contactcomp\", 
									contactjobtype = \"$contactjobtype\",
									contactemail = \"$contactemail\",
									contactphone = \"$contactphone\",
									contactfax = \"$contactfax\"  WHERE contactname = \"$oldcontactname\"";
	$myresult = mysqli_query($db, $sql);

	if (!$myresult) die ("Database access failed during rewrite_contact: " . mysql_error());
}

function rewrite_company_contacts($db, $oldcompanyname, $contactcomp) {
	$sql = "UPDATE contacts SET	contactcomp= \"$contactcomp\" WHERE contactcomp = \"$oldcompanyname\"";
	$myresult = mysqli_query($db, $sql);

	if (!$myresult) die ("Database access failed during rewrite_company_contacts: " . mysql_error());
}

function delete_contact($db, $contactname) {
	if ($contactname != "\"No Contact\"") {
		$sql = "DELETE FROM contacts WHERE contactname = $contactname";
		$myresult = mysqli_query($db, $sql);

		if (!$myresult) die ("Database access failed during delete_contact: " . mysql_error());
		return 0;
	} else {
		return 1;
	}
}

function deletecompanycontacts($db, $searchstring) {
	$sql = "DELETE FROM contacts WHERE contactcomp = $searchstring";
	$myresult = mysqli_query($db, $sql);

	if (!$myresult) die ("Database access failed during deletecompanycontacts: " . mysql_error());
}

?>