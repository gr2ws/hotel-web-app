<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

require '../scripts/handle_edit.php';
require '../scripts/handle_pass.php';

// Show update result message only if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['profile_submit'])) {
    $message = handleEdit($_SESSION['id']);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password_submit'])) {
    $message = handleChangePass($_SESSION['id'], $_POST['new_password'], $_POST['confirm_password']);
}


// setting data for ui
$id = $_SESSION['id'];
$fname = $_SESSION['fname'];
$lname = $_SESSION['lname'];
$address = $_SESSION['address'];
$phone = $_SESSION['phone'];
$birth = $_SESSION['birthday'];
$email = $_SESSION['email'];
$pass = $_SESSION['pass'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Dockside Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="../styles/user-dashboard.css">
    <link rel="stylesheet" href="../styles/index.css">
    <?php require_once 'common.php'; ?>
</head>

<body>

    <!-- Header Navigation -->
    <nav class="header-nav navbar navbar-expand-md shadow-sm">
        <div class="container">
            <button type="button" class="mobile-menu-btn d-xs-block d-sm-block d-md-none" id="mobileMenuToggle">
                <i class="bi-list"></i>
            </button>

            <a class="nav-hotel-name" href="index.html">
                Dockside Hotel
                <sup class="header-c bi-c-circle"></sup>
            </a>

            <!-- Main Navigation -->
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" data-bs-toggle="dropdown">
                            Accommodations <i class="bi-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <span class="dropdown-header">Find your perfect stay...</span>
                            <hr class="dropdown-divider">
                            <li><a class="dropdown-item" href="#">Standard Room</a></li>
                            <li><a class="dropdown-item" href="#">Deluxe Room</a></li>
                            <li><a class="dropdown-item" href="#">Suite Room</a></li>
                            <li><a class="dropdown-item" href="#">Family Room</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Facilities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.html">About Us</a>
                    </li>
                </ul>
            </div>

            <!-- User Menu -->
            <div class="dropdown">
                <button class="btn" type="button" data-bs-toggle="dropdown">
                    <i class="bi-person-circle"></i>
                    <span class="d-none d-lg-inline"><?php echo $fname; ?></span>
                    <i class="bi-chevron-down"></i>
                </button>
                <div class="dropdown-menu">
                    <span class="dropdown-header">Welcome back, <?php echo $fname; ?>!</span>
                    <hr class="dropdown-divider">
                    <a class="dropdown-item" href="#dashboard" data-tab="dashboard">Dashboard</a>
                    <a class="dropdown-item" href="#profile" data-tab="profile">Profile</a>
                    <a class="dropdown-item" href="#settings" data-tab="settings">Settings</a>
                    <hr class="dropdown-divider">
                    <a class="dropdown-item text-danger" href="../scripts/handle_logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mt-4">
        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-md-2 pb-4 pb-lg-0">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <nav>
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="#dashboard" class="nav-link active" data-tab="dashboard"
                                        onclick="ridMessage()">
                                        <i class="bi bi-speedometer2"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#reservations" class="nav-link" data-tab="reservations"
                                        onclick="ridMessage()">
                                        <i class="bi bi-calendar-check"></i> My Reservations
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#profile" class="nav-link" data-tab="profile"
                                        onclick="ridMessage()">
                                        <i class="bi bi-person"></i> Profile
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#settings" class="nav-link" data-tab="settings"
                                        onclick="ridMessage()">
                                        <i class="bi bi-gear"></i> Settings
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-md-7">

                <?php echo $message ?? ''; ?>

                <!-- Dashboard Section -->
                <div class="content-section" id="dashboard-content">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h2 class="card-title border-bottom pb-2">Welcome back, <?php echo $fname . "!"; ?></h2>
                            <p class="lead">Here's an overview of your activity and available rooms.</p>
                        </div>
                    </div>

                    <!-- Available Rooms Table -->
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title border-bottom pb-2">Available Rooms</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Room Type</th>
                                            <th>Capacity</th>
                                            <th>Rate/Night</th>
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($availableRooms as $room): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($room['room_type']); ?></td>
                                                <td><?php echo htmlspecialchars($room['capacity']); ?></td>
                                                <td>₱<?php echo number_format($room['rate'], 2); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo $room['status'] === 'Available' ? 'success' : 'warning'; ?>">
                                                        <?php echo htmlspecialchars($room['status']); ?>
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <button class="btn btn-sm btn-primary" onclick="selectRoom(<?php echo $room['id']; ?>)">
                                                        <i class="bi bi-calendar-plus"></i> Select
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reservations Section -->
                <div class="content-section d-none" id="reservations-content">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h2 class="card-title border-bottom pb-2">My Reservations</h2>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>Room Type</th>
                                            <th>Check-in</th>
                                            <th>Check-out</th>
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($userBookings as $booking): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($booking['id']); ?></td>
                                                <td><?php echo htmlspecialchars($booking['room_type']); ?></td>
                                                <td><?php echo date('M j, Y', strtotime($booking['check_in'])); ?></td>
                                                <td><?php echo date('M j, Y', strtotime($booking['check_out'])); ?></td>
                                                <td>
                                                    <span class="badge">
                                                        <!-- <span class="badge bg-< ?php echo getStatusColor($booking['status']); ?>"> -->
                                                        <?php echo htmlspecialchars($booking['status']); ?>
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <?php if ($booking['status'] !== 'cancelled'): ?>
                                                        <button class="btn btn-sm btn-outline-danger" onclick="cancelBooking(<?php echo $booking['id']; ?>)">
                                                            <i class="bi bi-x-circle"></i> Cancel
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Section -->
                <div class="content-section d-none" id="profile-content">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <h2 class="card-title mt-2">My Profile</h2>
                                <button
                                    type="button"
                                    class="btn btn-primary"
                                    onclick="makeEditable()">Edit Profile</button>
                            </div>

                            <hr>

                            <form id="profileForm" method="POST" action="./user_dashboard.php" class="mt-4">

                                <!-- flag for conditional data handling based on what form was submitted -->
                                <input type="hidden" name="profile_submit" value="1">

                                <!-- <div class="mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <img src="< ?php echo htmlspecialchars($userData['profile_photo'] ?? 'images/default-avatar.png'); ?>"
                                            alt="Profile" class="rounded-circle" width="80" height="80">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('profilePhoto').click()">
                                            <i class="bi bi-upload"></i> Change Photo
                                        </button>
                                        <input type="file" id="profilePhoto" name="profile_photo" class="d-none" accept="image/*">
                                    </div>
                                </div> -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fname" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="fname" name="fname"
                                            value="<?php echo $fname; ?>" disabled required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="lname" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="lname" name="lname"
                                            value="<?php echo $lname; ?>" disabled required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="birth">Birthday</label>
                                    <input
                                        type="date"
                                        class="form-control"
                                        id="birth"
                                        name="birth"
                                        disabled
                                        value="<?php echo $birth; ?>"
                                        required>
                                    <small><i class="text-muted">Format: dd/mm/yyyy</i></small>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input
                                        type="tel"
                                        class="form-control"
                                        id="phone"
                                        name="phone"
                                        pattern="[0-9]{4}-[0-9]{3}-[0-9]{4}"
                                        value="<?php echo $phone; ?>"
                                        maxlength="13"
                                        disabled
                                        required />
                                    <small><i class="text-muted">Format: 0912-345-6789</i></small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" name="address" rows="3" disabled><?php echo $address; ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg"></i> Save Changes
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Settings Section -->
                <div class="content-section d-none" id="settings-content">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h2 class="card-title border-bottom pb-2">Account Settings</h2>
                            <form id="settingsForm" method="POST" action="./user_dashboard.php" class="mt-4">

                                <!-- flag for conditional data handling based on what form was submitted -->
                                <input type="hidden" name="password_submit" value="1">

                                <div class="mb-4">
                                    <h4>Change Password</h4>
                                    <br>
                                    <div class="mb-3">
                                        <div class="d-flex justify-center align-center gap-2 mb-2">
                                            <label class="form-label mb-0">Current Password</label>
                                            <button type="button" id="one" class="togshow-pword show-pword d-block" onclick="showPass('current_password', 'one', 'two')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye">
                                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                            </button>
                                            <button type="button" id="two" class="toghide-pword hide-pword d-none" onclick="hidePass('current_password', 'one', 'two')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off-icon lucide-eye-off">
                                                    <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49" />
                                                    <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242" />
                                                    <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143" />
                                                    <path d="m2 2 20 20" />
                                                </svg>
                                            </button>
                                        </div>
                                        <input type="password" class="form-control" id="current_password" name="current_password" value="<?php echo $pass; ?>" disabled>
                                        <br>
                                        <small><i class="text-muted">Please ensure that you input the same new password in the two fields below.</i></small>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-center align-center gap-2 mb-2">
                                            <label class="form-label" for="new_password">New Password</label>
                                            <button type="button" id="three" class="togshow-pword show-pword d-block" onclick="showPass('new_password', 'three', 'four')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye">
                                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                            </button>
                                            <button type="button" id="four" class="toghide-pword hide-pword d-none" onclick="hidePass('new_password', 'three', 'four')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off-icon lucide-eye-off">
                                                    <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49" />
                                                    <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242" />
                                                    <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143" />
                                                    <path d="m2 2 20 20" />
                                                </svg>
                                            </button>
                                        </div>
                                        <input type="password" class="form-control" id="new_password" name="new_password" minlength="8" maxlength="30" required>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-center align-center gap-2 mb-2">

                                            <label class="form-label" for="confirm_password">Confirm New Password</label>
                                            <button type="button" id="five" class="togshow-pword show-pword d-block" onclick="showPass('confirm_password', 'five', 'six')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye">
                                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                            </button>
                                            <button type="button" id="six" class="toghide-pword hide-pword d-none" onclick="hidePass('confirm_password', 'five', 'six')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off-icon lucide-eye-off">
                                                    <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49" />
                                                    <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242" />
                                                    <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143" />
                                                    <path d="m2 2 20 20" />
                                                </svg>
                                            </button>
                                        </div>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="8" maxlength="30" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-key"></i> Update Password
                                    </button>
                                </div>
                                <!-- <hr>
                                <div class="mb-4">
                                    <h5>Notification Preferences</h5>
                                    <div class="form-check mb-2">
                                        <input type="checkbox" class="form-check-input" id="emailNotifs" name="email_notifications"
                                            < ?php echo isset($userData['email_notifications']) && $userData['email_notifications'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="emailNotifs">
                                            Email Notifications
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input type="checkbox" class="form-check-input" id="smsNotifs" name="sms_notifications"
                                            < ?php echo isset($userData['sms_notifications']) && $userData['sms_notifications'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="smsNotifs">
                                            SMS Notifications
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">
                                        <i class="bi bi-save"></i> Save Preferences
                                    </button>
                                </div> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="col-md-3">
                <!-- Quick Booking Form -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h3 class="card-title h5 border-bottom pb-2">Quick Booking</h3>
                        <form id="quickBookingForm">
                            <div class="mb-3">
                                <label class="form-label">Check-in Date</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    <input type="text" class="form-control datepicker" id="checkIn" placeholder="Check-in Date" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Check-out Date</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    <input type="text" class="form-control datepicker" id="checkOut" placeholder="Check-out Date" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-calendar-check"></i> Book Now
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title h5 border-bottom pb-2">Recent Activity</h3>
                        <div class="recent-activity">
                            <?php if (empty($recentActivities)): ?>
                                <p class="text-muted">No recent activities</p>
                            <?php else: ?>
                                <?php foreach ($recentActivities as $activity): ?>
                                    <div class="activity-item border-bottom pb-2 mb-2">
                                        <small class="text-muted">
                                            <?php echo date('M j, Y g:i A', strtotime($activity['created_at'])); ?>
                                        </small>
                                        <p class="mb-0"><?php echo htmlspecialchars($activity['description']); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Toast Container -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
        <div id="liveToast" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">
                    <!-- Message will appear here -->
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="../scripts/user-dashboard.js"></script>
    <script>
        function ridMessage() {
            document.querySelector(".alert").classList.remove('d-flex');
            document.querySelector(".alert").classList.add('d-none');
        }

        function makeEditable() {
            var readItems = document.querySelectorAll('input[disabled], textarea[disabled]');
            readItems.forEach((readItem) => {
                readItem.disabled = false;
            })
        }

        function showPass(inputId, showBtnId, hideBtnId) {
            const input = document.getElementById(inputId);
            const showBtn = document.getElementById(showBtnId);
            const hideBtn = document.getElementById(hideBtnId);

            input.type = "text";
            showBtn.classList.add("d-none");
            showBtn.classList.remove("d-block");
            hideBtn.classList.remove("d-none");
            hideBtn.classList.add("d-block");
        }

        function hidePass(inputId, showBtnId, hideBtnId) {
            const input = document.getElementById(inputId);
            const showBtn = document.getElementById(showBtnId);
            const hideBtn = document.getElementById(hideBtnId);

            input.type = "password";
            hideBtn.classList.add("d-none");
            hideBtn.classList.remove("d-block");
            showBtn.classList.remove("d-none");
            showBtn.classList.add("d-block");
        }
    </script>

</body>

</html>