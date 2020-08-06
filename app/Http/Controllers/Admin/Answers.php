<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\models\Answer;
use Illuminate\Http\Request;

class Answers extends Controller {
    public function list() {
        $view = view('admin.answers.answers-list');
        $view->menuItem = "answerslist";
        $view->answers = Answer::all();
        return $view;
    }

    public function add() {
        $view = view('admin.answers.answers-add');
        $view->menuItem = "answersadd";
        return $view;
    }

    public function edit(Request $request) {
        $id = $request->input()['id'];
        if(empty($id)) return redirect()->to("/admin/answers/list");

        $view = view('admin.answers.answers-edit');
        $view->answer = Answer::find($id);
        $view->menuItem = "answerslist";
        return $view;
    }

    public function save(Request $request) {
        $fills = $request->input();

        if(empty($fills['question']) || empty($fills['answer'])) {
            return redirect()->to("/admin/answers/add");
        }

        unset($fills['_token']);
        $answer = new Answer();

        if(isset($fills['id'])) {
            $answer::where('id', $fills['id'])
                ->update($fills);
        }
        else {
            $answer->fill($fills);
            $answer->save();
        }

        file_put_contents(public_path("json/answers.json"), Answer::all('question', 'answer', 'method')->toJson());

        return redirect()->to("/admin/answers/list");
    }

    public function delete(Request $request) {
        $id = $request->input()['id'];
        if(empty($id)) return redirect()->to("/admin/answers/list");

        Answer::where('id', $id)->delete();

        file_put_contents(public_path("json/answers.json"), Answer::all('question', 'answer', 'method')->toJson());

        return redirect()->to("/admin/answers/list");
    }
}
