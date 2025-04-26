<?php
// 开启会话
session_start();

$db_host = 'localhost';
$db_name = 'kasilab';
$db_user = 'kasihappy';
$db_pass = 'what_can_i_say';

// 连接数据库
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// 检查连接
if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}


// 1. 检查用户是否已登录
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("HTTP/1.1 401 Unauthorized");
    exit("错误：请先登录系统");
}


// 3. 检查是否提供了URL参数
if (!isset($_GET['url'])) {
    header("HTTP/1.1 400 Bad Request");
    exit("错误：必须提供URL参数");
}

// 4. 获取并验证URL
$url = $_GET['url'];

// 使用filter_var验证URL格式
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    header("HTTP/1.1 400 Bad Request");
    exit("错误：提供的URL格式无效");
}

// 5. 解析URL组件
$parsed_url = parse_url($url);
if ($parsed_url === false) {
    header("HTTP/1.1 400 Bad Request");
    exit("错误：无法解析URL");
}

// 6. 只允许访问localhost的api.php
$allowed_host = 'localhost';
$allowed_path = '/api.php';

if (!isset($parsed_url['host']) || $parsed_url['host'] !== $allowed_host) {
    header("HTTP/1.1 403 Forbidden");
    exit("错误：只允许访问{$allowed_host}");
}

if (!isset($parsed_url['path']) || $parsed_url['path'] !== $allowed_path) {
    header("HTTP/1.1 403 Forbidden");
    exit("错误：只允许访问{$allowed_path}");
}

// 7. 解析查询参数
$query_params = [];
if (isset($parsed_url['query'])) {
    parse_str($parsed_url['query'], $query_params);
}

// 8. 检查action参数是否为delete
if (!isset($query_params['action']) || $query_params['action'] !== 'delete') {
    header("HTTP/1.1 400 Bad Request");
    exit("错误：无效的操作");
}

// 9. 检查是否提供了id参数
if (!isset($query_params['id'])) {
    header("HTTP/1.1 400 Bad Request");
    exit("错误：无效的操作");
}

// 10. 获取并验证id参数
$id = $query_params['id'];
if (!ctype_digit($id) || $id <= 0) {
    header("HTTP/1.1 400 Bad Request");
    exit("错误：无效的操作");
}
$id = (int)$id;

$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php");
} else {
    http_response_code(500);
    header("Location: index.php");
}

$stmt->close();


// 示例调用URL: 
// admin.php?url=http%3a%2f%2flocalhost%2fapi.php%3faction%3ddelete%26id%3d1
?>