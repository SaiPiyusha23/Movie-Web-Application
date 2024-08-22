<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// OMDB API key
$apiKey = "94178f7"; // Replace this with your actual OMDB API key

// Initialize the movie list for the logged-in user if not already set
if (!isset($_SESSION['movie_lists'][$_SESSION['username']])) {
    $_SESSION['movie_lists'][$_SESSION['username']] = [];
}

// Function to fetch movie data
function getMovieData($title, $apiKey) {
    $url = "http://www.omdbapi.com/?apikey={$apiKey}&t=" . urlencode($title);
    
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    
    return json_decode($response, true);
}

$movieData = null;
if (isset($_POST['search'])) {
    $movieTitle = $_POST['movie'];
    $movieData = getMovieData($movieTitle, $apiKey);
}

// Handle adding movie to the list
if (isset($_POST['add_to_list'])) {
    $movieToAdd = $_POST['movie_to_add'];
    if (!in_array($movieToAdd, $_SESSION['movie_lists'][$_SESSION['username']])) {
        $_SESSION['movie_lists'][$_SESSION['username']][] = $movieToAdd;
    }
}

// Handle removing movie from the list
if (isset($_POST['remove_from_list'])) {
    $movieToRemove = $_POST['movie_to_remove'];
    if (($key = array_search($movieToRemove, $_SESSION['movie_lists'][$_SESSION['username']])) !== false) {
        unset($_SESSION['movie_lists'][$_SESSION['username']][$key]);
        $_SESSION['movie_lists'][$_SESSION['username']] = array_values($_SESSION['movie_lists'][$_SESSION['username']]); // Reindex array
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>
<div class="container mt-5">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h1>
    <div class="d-flex justify-content-end">
        <a href="mylist.php" class="btn btn-secondary">My List</a>
    </div>
    <form method="POST" action="welcome.php" class="mt-4">
        <div class="mb-3">
            <label for="movie" class="form-label">Search for a movie:</label>
            <input type="text" class="form-control" id="movie" name="movie" required>
        </div>
        <button type="submit" class="btn btn-primary" name="search">Search</button>
    </form>
    
    <?php if ($movieData): ?>
        <?php if ($movieData['Response'] === 'True'): ?>
            <div class="mt-5 movie-details">
                <div class="row">
                    <div class="col-md-6">
                        <h2>Movie Details</h2>
                        <p><strong>Title:</strong> <?php echo htmlspecialchars($movieData['Title']); ?></p>
                        <p><strong>Year:</strong> <?php echo htmlspecialchars($movieData['Year']); ?></p>
                        <p><strong>Released:</strong> <?php echo htmlspecialchars($movieData['Released']); ?></p>
                        <p><strong>Runtime:</strong> <?php echo htmlspecialchars($movieData['Runtime']); ?></p>
                        <p><strong>Genre:</strong> <?php echo htmlspecialchars($movieData['Genre']); ?></p>
                        <p><strong>Director:</strong> <?php echo htmlspecialchars($movieData['Director']); ?></p>
                        <p><strong>Actors:</strong> <?php echo htmlspecialchars($movieData['Actors']); ?></p>
                        <p><strong>Plot:</strong> <?php echo htmlspecialchars($movieData['Plot']); ?></p>
                        <p><strong>Language:</strong> <?php echo htmlspecialchars($movieData['Language']); ?></p>
                        <p><strong>Awards:</strong> <?php echo htmlspecialchars($movieData['Awards']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h2>Movie Poster</h2>
                        <?php if (isset($movieData['Poster'])): ?>
                            <img src="<?php echo htmlspecialchars($movieData['Poster']); ?>" alt="Movie Poster" class="img-fluid">
                        <?php else: ?>
                            <p class="text-center">Poster Not Available</p>
                        <?php endif; ?>
                    </div>
                </div>
                <form method="POST" action="welcome.php">
                    <input type="hidden" name="movie_to_add" value="<?php echo htmlspecialchars($movieData['Title']); ?>">
                    <button type="submit" class="btn btn-success" name="add_to_list">Add to List</button>
                </form>
            </div>
        <?php else: ?>
            <div class="mt-5">
                <h2>No results found for "<?php echo htmlspecialchars($movieTitle); ?>"</h2>
                <p><?php echo htmlspecialchars($movieData['Error']); ?></p>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
