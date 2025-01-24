<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Book</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .search-form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .results-container {
            margin-top: 20px;
        }
        .results-table {
            width: 100%;
            margin-top: 20px;
        }
        .edit-btn {
            color: #007bff;
            text-decoration: none;
        }
        .delete-btn {
            color: #dc3545;
            text-decoration: none;
        }
        .delete-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="results-container container">
        <?php
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "library_db";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("<div class='alert alert-danger'>Connection failed: " . $conn->connect_error . "</div>");
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $book_name = trim($_POST['bname']);

            if (!empty($book_name)) {
                $sql = "SELECT * FROM add_books WHERE book_name LIKE ?";
                $stmt = $conn->prepare($sql);
                $search_term = "%" . $book_name . "%";
                $stmt->bind_param("s", $search_term);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo "<h3>Search Results:</h3>";
                    echo "<table class='table table-bordered table-striped results-table'>
                            <thead>
                                <tr>
                                    <th>Book Name</th>
                                    <th>Author Name</th>
                                    <th>ISBN</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['book_name']) . "</td>
                                <td>" . htmlspecialchars($row['author_name']) . "</td>
                                <td>" . htmlspecialchars($row['isbn']) . "</td>
                                <td>" . htmlspecialchars($row['price']) . "</td>
                                <td>" . htmlspecialchars($row['quantity']) . "</td>
                                <td>
                                    <a href='edit_book.php?id=" . $row['book_id'] . "' class='edit-btn'>Edit</a>
                                    <a href='delete_book.php?id=" . $row['book_id'] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this book?\");'>Delete</a>
                                </td>
                              </tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<div class='alert alert-warning'>No books found matching your search.</div>";
                }

                $stmt->close();
            } else {
                echo "<div class='alert alert-danger'>Please enter a book name to search.</div>";
            }
        }

        $conn->close();
        ?>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>