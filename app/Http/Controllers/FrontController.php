<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



use Illuminate\Support\Facades\Http;

class FrontController extends Controller
{


    public $asteroid_array = null;
    public $dateArray = [];
    public $dateWiseAsteroidCount = [];
    public $maxSpeedArray =  [];
    public $averageSize = null;
    public $closestAsteroid = null;

      
    
    // plucking dates fronm ateroid array

    public function pluckDatesArray()
    {
        if ($this->asteroid_array != null) {
            foreach ($this->asteroid_array as $key => $value) {


                array_push($this->dateArray, $key);
            }
        }
        
    }

      
    // calulating date wise asteroid count

    public function pluckDateWiseAteroidCount()
    {
        if ($this->asteroid_array != null) {
            foreach ($this->asteroid_array as $key => $asteroids) {


                array_push($this->dateWiseAsteroidCount, count($asteroids));
            }
        }
       
    }

      
    // calulating max speed of an atsteroid

    public function maxSpeed(){
        if ($this->asteroid_array != null) {

          
             
            foreach ($this->asteroid_array as $key => $asteroids) {
                 
                foreach ($asteroids as $key2 => $asteroid) {
                    
                    array_push($this->maxSpeedArray,  $asteroid['close_approach_data'][0]['relative_velocity']['kilometers_per_hour']);
                }

            }

            $this->maxSpeedArray = max($this->maxSpeedArray);
        }
    }
  

     // calulating average size of  atsteroid

    public function averageSize(){
        if ($this->asteroid_array != null) {
             
            $singleArrayOfSize = [];
            foreach ($this->asteroid_array as $key => $asteroids) {
                foreach ($asteroids as $key2 => $asteroid) {   
                    array_push($singleArrayOfSize,  $asteroid['estimated_diameter']['kilometers']['estimated_diameter_max']);
                }
            }
            $singleArrayOfSize = collect($singleArrayOfSize)->sum() / collect($singleArrayOfSize)->count() ;

            $this->averageSize = $singleArrayOfSize ;

        }
    }
 

    // calulating closest asteroid from earth

    public function closestRange(){
        if ($this->asteroid_array != null) {
             
            $closeArrayOfSize = [];
            foreach ($this->asteroid_array as $key => $asteroids) {
                foreach ($asteroids as $key2 => $asteroid) {   
                    array_push($closeArrayOfSize,  (int)$asteroid['close_approach_data'][0]['miss_distance']['kilometers'] );
                }
            }
           
           
 
            $this->closestAsteroid = min($closeArrayOfSize) ;

        }
    }



     // validating and fetch data from external server


    public function fetchAteroidData(Request $request)
    {

        // validating the input fields

        $request->validate(
            [
                'start_date' => 'required|date|date_format:Y-m-d|before_or_equal:now',
                'end_date' => 'required|date|date_format:Y-m-d|after_or_equal:start_date',
            ]
        );


        // using laravel http library we are going to fetch data from the api



        $response =  Http::timeout(7)->get(
            'https://api.nasa.gov/neo/rest/v1/feed',

            // pass get get parameters 

            [
                'start_date' => $request->start_date,
                'end_date'  =>  $request->end_date,
                'detailed'  => true,
                'api_key'   => 'WhU9lvkI4ONwbBaxKLim9oy68kN7GkYf1Os8thoM',
            ]


        )->collect();

        $near_earth_objects  =  $response['near_earth_objects'];

        // making of colection of an array
        $this->asteroid_array = collect($near_earth_objects);

     

        $this->pluckDatesArray();
        $this->pluckDateWiseAteroidCount();
        $this->maxSpeed();
        $this->averageSize();
        $this->closestRange();

      

        
        // return view from the controller 

        return view('neo.home')

        // passing data to view

        ->withAsteroidArray($this->asteroid_array)
        ->withDateArray($this->dateArray)
        ->withDateWiseAsteroidCount($this->dateWiseAsteroidCount)
        ->withMaxSpeed($this->maxSpeedArray)
        ->withAverageSize($this->averageSize)
        ->withMissDistance($this->closestAsteroid);



    }


    
}
