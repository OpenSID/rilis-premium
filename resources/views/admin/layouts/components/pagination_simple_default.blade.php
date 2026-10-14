<?php if ($paginator->hasPages()) : ?>
<nav>
    <ul class="pagination">
        <!-- Previous Page Link -->
        <?php if ($paginator->onFirstPage()) : ?>
        <li class="disabled" aria-disabled="true"><span><?= __('pagination.previous') ?></span></li>
        <?php else : ?>
        <li><a href="<?= $paginator->previousPageUrl() ?>" rel="prev"><?= __('pagination.previous') ?></a></li>
        <?php endif ?>

        <!-- Next Page Link -->
        <?php if ($paginator->hasMorePages()) : ?>
        <li><a href="<?= $paginator->nextPageUrl() ?>" rel="next"><?= __('pagination.next') ?></a></li>
        <?php else : ?>
        <li class="disabled" aria-disabled="true"><span><?= __('pagination.next') ?></span></li>
        <?php endif ?>
    </ul>
</nav>
<?php endif ?>
