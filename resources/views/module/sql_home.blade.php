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
    </style>
@endsection

@section('content')

    <h2>SQL Tutorial</h2>
    <br>

    <div style="background-color:#D9EEE1; padding:30px;">
        <p>SQL (Structured query language) is a standard programming language for storing, manipulating and retrieving data in databases.</p>
        <p> A relational database stores information in tabular form, with rows and columns representing different data attributes and the various relationships between the data values.</p>
        {{-- <p>Our SQL tutorial will teach you how to use SQL in: MySQL, SQL Server, MS Access, Oracle, Sybase, Informix, Postgres, and other database systems.</p> --}}
        <button class="btn custom-button" onclick="goToIntroduction()">Start learning SQL now <i class="fa fa-arrow-right"></i></button>
    </div>

    <br>
    <hr>

    <h2>Example in Each Chapter</h2>
    <div>
        <p>Learn by examples! This tutorial supplements all explanations with clarifying examples.</p>
        <div style="background-color: rgb(231, 231, 231); padding:20px; width:90%; border-radius:10px" class="flex-container" style="flex-direction:column">
            <h3>Example</h3>
            <input class="form-control" type="text" value="SELECT * FROM Users" style="width: 80%">
        </div>
    </div>

    <br>
    <hr>

    <h2>SQL Exercises</h2>
    <div class="custom-background-exercises">
        <h3 style="color:white">Test Yourself With Exercises</h3>
        <div style="background-color:white;padding:20px;">
            <h3>Exercise:</h3>
            <p>Insert the missing statement to get all the columns from the <span class="hightlight">Customers</span> table.</p>
            <div style="padding: 20px; background-color:rgb(214, 214, 214)">
                <p class="wrong-answer" id="wrong">Wrong Answer</p>
                <p class="correct-answer"id="correct">Correct</p>
                <input class="form-control" type="text" id="customers" style="width: 15%; display:inline-block"><span> * FROM Customers;</span>
            </div>
            <br>
            <button class="btn custom-button" onclick="checkAnswer()">Submit Answer</button>
        </div>
    </div>

    <br>


@endsection

@section('tail')
<script>
    function goToIntroduction(){
        window.location.href = '/module/sql_intro';
    }

    function checkAnswer(){
        // set all id for wrong, and correct to display: none
        // then get the value from input id customers, string to lower all value then if the value == 'select'
        // set the correct to display not none
        // else wrong to display not none
        var inputValue = document.getElementById('customers').value.toLowerCase();
        var correct = document.getElementById('correct');
        var wrong = document.getElementById('wrong');

        correct.style.display = 'none';
        wrong.style.display = 'none';

        if (inputValue == 'select') {
            correct.style.display = 'block';
        } else {
            wrong.style.display = 'block';
        }

    }
</script>

@endsection
