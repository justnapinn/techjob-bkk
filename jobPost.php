<?php
session_start();
require 'databaseConnect.php';
include('navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Thai</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<?php generateNavbar(); ?>

<?php
$job_id = isset($_GET['job_id']) ? $_GET['job_id'] : '';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in. Please log in first.");
}

$user_id = $_SESSION['user_id'];

$sql_company = "SELECT j.job_id, u.first_name
                FROM jobs j
                INNER JOIN users u ON j.user_id = u.user_id
                WHERE j.job_id = ?";
$stmt = $conn->prepare($sql_company);
$stmt->bind_param("i", $job_id);  // ใช้ 'i' เพื่อให้ตรงกับประเภทข้อมูล job_id
$stmt->execute();
$result_company = $stmt->get_result();

if ($result_company->num_rows > 0) {
    $row_company = $result_company->fetch_assoc();
    echo "<h1 class='text-3xl font-bold text-center text-gray-800 mb-8'>" . htmlspecialchars($row_company["first_name"]) . "</h1>";
} else {
    echo "<h1 class='text-3xl font-bold text-center text-gray-800 mb-8'>ไม่พบข้อมูลบริษัท</h1>";
}


$job_id = isset($_GET['job_id']) ? $_GET['job_id'] : ''; // รับค่า job_id จาก URL

// ตรวจสอบว่า job_id มีค่าหรือไม่
if (!empty($job_id)) {
    // SQL query ที่ใช้ job_id เพื่อค้นหาข้อมูลที่ตรงกัน
    $sql = "SELECT * FROM jobs WHERE job_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $job_id);  // ใช้ 's' สำหรับประเภทข้อมูล VARCHAR (string)
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // ดึงข้อมูลจากฐานข้อมูล
        $row = $result->fetch_assoc();
        echo "<div class='max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-lg'>";
        
        // แสดงข้อมูลในรูปแบบที่ต้องการ
        echo "<div class='bg-gray-50 p-4 rounded-lg mb-4'>";
        echo "<h3 class='text-xl font-semibold text-blue-600 mb-2'>Job Title</h3>";
        echo "<p class='text-gray-700'>" . nl2br(htmlspecialchars($row["title"])) . "</p>";
        echo "</div>";
        
        // แสดงรายละเอียดเพิ่มเติม เช่น Description
        echo "<div class='bg-gray-50 p-4 rounded-lg mb-4'>";
        echo "<h3 class='text-xl font-semibold text-blue-600 mb-2'>Description</h3>";
        echo "<p class='text-gray-700'>" . nl2br(htmlspecialchars($row["description"])) . "</p>";
        echo "</div>";
        
        // เพิ่มข้อมูลอื่น ๆ ตามที่ต้องการ เช่น Welfare หรือ Contact
        echo "<div class='bg-gray-50 p-4 rounded-lg mb-4'>";
        echo "<h3 class='text-xl font-semibold text-blue-600 mb-2'>Welfare</h3>";
        echo "<p class='text-gray-700'>" . nl2br(htmlspecialchars($row["welfare"])) . "</p>";
        echo "</div>";
        
        echo "<div class='bg-gray-50 p-4 rounded-lg mb-4'>";
        echo "<h3 class='text-xl font-semibold text-blue-600 mb-2'>Contact</h3>";
        echo "<p class='text-gray-700'>" . nl2br(htmlspecialchars($row["contact"])) . "</p>";
        echo "</div>";

        echo "</div>";  // ปิด div ที่ครอบข้อมูล
    } else {
        echo "<p class='text-center text-xl text-gray-700'>ไม่พบข้อมูลงานนี้</p>";
    }

    $stmt->close();
} else {
    echo "<p class='text-center text-xl text-gray-700'>ไม่พบ job_id ที่ระบุ</p>";
}

$conn->close();
?>

</body>
</html>
