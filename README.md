# About

Simple library for working with text files (CSV, .txt and etc.)

# Methods

### Common functions

| Method | Info |
| ------ | ------ |
| getPathFile() | Get the path to the current file |
| getName() | Get file name |
| getSize() | Get file size (in bytes) |
| getExtension() | Get file extension  |
| exist() | Check file exist  |

### CSV

| Method | Info |
| ------ | ------ |
| skipFirstLine() | Skip first line when reading |
| readToArray() | Reading a file into an array |
| setAssociationsIndexKeys() | Replacing indexed keys with your read associations |
| overwriteToFile() | Overwrite the current file with new data  |
| appendToFile() | Append data to the end of the file |
| saveToNewFile() | Save data from current file to new file |

### Text File

| Method | Info |
| ------ | ------ |
| readFileToArray() | Reading a file into an array |
| readFileToString() | Reading a file into a string |
| skipEmptyLine() | Skip blank line when reading |
| ignoreNewLines() | Skip a newline at the end of each array element  |

# Example

### CSV

#### Read file

```php
<?php

use FaustVik\Files\Csv;

require dirname(__DIR__) . '/vendor/autoload.php';

$path_to_file = __DIR__ . '/GeoIP2-Country-Locations-en.csv';

$csv = new Csv($path_to_file);

$csv->setAssociationsIndexKeys([
    'geo_name',
    'local_code',
    'continent_code',
    'continent_name',
    'country_iso_code',
    'country_name',
    'is_in_european_union',
])->skipFirstLine();

$data = $csv->readToArray();
```

Result with associations:

```php
  array(7) {
    'geo_name' =>
    string(5) "49518"
    'local_code' =>
    string(2) "en"
    'continent_code' =>
    string(2) "AF"
    'continent_name' =>
    string(6) "Africa"
    'country_iso_code' =>
    string(2) "RW"
    'country_name' =>
    string(6) "Rwanda"
    'is_in_european_union' =>
    string(1) "0"
  }


```

Result without associations:

```php
  array(7) {
  [0] =>
  string(5) "49518"
  [1] =>
  string(2) "en"
  [2] =>
  string(2) "AF"
  [3] =>
  string(6) "Africa"
  [4] =>
  string(2) "RW"
  [5] =>
  string(6) "Rwanda"
  [6] =>
  string(1) "0"
}

```

#### Write to file

```php
<?php

use FaustVik\Files\Csv;

require dirname(__DIR__) . '/vendor/autoload.php';

$path_to_file = __DIR__ . '/GeoIP2-Country-Locations-en.csv';

$csv = new Csv($path_to_file);

$new_data  = [['line', 'line', 'line', 'line', 'line']];
$new_data1 = [['line123', 'line123', 'line123', 'line123', 123]];

$csv->overwriteToFile($new_data);
$csv->appendToFile($new_data1);
```

#### Save to new file

```php
<?php

use FaustVik\Files\Csv;

require dirname(__DIR__) . '/vendor/autoload.php';

$path_to_file     = __DIR__ . '/GeoIP2-Country-Locations-en.csv';
$path_to_new_file = __DIR__ . '/new_file.csv';

$csv = new Csv($path_to_file);

$csv->saveToNewFile($path_to_new_file);
```