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

Now you can use one of the following methods:

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

* table()
* field()
* where()
* order()
* orWhere()
* innerjoin()
* leftjoin()
* rightjoin()
* get()
* getAll()
* dump()


```
#!php
<?php
$query = $Database->select()
;
?>
```