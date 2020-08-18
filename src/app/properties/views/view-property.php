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

<h5><?= $this->title ?></h5>

<div class="form-group row">
  <div class="col"><?= $dto->address_street ?></div>

</div>

<div class="form-group row">
  <div class="col-md-6"><?= $dto->address_suburb ?></div>
  <div class="col-md-3 pt-3 pt-md-0"><?= $dto->address_state ?></div>
  <div class="col-md-3 pt-3 pt-md-0"><?= $dto->address_postcode ?></div>

</div>

<div class="form-group row">
  <div class="col pr-1"><div class="form-control"><?= $dto->description_type ?></div></div>
  <div class="col px-1">
    <div class="input-group">
      <div class="input-group-prepend">
          <div class="input-group-text"><i class="fa fa-bed"></i></div>
      </div>

      <div class="form-control"><?= $dto->description_beds ?></div>

    </div>

  </div>

  <div class="col px-1">
    <div class="input-group">
      <div class="input-group-prepend">
          <div class="input-group-text"><i class="fa fa-bath"></i></div>
      </div>

      <div class="form-control"><?= $dto->description_bath ?></div>

    </div>

  </div>

  <div class="col px-1">
    <div class="input-group">
      <div class="input-group-prepend">
          <div class="input-group-text"><i class="fa fa-bath"></i></div>
      </div>

      <div class="form-control"><?= $dto->description_car ?></div>

    </div>

  </div>

</div>

<div class="form-group row">
  <div class="col small text-muted">
    <?php
    if ( $dto->id > 0 && strtotime( $dto->updated) > 0) {
        printf( 'last update: %s', rtrim( date( 'D, d M Y H:ia', strtotime( $dto->updated)),'m'));

    }   ?>

  </div>

</div>

