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

// Fetch user details from the database
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

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

    <div class="container content">
        <h1>Wellness Tracker</h1>
        <p>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</p>

        <form action="wellness_tracker.php" method="POST">
            <div class="mb-3">
                <label for="sleep_hours" class="form-label">Sleep Hours (0-24):</label>
                <input type="number" id="sleep_hours" name="sleep_hours" class="form-control" min="0" max="24" required>
            </div>
            <div class="mb-3">
                <label for="mood" class="form-label">Mood:</label>
                <select id="mood" name="mood" class="form-control" required>
                    <option value="happy">Happy</option>
                    <option value="sad">Sad</option>
                    <option value="angry">Angry</option>
                    <option value="frustrated">Frustrated</option>
                    <option value="content">Content</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="water_intake" class="form-label">Water Intake (liters):</label>
                <input type="number" id="water_intake" name="water_intake" class="form-control" step="0.1" required>
            </div>
            <div class="mb-3">
                <label for="exercise_type" class="form-label">Exercise Type:</label>
                <input type="text" id="exercise_type" name="exercise_type" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="exercise_duration" class="form-label">Exercise Duration (minutes):</label>
                <input type="number" id="exercise_duration" name="exercise_duration" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="nutrition_type" class="form-label">Nutrition Type:</label>
                <input type="text" id="nutrition_type" name="nutrition_type" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Log Entry</button>
        </form>

        <h2>Your Wellness Entries</h2>
        <table class="table">
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
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>