<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\models\BotUsers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class Statistics extends Controller {

    public function index () {
        $view = view('admin.statistics.statistics');
        $view->menuItem = "statistics";

        $visitsJson = file_get_contents(public_path("json/visits.json"));
        $visitsArray = json_decode($visitsJson, true);

        for($i = 9; $i >= 0; $i--) {
            $date[] = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') - $i, date('Y')));
        }

        foreach($date as $d) {
            if(isset($visitsArray[$d])) {
                $count = count($visitsArray[$d]);
            }
            else {
                $count = 0;
            }
            $data[] = [
                $d, $count
            ];
        }

        $view->data = $data;

        //Статистика по странам
        $country = DB::select("SELECT country, COUNT(*) AS count FROM users WHERE country <> '' GROUP BY country");
        if(App::getLocale() == "ru") {
            $ISO = json_decode(file_get_contents(public_path("json/ISO_3166-1_alpha-2.json")), true);
        }
        else {
            $ISO = json_decode(file_get_contents(public_path("json/ISO_3166-1_alpha-2_us.json")), true);
        }
        $countries = [];
        foreach($country as $c) {
            $countries[] = [
                $ISO[$c->country], $c->count
            ];
        }
        $view->countries = $countries;

        //Статистика по мессенджерам
        $messenger = DB::select("SELECT messenger, COUNT(*) as count FROM users GROUP BY messenger");
        $messengers = [];
        foreach($messenger as $m) {
            $messengers[$m->messenger] = $m->count;
        }
        $view->messengers = $messengers;

        //Статистика по доступу
        $accessNo = DB::select("SELECT COUNT(*) AS count FROM users WHERE access = '0'");
        $accessPaid = DB::select("SELECT COUNT(*) AS count FROM users WHERE access = '1' AND access_free = '0'");
        $accessFree = DB::select("SELECT COUNT(*) AS count FROM users WHERE access = '1' AND access_free = '1'");
        $view->access = [
            'no' => $accessNo[0]->count,
            'paid' => $accessPaid[0]->count,
            'free' => $accessFree[0]->count
        ];

        //Количество обработаных фотографий
        $countPhoto = DB::select("SELECT date, COUNT(*) AS count FROM process_photos WHERE date > DATE_ADD(NOW(), INTERVAL -10 DAY) GROUP BY date");
        $countPhoto = json_encode($countPhoto);

        $countPhoto = json_decode($countPhoto, true);
        $dataCP = [];
        foreach($countPhoto as $cp) {
            $dataCP[] = [
                $cp['date'],
                $cp['count']
            ];
        }
        $view->countPhoto = $dataCP;

        return $view;
    }
}
