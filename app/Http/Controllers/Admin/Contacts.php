<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\models\ContactsModel;
use App\models\ContactsType;
use App\models\Message;
use Illuminate\Http\Request;

class Contacts extends Controller {
    public function general() {
        $contactsType = ContactsType::where('type', 'general')->first();
        $view = view('admin.contacts.contacts-list');
        $view->contacts = ContactsModel::where('contacts_type_id', $contactsType->id)->paginate(15);
        $view->type = 'general';
        $view->menuItem = 'contactsgeneral';
        return $view;
    }

    public function access() {
        $contactsType = ContactsType::where('type', 'access')->first();
        $view = view('admin.contacts.contacts-list');
        $view->contacts = ContactsModel::where('contacts_type_id', $contactsType->id)->paginate(15);
        $view->type = 'access';
        $view->menuItem = 'contactsaccess';
        return $view;
    }

    public function advertising() {
        $contactsType = ContactsType::where('type', 'advertising')->first();
        $view = view('admin.contacts.contacts-list');
        $view->contacts = ContactsModel::where('contacts_type_id', $contactsType->id)->paginate(15);
        $view->type = "advertising";
        $view->menuItem = 'contactsadvertising';
        return $view;
    }

    public function offers() {
        $contactsType = ContactsType::where('type', 'offers')->first();
        $view = view('admin.contacts.contacts-list');
        $view->contacts = ContactsModel::where('contacts_type_id', $contactsType->id)->paginate(15);
        $view->type = 'offers';
        $view->menuItem = 'contactsoffers';
        return $view;
    }

    public function answer(Request $request) {
        $view = view('admin.contacts.contacts-answer');
        $contact = ContactsModel::find($request->post('id'));
        $view->contact = $contact;
        $view->menuItem = 'contacts'.$contact->type->type;
        return $view;
    }

    public function answerSend(Request $request) {
        $message = new Message();
        $res = $message->send($request->post('messenger'), $request->post('chat'), $request->post('text'));
        return redirect(route('contacts-'.$request->post('type')));
    }

    public function delete(Request $request) {
        $contact = ContactsModel::find($request->post('id'));
        $type = $contact->type;
        $contact->delete();
        return redirect(route('contacts-'.$type->type));
    }

    public function deleteCheck(Request $request) {
        ContactsModel::whereIn('id', json_decode($request->post('data'), true))->delete();
        return redirect(route('contacts-'.$request->post('type')));
    }
}
