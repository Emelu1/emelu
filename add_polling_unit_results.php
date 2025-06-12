<?php
require "config.php";

$party_result = $conn->query("SELECT partyid FROM party");
$parties = [];
while ($row = $party_result->fetch_assoc()) {
    $parties[] = $row['partyid'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $unit_name = trim($_POST['polling_unit_name']);
    $lga_id = (int)$_POST['lga_id'];

    $stmt = $conn->prepare("INSERT INTO polling_unit (polling_unit_name, lga_id) VALUES (?, ?)");
    $stmt->bind_param("si", $unit_name, $lga_id);
    $stmt->execute();
    $polling_unit_id = $stmt->insert_id;
    $stmt->close();

    foreach ($parties as $party) {
        $score = isset($_POST['score'][$party]) ? (int)$_POST['score'][$party] : 0;
        $stmt = $conn->prepare("INSERT INTO announced_pu_results (polling_unit_uniqueid, party_abbreviation, party_score) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $polling_unit_id, $party, $score);
        $stmt->execute();
        $stmt->close();
    }
    echo "<p>Polling unit and results saved successfully!</p>";
}
$lga_result = $conn->query("SELECT lga_id, lga_name FROM lga");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Polling Unit Results</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Enter Results for a New Polling Unit</h2>
    <form method="post">
        <label>Polling Unit Name:</label>
        <input type="text" name="polling_unit_name" required><br><br>

        <label>Select Local Government:</label>
        <select name="lga_id" required>
            <option value="">-- Select LGA --</option>
            <?php while ($row = $lga_result->fetch_assoc()): ?>
                <option value="<?= $row['lga_id'] ?>"><?= htmlspecialchars($row['lga_name']) ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <h3>Enter Party Scores:</h3>
        <?php foreach ($parties as $party): ?>
            <label><?= htmlspecialchars($party) ?>:</label>
            <input type="number" name="score[<?= $party ?>]" min="0" value="0"><br>
        <?php endforeach; ?>

        <br><button type="submit">Save Results</button>
    </form>
</body>
</html>