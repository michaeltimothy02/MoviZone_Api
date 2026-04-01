<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../config/connection.php";

$user_id = $_GET['user_id'] ?? '';

if ($user_id == '') {
    echo json_encode(["status" => "error", "message" => "user_id diperlukan"]);
    exit;
}

$sql = "SELECT id, email, username, profile_image FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    echo json_encode([
        "status"         => "success",
        "id"             => $user['id'],
        "email"          => $user['email'],
        "username"       => $user['username'],
        "profile_image"  => $user['profile_image']
            ? "http://192.168.1.13/MOVIZONE_API/uploads/profiles/" . $user['profile_image']
            : null
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "User tidak ditemukan"]);
}
?>