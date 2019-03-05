<?php 



include_once('config.php');


// Configuration option.

$recipent_address = CONTACT_FORM_RECIPIENT;
$default_subject = CONTACT_DEFAULT_SUBJECT;

$name = $_POST['mrs_name'];
$email = $_POST['mrs_email'];
$message = $_POST['mrs_message'];
$json = [];
$check = true;

$validate = filter_var($email, FILTER_VALIDATE_EMAIL);

if(!$validate){
	$check = false;
	$json['email_error'] = 'Invalid email address.';
}

$subject = $default_subject;


// Configuration option.
// i.e. The standard subject will appear as, "You've been contacted by John Doe."

// Example, $e_subject = '$name . ' has contacted you via Your Website.';

$e_subject = 'You have been contacted by ' . $name . '.';


// Configuration option.
// You can change this if you feel that you need to.
// Developers, you may wish to add more fields to the form, in which case you must be sure to add them here.

$e_body = "You have been contacted by $name. Their additional message is as follows." . PHP_EOL . PHP_EOL;
$e_content = "Subject : $subject"  . PHP_EOL . PHP_EOL;
$e_content .= "\"$message\"" . PHP_EOL . PHP_EOL;
$e_reply = "You can contact $name via email, $email";

$msg = wordwrap( $e_body . $e_content . $e_reply, 70 );

$headers = "From: $email" . PHP_EOL;
$headers .= "Reply-To: $email" . PHP_EOL;
$headers .= "MIME-Version: 1.0" . PHP_EOL;
$headers .= "Content-type: text/plain; charset=utf-8" . PHP_EOL;
$headers .= "Content-Transfer-Encoding: quoted-printable" . PHP_EOL;

$mail = mail($recipent_address, $e_subject, $msg, $headers);


if($mail && $check) {
	$json['email_success'] = 'Email sent successfully!';
}

echo json_encode($json);