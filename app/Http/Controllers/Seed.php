<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class Seed extends Controller {
    public function index() {
        try{
            Artisan::call('migrate:refresh');
            Artisan::call('db:seed');
            echo "Ok";
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
