<?php

namespace App\Http\Controllers;

use App\models\BotUsers;
use App\models\buttons\Buttons;
use App\models\buttons\ButtonsTelegram;
use App\models\buttons\ButtonsViber;
use App\models\Curl;
use App\models\Interaction;
use App\models\Khatma;
use App\models\Mailing;
use App\models\Page;
use App\models\PageSent;
use App\models\Reminder;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Send extends Controller {

    public function mailing() {
       $mailing = new Mailing();
       $mailing->send();
    }
}
