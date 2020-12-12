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

use strings;

$dto = $this->data->dto;    ?>

<div id="<?= $_wrap = strings::rand() ?>">
  <form id="<?= $_form = strings::rand() ?>" autocomplete="off">
    <input type="hidden" name="action" value="set-password">
    <input type="hidden" name="id" value="<?= $dto->id ?>">

    <div class="modal fade" tabindex="-1" role="dialog" id="<?= $_modal = strings::rand() ?>" aria-labelledby="<?= $_modal ?>Label" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header bg-secondary text-white">
            <h5 class="modal-title" id="<?= $_modal ?>Label"><?= $this->title ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="input-group">
              <input type="password" class="form-control" name="password" autocomplete="new-password" required>

              <div class="input-group-append">
                <button type="button" class="btn btn-light input-group-text" tabindex="-1" id="<?= $_uid = strings::rand() ?>"><i class="bi bi-eye-fill"></i></button>

              </div>

            </div>

          </div>
          <script>
          ( _ => {
            $('#<?= $_uid ?>').on( 'click', function( e) {
              e.stopPropagation();e.preventDefault();

              let fld = $('input[name="password"]', '#<?= $_form ?>');
              if ( 'password' == fld.attr( 'type')) {
                fld.attr( 'type', 'text');
                $('.bi', this).removeClass('bi-eye-fill').addClass('bi-eye-slash-fill');

              }
              else {
                fld.attr( 'type', 'password');
                $('.bi', this).removeClass('bi-eye-slash-fill').addClass('bi-eye-fill');

              }

              $('#<?= $_form ?> input[name="password"]').focus()

            })

          }) (_brayworth_);
          </script>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">set password</button>

          </div>

        </div>
      </div>
    </div>
  </form>

  <script>
  ( _ => {
    $('#<?= $_modal ?>').on( 'shown.bs.modal', e => $('#<?= $_form ?> input[name="password"]').focus());

    $('#<?= $_form ?>')
    .on( 'submit', function( e) {
      let _form = $(this);
      let _data = _form.serializeFormJSON();

      _.post({
          url : _.url('<?= $this->route ?>'),
          data : _data,

      }).then( function( d) {
        _.growl( d);
        if ( 'ack' == d.response) {
            $('#<?= $_modal ?>').trigger( 'success', d);
            $('#<?= $_modal ?>').modal( 'hide');

        }

      });

      return false;

    });

  })(_brayworth_);
  </script>

</div>
