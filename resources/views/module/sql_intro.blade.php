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

    <h1>SQL Introduction</h1>
    <p>SQL is a standard language for accessing and manipulating databases.</p>

    <hr>
    <br>

    <h3>What is SQL?</h3>
    <ul>
        <li>SQL stands for Structured Query Language</li>
        <li>SQL lets you access and manipulate databases</li>
        <li>SQL became a standard of the American National Standards Institute (ANSI) in 1986, and of the International Organization for Standardization (ISO) in 1987</li>
    </ul>

    <hr>
    <br>

    <h3>What can SQL do?</h3>
    <ul>
        <li>SQL can execute queries against a database</li>
        <li>SQL can retrieve data from a database</li>
        <li>SQL can insert records in a database</li>
        <li>SQL can update records in a database</li>
        <li>SQL can delete records from a database</li>
        <li>SQL can create new databases</li>
        <li>SQL can create new tables in a database</li>
        <li>SQL can create stored procedures in a database</li>
        <li>SQL can create views in a database</li>
        <li>SQL can set permissions on tables, procedures, and views</li>
    </ul>

    <hr>
    <br>

    <h3>SQL is a Standard - BUT....</h3>
    <p>Although SQL is an ANSI/ISO standard, there are different versions of the SQL language.</p>
    <p>However, to be compliant with the ANSI standard, they all support at least the major commands (such as <span class="hightlight">SELECT</span> , <span class="hightlight">UPDATE</span>, <span class="hightlight">DELETE</span>, <span class="hightlight">INSERT</span>, <span class="hightlight">WHERE</span>) in a similar manner.</p>
    
    <hr>
    <br>

    <h3>RDBMS</h3>
    <p>RDBMS stands for Relational Database Management System.</p>
    <p>RDBMS is the basis for SQL, and for all modern database systems such as MS SQL Server, IBM DB2, Oracle, MySQL, and Microsoft Access.</p>
    <p>The data in RDBMS is stored in database objects called tables. A table is a collection of related data entries and it consists of columns and rows.</p>
    <p>Look at the "Customers" table:</p>

    <div style="background-color: rgb(231, 231, 231); padding:20px; width:90%; border-radius:10px" class="flex-container" style="flex-direction:column">
        <h3>Example</h3>
        <input class="form-control" type="text" value="SELECT * FROM Customers" style="width: 80%">
    </div>
    <br>
    <p>Every table is broken up into smaller entities called fields. The fields in the Customers table consist of CustomerID, CustomerName, ContactName, Address, City, PostalCode and Country. A field is a column in a table that is designed to maintain specific information about every record in the table.</p>
    <p>A record, also called a row, is each individual entry that exists in a table. For example, there are 91 records in the above Customers table. A record is a horizontal entity in a table.</p>
    <p>A column is a vertical entity in a table that contains all information associated with a specific field in a table.</p>
    <br>
@endsection
