<?php

require_once __DIR__ . '/admin_user.php';

/**
 * 管理画面の起動処理
 */
function admin_bootstrap($auth_require = TRUE)
{
	auth_realm('admin', '/admin/login.php');
	if ($auth_require) {
		auth_require();
	}
}

/**
 * ログイン中の管理ユーザが、指定した役割を持っているか調べる
 *
 * @param string|array $role 役割の識別子
 *
 * @return boolean 指定した役割を持っているか？
 */
function admin_has_role($role)
{
	// 役割を複数指定された場合
	if (is_array($role)) {
		foreach ($role as $r) {
			if (admin_has_role($r)) {
				return TRUE;
			}
		}
		return FALSE;
	}

	// 役割を一つ指定された場合
	$admin_user_me = admin_user_me();
	if (admin_user_me() !== NULL) {
		if (in_array('*', $admin_user_me['roles'])) {
			return TRUE;
		} else {
			return in_array($role, $admin_user_me['roles']);
		}
	} else {
		return FALSE;
	}
}

/**
 * グループIDによる拘束条件を取得する
 *
 * @return integer 拘束条件となるグループID
 */
function admin_group_id_constraint()
{
	if (!admin_has_role('admin_user') && admin_has_role('admin_user_local')) {
		return admin_user_me()['admin_group_id']; // 拘束条件あり
	} else {
		return 0; // 拘束条件なし
	}
}

/**
 * アクセス中のファイル名が一致したら、文字列"active"を返す
 *
 * @param string $filename ファイル名
 *
 * @return アクセス中のファイル名が$filenameなら"active"、違うなら空文字列
 */
function admin_controller_active($filename)
{
	return basename($_SERVER['PHP_SELF']) == $filename ? 'active' : '';
}

/**
 * 真偽値の選択肢を取得する
 *
 * @return array 真偽値の選択肢
 */
function boolean_get_options()
{
	return ['いいえ', 'はい'];
}

?>