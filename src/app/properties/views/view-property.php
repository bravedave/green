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
use green\property_type\dao\property_type;
use green\baths\dao\bath_list;

$dto = $this->data->dto;    ?>

<h5 class="d-none d-print-block"><?= $this->title ?></h5>

<div class="row mb-1">
  <div class="col"><?php
    printf( '<div><strong>%s</strong></div>', $dto->address_street);

    $a = [];

    if ( $dto->address_suburb) $a[] = $dto->address_suburb;
    if ( $dto->address_state) $a[] = $dto->address_state;
    if ( $dto->address_postcode) $a[] = $dto->address_postcode;

    if ( $a) {
      printf( '<div>%s</div>', implode( ' ', $a));

    }

  ?></div>

</div>

<div class="form-row mb-1">
  <div class="col-auto">
    <input type="text" readonly class="form-control-plaintext" value="<?= $dto->description_type ?>">

  </div>

  <div class="col-auto">
    <div class="input-group">
      <div class="input-group-prepend">
          <div class="input-group-text"><i class="fa fa-bed"></i></div>
      </div>

      <div class="form-control"><?= $dto->description_beds ?></div>

    </div>

  </div>

  <div class="col-auto">
    <div class="input-group">
      <div class="input-group-prepend">
          <div class="input-group-text"><i class="fa fa-bath"></i></div>
      </div>

      <div class="form-control"><?= $dto->description_bath ?></div>

    </div>

  </div>

  <div class="col-auto">
    <div class="input-group">
      <div class="input-group-prepend">
        <div class="input-group-text"><i class="fa fa-bath"></i></div>

      </div>

      <div class="form-control"><?= $dto->description_car ?></div>

    </div>

  </div>

</div>

<div class="form-row mt-4">
  <div class="col form-text text-muted font-italic">
    <?php
    if ( $dto->id > 0 && strtotime( $dto->updated) > 0) {
      printf( '<small>last update: %s</small>', rtrim( date( 'D, d M Y H:ia', strtotime( $dto->updated)),'m'));

    }   ?>

  </div>

</div>

