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

    </style>
@endsection

@section('content')

    {{-- FILTER --}}
    {{-- QUESTION, USER, IS CORRECT OR NOT --}}
    <div style="padding: 20px;">

        <div id="add-question">
            <button class="btn btn-secondary" onclick="goToIndex()"><i class="fa fa-arrow-left"></i> Back</button>
            <button class="btn btn-primary" onclick="goToAddQuestion()"><i class="fa fa-plus"></i> Add Question</button>
        </div>

        <div id="filter-row" class="row mb-3">
            <div class="col-md-4">
                <label for="filter-title">Title</label>
                <input id="filter-title" list="title-list" type="text" placeholder="Search Title" class="form-control" />
                <datalist id="title-list">
                    <!-- Options will be populated here dynamically -->
                </datalist>
            </div>
            <div class="col-md-4">
                <label for="filter-subtitle">Subtitle</label>
                <input id="filter-subtitle" list="subtitle-list" type="text" placeholder="Search Subtitle" class="form-control" />
                <datalist id="subtitle-list">
                    <!-- Options will be populated here dynamically -->
                </datalist>
            </div>
            <div class="col-md-4">
                <label for="filter-question-category">Question Category</label>
                <select id="filter-question-category" class="form-control">
                    <option value="">Select...</option>
                    <option value="PRACTICE">Practice</option>
                    <option value="CONTEST">Contest</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="filter-difficulty-level">Difficulty Level</label>
                <select id="filter-difficulty-level" class="form-control">
                    <option value="">Select...</option>
                    <option value="EASY">Easy</option>
                    <option value="MEDIUM">Medium</option>
                    <option value="HARD">Hard</option>
                </select>
            </div>
        </div>

        <div style="overflow-y: auto; width:100%">
            <table id="questions-table" class="table table-striped table-bordered" style="width: 80%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Action</th>
                        <th>Title</th>
                        <th>Subtitle</th>
                        <th>Question Category</th>
                        <th>Difficulty Level</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @endsection
    @section('tail')

    <script>
        $(document).ready(function(){
            createQuestionTable()
        });

        function createQuestionTable(){
            var table = $('#questions-table').DataTable({
                processing: true,
                serverSide: false,
                // filter: false,
                ajax: {
                    url: '{{ url('/admin/practice/question/data') }}', // Your route to fetch products
                    type: 'GET'
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'action', name: 'action' },
                    { data: 'title', name: 'title' },
                    { data: 'subtitle', name: 'subtitle' },
                    { data: 'question_category', name: 'question_category' },
                    { data: 'difficulty_level', name: 'difficulty_level' }
                ],
            });

            // Apply the search
            $('#filter-title').on( 'keyup change clear', function () {
                table
                    .column(2) // column index for 'Title'
                    .search( this.value )
                    .draw();
            } );

            $('#filter-subtitle').on( 'keyup change clear', function () {
                table
                    .column(3) // column index for 'Subtitle'
                    .search( this.value )
                    .draw();
            } );

            $('#filter-question-category').on( 'keyup change clear', function () {
                table
                    .column(4) // column index for 'Difficulty Level'
                    .search( this.value )
                    .draw();
            } );

            $('#filter-difficulty-level').on( 'keyup change clear', function () {
                table
                    .column(5) // column index for 'Difficulty Level'
                    .search( this.value )
                    .draw();
            } );

        }

        function goToQuestionDetail(id){
            window.location.href = '/admin/practice/question/add/' + id + '/detail'
        }

        function goToAddQuestion(){
            window.location.href = '/admin/practice/question/add'
        }

        function goToIndex(){
            window.location.href = '/admin/index';
        }

    </script>
@endsection
