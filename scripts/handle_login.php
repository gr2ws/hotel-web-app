<?php

require_once __DIR__ . '/setup_vars.php';


function handleLogin()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $email = $_POST['email'] ?? '';
        $pass  = $_POST['password'] ?? '';

        $dbConfig = getDbConfig();
        $conn = new mysqli($dbConfig['servername'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Validate user credentials and get full row
        $sql = "SELECT * FROM person WHERE pers_email = '$email' AND pers_pass = '$pass'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Store user info in session
            $_SESSION['id']       = $user['pers_id'];
            $_SESSION['fname']    = $user['pers_fname'];
            $_SESSION['lname']    = $user['pers_lname'];
            $_SESSION['address']  = $user['pers_address'];
            $_SESSION['phone']    = $user['pers_number'];
            $_SESSION['birthday'] = $user['pers_birthdate'];
            $_SESSION['email']    = $user['pers_email'];
            $_SESSION['pass']     = $user['pers_pass'];

            header("Location: ../pages/user_dashboard.php");
            exit;
        } else {
            // invalid credentials
            return '<div class="alert alert-danger d-flex align-items-center mt-4 mb-n2" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" class="lucide lucide-circle-x">
                    <circle cx="12" cy="12" r="10" />
                    <path d="m15 9-6 6" />
                    <path d="m9 9 6 6" />
                </svg>
                <div class="ms-3">
                    Incorrect username or password. Please try again.
                </div>
            </div>';
        }
    }
}
