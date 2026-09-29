<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'image',
        'badge',
        'is_available',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($menu) {
            if (empty($menu->slug)) {
                $menu->slug = Str::slug($menu->name);
            }
        });
    }

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
            $img = trim($this->image);
            // 1. Direct public path match
            if (file_exists(public_path($img))) {
                return asset($img);
            }
            
            // 2. Direct basename match in images/menus or uploads/menus
            $baseName = basename($img);
            $fileNameWithoutExt = pathinfo($baseName, PATHINFO_FILENAME);
            $extensions = ['', '.jpg', '.png', '.svg', '.jpeg', '.webp'];

            foreach ($extensions as $ext) {
                $candidate = $ext === '' ? $baseName : $fileNameWithoutExt . $ext;
                if (file_exists(public_path('images/menus/' . $candidate))) {
                    return asset('images/menus/' . $candidate);
                }
                if (file_exists(public_path('uploads/menus/' . $candidate))) {
                    return asset('uploads/menus/' . $candidate);
                }
                if (file_exists(public_path('images/' . $candidate))) {
                    return asset('images/' . $candidate);
                }
            }
        }

        // Automatic fallback by dish name
        $name = strtolower($this->name);
        if (str_contains($name, 'aqiqah') && str_contains($name, 'b')) return asset('images/menus/paket_aqiqah_b.jpg');
        if (str_contains($name, 'aqiqah')) return asset('images/menus/paket_aqiqah_a.jpg');
        if (str_contains($name, 'bento')) return asset('images/menus/paket_bento.jpg');
        if (str_contains($name, 'ayam')) return asset('images/menus/sate_ayam.jpg');
        if (str_contains($name, 'polos')) return asset('images/menus/sate_kambing_polos.jpg');
        if (str_contains($name, 'campur')) return asset('images/menus/sate_kambing_campur.jpg');
        if (str_contains($name, 'sate')) return asset('images/menus/sate_kambing_polos.jpg');
        if (str_contains($name, 'tongseng')) return asset('images/menus/tongseng_kambing.jpg');
        if (str_contains($name, 'sop')) return asset('images/menus/sop_kambing.jpg');
        if (str_contains($name, 'gulai')) return asset('images/menus/gulai_kambing.jpg');
        if (str_contains($name, 'nasi putih')) return asset('images/menus/nasi_putih.jpg');
        if (str_contains($name, 'nasi gurih')) return asset('images/menus/nasi_gurih.jpg');
        if (str_contains($name, 'paket') || str_contains($name, 'hemat')) return asset('images/menus/paket_murah.jpg');
        if (str_contains($name, 'poci')) return asset('images/menus/teh_poci.jpg');
        if (str_contains($name, 'air') || str_contains($name, 'mineral')) return asset('images/menus/air_putih.jpg');
        if (str_contains($name, 'es teh')) return asset('images/menus/es_teh_manis.jpg');
        if (str_contains($name, 'teh')) return asset('images/menus/teh_tawar.jpg');
        if (str_contains($name, 'es jeruk')) return asset('images/menus/es_jeruk.jpg');
        if (str_contains($name, 'jeruk')) return asset('images/menus/jeruk_panas.jpg');
        if (str_contains($name, 'kopi')) return asset('images/menus/kopi_toebroek.jpg');

        return asset('images/logo-goat.png');
    }
}
