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

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
        isset($_POST['text']) &&
        isset($_POST['senderId']) &&
        isset($_POST['receiverId'])) {
    
    $newMessage = new Message();
    $newMessage->setText($_POST['text']);
    $newMessage->setCreationDate(date('Y-m-d H:i:s'));
    $newMessage->setSenderId($_POST['senderId']);
    $newMessage->setReceiverId($_POST['receiverId']);

    $result = $newMessage->saveToDB($conn);
    
}

$sentMessages = Message::loadAllMessagesBySenderId($conn, $loggedUserId);
$receivedMessages = Message::loadAllMessagesByReceiverId($conn, $loggedUserId);
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
        
        <div class="container">
            
            <div class="row">
                
                <div class="col-md-5 col-md-offset-1">
                    <div class="panel panel-default">
                        <div class="panel-heading">Odebrane</div>
                    </div>
                    
                    <div class="panel-group">
                        <?php
                        foreach ($receivedMessages as $message) {

                            $messageId = $message->getId();
                            $senderId = $message->getSenderId();
                            $sender = User::loadUserById($conn, $senderId)->getUsername();
                            $creationDate = $message->getCreationDate();
                            $text = $message->getText();
                            $read = $message->isRead();


                            echo "<div class='panel panel-default'>
                                    <div class='panel-heading'>
                                        Od: <a href='userInfo.php?id=$senderId'>$sender</a>                                    
                                        <a href='readMessage.php?id=$messageId' class='pull-right'>$creationDate</a>
                                    </div>
                                    <div class='panel-body'>";
                                        if($read) {
                                            echo $text;
                                        } else {
                                            echo "<p class='bg-info'>$text</p>";
                                        }
                                    echo "</div>";
                            echo "<div class='panel-footer'>
                                    <a href='readMessage.php?id=$messageId'>Pokaż całość</a>
                                  </div>     
                                </div>";
                        }
                ?>
                    </div>
                </div>
                
                <div class="col-md-5">
                    <div class="panel panel-default">
                        <div class="panel-heading">Wysłane</div>
                    </div>
                    
                    <div class="panel-group">
                        <?php
                        foreach ($sentMessages as $message) {

                            $messageId = $message->getId();
                            $receiverId = $message->getReceiverId();
                            $receiver = User::loadUserById($conn, $receiverId)->getUsername();
                            $creationDate = $message->getCreationDate();
                            $text = $message->getText();


                            echo "<div class='panel panel-default'>
                                    <div class='panel-heading'>
                                        Od: <a href='userInfo.php?id=$receiverId'>$receiver</a>                                    
                                        <a href='readMessage.php?id=$messageId' class='pull-right'>$creationDate</a>
                                    </div>
                                    <div class='panel-body'>";
                                        if($read) {
                                            echo $text;
                                        } else {
                                            echo "<p class='bg-info'>$text</p>";
                                        }
                                    echo "</div>";
                            echo "<div class='panel-footer'>
                                    <a href='readMessage.php?id=$messageId'>Pokaż całość</a>
                                  </div>     
                                </div>";
                        }
                ?>
                    </div>
                    
                </div>
                
            </div>
            
        </div>

    </body>
</html>
