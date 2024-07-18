<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardLists extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'position',
        'board_id'
    ];
    public function boardLists()
    {
        return $this->belongsTo(Boards::class);
    }
}
