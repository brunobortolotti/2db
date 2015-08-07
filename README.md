# README #

2db is a php class created to make your mysql queries easier. 

## Setup

First of all you must require the class file.

```
<?php
require_once('2db.php');
?>
```

Once you have required the `2db.php` file to your script you must to instance an `ToDB` object

```
<?php
require_once('2db.php');

$Database = new ToDB();
?>
```
## Usage
Now you must configurate the connection using the method `connect()`

```
<?php
require_once('2db.php');

$Database = new ToDB();

$Database->connect('server', 'username', 'password', 'schema', 'charset');
?>
```

### Actions ###

As the name sugests, you can use one of the following methods based on what you want to do:

* `select()`
* `insert()`
* `update()`
* `delete()`

» asdasd

```
<?php
$query = $Database->select();
?>
```

### Methods

By using the `select()` you'll be able to use all of the following methods to improve your query:

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


#### Defining the table you want to work with

You can use the method `table()` to define the table you want in your select statement.

```
<?php
$query = $Database->select()
			->table('user');
?>
```
Output:
```
SELECT * FROM `user`;
```

#### Defining which fields you want from the specified table

You can use the method `field()` to define which columns you want to from the table and set aliases to them.

```
<?php
$query = $Database->select()
			->table('user')
			->field('id')
			->field('user_group_id', 'group_id');
?>
```
Output:
```
SELECT `id`, `user_group_id` as `group_id` FROM `user`;
```

#### Restricting the query

You can use the method `where()` to add restrictions to your query and glue them with `and`

```
<?php
$query = $Database->select()
			->table('user')
			->field('fullname', 'name')
			->where('id', '<>', 55);
			->where('id', '>', 20);
?>
```
Output:
```
SELECT `fullname` as `gname` FROM `user` WHERE `id` <> 55 AND `id` > 20;
```

You also can use the method `orWhere()` to add restrictions to your query and glue them with `or`

```
<?php
$query = $Database->select()
			->table('user')
			->field('fullname', 'name')
			->where('id',  55);
			->orWhere('status', 'active');
?>
```
Output:
```
SELECT `fullname` as `gname` FROM `user` WHERE `id` = 55 OR `status` = 'active';
```

#### Ordering the house

You can use the method `order()` specify the ordering rules

```
<?php
$query = $Database->select()
			->table('user')
			->field('fullname', 'name')
			->where('active', true)
			->order('fullname', 'asc')
			->order('birthdate', 'desc');
?>
```
Output:
```
SELECT `fullname` as `name` FROM `user` WHERE `active` = '1' ORDER BY `fullname` ASC, `birthdate` DESC;
```

#### Joining tables to get more complete data

You can use the method `innerjoin()` create a INNER JOIN the main table with another one

```
<?php
$query = $Database->select()
			->table('user')
			->field('user.name', 'user_name')
			->field('user_group.name', 'user_group_name')
			->innerjoin('user_group', array(array('user.user_group_id', 'user_group.id')))
			->where('user.active', true)
?>
```
Output:
```
SELECT `user`.`name` as `user_name`, `user_group`.`name` as `user_group_name` FROM `user` INNER JOIN `user_group` ON `user`.`user_group_id` = `user_group`.`id` WHERE `user`.`active` = '1';
```
