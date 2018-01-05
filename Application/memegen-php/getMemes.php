<?php 

include 'functions.php';

###############
## Get Memes ##
###############

    // Connect to db
    ConnectDB();
    
    // Get all memes, this function echo's the data so ajax can catch it.
    getMemes();
    
?>