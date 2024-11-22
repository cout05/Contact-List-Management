<?php
include('connect.php');

// Get the contact and user ID from the URL
$contact_id = isset($_GET['contact_id']) ? intval($_GET['contact_id']) : 0;
$user_id = intval($_GET['user_id']);

// Prepare and execute the SQL statement to fetch contact details
$sql = "SELECT first_name, last_name, p_number, tags FROM contacts WHERE contact_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $contact_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the contact exists
if ($result->num_rows > 0) {
  // Fetch contact information
  $contact = $result->fetch_assoc();
} else {
  echo "<p>Contact not found.</p>";
  exit; // Stop execution if contact not found
}

// Close the database connection
$stmt->close();
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style.css" />
  <title>Contacts | Information</title>
</head>
<style>
  h2 {
    margin: 0 auto 10px auto;
  }

  .contact-book {
    width: 125px;
    height: 125px;
  }

  .info {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .field {
    display: flex;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
    padding: 10px 15px;
    width: 250px;
    border-radius: 8px;
    background-color: rgb(255, 255, 255, 0.5);
    font-size: 1rem;
    color: #333;
    transition: background-color 0.2s;
  }

  .field:hover {
    background-color: #dee2e6;
  }

  .info_con {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
  }

  .functions {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 20px;
  }

  .function {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    font-weight: 600;
    padding: 10px;
    width: 200px;
    border-radius: 10px;
    cursor: pointer;
    background-color: rgb(255, 255, 255, 0.5);
  }

  .function:hover {
    background-color: #dee2e6;
  }

  .contact_con {
    background-color: rgb(255, 255, 255, 0.5);
    border-radius: 8px;
    padding: 25px 15px;
  }

  @media (max-width: 600px) {
    .info_con {
      flex-direction: column;
    }

    .field {
      width: 300px;
    }

    .contact_con {
      display: none;
    }
  }
</style>

<body>
  <a href="home.php?user_id=<?php echo $user_id; ?>">
    <img class="prev" src="./img/previous.png" alt="prev" /></a>

  <div class="container">
    <header>
      <h2>Contact Information</h2>
    </header>
    <div class="info_con">
      <div class="contact_con">
        <img
          class="contact-book"
          src="./img/contact-book.png"
          alt="contact-book" />
      </div>
      <div class="info">
        <div class="field">
          <label><img src="./img/pfp.png" class="icon" alt="pfp" /></label>
          <span id="contactName"><?php echo $contact["first_name"] . " " . $contact["last_name"]; ?></span>
        </div>
        <div class="field">
          <label><img src="./img/phone.png" class="icon" alt="phone" /></label>
          <span id="contactPhone"> <?php echo $contact["p_number"]; ?></span>
        </div>
        <div class="field">
          <label><img src="./img/tags.png" class="icon" alt="tags" /></label>
          <span id="contactTags"><?php echo $contact["tags"]; ?></span>
        </div>
      </div>
    </div>
    <div class="functions">
      <div class="function mssg">
        <img src="./img/message.png" class="icon" alt="message" /> Message
      </div>
      <div class="function call">
        <img src="./img/phone.png" class="icon" alt="message" /> Call
      </div>
    </div>
  </div>
</body>

</html>