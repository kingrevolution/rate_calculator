<?php
require_once 'config.php';

$mode = $_POST['mode'];
$country = $_POST['country'];
$prefix = $_POST['prefix'] . "%";
$current_page = empty($_POST['current_page']) ? 1 : $_POST['current_page'];
$limit = empty($_POST['limit']) ? $limit_of_rows : $_POST['limit'];

echo '<input type="hidden" id="current_page" value="' . $current_page . '" >';

if ($country != '0' || $prefix != '%') {
    try {
        $rate_client->__setSoapHeaders($auth_info);
        $offset = ($current_page - 1) * $limit;
        $GetRatesListRequest = array(
            'offset' => $offset,
            'i_tariff' => $tariff,
            'pager' => $limit,
            'type' => 'destination',
            'tmpl' => $prefix,
            'type1' => 'country',
            'tmpl1' => $country,
            'sortby' => $sort["by"],
            'direction' => $sort["direction"],
        );
        $GetRatesListResponse = $rate_client->get_rates_list($GetRatesListRequest);
        $total = $GetRatesListResponse->{'total'} ? $GetRatesListResponse->{'total'} : $limit;
        $items_from = 1;
        $items_to = $total;

        if ($total > 10) {
            $pages = ceil($total / $limit);
            $first = 1;
            $last = $pages;
            $items_from = $offset + 1;

            if ($current_page == $pages) {
                $items_to = $total;
            } else {
                $items_to = $offset + $limit;
            }

            if ($current_page > $pages) {
                $current_page = $pages;
            } elseif ($current_page < 1) {
                $current_page = 1;
            }

            if ($current_page - floor($pages_limit / 2) >= 1) {
                $first = $current_page - floor($pages_limit / 2);
            } else {
                $first = 1;
            }

            if ($pages > $first + ($pages_limit - 1)) {
                $last = $first + ($pages_limit - 1);
            } else {
                $last = $pages;
                if ($pages - $pages_limit - 1 > 1) {
                    $first = $pages - $pages_limit + 1;
                }
            }

            echo '<div class="row"><div class="col-sm-6"><div style="display: inline-block; float: left;"><label style="font-weight: normal;">Show <select id="limit" class="form-control" onChange="select_limit()" style="display: inline-block; width: 75px;">';
            foreach ($rates_limit as $value) {
                echo '<option value="' . $value . '"';
                if ($value == $limit) {
                    echo ' selected';
                }
                echo '>' . $value . '</option>';
            }
            echo '</select> entries</label></div></div></div>';
        }

        if ($total >= 1) {
            echo '<table cellpadding="0" cellspacing="20" border="0" class="table table-striped table-bordered table-condensed" id="rates_list"><thead><tr><th tabindex="0" rowspan="1" colspan="1" style="display: table-cell; text-align: left; vertical-align: middle;">Destination</th><th tabindex="0" rowspan="1" colspan="1" style="display: table-cell; text-align: left; vertical-align: middle;">Country</th><th tabindex="0" rowspan="1" colspan="1" style="display: table-cell; text-align: left; vertical-align: middle;">Price</th>';
            if ($mode == 1) {
                foreach ($rate_calc_minutes as $value) {
                    echo '<th tabindex="0" rowspan="1" colspan="1" style="display: table-cell; text-align: left; vertical-align: middle;"> Minutes for ' . $value . ' ' . $iso_4217 . '</th>';
                }
            }
            echo '</tr></thead><tbody>';

            foreach ($GetRatesListResponse->rates_list as $rate) {
                if ($rate->discontinued == 'Y') {
                    continue;
                }

                echo '<tr><td>' . $rate->destination . '</td><td>' . $rate->country . ' ' . $rate->description . '</td><td>' . $rate->price_n . '</td>';
                if ($mode == 1) {
                    foreach ($rate_calc_minutes as $value) {
                        if ($rate->price_n == 0) {
                            echo '<td class="center">' . 'unlimited minutes' . '</td>';
                        } else {
                            echo '<td class="center">' . round(($value / $rate->price_n), 0) . '</td>';
                        }
                    }
                    echo '</tr>';
                }
            }
            echo '</tbody></table>';

            echo '<div class="row"><div class="col-sm-6"><div style="float: left;"><label style="padding-top: 0.75em; font-weight: normal;">Showing ' . $items_from . ' to ' . $items_to . ' of ' . $total . ' entries</label></div></div>';
            if ($total > $limit) {
                echo '<div class="col-sm-6"><div style="float: right; padding-top: 0.25em; text-align: right;"><ul class="pagination" style="margin: 0;">';
                echo '<li class="paginate_button previous';
                if ($current_page == 1) {
                    echo ' disabled';
                }
                echo '"><a ';
                if ($current_page > 1) {
                    echo 'onClick="select_page(' . ($current_page - 1) . ')" ';
                }
                echo ' href="#">Previous</a></li>';

                for ($page = $first; $page <= $last; $page++) {
                    echo '<li class="paginate_button';
                    if ($page == $current_page) {
                        echo ' active';
                    }
                    echo '"><a onClick="select_page(' . $page . ')" href="#">' . $page . '</a></li>';
                }

                echo '<li class="paginate_button next';
                if ($current_page == $pages) {
                    echo ' disabled';
                }
                echo '"><a ';

                if ($current_page < $pages) {
                    echo 'onClick="select_page(' . ($current_page + 1) . ')" ';
                }
                echo 'href="#">Next</a></li></ul></div></div>';
            }

            echo '</div>';

        } else {
            echo '<div class="row"><div class="col-sm-6"><div style="float: left;"><label style="font-weight: normal;">No rates found.</label></div></div></div>';
        }

        $session->logout($session_id);

    } catch (SoapFault $e) {
    }

}
