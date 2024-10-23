<?php
include "db_connect.php";

// Check if the email is set in the URL
if (isset($_GET['email'])) {
    $sähköposti = urldecode($_GET['email']);

    // Look for the email in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $sähköposti);
    $stmt->execute();
    $result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    if ($row['status'] === 'confirmed') {
        $message = "Sähköpostisi on jo vahvistettu.";
    } else {
        // Update the user's status to 'confirmed'
        $stmt = $conn->prepare("UPDATE users SET status = 'confirmed' WHERE email = ?");
        $stmt->bind_param("s", $sähköposti);
        if ($stmt->execute()) {
            $message = "Sähköpostisi on vahvistettu onnistuneesti.";
        } else {
            $message = "Virhe sähköpostin vahvistamisessa.";
        }
    }
} else {
    $message = "Sähköpostia ei löytynyt.";
}

$stmt->close();
} else {
$message = "Sähköpostiosoitetta ei ole asetettu.";
}

$conn->close();
?>       





<!DOCTYPE html>
<html lang="fi

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sähköpostivahvistus</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    
<div class="container">
        <h1>Sähköpostin vahvistus</h1>
        <p><?php echo htmlspecialchars($message); ?></p>
        <a href="kirjaudu.php" class="btn btn-primary">Kirjaudu sisään</a>
    </div>

  
</body>
</html>