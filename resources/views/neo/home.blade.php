<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>Neo Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    @livewireStyles
</head>

<body>

    <div id="neo-app">

        <div class="container">
            <h3 class="mt-5 text-center text-primary">Neo Asteriod App</h3>

            <hr>

            <div>

                {{-- form goes hrere --}}

                <div class="row justify-content-md-center">

                    <div class="col-md-6">
                        <form class="row gy-4" action="{{ route('fetchAteroidData')}}" method="POST">
                            @csrf
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="" for="autoSizingInput">From Date</label>
                                    <input type="text" class="form-control start_date" id="autoSizingInput"
                                        placeholder="dd-mm-yyyy" name="start_date" value= " {{ old('start_date') }} ">
                                    @error('start_date')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="" for="autoSizingInputGroup">End Date</label>
                                    <input type="text" class="form-control end_date" id="autoSizingInputGroup"
                                        placeholder="dd-mm-yyyy" name="end_date" value="{{ old('end_date') }}">
                                    @error('end_date')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Get Asteriod Data</button>
                            </div>
                        </form>


                    </div>


                </div>


                <hr>


                @isset($asteroidArray)
                @if ($asteroidArray->count() > 0 )

                @foreach ($asteroidArray as $key=>$asteroids)

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
                            <td>{{ (int)$asteroid['close_approach_data'][0]['relative_velocity']['kilometers_per_hour'] }}
                            </td>
                            <td>{{ $asteroid['estimated_diameter']['kilometers']['estimated_diameter_max'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
                @endforeach
                @endif
                @endisset

              
                @isset($dateArray)
                   <div id="date-array-hold" data-date-array="{{ implode(' ', $dateArray) }}" data-asteroid-count-array="{{ implode(' ', $dateWiseAsteroidCount) }}"></div>
                @endisset

           
            







            <div class="row row justify-content-md-center">
                <div class="col-md-6">
                    <canvas id="myChart" width="400" height="400"></canvas>
                </div>
            </div>







        </div>




    </div>


    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.3.2/chart.min.js"
        integrity="sha512-VCHVc5miKoln972iJPvkQrUYYq7XpxXzvqNfiul1H4aZDwGBGC0lq373KNleaB2LpnC2a/iNfE5zoRYmB4TRDQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
    </script>

    <script>
        $(".start_date").flatpickr({
            dateFormat: "Y-m-d",
            enableTime: false
        });
        $(".end_date").flatpickr({
            dateFormat: "Y-m-d",
            enableTime: false
        });
    </script>



    <script>
        var ctx = document.getElementById('myChart');
        var dateArray = document.getElementById('date-array-hold').getAttribute('data-date-array').split(' ');
        var dateWiseAsteroidCount = document.getElementById('date-array-hold').getAttribute('data-asteroid-count-array').split(' ');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dateArray,
                datasets: [{
                    label: 'No Of Ateroids',
                    data: dateWiseAsteroidCount,
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
</body>

</html>