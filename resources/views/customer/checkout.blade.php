@extends('layouts.customer')

@section('content')
<section class="pt-32 pb-24 px-8 bg-[#0a0a0a]">
    <div class="container mx-auto max-w-6xl">
        <div class="flex flex-col lg:flex-row gap-16">

            <!-- KOLOM KIRI: FORM PENGIRIMAN -->
            <div class="flex-1" data-aos="fade-right">
                <h2 class="text-4xl font-black italic uppercase tracking-tighter text-white mb-10">
                    Shipping <span class="text-gray-700">Information.</span>
                </h2>

                <form action="#" class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="group">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 group-focus-within:text-white transition-colors">Full Name</label>
                            <input type="text" placeholder="REHAN FAEZAN" class="w-full bg-transparent border-b-2 border-white/10 py-3 text-white focus:outline-none focus:border-white transition-all uppercase font-bold tracking-widest text-sm">
                        </div>
                        <div class="group">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 group-focus-within:text-white transition-colors">Phone Number</label>
                            <input type="text" placeholder="+62 812..." class="w-full bg-transparent border-b-2 border-white/10 py-3 text-white focus:outline-none focus:border-white transition-all font-bold tracking-widest text-sm">
                        </div>
                    </div>

                    <div class="group">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 group-focus-within:text-white transition-colors">Shipping Address</label>
                        <textarea rows="3" placeholder="STREET NAME, CITY, POSTAL CODE" class="w-full bg-transparent border-b-2 border-white/10 py-3 text-white focus:outline-none focus:border-white transition-all uppercase font-bold tracking-widest text-sm resize-none"></textarea>
                    </div>

                    <div class="group">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 mb-4 block">Payment Method</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="payment" class="hidden peer" checked>
                                <div class="p-4 border border-white/10 rounded-2xl peer-checked:border-white peer-checked:bg-white peer-checked:text-black transition-all text-center">
                                    <span class="text-[10px] font-black uppercase tracking-widest">Bank Transfer</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="payment" class="hidden peer">
                                <div class="p-4 border border-white/10 rounded-2xl peer-checked:border-white peer-checked:bg-white peer-checked:text-black transition-all text-center">
                                    <span class="text-[10px] font-black uppercase tracking-widest">E-Wallet (QRIS)</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            <!-- KOLOM KANAN: ORDER SUMMARY -->
            <div class="w-full lg:w-[400px]" data-aos="fade-left">
                <div class="bg-[#111111] p-8 rounded-[30px] border border-white/5 sticky top-32 shadow-2xl">
                    <h3 class="text-xl font-black italic uppercase tracking-widest text-white mb-8 border-b border-white/10 pb-4">Order Summary</h3>

                    <!-- Item List (Looping dari Cart) -->
                    <div class="space-y-6 mb-8">
                        <div class="flex gap-4">
                            <div class="w-16 h-16 bg-[#1a1a1a] rounded-xl overflow-hidden flex-shrink-0 border border-white/5">
                                <!-- Pakai placeholder logo ELVO dulu -->
                                <div class="w-full h-full flex items-center justify-center text-white/10 font-black italic text-xs">ELVO</div>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-[10px] font-black text-white uppercase tracking-widest leading-tight">Signature Black</h4>
                                <p class="text-[10px] text-gray-500 mt-1 uppercase">QTY: 1</p>
                            </div>
                            <span class="text-xs font-bold text-white">IDR 185K</span>
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="space-y-4 pt-6 border-t border-white/5">
                        <div class="flex justify-between text-[10px] font-bold uppercase tracking-widest text-gray-500">
                            <span>Subtotal</span>
                            <span>IDR 185K</span>
                        </div>
                        <div class="flex justify-between text-[10px] font-bold uppercase tracking-widest text-gray-500">
                            <span>Shipping Cost</span>
                            <span>IDR 20K</span>
                        </div>
                        <div class="flex justify-between text-lg font-black italic uppercase tracking-tighter text-white pt-2">
                            <span>Grand Total</span>
                            <span>IDR 205K</span>
                        </div>
                    </div>

                    <!-- Button Pay -->
                    <!-- Di checkout.blade.php -->
                    <a href="{{ route('checkout.success') }}" class="w-full block text-center bg-white text-black font-black uppercase tracking-[0.3em] py-6 mt-10 hover:bg-gray-200 transition-all italic text-sm rounded-full">
                        Confirm Payment
                    </a>

                    <p class="text-center text-[9px] text-gray-600 uppercase tracking-widest mt-6">
                        Secure SSL Encryption
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection