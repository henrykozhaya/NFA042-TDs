<?php
if($_SERVER["REQUEST_METHOD"] != "POST"){
    http_response_code(405);
    die("Method not allowed");
}

function getConnection(){
    try {
        $conn = new PDO("mysql:host=".MYSQL_SERVERNAME.";dbname=".MYSQL_DBNAME, MYSQL_USERNAME, MYSQL_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
    catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        return null;
    }
}

function isValidPassword($password) {
    $passLength = strlen($password) >= 8;
    $uppercase    = preg_match('@[A-Z]@', $password);
    $lowercase    = preg_match('@[a-z]@', $password);
    $number       = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    return $passLength && $uppercase && $lowercase && $number && $specialChars;
}

session_start();
$error = [];

if
(
    isset($_POST["name"]) && !empty($_POST["name"])
    && isset($_POST["email"]) && !empty($_POST["email"])
    && isset($_POST["password"]) && !empty($_POST["password"])
    && isset($_POST["confirm"]) && !empty($_POST["confirm"])
)
{
    extract($_POST);
    $name = htmlspecialchars(trim($name));
    if(strlen($name) < 3){
        $error[] = ["name", "Invalid name length"];
    }
    else{
        $name = ucfirst(strtolower($name));
    }

    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error[] = ["email", "Invalid email format"];
    }
    
    if(!isValidPassword($password)){
        $error[] = ["password", "Invalid password format"];
    }
    else if($password !== $confirm){
        $error[] = ["confirm", "Passwords do not match"];
    }

    if(!empty($error)){
        $_SESSION["error"] = $error;
        header("Location: register.php");
        die();
    }
    else{
        $con = getConnection();
        $stmt = $con->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();
        if($stmt->rowCount() > 0){
            $_SESSION["error"] = ["email", "Email already exists"];
            header("Location: register.php");
            die();
        }
        else{
            $id = uniqid();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (id, name, email, password, created_at) VALUES (:id, :name, :email, :password, now())";
            $stmt = $con->prepare($query);
            $stmt->bindParam("id", $id, PDO::PARAM_STR);
            $stmt->bindParam("name", $name, PDO::PARAM_STR);
            $stmt->bindParam("email", $email, PDO::PARAM_STR);
            $stmt->bindParam("password", $hashedPassword, PDO::PARAM_STR);
            
            $stmt->execute();
            if($stmt->rowCount() > 0){
                $_SESSION["success"] = "Registration successful";
                header("Location: login.php");
                die();
            }
            else{
                $_SESSION["error"] = ["general", "Registration failed"];
                header("Location: register.php");
                die();
            }
        }
    }
}
else
{
    http_response_code(400);
    die("Bad request");
}