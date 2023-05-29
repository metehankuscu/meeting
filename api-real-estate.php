<?php 
    public function newAppointments(Request $request){
        if (Auth::user()) {
            $validate = Validator::make($request->all(),[
                'user_id' => 'required|numeric',
                'customer_full_name' => 'required|string|max:255',
                'customer_email' => 'required|email|min:6',
                'customer_phone' => 'required|string|min:6',
                'appointment_address' => 'required|string|max:255',
                'start_date' => 'required|date|date_format:Y-m-d H:i A',
                'end_date' => 'required|date|date_format:Y-m-d H:i A|after:start_date',
            ]);
    
            $start_date = new Carbon($request->start_date);
            $end_date = new Carbon($request->end_date);
    
            if($end_date->diffInMinutes($start_date) > 60) {
                return response()->json([
                    'end_date' => 'End date should not be more than 1 hour after the start date.'
                ]);
            }
    
            if ($validate->fails()) {
                return response()->json([
                    'errors' => $validate->errors()
                ]);
            }
    
            $appointment = Appointments::create([
                'user_id' => $request->user_id,
                'customer_full_name' => $request->customer_full_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'appointment_address' => $request->appointment_address,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Created successfully',
            ]);
        }
        else{
            return response()->json([
                'status' => 'error',
                'message' => 'You need to Login',
            ]);
        }

    }
?>