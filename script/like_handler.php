<?php
session_start();
require("./connnect.php");

if (!isset($_SESSION['account'])) {
    ob_clean();
    echo json_encode(["success" => false, "message" => "Вы не вошли в аккаунт"]);

}else
{

    ob_clean();
    echo json_encode(["success" => false, "message" => "Вы вошли в аккаунт"]);
    $likeCounter = 0;

    if (isset($_POST['picture_id'])) {
    $idUs = $_SESSION['account'];
    $idPic = $_POST['picture_id'];
    $query = "SELECT * from likes where user_id = $idUs and picture_id = $idPic";
    $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
    $likes = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if (!empty($likes)) {
        ob_clean();
        echo json_encode(["success" => false, "message" => "У вас уже стоит лайк!"]);
        $dropQuery = "DELETE FROM likes WHERE user_id = ? AND picture_id = ?";
        $stmt = mysqli_prepare($link, $dropQuery);
        mysqli_stmt_bind_param($stmt, "ii", $idUs, $idPic);
        mysqli_stmt_execute($stmt);
        $query = "SELECT * from likes where picture_id = $idPic";
          $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
          $LikesFromDB = mysqli_fetch_all($result, MYSQLI_ASSOC); 
          if (!empty($LikesFromDB)) {
            foreach ($LikesFromDB as $key => $value) {
                $likeCounter ++;
            }
          }
          if($likeCounter == 0)
          {
            $likeCounter = "";
          }
          $likeImg = "../img/like-active.png";
        ob_clean();
        echo json_encode([
            "success" => true,
            "likes" => $likeCounter,
            "img" => "../img/like-active.png"
        ], JSON_UNESCAPED_SLASHES);
        exit;


    } else 
    {
        // ob_clean();
        // echo json_encode(["success" => false, "message" => "Все окей"]);  
        $insertQuery = "INSERT into likes (picture_id, user_id) VALUES (?, ?)";

        $stmt = mysqli_prepare($link, $insertQuery);
        mysqli_stmt_bind_param($stmt, "ii", $idPic, $idUs);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);


        $query = "SELECT * from likes where picture_id = $idPic";
        $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
        $LikesFromDB = mysqli_fetch_all($result, MYSQLI_ASSOC); 
        if (!empty($LikesFromDB)) {
            foreach ($LikesFromDB as $key => $value) {
                $likeCounter ++;
            }
        }
        $likeImg = "../img/like-liked.png";
        ob_clean();
        echo json_encode([
            "success" => true,
            "likes" => $likeCounter,
            "img" => "../img/like-liked.png"
        ], JSON_UNESCAPED_SLASHES);
        exit;

    }
    }
}
?>