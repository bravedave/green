<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/  ?>

<h1 class="d-none d-print-block"><?= $this->title ?></h1>
<table class="table table-sm" id="<?= $_table = strings::rand() ?>">
	<thead class="small">
		<tr>
			<td class="text-center" line-number>#</td>
			<td>Name</td>
			<td>Mobile</td>
			<td>
				<div class="d-flex">
					<div class="d-none d-md-block flex-fill">Email</div>
					<button type="button" class="btn btn-sm py-0 btn-light" data-role="add-people"><i class="fa fa-plus"></i></a>

				</div>

			</td>

		</tr>

	</thead>

	<tbody><?php
	while ( $dto = $this->data->dataset->dto()) {	?>
	<tr data-id="<?= $dto->id ?>">
		<td class="small text-center" line-number>&nbsp;</td>
		<td><?= $dto->name ?></td>
		<td><?= strings::asLocalPhone( $dto->mobile) ?></td>
		<td><span class="d-none d-md-inline"><?= $dto->email ?></span></td>

	</tr>

	<?php
	}
	?></tbody>

	<tfoot class="d-print-none">
		<tr>
			<td colspan="4" class="text-right">
				<button type="button" class="btn btn-outline-secondary" data-role="add-people"><i class="fa fa-fw fa-plus"></i> add people</a>

			</td>

		</tr>

	</tfoot>

</table>
<script>
$(document).on( 'add-people', e => {
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

	$('#<?= $_table ?> button[data-role="add-people"]').on( 'click', e => { $(document).trigger( 'add-people'); });

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

				_context.append( $('<a href="#"><i class="fa fa-trash"></i>delete</a>').on( 'click', function( e) {
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

});
</script>
