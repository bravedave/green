<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

use dvc\icon;
?>

<h1 class="d-none d-print-block"><?= $this->title ?></h1>
<div class="form-row mb-2 d-print-none">
  <?php if ( $this->data->pages > 1) { ?>
    <div class="col-lg-5 col-xl-4">
      <form>
        <div class="input-group">
          <?php if ( $this->data->offset > 0) { ?>
            <div class="input-group-prepend">
              <a class="input-group-text" href="<?= strings::url( $this->route) ?>"><?= icon::get( icon::chevronStart ) ?></a>
            </div>
          <?php } // if ( $this->data->offset > 0) { ?>

          <div class="input-group-append">
            <a class="input-group-text" href="<?= strings::url( sprintf( '%s/?page=%d', $this->route, $this->data->offset)) ?>"><?= icon::get( icon::chevronLeft ) ?></a>
          </div>

          <input type="text" class="form-control text-center" name="page"
            id="<?= $_uid = strings::rand() ?>" value="<?= $this->data->offset + 1 ?>">

          <div class="input-group-append">
            <div class="input-group-text"><?= sprintf( '/%d', $this->data->pages) ?></div>
          </div>

          <div class="input-group-append">
            <a class="input-group-text" href="<?= strings::url( sprintf( '%s/?page=%d', $this->route, $this->data->offset + 2)) ?>"><?= icon::get( icon::chevronRight ) ?></a>
          </div>

        </div>

      </form>
      <script>
        ( _ => $(document).ready( () => {
          $('#<?= $_uid ?>').on( 'change', function(e) {
            $(this).closest( 'form').submit();

          });

        }))( _brayworth_);
      </script>

    </div>

  <?php } // if ( $this->data->pages > 1) { ?>

	<div class="col">
		<input type="search" class="form-control" autofocus id="<?= $srch = strings::rand() ?>" />

	</div>

</div>
<table class="table table-sm" id="<?= $_table = strings::rand() ?>">
	<thead class="small">
		<tr>
			<td class="text-center" line-number>#</td>
			<td>Name</td>
			<td>Mobile</td>
			<td>
				<div class="d-flex">
					<div class="d-none d-md-block flex-fill">Email</div>
					<button type="button" class="btn btn-sm py-0 btn-light" data-role="add-people"><?= icon::get( icon::plus ) ?></a>

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
				<button type="button" class="btn btn-outline-secondary" data-role="add-people"><?= icon::get( icon::plus ) ?> add people</a>

			</td>

		</tr>

	</tfoot>

</table>
<script>
( _ => {
  $(document).on( 'add-people', e => {
    _.get.modal( _.url('<?= $this->route ?>/edit'))
    .then( m => m.on( 'success', e => window.location.reload()));

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

  });

})( _brayworth_);
</script>
