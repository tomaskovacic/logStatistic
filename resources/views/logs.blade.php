<!DOCTYPE html>
<html lang="en">

<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<style>
</style>
</head>


<body>
    <div style="text-align:center">
    <h1 class="display-4">Log Statistics</h1>
    </div> <br>
    <div style="margin-left: 30px;">
    <h5> Number of errors: {{$array_of_results[0]}}</h5>
    <h5> Number of debugs: {{$array_of_results[1]}}</h5>
    <h5> Number of info: {{$array_of_results[2]}}</h5> <br>
    </div>
    <div class="table-responsive" >
        <table class="table table-striped" style="table-layout:fixed; width: 100%;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" style="text-align:center; word-wrap: break-word;">Errors</th>
                </tr>
            </thead>
            <tbody>
                @php 
                unset($array_of_results[0]);
                unset($array_of_results[1]);
                unset($array_of_results[2]);
                @endphp
                @foreach($array_of_results as $item)
                <tr>
                    <td style="word-wrap: break-word;">{{$item}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
                <br><br>


</body>
