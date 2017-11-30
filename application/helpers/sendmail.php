<?php
class SendMail
{
	public $headers;
	public function __construct()
    {	
		$header = 'MIME-Version: 1.0';
		$header .= 'Content-type: text/html; charset=iso-8859-1';
		$this->headers = $header;
	}

	public function setparameters($to="",$subject="",$message="",$from="sc7618009@gmail.com")
	{
		if (!isset($to) || empty($to)) 
		{
            throw new \InvalidArgumentException('Kindly define to whom you want to send email!!');
        }

        if (!isset($subject) || empty($subject)) 
		{
            throw new \InvalidArgumentException('Invalid Subject');
        }
		
		if (!isset($message) || empty($message)) 
		{
            throw new \InvalidArgumentException('Invalid Message');
        }
		if (!isset($from) || empty($from))
		{
            throw new \InvalidArgumentException('Invalid message');
        }
		$this->headers .='From:'.$from. "\r\n" .
						'Reply-To:'.$from. "\r\n" ;
		//mail($to,$subject,$message,$this->headers);
		
		$from_name="ForceFlexing";
		include (ABSPATH.'/phpmailer/PHPMailerAutoload.php');
		include (ABSPATH.'/phpmailer/class.phpmailer.php');
		$phpmailer = new PHPMailer();
		$phpmailer->From = $from;
		$phpmailer->FromName = $from_name;
		$phpmailer->isSMTP();   
		$phpmailer->Host = "mail.imarkclients.com";  // specify main and backup server
		$phpmailer->SMTPAuth = true;     // turn on SMTP authentication
		$phpmailer->Username = "test@imarkclients.com";  // SMTP username
		$phpmailer->Password = "aB}enOT-!vd&"; // SMTP password                        // SMTP password
		$phpmailer->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$phpmailer->Port = 25;   
		$phpmailer->Subject = $subject;
		$phpmailer->MsgHTML($message);
		$phpmailer->SMTPDebug=false;
		$phpmailer->AddAddress($to);
		$phpmailer->isHTML(true);
		$phpmailer->Send(); 
		/*if($phpmailer->Send())
		{
			return true;			
		}*/
		
		/*if(!$phpmailer->Send()) 
		{
			echo 'Message was not sent.';
			echo 'Mailer error: ' . $phpmailer->ErrorInfo;
		} else 
		{
			echo 'Message has been sent.';
		}*/
	}
}

?>