<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Checkout extends Component
{
    public int $step = 1;

    // Form fields
    public string $customerName = '';
    public string $phoneNumber = '';
    public string $shippingAddress = '';
    public string $orderDate = '';
    public string $deliveryTime = '11:00'; 
    public string $notes = '';
    
    // Payment selection
    public string $paymentGroup = 'transfer'; 
    public string $paymentMethod = 'BCA'; 
    public string $paymentType = 'lunas'; 

    // Delivery method & Auto Shipping
    public string $deliveryMethod = 'diambil'; 
    public float $distance = 0; 
    public float $shippingFee = 0;

    // Koordinat Dapur Mama Delisha (Polanharjo, Klaten)
    const STORE_LAT = -7.605652; 
    const STORE_LNG = 110.638520;

    // Titik awal peta pelanggan
    public ?float $latitude = -7.605652;
    public ?float $longitude = 110.638520;

    public array $cartItems = [];
    public float $total = 0;
    public ?Order $createdOrder = null;

    public function mount()
    {
        $user = Auth::user();
        $this->customerName = $user ? $user->name : '';
        $this->phoneNumber = $user ? ($user->phone ?? '') : '';
        $this->shippingAddress = $user ? ($user->address ?? '') : '';
        
        $this->cartItems = session('cart', []);

        if (empty($this->cartItems)) {
            return redirect()->route('cart');
        }

        foreach ($this->cartItems as $item) {
            $minQty = $item['min_qty'] ?? 1;
            if ($item['qty'] < $minQty) {
                return redirect()->route('cart');
            }
        }

        $this->total = collect($this->cartItems)->sum(fn($i) => $i['price'] * $i['qty']);
        $this->orderDate = now()->addDay()->format('Y-m-d');
    }

    public function updatedLatitude($value)
    {
        $this->calculateDistanceAndFee();
    }

    public function updatedLongitude($value)
    {
        $this->calculateDistanceAndFee();
    }

    public function updatedDeliveryMethod($value)
    {
        if ($value === 'diambil') {
            $this->shippingFee = 0;
            $this->distance = 0;
        } else {
            $this->calculateDistanceAndFee();
            $this->dispatch('initMap');
        }
    }

    private function calculateDistanceAndFee()
    {
        if ($this->deliveryMethod !== 'diantar') {
            $this->shippingFee = 0;
            return;
        }

        $lat1 = self::STORE_LAT;
        $lon1 = self::STORE_LNG;
        $lat2 = $this->latitude;
        $lon2 = $this->longitude;

        $earthRadius = 6371; 

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
             
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distanceKm = $earthRadius * $c;

        $this->distance = round($distanceKm, 1);

        $freeKm = 5;
        $tariffPerKm = 5000;
        $roundedKm = (int) floor($distanceKm);
        $chargeableKm = max(0, $roundedKm - $freeKm);
        $this->shippingFee = $chargeableKm * $tariffPerKm;
    }

    public function goToPayment()
    {
        $this->validate([
            'customerName' => 'required|string|max:255',
            'phoneNumber' => 'required|string|max:20',
            'shippingAddress' => 'required|string|max:1000',
            'orderDate' => 'required|date|after_or_equal:today',
            'deliveryTime' => 'required|string',
        ]);
        $this->step = 2;
    }

    public function backToDetails() { $this->step = 1; }

    public function selectPaymentGroup(string $group)
    {
        $this->paymentGroup = $group;
        $this->paymentMethod = ($group === 'transfer') ? 'BCA' : 'COD';
        if ($group === 'cod') $this->paymentType = 'lunas';
    }

    public function selectTransferSub(string $sub)
    {
        $this->paymentGroup = 'transfer';
        $this->paymentMethod = $sub;
    }

    public function placeOrder()
    {
        if ($this->step === 1) {
            $this->goToPayment();
            return;
        }

        if (empty($this->cartItems) && !$this->createdOrder) {
            $this->dispatch('notify', message: 'Keranjang belanja kosong!', type: 'error');
            return;
        }

        if (!in_array($this->paymentMethod, ['transfer', 'cod'], true)) {
            $this->dispatch('notify', message: 'Silakan pilih metode pembayaran terlebih dahulu!', type: 'error');
            return;
        }

        if ($this->deliveryMethod === 'diambil') {
            $this->shippingFee = 0;
        }

        $grandTotal = $this->total + $this->shippingFee;
        $isDp = ($this->paymentGroup !== 'cod' && $this->paymentType === 'dp');

        $finalPayAmount = $isDp ? $grandTotal * 0.5 : $grandTotal;

        $typeLabel = $isDp ? ' (DP 50%)' : ' (Lunas)';
        $methodLabel = $this->paymentMethod . $typeLabel;
        $formattedDeliveryTime = $this->deliveryTime . ' WIB';

        // PERBAIKAN: Menentukan status pembayaran agar jelas di admin (tidak terbaca gagal/batal)
        if ($this->paymentGroup === 'cod') {
            $paymentStatus = 'Belum Dibayar (COD)';
        } elseif ($isDp) {
            $paymentStatus = 'DP 50% (Menunggu Pelunasan)';
        } else {
            $paymentStatus = 'Menunggu Konfirmasi Lunas';
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'order_code' => 'ORD-' . strtoupper(Str::random(8)),
            'order_date' => $this->orderDate,
            'delivery_time' => $formattedDeliveryTime,
            'recipient_name' => $this->customerName,
            'phone_number' => $this->phoneNumber,
            'shipping_address' => $this->shippingAddress . ' [Pengiriman: ' . ucfirst($this->deliveryMethod) . ($this->deliveryMethod === 'diantar' ? ' - Jarak Otomatis: ' . $this->distance . ' KM' : '') . ']',
            'total_price' => $finalPayAmount,
            'payment_method' => $methodLabel,
            'payment_status' => $paymentStatus, // Status yang sudah diperjelas
            'status' => 'Menunggu',
            'notes' => trim($this->notes . ($this->shippingFee > 0 ? ' | Ongkir: Rp ' . number_format($this->shippingFee, 0, ',', '.') : '')),
            'latitude' => $this->deliveryMethod === 'diantar' ? $this->latitude : null,
            'longitude' => $this->deliveryMethod === 'diantar' ? $this->longitude : null,
        ]);

        foreach ($this->cartItems as $item) {
            OrderDetail::create([
                'order_id'   => $order->id,
                'menu_id'    => $item['id'] ?? null,   
                'product_id' => $item['id'] ?? null,   
                'qty'        => $item['qty'],
                'price'      => $item['price'],
                'total'      => $item['price'] * $item['qty'],
            ]);
        }

        $this->createdOrder = $order;
        session()->forget('cart');
        $this->cartItems = [];
        $this->total = 0;
        $this->dispatch('cartUpdated', count: 0);
        $this->dispatch('cartCleared');
        $this->step = 3;
    }

    public function render()
    {
        $activeShippingFee = ($this->deliveryMethod === 'diambil') ? 0 : $this->shippingFee;

        return view('livewire.customer.checkout', [
            'cartItems' => $this->cartItems,
            'total' => $this->total,
            'shippingFee' => $activeShippingFee,
            'distance' => $this->distance,
            'deliveryMethod' => $this->deliveryMethod,
            'grandTotal' => $this->total + $activeShippingFee,
        ])->layout('layouts.customer');
    }
}