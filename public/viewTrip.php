<!DOCTYPE html>
<html>
<head>
    <title>View Uber Trip</title>
</head>
<body>
    <h1>View Uber Trip</h1>
    
    <?php
    try {
        require "../config.php";
        require "../common.php";
        
        if (isset($_GET['id'])) {
            $connection = new PDO($dsn, $username, $password, $options);

            $id = $_GET['id'];

            $sql = "SELECT * FROM Trip WHERE id = :id";
            $statement = $connection->prepare($sql);
            $statement->bindValue(':id', $id);
            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                echo "<p><b>Start Location :  </b>" . $result["startLocation"] . "</p>";
                echo "<p><b>Destination :  </b>" . $result["destination"] . "</p>";
                echo "<p><b>Vehicle Type :  </b>" . $result["vehicleType"] . "</p>";
                echo "<p><b>Driver ID :  </b>" . $result["driverID"] . "</p>";
                echo "<p><b>Trip Date :  </b>" . $result["tripDate"] . "</p>";
				echo "<p><b>No of passengers :  </b>" . $result["noOFpassengers"] . "</p>";
				echo "<p><b>No of vehicles :  </b>" . $result["noOftaxis"] . "</p>";
                echo "<p><b>Date :  </b>" . $result["date"] . "</p>";
				
				echo '<a href="edit.php?id=' . $result["id"] . '">Edit</a><br><br>';
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
