<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Museum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function create(Museum $museum)
    {
        $museum->load('tickets');

        return view('user.booking', compact('museum'));
    }

    public function store(Request $request, Museum $museum)
    {
        $museum->load('tickets');

        // Bangun rules validasi dinamis untuk tiap kategori tiket museum ini
        $ticketRules = [];
        foreach ($museum->tickets as $ticket) {
            $ticketRules['ticket_' . $ticket->id] = 'nullable|integer|min:0';
        }

        $request->validate(array_merge([
            'visit_date'             => 'required|date|after_or_equal:today',
            'nama_penanggung_jawab'  => 'required|string|max:255',
            'jumlah_anggota'         => 'required|integer|min:1',
            'kota_asal'              => 'required|string|max:255',
            'no_hp'                  => ['required', 'string', 'regex:/^[0-9+]{9,15}$/'],
        ], $ticketRules), [
            'visit_date.required'            => 'Tanggal kunjungan wajib diisi.',
            'visit_date.after_or_equal'      => 'Tanggal kunjungan tidak boleh sebelum hari ini.',
            'nama_penanggung_jawab.required' => 'Nama penanggung jawab wajib diisi.',
            'jumlah_anggota.required'        => 'Jumlah anggota wajib diisi.',
            'jumlah_anggota.min'             => 'Jumlah anggota minimal 1 orang.',
            'kota_asal.required'             => 'Kota/Negara asal wajib diisi.',
            'no_hp.required'                 => 'Nomor HP wajib diisi.',
            'no_hp.regex'                    => 'Nomor HP hanya boleh berisi angka (9-15 digit).',
        ]);


        if ($request->visit_date === now()->toDateString()
            && $museum->opening_time
            && $museum->closing_time
        ) {
            $openingTime = Carbon::parse($museum->opening_time);
            $closingTime = Carbon::parse($museum->closing_time);

            if (now()->lt($openingTime) || now()->gt($closingTime)) {
                $msg = "Museum \"{$museum->name}\" sudah tutup untuk hari ini (jam operasional "
                    . $openingTime->format('H:i') . ' - ' . $closingTime->format('H:i')
                    . '). Silakan pilih tanggal kunjungan lain.';

                return back()
                    ->withInput()
                    ->with('error', $msg)
                    ->with('swal_error', $msg);
            }
        }

        $total = 0;
        $ticketData = [];
        $adultQty = 0;
        $studentQty = 0;
        $childQty = 0;


        return DB::transaction(function () use ($request, $museum, $total, $ticketData, $adultQty, $studentQty, $childQty) {

            Booking::where('visit_date', $request->visit_date)
                ->whereNotIn('status', ['cancelled', 'failed'])
                ->lockForUpdate()
                ->get();

            foreach ($museum->tickets as $ticket) {
                $qty = (int) $request->input('ticket_' . $ticket->id, 0);

                if ($qty <= 0) {
                    continue;
                }

                // Hitung berapa banyak yang sudah terjual untuk kategori tiket ini,
                // KHUSUS di tanggal kunjungan yang dipilih.
                $sudahTerjual = $this->getSoldQty($ticket->id, $request->visit_date);
                $sisaKuota = $ticket->slot - $sudahTerjual;

                if ($qty > $sisaKuota) {
                    return back()
                        ->withInput()
                        ->with('error', "Kuota tiket \"{$ticket->ticket_name}\" untuk tanggal {$request->visit_date} tidak mencukupi. Sisa kuota: {$sisaKuota}.")
                        ->with('swal_error', "Kuota tiket \"{$ticket->ticket_name}\" untuk tanggal {$request->visit_date} tidak mencukupi. Sisa kuota: {$sisaKuota}.");
                }

                $total += $qty * $ticket->price;

                $ticketData[] = [
                    'ticket_id'   => $ticket->id,
                    'ticket_name' => $ticket->ticket_name,
                    'qty'         => $qty,
                    'price'       => (int) $ticket->price,
                ];

                $nameLower = strtolower($ticket->ticket_name);
                if (str_contains($nameLower, 'anak') || str_contains($nameLower, 'child')) {
                    $childQty += $qty;
                } elseif (str_contains($nameLower, 'pelajar') || str_contains($nameLower, 'mahasiswa') || str_contains($nameLower, 'student')) {
                    $studentQty += $qty;
                } else {
                    $adultQty += $qty;
                }
            }

            if (empty($ticketData)) {
                return back()
                    ->withInput()
                    ->with('error', 'Pilih minimal 1 tiket sebelum melanjutkan.')
                    ->with('swal_error', 'Pilih minimal 1 tiket sebelum melanjutkan.');
            }

            // Pastikan jumlah anggota yang diisi sesuai dengan total tiket yang dipilih
            $totalTiket = $adultQty + $studentQty + $childQty;
            if ((int) $request->jumlah_anggota !== $totalTiket) {
                return back()
                    ->withInput()
                    ->with('error', "Jumlah anggota ({$request->jumlah_anggota} orang) harus sama dengan total tiket yang dipilih ({$totalTiket} tiket).")
                    ->with('swal_error', "Jumlah anggota ({$request->jumlah_anggota} orang) harus sama dengan total tiket yang dipilih ({$totalTiket} tiket).");
            }

            $booking = Booking::create([
                'user_id'               => Auth::id(),
                'nama_penanggung_jawab' => $request->nama_penanggung_jawab,
                'jumlah_anggota'        => $request->jumlah_anggota,
                'kota_asal'             => $request->kota_asal,
                'no_hp'                 => $request->no_hp,
                'museum_id'      => $museum->id,
                'visit_date'     => $request->visit_date,
                'adult_qty'      => $adultQty,
                'student_qty'    => $studentQty,
                'child_qty'      => $childQty,
                'total_price'    => $total,
                'ticket_summary' => json_encode($ticketData),
                'status'         => 'pending',
            ]);

            return redirect()
                ->route('user.payment', $booking->id)
                ->with('success', 'Data booking berhasil disimpan. Silakan lanjutkan ke pembayaran.')
                ->with('swal_success', 'Data booking berhasil disimpan. Silakan lanjutkan ke pembayaran.');
        });
    }


    public function checkQuota(Request $request, Museum $museum)
    {
        $request->validate([
            'visit_date' => 'required|date',
        ]);

        $museum->load('tickets');

        $result = [];

        foreach ($museum->tickets as $ticket) {
            $terjual = $this->getSoldQty($ticket->id, $request->visit_date);

            $result[$ticket->id] = [
                'slot'   => $ticket->slot,
                'terjual' => $terjual,
                'sisa'   => max(0, $ticket->slot - $terjual),
            ];
        }

        return response()->json($result);
    }

    public function getSoldQty(int $ticketId, string $visitDate): int
    {
        return Booking::where('visit_date', $visitDate)
            ->whereNotIn('status', ['cancelled', 'failed'])
            ->get()
            ->sum(function ($booking) use ($ticketId) {
                $items = json_decode($booking->ticket_summary, true) ?? [];

                foreach ($items as $item) {
                    if (($item['ticket_id'] ?? null) == $ticketId) {
                        return $item['qty'];
                    }
                }

                return 0;
            });
    }
}
