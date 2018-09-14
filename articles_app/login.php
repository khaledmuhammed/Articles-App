<?php
	ob_start();
	session_start();
	$pageTitle = 'Login';
	include 'init.php';

	if (isset($_SESSION['user_id'])) {
		header('Location: index.php');
	}

?>

<div class="container login-page">
	<h1 class="text-center">
		<span class="selected" data-class="login">Login</span> | 
		<span data-class="signup">Signup</span>
	</h1>
	<!-- Start Login Form -->
	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<div class="input-container">
			<input 
				class="form-control" 
				type="text" 
				name="username" 
				autocomplete="off"
				placeholder="Type your username" 
				required />
		</div>
		<div class="input-container">
			<input 
				class="form-control" 
				type="password" 
				name="password" 
				autocomplete="new-password"
				placeholder="Type your password" 
				required />
		</div>
		<input class="btn btn-primary btn-block" name="login" type="submit" value="Login" />
	</form>
	<?php
if (isset($_POST['login'])) {
	$username = $_POST['username'];
	$password = hash("sha512", $_POST['password']);

	$result = mysql_query("SELECT ID,USER_TYPE_ID FROM users 
	WHERE upper(email) = upper('$username') AND PASSWORD = '$password' 
	 OR  upper(NAME) = upper('$username') AND PASSWORD = '$password' ");

	$COUNT = mysql_num_rows($result);
	if ($COUNT > 0) {
		$data = mysql_fetch_assoc($result);
		$_SESSION['user_id'] = $data['ID'];
		$_SESSION['user_type_id'] = $data['USER_TYPE_ID'];
		header('location: login.php');
	} else{
		?>
		<div class="the-errors text-center">
		<?="incorrect username or password";?>
		</div>
		<?php
	}
		
}
?>
	<!-- End Login Form -->
	<!-- Start Signup Form -->
	<form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
		<div class="input-container">
			<input 
				pattern=".{4,}"
				title="Username Must Be Between 4 Chars"
				class="form-control" 
				type="text" 
				name="name" 
				autocomplete="off"
				placeholder="Type your username" 
				required />
		</div>
		<div class="input-container">
			<input 
				class="form-control" 
				type="email" 
				name="email" 
				placeholder="Type a Valid email"
				required  />
		</div>
		<div class="input-container">
			<input 
				class="form-control" 
				type="file" 
				name="image" 
				placeholder="Select your image" />
		</div>
		<div class="input-container">
			<input 
				minlength="4"
				class="form-control" 
				type="password" 
				name="password" 
				autocomplete="new-password"
				placeholder="Type a Complex password" 
				required />
		</div>
		<div class="input-container">
			<input 
				minlength="4"
				class="form-control" 
				type="password" 
				name="confirm_password" 
				autocomplete="new-password"
				placeholder="Type a password again" 
				required />
		</div>
		
		<input class="btn btn-success btn-block" name="register" type="submit" value="Signup" />
	</form>
	<?php
if (isset($_POST['register'])) {
	$name = $_POST['name'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$confirm_password = $_POST['confirm_password'];
	$hash_pass = hash('sha512',$password);

	//copy the emage selected to project directory and get the name of it
	$image_name = basename($_FILES["image"]["name"]);
	if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_name)) {
		?>
		<div class="the-errors text-center">
		<?="The file ". $image_name . " has been uploaded.<br>";?>
		</div>
		<?php
      
    } else {
		?>
		<div class="the-errors text-center">
		<?="Sorry, there was an error uploading your file.";?>
		</div>
		<?php
        
	}
	
  


	$result = mysql_query("SELECT ID,NAME,EMAIL FROM USERS WHERE EMAIL = '$email'");
	$COUNT = mysql_num_rows($result);
	if ($COUNT > 0) {
		?>
		<div class="the-errors text-center">
		<?="email is not available";?>
		</div>
		<?php
		
	}
	elseif($password != $confirm_password){
		?>
		<div class="the-errors text-center">
		<?="password does not match";?>
		</div>
		<?php
		
	}
	else{
		if (mysql_query("INSERT INTO USERS (NAME,EMAIL,PASSWORD,
			IMAGE,USER_TYPE_ID) 
			VALUES('$name','$email','$hash_pass','$image_name',2)"))
			{
			?>
				<div class="the-errors text-center">
				<?="Registration is added";?>
				</div>
			<?php 
			  }
				else{
					?>
				<div class="the-errors text-center">
				<?="Registration is failed";?>
				</div>
			<?php 
				}
			
	}
}
?>

	<!-- End Signup Form -->
	<!-- <div class="the-errors text-center">
		
	</div> -->
</div>

<?php 
include $tpl . 'footer.php';
ob_end_flush();
?>