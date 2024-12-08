<?php
require "../config.php";
require "../common.php";

$tripSelectOptions = '';

if (isset($_POST['submit'])) {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) die();

    try {
        $connection = new PDO($dsn, $username, $password, $options);

        // Begin a transaction
        $connection->beginTransaction();

        $new_passenger = array(
            "name" => $_POST['name'],
            "gender" => $_POST['gender'],
            "mobile" => $_POST['mobile'],
            "email" => $_POST['email']
        );

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            "Passenger",
            implode(", ", array_keys($new_passenger)),
            ":" . implode(", :", array_keys($new_passenger))
        );

        $statement = $connection->prepare($sql);
        $statement->execute($new_passenger);

        $passenger_id = $connection->lastInsertId();

        if (isset($_POST['trips']) && is_array($_POST['trips'])) {
            foreach ($_POST['trips'] as $trip_id) {
                $sql = "INSERT INTO trip_passenger (trip_id, passenger_id) VALUES (:trip_id, :passenger_id)";
                $statement = $connection->prepare($sql);
                $statement->execute(array(":trip_id" => $trip_id, ":passenger_id" => $passenger_id));
            }
        }

        // Commit the transaction if everything succeeded
        $connection->commit();

    } catch (PDOException $error) {
        // Rollback the transaction if any error occurs
        $connection->rollback();
        echo $sql . "<br>" . $error->getMessage();
    }
}

try {
    $connection = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT id, startLocation, destination FROM Trip";
    $statement = $connection->prepare($sql);
    $statement->execute();
    $trips = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($trips as $trip) {
        $tripSelectOptions .= '<option value="' . $trip['id'] . '">' . $trip['startLocation'] . ' to ' . $trip['destination'] . '</option>';
    }
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>

<?php require "templates/header.php"; ?>

<h2>Add a Passenger</h2>

<form method="post">
    <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">
    <label for="name">Name</label>
    <input type="text" name="name" id="name">
    <label for="gender">Gender</label>
    <select name="gender" id="gender">
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
    </select>
    <label for="mobile">Mobile Number</label>
    <input type="text" name="mobile" id="mobile">
    <label for="email">Email</label>
    <input type="text" name="email" id="email">
    <label for="trips">Select Trips</label>
    <select name="trips[]" id="trips" multiple>
        <?php echo $tripSelectOptions; ?>
    </select>
    <input type="submit" name="submit" value="Submit">
</form>

<a href="passengerHome.php">Back to home</a>

<?php require "templates/footer.php"; ?>
