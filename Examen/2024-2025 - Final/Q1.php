<?php
// Code de la page checkLogin.php
session_start();
$error = null;
if($_SERVER["REQUEST_METHOD"] == "POST"){ // Request Method Validation: 0.5 point
    if(
        isset($_POST["email"])
        && isset($_POST["password"])
        && !empty($_POST["email"])
        && !empty($_POST["password"])
    ){ // Post Validation: 0.5 point
        extract($_POST);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){ // Email Validation: 0.5 point
            $conn = getPDOConnection();
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam("email", $email, PDO::PARAM_STR);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                $user = $stmt->fetch(PDO::FETCH_ASSOC); // Query, BindParam, Execute, RowCount: 1 point
                if(password_verify($password, $user["password"])){ // Use of password_verify: 0.5 point
                    $_SESSION["user"]["id"] = $user["id"];
                    $_SESSION["user"]["name"] = $user["name"];
                    $_SESSION["user"]["email"] = $user["email"];
                    header("location:index.php"); // Redirection: 0.5 point
                }
                else{
                    $error = "Invalid Credentials"; 
                }                
            }
            else{
                $error = "Invalid Credentials";
            }
        }
        else{
            $error = "Invalid Request";
        }
    }
    else{
        $error = "Bad Request";
    }
}
else{ 
    // 0.5 point
    http_response_code(405); 
    die("Invalid Request Method"); 
}
$_SESSION["error"] = $error; // Error Reporting: 0.5 point
header("location:login.php");// Redirection: 0.5 point