<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * SECURITY: Use $guarded instead of $fillable to be more restrictive
     * Only guard these critical fields from mass assignment
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Fields that should ONLY be updated by admin
     */
    protected $adminOnlyFields = [
        'seat_number',
        'status',
        'seat_status',
        'test_score',
        'passed',
        'attendance',
        'attendance_marked_at',
        'rejection_message',
        'reading_score',
        'listening_score',
        'speaking_score',
        'writing_score',
        'test_format',
        'listening_score_pbt',
        'structure_score_pbt',
        'reading_score_pbt',
        'total_score_pbt',
        'is_score_validated',
        'score_validated_at',
        'raw_listening_pbt',
        'raw_structure_pbt',
        'raw_reading_pbt',
    ];

    /**
     * Fields that should ONLY be updated by system
     */
    protected $systemOnlyFields = [
        'verification_token',
        'temp_seat_number',
    ];

    protected $attributes = [
        'seat_number' => null,  // Default value for seat_number
    ];

    protected $casts = [
        'birth_date' => 'date',
        'payment_date' => 'datetime',
        'test_date' => 'date',
        'test_score' => 'integer',
        'reading_score' => 'decimal:1',
        'listening_score' => 'decimal:1',
        'speaking_score' => 'decimal:1',
        'writing_score' => 'decimal:1',
        'listening_score_pbt' => 'integer',
        'structure_score_pbt' => 'integer',
        'reading_score_pbt' => 'integer',
        'total_score_pbt' => 'integer',
        'passed' => 'boolean',
        'test_format' => 'string',
        'attendance_marked_at' => 'datetime',
        'is_score_validated' => 'boolean',
        'score_validated_at' => 'datetime',
        'raw_listening_pbt' => 'integer',
        'raw_structure_pbt' => 'integer',
        'raw_reading_pbt' => 'integer',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    // Accessor to get academic level from study program
    public function getAcademicLevelAttribute()
    {
        // Try to get from study program first
        if ($this->studyProgram) {
            $levelMap = [
                'bachelor' => 'undergraduate',
                'sarjana' => 'undergraduate',
                's1' => 'undergraduate',
                'undergraduate' => 'undergraduate',
                'master' => 'master',
                'magister' => 'master',
                's2' => 'master',
                'graduate' => 'master',
                'doctor' => 'doctorate',
                'doktor' => 'doctorate',
                's3' => 'doctorate',
                'doctoral' => 'doctorate',
                'doctorate' => 'doctorate',
            ];

            $rawLevel = strtolower(trim($this->studyProgram->level));

            // Check exact match first
            if (isset($levelMap[$rawLevel])) {
                return $levelMap[$rawLevel];
            }

            // Check if level contains keywords
            if (str_contains($rawLevel, 's1') || str_contains($rawLevel, 'sarjana') || str_contains($rawLevel, 'bachelor')) {
                return 'undergraduate';
            }
            if (str_contains($rawLevel, 's2') || str_contains($rawLevel, 'magister') || str_contains($rawLevel, 'master')) {
                return 'master';
            }
            if (str_contains($rawLevel, 's3') || str_contains($rawLevel, 'doktor') || str_contains($rawLevel, 'doctor')) {
                return 'doctorate';
            }

            return $rawLevel;
        }

        return $this->attributes['academic_level'] ?? 'undergraduate';
    }

    public function getAcademicLevelDisplayAttribute()
    {
        $academicLevel = $this->academic_level;
        $levelDisplay = [
            'undergraduate' => 'SARJANA S1',
            'master' => 'MAGISTER S2',
            'doctorate' => 'DOKTOR S3',
        ];

        return $levelDisplay[$academicLevel] ?? $academicLevel;
    }

    public function getAcademicLevelFullAttribute()
    {
        $academicLevel = $this->academic_level;
        $levelFull = [
            'undergraduate' => 'Sarjana S1',
            'master' => 'Magister S2',
            'doctorate' => 'Doktor S3',
        ];

        return $levelFull[$academicLevel] ?? $academicLevel;
    }

    public function getExamTypeAttribute()
    {
        $academicLevel = $this->academic_level;
        $examType = [
            'undergraduate' => 'skripsi',
            'master' => 'tesis',
            'doctorate' => 'disertasi',
        ];

        return $examType[$academicLevel] ?? 'skripsi';
    }

    // Accessor to get the effective seat number based on status
    public function getEffectiveSeatNumberAttribute()
    {
        if ($this->seat_status === 'confirmed') {
            return $this->seat_number;
        } elseif ($this->seat_status === 'reserved') {
            return $this->temp_seat_number ?? 'Belum Ditentukan';
        }
        return $this->seat_number ?? 'Belum Ditentukan';
    }

    // Check if seat is confirmed
    public function isSeatConfirmed()
    {
        return $this->seat_status === 'confirmed';
    }

    // Check if seat is reserved (pending verification)
    public function isSeatReserved()
    {
        return $this->seat_status === 'reserved';
    }

    // Check if participant is present
    public function isPresent()
    {
        return $this->attendance === 'present';
    }

    // Check if participant is absent
    public function isAbsent()
    {
        return $this->attendance === 'absent';
    }

    // Check if participant has permission
    public function hasPermission()
    {
        return $this->attendance === 'permission';
    }

    /**
     * Helper method to normalize academic level to one of three standard values
     * Returns: 'undergraduate', 'master', or 'doctorate'
     */
    private function getNormalizedAcademicLevel()
    {
        // Try to get from study program first
        if ($this->studyProgram) {
            $levelMap = [
                'bachelor' => 'undergraduate',
                'sarjana' => 'undergraduate',
                's1' => 'undergraduate',
                'undergraduate' => 'undergraduate',
                'master' => 'master',
                'magister' => 'master',
                's2' => 'master',
                'graduate' => 'master',
                'doctor' => 'doctorate',
                'doktor' => 'doctorate',
                's3' => 'doctorate',
                'doctoral' => 'doctorate',
                'doctorate' => 'doctorate',
            ];

            $rawLevel = strtolower(trim($this->studyProgram->level));

            // Check exact match first
            if (isset($levelMap[$rawLevel])) {
                return $levelMap[$rawLevel];
            }

            // Check if level contains keywords
            if (strpos($rawLevel, 's1') !== false || strpos($rawLevel, 'sarjana') !== false || strpos($rawLevel, 'bachelor') !== false) {
                return 'undergraduate';
            }
            if (strpos($rawLevel, 's2') !== false || strpos($rawLevel, 'magister') !== false || strpos($rawLevel, 'master') !== false) {
                return 'master';
            }
            if (strpos($rawLevel, 's3') !== false || strpos($rawLevel, 'doktor') !== false || strpos($rawLevel, 'doctor') !== false) {
                return 'doctorate';
            }
        }

        // Fallback to attributes
        if (isset($this->attributes['academic_level'])) {
            $attrLevel = strtolower(trim($this->attributes['academic_level']));

            // Check keywords in attribute
            if (
                strpos($attrLevel, 's1') !== false || strpos($attrLevel, 'sarjana') !== false ||
                strpos($attrLevel, 'bachelor') !== false || strpos($attrLevel, 'undergraduate') !== false
            ) {
                return 'undergraduate';
            }
            if (
                strpos($attrLevel, 's2') !== false || strpos($attrLevel, 'magister') !== false ||
                strpos($attrLevel, 'master') !== false
            ) {
                return 'master';
            }
            if (
                strpos($attrLevel, 's3') !== false || strpos($attrLevel, 'doktor') !== false ||
                strpos($attrLevel, 'doctor') !== false
            ) {
                return 'doctorate';
            }
        }

        // Default to undergraduate (safest default for majority of cases)
        return 'undergraduate';
    }

    // Accessor to dynamically calculate passed status based on academic level thresholds
    public function getPassedAttribute()
    {
        // If there's no test score, participant is not passed
        if (!$this->test_score) {
            return null;
        }

        // 1. Priority: Check if the specific study program has a passing grade set
        if ($this->studyProgram && $this->studyProgram->passing_grade !== null) {
            return $this->test_score >= $this->studyProgram->passing_grade;
        }

        // 2. Fallback: Get normalized academic level and use hardcoded defaults if study program is missing or has no grade
        $normalizedLevel = $this->getNormalizedAcademicLevel();

        // Apply thresholds based on normalized level (UHO Standard)
        switch ($normalizedLevel) {
            case 'undergraduate':
                // S1/Diploma: >= 410 = PASS
                return $this->test_score >= 410;

            case 'master':
                // S2: >= 450 = PASS
                return $this->test_score >= 450;

            case 'doctorate':
                // S3: >= 500 = PASS
                return $this->test_score >= 500;

            default:
                // Fallback to undergraduate rules
                return $this->test_score >= 410;
        }
    }

    /**
     * Get photo URL with fallback handling
     * Returns route to download photo or null if not available
     */
    public function getPhotoUrlAttribute()
    {
        if (!$this->photo_path) {
            return null;
        }

        // Check if photo exists in private storage disk
        if (\Storage::disk('private')->exists($this->photo_path)) {
            return route('participant.file.download', ['id' => $this->id, 'type' => 'photo']);
        }

        return null;
    }


    /**
     * SECURITY: Safe update method for participant-initiated updates
     */
    public function safeParticipantUpdate(array $data)
    {
        $allowedFields = ['payment_date', 'payment_proof_path', 'test_category', 'schedule_id'];
        $safeData = array_intersect_key($data, array_flip($allowedFields));
        return $this->update($safeData);
    }

    /**
     * SECURITY: Safe update method for admin updates
     */
    /**
     * SECURITY: Safe update method for admin updates
     */
    public function safeAdminUpdate(array $data)
    {
        $filteredData = array_diff_key($data, array_flip($this->systemOnlyFields));
        return $this->update($filteredData);
    }

    /**
     * Get the most recent previous participant record with the same NIM
     */
    public function getPreviousParticipationAttribute()
    {
        return self::where('nim', $this->nim)
            ->where('id', '!=', $this->id)
            ->whereNotNull('payment_proof_path')
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
