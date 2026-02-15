<?php
// السماح فقط بطلبات POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  http_response_code(405);
  exit("Method Not Allowed");
}

// تنظيف المدخلات
function clean($value) {
  return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

$name  = isset($_POST['name'])  ? clean($_POST['name'])  : '';
$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
$phone = isset($_POST['phone']) ? clean($_POST['phone']) : '';
$age   = isset($_POST['age'])   ? clean($_POST['age'])   : '';

// تحقق أساسي
if (empty($name) || empty($email) || empty($phone) || empty($age)) {
  exit("❌ برجاء ملء جميع الحقول.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  exit("❌ بريد إلكتروني غير صالح.");
}

// الإيميل اللي هيستقبل الرسائل (غيّره)
$to = "saifnasr575@gmail.com";

// عنوان الرسالة
$subject = "تسجيل جديد من صفحة الهبوط";

// محتوى الرسالة
$message = "تم تسجيل عميل جديد:\n\n";
$message .= "الاسم: $name\n";
$message .= "الإيميل: $email\n";
$message .= "الهاتف: $phone\n";
$message .= "العمر: $age\n";
$message .= "-------------------------\n";
$message .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
$message .= "الوقت: " . date("Y-m-d H:i:s") . "\n";

// الهيدرز (غيّر الإيميل للدومين الحقيقي)
$headers  = "From: Website <contact@yourdomain.com>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// إرسال الإيميل
$sent = mail($to, $subject, $message, $headers);

// ... بعد كود إرسال الإيميل
if ($sent) {
    // التحويل لصفحة النجاح
    header("Location: success.html");
    exit();
} else {
    echo "❌ حدث خطأ في الإرسال، حاول مجدداً.";
}