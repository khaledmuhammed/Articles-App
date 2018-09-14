<?php
	ob_start();
	session_start();
	$pageTitle = 'Create New Article';
	include 'init.php';
	if (isset($_SESSION['user_id'])) {

		$formErrors = array();
		//adding the article
		if (isset($_POST['add_article']))
       {
			$title = $_POST['title'];
			$description = $_POST['description'];
			
   //copy the emage selected to project directory and get the name of it
	$image_name = basename($_FILES["image"]["name"]);
	move_uploaded_file($_FILES["image"]["tmp_name"],"imgs/". $image_name);
	

    $result2 = mysql_query("SELECT ID,TITLE,DESCRIPTION FROM ARTICLE WHERE TITLE = '$title' AND DESCRIPTION = '$description'");
    $COUNT2 = mysql_num_rows($result2);
    if ($COUNT2 > 0) {
        $formErrors[]= 'Artical is already added';
    } 
    else {
        if (mysql_query("INSERT INTO ARTICLE (TITLE,DESCRIPTION,IMAGE,USER_ID) 
			VALUES('$title','$description','$image_name','$user_id')"))
			$succesMsg = 'Article Has Been Added';
			
        else
		$formErrors[]= "Error while Adding the Article";
         }
  }

?>
<h1 class="text-center"><?php echo $pageTitle ?></h1>
<div class="create-ad block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading"><?php echo $pageTitle ?></div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-8">
						<form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
							<!-- Start Name Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Title</label>
								<div class="col-sm-10 col-md-9">
									<input 
										pattern=".{4,}"
										title="This Field Require At Least 4 Characters"
										type="text" 
										name="title" 
										class="form-control live"  
										placeholder="Title of The Article"
										data-class=".live-title"
										required />
								</div>
							</div>
							<!-- End Name Field -->
							<!-- Start Description Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Description</label>
								<div class="col-sm-10 col-md-9">
									<input 
										pattern=".{10,}"
										title="This Field Require At Least 10 Characters"
										type="text" 
										name="description" 
										class="form-control live"   
										placeholder="Description of The Article" 
										data-class=".live-desc"
										required />
								</div>
							</div>
							<!-- End Description Field -->
							<!-- Start Image Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Article Image</label>
								<div class="col-sm-10 col-md-9">
									<input 
										pattern=".{10,}"
										title="This Field Require At Least 10 Characters"
										type="file" 
										name="image" 
										class="form-control live"  
										data-class=".live-img"
										/>
								</div>
							</div>
							<!-- End Image Field -->
							<!-- Start Submit Field -->
							<div class="form-group form-group-lg">
								<div class="col-sm-offset-3 col-sm-9">
									<input type="submit" name="add_article" value="Add Article" class="btn btn-primary btn-sm" />
								</div>
							</div>
							<!-- End Submit Field -->
						</form>
					</div>
					<div class="col-md-4">
					
						<div class="thumbnail item-box live-preview live-img">
							<img class="img-responsive" src="img.png" alt="" />
							<div class="caption">
								<h3 class="live-title">Title</h3>
								<p class="live-desc">Description</p>
							</div>
						</div>
					</div>
				</div>
				<!-- Start Loopiong Through Errors -->
				<?php 
					if (! empty($formErrors)) {
						foreach ($formErrors as $error) {
							echo '<div class="alert alert-danger">' . $error . '</div>';
						}
					}
					if (isset($succesMsg)) {
						echo '<div class="alert alert-success">' . $succesMsg . '</div>';
					}
				?>
				<!-- End Loopiong Through Errors -->
			</div>
		</div>
	</div>
</div>
<?php
	} else {
		header('Location: login.php');
		exit();
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>