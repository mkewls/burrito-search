<?php

    // configuration
    require("includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["location"]) && $_POST["geolocation"] == "false")
        {
            apologize("You must provide a location.");
        }
        
        // if valid submission via location input
        if ($_POST["location"] != "false")
        {    
            
            $location = $_POST["location"];
            // query Yelp API
            $data = search($_POST["location"]);
            
            // if no results
            if (empty($data))
            {
                apologize("Sorry, something went wrong.");
            }
            
            // decode results
            $results = json_decode($data, true);
            
            // if not logged-in, no name
            if (empty($_SESSION["id"]))
            {
                $name = null;                
            } 
            // else, get name
            else
            {
                $rows = query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);
                
                // convert to user array
                $row = $rows[0];
                $name = $row["first"];
            }
            
            // render results page
            render("search_results.php", ["title" => "Results", "name" => $name, "results" => $results, "location" => $location]);
        }

        // if geolocation clicked
        if ($_POST["geolocation"] != "false")
        {
            $location = $_POST["lat"] . ", " . $_POST["lng"];
            
            // query Yelp API via Geolocation
            $data = geoSearch($_POST["lat"], $_POST["lng"]);
            
            // if no results
            if (empty($data))
            {
                apologize("Sorry, something went wrong.");
            }
            
            // else decode results
            $results = json_decode($data, true);
            
            // if not logged-in, no name
            if (empty($_SESSION["id"]))
            {
                $name = null;
            } 
            // else, no name
            else
            {
                $rows = query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);
                
                // convert to user array
                $row = $rows[0];
                $name = $row["first"];
            }
            
            // render results page
            render("search_results.php", ["title" => "Results", "name" => $name, "results" => $results, "location" => $location]);
        }
    }

    // if form not submitted, back to search
    else
    {
        // if not logged-in, no name
        if ($_SESSION["id"])
        {
            $name = null;
        } 
        // else, get name
        else
        {
            $rows = query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);
            
            // convert to user array
            $row = $rows[0];
            $name = $row["first"];
        }
        
        // render search
        render("search.php", ["title" => "Search", "name" => $name]);
    }

?>
