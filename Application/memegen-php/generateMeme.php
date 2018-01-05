<?php 

include 'functions.php';

###################
## Generate Meme ##
###################

    // Verify input just to be sure
    if( isset($_POST['topText']) and isset($_POST['botText']) and isset($_POST['selectedMeme'])){
        // Connect to db
        ConnectDB();
        
        // Call python app to generate meme
        $imagenametarget = generateMeme( $_POST['topText'], $_POST['botText'], $_POST['selectedMeme'] );
        
        //insert meme info in db
        InsertMemes( $imagenametarget );
        
        //change website so it gets from db as well to show memes.
        getMemes();
              
      
    } else {
    	  echo "Error 1: Something wrong with inputs";
    }

?>