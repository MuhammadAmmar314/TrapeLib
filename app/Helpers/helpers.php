<?php

use Carbon\Carbon;

    function convert_date($value){
        return date('d M Y - H:i:s' , strtotime($value));
    }

    if (!function_exists('showExpiredTransaction')) {
        function showExpiredTransaction()
        {
            $a = today()->format("Y-m-d");
            $selisihs = \App\Models\Transaction::select('members.name' , 'date_end', 'status')
                                            ->selectRaw('DATEDIFF(NOW(), date_end) as beda')
                                            ->join('members' , 'members.id' , '=' , 'transactions.member_id')
                                            ->where('status' , '=' , '1')
                                            ->where('date_end', '<', Carbon::now('Asia/Jakarta')->format('Y-m-d'))
                                            ->get();

            
            // foreach ($selisihs as $key =>$selisih){
            //     $selisihs->beda = date_diff(date_create($selisih->date_end), date_create(today()->format('Y-m-d')));
            // }
            // $datatables = datatables()->of($selisihs)->addIndexColumn();
        
            return $selisihs;
        }
    }

    function convert_number($value){
        return  number_format($value,0,",",".");
    }

    function compute_date($x , $y){
        // $startTimeStamp = strtotime($x);
        // $endTimeStamp = strtotime($y);
        // $timeDiff = abs($endTimeStamp - $startTimeStamp);

        // $numberDays = $timeDiff/86400;  // 86400 seconds in one day

        // // and you might want to convert to integer
        // $numberDays = intval($numberDays);

        $datetime1 = date_create($x);
        $datetime2 = date_create($y);
        return date_diff($datetime1, $datetime2)->days;
    }

    function statusLabel(int $status)
    {
        return $status == 1 ? 'Belum dikembalikan' : 'Sudah dikembalikan';
    }
?>