<?php

    // configuration
    require("includes/config.php"); 

    // if not logged-in, no name
    if (empty($_SESSION["id"]))
    {
        $name = NULL;
    }    
    // but, if logged-in, get user's name
    else
    {
        $rows = query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);
        
        // convert to positions array
        $row = $rows[0];
        $name = $row["first"];
    }

    // render search
    render("search.php", ["title" => "Search", "name" => $name]);

?>
