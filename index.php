<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'fabs', 'totiloki');
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
session_start();
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
<h1>Fabiola Chazarreta's Resume Registry</h1>
<?php
if (isset($_SESSION['success'])){
    echo '<p style="color: green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])){
echo '<p style="color: red">'.$_SESSION['error']."</p>\n";
unset($_SESSION['error']);
}

if (!isset($_SESSION['name'])){
  echo '<p><a href="login.php">Please log in</a></p>'."\n";
} else {
	echo '<p><a href="logout.php">Logout</a></p>'."\n";
    echo '<p><a href="add.php">Add New Entry</a></p>'."\n";
}

$stmt_0 = $pdo -> query("SELECT COUNT(*) FROM profile");
$row = $stmt_0 -> fetch(PDO::FETCH_ASSOC);
$size = $row['COUNT(*)'];

if ($size == 0){
	echo '<p> No rows found </p>';
  	echo "\n";
} else {
	echo '<table border="1">';
	echo '<thead><tr>';
	echo '<th>Name</th>';
	echo '<th>Headline</th>';
if (isset($_SESSION['user_id'])){
	echo '<th>Action</th>';
}
echo '</tr></thead>';
$stmt = $pdo -> query ("SELECT * FROM profile");
while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
	echo '<tr>';
	echo '<td>'.'<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name']).' '.htmlentities($row['last_name']).' </a>'.'</td>'."\n";
	echo '<td>'.htmlentities($row['headline']).'</td>'."\n";
	if (isset($_SESSION['user_id'])){
	echo '<td><a href="edit.php?profile_id='.htmlentities( $row['profile_id']).'">Edit</a> / <a href="delete.php?profile_id='.htmlentities( $row['profile_id']).'"">Delete </a></td>';
	}
}
    echo '</tr></table>';
}
?>
</ul>

</div>
</body>