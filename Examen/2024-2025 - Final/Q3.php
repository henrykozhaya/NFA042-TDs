<?php
// Code de la page jobPosting.php
// 1 point pour la clarté des erreurs produites
$error = [];
if($_SERVER["REQUEST_METHOD"] == "POST"){ // 0.5 point
    if(
        isset($_POST["name"])
        && isset($_POST["email"])
        && isset($_POST["position"])
        && isset($_POST["expectedSalary"])
        && isset($_FILES["cv"])
        && !empty($_POST["name"])
        && !empty($_POST["email"])
        && !empty($_POST["position"])
        && !empty($_POST["expectedSalary"])
        && $_FILES["cv"]["error"] == 0
    ) // 0.5 point
    {
        extract($_POST);

        $pattern = "/^[a-zA-Z\s]{3,}$/"; // 0.5 point
        if(!preg_match($pattern, $name)){ // 0.5 point
            $error["name"] = "The name should contain only letters with a minimum length of 3";    
        }
        $name = ucfirst(strtolower($name)); // 0.5 point 
        

        $email = filter_var($email, FILTER_SANITIZE_EMAIL); 
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ // 0.5 point
            $error["email"] = "The mail is not valid";    
        }

        if(!in_array($position, ['developer', 'designer', 'project-manager'])){ // 0.5 point
            $error["position"] = "The position is not valid";    
        }

        $expectedSalary = intval($expectedSalary);
        if($expectedSalary <= 0){ // 0.5 point
            $error["expectedSalary"] = "The expected salary should be a positive integer";
        }

        if($_FILES["cv"]["type"] != 'application/pdf'){ // 0.5 point
            $error["cv"] = "CV should be in PDF format";
        }

        if($_FILES["cv"]["size"] > (5 * 1024 * 1024)){ // 0.5 point
            $error["cv"] = "CV should be less than 5Mo";
        }

        
        $extension = pathinfo($_FILES["cv"]["name"], PATHINFO_EXTENSION); 
        $filename = uniqid() . "." . $extension; 
        $path = "/cv/";
        $fullFilename = $path . $filename; // 0.5 point pour le nom du fichier
        if(!move_uploaded_file($_FILES["cv"]["tmp_name"], $fullFilename)){ // 0.5 pour move_uploaded_file
            $error["file_upload"] = "Error uploding the CV";
        };

        $conn = getPDOConnection();
        $sql = "
        INSERT INTO job_application (id, name, email, position, expected_salary, cv, date_applied) 
        VALUES (NULL, :name, :email, :position, :expected_salary, :cv, NOW()) 
        "; // 1 point
        
        // 1 point pour le code ci-dessous
        try{
            $stmt->prepare($sql);
            $stmt->bindParam("name", $name, PDO::PARAM_STR);
            $stmt->bindParam("email", $email, PDO::PARAM_STR);
            $stmt->bindParam("position", $position, PDO::PARAM_STR);
            $stmt->bindParam("expected_salary", $expectedSalary, PDO::PARAM_STR); // PDO::PARAM_INT is also correct
            $stmt->bindParam("cv", $filename, PDO::PARAM_STR);
            $stmt->execute();
        }
        catch(PDOException $e){
            $error["saving_information"] = "Database Error " . $e->getMessage(); 
        }
    }
}

// 1 point
if(count($error) > 0){
    session_start();
    $_SESSION["error"] = $error;
    header('location:jobPosting.php');
}
else{
    header('location:result.php');
}