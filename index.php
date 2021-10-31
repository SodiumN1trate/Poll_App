<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="static/Style/stylesheet.css">
    <title>Polls App</title>
</head>
<body>
    <div id="wrapper">
        <?php
            include "include/header.php";
        ?>
        <div class="main_container">
            <form method="POST"  id="main-page-main-container"  action="<?php echo $_SERVER['PHP_SELF'];?>">
                <h1>Poll Name?</h1>
                <input type="text" name="poll_name">
                <h1>Poll answers?</h1>
                <div id="all_answer_inputs">
                    <div class="add_answer_block">
                        <input type="text" name="poll_answer[0]" class="add_answer">
                        <br>
                    </div>
                </div>
                <h1>Can user answer more than once?</h1>
                <label for="answer_more_than_once">Yes</label>
                <input type="radio" name="answer_more_than_once[0]" value="True">
                <label for="answer_more_than_once">No</label>
                <input type="radio" name="answer_more_than_once[0]" value="False">
                <br>
                <button type="submit" id="submit-button">
                    Create poll
                </button>
            </form>
            <?php

            function generateRandomString($length = 10) {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                return $randomString;
            }

            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $generated_url = generateRandomString(10);
                $error_message = "";
                $radio_input = -1; // A value of -1 means that none of the radio inputs are marked
                if(isset($_POST["answer_more_than_once"][0])){
                    if($_POST["answer_more_than_once"][0] == "True"){
                        $radio_input = 1;
                    }
                    else{
                        $radio_input = 0;
                    }
                }

                if(isset($_POST["poll_name"]) && isset($_POST["poll_answer"][1]) && $_POST["poll_answer"][1] != "" && $radio_input != -1){
                    $sql = sprintf("INSERT INTO `poll` (`owner_ip`, `poll_name`, `generated_url`, `answer_more_than_once`) VALUES ('%s', '%s', '%s', %d);", $_POST["poll_name"], $_POST["poll_name"], $generated_url, $radio_input);
                    mysqli_query($conn, $sql);
                    $result = mysqli_query($conn, sprintf("SELECT * FROM `poll` WHERE `generated_url`='%s';", $generated_url));
                    $row = $result->fetch_assoc();
                    for ($i=0; $i < count($_POST["poll_answer"]) - 1; $i++) { 
                        $sql = sprintf("INSERT INTO `answers` (`poll_id`, `Answer`) VALUES (%d,'%s');", $row['id'], $_POST["poll_answer"][$i]);
                        mysqli_query($conn, $sql);
                    }
                    header(sprintf("Location: http://%s/Poll_app/result.php?url=%s", $_SERVER["HTTP_HOST"], $generated_url));
                }   
                else{
                    $error_message = "Fill all fields!";
                }
            }
            ?>
            <div id="error-message"><?php echo (isset($error_message) !=  "") ? $error_message : "" ;?></div>
        </div>
    </div>
    <script src="static/Scripts/add_answer.js"></script>
    <script src="static/Scripts/main.js"></script>
</body>
</html>