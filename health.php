<?php
$servername = "localhost";
$username = "root";  
$password = "xampp";     
$dbname = "health_db";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}
function calculate_bmi($height, $weight) {
    return $weight / (($height / 100) * ($height / 100));
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    
    $bmi = calculate_bmi($height, $weight);

    $sql = "INSERT INTO health_data (name, height, weight, bmi) 
            VALUES ('$name', '$height', '$weight', '$bmi')";
    
    if ($conn->query($sql) === TRUE) {
        echo "データが保存されました。";
    } else {
        echo "エラー: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>健康情報の登録</title>
</head>
<body>
    <h2>健康情報を登録</h2>
    <form method="post" action="">
        <label for="name">名前:</label><br>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="height">身長 (cm):</label><br>
        <input type="number" id="height" name="height" required><br><br>
        
        <label for="weight">体重 (kg):</label><br>
        <input type="number" id="weight" name="weight" required><br><br>
        
        <input type="submit" value="登録">
        <a href="http://localhost/view_health.php">管理画面に進む
        </a>
    </form>
</body>
</html>
