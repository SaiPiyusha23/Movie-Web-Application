<?php
if (isset($_POST['submit'])) {
    include "connection.php";
    $username = mysqli_real_escape_string($conn, $_POST['user']);
    $password = mysqli_real_escape_string($conn, $_POST['pass']);

    // Check if the username or email exists in the database
    $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if ($row) {
            // Verify the password
            if (password_verify($password, $row["password"])) {
                // Start the session
                session_start();

                // Store user data in session variables
                $_SESSION["loggedin"] = true;
                $_SESSION["username"] = $username;

                // Redirect to welcome.php
                header("Location: welcome.php");
                exit();
            } else {
                echo '<script>
                        alert("Invalid username/email or password!!");
                        window.location.href = "login.php";
                     </script>';
            }
        } else {
            echo '<script>
                    alert("Invalid username/email or password!!");
                    window.location.href = "login.php";
                 </script>';
        }
    } else {
        echo '<script>
                alert("Database query failed!!");
                window.location.href = "login.php";
             </script>';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
include "navbar.php";
?>
<div id="form">
    <h1>Login Form</h1>
    <form name="form" action="login.php" method="POST">
        <label>Enter Username/Email</label>
        <input type="text" id="user" name="user" required><br><br>
        <label>Enter Password</label>
        <input type="password" id="pass" name="pass" required><br><br>
        <input type="submit" id="btn" value="Login" name="submit"/>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
