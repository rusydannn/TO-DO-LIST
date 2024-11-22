<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, PUT, DELETE, GET');
header('Access-Control-Allow-Headers: Content-Type');

include_once 'koneksi.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        createTask();
        break;
    case 'PUT':
        completeTask();
        break;
    case 'DELETE':
        deleteTask();
        break;
    case 'GET':
        getTask();
        break;

    default:
        echo json_encode(['message' => 'Invalid Request']);
}

// Create a New Task
function createTask(): void
{
    $data = json_decode(file_get_contents("php://input"));
    if (!empty($data->title)) {
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO todos (title) VALUES (?)");
        $stmt->bind_param('s', $data->title);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Task Created']);
        } else {
            echo json_encode(['message' => 'Task Not Created']);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['message' => 'Incomplete Data']);
    }
}

// Mark a Task as Completed
function completeTask(): void
{
    $data = json_decode(file_get_contents("php://input"));
    if (!empty($data->id)) {
        $conn = getConnection();
        $stmt = $conn->prepare("UPDATE todos SET completed = 1 WHERE id = ?");
        $stmt->bind_param('i', $data->id);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Task Complete']);
        } else {
            echo json_encode(['message' => 'Task Not Complete']);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['message' => 'Invalid ID']);
    }
}

// Delete a Task
function deleteTask(): void
{
    $data = json_decode(file_get_contents("php://input"));
    if (!empty($data->id)) {
        $conn = getConnection();
        $stmt = $conn->prepare("DELETE FROM todos WHERE id = ?");
        $stmt->bind_param('i', $data->id);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Task Deleted']);
        } else {
            echo json_encode(['message' => 'Task Not Deleted']);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['message' => 'Invalid ID']);
    }
}

// Check if task ID is provided in the query string
function getTask(): void
{
    $conn = getConnection();

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM todos WHERE id =?");
        $stmt->bind_param('i', $id);
    } else {
        $stmt = $conn->prepare("SELECT * FROM todos");
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $tasks = [];
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
        echo json_encode($tasks);
    } else {
        echo json_encode(['message' => 'No Tasks Found']);
    }

    $stmt->close();
    $conn->close();
}
?>