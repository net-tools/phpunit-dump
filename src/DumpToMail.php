<?php


namespace Nettools\PHPUnitDump;



/**
 * Dump data to mail (one attachment per data dump)
 */
class DumpToMail extends DumpExtension
{
	/**
	 * @param string Recipient to send mail to
	 */
	protected $_recipient;
	
	
	/**
	 * @param string 'From' header
	 */
	protected $_from;
	
	
	
	/**
	 * @param string Content body
	 */
	protected $_content;
	
	
	
	/** 
	 * Constructor
	 *
	 * @param string $recipient Recipient to send dump data to
	 * @param string $from 'From' header
	 * @param string $content Content body of mail
	 */
	public function __construct($recipient, $from = 'dump@phpunit.de', $content = 'Dumping from unit tests.')
	{
		$this->_recipient = $recipient;
		$this->_from = $from;
		$this->_content = $content;
	}
	
	
	
	/**
	 * Dump data to a file
	 * 
	 * @param string $data
	 */
	protected function _dump($data)
	{
		$atts = [];
		
		
		try
		{
			if ( !is_array($data) )
				return;
			
						
			// for each data dump
			foreach ( $data as $k => $v )
				$atts[] = array('file'=>$v, 'filename'=>$k, 'application/octet-stream');


			$sep = "MailMultipart-mixed-" . sha1(uniqid());
			$h = 	"From: " . $this->_from . 
					"\r\n" .
					"Content-Type: multipart/mixed;\r\n   boundary=\"$sep\"";           
			
			$m =	"--$sep" . 
					"\r\n" .
					"Content-Type: text/plain; charset=UTF-8\r\n" .
						   "Content-Transfer-Encoding: quoted-printable" .
					"\r\n" .
					"\r\n" .
					trim(str_replace("=0A", "\n", str_replace("=0D", "\r", imap_8bit($this->_content)))) .
					"\r\n" . 
					"\r\n";

			foreach ( $atts as $att )
			$m .="--$sep" .
				 "\r\n" .
				 "Content-Type: application/octet-stream;\r\n   name=\"" . $att['filename'] . "\"\r\n" .
						 "Content-Transfer-Encoding: base64\r\n" .
						 "Content-Disposition: attachment;\r\n   filename=\"" . $att['filename'] . "\"" .
				 "\r\n" .
				 "\r\n" .
				 trim(chunk_split(base64_encode($att['file']))) .
				 "\r\n" .
				 "\r\n";

			$m .= "--$sep--";

			mail($this->_recipient, 'PHPUnit data dump : ' . count($atts) . ' attachments', $m, $h);
			
		}
		catch (\Throwable $e)
		{
			echo "Exception " . get_class($e) . " in DumpToMail extension : " . $e->getMessage();
		}
	}
}
	
	
?>