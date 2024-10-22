<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Päivittäinen Hyvinvointiseuranta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <?php include 'head.php'; ?>

<div class="content-wrapper">
    <?php
    $welcomeMessage = "Tervetuloa Päivittäiseen Hyvinvointiseurantaan";
    $description = "Seuraa päivittäisiä tapojasi, paranna elämäntapojasi ja pysy terveenä hyvinvointiseurantamme avulla.";
    $buttonText = "Aloita nyt";
    $buttonLink = "Rekisteroidy.php";
    ?>

    <!-- Hero-Section-->
    <section class="hero">
        <div class="hero-overlay">
            <div class="hero-content text-center">
                <h1><?php echo $welcomeMessage; ?></h1>
                <p><?php echo $description; ?></p>
                <a href="<?php echo $buttonLink; ?>" class="custom-btn"><?php echo $buttonText; ?></a>
            </div>
        </div>
    </section>

     <!-- Features Section -->
     <section class="features py-5">
        <div class="container">
            <h2 class="text-center mb-4">Miksi käyttää seurantaamme?</h2>
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="feature-item">
                        <i class="fas fa-check-circle fa-3x mb-3"></i>
                        <h3>Seuraa tapojasi</h3>
                        <p>Kirjaa päivittäiset rutiinisi ja seuraa edistymistäsi ajan myötä.</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="feature-item">
                        <i class="fas fa-chart-line fa-3x mb-3"></i>
                        <h3>Saa oivalluksia</h3>
                        <p>Analysoi tietojasi ja saat henkilökohtaista palautetta.</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="feature-item">
                        <i class="fas fa-heartbeat fa-3x mb-3"></i>
                        <h3>Paranna hyvinvointiasi</h3>
                        <p>Käytä tietojasi parantaaksesi elämäntapojasi ja hyvinvointiasi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

   <!-- Yhteystiedot Section -->
   <section id="yhteystiedot" class="py-5">
            <div class="container">
                <?php
                $isIncluded = true;
                include 'Yhteystiedot.php';
                ?>
            </div>
        </section>
    </div>

     <!-- Include Footer -->
     <?php include 'footer.php'; ?>

      <!-- jQuery Test Script -->
    <script>
        $(document).ready(function() {
            console.log("jQuery is loaded and ready!");

    
            $('a[href="Yhteystiedot.php"]').on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $('#yhteystiedot').offset().top
                }, 1000);
            });
        });
    </script>

     <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
