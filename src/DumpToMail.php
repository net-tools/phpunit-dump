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
	 * Send the mail
	 * 
	 * @param string $to Mail recipient
	 * @param string $subject Mail subject
	 * @param string $body Email body
	 * @param string $headers Mail top-level headers (including From:)
	 */
	protected function _sendMail($to, $subject, $body, $headers)
	{
		mail($to, $subject, $body, $headers);
	}
	
	
	
	/**
	 * Guessing mimeType
	 *
	 * @param string $file Filename
	 * @param string $def Default mimeType
	 * @return string
	 */
	protected function _guessMimeType($file, $def = 'application/octet-stream')
	{
		// extract file extension (after . symbol)
		$ext = substr(strrchr(strtolower($file), '.'), 1);
	
		switch ( $ext )
		{
			case 'gif':
			case 'jpeg':
			case 'png':
				return "image/$ext";
			case 'jpg':
				return 'image/jpeg';
			case 'mp4':
			case 'mpeg':
			case 'avi':
				return "video/$ext";
			case 'mp3':
				return 'audio/mpeg3';
			case 'wav':
				return 'audio/wav';
			case 'pdf':
				return 'application/pdf';
			case 'htm':
			case 'html':
				return 'text/html';
			case 'txt':
				return 'text/plain';
			case 'eml':
				return 'message/rfc822';
			case 'doc':
				return 'application/msword';
			case 'docx':
				return 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
			case 'xls':
				return 'application/vnd.ms-excel';
			case 'xlsx':
				return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
			case 'ppt':
			case 'pps':
				return 'application/vnd.ms-powerpoint';
			case 'pptx':
			case 'ppsx':
				return 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
			case 'odt':
				return 'application/vnd.oasis.opendocument.text';
			case 'ods':
				return 'application/vnd.oasis.opendocument.spreadsheet';
			case 'odp':
				return 'application/vnd.oasis.opendocument.presentation';
			case 'zip':
				return 'application/zip';
			default :
				return $def;
		}
	}
	
	
	/**
	 * Dump data to a mail
	 */
	public function doDump()
	{
		$atts = [];
		
		
		try
		{
			// for each data dump
			foreach ( self::$data as $k => $v )
				$atts[] = array('file'=>$v, 'filename'=>$k, 'filetype'=>$this->_guessMimeType($k, 'application/octet-stream'));


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
				 "Content-Type: " . $att['filetype'] . ";\r\n   name=\"" . $att['filename'] . "\"\r\n" .
						 "Content-Transfer-Encoding: base64\r\n" .
						 "Content-Disposition: attachment;\r\n   filename=\"" . $att['filename'] . "\"" .
				 "\r\n" .
				 "\r\n" .
				 trim(chunk_split(base64_encode($att['file']))) .
				 "\r\n" .
				 "\r\n";

			$m .= "--$sep--";

			
			// send the mail
			$this->_sendMail($this->_recipient, 'PHPUnit data dump : ' . count($atts) . ' attachments', $m, $h);
			
		}
		catch (\Throwable $e)
		{
			echo "Exception " . get_class($e) . " in " . __CLASS__ . " extension : " . $e->getMessage();
		}
	}
}
	
	
?>