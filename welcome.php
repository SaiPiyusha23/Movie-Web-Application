<?php
// Start the session
session_start();

// Check if the user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// OMDB API key
$apiKey = "94178f7"; // Replace this with your actual OMDB API key

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
<?php
include "navbar.php";
?>
<div class="container mt-5">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h1>
    <form method="POST" action="welcome.php" class="mt-4">
        <div class="mb-3">
            <label for="movie" class="form-label">Search for a movie:</label>
            <input type="text" class="form-control" id="movie" name="movie" required>
        </div>
        <button type="submit" class="btn btn-primary" name="search">Search</button>
    </form>
    
    <?php if ($movieData): ?>
        <?php if ($movieData['Response'] === 'True'): ?>
            <div class="mt-5">
                <h2>Movie Details</h2>
                <p><strong>Title:</strong> <?php echo htmlspecialchars($movieData['Title']); ?></p>
                <p><strong>Year:</strong> <?php echo htmlspecialchars($movieData['Year']); ?></p>
                <p><strong>Rated:</strong> <?php echo htmlspecialchars($movieData['Rated']); ?></p>
                <p><strong>Released:</strong> <?php echo htmlspecialchars($movieData['Released']); ?></p>
                <p><strong>Runtime:</strong> <?php echo htmlspecialchars($movieData['Runtime']); ?></p>
                <p><strong>Genre:</strong> <?php echo htmlspecialchars($movieData['Genre']); ?></p>
                <p><strong>Director:</strong> <?php echo htmlspecialchars($movieData['Director']); ?></p>
                <p><strong>Writer:</strong> <?php echo htmlspecialchars($movieData['Writer']); ?></p>
                <p><strong>Actors:</strong> <?php echo htmlspecialchars($movieData['Actors']); ?></p>
                <p><strong>Plot:</strong> <?php echo htmlspecialchars($movieData['Plot']); ?></p>
                <p><strong>Language:</strong> <?php echo htmlspecialchars($movieData['Language']); ?></p>
                <p><strong>Country:</strong> <?php echo htmlspecialchars($movieData['Country']); ?></p>
                <p><strong>Awards:</strong> <?php echo htmlspecialchars($movieData['Awards']); ?></p>
                <p><strong>Poster:</strong><br><img src="<?php echo htmlspecialchars($movieData['Poster']); ?>" alt="Poster"></p>
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
