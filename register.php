<?PHP
require_once("./include/membersite_config.php");

if(isset($_POST['submitted']))
{
   if($fgmembersite->RegisterUser())
   {
		$fgmembersite->RedirectToURL("upload.php?img=" . $_POST['userPicFile'].'&email='.$_POST['email']);
   }
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
	<title>Leyff | Sign Up</title>	
	
	<link href="bootstrap-3.3.6-dist/css/fileinput.css" media="all" rel="stylesheet" type="text/css">
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">  
  <link rel="stylesheet" href="icomoon/style.css">
  <link rel="stylesheet" href="css/style.css">
</head>

		<script>
		
		function showRegistration() {
			document.getElementById('fbLogin').style.display = "block"; 
			document.getElementById('registration').style.display = "none"; 
		}
		 window.onload = showRegistration;
		
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      runConfirm();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' + 'into Facebook.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }
  
  function FBLogout() {
	  FB.api('/me/permissions', 'delete', function(response) {
			console.log(response.status); // true for successful logout.
			location.reload();
		});
  }

  window.fbAsyncInit = function() {
			  FB.init({
				appId      : '1692957604301627',
				cookie     : true,  // enable cookies to allow the server to access 
									// the session
				xfbml      : true,  // parse social plugins on this page
				version    : 'v2.5' // use graph api version 2.5
			  });

			  // Now that we've initialized the JavaScript SDK, we call 
			  // FB.getLoginStatus().  This function gets the state of the
			  // person visiting this page and can return one of three states to
			  // the callback you provide.  They can be:
			  //
			  // 1. Logged into your app ('connected')
			  // 2. Logged into Facebook, but not your app ('not_authorized')
			  // 3. Not logged into Facebook and can't tell if they are logged into
			  //    your app or not.
			  //
			  // These three cases are handled in the callback function.

			  FB.getLoginStatus(function(response) {
				statusChangeCallback(response);
			  });
	  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function runConfirm() {
    console.log('Welcome!  Fetching your information.... ');
      FB.api('/me?fields=name,email,id', function(response) {
	
      var pic = 'https://graph.facebook.com/' + response.id +'/picture?width=400&height=400';
      document.getElementById('name').value = response.name;
	  document.getElementById('nameText').innerHTML = response.name;
	  document.getElementById('email').value = response.email;
	  document.getElementById('userPicture').src = pic;
	  document.getElementById('userPicFile').value = pic;
	  document.getElementById('fbLogin').style.display = "none"; 
	  document.getElementById('registration').style.display = "block"; 
	  document.getElementById('fbLogin').style.visibility = "hidden"; 
	  document.getElementById('registration').style.visibility = "visible"; 
    });
  }
 
</script>
<body style = "padding-top: 0px;">
    <div id="body-wrapper" style = "background-color: rgba(0,0,0,.1);padding-bottom: 15%;padding-top: 0px;">
            <!-- Signup Page --> 
            <div id="signup">

                     <nav class="navbar navbar-default">
                                                    <div class="container">
                                                              <div class="navbar-header">
                                                                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                                                                              <span class="sr-only">Toggle navigation</span>
                                                                              <span class="icon-bar"></span>
                                                                              <span class="icon-bar"></span>
                                                                              <span class="icon-bar"></span>
                                                                            </button>
                                                                            <a class="navbar-brand" href="home.php"><img src="./images/LeyffLogo.png" style="height: 50px; margin-top: -15px;"></a>
                                                              </div>
                                                    </div>
                     </nav>


                    <div class="main">
                            <div class="container" style="height: 800px; padding: 50px;" >
                                <div class = "col-md-3">				
                                </div>
                                    <div class="col-md-6" style = "background-color: #FFFFFF;  border: solid white 1px; border-radius: 20px;">
                                            <div class="col-md-12" id="fbLogin" style="height: 30%; width: 100%;text-align: center; position: relative;">
                                                                    <h4>We'll use your Facebook information to make you an account:</h4>
                                                                    <hr/>
                                                                    <p><fb:login-button size="large" scope="public_profile,email" style="position: absolute; top:50%; bottom:0; left:0; right:0;"onlogin="checkLoginState();"> Sign Up with Facebook</fb:login-button> </p>
                                            </div>
                                            <div class="col-md-12" id="registration">
                                            <hr/>
                                                            <div style="width: 100%; text-align: center;">								
                                                                    <div class="alert alert-success" id="alertUser">Successfully connected with Facebook!</div>									
                                                            </div>							
                                                            <div class="login" style = "text-align: center; ">
                                                                      <form method="POST" id='register' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
                                                                            <hr/>
                                                                            <input type='hidden' name='submitted' id='submitted' value='1'/>	
                                                                            <h3> <p id="nameText"></p></h3>
                                                                            <img class="img-rounded" id="userPicture" name="userPicture" style="hiehgt: 200px; width: 200px;"></img>
                                                                            <hr/>
                                                                            <input type="hidden" id="userPicFile" name="userPicFile"></input>
                                                                            <p><input type="hidden" id="name" name="name" value='<?php echo $fgmembersite->SafeDisplay('name') ?>' style = "width: 75%;" placeholder=" Full Name" readonly></p>
                                                                            <p><input type="hidden" name = "email" id="email" value='<?php echo $fgmembersite->SafeDisplay('email') ?>' maxlength="50" style = "width: 75%;" placeholder=" E-Mail Address" readonly></p>									
                                                                            <button class="btn btn-default newAccount" style="margin-bottom: 2%;" onclick="FBLogout();">Click here to register with a different account</button> 
                                                                            <hr/>									
                                                                            <h4> Now you can choose a Store Name and password: </h4>
                                                                            <p><input type="text" name = "connectName"id="connectName" value='<?php echo $fgmembersite->SafeDisplay('connectName') ?>' maxlength="50" style = "width: 75%;" placeholder="Store Name (For login)"></p>
                                                                            <p><input type="password" name ="password" id="password" value="" style = "width: 75%;" placeholder=" Password"></p>
                                                                            <br/>
                                                                            <div><span class='error' style="color: red;"><?php echo $fgmembersite->GetErrorMessage(); ?> </span> </div>
                                                                            <br/>
                                                                            <input type="submit" name="submit" class = "btn btn-large submit" id="submit" value="Submit"></p>
                                                                      </form>
                                                                      <hr/>
                                                            </div>
                                            </div>
                                    </div>
                                    <div class = "col-md-3">
                            </div>
                            </div>
                    </div>		    

            </div>		
    </div> 
<!-- Body Wrapper -->

	
	<script type='text/javascript'>
// <![CDATA[
    var frmvalidator  = new Validator("register");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();
    frmvalidator.addValidation("name","req","Please provide your name");

    frmvalidator.addValidation("email","req","Please provide your email address");

    frmvalidator.addValidation("email","email","Please provide a valid email address");

    frmvalidator.addValidation("connectName","req","Please provide a username");
    
    frmvalidator.addValidation("password","req","Please provide a password");
	
	 frmvalidator.addValidation("subject","req","Please choose your favorite subject");

// ]]>
</script>


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	</div>
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