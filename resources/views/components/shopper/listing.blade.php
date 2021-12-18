<x-table-column>
    <x-shopper.status :shopper="$shopper"/>
</x-table-column>

<x-table-column>
    {{ $shopper['first_name'] }} {{ $shopper['last_name'] }}
</x-table-column>

<x-table-column>
    {{ $shopper['email'] }}
</x-table-column>

<x-table-column>
    {{ $shopper['check_in'] }}
</x-table-column>

<x-table-column>
    {{ $shopper['check_out'] }}
</x-table-column>

<x-table-column>
    @if($shopper['status']['name'] == 'Active')
       <x-shopper.checkout :shopper="$shopper" :store="$store" />
    @endif
</x-table-column>