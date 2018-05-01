<!DOCTYPE html>
<html lang="en" ng-app='signUp'>

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
    <div class="text-vertical-center"  ng-controller="signUpController">
        <h1>Welcome to EasyBuy</h1>
        <h3>Your First-choice Shopping Site</h3>
        <br>
        <form class="form-horizontal" style="margin: 0 auto; width:250px"  method="post" action="signup.php">
            <fieldset>
                <div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">Username</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="inputEmail" placeholder="Username" name="username">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 control-label">Email</label>
                    <div class="col-lg-10">
                        <input type="email" class="form-control" id="inputPassword" placeholder="Email" name="email" >

                    </div><br>
                    <input type="checkbox" value="agree" name="checked"> Agree with <a href="#">terms & conditions</a>

                </div><br>

                <div class="form-group">
                    <div class="col-lg-10 col-lg-offset-2">
                        <button type="submit" class="btn btn-primary" style="width:100px" >Sign Up</button>
                    </div><br>

                </div>
            </fieldset>
        </form>

<!--            <div class="alert alert-dismissible alert-info" style="width:100%" ng-if="signup">-->
<!--<form method="post" action="signup.php">-->
<!--    Username:   <input type="text" name="username"/>-->
<!--    <br/>-->
<!--    Password:   <input type="password" name="password"/>-->
<!--    <br/>-->
<!--    Email:      <input type="email" name ="email"/>-->
<!--    <br/>-->
<!--    <input type="submit" value="Sign Up"/>-->
<!--</form>-->

<?php

require_once "db_connect.php";

$username = $mysqli->real_escape_string($_POST['username']);
//$password = $mysqli->real_escape_string($_POST['password']);
//$password = hash('SHA512', $password);

$email = $mysqli->real_escape_string($_POST['email']);
$checked = $_POST['checked'];
$signup_date = date('Y-m-d H:i:s', time());

if(empty($checked) && !empty($username) && !empty($email)){

    exit('<div class="alert alert-dismissible alert-info" style="width:100%"><div style="font-size:30px"> Please check <u>terms and conditions</u>!</div>');
}

// if none of the required fields is empty
if(!empty($username)  && !empty($email) && !empty($checked)){
    ?>
        <div class="alert alert-dismissible alert-info" style="width:100%">
<?php
        // check if user already exists
    $sql_user = "SELECT * FROM Users
                WHERE username = '$username'";

    $results_user = $mysqli->query($sql_user);

    if(!$results_user){
        exit($mysqli->error);
    }

    // check if username is taken
    if($results_user->num_rows > 0){
        exit(" <div style='font-size:30px'><strong>$username</strong> is already taken!</div> ");
    }

    // add user to database
    $sql = "INSERT INTO Users(username, password, email, signup_date)
            VALUES ('$username', '$password', '$email', '$signup_date')";

    $results = $mysqli->query($sql);

    if(!$results){
        exit($mysqli->error);
    }else {
        $to = $email;
        $subject = "Thanks for your registration with EasyBuy!";
        $msg = "Hello\r\nYou successfully registered with username: $username";

        if (mail($to, $subject, $msg)) {
            echo "<div style='font-size:30px'>Email was sent to $to <br>";
        } else {
            echo "Error: Email was not sent";
        }
        $sql_userId = "SELECT * FROM Users
                WHERE username = '$username'
                AND email = '$email'";
        $results_userId = $mysqli->query($sql_userId);
        $row = $results_userId->fetch_array(MYSQLI_ASSOC);
        $userId = $row['user_id'];

        exit("User <strong>$username </strong> successfully registered. <a href='profile.php?user_id=$userId' color='blue'>Login</a></div>");
    }
}
?></div>

    </div>
</header>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.js"></script>
<script src="signUpController.js"></script>
</body>
</html>