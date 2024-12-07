<?php
session_start();
require_once 'databaseConnect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error_message = '';
$success_message = '';

// Fetch current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $birthday = $_POST['birthday'];
    $address = htmlspecialchars(trim($_POST['address']));
    $subdistrict = htmlspecialchars(trim($_POST['subdistrict']));
    $district = htmlspecialchars(trim($_POST['district']));
    $postal_code = htmlspecialchars(trim($_POST['postal_code']));
    $user_email = filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL);
    $user_phone = htmlspecialchars(trim($_POST['user_phone']));

    // Validate inputs
    if (empty($first_name) || empty($last_name) || empty($birthday) ||
        empty($address) || empty($subdistrict) || empty($district) ||
        empty($postal_code) || !$user_email || empty($user_phone)) {
        $error_message = "All fields are required.";
    } else {
        // Check if any data has actually changed
        $changed = false;
        $fields = ['first_name', 'last_name', 'birthday', 'address', 'subdistrict', 'district',
            'postal_code', 'user_email', 'user_phone'];

        foreach ($fields as $field) {
            if ($user_data[$field] != ${$field}) {
                $changed = true;
                break;
            }
        }

        if ($changed) {
            $stmt = $conn->prepare("UPDATE users SET 
            first_name = ?, 
            last_name = ?, 
            birthday = ?, 
            address = ?, 
            subdistrict = ?, 
            district = ?, 
            postal_code = ?, 
            user_email = ?, 
            user_phone = ? 
            WHERE user_id = ?");

            $stmt->bind_param("ssssssssss",
                $first_name, $last_name, $birthday,
                $address, $subdistrict, $district,
                $postal_code, $user_email, $user_phone, $user_id);

            if ($stmt->execute()) {
                $success_message = "Profile updated successfully!";
                // Refresh user data
                $user_data = [
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'birthday' => $birthday,
                    'address' => $address,
                    'subdistrict' => $subdistrict,
                    'district' => $district,
                    'postal_code' => $postal_code,
                    'user_email' => $user_email,
                    'user_phone' => $user_phone
                ];
            } else {
                $error_message = "Update failed. " . $conn->error;
            }
        } else {
            // No changes were made
            $error_message = "No changes detected in the profile.";
        }
    }

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
<div class="w-full max-w-xl bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Edit Profile</h2>
    <form method="POST" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">First Name</label>
                <input type="text" name="first_name"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       value="<?php echo htmlspecialchars($user_data['first_name']); ?>" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Last Name</label>
                <input type="text" name="last_name"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       value="<?php echo htmlspecialchars($user_data['last_name']); ?>" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Birthday</label>
                <input type="date" name="birthday"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       value="<?php echo $user_data['birthday']; ?>" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="user_email"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       value="<?php echo htmlspecialchars($user_data['user_email']); ?>" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
                <input type="tel" name="user_phone"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       value="<?php echo htmlspecialchars($user_data['user_phone']); ?>" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Postal Code</label>
                <input type="text" name="postal_code"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       value="<?php echo htmlspecialchars($user_data['postal_code']); ?>" required>
            </div>
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Address</label>
            <textarea name="address" rows="3"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                      required><?php echo htmlspecialchars($user_data['address']); ?></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Sub district</label>
                <input type="text" name="subdistrict"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       value="<?php echo htmlspecialchars($user_data['subdistrict']); ?>" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">District</label>
                <input type="text" name="district"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       value="<?php echo htmlspecialchars($user_data['district']); ?>" required>
            </div>
        </div>

        <div class="flex items-center justify-center">
            <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update Profile
            </button>
        </div>
    </form>
</div>
<!-- Notification Popup -->
<div id="notification" class="fixed top-4 right-4 z-50 hidden">
    <div id="notificationContent"
         class="py-4 px-6 rounded-lg shadow-lg text-white transition-all duration-300 ease-in-out"></div>
</div>

<script>
    // Function to show notification
    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        const notificationContent = document.getElementById('notificationContent');

        // Reset classes
        notificationContent.classList.remove('bg-green-500', 'bg-red-500');

        // Set color based on type
        if (type === 'success') {
            notificationContent.classList.add('bg-green-500');
        } else {
            notificationContent.classList.add('bg-red-500');
        }

        // Set message
        notificationContent.textContent = message;

        // Show notification
        notification.classList.remove('hidden');

        // Auto-hide after 3 seconds
        setTimeout(() => {
            notification.classList.add('hidden');
        }, 3000);
    }

    // Check for PHP messages and show notification
    <?php if (!empty($success_message)): ?>
    showNotification('<?php echo $success_message; ?>', 'success');
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
    showNotification('<?php echo $error_message; ?>', 'error');
    <?php endif; ?>
</script>
</body>
</html>