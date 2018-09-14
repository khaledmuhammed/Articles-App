<?php
if (isset($_SESSION['user_id'])) { 
$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type_id'];
		$query = mysql_query("SELECT  NAME user__name,USER_TYPE_ID,IMAGE FROM USERS WHERE ID = '$user_id'");

		$inner = mysql_fetch_assoc($query);
}


?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title><?php getTitle() ?></title>
		<link rel="stylesheet" href="<?php echo $css ?>bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo $css ?>font-awesome.min.css" />
		<link rel="stylesheet" href="<?php echo $css ?>jquery-ui.css" />
		<link rel="stylesheet" href="<?php echo $css ?>jquery.selectBoxIt.css" />
		<link rel="stylesheet" href="<?php echo $css ?>front.css" />
	</head>
	<body>
	<div class="upper-bar">
		<div class="container">
			<?php 
				if (isset($_SESSION['user_id'])) { ?>

				<img class="my-image img-thumbnail img-circle" src="img.png" alt="" />
				<div class="btn-group my-info">
					<span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						<?php echo $inner['user__name']; ?>
						<span class="caret"></span>
					</span>
					<ul class="dropdown-menu">
						<li><a href="profile.php">My Profile</a></li>
						<li><a href="newad.php">New Article</a></li>
						<li><a href="my_articles.php">My Articles</a></li>
						<li><a href="logout.php">Logout</a></li>
					</ul>
				</div>

				<?php

				} else {
			?>
			<a href="login.php">
				<span class="pull-right">Login/Signup</span>
			</a>
			<?php } ?>
		</div>
	</div>
	<nav class="navbar navbar-inverse">
	  <div class="container">
	    <div class="navbar-header">
	      <button 
	      		type="button" 
	      		class="navbar-toggle collapsed" 
	      		data-toggle="collapse" 
	      		data-target="#app-nav" 
	      		aria-expanded="false">
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="index.php">Homepage</a>
	    </div>
	  </div>
	</nav>