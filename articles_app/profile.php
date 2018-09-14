<?php
	ob_start();
	session_start();
	$pageTitle = 'Profile';
	include 'init.php';
	
	if (isset($_SESSION['user_id'])) {
		$user_id = $_SESSION['user_id'];
        $user_type = $_SESSION['user_type_id'];
		$query = mysql_query("SELECT ID,NAME,EMAIL FROM USERS WHERE ID = '$user_id'");
        $inner = mysql_fetch_assoc($query);
	


?>
<h1 class="text-center">My Profile</h1>
<div class="information block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">My Information</div>
			<div class="panel-body">
				<ul class="list-unstyled">
					<li>
						<i class="fa fa-unlock-alt fa-fw"></i>
						<span>Login Name</span> : <?php echo $inner['NAME'] ?>
					</li>
					<li>
						<i class="fa fa-envelope-o fa-fw"></i>
						<span>Email</span> : <?php echo $inner['EMAIL'] ?>
					</li>
					<li>
						<i class="fa fa-user fa-fw"></i>
						<span>Full Name</span> : <?php echo $inner['NAME'] ?>
					</li>
					
				</ul>
				<!-- <a href="#" class="btn btn-default">Edit Information</a> -->
			</div>
		</div>
	</div>
</div>
<!-- <div id="my-ads" class="my-ads block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">My Profile</div>
			<div class="panel-body">
			<?php
				// $myItems = getAllFrom("*", "articles", "where Member_ID = $userid", "", "article_ID");
				// if (! empty($myItems)) {
				// 	echo '<div class="row">';
				// 	foreach ($myItems as $item) {
				// 		echo '<div class="col-sm-6 col-md-3">';
				// 			echo '<div class="thumbnail item-box">';
				// 				echo '<img class="img-responsive" src="img.png" alt="" />';
				// 				echo '<div class="caption">';
				// 					echo '<h3><a href="articles.php?itemid='. $item['article_ID'] .'">' . $item['Name'] .'</a></h3>';
				// 					echo '<p>' . $item['Description'] . '</p>';
				// 				echo '</div>';
				// 			echo '</div>';
				// 		echo '</div>';
				// 	}
				// 	echo '</div>';
				// } else {
				// 	echo 'Sorry There\' No Articles To Show, Create <a href="newad.php">New Article</a>';
				// }
			?>
			</div>
		</div>
	</div>
</div> -->
<!-- <div class="my-comments block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">Latest Comments</div>
			<div class="panel-body">
			<?php
				// $myComments = getAllFrom("comment", "comments", "where user_id = $userid", "", "c_id");
				// if (! empty($myComments)) {
				// 	foreach ($myComments as $comment) {
				// 		echo '<p>' . $comment['comment'] . '</p>';
				// 	}
				// } else {
				// 	echo 'There\'s No Comments to Show';
				// }
			?>
			</div>
		</div>
	</div>
</div> -->
<?php
	} else {
		header('Location: login.php');
		exit();
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>