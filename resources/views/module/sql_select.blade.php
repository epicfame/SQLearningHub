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
    <h1>SQL Select</h1>
    <hr>
    <p>The SELECT statement is used to select data from a database.</p>

    <div style="background-color: rgb(231, 231, 231); padding:20px; width:90%; border-radius:10px" class="flex-container" style="flex-direction:column">
        <h3>Example</h3>
        <p>Return data from the Customers table:</p>
        <input class="form-control" type="text" value="SELECT CustomerName, City FROM Customers;" style="width: 80%">
    </div>
    <hr>
    <h2>Syntax</h2>
    <div style="background-color: rgb(231, 231, 231); padding:20px; width:90%; border-radius:10px" class="flex-container" style="flex-direction:column">
        <p style="font-family: Arial, Helvetica, sans-serif;"><span style="color: rgb(75, 75, 255)">SELECT</span> column1, column2, ...</p>
        <p style="font-family: Arial, Helvetica, sans-serif;"><span style="color: rgb(75, 75, 255)">FROM</span> table_name;</p>
    </div>
    <p>Here, column1, column2, ... are the field names of the table you want to select data from.</p>
    <p>The table_name represents the name of the table you want to select data from.</p>
    <hr>
    <h2>Select ALL columns</h2>
    <p>If you want to return all columns, without specifying every column name, you can use the SELECT * syntax:</p>
    <div style="background-color: rgb(231, 231, 231); padding:20px; width:90%; border-radius:10px" class="flex-container" style="flex-direction:column">
        <h3>Example</h3>
        <p>Return all the columns from the Customers table:</p>
        <input class="form-control" type="text" value="SELECT * FROM Customers" style="width: 80%">
    </div>
    <br>
@endsection
