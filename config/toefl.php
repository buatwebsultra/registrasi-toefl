<?php

return [
    // Standar kelulusan TOEFL
    'passing_score' => 80,  // Skor minimum untuk lulus TOEFL
    
    // Standar untuk masing-masing section TOEFL
    'section_scores' => [
        'reading' => [
            'max' => 30,
            'passing' => 20,  // Skor minimum untuk lulus section Reading
        ],
        'listening' => [
            'max' => 30,
            'passing' => 20,  // Skor minimum untuk lulus section Listening
        ],
        'speaking' => [
            'max' => 30,
            'passing' => 20,  // Skor minimum untuk lulus section Speaking
        ],
        'writing' => [
            'max' => 30,
            'passing' => 20,  // Skor minimum untuk lulus section Writing
        ],
    ],
    
    // Definisi kategori skor
    'score_categories' => [
        'excellent' => ['min' => 100, 'max' => 120, 'description' => 'Excellent (Sangat Baik)'],
        'good' => ['min' => 80, 'max' => 99, 'description' => 'Good (Baik)'],
        'fair' => ['min' => 60, 'max' => 79, 'description' => 'Fair (Cukup)'],
        'below_expectation' => ['min' => 0, 'max' => 59, 'description' => 'Below Expectation (Di bawah Ekspektasi)'],
    ],
];