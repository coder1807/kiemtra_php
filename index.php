<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include 'config.php';

// Xử lý phân trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

// Đếm tổng số nhân viên
$stmt = $pdo->query('SELECT COUNT(*) FROM NHANVIEN');
$total_count = $stmt->fetchColumn();
$total_pages = ceil($total_count / $limit);

// Lấy danh sách nhân viên
$stmt = $pdo->prepare('
    SELECT NHANVIEN.*, PHONGBAN.Ten_Phong
    FROM NHANVIEN
    INNER JOIN PHONGBAN ON NHANVIEN.Ma_Phong = PHONGBAN.Ma_Phong
    LIMIT ?, ?
');
$stmt->bindValue(1, $offset, PDO::PARAM_INT);
$stmt->bindValue(2, $limit, PDO::PARAM_INT);
$stmt->execute();
$employees = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Nhân Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            border-radius: 10px;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background-color: #007bff;
            color: white;
            border-radius: 10px 10px 0 0;
        }

        .gender-img {
            width: 50px;
            height: auto;
            border-radius: 5px;
            margin-left: 5px;
        }

        .navbar {
            background: linear-gradient(90deg, #1e3a8a, #3b82f6);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1.5rem;
            color: #fff;
            display: flex;
            align-items: center;
        }

        .navbar-brand img {
            width: 30px;
            margin-right: 10px;
        }

        .nav-link {
            color: #fff !important;
            font-weight: 500;
            position: relative;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #ffd700 !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background-color: #ffd700;
            transition: width 0.3s ease-in-out;
        }

        .nav-link:hover::after {
            width: 100%;
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
    <!-- Thanh Điều Hướng (Header) -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="https://img.freepik.com/premium-vector/employee-management-icon-vector-image-can-be-used-project-management_120816-121037.jpg" alt="Logo">
                Employee Management
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Danh Sách Nhân Viên</a>
                    </li>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="add_employee.php">Thêm Nhân Viên</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Đăng Xuất</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Danh Sách Nhân Viên -->
    <div class="container mt-4">
        <h2 class="mb-4">Danh Sách Nhân Viên</h2>
        <div class="row">
            <?php foreach ($employees as $employee): ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card shadow">
                        <div class="card-header">
                            <h5 class="card-title">
                                <?php echo htmlspecialchars($employee['Ten_NV']); ?>
                                <?php if ($employee['Phai'] == 'NU'): ?>
                                    <img src="images/woman.jpg" alt="Nữ" class="gender-img">
                                <?php else: ?>
                                    <img src="images/man.jpg" alt="Nam" class="gender-img">
                                <?php endif; ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><strong>Mã NV:</strong> <?php echo htmlspecialchars($employee['Ma_NV']); ?></p>
                            <p class="card-text"><strong>Nơi Sinh:</strong> <?php echo htmlspecialchars($employee['Noi_Sinh']); ?></p>
                            <p class="card-text"><strong>Phòng Ban:</strong> <?php echo htmlspecialchars($employee['Ten_Phong']); ?></p>
                            <p class="card-text"><strong>Lương:</strong> <?php echo htmlspecialchars($employee['Luong']); ?></p>
                        </div>
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                            <div class="card-footer text-end">
                                <a href="edit_employee.php?id=<?php echo $employee['Ma_NV']; ?>" class="btn btn-warning btn-sm">Sửa</a>
                                <form method="post" action="delete_employee.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $employee['Ma_NV']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Phân Trang -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                        <a class="page-link" href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <!-- Bootstrap JS (chỉ cần cho navbar responsive) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>