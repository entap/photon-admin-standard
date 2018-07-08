<?php

require_once __DIR__ . '/../system/photon/photon.php';
require_once __DIR__ . '/../system/common.php';
require_once __DIR__ . '/../system/admin/admin.php';
require_once __DIR__ . '/../system/admin/admin_user.php';
require_once __DIR__ . '/../system/admin/admin_group.php';
require_once __DIR__ . '/../system/admin/admin_role.php';

/**
 * 管理ユーザの一覧
 */
function action_index($data)
{
	admin_has_role(['admin_user', 'admin_user_local']) or die;
	if (!isset($data['c'])) {
		$data['c'] = [];
	}
	if ($constraint_group_id = admin_group_id_constraint()) {
		$data['c']['admin_group_id'] = $constraint_group_id; // グループIDに制限がある場合
	}
	$data['data'] = db_paginate(admin_user_sql($data['c']));
	form_set_value('c', $data['c']);
	render('view/admin_user/index.php', $data);
}

/**
 * 管理ユーザの編集
 */
function action_edit($data)
{
	admin_has_role(['admin_user', 'admin_user_local']) or die;
	$data['id'] = isset($data['id']) ? intval($data['id']) : 0;
	if (is_request_post()) {
		$data = admin_user_validate($data, 'edit');
		if (!form_has_error()) {
			admin_user_save($data['admin_user'], admin_group_id_constraint());
			return redirect('admin_user.php');
		}
	} else if ($data['id']) {
		$cond = ['id' => $data['id'], 'admin_group_id' => admin_group_id_constraint()];
		$data['admin_user'] = db_select_row(admin_user_sql($cond));
		$data['admin_user'] or die;
	} else {
		$data['admin_user'] = [
			'enable_flag' => 1,
		];
	}
	$data['admin_user']['password'] = '';
	$data['admin_user']['password_confirm'] = '';
	form_set_value(NULL, $data);
	render('view/admin_user/edit.php', $data);
}

/**
 * 管理ユーザの削除
 */
function action_delete($data)
{
	admin_has_role(['admin_user', 'admin_user_local']) or die;
	admin_user_delete($data['id']) or die;
	redirect('admin_user.php');
}

/**
 * 管理ユーザのパスワード
 */
function action_password($data)
{
	$data['admin_user']['id'] = auth_id();
	if (is_request_post()) {
		$data = admin_user_validate($data, 'password');
		if (!form_has_error()) {
			admin_user_save($data['admin_user']);
			return redirect('admin_user.php');
		}
	}
	$data['admin_user']['password'] = '';
	$data['admin_user']['password_confirm'] = '';
	form_set_value(NULL, $data);
	render('view/admin_user/password.php', $data);
}

admin_bootstrap(TRUE);
execute();
?>