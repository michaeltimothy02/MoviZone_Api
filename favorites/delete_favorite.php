<?php
include '../config/connection.php';

$user_id  = $_POST['user_id'];
$movie_id = $_POST['movie_id'];

$query = "DELETE FROM favorites WHERE user_id='$user_id' AND movie_id='$movie_id'";

if (mysqli_query($connect, $query)) {
    echo json_encode(["status" => "success", "message" => "Berhasil dihapus"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal menghapus"]);
}
?>