<?php

require_once __DIR__ . '/../system/photon/photon.php';
require_once __DIR__ . '/../system/admin/admin.php';
require_once __DIR__ . '/../system/admin/admin_role.php';

/**
 * 役割の一覧
 */
function action_index($data)
{
	if (!isset($data['c'])) {
		$data['c'] = [];
	}
	$data['data'] = db_paginate(admin_role_sql($data['c']));
	form_set_value('c', $data['c']);
	render('view/admin_role/index.php', $data);
}

/**
 * 役割の編集
 */
function action_edit($data)
{
	$data['id'] = isset($data['id']) ? intval($data['id']) : 0;
	if (is_request_post()) {
		$data = admin_role_validate($data);
		if (!form_has_error()) {
			admin_role_save($data['admin_role']);
			return redirect('admin_role.php');
		}
	} else if ($data['id']) {
		$data['admin_role'] = admin_role_get($data['id']);
	} else {
		$data['admin_role'] = [];
	}
	form_set_value(NULL, $data);
	render('view/admin_role/edit.php', $data);
}

/**
 * 役割の削除
 */
function action_delete($data)
{
	admin_role_delete($data['id']) or die;
	redirect('admin_role.php');
}

admin_bootstrap(TRUE);
admin_has_role('admin_role') or die;
execute();
?>