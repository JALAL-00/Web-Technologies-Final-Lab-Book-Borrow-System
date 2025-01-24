<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all books
$sql = "SELECT * FROM add_books";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
        }
        .box3 {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn.success {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn.success:hover {
            background-color: #45a049;
        }
        .box4 {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            gap: 20px;
        }
        .minibox {
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }
        .minibox h3 {
            margin: 0 0 10px;
        }
        .minibox p {
            margin: 5px 0;
        }
        .no-data {
            text-align: center;
            color: red;
        }
    </style>
</head>
<body>
    <!-- Show Available Books -->
    <div class="box3">
        <h1 class="acenter-title">All Books</h1><br>
    </div>

    <div class="box4">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="minibox">
                    <h3><?php echo htmlspecialchars($row['book_name']); ?></h3>
                    <p><strong>Author:</strong> <?php echo htmlspecialchars($row['author_name']); ?></p>
                    <p><strong>ISBN:</strong> <?php echo htmlspecialchars($row['isbn']); ?></p>
                    <p><strong>Price:</strong> $<?php echo htmlspecialchars(number_format($row['price'], 2)); ?></p>
                    <p><strong>Quantity:</strong> <?php echo htmlspecialchars($row['quantity']); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-data">No books available in the database.</p>
        <?php endif; ?>
    </div>

    <?php $conn->close(); ?>
</body>
</html>