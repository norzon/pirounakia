<?php
    $app->post("/store", function($request, $response){
        global $db, $baseurl, $errors, $response_body, $response_url;

        if ($_SESSION["admin"] === false) {
            throw new Exception("Not allowed");
        }
        
        // Load post data
        $data = $request->getParsedBody();
        
        // Get the email and the password
        $day = dataCheck($data["day"], "No day given", "empty");
        $open = dataCheck($data["open"], "No open time given", "empty");
        $close = dataCheck($data["close"], "No close time given", "empty");
        $tables = dataCheck($data["tables"], "No tables given", "empty");
        $day = strtoupper($day);
        
        // Check if this exists
        $db->prepareGetStoreDay();
        $store_day = $db->getStoreDay($day);
        
        // Check that the day exists
        if (!empty($store_day)) {
            $db->prepareUpdateStoreDay(["day", "open_time", "close_time", "tables"]);
            $db->updateStoreDay($store_day->id, [
                "day" => $day,
                "open_time" => $open,
                "close_time" => $close,
                "tables" => $tables
            ]);
        } else {
            $db->prepareInsertStoreDay(["day", "open_time", "close_time", "tables"]);
            $db->insertStoreDay([
                "day" => $day,
                "open_time" => $open,
                "close_time" => $close,
                "tables" => $tables
            ]);
        }

        $response_body = json_encode(array(
            "success" => true,
            "description" => "Successfully saved store day"
        ));
        
        $response_url = "/";
        
        return $response;
    })->setName('post.store');
?>