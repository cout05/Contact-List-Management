<?php
include('connect.php');

if (isset($_GET['user_id'])) {
  $user_id = intval($_GET['user_id']);
} else {
  die("Error: Invalid or missing user ID.");
}

// Check if search term is provided
$search_term = '';
if (isset($_POST['search'])) {
  $search_term = $_POST['search'];  // Get the search term from the form
}

// Prepare the SQL statement to fetch contacts based on the search term
$sql = "SELECT contact_id, first_name FROM contacts WHERE user_id = ? AND first_name LIKE ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
  // Bind the parameters and execute the statement
  $search_like = "%" . $search_term . "%"; // Add percent symbols for the LIKE query
  $stmt->bind_param("is", $user_id, $search_like);
  $stmt->execute();

  // Get the result set
  $result = $stmt->get_result();
} else {
  die("Error preparing SQL statement: " . $conn->error);
}

// Function to get a random color for pfp
function getRandomColor()
{
  $colors = ['#FF5733', '#33FF57', '#3357FF', '#FF33A6', '#FF8333', '#33FFA6', '#A633FF']; // Define a set of colors
  return $colors[array_rand($colors)];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style.css" />
  <title>Contacts | Home</title>
</head>
<style>
  .pfp {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    color: white;
    background-color: aliceblue;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 18px;
    text-transform: uppercase;
  }

  .contacts {
    display: flex;
    flex-direction: column;
    height: 340px;
    overflow-y: auto;
  }

  .contact {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 15px;
    border-radius: 8px;
    background-color: rgba(255, 255, 255, 0.5);
    font-size: 1rem;
    color: #333;
    text-decoration: none;
    margin-bottom: 10px;
    transition: background-color 0.2s ease;
  }

  .contact:hover {
    background-color: #dee2e6;
  }

  .contact img.icon {
    cursor: pointer;
  }

  .contact a {
    text-decoration: none;
    color: inherit;
  }

  .info {
    display: flex;
    gap: 1rem;
    align-items: center;
  }

  .info>p {
    font-weight: 600;
  }

  .oper {
    display: flex;
    gap: 1rem;
    align-items: center;
  }

  .empty {
    color: #171a25;
    font-weight: 600;
    font-size: 1rem;
    text-align: center;
    margin: auto;
  }

  .empty span {
    display: block;
    font-size: 2rem;
  }

  h1 {
    color: #171a25;

  }

  @media (max-width: 600px) {

    .logout {
      width: 35px;
      height: 35px;
    }
  }
</style>

<body>
  <a href="users.php">
    <img class="prev logout" src="./img/logout.png" alt="logout" /></a>

  <div class="container">
    <header>
      <h1>Contacts</h1>
      <a href="add.php?user_id=<?php echo $user_id; ?>">
        <img src="./img/more.png" class="add-btn" alt="add" />
      </a>
    </header>

    <div class="main">

      <form method="POST" action="">
        <input
          placeholder="Search contacts..."
          style="width: 100%;
          padding: 10px 15px 10px 35px;
          margin: 20px 0 20px 0px;
          border: 1px solid #ddd;
          border-radius: 20px;
          background-color: rgb(255, 255, 255, 0.3);
          backdrop-filter: blur(10px);
          background-image: url('./img/search.svg');
          background-repeat: no-repeat;
          background-position: 10px center;
          font-size: 1rem;
          color: #333;
          outline: none;"
          type="text"
          name="search"
          value="<?php echo ($search_term); ?>" />
      </form>

      <div class="contacts">
        <?php
        // Check if there are any contacts
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $contact_id = $row['contact_id'];
            $first_name = ($row['first_name']); // Sanitize output

            // Generate the first letter and color for the contact
            $first_letter = strtoupper($first_name[0]); // First letter, capitalized
            $color = getRandomColor(); // Random color

            echo '
              <a href="info.php?contact_id=' . $contact_id . '&user_id=' . $user_id . '" class="contact">
                <div class="info">
                   <div class="pfp" style="background-color: ' . $color . ';">
                                        ' . $first_letter . '
                                    </div>
                  <p>' . $first_name . '</p>
                </div>
                <div class="oper">
                  <img 
                  class="icon" 
                  src="./img/edit.png" 
                  alt="edit" 
                  onclick="event.preventDefault(); window.location.href=\'update.php?contact_id=' . $contact_id . '&user_id=' . $user_id . '\';" 
                  />
                <img 
                  class="icon" 
                  src="./img/trash.png" 
                  alt="delete" 
                  onclick="event.preventDefault(); if(confirm(\'Are you sure you want to delete this contact?\')) window.location.href=\'delete.php?contact_id=' . $contact_id . '&user_id=' . $user_id . '\';" 
                  />
                </div>
              </a>';
          }
        } else {
          if (empty($search_term)) {
            echo '<p class="empty">You have no saved contacts yet. <span>🤷‍♂️</span></p>';
          } else {
            echo '<p class="empty">Contact not found. <span>🤷‍♂️</span></p>';
          }
        }

        // Close the statement and database connection
        $stmt->close();
        $conn->close();
        ?>
      </div>

    </div>
  </div>
</body>

</html>