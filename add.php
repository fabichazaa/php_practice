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

if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
	if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1 ){
		$_SESSION['error'] = "All fields are required";
		header("Location: add.php");
		return;
	} else if ( strpos($_POST['email'],'@') <= 0){
		$_SESSION['error'] = 'Email address must contain @';
		header('Location: add.php');
		return;	
	} else {
	$sql = "INSERT INTO profile (user_id, first_name, last_name, email, headline, summary) VALUES (:user_id, :first_name, :last_name, :email, :headline, :summary)";
	$stmt = $pdo-> prepare($sql);
	$stmt -> execute(array(
		':user_id' => $_SESSION['user_id'],
		':first_name' => $_POST['first_name'],
		':last_name' => $_POST['last_name'],
		':email' => $_POST['email'],
		':headline' => $_POST['headline'],
		':summary' => $_POST['summary']));
	$_SESSION['success'] = 'Profile added';
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
		<h1>Adding Profile for <?= htmlentities($_SESSION['name']) ?> </h1>
<?php 
if (isset($_SESSION['error'])){
echo '<p style="color: red">'.$_SESSION['error']."</p>\n";
unset($_SESSION['error']);
}
?>
		<form method="post">
			<p>First Name:
			<input type="text" name="first_name" size="60"/></p>
			<p>Last Name:
			<input type="text" name="last_name" size="60"/></p>
			<p>Email:
			<input type="text" name="email" size="30"/></p>
			<p>Headline:<br/>
			<input type="text" name="headline" size="80"/></p>
			<p>Summary:<br/>
			<textarea name="summary" rows="8" cols="80"></textarea>
			<p>
			<input type="submit" value="Add">
			<input type="submit" name="cancel" value="Cancel">
			</p>
		</form>
	</div>
</body>
</html>