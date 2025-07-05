<?php include 'config.php'; ?>

<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $polling_unit_id = $_POST['polling_unit_id'];
    $entered_by = $_POST['entered_by'];

    foreach ($_POST['party_score'] as $party => $score) {
        $score = intval($score);

        // Insert each party's score
        $sql = "INSERT INTO announced_pu_results 
        (polling_unit_uniqueid, party_abbreviation, party_score, entered_by_user, date_entered, user_ip_address) 
        VALUES (
            '$polling_unit_id', '$party', '$score', '$entered_by', NOW(), '127.0.0.1'
        )";

        $conn->query($sql);
    }

    echo "<p style='color:green;'>Results submitted successfully!</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add New Polling Unit Results</title>
 <link rel="stylesheet" href="css/add.css">
</head>
<body>
  <h2 class="section-heading">Submit New Results for a Polling Unit</h2>

  <form method="POST" action="" class="">
    <div class="center_input">
    <label for="polling_unit_id">Polling Unit ID:</label>
    <input type="text" name="polling_unit_id" id="polling_unit_id" required class="party-item inputstyle"><br><br>
      </div>
   <div class="center_input">
    <label for="entered_by">Entered By:</label>
    <input type="text" name="entered_by" id="entered_by" required class="inputstyle"><br><br>
</div>

    <h4>Enter Score for Each Party:</h4>
<div class="party-grid">
    <?php
    // Get unique parties from the database
    $parties = $conn->query("SELECT DISTINCT party_abbreviation FROM announced_pu_results LIMIT 12");
    while ($party = $parties->fetch_assoc()) {
        $abbr = $party['party_abbreviation'];
      echo "<div class='party-item '>";
        echo "<label>$abbr:</label>";
        echo "<input type='number' name='party_score[$abbr]' required class='inputstyle' min='0' value='0'>";
        echo "</div>";
    }
    ?>
  </div>
    <br>
    <input type="submit" value="Submit Results"
class="submit-button">
  </form>
</body>
</html>
