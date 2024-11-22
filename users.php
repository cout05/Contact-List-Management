<?php
include('connect.php');

// Add User - Check if form is submitted
if (isset($_POST['add_user'])) {
  $new_username = $_POST['username'];

  // Prepare SQL statement to insert a new user
  $sql_add = "INSERT INTO users (username) VALUES ('$new_username')";
  if ($conn->query($sql_add) === TRUE) {
  } else {
    echo "Error: " . $sql_add . "<br>" . $conn->error;
  }
}

// Edit User - Check if form is submitted with a user ID
if (isset($_POST['edit_user'])) {
  $user_id_to_edit = $_POST['user_id'];
  $updated_username = $_POST['username'];

  // Prepare SQL statement to update user
  $sql_edit = "UPDATE users SET username = '$updated_username' WHERE user_id = $user_id_to_edit";
  if ($conn->query($sql_edit) === TRUE) {
  } else {
    echo "Error: " . $sql_edit . "<br>" . $conn->error;
  }
}

// Delete User - Check if delete request is sent
if (isset($_GET['delete_id'])) {
  $user_id_to_delete = $_GET['delete_id'];

  // Prepare SQL statement to delete a user
  $sql_delete = "DELETE FROM users WHERE user_id = $user_id_to_delete";
  if ($conn->query($sql_delete) === TRUE) {
  } else {
    echo "Error: " . $sql_delete . "<br>" . $conn->error;
  }
}

// Prepare and execute the SQL to fetch users
$sql = "SELECT user_id, username FROM users";
$result = $conn->query($sql);

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style.css" />
  <title>Contacts | Users</title>
</head>

<style>
  h1 {
    color: #333;
  }

  .logo {
    display: flex;
    gap: 0.3rem;
  }

  label {
    color: #fff;
    font-weight: 600;
  }

  .add_user {
    display: none;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    max-width: 450px;
    background-color: rgb(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    z-index: 1000;
  }

  .add_user input {
    width: 100%;
    padding: 10px;
    margin: 10px 0 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
  }

  .blur-bg {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
    z-index: 2;
    transition: filter 0.3s ease;
  }

  .users {
    display: flex;
    flex-direction: column;
    height: 400px;
    overflow-y: auto;
    margin-top: 20px;
  }

  .user {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 15px;
    border-radius: 8px;
    background-color: rgba(255, 255, 255, 0.5);
    font-size: 1rem;
    color: #333;
    transition: background-color 0.2s;
    text-decoration: none;
    margin-bottom: 10px;

  }

  .user:hover {
    background-color: rgba(255, 255, 255, 0.8);
  }

  .user img.icon {
    cursor: pointer;
  }

  .user>a {
    text-decoration: none;
    color: #333;
  }

  .pfp {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
  }

  .uname {
    font-weight: 600;
  }

  .divider {
    color: #121212;
    text-align: center;
    padding: 20px 0px;
  }

  .actions {
    display: flex;
    gap: 1rem;
    align-items: center;
  }

  .empty {
    color: #333;
    font-weight: 600;
    font-size: 1rem;
    text-align: center;
    margin: auto;
  }

  .empty span {
    display: block;
    font-size: 2rem;
  }
</style>

<body>
  <div onclick="toggleModal()" class="blur-bg" id="blurBg"></div>

  <div id="userModal" class="add_user">
    <form method="POST">
      <label id="modalLabel" for="username">Add New User</label>
      <input
        type="text"
        id="usernameInput"
        name="username"
        placeholder="Enter username"
        required />
      <input type="hidden" id="userIdInput" name="user_id" />
      <button type="submit" id="modalSubmit" name="add_user">Add User</button>
    </form>
  </div>

  <div class="container">
    <header>
      <div class="logo">
        <h1>Users</h1>
      </div>

      <img
        onclick="addUser()"
        src="./img/more.png"
        class="add-btn"
        alt="add" />
    </header>

    <div class="users">
      <?php
      // Check if there are any results and iterate through each row
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $user_id = $row["user_id"]; // Fetch the user ID
          $uname = $row["username"];

          echo '   
      <a href="home.php?user_id=' . $user_id . '" class="user">
        <div class="pfp">
          <img class="icon" src="./img/user.png" alt="user" />
          <p class="uname">' . $uname . '</p>
        </div>
        <div class="actions">
          <img 
            class="icon" 
            src="./img/edit.png" 
            alt="edit" 
            onclick="event.preventDefault(); editUser(' . $user_id . ', \'' . $uname . '\')" 
          />
          <img 
            class="icon" 
            src="./img/trash.png" 
            alt="delete" 
            onclick="event.preventDefault(); if(confirm(\'Are you sure you want to delete this User?\')) window.location.href=\'?delete_id=' . $user_id . '\';" 
          />
        </div>
      </a>';
        }
      } else {
        echo '<div class="empty">No users found. <span>🤷‍♂️</span></div>';
      }
      ?>
    </div>
  </div>

  <script>
    let isModalOpen = false;
    const modal = document.getElementById("userModal");
    const blurBg = document.getElementById("blurBg");
    const usernameInput = document.getElementById("usernameInput");
    const userIdInput = document.getElementById("userIdInput");
    const modalLabel = document.getElementById("modalLabel");
    const modalSubmit = document.getElementById("modalSubmit");

    const toggleModal = () => {
      if (isModalOpen) {
        modal.style.display = "none";
        blurBg.style.display = "none";
      } else {
        modal.style.display = "block";
        blurBg.style.display = "block";
      }
      isModalOpen = !isModalOpen;
    };

    const addUser = () => {
      usernameInput.value = "";
      userIdInput.value = "";
      modalLabel.innerText = "Add New User";
      modalSubmit.name = "add_user";
      modalSubmit.innerText = "Add User";
      toggleModal();
    };

    const editUser = (userId, username) => {
      usernameInput.value = username;
      userIdInput.value = userId;
      modalLabel.innerText = "Enter new Username";
      modalSubmit.name = "edit_user";
      modalSubmit.innerText = "Update User";
      toggleModal();
    };
  </script>

</body>

</html>