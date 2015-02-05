<?php

    // configuration
    require("../includes/config.php");

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // make sure password and confirmation aren't blank
        if (empty($_POST["password"]))
        {
            apologize("You must provide a password.");
        }
        else if (empty($_POST["confirmation"]))
        {
            apologize("You must provide a confirmation.");
        }
  
        // make sure password and confirmation are the same
        if ($_POST["password"] !== $_POST["confirmation"])
        {
            apologize("Password and confirmation do not match!");
        }
            
        // make sure username isn't blank
        if (empty($_POST["username"]))
        {
            apologize("You must provide a username.");
        }
            
        // add user to database
        $result = query("INSERT INTO users (username, hash, cash)
                            VALUES (?, ?, 10000.000)", $_POST["username"],
                            crypt($_POST["password"]));
        
        // reject registration if username already exists
        if($result === false)
        {
        apologize("That username already exsists.");
        }
        
        // log the user in
        // find out their id
        $rows = query("SELECT LAST_INSERT_ID() as id");
        $id = $rows[0]["id"];
        
        // store their session id in
        $_SESSION["id"] = $id;
        
        // redirect to index.php
        redirect("/");
        
    }
    else
    {
        // else render form
        render("register_form.php", ["title" => "Register"]);
    }

?>
