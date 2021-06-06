<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
 * usage:
 * _brayworth_.get.modal(_brayworth_.url('people/getPerson')).then( m => m.on('success', d => console.log(d)))
*/

namespace green\people;

use strings;
use theme;  ?>

<form id="<?= $_form = strings::rand() ?>" autocomplete="off">
  <div class="modal fade" tabindex="-1" role="dialog" id="<?= $_modal = strings::rand() ?>" aria-labelledby="<?= $_modal ?>Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header <?= theme::modalHeader() ?>">
          <h5 class="modal-title" id="<?= $_modal ?>Label"><?= $this->title ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="search" name="name" class="form-control">

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">close</button>

        </div>

      </div>

    </div>

  </div>
  <script>
    (_ => {
      $('#<?= $_modal ?>').on('shown.bs.modal', e => {
        $('input[name="name"]', '#<?= $_form ?>').autofill({
          source: (request, response) => {
            _.post({
              url: _.url('<?= $this->route ?>'),
              data: {
                action : 'search',
                term: request.term

              },

            }).then( d => {
              if ( 'ack' == d.response) {
                response( d.data);

              }

            });

          },
          select: (e, ui) => {
            var o = ui.item;
            if (o.id > 0) {
              $('#<?= $_modal ?>').trigger('success', o);

            }

            $('#<?= $_modal ?>').modal('hide');

          }

        });

        $('input[name="name"]', '#<?= $_form ?>').focus();

        $('#<?= $_form ?>')
          .on('submit', function(e) {
            let _form = $(this);
            let _data = _form.serializeFormJSON();
            let _modalBody = $('.modal-body', _form);

            // console.table( _data);

            return false;
          });

      });

    })(_brayworth_);
  </script>

</form>
