<!DOCTYPE html>
<html lang="en">
<head>
<?php include "inc/head.php" ; ?>
<title>Feedback</title>
</head>
<body>
    <?php include "inc/nav.php"; ?>

    <h1>Feedback</h1>

    <div>
        <p>
            Please let us know what you think about the webiste. A comment would be appreciated
        </p>
    </div>

    <div id = "formDiv">
        <form action="inc/config.php">
            Name: <input type="text" name = "name">
            Comment <input type="textarea" name = "comment">
        </form>
        
    </div>
</body>
</html>