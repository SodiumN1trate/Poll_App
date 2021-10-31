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
            <div id="result-page-container">
                <div id="title">
                    <?php
                        // Get poll object
                        $sql = sprintf("SELECT `id`, `all_votes`, `poll_name`, `answer_more_than_once` FROM `poll` WHERE `generated_url` = '%s';", $_GET['url']);
                        $result = mysqli_query($conn, $sql);
                        $row = $result->fetch_assoc();
                        echo(sprintf('<div id="title"><h1>%s</h1></div>', $row['poll_name'])); // Display poll name
                        
                        // Checks if user already voted
                        if($row['answer_more_than_once'] == 0){
                            $sql = sprintf("SELECT * FROM `user_answers` WHERE `poll_id` = %d AND `user_ip` = '%s' ;", $row['id'], $_SERVER['REMOTE_ADDR']);
                            $result = mysqli_query($conn, $sql);
                            if($result->num_rows > 0){
                                header(sprintf("Location: http://%s/Poll_app/result.php?url=%s",$_SERVER["HTTP_HOST"], $_GET['url']));
                            }
                        }

                    ?>
                </div>
                <form id="vote-container" method="POST" action="<?php echo sprintf("include/send_vote.php?url=%s", $_GET['url']) ?>">
                    <?php
                        // Get all answers to display
                        $sql = sprintf("SELECT * FROM `answers` WHERE `poll_id` = %d ;", $row['id']);
                        $all_votes = ($row['all_votes'] == 0) ? 1 : $row['all_votes'];
                        $result = mysqli_query($conn, $sql);
                        $answer_id = 1;

                        while(($row = $result->fetch_assoc())){
                            echo(sprintf('
                                <div class="vote">
                                    <input type="radio" name="answer_radio_input[0]" value="%d">
                                    <label for="answer_radio_input">%s</label>
                                </div>', $row['id'], $answer_id . '. ' . $row["Answer"]));
                            $answer_id += 1;
                        }
                    ?>
                    <button type="submit" id="submit-button">
                        Vote
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script src="static/Scripts/main.js"></script>
</body>

</html>