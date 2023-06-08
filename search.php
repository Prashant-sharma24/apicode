<?php
// Pixabay API configuration
$pixabayApiKey = '21124213-9bb9be36529b78ecadd0d9d3e';

// Function to make a request to the Pixabay API
function searchPixabay($query, $type = 'photo') {
    global $pixabayApiKey;
    
    // Set the API endpoint URL
    $url = "https://pixabay.com/api/?key=$pixabayApiKey&q=" . urlencode($query) . "&image_type=$type";
    
    // Make the request to the Pixabay API
    $response = file_get_contents($url);
    
    // Decode the JSON response
    $data = json_decode($response, true);
    
    // Return the search results
    return $data['hits'];
}

// Check if the search form is submitted
if (isset($_POST['search'])) {
    // Get the search query from the form
    $query = $_POST['query'];
    
    // Perform the search using Pixabay API
    $results = searchPixabay($query);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Search</h2>
        
        <!-- Search form -->
        <form method="POST" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" name="query" placeholder="Enter your search query" required>
                <div class="input-group-append">
                    <button type="submit" name="search" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>
        
        <?php if (isset($results)) : ?>
            <?php if (empty($results)) : ?>
                <p>No results found.</p>
            <?php else : ?>
                <div class="row">
                    <?php foreach ($results as $result) : ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card">
                                <img src="<?php echo $result['webformatURL']; ?>" class="card-img-top" alt="<?php echo $result['tags']; ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $result['tags']; ?></h5>
                                    <a href="<?php echo $result['pageURL']; ?>" class="btn btn-primary" target="_blank">View</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
