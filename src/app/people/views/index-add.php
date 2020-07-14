<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/  ?>
<style>
.nav.flex-column > .nav-item > .nav-link > .fa {
	margin-left: -1rem; margin-right: 0.25rem; width: 1rem;

}
</style>
<div class="row">
    <div class="col">
        <button type="button" class="btn btn-block btn-outline-secondary" id="<?= $addBtn = strings::rand() ?>"><i class="fa fa-plus"></i> Add People</a>

    </div>

</div>

<script>
$(document).ready( () => {
    $('#<?= $addBtn ?>').on( 'click', e => {
        e.preventDefault();

        $(document).trigger( 'add-people');

    });

});
</script>