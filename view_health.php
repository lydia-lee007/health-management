<?php
$servername = "localhost";
$username = "root";
$password = "xampp";
$dbname = "health_db";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}
$sql = "SELECT * FROM health_data";
$result = $conn->query($sql);
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_sql = "DELETE FROM health_data WHERE id=$delete_id";
    if ($conn->query($delete_sql) === TRUE) {
        echo "データが削除されました。<br>";
    } else {
        echo "削除エラー: " . $conn->error . "<br>";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_id'])) {
    $update_id = $_POST['update_id'];
    $update_height = $_POST['update_height'];
    $update_weight = $_POST['update_weight'];
    $update_bmi = calculate_bmi($update_height, $update_weight);
    
    $update_sql = "UPDATE health_data SET height='$update_height', weight='$update_weight', bmi='$update_bmi' WHERE id=$update_id";
    if ($conn->query($update_sql) === TRUE) {
        echo "データが更新されました。<br>";
    } else {
        echo "更新エラー: " . $conn->error . "<br>";
    }
}

function calculate_bmi($height, $weight) {
    return $weight / (($height / 100) * ($height / 100));
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>健康情報の管理</title>
</head>
<body>
    <h2>健康情報の管理</h2>
    <h3>過去のデータ</h3>
    
    <table border="1">
        <tr>
            <th>名前</th>
            <th>身長</th>
            <th>体重</th>
            <th>BMI</th>
            <th>日付</th>
            <th>更新</th>
            <th>削除</th>
        </tr>
        
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["height"] . "</td>";
                echo "<td>" . $row["weight"] . "</td>";
                echo "<td>" . $row["bmi"] . "</td>";
                echo "<td>" . $row["date"] . "</td>";
                echo "<td>
                        <form method='POST'>
                            <input type='hidden' name='update_id' value='" . $row["id"] . "'>
                            <input type='number' name='update_height' placeholder='身長' value='" . $row["height"] . "' required>
                            <input type='number' name='update_weight' placeholder='体重' value='" . $row["weight"] . "' required>
                            <input type='submit' value='更新'>
                        </form>
                      </td>";
                echo "<td>
                        <form method='POST'>
                            <input type='hidden' name='delete_id' value='" . $row["id"] . "'>
                            <input type='submit' value='削除'>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>データがありません</td></tr>";
        }
        ?>
    </table>
    <a href="http://localhost/health.php">登録画面に戻る</a>
</body>
</html>
