<?php


namespace App\models;

class Visit {
    public function exists(string $date, int $id): ? array {
        $visitsJson = file_get_contents(public_path("json/visits.json"));
        if($visitsJson == "") return [];
        $visitsArray = json_decode($visitsJson, true);
        if(! isset($visitsArray[$date])) {
            $visitsArray[$date] = [];
            return $visitsArray;
        }
        if(array_search($id, $visitsArray[$date]) === false) return $visitsArray;

        return null;
    }

    public function save(array $visitsArray):void {
        $visitsJson = json_encode($visitsArray);
        file_put_contents(public_path("json/visits.json"), $visitsJson);
    }

    public function add(string $date, int $id) {
        $visitsArray = $this->exists($date, $id);
        if(is_array($visitsArray)) {
            $visitsArray[$date][] = $id;
            $this->save($visitsArray);
        }
    }
}

