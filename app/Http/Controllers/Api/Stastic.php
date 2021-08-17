<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bill_state;
use Carbon\Carbon;

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
         $dt = Carbon::now('Asia/Ho_Chi_Minh');
         $ngaycuatuan = Carbon::now()->dayOfWeek; //ngày của tuần
         $dautuan = $dt->subDays($ngaycuatuan - 1);
         $first_week = $dautuan->format('Y-m-d');
         $cuoituan = $dautuan->addDays(6);
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
         echo $first_month = new Carbon('first day of this month');
         echo $last_month = new Carbon('last day of this month');
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
      //theo doanh so
      $bills=DB::table("bills")->get();
      return response()->json([
            'code' => 200,
            'msg' => "Tìm thành công",
            'data' => $bills
         ], 200);

      //theo số lượng
      //top 3

   }
    public function statistic_product(Request $request){
      //theo số lượng
      //top3
   }
    public function statistic_staff(Request $request){
      
   }
}
