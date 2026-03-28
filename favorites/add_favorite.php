<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// 1. Sertakan file koneksi
include '../config/connection.php'; 

// 2. Ambil data (bisa dari POST Flutter atau URL Browser)
$user_id  = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
$movie_id = isset($_REQUEST['movie_id']) ? $_REQUEST['movie_id'] : '';

if (empty($user_id) || empty($movie_id)) {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
    exit;
}

// 3. Jalankan Query menggunakan $conn (sesuai file connection.php kamu)
// Cek dulu apakah sudah pernah difavoritkan
$check = mysqli_query($conn, "SELECT * FROM favorites WHERE user_id='$user_id' AND movie_id='$movie_id'");

if (mysqli_num_rows($check) > 0) {
    echo json_encode(["status" => "error", "message" => "Film sudah ada di favorit"]);
} else {
    // Jika belum ada, masukkan data baru
    $query = "INSERT INTO favorites (user_id, movie_id) VALUES ('$user_id', '$movie_id')";
    
    if (mysqli_query($conn, $query)) {
        echo json_encode(["status" => "success", "message" => "Berhasil ditambah!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal: " . mysqli_error($conn)]);
    }
}
?>