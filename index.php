<?php
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
   <title>Wellness Dashboard</title>
   <link rel="stylesheet" href="styles.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include 'sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="pt-3 pb-2 mb-3 border-bottom">
            <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
                <p>Today is a great day to prioritize your wellness.</p>
            </div>
            
            <!-- Wellness Metrics Section -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Daily Step Count</h5>
                            <p class="card-text display-4">7,200</p>
                            <p>Steps</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Hydration Tracker</h5>
                            <p class="card-text display-4">5</p>
                            <p>Glasses</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Mood Check-in</h5>
                            <p class="card-text display-4">😊</p>
                            <p>Feeling Great!</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wellness Tips Section -->
            <div class="mb-4">
                <h3>Today's Wellness Tips</h3>
                <ul class="list-group">
                    <li class="list-group-item">Take a 5-minute break every hour to stretch and breathe.</li>
                    <li class="list-group-item">Drink at least 8 glasses of water today.</li>
                    <li class="list-group-item">Write down three things you're grateful for.</li>
                    <li class="list-group-item">Try to go to bed at a consistent time tonight.</li>
                </ul>
            </div>

            <!-- Meditation Timer Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <h4 class="card-title">Quick Meditation Timer</h4>
                    <p>Take a few moments to relax.</p>
                    <button class="btn btn-primary" onclick="startTimer(5)">5 Minutes</button>
                    <button class="btn btn-primary" onclick="startTimer(10)">10 Minutes</button>
                    <div id="timer" class="display-4 mt-3">00:00</div>
                </div>
            </div>

            <!-- Journal Section -->
            <div class="mb-4">
                <h3>Your Daily Journal</h3>
                <form action="save_journal.php" method="post">
                    <div class="mb-3">
                        <textarea class="form-control" name="journal_entry" rows="4" placeholder="Write down your thoughts..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Save Entry</button>
                </form>
            </div>
        </main>
    </div>
</div>

<script>
    // Simple Timer Script
    function startTimer(minutes) {
        let seconds = minutes * 60;
        const timerElement = document.getElementById('timer');
        timerElement.textContent = formatTime(seconds);
        
        const interval = setInterval(() => {
            seconds--;
            timerElement.textContent = formatTime(seconds);
            if (seconds <= 0) clearInterval(interval);
        }, 1000);
    }

    function formatTime(seconds) {
        const min = Math.floor(seconds / 60);
        const sec = seconds % 60;
        return `${String(min).padStart(2, '0')}:${String(sec).padStart(2, '0')}`;
    }
</script>

</body>
</html>
