<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'af8857');
define('DB_PASSWORD', 'Learning4-cerambycidae');
define('DB_NAME', 'af8857');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

//this converts the date and time into a string
function time_elapsed_string($datetime, $full = false){
    //new DatTime object
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    $string = array('y' => 'year', 'm' => 'month', 'w' => 'week', 'd' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second');
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }
    //sees if the post was posted recently
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}


    // This function will populate the comments and comments replies using a loop
    function show_comments($comments, $parent_id = -1) {
        $html = '';
        if ($parent_id != -1) {
            // If the comments are replies sort them by the "submit_date" column
            array_multisort(array_column($comments, 'submit_date'), SORT_ASC, $comments);
        }
        // Iterate the comments using the foreach loop
        foreach ($comments as $comment) {
            if ($comment['parent_id'] == $parent_id) {
                // Add the comment to the $html variable
                $html .= '
                <div class="comment">
                    <div>
                        <h3 class="name">' . htmlspecialchars($comment['name'], ENT_QUOTES) . '</h3>
                        <span class="date">' . time_elapsed_string($comment['submit_date']) . '</span>
                    </div>
                    <p class="content">' . nl2br(htmlspecialchars($comment['content'], ENT_QUOTES)) . '</p>
                    <a class="reply_comment_btn" href="#" data-comment-id="' . $comment['id'] . '">Reply</a>
                    ' . show_write_comment_form($comment['id']) . '
                    <div class="replies">
                    ' . show_comments($comments, $comment['id']) . '
                    </div>
                </div>
                ';
            }
        }
        return $html;
    }
// This function is the template for the write comment form
function show_write_comment_form($parent_id = -1) {
    $html = '
        <div class="write_comment" data-comment-id="' . $parent_id . '">
            <form>
                <input name="parent_id" type="hidden" value="' . $parent_id . '">
                <input name="name" type="text" placeholder="Your Name" required>
                <textarea name="content" placeholder="Write your comment here..." required></textarea>
                <button type="submit">Submit Comment</button>
            </form>
        </div>
        ';
        return $html;
    }
// Page ID needs to exist, this is used to determine which comments are for which page
if (isset($_GET['page_id'])) {
    // Check if the submitted form variables exist
    if (isset($_POST['name'], $_POST['content'])) {
        // POST variables exist, insert a new comment into the MySQL comments table (user submitted form)
        $stmt = $pdo->prepare('INSERT INTO comments (page_id, parent_id, name, content, submit_date) VALUES (?,?,?,?,NOW())');
        $stmt->execute([ $_GET['page_id'], $_POST['parent_id'], $_POST['name'], $_POST['content'] ]);
        exit('Your comment has been submitted!');
    }
    // Get all comments by the Page ID ordered by the submit date
    $stmt = $pdo->prepare('SELECT * FROM comments WHERE page_id = ? ORDER BY submit_date DESC');
    $stmt->execute([ $_GET['page_id'] ]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Get the total number of comments
    $stmt = $pdo->prepare('SELECT COUNT(*) AS total_comments FROM comments WHERE page_id = ?');
    $stmt->execute([ $_GET['page_id'] ]);
    $comments_info = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    exit('No page ID specified!');
}
?>
?>
<div class="comment_header">
<span class="total"><?=$comments_info['total_comments']?> comments</span>
<a href="#" class="write_comment_btn" data-comment-id="-1">Write Comment</a>
</div>

<?=show_write_comment_form()?>

<?=show_comments($comments)?>
