<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit;
}
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ma_nv = $_POST['ma_nv'];
    $ten_nv = $_POST['ten_nv'];
    $phai = $_POST['phai'];
    $noi_sinh = $_POST['noi_sinh'];
    $ma_phong = $_POST['ma_phong'];
    $luong = $_POST['luong'];

    $stmt = $pdo->prepare('INSERT INTO NHANVIEN (Ma_NV, Ten_NV, Phai, Noi_Sinh, Ma_Phong, Luong) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$ma_nv, $ten_nv, $phai, $noi_sinh, $ma_phong, $luong]);
    header('Location: index.php');
    exit;
}

// Lấy danh sách phòng ban
$stmt = $pdo->query('SELECT * FROM PHONGBAN');
$departments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Nhân Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: white;
            border-radius: 10px 10px 0 0;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h2 class="mb-4">Thêm Nhân Viên</h2>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Thông Tin Nhân Viên</h5>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="ma_nv" class="form-label">Mã Nhân Viên</label>
                        <input type="text" class="form-control" id="ma_nv" name="ma_nv" required>
                    </div>
                    <div class="mb-3">
                        <label for="ten_nv" class="form-label">Tên Nhân Viên</label>
                        <input type="text" class="form-control" id="ten_nv" name="ten_nv" required>
                    </div>
                    <div class="mb-3">
                        <label for="phai" class="form-label">Phái</label>
                        <select class="form-control" id="phai" name="phai">
                            <option value="NAM">Nam</option>
                            <option value="NU">Nữ</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="noi_sinh" class="form-label">Nơi Sinh</label>
                        <input type="text" class="form-control" id="noi_sinh" name="noi_sinh">
                    </div>
                    <div class="mb-3">
                        <label for="ma_phong" class="form-label">Phòng Ban</label>
                        <select class="form-control" id="ma_phong" name="ma_phong">
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo $dept['Ma_Phong']; ?>"><?php echo $dept['Ten_Phong']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="luong" class="form-label">Lương</label>
                        <input type="number" class="form-control" id="luong" name="luong">
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                    <a href="index.php" class="btn btn-secondary">Quay Lại</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>