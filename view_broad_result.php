<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "rcf_database"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch data
$sql = "SELECT * FROM score_sheet ORDER BY candidate_name ASC"; // Replace with your actual table name
$result = mysqli_query($conn,$sql);

// Associative array to hold the pivoted data
$pivotedData = array();

// Loop through the result set
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $name = $row['candidate_name'];
        $course = $row['course'];
        $score = $row['score'];

        // Add score to the corresponding name and course in the pivoted data array
        $pivotedData[$name][$course] = $score;
    }
} else {
    echo "0 results";
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Broad Result</title>
    <style>
        table {
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
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
		thead {
			background-color:blue;
		}
    </style>
</head>
<body>
    <!--h2>View Result Broadsheet</h2-->
	<h2>23rd March, 2024: RCF eTrial Quiz Result</h2>
    <a href="admin_view_scores">View Score</a>
    <table width="100%" border="1" class="table">
		<thead>
			<tr>
				<th>SN</th>
				<th>NAME OF CANDIDATE</th>
				<th>CHM 101(20)</th>
				<th>PHY 101(20)</th>
				<th>PHY 103(20)</th>
				<th>MAT 101(20)</th>
				<th>MAT 113(20)</th>
			</tr>
		</thead>
		<tbody>
        <?php
        $counter = 0;
        // Loop through the pivoted data and display it in HTML table format
        foreach ($pivotedData as $name => $scores) {
            echo "<tr>";
            echo "<td>" . ++$counter . "</td>";
            echo "<td>" . strtoupper($name) . "</td>";
            echo "<td>" . (isset($scores['CHM101']) ? $scores['CHM101'] : '') . "</td>";
            echo "<td>" . (isset($scores['PHY101']) ? $scores['PHY101'] : '') . "</td>";
			echo "<td>" . (isset($scores['PHY103']) ? $scores['PHY103'] : '') . "</td>";
            echo "<td>" . (isset($scores['MAT101']) ? $scores['MAT101'] : '') . "</td>";
            echo "<td>" . (isset($scores['MAT113']) ? $scores['MAT113'] : '') . "</td>";
            echo "</tr>";
        }
        ?>
		</tbody>
    </table>

    <!-- Download CSV button -->
    <form action="download.php" method="post">
        <input type="hidden" name="csv_data" value="<?php echo base64_encode(serialize($pivotedData)); ?>">
        <button type="submit">Download CSV</button>
    </form>
	<footer style="text-align:center;">&#169 Designed by RCF dev Team (2023 - 2024)</footer>
</body>
</html>
