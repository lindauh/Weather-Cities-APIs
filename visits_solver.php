<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

require_once("config.php");


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);         // set the PDO error mode to exception
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

//header("HTTP/1.1 200 OK");
header('Content-Type: application/json; charset=utf-8');


switch ($_SERVER['REQUEST_METHOD']) {
    case "POST":
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO visits (latitude, longitude, state, code, city) VALUES (?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$data['latitude'], $data['longitude'], $data['state'], $data['code'], $data['city']]);
        break;


    case "GET":
        if (isset($_GET['country'])) {
            $sql = "SELECT DISTINCT state FROM visits";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $city = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($city);

        } else if (isset($_GET['count'])) {
            $state = $_GET['count'];

            $sql = "SELECT COUNT(*) FROM visits WHERE state = (?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$state]);

            $state = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($state);
        } else if (isset($_GET['code'])) {
            $code = $_GET['code'];

            $sql = "SELECT DISTINCT code FROM visits";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $code = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($code);

        } else {
            $sql = "SELECT * FROM visits";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $city = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($city);
        }
}
