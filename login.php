<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'fabs', 'totiloki');
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
session_start();

if(isset($_POST['cancel'])){
	header("Location: index.php");
	return;
}

$salt = 'XyZzy12*_';

if (isset($_POST['email']) and isset($_POST['pass'])){
	$check = hash('md5', $salt.$_POST['pass']); 
	$stmt = $pdo -> prepare('SELECT user_id, name FROM users WHERE email = :email AND password = :password');
	$stmt -> execute(array(':email' => $_POST['email'], ':password' => $check));
	$row = $stmt -> fetch(PDO::FETCH_ASSOC);

	if ($row !== false){
		$_SESSION['user_id'] = $row['user_id'];
		$_SESSION['name'] = $row['name'];
		$_SESSION['sucess'] = 'Access granted';
		header('Location: index.php');
		return;
	} else {
		$_SESSION['error'] = 'Incorrect password';
		header('Location: login.php');
		return;
	}
}
?>
<html>
<head>
	<title>Fabiola Antonella Chazarreta</title>
	<link rel="stylesheet" 
	    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
	    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
	    crossorigin="anonymous">

	<link rel="stylesheet" 
	    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
	    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
	    crossorigin="anonymous">

	<link rel="stylesheet" 
	    href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">

	<script
	  src="https://code.jquery.com/jquery-3.2.1.js"
	  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
	  crossorigin="anonymous"></script>

	<script
	  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
	  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
	  crossorigin="anonymous"></script>
</head>
<body>
	<div class="container">
		<h1>Please Log In</h1>
		<?php
		if (isset($_SESSION['error'])){
			echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
			unset($_SESSION['error']);
		}
		?>
		<form method="post" action="login.php">
			<label for="email">Email </label>
			<input type="text" name="email" id="email"><br/>
			<label for="password">Password </label>
			<input type="password" name="pass" id="password"><br/>
			<input type="submit" onclick="return doValidate();" value="Log In">
			<input type="submit" name="cancel" value="Cancel">
		</form>
		<p>
		For a password hint, view source and find a password hint
		in the HTML comments.
		<!-- Hint: The password is the three character name of the
		programming language used in this class (all lower case)
		followed by 123. -->
		</p>


	</div>
</body>
<script>
function doValidate() {
    console.log('Validating...');
    try {
        addr = document.getElementById('email').value;
        pw = document.getElementById('password').value;
        console.log("Validating addr="+addr+" pw="+pw);
        if (addr == null || addr == "" || pw == null || pw == "") {
            alert("Both fields must be filled out");
            return false;
        }
        if ( addr.indexOf('@') == -1 ) {
            alert("Invalid email address");
            return false;
        }
        return true;
    } catch(e) {
        return false;
    }
    return false;
}
</script>

</html>