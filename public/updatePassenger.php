<!DOCTYPE html>
<html>
<head>
    <title>Update Uber Passenger</title>
</head>
<body>
    <h1>Update Uber Passenger</h1>
    
    <?php
    try {
        require "../config.php";
        require "../common.php";
        
        if (isset($_GET['id'])) {
            $connection = new PDO($dsn, $username, $password, $options);

            $id = $_GET['id'];

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Get data from the form
                $name = $_POST['name'];
                $gender = $_POST['gender'];
                $mobile = $_POST['mobile'];
                $email = $_POST['email'];
                $selectedTrips = $_POST['trips']; // Updated list of selected trips

                // Start a transaction
                $connection->beginTransaction();

                // Update passenger details
                $sql = "UPDATE Passenger 
                        SET name = :name,
                            gender = :gender,
                            mobile = :mobile,
                            email = :email
                        WHERE userID = :id";
                $statement = $connection->prepare($sql);
                $statement->bindValue(':id', $id);
                $statement->bindValue(':name', $name);
                $statement->bindValue(':gender', $gender);
                $statement->bindValue(':mobile', $mobile);
                $statement->bindValue(':email', $email);
                $statement->execute();

                // Delete existing passenger-trip relationships
                $sql = "DELETE FROM trip_passenger WHERE passenger_id = :id";
                $statement = $connection->prepare($sql);
                $statement->bindValue(':id', $id);
                $statement->execute();

                // Insert updated passenger-trip relationships
                foreach ($selectedTrips as $trip_id) {
                    $sql = "INSERT INTO trip_passenger (trip_id, passenger_id) VALUES (:trip_id, :passenger_id)";
                    $statement = $connection->prepare($sql);
                    $statement->execute(array(":trip_id" => $trip_id, ":passenger_id" => $id));
                }

                // Commit the transaction if everything succeeded
                $connection->commit();

                echo "Passenger details and trips updated successfully.";
            }

            $sql = "SELECT * FROM Passenger WHERE userID = :id";
            $statement = $connection->prepare($sql);
            $statement->bindValue(':id', $id);
            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Display a form to edit the passenger details
                echo '<form method="post">';
                echo 'Name: <input type="text" name="name" value="' . $result["name"] . '"><br>';
                echo 'Gender: <input type="text" name="gender" value="' . $result["gender"] . '"><br>';
                echo 'Mobile: <input type="text" name="mobile" value="' . $result["mobile"] . '"><br>';
                echo 'Email: <input type="text" name="email" value="' . $result["email"] . '"><br>';
                echo '<input type="submit" value="Save">';

                // Display a list of available trips for selection
                echo '<h2>Select Trips</h2>';
                $sql = "SELECT id, startLocation, destination FROM Trip";
                $statement = $connection->prepare($sql);
                $statement->execute();
                $trips = $statement->fetchAll(PDO::FETCH_ASSOC);

                $sql = "SELECT trip_id FROM trip_passenger WHERE passenger_id = :id";
                $statement = $connection->prepare($sql);
                $statement->bindValue(':id', $id);
                $statement->execute();
                $selectedTrips = $statement->fetchAll(PDO::FETCH_COLUMN);

                echo '<select name="trips[]" multiple>';
                foreach ($trips as $trip) {
                    $selected = in_array($trip['id'], $selectedTrips) ? 'selected' : '';
                    echo '<option value="' . $trip['id'] . '" ' . $selected . '>' . $trip['startLocation'] . ' to ' . $trip['destination'] . '</option>';
                }
                echo '</select>';
                echo '</form>';
            } else {
                echo "Passenger not found.";
            }
        } else {
            echo "Invalid passenger ID.";
        }
    } catch (PDOException $error) {
        // Rollback the transaction if any error occurs
        $connection->rollback();
        echo $sql . "<br>" . $error->getMessage();
    }
    ?>
    
    <a href="viewPassenger.php?id=<?php echo $id; ?>">Back to View</a><br>
    <a href="passengerHome.php">Back to homepage</a>

    <?php require "templates/footer.php"; ?>
</body>
</html>
