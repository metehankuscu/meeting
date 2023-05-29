<?php 
    public function addCarPost(CarPostRequest $req){
        
        try {
            DB::beginTransaction();
            $imageName = $req->carImage->getClientOriginalName();
            $req->carImage->move(public_path('img'), $imageName);
            $addCar = Cars::create([
                'image' => $imageName,
                'model' => $req->carModel,
                'plate' => $req->carPlate,
                'insurance' => $req->carTrafficInsurance,
                'vehicle_insurance' => $req->carInsurance,
                'inspection_date' => $req->inspection_date,
                'financial_liability_date' => $req->financial_liability_date,
                'kilometre' => $req->kilometre,
                'note' => $req->note,
            ]);
            $insertedID = $addCar->id;

            if($req->hasfile('document'))
            {
               foreach($req->file('document') as $file)
               {
                   $name = uniqid().'.'.$file->extension();
                   $file->move(public_path('img'), $name);  
                   CarDetails::create([
                        'carID' => $insertedID,
                        'image' => $name,
                   ]);
               }
            }
            DB::commit();
            return redirect()->route('cars')->with('success','1');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('cars')->with('error','1');
        }
    }
?>