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
<div class="form-group row d-print-none">
	<div class="col">
		<input type="search" class="form-control" autofocus id="<?= $srch = strings::rand() ?>" />

	</div>

</div>

<table class="table table-sm" id="<?= $_table = strings::rand() ?>">
	<thead class="small">
		<tr>
			<td class="text-center d-none d-md-table-cell" line-number>#</td>
			<td>Street</td>
			<td>Suburb</td>
			<td class="d-none d-md-table-cell">State</td>
			<td class="d-none d-sm-table-cell">P/Code</td>
			<td class="text-center d-none d-md-table-cell">Type</td>
			<td class="text-center"><i class="fa fa-bed"></i></td>
			<td class="text-center"><i class="fa fa-bath"></i></td>
			<td class="text-center d-none d-sm-table-cell"><i class="fa fa-car"></i></td>

		</tr>

	</thead>

	<tbody><?php
	while ( $dto = $this->data->dataset->dto()) {	?>
	<tr
		data-id="<?= $dto->id ?>">

		<td class="small text-center d-none d-md-table-cell" line-number>&nbsp;</td>
		<td><?= $dto->address_street ?></td>
		<td><?= $dto->address_suburb ?></td>
		<td class="d-none d-md-table-cell"><?= $dto->address_state ?></td>
		<td class="d-none d-sm-table-cell"><?= $dto->address_postcode ?></td>
		<td class="text-center d-none d-md-table-cell"><?= $dto->description_type ?></td>
		<td class="text-center"><?= $dto->description_beds ?></td>
		<td class="text-center"><?= $dto->description_bath ?></td>
		<td class="text-center d-none d-sm-table-cell"><?= $dto->description_car ?></td>

	</tr>

	<?php
	}
	?></tbody>

	<tfoot class="d-print-none">
		<tr>
			<td colspan="2" class="d-none d-sm-table-cell"></td>
			<td colspan="3" class="d-none d-md-table-cell"></td>
			<td colspan="4" class="text-right">
				<button type="button" class="btn btn-outline-secondary" id="<?= $addBtn = strings::rand() ?>"><i class="fa fa-plus"></i></a>

			</td>

		</tr>

	</tfoot>

</table>

<script>
( _ => {
	$(document).on( 'add-property', e => {
		_.get.modal( _.url('<?= $this->route ?>/edit'))
		.then( m => m.on( 'success', e => window.location.reload()));

	});

	$('#<?= $_table ?>')
	.on('update-line-numbers', function(e) {
		let t = 0;
		$('> tbody > tr:not(.d-none) >td[line-number]', this).each( ( i, e) => {
			$(e).data('line', i+1).html( i+1);
			t++;

		});

		$('> thead > tr >td[line-number]', this).html( t);

	})

	$('#<?= $addBtn ?>').on( 'click', e => { $(document).trigger( 'add-property'); });

	$('#<?= $_table ?> > tbody > tr').each( ( i, tr) => {

		$(tr)
		.addClass( 'pointer' )
		.on( 'delete', function(e) {
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

			_.get.modal( _.url('<?= $this->route ?>/edit/' + _data.id))
			.then( m => m.on( 'success', e => window.location.reload()));

		})
		.on( 'contextmenu', function( e) {
			if ( e.shiftKey)
				return;

			e.stopPropagation();e.preventDefault();

			let _tr = $(this);
      let _data = _tr.data();

			_.hideContexts();

			let _context = _.context();

			_context.append( $('<a href="#"><b>edit</b></a>').on( 'click', function( e) {
				e.stopPropagation();e.preventDefault();

				_context.close();

				_tr.trigger( 'edit');

			}));

			_context.append(
        $('<a>view</a>')
        .attr( 'href', _.url( '<?= $this->route ?>/view/' + _data.id))
        .on( 'click', function( e) {
          _context.close();

        })

      );

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

	let srchidx = 0;
	$('#<?= $srch ?>').on( 'keyup', function( e) {
		let idx = ++srchidx;
		let txt = this.value;

		let _tbl = $('#<?= $_table ?>');
		let _tbl_data = _tbl.data();

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

	$(document).ready( () => {
		$('#<?= $_table ?>').trigger('update-line-numbers');

	});

})( _brayworth_);
</script>
