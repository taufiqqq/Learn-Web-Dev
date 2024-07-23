<?
require_once './config.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
switch ($action) {
    case 'getUsers':
        getUsers();
        break;
    case 'getUserById':
        getUserById();
        break;
    case 'createUser':
        createUser();
        break;
    case 'updateUser':
        updateUser();
        break;
    case 'deleteUser':
        deleteUser();
        break;
    default:
        echo json_encode(["error" => "Invalid action"]);
        break;
}

function getUsers()
{
    try {
        $db = new db();
        $conn = $db->connect();
        $sql = "SELECT * FROM users";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conn = null; // Close the connection
        echo json_encode($result);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
function getUserById()
{
    try {
        $db = new db();
        $conn = $db->connect();
        $id = $_GET['id'];
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            echo json_encode($user);
        } else {
            echo json_encode(["error" => "User not found"]);
        }
        $conn = null; // Close the connection
    } catch (PDOException $e) {
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
}
function createUser()
{
    try {
        $db = new db();
        $conn = $db->connect();
        // Get JSON data from the request body
        $json_data = file_get_contents('php://input');
        // Decode JSON data into associative array
        $data = json_decode($json_data, true);
        // Check if required fields are present
        if (!isset($data['name']) || !isset($data['email'])) {
            throw new Exception("Name and email are required.");
        }
        $name = $data['name'];
        $email = $data['email'];
        $sql = "INSERT INTO users (name, email) VALUES (:name, :email)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        echo json_encode(["message" => "User created successfully"]);
        $conn = null; // Close the connection
    } catch (Exception $e) {
        echo json_encode(["error" => "Error creating user: " . $e->getMessage()]);
    }
}
function updateUser()
{
    try {
        $db = new db();
        $conn = $db->connect();
        // Get JSON data from the request body
        $json_data = file_get_contents('php://input');
        // Decode JSON data into associative array
        $data = json_decode($json_data, true);
        // Check if required fields are present
        if (!isset($data['id']) || !isset($data['name']) || !isset($data['email'])) {
            throw new Exception("ID, name, and email are required.");
        }
        $id = $data['id'];
        $name = $data['name'];
        $email = $data['email'];
        $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        echo json_encode(["message" => "User updated successfully"]);
        $conn = null; // Close the connection
    } catch (Exception $e) {
        echo json_encode(["error" => "Error updating user: " . $e->getMessage()]);
    }
}

function patchUser()
{
    try {
        $db = new db();
        $conn = $db->connect();
        // Get JSON data from the request body
        $json_data = file_get_contents('php://input');
        // Decode JSON data into associative array
        $data = json_decode($json_data, true);
        // Check if required fields are present
        if (!isset($data['id'])) {
            throw new Exception("ID is required.");
        }
        $id = $data['id'];

        // Build the SQL dynamically based on provided fields
        $fields = [];
        if (isset($data['name'])) {
            $fields[] = "name = :name";
        }
        if (isset($data['email'])) {
            $fields[] = "email = :email";
        }
        if (empty($fields)) {
            throw new Exception("No fields to update.");
        }
        $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        if (isset($data['name'])) {
            $stmt->bindValue(':name', $data['name']);
        }
        if (isset($data['email'])) {
            $stmt->bindValue(':email', $data['email']);
        }
        $stmt->execute();
        echo json_encode(["message" => "User patched successfully"]);
        $conn = null; // Close the connection
    } catch (Exception $e) {
        echo json_encode(["error" => "Error patching user: " . $e->getMessage()]);
    }
}

function deleteUser()
{
    try {
        $db = new db();
        $conn = $db->connect();
        $id = $_GET['id'];
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        echo json_encode(["message" => "User deleted successfully"]);
        $conn = null; // Close the connection
    } catch (PDOException $e) {
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
}


?>