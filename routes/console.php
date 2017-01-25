<?php

// use Illuminate\Foundation\Inspiring;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->describe('Display an inspiring quote');


// Create migration command for own directory
Artisan::command('migrate:own {directory}',function($directory){
	Artisan::call('migrate',['--path'=>'/database/migrations/'.$directory]);
	$this->info('migrations/'.$directory.' directory migration success.');
})->describe('Migrate own directory database.');

Artisan::command('migrate:own:rollback {directory}',function($directory){
	Artisan::call('migrate:rollback',['--path'=>'/database/migrations/'.$directory]);
	$this->info('migrations/'.$directory.' directory rollback success.');
})->describe('Rollback tenant directory database.');


// Create migration command for tenant
Artisan::command('migrate:tenant',function(){
	Artisan::call('migrate',['--path'=>'/database/migrations/tenant']);
	Artisan::call('migrate',['--path'=>'/database/migrations/tenant/relations']);
	$this->info('migration success.');
})->describe('Migrate tenant directory database.');

Artisan::command('migrate:tenant:rollback',function(){
	Artisan::call('migrate:rollback',['--path'=>'/database/migrations/tenant']);
	Artisan::call('migrate:rollback',['--path'=>'/database/migrations/tenant/relations']);
	$this->info('rollback success.');
})->describe('Rollback tenant directory database.');



// Create database connection command
Artisan::command('db:connect {database?}',function($database=null){

	$connection = (!empty($database))? 'mysql_tenant' : 'mysql';
	$database = (!empty($database))? $database : 'tenant';

    \Config::set('database.default',$connection);
    \Config::set('database.connections.'.$connection.'.database',$database);
    \DB::reconnect();

	$this->info('Database connection success.');
})->describe('Dynamically db connection set.');
