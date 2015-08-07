# README #

2db is a php class created to make your mysql queries easier. 

### Setup ###

First of all you must require the class file.

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

### Primary Methods ###

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

### Select Secondary Methods ###

```
#!php
<?php
$query = $Database->select();
?>
```

By using the `select()` as primary function you're now able to use all of the following methods as secondary functions:

* `table()`
* `field()` 
* `where()`
* `orWhere()`
* `order()`
* `innerjoin()`
* `leftjoin()`
* `rightjoin()`
* `get()`
* `getAll()`
* `dump()`


`table()`

Defines the main table you want to get data from

```
#!php
<?php
$query = $Database->select()
			->table();
?>
```

`field()`

Defines which columns you want to extract from specified table

```
#!php
<?php
$query = $Database->select()
			->table('user')
			->field('id')
			->field('user_group_id', 'group_id');
?>
```


`where()`

Adds conditions by gluing with `and` operators

```
#!php
<?php
$query = $Database->select()
			->table('user')
			->field('id')
			->field('user_group_id', 'group_id')
			->where('id', 55);
?>
```

