@extends('layout.layout_practice')

@section('head')
    <style>
        #questions-table_wrapper .dataTables_filter {
            float: right;
        }

        #questions-table_wrapper .dataTables_length {
            float: left;
        }

        .dataTables_wrapper .dataTables_paginate {
            float: right;
            text-align: right;
        }

        .dataTables_wrapper .dataTables_info {
            clear: both;
            float: left;
            padding-top: 0.755em;
        }

        #questions-table_filter{
            display: none;
        }

        .form-group {
            margin-bottom: 1rem;
        }
        .form-row {
            margin-bottom: 0.5rem;
        }
        .table-list-container {
            /* margin-bottom: 1rem; */
        }
        .table-list {
            border: 1px solid #dee2e6;
            padding: 1rem;
            border-radius: 0.375rem;
            background-color: #f8f9fa;
            margin-bottom: 1rem;
        }
        .table-list .remove-db-btn {
            margin-top: 1rem;
        }
        .columns-container {
            margin-top: 1rem;
        }
        .column-row {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            background-color: #ffffff;
        }
        .column-row .remove-column-btn {
            margin-top: 0.5rem;
        }
        .add-table-btn {
            margin-top: 1rem;
        }

    </style>
@endsection

@section('content')

    {{-- INPUT --}}
    {{-- 
        TITLE (TEXT), 
        SUBTITLE (TEXT 255), 
        INTRODUCTION (TEXT 255),
        QUESTION (TEXT 255),
        TABLE_LIST (CAN ADD MULTIPLE DATABASE NAME, COLUMN, AND COLUMN DATA TYPE),
        DIFFICULTY LEVEL (DROP DOWN EASY, MEDIUM, HARD),
    --}}

    <div class="container">
        <div id="back">
            <button class="btn btn-secondary mt-4" onclick="goBackQuestionIndex()"><i class="fa fa-arrow-left"></i> Back</button>
        </div>
        <h2>Create a New Question</h2>
        <form class="form" method="POST" autocomplete="off">
            @csrf
            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="title">Title:</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="title" name="title" class="form-control">
                </div>
            </div>
            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="subtitle">Subtitle:</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="subtitle" name="subtitle" class="form-control" maxlength="255">
                </div>
            </div>
            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="introduction">Introduction:</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="introduction" name="introduction" class="form-control" maxlength="255">
                </div>
            </div>
            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="question">Question:</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="question" name="question" class="form-control" maxlength="255">
                </div>
            </div>
            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="table_list">Table List:</label>
                </div>
                <div class="col-md-9">
                    <div id="tableListContainer">
                        <div class="table-list-container">
                            <div class="table-list">
                                <div class="form-row align-items-center">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder="Database Name" name="dbName[]">
                                    </div>
                                    <div class="col-md-8 text-right">
                                        <button type="button" class="btn btn-danger btn-sm remove-db-btn" onclick="removeTableRow(this)">Remove Database</button>
                                    </div>
                                </div>
                                <div class="columns-container">
                                    <div class="form-row align-items-center mb-2">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" placeholder="Column" name="column[][name]">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" placeholder="Column Data Type" name="column[][dataType]">
                                        </div>
                                        {{-- <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeColumnRow(this)">×</button>
                                        </div> --}}
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary mt-2" onclick="addColumnRow(this)">Add Column</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary mt-2" onclick="addTableRow()">Add Another Table</button>
                </div>
            </div>
            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="difficulty">Difficulty Level:</label>
                </div>
                <div class="col-md-9">
                    <select id="difficulty" name="difficulty" class="form-control">
                        <option value="EASY">Easy</option>
                        <option value="MEDIUM">Medium</option>
                        <option value="HARD">Hard</option>
                    </select>
                </div>
            </div>

            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="question_category">Question Category:</label>
                </div>
                <div class="col-md-9">
                    <select id="question_category" name="question_category" class="form-control">
                        <option value="PRACTICE">Practice</option>
                        <option value="CONTEST">Contest</option>
                    </select>
                </div>
            </div>

            {{-- ANSWERS INPUT --}}
            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="answer">Answer:</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="answer" name="answer" class="form-control" maxlength="255">
                </div>
            </div>

            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="max-memory">Max Memory:</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="max-memory" name="max-memory" class="form-control" maxlength="255">
                </div>
            </div>

            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="max-time">Max Time:</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="max-time" name="max-time" class="form-control" maxlength="255">
                </div>
            </div>

            <div class="form-row align-items-center mt-3">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('tail')

    <script>
        $(document).ready(function(){
            // Bind submit event to the form
        });
        
        $('.form').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            // Collect form data
            const formData = $(this).serializeArray();
            const tableData = [];
            console.log(formData)

            // Collect table and column data
            $('.table-list-container').each(function() {
                const dbName = $(this).find('input[name="dbName[]"]').val();
                const columns = [];
                $(this).find('.columns-container .form-row').each(function() {
                    const columnName = $(this).find('input[name="column[][name]"]').val();
                    const columnDataType = $(this).find('input[name="column[][dataType]"]').val();
                    if (columnName && columnDataType) {
                        columns.push({name: columnName, dataType: columnDataType});
                    }
                });
                if (dbName) {
                    tableData.push({dbName: dbName, columns: columns});
                }
            });

            // Append table data to formData
            formData.push({ name: 'tableList', value: JSON.stringify(tableData) });

            $.ajax({
                url: '/admin/practice/question/data/add',  // Update this with your actual backend URL
                type: 'POST',
                data: formData,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Add CSRF token to AJAX request headers
                },
                success: function(response) {
                    alert(response.message);
                    if(response.success){
                        $('.form')[0].reset();
                        $('#tableListContainer').empty();
                    }
                    else{
                    }
                    console.log(response);
                    // Optionally clear the form
                    // $('.form')[0].reset();
                    // $('#tableListContainer').empty();
                },
                error: function(xhr, status, error) {
                    // Handle error
                    alert('An error occurred while submitting the form.');
                    console.error(xhr.responseText);
                }
            });
        });

        function addTableRow() {
            const tableListContainer = document.getElementById('tableListContainer');
            const newTableRow = document.createElement('div');
            newTableRow.className = 'table-list-container';
            newTableRow.innerHTML = `
                <div class="table-list">
                    <div class="form-row align-items-center">
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Database Name" name="dbName[]">
                        </div>
                        <div class="col-md-8 text-right">
                            <button type="button" class="btn btn-danger btn-sm remove-db-btn" onclick="removeTableRow(this)">Remove Database</button>
                        </div>
                    </div>
                    <div class="columns-container">
                        <div class="form-row align-items-center mb-2">
                            <div class="col-md-5">
                                <input type="text" class="form-control" placeholder="Column" name="column[][name]">
                            </div>
                            <div class="col-md-5">
                                <input type="text" class="form-control" placeholder="Column Data Type" name="column[][dataType]">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary mt-2" onclick="addColumnRow(this)">Add Column</button>
                </div>
            `;
            tableListContainer.appendChild(newTableRow);
        }

        // <div class="col-md-2">
        //     <button type="button" class="btn btn-danger btn-sm" onclick="removeColumnRow(this)">×</button>
        // </div>

        function removeTableRow(button) {
            button.closest('.table-list-container').remove();
        }

        function addColumnRow(button) {
            const columnsContainer = button.previousElementSibling;
            const newColumnRow = document.createElement('div');
            newColumnRow.className = 'form-row align-items-center mb-2';
            newColumnRow.innerHTML = `
                <div class="col-md-5">
                    <input type="text" class="form-control" placeholder="Column" name="column[][name]">
                </div>
                <div class="col-md-5">
                    <input type="text" class="form-control" placeholder="Column Data Type" name="column[][dataType]">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeColumnRow(this)">×</button>
                </div>
            `;
            columnsContainer.appendChild(newColumnRow);
        }

        function removeColumnRow(button) {
            button.closest('.form-row').remove();
        }

        function goBackQuestionIndex() {
            window.location.href = "/admin/practice/index";
        }

    </script>

@endsection


