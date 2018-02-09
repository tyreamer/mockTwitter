<?php 
require_once("./include/membersite_config.php");

$servername = "localhost";
$username = "tylre";
$password = "tylrePass";
$db = "YellowstoneDB1";

if(!($fgmembersite->CheckLogin()))
{
	$loggedIn = false;
	$fgmembersite->RedirectToURL("index.php");
}	

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

$currentUser = $fgmembersite->GetUserFromConnectName($fgmembersite->ConnectName());
$post_id = $_GET['pid']; 

//Get our categories
$myCollections = $fgmembersite->myCollections();

   function Redirect($url, $permanent = false)
	{
		if (headers_sent() === false)
		{
			header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
		}

		exit();
	}

	
	//Check notifications & messages	
$notificationCount = $fgmembersite->GetNotificationAlerts();
$messageCount = $fgmembersite->GetMessageAlerts();

	
?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <link rel="icon" href="favicon.ico">
	<title>Leyff | Add to Collection</title>	

	<link href="bootstrap-3.3.6-dist/css/fileinput.css" media="all" rel="stylesheet" type="text/css">
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">  
  <link rel="stylesheet" href="icomoon/style.css">
	
	<style>
		.input-group-addon:hover {
			background-color: rgba(0,0,0,.1);
			color: rgba(255,255,255,.4);
			cursor: pointer;
		}
		.input-group-addon:active {
			background-color: rgba(0,0,0,.2);
			color: rgba(255,255,255,.4);
			cursor: pointer;
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
			background-color: rgba(0,0,0,.1);
			list-style-type: none;
			text-align: center;
		}
		
	.nav li ul li {					
			padding: 5px 15px 5px 15px;
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
			<a class="navbar-brand" href="home.php"><img src="./images/LeyffLogo.png" style="height: 50px; margin-top: -15px;"></a>
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
	
<div id="body-wrapper" style = "background-color: rgba(0,0,0,.1); padding:5% 0% 3% 0%;">
	<!-- Signup Page --> 
	<div id="signup">
		<div class="main">
			<div class="container" style="height: 800px; padding: 50px; " >
			    <div class = "col-md-3">				
			    </div>
				<div class="col-md-6" style = "background-color: #FFFFFF; border-radius: 10px;">
					<div class="col-md-12" class="form-control" style="text-align: center;">
							<hr/>
							<h2>Favorite</h2>
							<hr/>
							<form action = "favoritePost.php" name="form" id="form" method = "POST" enctype = "multipart/form-data">	
								<input type="hidden" name="uid" value="<?php echo $currentUser; ?>"/>
								<input type="hidden" name="pid" value="<?php echo $post_id; ?>"/>
								<div style="width: 100%; text-align: center;">
								<div class="alert alert-success" id="success" style="display: none;"></div>
									<div class="alert alert-danger" id="text-error" style="display: none;"></div>
									<select name="cid" class="form-control" id="select" required="required">	
											<option selected="true" disabled = "disabled" disabled>Select a collection</option>								
									<?php 
											for ($i = 0; $i < sizeOf($myCollections); $i++) 
											{
									?>
											<option value="<?php echo $myCollections[$i]['collection_id'];?>"> <?php echo $myCollections[$i]['collection_name'];?></option>
									<?php		
											}
									?>
									</select>
									<p style="padding: 10px 0px 10px 0px">	- OR - 	</p>
									<div class="input-group" style="border-right-style:none;">
										<input type="text" id="newcollection" class="form-control" placeholder="Add a new collection..."/>
										<span class="input-group-addon" onclick="AddCollection()">
											<i class="glyphicon glyphicon-plus"></i>
										</span>								
									</div>
								</div>	
								<hr/>							
								<div class="login" style = "text-align: center; ">									
										<label class="control-label"></label>
										<input type="submit" name="submit" class = "btn btn-large" disabled="disabled" id="submit" value="Add to collection" style = "background-color: #75C0C0; width: 100%;">
										<button name="cancel" onclick="history.go(-1);" class = "btn btn-large" id="cancel" value="Cancel" style = "margin-top: 2%;background-color: #99C0C0; width: 100%;"> Cancel </button>
								</div>	
							</form>						
					</div>
				</div>
				<div class = "col-md-3">
		        </div>
			</div>
		</div>		    
			
	</div>		
</div> <!-- Body Wrapper -->

	
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	
<script>
		function AddCollection(){							
			if ($('#newcollection').val().trim() == "") {
				$('#text-error').html('Please enter a name for the new collection.');
				$('#text-error').show();
			}
			
			else {				
				var name = $('#newcollection').val().trim();
				var exists = false;
				
				 //Make sure we don't have this already
				 $("#select option").each(function(i){
					if ($(this).text().trim().toLowerCase() == name.trim().toLowerCase()) 
					{
						
						$('#text-error').html('This collection already exists.');
						$('#text-error').show();
						exists = true;
					}				
					
					else {
						exists = false;
					}
					
				  });
				
				if  (exists) {
					return false;
				}
				else {
					$.ajax
					({ 
						url: 'createCollection.php',
						 type: "POST",
						  data: "collection_name=" + name,
						  success: function(data){							 						
							$("#select").append($("<option></option>").val(data).html(name));
							$('#newcollection').val('').html('');
							$('#text-error').hide();
							$('#success').html('Successfully added "' + name + '"');
							$('#success').show();
						  }
					}); 	
				}
			}					
		}
		
		$( "#select" ).change(function() {
			var str = "";
			$( "select option:selected" ).each(function() {
			  str += $( this ).text() + " ";
			});
			$( "#submit" ).removeAttr('disabled');
		  })
		  
		
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