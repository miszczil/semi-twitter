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
    
    echo "Zalogowano jako <strong>" .  $loggedUser->getUsername() . "</strong><br>";
    echo "<a href='logout.php'>Wyloguj</a><br>";
    
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
        <link rel="stylesheet" href="css/style.css">
              <title></title>
    </head>
    <body>
          
            <a href="main.php">Wróć do strony głównej</a>
        <table>

<!--            <form action="#" method="POST">
                <tr>
                    <td colspan="2">
                        <input class="tweetText" type="textarea" name="text">
                    </td>
                </tr>
                <tr>
                    <td class="tableButton" colspan="2">
                        <input class="tableButton" type="submit" value="Dodaj nowy tweet">
                    </td>
                </tr>
                <tr><td class='border'></td></tr>

            </form>-->
            <?php
            
            echo "<tr>";
            echo "<td class='border'>
                    <span class='username'>$username</span>
                </td>";
            
            if($userId != $loggedUserId) {
                
                echo "<td class='border'>
                        <a href='sendMessage.php?id=$userId'>
                            <button class='tableButton' type='button'>Wyślij wiadomość</button>
                        </a>
                    </td>";
                
            }
                            
            echo "</tr>";
            
            foreach ($allUserTweets as $tweet) {

                $tweetId = $tweet->getId();
                $creationDate = $tweet->getCreationDate();
                $text = $tweet->getText();
                $commentsNo = count(Comment::loadAllCommentsByTweetId($conn, $tweetId));
                
                
                echo "<tr class='tweetInfo'>
                    <td><a href='userInfo.php?id=$userId'>$username</a></td>
                    <td><a href='tweetInfo.php?id=$tweetId'>$creationDate</a></td>
                    </tr>";
                echo "<tr class='tweetText'>
                    <td colspan='2'>$text</td>
                    </tr>";
                echo "<tr class='tweetInfo'>
                    <td colspan='2'>Komentarze: <a href='tweetInfo.php?id=$tweetId#comments'>$commentsNo</a></td>
                    </tr>";
                echo "<tr><td class='border'></td></tr>";

            }
            ?>
        </table>

    </body>
</html>
