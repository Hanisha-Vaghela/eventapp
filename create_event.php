<?php
// create_event.php

// Include database connection (using the provided file name)
require_once 'db.php'; // Make sure this path is correct!
// include 'header.php';

// Define variables and initialize with empty values
$title = $description = $location = $start_datetime = $end_datetime = $capacity = $price = "";
$errors = [];
$success_message = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Validate inputs (same as before)
    if (empty(trim($_POST["title"]))) { $errors[] = "Please enter an event title."; } else { $title = trim($_POST["title"]); }
    if (empty(trim($_POST["description"]))) { $errors[] = "Please enter an event description."; } else { $description = trim($_POST["description"]); }
    if (empty(trim($_POST["location"]))) { $errors[] = "Please enter the event location."; } else { $location = trim($_POST["location"]); }
    if (empty(trim($_POST["start_datetime"]))) { $errors[] = "Please enter a start date and time."; } else { $start_datetime = trim($_POST["start_datetime"]); }
    if (empty(trim($_POST["end_datetime"]))) { $errors[] = "Please enter an end date and time."; } else { $end_datetime = trim($_POST["end_datetime"]); }
    
    // Check if start is before end
    if (!empty($start_datetime) && !empty($end_datetime) && strtotime($start_datetime) >= strtotime($end_datetime)) {
        $errors[] = "The start date/time must be before the end date/time.";
    }

    // Capacity validation
    $capacity = filter_var($_POST["capacity"], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
    if ($capacity === false) { $errors[] = "Capacity must be a valid number (0 or greater)."; }

    // Price validation
    $price = filter_var($_POST["price"], FILTER_VALIDATE_FLOAT);
    if ($price === false || $price < 0) { $errors[] = "Price must be a valid number (0 or greater)."; }


    // 2. Check input errors before inserting in database
    if (empty($errors)) {
        
        // Prepare an insert statement using PDO's named placeholders
        $sql = "INSERT INTO events (title, description, location, start_datetime, end_datetime, capacity, price) 
                VALUES (:title, :description, :location, :start_datetime, :end_datetime, :capacity, :price)";
        
        try {
            // Prepare the statement using the PDO object $pdo
            $stmt = $pdo->prepare($sql); 
            
            // Bind parameters to the statement
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':location' => $location,
                ':start_datetime' => $start_datetime,
                ':end_datetime' => $end_datetime,
                ':capacity' => $capacity,
                ':price' => $price
            ]);
            
            // Event created successfully. Redirect to events page.
            header("location: events.php?status=created");
            exit();

        } catch (\PDOException $e) {
            // Catch any PDO exceptions (e.g., table not found, SQL error)
            // In a real application, you might log this instead of displaying it.
            $errors[] = "Database insertion failed: " . $e->getMessage();
        }
    }
}
// Note: PDO connections don't require an explicit close and will be released
// when the script finishes, so $conn->close() is no longer needed.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Event | EventApp</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">

    <div class="auth-card">
        <h2>Create New Event <span class="accent">⚡</span></h2>
        <p>Fill out the form below to schedule a new event on the platform.</p>

        <?php 
        // Display errors if any
        if (!empty($errors)) {
            echo '<div class="errors"><ul>';
            foreach ($errors as $error) {
                echo '<li>' . htmlspecialchars($error) . '</li>';
            }
            echo '</ul></div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            
            <div class="form-group">
                <label for="title">Event Title</label>
                <input type="text" name="title" id="title" placeholder="e.g., Cyberpunk Coding Workshop" value="<?php echo htmlspecialchars($title); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4" placeholder="Briefly describe the event..." required><?php echo htmlspecialchars($description); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" name="location" id="location" placeholder="e.g., Neo-Tokyo Convention Center" value="<?php echo htmlspecialchars($location); ?>" required>
            </div>

            <div class="form-group">
                <label for="start_datetime">Start Date & Time</label>
                <input type="datetime-local" name="start_datetime" id="start_datetime" value="<?php echo htmlspecialchars($start_datetime); ?>" required>
            </div>

            <div class="form-group">
                <label for="end_datetime">End Date & Time</label>
                <input type="datetime-local" name="end_datetime" id="end_datetime" value="<?php echo htmlspecialchars($end_datetime); ?>" required>
            </div>

            <div class="form-group">
                <label for="capacity">Capacity (Set 0 for unlimited)</label>
                <input type="number" name="capacity" id="capacity" min="0" placeholder="e.g., 100" value="<?php echo htmlspecialchars($capacity); ?>" required>
            </div>

            <div class="form-group">
                <label for="price">Price (Set 0.00 for Free)</label>
                <input type="number" name="price" id="price" min="0" step="0.01" placeholder="e.g., 29.99" value="<?php echo htmlspecialchars($price); ?>" required>
            </div>

            <div class="form-group" style="margin-top: 2rem;">
                <button type="submit" class="btn primary" style="width: 100%;">
                    Create Event
                </button>
            </div>
            
            <div class="form-group" style="text-align: center; margin-top: 1rem;">
                <a href="events.php" class="btn btn-ghost" style="width: 100%; border: none; font-size: 0.9rem; background:lightpink;">Back to Events</a>
            </div>

        </form>
    </div>
<!-- <?php include 'footer.php'; ?> -->
</body>
</html>