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
    $feedback_text = $_POST['feedback_text'];

    // Insert the feedback into the database
    $stmt = $conn->prepare("INSERT INTO feedback (user_id, feedback_text) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $feedback_text);
    $stmt->execute();
    $stmt->close();
}

// Fetch feedback from the database
$stmt = $conn->prepare("SELECT feedback.*, users.username FROM feedback JOIN users ON feedback.user_id = users.user_id ORDER BY feedback.created_at DESC");
$stmt->execute();
$feedbacks = $stmt->get_result();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 2rem;
        }
        .btn-primary {
            margin-bottom: 3rem !important; /* Adds extra space below the submit button */
        }
        h2 {
            color: #007bff; /* Slightly bluish header text color */
        }
    </style>
</head>
<body>
<div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <h2>Feedback</h2>
                <form action="feedback.php" method="POST">
                    <div class="mb-3">
                        <label for="feedback_text" class="form-label">Your Feedback:</label>
                        <textarea id="feedback_text" name="feedback_text" class="form-control" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                </form>

                <h2 class="mt-5">Submitted Feedback</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>User</th>
                            <th>Feedback</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($feedback = $feedbacks->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($feedback['created_at']); ?></td>
                                <td><?php echo htmlspecialchars($feedback['username']); ?></td>
                                <td><?php echo htmlspecialchars($feedback['feedback_text']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
</body>
</html>