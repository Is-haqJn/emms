<!DOCTYPE html>
<html>
<head>
    <title>@lang('equicare.equipments_excel')</title>
</head>
<body>
    <!-- <h2 style="verticle-align:middle;rowspan:6;">Title:{{ $title }}</h2>
    <h2>SubTitle:{{ $subtitle }}</h2> -->
    @if(isset($data) && $data->count())
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-inverse">
            <tr><th colspan="{{ count($columns)+1 }}" style="font-weight:bold;display:flex;justify-content:center;text-align:center;">{{ $title }}</th></tr>
            <tr><th colspan="{{ count($columns)+1 }}" style="font-weight:bold;display:flex;text-align:center;">{{ $subtitle }}</th></tr>
                <tr>
                    <th> # </th>
                    @foreach ($columns as $column)
                        <th> @lang('equicare.' . $column) </th>
                    @endforeach
                </tr>   
            </thead>
            <tbody>
                @foreach ($data as $key => $item)
                <tr>
                    <td> {{ $key + 1 }} </td>
                    @foreach ($columns as $column)
                        <td>
                            @if($column == 'user_id')
                                {{ $item->user ? ucfirst($item->user->name) : '-' }}
                            @elseif($column == 'hospital_id')
                                {{ $item->hospital ? $item->hospital->name : '-' }}
                            @elseif($column == 'department')
                                {{ $item->get_department ? ($item->get_department->short_name . ' (' . $item->get_department->name . ')') : '-' }}
                            @elseif(in_array($column, ['purchase_date', 'order_date', 'installation_date', 'warranty_date']))
                                {{ $item->$column ? date_change($item->$column) : '-' }}
                            @else
                                {{ $item->$column ?? '-' }}
                            @endif
                        </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</body>
</html>
