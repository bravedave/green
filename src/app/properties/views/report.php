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
<table class="table table-sm" id="<?= $_table = strings::rand() ?>">
	<thead class="small">
		<tr>
			<td>#</td>
			<td>Street</td>
			<td>Suburb</td>
			<td>Postcode</td>

		</tr>

	</thead>

	<tbody><?php
	while ( $dto = $this->data->dataset->dto()) {	?>
	<tr
		data-id="<?= $dto->id ?>">

		<td class="small" line-number>&nbsp;</td>
		<td><?= $dto->address_street ?></td>
		<td><?= $dto->address_suburb ?></td>
		<td><?= $dto->address_postcode ?></td>

	</tr>

	<?php
	}
	?></tbody>

	<tfoot class="d-print-none">
		<tr>
			<td colspan="4" class="text-right">
				<button type="button" class="btn btn-outline-secondary" id="<?= $addBtn = strings::rand() ?>"><i class="fa fa-plus"></i></a>

			</td>

		</tr>

	</tfoot>

</table>

<script>
$(document).on( 'add-property', e => {
	( _ => {
		_.get( _.url('<?= $this->route ?>/edit'))
		.then( html => {
			let _html = $(html)
			_html.appendTo( 'body');

			$('.modal', _html).on( 'success', e => {
				window.location.reload();

			});

		});

	})( _brayworth_);

});

$(document).ready( () => {
	$('#<?= $_table ?>')
	.on('update-line-numbers', function(e) {
		$('> tbody > tr:not(.d-none) >td[line-number]', this).each( ( i, e) => {
			$(e).data('line', i+1).html( i+1);
		});
	})
	.trigger('update-line-numbers');

	$('#<?= $addBtn ?>').on( 'click', e => { $(document).trigger( 'add-property'); });

	$('#<?= $_table ?> > tbody > tr').each( ( i, tr) => {

		$(tr)
		.addClass( 'pointer' )
		.on( 'edit', function(e) {
			let _tr = $(this);
			let _data = _tr.data();

			( _ => {
				_.get( _.url('<?= $this->route ?>/edit/' + _data.id))
				.then( html => {
					let _html = $(html)
					_html.appendTo( 'body');

					$('.modal', _html).on( 'success', e => {
						window.location.reload();

					});

				});

			})( _brayworth_);

		})
		.on( 'click', function(e) {
			e.stopPropagation(); e.preventDefault();
			$(this).trigger( 'edit');

		});

	});

});
</script>
