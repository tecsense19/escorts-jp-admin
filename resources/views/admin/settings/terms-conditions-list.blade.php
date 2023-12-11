
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Privacy Page</title>
    <meta name="description" content="A sample Bootstrap-based privacy web page for use with websites that use Google Analytics">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Customise Bootstrap for practical grid layouts -->
    <link rel="stylesheet" href="assets/css/bootstrap-custom.css"> 

</head>


<body>

    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1>{{$datas->title}}</h1>
                {!! $datas->description !!}
            </div>
        </div>
    </div>


 
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



    
</body>
</html>