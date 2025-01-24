<?php
$servername = "localhost"; 
$username = "root";         
$password = "";             
$dbname = "library_db";     

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $book_id = intval($_GET['id']);
    $sql = "DELETE FROM add_books WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);

    if ($stmt->execute()) {
        echo "Book deleted successfully!";
    } else {
        echo "Error deleting book: " . $conn->error;
    }
} else {
    echo "No book ID provided.";
}

$conn->close();
?>