<?php
include 'database.php';  // Koneksi ke database

header("Content-Type: application/json");  // Mengatur response content-type ke JSON

// Endpoint untuk mengambil semua data booking
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['id'])) {
    $stmt = $pdo->query("SELECT * FROM bookings");
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($bookings);
}

// Endpoint untuk mengambil booking berdasarkan ID
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
    $stmt->execute([$id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cek apakah data booking ditemukan
    if ($booking) {
        echo json_encode($booking);
    } else {
        echo json_encode(['error' => 'Booking tidak ditemukan']);
    }
}

// Endpoint untuk menambah booking baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan data JSON dari request body
    $data = json_decode(file_get_contents("php://input"), true);

    // Memeriksa apakah data yang diperlukan ada
    if (isset($data['nama_pemesan'], $data['tanggal_checkin'], $data['tanggal_checkout'], $data['kamar'])) {
        try {
            // Menyiapkan query untuk memasukkan data baru
            $stmt = $pdo->prepare("INSERT INTO bookings (nama_pemesan, tanggal_checkin, tanggal_checkout, kamar) VALUES (?, ?, ?, ?)");
            $stmt->execute([$data['nama_pemesan'], $data['tanggal_checkin'], $data['tanggal_checkout'], $data['kamar']]);
            
            // Mengirimkan respons sukses
            echo json_encode(['message' => 'Booking berhasil dibuat', 'data' => $data]);
        } catch (PDOException $e) {
            // Tangani error jika terjadi masalah dengan query
            echo json_encode(['error' => 'Gagal membuat booking: ' . $e->getMessage()]);
        }
    } else {
        // Mengirimkan respons jika data tidak lengkap
        echo json_encode(['error' => 'Data tidak lengkap. Pastikan semua field ada: nama_pemesan, tanggal_checkin, tanggal_checkout, kamar']);
    }
}

// Endpoint untuk memperbarui booking berdasarkan ID
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $data = json_decode(file_get_contents("php://input"), true);

    // Memeriksa apakah data yang diperlukan ada
    if (isset($data['nama_pemesan'], $data['tanggal_checkin'], $data['tanggal_checkout'], $data['kamar'])) {
        try {
            $stmt = $pdo->prepare("UPDATE bookings SET nama_pemesan = ?, tanggal_checkin = ?, tanggal_checkout = ?, kamar = ? WHERE id = ?");
            $stmt->execute([$data['nama_pemesan'], $data['tanggal_checkin'], $data['tanggal_checkout'], $data['kamar'], $id]);

            // Cek apakah ada data yang diubah
            if ($stmt->rowCount() > 0) {
                echo json_encode(['message' => 'Booking berhasil diperbarui', 'data' => $data]);
            } else {
                echo json_encode(['error' => 'Booking tidak ditemukan atau data tidak ada perubahan']);
            }
        } catch (PDOException $e) {
            // Tangani error jika terjadi masalah dengan query
            echo json_encode(['error' => 'Gagal memperbarui booking: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Data tidak lengkap. Pastikan semua field ada: nama_pemesan, tanggal_checkin, tanggal_checkout, kamar']);
    }
}

// Endpoint untuk menghapus booking berdasarkan ID
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Periksa apakah booking dengan ID ada
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE id = ?");
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // Menghapus booking berdasarkan ID
            $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['message' => 'Booking berhasil dihapus']);
        } else {
            echo json_encode(['error' => 'Booking tidak ditemukan']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Gagal menghapus booking: ' . $e->getMessage()]);
    }
}
?>
