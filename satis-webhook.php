<?php

if($_SERVER['REQUEST_METHOD'] !== 'POST')
{
  header('Location: /');
  exit();
}

$list = json_decode(file_get_contents("{$_SERVER['DOCUMENT_ROOT']}/packages.json"), true);
if(isset($_POST['update'], $list['providers'][$_POST['update']]))
{
  $res = file_put_contents("{$_SERVER['HOME']}/tmp/satis-update", "{$_POST['update']}\n", FILE_APPEND);
  http_response_code(202);
  exit('Operation queued.');
}

http_response_code(400);
print "Ff.";
