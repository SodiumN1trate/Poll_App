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
                <?php
                    // Get poll object
                    $sql = sprintf("SELECT `id`, `all_votes`, `poll_name` FROM `poll` WHERE `generated_url` = '%s';", $_GET['url']);
                    $result = mysqli_query($conn, $sql);
                    $row = $result->fetch_assoc();
                    echo(sprintf('<div id="title"><h1>%s</h1></div>', $row['poll_name'])); // Display poll name
                ?>
                <div id="results-container">
                    <?php
                        $sql = sprintf("SELECT * FROM `answers` WHERE `poll_id` = %d ;", $row['id']);
                        $result = mysqli_query($conn, $sql);
                        $all_votes = ($row['all_votes'] == 0) ? 1 : $row['all_votes'];
                        $answer_id = 1;

                        while(($row = $result->fetch_assoc())){
                            $percent = ($row["answer_count"] *  100) / $all_votes;
                            echo(sprintf('
                                <div class="result">
                                    <span>%s</span>
                                    <div class="result-bar" style="width: %s">
                                        %s
                                    </div>
                                </div>',$answer_id . '. ' . $row["Answer"], round($percent, 0) . "%", round($percent, 0) . "%"));
                            $answer_id += 1;
                        }

                    ?>
                </div>
                <hr>
                <div id="invite-link">
                    <div id="title">
                        <h1>Invite-link</h1>
                    </div>
                    <div class="invite-link-field">
                        <?php 
                            echo(sprintf("http://%s/Poll_app/vote.php?url=%s", $_SERVER["HTTP_HOST"], $_GET['url']));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>