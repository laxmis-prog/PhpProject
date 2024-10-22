<?php
// Check if the file is being included
if (!isset($isIncluded)) {
    $isIncluded = false;
}
?>

<?php if (!$isIncluded): ?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yhteystiedot | Hyvinvointiseuranta</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'head.php'; ?>
    <?php endif; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Yhteystiedot</h1>
        <p class="text-center">Ota yhteyttä, niin autamme sinua parantamaan hyvinvointiasi!</p>

        <div class="contact-item">
            <h3>Asiakaspalvelu</h3>
            <p>
                📧 Sähköposti:
                <a href="mailto:asiakaspalvelu@hyvinvointiseuranta.fi">asiakaspalvelu@hyvinvointiseuranta.fi</a>
            </p>
            <p>📞 Puhelin: <a href="tel:+358401234567">+358 40 123 4567</a></p>
            <p>⏰ Aukioloajat: Ma-Pe klo 9:00–17:00</p>
        </div>

        <div class="contact-item">
            <h3>Tekninen tuki</h3>
            <p>
                📧 Sähköposti:
                <a href="mailto:tuki@hyvinvointiseuranta.fi">tuki@hyvinvointiseuranta.fi</a>
            </p>
            <p>📞 Puhelin: <a href="tel:+358407654321">+358 40 765 4321</a></p>
            <p>⏰ Aukioloajat: Ma-Pe klo 8:00–18:00</p>
        </div>

        <div class="contact-item">
            <h3>Media</h3>
            <p>
                📧 Sähköposti:
                <a href="mailto:media@hyvinvointiseuranta.fi">media@hyvinvointiseuranta.fi</a>
            </p>
            <p>📞 Puhelin: <a href="tel:+358405678910">+358 40 567 8910</a></p>
        </div>

        <!-- Käyntiosoite laatikko -->
        <div class="address-section box">
            <h2>Käyntiosoite</h2>
            <p>Hyvinvointiseuranta Oy</p>
            <p>Wellnesskatu 10,</p>
            <p>00100 Helsinki, Suomi</p>
        </div>

        <!-- Sosiaalinen media laatikko -->
        <div class="social-media-section box">
            <h2>Sosiaalinen media</h2>
            <p>Pysy yhteydessä ja seuraa hyvinvointivinkkejämme sosiaalisessa mediassa!</p>
            <ul>
                <li><a href="https://facebook.com/hyvinvointiseuranta" target="_blank">Facebook</a></li>
                <li><a href="https://instagram.com/hyvinvointiseuranta" target="_blank">Instagram</a></li>
                <li><a href="https://linkedin.com/company/hyvinvointiseuranta" target="_blank">LinkedIn</a></li>
            </ul>
        </div>


        <div class="contact-form-section">
            <h2>Yhteydenottolomake</h2>
            <?php
            // PHP form handling section
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $name = htmlspecialchars($_POST['name']);
                $email = htmlspecialchars($_POST['email']);
                $message = htmlspecialchars($_POST['message']);

                // Mail function (if needed, configure SMTP settings first)
                // mail("your_email@example.com", "New Contact Message", $message, "From: $email");

                echo "<p class='alert alert-success'>Kiitos yhteydenotosta, $name! Viestisi on vastaanotettu.</p>";
            }
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="name">Nimesi:</label>
                <input type="text" id="name" name="name" required />

                <label for="email">Sähköpostisi:</label>
                <input type="email" id="email" name="email" required />

                <label for="message">Viesti:</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <button type="submit">Lähetä</button>
            </form>
        </div>
    </div>

    <?php if (!$isIncluded): ?>
    <?php include 'footer.php'; ?>
</body>
</html>
<?php endif; ?>