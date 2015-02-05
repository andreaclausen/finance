<?php

    // configuration
    require("../includes/config.php"); 
    
    // get portfolio information
    $rows = query("SELECT symbol, shares FROM portfolio
        WHERE id = ?", $_SESSION["id"]);
    
    $positions = [];
    foreach ($rows as $row)
    {
        $stock = lookup($row["symbol"]);
        if ($stock !== false)
        {
            $positions[] = [
                "name" => $stock["name"],
                "price" => $stock["price"],
                "shares" => $row["shares"],
                "symbol" => $row["symbol"],
                "total" => number_format($stock["price"] * $row["shares"], 2)
            ];
        }
    }
    
    // get cash information
    $rows = query("SELECT cash FROM users
        WHERE id = ?", $_SESSION["id"]);
    
    foreach ($rows as $row)
    {
        $cash = number_format($row["cash"], 2);
    }
    
    // render portfolio
    render("portfolio.php", ["positions" => $positions, "cash" => $cash, "title" => "Portfolio"]);

?>
