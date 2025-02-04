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

    <div style="padding: 20px;">
        <h1>Hello this is the admin page</h1>
        <p></p>
        <button class="btn btn-secondary" onclick="goToPracticeMenu()">Question Menu</button>
        <button class="btn btn-secondary" onclick="goToContestMenu()">Contest Menu</button>
        {{-- <button class="btn btn-secondary" onclick="goToPracticeReport()">Practice Report</button>
        <button class="btn btn-secondary" onclick="goToContestReport()">Contest Report</button> --}}

    </div>


@endsection
@section('tail')

    <script>
        $(document).ready(function(){
        });

        function goToPracticeMenu(){
            window.location.href = `/admin/practice/index`;
        }

        function goToContestMenu(){
            window.location.href = `/admin/contest/index`;
        }

        // function goToPracticeReport(){
        //     window.location.href = `/admin/practice/report-practice`;
        // }

        // function goToContestReport(){
        //     window.location.href = `/admin/contest/report-contest`;
        // }


    </script>
@endsection
