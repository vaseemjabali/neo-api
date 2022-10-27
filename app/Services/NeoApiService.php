<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NeoApiService {
    
    /**
     * Get neo stats
     *
     * @param string $fromDate
     * @param string $toDate
     */
    public function getNeoStats($fromDate, $toDate)
    {
        $response = Http::get(config('neoapi.api_url').'/feed?start_date='.$fromDate.'&end_date='.$toDate.'&api_key=DEMO_KEY');
        if($response->successful()){
            $asteroids = $this->getAllAsteroidsAndStats($response);
            $data['fastest_asteroid'] = $this->getFastestAsteroid($asteroids['all_asteroids']);
            $data['closest_asteroid'] = $this->getClosestAsteroid($asteroids['all_asteroids']);
            $data['avg_asteroid_size'] = $this->getAverageSizeOfAsteroids($asteroids['all_asteroids']);
            $data['days'] = array_keys($response['near_earth_objects']);
            $data['stats'] = $asteroids['asteroid_stats'];

            return $data;
        }
        return null;
    }

    private function getAllAsteroidsAndStats($response){
        $days = array_keys($response['near_earth_objects']);
        $allAsteroids = [];
        $asteroidStats = [];
        for($i = 0; $i < count($days); $i++){
            array_push($allAsteroids, $response['near_earth_objects'][$days[$i]]);
            array_push($asteroidStats, count($response['near_earth_objects'][$days[$i]]));
        }
        $data['all_asteroids'] = array_merge(...$allAsteroids);
        $data['asteroid_stats'] = $asteroidStats;
        return $data;
    }

    private function getFastestAsteroid($asteroids){
        $fastestAsteroid = $asteroids[0];
        $fastestAsteroidSpeed = $fastestAsteroid['close_approach_data'][0]['relative_velocity']['kilometers_per_hour'];
        for($i = 0; $i < count($asteroids); $i++){
            if($asteroids[$i]['close_approach_data'][0]['relative_velocity']['kilometers_per_hour'] > $fastestAsteroidSpeed){
                $fastestAsteroid = $asteroids[$i];
                $fastestAsteroidSpeed = $fastestAsteroid['close_approach_data'][0]['relative_velocity']['kilometers_per_hour'];
            }
        }
        $data['fastest_asteroid_id'] = $fastestAsteroid['id'];
        $data['fastest_asteroid_speed'] = $fastestAsteroidSpeed.' '.'km/h';
        return $data;
    }

    private function getClosestAsteroid($asteroids){
        $closestAsteroid = $asteroids[0];
        $closestAsteroidDistance = $closestAsteroid['close_approach_data'][0]['miss_distance']['kilometers'];
        for($i = 0; $i < count($asteroids); $i++){
            if($asteroids[$i]['close_approach_data'][0]['miss_distance']['kilometers'] < $closestAsteroidDistance){
                $closestAsteroid = $asteroids[$i];
                $closestAsteroidDistance = $closestAsteroid['close_approach_data'][0]['miss_distance']['kilometers'];
            }
        }
        $data['closest_asteroid_id'] = $closestAsteroid['id'];
        $data['closest_asteroid_distance'] = $closestAsteroidDistance.' '.'km';
        return $data;
    }

    private function getAverageSizeOfAsteroids($asteroids){
        $diameter = [];
        for($i = 0; $i < count($asteroids); $i++){
            $diameter[] = $asteroids[$i]['estimated_diameter']['kilometers']['estimated_diameter_max'];
        }
        return array_sum($diameter) / count($asteroids).' '.'km';
    }

    private function getAsteroidsStatsByDay($days, $response){
        $asteroidStats = [];
        for($i = 0; $i < count($days); $i++){
            $asteroidStats[] = count($response['near_earth_objects'][$days[$i]]);
        }
        return array_merge(...$asteroidStats);
    }
}