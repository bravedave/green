<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green;

use dvc\icon;
use strings;    ?>

<nav class="navbar navbar-expand-sm navbar-light bg-light">
    <div class="navbar-brand"><?= $this->title ?></div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>

    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?= strings::url() ?>"><?= icon::get( icon::house ) ?> <span class="sr-only">(current)</span></a>

            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Maintenance
                </a>

                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="<?= strings::url('beds_list') ?>">Beds</a>
                    <a class="dropdown-item" href="<?= strings::url('baths') ?>">Baths</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?= strings::url('people') ?>">People</a>
                    <a class="dropdown-item" href="<?= strings::url('properties') ?>">Properties</a>
                </div>

            </li>

            <li class="nav-item">
                <button class="btn" id="<?= $_uid = strings::rand() ?>"><i class="fa fa-search"></i></button>
                <script>
                $(document).ready( () => {
                    $('#<?= $_uid ?>').on( 'click', function( e) {
                        ( _ => {
                            _.post({
                                url : _.url('/'),
                                data : {
                                    action : 'search',
                                    term : 'dil'
                                },

                            }).then( d => {
                                    console.log( d);
                                if ( 'ack' == d.response) {
                                }
                                else {
                                    _.growl( d);

                                }

                            });
                        }) (_brayworth_);

                    })

                });

                </script>

            </li>

        </ul>

        <form class="form-inline my-2 my-sm-0">
            <input class="form-control mr-sm-2" type="search"
                placeholder="Search" aria-label="Search"
                id="<?= $_uid = strings::rand() ?>" />

        </form>
        <script>
        ( _ => {
            $( '#<?= $_uid ?>').on( 'keypress', ( e) => {
                if ( !/[a-z0-9\s\-\_\/\+@\.&']/i.test(e.key)) {
		            //~ console.log( e);
                    return false;

                }

            })
            .autofill({
                source : ( request, response) => {
                    _.post({
                        url : _.url(''),
                        data : {
                            action : 'search',
                            term : request.term

                        },

                    }).then( d => response( 'ack' == d.response ? d.data : []));

                },
                select : ( e, ui) => {
                    let item = ui.item;
                    if ( 'properties' == item.type) {
                        _.get( _.url('properties/edit/' + item.id))
                        .then( html => $(html).appendTo( 'body'));

                        $( '#<?= $_uid ?>').val('').trigger('keyup');

                    }

                }

            });

        }) (_brayworth_);
        </script>

    </div>

</nav>