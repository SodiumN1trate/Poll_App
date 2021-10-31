<?php
    include "db.php";
    $sql = sprintf("SELECT `id`, `all_votes` FROM `poll` WHERE `generated_url` = '%s';", $_GET['url']);
    $result = mysqli_query($conn, $sql);
    $row = $result->fetch_assoc();    
    // When Request method POST
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['answer_radio_input'][0])){
            $answer_id = (int) $_POST['answer_radio_input'][0];
            $sql = sprintf("INSERT INTO `user_answers` (`user_ip`, `poll_id`, `answer_id`) VALUES ('%s', %d, %d);", $_SERVER['REMOTE_ADDR'], $row['id'], $answer_id);
            mysqli_query($conn, $sql);
            $sql = sprintf("UPDATE `poll` SET `all_votes` =  `all_votes` + 1 WHERE `id` = %d;", $row['id']);
            mysqli_query($conn, $sql);
            $sql = sprintf("UPDATE `answers` SET `answer_count` = `answer_count` + 1 WHERE `id` = %d;", $answer_id);
            mysqli_query($conn, $sql);
            echo 1 + $_POST['answer_radio_input'][0] . ". jautājums tika atzīmēts" . "<br>";
            header(sprintf("Location: /Poll_app/result.php?url=%s", $_GET['url']));
        }
        else{
            header(sprintf("Location: /Poll_app/vote.php?url=%s", $_GET['url']));
        }
    }

?>