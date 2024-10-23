<?php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load Composer's autoloader
require 'vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Define the base URL of your website
$baseURL = 'http://localhost/PhpProject/'; // Replace with your actual base URL

// Include database connection
include('config.php');


// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $käyttäjänimi = $_POST['username'];
    $sähköposti = $_POST['email'];
    $salasana = $_POST['password'];
    $vahvistasalasana = $_POST['confirmPassword'];
    
  
    // Server-side validation
    if ($salasana !== $vahvistasalasana) {
        echo "Salasanat eivät täsmää.";
        exit;
    }

    if (!filter_var($sähköposti, FILTER_VALIDATE_EMAIL)) {
        echo "Virheellinen sähköpostin muoto.";
        exit;
    }

    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $salasana)) {
        echo "Salasanan on oltava vähintään 8 merkkiä pitkä ja siinä on oltava isoja kirjaimia, pieniä kirjaimia, numeroita ja erikoismerkkejä.";
        exit;
    }

    // Check if the email is already registered
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $sähköposti);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Sähköposti on jo rekisteröity.";
        exit;
    }

    // Store the submitted data in the database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $käyttäjänimi, $sähköposti, password_hash($salasana, PASSWORD_DEFAULT));

    if ($stmt->execute()) {
        // If everything is valid, send the confirmation email
        try {
            $mail = new PHPMailer(true);

            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Set your SMTP server here
            $mail->SMTPAuth = true;
            $mail->Username = 'lixmihei@gmail.com'; // SMTP username
            $mail->Password = 'siga kwwq ndjj gtsk'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encryption
            $mail->Port = 587; // TCP port to connect to

            // Recipients
            $mail->setFrom('lixmihei@gmail.com', 'PROJECT'); // Add a sender
            $mail->addAddress($sähköposti, $käyttäjänimi); // Add the user's email and name

            // Construct the confirmation link
            $confirmationLink = $baseURL . 'Sähköpostivahvistus.php?email=' . urlencode($sähköposti);

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Sähköpostin vahvistus';
            $mail->Body = 'Kiitos rekisteröitymisestä. Vahvista sähköpostisi napsauttamalla seuraavaa linkkiä: <a href="' . $confirmationLink . '">Vahvista sähköposti </a>';
            $mail->AltBody = 'Vahvista sähköpostisi käymällä seuraavassa linkissä: ' . $confirmationLink;

            $mail->send();
            echo 'Vahvistussähköposti on lähetetty.';
        } catch (Exception $e) {
            echo "Viestiä ei voitu lähettää. Virhe: {$mail->ErrorInfo}";
        }

        // Redirect to email confirmation page
        header('Location: Kirjaudu.php');
        exit();
    } else {
        echo "Virhe tietojen tallentamisessa: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekisteröitymissivu</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'head.php'; ?>

    <div class="container content">
        <h1>Rekisteröidy</h1>
        <form action="Rekisteroidy.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Käyttäjänimi:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Sähköposti:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Salasana:</label>
                <input type="password" id="password" name="password" class="form-control" required>
                <span id="passwordStrength" class="strength"></span>
            </div>
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Vahvista salasana:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Rekisteröidy</button>
        </form>

        <div class="login-link mt-3">
            <p>Onko sinulla jo tili? <a href="Kirjaudu.php">Kirjaudu sisään</a>.</p>
        </div>
    </div>

    <div class="confirmation-link mt-3">
        <p>Rekisteröitymisen jälkeen, ole hyvä ja <a href="Kirjaudu.php">tarkista sähköpostisi vahvistusta varten</a>.</p>
    </div>

    <?php include 'footer.php'; ?>

    <script src="validation1.js"></script>
</body>
</html>