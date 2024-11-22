<?php
include('connect.php');

// Get the contact and user ID from the URL
$contact_id = isset($_GET['contact_id']) ? intval($_GET['contact_id']) : 0;
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// Initialize variables
$first_name = "";
$last_name = "";
$p_number = "";
$tags = "";

// Fetch contact information for editing
if ($contact_id > 0) {
  $sql = "SELECT first_name, last_name, p_number, tags FROM contacts WHERE contact_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $contact_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $contact = $result->fetch_assoc();
    $first_name = $contact['first_name'];
    $last_name = $contact['last_name'];
    $p_number = $contact['p_number'];
    $tags = $contact['tags'];
  } else {
    echo "Contact not found!";
    exit;
  }

  $stmt->close();
}

// Handle form submission for updating
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $first_name = trim($_POST['fname']);
  $last_name = trim($_POST['lname']);
  $p_number = trim($_POST['pnumber']);
  $tags = trim($_POST['tags']);

  // Update existing contact
  $sql = "UPDATE contacts SET first_name = ?, last_name = ?, p_number = ?, tags = ? WHERE contact_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssssi", $first_name, $last_name, $p_number, $tags, $contact_id);
  $stmt->execute();
  $stmt->close();
  $conn->close();

  // Redirect back to home after saving
  header("Location: home.php?user_id=" . $user_id);
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style.css" />
  <title>Update Contact</title>
  <style>
    form label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
      color: #333;
    }

    input {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 1rem;
    }

    button {
      padding: 10px 15px;
      background-color: #4caf50;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 1rem;
      cursor: pointer;
    }

    button:hover {
      background-color: #45a049;
    }
  </style>
</head>

<body>
  <a href="home.php?user_id=<?php echo $user_id; ?>">
    <img class="prev" src="./img/previous.png" alt="Go Back" /></a>

  <div class="container">
    <header>
      <h2>Update Contact</h2>
    </header>
    <form method="post">
      <label for="fname">First Name:</label>
      <input type="text" name="fname" id="fname" value="<?php echo ($first_name); ?>" placeholder="First name..." required />

      <label for="lname">Last Name:</label>
      <input type="text" name="lname" id="lname" value="<?php echo ($last_name); ?>" placeholder="Last name..." required />

      <label for="pnumber">Phone Number:</label>
      <input type="text" name="pnumber" id="pnumber" value="<?php echo ($p_number); ?>" placeholder="Phone number..." required />

      <label for="tags">Tags:</label>
      <input type="text" name="tags" id="tags" value="<?php echo ($tags); ?>" placeholder="Family, Friends, Work, or others..." />

      <button type="submit">Update</button>
    </form>
  </div>
</body>

</html>