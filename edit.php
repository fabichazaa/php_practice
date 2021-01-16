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

$stmt = $pdo -> prepare('SELECT * FROM profile WHERE profile_id = :p_id');
$stmt -> execute(array(':p_id' => $_GET['profile_id']));
$row =  $stmt -> fetch(PDO::FETCH_ASSOC);
if($row === false){
	$_SESSION['error'] = 'Could not load profile';
	header('Location: index.php');
	return;
} else if($row['user_id'] != $_SESSION['user_id']){
	$_SESSION['error'] = 'Unable to edit this profile';
	header('Location: index.php');
	return;
}


if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
	if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1 ){
		$_SESSION['error'] = "All fields are required";
		header("Location: edit.php?profile_id=".$_POST['profile_id']);
		return;
	} else if ( strpos($_POST['email'],'@') <= 0){
		$_SESSION['error'] = 'Email address must contain @';
		header("Location: edit.php?profile_id=".$_POST['profile_id']);
		return;	
	} else {
	$sql = "UPDATE profile SET user_id = :user_id, first_name = :first_name, last_name = :last_name, email = :email, headline = :headline, summary = :summary WHERE profile_id = :profile_id";
	$stmt = $pdo-> prepare($sql);
	$stmt -> execute(array(
		':user_id' => $_SESSION['user_id'],
		':first_name' => $_POST['first_name'],
		':last_name' => $_POST['last_name'],
		':email' => $_POST['email'],
		':headline' => $_POST['headline'],
		':summary' => $_POST['summary'],
		':profile_id' => $_POST['profile_id']));
	$_SESSION['success'] = 'Profile updated';
	header('Location: index.php');
	return;
	}
} 


?>

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
		<h1>Editing Profile for <?= htmlentities($_SESSION['name']) ?> </h1>
<?php 
if (isset($_SESSION['error'])){
echo '<p style="color: red">'.$_SESSION['error']."</p>\n";
unset($_SESSION['error']);
}
?>
		<form method="post">
			<p>First Name:
			<input type="text" name="first_name" size="60" value=<?= htmlentities($row['first_name']) ?> /></p>
			<p>Last Name:
			<input type="text" name="last_name" size="60" value=<?= htmlentities($row['last_name']) ?> /></p>
			<p>Email:
			<input type="text" name="email" size="30" value= <?= htmlentities($row['email']) ?> /></p>
			<p>Headline:<br/>
			<input type="text" name="headline" size="80" value= <?= htmlentities($row['headline']) ?> /></p>
			<p>Summary:<br/>
			<textarea name="summary" rows="8" cols="80"><?= htmlentities($row['summary'])?></textarea>
			<p>
			<input type="hidden" name='profile_id' value= <?= '"'.$_GET['profile_id'].'"' ?> />
			<input type="submit" value="Save">
			<input type="submit" name="cancel" value="Cancel">
			</p>
		</form>
	</div>
</body>
</html>