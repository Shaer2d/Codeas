<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration form</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
      <div class="wrapper">
        <div class="title"><span>Login Form</span></div>
	 
  <form method="post" action="login.php">
  	<?php include('errors.php'); ?>
  	<!-- <div class="input-group"> -->
      <div class="row">
            <i class="fas fa-user"></i>
  		
  		<input type="text" name="username" placeholder="Username"></div>
  	<!-- </div> -->
  	<!-- <div class="input-group"> -->
      <div class="row">
            <i class="fas fa-lock"></i>
  	
  		<input type="password" name="password" placeholder="Password"></div>
  	<!-- </div> -->
  	<!-- <div class="input-group"> -->
      <div class="row button">
  		<button type="submit" name="login_user">Login</button></div>
  	<!-- </div> -->
  	<!-- <p> --> <div class="signup-link">
  		Not yet a member? <a href="register.php">Sign up</a></div>
  	<!-- </p> -->
  </form>
  </div>
    </div>
    
</body>
</html>