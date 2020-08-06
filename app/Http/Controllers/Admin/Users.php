<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\UserDepositRequest;
use App\models\Bonus;
use App\models\BotUsers;
use App\models\Message;
use App\models\PaymentCreateChat;
use App\models\PaymentMailingChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Users extends Controller {
    public function index() {
        $view = view('admin.users.users');
        $view->menuItem = "users";
        $view->users = BotUsers::paginate(15);
        return $view;
    }

    public function profile($id) {
        $user = new BotUsers;

        $profile = $user->find($id);
        if(empty($profile)) {
            return redirect()->to("404");
        }
        $view = view('admin.users.user-profile');
        $view->profile = $profile;
        $view->menuItem = "users";
        return $view;
    }

    public function createUrlSearch(Request $request) {
        $params = $request->input();
        if(empty($params['str'])) {
            return redirect()->to("/admin/users");
        }
        return redirect()->to("/admin/users/search/{$params['str']}");
    }

    public function search($str) {
        $BotUsers = new BotUsers();
        $users = $BotUsers->where('chat', 'LIKE', "%$str%")->orwhere('username', 'LIKE', "%$str%")->paginate(15);
        $view = view('admin.users.users');
        $view->users = $users;
        $view->str = $str;
        $view->menuItem = "users";
        return $view;
    }

    public function access(Request $request) {
        $user = BotUsers::find($request->post('id'));
        $access = $request->post('access');

        if($access == 'on') {
            $user->access = '1';
        }
        else {
            $user->access = '0';
        }
        $user->save();

        $message = new Message();
        if($access == 'on') {
            $message->send($user->messenger, $user->chat, "{full_access_granted}");
        }
        else {
            $message->send($user->messenger, $user->chat, "{full_access_canceled}");
        }

        return redirect()->to("/admin/users/profile/".$request->post('id'));
    }

    public function countChat(Request $request) {
        $paymentCreateChat = new PaymentCreateChat();
        $paymentCreateChat->users_id = $request->post('user_id');
        $paymentCreateChat->amount = '0';
        $paymentCreateChat->count = $request->post('count');
        $paymentCreateChat->date = date('Y-m-d');
        $paymentCreateChat->time = date('H:i:s');
        $paymentCreateChat->save();

        $user = BotUsers::find($request->post('user_id'));
        $message = new Message();
        $message->send($user->messenger, $user->chat, '{added_the_ability_to_create_chats}', [
            'count' => $request->post('count')
        ]);

        return redirect()->to(url('admin/users/profile/'.$request->post('user_id')));
    }

    public function countMailing(Request $request) {
        $paymentMailingChat = new PaymentMailingChat();
        $paymentMailingChat->chats_id = $request->post('chat');
        $paymentMailingChat->type = 'free';
        $paymentMailingChat->amount = '0';
        $paymentMailingChat->count = $request->post('count');
        $paymentMailingChat->count = $request->post('count');
        $paymentMailingChat->date = date('Y-m-d');
        $paymentMailingChat->time = date('H:i:s');
        $paymentMailingChat->save();

        $user = BotUsers::find($request->post('user_id'));
        $message = new Message();
        $message->send($user->messenger, $user->chat, '{added_the_ability_to_make_additional_mailings}', [
            'count' => $request->post('count')
        ]);

        return redirect()->to(url('admin/users/profile/'.$request->post('user_id')));
    }

    public function sendMessage(Request $request) {
        $user = BotUsers::find($request->post('id'));
        $message = new Message();
        $message->send($user->messenger, $user->chat, $request->post('message'));

        return redirect()->to(url('admin/users/profile/'.$request->post('id')));
    }

}
