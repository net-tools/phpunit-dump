<?php


namespace Nettools\PHPUnitDump;



/**
 * Dump data directly to stdout
 */
class DumpToStdout extends DumpExtension
{
	/**
	 * Dump data to stdout
	 */
	public function doDump()
	{
		try
		{
			foreach ( $this->data as $k => $v )
			{
				echo "\r\n";
				echo "-- DUMP '$k' --\r\n";
				echo $v;
				echo "\r\n";
				echo "===================";
				echo "\r\n";
				echo "\r\n";
			}
		}
		catch (\Throwable $e)
		{
			echo "Exception " . get_class($e) . " in " . __CLASS__ . " extension : " . $e->getMessage();
		}
	}
}
	
	
?>