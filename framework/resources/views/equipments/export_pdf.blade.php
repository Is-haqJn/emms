<!DOCTYPE html>
<html>
<head>
    <title>@lang('equicare.equipment_pdf')</title>
    <style type="text/css">
        /* body {
            margin: 10px;
        } */

        .container-fluid {
            width: 100%;
        }

        h1, h2 {
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table, th, td {
            border: 1px solid black;
        }

        th, td {
            text-align: center;
            padding: 5px;
            font-size: 14px;
        }

        .table-responsive {
            width: 100%;
            overflow: auto;
        }

        .page-break {
            page-break-after: always;
        }

        @media print {
            .page-break {
                display: block;
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h1>Title: {{ $title }}</h1>
        <h2>SubTitle: {{ $subtitle }}</h2>

        @if(isset($data) && $data->count())
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            @foreach ($columns as $column)
                                <th>@lang('equicare.' . $column)</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $item)
                            <tr>
                                @foreach ($columns as $column)
                                    @php
                                        $u_e_id = (\App\QrGenerate::where('id', $item->qr_id)->first() != null) ? 
                                        (\App\QrGenerate::where('id', $item->qr_id)->first()->uid) : '';
                                    @endphp
                                    <td>
                                        @if($column == 'user_id')
                                            {{ $item->user ? ucfirst($item->user->name) : '-' }}
                                        @elseif($column == 'qr_id')
                                            <img src="{{ asset('/uploads/qrcodes/qr_assign/' . $u_e_id . '.png') }}" width="60px" />
                                        @elseif($column == 'hospital_id')
                                            {{ $item->hospital ? $item->hospital->name : '-' }}
                                        @elseif($column == 'department')
                                            {{ $item->get_department ? ($item->get_department->short_name . ' (' . $item->get_department->name . ')') : '-' }}
                                        @elseif($column == 'unique_id')
                                            {{ $item->unique_id }}
                                        @elseif(in_array($column, ['purchase_date', 'order_date', 'installation_date', 'warranty_date']))
                                            {{ $item->$column ? date('Y-m-d', strtotime($item->$column)) : '-' }}
                                        @else
                                            {{ $item->$column ?? '-' }}
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            @foreach ($columns as $column)
                                <th>@lang('equicare.' . $column)</th>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
</body>
</html>