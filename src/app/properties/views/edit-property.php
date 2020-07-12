<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

    $dto = $this->data->dto;    ?>

<form id="<?= $_form = strings::rand() ?>">
    <input type="hidden" name="action" value="save-property">
    <input type="hidden" name="id" value="<?= $dto->id ?>">

    <div class="modal fade" tabindex="-1" role="dialog" id="<?= $_modal = strings::rand() ?>" aria-labelledby="<?= $_modal ?>Label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
                            <input type="text" class="form-control" name="address_street"
                                placeholder="Street Address"
                                value="<?= $dto->address_street ?>">

                        </div>

                    </div>

                    <div class="form-group row">
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="address_suburb"
                                placeholder="Suburb"
                                value="<?= $dto->address_suburb ?>">

                        </div>

                        <div class="col-md-4">
                            <input type="text" class="form-control" name="address_postcode"
                                placeholder="P/Code"
                                value="<?= $dto->address_postcode ?>">

                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready( () => {

        $('#<?= $_modal ?>').on( 'hidden.bs.modal', e => { $('#<?= $_form ?>').remove(); })
        $('#<?= $_modal ?>').modal( 'show');

        $('#<?= $_form ?>')
        .on( 'submit', function( e) {
            let _form = $(this);
            let _data = _form.serializeFormJSON();
            let _modalBody = $('.modal-body', _form);

            ( _ => {
                _.post({
                    url : _.url('<?= $this->route ?>'),
                    data : _data,

                }).then( function( d) {
                    if ( 'ack' == d.response) {
                        $('#<?= $_modal ?>').trigger( 'success');
                        $('#<?= $_modal ?>').modal( 'hide');

                    }
                    else {
                        _brayworth_.growl( d);

                    }

                });

            })(_brayworth_);

            return false;

        });

    });
    </script>

</form>
