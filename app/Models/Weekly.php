<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Weekly extends Model
{
    protected $table = 'weekly_chart';
    protected $fillable = [
        'station', 'total_fault', 'week','year'
    ];
    
    public $timestamps = false;
}