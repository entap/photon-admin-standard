<div class="list-group">
	<?php if (admin_has_role(['admin_user', 'admin_user_local'])) { ?>
		<a href="admin_user.php" class="list-group-item <?= active_class('admin_user.php') ?>">管理ユーザ</a>
	<?php } ?>
	<?php if (admin_has_role('admin_group')) { ?>
		<a href="admin_group.php" class="list-group-item <?= active_class('admin_group.php') ?>">管理グループ</a>
	<?php } ?>
	<?php if (admin_has_role('admin_role')) { ?>
		<a href="admin_role.php" class="list-group-item <?= active_class('admin_role.php') ?>">役割</a>
	<?php } ?>
</div>