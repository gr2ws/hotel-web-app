<!-- Bootstrap CSS -->
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
    crossorigin="anonymous" />

<!-- Bootstrap Icons -->
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

<!-- Flatpickr Date Picker -->
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />

<!-- Put all component styles here -->


<!-- Component styles-->

<link rel="stylesheet" href="../styles/header.css">
<link rel="stylesheet" href="../styles/booking_header.css">
<link rel="stylesheet" href="../styles/footer.css">

<?php

# utility functions to make putting components more intuitive

function placeHeader()
{
    require '../components/header.html';
}

function placeFooter()
{
    require '../components/footer.html';
}

function placeBookingHeader()
{
    require '../components/booking_header.html';
}

# backend functions
require '../scripts/handle_newacc.php';
require '../scripts/handle_login.php';

?>