<?php
include '../core/database.php';


$data = json_decode(file_get_contents("php://input"), true);

$email = $data['email'] ?? $_POST['email'] ?? '';
$password = $data['password'] ?? $_POST['password'] ?? '';
$fullname = $data['fullname'] ?? $_POST['fullname'] ?? '';

$action = $_GET['action'] ?? '';

$errors = [];


if(empty($email)){
    $errors['email'] = "Email required";
}
elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $errors['email'] = "Invalid email";
}
elseif(!preg_match("/^[a-zA-Z0-9._%+-]+@gmail\.com$/",$email)){
    $errors['email'] = "Only Gmail allowed";
}


if(empty($password)){
    $errors['password'] = "Password required";
}
elseif(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/",$password)){
    $errors['password'] = "Weak password";
}


if(!empty($errors)){
    echo json_encode([
        "status" => "error",
        "errors" => $errors
    ]);
    exit();
}

if($action == "register"){

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users(fullname,email,password)
            VALUES('$fullname','$email','$hashed')";

    if(mysqli_query($con,$sql)){
        echo json_encode([
            "status" => "success",
            "message" => "Registered successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Email already exists"
        ]);
    }
}


if($action == "login"){

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($con,$sql);
    $user = mysqli_fetch_assoc($result);

    if($user && password_verify($password,$user['password'])){
        echo json_encode([
            "status" => "success",
            "user" => [
                "id" => $user['id'],
                "name" => $user['fullname']
            ]
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid login"
        ]);
    }
}