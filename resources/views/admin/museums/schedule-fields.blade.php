@php
    $dayLabels = \App\Models\Museum::dayLabels();
@endphp

<div>
    <label class="block text-sm font-semibold text-zinc-700 mb-3">
        Jadwal Operasional
    </label>

    <div class="space-y-3">
        @foreach ($dayLabels as $dayKey => $dayLabel)
            @php
                $daySchedule = $schedule[$dayKey] ?? ['closed' => true, 'sessions' => []];
                $isClosed = $daySchedule['closed'] ?? true;
                $sessions = $daySchedule['sessions'] ?? [];
                if (empty($sessions)) {
                    $sessions = [['open' => '', 'close' => '']];
                }
            @endphp

            <div class="border border-zinc-200 rounded-xl p-4 day-schedule-row" data-day="{{ $dayKey }}">

                <div class="flex items-center justify-between mb-3">
                    <span class="font-semibold text-zinc-800 text-sm w-20">{{ $dayLabel }}</span>

                    <label class="flex items-center gap-2 text-xs text-zinc-500 cursor-pointer">
                        <input type="checkbox"
                               name="operational_hours[{{ $dayKey }}][closed]"
                               value="1"
                               class="closed-toggle"
                               {{ $isClosed ? 'checked' : '' }}
                               onchange="toggleDayClosed(this)">
                        Libur hari ini
                    </label>
                </div>

                <div class="sessions-container space-y-2 {{ $isClosed ? 'hidden' : '' }}">
                    @foreach ($sessions as $i => $session)
                        <div class="flex items-center gap-2 session-row">
                            <input type="time"
                                   name="operational_hours[{{ $dayKey }}][sessions][{{ $i }}][open]"
                                   value="{{ $session['open'] ?? '' }}"
                                   class="flex-1 rounded-lg border border-zinc-200 px-3 py-2 text-sm">
                            <span class="text-zinc-400 text-sm">s/d</span>
                            <input type="time"
                                   name="operational_hours[{{ $dayKey }}][sessions][{{ $i }}][close]"
                                   value="{{ $session['close'] ?? '' }}"
                                   class="flex-1 rounded-lg border border-zinc-200 px-3 py-2 text-sm">
                            <button type="button" onclick="removeSessionRow(this)"
                                    class="px-2 text-red-500 hover:text-red-700 text-sm">✕</button>
                        </div>
                    @endforeach
                </div>

                <button type="button" onclick="addSessionRow(this)"
                        class="mt-2 text-xs font-semibold text-blue-600 hover:text-blue-700 add-session-btn {{ $isClosed ? 'hidden' : '' }}">
                    + Tambah Sesi
                </button>

            </div>
        @endforeach
    </div>

    <p class="text-xs text-zinc-400 mt-2">
        Maksimal 2 sesi per hari (misal: sesi pagi dan sesi siang dengan jeda istirahat di antaranya).
    </p>
</div>

<script>
function toggleDayClosed(checkbox) {
    const row = checkbox.closest('.day-schedule-row');
    const sessionsContainer = row.querySelector('.sessions-container');
    const addBtn = row.querySelector('.add-session-btn');

    if (checkbox.checked) {
        sessionsContainer.classList.add('hidden');
        addBtn.classList.add('hidden');
    } else {
        sessionsContainer.classList.remove('hidden');
        addBtn.classList.remove('hidden');
    }
}

function addSessionRow(button) {
    const row = button.closest('.day-schedule-row');
    const dayKey = row.dataset.day;
    const container = row.querySelector('.sessions-container');
    const existingRows = container.querySelectorAll('.session-row');

    if (existingRows.length >= 2) {
        alert('Maksimal 2 sesi per hari.');
        return;
    }

    const index = existingRows.length;
    const div = document.createElement('div');
    div.className = 'flex items-center gap-2 session-row';
    div.innerHTML = `
        <input type="time" name="operational_hours[${dayKey}][sessions][${index}][open]"
               class="flex-1 rounded-lg border border-zinc-200 px-3 py-2 text-sm">
        <span class="text-zinc-400 text-sm">s/d</span>
        <input type="time" name="operational_hours[${dayKey}][sessions][${index}][close]"
               class="flex-1 rounded-lg border border-zinc-200 px-3 py-2 text-sm">
        <button type="button" onclick="removeSessionRow(this)"
                class="px-2 text-red-500 hover:text-red-700 text-sm">✕</button>
    `;
    container.appendChild(div);
}

function removeSessionRow(button) {
    const container = button.closest('.sessions-container');
    const rows = container.querySelectorAll('.session-row');

    if (rows.length <= 1) {
        // minimal 1 baris tersisa, cukup kosongkan isinya
        const inputs = button.closest('.session-row').querySelectorAll('input');
        inputs.forEach(input => input.value = '');
        return;
    }

    button.closest('.session-row').remove();
}
</script>
