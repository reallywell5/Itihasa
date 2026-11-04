<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Broadcast;
use App\Models\BroadcastRead;
use App\Models\SystemNotification;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Dispatch notification when a new booking is created (pending payment).
     */
    public function notifyBookingCreated(Booking $booking): void
    {
        $user = $booking->user;
        $museum = $booking->museum;
        $transaction = $booking->transaction;

        if (! $user || ! $transaction) {
            return;
        }

        $totalPax = ($booking->adult_qty ?? 0) + ($booking->student_qty ?? 0) + ($booking->child_qty ?? 0);
        $totalFormatted = 'Rp '.number_format($transaction->total_amount, 0, ',', '.');
        $museumName = $museum->name ?? 'Museum';

        // 1. Notifikasi ke Pengunjung (User)
        SystemNotification::create([
            'user_id' => $user->id,
            'category' => 'pesanan_baru',
            'title' => 'Pesanan Tiket Dibuat',
            'message' => "Pesanan tiket {$museumName} ({$totalPax} pax) dengan invoice {$transaction->invoice_code} berhasil dibuat. Total: {$totalFormatted}. Silakan selesaikan pembayaran sebelum batas waktu.",
            'action_url' => route('user.payment', $booking->id),
            'urgency' => 'info',
            'data' => [
                'booking_id' => $booking->id,
                'transaction_id' => $transaction->id,
                'invoice_code' => $transaction->invoice_code,
                'museum_name' => $museumName,
                'pax' => $totalPax,
            ],
        ]);

        // 2. Notifikasi ke Admin Pengelola Museum Terkait
        if ($museum) {
            $admins = User::where('role', 'admin')
                ->where('museum_id', $museum->id)
                ->get();

            foreach ($admins as $admin) {
                SystemNotification::create([
                    'user_id' => $admin->id,
                    'category' => 'pesanan_baru',
                    'title' => 'Pesanan Tiket Masuk',
                    'message' => "Pengunjung {$user->name} membuat pesanan baru {$totalPax} tiket ({$transaction->invoice_code}) untuk kunjungan tanggal ".Carbon::parse($booking->visit_date)->translatedFormat('d M Y').'.',
                    'action_url' => route('transactions.show', $transaction->id),
                    'urgency' => 'info',
                    'data' => [
                        'booking_id' => $booking->id,
                        'transaction_id' => $transaction->id,
                        'invoice_code' => $transaction->invoice_code,
                        'customer_name' => $user->name,
                        'pax' => $totalPax,
                    ],
                ]);
            }
        }
    }

    /**
     * Dispatch notification when payment is successful and QR ticket is issued.
     */
    public function notifyPaymentSuccess(Transaction $transaction): void
    {
        $booking = $transaction->booking;
        if (! $booking) {
            return;
        }

        $user = $booking->user;
        $museum = $booking->museum;
        $museumName = $museum->name ?? 'Museum';
        $totalFormatted = 'Rp '.number_format($transaction->total_amount, 0, ',', '.');
        $totalPax = ($booking->adult_qty ?? 0) + ($booking->student_qty ?? 0) + ($booking->child_qty ?? 0);

        // 1. Notifikasi ke Pengunjung (User)
        if ($user) {
            SystemNotification::create([
                'user_id' => $user->id,
                'category' => 'pembayaran_sukses',
                'title' => 'Pembayaran Berhasil & E-Tiket Terbit',
                'message' => "Pembayaran sebesar {$totalFormatted} untuk invoice {$transaction->invoice_code} telah lunas. E-Tiket QR Anda untuk {$museumName} telah aktif dan siap dipindai di gate masuk!",
                'action_url' => route('user.ticket', $transaction->id),
                'urgency' => 'success',
                'data' => [
                    'transaction_id' => $transaction->id,
                    'invoice_code' => $transaction->invoice_code,
                    'museum_name' => $museumName,
                ],
            ]);
        }

        // 2. Notifikasi ke Admin Museum
        if ($museum) {
            $admins = User::where('role', 'admin')
                ->where('museum_id', $museum->id)
                ->get();

            foreach ($admins as $admin) {
                SystemNotification::create([
                    'user_id' => $admin->id,
                    'category' => 'pembayaran_sukses',
                    'title' => 'Pembayaran Tiket Lunas',
                    'message' => "Transaksi {$transaction->invoice_code} ({$totalPax} pax - {$totalFormatted}) dari {$user?->name} telah berhasil dibayar.",
                    'action_url' => route('transactions.show', $transaction->id),
                    'urgency' => 'success',
                    'data' => [
                        'transaction_id' => $transaction->id,
                        'invoice_code' => $transaction->invoice_code,
                        'amount' => $transaction->total_amount,
                    ],
                ]);
            }
        }
    }

    /**
     * Dispatch notification when payment failed or was rejected.
     */
    public function notifyPaymentFailed(Transaction $transaction, ?string $reason = null): void
    {
        $booking = $transaction->booking;
        $user = $booking?->user;
        $museumName = $booking?->museum?->name ?? 'Museum';

        if (! $user) {
            return;
        }

        $reasonText = $reason ? "Alasan: {$reason}." : 'Waktu pembayaran telah habis atau transaksi tidak valid.';

        SystemNotification::create([
            'user_id' => $user->id,
            'category' => 'pembayaran_gagal',
            'title' => 'Pembayaran Gagal / Dibatalkan',
            'message' => "Pembayaran tiket {$museumName} ({$transaction->invoice_code}) tidak dapat diproses. {$reasonText}",
            'action_url' => route('user.profile'),
            'urgency' => 'danger',
            'data' => [
                'transaction_id' => $transaction->id,
                'invoice_code' => $transaction->invoice_code,
            ],
        ]);
    }

    /**
     * Dispatch notification when ticket is scanned and validated at museum gate.
     */
    public function notifyTicketValidated(Transaction $transaction, int $scannedPax, ?User $petugas = null): void
    {
        $booking = $transaction->booking;
        $user = $booking?->user;
        $museum = $booking?->museum;
        $museumName = $museum->name ?? 'Museum';

        // 1. Notifikasi ke Pengunjung (User)
        if ($user) {
            SystemNotification::create([
                'user_id' => $user->id,
                'category' => 'validasi_gate',
                'title' => 'Tiket Tervalidasi di Gate Masuk',
                'message' => "Selamat datang di {$museumName}! Tiket Anda ({$scannedPax} pengunjung) telah berhasil dicap dan divalidasi oleh petugas gate. Stempel resmi paspor budaya Anda telah diperbarui!",
                'action_url' => route('user.profile', ['tab' => 'paspor']),
                'urgency' => 'success',
                'data' => [
                    'transaction_id' => $transaction->id,
                    'invoice_code' => $transaction->invoice_code,
                    'museum_name' => $museumName,
                    'scanned_pax' => $scannedPax,
                ],
            ]);
        }

        // 2. Notifikasi ke Admin Museum
        if ($museum) {
            $admins = User::where('role', 'admin')
                ->where('museum_id', $museum->id)
                ->get();

            foreach ($admins as $admin) {
                SystemNotification::create([
                    'user_id' => $admin->id,
                    'category' => 'validasi_gate',
                    'title' => 'Pengunjung Masuk Gate',
                    'message' => "Tiket {$transaction->invoice_code} ({$scannedPax} pax) telah berhasil divalidasi masuk oleh staf scanner.",
                    'action_url' => route('transactions.show', $transaction->id),
                    'urgency' => 'info',
                    'data' => [
                        'transaction_id' => $transaction->id,
                        'invoice_code' => $transaction->invoice_code,
                        'scanned_pax' => $scannedPax,
                    ],
                ]);
            }
        }
    }

    /**
     * Dispatch notification when booking is cancelled.
     */
    public function notifyBookingCancelled(Booking $booking, string $reason): void
    {
        $user = $booking->user;
        $museumName = $booking->museum?->name ?? 'Museum';
        $transaction = $booking->transaction;

        if ($user) {
            SystemNotification::create([
                'user_id' => $user->id,
                'category' => 'tiket_batal',
                'title' => 'Booking Tiket Dibatalkan',
                'message' => "Booking tiket {$museumName} (".($transaction?->invoice_code ?? '-').") telah dibatalkan. Alasan: {$reason}",
                'action_url' => route('user.profile'),
                'urgency' => 'warning',
                'data' => [
                    'booking_id' => $booking->id,
                    'reason' => $reason,
                ],
            ]);
        }
    }

    /**
     * Sinkronisasi & nonaktifkan otomatis broadcast yang sudah melewati batas waktu.
     */
    public function syncExpiredBroadcasts(): int
    {
        return Broadcast::where('is_active', true)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['is_active' => false]);
    }

    /**
     * Get total unread notifications (system notifications + unread active broadcasts).
     */
    public function getUnreadCount(User $user): int
    {
        $this->syncExpiredBroadcasts();

        $unreadSystemCount = SystemNotification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Broadcast aktif yang belum dibaca oleh user
        $unreadBroadcastCount = Broadcast::active()
            ->forRole($user->role)
            ->whereDoesntHave('reads', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->count();

        return $unreadSystemCount + $unreadBroadcastCount;
    }

    /**
     * Get recent unified notifications for the bell dropdown (combined system + broadcast).
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function getRecentNotifications(User $user, int $limit = 8): Collection
    {
        $userRole = $user->role;

        // 1. Ambil Notifikasi Transaksi / Sistem
        $systemNotes = SystemNotification::where('user_id', $user->id)
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'key' => 'tx_'.$item->id,
                    'id' => $item->id,
                    'type' => 'transaction',
                    'category' => $item->category,
                    'category_label' => $this->getCategoryLabel($item->category),
                    'title' => $item->title,
                    'message' => $item->message,
                    'urgency' => $item->urgency ?? 'info',
                    'display_type' => 'bell',
                    'is_read' => (bool) $item->is_read,
                    'read_at' => $item->read_at,
                    'action_url' => $item->action_url,
                    'created_at' => $item->created_at,
                    'time_ago' => $item->created_at->diffForHumans(),
                ];
            });

        // 2. Ambil Broadcast Resmi yang aktif untuk role user
        $readBroadcastIds = $user->hasMany(BroadcastRead::class, 'user_id')->pluck('broadcast_id')->toArray();

        $broadcasts = Broadcast::active()
            ->forRole($userRole)
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($item) use ($readBroadcastIds) {
                $isRead = in_array($item->id, $readBroadcastIds);

                return [
                    'key' => 'bc_'.$item->id,
                    'id' => $item->id,
                    'type' => 'broadcast',
                    'category' => $item->category ?? 'umum',
                    'category_label' => 'Broadcast: '.ucfirst($item->category ?? 'Umum'),
                    'title' => $item->title,
                    'message' => $item->message,
                    'urgency' => $item->urgency ?? 'info',
                    'display_type' => $item->display_type ?? 'bell',
                    'is_read' => $isRead,
                    'read_at' => null,
                    'action_url' => null,
                    'created_at' => $item->created_at,
                    'time_ago' => $item->created_at->diffForHumans(),
                ];
            });

        // 3. Gabungkan dan urutkan berdasarkan waktu terbaru
        return $systemNotes->concat($broadcasts)
            ->sortByDesc('created_at')
            ->take($limit)
            ->values();
    }

    /**
     * Get active broadcast banners for current user.
     *
     * @return Collection<int, Broadcast>
     */
    public function getActiveBanners(User $user): Collection
    {
        $this->syncExpiredBroadcasts();

        return Broadcast::active()
            ->forRole($user->role)
            ->where('display_type', 'banner')
            ->whereDoesntHave('reads', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->get();
    }

    /**
     * Get active broadcast popups for current user.
     *
     * @return Collection<int, Broadcast>
     */
    public function getActivePopups(User $user): Collection
    {
        $this->syncExpiredBroadcasts();

        return Broadcast::active()
            ->forRole($user->role)
            ->where('display_type', 'popup')
            ->whereDoesntHave('reads', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->get();
    }

    /**
     * Mark an item as read.
     */
    public function markAsRead(User $user, string $itemKey): bool
    {
        if (str_starts_with($itemKey, 'tx_')) {
            $id = (int) substr($itemKey, 3);
            $note = SystemNotification::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if ($note) {
                $note->markAsRead();

                return true;
            }
        } elseif (str_starts_with($itemKey, 'bc_')) {
            $id = (int) substr($itemKey, 3);
            $broadcast = Broadcast::find($id);
            if ($broadcast) {
                $broadcast->markAsReadBy($user);

                return true;
            }
        }

        return false;
    }

    /**
     * Mark all notifications and active broadcasts as read for the user.
     */
    public function markAllAsRead(User $user): void
    {
        // 1. Mark system notifications
        SystemNotification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        // 2. Mark active broadcasts
        $activeBroadcasts = Broadcast::active()
            ->forRole($user->role)
            ->get();

        foreach ($activeBroadcasts as $b) {
            $b->markAsReadBy($user);
        }
    }

    /**
     * Translate category key into a friendly human badge.
     */
    public function getCategoryLabel(string $category): string
    {
        return match ($category) {
            'pesanan_baru' => '🎟️ Pesanan Tiket',
            'pembayaran_sukses' => '💰 Pembayaran Lunas',
            'pembayaran_gagal' => '❌ Pembayaran Gagal',
            'qr_terbit' => '📱 E-Tiket QR',
            'validasi_gate' => '✓ Validasi Gate',
            'tiket_batal' => '⚠️ Tiket Batal',
            'operasional' => '📢 Info Operasional',
            'pemeliharaan' => '🔧 Pemeliharaan',
            'kebijakan' => '📜 Kebijakan Baru',
            'darurat' => '🚨 Perhatian Khusus',
            default => '🔔 Notifikasi Sistem',
        };
    }
}
