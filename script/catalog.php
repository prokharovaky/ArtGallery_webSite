<?php
session_start();
include("./head.php");
require("./connnect.php");

include("./createCard.php");

?>
<main class="main" id="catalogMain">
        <h1 class="catalogHeader">
            <?php
                if (isset($_GET['sub'])) {
                    echo implode(" ", $_GET['sub']);
                } else if (isset($_GET['category'])) {
                        echo $_GET['category'];
                } else {
                    echo "Каталог:";
                }
            ?>
        </h1>
    <div class="catalogContainer">
        <div class="catalogSorting">
                <form class="sorting">
                    <p>Выберите фильтры:</p>

                    <!-- автор -->


                    <!-- страна -->
                    <label for="countrySelect" class="sortingLabel">Страна:</label>
                    <select name="countrySelect" id="countrySelect" class="selectSorting">
                    <option value="" selected>Не выбрано</option>
                        <?php
                        $query = "SELECT * FROM countries";
                        $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
                        if ($result) {
                            $countries = mysqli_fetch_all($result, MYSQLI_ASSOC); 
                            // print_r($pictures); 
                            foreach ($countries as $country) {
                                $CountryId = $country['country_id'];
                                $CountryName = $country['country'];
                                echo "<option value='$CountryId' id='$CountryId'>$CountryName</option>";
                            }
                        }
                        ?>
                    </select>


                    <!-- жанр -->
                    <label for="genreSelect" class="sortingLabel">Жанр:</label>
                    <select name="genreSelect" id="genreSelect" class="selectSorting">
                    <option value="" selected>Не выбрано</option>
                        <?php
                        $query = "SELECT * FROM genres";
                        $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
                        if ($result) {
                            $genres = mysqli_fetch_all($result, MYSQLI_ASSOC); 
                            // print_r($pictures); 
                            foreach ($genres as $genre) {
                                $GenreId = $genre['genre_id'];
                                $GenreName = $genre['genre'];
                                echo "<option value='$GenreId' id='$GenreId'>$GenreName</option>";
                            }
                        }
                        ?>
                    </select>



                    <!-- стиль -->
                    <label for="styleSelect" class="sortingLabel">Стиль:</label>
                    <select name="styleSelect" id="styleSelect" class="selectSorting">
                    <option value="" selected>Не выбрано</option>
                        <?php
                        $query = "SELECT * FROM styles";
                        $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
                        if ($result) {
                            $styles = mysqli_fetch_all($result, MYSQLI_ASSOC); 
                            // print_r($pictures); 
                            foreach ($styles as $style) {
                                $StyleId = $style['style_id'];
                                $StyleName = $style['style'];
                                echo "<option value='$StyleId' id='$StyleId'>$StyleName</option>";
                            }
                        }
                        ?>
                    </select>
                    <label for="colorButtons" class="sortingLabel">Цвет:</label>
                    <section class="colorButtons">
                        <label class="color-option" style="background-color: #435287;">
                        <input type="radio" name="color" value="1">
                        </label>
                        <label class="color-option" style="background-color: #65855B;">
                            <input type="radio" name="color" value="2">
                        </label>
                        <label class="color-option" style="background-color: #FFCC00;">
                            <input type="radio" name="color" value="3">
                        </label>
                        <label class="color-option" style="background-color: #BAA8A2;">
                            <input type="radio" name="color" value="4">
                        </label>
                        <label class="color-option" style="background-color: #624274;">
                            <input type="radio" name="color" value="5">
                        </label>
                        <label class="color-option" style="background-color: #893A3A;">
                            <input type="radio" name="color" value="6">
                        </label>
                    </section>


                    <label for="buyOrig" class="sortingLabel">Покупка оригинала:</label>
                    <section class="buyOrig">
                        <label class="availability-option available">
                            <input type="radio" name="availability" value="1">
                            <span>Можно купить</span>
                        </label>
                        <label class="availability-option not-available">
                            <input type="radio" name="availability" value="0">
                            <span>Нельзя купить</span>
                        </label>
                    </section>


<?php
                    $query = "SELECT year FROM pictures";
                    $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
                    if ($result) {
                    $years = mysqli_fetch_all($result, MYSQLI_ASSOC); 
                    // print_r($pictures); 
                    }
                    // $maxYear = max(intval( $years));
                    echo("<label for='range'>До какого года:</label>");
                    echo("<input type='range' id='range' name='range' min='1700' max='2000' value='2000' oninput='updateValue()'>");
                    echo("<span id='value'>2000</span>");
?>
                        <script>
                            function updateValue() {
                                document.getElementById("value").textContent = document.getElementById("range").value;
                            }
                        </script>





                    <button type="button" class="reset-button">Сбросить все фильтры</button>

                </form>
        </div>

        <div class="catalogCards" id="cardsContainer">
            



        <?php

                // include "sorting.php";
                if (!empty($resultPictures)) {
                    // foreach ($resultPictures as $key => $picRes) {
                    //     $query = "SELECT * FROM authors";
                    //     $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
                    //     if ($result) {
                    //         $authors = mysqli_fetch_all($result, MYSQLI_ASSOC); 
                    //         // print_r($pictures); 
                    //     }
                    //     createPic($picRes, $authors);
                    // }



                }else{


                require("./connnect.php");
                $marker = "catalog";
                $category = "none"; 
                if(isset($_GET['sub']) && is_array($_GET['sub'])) {
                    $subCategories = $_GET['sub'];
                    if (count($subCategories) !=2 ) {
                        $marker="catalog";
                        $category = "none"; 
                    }else
                    {
                        $category = "$subCategories[0]"; 
                        $marker = $subCategories[1];
                    }
                }

                $query = "SELECT * FROM pictures";
                $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));

                if ($result) {
                    $pictures = mysqli_fetch_all($result, MYSQLI_ASSOC); 
                    // print_r($pictures); 
                }

                $query = "SELECT * FROM authors";
                $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
                if ($result) {
                    $authors = mysqli_fetch_all($result, MYSQLI_ASSOC); 
                    // print_r($pictures); 
                }


                if ($marker != "catalog"){
                    $arrayForShow = [];
                    $keyForSearch = "";
                    // foreach ($pictures as $key => $pic) {
                        switch ($category) {
                            case "Жанры":
                                $query = "SELECT * from genres where genre = '$marker'";
                                    $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
                                    $idArr = mysqli_fetch_all($result, MYSQLI_ASSOC); 
                                    $id = $idArr[0]['genre_id'];


                                foreach ($pictures as $pic) {
                                    if($pic['genre_id'] == $id){
                                        createPic($pic, $authors);
                                    }
                                    // break;
                            }

                                
                                    break;
                            case "Стили":
                                $query = "SELECT * from styles where style = '$marker'";
                                    $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
                                    $idArr = mysqli_fetch_all($result, MYSQLI_ASSOC); 
                                    $id = $idArr[0]['style_id'];


                                foreach ($pictures as $pic) {
                                    if($pic['style_id'] == $id){
                                        createPic($pic, $authors);
                                    }
                                    // break;
                            }

                                
                                    break;
                            case "Авторы":
                                $query = "SELECT * from authors where russian_name = '$marker'";
                                    $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
                                    $idArr = mysqli_fetch_all($result, MYSQLI_ASSOC); 
                                    $id = $idArr[0]['author_id'];


                                foreach ($pictures as $pic) {
                                    if($pic['author_id'] == $id){
                                        createPic($pic, $authors);
                                    }
                                    // break;
                            }

                                
                                    break;
                            case "Страны":
                                $query = "SELECT * from countries where country = '$marker'";
                                    $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
                                    $idArr = mysqli_fetch_all($result, MYSQLI_ASSOC); 
                                    $id = $idArr[0]['country_id'];


                                foreach ($pictures as $pic) {
                                    if($pic['country_id'] == $id){
                                        createPic($pic, $authors);
                                    }
                                    // break;
                            }

                                
                                    break;
                            default:
                                foreach ($pictures as $key => $pic) {
                                    createPic($pic, $authors);
                                    break;
                            }
                    // }
                }
                }else {
                foreach ($pictures as $key => $pic) {
                    createPic($pic, $authors);
                }
            }
            }
            ?>


        </div>
    </div>
</main>
<?php
include("./footer.html");
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    likeImg.attr("src", data.img);
                } else {
                    alert(data.message);
                }
            }
        });
    });
});
</script>
<script>
$(document).ready(function () {
    let filteredData = [];

    function sendData() {
        var formData = $(".sorting").serialize();

        $.ajax({
            url: "./sorting.php", 
            type: "POST",
            data: formData,
            dataType: "json", 
            success: function (data) {
                if (data.error) {
                    console.error("Ошибка на сервере: " + data.error);
                } else {
                    filteredData = data; 
                    console.log(filteredData); 
                    updatePage();
                }
            },
            error: function (xhr, status, error) {
                console.error("Ошибка запроса: " + error);
                console.log(xhr.responseText); 
            }
        });
    }

    function updatePage() {
        let container = $("#cardsContainer");
        container.empty(); 

        filteredData.forEach(pic => {
            let likeImg = pic.isLiked ? "../img/like-liked.png" : "../img/like-active.png";

            let card = `
                <div class='card' id='${pic.picture_id}'>
                    <img class='cardImg' src='${pic.img_pass}' alt='${pic.english_name}'>
                    <div class='cardHeader'>
                        <p>${pic.english_name}</p>
                        <div class='like'>
                            <img class='like' id='${pic.picture_id}' src='${pic.picture_path}'>
                            <p class='likeCounter'>${pic.like_count}</p>
                        </div>
                    </div>
                    <p class='cardAuthorName'>${pic.author_name}</p>
                    <a href='./picturepage.php?pic=${pic.picture_id}'>Подробнее</a>
                </div>
            `;
            container.append(card);
        });

    }

    $(".sorting input, .sorting select, .sorting input[type='range']").on("input change", sendData);


    $(".reset-button").on("click", function () {
        $(".sorting")[0].reset();
        sendData();
    });
});
</script>