<?php
include("./head.php");
include("./createCard.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchText = isset($_POST['textInp']) ? $_POST['textInp'] : '';
    $inAuthor = isset($_POST['inAuthor']) ? true : false;
    $inDescription = isset($_POST['inDescription']) ? true : false;
    $inTechn = isset($_POST['inTechn']) ? true : false;

    echo "Поиск: " . $searchText . "<br>";
    echo "Автор: " . ($inAuthor ? "Включено" : "Выключено") . "<br>";
    echo "Описание: " . ($inDescription ? "Включено" : "Выключено") . "<br>";
    echo "Техника: " . ($inTechn ? "Включено" : "Выключено") . "<br>";
}

?>
<main class="main" id="poiskMain">
    <form class="poisk">
        <section class="inpPlace">
            <input type="text" class="textInp" id="textInp" name="textInp">
        </section>
            
        <section class="filter">
            <div class="poiskElement">
            <input type="checkbox" id="inAuthor" value="inAuthor" name="inAuthor">
            <label for="inAuthor">Поиск по автору</label>
            </div>
            <div class="poiskElement">
            <input type="checkbox" id="inDescription" value="inDescription" name="inDescription">
            <label for="inDescription">Поиск в описании</label>
            </div>
            <div class="poiskElement">
            <input type="checkbox" id="inTechn" value="inTechn" name="inTechn">
            <label for="inTechn">Поиск по технике выполнения</label>
            </div>
        </section>
    </form>
    <div class="catalogCards">

    </div>
</main>
<?php
include("./footer.html");
?>

<script>
$(document).ready(function(){
    function sendData() {
        let formData = new FormData($(".poisk")[0]);

        $.ajax({
            url: "./poisk.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                $(".catalogCards").html(data);
            },
            error: function(xhr, status, error) {
                console.error("Ошибка:", error);
            }
        });
    }

    $("#textInp, #inAuthor, #inDescription, #inTechn").on("input change", sendData);
});
</script>
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