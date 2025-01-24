<?php

$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "library_db";     


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $book_name = trim($_POST['bname']);
    $author_name = trim($_POST['aname']);
    $isbn = trim($_POST['isbn']);
    $price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);
    
    
    $error_message = "";

    
    if (empty($book_name)) {
        $error_message .= "Book name is required.<br>";
    }

    if (empty($author_name)) {
        $error_message .= "Author name is required.<br>";
    }

    if (empty($isbn) || !preg_match("/^\d{5}$/", $isbn)) {
        $error_message .= "ISBN must be 5 digits.<br>";
    }

    if (empty($price) || !preg_match("/^\d+(\.\d{1,2})?$/", $price)) {
        $error_message .= "Price must be a valid number with up to two decimal places.<br>";
    }

    if (empty($quantity) || !is_numeric($quantity) || $quantity <= 0) {
        $error_message .= "Quantity must be a positive integer.<br>";
    }

    
    if (!empty($error_message)) {
        echo "<div style='color: red;'>$error_message</div>";
    } else {
        
        $sql = "INSERT INTO add_books (book_name, author_name, isbn, price, quantity) 
                VALUES ('$book_name', '$author_name', '$isbn', '$price', '$quantity')";

        if ($conn->query($sql) === TRUE) {
            echo "New book added successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>