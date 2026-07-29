<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'ticket_id',
        'museum_id',
        'visit_date',
        'adult_qty',
        'student_qty',
        'child_qty',
        'total_price',
        'status',
        'ticket_summary',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function museum()
    {
        return $this->belongsTo(Museum::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function getTicketItemsAttribute(): array
    {
        $items = json_decode($this->ticket_summary ?? '[]', true);
        if (is_array($items) && ! empty($items)) {
            return array_map(function ($item) {
                return [
                    'ticket_name' => $item['ticket_name'] ?? 'Tiket Museum',
                    'qty' => (int) ($item['qty'] ?? 1),
                    'price' => (int) ($item['price'] ?? 0),
                    'subtotal' => (int) (($item['qty'] ?? 1) * ($item['price'] ?? 0)),
                ];
            }, $items);
        }

        $fallback = [];
        if ($this->adult_qty > 0) {
            $fallback[] = [
                'ticket_name' => 'Dewasa',
                'qty' => (int) $this->adult_qty,
                'price' => 0,
                'subtotal' => 0,
            ];
        }
        if ($this->student_qty > 0) {
            $fallback[] = [
                'ticket_name' => 'Pelajar',
                'qty' => (int) $this->student_qty,
                'price' => 0,
                'subtotal' => 0,
            ];
        }
        if ($this->child_qty > 0) {
            $fallback[] = [
                'ticket_name' => 'Anak-anak',
                'qty' => (int) $this->child_qty,
                'price' => 0,
                'subtotal' => 0,
            ];
        }

        return $fallback;
    }
}
