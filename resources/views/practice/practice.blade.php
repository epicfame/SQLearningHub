@extends('layout.layout_practice')

@section('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.css"></link>
    <style>
        .feature {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 4rem;
            width: 4rem;
            font-size: 2rem;
            box-sizing: border-box;
        }

        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            box-sizing: border-box;
        }

        .myButton {
            box-shadow: 0px 0px 13px 0px #3dc21b;
            background-color:#44c767;
            border-radius:16px;
            border:2px solid #18ab29;
            display:inline-block;
            cursor:pointer;
            color:#ffffff;
            font-family:Courier New;
            font-size:14px;
            padding:15px 21px;
            text-decoration:none;
            text-shadow:0px 1px 0px #2f6627;
            box-sizing: border-box;
        }
        .myButton:hover {
            background-color:#5cbf2a;
        }
        .myButton:active {
            position:relative;
            top:1px;
        }
        .left{
            width: 50%;
            background-color:white;
            margin:10px;
            padding:10px;
            border-radius: 10px;
            box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
            overflow-y: auto;
            box-sizing: border-box;
        }


    </style>
@endsection

@section('content')

<div id="flash-message" style="display: none;"></div>
<a href="{{ url('/practice') }}" class="btn btn-secondary mb-3" style="width:100px"><i class="fa fa-arrow-left"></i> Back</a>
    <div style="width: 100%; height:100vh; display:flex; flex-direction:row; justify-content: center;box-sizing: border-box;">
        <div class="left">
            <h1>Soal - {{$question->title}}</h1>
            <br>
            <div id="introduction">
                <h3>Introduction</h3>
                <p>{{$question->introduction}}</p>
            </div>
            <br>
            <div id="table">
                <h3>Table</h3>
                {{-- Looping mulai dari sini --}}
                </table>
            </div>
            <br>
            <hr>
            <div id="example">
                <h3>Perintah</h3>
                <p>{{$question->question}}</p>
            </div>
        </div>
        <div class="right" style="width: 50%;">
            <h1>Jawaban</h1>
            <br>
            <form method="POST" id="submit-form" class="form" role="form">
                @CSRF
                <div id="code"></div>
                <div class="mt-4" style="margin-right:10px;float: right">
                    <button type="submit" class="myButton" id="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('tail')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/mode/sql/sql.min.js"></script>
    <script>

        $(document).ready(function(){
            addTableList()
        });

        var myCodeMirror = CodeMirror(document.querySelector('#code'), {
            lineNumbers: true,
            tabSize: 2,
            value: '{!! $answer !!}',
            mode: 'text/x-mysql'
        });

        $('#submit-form').on('submit', function(e) {
            var code = myCodeMirror.getValue();
            e.preventDefault();
            $.ajax({
                url: "/practice/question/compile/{{$id}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{!! csrf_token() !!}'
                },
                data: {
                    query : code
                },
                success: function(result){
                    console.log(result)
                    if(result.success){
                        showMessage('success', result.message)
                    }
                    else{
                        showMessage('error', result.message)
                    }
                    // $("#message").html(result.message);
                },
                error: function(result){
                    console.log(result)
                    showMessage('error', result.message)
                    // $("#message").html(result.message);

                }
            });
        });

        // FUNCTION FOR SUCCESS/ERROR MESSAGE
        function showMessage(type, message, time) {
            let alertClass;

            switch(type) {
                case 'success':
                    alertClass = 'alert-success';
                    break;
                case 'error':
                    alertClass = 'alert-danger';
                    break;
                case 'info':
                    alertClass = 'alert-info';
                    break;
                default:
                    alertClass = 'alert-secondary';
            }

            $('#flash-message').html('<div class="alert ' + alertClass + '">' + message + '</div>').show();
            if(time != null && time != ''){
                setTimeout(function() {
                    $('#flash-message').fadeOut('slow');
                }, time * 1000); // time in seconds
            }
        }

        function addTableList(){
            let tableList = "{{$question->table_list}}"
            tableList = tableList.split(':')
            console.log(tableList)
            let tableData = [];
            tableList.forEach(element => {
                let tempData = []
                let splitData = element.split(',')
                let tableName = splitData[0]
                let tableColumn = splitData[1].split(';') // bentuknya array, tiap datanya nama_kolom-tipe_data. Harus displit lagi buat dapetin nama_kolom dan tipe_data
                tempData.push(tableName)
                tempData.push(tableColumn)
                tableData.push(tempData)
            });
            console.log(tableData)
            // add and format the data (tableData) to the html
            // add and format the data (tableData) to the html
            tableData.forEach((table) => {
                let tableName = table[0];
                let tableColumns = table[1];

                // Create a new heading for the table name
                let tableHeading = document.createElement('li');
                tableHeading.textContent = tableName;

                // Add the new heading to the container
                let container = document.getElementById('table');
                container.appendChild(tableHeading);

                // Create a new table and a header row
                let newTable = document.createElement('table');
                let headerRow = document.createElement('tr');
                newTable.appendChild(headerRow);

                // Create headers for 'Column Name' and 'Data Type'
                let nameHeader = document.createElement('th');
                nameHeader.textContent = 'Nama Kolom';
                headerRow.appendChild(nameHeader);

                let typeHeader = document.createElement('th');
                typeHeader.textContent = 'Tipe Data';
                headerRow.appendChild(typeHeader);

                // Add columns to the table
                tableColumns.forEach((column) => {
                    let splitColumn = column.split('-');
                    let columnName = splitColumn[0];
                    let dataType = splitColumn[1];

                    // Create a new row for each column
                    let row = document.createElement('tr');
                    newTable.appendChild(row);

                    // Create cells for the column name and data type
                    let nameCell = document.createElement('td');
                    nameCell.textContent = columnName;
                    row.appendChild(nameCell);

                    let typeCell = document.createElement('td');
                    typeCell.textContent = dataType;
                    row.appendChild(typeCell);
                });

                // Add the new table to the container
                container.appendChild(newTable);
            });

        }
    </script>
@endsection
