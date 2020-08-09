<?php

namespace App\Http\Controllers;
use App\models\API\Payment\QIWI;
use App\models\PaymentData;
use App\models\PhotoEditor;
use Illuminate\Http\Request;

class Test extends Controller {
    public function index() {
        $image = "https://photoeditor.vitovtov.info/img/EPSON003.JPG";
        $filters = json_decode(file_get_contents(public_path().'/json/dict.json'), true);
        $photoeditor = new PhotoEditor($image);

        $res = "";

        foreach ($filters as $id => $filter) {
            $res .= $id." - ".$photoeditor->ApplyFilter($id)."\n";
        }

        file_put_contents(public_path()."/log.txt", $res);

        echo $res;
    }
}
