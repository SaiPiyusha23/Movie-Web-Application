<?php
// Start the session
session_start();

// Check if the user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>
<div class="container mt-5">
    <h1>My Movie List</h1>
    <div class="list-group">
        <?php if (!empty($_SESSION['movie_list'])): ?>
            <?php foreach ($_SESSION['movie_list'] as $movie): ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo htmlspecialchars($movie); ?>
                    <form method="POST" action="welcome.php" class="m-0">
                        <input type="hidden" name="movie_to_remove" value="<?php echo htmlspecialchars($movie); ?>">
                        <button type="submit" class="btn btn-danger btn-sm" name="remove_from_list">Remove</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Your movie list is empty.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>