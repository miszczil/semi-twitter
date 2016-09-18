<?php

require_once 'src/connection.php';
require_once 'src/init.php';
require_once 'src/User.php';
require_once 'src/Tweet.php';
require_once 'src/Comment.php';

if(!isset($_SESSION['loggedUserId'])) {
    
    header('Location: login.php');
    
} else {
    
    $loggedUserId = $_SESSION['loggedUserId'];
    
    $loggedUser = User::loadUserById($conn, $loggedUserId);

}

if($_SERVER['REQUEST_METHOD'] === 'GET' &&
   isset($_GET['id'])) {
    
    $userId = $_GET['id'];
    $user = User::loadUserById($conn, $userId);
    $username = $user->getUsername();
    
    $allUserTweets = Tweet::loadAllTweetsByUserId($conn, $userId);
    
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
            
            <?php
            
            echo "<div class='jumbotron'>
                    <h2>$username</h2>";
            
            if($userId != $loggedUserId) {
                
                echo "<a href='sendMessage.php?id=$userId'>
                            <button class='btn btn-primary' type='button'>Wyślij wiadomość</button>
                        </a>";
                
            }
                            
            echo "</div>";
            
            ?>
            
            <div class="panel-group">
            
                <?php

                foreach ($allUserTweets as $tweet) {

                    $tweetId = $tweet->getId();
                    $creationDate = $tweet->getCreationDate();
                    $text = $tweet->getText();
                    $commentsNo = count(Comment::loadAllCommentsByTweetId($conn, $tweetId));


                    echo "<div class='panel panel-default'>
                            <div class='panel-heading'>
                                <a href='userInfo.php?id=$userId'>$username</a>
                                <a href='tweetInfo.php?id=$tweetId' class='pull-right'>$creationDate</a>
                            </div>
                            <div class='panel-body'>
                                $text
                            </div>
                            <div class='panel-footer'>
                                Komentarze: <a href='tweetInfo.php?id=$tweetId#comments'>$commentsNo</a>
                            </div>
                        </div>";
                }
                ?>
            </div>
        </div>

    </body>
</html>
