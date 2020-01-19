<html>
<head>

<?php

require_once __DIR__ . '/pass_check.php';
require_logined_session();

header('Content-Type: text/html; charset=UTF-8');

?>

</head>

<body>

<h1>ようこそ,<?=h($_SESSION['username'])?>さん</h1>

<a href="/album/logout.php?token=<?=h(generate_token())?>">ログアウト</a>

</body>
</html>