<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use \Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');
    }

    public function index(Request $request)
    {
        return view('donations.index', [
            'donation_types' => Donation::TYPES,
            'statuses' => Donation::STATUSES,
            'donations' => Donation::filter([
                'donation_type' => $request->donation_type,
                'status' => $request->status,
                'search' => $request->search,
            ])->latest()->paginate($request->per_page ?? 10)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('donations.create', [
            'donation_types' => Donation::TYPES,
        ]);
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $donation = Donation::create([
                'donation_code' => 'SANDBOX-' . uniqid(),
                'donor_name' => $request->donor_name,
                'donor_email' => $request->donor_email,
                'donation_type' => $request->donation_type,
                'amount' => floatval($request->amount),
                'note' => $request->note,
            ]);

            $payload = [
                'transaction_details' => [
                    'order_id' => $donation->donation_code,
                    'gross_amount' => $donation->amount,
                ],
                'customer_details' => [
                    'first_name' => $donation->donor_name,
                    'email' => $donation->donor_email,
                ],
                'item_details' => [
                    [
                        'id' => $donation->donation_type,
                        'price' => $donation->amount,
                        'quantity' => 1,
                        'name' => ucwords(str_replace('_', '', $donation->donation_type)),
                    ],
                ],
            ];

            $snapToken = Snap::getSnapToken($payload);
            $donation->snap_token = $snapToken;
            $donation->save();

            $this->response['snap_token'] = $snapToken;
        });

        return response()->json($this->response);
    }

    public function notification()
    {
        $notification = new Notification();
        DB::transaction(function () use ($notification) {
            $transactionStatus = $notification->transaction_status;
            $paymentType = $notification->payment_type;
            $orderId = $notification->order_id;
            $fraudStatus = $notification->fraud_status;
            $donation = Donation::where('donation_code', $orderId)->first();

            if ($transactionStatus == 'capture') {
                if ($paymentType == 'credit_card') {
                    if ($fraudStatus == 'challenge') {
                        $donation->setStatusPending();
                    } else {
                        $donation->setStatusSuccess();
                    }
                }
            } elseif ($transactionStatus == 'settlement') {
                $donation->setStatusSuccess();
            } elseif ($transactionStatus == 'pending') {
                $donation->setStatusPending();
            } elseif ($transactionStatus == 'deny') {
                $donation->setStatusFailed();
            } elseif ($transactionStatus == 'expire') {
                $donation->setStatusExpired();
            } elseif ($transactionStatus == 'cancel') {
                $donation->setStatusFailed();
            }
        });

        return;
    }
}
