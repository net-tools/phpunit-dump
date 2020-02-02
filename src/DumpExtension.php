<?php


namespace Nettools\PHPUnitDump;


use \PHPUnit\Runner\AfterLastTestHook;



abstract class DumpExtension implements AfterLastTestHook
{
	/**
	 * @var string Name of the superglobal var used to store data between tests ; this extension will fetch this global data and use it
	 */
	const GLOBAL_VAR = "__phpunit_dump";
	
	
	
	/**
	 * Called after the last test has been run
	 */
	public function executeAfterLastTest(): void
	{
		// get data in superglobal
		$data = $this->_getData();
		if ( $data && is_array($data) && count($data) )
			$this->_dump($data);
	}	
	
	
	
	/** 
	 * Get data from superglobal var
	 *
	 * @return string
	 */
	protected function _getData()
	{
		return $GLOBALS[self::GLOBAL_VAR];
	}
	
	
	
	/**
	 * Dump data to the superglobal var
	 *
	 * @param string $name Name (title) of data to dump
	 * @param string $data Data to dump as a string
	 */
	public static function dump($name, $data)
	{
		$GLOBALS[self::GLOBAL_VAR] or $GLOBALS[self::GLOBAL_VAR] = array();
		$GLOBALS[self::GLOBAL_VAR][$name] = $data;
	}
	
	
	
	/**
	 * Dump data ; to be implemented in classes inheriting from DumpExtension
	 * 
	 * @param string[] $data Associative array of data values ($name, $value)
	 */
	abstract protected function _dump($data);
}
	
	
?>