<?php
session_start();
// ตรวจสอบว่า user เป็น admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'a') {
    header("Location: event.php");
    exit();
}

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // รับข้อมูลจากฟอร์ม
    $name = trim($_POST["name"]);
    $location = trim($_POST["location"]);
    $activities = isset($_POST["activity"]) ? $_POST["activity"] : []; // รับค่าจากฟอร์มที่เลือกหลายกิจกรรม
    $price = trim($_POST["price"]);
    $province_id = trim($_POST["province"]);  // รับค่าจังหวัดจากฟอร์ม (province_id)
    $description = trim($_POST["description"]);  // รับค่าคำอธิบาย

    // ตรวจสอบข้อมูลพื้นฐาน
    if (empty($name)) $errors[] = "Event name is required.";
    if (empty($location)) $errors[] = "Location is required.";
    if (empty($activities)) $errors[] = "At least one activity is required."; // ตรวจสอบว่ามีกิจกรรมที่เลือก
    if (empty($price) || !is_numeric($price)) $errors[] = "Valid price is required.";
    if (empty($province_id)) $errors[] = "Province is required."; // ตรวจสอบจังหวัด
    if (empty($description)) $errors[] = "Description is required.";
    
    // ตรวจสอบการอัปโหลดไฟล์รูป
    if (!isset($_FILES["image"]) || $_FILES["image"]["error"] != 0) {
        $errors[] = "Image file is required.";
    }

    if (empty($errors)) {
        // กำหนดโฟลเดอร์สำหรับเก็บไฟล์อัปโหลด
        $targetDir = "../uploads/";
        // สร้างโฟลเดอร์ถ้ายังไม่มี
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // สร้างชื่อไฟล์ที่ไม่ซ้ำ (อาจใช้เวลา, random string, หรือทั้งสอง)
        $fileName = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . time() . "_" . $fileName;

        // ตรวจสอบประเภทไฟล์ (ตัวอย่าง: jpg, jpeg, png)
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png'];
        if (!in_array($imageFileType, $allowedTypes)) {
            $errors[] = "Only JPG, JPEG, and PNG files are allowed.";
        }

        // หากไม่มี error เกี่ยวกับไฟล์ ให้ย้ายไฟล์ไปยังโฟลเดอร์ target
        if (empty($errors)) {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $errors[] = "There was an error uploading the image.";
            }
        }

        // หากไม่มี error ทั้งหมด ให้บันทึกข้อมูลลงฐานข้อมูล
        if (empty($errors)) {
            $conn = new mysqli('mysql', 'user', 'password', 'aquarium');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // ดึง province_name จาก province_id
            $province_sql = "SELECT province_name FROM provinces WHERE province_id = ?";
            $stmt = $conn->prepare($province_sql);
            $stmt->bind_param("i", $province_id);
            $stmt->execute();
            $stmt->bind_result($province_name);
            $stmt->fetch();
            $stmt->close();

            // ตรวจสอบว่า province_name มีค่าหรือไม่
            if (empty($province_name)) {
                $errors[] = "Invalid province selected.";
            }

            if (empty($errors)) {
                // แปลงอาเรย์กิจกรรมเป็น string
                $activities_str = implode(",", $activities);
                $description = $_POST['description'] ?? 'No description';
                
                // บันทึกข้อมูลลงในตาราง events
                // คำสั่ง INSERT INTO ที่มีคอลัมน์ที่ตรงกัน
                $stmt = $conn->prepare("INSERT INTO events (name, image, location, province, activity, price, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
                
                // แก้ไขการใช้ bind_param() ให้ตรงกับจำนวนตัวแปร
                $stmt->bind_param("ssssdss", $name, $targetFile, $location, $province_name, $activities_str, $price, $description);

                if ($stmt->execute()) {
                    $success = "Event added successfully!";
                } else {
                    $errors[] = "Error: " . $stmt->error;
                }

                header("Location:../admin/editinfo_ticket.php");
                $stmt->close();
            }
            $conn->close();
        }
    }
}
?>
