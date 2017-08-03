<?php

echo json_encode('62,63');
exit();
require("/home/imarkclients/public_html/force/PHPMailer_5.2.0/class.phpmailer.php");

$mail = new PHPMailer();

$mail->IsSMTP();                                      // set mailer to use SMTP
$mail->Host = "mail.imarkclients.com";  // specify main and backup server
$mail->SMTPAuth = true;     // turn on SMTP authentication
$mail->Username = "test@imarkclients.com";  // SMTP username
$mail->Password = "aB}enOT-!vd&"; // SMTP password

$mail->From = "test@imarkclients.com";
$mail->FromName = "Mailer";
$mail->AddAddress("navneet1992kaur@gmail.com", "test");

$mail->IsHTML(true);                                  // set email format to HTML

$mail->Subject = "Here is the subject";
$mail->Body    = "<p>Hi,<br /> <br />Would you be interested in building your App and website? We are a professional App Design and Web Design company.<br /> <br />We are expert in the following:<br /> <br />&middot; Mobile Apps (Android, IOS, Windows, IPhone)<br />&middot; Word press Websites<br />&middot; Magneto Websites<br />&middot; Drupal Website<br />&middot; Shopify Websites<br />&middot; Custom Websites<br />&middot; Digital Marketing<br />&middot; Website RE-Design<br />&middot; Search Engine Optimization <br />&middot; Reputation Management<br />&middot; Pay Per Click<br /> <br />If you want to know the price/cost and examples of our website design and app design project, please share your requirements and website URL.<br /> <br />Kind Regards,<br />Simon Seth<br />Business Development Executive<br />Ph No: - +1 310 933 6106<br /> <br />**********<br />Disclaimer: The CAN-SPAM Act of 2003 (Controlling the Assault of Non-Solicited Pornography and Marketing Act) establishes requirements for those who send commercial email, spells out penalties for spammers and companies whose products are advertised in spam if they violate the law, and gives consumers the right to ask mailers to stop spamming them. The above mail is in accordance to the Can Spam act of 2003: There are no deceptive subject lines and is a manual process through our efforts on World Wide Web. You can opt out by sending to ssusanjohnsonn@gmail.com ensure you will not receive any such mails. Or click here unsubscribe<br /></p>";
$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

if(!$mail->Send())
{
   echo "Message could not be sent. <p>";
   echo "Mailer Error: " . $mail->ErrorInfo;
   exit;
}

echo "Message has been sent";
?>
