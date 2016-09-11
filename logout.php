<?php

require_once 'src/connection.php';
require_once 'src/init.php';
require_once 'src/User.php';
require_once 'src/Tweet.php';

unset($_SESSION['loggedUserId']);

header('Location: login.php');
