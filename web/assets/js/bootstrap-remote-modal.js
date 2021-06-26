/**
 * リモートコンテンツに対応させたbootstrapのModalダイアログ
 *
 * - <a href="<URL>" data-toggle="remote-modal">...</a>の場合、<URL>をModalダイアログで開く。
 * - <URL>のリンク先の<div class="modal-dialog">以下が、Modalダイアログのコンテンツとして表示される。
 * - コンテンツの<form>タグのリクエスト結果のコンテンツが<div class="modal-dialog">を含むなら、
 *   Modalダイアログのコンテンツを更新し、含まないなら、画面のリロードを行う。
 */
$(function () {
	$('body').append('<div id="remote-modal" class="modal" />');
	var $remoteModal = $('#remote-modal');
	$('a[data-toggle="remote-modal"]').click(function () {
		var showRemoteModal = function (content) {
			$remoteModal.modal('hide');
			var $modalDialog = $(content).find('.modal-dialog');
			if ($modalDialog.length > 0) {
				$remoteModal.empty().append($modalDialog).modal('show');
				$remoteModal.find('form').submit(function () {
					var formData = new FormData();
					$.each($(this).serializeArray(), function (i, field) {
						formData.append(field.name, field.value);
					});
					$.each($(this).find('input[type=file]'), function (i, input) {
						$.each(input.files, function (i, file) {
							formData.append(input.name, file, file.name);
						});
					});
					$.ajax({
						url: $(this).attr('action'),
						data: formData,
						method: 'post',
						processData: false,
						contentType: false,
					}).done(showRemoteModal);
					return false;
				});
				$remoteModal.find('a[href]').click(function () {
					$.get($(this).attr('href'), showRemoteModal);
					return false;
				});
			} else {
				location.reload();
			}
		}
		$.get($(this).attr('href'), showRemoteModal);
		return false;
	});
})