<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration system PHP and MySQL</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
      <div class="wrapper">
  	<div class="title"><span>Register</span></div>

	
  <form method="post" action="register.php">
  	<?php include('errors.php'); ?>
	  <div class="row">
            <i class="fas fa-user"></i>
  	  <input type="text" name="username" value="<?php echo $username; ?>"placeholder="Username">
  	</div>
	  <div class="row">
            <i class="far fa-envelope"></i>
 
  	  <input type="email" name="email" value="<?php echo $email; ?>"placeholder="Email">
  	</div>
	  <div class="row">
            <i class="fas fa-lock"></i>
  
  	  <input type="password" name="password_1"placeholder="Password">
  	</div>
  	<div class="row">
            <i class="fas fa-lock"></i>
  	
  	  <input type="password" name="password_2"placeholder="Confirm password">
  	</div>
  	<div class="row button">
  	  <button type="submit" class="btn" name="reg_user">Register</button>
  	</div>
  	<div class="signup-link">
  		Already a member? <a href="login.php">Sign in</a>
</div>
  </form>
</div>
</div>
</body>
</html>