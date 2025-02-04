@extends('layout.layout_practice')

@section('head')
    <style>
        /* Existing styles for table filters, pagination, etc. */
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

        .form-group {
            margin-bottom: 1rem;
        }

        .form-row {
            margin-bottom: 0.5rem;
        }

        .table-list-container {
            margin-bottom: 1rem;
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

        .user-container, .question-container {
            margin-bottom: 1rem;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div id="back">
            <button class="btn btn-secondary mt-4" onclick="goBackContestIndex()"><i class="fa fa-arrow-left"></i> Back</button>
        </div>
        <h2>Create a New Contest</h2>
        <form id="create-contest-form" class="form" method="POST" autocomplete="off">
            @csrf

            <!-- Competition Name -->
            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="competition-name">Competition Name:</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="competition-name" name="competition_name" class="form-control" value="{{ old('competition_name') }}" required>
                </div>
            </div>

            <!-- Start Date -->
            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="start-date">Start Date:</label>
                </div>
                <div class="col-md-9">
                    <input type="datetime-local" id="start-date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                </div>
            </div>

            <!-- End Date -->
            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="end-date">End Date:</label>
                </div>
                <div class="col-md-9">
                    <input type="datetime-local" id="end-date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                </div>
            </div>

            <!-- Questions Section -->
            <div class="form-row align-items-center mt-3">
                <div class="col-md-3">
                    <label for="questions">Select Questions:</label>
                </div>
                <div class="col-md-9">
                    <select id="questions" name="questions[]" class="form-control" multiple size="10" required>
                        @foreach ($questions as $question)
                            <option value="{{ $question->id }}">{{ $question->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Contestants Section -->
            <div class="form-row align-items-center mt-3">
                <div class="col-md-3">
                    <label for="contestants">Select Contestants:</label>
                </div>
                <div class="col-md-9">
                    <select id="contestants" name="contestants[]" class="form-control" multiple size="10" required>
                        @foreach ($contestants as $contestant)
                            <option value="{{ $contestant->id }}">{{ $contestant->name }} ({{ $contestant->email }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-row align-items-center mt-3">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('tail')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize select2 for the questions and users multi-select dropdowns
            $('#questions').select2({
                placeholder: 'Select Questions',
                width: '100%',
                allowClear: true
            });

            $('#contestants').select2({
                placeholder: 'Select Contestants',
                width: '100%',
                allowClear: true
            });

            // Handle form submission via AJAX
            $('#create-contest-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/admin/contest/add',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        // Handle the success response
                        alert('Contest created successfully!');
                        // REMOVE THE INPUTTED DATA
                        $('#create-contest-form')[0].reset();
                        $('#questions').val([]).trigger('change'); // Reset select2 questions
                        $('#contestants').val([]).trigger('change');
                    },
                    error: function(xhr) {
                        // Handle the error response
                        const errors = xhr.responseJSON.errors;
                        let errorMessages = '';
                        $.each(errors, function(key, value) {
                            errorMessages += value.join(' ') + '\n';
                        });
                        alert('Error: \n' + errorMessages);
                    }
                });
            });
            
        });
        // Go back to contest index page
        function goBackContestIndex() {
            window.location.href = "/admin/contest/index";
        }
    </script>
@endsection
