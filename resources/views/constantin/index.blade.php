{{-- @foreach ($produse_constantin_contabilitate_grupate_dupa_cod as $produse_grupa)
    @php
        $cantitate = 0;
        $locatie = '';
    @endphp
        @foreach ($produse_grupa as $produs)
            @php
                $cantitate += $produs->cantitate;
                ($locatie == '') ? $locatie .= $produs->locatie : $locatie .= (', ' . $produs->locatie);
            @endphp

            @if ($loop->last)
                {{ $produs->cod }}, {{ $cantitate }}, {{ $locatie }}
            @endif
        @endforeach
    <br>
    <br>
@endforeach --}}

{{-- @foreach ($produse_constantin_site as $produse_grupa)
    @foreach ($produse_grupa as $produs)
            @if ($loop->count > 1)
                {{ $produs->sku }}
            <br>
            @endif
    @endforeach
@endforeach --}}
