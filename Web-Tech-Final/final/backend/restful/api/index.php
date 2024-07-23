<?php
header('Content-Type: application/json');
require_once '../config.php';

// Helper function to get input data
function get_input_data()
{
    return json_decode(file_get_contents('php://input'), true);
}

// Connect to the database
$conn = getDbConnection();

// Parse the request URL to determine the resource and ID
$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);
$path = trim($request_uri[0], '/');
$segments = explode('/', $path);
$resource = $segments[1] ?? null;
$id = $segments[2] ?? null;

// Fetch all users
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $resource == 'users' && !$id) {
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    echo json_encode($users);
}

// Fetch a specific user
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $resource == 'users' && $id) {
    $id = intval($id);
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["message" => "User not found"]);
    }
}

// Insert a new user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $resource == 'users') {
    $input = get_input_data();
    $name = $conn->real_escape_string($input['name']);
    $email = $conn->real_escape_string($input['email']);
    $sql = "INSERT INTO users (name, email) VALUES ('$name', '$email')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "User created successfully", "id" => $conn->insert_id]);
    } else {
        echo json_encode(["message" => "Error: " . $conn->error]);
    }
}

// Update a user
if ($_SERVER['REQUEST_METHOD'] == 'PUT' && $resource == 'users' && $id) {
    $id = intval($id);
    $input = get_input_data();
    $name = $conn->real_escape_string($input['name']);
    $email = $conn->real_escape_string($input['email']);
    $sql = "UPDATE users SET name = '$name', email = '$email' WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "User updated successfully"]);
    } else {
        echo json_encode(["message" => "Error: " . $conn->error]);
    }
}

// patch a user
if ($_SERVER['REQUEST_METHOD'] == 'PATCH' && $resource == 'users' && $id) {
    $id = intval($id);
    $input = get_input_data();
    $fields = [];
    if (isset($input['name'])) {
        $name = $conn->real_escape_string($input['name']);
        $fields[] = "name = '$name'";
    }
    if (isset($input['email'])) {
        $email = $conn->real_escape_string($input['email']);
        $fields[] = "email = '$email'";
    }
    if (empty($fields)) {
        echo json_encode(["message" => "No fields to update"]);
    } else {
        $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "User updated successfully"]);
        } else {
            echo json_encode(["message" => "Error: " . $conn->error]);
        }
    }
}

// Delete a user
if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && $resource == 'users' && $id) {
    $id = intval($id);
    $sql = "DELETE FROM users WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "User deleted successfully"]);
    } else {
        echo json_encode(["message" => "Error: " . $conn->error]);
    }
}

$conn->close();

?>