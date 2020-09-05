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
    <input type="hidden" name="action" value="save-user">
    <input type="hidden" name="id" value="<?= $dto->id ?>">

    <div class="modal fade" tabindex="-1" role="dialog" id="<?= $_modal = strings::rand() ?>" aria-labelledby="<?= $_modal ?>Label" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header bg-secondary text-white py-2">
            <h5 class="modal-title" id="<?= $_modal ?>Label"><?= $this->title ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>

          </div>

          <div class="modal-body">
            <div class="form-group row">
                <div class="col">
                    <input type="text" class="form-control" name="name" placeholder="name" required
                        value="<?= $dto->name ?>" />

                </div>

            </div>

            <div class="form-group row">
              <div class="col">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><?= \dvc\icon::get( \dvc\icon::at) ?></i></div>

                  </div>

                  <input type="email" class="form-control" name="email" placeholder="@"
                      value="<?= $dto->email ?>" />

                </div>

              </div>

            </div>

            <div class="form-group row">
              <div class="col">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><?= \dvc\icon::get( \dvc\icon::phone) ?></i></div>

                  </div>

                  <input type="tel" class="form-control" name="mobile" placeholder="0000 000 000"
                      value="<?= strings::asLocalPhone( $dto->mobile) ?>" />

                </div>

              </div>

            </div>

            <?php if ( config::$GREEN_FIELD_ADMIN) { ?>
              <div class="form-group row">
                  <div class="col">
                      <div class="form-check">
                          <input type="checkbox" class="form-check-input" name="active" value="1"
                              id="<?= $_uid = strings::rand() ?>"
                              <?php if ( $dto->active) print 'checked'; ?> />
                          <label class="form-check-label" for="<?= $_uid ?>">Active</label>

                      </div>

                  </div>

              </div>

            <?php } ?>

            <?php if ( config::$GREEN_FIELD_ACTIVE) { ?>
              <div class="form-group row">
                  <div class="col">
                      <div class="form-check">
                          <input type="checkbox" class="form-check-input" name="admin" value="1"
                              id="<?= $_uid = strings::rand() ?>"
                              <?php if ( $dto->admin) print 'checked'; ?> />
                          <label class="form-check-label" for="<?= $_uid ?>">Admin</label>

                      </div>

                  </div>

              </div>

            <?php } ?>

          </div>

          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>

          </div>

        </div>

      </div>

    </div>

  </form>

  <script>
  ( _ => {
      $('#<?= $_modal ?>').on( 'shown.bs.modal', e => { $('#<?= $_form ?> input[name="name"]').focus(); });

      $('#<?= $_form ?>')
      .on( 'submit', function( e) {
          let _form = $(this);
          let _data = _form.serializeFormJSON();
          let _modalBody = $('.modal-body', _form);

          _.post({
              url : _.url('<?= $this->route ?>'),
              data : _data,

          }).then( function( d) {
              if ( 'ack' == d.response) {
                  $('#<?= $_modal ?>').trigger( 'success', d);
                  $('#<?= $_modal ?>').modal( 'hide');

              }
              else {
                  _.growl( d);

              }

          });


          return false;
      });

  })(_brayworth_);
  </script>

</div>
