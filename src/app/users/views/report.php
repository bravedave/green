<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\users;

use dvc\icon;
use strings;  ?>

<h1 class="d-none d-print-block"><?= $this->title ?></h1>
<table class="table table-sm" id="<?= $_table = strings::rand() ?>">
	<thead class="small">
		<tr>
			<td class="text-center" line-number>#</td>
			<td>Name</td>
			<td>Mobile</td>
			<td>Email</td>
      <?php if ( config::$GREEN_FIELD_ACTIVE) { ?>
			<td class="text-center">Active</td>
      <?php }
            if ( config::$GREEN_FIELD_ADMIN) { ?>
			<td class="text-center">Admin</td>
      <?php } ?>

		</tr>

	</thead>

	<tbody><?php
	while ( $dto = $this->data->dataset->dto()) {
    printf('<tr data-id="%s">', $dto->id);

		printf('<td class="small text-center" line-number>&nbsp;</td>');
		printf('<td>%s</td>', $dto->name);
		printf('<td>%s</td>', strings::asLocalPhone( $dto->mobile));
    printf('<td>%s</td>', $dto->email);
    if ( config::$GREEN_FIELD_ACTIVE) {
      printf('<td class="text-center">%s</td>', $dto->active ? strings::html_tick : '&nbsp;' );
    }
    if ( config::$GREEN_FIELD_ADMIN) {
      printf('<td class="text-center">%s</td>', $dto->admin ? strings::html_tick : '&nbsp;' );
    }

    print '</tr>';

	}
	?></tbody>

	<tfoot class="d-print-none">
		<tr>
			<td colspan="6" class="text-right">
				<button type="button" class="btn btn-outline-secondary" id="<?= $addBtn = strings::rand() ?>"><?= icon::get( icon::plus ) ?></a>

			</td>

		</tr>

	</tfoot>

</table>

<script>
( _ => {
  $(document).on( 'add-user', e => {
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
  .trigger('update-line-numbers');

  $('#<?= $addBtn ?>').on( 'click', e => $(document).trigger( 'add-user'));

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
    .on( 'set-password', function(e) {
      let _tr = $(this);
      let _data = _tr.data();

      _.get.modal( _.url('<?= $this->route ?>/setpassword/' + _data.id));

    })
    .on( 'contextmenu', function( e) {
      if ( e.shiftKey)
        return;

      e.stopPropagation();e.preventDefault();

      _.hideContexts();

      let _tr = $(this);
      let _context = _.context();

      _context.append( $('<a href="#"><b>edit</b></a>').on( 'click', function( e) {
        e.stopPropagation();e.preventDefault();

        _context.close();
        _tr.trigger( 'edit');

      }));

      _context.append( $('<a href="#">set password</a>').on( 'click', function( e) {
        e.stopPropagation();e.preventDefault();

        _context.close();
        _tr.trigger( 'set-password');

      }));

      _context.append( '<hr>');

      _context.append( $('<a href="#"><i class="bi bi-trash"></i>delete</a>').on( 'click', function( e) {
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

}) (_brayworth_);
</script>
