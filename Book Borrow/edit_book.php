<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $book_id = intval($_POST['book_id']);
    $book_name = trim($_POST['book_name']);
    $author_name = trim($_POST['author_name']);
    $isbn = trim($_POST['isbn']);
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);

    // Update query
    $sql = "UPDATE add_books SET book_name = ?, author_name = ?, isbn = ?, price = ?, quantity = ? WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdis", $book_name, $author_name, $isbn, $price, $quantity, $book_id);

    if ($stmt->execute()) {
        $success_message = "Book updated successfully!";
    } else {
        die("Error updating book: " . $conn->error);
    }

    $stmt->close();
}

if (isset($_GET['id'])) {
    $book_id = intval($_GET['id']); 
    $sql = "SELECT * FROM add_books WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
    } else {
        die("Book not found.");
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
        }
        .form-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], input[type="number"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .success-message {
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit Book</h1>
        
        <?php if (!empty($success_message)): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <form action="edit_book.php" method="POST">
            <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($row['book_id']); ?>">

            <label for="book_name">Book Name:</label>
            <input type="text" id="book_name" name="book_name" value="<?php echo htmlspecialchars($row['book_name']); ?>" required>

            <label for="author_name">Author Name:</label>
            <input type="text" id="author_name" name="author_name" value="<?php echo htmlspecialchars($row['author_name']); ?>" required>

            <label for="isbn">ISBN:</label>
            <input type="text" id="isbn" name="isbn" value="<?php echo htmlspecialchars($row['isbn']); ?>" required>

            <label for="price">Price:</label>
            <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($row['price']); ?>" required>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($row['quantity']); ?>" required>

            <input type="submit" value="Update Book">
        </form>
    </div>
</body>
</html>