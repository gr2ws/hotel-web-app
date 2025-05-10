<?php

require_once __DIR__ . '/setup_vars.php';

function handleChangePass($id, $new_pass, $conf_pass)
{
    // get db config data
    $dbConfig = getDbConfig();
    $servername = $dbConfig['servername'];
    $username = $dbConfig['username'];
    $password = $dbConfig['password'];
    $dbname = $dbConfig['dbname'];

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        return '<div class="alert alert-danger mt-3">Database connection failed.</div>';
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Check whether or not new_pass and conf_pass are the same
        if ($new_pass !== $conf_pass) {
            return '<div class="alert alert-danger d-flex align-items-center" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-circle-x me-2"><circle cx="12" cy="12" r="10"/>
                            <path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                        <div>Password change failed. "New Password" and "Confirm New Password" fields do not match.</div>
                    </div>';
        }

        // Change original password to the password parameter passed 
        // from the submission of the  change_password form.
        $SQLcommand = "UPDATE person 
               SET pers_pass = '$new_pass'
               WHERE pers_id = $id";

        if ($conn->query($SQLcommand) === TRUE) {
            $_SESSION['pass'] = $new_pass;

            // Set a success message
            return '<div class="alert alert-success d-flex align-items-center mb-n2 w-100" role="alert">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-check-icon lucide-user-round-check"><path d="M2 21a8 8 0 0 1 13.292-6"/><circle cx="10" cy="8" r="5"/><path d="m16 19 2 2 4-4"/></svg>
                                        <div class="ms-3">
                                            Password changed successfully!
                                        </div>
                                    </div>';
        } else {
            return '<div class="alert alert-danger d-flex align-items-center mt-4 w-25" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-circle-x me-2"><circle cx="12" cy="12" r="10"/>
                        <path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                    <div>Something went wrong. Please try again.</div>
                </div>';
        }
    }
}
