<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Sign In | {{ $location['location_name'] }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<!-- Responsive navbar-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand" href="#">
            {{ $location['location_name'] }}
        </span>
    </div>
</nav>
<!-- Page content-->
<div class="container">
    <div class="text-center mt-5">
        <h1>Welcome To {{ $location['location_name'] }}</h1>
        <p class="lead">A complete project boilerplate built with Bootstrap</p>
        <p>Bootstrap v5.1.3</p>
    </div>
    <div class="flex">
      
        <x-alert />
        
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="{{ route('queue.checkin', ['locationUuid' => $location['uuid'], 'storeUuid'=> $location->store->id ]) }}" id="check-in" class="form-horizontal" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="first-name" class="col-sm-3 control-label">First Name</label>
                        <div class="col-sm-7">
                            <input id="first-name" name="first_name" class="form-control" type="text" value="{{ old('first_name') }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="last-name" class="col-sm-3 control-label">Last Name</label>
                        <div class="col-sm-7">
                            <input id="last-name" name="last_name" class="form-control" type="text" value="{{ old('last_name') }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-7">
                            <input id="email" name="email" class="form-control" type="email" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Check In</button>
                </form>
            </div>

        </div>                
                        
    </div>

</div>
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
