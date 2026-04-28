<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>log in page</title>
</head>
<body>
    <form action="login.php" method="post">
        
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" name="login" value="Login">
    </form>
    
</body>
</html>
<?php
$userErr =  $passwordErr = "";
$user =  $password = "";

function cleanInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $userErr = "Username is required";
    } else {
        $user = cleanInput($_POST["username"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $user)) {
            $userErr = "Only letters and white space allowed";
        }
    }

    if (empty($_POST["lname"])) {
        $lnameErr = "Last name is required";
    } else {
        $lname = cleanInput($_POST["lname"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $lname)) {
            $lnameErr = "Only letters and white space allowed";
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = cleanInput($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    if (empty($_POST["number"])) {
        $numberErr = "Contact number is required";
    } else {
        $number = cleanInput($_POST["lname"]);
        if (!preg_match("/^[0-9' ]*$/", $number)) {
            $numberErr = "Only numbers are allowa";
        }
    }

    if (empty($_POST["password"])) {
      if (strlen($_POST["password"]) < 8) {
            $passwordErr = "Password must be at least 8 characters long";
        } else {
            $password = cleanInput($_POST["password"]);
        }
        $passwordErr = "Password is required";
    } 
        }

if (empty($fnameErr) && empty($lnameErr) && empty($emailErr) && empty($numberErr) && empty($passwordErr)) {
    echo "First Name: {$fname} <br>";
    echo "Last Name: {$lname} <br>";
    echo "Email: {$email} <br>";
    echo "Contact Number: {$number} <br>";
    echo "Password: {$password} <br>";
} else {
    echo $fnameErr . "<br>" . $lnameErr . "<br>" . $emailErr . "<br>" . $numberErr . "<br>" . $passwordErr;
}
        