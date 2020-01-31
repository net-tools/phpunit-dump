<?php

namespace Nettools\PHPUnitDump\Tests;


use \Nettools\PHPUnitDump\DumpToMail;



class UnitTestDumpToMailExtension extends DumpToMail
{
	protected function _getData()
	{
		return array('dump1.html'=>'data dump 1', 'dump2.html'=>'data dump 2');
	}

	protected function _sendMail($to, $subject, $body, $headers)
	{
		$this->stub($to, $subject, $body, $headers);
	}
	
	
	public function stub($to, $subject, $body, $headers)
	{
		
	}
}



class DumpToMailTest extends \PHPUnit\Framework\TestCase
{
    public function testDump()
    {
		// create a stub for user-defined class above, with assertions that the 'stub' method is called with specific parameters
		$ext = $this->getMockBuilder(UnitTestDumpToMailExtension::class)->setMethods(['stub'])->setConstructorArgs(['to@domain.tld', 'from@domain.tld', 'body content'])->getMock();
		$ext->expects($this->once())->method('stub')->with(
				// recipient
				$this->equalTo('to@domain.tld'), 

				// subject
				$this->equalTo('PHPUnit data dump : 2 attachments'),
			
				// body
				$this->callback(function($s)
								{
									// guessing multipart separator
									if ( !preg_match('/--(.+)\r\n/', $s, $regs) )
										return false;
									
									$sep = $regs[1];

									$m =	"--$sep" . 
											"\r\n" .
											"Content-Type: text/plain; charset=UTF-8\r\n" .
												   "Content-Transfer-Encoding: quoted-printable" .
											"\r\n" .
											"\r\n" .
											trim(str_replace("=0A", "\n", str_replace("=0D", "\r", imap_8bit('body content')))) .
											"\r\n" . 
											"\r\n";

									for ( $i = 1 ; $i <= 2 ; $i++ )
										$m .="--$sep" .
											 "\r\n" .
											 "Content-Type: text/html;\r\n   name=\"dump$i.html\"\r\n" .
													 "Content-Transfer-Encoding: base64\r\n" .
													 "Content-Disposition: attachment;\r\n   filename=\"dump$i.html\"" .
											 "\r\n" .
											 "\r\n" .
											 trim(chunk_split(base64_encode("data dump $i"))) .
											 "\r\n" .
											 "\r\n";

									$m .= "--$sep--";
									
									return $m == $s;
								}
								),
			
				// headers
				$this->stringContains(	"From: from@domain.tld" .
										"\r\n" .
										"Content-Type: multipart/mixed;\r\n   boundary=\"")
			)->willReturn('ok');
		$ext->executeAfterLastTest();
		
    }
}

?>