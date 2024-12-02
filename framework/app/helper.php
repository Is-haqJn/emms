<?php
function date_change($date){
  if($date!=null){
    try {
      $carbonDate = \Carbon\Carbon::parse($date);
    } catch (\Exception $e) {
      // Parsing failed, fallback to original date string
      $carbonDate = \Carbon\Carbon::createFromFormat('m-d-Y', $date);
    }
    if(env('date_settings')!='' || env('date_settings')!= null)
    {
    $formattedDate = $carbonDate->format(env('date_convert'));
    }
    else{
        $formattedDate=$date;
    }
    return $formattedDate;
} 

else{
      return $date;
}
}
function qr_counts($count){
  // $qr_count
  // dd($count);
  switch($count){
    case 100 :
      $qr_count = 6;
      break; 
     case 200 :
      $qr_count = 3;
      break; 
    case 300 :
      $qr_count = 2;
      break; 
    case 400:
      $qr_count = 1;
      break;
    default:
      $qr_count = 0;
      break;
  }
  return $qr_count;
}
function responseData($status,$message,$data,$status_code)
{
  return response()->json([
    'success' => $status,
    'message' => $message,
    'data' => $data,
  ], $status_code);
}
 function handleException(\Throwable $th)
    {
        return responseData('0', $th->getMessage(), '', 500);
    }
    function decode_dates ($value, $key) {
      return isset(json_decode($value, true)[$key])
        ? date_change(date('Y-m-d', strtotime(json_decode($value, true)[$key])))
        : date_change(date('Y-m-d'));
    };

?>