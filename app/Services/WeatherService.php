<?php

namespace App\Services;

use App\Models\District;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    /**
     * Get risk assessment for all districts based on rainfall.
     * Uses Open-Meteo API (Free, no key required).
     */
    public function getRiskAssessment()
    {
        // Cache the result for 1 hour to avoid hitting API limits and improve performance
        return Cache::remember('weather_risk_assessment', 3600, function () {
            $districts = District::whereNotNull('latitude')->whereNotNull('longitude')->get();
            
            $risks = [
                'high' => 0,
                'medium' => 0,
                'low' => 0,
                'none' => 0,
                'details' => [],
                'updated_at' => now()->setTimezone('Asia/Bangkok')->format('H:i')
            ];

            foreach ($districts as $district) {
                try {
                    // Fetch current weather and daily forecast (precipitation sum)
                    $response = Http::timeout(5)->get('https://api.open-meteo.com/v1/forecast', [
                        'latitude' => $district->latitude,
                        'longitude' => $district->longitude,
                        'daily' => 'precipitation_sum',
                        'timezone' => 'Asia/Bangkok',
                        'forecast_days' => 1
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        $rain = $data['daily']['precipitation_sum'][0] ?? 0; // mm

                        // Risk Logic based on 24h Rainfall
                        // > 90mm: High Risk (Heavy Rain)
                        // 35-90mm: Medium Risk (Moderate to Heavy)
                        // 10-35mm: Low Risk (Moderate)
                        // < 10mm: None/Low
                        
                        $level = 'none';
                        if ($rain >= 90) {
                            $level = 'high';
                            $risks['high']++;
                        } elseif ($rain >= 35) {
                            $level = 'medium';
                            $risks['medium']++;
                        } elseif ($rain >= 10) {
                            $level = 'low';
                            $risks['low']++;
                        } else {
                            $level = 'none';
                            $risks['none']++;
                        }

                        $risks['details'][] = [
                            'district' => $district->name,
                            'rain_mm' => $rain,
                            'level' => $level
                        ];
                    }
                } catch (\Exception $e) {
                    // Fallback or log error
                    continue;
                }
            }

            return $risks;
        });
    }
}
