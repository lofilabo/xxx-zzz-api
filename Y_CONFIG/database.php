<?php  
require 'vendor/autoload.php';  
use Illuminate\Database\Capsule\Manager as Capsule;

$apikey =""; //THE API KEY AS PER DOCS IN HERE

$capsule = new Capsule; 

/*
We just made an instance of Capsule; but mostly we will want to call
Static methods on Capsule, **whilst making sure that** the Static class
has been fed the Config below.
How to do this?  Invoke Our capsule instance's "make-me-a-singleton" native method.
*/
$capsule->setAsGlobal();
 
// Setup the Eloquent ORM
$capsule->bootEloquent();

$capsule->addConnection(array(
    'driver'    => 'mysql',
    'host'      => '',		//DB HOST, USUALLY 127.0.0.1
    'database'  => '',		//A new database of your choice
    'username'  => '',		//uname with full perms on this database
    'password'  => '',		//speak friend and enter
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => ''
));
 
$capsule->bootEloquent();
//var_dump($capsule);
