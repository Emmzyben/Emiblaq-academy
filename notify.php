<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    // Retrieve email from the POST data
    $email = $_POST['email'];

    // Database connection details
   $DATABASE_HOST = 'localhost';
$DATABASE_USER = 'grafordc_graford';
$DATABASE_PASS = 'Gratia12345';
$DATABASE_NAME = 'grafordc_graford';

    // Create a database connection
    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if (mysqli_connect_errno()) {
        die('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    // Prepare and execute a query to retrieve exam number and full name
    $query = $con->prepare('SELECT accreditationNumber, fullName, randomPassword FROM accounts WHERE email = ?');
    $query->bind_param('s', $email);
    $query->execute();
    $query->bind_result($accreditationNumber, $fullName, $randomPassword);
    $query->fetch();
    $query->close();

    // Close the database connection
    mysqli_close($con);

    if ($accreditationNumber && $fullName && $randomPassword) {
  
        $subject = 'Registration Successful';
        $message = 'Dear ' . $fullName . '';
        $message .= 'Thank you for registering at Emiblaq tech Academy. Your registration was successful.';
        $message .= 'Your accreditation number is: ' . $accreditationNumber . '';
        $message .= 'Your Password is: ' . $randomPassword . '';
         $message .= 'You can now login to the student portal to see your dashboard ';
         $message .= 'Please endeavour to always check the blog page of our website for updates and free lessons';
        $message .= 'See you in class!';

        $headers = 'From: support@emiblaqtech.com'; 

        // Send email
        if (mail($email, $subject, $message, $headers)) {
            
        } else {
           
        }
    } else {
        
    }
} else {
    
}
?>
