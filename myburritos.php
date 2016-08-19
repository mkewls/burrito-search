<?php

    // configuration
    require("includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST))
        {
            apologize("Sorry, but you must select a place to save.");
        }
        // create results
        else
        {
            // if form was submitted to add businesses...
            if ($_POST["function"] == "add")
            {     
                // for each business selected, query Yelp
                foreach ($_POST as $id)
                {
                    // skip "add" id from form
                    if ($id != "add")
                    {
                        $data = bizSearch($id);
                        
                        // decode biz info from the json results
                        $business = json_decode($data, true);
                        
                        // validate result
                        if(empty($business))
                        {
                            apologize("We encountered an error.");
                        }
                        else if(!empty($business["error"]))
                        {
                            apologize("We encountered an error with Yelp.");
                        }
                        else
                        {
                            // insert business info into user's burrito table
                            query("INSERT INTO burritos (id, place, street, city, state, zip, yelp_id) VALUES (?, ?, ?, ?, ? ,?, ?)",
                            $_SESSION["id"], $business["name"], $business["location"]["address"][0], $business["location"]["city"],
                            $business["location"]["state_code"], $business["location"]["postal_code"], $business["id"]);
                        }
                    }
                }
            }
            if ($_POST["function"] == "remove")
            {
                query("DELETE FROM burritos WHERE yelp_id = ?", $_POST["id"]);
            }   
        }
    }
    
   
    // query user's saved burrito places
    $rows = query("SELECT * FROM burritos WHERE id = ?", $_SESSION["id"]);
    
    // convert to burrito array
    $burritos = [];
    foreach ($rows as $row)
    {
        $burritos[] = [
            "place" => $row["place"],
            "street" => $row["street"],
            "city" => $row["city"],
            "state" => $row["state"],
            "zip" => $row["zip"],
            "id" => $row["yelp_id"]
        ];
    }
    
    // get user's name
     $namequery = query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);
        
    // convert to positions array
    $names = $namequery[0];
    $name = $names["first"];

    // render user's burritos
    render("myburritos_view.php", ["title" => "My Burritos", "name" => $name, "burritos" => $burritos]);
             
?>
