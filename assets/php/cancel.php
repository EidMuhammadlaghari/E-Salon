<?php

// Getting data from the form
$name = $_POST["name"];
$email = $_POST["email_address"];
$date = $_POST["date"];

// *************************** Connecting with the database ***************************

// Creating variables
$host = "localhost";
$dbname = "barber_db";
$username = "root";
$password = "";

// Establishing connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// For any error
if (mysqli_connect_errno()) {
    die("Connection error" . mysqli_connect_error());
}

// Check if the person has an appointment on the specified date
$sql_check = "SELECT * FROM appointment WHERE name = ? AND email = ? AND date = ?";
$stmt_check = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt_check, $sql_check)) {
    die(mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt_check, "sss", $name, $email, $date);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);

if (mysqli_num_rows($result_check) > 0) {
    // Appointment exists, delete it
    $sql_delete = "DELETE FROM appointment WHERE name = ? AND email = ? AND date = ?";
    $stmt_delete = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt_delete, $sql_delete)) {
        die(mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt_delete, "sss", $name, $email, $date);
    mysqli_stmt_execute($stmt_delete);

    $response = array(
        'success' => true,
        'message' => 'Your appointment has been canceled.'
    );
} else {
    $response = array(
        'success' => false,
        'message' => 'You do not have an appointment on this date.'
    );
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
