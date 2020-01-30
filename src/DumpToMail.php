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
			
						
			// temp dir 
			$tmp = sys_get_temp_dir() . '/' . uniqid() . '-';
			
			// for each data dump
			foreach ( $data as $k => $v )
			{
				$f = fopen($tmp . $k, 'w');
				fwrite($f, $v);
				fclose($f);
				
				$atts[] = $tmp.$k;
			}
			
			
			// creating a Multipart mail with attachments
			Mailer::getDefault()->expressSendmail($this->_content, $this->_from, $this->_recipient, 'PHPUnit data dump : ' . count($atts) . ' attachments', $atts);
		}
		catch (\Throwable $e)
		{
			echo "Exception " . get_class($e) . " in DumpToMail extension : " . $e->getMessage();
		}
		finally
		{
			// deleting temp files
			foreach ( $atts as $att )
				unlink($att);
		}
	}
}
	
	
?>