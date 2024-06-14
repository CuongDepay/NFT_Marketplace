<?php

namespace App\Http\Services\Checkout;

use App\Models\CartDetail;
use App\Models\Coupon;
use App\Models\NFT;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutService
{
    public function createCheckout($data)
    {
        return DB::transaction(function () use ($data) {
            // Find the coupon by code
            $coupon = null;
            if (!empty($data['code'])) {
                $coupon = Coupon::where('code', $data['code'])->first();
                if (!$coupon) {
                    throw new \Exception('Invalid coupon code');
                }
            }

            // Create the order
            $order = Order::create([
                'user_id' => $data['user_id'],
                'coupon_id' => $coupon ? $coupon->id : null,
                'shipping' => $data['shipping'],
                'tax' => $data['tax'],
                'company_name' => $data['company_name'],
                'order_notes' => $data['order_notes']
            ]);

            foreach ($data['items'] as $item) {
                // Check quantity in cart
                $cartDetail = CartDetail::where('user_id', $data['user_id'])
                    ->where('nft_id', $item['nft_id'])
                    ->first();

                if (!$cartDetail || $cartDetail->quantity < $item['quantity']) {
                    throw new \Exception('Insufficient quantity in cart');
                }

                // Create order detail
                OrderDetail::create([
                    'order_id' => $order->id,
                    'nft_id' => $item['nft_id'],
                    'quantity' => $item['quantity'],
                ]);

                // Reduce the quantity in the NFT table
                $nft = NFT::find($item['nft_id']);
                if ($nft->quantity < $item['quantity']) {
                    throw new \Exception('Insufficient quantity in stock');
                }
                $nft->decrement('quantity', $item['quantity']);

                // Remove the item from the cart
                CartDetail::where('user_id', $data['user_id'])
                    ->where('nft_id', $item['nft_id'])
                    ->delete();
            }
            return $order;
        });
    }
}
