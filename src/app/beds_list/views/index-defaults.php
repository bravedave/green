<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/  ?>

<nav class="nav flex-column">
  <div class="nav-item"><a href="#" id="<?= $_uid = strings::rand()  ?>">Create Default</a></div>

</nav>
<script>
( _ => {
  $('#<?= $_uid ?>').on( 'click', function( e) {
    e.stopPropagation();e.preventDefault();

    let _me = $(this);
    _me.addClass( 'd-none');
    _me.parent().append('<span>importing <span class="spinner-grow spinner-grow-sm"></span></span>');

    _.post({
      url : _.url('<?= $this->route ?>'),
      data : { action : 'create-default-set' },

    }).then( d => {
      if ( 'ack' == d.response) {
        window.location.reload();

      }
      else {
        _.growl( d);

      }

    });

  });

}) (_brayworth_);
</script>
