<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\property_diary;

use strings;  ?>

<div class="table-responsive">
  <table class="table table-sm" id="<?= $_table = strings::rand() ?>">
    <thead>
      <tr>
        <td class="text-center">#</td>
        <td>date</td>
        <td>event</td>
        <td>subject</td>
      </tr>
    </thead>
    <tbody>
    <?php
      while ( $dto = $this->data->res->dto()) {
        print '<tr>';
        print '<td class="text-center" line-number>#</td>';
        printf( '<td>%s</td>', strings::asLocalDate( $dto->date));
        printf( '<td>%s</td>', $dto->event);
        printf( '<td>%s</td>', $dto->subject);
        print '</tr>';

      }
    ?>
    </tbody>

  </table>

</div>
<script>
( _ => {
  $('#<?= $_table ?>')
  .on('update-line-numbers', function(e) {
    let t = 0;
    $('> tbody > tr:not(.d-none) >td[line-number]', this).each( ( i, e) => {
      $(e).data('line', i+1).html( i+1);
      t++;

    });

    $('> thead > tr >td[line-number]', this).html( t);

  })
  .trigger('update-line-numbers');

}) (_brayworth_);
</script>
