<?php

// Getting data from the form
$name = $_POST["name"];
$email = $_POST["email_address"];
$phone = $_POST["phone"];
$category = $_POST["category"];
$date = $_POST["date"];
$message = $_POST["message"];

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

// Check if the person has already made an appointment on the same date
$sql_check = "SELECT * FROM appointment WHERE name = ? AND email = ? AND date = ?";
$stmt_check = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt_check, $sql_check)) {
    die(mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt_check, "sss", $name, $email, $date);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);

if (mysqli_num_rows($result_check) > 0) {
    $row = mysqli_fetch_assoc($result_check);
    $existingDate = $row['date'];
    echo "You have already appointed on $existingDate.";
} else {
    // Inserting into the database
    $sql = "INSERT INTO appointment (name, email, number, category, date, message) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $phone, $category, $date, $message);
    mysqli_stmt_execute($stmt);

    echo "Record has been saved";
}
