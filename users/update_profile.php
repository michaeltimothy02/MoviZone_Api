<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../config/connection.php";

$user_id  = $_POST['user_id']  ?? '';
$username = $_POST['username'] ?? '';

if ($user_id == '') {
    echo json_encode(["status" => "error", "message" => "user_id diperlukan"]);
    exit;
}

$uploadDir = "../uploads/profiles/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$imageFilename = null;


if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $tmpName  = $_FILES['profile_image']['tmp_name'];
    $origName = $_FILES['profile_image']['name'];
    $ext      = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
    $allowed  = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed)) {
        echo json_encode(["status" => "error", "message" => "Format gambar tidak didukung"]);
        exit;
    }

    $maxSize = 5 * 1024 * 1024; 
    if ($_FILES['profile_image']['size'] > $maxSize) {
        echo json_encode(["status" => "error", "message" => "Ukuran gambar maksimal 5MB"]);
        exit;
    }


    $sqlOld = "SELECT profile_image FROM users WHERE id='$user_id'";
    $resOld = mysqli_query($conn, $sqlOld);
    if ($rowOld = mysqli_fetch_assoc($resOld)) {
        if (!empty($rowOld['profile_image'])) {
            $oldPath = $uploadDir . $rowOld['profile_image'];
            if (file_exists($oldPath)) unlink($oldPath);
        }
    }

    $imageFilename = "user_{$user_id}_" . time() . "." . $ext;
    move_uploaded_file($tmpName, $uploadDir . $imageFilename);
}


if ($imageFilename !== null && $username !== '') {
    $sql = "UPDATE users SET username='$username', profile_image='$imageFilename' WHERE id='$user_id'";
} elseif ($imageFilename !== null) {
    $sql = "UPDATE users SET profile_image='$imageFilename' WHERE id='$user_id'";
} elseif ($username !== '') {
    $sql = "UPDATE users SET username='$username' WHERE id='$user_id'";
} else {
    echo json_encode(["status" => "error", "message" => "Tidak ada data yang diupdate"]);
    exit;
}

if (mysqli_query($conn, $sql)) {

    $sqlGet = "SELECT username, profile_image FROM users WHERE id='$user_id'";
    $res    = mysqli_query($conn, $sqlGet);
    $user   = mysqli_fetch_assoc($res);

    echo json_encode([
        "status"        => "success",
        "message"       => "Profil berhasil diupdate",
        "username"      => $user['username'],
        "profile_image" => $user['profile_image']
            ? "http://192.168.1.13/MOVIZONE_API/uploads/profiles/" . $user['profile_image']
            : null
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal update: " . mysqli_error($conn)]);
}
?>