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

use theme;
use strings;    ?>

<nav class="<?= theme::navbar() ?>">
  <div class="navbar-brand mr-0"><?= $this->title ?></div>

  <form class="flex-fill my-2 my-sm-0 mx-2">
    <input class="form-control mr-sm-2"
      type="search" placeholder="Search" aria-label="Search"
      id="<?= $_uid = strings::rand() ?>" />

  </form>
  <script>
    ( _ => {
      $( '#<?= $_uid ?>')
      .on( 'keypress', e => /[a-z0-9\s\-\_\/\+@\.&']/i.test(e.key))
      .autofill({
        source : ( request, response) => {
          _.post({
            url : _.url(),
            data : {
              action : 'search',
              term : request.term

            },

          }).then( d => response( 'ack' == d.response ? d.data : []));

        },
        select : ( e, ui) => {
          let item = ui.item;
          if ( /^(properties|people)$/.test( item.type)) {
            if ( 'properties' == item.type) {
              _.get.modal( _.url('properties/edit/' + item.id));

            }
            else if ( 'people' == item.type) {
              _.get.modal( _.url('people/edit/' + item.id));

            }

            $( '#<?= $_uid ?>').val('').trigger('keyup');

          }

        }

      });

    }) (_brayworth_);
  </script>

  <button class="navbar-toggler" type="button" data-toggle="collapse"
    data-target="#<?= $_uid = strings::rand() ?>"
    aria-controls="<?= $_uid ?>" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>

  </button>

  <div class="collapse navbar-collapse flex-grow-0" id="<?= $_uid ?>">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="<?= strings::url() ?>"><i class="bi bi-house"></i></a>

      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="bi bi-gear"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?= strings::url('beds_list') ?>">Beds</a>
          <a class="dropdown-item" href="<?= strings::url('baths') ?>">Baths</a>
          <a class="dropdown-item" href="<?= strings::url('property_type') ?>">Property Type</a>
          <a class="dropdown-item" href="<?= strings::url('postcodes') ?>">Postcodes</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?= strings::url('people') ?>">People</a>
          <a class="dropdown-item" href="<?= strings::url('properties') ?>">Properties</a>

        </div>

      </li>

    </ul>

  </div>

</nav>
