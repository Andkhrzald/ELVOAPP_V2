<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tangkap status dari URL (misal: ?status=pending). Jika tidak ada, defaultnya 'proses'
        $status = $request->query('status', 'proses');

        // 2. Data Dummy Lengkap dengan rincian untuk Detail Pesanan
        $all_orders = [
            [
                'id' => 'TRX-99211',
                'customer_name' => 'Siti Aminah',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 123, Kecamatan Sukajadi, Kota Bandung, Jawa Barat 40162',
                'payment_method' => 'Transfer Bank BCA',
                'shipping_method' => 'J&T Express - Reguler',
                'subtotal' => 1150000,
                'shipping_cost' => 50000,
                'total' => 1200000,
                'status' => 'proses',
                'items' => '2x Produk Elvo Premium (XL), 1x Aksesoris',
                'created_at' => '21 Okt 2023 14:20 WIB'
            ],
            [
                'id' => 'TRX-77344',
                'customer_name' => 'Andi Wijaya',
                'phone' => '085711223344',
                'address' => 'Perumahan Indah Kencana Blok C5, Serpong, Tangerang Selatan, Banten 15310',
                'payment_method' => 'E-Wallet (Gopay)',
                'shipping_method' => 'SiCepat Best',
                'subtotal' => 330000,
                'shipping_cost' => 20000,
                'total' => 350000,
                'status' => 'pending',
                'items' => '1x T-Shirt Elvo Basic (L)',
                'created_at' => '22 Okt 2023 09:15 WIB'
            ],
            [
                'id' => 'TRX-88122',
                'customer_name' => 'Budi Santoso',
                'phone' => '089988776655',
                'address' => 'Jl. Gajah Mada No. 45, Kec. Genteng, Surabaya, Jawa Timur 60275',
                'payment_method' => 'Transfer Bank Mandiri',
                'shipping_method' => 'JNE YES',
                'subtotal' => 465000,
                'shipping_cost' => 35000,
                'total' => 500000,
                'status' => 'dikirim',
                'items' => '1x Hoodie Elvo Signature (M)',
                'created_at' => '20 Okt 2023 11:00 WIB'
            ]
        ];

        // 3. Filter data berdasarkan status yang sedang aktif
        // Menggunakan array_values agar index array kembali rapi (0, 1, 2) setelah difilter
        $orders = array_values(array_filter($all_orders, function($item) use ($status) {
            return $item['status'] == $status;
        }));

        // 4. Kirim data ke view
        return view('admin.pesanan-masuk', compact('orders', 'status'));
    }

    /**
     * Fungsi untuk memproses konfirmasi pengiriman
     * Sesuai dengan route: admin.orders.confirm
     */
    public function confirm(Request $request, $id)
    {
        // 1. Validasi input
        $request->validate([
            'resi' => 'required|string|max:50'
        ]);

        // 2. Ambil nomor resi dari form modal
        $noResi = $request->input('resi');

        // Note: Nanti di sini tempat kamu update status di Database
        // Contoh: Order::where('invoice', $id)->update(['status' => 'dikirim', 'no_resi' => $noResi]);

        // 3. Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Pesanan #' . $id . ' berhasil diproses. Nomor Resi: ' . $noResi);
    }
}