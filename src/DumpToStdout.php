<?php


namespace Nettools\PHPUnitDump;



/**
 * Dump data directly to stdout
 */
class DumpToStdout extends DumpExtension
{
	/**
	 * Dump data to stdout
	 * 
	 * @param string[] $data Associative array of data values ($name, $value)
	 */
	protected function _dump($data)
	{
		try
		{
			foreach ( $data as $k => $v )
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