<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept,
Origin, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
require 'vendor/autoload.php';
require_once './config.php';
// Instantiate db class
$db = new db();
// Create Slim app
$app = new \Slim\App;
// Routes for users
$app->get('/users', function ($request, $response, $args) use ($db) {
    try {
        $conn = $db->connect();
        $sql = "SELECT * FROM users";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $response->withJson($result);
    } catch (PDOException $e) {
        return $response->withJson(["error" => "Error: " . $e->getMessage()]);
    }
});
$app->get('/users/{id}', function ($request, $response, $args) use ($db) {
    try {
        $id = $args['id'];
        $conn = $db->connect();
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            return $response->withJson($user);
        } else {
            return $response->withJson(["error" => "User not found"]);
        }
    } catch (PDOException $e) {
        return $response->withJson(["error" => "Database error: " . $e->getMessage()]);
    }
});
$app->post('/users', function ($request, $response, $args) use ($db) {
    try {
        $conn = $db->connect();
        $data = $request->getParsedBody();
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
        return $response->withJson(["message" => "User created successfully"]);
    } catch (Exception $e) {
        return $response->withJson(["error" => "Error creating user: " . $e->getMessage()]);
    }
});
$app->put('/users/{id}', function ($request, $response, $args) use ($db) {
    try {
        $id = $args['id'];
        $conn = $db->connect();
        $data = $request->getParsedBody();
        if (!isset($data['name']) || !isset($data['email'])) {
            throw new Exception("Name and email are required.");
        }
        $name = $data['name'];
        $email = $data['email'];
        $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $response->withJson(["message" => "User updated successfully"]);
    } catch (Exception $e) {
        return $response->withJson(["error" => "Error updating user: " . $e->getMessage()]);
    }
});
$app->delete('/users/{id}', function ($request, $response, $args) use ($db) {
    try {
        $id = $args['id'];
        $conn = $db->connect();
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $response->withJson(["message" => "User deleted successfully"]);
    } catch (PDOException $e) {
        return $response->withJson(["error" => "Error deleting user: " . $e->getMessage()]);
    }
});
// Routes for products
// lengkapkan routes for products here
$app->get('/products', function ($request, $response, $args) {
    return $response->write("get all products executes!");
});
$app->get('/products/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    return $response->write("Get product with ID: $id");
});
// Routes for orders
// lengkapkan routes untuk orders here
$app->get('/orders', function ($request, $response, $args) {
    return $response->write("get all orders executes!");
});
//dummy routes...cuba disini
$app->get('/xyz', function ($request, $response, $args) {
    $userObject = [
        'id' => 101,
        'name' => 'Johanna Alis',
        'email' => 'johalis@utm.my'
    ];
    return $response->withJson($userObject);
});
$app->run();
?>