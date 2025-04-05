<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <title>Art Gallery Registration</title>
    <script
  src="https://code.jquery.com/jquery-3.7.1.js"
  integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script>
</head>
<body>
<header>
  <?php
    // Добавляем все жанры авторы и прочее из бд 
    require("./connnect.php");


    function AddIntoMenu($type, $table, $parent)
    {
      require("./connnect.php");
      $queryCommon = "SELECT menu_item FROM menu";
      $resultCommon = mysqli_query($link, $queryCommon) or die("Ошибка " . mysqli_error($link));
          
      $menuItems = [];
      while ($row = mysqli_fetch_assoc($resultCommon)) {
        $menuItems[] = $row['menu_item']; 
      }
          

      $queryGenre = "SELECT $type FROM $table";
      $resultGenre = mysqli_query($link, $queryGenre) or die("Ошибка " . mysqli_error($link));
          
      while ($rowGenre = mysqli_fetch_assoc($resultGenre)) 
      {
        if (!in_array($rowGenre[$type], $menuItems)) { 
          $query = "INSERT INTO menu (menu_item, menu_item_parent) VALUES ('$rowGenre[$type]', $parent)";
          mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
        }
      }
    }

    
    AddIntoMenu("genre", "genres", 6);
    AddIntoMenu("russian_name", "authors",8);
    AddIntoMenu("country", "countries", 9);
    AddIntoMenu("style", "styles", 10);

    
    




    function get_category()
    {
      require("./connnect.php");
      $query = "SELECT * FROM menu";
      $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));

      if ($result) {
        $arr_cat = [];
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
          $arr_cat[$row['menu_item_parent']][] = $row;
        }
        return $arr_cat;
      }
      return [];
    }

    function view_category($arr, $parent_id = 0, $parent_href = '#')
    {
      if (empty($arr[$parent_id])) return;
      echo '<ul>';
      foreach ($arr[$parent_id] as $item) {

        $menu_name = urlencode($item['menu_item']);
        $href = $parent_href; 

        if ($parent_id == 0) {

          switch ($item['menu_item']) {
              case "Главная":
                  $href = "catalog.php";
                  break;
              case "Каталог":
                  $href = "catalog.php?category=$menu_name";
                  break;
              case "Вопрос-ответ":
                  $href = "vopros.php";
                  break;
              case "Поиск":
                  $href = "finder.php";
                  break;
              case "Личный кабинет":
                    $href = "catalog.php";
                    $href = "registration.php";
                  break;
              default:
                  $href = "#";
          }
      } else {
          $href .= "&sub[]=" . $menu_name;
      }
      if (isset($_SESSION['account']) && $item['menu_item'] != "Личный кабинет")
      {
      echo "<li><a href='$href' onclick='get_plant_category(" . $item['id'] . ")'>" . $item['menu_item'] . "</a>";
      } else if (!isset($_SESSION['account']))
      {
        echo "<li><a href='$href' onclick='get_plant_category(" . $item['id'] . ")'>" . $item['menu_item'] . "</a>";
      }
      view_category($arr, $item['id'], $href);

      echo "</li>";
      }
      echo '</ul>';
    }

    $result = get_category();
    echo '<div class="menu">';
    view_category($result);
    // if (isset($_SESSION['account'])) {
    //   echo '<li>Выйти</li>';
    // }
    // else
    // {
    //   echo '<li>Djqnb</li>';
    // }
    if (isset($_SESSION['account'])) {
      echo '<li><a href="#" onclick="confirmLogout()">Выйти</a></li>';
    }
    echo '</div>';
  ?>
</header>
<script>
  function confirmLogout()
  {
    if(confirm("Вы точно хотите выйти?"))
    {
      window.location.href = "logout.php";
    }
  }
</script>
<script>

// $(document).ready(function(){
//     $(document).on("dblclick", ".like img", function(){ 
//         let picId = $(this).attr("id");
//         let likeCounterElem = $(this).siblings(".likeCounter"); 

//         $.ajax({
//             url: "./like_handler.php",
//             type: "POST",
//             data: { picture_id: picId },
//             success: function(response) {
//                 console.log("Ответ сервера:", response);
//                 try {
//                     let data = JSON.parse(response);
//                     if (data.success) {
//                         likeCounterElem.text(data.likes);
//                     } else {
//                         alert(data.message);
//                     }
//                 } catch (e) {
//                 }
//             },
//             error: function(xhr, status, error) {
//             }
//         });
//     });
// });

document.addEventListener("DOMContentLoaded", function () {
    let timeout;
    const menuItems = document.querySelectorAll(".menu li");

    menuItems.forEach((item) => {
        const submenu = item.querySelector("ul");
        if (submenu) {
            item.addEventListener("mouseenter", () => {
                clearTimeout(timeout); 
                submenu.style.display = "block"; 
            });

            item.addEventListener("mouseleave", () => {
                timeout = setTimeout(() => {
                    submenu.style.display = "none"; 
                }, 10000); 
            });

            submenu.addEventListener("mouseenter", () => {
                clearTimeout(timeout); 
            });

            submenu.addEventListener("mouseleave", () => {
                timeout = setTimeout(() => {
                    submenu.style.display = "none";
                }, 10000);
            });
        }
    });
});

</script>

