<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="Ben Leynen @ Gluo"/>
		
		<title>Gluo MemeGen</title>
		
		<link rel="icon" href=site-files/images/favicon.ico type="image/x-icon"/>
		<link rel="stylesheet" href="site-files/css/bootstrap.min.css">
		<link rel="stylesheet" href="site-files/css/style.css">
		
		<script src="site-files/js/jquery-3.1.1.min.js"></script>
		<script src="site-files/js/bootstrap.min.js"></script>
		<script>
				$( document ).ready(function() {
						//////////////////////////////
						///////// GLOBAL VAR /////////
						//////////////////////////////
					
						// Declare memes dir
						var dirname = "meme-generator/memes/";
					
						function refreshImages(ajaxResult){
								// Remove all images
								$(".createdMemes").html("");

								// Sort by date (newest memes first)
								var resultSorted = $.parseJSON(ajaxResult).sort(function(obj1, obj2) { return obj1.date.S - obj2.date.S; });
								
								// Add all images
								$.each( resultSorted, function( key, value ) {
										$("<div class=\"col-md-4\"><img src=\"" + dirname + value.name.S + ".jpg\" alt=\"" + dirname + value.name.S + ".jpg\" height=\"400\"/></div>").prependTo(".createdMemes");			
								});
						}
					
					
						//////////////////////////////
						///////// GET CONFIG ///////// (trigger: visit page)
						//////////////////////////////
						$.ajax({
							type: "POST",
							url: "getConfig.php",
							success: function(result){
									// Turn into vars
									var config = jQuery.parseJSON(result);
									var remoteFiles = config.remoteFiles;
									var remoteData = config.remoteData;
									var siteColorBlue = config.siteColorBlue;
									
									// Log
									console.log( "Config file received:");
									console.log( "> remoteFiles: " + remoteFiles );
									console.log( "> remoteData: " + remoteData );
									console.log( "> siteColorBlue: " + siteColorBlue );
									
									// Change site display
									$("#remoteFiles").html("remoteFiles: " + remoteFiles);
									$("#remoteData").html("remoteData: " + remoteData);
									$("#siteColorBlue").html("siteColorBlue: " + siteColorBlue);
									
									// Change site color
									if(siteColorBlue){
											// Blue
											$(".generateButton").attr("class", "btn btn-lg btn-primary btn-block marginTop generateButton");
									} else {
											// green
											$(".generateButton").attr("class", "btn btn-lg btn-success btn-block marginTop generateButton");
									}
							},
							error: function(result){
								// Log
								console.log( "Get of config file failed: " + result );
							}
						});
						
						/////////////////////////////
						///////// GET MEMES ///////// (trigger: visit page)
						/////////////////////////////
						$.ajax({
							type: "POST",
							url: "getMemes.php",
							success: function(result){
									// Log
									console.log( "Memes gotten: " + result );
									
									// Delete and recreate images on page
									refreshImages(result);
							},
							error: function(result){
								// Log
								console.log( "Memes get failed: " + result );
								Console.log( "Check if you ran `composer` right for the PHP AWS SDK.");
							}
						});
					
						/////////////////////////////////
						///////// GENERATE MEME ///////// (trigger: press button)
						/////////////////////////////////
						$(".generateButton").click(function(){
								// Log
								console.log( "Generate button clicked." );
								
								// Set variables
								var topText = $(".generate-meme").find("#toptext").val();
								var botText = $(".generate-meme").find("#bottext").val();
								var selectedMeme = $(".generate-meme").find("#selectedMeme").val();
								
								// if variables contain something, generate the meme
								if(topText != "" && botText != ""){
										// Log
										console.log( "Meme generated: " + "( "+selectedMeme + " / "+ topText + " / " + botText + " )" );
									
										$.ajax({
											type: "POST",
											url: "generateMeme.php",
											data: {
												// Pass variables
												topText: topText,
												botText: botText,
												selectedMeme: selectedMeme
											},
											success: function(result){
												// Log
												console.log( "Memes gotten: " + result );
												
												// Delete and recreate images on page
												refreshImages(result);
												
												// Empty input fields
												$(".generate-meme").find("#toptext").val("");
												$(".generate-meme").find("#bottext").val("");
											},
											error: function(result){
												// Log
												console.log( "Meme generation failed: " + result );
											}
										});
								} else {
										// Log
										console.log( "One or more input fields are empty." );
										
										// Tell user to not be dumb
										alert("Please fill in both fields before generating a meme.");
								}
						});
						
						//////////////////////////////////////
						///////// SELECT DROPDOWNBOX ///////// (trigger: select a meme from the dropdownbox)
						//////////////////////////////////////
						$(".meme").click(function(){
								// Save selected meme in hidden input.
								var memeName = $(this).attr("name");
								$("#selectedMeme").val(memeName);
								var selected = $("#selectedMeme").val();
								console.log( "New meme selected: " + selected );
								
								// Change picture of selected meme visually.
								$("#showingImage").attr("src","meme-generator/templates/" + selected + ".jpg");
								$("#showingMeme").html(selected);
						});
						
				});
		</script>
	</head>
 	<body>
		<div class="container-fluid text-center col-md-12">
			<div class="col-md-3"></div>
  		<div class="col-md-6">
				<p><a href="http://www.gluo.be"><img src="site-files/images/gluo.png" alt="gluo logo"/></a></p>
				<h2>Meme Generator</h2>
				<form class="generate-meme" method="POST">
					<div class="col-sm-3"></div>
					<div class="col-sm-6 marginTop">
							<div class="btn-group">
								<input type="hidden" id="selectedMeme" value="successkid"/>
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<img id="showingImage" src="meme-generator/templates/successkid.jpg" height="100">
									<span id="showingMeme">successkid</span>
									<span class="glyphicon glyphicon-chevron-down"></span>
								</button>
								<ul class="dropdown-menu">
									<li>
										<a href="#" class="meme" name="aliens" title="Select aliens"><img src="meme-generator/templates/aliens.jpg" height="80">Aliens</a>
									</li>
									<li>
										<a href="#" class="meme" name="archer" title="Select archer"><img src="meme-generator/templates/archer.jpg" height="80">Archer</a>
									</li>
									<li>
										<a href="#" class="meme" name="badluckbrian" title="Select badluckbrian"><img src="meme-generator/templates/badluckbrian.jpg" height="80">Bad Luck Brian</a>
									</li>
									<li>
										<a href="#" class="meme" name="buzzlightyear" title="Select buzzlightyear"><img src="meme-generator/templates/buzzlightyear.jpg" height="80">Buzz Lightyear</a>
									</li>
									<li>
										<a href="#" class="meme" name="goodguygreg" title="Select goodguygreg"><img src="meme-generator/templates/goodguygreg.jpg" height="80">Good Guy Greg</a>
									</li>
									<li>
										<a href="#" class="meme" name="officespace" title="Select officespace"><img src="meme-generator/templates/officespace.jpg" height="80">Office Space</a>
									</li>
									<li>
										<a href="#" class="meme" name="onedoesnotsimply" title="Select onedoesnotsimply"><img src="meme-generator/templates/onedoesnotsimply.jpg" height="80">One Does Not Simply</a>
									</li>
									<li>
										<a href="#" class="meme" name="scumbagsteve" title="Select scumbagsteve"><img src="meme-generator/templates/scumbagsteve.jpg" height="80">Scumbag Steve</a>
									</li>
									<li>
										<a href="#" class="meme" name="successkid" title="Select successkid"><img src="meme-generator/templates/successkid.jpg" height="80">Success Kid</a>
									</li>
									<li>
										<a href="#" class="meme" name="willywonka" title="Select willywonka"><img src="meme-generator/templates/willywonka.jpg" height="80">Willy Wonka</a>
									</li>
									<li>
										<a href="#" class="meme" name="yodawg" title="Select yodawg"><img src="meme-generator/templates/yodawg.jpg" height="80">Yo Dawg</a>
									</li>
								</ul>
							</div>
		  				<input type="text" id="toptext" class="form-control marginTop" placeholder="Top Text" required/>
    					<input type="text" id="bottext" class="form-control" placeholder="Bottom Text" required/>
							<input class="btn btn-lg btn-success btn-block marginTop generateButton" type="button" value="Generate!"/>
						</div>
						<div class="col-sm-3"></div>
	  			</form>
					
				</div>
				<div class="col-md-3"></div>
			</div>
			<div class="container-fluid text-center">
				<div class="row"> 
					<h2>Generated Memes</h2>
					<div class="createdMemes"></div>
			</div>
			<div class="container-fluid text-center">
					<div class="row">
							<h2>Site Config</h2>
							<p id="remoteFiles">remoteFiles: N/A</p>
							<p id="remoteData">remoteData: N/A</p>
							<p id="siteColorBlue">siteColorBlue: N/A</p>
					</div>
			</div>
		</div>
	</body>
</html>
