<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Illuminate\Support\Arr;

class AsterForm extends Component
{   
     
    public $start_date;
    public $end_date;
    
    public $asteroid_array = null;
   
    public $dateArray = [];

    protected $rules = [
        'start_date' => 'required|date|date_format:Y-m-d|before_or_equal:now',
        'end_date' => 'required|date|date_format:Y-m-d|after_or_equal:start_date',
    ];
     


    public function fetchAteroidData(){
         
        
        // validating the input fields

        $this->validate();

         
        // using laravel http library we are going to fetch data from the api
        
        

        $response =  Http::get('https://api.nasa.gov/neo/rest/v1/feed', 
        
        // pass get get parameters 

        [
          'start_date' => $this->start_date ,
          'end_date'  => $this->end_date ,
          'detailed'  => true,
          'api_key'   => 'DEMO_KEY', 
        ] 
        
        
        )->collect();
    
       $near_earth_objects  = $response['near_earth_objects'];
 
        
       $this->asteroid_array = collect($near_earth_objects);

    //   dd($this->asteroid_array);
        
       $this->pluckDatesArray();

    }


    public function pluckDatesArray(){

         if ($this->asteroid_array != null) {
             foreach ($this->asteroid_array as $key=>$value) {
                 
                
                 array_push($this->dateArray , $key);
                     
                 
             }
              
            // $this->dateArray =  implode(',' , $this->dateArray);
         }

        //  dd($this->dateArray);

    }


    public function render()
    {
        return view('livewire.aster-form');
    }
}
