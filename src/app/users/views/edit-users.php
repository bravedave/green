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
use theme;

$dto = $this->data->dto;    ?>

<div id="<?= $_wrap = strings::rand() ?>">
  <form id="<?= $_form = strings::rand() ?>" autocomplete="off">
    <input type="hidden" name="action" value="save-user">
    <input type="hidden" name="id" value="<?= $dto->id ?>">

    <div class="modal fade" tabindex="-1" role="dialog" id="<?= $_modal = strings::rand() ?>" aria-labelledby="<?= $_modal ?>Label" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header <?= theme::modalHeader() ?>">
            <h5 class="modal-title" id="<?= $_modal ?>Label"><?= $this->title ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>

          </div>

          <div class="modal-body">
            <div class="form-row mb-2">
              <div class="col">
                <input type="text" class="form-control" name="name" placeholder="name" required value="<?= $dto->name ?>">

              </div>

            </div>

            <div class="form-row mb-2">
              <div class="col">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="bi bi-at"></i></div>

                  </div>

                  <input type="email" class="form-control" name="email" placeholder="@" value="<?= $dto->email ?>">

                </div>

              </div>

            </div>

            <div class="form-row mb-2">
              <div class="col">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="bi bi-phone"></i></div>

                  </div>

                  <input type="tel" class="form-control" name="mobile" placeholder="0000 000 000"
                      value="<?= strings::asLocalPhone( $dto->mobile) ?>">

                </div>

              </div>

            </div>

            <div class="form-row mb-2">
              <div class="col-sm">
                <div class="form-text text-muted small font-italic">date of birth</div>
                <input type="date" class="form-control" name="birthdate" value="<?php if ( strtotime( $dto->birthdate) > strtotime('1900-01-01')) print $dto->birthdate ?>">

              </div>

              <div class="col-sm">
                <div class="form-text text-muted small font-italic">start date</div>
                <input type="date" class="form-control" name="start_date" value="<?php if ( strtotime( $dto->start_date) > strtotime('1900-01-01')) print $dto->start_date ?>">

              </div>

            </div>

            <div class="form-row mb-2">
              <div class="col-form-label col-sm-3">Group</div>
              <div class="col">
                <input type="text" class="form-control" name="group" placeholder="team" value="<?= $dto->group ?>">

              </div>

            </div>

            <?php if ( config::green_email_enable()) { ?>
              <div class="form-row mb-2">
                <div class="col-3">Email</div>
                <div class="col">
                  <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="mail_type" value="imap"
                      id="<?= $_uid = strings::rand() ?>"
                      <?php if ( 'imap' == $dto->mail_type) print 'checked' ?> />

                    <label class="form-check-label" for="<?= $_uid ?>">Imap</label>

                  </div>

                  <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="mail_type" value="exchange"
                      id="<?= $_uid = strings::rand() ?>"
                      <?php if ( 'exchange' == $dto->mail_type) print 'checked' ?> />

                    <label class="form-check-label" for="<?= $_uid ?>">Exchange</label>

                  </div>

                  <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="mail_type" value="none"
                      id="<?= $_uid = strings::rand() ?>"
                      <?php if ( !\in_array( $dto->mail_type, ['imap','exchange'])) print 'checked' ?> />

                    <label class="form-check-label" for="<?= $_uid ?>">none</label>

                  </div>

                </div>

              </div>

              <div class="form-row mb-2 d-none" email-row>
                <div class="col-3 col-form-label">Email server</div>
                <div class="col">
                  <input type="text" class="form-control" name="mail_server"
                    value="<?= $dto->mail_server ?>" />

                  <div class="form-text text-muted font-italic">to connect to a imap server using ssl (normal) use ssl:://mail.domaon.dom</div>

                </div>

              </div>

              <div class="form-row mb-2 d-none" email-row>
                <div class="col-3 col-form-label">Email account</div>
                <div class="col">
                  <input type="text" class="form-control" name="mail_account"
                    value="<?= $dto->mail_account ?>" />

                </div>

              </div>

              <div class="form-row mb-2 d-none" email-row>
                <div class="col-3 col-form-label">Email password</div>
                <div class="col">
                  <div class="input-group">
                    <input type="password" class="form-control" name="mail_password" />

                    <div class="input-group-append">
                      <div class="input-group-text" id="<?= $_uid = strings::rand() ?>"><i class="bi bi-eye-slash-fill"></i></div>

                    </div>

                    <div class="input-group-append">
                      <button type="button" class="btn input-group-text" disabled id="<?= $_uid ?>_verify">
                        verify

                      </button>

                    </div>

                  </div>

                  <div class="form-text text-muted small">only required if changing/verifying</div>

                </div>

              </div>
              <script>
              $(document).ready( () => {
                $('#<?= $_uid ?>').on( 'click', function( e) {
                  e.stopPropagation();e.preventDefault();

                  let _me = $(this);
                  let fld = $('input[name="mail_password"]', '#<?= $_form ?>');

                  if ( 'password' == fld.attr('type')) {
                    fld.attr( 'type', 'text');
                    $('i', this).removeClass('bi-eye-slash-fill').addClass('bi-eye-fill')

                  }
                  else {
                    fld.attr( 'type', 'password');
                    $('i', this).removeClass('bi-eye-fill').addClass('bi-eye-slash-fill')

                  }

                });

                $('input[name="mail_type"]', '#<?= $_form ?>').change( function(e) {
                  let _me = $(this);

                  if ( _me.prop('checked')) {
                    if ( 'none' == _me.val()) {
                      $('[email-row]', '#<?= $_form ?>').addClass( 'd-none');

                    }
                    else {
                      $('[email-row]', '#<?= $_form ?>').removeClass( 'd-none');

                    }

                  }

                });

                $('input[name="mail_password"]', '#<?= $_form ?>').on( 'keyup', function(e) {
                  let _me = $(this);
                  if ( '' == _me.val()) {
                    $('#<?= $_uid ?>_verify').prop( 'disabled', true);

                  }
                  else {
                    $('#<?= $_uid ?>_verify').prop( 'disabled', false);

                  }

                });

                $('input[name="mail_type"]:checked', '#<?= $_form ?>').trigger( 'change');

                $('#<?= $_uid ?>_verify').on( 'click', function( e) {
                  e.stopPropagation();e.preventDefault();

                  let _me = $(this);
                  let _form = $('#<?= $_form ?>');
                  let _data = _form.serializeFormJSON();

                  // console.log( _data);
                  let pkt = {
                    action : 'email-verify',
                    email_type : _data.mail_type,
                    email_server : _data.mail_server,
                    email_account : _data.mail_account,
                    email_password : _data.mail_password,

                  };

                  ( _ => {
                    _.post({
                      url : _.url('<?= $this->route ?>'),
                      data : pkt,

                    }).then( d => {
                      if ( 'ack' == d.response) {
                        _me.parent().append('<div class="input-group-text"><i class="bi bi-check text-success"></i></div>');
                        _me.remove();

                      }
                      else {
                        _.growl( d);

                      }

                    });

                  }) (_brayworth_);

                });

              });
              </script>

            <?php } ?>

            <?php if ( config::$GREEN_FIELD_ACTIVE) { ?>
              <div class="form-row mb-2">
                <div class="offset-sm-3 col">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="active" value="1"
                      id="<?= $_uid = strings::rand() ?>"
                      <?php if ( $dto->active) print 'checked'; ?>>
                    <label class="form-check-label" for="<?= $_uid ?>">Active</label>

                  </div>

                </div>

              </div>

            <?php } ?>

            <?php if ( config::$GREEN_FIELD_ADMIN) { ?>
              <div class="form-row mb-2">
                <div class="offset-sm-3 col">
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
            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">close</button>
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

  }) (_brayworth_);
  </script>

</div>
