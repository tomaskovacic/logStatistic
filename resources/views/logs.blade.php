<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js">
    </script>
    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                "ordering": false
            });
            $('.dataTables_length').addClass('bs-select');
        });

    </script>
</head>


<body>
    <div style="text-align:center">
        <h1 class="display-4">Log Statistics</h1>
    </div> <br>
    <div style="margin-left: 30px;">
        <h5> Number of errors: {{ $arrayOfResults[0] }}</h5>
        <h5> Number of debugs: {{ $arrayOfResults[1] }}</h5>
        <h5> Number of info: {{ $arrayOfResults[2] }}</h5> <br>
    </div>
    <div class="table-responsive">
        <table id="table" class="table table-striped" style="table-layout:fixed; width: 100%;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" style="word-wrap: break-word;">Date</th>
                    <th scope="col" style="word-wrap: break-word;">Error name</th>
                    <th scope="col" style="word-wrap: break-word;">Error description</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < sizeof($arrayFinal[0]); $i++)
                    <tr>
                        @for ($j = 0; $j < count($arrayFinal); $j++)
                            <td style="word-wrap: break-word;">{{ $arrayFinal[$j][$i] }}</td>
                        @endfor
                    </tr>
                @endfor

            </tbody>
        </table>
    </div>
    <br><br><br>
    <div style="text-align:center">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">First Tab</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Second Tab</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">Third Tab</a>
            </li>
        </ul><!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                <div class="table-responsive">
                    <table id="table" class="table table-striped" style="table-layout:fixed; width: 100%;">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" style="word-wrap: break-word;">Date</th>
                                <th scope="col" style="word-wrap: break-word;">Error name</th>
                                <th scope="col" style="word-wrap: break-word;">Error description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="word-wrap: break-word;">Test</td>
                                <td style="word-wrap: break-word;">Test</td>
                                <td style="word-wrap: break-word;">Test</td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane" id="tabs-2" role="tabpanel">
                <div class="table-responsive">
                    <table id="table" class="table table-striped" style="table-layout:fixed; width: 100%;">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" style="word-wrap: break-word;">Date</th>
                                <th scope="col" style="word-wrap: break-word;">Error name</th>
                                <th scope="col" style="word-wrap: break-word;">Error description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="word-wrap: break-word;">Test2</td>
                                <td style="word-wrap: break-word;">Test2</td>
                                <td style="word-wrap: break-word;">Test2</td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane" id="tabs-3" role="tabpanel">
                <div class="table-responsive">
                    <table id="table" class="table table-striped" style="table-layout:fixed; width: 100%;">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" style="word-wrap: break-word;">Date</th>
                                <th scope="col" style="word-wrap: break-word;">Error name</th>
                                <th scope="col" style="word-wrap: break-word;">Error description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="word-wrap: break-word;">Test3</td>
                                <td style="word-wrap: break-word;">Test3</td>
                                <td style="word-wrap: break-word;">Test3</td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>




    <br><br><br>
</body>
