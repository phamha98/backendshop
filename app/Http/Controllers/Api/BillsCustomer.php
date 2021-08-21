<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bills;
use App\Bill_details;
use App\Bill_state;
use App\Session_user;
use App\Product;
use App\User;
use App\product_type_details;
use Illuminate\Support\Facades\DB;

class BillsCustomer extends Controller
{

    //insertfullbill
    //Đặt hàng hóa xử lý 3 bảng,chèn bill,billstate,trừ đi product
    public function insert_fullbills(Request $request)
    {

        DB::beginTransaction();
        try {
            $bill = new Bills; //in
            $bill->id_user = $request->input("id_user");
            $bill->date = date('Y-m-d H:i:s');
            $bill->total_price = $request->input('total_price');
            $bill->save();
            $data = $request->input('data');
            for ($i = 0; $i < count($data); $i++) {
                $bill_details = new Bill_details; //in
                $bill_details->id_bill = $bill->id;
                $string = "data." . (string)$i;;
                $bill_details->id_product = $request->input($string . ".id_product");
                $bill_details->number = $request->input($string . ".number");
                $bill_details->price = $request->input($string . ".price");
                $bill_details->save();

                $product_f = Product::find($bill_details->id_product);
                if ($product_f->number < $bill_details->number) {
                    DB::rollBack();
                    return response()->json([
                        'code' => 401,
                        'msg' => "Sản phẩm hiện tại hết"
                    ], 401);
                }
                $product_f->number = $product_f->number - $bill_details->number;
                $product_f->save(); //in
            }
            $billstate = new Bill_state; //in
            $billstate->id_bill = $bill->id;
            $billstate->state = '1';
            $billstate->id_user_order = $request->id_user; //ID_USER LÀ NGƯỜI ĐẶT HÀNG

            $billstate->save();
            DB::commit(); 
            return response()->json([
                'code' => 200,
                'message' => "sucess",
                //'data'=>count($data)
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code' => 401,
                'msg' => "Lỗi không order được" . $e,
            ], 401);
        }
    }
    //showstateuserdetail
    public function show_billdetail(Request $request)
    {

        $bills = DB::table('bills')->where('id', $request->id_bill)->first();
        $state = DB::table('bill_states')->where('id_bill', $request->id_bill)->first();
        $bills->state = $state->state;

        $bills_details = DB::table('bill_details')->where('id_bill', $request->id_bill)->get();
        $data_show = array();
        foreach ($bills_details as $children) {
            $product = Product::find($children->id_product);
            $product_details = product_type_details::find($product->id_type_details);
            $img = $product_details->img;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                $product_details->img = asset('storage/imagetypemain/' . $img);
            }
            $children->product = $product;
            $children->product_details = $product_details;
            array_push($data_show, $children);
        }
        return response()->json([
            'code' => 200,
            'bills_details' => $data_show,
            'bills' =>  $bills,
        ], 200);
    }
    //store
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $bill = new Bills;
            $bill->id_user = $request->id_user;
            $bill->name = $request->name;
            $bill->date = $request->date;
            DB::table("bills")->insert([
                "name" => $request->name,
                "id_user" => $request->id_user,
                "date" => $request->date

            ]);
            $check = DB::table('bills')->where('name', 'like', $request->name)->first();
            $info = $request->input('info');
            for ($i = 0; $i < count($info); $i++) {
                $billdetails = new Bill_details;
                $billdetails->id_bill = $check->id;
                ///
                $string = "info." . (string)$i;;
                $billdetails->id_product = $request->input($string . ".id_product");
                $billdetails->number = $request->input($string . ".number");
                $billdetails->price = $request->input($string . ".price");
                DB::table("bill_details")->insert([
                    "id_bill" => $billdetails->id_bill,
                    "id_product" => $billdetails->id_product,
                    "number" => $billdetails->number,
                    "price" => $billdetails->price
                ]);
            }
            $billstate = new Bill_state;
            $billstate->id_bill = $check->id;
            $billstate->state = $request->state;
            $billstate->id_user = $request->id_user;
            DB::table("bill_states")->insert([
                "id_bill" => $billstate->id_bill,
                "state" =>  $billstate->state,
                "id_user" => $billstate->id_user
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code' => 401,
                'message' => "Không chèn được" . $e,
            ], 401);
        }
    }
    //showstateuser
    public function show_billstate_user(Request $request)
    {

        $bills1 = DB::table('bills')->where('id_user', $request->id_user)->get();
        $billconfirm = array();
        $billtransport = array();
        $billsuccess = array();
        $billcancel = array();
        foreach ($bills1 as $bills) {
            $bill_id = $bills->id;
            $staterow = DB::table('bill_states')
                ->where('id_bill', $bill_id)
                ->first();
            $staterow->total_price = $bills->total_price;
            $staterow->date = $bills->date;
            if ($staterow->state == 1) array_push($billconfirm, $staterow);
            if ($staterow->state == 2) array_push($billtransport, $staterow);
            if ($staterow->state == 3) array_push($billsuccess, $staterow);
            if ($staterow->state == 4) array_push($billcancel, $staterow);
        }
        return response()->json([
            'code' => 200,
            'billconfirm' =>  $billconfirm,
            'billtransport' =>  $billtransport,
            'billsuccess' =>  $billsuccess,
            'billcancel' =>  $billcancel,

        ], 200);
    }
}
