<?php

namespace App\Http\Controllers;

use App\Coupon;
use App\Order;
use App\Plan;
use App\UserCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe;

class StripePaymentController extends Controller
{
    public function payment($code)
    {
        if(Auth::user()->type != 'admin')
        {
            $planID = \Illuminate\Support\Facades\Crypt::decrypt($code);
            $plan   = Plan::find($planID);
            if($plan)
            {
                return view('plans.payment', compact('plan'));
            }
            else
            {
                return redirect()->back()->with('error', __('Plan is deleted.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function stripePost(Request $request)
    {
        $objUser = \Auth::user();
        if($objUser->type != 'admin')
        {
            $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->code);
            $plan   = Plan::find($planID);

            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:120',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('payment', $request->code)->with('error', $messages->first());
            }

            if($plan)
            {
                try
                {
                    $price = $plan->price;
                    if(!empty($request->coupon))
                    {
                        $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                        if(!empty($coupons))
                        {
                            $usedCoupun     = $coupons->used_coupon();
                            $discount_value = ($plan->price / 100) * $coupons->discount;
                            $price          = $plan->price - $discount_value;

                            if($usedCoupun >= $coupons->limit)
                            {
                                return redirect()->back()->with('error', __('This coupon code has expired.'));
                            }
                        }
                        else
                        {
                            return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                        }
                    }

                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                    if($price > 0.0)
                    {
                        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                        $data = Stripe\Charge::create(
                            [
                                "amount" => 100 * $price,
                                "currency" => strtolower(env('CURRENCY_CODE')),
                                "source" => $request->stripeToken,
                                "description" => " Plan - " . $plan->name,
                                "metadata" => ["order_id" => $orderID],
                            ]
                        );
                    }
                    else
                    {
                        $data['amount_refunded'] = 0;
                        $data['failure_code']    = '';
                        $data['paid']            = 1;
                        $data['captured']        = 1;
                        $data['status']          = 'succeeded';
                    }

                    if($data['amount_refunded'] == 0 && empty($data['failure_code']) && $data['paid'] == 1 && $data['captured'] == 1)
                    {
                        Order::create(
                            [
                                'order_id' => $orderID,
                                'name' => $request->name,
                                'card_number' => $data['payment_method_details']['card']['last4'],
                                'card_exp_month' => $data['payment_method_details']['card']['exp_month'],
                                'card_exp_year' => $data['payment_method_details']['card']['exp_year'],
                                'plan_name' => $plan->name,
                                'plan_id' => $plan->id,
                                'price' => $price,
                                'price_currency' => $data['currency'],
                                'txn_id' => $data['balance_transaction'],
                                'payment_type' => 'STRIPE',
                                'payment_status' => $data['status'],
                                'receipt' => $data['receipt_url'],
                                'user_id' => $objUser->id,
                            ]
                        );

                        if(!empty($request->coupon))
                        {
                            $userCoupon         = new UserCoupon();
                            $userCoupon->user   = $objUser->id;
                            $userCoupon->coupon = $coupons->id;
                            $userCoupon->order  = $orderID;
                            $userCoupon->save();
                            $usedCoupun = $coupons->used_coupon();
                            if($coupons->limit <= $usedCoupun)
                            {
                                $coupons->is_active = 0;
                                $coupons->save();
                            }
                        }

                        if($data['status'] == 'succeeded')
                        {
                            $assignPlan = $objUser->assignPlan($plan->id);
                            if($assignPlan['is_success'])
                            {
                                return redirect()->route('profile')->with('success', __('Plan activated Successfully!'));
                            }
                            else
                            {
                                return redirect()->route('profile')->with('error', __($assignPlan['error']));
                            }
                        }
                        else
                        {
                            return redirect()->route('profile')->with('error', __('Your Payment has failed!'));
                        }
                    }
                    else
                    {
                        return redirect()->route('profile')->with('error', __('Transaction has been failed!'));
                    }
                }
                catch(\Exception $e)
                {
                    return redirect()->route('profile')->with('error', __($e->getMessage()));
                }
            }
            else
            {
                return redirect()->route('profile')->with('error', __('Plan is deleted.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
