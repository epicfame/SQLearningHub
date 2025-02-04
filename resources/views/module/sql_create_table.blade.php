@extends('layout.layout_module')

@section('head')
    <style>
        .custom-button{
            background-color: #04AA6D;
            color: white;
        }
        .custom-button:hover{
            background-color: #01764b;
            color: white;
        }
        .custom-background-exercises{
            background-color: #282A35;
            border-radius: 10px;
            padding: 20px;
            width: 80%;
        }
        .wrong-answer{
            background-color:rgb(250, 80, 80);
            color:white;
            display:none;
            border-radius: 5px;
            padding: 5px;

        }
        .correct-answer{
            background-color:rgb(72, 189, 72);
            color:white;
            display:none;
            border-radius: 5px;
            padding: 5px;
        }
        .hightlight{
            background-color: rgb(222, 222, 222); 
            color:red;
        }
        .table-all{
            border-collapse: collapse;
            border-spacing: 0;
            width: 80%;
            display: table;
            border: 1px solid #ccc;
            overflow-x: auto;
        }
        .table-all tr{
            border-bottom: 1px solid #ddd;
        }
        .table-all tr:nth-child(odd){
            background-color: #fff;
        }
        .table-all td, .table-all th{
            padding: 8px;
        }
        
    </style>
@endsection

@section('content')

    <h1>SQL Create Table</h1>
    <hr>
    <h2>The SQL CREATE TABLE Statement</h2>
    <p>The <span class="hightlight">CREATE TABLE</span> statement is used to create a new table in database.</p>
    <p>Syntax</p>
    <div style="background-color: rgb(231, 231, 231); padding:20px; width:90%; border-radius:10px" class="flex-container" style="flex-direction:column">
        {{-- <input class="form-control" type="text" 
        value="CREATE TABLE table_name (
                    column1 datatype,
                    column2 datatype,
                    column3 datatype,
                ....
                );" 
        style="width: 80%"> --}}
<textarea class="form-control" name="" id="" cols="30" rows="6">
CREATE TABLE table_name (
    column1 datatype,
    column2 datatype,
    column3 datatype,
    ....
);</textarea>
    </div>

    <p>The column parameters specify the names of the columns of the table.</p>
    <p>The datatype parameter specifies the type of data the column can hold (e.g. varchar, integer, date, etc.).</p>
    <hr>
    <br>

    <h3>SQL CREATE TABLE Example</h3>
    <p>The following example creates a table called "Persons" that contains five columns: PersonID, LastName, FirstName, Address, and City:</p>
    <div>
        <div style="background-color: rgb(231, 231, 231); padding:20px; width:90%; border-radius:10px" class="flex-container" style="flex-direction:column">
            <h3>Example</h3>
            <textarea class="form-control" name="" id="" cols="30" rows="8">
CREATE TABLE Persons (
    PersonID int,
    Name varchar(255),
    Address varchar(255),
    City varchar(255),
    ....
);</textarea>
        </div>
    </div>
    <p>The PersonID column is of type int and will hold an integer.</p>
    <p>The Name, Address, and City columns are of type varchar and will hold characters, and the maximum length for these fields is 255 characters.</p>
    <br>
    <hr>

    <h2>SQL Exercises</h2>
    <div class="custom-background-exercises">
        <h3 style="color:white">Test Yourself With Exercises</h3>
        <div style="background-color:white;padding:20px;">
            <h3>Exercise:</h3>
            <p>Write the correct SQL statement to create a new table called <span class="hightlight">Persons</span></p>
            <div style="padding: 20px; background-color:rgb(214, 214, 214)">
                <p class="wrong-answer" id="wrong">Wrong Answer</p>
                <p class="correct-answer"id="correct">Correct</p>
                <input class="form-control" type="text" id="input" style="width: 35%; display:inline-block"><span> (</span>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;PersonsID int(18) NOT NULL,</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;Name varchar(255) NOT NULL,</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;Address varchar(255) NOT NULL,</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;City varchar(255) NULL,</p>
                <p>);</p>
            </div>
            <br>
            <button class="btn custom-button" onclick="checkAnswer()">Submit Answer</button>
        </div>
    </div>

    <br>

    <h2>phpMyAdmin Exercises</h2>
    <div class="custom-background-exercises">
        <h3 style="color:white">Test Yourself With Exercises</h3>
        <div style="background-color:white;padding:20px;">
            <h3>Exercise:</h3>
            <p>Make a <span class="hightlight">Persons</span> database that have PersonsID, Name, Address, City</p>

            <div style="padding: 20px; background-color:rgb(214, 214, 214); overflow:auto">
                <p class="wrong-answer" id="wrong1">Wrong Answer</p>
                <p class="correct-answer"id="correct1">Correct</p>
                <table >
                    <thead>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Length</th>
                        <th>Null</th>
                    </thead>
                    <tbody>
                        @for($i=0; $i<4;$i++)
                        <tr>
                            <td><input class="form-control" type="text" id="columnName{{$i}}"></td>
                            <td>
                                <select class="form-control" name="" id="columnType{{$i}}">
                                    <option value="INT">INT</option>
                                    <option value="VARCHAR">VARCHAR</option>
                                    <option value="DATETIME">DATETIME</option>
                                    <option value="DOUBLE">DOUBLE</option>
                                    <option value="BOOLEAN">BOOLEAN</option>
                                </select>
                            </td>
                            <td><input class="form-control" type="number" id="columnLength{{$i}}"></td>
                            <td><input class="checkbox" type="checkbox" id="columnCheckbox{{$i}}"></td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
            <br>
            <button class="btn custom-button" onclick="checkAnswerPhpMyAdmin()">Submit Answer</button>
        </div>
    </div>

    <br>

    <br>

@endsection

@section('tail')
    <script>
        function checkAnswer(){
            // set all id for wrong, and correct to display: none
            // then get the value from input id customers, string to lower all value then if the value == 'select'
            // set the correct to display not none
            // else wrong to display not none
            var inputValue = document.getElementById('input').value.toLowerCase();
            var correct = document.getElementById('correct');
            var wrong = document.getElementById('wrong');
        
            correct.style.display = 'none';
            wrong.style.display = 'none';
        
            if (inputValue == 'create table persons') {
                correct.style.display = 'block';
            } else {
                wrong.style.display = 'block';
            }
        
        }
        
        function checkAnswerPhpMyAdmin() {
            let nameArray = ["PersonsID", "Name", "Address", "City"]
            let typeArray = ['INT', 'VARCHAR', 'VARCHAR', 'VARCHAR']
            let numberArray = ['18', '255', '255', '255']
            let checkArray = [false, false, false, true]
            let isCorrect = true
            for (let i = 0 ; i < 4 ; i++) {
                let name = $('#columnName' + i).val().trim()
                let type = $('#columnType' + i + ' option:selected').val()
                let length = $('#columnLength' + i).val()
                let isChecked = $('#columnCheckbox' + i).prop('checked')
                console.log('Name',name, type, length, isChecked)
                if (name != nameArray[i] || type != typeArray[i] || length != numberArray[i] || isChecked != checkArray[i]) {
                    isCorrect = false
                }
            }
            var correct = document.getElementById('correct1');
            var wrong = document.getElementById('wrong1');
        
            correct.style.display = 'none';
            wrong.style.display = 'none';
            if(isCorrect){
                correct.style.display = 'block';
            }
            else{
                wrong.style.display = 'block';
            }
        }

    </script>
@endsection
