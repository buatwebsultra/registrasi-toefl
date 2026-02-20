<!-- Reschedule Modal -->
<div class="modal fade" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rescheduleModalLabel">Pilih Jadwal Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Silakan pilih jadwal tes pengganti untuk peserta ini:</p>
                <form id="rescheduleForm" action="" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="new_schedule_id" class="form-label">Jadwal Tersedia</label>
                        <select class="form-select" name="new_schedule_id" id="new_schedule_id" required>
                            <option value="">-- Pilih Jadwal --</option>
                            @php
                                $isSuperAdmin = auth()->user()->isSuperAdmin();
                                $currentScheduleId = isset($schedule) ? $schedule->id : (isset($participant) ? $participant->schedule_id : null);

                                $query = \App\Models\Schedule::where('id', '!=', $currentScheduleId);

                                // SuperAdmin sees EVERYTHING. Others only see schedules with capacity.
                                if (!$isSuperAdmin) {
                                    $query->whereColumn('used_capacity', '<', 'capacity');
                                }

                                $availableSchedules = $query->orderBy('date', 'desc')->get();
                            @endphp
                            @foreach($availableSchedules as $s)
                                <option value="{{ $s->id }}">
                                    {{ $s->date->format('d M Y') }} - {{ $s->room }}
                                    ({{ $s->used_capacity }}/{{ $s->capacity }})
                                </option>
                            @endforeach
                        </select>
                        @if($availableSchedules->isEmpty())
                            <div class="text-danger mt-2 small">
                                <i class="fas fa-exclamation-circle"></i> Tidak ada jadwal lain yang tersedia. Silakan buat
                                jadwal baru terlebih dahulu.
                            </div>
                        @endif
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn-submit-reschedule">Pindahkan Peserta</button>
            </div>
        </div>
    </div>
</div>