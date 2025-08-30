<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Helper\SSLCommerz;
use App\Models\CustomerProfile;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\ProductCart;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    // Create invoice from current user's cart and initiate SSLCommerz payment
    function InvoiceCreate(Request $request)
    {
        DB::beginTransaction();
        try {
            $userId = $request->header('id');
            $userEmail = $request->header('email');
            $tranId = uniqid();
            $Profile = CustomerProfile::where('user_id',$userId)->first();
            
            if (!$Profile) {
                return ResponseHelper::error('Customer profile not found', null, 400);
            }
            
            $cus_details = "Name:$Profile->cus_name,Address:$Profile->cus_address,City:$Profile->cus_city,Phone:$Profile->cus_phone";
            $ship_details = "Name:$Profile->ship_name,Address:$Profile->ship_address,City:$Profile->ship_city,Phone:$Profile->ship_phone";

            // Payable Calculation
            $total = 0;
            $cartList = ProductCart::where('user_id',$userId)->get();
            
            if ($cartList->isEmpty()) {
                return ResponseHelper::error('Cart is empty', null, 400);
            }
            
            foreach ($cartList as $item) { $total += $item->price; }

            $vat = round($total * 0.03,2);
            $payable = $total + $vat;

            $invoice = Invoice::create([
                'total'=>$total,
                'vat'=>$vat,
                'payable'=>$payable,
                'cus_details'=>$cus_details,
                'ship_details'=>$ship_details,
                'tran_id'=>$tranId,
                'delivery_status'=>'Pending',
                'payment_status'=>'Pending',
                'user_id'=>$userId
            ]);

            foreach ($cartList as $c) {
                InvoiceProduct::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $c->product_id,
                    'user_id'=>$userId,
                    'qty' =>  $c->qty,
                    'sale_price'=>  $c->price,
                ]);
            }

           $paymentMethod = SSLCommerz::InitiatePayment($Profile,$payable,$tranId,$userEmail);

           DB::commit();

           if ($paymentMethod === null) {
               Log::warning('SSLCommerz initiation returned null', ['tran_id'=>$tranId]);
               return ResponseHelper::error('Payment gateway not configured', null, 500);
           }

           // Normalize status
       $status = $paymentMethod['paymentMethod']['status'] ?? 'UNKNOWN'; // adapt to actual response structure
           if (strtoupper($status) !== 'SUCCESS') {
               Log::warning('SSLCommerz payment initiation failed', [
           'tran_id'=>$tranId,
                   'status'=>$status,
                   'reason'=>$paymentMethod['paymentMethod']['failedreason'] ?? null
               ]);
           }

           return ResponseHelper::success([
                    'payment' => $paymentMethod,
                    'totals' => [
                        'total' => $total,
                        'vat' => $vat,
                        'payable' => $payable,
                    ],
                    'status' => $status
                ], 'Invoice created');

        }
    catch (Exception $e) {
            DB::rollBack();
            // Log the actual error for debugging
            Log::error('Invoice creation failed: ' . $e->getMessage());
            return ResponseHelper::error($e->getMessage(), null, 500);
        }

    }


    function InvoiceList(Request $request){
        return Invoice::where('user_id',$request->header('id'))->get();
    }

    function InvoiceProductList(Request $request){
        return InvoiceProduct::where([
            'user_id'=>$request->header('id'),
            'invoice_id'=>$request->invoice_id
        ])->with('product')->get();
    }

    function PaymentSuccess(Request $request){
        SSLCommerz::InitiateSuccess($request->input('tran_id') ?? $request->query('tran_id'));
        return redirect('/profile');
    }


    function PaymentCancel(Request $request){
        SSLCommerz::InitiateCancel($request->input('tran_id') ?? $request->query('tran_id'));
        return redirect('/profile');
    }

    function PaymentFail(Request $request){
        SSLCommerz::InitiateFail($request->input('tran_id') ?? $request->query('tran_id'));
        return redirect('/profile');
    }

    function PaymentIPN(Request $request){
        return SSLCommerz::InitiateIPN($request->input('tran_id'),$request->input('status'),$request->input('val_id'));
    }

}
