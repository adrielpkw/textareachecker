<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Compare CSV Files</title>
</head>
<body>
    <div style = "display: flex;
    justify-content: center;">
     <form method="post" action="">
        <label for="data1">Enter data for File 1:</label>
        <br>
        <textarea name="data1" id="data1" rows="10" cols="50" required></textarea>
        <br>
        <label for="data2">Enter data for File 2:</label>
        <br>
        <textarea name="data2" id="data2" rows="10" cols="50" required></textarea>
        <br>
        <input style = "    margin: auto;
    display: flex; " type="submit" name="submit" value="Compare">
    </form>   
    </div>
    
    <br>
    <br>

    <?php
    if(isset($_POST['submit'])) {
        $data1 = explode("\n", $_POST['data1']);
        $data2 = explode("\n", $_POST['data2']);

        // Convert the data to arrays of arrays
        if(!empty($data1)){
            $data1 = array_map(function ($row) {
                return str_getcsv($row, "\t");
            }, $data1);
            
            // Skip empty rows
            $data1 = array_filter($data1, function ($row) {
                return !empty(array_filter($row));
            });
        }
        if(!empty($data2)){
            $data2 = array_map(function ($row) {
                return str_getcsv($row, "\t");
            }, $data2);
            
            // Skip empty rows
            $data2 = array_filter($data2, function ($row) {
                return !empty(array_filter($row));
            });
        }

    // Count the rows in File 1 and File 2
    if(!empty($data1)){$rowCountFile1 = count($data1);}
    if(!empty($data2)){$rowCountFile2 = count($data2);}
  // Count the number of rows containing specified strings

        // Display the results
        function displayResultsInTable($results) {
            echo "<style>";
            echo "table {
                      border-collapse: collapse;
                      width: 100%;
                      font-size: 15px;
                   }
                   th, td {
                      padding: 10px;
                      border: 1px solid black;
                   }
                   th {
                      background-color: #f2f2f2;
                   }";
            echo "</style>";
            echo "<table>";
            echo "<tr>";
            echo "<th>No.</th>";
            echo "<th>Tracking</th>";
            echo "<th>Recipient</th>";
            echo "<th style = 'background-color: lightgreen;'>Last Status (File 1)</th>";
            echo "<th>Last Status (File 2)</th>";
            echo "</tr>";
            foreach ($results as $row) {
                echo "<tr>";
                echo "<td>" . $row[0] . "</td>";
                echo "<td>" . $row[3] . "</td>";
                echo "<td>" . $row[4] . "</td>";
                echo "<td >" . $row[5] . "</td>";
                echo "<td>" . $row[6] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }

       

        // Compare the data and display the results
        $results = array();
        // Initialize counters
$count1 = 0;
$count2 = 0;

// Count rows with specified strings in File 1
foreach ($data1 as $row1) {
    if(!empty($row1) &&!empty($row1[0])){
    if (strpos($row1[5], "37") !== false || strpos($row1[5], "75") !== false || strpos($row1[5], "1261") !== false || strpos($row1[5], "36") !== false || strpos($row1[5], "1259") !== false) {
        $count1++;
    }
}
}

// Count rows with specified strings in File 2
foreach ($data2 as $row2) {
    if(!empty($row2) &&!empty($row2[0])){

    if (strpos($row2[5], "37") !== false || strpos($row2[5], "75") !== false || strpos($row2[5], "1261") !== false || strpos($row2[5], "36") !== false || strpos($row2[5], "1259") !== false) {
        $count2++;
    }
}
}
// Display the results

$pattern = "/(75|1261|36|1259)/";

$percent1 = round((($count1/$rowCountFile1)* 100), 2);
$percent2 = round((($count2/$rowCountFile2) * 100), 2);
    foreach ($data1 as $row1) {
        foreach ($data2 as $row2) {
            if (count($row1) >= 6 && count($row2) >= 6 && isset($row1[0], $row1[1], $row1[2], $row1[3], $row1[4], $row1[5], $row2[5]) && $row1[0] == $row2[0] && $row1[1] == $row2[1] && $row1[2] == $row2[2] && $row1[3] == $row2[3] && $row1[4] == $row2[4] && $row1[5] !== $row2[5] && strpos($row2[5], "37") == false && strpos($row1[5], "37") == false) {
                if (preg_match($pattern, $row1[5]))  {
                    if (!(preg_match($pattern, $row2[5]))) {
                        $results[] = array($row1[0], $row1[1], $row1[2], $row1[3], $row1[4], $row1[5], $row2[5]);
                    }
                }
            }
        }
    }
    
    echo "<table style = '    margin: auto;
    width: 80%;'>";
            echo "<tr>";
            echo "<th>Total Records Processed for File 1</th>";
            echo "<th style = 'background-color: lightgreen;'>Total Records in Green for File 1</th>";
            echo "<th style = 'background-color: lightgreen;'>Green Percentage Value for File 1</th>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>" . $rowCountFile1 . "</td>";
            echo "<td >" . $count1 . "</td>";
            echo "<td >" . $percent1 . "</td>";
            echo "</tr>";
            echo"</table>";

            echo "<table style = '    margin: auto;
            width: 80%;'>";
            echo "<tr>";
            echo "<th>Total Records Processed for File 2</th>";
            echo "<th style = 'background-color: lightgreen;'>Total Records in Green for File 2</th>";
            echo "<th style = 'background-color: lightgreen;'>Green Percentage Value for File 2</th>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>" . $rowCountFile2 . "</td>";
            echo "<td >" . $count2 . "</td>";
            echo "<td > " . $percent2 . "</td>";
            echo "</tr>";
            echo"</table>";
    echo "<br>";
    displayResultsInTable($results);
    
    

}

?>

