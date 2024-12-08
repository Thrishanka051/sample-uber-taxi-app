<!DOCTYPE html>
<html>
<head>
    <title>View Uber Passenger</title>
</head>
<body>
    <h1>View Uber Passenger</h1>
    
    <?php
    try {
        require "../config.php";
        require "../common.php";
        
        if (isset($_GET['id'])) {
            $connection = new PDO($dsn, $username, $password, $options);

            $id = $_GET['id'];

            $sql = "SELECT * FROM Passenger WHERE userID = :id";
            $statement = $connection->prepare($sql);
            $statement->bindValue(':id', $id);
            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                echo "<p><b>Name: </b>" . $result["name"] . "</p>";
                echo "<p><b>Gender: </b>" . $result["gender"] . "</p>";
                echo "<p><b>Mobile: </b>" . $result["mobile"] . "</p>";
                echo "<p><b>Email: </b>" . $result["email"] . "</p>";

                $sql = "SELECT T.startLocation, T.destination, T.tripDate 
                        FROM Trip T
                        JOIN trip_passenger TP ON T.id = TP.trip_id
                        WHERE TP.passenger_id = :id";

                $statement = $connection->prepare($sql);
                $statement->bindValue(':id', $id);
                $statement->execute();

                $trips = $statement->fetchAll(PDO::FETCH_ASSOC);

                if ($trips) {
                    echo "<h2>Ordered Trips</h2>";
                    echo "<table border='1'>"; // Add 'border' attribute to create table lines
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Start Location</th>";
                    echo "<th>Destination</th>";
                    echo "<th>Trip Date</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";

                    foreach ($trips as $trip) {
                        echo "<tr>";
                        echo "<td>" . $trip["startLocation"] . "</td>";
                        echo "<td>" . $trip["destination"] . "</td>";
                        echo "<td>" . $trip["tripDate"] . "</td>";
                        echo "</tr>";
                    }

                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "<p>No corresponding trips found for this passenger.</p>";
                }

                // Add a link to the Edit page for updating passenger details
                echo '<a href="updatePassenger.php?id=' . $id . '">update</a><br>';
            } else {
                echo "Passenger not found.";
            }
        } else {
            echo "Invalid passenger ID.";
        }
    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
    ?>

    <a href="passengerHome.php">Back to homepage</a>

    <?php require "templates/footer.php"; ?>
</body>
</html>
