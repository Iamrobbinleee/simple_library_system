<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Dashboard Page</title>
</head>
<body>
<?php 
    session_start();
    if (!isset($_SESSION["user_id"])) {
        header("Location: /index.php");
        exit();
    } else {
        $user_id = $_SESSION['user_id'];
    }
?>
<div style="text-align: center; margin-top: 50px;">
    <h2>Welcome to the Dashboard Page, <?php echo $_SESSION['username']; ?></h2>
    <br>
    <a href="/controllers/logout.php">Signout</a>
</div>
<br>

<!-- Add Book Form -->
<div style="text-align: center;">
    <h3>Add New Book</h3>
    <input type="text" id="bookName" placeholder="Book Name">
    <input type="text" id="bookDesc" placeholder="Description">
    <button id="addBookBtn">Add Book</button>
</div>
<br>

<!-- List of Book -->
<div style="margin: 10px;">
    <h2>My Books</h2>
    <table border="1" style="border-collapse: collapse; text-align: center; width: 100%;" id="bookTable">
        <thead>
            <tr>
                <th>No.</th>
                <th>Book Name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Borrowed Books -->
<div style="margin: 10px;">
    <h2>Borrowed Books</h2>
    <table border="1" style="border-collapse: collapse; text-align: center; width: 100%;" id="bookTable">
        <thead>
            <tr>
                <th>No.</th>
                <th>Book Name</th>
                <th>Description</th>
                <th>Borrowed From</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
const userId = <?php echo json_encode($user_id); ?>;

function loadBooks() {
    fetch(`http://library_system_first_philec.proj/controllers/BookController.php/books/${userId}`)
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector('#bookTable tbody');
            tbody.innerHTML = '';

            if (data.length === 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td colspan="4">No data available.</td>
                `;
                tbody.appendChild(tr);
            } else {
                data.forEach((book, index) => {
                    const tr = document.createElement('tr');

                    tr.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${book.name}</td>
                        <td>${book.description}</td>
                        <td>
                            <button onclick="editBook(${book.id}, '${book.name}', '${book.description}')">Edit</button>
                            <button onclick="deleteBook(${book.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }    
        })
        .catch(err => console.error(err));
}

// Add Book
document.getElementById('addBookBtn').addEventListener('click', () => {
    const name = document.getElementById('bookName').value.trim();
    const desc = document.getElementById('bookDesc').value.trim();

    if (!name || !desc) {
        alert('Please enter both name and description.');
        return;
    }

    fetch('http://library_system_first_philec.proj/controllers/BookController.php/books', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ user_id: userId, name: name, description: desc })
    })
    .then(res => res.json())
    .then(data => {
        alert('Book added successfully.');
        document.getElementById('bookName').value = '';
        document.getElementById('bookDesc').value = '';
        loadBooks();
    })
    .catch(err => console.error(err));
});

loadBooks();
</script>
</body>
</html>
