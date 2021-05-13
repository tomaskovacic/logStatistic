<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>

    <script>
        $(document).ready(function() {

            $.ajax({
                url: '/api/getFilenames',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    for (let i = 0; i < response.length; i++) {
                        $('.form-select').append("<option>" + response[i] + "</option>");
                    }
                }
            });
            $('.form-select').change(function() {
                const value = $(this).val();
                $('#number').empty();
                $.ajax({
                    url: '/api/getNumber/' + value,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        $('#number').append("<h5 id='numberErrors'> Number of info: " + response[0] + "</h5>");
                        $('#number').append("<h5 id='numberDebugs'> Number of debugs: " + response[1] + "</h5>");
                        $('#number').append("<h5 id='numberErrors'> Number of errors: " + response[2] + "</h5>");
                    }
                });
                var table = $('#table').DataTable({
                    destroy: true,
                    paging: true,
                    select: 'single',
                    "order": [],
                    "processing": true,
                    "serverSide": true,
                    "ajax": 'api/getData/' + value,
                    "columns": [{
                        "data": "date"
                    },
                        {
                            "data": "errorName"
                        },
                        {
                            "data": "errorDesc"
                        },
                    ],
                });
                $('.dataTables_length').addClass('bs-select');
                $('#table tbody').on('click', 'tr', function() {
                    var index = table.row(this).index();
                    var table2 = $('#table2').DataTable({
                        destroy: true,
                        "order": [],
                        "aaSorting": [],
                        "ordering": false,
                        "info": false,
                        "searching": false,
                        "lengthChange": false,
                        "ajax": 'api/getErrors/' + value + "@" + index,
                        "columns": [{
                            "data": "error"
                        }, ],
                    });
                });
            });
        });
    </script>
    <style>
        td {
            word-wrap: break-word;
        }
    </style>
</head>

<body>

<div style="text-align:center">
    <h1 class="display-4">Log Statistics</h1>
</div> <br>

<div style="display: inline;">
<select class="form-select" style="margin-left: 30px;" aria-label="Default select example">
    <option selected disabled>Choose file</option>
</select>
    <span style="margin-left: 50px;">
    From
    <input type="datetime-local" id="datetimeFrom" name="datetime">
    To
    <input type="datetime-local" id="datetimeTo" name="datetime">
    </span>
</div>
<br><br>
<div id="number" style="margin-left: 30px;">

</div>
<br>

<div class="table-responsive">
    <table id="table" class="table table-striped" style="table-layout:fixed; width: 100%;">
        <thead class="thead-dark">
        <tr>
            <th scope="col" style="word-wrap: break-word;">Date</th>
            <th scope="col" style="word-wrap: break-word;">Error name</th>
            <th scope="col" style="word-wrap: break-word;">Error description</th>
        </tr>
        </thead>
    </table>
</div>
<br><br><br>
<div>
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Errors</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tabs-1" role="tabpanel" style="table-layout:fixed; width: 100%;">
                <div class="table-responsive">
        <table id="table2" class="table table-striped" style="table-layout:fixed; width: 100%;">
            <thead class="thead-dark">
            <tr>
                <th scope="col" style="word-wrap: break-word;">Full error description</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
</div>
</div>
<br><br><br>
</body>
