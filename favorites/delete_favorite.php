<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include '../config/connection.php';

$user_id  = isset($_POST['user_id']) ? $_POST['user_id'] : '';
$movie_id = isset($_POST['movie_id']) ? $_POST['movie_id'] : '';

if (!empty($user_id) && !empty($movie_id)) {
    $m_id = (int)$movie_id;
    $u_id = mysqli_real_escape_string($conn, $user_id); 

    $query = "DELETE FROM favorites WHERE user_id = '$u_id' AND movie_id = $m_id";
    
    if (mysqli_query($conn, $query)) {
        if (mysqli_affected_rows($conn) > 0) {
            echo json_encode(["status" => "success", "message" => "Dihapus dari watchlist"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Data tidak ditemukan di database"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap: User $user_id, Movie $movie_id"]);
}
?>