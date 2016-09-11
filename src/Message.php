<?php

class Message {

    private $id;
    private $senderId;
    private $receiverId;
    private $creationDate;
    private $text;
    private $read;

    public function __construct() {
        $this->id = -1;
        $this->senderId = 0;
        $this->receiverId = 0;
        $this->creationDate = 0;
        $this->text = 0;
        $this->read = 0;
    }

    public function setSenderId($newSenderId) {
        $this->senderId = $newSenderId;
    }

    public function setReceiverId($newReceiverId) {
        $this->receiverId = $newReceiverId;
    }

    public function setCreationDate($newCreationDate) {
        $this->creationDate = $newCreationDate;
    }

    public function setText($newText) {
        $this->text = $newText;
    }

    public function readMessage() {
        $this->read = 1;
    }

    public function getId() {
        return $this->id;
    }

    public function getSenderId() {
        return $this->senderId;
    }

    public function getReceiverId() {
        return $this->receiverId;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getText() {
        return $this->text;
    }

    public function isRead() {
        return $this->read;
    }

    public function saveToDB(mysqli $connection) {

        if ($this->id == -1) {

            $sql = "INSERT INTO Message(sender_id, receiver_id, creation_date, text, `read`)
                    VALUES ($this->senderId, $this->receiverId, '$this->creationDate', '$this->text', $this->read)";

            $result = $connection->query($sql);

            if ($result) {
                $this->id = $connection->insert_id;
                return true;
            }
        }

        return false;
    }

    static public function loadMessageById(mysqli $connection, $id) {

        $sql = "SELECT * FROM Message WHERE id=$id";

        $result = $connection->query($sql);

        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();

            $loadedMessage = new Message();
            $loadedMessage->id = $row['id'];
            $loadedMessage->senderId = $row['sender_id'];
            $loadedMessage->receiverId = $row['receiver_id'];
            $loadedMessage->creationDate = $row['creation_date'];
            $loadedMessage->text = $row['text'];
            $loadedMessage->read = $row['read'];
            

            return $loadedMessage;
        }
    }

    static public function loadAllMessagesBySenderId(mysqli $connection, $senderId) {

        $sql = "SELECT * FROM Message
                    WHERE sender_id = $senderId
                    ORDER BY creation_date DESC";

        $result = $connection->query($sql);

        $ret = [];

        if ($result && $result->num_rows != 0) {

            foreach ($result as $row) {

                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->senderId = $row['sender_id'];
                $loadedMessage->receiverId = $row['receiver_id'];
                $loadedMessage->creationDate = $row['creation_date'];
                $loadedMessage->text = substr($row['text'], 0, 30) . '...';
                $loadedMessage->read = $row['read'];

                $ret[] = $loadedMessage;
            }
        }

        return $ret;
    }
    
    static public function loadAllMessagesByReceiverId(mysqli $connection, $receiverId) {

        $sql = "SELECT * FROM Message
                    WHERE receiver_id = $receiverId
                    ORDER BY creation_date DESC";

        $result = $connection->query($sql);

        $ret = [];

        if ($result && $result->num_rows != 0) {

            foreach ($result as $row) {

                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->senderId = $row['sender_id'];
                $loadedMessage->receiverId = $row['receiver_id'];
                $loadedMessage->creationDate = $row['creation_date'];
                $loadedMessage->text = substr($row['text'], 0, 30) . '...';
                $loadedMessage->read = $row['read'];

                $ret[] = $loadedMessage;
            }
        }

        return $ret;
    }


}
