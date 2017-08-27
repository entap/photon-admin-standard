<?php

require_once __DIR__ . '/../system/photon/photon.php';
require_once __DIR__ . '/../system/admin/admin.php';

/**
 * ログアウト
 */
function action_index($data)
{
	auth_logout();
	redirect('login.php');
}

admin_bootstrap(FALSE);
execute();
?>