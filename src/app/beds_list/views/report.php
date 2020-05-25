<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/	?>
<h1 class="d-none d-print-block"><?= $this->title ?></h1>
<table class="table table-sm" id="<?= $tbl = strings::rand() ?>">
	<thead class="small">
		<tr>
			<td>#</td>
			<td>Beds</td>
			<td>Description</td>

		</tr>

	</thead>

	<tbody>
		<?php
		while ( $dto = $this->data->dataset->dto()) {	?>
		<tr
			data-id="<?= $dto->id ?>"
			data-beds="<?= htmlspecialchars( $dto->beds) ?>"
			data-description="<?= htmlspecialchars( $dto->description) ?>">

			<td class="small" line-number>&nbsp;</td>
			<td><?= $dto->beds ?></td>
			<td><?= $dto->description ?></td>

		</tr>

		<?php
		}   ?>

	</tbody>

	<tfoot class="d-print-none">
		<tr>
			<td colspan="3" class="text-right">
				<button type="button" class="btn btn-outline-secondary" id="<?= $addBtn = strings::rand() ?>"><i class="fa fa-plus"></i></a>

			</td>

		</tr>

	</tfoot>

</table>

<form id="<?= $formID = strings::rand() ?>">
	<input type="hidden" name="id" />
	<input type="hidden" name="action" />

	<div class="modal fade" tabindex="-1" role="dialog" id="<?= $modalID = strings::rand() ?>"
		aria-labelledby="<?= $modalID ?>Label" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header bg-primary text-white">
					<h5 class="modal-title" id="<?= $modalID ?>Label" title="<?= htmlspecialchars( $this->title) ?>"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>

				</div>
				<div class="modal-body">

					<div class="form-group">
						<label for="<?= $uid = strings::rand() ?>">Beds</label>
						<input class="form-control" type="text" name="beds" required id="<?= $uid ?>" />

					</div>

					<div class="form-group">
						<label for="<?= $uid = strings::rand() ?>">Description</label>
						<input class="form-control" type="text" name="description" required id="<?= $uid ?>" />

					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-outline-danger d-none" id="<?= $deleteBtn = strings::rand() ?>">delete</button>
					<button type="submit" class="btn btn-primary">save</button>

				</div>

			</div><!-- modal-content -->

		</div><!-- modal-dialog -->

	</div><!-- modal -->

</form>
<script>
$(document).ready( () => {
	$('#<?= $tbl ?>')
	.on('update-row-numbers', function(e) {
		$('> tbody > tr:not(.d-none) >td[line-number]', this).each( ( i, e) => {
			$(e).html( i+1);

		});

	})
	.trigger('update-row-numbers');

	$('#<?= $addBtn ?>').on( 'click', ( e) => { $('#<?= $formID ?>').trigger( 'add'); });
	$('#<?= $deleteBtn ?>').on( 'click', ( e) => { $('#<?= $formID ?>').trigger( 'delete'); });

	$('#<?= $modalID ?>').on('shown.bs.modal', function( e) {
		$('input[name="beds"]', '#<?= $formID ?>').focus();

	});

	$('#<?= $formID ?>')
	.on( 'add', function( e) {
		let _form = $(this);

		$('.modal-title', _form).html( 'add' );
		$('input[name="action"]', _form).val( 'add');
		$('input[name="id"]', _form).val( '');
		$('input[name="beds"]', _form).val( '');
		$('input[name="description"]', _form).val( '');

		$('#<?= $deleteBtn ?>').addClass( 'd-none');

		$('#<?= $modalID ?>').modal('show');

	})
	.on( 'delete', function( e) {
		let _form = $(this);

		$('#<?= $modalID ?>').modal('hide');

		_brayworth_.ask({
			headClass: 'text-white bg-danger',
			text: 'Are you sure ?',
			title: 'Confirm Delete',
			buttons : {
				yes : function(e) {
					$('input[name="action"]', _form).val( 'delete');
					_form.submit();

					$(this).modal('hide');

				}

			}

		});

	})
	.on( 'submit', function( e) {
		let _form = $(this);
		let _data = _form.serializeFormJSON();

		_brayworth_.post({
			url : _brayworth_.url('<?= $this->route ?>'),
			data : _data,

		}).then( (d) => {
			if ( 'ack' == d.response) {
				window.location.reload();

			}
			else {
				_brayworth_.growl( d);

			}

		});

		$('#<?= $modalID ?>').modal('hide');
		return false;

	});

	$('#<?= $tbl ?> > tbody > tr').each( ( i, tr) => {

		$(tr)
		.addClass( 'pointer' )
		.on( 'click', function(e) {
			e.stopPropagation(); e.preventDefault();

			let _tr = $(this);
			let _data = _tr.data();

			let _form = $('#<?= $formID ?>');

			$('.modal-title', _form).html( 'edit' );

			$('input[name="action"]', _form).val( 'update');
			$('input[name="id"]', _form).val( _data.id);
			$('input[name="beds"]', _form).val( _data.beds);
			$('input[name="description"]', _form).val( _data.description);

			$('#<?= $deleteBtn ?>').removeClass( 'd-none');

			$('#<?= $modalID ?>').modal('show');

		});

	});

});
</script>
