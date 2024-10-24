
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs (additional layer of security)
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Look for the email in the Users table
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Debugging: Print the $user array
        echo "<pre>";
        print_r($user);
        echo "</pre>";

        // Check if the user's status is 'verified'
        if ($user['status'] !== 'verified') {
            echo "Sähköpostia ei ole vahvistettu."; // Email is not verified
            exit;
        }

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start a session and log the user in
            session_start();
            // Regenerate session ID for security
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            echo "Login successful!";

            // Redirect to a protected page
            header("Location: index.php");
            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that email address.";
    }
}


// Check for remember token
if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        session_start();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        header("Location: protected_page.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirjaudu sisään</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'head.php'; ?>

    <div class="container content">
        <h1>Kirjaudu sisään</h1>
        <?php if (isset($message)): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="Kirjaudu.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Sähköposti:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Salasana:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Muista minut</label>
            </div>
            <button type="submit" class="btn btn-primary">Kirjaudu sisään</button>
        </form>
        <a href="forgot_password.php">Unohditko salasanasi?</a>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>