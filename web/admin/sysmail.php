<?php

require_once __DIR__ . '/../system/photon/photon.php';
require_once __DIR__ . '/../system/common.php';
require_once __DIR__ . '/../system/admin/admin.php';
require_once __DIR__ . '/../system/admin/sysmail.php';

/**
 * メールテンプレートの一覧
 */
function action_index($data)
{
	if (!isset($data['c'])) {
		$data['c'] = [];
	}
	$data['data'] = db_paginate(sysmail_sql($data['c']));
	form_set_value('c', $data['c']);
	render('view/sysmail/index.php', $data);
}

/**
 * メールテンプレートの編集
 */
function action_edit($data)
{
	$data['id'] = isset($data['id']) ? intval($data['id']) : 0;
	if (is_request_post()) {
		$data = sysmail_validate($data, 'edit');
		if (!form_has_error()) {
			sysmail_save($data['sysmail']);
			return redirect('sysmail.php');
		}
	} else if ($data['id']) {
		$data['sysmail'] = sysmail_get($data['id']);
		if (get_action() == 'copy') {
			unset($data['sysmail']['id']);
		}
	} else {
		$today = db_date();
		$data['sysmail'] = [
			'enable_flag'  => 1,
			'period_begin' => $today,
			'period_end'   => $today,
		];
	}
	form_set_value(NULL, $data);
	render('view/sysmail/edit.php', $data);
}

/**
 * メールテンプレートの複製
 */
function action_copy($data)
{
	action_edit($data);
}

/**
 * メールテンプレートの削除
 */
function action_delete($data)
{
	sysmail_delete($data['id']);
	redirect('sysmail.php');
}

admin_bootstrap(TRUE);
admin_has_role('sysmail') or die;
execute();
?>