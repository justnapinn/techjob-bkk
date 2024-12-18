<?php

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_type'] == 'company') {
        // Company navbar
        echo '
            <nav class="bg-blue-500 text-white py-4">
                <div class="container mx-auto px-4 flex justify-between items-center">
                    <a href="index.php" class="text-2xl font-bold">TechJobBkk</a>
                    <div class="space-x-4">
                    <a href="createPost.php" class="text-white hover:bg-gray-700 px-3 py-2 rounded">Post</a>
                    <a href="applicants.php" class="text-white hover:bg-gray-700 px-3 py-2 rounded">Applicants</a>
                       <a href="profile.php" class="text-white hover:bg-gray-700 px-3 py-2 rounded">Profile</a>
                        <a href="logout.php" class="text-white hover:bg-gray-700 px-3 py-2 rounded">Logout</a>
                    </div>
                </div>
            </nav>';
        return;
    } else {
        // Applicants navbar
        echo '
        <nav class="bg-blue-500 text-white py-4">
            <div class="container mx-auto px-4 flex justify-between items-center">
                <a href="index.php" class="text-2xl font-bold">TechJobBkk</a>
                <div class="space-x-4">
                     <a href="index.php" class="text-white hover:bg-gray-700 px-3 py-2 rounded">Search Jobs</a>
                    <a href="profile.php" class="text-white hover:bg-gray-700 px-3 py-2 rounded">Profile</a>
                    <a href="myJobs.php" class="text-white hover:bg-gray-700 px-3 py-2 rounded">My Jobs</a>
                    <a href="logout.php" class="text-black hover:bg-white-700 bg-white-600 px-3 py-2 rounded">Logout</a>
                </div>
            </div>
        </nav>';
    }
} else {
    // Not logged-in navbar
    echo '
        <nav class="bg-blue-500 text-white py-4">
            <div class="container mx-auto flex justify-between items-center">
               <a href="index.php" class="text-2xl font-bold">TechJobBkk</a>
               <ul class="flex gap-4">
               <li><a href="index.php">หางาน</a></li>
               <li><a href="registerApp.php">สมัครสมาชิก</a></li>
               <li><a href="login.php">เข้าสู่ระบบ</a></li>
                <li><a href="registerCom.php">สมาชิกบริษัท</a></li>
               </ul>
            </div>
        </nav>';
}


?>