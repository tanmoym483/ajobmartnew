
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
                <i class="bi bi-house"></i>
            </span>
            <span class="aj-v3-nav-text">Home</span>
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
