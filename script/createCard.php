<?php
session_start();
function createPic($pic, $authors)
{
    require("./connnect.php");
    echo "<div class='card' id='$pic[picture_id]'>";
                
    echo "<img class='cardImg' src='$pic[img_pass]'>";
    echo "<div class='cardHeader'><p>$pic[english_name]</p>";
    echo "<div class='like'>";
          if (!isset($_SESSION['account'])){
            echo "<img class='like' id='$pic[picture_id]' src='../img/like-active.png'>";

          }else{

            $usId = $_SESSION['account'];
            $query = "SELECT * from likes where user_id = $usId and picture_id = $pic[picture_id]";
            $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
            $LikesFromDB = mysqli_fetch_all($result, MYSQLI_ASSOC); 
            if (!empty($LikesFromDB)) {
                echo "<img class='like' id='$pic[picture_id]' src='../img/like-liked.png'>";
            }
            else{
                echo "<img class='like' id='$pic[picture_id]' src='../img/like-active.png'>";
            }


            // echo "<img class='like' id='$pic[picture_id]' src='../img/like-active.png'>";
            // $query = "SELECT * from likes where user_id = $pic[picture_id]";
          }
          $query = "SELECT * from likes where picture_id = $pic[picture_id]";
          $likeCounter = 0;
          $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
          $LikesFromDB = mysqli_fetch_all($result, MYSQLI_ASSOC); 
          if (!empty($LikesFromDB)) {
            foreach ($LikesFromDB as $key => $value) {
                $likeCounter ++;
            }
            echo "<p class='likeCounter'>$likeCounter</p>";
          } else {
            echo "<p class='likeCounter'></p>";
          }

    echo "</div>";
    echo "</div>";
    $author = "";
    foreach ($authors as $keyA => $value) {
        if($value['author_id']==$pic['author_id'])
        {
            $author = $value['english_name'];
        }
    }
    echo "<p class='cardAuthorName'>$author</p>";
    echo "<a href='./picturepage.php?pic=$pic[picture_id]'>Подробнее</a>";
                
    echo "</div>";
}
?>