<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="<?= lang('Pager.pageNavigation') ?>" class="inline-flex">
    <ul class="flex items-center -space-x-px">
        <?php if ($pager->hasPrevious()) : ?>
            <!-- First Page -->
            <li>
                <a href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>"
                    class="flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 transition-colors duration-200">
                    <i class="fas fa-angle-double-left"></i>
                </a>
            </li>
            <!-- Previous Page -->
            <li>
                <a href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>"
                    class="flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 transition-colors duration-200">
                    <i class="fas fa-angle-left"></i>
                </a>
            </li>
        <?php else: ?>
            <!-- Disabled First Page -->
            <li>
                <span class="flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-300 bg-gray-50 border border-gray-300 rounded-l-lg cursor-not-allowed">
                    <i class="fas fa-angle-double-left"></i>
                </span>
            </li>
            <!-- Disabled Previous Page -->
            <li>
                <span class="flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-300 bg-gray-50 border border-gray-300 cursor-not-allowed">
                    <i class="fas fa-angle-left"></i>
                </span>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li>
                <?php if ($link['active']) : ?>
                    <span aria-current="page"
                        class="flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 border border-blue-600 z-10">
                        <?= $link['title'] ?>
                    </span>
                <?php else : ?>
                    <a href="<?= $link['uri'] ?>"
                        class="flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 transition-colors duration-200">
                        <?= $link['title'] ?>
                    </a>
                <?php endif ?>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <!-- Next Page -->
            <li>
                <a href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>"
                    class="flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 transition-colors duration-200">
                    <i class="fas fa-angle-right"></i>
                </a>
            </li>
            <!-- Last Page -->
            <li>
                <a href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>"
                    class="flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 transition-colors duration-200">
                    <i class="fas fa-angle-double-right"></i>
                </a>
            </li>
        <?php else: ?>
            <!-- Disabled Next Page -->
            <li>
                <span class="flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-300 bg-gray-50 border border-gray-300 cursor-not-allowed">
                    <i class="fas fa-angle-right"></i>
                </span>
            </li>
            <!-- Disabled Last Page -->
            <li>
                <span class="flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-300 bg-gray-50 border border-gray-300 rounded-r-lg cursor-not-allowed">
                    <i class="fas fa-angle-double-right"></i>
                </span>
            </li>
        <?php endif ?>
    </ul>
</nav>