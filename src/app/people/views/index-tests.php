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
  <div class="nav-item h6 mt-4 mb-0 pl-3">Tests</div>
  <div class="nav-item">
    <a class="nav-link" href="<?= strings::url( $this->route) ?>/tests/find">QuickPerson::find</a>
    <div class="text-muted font-italic pl-3">Finds John Citizen, john@citizens.tld</div>

  </div>

  <div class="nav-item">
    <a class="nav-link" href="<?= strings::url( $this->route) ?>/tests/harvest">QuickPerson::harvest</a>
    <div class="text-muted font-italic pl-3">Harvests John Citizen, 0418 767676</div>

  </div>

</nav>
