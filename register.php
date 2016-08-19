<?php

    // configuration
    require("includes/config.php");

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["username"]))
        {
            apologize("You must provide your username.");
        }
        else if (empty($_POST["password"]))
        {
            apologize("You must provide your password.");
        }
        else if ($_POST["password"] != $_POST["confirmation"])
        {
            apologize("Your passwords do not match.");
        }
        else if (empty($_POST["firstname"]))
        {
            apologize("Please provide your name.");
        }
        else if (empty($_POST["lastname"]))
        {
            apologize("Please provide your last name.");
        }
        
        if (query("INSERT INTO users (username, hash, first, last) VALUES(?, ?, ?, ?)", 
            $_POST["username"], crypt($_POST["password"]), $_POST["firstname"], $_POST["lastname"]) !== false)
        { 
            // find id and assign session id
            $rows = query("SELECT LAST_INSERT_ID() AS id");
            $id = $rows[0]["id"];
            $_SESSION["id"] = $id;
            
            // redirect to index.php
            redirect("index.php");
        }
        else if (query("INSERT INTO users (username, hash, first, last) VALUES(?, ?, ?, ?)", 
            $_POST["username"], crypt($_POST["password"]), $_POST["firstname"], $_POST["lastname"]) === false)
        {
            apologize("Username already taken.");
        }
    }
    else
    {
        // else render form
        render("register_form.php", ["title" => "Register"]);
    }

?>
