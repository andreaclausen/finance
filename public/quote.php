<?php

    // configuration
    require("../includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // retrieve stock quote
        // validate submission
        if (empty($_POST["symbol"]))
        {
            apologize("You must provide a stock symbol.");
        }
        
        // look up the stock symbol
        $stock = lookup($_POST["symbol"]);
        
        // if the stock doesn exist, apologize
        if($stock === false)
        {
        apologize("Please enter a valid stock symbol.");
        }
        
        // display the stock price
        render("quote.php", ["title" => $stock["symbol"], "stock" => $stock]);
        // redirect to quote.php and display price 2-4 decimals places (number_format)
    }
    
    else
    {
        // else render form
        render("quote_form.php", ["title" => "Quote Lookup"]);
    }
?>
