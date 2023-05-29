<?php

namespace App\Mail;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class proposalMail extends Mailable
{
    use Queueable, SerializesModels;
    public $proposalData;

    public function __construct($proposalData)
    {
        $this->proposalData = $proposalData;
        $path = public_path('pdf/');
        $truePath = $path.$proposalData['formNumber'].".pdf";
        $this->subject($proposalData['formNumber']. " Numaralı Teklifimiz 📧")->attach($truePath);
    }

    public function build()
    {   
        $company = Company::orderBy("id", "desc")->limit(1)->get();
        foreach ($company as $row) {
            $companyName = $row->name;
            $companyAddress = $row->address;
            $companyImage = "/img/".$row->image;
        }
        return $this->view('proposalMailTemplate',[
            'name' => $companyName,
            'companyAddress' => $companyAddress,
            'image' => $companyImage,
        ]);
    }
}
