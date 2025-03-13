<?php
require("./connnect.php");
session_start();

$author = isset($_POST['authorSelect']) ? intval($_POST['authorSelect']) : null;
$country = isset($_POST['countrySelect']) ? intval($_POST['countrySelect']) : null;
$genre = isset($_POST['genreSelect']) ? intval($_POST['genreSelect']) : null;
$style = isset($_POST['styleSelect']) ? intval($_POST['styleSelect']) : null;
$color = isset($_POST['color']) ? intval($_POST['color']) : null;
$availability = isset($_POST['availability']) ? (int) $_POST['availability'] : null;
$year = isset($_POST['range']) ? intval($_POST['range']) : null;



$query = "SELECT * FROM pictures";
$result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));

if ($result) {
    $pictures = mysqli_fetch_all($result, MYSQLI_ASSOC); 
    // print_r($pictures); 
}


$resultPictures = [];

// if ($author != null) {
//     foreach ($pictures as $pic) {
//         if($pic['author_id'] == $author)
//         {
//             array_push($resultPictures, $pic);
//         }
//     }
// }


// if ($country != null) {
//     foreach ($pictures as $pic) {
//         if($pic['country_id'] == $country)
//         {
//             array_push($resultPictures, $pic);
//         }
//     }
// }


// if ($genre != null) {
//     foreach ($pictures as $pic) {
//         if($pic['genre_id'] == $genre)
//         {
//             array_push($resultPictures, $pic);
//         }
//     }
// }

// if ($style != null) {
//     foreach ($pictures as $pic) {
//         if($pic['style_id'] == $style)
//         {
//             array_push($resultPictures, $pic);
//         }
//     }
// }



// if ($color != null) {
//     foreach ($pictures as $pic) {
//         if($pic['color_id'] == $color)
//         {
//             array_push($resultPictures, $pic);
//         }
//     }
// }


// if ($availability != null) {

//     foreach ($pictures as $pic) {
//         if($pic['purchase'] == $availability)
//         {
//             array_push($resultPictures, $pic);
//         }
//     }
// }


// if ($year != null) {

//     foreach ($pictures as $pic) {
//         if($pic['year'] <= $year)
//         {
//             array_push($resultPictures, $pic);
//         }
//     }
// }
foreach ($pictures as $pic) {
    $match = true; 


    // if ($author != null && $pic['author_id'] != $author) {
    //     $match = false;
    // }

    if ($country != null && $pic['country_id'] != $country) {
        $match = false;
    }


    if ($genre != null && $pic['genre_id'] != $genre) {
        $match = false;
    }


    if ($style != null && $pic['style_id'] != $style) {
        $match = false;
    }


    if ($color != null && $pic['color_id'] != $color) {
        $match = false;
    }


    if ($availability !== null && (int)$pic['purchase'] !== $availability) {
        $match = false;
    }


    if ($year != null && $pic['year'] >= $year) {
        $match = false;
    }

    if ($match) {

        $likeQuery = "SELECT COUNT(*) as like_count FROM likes WHERE picture_id = " . $pic['picture_id'];
        $likeResult = mysqli_query($link, $likeQuery);
        $likeCount = ($likeResult) ? mysqli_fetch_assoc($likeResult)['like_count'] : 0;


        $authorQuery = "SELECT english_name FROM authors WHERE author_id = " . $pic['author_id'];
        $authorResult = mysqli_query($link, $authorQuery);
        $authorName = ($authorResult) ? mysqli_fetch_assoc($authorResult)['english_name'] : "Неизвестный автор";

        $picPage="";
        if (!isset($_SESSION['account'])){
            $picPage = '../img/like-active.png';

          }else{

            $usId = $_SESSION['account'];
            $query = "SELECT * from likes where user_id = $usId and picture_id = $pic[picture_id]";
            $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
            $LikesFromDB = mysqli_fetch_all($result, MYSQLI_ASSOC); 
            if (!empty($LikesFromDB)) {
                // echo "<img class='like' id='$pic[picture_id]' src='../img/like-liked.png'>";
                $picPage = '../img/like-liked.png';

            }
            else{
                // echo "<img class='like' id='$pic[picture_id]' src='../img/like-active.png'>";
                $picPage = '../img/like-active.png';

            }


          }




        $resultPictures[] = [
            "picture_id" => $pic['picture_id'],
            "img_pass" => $pic['img_pass'],
            "english_name" => $pic['english_name'],
            "like_count" => $likeCount,
            "author_name" => $authorName,
            "picture_path" => $picPage
        ];
    }
}




// header("Content-Type: application/json");

if (empty($resultPictures)) {
    echo json_encode(['error' => 'Ничего не найдено']);
    exit;
}

echo json_encode($resultPictures);


?>