<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include '../config/connection.php';

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '2'; // Default ke 2 jika kosong

$query = "SELECT movie_id FROM favorites WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);

$favorites = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Pakai (string) atau (int) agar konsisten
    $favorites[] = $row['movie_id'];
}

// WAJIB PAKAI json_encode
echo json_encode($favorites);
?>