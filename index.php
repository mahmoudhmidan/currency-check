<?php
# Initialize the session
session_start();

# If user is not logged in then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
  echo "<script>" . "window.location.href='./login.php';" . "</script>";
  exit;
}
?>
<?php
if (isset($_SESSION['email'])) {
    header('Location: login.php');
}

$api_key = 'b8c391d8-3af6-441e-b3c9-7672385630a4';

if (isset($_GET['coin'])) {
    $coin = $_GET['coin'];
    $url = "https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?symbol=$coin";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-CMC_PRO_API_KEY: '.$api_key));
    $result = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($result, true);

    if (isset($data['data'][$coin])) {
        $name = $data['data'][$coin]['name'];
        $rank = $data['data'][$coin]['cmc_rank'];
        $price = $data['data'][$coin]['quote']['USD']['price'];
        $change24h = $data['data'][$coin]['quote']['USD']['percent_change_24h'];

        $result = "<h2>$name ($coin) - Rank #$rank</h2>";
        $result .= "<p>Price: $$price</p>";
        $result .= "<p>24h Change: $change24h%</p>";
    } else {
        $result = "<p>No results found.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>search page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/main.css">
 
  <style>
    .result-container {
  border: 1px solid #ccc;
  padding: 10px;
  margin: 20px;
  text-align: center;
  font-size:1.5rem;
}
html, body {
    height: 100%;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

/* Optional: Add some spacing and styling */
.form-container {
    text-align: center;
    margin-bottom: 20px;
}

h1 {
    text-align: center;
    margin-bottom: 20px;
}

form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.result-container {
    text-align: center;
}
</style>
</head>

<body>
  <div class="container">
    <div class="alert alert-success my-5">
      Welcome ! You are now signed in to your account.
    </div>
    <!-- User profile -->
    <div class="row justify-content-end">
  <div class="col-lg-5 text-center">
    <img src="./img/blank-avatar.jpg" class="img-fluid rounded" alt="User avatar" width="50">
    <h4 class="my-2">Hello, <?= htmlspecialchars($_SESSION["username"]); ?></h4>
    <a href="./logout.php" class="btn btn-primary">Log Out</a>
  </div>
</div>
    </div>
  </div>
  <div class="form-container"><img src="image/cashrich photos.png" alt=""><h2>Cash<span>Rich</span></h2></div>

    <h1>Search Coin</h1>
    <form>
        <label for="coin">Coin:</label>
        <input type="text" id="coin" name="coin" placeholder="Enter a coin symbol">
        <button type="submit" id="search-btn">Search</button>
    </form>

    <div class="result-container">
        <?php if (isset($_GET['coin'])): ?>
            <?php echo $result; ?>
        <?php else: ?>
            <p>Search for a coin to see its details.</p>
        <?php endif; ?>
    </div>
</body>

</html>