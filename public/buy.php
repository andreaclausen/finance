<?php
    
    // configuration
    require("../includes/config.php");
    
    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
    
    // get information about the stock
    $stock = lookup($_POST["symbol"]);
    
    // only allow whole shares
    if (preg_match("/^\d+$/", $_POST["shares"]) != true)
    {
        apologize("Invalid number of shares.");
    }
    
    // only proceed if the user has enough cash for the transaction
    // get the amount of cash the user has
    $rows = query("SELECT cash FROM users
        WHERE id = ?", $_SESSION["id"]);
    
    foreach ($rows as $row)
    {
        $cash = $row["cash"];
    }
    
    // check if the user has enough cash to buy the stock
    $cost = $_POST["shares"] * $stock["price"];
    if ($cash < $cost)
    {
        apologize("You can't afford that.");
    }
    
    // add stock to portfolio
    
    // convert symbol to uppercase
    $symbol = strtoupper($_POST["symbol"]);
    
    // get the number of current shares for that stock, if any
    $rows = query("SELECT shares FROM portfolio
                        WHERE symbol = ? AND id = ?",
                        $symbol, $_SESSION["id"]);
    foreach ($rows as $row)
    {
        $shares = $row["shares"];
    }
    
    // add new shares
    query("INSERT INTO portfolio (id, symbol, shares)
        VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE shares = ?",
        $_SESSION["id"], $symbol, $_POST["shares"], $shares + $_POST["shares"]);

    // update cash
    query("UPDATE users SET cash = ? WHERE ID = ?",
        $cash - $cost, $_SESSION["id"]);
        
    // log buy in history
    query("INSERT INTO history (id, time, symbol, shares, price, transaction)
        VALUES(?, NOW(), ?, ?, ?, ?)", $_SESSION["id"], $symbol, $_POST["shares"], $stock["price"], "BUY");
    
    // redirect to index.php
    redirect("/");
    }
    
    else
    {
        render("buy.php", ["title" => "Buy"]);
    }
    
?>
