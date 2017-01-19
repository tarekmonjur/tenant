<?php
namespace App\Http\Controllers\Setup;

// model class
use App\Models\Setup\Config;
use App\Models\Setup\User;
use App\Models\User as Users;

// form validation class
use App\Http\Requests\ConfigRequest;

// laravel service class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class ConfigController extends Controller
{
    public function __construct(){

    }


    public function index(){
    	Artisan::call('db:connect');
    	return view('config');
    }


    public function config(ConfigRequest $request){

    	$database_name = $this->makeDatabaseName($request->company_name);

    	try{
	    	DB::beginTransaction();

	    	$config = Config::create([
	    			'company_name' => $request->company_name,
	    			'company_address' => $request->company_address,
	    			'database_name' => $database_name,
	    			'application_key' => $request->application_key,
	    		]);

	    	User::create([
	    			'config_id' => $config->id,
	    			'email' => $request->email,
	    		]);
	    	
			DB::statement('CREATE DATABASE IF NOT EXISTS '.$database_name);
			
	    	Artisan::call("db:connect", ['database'=> $database_name]);
	    	Artisan::call("migrate:tenant");

	    	Users::create([
	    			'first_name' => $request->first_name,
	    			'last_name' => $request->last_name,
	    			'email' => $request->email,
	    			'password' => bcrypt($request->password),
	    		]);

	    	DB::commit();

	    	$request->session()->flash('success','Application successfully setup!');

	    }catch(\Exception $e){
	    	Artisan::call('db:connect');
	    	DB::rollback();

	    	Artisan::call('db:connect', ['database'=> $database_name]);
	    	Artisan::call("migrate:tenant:rollback");
	    	DB::statement('DROP DATABASE IF EXISTS '.$database_name);

	    	$request->session()->flash('danger','Application setup not success!');
	    }

    	return back();
    }


    private function makeDatabaseName($database){
    	if(stristr($database,' ')){
    		$database = str_replace(' ', '_', $database);
    		if(stristr($database,'-')){
    			$database = str_replace(' ', '_', $database);
    		}
    	}
    	return $database;
    }


}
