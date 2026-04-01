<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../config/connection.php";

$user_id = $_POST['user_id'] ?? '';

if ($user_id == '') {
    echo json_encode(["status" => "error", "message" => "user_id diperlukan"]);
    exit;
}


$sql = "SELECT profile_image FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo json_encode(["status" => "error", "message" => "User tidak ditemukan"]);
    exit;
}

$user = mysqli_fetch_assoc($result);

if (empty($user['profile_image'])) {
    echo json_encode(["status" => "error", "message" => "Tidak ada foto profil"]);
    exit;
}


$filePath = "../uploads/profiles/" . $user['profile_image'];
if (file_exists($filePath)) {
    unlink($filePath);
}


$sqlUpdate = "UPDATE users SET profile_image = NULL WHERE id='$user_id'";
if (mysqli_query($conn, $sqlUpdate)) {
    echo json_encode(["status" => "success", "message" => "Foto profil berhasil dihapus"]);
} else {
    echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
}
?>