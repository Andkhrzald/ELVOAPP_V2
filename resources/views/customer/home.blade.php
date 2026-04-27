@extends('layouts.customer') {{-- Pake layout khusus customer --}}

@section('content')
<h1 class="text-3xl font-bold mb-4">Homepage</h1>

<div class="grid grid-cols-3 gap-4">
    @foreach ($products as $product)
        <a href="/product/{{ $product['id'] }}">
            <div class="p-4 border hover:shadow">
                <h2 class="font-bold">{{ $product['name'] }}</h2>
                <p>Rp {{ $product['price'] }}</p>
            </div>
        </a>
    @endforeach
</div>
@endsection