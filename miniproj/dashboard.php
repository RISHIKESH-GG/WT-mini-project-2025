<?php
session_start();
require_once 'db_connect.php';

// --- FIX: HANDLE LOGIN FIRST ---
// Check if the user just came from the login page
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_username'])) {
    $_SESSION['username'] = $_POST['login_username'];
}

// --- NOW CHECK SESSION ---
// If still no username, THEN kick them out
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: index.php");
    exit;
}
$current_user = $_SESSION['username'];

// --- HANDLE RATING SUBMISSION ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['review_id']) && isset($_POST['rating'])) {
    $review_id = (int)$_POST['review_id'];
    $new_rating = (int)$_POST['rating'];

    // Get existing ratings
    $stmt = $conn->prepare("SELECT rated_by_users FROM reviews WHERE review_id = ?");
    $stmt->bind_param("i", $review_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $stmt->close();

        $rated_by_users = json_decode($row['rated_by_users'] ?: '{}', true);
        $rated_by_users[$current_user] = $new_rating;
        $updated_json = json_encode($rated_by_users);

        // Update DB
        $stmt = $conn->prepare("UPDATE reviews SET rated_by_users = ? WHERE review_id = ?");
        $stmt->bind_param("si", $updated_json, $review_id);
        $stmt->execute();
        $stmt->close();
    }
    // Refresh page
    header("location: dashboard.php?current_review_id=" . $review_id);
    exit;
}

// --- GET MOVIE TO DISPLAY ---
$current_review = null;
$display_id = isset($_GET['current_review_id']) ? (int)$_GET['current_review_id'] : 0;

$all_ids = [];
$result = $conn->query("SELECT review_id FROM reviews");
while ($row = $result->fetch_assoc()) { $all_ids[] = $row['review_id']; }

if (!empty($all_ids)) {
    // Pick specific ID or Random ID
    $id_to_show = ($display_id && in_array($display_id, $all_ids)) ? $display_id : $all_ids[array_rand($all_ids)];
    
    $stmt = $conn->prepare("SELECT * FROM reviews WHERE review_id = ?");
    $stmt->bind_param("i", $id_to_show);
    $stmt->execute();
    $current_review = $stmt->get_result()->fetch_assoc();
}

// Get User Rating
$user_rating = 0;
if ($current_review && $current_review['rated_by_users']) {
    $arr = json_decode($current_review['rated_by_users'], true);
    if (isset($arr[$current_user])) $user_rating = $arr[$current_user];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ReviewHub</title>
    <style>
        /* CSS MATCHING YOUR IMAGE */
        body { font-family: Arial, sans-serif; background-color: #f0f2f5; text-align: center; margin: 0; height: 100vh; display: flex; justify-content: center; align-items: center; position: relative; }
        .logout-btn { position: absolute; top: 20px; right: 20px; background: #999; color: white; padding: 10px 20px; text-decoration: none; border-radius: 20px; font-weight: bold; }
        .main-card { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h1 { text-transform: uppercase; color: #333; margin-bottom: 20px; }
        .poster-box { width: 300px; height: 450px; background: #ccc; margin: 0 auto 30px; border-radius: 10px; overflow: hidden; display: flex; align-items: center; justify-content: center; }
        .poster-box img { width: 100%; height: 100%; object-fit: cover; }
        .buttons { display: flex; gap: 15px; justify-content: center; }
        .circle-btn { width: 50px; height: 50px; border-radius: 50%; background: #999; color: white; font-size: 20px; font-weight: bold; display: flex; align-items: center; justify-content: center; cursor: pointer; text-decoration: none; border: none; }
        .circle-btn:hover { background: #777; }
        .circle-btn.active { background: #3498db; } /* Blue for selected */
        .next-link { display: block; margin-top: 20px; color: #555; text-decoration: none; }
    </style>
</head>
<body>

    <a href="logout.php" class="logout-btn">LOGOUT</a>

    <?php if ($current_review): ?>
    <div class="main-card">
        <h1><?php echo htmlspecialchars($current_review['title']); ?></h1>
        
        <div class="poster-box">
            <img src="<?php echo htmlspecialchars($current_review['poster_url']); ?>" alt="Poster">
        </div>

        <div class="buttons">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <a href="#" onclick="document.getElementById('rate-<?php echo $i; ?>').submit(); return false;" 
                   class="circle-btn <?php echo ($user_rating == $i) ? 'active' : ''; ?>">
                   <?php echo $i; ?>
                </a>
                <form id="rate-<?php echo $i; ?>" method="POST" style="display:none;">
                    <input type="hidden" name="review_id" value="<?php echo $current_review['review_id']; ?>">
                    <input type="hidden" name="rating" value="<?php echo $i; ?>">
                </form>
            <?php endfor; ?>
        </div>

        <a href="dashboard.php" class="next-link">Next Movie ➡</a>
    </div>
    <?php else: ?>
        <h2>No movies found in database.</h2>
    <?php endif; ?>

</body>
</html>