<?php

require_once "db_connect.php";

session_start();

$first_page = 1;
$results_per_page = 10;
$page_num = $_GET['page'];
if(empty($_SESSION['logged_in'])) {
    $username = $mysqli->real_escape_string($_POST ['username']);
    $password = $mysqli->real_escape_string($_POST['pass']);
    $password = hash('SHA512', $password);

    if (empty($username) || empty($password)) {
        echo "<div style='text-align:center; font-size:30px; margin:20px;'>Please enter login credentials.</div>";
        include "login_form.php";
        exit();
    }else{
        $sql = "SELECT * FROM Users WHERE username = '$username'";

        $results = $mysqli->query($sql);

        if(!$results){
            exit($mysqli->error);
        }

        // will only get one record, so no need to loop
        $row = $results->fetch_array(MYSQLI_ASSOC);

        if ($password == $row['password']) {
            // Logged In
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
        } else {
            echo "<div style='text-align:center; font-size:30px; margin:20px'>Invalid login information!</div>";
            include "login_form.php";
            exit();
        }
    }
}

?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <link href="https://bootswatch.com/flatly/bootstrap.min.css" rel="stylesheet">


</head>
<body>
<ul class="breadcrumb">
    <li class="active" style="font-size:30px">EasyBuy</li>
</ul>
<?php
$greet_user= $_SESSION['username'];
echo "<div style='margin-left:800px'>Hi! $greet_user | <a href='logout.php'>Logout</a></div>"
?>
<div class="col-lg-3 col-md-3 col-sm-4" style='width:200px; position:fixed'>
    <div class="list-group table-of-contents">
        <a class="list-group-item" href="profile.php">All</a>
        <a class="list-group-item" href="schoolSupplies.php">School Supplies</a>
        <a class="list-group-item" href="homeSupplies.php">Home Supplies</a>
        <a class="list-group-item" href="entertainment.php">Entertainment</a>
        <a class="list-group-item" href="otherItems.php">Others</a>
        <a class="list-group-item" href="favoriteInsert.php">Favorites</a>

    </div>
</div>

<div class="rightColumn" style="margin-left:250px">

    <form method="POST" action="otherItems.php">
        <input type="text" name="searchItem" placeholder="Search a product" style="width:200px; padding: 5px;
  border: solid 2px #dcdcdc;
  transition: box-shadow 0.3s, border 0.3s;">
    </form>

    <?php

    $username= $_SESSION['username'];

    $sql_userId = "SELECT * FROM Users
                WHERE username = '$username'";
    //echo $username;
    $results_userId = $mysqli->query($sql_userId);
    $row = $results_userId->fetch_array(MYSQLI_ASSOC);
    $userId = $row['user_id'];
    echo "<a href='upload_otherItems.php?user_id=$userId' class='label label-success'>Upload an item</a><br><br><br>";

    ?>

    <?php


    if($mysqli->error){
        exit($mysqli->error);
    }

    $sqlItems = "SELECT * FROM OtherItems  ORDER BY item_id DESC";

    $results_items = $mysqli->query($sqlItems);

    $num_results = $results_items->num_rows;

    // ceiling of Total # of results / # of results per page
    $last_page = ceil($num_results/ $results_per_page);

    if(empty($page_num)){
        $page_num = $first_page;
    }else{
        if($page_num < $first_page){
            $page_num = $first_page;
        }elseif($page_num > $last_page){
            $page_num = $last_page;
        }
    }
    $start_index = ($page_num-1) * $results_per_page;

    $sqlItems = $sqlItems. " LIMIT $start_index, $results_per_page";

    $results_items = $mysqli->query($sqlItems);
    if(!$results_items){
        exit($mysqli->error);
    }
    else{
        if($_POST['searchItem']=='') {
            while ($row = $results_items->fetch_array(MYSQLI_ASSOC)) {


                echo "<img height='300' src='data:image;base64," . $row['image'] . "'><br><br><strong>Product Name: </strong>" . $row['item_name'] . "<br>
                       <strong>Price: </strong>" . $row['price'] . "<br>
                       <strong> Description: </strong>" . $row['description'] . "<br>";
                $user_info = $row['user_info'];
                $sqlUsers = "SELECT * FROM Users, OtherItems
                                    WHERE Users.user_id = $user_info";
                $results_users = $mysqli->query($sqlUsers);
                $rowUser = $results_users->fetch_array(MYSQLI_ASSOC);
                echo "<strong> Seller Info: </strong><ul><li>Username: <u>" . $rowUser['username'] . "</u></li><li>Email: <u>
                       " . $rowUser['email'] . "</u></li></ul>
                       <form method='POST' action='favoriteInsert.php'>

                       <input type='hidden' name='item_name' value=\"".$row['item_name']."\">
                       <input type='hidden' name='price' value=\"".$row['price']."\">
                       <input type='hidden' name='description' value=\"".$row['description']."\">
                       <input type='hidden' name='user_info' value=\"".$user_info."\">
                       <input type='hidden' name='image' value=\"".$row['image']."\">
                       <input type='hidden' name='username' value=\"".$rowUser['username']."\">
                       <input type='hidden' name='email' value=\"".$rowUser['email']."\">";
                $item_name = $mysqli->real_escape_string($row['item_name']);
                $sql_item_name = "SELECT * FROM Favorites WHERE item_name = '$item_name' AND user_info = '$userId'";
                $results_item_name = $mysqli->query($sql_item_name);
                if($results_item_name->num_rows == 0){
                    echo "
                                    Rating:
                                <select name='rating'>
                                <option value='n/a'></option>
                                <option value='&#9733'>&#9733</option>
                                <option value='&#9733&#9733'>&#9733&#9733</option>
                                <option value='&#9733&#9733&#9733'>&#9733&#9733&#9733</option>
                                <option value='&#9733&#9733&#9733&#9733'>&#9733&#9733&#9733&#9733</option>
                                <option value='&#9733&#9733&#9733&#9733&#9733'>&#9733&#9733&#9733&#9733&#9733</option>
                                </select>
                                <button type='submit' class='btn btn-primary btn-sm' style='margin-left:50px'>Add to Favorites</button>
                                </form><br><br><br>";
                }
                else{
                    echo "</form><br><br><br>";
                }?>
                <?php

            }
    ?></div><div style="text-align:center">
    <?php
    if($page_num > $first_page) {

        ?>


        <a href="<?php echo $_SERVER['PHP_SELF'] . "?page=$first_page" ?>">[<< First]</a>

        <a href="<?php echo $_SERVER['PHP_SELF'] . "?page=".($page_num - 1) ?>">[< Previous]</a>
        <?php
    }
    ?>
    <?php echo $page_num; ?>

    <?php
    if($page_num < $last_page) {
        ?>

        <a href="<?php echo $_SERVER['PHP_SELF'] . "?page=" . ($page_num + 1) ?>">[Next >]</a>
        <a href="<?php echo $_SERVER['PHP_SELF'] . "?page=$last_page" ?>">[Last >>]</a>

        <?php
    }
    ?></div> <?php


}else{
            $searchResult = $_POST['searchItem'];
            $sqlSearchItems = "SELECT * FROM OtherItems WHERE item_name LIKE '%$searchResult%'  ORDER BY item_id DESC";

            $results_SearchItems = $mysqli->query($sqlSearchItems);
            if (!$results_SearchItems) {
                exit($mysqli->error);
            }
            while ($row = $results_SearchItems->fetch_array(MYSQLI_ASSOC)) {

                echo "<img height='300' src='data:image;base64," . $row['image'] . "'><br><br><strong>Product Name: </strong>" . $row['item_name'] . "<br>
                       <strong>Price: </strong>" . $row['price'] . "<br>
                       <strong> Description: </strong>" . $row['description'] . "<br>";
                $user_info = $row['user_info'];
                $sqlUsers = "SELECT * FROM Users, OtherItems
                                    WHERE Users.user_id = $user_info";
                $results_users = $mysqli->query($sqlUsers);
                $rowUser = $results_users->fetch_array(MYSQLI_ASSOC);
                echo "<strong> Seller Info: </strong><ul><li>Username: <u>" . $rowUser['username'] . "</u></li><li>Email: <u>
                       " . $rowUser['email'] . "</u></li></ul>
                       <form method='POST' action='favoriteInsert.php'>

                       <input type='hidden' name='item_name' value=\"".$row['item_name']."\">
                       <input type='hidden' name='price' value=\"".$row['price']."\">
                       <input type='hidden' name='description' value=\"".$row['description']."\">
                       <input type='hidden' name='user_info' value=\"".$user_info."\">
                       <input type='hidden' name='image' value=\"".$row['image']."\">
                       <input type='hidden' name='username' value=\"".$rowUser['username']."\">
                       <input type='hidden' name='email' value=\"".$rowUser['email']."\">";
                $item_name = $mysqli->real_escape_string($row['item_name']);
                $sql_item_name = "SELECT * FROM Favorites WHERE item_name = '$item_name' AND user_info = '$userId'";
                $results_item_name = $mysqli->query($sql_item_name);
                if($results_item_name->num_rows == 0){
                    echo "
                                    Rating:
                                <select name='rating'>
                                <option value='n/a'></option>
                                <option value='&#9733'>&#9733</option>
                                <option value='&#9733&#9733'>&#9733&#9733</option>
                                <option value='&#9733&#9733&#9733'>&#9733&#9733&#9733</option>
                                <option value='&#9733&#9733&#9733&#9733'>&#9733&#9733&#9733&#9733</option>
                                <option value='&#9733&#9733&#9733&#9733&#9733'>&#9733&#9733&#9733&#9733&#9733</option>
                                </select>
                                <button type='submit' class='btn btn-primary btn-sm' style='margin-left:50px'>Add to Favorites</button>
                                </form><br><br><br>";
                }
                else{
                    echo "</form><br><br><br>";
                }

            }
            if($results_SearchItems->num_rows==0){
                echo "<br> No item is found based on your search!";
            }
        }
    }
    ?>


<br/>

</body>
</html>