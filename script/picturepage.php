<?php
session_start();
include("./head.php");
require("./connnect.php");

$pictureId = isset($_GET['pic']) ? intval($_GET['pic']) : 0;

$query = "SELECT * FROM pictures where picture_id = $pictureId";
$result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));

if ($result) {
    $pictures = mysqli_fetch_all($result, MYSQLI_ASSOC); 
}

$pic = $pictures[0];
?>
<main class="main" id="mainCardPage">
    <div class="cardDivPage">
        <p class="cardPage"><?php  echo($pic['english_name'])?></p>
        <div class="upPart">
            <div class="cardImgPage">
                <?php
                    $link = $pic['img_pass'];
                    echo("<img src='$link'>");
                ?>
            </div>
            <div class="tablePage">
            <table>
                    <tr>
                        <th>Название</th>
                        <td><?php echo $pic['russian_name']; ?></td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td><?php echo $pic['english_name']; ?></td>
                    </tr>
                    <tr>
                        <th>Автор</th>
                        <td>
                            <?php 
                                require("./connnect.php");
                                $query = "SELECT * FROM authors";
                                $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
                                if ($result) {
                                    $authors = mysqli_fetch_all($result, MYSQLI_ASSOC); 
                                    // print_r($pictures); 
                                }
                                $author = "";
                                foreach ($authors as $keyA => $value) {
                                    if($pic['author_id']==$value['author_id'])
                                    {
                                        $author = $value['russian_name'];
                                    }
                                }
                                echo $author; 
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Author</th>
                        <td>
                            <?php 
                                require("./connnect.php");
                                $query = "SELECT * FROM authors";
                                $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
                                if ($result) {
                                    $authors = mysqli_fetch_all($result, MYSQLI_ASSOC); 
                                    // print_r($pictures); 
                                }
                                $author = "";
                                foreach ($authors as $keyA => $value) {
                                    if($pic['author_id']==$value['author_id'])
                                    {
                                        $author = $value['english_name'];
                                    }
                                }
                                echo $author; 
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Год</th>
                        <td><?php echo $pic['year']; ?></td>
                    </tr>
                    <tr>
                        <th>Страна</th>
                        <td>
                            <?php 
                                require("./connnect.php");
                                $query = "SELECT * FROM countries";
                                $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
                                if ($result) {
                                    $countries = mysqli_fetch_all($result, MYSQLI_ASSOC); 
                                    // print_r($pictures); 
                                }
                                $country = "";
                                foreach ($countries as $keyA => $value) {
                                    if($pic['country_id']==$value['country_id'])
                                    {
                                        $country = $value['country'];
                                    }
                                }
                                echo $country; 
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Жанр</th>
                        <td>
                            <?php 
                                require("./connnect.php");
                                $query = "SELECT * FROM genres";
                                $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
                                if ($result) {
                                    $genres = mysqli_fetch_all($result, MYSQLI_ASSOC); 
                                    // print_r($pictures); 
                                }
                                $genre = "";
                                foreach ($genres as $keyA => $value) {
                                    if($pic['genre_id']==$value['genre_id'])
                                    {
                                        $genre = $value['genre'];
                                    }
                                }
                                echo $genre; 
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Стиль</th>
                        <td>
                            <?php 
                                require("./connnect.php");
                                $query = "SELECT * FROM styles";
                                $result = mysqli_query($link, $query) or die("Ошибка: " . mysqli_error($link));
                                if ($result) {
                                    $styles = mysqli_fetch_all($result, MYSQLI_ASSOC); 
                                    // print_r($pictures); 
                                }
                                $style = "";
                                foreach ($styles as $keyA => $value) {
                                    if($pic['style_id']==$value['style_id'])
                                    {
                                        $style = $value['style'];
                                    }
                                }
                                echo $style; 
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Доступность</th>
                        <td><?php echo $pic['purchase'] == 1 ? 'Доступно' : 'Недоступно'; ?></td>
                    </tr>
                    <tr>
                        <th>Местоположение</th>
                        <td><?php echo $pic['location'] ?></td>
                    </tr>
                </table>

            </div>
        </div>
        <div class="downPart">
            <?php
                echo("<p class='picDescription'>$pic[description]</p>");
            ?>
        </div>
    </div>
</main>
<?php
include("./footer.html");
?>