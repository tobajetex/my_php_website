<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Total LGA Results</title>
  <link rel="stylesheet" href="css/add.css">
</head>
<body class="center">
  <h1>Total Results by Local Government Area</h1>
  <h2>View Total Results by Local Government</h2>

  <form method="GET" action="">
    <label for="lga_id">Select Local Government:</label>
    <select name="lga_id" id="lga_id" required class="inputstyle">
      <option value="">-- Select LGA --</option>
      <?php
        $lga_query = "SELECT * FROM lga";
        $lga_result = $conn->query($lga_query);
        while ($lga = $lga_result->fetch_assoc()) {
            $selected = (isset($_GET['lga_id']) && $_GET['lga_id'] == $lga['uniqueid']) ? 'selected' : '';
            echo "<option value='{$lga['uniqueid']}' $selected>{$lga['lga_name']}</option>";
        }
      ?>
    </select>
    <input type="submit" value="View Total" class="inputstyle">
  </form>

  <hr>

  <?php
  if (isset($_GET['lga_id']) && $_GET['lga_id'] != "") {
      $lga_id = $_GET['lga_id'];

      echo "<h3 class='section-heading'>Summed Party Results for Selected LGA</h3>";

      // SQL: Join polling_unit and announced_pu_results, filter by lga_id, group by party
      $sql = "
      SELECT r.party_abbreviation, SUM(r.party_score) AS total_score
      FROM announced_pu_results r
      JOIN polling_unit p ON r.polling_unit_uniqueid = p.uniqueid
      WHERE p.lga_id = '$lga_id'
      GROUP BY r.party_abbreviation
      ";

      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          echo "<ul class='center'>";
          while ($row = $result->fetch_assoc()) {
              echo "<li>{$row['party_abbreviation']}: {$row['total_score']}</li>";
          }
          echo "</ul>";
      } else {
          echo "<p>No results available for this LGA.</p>";
      }
  }
  ?>
</body>
</html>
