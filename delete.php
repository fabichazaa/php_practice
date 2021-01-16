<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'fabs', 'totiloki');
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
session_start();
if (!isset($_SESSION['name'])){
	die('ACCESS DENIED');
} 
if(isset($_POST['cancel'])){
	header('Location: index.php');
	return;
}

if (isset($_POST['profile_id']) and isset($_POST['Delete'])){
	$sql = 'DELETE FROM profile WHERE profile_id = :profile_id';
	$stmt = $pdo -> prepare($sql);
	//WHEN DELETING/EDITING USE THE POST VALUE!!! 
	$stmt -> execute(array(':profile_id' => $_POST['profile_id']));
	$_SESSION['sucess'] = 'Profile deleted';
	header('Location: index.php');
	return;
}


$stmt = $pdo -> prepare('SELECT * FROM profile WHERE profile_id = :p_id');
$stmt -> execute(array(':p_id' => htmlentities($_GET['profile_id'])));
$row =  $stmt -> fetch(PDO::FETCH_ASSOC);
if($row === false){
	$_SESSION['error'] = 'Could not load profile';
	header('Location: index.php');
	return;
} 

?>
<!DOCTYPE html>
<html>
<head>
<title>Fabiola Antonella Chazarreta</title>
<!-- bootstrap.php - this is HTML -->

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
    crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
    crossorigin="anonymous">

</head>
	<body>
		<div class="container">
			<h1>Deleteing Profile</h1>
			<form method="post" action="delete.php">
				<p>First Name: <?= htmlentities($row['first_name']) ?></p>
				<p>Last Name: <?= htmlentities($row['last_name']) ?></p>
				<input type="hidden" name="profile_id" value= <?= '"'.htmlentities($_GET['profile_id']).'"' ?> />
				<input type="submit" name="Delete" value="Delete">
				<input type="submit" name="cancel" value="Cancel">
				</p>
			</form>
		</div>
	</body>
</html>
