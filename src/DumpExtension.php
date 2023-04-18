<?php


namespace Nettools\PHPUnitDump;


use \PHPUnit\Runner\Extension\Extension as PhpunitExtension;
use \PHPUnit\Runner\Extension\Facade as EventFacade;
use \PHPUnit\Runner\Extension\ParameterCollection;
use \PHPUnit\TextUI\Configuration\Configuration;

use \PHPUnit\Event\TestRunner\ExecutionFinished;
use \PHPUnit\Event\TestRunner\ExecutionFinishedSubscriber as ExecutionFinishedSubscriberInterface;




final class ExecutionFinishedSubscriber implements ExecutionFinishedSubscriberInterface
{
	protected $_ext = NULL;
	
	
	
	public function __construct(DumpExtension $ext)
	{
		$this->_ext = $ext;
	}
	
	
	
    public function notify(ExecutionFinished $event): void
    {
		// get data in superglobal
		if ( count(DumpExtension::$data) )
			$this->_ext->doDump();
	}
}




abstract class DumpExtension implements PhpunitExtension
{
	/**
	 * @var string[] Associative array of (key,value) items to dump 
	 */
	public static $data = array();	
	
	
	
    public function bootstrap(Configuration $configuration, EventFacade $facade, ParameterCollection $parameters): void
    {
        $facade->registerSubscriber(
            new ExecutionFinishedSubscriber($this)
        );
    }

	
	
	/**
	 * Dump data to the superglobal var
	 *
	 * @param string $name Name (title) of data to dump
	 * @param string $d Data to dump as a string
	 */
	public static function dump($name, $d)
	{
		self::$data[$name] = $d;
	}
	
	
	
	/**
	 * Dump data ; to be implemented in classes inheriting from DumpExtension
	 */
	abstract function doDump();
}
	
	
?>