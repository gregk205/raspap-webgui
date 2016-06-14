<?php

function Status($message, $level='success', $dismissable=true) {
	$status = '<div class="alert alert-'.$level;
	if ($dismissable) $status .= ' alert-dismissable';
	$status .= '">'.$message;
	if ($dismissable) $status .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>';
	$status .= '</div>';

	return $status;
}

function DisplayAuthConfig($username, $password){
  $status = '';
  if (isset($_POST['UpdateAdminPassword'])) {
		if (password_verify($_POST['oldpass'], $password)) {
			$new_username=trim($_POST['username']);
			if ($_POST['newpass'] != $_POST['newpassagain']) {
				$status = Status('New passwords do not match', 'danger');
			} else if ($new_username == '') {
				$status = Status('Username must not be empty', 'danger');
			} else {
				if ($auth_file = fopen(RASPI_ADMIN_DETAILS, 'w')) {
					fwrite($auth_file, $new_username.PHP_EOL);
					fwrite($auth_file, password_hash($_POST['newpass'], PASSWORD_BCRYPT).PHP_EOL);
					fclose($auth_file);
					$username = $new_username;
					$status = Status('Admin password updated');
				} else {
					$status = Status('Failed to update admin password', 'danger');
				}
			}
		} else {
			$status = Status('Old password does not match', 'danger');
		}
  }
?>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading"><i class="fa fa-lock fa-fw"></i>Configure Auth</div>
				<div class="panel-body">
					<p><?php echo $status; ?></p>
					<form role="form" action="/?page=admin_conf" method="POST">
						<div class="row">
							<div class="form-group col-md-4">
								<label for="username">Username</label>
								<input type="text" class="form-control" name="username" value="<?php echo $username; ?>"/>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-4">
								<label for="password">Old password</label>
								<input type="password" class="form-control" name="oldpass"/>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-4">
								<label for="password">New password</label>
								<input type="password" class="form-control" name="newpass"/>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-4">
								<label for="password">Repeat new password</label>
								<input type="password" class="form-control" name="newpassagain"/>
							</div>
						</div>
						<input type="submit" class="btn btn-outline btn-primary" name="UpdateAdminPassword" value="Save settings" />
					</form>
				</div><!-- /.panel-body -->
			</div><!-- /.panel-default -->
		</div><!-- /.col-lg-12 -->
	</div><!-- /.row -->
<?php 
}

?>
