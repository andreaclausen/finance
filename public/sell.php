<?php
    
    // configuration
    require("../includes/config.php");
    
    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    { 
    // remove stock from portfolio
    
    // take note of how many shares the user owns and store value in $shares
    $rows = query("SELECT shares FROM portfolio
        WHERE id = ? AND symbol = ?", $_SESSION["id"], $_POST["symbol"]);
    
    foreach ($rows as $row)
    {
        $shares = $row["shares"];
    }
        
    // remove stock from portfolio    
    query("DELETE FROM portfolio
        WHERE id = ? AND symbol = ?", $_SESSION["id"], $_POST["symbol"]);

    // update cash
    
    // get current amount of cash and store it in $cash
    $rows = query("SELECT cash FROM users
        WHERE id = ?", $_SESSION["id"]);
    
    foreach ($rows as $row)
    {
        $cash = $row["cash"];
    }
    
    // get value of the sale and store it in $sale
    
    // get information about the stock
    $stock = lookup($_POST["symbol"]);
    
    // calculate value of the sale
    $sale = $stock["price"] * $shares;
    
    // update cash
    query("UPDATE users SET cash = ? WHERE ID = ?",
        $cash + $sale, $_SESSION["id"]);
    
    // log sell in history
    query("INSERT INTO history (id, time, symbol, shares, price, transaction)
        VALUES(?, NOW(), ?, ?, ?, ?)", $_SESSION["id"], $_POST["symbol"], $shares, $stock["price"], "SELL");
    
    // redirect to index.php
    redirect("/");
    
    }
    
    else
    {
        // else render form
        $rows = query("SELECT symbol FROM portfolio
            WHERE id = ?", $_SESSION["id"]);
        
        $portfolio = [];
        foreach ($rows as $row)
        {
            $portfolio[] = [
                "symbol" => $row["symbol"],
            ];
        }
        
        render("sell.php", ["portfolio" => $portfolio, "title" => "Sell"]);
    }
    
?>
