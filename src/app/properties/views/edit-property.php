<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\properties;

use strings;
use green\baths\dao\bath_list;
use green\beds_list\dao\beds_list;

$dto = $this->data->dto;    ?>

<div id="<?= $_wrap = strings::rand() ?>">
    <form id="<?= $_form = strings::rand() ?>" autocomplete="off">
        <input type="hidden" name="action" value="save-property">
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

                            <div class="col-md-4 pt-3 pt-md-0">
                                <input type="text" class="form-control" name="address_postcode"
                                    placeholder="P/Code"
                                    value="<?= $dto->address_postcode ?>">

                            </div>

                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-bed"></i></div>
                                    </div>

                                    <select class="form-control" name="description_beds">
                                        <option value=""></option>
                                        <?php
                                        $_dao = new beds_list;
                                        if ( $_res = $_dao->getAll()) {
                                            while ( $_dto = $_res->dto()) {
                                                printf( '<option value="%s" %s>%s</option>',
                                                    $_dto->beds,
                                                    $dto->description_beds == $_dto->beds ? 'selected' : '',
                                                    $_dto->description);

                                            }

                                        }   ?>

                                    </select>

                                </div>

                            </div>

                            <div class="col">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-bath"></i></div>
                                    </div>

                                    <select class="form-control" name="description_bath">
                                        <option value=""></option>
                                        <?php
                                        $_dao = new bath_list;
                                        if ( $_res = $_dao->getAll()) {
                                            while ( $_dto = $_res->dto()) {
                                                printf( '<option value="%s" %s>%s</option>',
                                                    $_dto->bath,
                                                    $dto->description_bath == $_dto->bath ? 'selected' : '',
                                                    $_dto->description);

                                            }

                                        }   ?>

                                    </select>

                                </div>

                            </div>

                            <div class="col">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-car"></i></div>
                                    </div>

                                    <select class="form-control" name="description_car">
                                        <option value=""></option>
                                        <?php
                                        for ($i=1; $i <= 6; $i++) {
                                            printf( '<option value="%s" %s>%s</option>',
                                                $i,
                                                $dto->description_car == $i ? 'selected' : '',
                                                $i);

                                        }   ?>
                                    </select>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer py-1">
                        <?php
                        if ( $dto->id > 0 && strtotime( $dto->updated) > 0) {
                            printf( '<div class="mr-auto small text-muted">last update: %s</div>', rtrim( date( 'D, d M Y H:ia', strtotime( $dto->updated)),'m'));

                        }   ?>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>

                    </div>

                </div>

            </div>

        </div>

    </form>

    <script>
    $(document).ready( () => {

        ( _ => {
            $('#<?= $_modal ?>').on( 'hidden.bs.modal', e => { $('#<?= $_wrap ?>').remove(); });
            $('#<?= $_modal ?>').on( 'shown.bs.modal', e => { $('#<?= $_form ?> input[name="address_street"]').focus(); });
            $('#<?= $_modal ?>').modal( 'show');

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
                        _brayworth_.growl( d);

                    }

                });

                return false;

            });

            $('input[name="address_suburb"]', '#<?= $_form ?>').autofill({
                source : ( request, response) => {
                    _.post({
                        url : _.url(''),
                        data : {
                            action : 'search-postcode',
                            term : request.term

                        },

                    }).then( d => response( 'ack' == d.response ? d.data : []));

                },
                select : ( e, ui) => {
                    let item = ui.item;
                    $('input[name="address_postcode"]', '#<?= $_form ?>').val(item.postcode);

                }

            });

        })(_brayworth_);

    });
    </script>

</div>