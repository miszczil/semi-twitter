<?php
require_once 'src/connection.php';
require_once 'src/init.php';
require_once 'src/User.php';
require_once 'src/Tweet.php';
require_once 'src/Comment.php';

if (!isset($_SESSION['loggedUserId'])) {

    header('Location: login.php');
} else {

    $loggedUserId = $_SESSION['loggedUserId'];

    $loggedUser = User::loadUserById($conn, $loggedUserId);
}

if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $tweet = Tweet::loadTweetById($conn, $id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
            isset($_POST['text'])) {

        $newComment = new Comment();
        $newComment->setText($_POST['text']);
        $newComment->setCreationDate(date('Y-m-d H:i:s'));
        $newComment->setAuthorId($loggedUserId);
        $newComment->setTweetId($id);

        $newComment->saveToDB($conn);
    }

    $comments = Comment::loadAllCommentsByTweetId($conn, $id);
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
            <div class="jumbotron page-header">
            <div class="panel panel-info">

            <?php
            $userId = $tweet->getUserId();
            $username = User::loadUserById($conn, $userId)->getUsername();
            $creationDate = $tweet->getCreationDate();
            $text = $tweet->getText();

            echo "<div class='panel-heading'>
                    <a href='userInfo.php?id=$userId'>$username</a>
                    <span class='pull-right'>$creationDate</span>
                  </div>";
            echo "<div class='panel-body'>
                    $text
                   </div>";
            ?>
            </div>
            </div>
            <div class="panel-group" id="comments">
                <div class="panel panel-default">
                <form action="#" method="POST">
                    <div class="panel-body">
                        <textarea class="form-control input-lg" maxlength="140" name="text"></textarea>
                    </div>
                    <div class="panel-footer clearfix">
                        <input class="btn btn-primary pull-right" type="submit" value="Skomentuj">
                    </div>
               </form>
            </div>
                

            </form>
            <?php
            foreach ($comments as $comment) {

                $commenterId = $comment->getAuthorId();
                $username = User::loadUserById($conn, $commenterId)->getUsername();
                $creationDate = $comment->getCreationDate();
                $text = $comment->getText();

                echo "<div class='panel panel-default'>
                        <div class='panel-heading'>
                            <a href='userInfo.php?id=$commenterId'>$username</a></td>
                            <span class='pull-right'>$creationDate</span>
                        </div>
                        <div class='panel-body'>
                            $text
                        </div>
                    </div>";
            }
            ?>
        </div>

    </body>
</html>
