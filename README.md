# 2DB README #

2db is a php class created to make mysql queries easier. 

### Setup ###

First of all you must require the class

```
#!php
<?php
require_once('2db.php');
?>
```

Once you have required the 2db.php file to your script you must to instance an ToDB object

```
#!php
<?php
require_once('2db.php');

$Database = new ToDB();
?>
```

Now you can use one of the following methods:
* select()
* insert()
* update()
* delete()


* Configuration


```
#!php
<?php
require_once('2db.php');

$Database = new ToDB();
?>
```