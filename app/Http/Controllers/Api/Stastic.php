<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bill_state;
use Carbon\Carbon;
use Illuminate\Support\Arr;
class Stastic extends Controller
{
   //
   //tong ke theo time
   public function stastic_time(Request $request)
   {
      $type_filter = $request->type_filter;
      //tìm theo khoảng
      if ($type_filter === "range") {
         $bills = DB::table("bills")
            ->whereBetween('date', [$request->first_date, $request->last_date])
            ->get();
         foreach ($bills as $bill) {
            $bill->state = DB::table("bill_states")
               ->where('id_bill', $bill->id)
               ->select("state")
               ->first();
            $bill->total_number = DB::table("bill_details")
               ->where('id_bill', $bill->id)
               ->selectRaw("sum(number) as total_number")
               ->groupBy("id_bill")
               ->first();
         }
         if (count($bills) === 0) {
            return response()->json([
               'code' => 401,
               'msg' => "Không có đơn hàng nào"
            ], 401);
         }
         return response()->json([
            'code' => 200,
            'msg' => "Tìm thành công",
            'data' => $bills
         ], 200);
      }
      //tìm theo ngày
      if ($type_filter === "today") {
         $bills = DB::table("bills")
            ->where('date', date("Y-m-d"))
            ->get();
         foreach ($bills as $bill) {
            $bill->state = DB::table("bill_states")
               ->where('id_bill', $bill->id)
               ->select("state")
               ->first();
            $bill->total_number = DB::table("bill_details")
               ->where('id_bill', $bill->id)
               ->selectRaw("sum(number) as total_number")
               ->groupBy("id_bill")
               ->first();
         }
         if (count($bills) === 0) {
            return response()->json([
               'code' => 401,
               'msg' => "Không có đơn hàng nào"
            ], 401);
         }
         return response()->json([
            'code' => 200,
            'msg' => "Tìm thành công",
            'data' => $bills
         ], 200);
      }
      //tìm theo tuần
      if ($type_filter === "week") {
         $UTC='Asia/Ho_chi_Minh';
         $today = Carbon::now($UTC);
         $dautuan = $today;
         $cuoituan=$today;
         //******* */
         $key = Carbon::now($UTC)->dayOfWeek; 
        if($key==0){
         $dautuan = $today->subDays(6);
         $cuoituan =  Carbon::now($UTC);
         
        }else{
         $dautuan = $today->subDays($key-1); 
         $cuoituan =  Carbon::now($UTC)->addDays(7-$key);
        }
         $first_week = $dautuan->format('Y-m-d');
         $last_week = $cuoituan->format('Y-m-d');
         $bills = DB::table("bills")
            ->whereBetween('date', [$first_week, $last_week])
            ->get();
         foreach ($bills as $bill) {
            $bill->state = DB::table("bill_states")
               ->where('id_bill', $bill->id)
               ->select("state")
               ->first();
            $bill->total_number = DB::table("bill_details")
               ->where('id_bill', $bill->id)
               ->selectRaw("sum(number) as total_number")
               ->groupBy("id_bill")
               ->first();
         }
         if (count($bills) === 0) {
            return response()->json([
               'code' => 401,
               'msg' => "Không có đơn hàng nào"
            ], 401);
         }
         return response()->json([
            'code' => 200,
            'msg' => "Tìm thành công",
            'data' => $bills
         ], 200);
      }
      if ($type_filter === "month") {
         $first_month = new Carbon('first day of this month');
         $last_month = new Carbon('last day of this month');
         $bills = DB::table("bills")
            ->whereBetween('date', [$first_month, $last_month])
            ->get();
         foreach ($bills as $bill) {
            $bill->state = DB::table("bill_states")
               ->where('id_bill', $bill->id)
               ->select("state")
               ->first();
            $bill->total_number = DB::table("bill_details")
               ->where('id_bill', $bill->id)
               ->selectRaw("sum(number) as total_number")
               ->groupBy("id_bill")
               ->first();
         }
         if (count($bills) === 0) {
            return response()->json([
               'code' => 401,
               'msg' => "Không có đơn hàng nào"
            ], 401);
         }
         return response()->json([
            'code' => 200,
            'msg' => "Tìm thành công",
            'data' => $bills
         ], 200);
      }
      if ($type_filter === "year") {

         $year_ = Carbon::now()->year;
         $first_year = Carbon::parse((string)$year_ . '-01-01')->format('Y-m-d');
         $last_year = Carbon::parse((string)$year_ . '-12-31')->format('Y-m-d');
         $bills = DB::table("bills")
            ->whereBetween('date', [$first_year, $last_year])
            ->get();
         foreach ($bills as $bill) {
            $bill->state = DB::table("bill_states")
               ->where('id_bill', $bill->id)
               ->select("state")
               ->first();
            $bill->total_number = DB::table("bill_details")
               ->where('id_bill', $bill->id)
               ->selectRaw("sum(number) as total_number")
               ->groupBy("id_bill")
               ->first();
         }
         if (count($bills) === 0) {
            return response()->json([
               'code' => 401,
               'msg' => "Không có đơn hàng nào"
            ], 401);
         }
         return response()->json([
            'code' => 200,
            'msg' => "Tìm thành công",
            'data' => $bills
         ], 200);
      }
      if ($type_filter === "chart") {
         $range = Carbon::now()->subDays(30);
         $stats = DB::table('bills')
            ->where('created_at', '>=', $range)
            //->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
               DB::raw('Date(created_at) as date'),
               //DB::raw('COUNT(*) as value')
            ]);
            // ->get();
         return $stats;
      }
   }
   public function statistic_customer(Request $request){
      $aray_user=DB::table("users")->select("id","email","name")->get();
       foreach ($aray_user as $key=>$user) {
          # code...
          $array_bill=DB::table("bills")
          ->where("id_user","like",$user->id)
          ->select("id_user" , DB::raw('sum(total_price) as quantity_price'))
          ->groupBy('id_user')
          ->first();
         if($array_bill)$user->quantity_price=$array_bill->quantity_price;
         else $user->quantity_price=0;
         $quantity_bill=DB::table("bills")
         ->where("id_user","like",$user->id)->count();
         $user->quantity_bill=$quantity_bill;
         $array_bill_state=DB::table("bill_states")
          ->where("id_user_order","like",$user->id)
          ->where("state","like","4")
          ->count();
          $user->quantity_cancel=$array_bill_state;
          //quantity_product
          $array_bill_quantity_product=DB::table("bills")
          ->where("id_user","like",$user->id)
          ->join("bill_details","bill_details.id_bill","=","bills.id")
         ->sum("number");
          $user->quantity_product=$array_bill_quantity_product;
       }
       //sort
      //  $sort=Arr::sort($aray_user);
      // //laravel
      // $sorted = sortByOrder($aray_user);
       
      return response()->json([
            'code' => 200,
            'msg' => "Tìm thành công",
            'data' => $aray_user
         ], 200);

   }
    public function statistic_product(Request $request){
      //theo số lượng
      //top3
      $aray_product=DB::table("products")
      ->select("id","id_type_details","number","size")
      ->get();
      foreach ($aray_product as $key=>$product) {
         $find=DB::table("bills")
         ->join("bill_details","bill_details.id_bill","=","bills.id")
         ->where("bill_details.id_product",$product->id)
         ->select("bill_details.id_product" , DB::raw('sum(number) as quantity_number'))
         ->groupBy('bill_details.id_product')
         ->first();
        if($find) $product->quantity_number=$find->quantity_number;
        else{$product->quantity_number=0;}
        $find2=DB::table("product_type_details")
       
        ->select("name","price","img")
        ->find($product->id_type_details);
        $product->detail= $find2;
      }
       
      return response()->json([
         'code' => 200,
         'msg' => "Thống kê theo khách hàng",
         'data' => $aray_product
      ], 200);

   }
    public function statistic_staff(Request $request){
      
   }
//    function sortByOrder($a, $b) {
//       return $a->quantity_product - $b->quantity_product;
//   }
  
}
