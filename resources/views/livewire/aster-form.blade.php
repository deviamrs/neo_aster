<div>

    {{-- form goes hrere --}}

    <div class="row justify-content-md-center">

        <div class="col-md-6">
            <form class="row gy-4" wire:submit.prevent="fetchAteroidData">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="" for="autoSizingInput">From Date</label>
                        <input type="text" class="form-control start_date" id="autoSizingInput" placeholder="dd-mm-yyyy"
                            wire:model.defer="start_date">
                        @error('start_date')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">

                    <div class="form-group">
                        <label class="" for="autoSizingInputGroup">End Date</label>
                        <input type="text" class="form-control end_date" id="autoSizingInputGroup"
                            placeholder="dd-mm-yyyy" wire:model.defer="end_date">
                        @error('end_date')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Get Asteriod Data</button>
                </div>
            </form>

            <div wire:loading>
                Getting Data From The Sever ...
            </div>
        </div>


    </div>


    <hr>


    @if ($asteroid_array != null)
    @if ($asteroid_array->count() > 0 )

    @foreach ($asteroid_array as $key=>$asteroids)

    <h5 class="text-primary">Date : {{ $key }}</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Asteroid Id</th>
                <th scope="col">Name</th>
                <th scope="col">Miss Distance in Km</th>
                <th scope="col">Speed(Km/h)</th>
                <th scope="col">Size In Km</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($asteroids as $asteroid_id=>$asteroid)
                 <tr>
                    <td>{{ $asteroid['neo_reference_id'] }}</td>    
                    <td>{{ $asteroid['name'] }}</td>    
                    <td>{{ (int)$asteroid['close_approach_data'][0]['miss_distance']['kilometers'] }}</td>    
                    <td>{{ (int)$asteroid['close_approach_data'][0]['relative_velocity']['kilometers_per_hour'] }}</td>    
                    <td>{{ $asteroid['estimated_diameter']['kilometers']['estimated_diameter_max'] }}</td>    
                 </tr>    
            @endforeach
        </tbody>

    </table>
    @endforeach
    @endif
    @endif
   
     
    
    @if ($asteroid_array != null)
    @if ($dateArray)
       <div id="date-array-hold" data-date-array="{{ implode(' ', $dateArray) }}"></div> 
    @endif
    @endif
    
    
  
    @if ($asteroid_array != null)
      
    <div wire:poll.750ms>
    <div class="row row justify-content-md-center">
        <div class="col-md-6">
            <canvas id="myChart" width="400" height="400"></canvas>
        </div>
    </div>
    

    <script>
        var ctx = document.getElementById('myChart');
        var dateArray = document.getElementById('date-array-hold').getAttribute('data-date-array').split(' ');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dateArray,
                datasets: [{
                    label: 'No Of Ateroids',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        </script>

</div>

@endif

</div>
