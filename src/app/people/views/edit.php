<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\people;

use dvc\icon;
use strings;
use theme;
use green\baths\dao\bath_list;
use green\beds_list\dao\beds_list;

$dto = $this->data->dto;    ?>

<div id="<?= $_wrap = strings::rand() ?>">
  <form id="<?= $_form = strings::rand() ?>" autocomplete="off">
    <input type="hidden" name="action" value="save-people">
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
                <input class="form-control" name="name" type="text" placeholder="name" value="<?= $dto->name ?>">

              </div>

            </div>

            <div class="form-row mb-2">
              <div class="col">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><?= icon::get( icon::phone ) ?></div>
                  </div>

                  <input class="form-control" name="mobile" type="text" placeholder="0418 .." value="<?= $dto->mobile ?>">

                </div>

              </div>

            </div>

            <div class="form-row">
              <div class="col-12 col-md-6 mb-2">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><?= icon::get( icon::telephone ) ?></div>
                  </div>

                  <input class="form-control" name="telephone" type="text" placeholder="home .." value="<?= $dto->telephone ?>">

                </div>

              </div>

              <div class="col-12 col-md-6 mb-2">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">w</div>
                  </div>

                  <input class="form-control" name="telephone_business" type="text" placeholder="work .." value="<?= $dto->telephone_business ?>">

                </div>

              </div>

            </div>

            <div class="form-row mb-2">
              <div class="col">
                <div class="input-group">
                  <div class="input-group-prepend">
                      <div class="input-group-text">@</div>
                  </div>

                  <input class="form-control" name="email" type="text" placeholder="john@domain.tld" value="<?= $dto->email ?>">

                </div>

              </div>

            </div>

            <div class="form-row">
              <label class="col-12 col-md-2 col-form-label text-truncate pb-0">Address</label>
              <div class="col-12 col-md-10 mb-2">
                <input class="form-control" name="address_street" type="text" placeholder="street address" value="<?= $dto->address_street ?>">

              </div>

              <div class="offset-md-2 col-8 mb-2">
                <input class="form-control" name="address_suburb" type="text" placeholder="suburb" value="<?= $dto->address_suburb ?>">

              </div>

              <div class="col-4 col-md-2 mb-2">
                <input class="form-control" name="address_postcode" type="text" placeholder="postcode" value="<?= $dto->address_postcode ?>">

              </div>

            </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">cancel</button>
            <button type="submit" class="btn btn-primary">Save</button>

          </div>

        </div>

      </div>

    </div>

  </form>

  <script>
  ( _ => {

    if ('undefined' == typeof _.search) _.search = {};
    if ('undefined' == typeof _.search.address) {
      _.search.address = (request, response) => {
        _.post({
          url: _.url('<?= $this->route ?>'),
          data: {
            action: 'search-properties',
            term: request.term

          },

        }).then(d => response('ack' == d.response ? d.data : []));

      };

    }

    $('#<?= $_modal ?>').on( 'shown.bs.modal', e => { $('#<?= $_wrap ?> input[name="name"]').focus(); });

    $('input[name="address_street"]', '#<?= $_form ?>').autofill({
      autoFocus: true,
      source: _.search.address,
      select: ( e, ui) => {
        let o = ui.item;
        // console.log( o);
        $('input[name="address_suburb"]', '#<?= $_form ?>').val( o.suburb);
        $('input[name="address_postcode"]', '#<?= $_form ?>').val( o.postcode);

      },

    });

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
