<?php

require_once __DIR__ . '/../system/photon/photon.php';
require_once __DIR__ . '/../system/admin/admin.php';
require_once __DIR__ . '/../system/admin/admin_user.php';

/**
 * ログイン画面
 */
function action_index($data)
{
	if (is_request_post()) {
		if (admin_user_login($data['username'], $data['password'])) {
			redirect('/admin/');
		}
	}
	render('view/login/index.php', $data);
}

admin_bootstrap(FALSE);
execute();
?>