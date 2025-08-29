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
   
    function InvoiceCreate(Request $request)
    {
        DB::beginTransaction();
        try {

            //
            $user_id=$request->header('id');
            $user_email=$request->header('email');

            $tran_id=uniqid();
            $delivery_status='Pending';
            $payment_status='Pending';

            $Profile=CustomerProfile::where('user_id','=',$user_id)->first();
            
            if (!$Profile) {
                return ResponseHelper::Out('fail', 'Customer profile not found', 400);
            }
            
            $cus_details="Name:$Profile->cus_name,Address:$Profile->cus_address,City:$Profile->cus_city,Phone: $Profile->cus_phone";
            $ship_details="Name:$Profile->ship_name,Address:$Profile->ship_address,City:$Profile->ship_city,Phone: $Profile->ship_phone";

            // Payable Calculation
            $total=0;
            $cartList=ProductCart::where('user_id','=',$user_id)->get();
            
            if ($cartList->isEmpty()) {
                return ResponseHelper::Out('fail', 'Cart is empty', 400);
            }
            
            foreach ($cartList as $cartItem) {
                $total=$total+$cartItem->price;
            }

            $vat=($total*3)/100;
            $payable=$total+$vat;

            $invoice= Invoice::create([
                'total'=>$total,
                'vat'=>$vat,
                'payable'=>$payable,
                'cus_details'=>$cus_details,
                'ship_details'=>$ship_details,
                'tran_id'=>$tran_id,
                'delivery_status'=>$delivery_status,
                'payment_status'=>$payment_status,
                'user_id'=>$user_id
            ]);

            $invoiceID=$invoice->id;

            foreach ($cartList as $EachProduct) {
                InvoiceProduct::create([
                    'invoice_id' => $invoiceID,
                    'product_id' => $EachProduct['product_id'],
                    'user_id'=>$user_id,
                    'qty' =>  $EachProduct['qty'],
                    'sale_price'=>  $EachProduct['price'],
                ]);
            }

           $paymentMethod=SSLCommerz::InitiatePayment($Profile,$payable,$tran_id,$user_email);

           DB::commit();

           return ResponseHelper::Out('success',array(['paymentMethod'=>$paymentMethod,'payable'=>$payable,'vat'=>$vat,'total'=>$total]),200);

        }
        catch (Exception $e) {
            DB::rollBack();
            // Log the actual error for debugging
            Log::error('Invoice creation failed: ' . $e->getMessage());
            return ResponseHelper::Out('fail', $e->getMessage(), 500);
        }

    }


    function InvoiceList(Request $request){
        $user_id=$request->header('id');
        return Invoice::where('user_id',$user_id)->get();
    }

    function InvoiceProductList(Request $request){
        $user_id=$request->header('id');
        $invoice_id=$request->invoice_id;
        return InvoiceProduct::where(['user_id'=>$user_id,'invoice_id'=>$invoice_id])->with('product')->get();
    }

    function PaymentSuccess(Request $request){
        $tran_id = $request->input('tran_id') ?? $request->query('tran_id');
        SSLCommerz::InitiateSuccess($tran_id);
        return redirect('/profile');
    }


    function PaymentCancel(Request $request){
        $tran_id = $request->input('tran_id') ?? $request->query('tran_id');
        SSLCommerz::InitiateCancel($tran_id);
        return redirect('/profile');
    }

    function PaymentFail(Request $request){
        $tran_id = $request->input('tran_id') ?? $request->query('tran_id');
        SSLCommerz::InitiateFail($tran_id);
        return redirect('/profile');
    }

    function PaymentIPN(Request $request){
        return SSLCommerz::InitiateIPN($request->input('tran_id'),$request->input('status'),$request->input('val_id'));
    }

}
