<?php
$conn = new mysqli("localhost", "root", "", "student_db");
$conn->query("CREATE DATABASE IF NOT EXISTS student_db");
$conn->select_db("student_db");
$conn->query("CREATE TABLE IF NOT EXISTS students (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100), email VARCHAR(100), phone VARCHAR(20), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");

$message = "";

if (isset($_POST['add'])) {
    $stmt = $conn->prepare("INSERT INTO students (name, email, phone) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_POST['name'], $_POST['email'], $_POST['phone']);
    $stmt->execute();
    $message = "Student added successfully!";
    $stmt->close();
}

if (isset($_POST['delete'])) {
    $conn->query("DELETE FROM students WHERE id=" . $_POST['id']);
    $message = "Student deleted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records - Edit & Delete</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); min-height: 100vh; padding: 20px; }
        .main-card { background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); max-width: 900px; margin: 0 auto; }
        .card-header-custom { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 15px 15px 0 0; }
        .btn-edit { background: #ffc107; color: black; }
        .btn-delete { background: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="main-card">
        <div class="card-header-custom">
            <h3 class="mb-1">👨‍🎓 Student Records</h3>
            <p class="mb-0">Edit and Delete Operations</p>
        </div>
        
        <div class="card-body">
            <?php if ($message): ?><div class="alert alert-success"><?php echo $message; ?></div><?php endif; ?>
            
            <form method="POST" class="row mb-4">
                <div class="col-md-3"><input type="text" name="name" class="form-control" placeholder="Name" required></div>
                <div class="col-md-4"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
                <div class="col-md-3"><input type="text" name="phone" class="form-control" placeholder="Phone"></div>
                <div class="col-md-2"><button type="submit" name="add" class="btn btn-primary w-100">Add</button></div>
            </form>
            
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM students");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td>{$row['name']}</td>";
                        echo "<td>{$row['email']}</td>";
                        echo "<td>{$row['phone']}</td>";
                        echo "<td>";
                        echo "<button class='btn btn-sm btn-edit me-1' onclick=\"editStudent({$row['id']}, '{$row['name']}', '{$row['email']}', '{$row['phone']}')\">Edit</button>";
                        echo "<form method='POST' style='display:inline'>";
                        echo "<input type='hidden' name='id' value='{$row['id']}'>";
                        echo "<button type='submit' name='delete' class='btn btn-sm btn-delete' onclick=\"return confirm('Delete?')\">Delete</button>";
                        echo "</form>";
                        echo "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editStudent(id, name, email, phone) {
            const newName = prompt('Edit Name:', name);
            if (newName) {
                // In a real app, this would make an AJAX call to update
                alert('Edit functionality would update record here');
            }
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>