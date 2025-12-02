<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id', 'file_path', 'file_name', 'requested_by_agent'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
