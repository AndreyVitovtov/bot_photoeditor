<?php


namespace App\Http\Controllers\Developer;


use App\models\SettingsButtons;
use App\models\SettingsMain;
use App\models\SettingsPages;
use Illuminate\Http\Request;

class Settings {
    private function settingsToJson($arr, $file_name) {
        $res = [];
        foreach($arr as $r) {
            $res[$r['name']] = base64_decode($r['text']);
        }

        file_put_contents(public_path("json/{$file_name}.json"), json_encode($res));
    }

    public function index() {
        return redirect()->to("developer/settings/main");
    }

    public function settingsMain() {
        $view = view('developer.settings.settings-main');
        $view->menuItem = "settingsmain";
        $settingsMain = new SettingsMain();
        $view->fields = $settingsMain->all();
        return $view;
    }

    public function settingsPages() {
        $view = view('developer.settings.settings-pages');
        $view->menuItem = "settingspages";
        $view->fields = SettingsPages::all();
        return $view;
    }

    public function settingsButtons() {
        $view = view('developer.settings.settings-buttons');
        $view->menuItem = "settingsbuttons";
        $view->fields = SettingsButtons::all();
        return $view;
    }

    public function settingsMainAdd(Request $request) {
        $fills = $request->input();
        unset($fills['_token']);

        $settingsMain = new SettingsMain();
        $settingsMain->fill($fills);
        $settingsMain->save();

        $main = SettingsMain::all('prefix', 'value');
        foreach($main as $r) {
            $res[$r['prefix']] = $r['value'];
        }
        file_put_contents(public_path("json/main.json"), json_encode($res));

        return redirect()->to("developer/settings/main");
    }

    public function settingsMainDelete(Request $request) {
        $id = $request->input()['id'];

        if(empty($id)) return redirect()->to("developer/settings/main");

        $settingsMain = new SettingsMain();
        $settingsMain::where('id', $id)->delete();

        $main = SettingsMain::all('prefix', 'value');
        $res = [];
        foreach($main as $r) {
            $res[$r['prefix']] = $r['value'];
        }
        file_put_contents(public_path("json/main.json"), json_encode($res));

        return redirect()->to("developer/settings/main");
    }

    public function settingsPagesAdd(Request $request) {
        $fills = $request->input();
        $text = base64_encode($fills['text']);
        unset($fills['_token']);
        unset($fills['text']);

        $settingsPages = new SettingsPages();
        $settingsPages->fill($fills);
        $settingsPages->text = $text;
        $settingsPages->save();

        $pages = SettingsPages::all('name', 'text');
        $this->settingsToJson($pages, "pages");

        return redirect()->to("developer/settings/pages");
    }

    public function settingsPagesDelete(Request $request) {
        $id = $request->input()['id'];

        if(empty($id)) return redirect()->to("developer/settings/pages");

        $settingsPages = new SettingsPages();
        $settingsPages::where('id', $id)->delete();

        $pages = SettingsPages::all('name', 'text');
        $this->settingsToJson($pages, "pages");

        return redirect()->to("developer/settings/pages");
    }

    public function settingsButtonsAdd(Request $request) {
        $fills = $request->input();
        $text = base64_encode($fills['text']);
        unset($fills['_token']);
        unset($fills['text']);

        $settingsPages = new SettingsButtons();
        $settingsPages->fill($fills);
        $settingsPages->text = $text;
        $settingsPages->save();

        $buttons = SettingsButtons::all('name', 'text');
        $this->settingsToJson($buttons, "buttons");

        return redirect()->to("developer/settings/buttons");
    }

    public function settingsButtonsDelete(Request $request) {
        $id = $request->input()['id'];

        if(empty($id)) return redirect()->to("developer/settings/buttons");

        $settingsPages = new SettingsButtons();
        $settingsPages::where('id', $id)->delete();

        $buttons = SettingsButtons::all('name', 'text');
        $this->settingsToJson($buttons, "buttons");

        return redirect()->to("developer/settings/buttons");
    }

    public function settingsMainEdit(Request $request) {
        $id = $request->post('id');
        $settingsMain = new SettingsMain();
        $res = $settingsMain->where('id', $id)->get();
        $view = view("developer.settings.edit-main");
        $view->id = $res[0]['id'];
        $view->name = $res[0]['name'];
        $view->name_us = $res[0]['name_us'];
        $view->prefix = $res[0]['prefix'];
        $view->value = $res[0]['value'];
        $view->type = $res[0]['type'];
        $view->menuItem = "settingsmain";
        return $view;
    }

    public function settingsMainSave(Request $request) {
        $id = $request->post('id');
        $name = $request->post('name');
        $name_us = $request->post('name_us');
        $prefix = $request->post('prefix');
        $value = $request->post('value');
        $type = $request->post('type');

        $settingsMain = SettingsMain::find($id);
        $settingsMain->name = $name;
        $settingsMain->name_us = $name_us;
        $settingsMain->prefix = $prefix;
        $settingsMain->value = $value;
        $settingsMain->type = $type;
        $settingsMain->save();

        $main = SettingsMain::all('prefix', 'value');
        foreach($main as $r) {
            $res[$r['prefix']] = $r['value'];
        }
        file_put_contents(public_path("json/main.json"), json_encode($res));

        return redirect()->to('/developer/settings/main');
    }

    public function settingsPagesEdit(Request $request) {
        $id = $request->post('id');
        $settingsPages = SettingsPages::find($id);
        $view = view('developer.settings.edit-pages');
        $view->id = $settingsPages['id'];
        $view->name = $settingsPages['name'];
        $view->text = base64_decode($settingsPages['text']);
        $view->description = $settingsPages['description'];
        $view->description_us = $settingsPages['description_us'];
        $view->menuItem = "settingspages";
        return $view;
    }

    public function settingsPagesSave(Request $request) {
        $id = $request->post('id');
        $name = $request->post('name');
        $text = $request->post('text');
        $description = $request->post('description');
        $description_us = $request->post('description_us');
        $settingsPages = SettingsPages::find($id);
        $settingsPages->name = $name;
        $settingsPages->text = base64_encode($text);
        $settingsPages->description = $description;
        $settingsPages->description_us = $description_us;
        $settingsPages->save();

        $pages = SettingsPages::all('name', 'text');
        $this->settingsToJson($pages, "pages");

        return redirect()->to('/developer/settings/pages');
    }

    public function settingsButtonsEdit(Request $request) {
        $id = $request->post('id');
        $settingsButtons = SettingsButtons::find($id);
        $view = view('developer.settings.edit-buttons');
        $view->id = $settingsButtons['id'];
        $view->name = $settingsButtons['name'];
        $view->text = base64_decode($settingsButtons['text']);
        $view->menu = $settingsButtons['menu'];
        $view->menu_us = $settingsButtons['menu_us'];
        $view->menuItem = "settingsbuttons";
        return $view;
    }

    public function settingsButtonsSave(Request $request) {
        $id = $request->post('id');
        $name = $request->post('name');
        $text = $request->post('text');
        $menu = $request->post('menu');
        $menu_us = $request->post('menu_us');
        $settingsButtons = SettingsButtons::find($id);
        $settingsButtons->name = $name;
        $settingsButtons->text = base64_encode($text);
        $settingsButtons->menu = $menu;
        $settingsButtons->menu_us = $menu_us;
        $settingsButtons->save();

        $buttons = SettingsButtons::all('name', 'text');
        $this->settingsToJson($buttons, "buttons");

        return redirect()->to('/developer/settings/buttons');
    }
}
