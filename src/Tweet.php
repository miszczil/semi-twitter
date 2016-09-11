<?php

class Tweet {

    private $id;
    private $userId;
    private $text;
    private $creationDate;

    public function __construct() {
        $this->id = -1;
        $this->userId = 0;
        $this->text = 0;
        $this->creationDate = 0;
    }

    public function setUserId($newUserId) {
        $this->userId = $newUserId;
    }

    public function setText($newText) {
        $this->text = $newText;
    }

    public function setCreationDate($newDate) {
        $this->creationDate = $newDate;
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getText() {
        return $this->text;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function saveToDB(mysqli $connection) {
        
        if ($this->id == -1) {

            $sql = "INSERT INTO Tweet(user_id, text, creation_date)
                    VALUES ($this->userId, '$this->text', '$this->creationDate')";

            $result = $connection->query($sql);

            if ($result) {
                $this->id = $connection->insert_id;
                return true;
            }
        }
//        else {
//
//            $sql = "UPDATE Tweet SET user_id=$this->username,
//                                     text='$this->email',
//                                     creation_date='$this->creationDate'
//                    WHERE id=$this->id";
//
//            $result = $connection->query($sql);
//
//            if ($result) {
//                return true;
//            }
//        }

        return false;
    }

    static public function loadTweetById(mysqli $connection, $id) {

        $sql = "SELECT * FROM Tweet WHERE id=$id";

        $result = $connection->query($sql);

        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();

            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['user_id'];
            $loadedTweet->text = $row['text'];
            $loadedTweet->creationDate = $row['creation_date'];

            return $loadedTweet;
        }
    }

    static public function loadAllTweetsByUserId(mysqli $connection, $userId) {
        
        $sql = "SELECT * FROM Tweet
                WHERE user_id = $userId
                ORDER BY creation_date DESC";

        $result = $connection->query($sql);
        
        $ret = [];

        if ($result && $result->num_rows != 0) {

            foreach ($result as $row) {

                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['user_id'];
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creation_date'];

                $ret[] = $loadedTweet;
            }
        }
        
        return $ret;
    }

    static public function loadAllTweets(mysqli $connection) { 

        $sql = "SELECT * FROM Tweet ORDER BY creation_date DESC"; //DODAĆ SORTOWANIE WG DATY

        $result = $connection->query($sql);
        
        $ret = [];

        if ($result && $result->num_rows != 0) {

            foreach ($result as $row) {

                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['user_id'];
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creation_date'];

                $ret[] = $loadedTweet;
            }
        }
        
        return $ret;
    }

}
