@if( isset($shopper) && isset($store) )
    <a href="{{ route('store.location.checkout', ['shopperUuid' => $shopper['uuid'], 'storeUuid'=> $store['uuid'] ]) }}" class="bg-blue-800 text-white font-bold py-2 px-4 rounded" >
        Checkout
    </a>
@endif