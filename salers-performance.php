<?php 
    public function reportFilter(FilterRequest $req){
        $old_start_date = $req->start_date;
        $old_end_date = $req->end_date;

        $users = array();
        $statu = $req->statu;
        $employee = $req->employee;
        foreach ($employee as $key => $value) {
            if ($value == 0) {
                $text = "Tüm satışlara ait ";
            }
            else{
                $text = "<b style='margin-right:5px'>".User::where('id','=',$value)->first()->nameSurname. "'a</b>ait ";
            }
        }
        foreach ($employee as $key => $value) {
            if ($value == 0) {
                array_diff($users,$users);
                $allSalers = User::where('sale','=',1)->get();
                foreach ($allSalers as $item) {
                    array_push($users,[
                        'id' => $item->id,
                    ]);
                }
            }
            else{
                array_push($users,[
                    'id' => $value,
                ]);
            }
        }

        $start_date = $req->start_date;
        $end_date = $req->end_date;
        $bill_address = $req->bill_address;
        
        $currencies = Currency::get();
        $array = array();


        foreach ($users as $eachUser) {
            $sumTL = 0;
            $sumUSD = 0;
            $sumEURO = 0;
            foreach ($currencies as $row) {

                if ($bill_address == 0) {
                    if ($statu == 100) {
                        $forms = FormSale::where('currency','=',$row->id)->where('form_owner','=',$eachUser['id'])->whereBetween('created_at', [$start_date, $end_date])->get();
                    }
                    else{
                        $forms = FormSale::where('currency','=',$row->id)->where('confirm','=',$statu)->where('form_owner','=',$eachUser['id'])->whereBetween('created_at', [$start_date, $end_date])->get();
                    }
                } else {
                    if ($statu == 100) {
                        $forms = FormSale::where('currency','=',$row->id)->where('bill_address','=',$bill_address)->where('form_owner','=',$eachUser['id'])->whereBetween('created_at', [$start_date, $end_date])->get();
                    }
                    else{
                        $forms = FormSale::where('currency','=',$row->id)->where('confirm','=',$statu)->where('bill_address','=',$bill_address)->where('form_owner','=',$eachUser['id'])->whereBetween('created_at', [$start_date, $end_date])->get();
                    }
                }
                foreach ($forms as $item) {
                    $formElement = FormSaleElement::where('form_number','=',$item->id)->get();
                    foreach ($formElement as $key) {
                        if ($row->currency == 'TL') {
                            $sumTL += $key->sale_price * $key->piece;
                        }
                        if ($row->currency == 'USD') {
                            $sumUSD += $key->sale_price * $key->piece;
                        }
                        if ($row->currency == 'EURO') {
                            $sumEURO += $key->sale_price * $key->piece;
                        }
                    }
                }
    
            }
            $image = User::where('id','=',$eachUser['id'])->first()->image;
            if ($image == '') {
                $image_path = "default-user.png";
            }
            else{
                $image_path = User::where('id','=',$eachUser['id'])->first()->image;
            }

            array_push($array,[
                'user_id' => $eachUser['id'],
                'image' => $image_path,
                'nameSurname' => User::where('id','=',$eachUser['id'])->first()->nameSurname,
                'sumTL' => number_format($sumTL,2,',','.'),
                'sumUSD' => number_format($sumUSD,2,',','.'),
                'sumEURO' => number_format($sumEURO,2,',','.'),
            ]);
        }

        $saler = User::where('sale','=',1)->get();
        $bill_address_view = FormSale::select('bill_address')->distinct()->get();
        return view('raporcek',[
            'start_date' => $start_date,
            'end_date' => $end_date,
            'text' => $text,
            'array' => $array,
            'saler' => $saler,
            'bill_address' => $bill_address_view,
            'old_start_date' => $old_start_date,
            'old_end_date' => $old_end_date,
        ]);
    }
?>