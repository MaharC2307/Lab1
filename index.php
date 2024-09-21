<?php
require_once 'Book.php'; // Load the Book class first
session_start(); // Start session after class is available

// Initialize session if not set
if (!isset($_SESSION['books'])) {
    $_SESSION['books'] = [];
}

$books = $_SESSION['books'];
$errorMessage = '';

// Check if the request is an AJAX POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'clear') {
        // Clear all books from session
        $_SESSION['books'] = [];
        echo 'All books have been cleared!';
        exit;
    }

    try {
        $title = $_POST['title'] ?? '';
        $author = $_POST['author'] ?? '';
        $year = $_POST['year'] ?? '';

        // Create a new book object
        $book = new Book($title, $author, $year);

        // Store the book in the session
        $_SESSION['books'][] = $book;

        // If it's an AJAX request, return the updated book list as response
        if (isset($_POST['ajax'])) {
            Book::displayBooks($_SESSION['books']);
            exit; // Stop further execution to avoid loading the full page
        }
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();

        // If it's an AJAX request, return the error message
        if (isset($_POST['ajax'])) {
            echo '<p style="color: red;">' . $errorMessage . '</p>';
            exit; // Stop further execution
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management System</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        h1 {
            margin-top: 20px;
            color: #4a90e2;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            width: 100%;
        }

        input[type="submit"] {
            background-color: #4a90e2;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #357ab7;
        }

        .error-message {
            color: red;
            margin-bottom: 15px;
        }

        .book-list {
            margin-top: 20px;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4a90e2;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .clear-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 15px;
            width: 100%;
        }

        .clear-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add a New Book</h1>

        <?php if (!empty($errorMessage)): ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <form id="bookForm">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title">

            <label for="author">Author:</label>
            <input type="text" id="author" name="author">

            <label for="year">Publication Year:</label>
            <input type="number" id="year" name="year">

            <input type="submit" value="Add Book">
        </form>

        <button class="clear-btn" id="clearBooks">Clear All Books</button>

        <h2>Book List</h2>
        <div class="book-list" id="bookList">
            <?php Book::displayBooks($books); ?>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#bookForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the form from submitting normally

                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    type: 'POST',
                    url: '', // Same file
                    data: formData + '&ajax=1', // Add an extra field to identify AJAX request
                    success: function(response) {
                        $('#bookList').html(response); // Update the book list dynamically
                    },
                    error: function() {
                        alert('An error occurred while adding the book.');
                    }
                });
            });

            $('#clearBooks').on('click', function() {
                $.ajax({
                    type: 'POST',
                    url: '', // Same file
                    data: { action: 'clear' }, // Send action to clear data
                    success: function(response) {
                        $('#bookList').html('<p>No books have been added yet.</p>'); // Clear the list visually
                    },
                    error: function() {
                        alert('An error occurred while clearing the books.');
                    }
                });
            });
        });
    </script>
</body>
</html>
