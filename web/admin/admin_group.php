<?php

require_once __DIR__ . '/../system/photon/photon.php';
require_once __DIR__ . '/../system/admin/admin.php';
require_once __DIR__ . '/../system/admin/admin_group.php';

/**
 * 管理グループの一覧
 */
function action_index($data)
{
	if (!isset($data['c'])) {
		$data['c'] = [];
	}
	$data['data'] = db_paginate(admin_group_sql($data['c']));
	form_set_value('c', $data['c']);
	render('view/admin_group/index.php', $data);
}

/**
 * 管理グループの編集
 */
function action_edit($data)
{
	$data['id'] = isset($data['id']) ? intval($data['id']) : 0;
	if (is_request_post()) {
		$data = admin_group_validate($data);
		if (!form_has_error()) {
			admin_group_save($data['admin_group']);
			return redirect('admin_group.php');
		}
	} else if ($data['id']) {
		$data['admin_group'] = admin_group_get($data['id']);
	} else {
		$data['admin_group'] = [];
	}
	form_set_value(NULL, $data);
	render('view/admin_group/edit.php', $data);
}

/**
 * 管理グループの削除
 */
function action_delete($data)
{
	admin_group_delete($data['id']) or die;
	redirect('admin_group.php');
}

admin_bootstrap(TRUE);
admin_has_role('admin_group') or die;
execute();
?>