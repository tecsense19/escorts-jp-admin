<?php

namespace App\Services;

class FirebaseNotification
{
    public static function sendFirebaseNotification($encodedData)
    {
        $headers = [
            'Authorization:key=' . env('FIREBASE_SERVER_KEY'),
            'Content-Type: application/json',
        ];

        // Your cURL code here
        $ch = curl_init();
            
        curl_setopt($ch, CURLOPT_URL, env('FIREBASE_NOTIFICATION_URL'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);

        // Set cURL options here...

        $result = curl_exec($ch);

        if ($result === FALSE) {
            return response()->json(['error' => 'Curl failed: ' . curl_error($ch)], 500);
        }

        // Close cURL handle
        curl_close($ch);

        return json_decode($result);
    }
}
