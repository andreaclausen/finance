<?php

    // configuration
    require("../includes/config.php"); 
    
    // get portfolio information
    $rows = query("SELECT * FROM history
        WHERE id = ?", $_SESSION["id"]);
    
    $positions = [];
    foreach ($rows as $row)
    { 
        $positions[] = [
            "transaction" => $row["transaction"],
            "time" => $row["time"],
            "symbol" => $row["symbol"],
            "shares" => $row["shares"],
            "price" => number_format($row["price"], 2)
        ];
    }
  
    // render history
    render("history.php", ["positions" => $positions, "title" => "History"]);

?>
