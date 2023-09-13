<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "educate";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];

    // Fetch user data
    $stmt = $conn->prepare("SELECT * FROM `tutor` WHERE tutor_id = ? LIMIT 1");
    $stmt->bind_param("s", $tutor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image = "image/" .$row['image']; // Assuming the column name for profile image is 'profile_image'
        $name = $row['name']; // Assuming the column name for user's name is 'name'
        $profession = $row['profession'];
    } else {
        // User data not found, handle accordingly
        $image = "image.jpg"; // Set a default profile image
        $name = "Guest"; // Set a default name for guest users
    }
} else {
    $student_id = '';
    $image = "default-profile-image.jpg"; // Set a default profile image
    $name = "Guest"; // Set a default name for guest users
}



// Prepare the SQL statement

if (isset($_GET['doc_id'])) {
    // Get the document ID from the query parameter
    $doc_id = $_GET['doc_id'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT filename,  filedata FROM document WHERE doc_id = ?");
    $stmt->bind_param("i", $doc_id);
    $stmt->execute();
    $result = $stmt->get_result();;

    // Check if a document was found
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $filename = $row['filename'];
        $filedata = $row['filedata'];

        // Close the statement
        $stmt->close();

        // Close the connection
        $conn->close();

        // Set the appropriate headers for the embedded PDF
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=$filename");

        // Output the file data
        echo $filedata;
    } else {
        echo "PDF document not found.";
    }
} else {
    echo "Invalid document ID.";
}

// ...
?>   