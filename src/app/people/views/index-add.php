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

?>

<div class="row">
  <div class="col">
    <button type="button" class="btn btn-block btn-outline-secondary" id="<?= $addBtn = strings::rand() ?>"><i class="bi bi-person-plus"></i> Add People</a>

  </div>

</div>

<script>
$(document).ready( () => $('#<?= $addBtn ?>').on( 'click', e => $(document).trigger( 'add-people')));
</script>
