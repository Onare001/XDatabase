<?php
function Remark($score){
    $value = ($score/20);
    if ($value >= 0.5){
        $remark = 'Pass';
    } else if ($value >= 0.75){
		$remark = 'Excellent';
	} else {
        $remark = 'Fail';
    }
    return $remark;
}

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "rcf_database"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

$counter = '0';

// Fetch data from database
$quiz_result = mysqli_query($conn,"SELECT * FROM score_sheet");

// Start CSV file content
$csvData = "S/N,Candidate Name,Course Code,Score,Remark\n";

// Start HTML table content
$htmlTable = '<table width="100%" border="1" class="table">';
$htmlTable .= '<thead>';
$htmlTable .= '<tr>';
$htmlTable .= '<th>S/N</th>';
$htmlTable .= '<th>Full Name of Candidate</th>';
$htmlTable .= '<th>Time of Submission</th>';
$htmlTable .= '<th>Course Code</th>';
$htmlTable .= '<th>Score</th>';
$htmlTable .= '<th>Remark</th>';
$htmlTable .= '</tr>';
$htmlTable .= '</thead>';
$htmlTable .= '<tbody>';

// Loop through fetched data and add to CSV content and HTML table
while($row = mysqli_fetch_assoc($quiz_result)){
    $counter++;
    $csvData .= "$counter,".strtoupper($row['candidate_name']).",".$row['course'].",".$row['score'].",".Remark($row['score'])."\n";
    $htmlTable .= '<tr>';
    $htmlTable .= '<td align="center" width="5%">'.$counter.'</td>'.'<td>'.strtoupper($row['candidate_name']).'</td>'.'<td>'.strtoupper($row['time_submitted']).'</td>'.'<td align="center" width="15%">'.$row['course'].'</td>'.'<td align="center">'.$row['score'].'</td><td align="center" width="15%">'.Remark($row['score']).'</td>';
    $htmlTable .= '</tr>';
}

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!--meta http-equiv="refresh" content="1"-->
    <title>View and Download Table</title>
    <style>
        .table {
            border-collapse: collapse;
            width: 100%;
        }
		body {
            width: 80%;
			margin: 0 auto;
			margin-bottom:50px;
        }
        .table th, .table td {
            border: 1px solid #dddddd;
            text-align: center;
            padding: 8px;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>RCF eTrial Quiz Result</h1>
	<a href="view_broad_result">View BroadSheet</a>
    <?php echo $htmlTable; ?>
    <!-- Create a form with a hidden input field containing the CSV data -->
    <form action="download.php" method="post" id="downloadForm">
        <input type="hidden" name="csvData" value="<?php echo htmlspecialchars($csvData); ?>">
        <button type="submit">Download CSV</button>
    </form>
</body>
</html>
