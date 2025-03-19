<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Include the database connection
    include_once 'dbh.inc.php';

    // Get the userHandle from the POST request
    $userHandle = trim($_POST['userHandle']);

    if (!empty($userHandle)) {
        // Prepare a query to check the userHandle
        $sql = "SELECT * FROM users WHERE usersHandle = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $userHandle);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "'$userHandle' handle is already taken. ❌";
        } else {
            echo "'$userHandle' handle is available. ✔️";
        }

        $stmt->close();
    } else {
        echo "⚠️ Please enter a valid handle.";
    }

    $conn->close();
}
?>
