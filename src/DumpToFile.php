<?php


namespace Nettools\PHPUnitDump;


use \PHPUnit\Runner\Extension\Facade as EventFacade;
use \PHPUnit\Runner\Extension\ParameterCollection;
use \PHPUnit\TextUI\Configuration\Configuration;






/**
 * Dump data to files (1 file per data dumped)
 */
class DumpToFile extends DumpExtension
{
	/**
	 * @param string Directory to save files to
	 */
	protected $_basedir;
	
	
	
    public function bootstrap(Configuration $configuration, EventFacade $facade, ParameterCollection $parameters): void
    {
		if ($parameters->has('path')) {
			$this->_basedir = $parameters->get('path');
		}		
				
		
		// call inherited method
		parent::boostrap($configuration, $facade, $parameters);
    }

	
	
	/**
	 * Dump data to a file
	 */
	public function doDump()
	{
		try
		{
			foreach ( self::$data as $k => $v )
			{
				$f = fopen($this->_basedir . '/' . $k, 'w');
				fwrite($f, $v);
				fclose($f);
			}
		}
		catch (\Throwable $e)
		{
			echo "Exception " . get_class($e) . " in " . __CLASS__ . " extension : " . $e->getMessage();
		}
	}
}
	
	
?>