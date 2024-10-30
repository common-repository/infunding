<?php

/*
 * @package Inwave Funding
 * @version 1.0.0
 * @created May 20, 2016
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of inFundingPaging
 *
 * @developer duongca
 */
class inFundingPaging {

    public function findStart($limit) {
        if ((!isset($_GET['pagenum'])) || ($_GET['pagenum'] == '1')) {
            $start = 0;
            $_GET['pagenum'] = 1;
        } else {
            $start = ($_GET['pagenum'] - 1) * $limit;
        }
        return $start;
    }

    /*     * *******************************************************************************
     * Hàm int findPages (int count, int limit)
     * Hàm trả về số lượng trang cần thiết dựa trên tổng số dòng có trong Table và limit
     * ******************************************************************************** */

    public function findPages($count, $limit) {
        $page = (($count % $limit ) == 0) ? $count / $limit : floor($count / $limit) + 1;
        return $page;
    }

    /*     * **********************************************************************************
     * Hàm string pageList (int curpage, int pages)
     * Trả về danh sách trang theo định dạng "Trang đầu tiên <[Các trang]> Trang cuối cùng"
     * *********************************************************************************** */

    public function pageList($curpage, $pages, $num_pages=4) {

        //Nếu tổng sô trang nhỏ hơn hoặc bằng 1 thì sẽ không hiển thị phân trang
        if ($pages <= 1) {
            return '';
        }

        $page_list = '<div class="pagination">';

        //In ra liên kết về trang đầu tiên
//        if (($curpage != 1) && ($curpage)) {
//            $page_list .= " <a href=\"" . $this->checkURI() . "1\" title=\"Trang đầu\">Trang đầu</a>";
//        }
        //In ra liên kết về trang trước
        if (($curpage - 1) > 0) {
            $page_list .= " <a href=\"" . $this->checkURI() . ($curpage - 1) . "\" title=\"Prev page\">←prev</a>";
        } else {
            $page_list .= '<span class="disabled">←prev</span>';
        }

        //In ra danh sách các trang và làm cho trang hiên tại đậm hơn mà mất gạch chân
        if ($num_pages >= $pages) {
            for ($i = 1; $i <= $pages; $i++) {
                if ($i == $curpage) {
                    $page_list .= '<span class="current">' . $i . ' </span>';
                } else {
                    $page_list .= " <a href=\"" . $this->checkURI() . $i . "\" title=\"Page " . $i . "\">" . $i . "</a>";
                }
                $page_list .= "";
            }
        } else {
            $n = floor($num_pages / 2);
            $i = $curpage - $n;
            if ($i <= 0)
                $i = 1;
            if ($curpage + $n <= $num_pages) {
                for ($i; $i <= $num_pages; $i++) {
                    if ($i == $curpage) {
                        $page_list .= '<span class="current">' . $i . ' </span>';
                    } else {
                        $page_list .= " <a href=\"" . $this->checkURI() . $i . "\" title=\"Page " . $i . "\">" . $i . "</a>";
                    }

                    $page_list .= "";
                }
            } else {
                if ($curpage <= $pages - $n) {
                    for ($i; $i <= $curpage + $n; $i++) {
                        if ($i == $curpage) {
                            $page_list .= '<span class="current">' . $i . ' </span>';
                        } else {
                            $page_list .= " <a href=\"" . $this->checkURI() . $i . "\" title=\"Page " . $i . "\">" . $i . "</a>";
                        }
                        $page_list .= "";
                    }
                } else {
                    for ($i = $pages - $num_pages + 1; $i <= $pages; $i++) {
                        if ($i == $curpage) {
                            $page_list .= '<span class="current">' . $i . ' </span>';
                        } else {
                            $page_list .= " <a href=\"" . $this->checkURI() . $i . "\" title=\"Page " . $i . "\">" . $i . "</a>";
                        }
                        $page_list .= "";
                    }
                }
            }
        }

        //In link của trang tiếp theo
        if (($curpage + 1) <= $pages) {
            $page_list .= " <a href=\"" . $this->checkURI() . ($curpage + 1) . "\" title=\"Next page\">next→</a>";
        } else {
            $page_list .= '<span class="disabled">next→</span>';
        }


        //In ra liên kết đến trang cuối cùng
//        if (($curpage != $pages) && ($pages != 0)) {
//            $page_list .= " <a href=\""  . $this->checkURI() . $pages . "\" title=\"Trang Cuối\">Trang cuối</a>";
//        }
        $page_list .= "<div style=\"clear:both;\"></div>";
        $page_list .= "</div>";
        printf($page_list);
    }

    /*     * ************************************************
     * Hàm string nextPrev (int curpage, int pages)
     * Return "Privious | Next"
     * ************************************************* */

    public function nextPrev($curpage, $pages) {
        $next_prev = "";


        if (($curpage - 1) <= 0) {
            $next_prev .= __("Prev page", 'inwavethemes');
        } else {
            $next_prev .= "<a href=\"" . $this->checkURI() . ($curpage - 1) . "\">" . __('Prev page', 'inwavethemes') . "</a>";
        }
        $next_prev .= " | ";

        if (($curpage + 1) <= 0) {
            $next_prev .= __("Next page", 'inwavethemes');
        } else {
            $next_prev .= "<a href=\"" . $this->checkURI() . ($curpage + 1) . "\">".__("Next page", 'inwavethemes')."</a>";
        }

        return $next_prev;
    }

    public function checkURI() {
        $uri = '';
        if (isset($_REQUEST['pagenum'])) {
            $arr_p = explode('=', $_SERVER['REQUEST_URI']);
            $new_arr = '';
            for ($i = 0; $i < count($arr_p) - 1; $i++) {
                $new_arr[$i] = $arr_p[$i];
            }
            $uri = implode("=", $new_arr) . '=';
        } else {
            $count = count(explode('?', $_SERVER['REQUEST_URI']));
            if ($count == 1)
                $uri = $_SERVER['REQUEST_URI'] . '?pagenum=';
            else
                $uri = $_SERVER['REQUEST_URI'] . '&pagenum=';
        }
        return $uri;
    }

}
