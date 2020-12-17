<?php require_once __DIR__ . '/../common/header.php' ?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form action="admin_user.php?action=edit" method="post" enctype="multipart/form-data" class="form-inline">
            <?= form_hidden('admin_user[id]') ?>
            <div class="modal-header">
                <h4 class="modal-title">管理ユーザ</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th class="col-3">有効</th>
                        <td class="col-9">
                            <?= form_error('admin_user[enable_flag]') ?>
                            <?= form_select_assoc('admin_user[enable_flag]', 'boolean', 'class="form-control"') ?>
                        </td>
                    </tr>
                    <tr>
                        <th>フルネーム</th>
                        <td>
                            <?= form_error('admin_user[name]') ?>
                            <?= form_text('admin_user[name]', 'class="form-control" size="40"') ?>
                        </td>
                    </tr>
                    <tr>
                        <th>ユーザ名</th>
                        <td>
                            <?= form_error('admin_user[username]') ?>
                            <?= form_text('admin_user[username]', 'class="form-control" size="20"') ?>
                        </td>
                    </tr>
                    <tr>
                        <th>パスワード</th>
                        <td>
                            <?= form_error('admin_user[password]') ?>
                            <?= form_password('admin_user[password]', 'class="form-control" size="20" autocomplete="off"') ?>
                        </td>
                    </tr>
                    <tr>
                        <th>メールアドレス</th>
                        <td>
                            <?= form_error('admin_user[email]') ?>
                            <?= form_text('admin_user[email]', 'class="form-control" size="40"') ?>
                        </td>
                    </tr>
                    <?php if (admin_group_id_constraint()) { ?>
                        <?= form_hidden('admin_user[admin_group_id]') ?>
                    <?php } else { ?>
                        <tr>
                            <th>グループ</th>
                            <td>
                                <?= form_error('admin_user[admin_group_id]') ?>
                                <?= form_select_assoc('admin_user[admin_group_id]', 'admin_group', 'class="form-control"') ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <th>役割</th>
                        <td>
                            <?= form_error('admin_user[admin_role_id]') ?>
                            <?= form_select_assoc('admin_user[admin_role_id]', 'admin_role', 'class="form-control"') ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> 保存</button>
            </div>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../common/footer.php' ?>
