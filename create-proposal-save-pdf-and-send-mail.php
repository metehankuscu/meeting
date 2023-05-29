<?php 
    public function sendProposal(Request $req){

        DB::beginTransaction();
        try {
            $formNumber = "MHR-00000".rand(1,9999999);
            $createProposal = Proposals::create([
                'sender' => session()->get('id'),
                'proposal_id' => $formNumber,
                'subject' => $req->project_name,
                'selected_date' => $req->date_now,
                'validity_date' => $req->expiry_date,
                'currency' => $req->currency,
                'form_number' => $req->request_number,
                'special_condition' => $req->editor1,
                'company_person' => $req->company_person,
                'statu' => $req->statu,
                'company' => $req->bill,
                'mail' => $req->mail,
                'address' => $req->address,
                'explained' => $req->explained,
                'date' => $req->date_now,
            ]);
            $sendedName  = Companies::where('id','=',$req->company_person)->get();
            foreach ($sendedName as $key) {
                $nameSurname = $key->authorized;
                $company = $key->company;
            }
            $mail = $req->mail;
            $subject = $req->project_name;
            $date_now = date('d-m-Y',strtotime($req->date_now));
            $expiry_date = date('d-m-Y',strtotime($req->expiry_date));
            $customerAddress = $req->address;

            $companyInfo = Company::orderBy("id", "desc")->limit(1)->get();
            foreach ($companyInfo as $row) {
                $companyImage = "/img/".$row->image; 
                $companyName = $row->name; 
                $companyAddress = $row->address; 
                $companyLittle_address = $row->little_address; 
                $companyCountry = $row->country; 
                $companyZip = $row->zip; 
            }

            $getCurrency = Currency::where('id','=',$req->currency)->get();
            foreach ($getCurrency as $key) {
                $currency = $key->currency;
            }
            if($currency == 'TL')   { $currency = "₺"; }
            if($currency == 'USD')  { $currency = "$"; }
            if($currency == 'EURO') { $currency = "€"; }
            if($currency == 'GBP')  { $currency = "£"; }
          
            $proposal_id = $createProposal->id;

            if (isset($req->ticket)) {
                for ($i=0; $i < count($req->ticket); $i++) { 
                    ProposalTicketUser::create([
                        'proposal_id' => $proposal_id,
                        'user_id' => $req->ticket[$i],
                    ]);
                }
            }

            $sum = 0;
            $tax = 0;
            $totalSum = 0;
            for ($i=0; $i < count($req->name); $i++) { 
                if ($req->name[$i] != '') {
                    $createProduct = ProposalItem::create([
                        'proposal_id'=>$proposal_id,
                        'name'=>$req->name[$i],
                        'piece'=>$req->count[$i],
                        'purchasePrice'=>number_format($req->purchasePrice[$i],2,'.',''),
                        'profit'=>$req->profit[$i],
                        'salePrice'=>$req->salePrice[$i],
                        'tax'=>$req->tax[$i],
                        'supplier'=>$req->supplier[$i],
                    ]);
                    $sum += $req->salePrice[$i]*$req->count[$i];
                    $tax += ((($req->salePrice[$i]*$req->count[$i])*$req->tax[$i])/100);
                    $totalSum += ($req->salePrice[$i]*$req->count[$i]) + ((($req->salePrice[$i]*$req->count[$i])*$req->tax[$i])/100);
                }
            }
            $formatSum = number_format($sum,2,'.',',');
            $formatTax = number_format($tax,2,'.',',');
            $formatTotalSum = number_format($totalSum,2,'.',',');
            $proposalSpecial = $req->editor1;
            $allProducts = array();
            $total = 0;
            for ($i=0; $i < count($req->name); $i++) { 
                if ($req->name[$i] != '') {
                    $salePrice = $req->salePrice[$i];
                    $formatPrice = number_format($salePrice,2,'.','');
                    $piece = $formatPrice * $req->count[$i];
                    $total = number_format($piece,2,'.',',');

                    array_push($allProducts,[
                        'name' => $req->name[$i],
                        'piece' => $req->count[$i],
                        'price' => number_format($req->salePrice[$i],2,'.',','),
                        'tax' => $req->tax[$i],
                        'total' => $total,
                    ]);
                }
            }
            
            $companyData = Company::where('id','=',1)->first();
            $imageName = $companyData->image;

            $pdf = PDF::loadView('proposalMail',compact('allProducts','imageName','company','currency','formatSum','formatTax','formatTotalSum','mail','nameSurname','formNumber','subject','date_now','expiry_date','companyImage','companyName','companyAddress','companyLittle_address','companyCountry','companyZip','customerAddress','proposalSpecial'));
            $pdf->setPaper('A4', 'portrait');
            $proposalData = [
                'formNumber'=> $formNumber,
            ];

            $path = public_path('pdf/');
            $pdf->save($path.$formNumber.'.pdf');
            if ($mail != '' and $req->sendMail == 1) {
                $sendMail = Mail::to($mail)->send(new ProposalMail($proposalData));
                if ($sendMail) {

                    DB::commit();
                    return redirect()->route('proposals')->with('success','1');
                }
            }
            else{
                DB::commit();
                return redirect()->route('proposals')->with('success','1');
            }

        } catch (Exception $e) {
            return redirect()->route('proposals')->with('error','1');
        }
    }
?>