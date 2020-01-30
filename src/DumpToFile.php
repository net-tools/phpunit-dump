<?php


namespace Nettools\PHPUnitDump;



/**
 * Dump data to files (1 file per data dumped)
 */
class DumpToFile extends DumpExtension
{
	/**
	 * @param string Directory to save files to
	 */
	protected $_basedir;
	
	
	
	/** 
	 * Constructor
	 *
	 * @param string $basedir Directory to save files to
	 */
	public function __construct($basedir)
	{
		$this->_basedir = $basedir;
	}
	
	
	
	/**
	 * Dump data to a file
	 * 
	 * @param string $data
	 */
	protected function _dump($data)
	{
		try
		{
			if ( !is_array($data) )
				return;
			
			foreach ( $data as $k => $v )
			{
				$f = fopen($this->_basedir . '/' . $k, 'w');
				fwrite($f, $v);
				fclose($f);
			}
		}
		catch (\Throwable $e)
		{
			echo "Exception " . get_class($e) . " in DumpToFile extension : " . $e->getMessage();
		}
	}
}
	
	
?>