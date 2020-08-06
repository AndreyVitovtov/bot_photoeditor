<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequestJSON extends Controller {
    public function index() {
        $view = view('admin.request.request-json');
        $view->json = file_get_contents(public_path()."/json/request.json");
        return $view;
    }
}
