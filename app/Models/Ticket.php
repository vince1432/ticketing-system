<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'assigned_to',
        'priority_id',
        'summary'
    ];

    protected $casts = [
        'closed_at' => 'date:Y-m-d H:i:s',
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s',
        'deleted_at' => 'date:Y-m-d H:i:s',
    ];

    protected $dates = [
        'closed_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class)->select('id', 'ticket_id', 'commenter_id', 'comment', 'created_at', 'updated_at');
    }

    public function assignedTo(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'assigned_to')->select('id', 'name');
    }

    public function closedBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'closed_by')->select('id', 'name');
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(TicketPrioty::class)->select('id', 'name');
    }
}
