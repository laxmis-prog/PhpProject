
<!-- HTML Structure for Sidebar -->
<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="index.php">
                    <i class="bi bi-house-door"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="wellness_tracker.php">
                    <i class="bi bi-activity"></i> Wellness Tracker
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="feedback.php">
                    <i class="bi bi-chat-dots"></i> Feedback
                </a>
            </li>
        </ul>
        <div class="mt-3">
            <a href="logout.php" class="btn btn-danger w-100">
                <i class="bi bi-box-arrow-right"></i> Kirjaudu ulos
            </a>
        </div>
    </div>
</nav>

<!-- CSS for Styling -->
<style>
    /* Sidebar Styling */
    #sidebar {
        min-height: 100vh;
        padding-top: 1rem;
        background-color: #f8f9fa; /* Light gray background */
        border-right: 1px solid #e0e0e0; /* Subtle border for separation */
    }

    /* Navigation link styles */
    #sidebar .nav-link {
        color: #495057; /* Darker gray for better readability */
        padding: 0.75rem 1rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        transition: background 0.3s ease, color 0.3s ease;
    }

    /* Icon styling within nav-links */
    #sidebar .nav-link i {
        margin-right: 8px;
        font-size: 1.1em;
    }

    /* Active and hover styles */
    #sidebar .nav-link.active,
    #sidebar .nav-link:hover {
        background-color: #007bff;
        color: white;
        border-radius: 5px;
    }

    /* Button styling */
    #sidebar .btn-danger {
        margin-top: 1rem;
        font-weight: bold;
        border-radius: 5px;
    }

    /* Adjust link text spacing */
    #sidebar .nav-item {
        margin-bottom: 0.5rem;
    }
</style>