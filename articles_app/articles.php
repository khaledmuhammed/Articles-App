<?php
	ob_start();
	session_start();
	$pageTitle = 'Show article';
	include 'init.php';

	$article_id = $_GET['article_id'];
	$query = mysql_query("SELECT U.NAME, A.ID,A.TITLE,A.DESCRIPTION,A.IMAGE, A.USER_ID
	FROM ARTICLE A
	INNER JOIN USERS U
     ON U.ID = A.USER_ID
	 WHERE A.ID = '$article_id'");
		$inner = mysql_fetch_assoc($query);
		$count = mysql_num_rows($query);
	if ($count > 0) {

	
?>
<h1 class="text-center"><?php echo $inner['TITLE'] ?></h1>
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<img class="img-responsive img-thumbnail center-block" src="img.png" alt="" />
		</div>
		<div class="col-md-9 item-info">
			<h2><?php echo $inner['TITLE'] ?></h2>
			<p><?php echo $inner['DESCRIPTION'] ?></p>
			<ul class="list-unstyled">
				<li>
					<i class="fa fa-user fa-fw"></i>
					<span>Added By</span> : <a href="#"><?php echo $inner['NAME'] ?></a>
				</li>
			</ul>
		</div>
	</div>
	<hr class="custom-hr">
	<?php if (isset($_SESSION['user_id'])) { ?>
	<!-- Start Add Comment -->
	<div class="row">
		<div class="col-md-offset-3">
			<div class="add-comment">
				<h3>Add Your Comment</h3>
				<form action="<?php echo $_SERVER['PHP_SELF'].'?article_id='.$article_id ?>" method="POST">
					<textarea name="comment" required></textarea>
					<input class="btn btn-primary" name="add_comment" type="submit" value="Add Comment">
				</form>
				<?php 
					if (isset($_POST['add_comment'])) 
					{
							$comment 	= $_POST['comment'];
							$userid 	= $_SESSION['user_id'];

							if (!empty($comment)) {

								if (mysql_query("INSERT INTO COMMENTS (COMMENT,ARTICLE_ID,USER_ID)
								VALUES('$comment','$article_id','$userid')") ) {
									echo '<div class="alert alert-success">Comment Added</div>';
								}
								else{
									echo '<div class="alert alert-danger">Error Adding Comment</div>';
								}

						}
						 else {

							echo '<div class="alert alert-danger">You Must Add Comment</div>';

						}

					}
				?>
			</div>
		</div>
	</div>
	<!-- End Add Comment -->
	<?php }
	 else {
		echo '<a href="login.php">Login</a> or <a href="login.php">Register</a> To Add Comment';
	} ?>
	<hr class="custom-hr">
		<?php

			// Select All Users Except Admin 
			$query2 = mysql_query("SELECT C.ID, C.COMMENT,U.NAME 
									FROM COMMENTS C 
									INNER JOIN USERS U
									ON U.ID = C.USER_ID
									order by C.ID");
			

			
       while($inner2 = mysql_fetch_assoc($query2)) {
		?>	
		<div class="comment-box">
			<div class="row">
				<div class="col-sm-2 text-center">
					<img class="img-responsive img-thumbnail img-circle center-block" src="img.png" alt="" />
					<?php echo $inner2['NAME'] ?>
				</div>
				<div class="col-sm-10">
					<p class="lead"><?php echo $inner2['COMMENT'] ?></p>
				</div>
			</div>
		</div>
		<hr class="custom-hr">
	<?php
	}
	?>
</div>
<?php
	
  }
	 else {
		echo '<div class="container">';
			echo '<div class="alert alert-danger">There\'s no Such ID Or This Item Is Waiting Approval</div>';
		echo '</div>';
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>