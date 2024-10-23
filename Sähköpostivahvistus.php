<?php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config.php";

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

        // Debugging: Print the $row array
        echo "<pre>";
        print_r($row);
        echo "</pre>";

        if (isset($row['status']) && $row['status'] === 'verified') {
            $message = "Sähköpostisi on jo vahvistettu.";
        } else {
            // Update the user's status to 'verified'
            $stmt = $conn->prepare("UPDATE users SET status = 'verified' WHERE email = ?");
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
    $conn->close();

    echo $message;

    // Redirect to kirjaudu.php
    header("Location: http://localhost/PhpProject/kirjaudu.php");
    exit;
} else {
    echo "Sähköpostiosoitetta ei ole asetettu.";
}
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