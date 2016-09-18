<?php

require_once 'src/connection.php';
require_once 'src/init.php';
require_once 'src/User.php';
require_once 'src/Tweet.php';

$info = "";

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
            
        } elseif (User::loadUserByEmail($conn, $email) != null) {

            $info = "Ten e-mail jest już zajęty.";
            
        } elseif ($_POST['password1'] != $_POST['password2']) {

            $info = "Hasła się nie zgadzają.";
            
        } else {

            $newUser = new User();
            $newUser->setEmail($email);
            $newUser->setUsername($username);
            $newUser->setPassword($password1);
            
            $result = $newUser->saveToDB($conn);
            
            if($result) {
                $info = "Dodano do bazy użytkownika <strong>" . $newUser->getUsername() . "</strong>";
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
        <link rel="stylesheet" href="css/style.css"
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
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#">Załóż konto</a></li>
                        <li><a href="login.php">Zaloguj</a></li>
                    </ul>                    
                </div>       
            </div>
        </nav>
        <br>
        

        <div class="container col-md-6 col-md-offset-3 col-sm-12">
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
                        <input class="btn btn-primary pull-right" type="submit" value="Załóż konto">
                    </div>
                </form>
            </div>
        </div>



    </body>
</html>

