<?php

namespace App\Models\Order;

use App\Models\Client\Client;
use App\Enums\Order\DiscountType;
use App\Models\Client\ClientEmail;
use App\Models\Client\ClientPhone;
use App\Models\Client\ClientAdrress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Order extends Model
{

    protected $table = 'order';

    protected $guarded = [];
    public static function boot()
    {
        parent::boot();
        static::creating(function($model){
            $model->number = 'ORD-'.'_'.rand(1000,9999).'-'.date('m').date('y');
        });
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function clientPhone()
    {
        return $this->belongsTo(ClientPhone::class);
    }
    public function clientEmail()
    {
        return $this->belongsTo(ClientEmail::class);
    }
    public function clientAddress()
    {
        return $this->belongsTo(ClientAdrress::class);
    }
    // public function getTotalPriceAttribute()
    // {
    //     return $this->items->sum(function ($item) {
    //         return $item->price * $item->qty;
    //     });
    // }
    // public function getTotalPriceAfterDiscountAttribute()
    // {
    //     return $this->total_price - $this->discount;
    // }

    protected function priceAfterDiscount(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $this->setPriceAfterDiscount($value)
        );
    }
    private function setPriceAfterDiscount($value)
    {

        // تعيين سعر المنتج مباشرةً إذا لم يكن هناك خصم
        if (empty($this->discount) || $this->discount_type === null) {
            return $value;
        }
        // إذا كان هناك خصم، قم بتحديث الخصم بناءً على السعر الجديد
        if ($this->discount_type == DiscountType::FIXCED) {
            return $this->price - $value; // احسب الخصم الثابت
        } elseif ($this->discount_type == DiscountType::PERCENTAGE) {
            $this->discount = (($this->total_price - $value) / $this->total_price) * 100; // احسب الخصم النسبي
        }
        $this->total_price = $value;
    }
}
