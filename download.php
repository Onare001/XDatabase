<?php
/* if(isset($_POST['csvData'])) {
    $csvData = $_POST['csvData'];
    
    // Set CSV headers
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=RCF_Quiz_Results2024.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Output CSV data
    echo $csvData;
    exit();
} else {
    // Handle case where CSV data is not received
    echo "Error: CSV data not received.";
}
 */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['csv_data'])) {
    // Retrieve the serialized and base64 encoded data from the form
    $serialized_data = $_POST['csv_data'];

    // Decode the base64 encoded data and unserialize it
    $pivotedData = unserialize(base64_decode($serialized_data));

    // Set headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="Rcf_eTrialQuiz_Result2024.csv"');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Write CSV headers
    fputcsv($output, array('Name of Candidate', 'CHM 101(20)', 'PHY 101(20)', 'PHY 103(20)', 'MAT 101(20)', 'MAT 113(20)'));

    // Write CSV data
    foreach ($pivotedData as $name => $scores) {
        fputcsv($output, array($name, $scores['CHM101'] ?? '', $scores['PHY101'] ?? '', $scores['PHY103'] ?? '', $scores['MAT101'] ?? '', $scores['MAT113'] ?? ''));
    }

    // Close output stream
    fclose($output);
} else {
    // Handle invalid request
    echo "Invalid request";
}
?>
