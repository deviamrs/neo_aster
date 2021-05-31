<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Http;

class FrontController extends Controller
{


    public $asteroid_array = null;
    public $dateArray = [];
    public $dateWiseAsteroidCount = [];

      
    public function pluckDatesArray()
    {
        if ($this->asteroid_array != null) {
            foreach ($this->asteroid_array as $key => $value) {


                array_push($this->dateArray, $key);
            }
        }
        
    }

    public function pluckDateWiseAteroidCount()
    {
        if ($this->asteroid_array != null) {
            foreach ($this->asteroid_array as $key => $asteroids) {


                array_push($this->dateWiseAsteroidCount, count($asteroids));
            }
        }
       
    }



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

        // dd($response);



        $near_earth_objects  =  $response['near_earth_objects'];

        // dd($response['near_earth_objects']);

        $this->asteroid_array = collect($near_earth_objects);

        //   dd($this->asteroid_array);

        $this->pluckDatesArray();
        $this->pluckDateWiseAteroidCount();


        return view('neo.home')->withAsteroidArray($this->asteroid_array)->withDateArray($this->dateArray)->withDateWiseAsteroidCount($this->dateWiseAsteroidCount);
    }


    
}
