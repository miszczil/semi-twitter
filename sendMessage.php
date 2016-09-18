<?php
require_once 'src/connection.php';
require_once 'src/init.php';
require_once 'src/User.php';
require_once 'src/Tweet.php';
require_once 'src/Message.php';

if (!isset($_SESSION['loggedUserId'])) {

    header('Location: login.php');
} else {

    $loggedUserId = $_SESSION['loggedUserId'];

    $loggedUser = User::loadUserById($conn, $loggedUserId);

}

if ($_SERVER['REQUEST_METHOD'] === 'GET' &&
        isset($_GET['id'])) {

    $id = $_GET['id'];
    $user = User::loadUserById($conn, $id);
    $username = $user->getUsername();
    
    
} else {

    header('Location: main.php');
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
                <form action="messages.php" method="POST">
                    <div class="panel-heading">
                        <h3>Wiadomość do <?php echo $username; ?></h3>
                    </div>
                    <div class="panel-body">
                        <textarea class="form-control" name="text"></textarea>
                    </div>
                    <div class="panel-footer clearfix">
                        <input class="btn btn-primary pull-right" type="submit" value="Wyślij wiadomość">
                        <input type="hidden" name="senderId" value="<?php echo $loggedUserId; ?>">
                        <input type="hidden" name="receiverId" value="<?php echo $id; ?>">
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
