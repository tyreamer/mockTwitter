<?php

require_once("./include/membersite_config.php");

if (!$fgmembersite->CheckLogin()) {
    $fgmembersite->RedirectToURL("index.php");
    exit();
}

?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
    <link rel="icon" href="favicon.ico">
	<title>Leyff | Create Post</title>	
	
<link href="bootstrap-3.3.6-dist/css/fileinput.css" media="all" rel="stylesheet" type="text/css">
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">  
  <link rel="stylesheet" href="icomoon/style.css">
   <script src="bootstrap-3.3.6-dist/js/fileinput.js" type="text/javascript"></script>
	<script src="bootstrap-3.3.6-dist/js/fileinput_locale_fr.js" type="text/javascript"></script>
	<script src="bootstrap-3.3.6-dist/js/fileinput_locale_es.js" type="text/javascript"></script>
		
		<script>
		$(document).ready(function() {
			var text_max = 25;
			$('#textarea_feedback').html(text_max + ' characters remaining');

			$('#postText').keyup(function() {
				var text_length = $('#postText').val().length;
				var text_remaining = text_max - text_length;

				$('#textarea_feedback').html(text_remaining + ' characters remaining');
			});
		});
		</script>
		
		<style>
		body {
			background-color: #fff;
		}
			.error {
				color: #990000;
			}
            
            /* Thumbnails */
            
            .postImageLabel {
                cursor: pointer;
                -webkit-appearance: button;
                -moz-appearance: button;
                -o-appearance: button;
                -ms-appearance: button;
                appearance: button;
				display: inline !important;			
            }
			
            .postImageRadio {
                display: none;
            }
            
            .postImageThumbnail {
                width: 100%;
				display: inline !important;		
            }
            
            .postImageSelected {
                outline: 5px solid #C6E48B;
            }
			
			#postImageSelection {
				max-height: 300px;
				overflow-x: auto;
			}
			
			    .nav li ul
		{
			display: none;			
		}

	.nav li:hover ul
		{
			display: block;
			position: absolute;
			padding: 10px;
			background-color: #fff;
			list-style-type: none;
			text-align: center;
		}
		
	.nav li ul li {					
			padding: 5px 15px 5px 15px;
	}
		
	ul li ul li:hover
	{
		background-color: rgba(0,0,0,.2);
	}
	 .nav {
      font-size: 18px;
    }
	
	.nav .btn-default {
		height:34px;
	}
	
	  div.circle-avatar{
		/* make it responsive */
		max-width: 100%;
		width:100%;
		height:auto;
		display:block;
		/* div height to be the same as width*/
		padding-top:100%;

		/* make it a cirkle */
		border-radius:50%;

		/* Centering on image`s center*/
		background-position-y: center;
		background-position-x: center;
		background-repeat: no-repeat;

		/* it makes the clue thing, takes smaller dimention to fill div */
		background-size: cover;

		/* it is optional, for making this div centered in parent*/
		margin: 0 auto;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
	}
			
		</style>
</head>

 <nav class="navbar navbar-default navbar-fixed-top"> 
	   <div class="container-fluid">
		  <!-- Brand and toggle get grouped for better mobile display -->
		  <div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="true">
			  <span class="sr-only">Toggle navigation</span>
			  <span class="icon-bar"></span>
			  <span class="icon-bar"></span>
			  <span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="home.php"><img src="./images/LeyffLogo.jpg" style="height: 50px; margin-top: -15px;"></a>
		  </div>

		  <!-- Collect the nav links, forms, and other content for toggling -->
		  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav navbar-left">
			  <form class="navbar-form form-horizontal" method="GET" action="search.php" role="search">
				<div class="input-group" style="position: fixed; left: 50%; width: 300px; margin-left: -150px;">
				  <input type="text" class="form-control" name="qry" placeholder="Search" id="searchText" type="text">
				  <span class="input-group-btn">
					<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
				</div>
			  </form>
			</ul>

			<ul class="nav navbar-nav navbar-right">
			  <li><a href= "profile.php"><div class="circle-avatar" style= "width: 24px; height: 24px; border-radius: 12px; background-image: url(<?php echo $fgmembersite->GetProfilePicture(); ?>)"> </div></a> 
				<ul>
					<a href="profile.php"><li><h5> View Profile </h5> </li></a>	
					<a href="editProfile.php"> <li><h5> Edit Profile </h5> </li></a>
					<a href="logout.php"><li><h5> Logout </h5></li></a>
				</ul>
			  </li>
			  <li><a href="createPost.php"><span class="glyphicon glyphicon-leaf"></span></a></li>
			  <li><a href="notifications.php"><span class="glyphicon glyphicon-globe" 
							<?php if ($notificationCount > 0) 
									{echo 'style="color: lightblue;"';
								  } 
							?>></span></a></li>
			  <li><a href="messages.php"><span class="glyphicon glyphicon-envelope"
							<?php if ($messageCount > 0) 
									{echo 'style="color: lightblue;"';
								  } 
							?>></span></a></li>
			</ul>
		  </div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>
 <hr style="border: solid 1px #e0e0e0;"/>

<body>
	
	<div id="signup">
		<div class="main">
			<div class="container" style="height: 800px; padding: 50px;" >
			    <div class = "col-md-4">				
			    </div>
				<div class="col-md-4" style = "background-color: lightblue; border-color: #fff; border-style: solid; border-radius: 20px;">
					<div class="col-md-12">
							<div style="width: 100%; text-align: center;">
								<h3>New Post</h3>				
							</div>		
							<hr/>
							<div class="login" style = "text-align: center; ">
								<form action="submitPost.php" method = "POST" name="form" id="form" enctype = "multipart/form-data">	
                                    <label class="control-label"></label>
                                    <p style="margin-bottom: 0px;"> What are you sharing? <input type="text" name= "postText" id = "postText" size="25" maxlength = "25" required>  </input> </p>
                                     <div id="text-error" class="error"></div>
									  <div id="textarea_feedback"></div>
                                    <br/>
                                    <p>Provide a Link: <input type="text" name= "postLink" id = "postLink" style= "width: 80%;" required>  </input> </p>
                                     <div id="link-error" class="error"></div>                                   
                                    <br/>
									<p>Add a custom thumbnail? </p> 									
									<input type = "file" class="file" id= "customImage" name = "customImage" accept="image/png, image/gif, image/jpeg" />    
									<br/>
                                     <div id = "postImageSelection"> </div>        
									 <img src="images/loading.gif" id="gif" style="margin: 0 auto; display:none; visibility: hidden;">									
                                    <input type="submit" name="submit" class = "btn btn-large" id="submit" value="Submit" style = "background-color: #C6E48B; margin-top: 2%;width: 100%;"></p>
								</form>
							</div> 
					</div>
				</div>
				<div class = "col-md-4">
		        </div>
			</div>
		</div>		    
			
	</div>	

	
  <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	
	<script>
		$("#navbar").load("navbar.html");
        
        var queryImage = function(e) {
            
			// TODO the keyup event might put too much stress on the server
            if (validateLink($("#form #postLink")[0]).length > 0) {
                // Invalid link
                return;
            }
            
            // If a custom image is selected, don't grab images
            if ($("#customImage").val() != "") {
				
				return;
            }
							
            $.post("submitPost.php",
                { 'queryLink': $("#postLink").val() },
                function(data, status) {					  
                    if (data.data) {
                        // Get the possible images
                        var images = data.data;
                        if (images.length == 0) {                            
							// Display all possible images
                            $("#postImageSelection").empty();
							 $("#postImageSelection").html("<br/><p> Or, use the default image: </p> <label class='postImageIndex postImageSelected'><input class='postImageRadio' name = 'postImageIndex' type='radio' checked='true' value='0'/><img class='postImageThumbnail' src='images/LinkDefaultImg.jpg'> </img></label>");
                        }
                        else {
                            // Display all possible images
                            $("#postImageSelection").empty();
							 $("#postImageSelection").html("<p> Or, select an image to display: </p>");
                            images.forEach(function(img, i) {
                                var label = $(document.createElement("label")).attr({
                                    "class": "postImageLabel" + ((i==0) ? " postImageSelected" : "")
                                });
                                var image = $(document.createElement("img")).attr({
                                    "class": "postImageThumbnail",
                                    "src": img
                                });
                                var radio = $(document.createElement("input")).attr({
                                    "name": "postImageIndex",
                                    "class": "postImageRadio",
                                    "type": "radio",
                                    "value": i,
                                    "checked": (i==0) // First one is selected
                                }).on("change", function() {
                                    $('.postImageRadio').not(this).closest('label').removeClass('postImageSelected');
                                    $(this).closest('label').addClass('postImageSelected');
                                });
                                label.append(radio);
                                label.append(image);
                                $("#postImageSelection").append(label);
                            });
                        }
                    }
                    else {
                        // Display the error message
                        console.log(data.message);
						 $("#submit").prop("disabled",false);
                    }
                }, "json");
            
            return false;
        };
			        
        $("#postLink").on("change keyup paste", queryImage);
        
        $("#form").on("submit", function(e) {
			$("#submit").prop("disabled",true);
			e.preventDefault();
            reason = "";
			reason += validateText($("#form #postText")[0]);
			reason += validateLink($("#form #postLink")[0]);
            
            if (reason.length > 0) {
                return false;
            }
			
            $('#gif').css('visibility', 'visible');
			$('#gif').css('display', 'block');
			 $(this).find('input[type=submit]').prop('disabled', true);
			
            var data = new FormData($('#form')[0]);
            $.ajax({
                "url": "submitPost.php",
                "method": "POST",
                "contentType": false,
                "data": data,
                "success": function(data, status) {
                    if (data.success) {
                        // Redirect to home.php
                        window.location.href = "home.php";
                    }
                    else {
                        // Display the error message
                        console.log(data.message);
                    }
                },
                "processData": false,
                "dataType": "json"
            });
            
            return false;
        });
		
		 $("#clearCustomImage").on("click", function(e) {
            // Clear upload
            $("#customImage").val("");
            // Invoke images from the link
            queryImage();
            e.preventDefault();
            return false;
        });
	
		
		function validateText(text) {
			var error = "";
			var textTrimmed = text.value.trim(); // value of field with whitespace trimmed off

			if (text.value == "") {
				text.style.background = 'Red';
				document.getElementById('text-error').innerHTML = "Please enter a title.";
				var error = "2";
			}else {
				text.style.background = 'White';
				document.getElementById('text-error').innerHTML = '';
			}
			return error;
		}
		
		function validateLink(link) {
			var error = "";
			var linkTrimmed = link.value.trim(); // value of field with whitespace trimmed off
			var linkFilter = /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;

			if (link.value == "") {
				link.style.background = 'Red';
				document.getElementById('link-error').innerHTML = "Please enter a link.";
				var error = "2";
			} else if (!linkFilter.test(linkTrimmed)) { //test for format
				link.style.background = 'Red';
				document.getElementById('link-error').innerHTML = "Please enter a valid URL.";
				var error = "3";
			} else {
				link.style.background = 'White';
				document.getElementById('link-error').innerHTML = '';
			}
			return error;
		}

		
	</script>
	
   </body>
   <div id="footer"></div> 
   <script>
	   $(function() {
		  $("#footer").load("footer.html");		 
		 
		  $.ajaxSetup({
			async: false
		  });
		});
   </script>
</html>