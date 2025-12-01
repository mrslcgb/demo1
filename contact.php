<?php
// Define variables and set to empty values
$name = $email = $message = $success_message = $error_message = "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- 1. Basic Sanitization and Validation ---

    // Function to safely clean input data
    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $name = clean_input($_POST["name"]);
    $email = clean_input($_POST["email"]);
    $message = clean_input($_POST["message"]);

    // Simple validation (can be much more robust)
    if (empty($name) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please fill out all fields correctly (including a valid email address).";
    } else {

        // --- 2. Email Configuration and Sending ---

        $to = "your-email@example.com"; // **<-- CHANGE THIS TO YOUR EMAIL ADDRESS**
        $subject = "New Contact Form Submission from " . $name;
        $body = "Name: " . $name . "\n"
              . "Email: " . $email . "\n"
              . "Message:\n" . $message;

        $headers = "From: webmaster@yourdomain.com" . "\r\n" .
                   "Reply-To: " . $email . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();

        // Attempt to send the email
        if (mail($to, $subject, $body, $headers)) {
            $success_message = "Thank you for contacting us! Your message has been sent.";
            // Clear the form fields upon successful submission
            $name = $email = $message = "";
        } else {
            $error_message = "Oops! There was an issue sending your message. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us</title>
    <style>
        /* Simple CSS for readability */
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        textarea { resize: vertical; height: 150px; }
        .submit-btn { background-color: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .success { color: green; font-weight: bold; padding: 10px; border: 1px solid green; background-color: #e6ffe6; }
        .error { color: red; font-weight: bold; padding: 10px; border: 1px solid red; background-color: #ffe6e6; }
    </style>
</head>
<body>

    <h2>Get in Touch</h2>

    <?php
        // Display success or error messages
        if (!empty($success_message)) {
            echo "<div class='success'>" . $success_message . "</div>";
        }
        if (!empty($error_message)) {
            echo "<div class='error'>" . $error_message . "</div>";
        }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

        <div class="form-group">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>

        <div class="form-group">
            <label for="message">Message:</label>
            <textarea id="message" name="message" required><?php echo htmlspecialchars($message); ?></textarea>
        </div>

        <div class="form-group">
            <button type="submit" class="submit-btn">Send Message</button>
        </div>

    </form>

<a href="index.html">Go BACK</a>

</body>
</html>