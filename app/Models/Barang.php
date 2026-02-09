<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $fillable = ['code', 'nama_pupuk', 'locator', 'uom', 'stok_awal', 'masuk', 'keluar', 'stok_total'];
}
