<?php
session_start();
require_once 'databaseConnect.php';
include('navbar.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen items-center justify-center">
<div class="w-full fixed top-[60px] bg-white p-8 shadow-md z-10">
    <div class="flex items-center">
        <div class="mr-4">
            <?php if (!empty($user_data['logo'])): ?>
                <img
                        src="<?php echo htmlspecialchars($user_data['logo']); ?>"
                        alt="Profile Picture"
                        class="w-24 h-24 rounded-full object-cover border-4 border-blue-500"
                >
            <?php else: ?>
                <div class="w-24 h-24 rounded-full bg-gray-300 flex items-center justify-center">
                    <span class="text-gray-500">No Image</span>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                <?php echo htmlspecialchars($user_data['first_name'] . ' ' . $user_data['last_name']); ?>
            </h2>
            <p class="text-gray-600 mt-2">
                <?php echo htmlspecialchars($user_data['user_email']); ?>
            </p>
        </div>
    </div>

    <a href="profileEdit.php"
       class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
        Edit Profile
    </a>
</div>
</body>
</html>