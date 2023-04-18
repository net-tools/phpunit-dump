<?php


namespace Nettools\PHPUnitDump;


use PHPUnit\Runner\Extension\Extension as PhpunitExtension;
use PHPUnit\Runner\Extension\Facade as EventFacade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

use PHPUnit\Event\TestRunner\ExecutionFinished;
use PHPUnit\Event\TestRunner\ExecutionFinishedSubscriber as ExecutionFinishedSubscriberInterface;




final class ExecutionFinishedSubscriber implements ExecutionFinishedSubscriberInterface
{
	protected $_ext = NULL;
	
	
	
	public __construct(DumpExtension $ext)
	{
		$this->_ext = $ext;
	}
	
	
	
    public function notify(ExecutionFinished $event): void
    {
		/**
		 * Called after the last test has been run
		 */
		public function executeAfterLastTest():void
		{
			// get data in superglobal
			if ( count($this->_ext->data) )
				$this->_ext->doDump();
		}	

   }
}




abstract class DumpExtension implements PhpunitExtension
{
	/**
	 * @var string[] Associative array of (key,value) items to dump 
	 */
	public $data = array();
	
	
	
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
	public function dump($name, $d)
	{
		$this->data[$name] = $d;
	}
	
	
	
	/**
	 * Dump data ; to be implemented in classes inheriting from DumpExtension
	 */
	abstract function doDump();
}
	
	
?>