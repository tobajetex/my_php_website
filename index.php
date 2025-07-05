<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Polling Unit Result Viewer</title>
   <link rel="stylesheet" href="css/add.css">
</head>
<body class="center">
  <h2>View Polling Unit Results</h2>

  <form method="GET" action="">
    <!-- Select LGA -->
    <label for="lga_id" class="section-heading">Select Local Government:</label>
    <select name="lga_id" id="lga_id" onchange="this.form.submit()" class="inputstyle">
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

    <br><br>

    <!-- Select Polling Unit (only after LGA is selected) -->
    <?php if (isset($_GET['lga_id']) && $_GET['lga_id'] != ""): ?>
      <label for="polling_unit_id">Select Polling Unit:</label>
      <select name="polling_unit_id" id="polling_unit_id" onchange="this.form.submit()"class="inputstyle">
        <option value="">-- Select Polling Unit --</option>
        <?php
          $lga_id = $_GET['lga_id'];
          $pu_query = "SELECT * FROM polling_unit WHERE lga_id = '$lga_id'";
          $pu_result = $conn->query($pu_query);
          while ($pu = $pu_result->fetch_assoc()) {
              $selected = (isset($_GET['polling_unit_id']) && $_GET['polling_unit_id'] == $pu['uniqueid']) ? 'selected' : '';
              echo "<option value='{$pu['uniqueid']}' $selected>{$pu['polling_unit_name']}</option>";
          }
        ?>
      </select>
    <?php endif; ?>
  </form>

  <hr>

  <!-- Show polling unit results -->
  <?php
    if (isset($_GET['polling_unit_id']) && $_GET['polling_unit_id'] != "") {
        $pu_id = $_GET['polling_unit_id'];
        echo "<h3>Results for Polling Unit ID: $pu_id</h3>";

        $results = $conn->query("SELECT * FROM announced_pu_results WHERE polling_unit_uniqueid = '$pu_id'");
        if ($results->num_rows > 0) {
            echo "<ul class='center'>";
            while ($row = $results->fetch_assoc()) {
                echo "<li>{$row['party_abbreviation']}: {$row['party_score']}</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No results found for this polling unit.</p>";
        }
    }
  ?>
</body>
</html>
