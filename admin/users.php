<?php

if (!isset($myUser) || $myUser->role != 2) {
	die('unauthorized access');
}

if (isset($_POST['update']) && $_POST['update'] == 1) {
	$user = new stdClass();
	$user->role = $_POST['type'];
	$user->email = $_POST['email'];
	$user->firstName = $_POST['firstName'];
	$user->lastName = $_POST['lastName'];
	$user->password = $_POST['password'];
	$con->connect();
	$con->createUser($user);
	$con->close();
}

$users = $con->getAllUsers();
?>

<h1>Manage users</h1>

<a href="javascript:createUser();">Create user</a>

<div id="createUserForm" style="display:none;">
	<form method="post" action="?page=users">
		<input type="hidden" name="update" value="1" />
		<label>User type:</label>
		<select name="type">
			<option value="1">User</option>
			<option value="2">Admin</option>
		</select><br />		
		<label>First name:</label>
		<input type="text" required="required" name="firstName" value="" /><br />
		<label>Last name:</label>
		<input type="text" required="required" name="lastName" value="" /><br />
		<label>E-mail:</label>
		<input type="email" required="required" name="email" value="" /><br />
		<br />
		<label>Password:</label>
		<input type="password" required="required" id="pasw1" name="password" value="" /><br />
		<label>Confirm password:</label>
		<input type="password" id="pasw2" value="" /> <label id="dontmatch" style="display:none;">Passwords don't match!</label><br />	
		<button type="button" onclick="javascript:submitForm();" >Create</button>
	</form>
</div>

<table style="width: 95%;">
	<tr>
		<th style="text-align:left; width:10%;">ID</th>
		<th style="text-align:left; width:17%;">Name</th>
		<th style="text-align:left; width:18%;">Email</th>
		<th style="text-align:left; width:17%;">Approved by</th>
		<th style="text-align:left; width:14%;">Registered</th>
		<th style="text-align:left; width:24%;">Options</th>
	</tr>
	<?php

	foreach ($users as $u) {
		echo "<tr>";
		echo "<td>" . $u->userId . "</td>";
		echo "<td>" . $u->firstName . " " . $u->lastName . "</td>";
		echo "<td>" . $u->email . "</td>";
		if ($u->approvedBy) {
			$admname = $con->getUserById($u->approvedBy);
			$admname = $admname -> firstName . " " . $admname ->lastName;
			echo "<td>" . $admname . "</td>";
		} else {echo "<td></td>";}
		echo "<td>" . date("h:i a, m/d/y",$u->created) . "</td>";
		echo "<td>";
		if ($u->userId == 1)
			echo "Superadmin: No options";
		if ($u->approvedBy && $u->userId > 1)
			echo "<a href='javascript:blockUser(".$u->userId.");'>Block</a>";
		if (!$u->approvedBy)
			echo "<a href='javascript:approveUser(".$u->userId.");'>Approve</a>";
		if ($u->approvedBy && $u->role == 1)
			echo "<a href='javascript:makeAdmin(".$u->userId.");'>Make Admin</a>";
		if ($u->approvedBy && $u->role == 2 && $u->userId > 1)
			echo "<a href='javascript:makeUser(".$u->userId.");'>Make NON-Admin</a>";		
		echo "</td>";
	}
?>
</table>

<script>
	function blockUser(id) {
		
	}

	function approveUser(id) {

	}

	function makeAdmin(id) {

	}

	function makeUser(id) {

	}
	
	function createUser() {
		$("#createUserForm").fadeIn(200);
	}

	var allowPost = true;
	$(document).ready(function(){
		$("#dontmatch").hide();
		$("#pasw1").keyup(function(){
			allowPost = false;
			if ($("#pasw2").val().length > 0 && $("#pasw2").val()!=$("#pasw1").val()) {
				$("#dontmatch").fadeIn(200);
			} else {$("#dontmatch").hide();}
		});	
		$("#pasw2").keyup(function(){
			allowPost = false;
			if ($("#pasw2").val()!=$("#pasw1").val()) {
				$("#dontmatch").fadeIn(200);
				allowPost = false;
			} else {$("#dontmatch").hide();allowPost = true;}
		});	
	});
	function submitForm() {
		if (!allowPost) return false;
		else $("#createUserForm form").submit();
	}
</script>
