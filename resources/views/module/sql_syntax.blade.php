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

    <h1>SQL Syntax</h1>
    <hr>
    <h2>SQL Statements</h2>
    <p>Most of the actions you need to perform on a database are done with SQL statements.</p>
    <p>SQL statements consists of keywords that are easy to understand.</p>
    <p>The following SQL statement returns all records from a table named "Customers":</p>
    <div style="background-color: rgb(231, 231, 231); padding:20px; width:90%; border-radius:10px" class="flex-container" style="flex-direction:column">
        <h3>Example</h3>
        <p>Select all records form the Customer table:</p>
        <input class="form-control" type="text" value="SELECT * FROM Customers" style="width: 80%">
    </div>
    <p>In this tutorial we will teach you all about the different SQL statements.</p>
    <hr>
    <br>

    <h3>Database Tables</h3>
    <p>A database most often contains one or more tables. Each table is identified by a name (e.g. "Customers" or "Orders"), and contain records (rows) with data.</p>
    <p>In this tutorial we will use the well-known Northwind sample database (included in MS Access and MS SQL Server).</p>
    <p>Below is a selection from the Customers table used in the examples:</p>

    <table class="table-all notranslate">
        <tbody>
            <tr>
                <th>CustomerID</th>
                <th>CustomerName</th>
                <th>ContactName</th>
                <th>Address</th>
                <th>City</th>
                <th>PostalCode</th>
                <th>Country</th>
            </tr>
            <tr>
                <td>1<br><br></td>
                <td>Alfreds Futterkiste</td>
                <td>Maria Anders</td>
                <td>Obere Str. 57</td>
                <td>Berlin</td>
                <td>12209</td>
                <td>Germany</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Ana Trujillo Emparedados y helados</td>
                <td>Ana Trujillo</td>
                <td>Avda. de la Constitución 2222</td>
                <td>México D.F.</td>
                <td>05021</td>
                <td>Mexico</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Antonio Moreno Taquería</td>
                <td>Antonio Moreno</td>
                <td>Mataderos 2312</td>
                <td>México D.F.</td>
                <td>05023</td>
                <td>Mexico</td>
            </tr>
            <tr>
                <td>4<br><br></td>
                <td>Around the Horn</td>
                <td>Thomas Hardy</td>
                <td>120 Hanover Sq.</td>
                <td>London</td>
                <td>WA1 1DP</td>
                <td>UK</td>
            </tr>
            <tr>
                <td>5</td>
                <td>Berglunds snabbköp</td>
                <td>Christina Berglund</td>
                <td>Berguvsvägen 8</td>
                <td>Luleå</td>
                <td>S-958 22</td>
                <td>Sweden</td>
            </tr>
      </tbody>
    </table>
    <br>
    <p>The table above contains five records (one for each customer) and seven columns (CustomerID, CustomerName, ContactName, Address, City, PostalCode, and Country).</p>

    <hr>
    <br>

    <h3>Keep in Mind That...</h3>
    <p>SQL keywords are NOT case sensitive: <span class="hightlight">select</span> is the same as <span class="hightlight">SELECT</span></p>
    <p>In this tutorial we will write all SQL keywords in upper-case.</p>

    <hr>
    <br>

    <h3>Semicolon after SQL Statements?</h3>
    <p>Some database systems require a semicolon at the end of each SQL statement.</p>
    <p>Semicolon is the standard way to separate each SQL statement in database systems that allow more than one SQL statement to be executed in the same call to the server.</p>
    <p>In this tutorial, we will use semicolon at the end of each SQL statement.</p>

    <hr>
    <br>

    <h3>Some of The Most Important SQL Commands</h3>
    <ul>
        <li><span class="hightlight">SELECT</span> - extracts data from a database</li>
        <li><span class="hightlight">UPDATE</span> - updates data in a database</li>
        <li><span class="hightlight">DELETE</span> - deletes data from a database</li>
        <li><span class="hightlight">INSERT INTO</span> - inserts new data into a database</li>
        <li><span class="hightlight">CREATE DATABASE</span> - creates a new database</li>
        <li><span class="hightlight">ALTER DATABASE</span> - modifies a database</li>
        <li><span class="hightlight">CREATE TABLE</span> - creates a new table</li>
        <li><span class="hightlight">ALTER TABLE</span> - modifies a table</li>
        <li><span class="hightlight">DROP TABLE</span> - deletes a table</li>
        <li><span class="hightlight">CREATE INDEX</span> - creates an index (search key)</li>
        <li><span class="hightlight">DROP INDEX</span> - deletes an index</li>
    </ul>
    <br>

@endsection
