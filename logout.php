<?php
session_start();
$username = $_SESSION['username'];
if(empty($username)) {

    echo "<div style='text-align:center; font-size:30px; margin:20px;'>Please enter login credentials.</div>";
    include "login_form.php";
    exit();
}
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>EasyBuy</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://bootswatch.com/flatly/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/stylish-portfolio.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>


<!-- Header -->
<header id="top" class="header">
    <div class="text-vertical-center">
        <h1>Thanks for visiting EasyBuy</h1>
        <div style="margin-top:100px; font-size:25px">

<?php
echo "$username was logged out.<br><br><a href='login_form.php' class='btn btn-success'>Back to Login</a>";

?>
            </div>
    </div>
</header>
