<?php

$host = 'localhost';  
$username = 'root';   
$password = '';       
$dbname = 'library_db'; 

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize errors array
$errors = [];

// File paths
$tokenFile = 'token.json';
$saveDataFile = 'save_data.json';
$usedTokenFile = 'used_tokens.json';

// Ensure token.json exists
if (!file_exists($tokenFile)) {
    file_put_contents($tokenFile, json_encode(['tokens' => []], JSON_PRETTY_PRINT));
}

// Ensure save_data.json exists
if (!file_exists($saveDataFile)) {
    file_put_contents($saveDataFile, json_encode([], JSON_PRETTY_PRINT));
}

// Ensure used_tokens.json exists
if (!file_exists($usedTokenFile)) {
    file_put_contents($usedTokenFile, json_encode(['tokens' => []], JSON_PRETTY_PRINT));
}

// Validation for Name
if (empty($_POST["fname"])) {
    $errors[] = "Name is required";
} else {
    $name = htmlspecialchars(trim($_POST["fname"]));
    if (!preg_match("/^([A-Z][a-z]*\s)*[A-Z][a-z]*$/", $name)) {
        $errors[] = "Each name should start with a letter & the first letter should be capital.";
    }
}

// Validation for ID
if (empty($_POST["id"])) {
    $errors[] = "ID is required";
} else {
    $id = htmlspecialchars(trim($_POST["id"]));
    if (!preg_match("/^\d{2}-\d{5}-\d{1}$/", $id)) {
        $errors[] = "ID format should be xx-xxxxx-x";
    }
}

// Validation for Email
if (empty($_POST["email"])) {
    $errors[] = "Email is required";
} else {
    $mail = htmlspecialchars(trim($_POST["email"]));
    if (!preg_match("/^[\w\.-]+@student\.aiub\.edu$/", $mail)) {
        $errors[] = "Incorrect student email format";
    }
}

// Validation for Book Title
if (empty($_POST["btitle"])) {
    $errors[] = "Please choose a book";
} else {
    $book = htmlspecialchars(trim($_POST["btitle"]));  // Ensure the book title is assigned
}

// Validation for Dates
if (empty($_POST["bdate"]) || empty($_POST["rdate"])) {
    $errors[] = "Both borrow and return dates are required";
} else {
    $borrowDate = $_POST["bdate"];
    $returnDate = $_POST["rdate"];

    $borrowDateObj = DateTime::createFromFormat('Y-m-d', $borrowDate);
    $returnDateObj = DateTime::createFromFormat('Y-m-d', $returnDate);

    if ($borrowDateObj && $returnDateObj) {
        $dateDiff = $borrowDateObj->diff($returnDateObj)->days;
        if ($dateDiff > 10) {
            $errors[] = "You have missed the submission deadline, can't be borrowed for more than 10 days";
        }
    } else {
        $errors[] = "Invalid date format";
    }
}

// Validation for Token
if (empty($_POST["token"])) {
    $errors[] = "Token is required";
} else {
    $token = htmlspecialchars(trim($_POST["token"]));
    if (!preg_match("/^[0-9]{4}$/", $token)) {
        $errors[] = "Token must match Available Tokens";
    } else {
        // Fetch available tokens from token.json
        $tokenData = file_get_contents($tokenFile);
        $tokens = json_decode($tokenData, true)['tokens'];

        if (!in_array($token, $tokens)) {
            $errors[] = "Invalid token. Please check the available tokens.";
        } else {
            // Remove token from token.json
            $updatedTokens = array_diff($tokens, [$token]);
            file_put_contents($tokenFile, json_encode(['tokens' => array_values($updatedTokens)], JSON_PRETTY_PRINT));

            // Save the used token to used_tokens.json
            $usedTokenData = json_decode(file_get_contents($usedTokenFile), true);
            $usedTokenData['tokens'][] = $token;
            file_put_contents($usedTokenFile, json_encode($usedTokenData, JSON_PRETTY_PRINT));
        }
    }
}

// Validation for Fees
if (empty($_POST["fees"])) {
    $errors[] = "Fees are required";
} else {
    $fees = htmlspecialchars(trim($_POST["fees"]));
    if (!is_numeric($fees) || $fees < 0) {
        $errors[] = "Fees must be a positive number";
    }
}

// Error Handling or Save Data
if (!empty($errors)) {
    echo "<div class='error-messages'><ul>";
    foreach ($errors as $error) {
        echo "<li style='color: red;'>$error</li>";
    }
    echo "</ul></div>";
} else {
    // Prepare the data to be saved
    $userData = [
        'fname' => $name,
        'id' => $id,
        'email' => $mail,
        'btitle' => $book,
        'bdate' => $borrowDate,
        'rdate' => $returnDate,
        'token' => $token,
        'fees' => $fees
    ];

    // Read the existing data from save_data.json
    $existingData = json_decode(file_get_contents($saveDataFile), true);

    // Append the new data
    $existingData[] = $userData;

    // Save the updated data back to save_data.json
    file_put_contents($saveDataFile, json_encode($existingData, JSON_PRETTY_PRINT));

    // Insert the validated data into the "borrow_book" table
    $stmt = $conn->prepare("INSERT INTO borrow_book (fname, student_id, email, btitle, bdate, rdate, token, fees) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $name, $id, $mail, $book, $borrowDate, $returnDate, $token, $fees);

    if ($stmt->execute()) {
        // Display a success message
        echo "<h2 style='color: green;'>Borrowing Process Completed</h2>";
        echo "<p>Book has been successfully borrowed.</p>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement and the connection
    $stmt->close();
    $conn->close();

    // Receipt Display
    echo "<h2 style='color: green;'>Receipt</h2>";
    echo "<div style='border: 1px solid #ccc; padding: 15px; border-radius: 10px; max-width: 400px;'>";
    echo "<p><strong>Name:</strong> $name</p>";
    echo "<p><strong>ID:</strong> $id</p>";
    echo "<p><strong>Email:</strong> $mail</p>";
    echo "<p><strong>Borrow Date:</strong> $borrowDate</p>";
    echo "<p><strong>Return Date:</strong> $returnDate</p>";
    echo "<p><strong>Token:</strong> $token</p>";
    echo "<p><strong>Fees:</strong> $fees</p>";
    echo "<p style='color: blue;'><strong>Thank you</strong></p>";
    echo "</div>";
}
?>