<?php
// Include database connection
require_once 'config.php';

// Initialize variables
$name = "";
$nim = "";
$jurusan = "";
$alamat = "";
$id = 0;
$update = false;
$error = "";
$success = "";

// Insert data
if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $nim = $_POST['nim'];
    $jurusan = $_POST['jurusan'];
    $alamat = $_POST['alamat'];

    if (empty($name) || empty($nim) || empty($jurusan) || empty($alamat)) {
        $error = "Semua kolom harus diisi";
    } else {
        $stmt = $conn->prepare("INSERT INTO students (name, nim, jurusan, alamat) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $nim, $jurusan, $alamat);
        
        if ($stmt->execute()) {
            $success = "Data berhasil ditambahkan";
            // Clear the form
            $name = "";
            $nim = "";
            $jurusan = "";
            $alamat = "";
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Delete data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $success = "Data berhasil dihapus";
    } else {
        $error = "Error menghapus data: " . $stmt->error;
    }
    $stmt->close();
    
    // Redirect to avoid resubmission
    header("Location: index.php");
    exit();
}

// Edit data - get record for editing
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $stmt = $conn->prepare("SELECT name, nim, jurusan, alamat FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_array();
        $name = $row['name'];
        $nim = $row['nim'];
        $jurusan = $row['jurusan'];
        $alamat = $row['alamat'];
    }
    $stmt->close();
}

// Update data
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $nim = $_POST['nim'];
    $jurusan = $_POST['jurusan'];
    $alamat = $_POST['alamat'];
    
    if (empty($name) || empty($nim) || empty($jurusan) || empty($alamat)) {
        $error = "Semua kolom harus diisi";
    } else {
        $stmt = $conn->prepare("UPDATE students SET name = ?, nim = ?, jurusan = ?, alamat = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $nim, $jurusan, $alamat, $id);
        
        if ($stmt->execute()) {
            $success = "Data berhasil diperbarui";
            $update = false;
            // Clear the form
            $name = "";
            $nim = "";
            $jurusan = "";
            $alamat = "";
        } else {
            $error = "Error memperbarui data: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch all students
$result = $conn->query("SELECT * FROM students ORDER BY id");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-graduation-cap"></i> Sistem Manajemen Data Mahasiswa</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <!-- Input Form -->
        <form method="post" action="">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            
            <div class="form-group">
                <label><i class="fas fa-user"></i> Nama:</label>
                <input type="text" name="name" value="<?php echo $name; ?>" placeholder="Masukkan nama" required>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-id-card"></i> NIM:</label>
                <input type="text" name="nim" value="<?php echo $nim; ?>" placeholder="Masukkan NIM" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-book"></i> Jurusan:</label>
                <input type="text" name="jurusan" value="<?php echo $jurusan; ?>" placeholder="Masukkan jurusan" required>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-map-marker-alt"></i> Alamat:</label>
                <textarea name="alamat" placeholder="Masukkan alamat" required><?php echo $alamat; ?></textarea>
            </div>
            
            <div class="form-group">
                <?php if ($update): ?>
                    <button type="submit" name="update" class="btn btn-update">
                        <i class="fas fa-sync-alt"></i> Perbarui
                    </button>
                <?php else: ?>
                    <button type="submit" name="save" class="btn btn-save">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                <?php endif; ?>
            </div>
        </form>
        
        <!-- Data Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Jurusan</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['nim']); ?></td>
                        <td><?php echo htmlspecialchars($row['jurusan']); ?></td>
                        <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                        <td class="action-buttons">
                            <a href="index.php?edit=<?php echo $row['id']; ?>" class="btn btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="index.php?delete=<?php echo $row['id']; ?>" 
                               class="btn btn-delete"
                               onclick="return confirm('Anda yakin ingin menghapus data ini?')">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>