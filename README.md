# net-tools/phpunit-dump

## Composer library to dump data from a PHPUnit testsuite

Sometimes we have to check some data produced by a unit test, but this can't be done with PHPUnit assertions.

This library makes it possible do dump this data to a file or to have the dump sent to a mail recipient, for further inspection.


## Setup instructions

To install net-tools/phpunit-dump package, just require it through composer : `require net-tools/phpunit-dump:^1.0.0`.


## How to use ?

The extension must be registered in PHPUnit xml config file. For example, to register `DumpToMail` extension class :

```xml
<extension class="Nettools\PHPUnitDump\DumpToMail">
	<arguments>
		<string>email_recipient@domain.tld</string>
		<string>from_address@domain-test.tld</string>
    <string>Email body</string>
	</arguments>
</extension>
```

The other PHPUnit extension class is `DumpToFile` which write all data to files inside a given path :

```xml
<extension class="Nettools\PHPUnitDump\DumpToFile">
	<arguments>
		<string>directory to store dump files into</string>
	</arguments>
</extension>
```
