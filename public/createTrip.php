<?php

require "../config.php";
require "../common.php";

if (isset($_POST['submit'])) {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) die();

    try {
        $connection = new PDO($dsn, $username, $password, $options);

        // Begin a transaction
        $connection->beginTransaction();

        $new_trip = array(
            "startLocation" => $_POST['startLocation'],
            "destination" => $_POST['destination'],
            "vehicleType" => $_POST['vehicleType'],
            "driverID" => $_POST['driverID'],
            "tripDate" => $_POST['tripDate'],
            "noOFpassengers" => $_POST['noOFpassengers'],
            "noOftaxis" => $_POST['noOftaxis']
        );

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            "Trip",
            implode(", ", array_keys($new_trip)),
            ":" . implode(", :", array_keys($new_trip))
        );

        $statement = $connection->prepare($sql);
        $statement->execute($new_trip);

        // Get the last inserted trip ID
        $trip_id = $connection->lastInsertId();

        // Commit the transaction if everything succeeded
        $connection->commit();

    } catch (PDOException $error) {
        // Rollback the transaction if any error occurs
        $connection->rollback();
        echo "Transaction failed: " . $error->getMessage();
    }
}
?>

<?php require "templates/header.php"; ?>

<?php if (isset($_POST['submit']) && $trip_id) : ?>
    <blockquote>Trip <?php echo escape($_POST['startLocation']); ?> successfully added with ID: <?php echo escape($trip_id); ?></blockquote>
<?php endif; ?>

<h2>Add a Trip</h2>

<form method="post">
    <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">
    <label for="startLocation">Start Location</label>
    <input type="text" name="startLocation" id="startLocation">
    <label for="destination">Destination Location</label>
    <input type="text" name="destination" id="destination">
    <label for="vehicleType">Vehicle Type</label>
    <input type="text" name="vehicleType" id="vehicleType">
    <label for="driverID">Driver ID No</label>
    <input type="text" name="driverID" id="driverID">
    <label for="tripDate">Trip Date</label>
    <input type="date" name="tripDate" id="tripDate">
    <label for="noOFpassengers">No of passengers</label>
    <input type="text" name="noOFpassengers" id="noOFpassengers">
    <label for="noOftaxis">No of vehicles</label>
    <input type="text" name="noOftaxis" id="noOftaxis">
    <input type="submit" name="submit" value="Submit">
</form>

<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>
