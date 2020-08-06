<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class Migrate extends Controller {
    public function index() {
        try{
            Artisan::call('migrate', [
                '--force' => true
            ]);
            echo "Ok";
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function rollback() {
        try{
            Artisan::call('migrate:rollback', [
                '--force' => true
            ]);
            echo "Ok";
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
