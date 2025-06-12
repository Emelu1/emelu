<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page 2</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
require "config.php";

// Handle LGA selection
$lga_id = isset($_POST['lga_id']) ? (int)$_POST['lga_id'] : 0;

$lga_result = $conn->query("SELECT lga_id, lga_name FROM lga");
?>
    <h2>Select Local Government</h2>
    <form method="post">
        <select name="lga_id" required>
            <option value="">-- Select LGA --</option>
            <?php while ($row = $lga_result->fetch_assoc()): ?>
                <option value="<?= $row['lga_id'] ?>" <?= ($lga_id == $row['lga_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['lga_name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">View Result</button>
    </form>

    <?php
    if ($lga_id > 0) {
        $sql = "
            SELECT r.party_abbreviation, SUM(r.party_score) AS total_score
            FROM announced_pu_results r
            JOIN polling_unit p ON r.polling_unit_uniqueid = p.polling_unit_id
            WHERE p.lga_id = ?
            GROUP BY r.party_abbreviation
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $lga_id);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<h3>Total Result for Selected LGA:</h3>";
        if ($result->num_rows > 0) {
            echo "<table border='1' cellpadding='5'>
                    <tr><th>Party</th><th>Total Score</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['party_abbreviation']}</td><td>{$row['total_score']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No results found for this LGA.</p>";
        }
        $stmt->close();
    }

    $conn->close();
    ?>
</body>
</html>