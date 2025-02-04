@extends('layout.layout_practice')

@section('head')
    <style>
        .question {
            border-radius: 10px;
            background-color: rgb(255, 255, 255);
            width: 90%;
            margin: auto;
            margin-top: 20px;
            padding: 1rem;
        }

        .question-container {
            display: flex;
            flex-direction: row;
            margin: auto;
        }

        .question-content-left {
            width: 80%;
        }

        .question-content-button {
            width: 20%;
            text-align: center;
            margin: auto;
        }

        .btn {
            width: 60%;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .pagination .page-item {
            margin: 0 2px;
        }

        .pagination .page-link {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 8px 16px;
            color: #007bff;
            background-color: #fff;
            text-decoration: none;
        }

        .pagination .page-link:hover {
            background-color: #e9ecef;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            cursor: not-allowed;
        }
    </style>
@endsection

@section('content')

    <div id="content-separator" style="display: flex; justify-content: center;">
        <div style="flex: 1;">
            <div style="width: 100%; display: flex; flex-direction: column; justify-content: center;">
                @foreach ($questions as $question)
                    <div class="question">
                        <div class="question-container">
                            <div class="question-content-left">
                                <h4>{{ $question->title }}</h4>
                                <p>{{ $question->subtitle }}</p>
                                @if($question->difficulty_level == 'EASY')
                                    <p class="text-success">Easy</p>
                                @elseif($question->difficulty_level == 'MEDIUM')
                                    <p class="text-warning">Medium</p>
                                @elseif($question->difficulty_level == 'HARD')
                                    <p class="text-danger">Hard</p>
                                @endif
                            </div>
                            <div class="question-content-button">
                                @php
                                    $answered = $answers->get($question->id);
                                @endphp
                                @if ($answered && $answered->is_true)
                                    <button type="button" class="btn btn-light" onclick="goToQuestion({{ $question->id }})">Solved <i class="fa fa-check"></i></button>
                                @else
                                    <button type="button" class="btn btn-success" onclick="goToQuestion({{ $question->id }})">Solve Practice</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                {{ $questions->links('pagination::bootstrap-4') }}
            </div>
        </div>

        {{-- <div id="sidebar" style="flex: 0 0 20%;">
            <!-- Sidebar for filter -->
            <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse" style="height: 100vh; overflow-y: auto;">
                <div class="position-sticky">
                    <div class="list-group list-group-flush mx-3 mt-4" id="menuSidebar">
                        <div class="list-group-item py-2 ripple" aria-current="true">
                            <div>STATUS</div>
                            <div class="" aria-current="true">
                                <input type="checkbox">
                                <span>Solved</span>
                            </div>
                            <div class="" aria-current="true">
                                <input type="checkbox">
                                <span>Unsolved</span>
                            </div>
                        </div>

                        <div class="list-group-item py-2 ripple" aria-current="true">
                            <div>DIFFICULTY</div>
                            <div class="" aria-current="true">
                                <input type="checkbox">
                                <span>Easy</span>
                            </div>
                            <div class="" aria-current="true">
                                <input type="checkbox">
                                <span>Medium</span>
                            </div>
                            <div class="" aria-current="true">
                                <input type="checkbox">
                                <span>Hard</span>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Sidebar -->
        </div> --}}

    </div>

@endsection

@section('tail')

    <script>
        function goToQuestion(id){
            window.location.href = "/practice/question/" + id;
        }
    </script>

@endsection
