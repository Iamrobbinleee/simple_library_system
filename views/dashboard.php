<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.3/css/dataTables.dataTables.min.css" crossorigin="anonymous">
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
    <a href="/controllers/logout.php" class="btn btn-secondary btn-sm">Signout</a>
</div>
<br>
<div class="container">
    <!-- Add Book Form -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Add New Book</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <label class="form-label" for="book_name">Book name: </label>
                    <input type="text" id="bookName" placeholder="ex. Peter Pan" class="form-control" required>
                    <br>
                    <label class="form-label" for="book_desc">Book description: </label>
                    <input type="text" id="bookDesc" placeholder="ex. Fantasy and Non-fiction story."class="form-control" required>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white" style="text-align: right;">
            <button id="addBookBtn" class="btn btn-success btn-sm">Add Book</button>
        </div>
    </div>
    <br>

    <!-- List of Book -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>My Books</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" border="1" style="border-collapse: collapse; text-align: center; width: 100%;" id="bookTable">
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
        </div>
    </div>

    <!-- Borrowed Books -->
    <div style="margin: 10px;">
        <h2>Borrowed Books</h2>
        <div class="table-responsive">
            <table class="table table-striped" border="1" style="border-collapse: collapse; text-align: center; width: 100%;" id="borrowedBookTable">
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
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.datatables.net/2.3.3/js/dataTables.min.js"></script>
<script>
const userId = <?php echo json_encode($user_id); ?>;

function loadBooks() {
    fetch(`http://simple_library_system.proj/controllers/BookController.php/books/${userId}`)
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
                            <button onclick="editBook(${book.id}, '${book.name}', '${book.description}',)" class="btn btn-success btn-sm">Edit</button>
                            <button onclick="deleteBook(${book.id})" class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                new DataTable('#bookTable');
            }    
        })
        .catch(err => console.error(err));
}

// Add Book
document.getElementById('addBookBtn').addEventListener('click', () => {
    const name = document.getElementById('bookName').value.trim();
    const desc = document.getElementById('bookDesc').value.trim();

    if (!name || !desc) {
        alert('Please enter name and description.');
        return;
    }

    fetch('http://simple_library_system.proj/controllers/BookController.php/books', {
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

function editBook(bookId, bookName, bookDescription){
    // console.info(bookId + ' | ' + bookName + ' | ' + bookDescription);
    let newName = prompt("Enter new book name:", bookName);
    let newDesc = prompt("Enter new book description:", bookDescription);

    if (newName) bookName = newName;
    if (newDesc) bookDescription = newDesc;

    fetch('http://simple_library_system.proj/controllers/BookController.php/books', {
        method: 'PUT',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ user_id: userId, bookId: bookId, bookName: bookName, bookDescription: bookDescription })
    })
    .then(res => res.json())
    .then(data => {
        alert('Book updated successfully.');
        document.getElementById('bookName').value = bookName;
        document.getElementById('bookDesc').value = bookDescription;
        loadBooks();
    })
    .catch(err => console.error(err));
}

function deleteBook(bookId){
    fetch('http://simple_library_system.proj/controllers/BookController.php/books', {
        method: 'DELETE',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ bookId: bookId })
    })
    .then(res => res.json())
    .then(data => {
        alert('Book has been deleted successfully.');
        loadBooks();
    })
    .catch(err => console.error(err));
}

loadBooks();
</script>
</body>
</html>
