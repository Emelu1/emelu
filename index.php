<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
require "config.php";
$state_id = 25;
$sql = "SELECT state_name FROM states WHERE state_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $state_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<h3 class="name">Polling unit for '.$row['state_name'].' State</h3>';
    }
} else {
    echo "No state found with ID 25.";
}

$stmt->close();
$conn->close();
?>
<p>Polling unit result</p>
<?php
require "config.php";
$result_id = 111;
$sql = "SELECT party_abbreviation, party_score FROM announced_pu_results WHERE result_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $result_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<table border="1" cellpadding="10">
            <tr>
                <th>Party</th>
                <th>Score</th>
            </tr>
            <tr>
                <td>'.$row['party_abbreviation'].'</td>
                <td>'.$row['party_score'].'</td>
            </tr>
        </table>';
    }
} else {
    echo "No result found with ID 111.";
}

$stmt->close();
$conn->close();
?><br><br>
<a href="page2.php">Answer 2</a>
<a href="add_polling_unit_results.php">Answer 3</a>
</body>
</html>