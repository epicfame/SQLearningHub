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

        #questions-table_filter{
            display: none;
        }

    </style>
@endsection

@section('content')

    {{-- FILTER --}}
    {{-- QUESTION, USER, IS CORRECT OR NOT --}}
    <div style="padding: 20px;">

        <div id="add-question">
            <button class="btn btn-secondary" onclick="goToIndex()"><i class="fa fa-arrow-left"></i> Back</button>
            <button class="btn btn-primary" onclick="goToAddContest()"><i class="fa fa-plus"></i> Add Contest</button>
        </div>
        <div id="filter-row" class="row mb-3">
            <div class="col-md-4">
                <label for="filter-competition-name">Competition Name</label>
                <input id="filter-competition-name" list="competition-name-list" type="text" placeholder="Search Competition Name" class="form-control" />
                <datalist id="competition-name-list">
                    <!-- Options will be populated here dynamically -->
                </datalist>
            </div>
            <div class="col-md-4">
                <label for="filter-start-date">Start Date</label>
                <input id="filter-start-date" list="start-date-list" type="text" placeholder="Search Start Date" class="form-control" />
                <datalist id="start-date-list">
                    <!-- Options will be populated here dynamically -->
                </datalist>
            </div>
            <div class="col-md-4">
                <label for="filter-end-date">End Date</label>
                <input id="filter-end-date" list="end-date-list" type="text" placeholder="Search End Date" class="form-control" />
                <datalist id="end-date-list">
                    <!-- Options will be populated here dynamically -->
                </datalist>
            </div>
        </div>
        <div style="overflow-y: auto; width:100%">
            <table id="contests-table" class="table table-striped table-bordered" style="width: 80%">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>ID</th>
                        <th>Competition Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @endsection
    @section('tail')

    <script>
        $(document).ready(function(){
            createContestTable()
        });

        function createContestTable(){
            var table = $('#contests-table').DataTable({
                processing: true,
                serverSide: false,
                // filter: false,
                ajax: {
                    url: '{{ url('/admin/contest/data') }}', // Your route to fetch products
                    type: 'GET'
                },
                columns: [
                    { data: 'action', name: 'action' },
                    { data: 'id', name: 'id' },
                    { data: 'competition_name', name: 'competition_name' },
                    { data: 'start_date', name: 'start_date' },
                    { data: 'end_date', name: 'end_date' },
                ],
            });

            // Apply the search
            $('#filter-competition-name').on( 'keyup change clear', function () {
                table
                    .column(2) // column index for 'Competition Name'
                    .search( this.value )
                    .draw();
            } );

            $('#filter-start-date').on( 'keyup change clear', function () {
                table
                    .column(3) // column index for 'Start Date'
                    .search( this.value )
                    .draw();
            } );

            $('#filter-end-date').on( 'keyup change clear', function () {
                table
                    .column(4) // column index for 'End Date'
                    .search( this.value )
                    .draw();
            } );

        }

        function goToAddContest(){
            window.location.href = '/admin/contest/add'
        }
        
        function goToContestDetail(id){
            window.location.href = '/admin/contest/' + id + '/detail'
        }

        function goToIndex(){
            window.location.href = '/admin/index';
        }



    </script>
@endsection
