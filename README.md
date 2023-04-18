# net-tools/phpunit-dump

## Composer library to dump data from a PHPUnit testsuite

Sometimes we have to check some data produced by a unit test, but this can't be done with PHPUnit assertions.

This library makes it possible do dump this data to a file or to have the dump sent to a mail recipient, for further inspection.


## Setup instructions

To install net-tools/phpunit-dump package, just require it through composer : `require net-tools/phpunit-dump:^1.0.0`.


## How to use ?

The extension must be registered in PHPUnit xml config file. For example, to register `DumpToMail` extension class :

```xml
<extensions>
	<bootstrap class="Nettools\PHPUnitDump\DumpToMail">
		<parameter name="recipient" value="to@mydomain.tld"/>
		<parameter name="from" value="phpunit@mydomain.tld"/>
		<parameter name="body" value="email body text"/>
	</bootstrap>
</extensions>
```

The other PHPUnit extension class is `DumpToFile` which write all data to files inside a given path :

```xml
<bootstrap class="Nettools\PHPUnitDump\DumpToFile">
	<parameter name="path" value="path/to/file"/>
</bootstrap>
```


Then, during a test, call the static method `DumpExtension::dump($name, $data)` to create a dump value ; `$data` and `$name` are string values (in the case of `DumpToFile`, `$name` will be the filename created for this dump).

At the end of the whole PHPUnit test, the values will be dumped (to files or as mail attachments).
