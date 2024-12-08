<!DOCTYPE html>
<html>
<head>
    <title>Uber Trips</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Last 3 Uber Trips</h1>
    
    <?php
    try {
        require "../config.php";
        require "../common.php";
        
        $connection = new PDO($dsn, $username, $password, $options);

        $sql = "SELECT * FROM Trip ORDER BY date DESC LIMIT 3";
        $statement = $connection->prepare($sql);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($result && count($result) > 0) {
            echo '<table>';
            echo '<tr>';
            echo '<th>Start Location</th>';
            echo '<th>Destination</th>';
            echo '<th>Vehicle Type</th>';
            echo '<th>Driver ID</th>';
            echo '<th>Trip Date</th>';
			echo '<th>No of passengers</th>';
			echo '<th>No of vehicles</th>';
            echo '<th>Date</th>';
            echo '<th>Action</th>';
            echo '</tr>';

            foreach ($result as $row) {
                echo '<tr>';
                echo '<td>' . $row["startLocation"] . '</td>';
                echo '<td>' . $row["destination"] . '</td>';
                echo '<td>' . $row["vehicleType"] . '</td>';
                echo '<td>' . $row["driverID"] . '</td>';
                echo '<td>' . $row["tripDate"] . '</td>';
				echo '<td>' . $row["noOFpassengers"] . '</td>';
				echo '<td>' . $row["noOftaxis"] . '</td>';
                echo '<td>' . $row["date"] . '</td>';
                echo '<td><a href="viewTrip.php?id=' . $row["id"] . '">View</a></td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo "No trips found.";
        }
    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
    ?>
	 <a href="index.php">Back</a>

<?php require "templates/footer.php"; ?>

</body>
</html>
