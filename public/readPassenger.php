<?php
require "../config.php";
require "../common.php";

if (isset($_POST['submit'])) {
  if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) die();

  try  {
    $connection = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT * 
            FROM Passenger
            WHERE name = :passenger_name";

    $passenger_name = $_POST['passenger_name'];
    $statement = $connection->prepare($sql);
    $statement->bindParam(':passenger_name', $passenger_name, PDO::PARAM_STR);
    $statement->execute();

    $result = $statement->fetchAll();
  } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
  }
}
?>
<?php require "templates/header.php"; ?>

<?php  
if (isset($_POST['submit'])) {
  if ($result && $statement->rowCount() > 0) { ?>
    <h2>Results</h2>

    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Gender</th>
          <th>Mobile</th>
          <th>Email</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($result as $row) : ?>
    <tr>
        <td><?php echo escape($row["userID"]); ?></td>
        <td><?php echo escape($row["name"]); ?></td>
        <td><?php echo escape($row["gender"]); ?></td>
        <td><?php echo escape($row["mobile"]); ?></td>
        <td><?php echo escape($row["email"]); ?></td>
        <td>
            <a href="viewPassenger.php?id=<?php echo escape($row["userID"]); ?>">Trips</a>
        </td>
    </tr>
<?php endforeach; ?>

      </tbody>
    </table>
    <?php } else { ?>
      <blockquote>No results found for <?php echo escape($_POST['passenger_name']); ?>.</blockquote>
    <?php } 
} ?> 

<h2>Find Passenger by Name</h2>

<form method="post">
  <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">
  <label for="passenger_name">Passenger Name</label>
  <input type="text" id="passenger_name" name="passenger_name">
  <input type="submit" name="submit" value="View Results">
</form>

<a href="passengerHome.php">Back to home</a>

<?php require "templates/footer.php"; ?>
