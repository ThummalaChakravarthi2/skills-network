<?php
// File: index.php

// File where users are saved
$dataFile = 'users.json';

// Read users from file
function readUsers() {
    global $dataFile;
    if (!file_exists($dataFile)) {
        file_put_contents($dataFile, json_encode([]));
    }
    $json = file_get_contents($dataFile);
    return json_decode($json, true);
}

// Write users to file
function writeUsers($users) {
    global $dataFile;
    file_put_contents($dataFile, json_encode($users, JSON_PRETTY_PRINT));
}

$users = readUsers();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'add') {
        // Add user
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        if ($name && $email) {
            $id = time(); // simple unique id
            $users[$id] = ['name' => $name, 'email' => $email];
            writeUsers($users);
            header("Location: index.php");
            exit;
        }
    } elseif ($action == 'edit') {
        // Edit user
        $id = $_POST['id'];
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        if (isset($users[$id]) && $name && $email) {
            $users[$id]['name'] = $name;
            $users[$id]['email'] = $email;
            writeUsers($users);
            header("Location: index.php");
            exit;
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if (isset($users[$id])) {
        unset($users[$id]);
        writeUsers($users);
        header("Location: index.php");
        exit;
    }
}

// Get user to edit
$editUser = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    if (isset($users[$id])) {
        $editUser = ['id' => $id] + $users[$id];
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP User Manager</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        table { border-collapse: collapse; width: 60%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #eee; }
        input[type=text], input[type=email] { width: 90%; padding: 6px; }
        input[type=submit] { padding: 8px 15px; margin-top: 10px; }
        a.button { padding: 6px 10px; background: #007BFF; color: white; text-decoration: none; border-radius: 4px; }
        a.button:hover { background: #0056b3; }
    </style>
</head>
<body>

<h2>PHP User Manager</h2>

<?php if ($editUser): ?>
    <h3>Edit User</h3>
    <form method="post" action="index.php">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" value="<?= htmlspecialchars($editUser['id']) ?>">
        <label>Name:<br>
            <input type="text" name="name" required value="<?= htmlspecialchars($editUser['name']) ?>">
        </label><br><br>
        <label>Email:<br>
            <input type="email" name="email" required value="<?= htmlspecialchars($editUser['email']) ?>">
        </label><br><br>
        <input type="submit" value="Update User">
        <a href="index.php" class="button">Cancel</a>
    </form>

<?php else: ?>
    <h3>Add New User</h3>
    <form method="post" action="index.php">
        <input type="hidden" name="action" value="add">
        <label>Name:<br>
            <input type="text" name="name" required>
        </label><br><br>
        <label>Email:<br>
            <input type="email" name="email" required>
        </label><br><br>
        <input type="submit" value="Add User">
    </form>
<?php endif; ?>

<h3>All Users</h3>

<?php if (empty($users)): ?>
    <p>No users found.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $id => $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                    <a href="index.php?edit=<?= $id ?>" class="button">Edit</a>
                    <a href="index.php?delete=<?= $id ?>" class="button" onclick="return confirm('Delete this user?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>
