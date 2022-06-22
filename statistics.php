<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

require_once("config.php");

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/js/bootstrap.min.js" integrity="sha512-28e47INXBDaAH0F91T8tup57lcH+iIqq9Fefp6/p+6cgF7RKnqIMSmZqZKceq7WWo9upYMBLMYyMsFq7zHGlug==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <header>
        <h1>Visit statistics</h1>
    </header>

    <div class="content">
        <div id="div_ahref"><a href="index.php">Main page</a></div>

        <div class="container">
            <table id="result-table" class="table">
                <thead>
                    <tr>
                        <th>State</th>
                        <th>Number of visits</th>
                        <th>Flag</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT DISTINCT state FROM visits";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $distinctStates = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $sql = "SELECT DISTINCT code FROM visits";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $stateCodes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $num = 0;
                    foreach ($distinctStates as $item) {
                        $sql = "SELECT COUNT(*) FROM visits WHERE state = (?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([$item["state"]]);
                        $visitsCounts = $stmt->fetch(PDO::FETCH_ASSOC);

                        //var_dump($visitsCounts);

                        $imageSrc = 'https://www.geonames.org/flags/x/' . strtolower($stateCodes[$num]['code']) . '.gif';

                        echo "<tr>
                                <td><button type='button' class='btn btn-link' data-bs-toggle='modal' data-bs-target='#citiesModal'>".$item['state']."</button></td>
                                <td id='td2'>{$visitsCounts["COUNT(*)"]}</td>
                                <td id='td3'><img src=$imageSrc border=1 height=40 width=60></img></td>
                            </tr>";

                        $num++;
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Modalne okno navstevy z miest-->
        <div class="modal hide fade" id="citiesModal" aria-labelledby="citiesModalLabel" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="citiesModalLabel">Cities visits info</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <table id="result-table" class="table">
                            <thead>
                                <tr>
                                    <th class="text-center">State</th>
                                    <th class="text-center">City</th>
                                    <th class="text-center">Number of visits</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sql = "SELECT DISTINCT city, state FROM visits";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $allCities = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    foreach ($allCities as $item) {
                                        $sql = "SELECT COUNT(*) FROM visits WHERE city = (?)";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute([$item["city"]]);
                                        $visitsCounts = $stmt->fetch(PDO::FETCH_ASSOC);

                                        echo "<tr>
                                                <td id='td1'>" . $item["state"] . "</td>
                                                <td id='td1'>" . $item["city"] . "</td>
                                                <td id='td2'>{$visitsCounts["COUNT(*)"]}</td>
                                            </tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="mapDiv"></div>
    </div>
    <script>
        // https://developers.google.com/maps/documentation/javascript/markers#maps_icon_simple-javascript
        function initMap() {
            const myLatLng = {
                lat: 48.148598,
                lng: 17.107748
            };
            const map = new google.maps.Map(document.getElementById("mapDiv"), {
                zoom: 3,
                center: myLatLng,
            });

            fetch("visits_solver.php", {
                method: "GET"
            }).then(response => response.json()).then(result => {
                result.forEach(result => {

                    var lat = result['latitude']
                    var long = result['longitude']

                    new google.maps.Marker({
                        position: new google.maps.LatLng(lat, long),
                        map: map
                    });
                })
            })
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" integrity="sha512-n/4gHW3atM3QqRcbCn6ewmpxcLAHGaDjpEBu4xZd47N0W2oQ+6q7oc3PXstrJYXcbNU1OHdQ1T7pAP+gi5Yu8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=API-KEY&callback=initMap&libraries=places&v=weekly" async=""></script>
</body>

</html>


<?php
