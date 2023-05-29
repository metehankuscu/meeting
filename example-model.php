<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSale extends Model
{
    use HasFactory;
    protected $table = "form_sale";
  	protected $primaryKey = 'id';
    protected $fillable = [
        'creator',
        'form_owner',
        'project_name',
        'payment_type',
        'expiry',
        'currency',
        'awaiting_manager',
        'bill_address',
        'company_person',
        'price_diffrence',
        'process_ongoing',
        'sas_no',
        'mail',
        'address',
        'comment',
        'storage',
        'date'
    ];
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
}
