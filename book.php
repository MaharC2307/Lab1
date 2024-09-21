<?php
class Book {
    private $title;
    private $author;
    private $year;

    public function __construct($title, $author, $year) {
        if (empty($title) || empty($author) || empty($year)) {
            throw new Exception('All fields must be filled');
        }
        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getYear() {
        return $this->year;
    }

    public static function displayBooks($books) {
        if (empty($books)) {
            echo "<p>No books have been added yet.</p>";
        } else {
            echo "<table border='1'>
                    <tr><th>Title</th><th>Author</th><th>Year</th></tr>";
            foreach ($books as $book) {
                echo "<tr>
                        <td>{$book->getTitle()}</td>
                        <td>{$book->getAuthor()}</td>
                        <td>{$book->getYear()}</td>
                      </tr>";
            }
            echo "</table>";
        }
    }
}
?>
