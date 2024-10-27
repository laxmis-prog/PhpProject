<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: kirjaudu.php");
    exit;
}

// Include database connection
include "config.php";

// Fetch user details from the database
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protected page</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container content">
        <h1>Tervetuloa, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        <p>Tämä on suojattu sivu, johon pääsee vain kirjautuneet käyttäjät.</p>
        <p>Sähköposti: <?php echo htmlspecialchars($user['email']); ?></p>
        <p>Rooli: <?php echo htmlspecialchars($user['role']); ?></p>
        <a href="logout.php" class="btn btn-danger">Kirjaudu ulos</a>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>