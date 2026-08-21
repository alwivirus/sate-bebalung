<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'is_available',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            // Check direct paths in uploads or images
            $extensions = ['', '.jpg', '.png', '.svg'];
            $baseName = pathinfo($this->image, PATHINFO_FILENAME);

            foreach ($extensions as $ext) {
                $checkName = $ext === '' ? $this->image : $baseName . $ext;
                if (file_exists(public_path('images/menus/' . $checkName))) {
                    return asset('images/menus/' . $checkName);
                }
                if (file_exists(public_path('uploads/menus/' . $checkName))) {
                    return asset('uploads/menus/' . $checkName);
                }
            }
        }

        // Automatic fallback by dish name
        $name = strtolower($this->name);
        if (str_contains($name, 'ayam')) return asset('images/menus/sate_ayam.jpg');
        if (str_contains($name, 'polos')) return asset('images/menus/sate_kambing_polos.jpg');
        if (str_contains($name, 'campur')) return asset('images/menus/sate_kambing_campur.jpg');
        if (str_contains($name, 'sate')) return asset('images/menus/sate_kambing_polos.jpg');
        if (str_contains($name, 'tongseng')) return asset('images/menus/tongseng_kambing.jpg');
        if (str_contains($name, 'sop')) return asset('images/menus/sop_kambing.jpg');
        if (str_contains($name, 'gulai')) return asset('images/menus/gulai_kambing.jpg');
        if (str_contains($name, 'nasi putih')) return asset('images/menus/nasi_putih.jpg');
        if (str_contains($name, 'nasi gurih')) return asset('images/menus/nasi_gurih.jpg');
        if (str_contains($name, 'paket')) return asset('images/menus/paket_murah.jpg');
        if (str_contains($name, 'poci')) return asset('images/menus/teh_poci.jpg');
        if (str_contains($name, 'kopi') || str_contains($name, 'toebroek') || str_contains($name, 'tubruk')) return asset('images/menus/kopi_toebroek.svg');
        if (str_contains($name, 'air') || str_contains($name, 'mineral')) return asset('images/menus/air_putih.jpg');
        if (str_contains($name, 'es teh')) return asset('images/menus/es_teh_manis.jpg');
        if (str_contains($name, 'teh')) return asset('images/menus/teh_tawar.jpg');
        if (str_contains($name, 'es jeruk')) return asset('images/menus/es_jeruk.jpg');
        if (str_contains($name, 'jeruk')) return asset('images/menus/jeruk_panas.jpg');

        return asset('images/logo-goat.png');
    }
}
