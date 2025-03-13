<?php
session_start();
include("./createCard.php");






if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchText = isset($_POST['textInp']) ? $_POST['textInp'] : '';
    $inAuthor = isset($_POST['inAuthor']) ? true : false;
    $inDescription = isset($_POST['inDescription']) ? true : false;
    $inTechn = isset($_POST['inTechn']) ? true : false;

    // echo "Поиск: " . ($searchText) . "<br>";
    // echo "Автор: " . ($inAuthor ? "Включено" : "Выключено") . "<br>";
    // echo "Описание: " . ($inDescription ? "Включено" : "Выключено") . "<br>";
    // echo "Тех. информация: " . ($inTechn ? "Включено" : "Выключено") . "<br>";

    $cardsforRender = [];

    require("./connnect.php");

    $query = "SELECT * from pictures";
    $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
    $pictures = mysqli_fetch_all($result, MYSQLI_ASSOC); 

    if ($inDescription == False && $inAuthor == False && $inTechn == False ) {
        foreach ($pictures as $pic) {
            // if((str_contains(strtolower($pic["russian_name"]), strtolower($searchText))) || (str_contains(strtolower($pic["english_name"]), strtolower($searchText))))
            // {
            //     array_push($cardsforRender, $pic);
            // }

            $russianName = mb_strtolower($pic["russian_name"], 'UTF-8');
            $englishName = mb_strtolower($pic["english_name"], 'UTF-8');
            $searchTextLower = mb_strtolower($searchText, 'UTF-8');
    
            if (str_contains($russianName, $searchTextLower) || str_contains($englishName, $searchTextLower)) {
                array_push($cardsforRender, $pic);
            }
        }
    }else 
    {
        if ($inDescription == True) {
            foreach ($pictures as $pic) {
    
                $russianText = mb_strtolower($pic["description"], 'UTF-8');
                $searchText = mb_strtolower($searchText, 'UTF-8');
        
                if (str_contains($russianText, $searchText)) {
                    array_push($cardsforRender, $pic);
                }
            }
        }
        if ($inAuthor == True) {
            $query = "SELECT * FROM authors";
            $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
            if ($result) {
                $authors = mysqli_fetch_all($result, MYSQLI_ASSOC); 
            }
            foreach ($pictures as $pic) {
                $author = "";
                foreach ($authors as $keyA => $value) {
                    if($value['author_id']==$pic['author_id'])
                    {
                        $authorEng = mb_strtolower($value['english_name'], 'UTF-8');
                        $authorRus = mb_strtolower($value['russian_name'], 'UTF-8');
                    }
                }

                $searchText = mb_strtolower($searchText, 'UTF-8');
            
                if ((str_contains($authorEng, $searchText))||(str_contains($authorRus, $searchText)) ) {
                    array_push($cardsforRender, $pic);
                }
            }
        }

        if ($inTechn == True) {
            foreach ($pictures as $pic) {
                $russianText = mb_strtolower($pic["technique"], 'UTF-8');
                $searchText = mb_strtolower($searchText, 'UTF-8');
            
                if (str_contains($russianText, $searchText)) {
                    array_push($cardsforRender, $pic);
                }
            }
        }


}



    $query = "SELECT * FROM authors";
    $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
    if ($result) {
        $authors = mysqli_fetch_all($result, MYSQLI_ASSOC); 
        // print_r($pictures); 
    }
    foreach ($cardsforRender as $card) {
        createPic($card, $authors);
    }


}

?>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $(document).on("click", ".like img", function(){
        let picId = $(this).attr("id");
        let likeCounterElem = $(this).siblings(".likeCounter");
        let likeImg = $(this);
        console.log("Найдено элементов .likeCounter:", likeCounterElem.length);
        $.ajax({
            url: "like_handler.php",
            type: "POST",
            data: { picture_id: picId },
            success: function(response) {
                console.log(response); 
                let data = JSON.parse(response);
                if (data.success) {
                    likeCounterElem.text(data.likes);
                    likeImg.attr("src", data.likes + "?t=" + new Date().getTime());
                } else {
                    alert(data.message);
                }
            }
        });
    });
});
</script> -->