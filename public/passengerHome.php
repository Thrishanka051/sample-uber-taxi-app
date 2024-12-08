<?php require "templates/header.php"; ?>

<h2>Passenger Home</h2>

<p><a href="readPassenger.php">Read Passenger</a></p>
<p><a href="createPassenger.php">Create Passenger</a></p>

<h2>Latest three Passengers</h2>
<table>
  <thead>
    <tr>
      <th>#id</th>
      <th>Name</th>
      <th>Gender</th>
      <th>Mobile</th>
      <th>Email</th>
    </tr>
  </thead>
  <tbody>
    <?php
    require "../config.php";
    require "../common.php";

    try {
        $connection = new PDO($dsn, $username, $password, $options);

        $sql = "SELECT * FROM Passenger ORDER BY userID DESC LIMIT 3";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $passengers = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($passengers as $passenger) {
            echo "<tr>";
            echo "<td>" . escape($passenger["userID"]) . "</td>";
            echo "<td>" . escape($passenger["name"]) . "</td>";
            echo "<td>" . escape($passenger["gender"]) . "</td>";
            echo "<td>" . escape($passenger["mobile"]) . "</td>";
            echo "<td>" . escape($passenger["email"]) . "</td>";
			 echo '<td><a href="viewPassenger.php?id=' . $passenger["userID"] . '">View</a></td>';
            echo "</tr>";
        }
    } catch (PDOException $error) {
        echo $error->getMessage();
    }
    ?>
  </tbody>
</table>

<a href="index.php">Back</a>

<?php require "templates/footer.php"; ?>
