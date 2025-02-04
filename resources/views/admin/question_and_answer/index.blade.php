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
/* 
        .paginate_button {
            display: inline-block;
            padding: .375rem .75rem;
            margin-left: .25rem;
            text-align: center;
            vertical-align: middle;
            border: 1px solid transparent;
            border-radius: 5px;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }

        .paginate_button.disabled {
            color: #fff !important;
            background-color: #6c757d !important;
            border-color: #6c757d !important;
        }

        .paginate_button:hover {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .paginate_button {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            border-radius: 5px;
        } */

    </style>
@endsection

@section('content')

    <div style="padding: 20px">

        <div id="filter-row" class="row mb-3">
            <div class="col-md-4">
                <label for="filter-title">Title</label>
                <input id="filter-title" list="title-list" type="text" placeholder="Search Title" class="form-control" />
                <datalist id="title-list">
                    <!-- Options will be populated here dynamically -->
                </datalist>
            </div>
            <div class="col-md-4">
                <label for="filter-user-name">User Name</label>
                <input id="filter-user-name" list="user-name-list" type="text" placeholder="Search User Name" class="form-control" />
                <datalist id="user-name-list">
                    <!-- Options will be populated here dynamically -->
                </datalist>
            </div>
            <div class="col-md-4">
                <label for="filter-is-correct">Is Correct</label>
                <select id="filter-is-correct" class="form-control">
                    <option value="">Select...</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
        </div>

        <table id="questions-table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>User Name</th>
                    <th>Is Correct</th>
                    <th>Attempts</th>
                </tr>
            </thead>
        </table>
    </div>

@endsection
@section('tail')

    <script>
        $(document).ready(function(){
            console.log(1)
            createQuestionTable();
        });

        function createQuestionTable(){
            var table = $('#questions-table').DataTable({
                processing: true,
                serverSide: false,
                filter: false,
                ajax: {
                    url: '{{ url('/admin/report/list') }}', // Your route to fetch products
                    type: 'GET'
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'title', name: 'title' },
                    { data: 'user_name', name: 'user_name' },
                    { data: 'is_correct', name: 'is_correct' },
                    { data: 'attempts', name: 'attempts' }
                ],
            });

            // Apply the search
            $('#filter-title').on( 'keyup change clear', function () {
                table
                    .column(1) // column index for 'Title'
                    .search( this.value )
                    .draw();
            } );

            $('#filter-user-name').on( 'keyup change clear', function () {
                table
                    .column(2) // column index for 'User Name'
                    .search( this.value )
                    .draw();
            } );

            $('#filter-is-correct').on( 'keyup change clear', function () {
                table
                    .column(3) // column index for 'Is Correct'
                    .search( this.value )
                    .draw();
            } );
        }
    </script>
@endsection
