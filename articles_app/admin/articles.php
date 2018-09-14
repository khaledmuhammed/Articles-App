<?php

	/*
	================================================
	== Items Page
	================================================
	*/

	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = 'articles';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') {


			$stmt = $con->prepare("SELECT 
										articles.*, 
										users.Username 
									FROM 
										articles
	
									INNER JOIN 
										users 
									ON 
										users.UserID = articles.Member_ID
									ORDER BY 
										article_ID DESC");

			// Execute The Statement

			$stmt->execute();

			// Assign To Variable 

			$items = $stmt->fetchAll();

			if (! empty($items)) {

			?>

			<h1 class="text-center">Manage articles</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Article Name</td>
							<td>Description</td>
							<td>Username</td>
							<td>Control</td>
						</tr>
						<?php
							foreach($items as $item) {
								echo "<tr>";
									echo "<td>" . $item['article_ID'] . "</td>";
									echo "<td>" . $item['Name'] . "</td>";
									echo "<td>" . $item['Description'] . "</td>";;
									echo "<td>" . $item['Username'] ."</td>";
									echo "<td>
										<a href='articles.php?do=Edit&itemid=" . $item['article_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
										<a href='articles.php?do=Delete&itemid=" . $item['article_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
									echo "</td>";
								echo "</tr>";
							}
						?>
						<tr>
					</table>
				</div>
				<a href="articles.php?do=Add" class="btn btn-sm btn-primary">
					<i class="fa fa-plus"></i> New Articles
				</a>
			</div>

			<?php } else {

				echo '<div class="container">';
					echo '<div class="nice-message">There\'s No Items To Show</div>';
					echo '<a href="articles.php?do=Add" class="btn btn-sm btn-primary">
							<i class="fa fa-plus"></i> New article
						</a>';
				echo '</div>';

			} ?>

		<?php 

		} elseif ($do == 'Add') { ?>

			<h1 class="text-center">Add New Article</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Insert" method="POST">
					<!-- Start Name Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10 col-md-6">
							<input 
								type="text" 
								name="name" 
								class="form-control" 
								required="required"  
								placeholder="Name of The Article" />
						</div>
					</div>
					<!-- End Name Field -->
					<!-- Start Description Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Description</label>
						<div class="col-sm-10 col-md-6">
							<input 
								type="text" 
								name="description" 
								class="form-control" 
								required="required"  
								placeholder="Description of The Article" />
						</div>
					</div>
					<!-- End Description Field -->
					<!-- Start Members Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Member</label>
						<div class="col-sm-10 col-md-6">
							<select name="member">
								<option value="0">...</option>
								<?php
									$allMembers = getAllFrom("*", "users", "", "", "UserID");
									foreach ($allMembers as $user) {
										echo "<option value='" . $user['UserID'] . "'>" . $user['Username'] . "</option>";
									}
								?>
							</select>
						</div>
					</div>
					<!-- End Members Field -->
					<!-- Start Submit Field -->
					<div class="form-group form-group-lg">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" value="Add Article" class="btn btn-primary btn-sm" />
						</div>
					</div>
					<!-- End Submit Field -->
				</form>
			</div>

			<?php

		} elseif ($do == 'Insert') {

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				echo "<h1 class='text-center'>Insert Item</h1>";
				echo "<div class='container'>";

				// Get Variables From The Form

				$name		= $_POST['name'];
				$desc 		= $_POST['description'];
				$member 	= $_POST['member'];
	

				// Validate The Form

				$formErrors = array();

				if (empty($name)) {
					$formErrors[] = 'Name Can\'t be <strong>Empty</strong>';
				}

				if (empty($desc)) {
					$formErrors[] = 'Description Can\'t be <strong>Empty</strong>';
				}


				if ($member == 0) {
					$formErrors[] = 'You Must Choose the <strong>Member</strong>';
				}


				// Loop Into Errors Array And Echo It

				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {

					// Insert Userinfo In Database

					$stmt = $con->prepare("INSERT INTO 

						articles(Name, Description, Member_ID)

						VALUES(:zname, :zdesc, :zmember)");

					$stmt->execute(array(

						'zname' 	=> $name,
						'zdesc' 	=> $desc,
						'zmember'	=> $member

					));

					// Echo Success Message

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';

					redirectHome($theMsg, 'back');

				}

			} else {

				echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

				redirectHome($theMsg);

				echo "</div>";

			}

			echo "</div>";

		} elseif ($do == 'Edit') {

			// Check If Get Request article Is Numeric & Get Its Integer Value

			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

			// Select All Data Depend On This ID

			$stmt = $con->prepare("SELECT * FROM articles WHERE article_ID = ?");

			// Execute Query

			$stmt->execute(array($itemid));

			// Fetch The Data

			$item = $stmt->fetch();

			// The Row Count

			$count = $stmt->rowCount();

			// If There's Such ID Show The Form

			if ($count > 0) { ?>

				<h1 class="text-center">Edit Article</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="itemid" value="<?php echo $itemid ?>" />
						<!-- Start Name Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Name</label>
							<div class="col-sm-10 col-md-6">
								<input 
									type="text" 
									name="name" 
									class="form-control" 
									required="required"  
									placeholder="Name of The Article"
									value="<?php echo $item['Name'] ?>" />
							</div>
						</div>
						<!-- End Name Field -->
						<!-- Start Description Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10 col-md-6">
								<input 
									type="text" 
									name="description" 
									class="form-control" 
									required="required"  
									placeholder="Description of The article"
									value="<?php echo $item['Description'] ?>" />
							</div>
						</div>
						<!-- End Description Field -->
						<!-- Start Members Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Member</label>
							<div class="col-sm-10 col-md-6">
								<select name="member">
									<?php
										$allMembers = getAllFrom("*", "users", "", "", "UserID");
										foreach ($allMembers as $user) {
											echo "<option value='" . $user['UserID'] . "'"; 
											if ($item['Member_ID'] == $user['UserID']) { echo 'selected'; } 
											echo ">" . $user['Username'] . "</option>";
										}
									?>
								</select>
							</div>
						</div>
						<!-- End Members Field -->
					    <!-- Start Submit Field -->
					    <div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Save Article" class="btn btn-primary btn-sm" />
							</div>
						</div>
						<!-- End Submit Field -->
					</form>

					<?php

					// Select All Users Except Admin 

					$stmt = $con->prepare("SELECT 
												comments.*, users.Username AS Member  
											FROM 
												comments
											INNER JOIN 
												users 
											ON 
												users.UserID = comments.user_id
											WHERE article_id = ?");

					// Execute The Statement

					$stmt->execute(array($itemid));

					// Assign To Variable 

					$rows = $stmt->fetchAll();

					if (! empty($rows)) {
						
					?>
					<h1 class="text-center">Manage [ <?php echo $item['Name'] ?> ] Comments</h1>
					<div class="table-responsive">
						<table class="main-table text-center table table-bordered">
							<tr>
								<td>Comment</td>
								<td>User Name</td>
								<td>Added Date</td>
								<td>Control</td>
							</tr>
							<?php
								foreach($rows as $row) {
									echo "<tr>";
										echo "<td>" . $row['comment'] . "</td>";
										echo "<td>" . $row['Member'] . "</td>";
										echo "<td>" . $row['comment_date'] ."</td>";
										echo "<td>
											<a href='comments.php?do=Edit&comid=" . $row['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
											<a href='comments.php?do=Delete&comid=" . $row['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
											if ($row['status'] == 0) {
												echo "<a href='comments.php?do=Approve&comid="
														 . $row['c_id'] . "' 
														class='btn btn-info activate'>
														<i class='fa fa-check'></i> Approve</a>";
											}
										echo "</td>";
									echo "</tr>";
								}
							?>
							<tr>
						</table>
					</div>
					<?php } ?>
				</div>

			<?php

			// If There's No Such ID Show Error Message

			} else {

				echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';

				redirectHome($theMsg);

				echo "</div>";

			}			

		} elseif ($do == 'Update') {

			echo "<h1 class='text-center'>Update article</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				// Get Variables From The Form

				$id 		= $_POST['itemid'];
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$member 	= $_POST['member'];

				// Validate The Form

				$formErrors = array();

				if (empty($name)) {
					$formErrors[] = 'Name Can\'t be <strong>Empty</strong>';
				}

				if (empty($desc)) {
					$formErrors[] = 'Description Can\'t be <strong>Empty</strong>';
				}

				if ($member == 0) {
					$formErrors[] = 'You Must Choose the <strong>Member</strong>';
				}


				// Loop Into Errors Array And Echo It

				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {

					// Update The Database With This Info

					$stmt = $con->prepare( "UPDATE 
												articles 
											SET 
												Name = ?, 
												Description = ?, 
												Member_ID = ?
											WHERE 
												article_ID = ?");

					$stmt->execute(array($name, $desc, $member, $id));

					// Echo Success Message

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

					redirectHome($theMsg, 'back');

				}

			} else {

				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

				redirectHome($theMsg);

			}

			echo "</div>";

		} elseif ($do == 'Delete') {

			echo "<h1 class='text-center'>Delete article</h1>";
			echo "<div class='container'>";

				// Check If Get Request article ID Is Numeric & Get The Integer Value Of It

				$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('article_ID', 'articles', $itemid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("DELETE FROM articles WHERE article_ID = :zid");

					$stmt->bindParam(":zid", $itemid);

					$stmt->execute();

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';

					redirectHome($theMsg, 'back');

				} else {

					$theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

					redirectHome($theMsg);

				}

			echo '</div>';

		} 
		include $tpl . 'footer.php';

	} else {

		header('Location: index.php');

		exit();
	}

	ob_end_flush(); // Release The Output

?>