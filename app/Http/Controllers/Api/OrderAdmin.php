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

class OrderAdmin extends Controller
{
    public function list_full_order(Request $request)
    {

        DB::beginTransaction();
        try {
            //Start
            //return Bills::all();
            $bill_states = Bill_state::all();
            $bill_state_display = array();
            foreach ($bill_states as $bill_state) {
                // $id_bill=$bill_state->id_bill;
                $bill_state->bill_details = $bill = Bills::find($bill_state->id_bill);
                //user
                $user = User::find($bill_state->id_user_order)->first();
                $img = $user->img;
                if (!filter_var($img, FILTER_VALIDATE_URL)) {
                    $user->img = asset('storage/imagetypemain/' . $img);
                }
                $bill_state->user_order = $user;
                //
                if (!empty($bill_state->id_user_confirm)) {
                    $user = User::find($bill_state->id_user_confirm)->first();
                    $img = $user->img;
                    if (!filter_var($img, FILTER_VALIDATE_URL)) {
                        $user->img = asset('storage/imagetypemain/' . $img);
                    }
                    $bill_state->user_confirm = $user;
                } else  $bill_state->user_confirm = null;
                if (!empty($bill_state->id_user_transport)) {
                    $user = User::find($bill_state->id_user_transport)->first();
                    $img = $user->img;
                    if (!filter_var($img, FILTER_VALIDATE_URL)) {
                        $user->img = asset('storage/imagetypemain/' . $img);
                    }
                    $bill_state->user_transport = $user;
                } else  $bill_state->user_transport = null;
                array_push($bill_state_display, $bill_state);
            }
            DB::commit();
            return response()->json([
                'code' => 200,
                'bill_state_display' => $bill_state_display,

            ], 200);
        } //end else
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code' => 401,
                'message' => "khong lay dc",
                //'data'=>count($data)
            ], 401);
        }
    }

    //$RECYCLE.BIN
    //HIỆN RA CÁC BILLSTATE THEO TRẠNG THÁI ALL ADMIN
    public  function show_bill_state(Request $request)
    {

        $billconfirm = array();
        $billtransport = array();
        $billcancel = array();
        $billsuccess = array();
        $staterows = DB::table('bill_states')->get();
        foreach ($staterows as $staterow) {
            $bill = Bills::find($staterow->id_bill);
            $staterow->bill_details  =  $bill;
            $user = User::find($staterow->id_user_order);
            $img = $user->img;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                $user->img = asset('storage/imagetypemain/' . $img);
            }
            $staterow->user_order =  $user;

            if (!empty($staterow->id_user_confirm)) {
                $user = User::find($staterow->id_user_confirm);
                $img = $user->img;
                if (!filter_var($img, FILTER_VALIDATE_URL)) {
                    $user->img = asset('storage/imagetypemain/' . $img);
                }
                $staterow->user_confirm = $user;
            } else  $staterow->user_confirm = null;
            if (!empty($staterow->id_user_transport)) {
                $user = User::find($staterow->id_user_transport);
                $img = $user->img;
                if (!filter_var($img, FILTER_VALIDATE_URL)) {
                    $user->img = asset('storage/imagetypemain/' . $img);
                }
                $staterow->user_transport = $user;
            } else  $staterow->user_transport = null;

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
    //UPDATE CONFIRM ;STATE+USER_CONFIRM
    public function update_confirm(Request $request)
    {

        $state1 = Bill_state::where('id_bill', $request->id_bill)->first();
        $state1->id_user_confirm = $request->id_user_confirm;
        $state1->state = "2";
        $state1->save();
        return response()->json([
            'code' => 200,
        ], 200);
    }
    //UP DATE UPDATE TRANSPORT ;STATE+USER_TRANSPORT
    public function update_transport(Request $request)
    {

        $state1 = Bill_state::where('id_bill', $request->id_bill)->first();
        $state1->id_user_transport = $request->id_user_transport;
        $state1->state = "3";
        $state1->save();
        return response()->json([
            'code' => 200,

        ], 200);
    }
    public function update_cancel(Request $request)
    {


        try {
            $state1 = Bill_state::where('id_bill', $request->id_bill)->first();
            $state1->id_user_cancel = $request->id_user_cancel;
            $state1->state = "4";
            $state1->save();

            return response()->json([
                'code' => 200,
                'msg' => "Đã hủy đơn hàng thành công"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 401,
                'msg' => "Lỗi không hủy được đơn hàng" . $e,
            ], 401);
        }
    }
    //search by id
    public function search_bills_admin(Request $request)
    {

        $staterow = DB::table('bill_states')->where('id_bill', $request->id_bill)->first();
        $bill = Bills::find($staterow->id_bill);
        $staterow->bill_details  =  $bill;
        $user = User::find($staterow->id_user_order);
        $img = $user->img;
        if (!filter_var($img, FILTER_VALIDATE_URL)) {
            $user->img = asset('storage/imagetypemain/' . $img);
        }
        $staterow->user_order =  $user;
        if (!empty($staterow->id_user_confirm)) {
            $user = User::find($staterow->id_user_confirm);
            $img = $user->img;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                $user->img = asset('storage/imagetypemain/' . $img);
            }
            $staterow->user_confirm = $user;
        } else  $staterow->user_confirm = null;
        if (!empty($staterow->id_user_transport)) {
            $user = User::find($staterow->id_user_transport);
            $img = $user->img;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                $user->img = asset('storage/imagetypemain/' . $img);
            }
            $staterow->user_transport = $user;
        } else  $staterow->user_transport = null;
        return response()->json([
            'code' => 200,
            'staterow' =>  $staterow,
        ], 200);
    }
    public function search_by_nameuser(Request $request)
    {
        $staterows = DB::table('bill_states')->where('id_user_order', $request->id_user_order)->get();
        $staterow_show = array();
        foreach ($staterows as $staterow) {
            $bill = Bills::find($staterow->id_bill);
            $staterow->bill_details  =  $bill;
            $user = User::find($staterow->id_user_order);
            $img = $user->img;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                $user->img = asset('storage/imagetypemain/' . $img);
            }
            $staterow->user_order =  $user;

            if (!empty($staterow->id_user_confirm)) {
                $user = User::find($staterow->id_user_confirm);
                $img = $user->img;
                if (!filter_var($img, FILTER_VALIDATE_URL)) {
                    $user->img = asset('storage/imagetypemain/' . $img);
                }
                $staterow->user_confirm = $user;
            } else  $staterow->user_confirm = null;
            if (!empty($staterow->id_user_transport)) {
                $user = User::find($staterow->id_user_transport);
                $img = $user->img;
                if (!filter_var($img, FILTER_VALIDATE_URL)) {
                    $user->img = asset('storage/imagetypemain/' . $img);
                }
                $staterow->user_transport = $user;
            } else  $staterow->user_transport = null;
            array_push($staterow_show, $staterow);
        }


        return response()->json([
            'code' => 200,
            'data' => $staterow_show

        ], 200);
    }
    public function search_by_product(Request $request)
    {

        $staterows = DB::table('bill_states')
            ->join('bills', 'bills.id', '=', 'bill_states.id_bill')
            ->join('bill_details', 'bill_details.id_bill', '=', 'bills.id')
            ->join('products', 'products.id', '=', 'bill_details.id_product')
            ->join('product_type_details', 'product_type_details.id', '=', 'products.id_type_details')
            ->where('product_type_details.id', $request->id_product_details)
            ->select(
                'bill_states.id',
                'bill_states.id_bill',
                'bill_states.id_bill',
                'bill_states.id_user_order',
                'bill_states.id_user_confirm',
                'bill_states.id_user_transport'
            )
            ->groupBy(
                'bill_states.id',
                'bill_states.id_bill',
                'bill_states.id_bill',
                'bill_states.id_user_order',
                'bill_states.id_user_confirm',
                'bill_states.id_user_transport'
            )
            ->get();;

        foreach ($staterows as  $staterow) {
            $staterow->bill_details = Bills::find($staterow->id_bill);
            $staterow->user_order = User::find($staterow->id_user_order);
            $staterow->user_confirm = User::find($staterow->id_user_confirm);
            $staterow->user_transport = User::find($staterow->id_user_transport);
        }
        return response()->json([
            'code' => 200,
            'data' => $staterows
        ], 200);
    }
    //
    public function search_by_date(Request $request)
    {
        $staterows = DB::table('bill_states')
            ->join('bills', 'bills.id', '=', 'bill_states.id_bill')
            ->whereBetween('date', [$request->date_left, $request->date_right])
            ->get();;

        foreach ($staterows as  $staterow) {
            $staterow->bill_details = Bills::find($staterow->id_bill);
            $staterow->user_order = User::find($staterow->id_user_order);
            $staterow->user_confirm = User::find($staterow->id_user_confirm);
            $staterow->user_transport = User::find($staterow->id_user_transport);
        }
        return response()->json([
            'code' => 200,
            'data' => $staterows
        ], 200);
    }
    public function sort_by_price(Request $request)
    {


        $staterows = DB::table('bill_states')
            ->join('bills', 'bills.id', '=', 'bill_states.id_bill')
            ->orderBy('total_price', $request->type)
            ->get();;
        foreach ($staterows as  $staterow) {
            $staterow->bill_details = Bills::find($staterow->id_bill);
            $staterow->user_order = User::find($staterow->id_user_order);
            $staterow->user_confirm = User::find($staterow->id_user_confirm);
            $staterow->user_transport = User::find($staterow->id_user_transport);
        }
        return response()->json([
            'code' => 200,
            'data' =>  $staterows
        ], 200);
    }
    public function sort_by_date(Request $request)
    {


        $staterows = DB::table('bill_states')
            ->join('bills', 'bills.id', '=', 'bill_states.id_bill')
            ->orderBy('date', $request->type)
            ->get();;
        foreach ($staterows as  $staterow) {
            $staterow->bill_details = Bills::find($staterow->id_bill);
            $staterow->user_order = User::find($staterow->id_user_order);
            $staterow->user_confirm = User::find($staterow->id_user_confirm);
            $staterow->user_transport = User::find($staterow->id_user_transport);
        }
        return response()->json([
            'code' => 200,
            'data' =>  $staterows
        ], 200);
    }
    //sap xep theoso luong sp
    public function sort_by_number(Request $request)
    {

        $staterows_find = DB::table('bill_states')
            ->join('bills', 'bills.id', '=', 'bill_states.id_bill')
            ->join('bill_details', 'bill_details.id_bill', '=', 'bills.id')
            ->select('bill_details.id_bill', DB::raw('sum(number) as total_number'))
            ->groupBy('bill_details.id_bill')
            ->orderBy('total_number', $request->type)
            ->get();;
        $staterows = array();
        foreach ($staterows_find as  $staterow_find) {
            $staterow = Bill_state::where("id_bill", $staterow_find->id_bill)->first();
            $staterow->bill_details = Bills::find($staterow_find->id_bill);
            $staterow->user_order = User::find($staterow->id_user_order);
            $staterow->user_confirm = User::find($staterow->id_user_confirm);
            $staterow->user_transport = User::find($staterow->id_user_transport);
            $staterow->total_number = $staterow_find->total_number;
            array_push($staterows, $staterow);
        }
        return response()->json([
            'code' => 200,
            'data' =>  $staterows
        ], 200);
    }
    public function bill_user_state(Request $request)
    {

        $staterows = DB::table('bill_states')
            ->where("id_user_confirm", $request->id_user)
            ->orWhere("id_user_transport", $request->id_user)
            ->get();
        foreach ($staterows as $staterow) {
            $staterow->total_number = DB::table("bill_details")
                ->selectRaw(' sum(number) as total_number')
                ->where("id_bill", $staterow->id_bill)
                ->first();
            $staterow->bill_details = Bills::find($staterow->id_bill)
                ->first();
            $staterow->user_order = User::find($staterow->id_user_order);
        }
        return response()->json([
            'code' => 200,
            'data' =>  $staterows
        ], 200);
    }
}
