<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/  ?>

<script>
  (_ => $(document).ready(() => {
    _.get
      .modal(_.url('<?= $this->route ?>/edit/<?= $this->data->id ?>'))
      .then(m => m.on('success', e => _.nav('<?= $this->route ?>')));

  }))(_brayworth_);
</script>
