<?php
	ob_start();
	session_start();
	$pageTitle = 'my Articles';
	include 'init.php';
?>
    <h1 class="text-center">My Articles</h1>
	<div class="container">
		<div class="row">
			<?php
				$query2 = mysql_query("SELECT U.ID, U.NAME,A.ID article_id, A.TITLE ,A.IMAGE,A.DESCRIPTION
							FROM USERS U 
							INNER JOIN ARTICLE A ON U.ID = A.USER_ID
                             WHERE U.ID ='$user_id'
							ORDER BY A.ID DESC  ");

							while ($inner = mysql_fetch_assoc($query2)) {
								echo '<div class="col-sm-6 col-md-3">';
								echo '<div class="thumbnail item-box">';
								?>
									<img class="img-responsive" src="/php/article_design/imgs/<?= $inner['IMAGE'] ?>">
								<?php
									echo '<div class="caption text-center">';
									echo '<h3><a href="articles.php?itemid='. $inner['article_id'] .'">' . $inner['TITLE'] .'</a></h3>';
									
									echo '</div>';
								echo '</div>';
							echo '</div>';
										}

				
				
			?>
		</div>
	</div>
	<?php
		include $tpl . 'footer.php'; 
		ob_end_flush();
?>
