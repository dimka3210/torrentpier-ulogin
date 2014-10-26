<?php

define('IN_ADMIN', true);
define('BB_ROOT', './../../');
require(BB_ROOT .'common.php');

$user->session_start();

if (!IS_ADMIN) bb_die($lang['NOT_AUTHORISED']);

$queries = array(
    'неактивные пользователи в течение 30 дней' => 'SELECT count(*) FROM `' . BB_USERS . '` WHERE `user_lastvisit` < UNIX_TIMESTAMP()-2592000',
    'неактивные пользователи в течение 90 дней' => 'SELECT count(*) FROM `' . BB_USERS . '` WHERE `user_lastvisit` < UNIX_TIMESTAMP()-7776000',
    'средний размер раздачи на трекере (сколько мегабайт)' => 'SELECT round(avg(size)/1048576) FROM `' . BB_BT_TORRENTS . '`',
    'сколько у нас всего раздач на трекере' => 'SELECT count(*) FROM `' . BB_BT_TORRENTS . '`',
    'сколько живых раздач (есть хотя бы 1 сид)' => 'SELECT count(distinct(topic_id)) FROM `' . BB_BT_TRACKER_SNAP . '` WHERE seeders > 0',
    'сколько раздач где которые сидируются больше 5 сидами' => 'SELECT count(distinct(topic_id)) FROM `' . BB_BT_TRACKER_SNAP . '` WHERE seeders > 5',
    'сколько у нас аплоадеров (те, кто залили хотя бы 1 раздачу)' => 'SELECT count(distinct(poster_id)) FROM `' . BB_BT_TORRENTS . '`',
    'сколько аплоадеров за последние 30 дней' => 'SELECT count(distinct(poster_id)) FROM `' . BB_BT_TORRENTS . '` WHERE reg_time >= UNIX_TIMESTAMP()-2592000'
);

$out = "<table>";
foreach ($queries as $title => $query) {
    $row = DB::i()->q($query)->fetch();
    $out .= "<tr><td>{$title}</td><td>{$row[0]}</td>";
}
$out .= "</table>";
?>

<html>
<head>
    <title>Stat</title>
    <meta charset="utf-8">
</head>
<body>
<?= $out ?>
</body>
</html>