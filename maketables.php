<?php 

	error_reporting(E_ALL & ~E_NOTICE);

	include 'Y_CONFIG/database.php'; 
	use Illuminate\Database\Eloquent\ModelNotFoundException;
	use Illuminate\Database\Eloquent\Model as Eloquent;
	use Illuminate\Database\Capsule\Manager as Capsule; 

	/*
	Because we invoked setGlobal in the config, we can 
	call Capsule statically and it's already pre-primed with
	the necessary config.

	No need to ever share the config file with people
	working on these modules!
	*/

	/*
	This does very much the same job as Laravel's Migrations (without
	any time-tracking or UP/DOWN capibilities.....)
	*/

	Capsule::schema()->create('properties', function($table){  
		$table->increments('id');   
		$table->string('uuid', 128);
		$table->string('county', 128)->nullable();
		$table->string('country', 128)->nullable();
		$table->string('town', 128)->nullable();
		$table->string('description', 1024)->nullable();
		$table->string('displayableAddress', 128)->nullable();
		$table->string('image', 128)->nullable();
		$table->string('thumbnail', 128)->nullable();
		$table->string('latitude', 128)->nullable();
		$table->string('longitude', 128)->nullable();
		$table->string('numberOfBedrooms', 128)->nullable();
		$table->string('numberOfBathrooms', 128)->nullable();
		$table->string('price', 128)->nullable();
		$table->string('propertyType', 1024)->nullable();
		$table->string('saleOrRent', 128)->nullable();
		$table->datetime('updated_at', 128)->nullable();
		$table->datetime('created_at', 128)->nullable();
	});
