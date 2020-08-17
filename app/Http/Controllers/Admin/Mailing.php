<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\models\BotUsers;
use App\models\Chat;
use App\models\Khatma;
use App\models\UsersChats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class Mailing extends Controller{
    public function index(Request $request) {
        $view = view('admin.mailing.mailing');
        $view->menuItem = "mailing";

        if(file_exists(public_path()."/json/mailing_task.json")) {
            $task = file_get_contents(public_path()."/json/mailing_task.json");
            $task = json_decode($task, true);
            $disable = "disabled";
        }
        else {
            $task = "";
            $disable = "";
        }
        $view->task = $task;
        $view->disable = $disable;
        $res = DB::select("SELECT country FROM users WHERE country != '' GROUP BY country");
        $countries = [];
        if(App::getLocale() == "ru") {
            $ISO_3166_1_alpha_2 = json_decode(file_get_contents(public_path()."/json/ISO_3166-1_alpha-2.json"), true);
        }
        else {
            $ISO_3166_1_alpha_2 = json_decode(file_get_contents(public_path()."/json/ISO_3166-1_alpha-2_us.json"), true);
        }
        foreach($res as $c) {
            $countries[$c->country] = $ISO_3166_1_alpha_2[$c->country];
        }

        $view->countries = $countries;
        return $view;
    }

    public function send(Request $request) {
        $params = $request->input();
        if(empty($params['text'])) {
            return redirect()->to("/admin/mailing");
        }
        else {
            $task = [];
            if($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time().$file->getClientOriginalName();
                $file->move(public_path() . '/img', $fileName);
                $task['type'] = "img";
                $task['img'] = url('/')."/img/".$fileName;
            }
            else {
                if(!empty($params['url_image'])) {
                    $task['type'] = "img";
                    $task['img'] = $params['url_image'];
                }
                else {
                    $task['type'] = "text";
                    $task['text'] = $params['text'];
                }
            }

            $count = 0;

            if($params['country'] == "%") {
                $country = "(country LIKE '%' OR country IS NULL)";
            }
            else {
                $country = "country LIKE '".$params['country']."'";
            }

            if($params['access'] == "all") {
                $db = DB::select("
                    SELECT COUNT(*) AS count
                        FROM users
                        WHERE messenger LIKE '".$params['messenger']."'
                            AND {$country}"
                );
            }
            elseif($params['access'] == "paid") {
                $db = DB::select("
                    SELECT COUNT(DISTINCT(u.id)) AS count
                        FROM users u
                        WHERE u.messenger LIKE '".$params['messenger']."'
                            AND {$country}
                            AND u.access = '1'
                            AND u.access_free = '0'"
                );
            }
            elseif($params['access'] == "free") {
                $db = DB::select("
                    SELECT COUNT(DISTINCT(u.id)) AS count
                        FROM users u
                        WHERE u.messenger LIKE '".$params['messenger']."'
                            AND {$country}
                            AND u.access = '1'
                            AND u.access_free = '1'"
                );
            }
            elseif($params['access'] == "no") {
                $db = DB::select("
                    SELECT COUNT(DISTINCT(u.id)) AS count
                        FROM users u
                        WHERE u.messenger LIKE '".$params['messenger']."'
                            AND {$country}
                            AND u.access = '0'"
                );
            }

            $count = $db[0]->count;

            if($count == 0) {
                return redirect()->to("/admin/mailing");
            }

            $task['count'] = $count;
            $task['start'] = 0;
            $task['create'] = date("Y-m-d H:i:s");
            $task['performed'] = "false";
            $task['country'] = $params['country'];
            $task['messenger'] = $params['messenger'];
            $task['access'] = $params['access'];

            file_put_contents(public_path()."/json/mailing_task.json", json_encode($task));
            file_put_contents(public_path()."/txt/log.txt", "");

            return redirect()->to("/admin/mailing");
        }
    }

    public function cancel() {
        unlink(public_path()."/json/mailing_task.json");
        return redirect()->to("/admin/mailing");
    }

    public function analize() {
        $view = view('admin.mailing.mailing-analize');
        $view->menuItem = "mailing";

        $all = 0;
        $true = 0;
        $false = 0;

        $fileLog = fopen(public_path()."/txt/log.txt", "r");
        while(!feof($fileLog)) {
            $line = fgets($fileLog);
            if($line != "") {
                $all++;
                $arrLine = explode("=>", $line);
                if(isset($arrLine[1])) {
                    $arr = json_decode($arrLine[1], true);
                    if(stripos($arrLine[0], "=") !== false) {
                        if($arr['status'] == "0") {
                            $true++;
                        }
                        else {
                            $false++;
                        }
                    }
                    elseif(isset($arr['ok'])) {
                        if($arr['ok'] == "true") {
                            $true++;
                        }
                        else {
                            $false++;
                        }
                    }
                    else {
                        if(isset($arr['error'])) {
                            $false++;
                        }
                        else {
                            $true++;
                        }
                    }
                }
            }
        }
        fclose($fileLog);

        $view->data = [
            'all' => $all,
            'true' => $true,
            'false' => $false
        ];
        return $view;
    }

    public function log() {
        if(file_exists(public_path()."/txt/log.txt")) {
            $lines = file(public_path()."/txt/log.txt");
            $log = '';
            foreach($lines as $line) {
                $line = str_replace("=>", "<i class='icon-right-small'></i>", $line);
                $line = preg_replace('|[\s]+|s', ' ', $line);
                $log .= $line."<br><br>";

            }
        }
        else {
            $log = "";
        }
        $view = view('admin.mailing.mailing-log');
        $view->log = $log;
        $view->menuItem = "mailing";
        return $view;
    }

    public function markInactiveUsers() {
        $fileLog = fopen(public_path()."/txt/log.txt", "r");
        $count = 0;
        $ids = [];
        while (!feof($fileLog)) {
            $line = fgets($fileLog);
            if($line != "") {
                $arrLine = explode("=>", $line);
                if (isset($arrLine[1])) {
                    $arr = json_decode($arrLine[1], true);

                    if(stripos($arrLine[0], "=") !== false) {
                        if($arr['status'] != "0") {
                            $count++;
                            $ids[] = trim($arrLine[0]);
                        }
                    }
                    elseif(isset($arr['ok'])) {
                        if($arr['ok'] != "true") {
                            $count++;
                            $ids[] = trim($arrLine[0]);
                        }
                    }
                }
            }
        }

        $ids = array_chunk($ids, 50000);
        foreach ($ids as $key => $id) {
            DB::table('users')->whereIn('chat', $ids[$key])->update(['active' => 0]);
        }

        return redirect()->to("/admin/mailing/analize");
    }
}
