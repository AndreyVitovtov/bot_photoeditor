<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Filters extends Controller {
    public function index() {
        $filters = json_decode(file_get_contents(public_path().'/json/_dict.json'), true);
        $f = [];
        foreach($filters as $filter) {
            $f[] = $filter['id'];
        }

        return view('admin.filters.index', [
            'filters' => json_decode(file_get_contents(public_path().'/json/dict.json')),
            '_filters' => $f,
            'menuItem' => 'filters'
        ]);
    }

    public function save(Request $request) {
        $filtersAll = json_decode(file_get_contents(public_path()."/json/dict.json"), true);
        $newFilters = [];
        $filters = $request->post('filters');

        foreach($filters as $id) {
            $newFilters[] = [
                'id' => $id,
                'text_code' => $filtersAll[$id]['text_code'],
                'image_link' => $filtersAll[$id]['image_link'],
                'description' => $filtersAll[$id]['description']
            ];
        }

        file_put_contents(public_path().'/json/_dict.json', json_encode($newFilters));
        return redirect()->to(route('filters'));
    }
}
