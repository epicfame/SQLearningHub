@extends('layout.layout_practice')

@section('head')
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            box-sizing: border-box;
        }
    </style>

@endsection


@section('content')

    {{-- Make label and input editable for title, subtitle, introduction, question, table list, difficulty level, code_answer, max_time, max_memory --}}
    <div style="padding: 20px;">
        <div id="back">
            <button class="btn btn-secondary mt-4" onclick="goBackQuestionIndex()"><i class="fa fa-arrow-left"></i> Back</button>
        </div>
        <h2>Question Detail</h2>
        <form class="form" method="POST" autocomplete="off">
            @csrf
            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="title">Title:</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="title" name="title" class="form-control" value="{{$question->title}}">
                </div>
            </div>
            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="subtitle">Subtitle:</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="subtitle" name="subtitle" class="form-control" maxlength="255" value="{{$question->subtitle}}">
                </div>
            </div>
            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="introduction">Introduction:</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="introduction" name="introduction" class="form-control" maxlength="255" value="{{$question->introduction}}">
                </div>
            </div>
            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="question">Question:</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="question" name="question" class="form-control" maxlength="255" value="{{$question->question}}">
                </div>
            </div>

            <div id="table">
                <h3>Table</h3>
                {{-- Looping mulai dari sini --}}
                </table>
            </div>

            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="difficulty">Difficulty Level:</label>
                </div>
                <div class="col-md-9">
                    <select id="difficulty" name="difficulty" class="form-control">
                        <option value="EASY" {{($question->difficulty_level == 'EASY') ? 'selected' : ''}}>Easy</option>
                        <option value="MEDIUM" {{($question->difficulty_level == 'MEDIUM') ? 'selected' : ''}}>Medium</option>
                        <option value="HARD" {{($question->difficulty_level == 'HARD') ? 'selected' : ''}}>Hard</option>
                    </select>
                </div>
            </div>


            {{-- ANSWERS INPUT --}}
            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="answer">Answer:</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="answer" name="answer" class="form-control" maxlength="255" value="{{$answer->code_answer}}">
                </div>
            </div>

            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="max-memory">Max Memory:</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="max-memory" name="max-memory" class="form-control" maxlength="255" value="{{$answer->max_memory}}">
                </div>
            </div>

            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="max-time">Max Time:</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="max-time" name="max-time" class="form-control" maxlength="255" value="{{$answer->max_time}}">
                </div>
            </div>

            {{-- <div class="form-row align-items-center mt-3">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div> --}}
        </form>
    </div>


@endsection


@section('tail')

    <script>

        $(document).ready(function(){
            addTableList()
        });

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

        function goBackQuestionIndex() {
            window.location.href = "/admin/practice/index";
        }
    </script>

@endsection