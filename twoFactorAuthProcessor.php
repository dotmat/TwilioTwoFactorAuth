<?php 
 error_reporting(E_ALL);
 ini_set("display_errors", 1);
// Twilio Two Factor Authentication Process. 
session_start();

// Twilio Two Factor Authentication allows you to add a new level of security to your website
// Requiring your users to register a phone number with you. This phone number is used to secure the users account

// If you havent done so already. Please setup an account at: https://www.twilio.com/try-twilio
// You will also need the Twilio PHP library found at: https://github.com/twilio/twilio-php
// Put your Twilio AccountSID here:
$account_sid = "AC...";
//Put your Twilio Auth Key here:
$auth_token = "dcba...";
// Put your Twilio Outgoing SMS enabled number here:
$outboundNumber = "+..."; // This needs to be full international, ie +1.. for US +44... for UK +61.. for Aus etc
// Include the PHP Twilio Library
include "Services/Twilio.php";

// Pick up the POST data from a form and then generates a two-factor password, 
// send this password to the handset via SMS message or voice call

// Get the POST variables from the form
// With all data you get from a user, consider some kind of data clensing to remove SQL syntax or other malicious code. 
$userName = $_POST["userName"]; 
$toNumber = $_POST["tel_number"];
$authMethod = $_POST["method"];

// Check the To number is in the correct format, if not try to correct it. 
if ($toNumber[0] == "+") 
{
// We assume if it has a + symbol its correct
// So we Do nothing to the number
}
else
{
// If the toNumber doesnt have a + we put a + in front
$toNumber = "+" . $toNumber;
// Feel free to add more steps to check the validity of a number, such as google phone lib: https://code.google.com/p/libphonenumber/
}

// Generate a new password for this two factor instance
$password = substr(md5(time().rand(0, 10^10)), 0, 10);

// Assemble the SMS or voice message ready for Twilio to deliver

$content = " Hi ". $userName . ", Your Two Factor Authentication Password is: " . $password;

// If the delivery method is via SMS, make the API call to Twilio sending the message using the supplied credentials. 
$client = new Services_Twilio($account_sid, $auth_token); 

if ($authMethod == "sms") {
 
$client->account->messages->create(array( 
	'To' => $toNumber, 
	'From' => $outboundNumber, 
	'Body' => $content,   
));
}
else // If we are not delivering the message via SMS it must be via voice
{
//Generate a Twimlet Message with the Two Factor Password inside it
$messageURL = "http://twimlets.com/message?Message%5B0%5D=Hello%20". $userName ."%20Your%20Two%20Factor%20Authentication%20Password%20is%20%20" . urlencode(preg_replace("/(.)/i", "\${1},,", $password)) . ".%20Again%2C%20that%20is%20" . urlencode(preg_replace("/(.)/i", "\${1},,", $password)) . "&";

 
$client->account->calls->create($outboundNumber, $toNumber, $messageURL, array( 
	'Method' => 'GET',  
	'FallbackMethod' => 'GET',  
	'StatusCallbackMethod' => 'GET',    
	'Record' => 'false', 
));
}

// Store the username and password in the session - This is for demo purposes only!
// When your done testing remove this to prevent the username and password being stored on the client machine. 
$_SESSION['username'] = $userName;
$_SESSION['password'] = $password;

header("Location: index.php");
?>