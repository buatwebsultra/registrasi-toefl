<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage; // Added for file export

class ActivityLogger
{
    /**
     * Log an activity.
     *
     * @param string $action
     * @param string|null $description
     * @param string|null $userType ('admin' or 'participant')
     * @param int|null $userId
     * @return ActivityLog|null
     */
    public static function log($action, $description = null, $userType = null, $userId = null)
    {
        // Skip logging for Super Admins
        // Assuming 'isSuperAdmin()' is a method on your User model
        // or you have another way to identify a Super Admin (e.g., role check)
        if (Auth::check() && Auth::user()->isSuperAdmin()) {
            return null; // Do not log activities for Super Admins
        }

        $userName = 'Guest';
        
        if (!$userType) {
            if (Auth::check()) {
                $userType = 'admin';
                $userId = Auth::id();
                $userName = Auth::user()->name;
            } elseif (session()->has('participant_id')) {
                $userType = 'participant';
                $userId = session('participant_id');
                $participant = \App\Models\Participant::find($userId);
                $userName = $participant ? $participant->name : 'Unknown Participant';
            }
        } else {
            if ($userType === 'admin' && $userId) {
                $user = \App\Models\User::find($userId);
                $userName = $user ? $user->name : 'Unknown Admin';
            } elseif ($userType === 'participant' && $userId) {
                $participant = \App\Models\Participant::find($userId);
                $userName = $participant ? $participant->name : 'Unknown Participant';
            }
        }

        $logData = [
            'user_id' => $userId,
            'user_type' => $userType,
            'user_name' => $userName,
            'action' => $action,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ];

        // Log to database
        $activityLog = ActivityLog::create($logData);

        // Add timestamp for file export
        $logData['created_at'] = $activityLog->created_at->toDateTimeString();

        // Export to monthly TXT file
        self::exportToFile($logData);

        return $activityLog;
    }

    /**
     * Exports the activity log data to a monthly TXT file.
     *
     * @param array $logData
     * @return void
     */
    protected static function exportToFile(array $logData)
    {
        $fileName = 'activity_log_' . date('Y-m') . '.txt';
        $filePath = 'activity_logs/' . $fileName; // Stored in storage/app/activity_logs/

        // Format log entry for human readability
        $timestamp = $logData['created_at'] ?? now()->toDateTimeString();
        $userType = strtoupper($logData['user_type'] ?? 'UNKNOWN');
        $userName = $logData['user_name'] ?? 'Unknown';
        $action = $logData['action'] ?? 'No Action';
        $description = $logData['description'] ?? 'No Description';
        $ipAddress = $logData['ip_address'] ?? 'Unknown IP';
        $userAgent = $logData['user_agent'] ?? 'Unknown User Agent';

        // Create formatted log entry
        $logEntry = "========================================\n";
        $logEntry .= "Timestamp: {$timestamp}\n";
        $logEntry .= "User Type: {$userType}\n";
        $logEntry .= "User Name: {$userName}\n";
        $logEntry .= "Action: {$action}\n";
        $logEntry .= "Description: {$description}\n";
        $logEntry .= "IP Address: {$ipAddress}\n";
        $logEntry .= "User Agent: {$userAgent}\n";
        $logEntry .= "========================================\n\n";

        // Append to the file. Storage will create directory if it doesn't exist.
        Storage::disk('local')->append($filePath, $logEntry);
    }
}
