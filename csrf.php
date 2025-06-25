<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_SESSION["csrf_token"]) && $_POST["csrf_token"] === $_SESSION["csrf_token"]){
        $username = $_POST['username'];
        $password = $_POST['password'];
    
        die(json_encode(array(
            'username' => $username,
            'password' => $password
        )));
    } else {
        die("Permission denied");
    }

}
else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="csrf.php" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="text" name="username" placeholder="Username" required> 
        <br>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
<?php
}
?>