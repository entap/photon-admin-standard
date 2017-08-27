<?php

require_once __DIR__ . '/../system/photon/photon.php';
require_once __DIR__ . '/../system/admin/admin.php';

function action_index($data)
{
	render('view/index/index.php', $data);
}

admin_bootstrap(TRUE);
execute();
?>