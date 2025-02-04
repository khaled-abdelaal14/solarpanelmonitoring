<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class BatteryNotificationController extends Controller
{
    private $messaging;
    
    public function __construct()
    {
        // Initialize Firebase
        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/firebase-credentials.json'))
            ->withProjectId('alsooq-online-0x1');
            
        $this->messaging = $factory->createMessaging();
    }
    
    public function checkBatteryLevel(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'battery_level' => 'required|numeric|min:0|max:100',
            'device_token' => 'required|string'
        ]);
        
        $batteryLevel = $request->battery_level;
        $deviceToken = $request->device_token;
        
        // Check if battery level is low (e.g., below 20%)
        if ($batteryLevel < 20) {
            try {
                // Create notification message
                $message = CloudMessage::withTarget('token', $deviceToken)
                    ->withNotification(Notification::create(
                        'Battery Level Alert',
                        "Battery level is critically low: {$batteryLevel}%"
                    ))
                    ->withData(['battery_level' => (string)$batteryLevel]);
                
                // Send notification
                $this->messaging->send($message);
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Notification sent successfully',
                    'battery_level' => $batteryLevel
                ]);
                
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send notification: ' . $e->getMessage()
                ], 500);
            }
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Battery level is normal',
            'battery_level' => $batteryLevel
        ]);
    }
}
