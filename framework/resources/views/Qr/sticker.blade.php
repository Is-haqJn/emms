<!DOCTYPE html>
<html>

<head>
    <title>@lang('equicare.sticker_generate')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        /* Custom CSS for grid layout */
       
        .size{
          width: {{$size}}px;
        }
        img:nth-child(1){
            /* margin-left: 10px; */
        }
    </style>
</head>

<body>
    {{-- @dd($qr) --}}
    {{-- <div class="container"> --}}
    @if ($qr->count())
        @php $count=1; @endphp
        <div  class="mt-4">
            @foreach ($qr as $q)
                {{-- @dd($qr) --}}
                <div style="float:left;margin-right:15px;">
                    {{-- @dd($qr) --}}
                    <img class="" src="{{ asset('/uploads/qrcodes/qr_assign/' . $q->uid . '.png') }}"
                        alt="QR Code {{ $q->uid }}" style="width:{{ $size }}px">
                        
                        <p class="text-center size" style="text-align:center;font-size:13px;">{{$qr_line??''}}</p>
                </div>
                {{-- @dd(qr_counts($size)) --}}

                @if ($count == qr_counts($size))
                    <div style="clear:both;">
                    </div>
                    @php $count=0; @endphp
                @endif
                @php $count++; @endphp
            @endforeach
        </div>
    @else
        <div style="text-align: center;">
            <strong><span>No Stickers</span></strong>
        </div>
    @endif
    {{-- </div> --}}
</body>

</html>
