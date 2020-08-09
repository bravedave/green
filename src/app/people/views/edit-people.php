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

use strings;
use green\baths\dao\bath_list;
use green\beds_list\dao\beds_list;

$dto = $this->data->dto;    ?>

<div id="<?= $_wrap = strings::rand() ?>">
    <form id="<?= $_form = strings::rand() ?>" autocomplete="off">
        <input type="hidden" name="action" value="save-people">
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
                                <input class="form-control" name="name" type="text"
                                    placeholder="name"
                                    value="<?= $dto->name ?>" />

                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-fw fa-mobile"></i></div>
                                    </div>

                                    <input class="form-control" name="mobile" type="text"
                                        placeholder="0418 .."
                                        value="<?= $dto->mobile ?>" />

                                </div>

                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-fw fa-phone"></i></div>
                                    </div>

                                    <input class="form-control" name="telephone" type="text"
                                        placeholder="tel .."
                                        value="<?= $dto->telephone ?>" />

                                </div>

                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-fw fa-at"></i></div>
                                    </div>

                                    <input class="form-control" name="email" type="text"
                                        placeholder="john@domain.tld"
                                        value="<?= $dto->email ?>" />

                                </div>

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
        $('#<?= $_modal ?>').on( 'shown.bs.modal', e => { $('#<?= $_wrap ?> input[name="name"]').focus(); });

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
