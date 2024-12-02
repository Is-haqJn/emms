<!-- Modal -->
<div id="get_info_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">@lang('equicare.maintenance_cost_details')</h4>
      </div>
      <div class="modal-body">
        <table class="table table-striped">
          <tr>
            <th>@lang('equicare.hospital')</th>
            <td>{{ $cost->hospital->name }}</td>

            <th>@lang('equicare.type')</th>
            <td>{{ strtoupper($cost->type??"-") }}</td>
          </tr>
          <tr>
            <th>@lang('equicare.by')</th>
            @php
              if($cost->cost_by =='tp'){
                $text = trans('equicare.third_party');
              }else{
                $text = isset(\App\Setting::first()->company)?\App\Setting::first()->company:config('app.name');
              }
            @endphp
            <td>{{ $text }}</td>

          @if ($cost->cost_by == 'tp')

              <th>@lang('equicare.third_p_name')</th>
              <td>{{ $cost->tp_name }}</td>
            </tr>
            <tr>
              <th>@lang('equicare.third_p_mobile')</th>
              <td>{{ $cost->tp_mobile }}</td>

              <th>@lang('equicare.third_p_email')</th>
              <td>{{ $cost->tp_email }}</td>
            </tr>
          @endif
          @if (count($decoded_ids) > 0)
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>@lang('equicare.equipment')</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($decoded_ids as $k => $id)
                @php
                    $equipment = App\Equipment::find($id);
                @endphp
                <tr>
                    <td>{{ $equipment->name ?? "-" }}</td>
                    <td>{{ $decoded_start_date[$k] ?? "-" }}</td>
                    <td>{{ $decoded_end_dates[$k] ?? "-" }}</td>
                    <td>{{ $decoded_costs[$k] ?? "-" }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('equicare.close')</button>
      </div>
    </div>

  </div>
</div>