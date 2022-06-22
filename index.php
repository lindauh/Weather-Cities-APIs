<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

require_once("config.php");


try {
    $conn = new PDO("mysql:host=$servername; dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);         // set the PDO error mode to exception
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="utf-8">
    <title>Assignment 7</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <header>
        <h1>City information</h1>
    </header>

    <div class="container">
    <div id="div_ahref"><a href="statistics.php">Visit statistics</a></div>

        <div class="form-group">
            <label for="city-input">Write city you want to check weather for:</label><br>
            <input id="city-input" class="form-control controls" type="text" placeholder="Bratislava" />
            <div class="formButtons"><input type="submit" id="button" class="btn btn-dark" value="Search"></div>
        </div>

        <div>
            <div id="weather"></div>
            <div id="windDiv"></div>
            <div id="pressureDiv"></div>
            <div id="temperatureDiv"></div>
            <br>
            <div id="lat"></div>
            <div id="lon"></div>
            <div id="country"></div>
            <div id="capital_city"></div>
            <pre id="resultDiv"></pre>
        </div>
    </div>

    <script src="countries.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=API-KEY&callback=initAutocomplete&libraries=places&v=weekly" async></script>
</body>

</html>