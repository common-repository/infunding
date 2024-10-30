<?php

/*
 * @package Inwave Funding
 * @version 1.0.0
 * @created Dec 1, 2016
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://www.inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

/**
 * Description of taxonomy-infunding_category
 *
 * @developer duongca
 */
if (inFundingGetTemplatePath('infunding/taxonomy')) {
    include inFundingGetTemplatePath('infunding/taxonomy');
} else {
    $taxonomy_path = INFUNDING_THEME_PATH . 'taxonomy.php';
    if (file_exists($taxonomy_path)) {
        include_once $taxonomy_path;
    } else {
        echo esc_html__('No file exists', 'incharity');
    }
}
