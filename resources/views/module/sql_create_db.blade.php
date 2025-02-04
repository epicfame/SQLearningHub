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

    <h1>SQL Create Database</h1>
    <hr>
    <h2>The SQL CREATE DATABASE Statement</h2>
    <p>The CREATE DATABASE statement is used to create a new SQL database.</p>
    <p>Syntax</p>
    <div style="background-color: rgb(231, 231, 231); padding:20px; width:90%; border-radius:10px" class="flex-container" style="flex-direction:column">
        <input class="form-control" type="text" value="CREATE DATABASE databasename" style="width: 80%">
    </div>
    <hr>
    <br>

    <h3>CREATE DATABASE Example</h3>
    <p>The following SQL statement creates a database called "testDB":</p>
    <div>
        <div style="background-color: rgb(231, 231, 231); padding:20px; width:90%; border-radius:10px" class="flex-container" style="flex-direction:column">
            <h3>Example</h3>
            <input class="form-control" type="text" value="CREATE DATABASE testDB" style="width: 80%">
        </div>
    </div>
    <br>
    <hr>

    <h2>SQL Exercises</h2>
    <div class="custom-background-exercises">
        <h3 style="color:white">Test Yourself With Exercises</h3>
        <div style="background-color:white;padding:20px;">
            <h3>Exercise:</h3>
            <p>Write the correct SQL statement to create a new database called <span class="hightlight">testDB</span></p>
            <div style="padding: 20px; background-color:rgb(214, 214, 214)">
                <p class="wrong-answer" id="wrong">Wrong Answer</p>
                <p class="correct-answer"id="correct">Correct</p>
                <input class="form-control" type="text" id="input" style="width: 35%; display:inline-block"><span> ;</span>
            </div>
            <br>
            <button class="btn custom-button" onclick="checkAnswer()">Submit Answer</button>
        </div>
    </div>

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
        
            if (inputValue == 'create database testdb') {
                correct.style.display = 'block';
            } else {
                wrong.style.display = 'block';
            }
        
        }
    </script>
@endsection
