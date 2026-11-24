<style>
    .aj-v3-nav-category {
    display: flex;
    align-items: center;
    gap: 3px;               /* Gap between icon and text */
    flex-shrink: 0;
    text-decoration: none;
    padding: 2px 4px;       /* Space around category */
}

.aj-v3-nav-icon {
    flex-shrink: 0;
    font-size: 5px;        /* Icon size */
    line-height: 1;
}

.aj-v3-nav-text {
    display: inline-block;
    max-width: 65px;        /* Text width before ... */
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    font-size: 10px;        /* Text size */
    line-height: 1.2;
}
</style>
<?php

$categoryIcons = [
    'electronics'  => 'bi-phone',
    'fashion'      => 'bi-handbag',
    'grocery'      => 'bi-basket',
    'home'         => 'bi-house-door',
    'beauty'       => 'bi-droplet-half',
    'computers'    => 'bi-laptop',
    'sports'       => 'bi-dribbble',
    'furniture'    => 'bi-lamp',
    'automotive'   => 'bi-car-front',
    'books'        => 'bi-book',
    'pets'         => 'bi-paw',
    'toys'         => 'bi-controller',
    'garden'       => 'bi-flower1',
    'jewelry'      => 'bi-gem',
    'watches'      => 'bi-smartwatch',
    'arts_crafts'  => 'bi-palette',
    'baby_kids'    => 'bi-balloon',
    'health'       => 'bi-heart-pulse',
    'audio'        => 'bi-headphones',
    'tools'        => 'bi-tools',
    'other'        => 'bi-grid',

];

?>

<section class="aj-v3-category-nav">

    <div class="aj-v3-category-nav-inner" id="ajCategoryNav">

        <!-- Home -->
        <a href="<?= base_url() ?>" class="aj-v3-nav-category active">
            <span class="aj-v3-nav-icon">
                <i class="bi bi-stars"></i>
            </span>
            <span class="aj-v3-nav-text">For You</span>
        </a>

        <!-- Dynamic Categories -->
        <?php if (!empty($categories)): ?>

            <?php foreach ($categories as $category): ?>

                <?php
                    $slug = $category['slug'];

                    $icon = $categoryIcons[$slug] ?? 'bi-tag';
                ?>

                <a href="<?= base_url('products?category=' . rawurlencode($slug)) ?>"
                   class="aj-v3-nav-category">

                    <span class="aj-v3-nav-icon">
                        <i class="bi <?= esc($icon) ?>"></i>
                    </span>

                    <span class="aj-v3-nav-text">
                        <?= esc($category['name']) ?>
                    </span>

                </a>

            <?php endforeach; ?>

        <?php endif; ?>

        <!-- All Categories -->
        <!-- <a href="<?= base_url('categories') ?>" class="aj-v3-nav-category">

            <span class="aj-v3-nav-icon">
                <i class="bi bi-grid"></i>
            </span>

            <span class="aj-v3-nav-text">All Categories</span>

        </a> -->

    </div>

</section>
