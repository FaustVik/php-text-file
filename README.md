# About

Library for working with files. You can conveniently work with ksv or simple text files.
Also create directories and work with them

#Install

```bash
composer require faustvik/php-text-file
```

# Helper classes

| Class              | Info                                                                                   |
|--------------------|----------------------------------------------------------------------------------------|
| FileInfo           | Get information about a file (owner, size, file existence, etc.)                       |
| FileLocker         | Base class for blocking files with the flock function                                  |
| FileMode           | Contains a list of mods for opening files                                              |
| FileOperation      | The class allows you to manipulate the file - move, delete, clear the file, copy, etc. |
| DirectoryOperation | The class allows you to manipulate the directories -delete, create, open etc.          |
| DirectoryInfo      | The class allows you to for directory operations (existence, scanning)                 |

# Methods

### CSV

| Method                       | Info                                                             |
|------------------------------|------------------------------------------------------------------|
| skipFirstLine()              | Skip first line when reading                                     |
| read()                       | Reading a file into an array                                     |
| setAssociationsIndexKeys()   | Replacing indexed keys with your read associations               |
| associationsKeyWithHeaders() | Replacing indexed keys with first line from file (headers table) |
| overWrite()                  | Overwrite the current file with new data                         |
| write()                      | Append data to the end of the file                               |
| getHeadersColumn()           | Gets the first line in a file                                    |
| updateHeaders()              | Updates the first line in a file                                 |
| deleteColumn()               | Removes an entire columns from a file. The countdown is from 0   |
| deleteLine()                 | Removes an entire lines from a file. The countdown is from 0     |
| getColumns()                 | Return selected array columns. The countdown is from 0           |
| getLines()                   | Return selected array lines. The countdown is from 0             |

### Text File

| Method              | Info                                              |
|---------------------|---------------------------------------------------|
| readToArray()       | Reading a file into an array                      |
| readToString()      | Reading a file into a string                      |
| skipEmptyLine()     | Skip blank line when reading (Only read to array) |  |
| overWrite()         | Overwrite the current file with new data          |
| write()             | Append data to the end of the file                |
| appendToStartFile() | Append data to the start of the file              ||
