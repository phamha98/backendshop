<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//SetUp
Route::post('setup_permission', 'Api\MediaFunction@setup_permission');
Route::post('setup_products', 'Api\MediaFunction@setup_products');
//post
Route::prefix('post_tags')->group(function () {
    Route::get('/load', 'Api\PostController@load');
    Route::post('/insert', 'Api\PostController@insert');
    Route::post('/delete', 'Api\PostController@delete'); //->middleware('verfiy-account');
    Route::post('/update', 'Api\PostController@update'); //->middleware('verfiy-account');

});

//wishlist
Route::prefix('wishlist')->group(function () {
    Route::post('/insert_wish', 'Api\Wish@insert_wish'); //->middleware('verfiy-account');
    Route::post('/load_number_product_wish', 'Api\Wish@load_number_product_wish');
    Route::post('/load_user_wish', 'Api\Wish@load_user_wish')->middleware('verfiy-account');

});
//Products_Customer
Route::prefix('products_customer')->group(function () {

    Route::get('/list_product', 'Api\ProductsCustomer@list_product');
    Route::get('/new_product', 'Api\ProductsCustomer@new_product');
    Route::get('/sale_product', 'Api\ProductsCustomer@sale_product');
    Route::post('/show_product', 'Api\ProductsCustomer@show_product');
    //Search Home
    Route::post('/filterproduct', 'Api\ProductsCustomer@filterproduct');
    Route::post('/search', 'Api\ProductsCustomer@search');
    Route::get('/list_type_goods', 'Api\ProductsCustomer@list_type_goods');
    Route::post('/sort_goods_gender', 'Api\ProductsCustomer@sort_goods_gender');
    Route::post('/main_goods_details', 'Api\ProductsCustomer@main_goods_details');
    Route::get('/list_post', 'Api\ProductsCustomer@list_post');
    //
    Route::post('setup_permission', 'Api\ProductsCustomer@setup_permission');
    Route::post('setup_products', 'Api\ProductsCustomer@setup_products');
});

//Bills_Customer
Route::group(
    ['prefix' => '/bills_customer', 'middleware' => ['verfiy-account']],
    function () {
        Route::post('/insert_fullbills', 'Api\BillsCustomer@insert_fullbills');
        Route::post('/show_billstate_user', 'Api\BillsCustomer@show_billstate_user');
        Route::post('/show_billdetail', 'Api\BillsCustomer@show_billdetail');
    }
);
// acount_Customer
Route::prefix('acount_customer')->group(function () {
    Route::post('/register', 'Api\AcountCustomer@register');
    Route::post('/login', 'Api\AcountCustomer@login');
    Route::post('/login_permision', 'Api\AcountCustomer@login_permision');
    Route::post('/refresh_token', 'Api\AcountCustomer@refresh_token');
    Route::delete('/delete_token', 'Api\AcountCustomer@delete_token');
});

//acount_Admin
Route::group(
    ['prefix' => '/acount_admin', 'middleware' => ['verfiy-account']],
    function () {
        Route::get('/list_user', 'Api\AcountAdmin@list_user');
        Route::post('/search_user_id', 'Api\AcountAdmin@search_user_id');
        Route::post('/search_user_name', 'Api\AcountAdmin@search_user_name');
        Route::get('/filter_user_birthday', 'Api\AcountAdmin@filter_user_birthday');
        Route::get('/filter_user_gender', 'Api\AcountAdmin@filter_user_gender');
        Route::post('/delete_user_id', 'Api\AcountAdmin@delete_user_id');
        Route::post('/update_acount', 'Api\AcountAdmin@update_acount');
        Route::post('/update_acount_pass', 'Api\AcountAdmin@update_acount_pass');
        Route::post('/show_acount', 'Api\AcountAdmin@show_acount');
        Route::post('/create_staff', 'Api\AcountAdmin@create');
    }
);

//order_admin
Route::group(
    ['prefix' => '/orderadmin', 'middleware' => ['verfiy-account']],
    function () {
        Route::post('/list_full_order', 'Api\OrderAdmin@list_full_order');
        Route::get('/show_bill_state', 'Api\OrderAdmin@show_bill_state');
        Route::post('/update_confirm', 'Api\OrderAdmin@update_confirm');
        Route::post('/update_transport', 'Api\OrderAdmin@update_transport');
        Route::post('/update_cancel', 'Api\OrderAdmin@update_cancel');
        Route::post('/search_bills', 'Api\OrderAdmin@search_bills_admin');
        Route::post('/search_by_nameuser', 'Api\OrderAdmin@search_by_nameuser');
        Route::post('/search_by_product', 'Api\OrderAdmin@search_by_product');
        Route::post('/search_by_date', 'Api\OrderAdmin@search_by_date');
        Route::post('/sort_by_price', 'Api\OrderAdmin@sort_by_price');
        Route::post('/sort_by_date', 'Api\OrderAdmin@sort_by_date');
        Route::post('/sort_by_number', 'Api\OrderAdmin@sort_by_number');
        Route::post('/bill_user_state', 'Api\OrderAdmin@bill_user_state');
    }
);

//GoodsAdmin
Route::group(
    ['prefix' => '/goods_admin', 'middleware' => ['verfiy-account']],
    function () {
        Route::get('/list_type_goods', 'Api\GoodsAdmin@list_type_goods');
        Route::post('/main_goods_details', 'Api\GoodsAdmin@main_goods_details');
        Route::get('/list_goods', 'Api\GoodsAdmin@list_goods');
        Route::post('/goods_details', 'Api\GoodsAdmin@goods_details');
        Route::post('/search_goods_name', 'Api\GoodsAdmin@search_goods_name');
        Route::post('/sort_goods_new', 'Api\GoodsAdmin@sort_goods_new');
        Route::post('/sort_goods_sale', 'Api\GoodsAdmin@sort_goods_sale');
        Route::post('/sort_goods_gender', 'Api\GoodsAdmin@sort_goods_gender');
        Route::post('/sort_goods_totalnumber', 'Api\GoodsAdmin@sort_goods_totalnumber');
        Route::post('/sort_goods_price', 'Api\GoodsAdmin@sort_goods_price');
        Route::post('/update_goods', 'Api\GoodsAdmin@update_goods');
        Route::post('/insert_goods', 'Api\GoodsAdmin@insert_goods');
        Route::post('/test', 'Api\GoodsAdmin@test');
        Route::post('/insert_type_main', 'Api\GoodsAdmin@insert_type_main');
        Route::post('/update_type_main', 'Api\GoodsAdmin@update_type_main');
        Route::post('/delete_product', 'Api\GoodsAdmin@delete_product');
        Route::post('/state_product', 'Api\GoodsAdmin@state_product');
    }
);
//Permission
Route::group(
    ['prefix' => '/permission_admin', 'middleware' => ['verfiy-account']],
    function () {
        Route::post('/list_all', 'Api\PermissionAdmin@list_all');
        Route::get('/list_staff', 'Api\PermissionAdmin@list_staff');
        Route::get('/list_group_user', 'Api\PermissionAdmin@list_group_user');
        Route::post('/list_permission_role', 'Api\PermissionAdmin@list_permission_role');
        Route::get('/list_permission', 'Api\PermissionAdmin@list_permission');
        Route::get('/list_role', 'Api\PermissionAdmin@list_role');
        Route::post('/listuser_group_role', 'Api\PermissionAdmin@listuser_group_role');
        Route::post('/delete_role_permission', 'Api\PermissionAdmin@delete_role_permission');
        Route::post('/insert_role_permission', 'Api\PermissionAdmin@insert_role_permission');
        Route::post('/insert_role', 'Api\PermissionAdmin@insert_role');
        Route::post('/delete_user_role', 'Api\PermissionAdmin@delete_user_role');
        Route::post('/insert_role_user', 'Api\PermissionAdmin@insert_role_user');
        Route::get('/hello', 'Api\PermissionAdmin@hello');
        Route::post('/upload', 'Api\PermissionAdmin@upload')->name("img");
        Route::get('/test_img', 'Api\PermissionAdmin@test_img');
        Route::get('/load_img', 'Api\PermissionAdmin@load_img');
        Route::post('/insert_img', 'Api\PermissionAdmin@insert_img');
    }
);

//Stastic
Route::prefix('stastic')->group(function () {
    Route::post('/time', 'Api\Stastic@stastic_time');
    Route::post('/customer', 'Api\Stastic@statistic_customer');
    Route::post('/product', 'Api\Stastic@statistic_product');
});
