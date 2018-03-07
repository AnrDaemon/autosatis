<?php

$list = json_decode(file_get_contents("{$_SERVER['DOCUMENT_ROOT']}/packages.json"), true);
if(isset($_GET['update'], $list['providers'][$_GET['update']]))
{
  $res = file_put_contents("{$_SERVER['HOME']}/tmp/satis-update", "{$_GET['update']}\n", FILE_APPEND);
  if(false === $res)
    print "Ff.";
}
else
{
  print "Ff.";
}
