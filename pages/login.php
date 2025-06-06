<?php
session_start();

require '../scripts/handle_login.php';

// Prepare a message variable
$loginMessage = null;

// Check if account has been deleted
if (isset($_SESSION['account_deleted']) && $_SESSION['account_deleted'] === true) {
    $loginMessage = '<div class="alert alert-success alert-dismissible fade show" role="alert">
        ' . $_SESSION['deletion_message'] . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    // Remove the session variables
    unset($_SESSION['account_deleted']);
    unset($_SESSION['deletion_message']);
}

// Check for booking error in session (added from booking redirect)
$bookingMessage = '';
if (isset($_SESSION['booking_error'])) {
    $bookingMessage = $_SESSION['booking_error'];
    unset($_SESSION['booking_error']);
}

// Check if there's a pending booking that needs authentication
$hasBookingDetails = isset($_SESSION['pending_booking_details']) &&
    $_SESSION['pending_booking_details']['requires_auth'] === true;

// For backward compatibility - check if booking was initiated from URL
$isBookingRedirect = false;

// Handle login before output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginMessage = handleLogin();

    // If login was successful and we have pending booking details, redirect directly to booking.php
    if (isset($_SESSION['id']) && $hasBookingDetails) {
        // Set a flag to indicate we're coming from booking flow
        $_SESSION['from_booking_flow'] = true;

        // Redirect to booking.php to process the pending booking
        header("Location: booking.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dockside Hotel© | Log In</title>
    <link rel="stylesheet" href="../styles/login.css">
    <?php require 'common.php'; ?>
</head>

<body>
    <?php placeHeader() ?>

    <div class="login-body container-fluid my-auto d-flex flex-column justify-center align-items-center">

        <?php
        // Show booking message if redirected from booking page
        if ($isBookingRedirect || !empty($bookingMessage)):
        ?>
            <div class="alert alert-info d-flex align-items-center mb-3 mt-3 w-100 max-w-md" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <div>
                    <strong>Please Note:</strong> Log in below to continue with your booking.
                </div>
            </div>
        <?php
        endif;

        // Show regular login error message
        if ($loginMessage) {
            echo $loginMessage;
        }
        ?>

        <div class="login-container py-4">
            <h1>Log In</h1>
            <form id="loginForm" method="POST">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        maxlength="255"
                        required>

                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        minlength="8"
                        maxlength="30"
                        required>
                </div>
                <button type="submit" class="login-btn">Log In</button>
            </form>
            <div class="or-divider">OR</div>
            <button class="secondary-btn" onclick="window.location.href='sign_up.php'">Create an Account</button>
        </div>
    </div>

    <script src="../scripts/login.js"></script>
    <?php placeFooter() ?>
</body>

</html>