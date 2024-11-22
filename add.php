<?php
include('connect.php');

if (isset($_GET['user_id'])) {
  $user_id = intval($_GET['user_id']);
} else {
  die("Error: Invalid or missing user ID.");
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $first_name = ($_POST['fname']);
  $last_name = ($_POST['lname']);
  $p_number = ($_POST['pnumber']);
  $tags = ($_POST['tags']);

  // Prepare the SQL statement
  $sql = "INSERT INTO contacts (first_name, last_name, p_number, tags, user_id) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind parameters
    $stmt->bind_param("ssssi",  $first_name, $last_name, $p_number, $tags, $user_id);

    // Execute the statement
    if ($stmt->execute()) {
      header("Location: home.php?user_id=$user_id");
      exit();
    } else {
      echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
  } else {
    echo "Error preparing statement: " . $conn->error;
  }

  // Close the connection
  $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <title>Contacts | Add</title>
  </head>
</head>
<style>
  form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
  }
</style>

<body>
  <a href="home.php?user_id=<?php echo $user_id; ?>">
    <img src="./img/previous.png" class="prev" alt="prev" />
  </a>

  <div class="container">
    <header>
      <h2>Create New Contact</h2>
    </header>
    <form method="post">
      <label for="fname">First Name:</label>
      <input
        type="text"
        placeholder="First name..."
        name="fname"
        id="fname"
        required />
      <br />

      <label for="lname">Last Name:</label>
      <input
        type="text"
        placeholder="Last name..."
        name="lname"
        id="lname"
        required />
      <br />

      <label for="pnumber">Phone Number:</label>
      <input
        type="text"
        placeholder="Phone number..."
        name="pnumber"
        id="pnumber"
        required />
      <br />

      <label for="tags">Tags:</label>
      <input
        type="text"
        placeholder="Family, Friends, Work or others..."
        name="tags"
        id="tags" />
      <br />

      <button type="submit">Add Contact</button>
    </form>
  </div>
</body>

</html>