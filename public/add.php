<?php

    // configuration
    require("../includes/config.php"); 
    
    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
    
        // make sure field is not blank
        if (empty($_POST["amount"]))
        {
            apologize("You did not enter an amount.");
        }
        
        // only allow non-negative amounts of money
        if (preg_match("/^\d+$/", $_POST["amount"]) != true)
        {
            apologize("You can only add whole numbers.");
        }
        
        // add money
        // get the amount of cash the user has
        $rows = query("SELECT cash FROM users
            WHERE id = ?", $_SESSION["id"]);
        
        foreach ($rows as $row)
        {
            $cash = $row["cash"];
        }
        
        $cash = $cash + $_POST["amount"];
        
        query("UPDATE users SET cash = ? WHERE ID = ?",
            $cash, $_SESSION["id"]);
        
        // redirect to index.php
        redirect("/");
    
    }
    
    // render page
    else
    {
        render("add.php", ["title" => "Add Money"]);
    }

?>
