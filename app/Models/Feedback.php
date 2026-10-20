<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'visitor_id',
        'nama_pengunjung',
        'no_hp',
        'email',
        'rating',
        'ulasan',
        'sumber',
        'status_pengisian',
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}