<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../config/connection.php";

$email    = $_POST['email']    ?? '';
$password = $_POST['password'] ?? '';
$username = $_POST['username'] ?? '';  

if ($email == '' || $password == '' || $username == '') {
    echo json_encode([
        "status"  => "error",
        "message" => "Isi semua field"
    ]);
    exit;
}

$check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
if (mysqli_num_rows($check) > 0) {
    echo json_encode([
        "status"  => "error",
        "message" => "Email sudah ada"
    ]);
    exit;
}

// ← username ikut disimpan ke database
$sql = "INSERT INTO users (email, password, username) VALUES ('$email', '$password', '$username')";
if (mysqli_query($conn, $sql)) {
    echo json_encode([
        "status"  => "success",
        "message" => "Register berhasil"
    ]);
} else {
    echo json_encode([
        "status"  => "error",
        "message" => mysqli_error($conn)
    ]);
}
?>