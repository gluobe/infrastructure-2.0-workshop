<?php 

include 'config.php';
include 'functions.php';

###################
## Generate Meme ##
###################

    // Verify input just to be sure
    if( isset($_POST['topText']) and isset($_POST['botText']) and isset($_POST['selectedMeme'])){
        // Connect to db
        ConnectDB($remoteData);
        
        // Call python app to generate meme
        $imagenametarget = generateMeme( $remoteFiles, $_POST['topText'], $_POST['botText'], $_POST['selectedMeme'] );
        
        //insert meme info in db
        InsertMemes($remoteData, $imagenametarget );
              
        //change website so it gets from db as well to show memes.
        getMemes($remoteData);
              
      
    } else {
    	  echo "Error 1: Something wrong with inputs";
    }

?>