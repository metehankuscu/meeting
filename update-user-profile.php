<?php 
    public function updateUser(Request $req){
        if (!Auth::user()) {
            return redirect()->route('login');
        }
        $req->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        ]);

        $imageName = time().'.'.$req->image->extension();  
        try {
            $updateUser = User::where('id','=',Auth::user()->id)->update([
                'image' => $imageName,
            ]);
            $req->image->move(public_path('res/img'), $imageName);
            return redirect()->route('profile')->with('success','Güncelleme işlemi başarılı.');
        } catch (\Throwable $th) {
            return redirect()->route('profile')->with('error','Bir sorun oluştu lütfen tekrar deneyiniz.');
        }
    }
?>