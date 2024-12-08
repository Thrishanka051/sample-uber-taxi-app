<!DOCTYPE html>
<html>
<head>
    <title>Edit Uber Trip</title>
</head>
<body>
    <h1>Edit Uber Trip</h1>
    
    <?php
    try {
        require "../config.php";
        require "../common.php";
        
        if (isset($_GET['id'])) {
            $connection = new PDO($dsn, $username, $password, $options);

            $id = $_GET['id'];

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Get data from the form
                $startLocation = $_POST['startLocation'];
                $destination = $_POST['destination'];
                $vehicleType = $_POST['vehicleType'];
                $driverID = $_POST['driverID'];
                $tripDate = $_POST['tripDate'];
				$noOFpassengers = $_POST['noOFpassengers'];
				$noOftaxis = $_POST['noOftaxis']; 
				
                // Update the data in the database
                $sql = "UPDATE Trip 
                        SET startLocation = :startLocation,
                            destination = :destination,
                            vehicleType = :vehicleType,
                            driverID = :driverID,
                            tripDate = :tripDate,
							noOFpassengers =:noOFpassengers,
							noOftaxis = :noOftaxis
                        WHERE id = :id";
                $statement = $connection->prepare($sql);
                $statement->bindValue(':id', $id);
                $statement->bindValue(':startLocation', $startLocation);
                $statement->bindValue(':destination', $destination);
                $statement->bindValue(':vehicleType', $vehicleType);
                $statement->bindValue(':driverID', $driverID);
                $statement->bindValue(':tripDate', $tripDate);
				$statement->bindValue(':noOFpassengers', $noOFpassengers);
				$statement->bindValue(':noOftaxis', $noOftaxis);
                $statement->execute();

                echo "Record updated successfully.";
            }

            $sql = "SELECT * FROM Trip WHERE id = :id";
            $statement = $connection->prepare($sql);
            $statement->bindValue(':id', $id);
            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Display a form to edit the trip details
                echo '<form method="post">';
                echo 'Start Location: <input type="text" name="startLocation" value="' . $result["startLocation"] . '"><br>';
                echo 'Destination: <input type="text" name="destination" value="' . $result["destination"] . '"><br>';
                echo 'Vehicle Type: <input type="text" name="vehicleType" value="' . $result["vehicleType"] . '"><br>';
                echo 'Driver ID: <input type="text" name="driverID" value="' . $result["driverID"] . '"><br>';
                echo 'Trip Date: <input type="text" name="tripDate" value="' . $result["tripDate"] . '"><br>';
				echo 'No of passengers: <input type="text" name="noOFpassengers" value="' . $result["noOFpassengers"] . '"><br>';
				echo 'No of vehicles: <input type="text" name="noOftaxis" value="' . $result["noOftaxis"] . '"><br>';
                echo '<input type="submit" value="Save">';
                echo '</form>';
            } else {
                echo "Trip not found.";
            }
        } else {
            echo "Invalid trip ID.";
        }
    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
    ?>
	 <a href="home.php">Back to homepage</a>

<?php require "templates/footer.php"; ?>
</body>
</html>
