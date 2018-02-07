# Application README #
### Application visual representation ###

![](../../Images/PHPApplicationSchema.png?raw=true)

### What is? ###
* This application folder houses the Gluo PHP Meme Generator.

### Where is what? ###
#### Folders ####
* `meme-generator` houses the python app to create memes through the bash commandline. It is used in the backend to create memes by the PHP application's web interface. It also contains the meme templates and created memes in `meme-generator/templates` and `meme-generator/memes` respectively.
* `site-files` houses the website's files. Bootstrap framework, images, css, fonts, jquery and js.

#### Files ####
* `config.php` is the file where a student can change the save locations for meme files and meme data and change the site color.
* `functions.php` contains all used functions by the other smaller php files in this dir. It contains the most logic and is the proxy to access the database or filesystem.
* `index.php` is the site itself and contains jquery and ajax logic to update the site dynamically.

