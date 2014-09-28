<?php

if (!empty($setmodules)) {
    if (IS_ADMIN) $module['GENERAL']['CONFIGURE_BBCODE'] = basename(__FILE__);
    return;
}
require('./pagestart.php');
require(INC_DIR . 'bbcode.php');

if (!IS_ADMIN) bb_die($lang['NOT_ADMIN']);

if (isset($_POST['NewCode'])) {
	if(!$err_message = add_new_bb_code($_POST['NewCode'])) {
		bb_die('Новый код успешно добавлен');
	} else {
		bb_die($err_message);
	}
}

$rows = get_code_rows();

foreach ($rows as $row) {
	$access = "Пользователям, модераторам, администраторам";

	if ($row['user_level'] == 2) {
		$access = 'Только администраторам';
	} elseif ($row['user_level'] == 1) {
		$access = 'Модераторам, администраторам';
	}

    $template->assign_block_vars('codes', array(
        'ID' => $row['id'],
        'CODE' => $row['code'],
        'REGEXP' => $row['reg_exp'],
        'OUT_HTML' => $row['out_html'],
        'DESCRIPTION' => $row['description'],
        'USER_LEVEL' => $access,
        'MIN_POSTS' => $row['min_posts'],
        'IS_ENABLED' => boolval($row['is_enabled']),
        'CASE_SENSITIVITY' => boolval($row['case_sensitivity']),
        'CREATED_BY' => $row['created_by'],
        'CREATED_DATE' => $row['created_date'],
        'MODIFY_BY' => $row['modify_by'],
        'MODIFY_DATE' => $row['modify_date'],
    ));
}

$template->assign_vars(array(
    'IS_ROWS' => boolval(count($rows)),
));

print_page('admin_bbcode.tpl', 'admin');