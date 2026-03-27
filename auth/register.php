<?php
header("Content-Type: application/json");
include "../config/connection.php";

// ambil data dari POST form
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ($email == '' || $password == '') {
    echo json_encode([
        "status" => "error",
        "message" => "Isi semua field"
    ]);
    exit;
}

// cek email sudah ada
$check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
if (mysqli_num_rows($check) > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Email sudah ada"
    ]);
    exit;
}

// insert
$sql = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
if (mysqli_query($conn, $sql)) {
    echo json_encode([
        "status" => "success",
        "message" => "Register berhasil"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => mysqli_error($conn)
    ]);
}
?>