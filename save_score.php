<?php
// Allow requests from any origin
header("Access-Control-Allow-Origin: *");

// Set the content type for the response as JSON
header("Content-Type: application/json");

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "rcf_database"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process incoming POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the data from the request parameters
    $username = $_GET["username"];
    $id = strtoupper($_GET["id"]);
    $score = base64_decode($_GET["score"]);
	
	$check_record = mysqli_query($conn, "SELECT * FROM score_sheet WHERE candidate_name='$username' AND course='$id'");
	if (mysqli_num_rows($check_record) == true){
		//echo json_encode(array("error" => false));
	} else {
		// Prepare SQL statement to insert data into the database
		$sql = "INSERT INTO score_sheet (candidate_name, course, score) VALUES ('$username', '$id', '$score')";
	}
	
    if (mysqli_query($conn, $sql) === TRUE) {
        // If insertion is successful, send a JSON response indicating success
        echo json_encode(array("success" => true));
    } else {
        // If insertion fails, send a JSON response indicating error
        echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
    }
} else {
    // Handle unsupported HTTP methods with an error response
    http_response_code(405); // Method Not Allowed
    echo json_encode(array("error" => "Method Not Allowed"));
}

// Close the database connection
$conn->close();
?>
