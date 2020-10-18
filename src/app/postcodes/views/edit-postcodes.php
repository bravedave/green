<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\postcodes;

use strings;

$dto = $this->data->dto;    ?>

<div id="<?= $_wrap = strings::rand() ?>">
    <form id="<?= $_form = strings::rand() ?>" autocomplete="off">
        <input type="hidden" name="action" value="save-postcodes">
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
                        <div class="form-group row">
                            <div class="col">
                                <input type="text" class="form-control" name="suburb"
                                    placeholder="suburb"
                                    value="<?= $dto->suburb ?>">

                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="state"
                                    placeholder="state"
                                    value="<?= $dto->state ?>">

                            </div>

                            <div class="col-md-4 mt-3 mt-md-0">
                                <input type="text" class="form-control" name="postcode"
                                    placeholder="postcode"
                                    value="<?= $dto->postcode ?>">

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer py-1">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>

                    </div>

                </div>

            </div>

        </div>

    </form>

    <script>
    ( _ => {
        $('#<?= $_modal ?>').on( 'shown.bs.modal', e => { $('#<?= $_form ?> input[name="suburb"]').focus(); });

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
