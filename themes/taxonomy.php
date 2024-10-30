<?php
/*
 * @package Inwave Charity
 * @version 1.0.0
 * @created Dec 1, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://www.inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

/**
 * Description of taxonomy
 *
 * @developer duongca
 */
get_header();
$get_term = get_term_by('slug', get_query_var('infunding_category'), 'infunding_category');
?>
<div class="iw-container">
    <?php
    echo do_shortcode('[infunding_list category="' . $get_term->term_id . '" desc_text_limit="15" limit="3" page="category"]');
    ?>
</div>
<?php
get_footer();
