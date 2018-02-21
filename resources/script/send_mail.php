<?php
// email injection check. Specifically, it checks for carriage returns - typically used by spammers to inject a CC list.
function isInjected($str) {
	$injections = array('(\n+)',
	'(\r+)',
	'(\t+)',
	'(%0A+)',
	'(%0D+)',
	'(%08+)',
	'(%09+)'
	);
	$inject = join('|', $injections);
	$inject = "/$inject/i";
	if(preg_match($inject,$str)) {
		return true;
	}
	else {
		return false;
	}
}

/*echo '<script type="text/javascript">'
	,'function errPop(){window.alert("Oops! \n Please ensure you have filled in the required fields before submitting the form.");}'
	,'</script>'
	;
*/

// Load form field data into variables.
$f_name = $_REQUEST['f_name'];
$title = $_REQUEST['title'];
$ozation = $_REQUEST['ozation'];
$st_add01 = $_REQUEST['st_add01'];
$st_add02 = $_REQUEST['st_add02'];
$city = $_REQUEST['city'];
$st_pr = $_REQUEST['st_pr'];
$zip = $_REQUEST['zip'];
$country = $_REQUEST['country'];
$w_phone = $_REQUEST['w_phone'];
$w_fax = $_REQUEST['w_fax'];
$email_address = $_REQUEST['email_address'];
$w_url = $_REQUEST['w_url'];
$h_spon = $_REQUEST['h_spon'];
$gfr_1 = $_REQUEST['gfr_1'];
$gfr_2 = $_REQUEST['gfr_2'];
$gfr_3 = $_REQUEST['gfr_3'];
$gfr_4 = $_REQUEST['gfr_4'];
$comments = $_REQUEST['comments'];

// Message compilation area including word wrap for more then 70 chrs even though who cares lol
$message = "$f_name\r\n$title\r\n$ozation\r\n$st_add01\r\n$st_add02\r\n$city, $st_pr $zip\r\n$country\r\n$w_phone\r\n$w_fax fax\r\n$email_address\r\n$w_url\r\n\r\n Hole Sponsor: $h_spon \r\n Golfers:\r\n 1: $gfr_1 \r\n 2: $gfr_2 \r\n 3: $gfr_3 \r\n 4: $gfr_4 \r\n\r\n$comments";
$remessage = "Thank you for your interest in the 2018 Akron Foundry, Akron Electric Robert A. Sik Memorial Golf Outing. Either Mike or Chris will be in contact with you shortly! \r\n\r\nAs a reminder, all monies are due before June 15, 2018. \r\n\r\nYour Email included the following: \r\n\r\n$message";

$message = wordwrap($message, 70, "\r\n");
$remessage = wordwrap($remessage, 70, "\r\n");

// If the user tries to access this script directly, redirect them to inquiry form
if (!isset($_REQUEST['email_address'])) {header( "Location: quote-request.html" );
}

// If the form fields are empty, trying for popup msg instead of error page.
elseif (empty($email_address) || empty($f_name) || empty($w_phone)){
	echo '<script type="text/javascript">'
		,'window.alert("Oops!\nPlease ensure you have filled in the required fields before submitting the form.\n\nName, Phone Number, and Email Address are required");'
		,'JavaScript:history.go(-1);'
		,'</script>'
		;
}

// If email injection is detected, redirect to the error page.
elseif ( isInjected($email_address) ) {header( "Location: error_message.html" );
}

// If we passed all previous tests, send the email!
else {
mail("michael@akronelectric.com", "Golf Outing Website", $message, "From: $email_address" );
mail("c-sam@akronfoundry.com", "Golf Outing Website", $message, "From: $email_address" );
mail("$email_address", "2017 AFC AE Golf Outing Web", $remessage, "From: michael@akronelectric.com");
mail("c-sam@akronfoundry.com", "SIQW", "Site Used", "From: $email_address");
header( "Location: ../../thank_you.html" );
}
?>
