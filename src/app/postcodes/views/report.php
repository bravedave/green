<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

use dvc\icon; ?>

<h1 class="d-none d-print-block"><?= $this->title ?></h1>
<div class="row">
	<div class="col">
		<input type="search" class="form-control" autofocus id="<?= $_search = strings::rand() ?>" />

	</div>

</div>
<table class="table table-sm" id="<?= $_table = strings::rand() ?>">
	<thead class="small">
		<tr>
			<td class="text-center" line-number>#</td>
			<td>Suburb</td>
			<td>State</td>
			<td class="text-center">Postcode</td>

		</tr>

	</thead>

	<tbody><?php
	while ( $dto = $this->data->dataset->dto()) {	?>
	<tr
		data-id="<?= $dto->id ?>">

		<td class="small text-center" line-number>&nbsp;</td>
		<td><?= $dto->suburb ?></td>
		<td><?= $dto->state ?></td>
		<td class="text-center"><?= $dto->postcode ?></td>

	</tr>

	<?php
	}
	?></tbody>

	<tfoot class="d-print-none">
		<tr>
			<td colspan="4" class="text-right">
				<button type="button" class="btn btn-outline-secondary" id="<?= $addBtn = strings::rand() ?>"><?= icon::get( icon::plus ) ?></a>

			</td>

		</tr>

	</tfoot>

</table>

<script>
$(document).on( 'add-postcode', e => {
	( _ => {
		_.get.modal( _.url('<?= $this->route ?>/edit'))
		.then( m => m.on( 'success', e => window.location.reload()));

	})( _brayworth_);

});

$(document).ready( () => {
	$('#<?= $_table ?>')
	.on('update-line-numbers', function(e) {
		let t = 0;
		$('> tbody > tr:not(.d-none) >td[line-number]', this).each( ( i, e) => {
			$(e).data('line', i+1).html( i+1);
			t++;

		});

		$('> thead > tr >td[line-number]', this).html( t);

	})
	.trigger('update-line-numbers');

	$('#<?= $addBtn ?>').on( 'click', e => { $(document).trigger( 'add-postcode'); });

	$('#<?= $_table ?> > tbody > tr').each( ( i, tr) => {

		$(tr)
		.addClass( 'pointer' )
		.on( 'delete', function(e) {
			let _tr = $(this);

			_brayworth_.ask({
				headClass: 'text-white bg-danger',
				text: 'Are you sure ?',
				title: 'Confirm Delete',
				buttons : {
					yes : function(e) {

						$(this).modal('hide');
						_tr.trigger( 'delete-confirmed');

					}

				}

			});

		})
		.on( 'delete-confirmed', function(e) {
			let _tr = $(this);
			let _data = _tr.data();

			( _ => {
				_.post({
					url : _.url('<?= $this->route ?>'),
					data : {
						action : 'delete',
						id : _data.id

					},

				}).then( d => {
					if ( 'ack' == d.response) {
						_tr.remove();
						$('#<?= $_table ?>').trigger('update-line-numbers');

					}
					else {
						_.growl( d);

					}

				});

			}) (_brayworth_);

		})
		.on( 'edit', function(e) {
			let _tr = $(this);
			let _data = _tr.data();

			( _ => {
				_.get.modal( _.url('<?= $this->route ?>/edit/' + _data.id))
				.then( m => m.on( 'success', e => window.location.reload()));

			})( _brayworth_);

		})
		.on( 'contextmenu', function( e) {
			if ( e.shiftKey)
				return;

			e.stopPropagation();e.preventDefault();

			let _tr = $(this);

			( _ => {
				_.hideContexts();

				let _context = _.context();

				_context.append( $('<a href="#"><b>edit</b></a>').on( 'click', function( e) {
					e.stopPropagation();e.preventDefault();

					_context.close();

					_tr.trigger( 'edit');

				}));

				_context.append( $('<a href="#"><i class="bi bi-trash"></i>delete</a>').on( 'click', function( e) {
					e.stopPropagation();e.preventDefault();

					_context.close();

					_tr.trigger( 'delete');

				}));

				_context.open( e);

			})( _brayworth_);

		})
		.on( 'click', function(e) {
			e.stopPropagation(); e.preventDefault();
			$(this).trigger( 'edit');

		});

	});

	let srchidx = 0;
	$('#<?= $_search ?>').on( 'keyup', function( e) {
		let idx = ++srchidx;
		let txt = this.value;

		$('#<?= $_table ?> > tbody > tr').each( ( i, tr) => {
			if ( idx != srchidx) return false;

			let _tr = $(tr);
			let _data = _tr.data();

			if ( '' == txt.trim()) {
				_tr.removeClass( 'd-none');

			}
			else {
				let str = _tr.text()
				if ( str.match( new RegExp(txt, 'gi'))) {
					_tr.removeClass( 'd-none');

				}
				else {
					_tr.addClass( 'd-none');

				}

			}

		});

		$('#<?= $_table ?>').trigger( 'update-line-numbers');

	});

});
</script>
