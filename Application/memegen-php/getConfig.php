<?php 

include 'config.php';

#######################
## Get Site Settings ##
#######################

    // Create an array of the site options
    $configArray = array("yourId" => $yourId, "remoteFiles" => $remoteFiles, "remoteData" => $remoteData, "siteColorBlue" => $siteColorBlue );
    
    // Encode and echo it so ajax can catch it
    echo json_encode($configArray);

?>