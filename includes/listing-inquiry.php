<?php

//response generation function

$response = "";

//function to generate response
function wplistings_contact_form_generate_response($type, $message){

	global $response, $post;

	if($type == "success") $response = "<div class='success'>{$message}</div>";
	else $response = "<div class='error'>{$message}</div>";

}

//response messages
$not_human       = "Human verification incorrect.";
$missing_content = "Please supply all required information.";
$email_invalid   = "Email Address Invalid.";
$message_unsent  = "Message was not sent. Try Again.";
$message_sent    = "Thanks! Your message has been sent. We'll be in touch!";

//user posted variables
$name = $_POST['message_name'];
$email = $_POST['message_email'];
$phone = $_POST['message_phone'];
$message = $_POST['message_content'];
$human = $_POST['message_human'];
$url = $_POST['message_url'];

//php mailer variables
$to = get_option('admin_email');
$subject = 'Listing Inquiry for ' . get_the_title();
$headers = 'From: '. $email . "\r\n" .
	'Reply-To: ' . $email . "\r\n";
$message_content = '<p>Message:' . $message . '</p><p>From: ' . $name . '</p><p>From URL: ' . $url . '</p>';

if(!$human == 0){
	if($human != 2) wplistings_contact_form_generate_response("error", $not_human); //not human!
	else {

		//validate email
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
			wplistings_contact_form_generate_response("error", $email_invalid);
		else //email is valid
		{
			//validate presence of name and message
			if(empty($name) || empty($message)){
				wplistings_contact_form_generate_response("error", $missing_content);
			}
			else //ready to go!
			{
				$sent = wp_mail($to, $subject, strip_tags($message_content), $headers);
				if($sent) wplistings_contact_form_generate_response("success", $message_sent); //message sent!
				else wplistings_contact_form_generate_response("error", $message_unsent); //message wasn't sent
			}
		}
	}
}
else if ($_POST['submitted']) wplistings_contact_form_generate_response("error", $missing_content);
?>

<h4>Listing Inquiry</h4>
<?php echo $response; ?>
<form action="<?php the_permalink(); ?>" method="post">
	<p class="message_name"><label for="name">Name: <span class="required">*</span> <br><input type="text" name="message_name" value="<?php echo esc_attr($_POST['message_name']); ?>"></label></p>
	<p class="message_email"><label for="message_email">Email: <span class="required">*</span> <br><input type="text" name="message_email" value="<?php echo esc_attr($_POST['message_email']); ?>"></label></p>
	<p class="message_phone"><label for="message_phone">Phone: <span class="required">*</span> <br><input type="text" name="message_phone" value="<?php echo esc_attr($_POST['message_phone']); ?>"></label></p>
	<p class="message_content"><label for="message_content">Message: <span class="required">*</span> <br><textarea type="text" name="message_content"><?php echo esc_textarea($_POST['message_content']); ?></textarea></label></p>
	<p class="message_human"><label for="message_human">Human Verification: <span class="required">*</span> <br><input type="text" name="message_human"> + 3 = 5</label></p>
	<input type="hidden" name="message_url" value="<?php echo get_permalink(); ?>">
	<input type="hidden" name="submitted" value="1">
	<p><input type="submit" value="Send Inquiry"></p>
</form>