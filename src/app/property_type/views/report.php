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
<table class="table" id="<?= $_table = strings::rand() ?>">
	<thead class="small">
		<tr>
			<td class="text-center">#</td>
			<td>Property Type</td>

		</tr>

	</thead>

	<tbody>
	<?php
	while ( $dto = $this->data->dataset->dto()) {
		printf( '<tr data-id="%d">', $dto->id);

		print '<td class="small text-center" line-number>&nbsp;</td>';
		printf( '<td>%s</td>', $dto->property_type);

		print '</tr>';
	}
	?></tbody>

	<tfoot class="d-print-none">
		<tr>
			<td colspan="2" class="text-right">
				<button type="button" class="btn btn-outline-secondary" id="<?= $addBtn = strings::rand() ?>"><i class="fa fa-plus"></i></a>

			</td>

		</tr>

	</tfoot>

</table>

<script>
( _ => {
	$(document).on( 'add-property_type', e => {
		_.get( _.url('<?= $this->route ?>/edit'))
		.then( html => {
			let _html = $(html)
			_html.appendTo( 'body');

			$('.modal', _html).on( 'success', e => window.location.reload());

		});

	});

	$(document).ready( () => {
		$('#<?= $_table ?>')
		.on('update-row-numbers', function(e) {
			$('> tbody > tr:not(.d-none) >td[line-number]', this).each( ( i, e) => {
				$(e).html( i+1);

			});

		})
		.trigger('update-row-numbers');

		$('#<?= $addBtn ?>').on( 'click', e => { $(document).trigger( 'add-property_type'); });

		$('#<?= $_table ?> > tbody > tr').each( ( i, tr) => {

			$(tr)
			.addClass( 'pointer' )
			.on( 'delete', function( e) {
				let _tr = $(this);

				_.ask({
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

			})
			.on( 'edit', function(e) {
				let _tr = $(this);
				let _data = _tr.data();

				_.get( _.url('<?= $this->route ?>/edit/' + _data.id))
				.then( html => {
					let _html = $(html)
					_html.appendTo( 'body');

					$('.modal', _html).on( 'success', e => {
						window.location.reload();

					});

				});

			})
			.on( 'contextmenu', function( e) {
				if ( e.shiftKey)
					return;

				e.stopPropagation();e.preventDefault();

				let _tr = $(this);

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

			})
			.on( 'click', function(e) {
				e.stopPropagation(); e.preventDefault();
				$(this).trigger( 'edit');

			});

		});

	});

})( _brayworth_);
</script>
