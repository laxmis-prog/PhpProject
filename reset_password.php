<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Look for the token in the Users table
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expires >= ?");
    $stmt->bind_param("si", $token, date("U"));
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the user's password and clear the reset token and expiration time
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?");
        $stmt->bind_param("ss", $hashed_password, $token);
        if ($stmt->execute()) {
            echo "Your password has been updated successfully.";
        } else {
            echo "Failed to update your password.";
        }
    } else {
        echo "Invalid or expired token.";
    }
} else if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    echo "No token provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'head.php'; ?>

    <div class="container content">
        <h1>Reset Password</h1>
        <form action="reset_password.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password:</label>
                <input type="password" id="new_password" name="new_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>