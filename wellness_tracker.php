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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $sleep_hours = $_POST['sleep_hours'];
    $mood = $_POST['mood'];
    $water_intake = $_POST['water_intake'];
    $exercise_type = $_POST['exercise_type'];
    $exercise_duration = $_POST['exercise_duration'];
    $nutrition_type = $_POST['nutrition_type'];

    // Insert the entry into the database
    $stmt = $conn->prepare("INSERT INTO wellness_entries (user_id, sleep_hours, mood, water_intake, exercise_type, exercise_duration, nutrition_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssss", $user_id, $sleep_hours, $mood, $water_intake, $exercise_type, $exercise_duration, $nutrition_type);
    $stmt->execute();
    $stmt->close();
}

// Fetch wellness entries from the database
$stmt = $conn->prepare("SELECT * FROM wellness_entries WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$entries = $stmt->get_result();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wellness Tracker</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'head.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <h2>Wellness Tracker</h2>
                <form action="wellness_tracker.php" method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="sleep_hours" class="form-label">Sleep Hours (0-24):</label>
                        <input type="number" id="sleep_hours" name="sleep_hours" class="form-control" min="0" max="24" required>
                        <div class="invalid-feedback">
                            Please enter a valid number of sleep hours.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="mood" class="form-label">Mood:</label>
                        <select id="mood" name="mood" class="form-select" required>
                            <option value="" disabled selected>Select your mood</option>
                            <option value="happy">Happy</option>
                            <option value="sad">Sad</option>
                            <option value="angry">Angry</option>
                            <option value="frustrated">Frustrated</option>
                            <option value="content">Content</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select your mood.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="water_intake" class="form-label">Water Intake (liters):</label>
                        <input type="number" id="water_intake" name="water_intake" class="form-control" step="0.1" required>
                        <div class="invalid-feedback">
                            Please enter your water intake in liters.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="exercise_type" class="form-label">Exercise Type:</label>
                        <input type="text" id="exercise_type" name="exercise_type" class="form-control" required>
                        <div class="invalid-feedback">
                            Please enter the type of exercise.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="exercise_duration" class="form-label">Exercise Duration (minutes):</label>
                        <input type="number" id="exercise_duration" name="exercise_duration" class="form-control" required>
                        <div class="invalid-feedback">
                            Please enter the duration of exercise in minutes.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="nutrition_type" class="form-label">Nutrition Type:</label>
                        <input type="text" id="nutrition_type" name="nutrition_type" class="form-control" required>
                        <div class="invalid-feedback">
                            Please enter the type of nutrition.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Log Entry</button>
                </form>

                <h2 class="mt-5">Your Wellness Entries</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Sleep Hours</th>
                            <th>Mood</th>
                            <th>Water Intake</th>
                            <th>Exercise Type</th>
                            <th>Exercise Duration</th>
                            <th>Nutrition Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($entry = $entries->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($entry['created_at']); ?></td>
                                <td><?php echo htmlspecialchars($entry['sleep_hours']); ?></td>
                                <td><?php echo htmlspecialchars($entry['mood']); ?></td>
                                <td><?php echo htmlspecialchars($entry['water_intake']); ?></td>
                                <td><?php echo htmlspecialchars($entry['exercise_type']); ?></td>
                                <td><?php echo htmlspecialchars($entry['exercise_duration']); ?></td>
                                <td><?php echo htmlspecialchars($entry['nutrition_type']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function () {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html>