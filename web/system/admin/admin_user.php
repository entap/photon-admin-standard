<?php

/**
 * 管理ユーザでログインする
 *
 * @param string $username ユーザ名
 * @param string $password パスワード
 *
 * @return boolean ログインに成功したか？
 */
function admin_user_login($username, $password)
{
	// IPアドレスがブロック中か調べる
	sql_clean();
	sql_table('log_admin_login_error');
	sql_field('COUNT(*)');
	sql_where_string('log_admin_login_error.ip_address', $_SERVER['REMOTE_ADDR']);
	sql_where_string('log_admin_login_error.created', db_datetime(time() - 600), '>=');
	if (db_select_value(sql_select()) >= 3) {
		form_set_error('login', 'アクセスが制限されています');
		return FALSE; // IPアドレスはブロック中
	}

	// 管理ユーザを取得
	sql_clean();
	sql_table('admin_user');
	sql_where_integer('admin_user.enable_flag', 1);
	sql_where_string('admin_user.username', $username);
	sql_where_string('admin_user.password', admin_user_hash_password($password));
	$admin_user = db_select_row(sql_select());
	if ($admin_user === NULL) {
		db_insert('log_admin_login_error', [
			'ip_address' => $_SERVER['REMOTE_ADDR'],
		]);
		form_set_error('login', 'ユーザ名またはパスワードが違います');
		return FALSE; // ID/PWが違う
	}

	// ログイン履歴に記録
	db_insert('log_admin_login', [
		'admin_user_id' => $admin_user['id'],
		'ip_address'    => $_SERVER['REMOTE_ADDR'],
	]);

	// ログイン
	auth_login($admin_user['id']);

	// 成功
	return TRUE;
}

/**
 * 管理ユーザのパスワードをハッシュ化する
 *
 * @param string $password パスワード
 *
 * @return string ハッシュ化したパスワード
 */
function admin_user_hash_password($password)
{
	return hash('sha256', config('secret_key') . $password);
}

/**
 * 管理ユーザを取得するSQLを生成する
 *
 * @param array $cond 検索条件
 *
 * @return string SQL
 */
function admin_user_sql($cond)
{
	sql_clean();
	sql_table('admin_user');
	sql_field('admin_user.*');
	sql_field('admin_group.name', 'admin_group_name');
	sql_field('admin_role.name', 'admin_role_name');
	sql_field('admin_role.roles', 'roles');
	sql_field('(SELECT MAX(created) FROM log_admin_login WHERE log_admin_login.admin_user_id=admin_user.id)', 'latest_login');
	sql_join('admin_group', 'id', 'admin_user', 'admin_group_id');
	sql_join('admin_role', 'id', 'admin_user', 'admin_role_id');
	if (isset($cond['keywords']) && $cond['keywords'] !== '') {
		sql_where_search(['admin_user.name', 'admin_user.username'], $cond['keywords']);
	}
	if (isset($cond['id']) && $cond['id']) {
		sql_where_integer('admin_user.id', $cond['id']);
	}
	if (isset($cond['admin_group_id']) && $cond['admin_group_id']) {
		sql_where_integer('admin_user.admin_group_id', $cond['admin_group_id']);
	}
	if (isset($cond['admin_role_id']) && $cond['admin_role_id']) {
		sql_where_integer('admin_user.admin_role_id', $cond['admin_role_id']);
	}
	sql_order('admin_user.id', FALSE);
	return sql_select();
}

/**
 * 管理ユーザを取得する
 *
 * @param integer $id 管理ユーザのID
 *
 * @return array 管理ユーザ
 */
function admin_user_get($id)
{
	$admin_user = db_select_row(admin_user_sql(['id' => $id]));
	$admin_user['roles'] = explode(',', $admin_user['roles']);
	return $admin_user;
}

/**
 * 管理ユーザの入力データをバリデーションする
 *
 * @param array  $data   入力データ
 * @param string $action 操作の種類 (edit / password)
 *
 * @return array 入力データのフィルタ結果
 */
function admin_user_validate($data, $action)
{
	rule_clean();
	if ($action === 'edit') {
		// ユーザ名の重複チェック
		$d = db_select_at('admin_user', $data['admin_user']['username'], 'username');
		if ($d !== NULL && $d['id'] != $data['admin_user']['id']) {
			form_set_error('admin_user[username]', 'このユーザ名は既に使用されています');
		}
	}
	rule('admin_user[id]');
	if ($action === 'edit') {
		rule('admin_user[admin_role_id]', ['required' => 'yes', 'options' => 'admin_role']);
		rule('admin_user[admin_group_id]', ['required' => 'yes', 'options' => 'admin_group']);
		rule('admin_user[enable_flag]', ['required' => 'no', 'options' => 'boolean']);
		rule('admin_user[name]', ['required' => 'yes', 'max_chars' => 20]);
		rule('admin_user[username]', ['required' => 'yes', 'type' => 'alnum_dash', 'min_chars' => 4, 'max_chars' => 20]);
	}
	$password_required = $action == 'password' || $data['admin_user']['id'] == 0;
	rule('admin_user[password]', ['required' => $password_required, 'type' => 'graph', 'min_chars' => 4, 'max_chars' => 20]);
	if ($action == 'password') {
		rule('admin_user[password_confirm]', ['matches' => 'admin_user[password]']);
	}
	if ($action === 'edit') {
		rule('admin_user[email]', ['required' => 'yes', 'type' => 'email']);
	}
	$data = filter($data);
	validate($data);
	return $data;
}

/**
 * 管理ユーザの入力データを保存する
 *
 * @param array   $admin_user          入力データ
 * @param integer $constraint_group_id 拘束条件のグループID
 *
 * @return integer ID
 */
function admin_user_save($admin_user, $constraint_group_id = 0)
{
	if ($admin_user['password'] === '') {
		unset($admin_user['password']);
	} else {
		$admin_user['password'] = admin_user_hash_password($admin_user['password']);
	}
	$update_cond = ['id' => $admin_user['id']];
	if ($constraint_group_id) {
		$admin_user['admin_group_id'] = $update_cond['admin_group_id'] = $constraint_group_id;
	}
	if ($admin_user['id']) {
		db_update('admin_user', $admin_user, $update_cond);
		return $admin_user['id'];
	} else {
		return db_insert('admin_user', $admin_user);
	}
}

/**
 * 管理ユーザが変更可能か？
 *
 * @param array $admin_user 管理ユーザ
 *
 * @return boolean 編集できるか？
 */
function admin_user_editable($admin_user)
{
	if (in_array('*', explode(',', $admin_user['roles']))) {
		if (!admin_has_role('*')) {
			return FALSE; // 特権ユーザを編集できるのは、特権ユーザのみ
		}
	}
	return TRUE;
}

/**
 * 管理ユーザが削除可能か？
 *
 * @param array $admin_user 管理ユーザ
 *
 * @return boolean 削除できるか？
 */
function admin_user_deletable($admin_user)
{
	return $admin_user['id'] != auth_id();
}

/**
 * 管理ユーザを削除する
 *
 * @param integer $id 管理ユーザのID
 *
 * @return boolean 削除できたか？
 */
function admin_user_delete($id)
{
	$admin_user = admin_user_get($id);
	if (admin_user_deletable($admin_user)) {
		db_delete_at('admin_user', $id);
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * ログイン中の管理ユーザを取得する
 *
 * @return array ログイン中の管理ユーザ
 */
function admin_user_me()
{
	global $__admin_user_me;
	if (isset($__admin_user_me)) {
		return $__admin_user_me;
	} else {
		return $__admin_user_me = admin_user_get(auth_id());
	}
}

?>