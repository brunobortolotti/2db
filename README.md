# README #

2db is a php class created to make mysql queries easier. 

### Setup ###

First of all you must require the class

```
#!php
<?php
require_once('2db.php');
?>
```

Once you have required the `2db.php` file to your script you must to instance an `ToDB` object

```
#!php
<?php
require_once('2db.php');

$Database = new ToDB();
?>
```

Now you must configurate the connection using the method `config()`

```
#!php
<?php
require_once('2db.php');

$Database = new ToDB();

$Database->setup('server', 'username', 'password', 'schema', 'charset');
?>
```

Now you can use one of the following methods as primary function:

* `select()`
* `insert()`
* `update()`
* `delete()`

## Select() ##

```
#!php
<?php
$query = $Database->select();
?>
```

### Select methods ###

```
#!php
<?php
$query = $Database->select()
;
?>
```

By using the `select()` as primary function you're now able to use all of the following methods as secondary functions:

* `table()`: Defines the main table you want to get data from
* `field()`: Defines which columns you want to extract from specified table
* `where()`: Adds conditions by gluing with `and` operators
* `orWhere()`: Adds conditions by gluing with `or` operators
* `order()`: Defines the ordering rules
* `innerjoin()`
* `leftjoin()`
* `rightjoin()`
* `get()`
* `getAll()`
* `dump()
