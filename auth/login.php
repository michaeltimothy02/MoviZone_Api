<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../config/connection.php";

$email    = $_POST['email']    ?? '';
$password = $_POST['password'] ?? '';

if ($email == '' || $password == '') {
    echo json_encode([
        "status"  => "error",
        "message" => "Isi semua field"
    ]);
    exit;
}

$sql    = "SELECT * FROM users WHERE email='$email' AND password='$password'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    echo json_encode([
        "status"        => "success",
        "user_id"       => $user['id'],
        "email"         => $user['email'],
        "username"      => $user['username'],       // ← tambah ini
        "profile_image" => $user['profile_image'],  // ← tambah ini
    ]);
} else {
    echo json_encode([
        "status"  => "error",
        "message" => "Login gagal"
    ]);
}
?>