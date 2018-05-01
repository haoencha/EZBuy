<?php
    require_once "db_connect.php";
    $user_id = $_GET['user_id'];

if(empty($user_id)) {

        echo "<div style='text-align:center; font-size:30px; margin:20px;'>Please enter login credentials.</div>";
        include "login_form.php";
        exit();
}

?>
    <link href="https://bootswatch.com/flatly/bootstrap.min.css" rel="stylesheet">
    <ul class="breadcrumb">
        <li class="active" style="font-size:30px">EasyBuy</li>
    </ul>
    <div style="width:70%; margin: 0 auto">
    <form class="form-horizontal" method="POST" action="upload.php?user_id= <?php echo $user_id?>" enctype="multipart/form-data">
        <fieldset>
            <div class="form-group">
                <label for="inputEmail" class="col-lg-2 control-label">Product Name:</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" id="inputEmail" name="productName">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword" class="col-lg-2 control-label">Price:</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" id="inputPassword"  name="price">
                </div>
            </div>
            <div class="form-group">
                <label for="textArea" class="col-lg-2 control-label">Description:</label>
                <div class="col-lg-10">
                    <textarea class="form-control" rows="5" id="textArea" name="description"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail" class="col-lg-2 control-label">Image:</label>
                <div class="col-lg-10 col-lg-offset-2">
                    <input type="file" name="image" style="margin-top:5px"/>
                    <input type="submit" name="submit" class="btn btn-primary" value="Upload" style="float:right"/>
                </div>
            </div>
        </fieldset>

<?php

if(isset($_POST['submit'])) {
    if (empty($_POST['productName'])) {
        exit("<div class='alert alert-dismissible alert-danger' style='text-align:center'>Please fill out the product name!</div>");
    }
    if (empty($_POST['price'])) {
        exit("<div class='alert alert-dismissible alert-danger' style='text-align:center'>Please fill out the product price!</div>");
    }
    if (empty($_POST['description'])) {
        exit("<div class='alert alert-dismissible alert-danger' style='text-align:center'>Please fill out the product description!</div>");
    }
    if(getimagesize($_FILES['image']['tmp_name'])==FALSE){
        exit("<div class='alert alert-dismissible alert-danger' style='text-align:center'>Please select an image!</div>");
    }
    else {

        $image = addslashes($_FILES['image']['tmp_name']);
        $name = addslashes($_FILES['image']['name']);
        $image = file_get_contents($image);
        $image = base64_encode($image);
        $productName = $_POST['productName'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $user_id = $_GET['user_id'];

        saveItem($productName, $price, $description, $user_id, $name, $image);
    }
}
    function saveItem($productName, $price, $description, $user_id, $name, $image){

        $host = "uscitp.com";
        $username = "haoencha_user";
        $password = "usc2015";
        $database = "haoencha_finalProject_users_db";

        $mysqli = new mysqli($host, $username, $password, $database);

        if($mysqli->error){
            exit($mysqli->error);
        }
        $description = $mysqli->real_escape_string($description);
        $productName = $mysqli->real_escape_string($productName);

        $sql = "INSERT INTO AllItems (item_name, price, description, image, image_name, user_info)
                VALUES ('$productName', '$price', '$description', '$image', '$name', $user_id)";

        $result = $mysqli->query($sql);

        if($result){
            echo "<br><div style='text-align:center; font-size:25px'> Item uploaded! <a href='profile.php' style=' margin-left:15px'><u>Back</u></a></div>";
        }else{
            echo "<br><div style='text-align:center; font-size:25px'> Item not uploaded".$mysqli->error."
            <a href='profile.php' style=' margin-left:15px'><u>Back</u></a></div>";

        }
    }

?>

    </form>
    </div>
