<?php 

    public function login(Request $request) {
        $email = $request->input('email');
        $password = $request->input('password');
        info(Hash::make($password));
        if (empty($email) || empty($password)) {
            $apiResponse = $this->returnApiResponse(true, "Email ve şifre boş olamaz", null);
            return response()->json($apiResponse);
        }
        if (!Auth::attempt(['email'=> $email, 'password'=> $password], true)) {
            $apiResponse = $this->returnApiResponse(true, "Email veya Şifre Yanlış", null);
            return response()->json($apiResponse);
        }
        $apiResponse = $this->returnApiResponse(false, "Giriş başarılı bir şekilde yapıldı, Ana sayfaya yönlendiriliyorsunuz...", null);
        return response()->json($apiResponse);
    }

    public function logout() {
        Auth::logout();
        return redirect("/auth/login");
    }
?>