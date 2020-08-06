<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Locale extends Controller {
    public function index($language) {
        App::setLocale($language);
        session()->put('locale', $language);

        if(Auth::check()) {
            Auth::user()->setLocale($language);
        }

        return redirect()->back();
    }

    public function locale() {
        $view = view('admin.locale.locale');

        return $view;
    }
}
