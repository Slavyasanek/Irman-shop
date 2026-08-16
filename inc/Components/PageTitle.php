<?php

namespace CleanTheme\Components;


class PageTitle {
    public static function render(string $title = '') {
        if (!$title) return;

        ?>
            <div class="page-title bg--white-200">
                <div class="container page-title__container">
                    <h1 class="section-title page-title__title ff--title"><?= $title ?></h1>
                </div>
            </div>
        <?php
    }
}