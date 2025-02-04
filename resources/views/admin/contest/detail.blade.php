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

        #questions-table_filter {
            display: none;
        }

        .table-container {
            margin-bottom: 2rem;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-container th, .table-container td {
            padding: 0.75rem;
            vertical-align: top;
            border: 1px solid #dee2e6;
        }

        .table-container thead {
            background-color: #f8f9fa;
        }

        .form-row {
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        #back {
            margin-bottom: 2rem;
        }
    </style>
@endsection

@section('content')
    <div style="padding: 20px;">

        <div id="back">
            <button class="btn btn-secondary mt-4" onclick="goBackContestIndex()"><i class="fa fa-arrow-left"></i> Back</button>
            <button class="btn btn-secondary mt-4" onclick="downloadReportContest()"><i class="fa fa-download"></i> Download</button>
        </div>

        <div class="form-row align-items-center">
            <div class="col-md-3">
                <label for="competition-name">Competition Name:</label>
            </div>
            <div class="col-md-9">
                <input type="text" id="competition-name" name="competition-name" class="form-control" value="{{$contest->competition_name}}" readonly>
            </div>
        </div>
        <div class="form-row align-items-center">
            <div class="col-md-3">
                <label for="start-date">Start Date:</label>
            </div>
            <div class="col-md-9">
                <input type="text" id="start-date" name="start-date" class="form-control" maxlength="255" value="{{$contest->start_date}}" readonly>
            </div>
        </div>

        <div class="form-row align-items-center">
            <div class="col-md-3">
                <label for="end-date">End Date:</label>
            </div>
            <div class="col-md-9">
                <input type="text" id="end-date" name="end-date" class="form-control" maxlength="255" value="{{$contest->end_date}}" readonly>
            </div>
        </div>

        <!-- Questions Table -->
        <div class="table-container" style="width: 80%">
            <h3>Questions</h3>
            <table id="questions-table" class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                      $index = 0   
                    @endphp
                    @foreach ($questions as $question)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $question->title }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Contestants Table -->
        <div class="table-container" style="width: 80%">
            <h3>Contestants</h3>
            <table id="contestants-table" class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                      $index = 0   
                    @endphp
                    @foreach ($contestants as $contestant)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $contestant->name }}</td>
                            <td>{{ $contestant->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Report Table -->
        <div class="table-container" style="width: 80%">
            <h3>Report Contest</h3>
            <table id="report-table" class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $index = 0   
                    @endphp
                    @foreach ($reportTemp as $report)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $report['name'] }}</td>
                            <td>{{ $report['email'] }}</td>
                            <td>{{ $report['score'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Report Question Table -->
        <div class="table-container" style="width: 80%">
            <h3>Report Question Contest</h3>
            <table id="report-question-table" class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Title</th>
                        <th>Diffculty Level</th>
                        <th>Correct</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $index = 0   
                    @endphp
                    @foreach ($reportQuestions as $reportQuestion)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $reportQuestion['name'] }}</td>
                            <td>{{ $reportQuestion['email'] }}</td>
                            <td>{{ $reportQuestion['title'] }}</td>
                            <td>{{ $reportQuestion['difficulty_level'] }}</td>
                            <td>{{ $reportQuestion['correct'] }}</td>
                            <td>{{ $reportQuestion['score'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('tail')
    <script>
        $(document).ready(function() {
            // Initialize DataTables for questions and contestants tables
            $('#questions-table').DataTable({
                paging: true,
                searching: true,
                info: true,
                columnDefs: [
                    { targets: '_all', orderable: false }
                ]
            });

            $('#contestants-table').DataTable({
                paging: true,
                searching: true,
                info: true,
                columnDefs: [
                    { targets: '_all', orderable: false }
                ]
            });

            $('#report-table').DataTable({
                paging: true,
                searching: true,
                info: true,
                columnDefs: [
                    { targets: '_all', orderable: false }
                ]
            });
            $('#report-question-table').DataTable({
                paging: true,
                searching: true,
                info: true,
                columnDefs: [
                    { targets: '_all', orderable: false }
                ]
            });

        });
        // Go back to contest index page
        function goBackContestIndex() {
            window.location.href = "/admin/contest/index";
        }
    </script>
@endsection
