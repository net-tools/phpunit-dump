<?php

namespace Nettools\PHPUnitDump\Tests;


use \Nettools\PHPUnitDump\DumpToFile;
use \org\bovigo\vfs\vfsStream;



class UnitTestDumpToFileExtension extends DumpToFile
{
	protected function _getData()
	{
		return array('dump1.txt'=>'data dump 1', 'dump2.txt'=>'data dump 2');
	}

}



class DumpToFileTest extends \PHPUnit\Framework\TestCase
{
    public function testDump()
    {
		$vfs = vfsStream::setup('root');
		
		$ext = new UnitTestDumpToFileExtension($vfs->url());
		$ext->executeAfterLastTest();
		
		$this->assertEquals(true, file_exists($vfs->url() . '/dump1.txt'));
		$this->assertEquals(true, file_exists($vfs->url() . '/dump2.txt'));
		
		$this->assertEquals('data dump 1', file_get_contents($vfs->url() . '/dump1.txt'));
		$this->assertEquals('data dump 2', file_get_contents($vfs->url() . '/dump2.txt'));
    }
}

?>