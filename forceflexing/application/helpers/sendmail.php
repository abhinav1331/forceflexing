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

	public function setparameters($to="",$subject="",$from="",$message="")
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
		mail($to,$subject,$message,$this->headers);
		
	}
}

?>