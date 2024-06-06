<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Define and assign the API key
$apiKey = "94178f7"; // Replace "YOUR_API_KEY" with your actual OMDB API key

// Function to fetch movie data
function getMovieData($title, $apiKey) {
    $url = "http://www.omdbapi.com/?apikey={$apiKey}&t=" . urlencode($title);
    
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    
    return json_decode($response, true);
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .poster {
            max-width: 150px; /* Adjust the max-width to your desired size */
            height: auto;
        }
    </style>
</head>
<body>
<?php include "navbar.php"; ?>
<div class="container mt-5">
    <h1>My Movie List</h1>
    <div class="row">
        <?php if (!empty($_SESSION['movie_lists'][$_SESSION['username']])): ?>
            <?php foreach ($_SESSION['movie_lists'][$_SESSION['username']] as $movie): ?>
                <?php $movieData = getMovieData($movie, $apiKey); ?>
                <div class="col-md-6">
                    <div class="card mt-3">
                        <div class="card-header">
                            <?php if (isset($movieData['Title'])): ?>
                                <h5 class="card-title"><?php echo htmlspecialchars($movieData['Title']); ?></h5>
                            <?php else: ?>
                                <h5 class="card-title">Title Not Available</h5>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <?php if (isset($movieData['Poster'])): ?>
                                <img src="<?php echo htmlspecialchars($movieData['Poster']); ?>" class="poster" alt="Movie Poster">
                            <?php else: ?>
                                <p class="card-text">Poster Not Available</p>
                            <?php endif; ?>
                            <?php if (isset($movieData['Plot'])): ?>
                                <p class="card-text"><?php echo htmlspecialchars($movieData['Plot']); ?></p>
                            <?php else: ?>
                                <p class="card-text">Plot Not Available</p>
                            <?php endif; ?>
                            <?php if (isset($movieData['Year'])): ?>
                                <p class="card-text">Year: <?php echo htmlspecialchars($movieData['Year']); ?></p>
                            <?php else: ?>
                                <p class="card-text">Year Not Available</p>
                            <?php endif; ?>
                            <?php if (isset($movieData['Director'])): ?>
                                <p class="card-text">Director: <?php echo htmlspecialchars($movieData['Director']); ?></p>
                            <?php else: ?>
                                <p class="card-text">Director Not Available</p>
                            <?php endif; ?>
                            <!-- Add more details as needed -->
                        </div>
                    </div>
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

