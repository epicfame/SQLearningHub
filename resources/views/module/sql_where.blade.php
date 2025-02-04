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
    <h1>SQL Where</h1>
    <hr>
    <h2>The SQL WHERE Clause</h2>
    <p>The WHERE clause is used to filter records.</p>
    <p>It is used to extract only those records that fulfill a specified condition.</p>
    <div style="background-color: rgb(231, 231, 231); padding:20px; width:90%; border-radius:10px" class="flex-container" style="flex-direction:column">
        <h3>Example</h3>
        <p>Return data from the Customers table:</p>
<textarea class="form-control" name="" id="" cols="30" rows="2">
SELECT * FROM Customers
WHERE Country='Mexico';
</textarea>
    </div>
    <hr>
    <h2>Syntax</h2>
    <div style="background-color: rgb(231, 231, 231); padding:20px; width:90%; border-radius:10px" class="flex-container" style="flex-direction:column">
        <p style="font-family: Arial, Helvetica, sans-serif;"><span style="color: rgb(75, 75, 255)">SELECT</span> column1, column2, ...</p>
        <p style="font-family: Arial, Helvetica, sans-serif;"><span style="color: rgb(75, 75, 255)">FROM</span> table_name</p>
        <p style="font-family: Arial, Helvetica, sans-serif;"><span style="color: rgb(75, 75, 255)">WHERE</span> condition;</p>
    </div>
    <br>
    <div style="background-color: rgb(255, 255, 149); padding:20px; width:90%;">
        <p style="">Note: The <span class="hightlight">WHERE</span> clause is not only used in <span class="hightlight">SELECT</span> statements, it is also used in <span class="hightlight">UPDATE</span>, <span class="hightlight">DELETE</span>, etc.!</p>
    </div>
    <hr>
    <br>
    <h2>Text Fields vs. Numeric Fields</h2>
    <p>SQL requires single quotes around text values (most database systems will also allow double quotes).</p>
    <div style="background-color: rgb(231, 231, 231); padding:20px; width:90%; border-radius:10px" class="flex-container" style="flex-direction:column">
        <h3>Example</h3>
<textarea class="form-control" name="" id="" cols="30" rows="2">
SELECT * FROM Customers
WHERE CustomerID=1;
</textarea>
    </div>
    <br>
@endsection
