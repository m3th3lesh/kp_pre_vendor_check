<?php

use Tygh\Registry;
/**
 *
 * @author Kartpay.com <support@kartpay.com>
 * @since Sep 25, 2016
 */
function fn_settings_variants_addons_kp_pre_vendor_check_open_status() {
    $statuses = fn_get_statuses();
    $data = array();
    foreach ($statuses as $k => $status) {
        $data[$k] = $status['description'];
    }
    return $data;
}

function fn_settings_variants_addons_kp_pre_vendor_check_confirmed_status() {
    $statuses = fn_get_statuses();
    $data = array();
    foreach ($statuses as $k => $status) {
        $data[$k] = $status['description'];
    }
    return $data;
}

function fn_settings_variants_addons_kp_pre_vendor_check_unconfirmed_status() {
    $statuses = fn_get_statuses();
    $data = array();
    foreach ($statuses as $k => $status) {
        $data[$k] = $status['description'];
    }
    return $data;
}

function fn_kp_pre_vendor_check_change_order_status(&$status_to, $status_from, $order_info, $force_notification, $order_statuses, $place_order) {
    $stOp = Registry::get('addons.kp_pre_vendor_check.open_status');
    $stCo = Registry::get('addons.kp_pre_vendor_check.confirmed_status');
    $stUnCo = Registry::get('addons.kp_pre_vendor_check.unconfirmed_status');
    if( $status_to==$stCo || ($status_to==$stUnCo && $status_from==$stCo) ) {
        db_query(
            "update ?:users set is_confirmed=?s where user_id=?i",
            $status_to==$stCo ? 'Y' : 'N',
            $order_info['user_id']
        );
    }
}


function fn_kp_pre_vendor_check_order_placement_routines($order_id, $force_notification, $order_info, $_error) {
    $stOp = Registry::get('addons.kp_pre_vendor_check.open_status');
    $stCo = Registry::get('addons.kp_pre_vendor_check.confirmed_status');
    if( $_error==false ) {
        if( $order_info['status']==$stOp ) {
            $isConfirmed = db_get_field("select is_confirmed from ?:users where user_id=?i", $order_info['user_id']);
            if( $isConfirmed=='Y') {
                fn_change_order_status($order_id, $stCo);
            }
        }
    }
}