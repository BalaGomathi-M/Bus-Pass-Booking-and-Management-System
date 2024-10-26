<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $sql = "SELECT ID FROM tbladmin WHERE UserName=:username and Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if($query->rowCount() > 0) {
        foreach ($results as $result) {
            $_SESSION['bpmsaid'] = $result->ID;
        }

        if(!empty($_POST["remember"])) {
            // COOKIES for username
            setcookie("user_login", $_POST["username"], time() + (10 * 365 * 24 * 60 * 60));
            // COOKIES for password
            setcookie("userpassword", $_POST["password"], time() + (10 * 365 * 24 * 60 * 60));
        } else {
            if(isset($_COOKIE["user_login"])) {
                setcookie("user_login", "");
                if(isset($_COOKIE["userpassword"])) {
                    setcookie("userpassword", "");
                }
            }
        }
        $_SESSION['login'] = $_POST['username'];
        echo "<script type='text/javascript'>document.location ='dashboard.php';</script>";
    } else {
        echo "<script>alert('Invalid Details');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Pass Booking & Management System | Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f5f5f5;
        }
        .login-container {
            max-width: 400px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .logo {
            margin-bottom: 20px;
            text-align: center;
        }
        .form-floating {
            margin-bottom: 15px;
        }
        .form-floating input {
            border-radius: 0.375rem;
        }
        .btn {
            width: 100%;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="logo">
            <h3>Bus Pass Booking & Management System</h3>
        </div>
        <form method="post" name="login">
            <div class="form-floating">
                <input type="text" class="form-control" id="floatingInput" placeholder="Username" name="username" value="<?php if(isset($_COOKIE["user_login"])) { echo $_COOKIE["user_login"]; } ?>" required>
                <label for="floatingInput">Username</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password" value="<?php if(isset($_COOKIE["userpassword"])) { echo $_COOKIE["userpassword"]; } ?>" required>
                <label for="floatingPassword">Password</label>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="remember" name="remember" <?php if(isset($_COOKIE["user_login"])) { ?> checked <?php } ?>>
                <label class="form-check-label" for="remember">Keep me signed in</label>
            </div>
            <button type="submit" class="btn btn-lg btn-primary" name="login">Login</button>
            <div class="mt-3 text-center">
                <a href="forgot-password.php">Lost Password?</a>
            </div>
            <div class="mt-3 text-center">
                <a href="../index.php">Back Home</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>
