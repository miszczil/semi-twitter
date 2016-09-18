<?php

require_once 'src/connection.php';
require_once 'src/init.php';
require_once 'src/User.php';
require_once 'src/Tweet.php';

$info = "";

if(!isset($_SESSION['loggedUserId'])) {
    
    header('Location: login.php');
    
} else {
    
    $loggedUserId = $_SESSION['loggedUserId'];
    
    $loggedUser = User::loadUserById($conn, $loggedUserId);
    
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['username']) &&
        isset($_POST['email']) &&
        isset($_POST['password1']) &&
        isset($_POST['password2'])) {


        $username = $_POST['username'];
        $email = $_POST['email'];
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];
        
        if($username == "" ||
           $email == "" ||
           $password1 == "" ||
           $password2 == "") {
            
            $info = "Uzupełnij wszystkie pola";
        
        } elseif ($password1 != $password2) {

            $info = "Hasła się nie zgadzają.";
            
        } else {

            $loggedUser->setEmail($email);
            $loggedUser->setUsername($username);
            $loggedUser->setPassword($password1);
            
            $result = $loggedUser->saveToDB($conn);
            
            if($result) {
                $info = "Edytowano Twoje dane.";
            }        
            
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl-PL">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <title></title>
    </head>
    <body>
        
        <nav class="nav navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="menu">
                    <ul class="nav navbar-nav">
                        <li><a href="main.php"><span class="glyphicon glyphicon-home"></span></a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="messages.php?id=<?php echo $loggedUserId; ?>"><span class="glyphicon glyphicon-envelope"></span></a></li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <span class="glyphicon glyphicon-user"></span> <?php echo $loggedUser->getUsername(); ?>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="editUser.php?id=<?php $loggedUserId; ?>">
                                        <span class="glyphicon glyphicon-pencil"></span> Edytuj profil
                                    </a>
                                </li>
                                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Wyloguj</a></li>
                            </ul>
                        </li>
                    </ul>                    
                </div>       
            </div>
        </nav>
        <br>

        <div class="container col-md-6 col-md-offset-3 col-sm-12">
            
            <div class="panel panel-default">
                <div class="panel-heading">Edytuj swoje dane</div>
            </div>
            
            <div class="panel panel-info">
                <form action="#" method="POST">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label" for="username">Nazwa użytkownika:</label>
                            <input class="form-control" type="text" name="username" id="username">                    
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="email">E-mail:</label>
                            <input class="form-control" type="text" name="email" id="email">                    
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="pass1">Hasło:</label>
                            <input class="form-control" type="password" name="password1" id="pass1">                    
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="pass2">Powtórz hasło:</label>
                            <input class="form-control" type="password" name="password2" id="pass2">                    
                        </div>
                    </div>
                    <div class="panel-footer clearfix">
                        <?php echo $info; ?>
                        <input class="btn btn-primary pull-right" type="submit" value="Edytuj dane">
                    </div>
                </form>
            </div>
        </div>

    </body>
</html>

