@extends('layout.layout_practice')

@section('head')
    <style>
        /* Existing styles */
        .question {
            border-radius: 10px;
            background-color: rgb(255, 255, 255);
            width: 90%;
            margin: auto;
            margin-top: 20px;
            padding: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .question:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }

        .question-container {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        .question-content-left {
            width: 75%;
        }

        .question-content-button {
            width: 25%;
            text-align: center;
            margin: auto;
        }

        .btn {
            width: 100%;
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

        .status {
            display: inline-flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .status-icon {
            margin-right: 5px;
        }

        .status-not-started {
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        .status-ongoing {
            color: #28a745;
            border: 1px solid #28a745;
        }

        .status-ended {
            color: #6c757d;
            border: 1px solid #6c757d;
        }

        #content-separator {
            display: flex;
            justify-content: center;
        }

        #content-separator > div {
            flex: 1;
        }

        .list-group-item {
            border: none;
            padding: 1rem;
        }

        .list-group-item .ripple {
            cursor: pointer;
        }

        .list-group-item .ripple:hover {
            background-color: #e2e6ea;
        }

        .list-group-item input {
            margin-right: 10px;
        }

        .list-group-item span {
            font-size: 0.9rem;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2rem;
            color: #007bff;
        }

        .text-success {
            color: #28a745;
        }

        .text-warning {
            color: #ffc107;
        }

        .text-danger {
            color: #dc3545;
        }

        .progress-container {
            width: 80%;
            margin: 20px auto;
            background-color: #f0f0f0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 10px;
            text-align: center;
        }

        .progress-bar {
            height: 25px;
            width: 0;
            background-color: #007bff;
            border-radius: 5px;
            transition: width 0.5s ease;
        }

        .progress-text {
            margin-top: 5px;
            font-size: 1rem;
            color: #333;
        }
    </style>
@endsection

@section('content')
    <a href="{{ url('/contest') }}" class="btn btn-secondary mb-2 mt-2" style="width:100px; margin-left:20px;"><i class="fa fa-arrow-left"></i> Back</a>

    {{-- <a href="{{ url('/contest') }}" class="btn btn-secondary mb-3" style="width:100px"><i class="fa fa-arrow-left"></i> Back</a> --}}
    <h1>{{ $contest->competition_name }}</h1>
    @php
        $startDate = \Carbon\Carbon::parse($contest->start_date);
        $endDate = \Carbon\Carbon::parse($contest->end_date);
        $now = \Carbon\Carbon::now();

        $isOngoing = $now->between($startDate, $endDate);
        $isNotStarted = $now->lt($startDate);
        $isEnded = $now->gt($endDate);

        $totalDuration = $startDate->diffInSeconds($endDate);
        $elapsedDays = $startDate->diffInSeconds($now);
        $progress = $totalDuration > 0 ? ($elapsedDays / $totalDuration) * 100 : 0;
        if($progress > 100){
            $progress = 100;
        }
        $progressText = $isNotStarted ? 'Not Started' : ($isEnded ? 'Ended' : number_format($progress, 2) . '%');
    @endphp
    <p style="text-align: center; font-size: 1rem;">Start: {{ $startDate->format('d M Y H:i:s') }}</p>
    <p style="text-align: center; font-size: 1rem;">End: {{ $endDate->format('d M Y H:i:s') }}</p>
    <p style="text-align: center; font-size: 1rem;" id="time-left"></p>

    <!-- Progress Bar -->
    <div class="progress-container">
        <div class="progress-bar" id="progress-bar" style="width: {{ $progress }}%;"></div>
        <div class="progress-text" id="progress-text">{{ $progressText }}</div>
    </div>

    <div style="text-align: center; margin-bottom: 20px;">
        @if ($isNotStarted)
            <span class="status status-not-started">
                <i class="fa fa-calendar-plus status-icon"></i> Not Started
            </span>
        @elseif ($isOngoing)
            <span class="status status-ongoing">
                <i class="fa fa-calendar-check status-icon"></i> Ongoing
            </span>
        @else
            <span class="status status-ended">
                <i class="fa fa-calendar-times status-icon"></i> Has Ended
            </span>
        @endif
    </div>

    <div id="content-separator">
        <div>
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
                                // dd($question->id, $contest->id);
                                // $answered = optional($answers->get($question->id))->get($contest->id);
                                // $answered = optional(optional($answers->get($question->id)))->get($contest->id);
                                // dd($answered)
                                $questionAnswers = $answers->get($question->id);  // First get the answers by question ID
                                $answered = false;
                                if($questionAnswers->competition_batch_id == $contest->id && $questionAnswers->question_id == $question->id && $questionAnswers->is_true && $questionAnswers->user_id == Auth::user()->id ) {
                                    $answered = true;
                                }
                                // $answered = $questionAnswers[$contest->id] ?? false;
                            @endphp
                            @if(!$isOngoing)
                                <button type="button" class="btn btn-secondary" disabled>Has Ended</button>
                            @elseif ($answered)
                                <button type="button" class="btn btn-light" onclick="goToQuestion({{ $question->id }})">Solved <i class="fa fa-check"></i></button>
                            @else
                                <button type="button" class="btn btn-success" onclick="goToQuestion({{ $question->id }})">Solve Question</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            {{ $questions->links('pagination::bootstrap-4') }}
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

        var isCounting = true

        function goToQuestion(questionId) {
            const contestId = {{ $contest->id }};
            window.location.href = `/contest/${contestId}/index/${questionId}`;
        }

        function timeLeft(startTime, endTime) {
            // Calculate the difference in milliseconds
            let timeDiff = endTime - startTime;

            // Convert time difference to years, months, days, hours, minutes, and seconds
            let years = endTime.getFullYear() - startTime.getFullYear();
            let months = endTime.getMonth() - startTime.getMonth();
            let days = endTime.getDate() - startTime.getDate();
            let hours = endTime.getHours() - startTime.getHours();
            let minutes = endTime.getMinutes() - startTime.getMinutes();
            let seconds = endTime.getSeconds() - startTime.getSeconds();

            // Adjust for negative values in each component
            if (seconds < 0) {
                seconds += 60;
                minutes--;
            }
            if (minutes < 0) {
                minutes += 60;
                hours--;
            }
            if (hours < 0) {
                hours += 24;
                days--;
            }
            if (days < 0) {
                // Calculate days in the previous month
                let prevMonth = new Date(startTime.getFullYear(), startTime.getMonth(), 0).getDate();
                days += prevMonth;
                months--;
            }
            if (months < 0) {
                months += 12;
                years--;
            }

            return {
                years: years,
                months: months,
                days: days,
                hours: hours,
                minutes: minutes,
                seconds: seconds,
                timeDiff: timeDiff // For stopping the loop when time is up
            };
        }

        function calculateProgress(startTime, currentTime, endTime) {
            // Calculate total duration and elapsed time in milliseconds
            let totalDuration = endTime - startTime;
            let elapsedTime = currentTime - startTime;

            // Calculate the progress percentage
            let progressPercentage = (elapsedTime / totalDuration) * 100;

            // Ensure the percentage is between 0 and 100
            progressPercentage = Math.min(Math.max(progressPercentage, 0), 100);

            return progressPercentage;
        }

        function countTime() {
            // End time should be passed from PHP to JavaScript
            let endTime = new Date("{{ $endDate->format('d M Y H:i:s') }}"); // e.g., "2024-08-29 15:00:00"
            let startTime = new Date("{{ $startDate->format('d M Y H:i:s') }}"); // e.g., "2024-08-29 15:00:00"

            // Set an interval to update the time left every second
            let interval = setInterval(function() {
                // Get the current time
                let currentTime = new Date();

                // Calculate the progress percentage
                let progressPercentage = calculateProgress(startTime, currentTime, endTime);

                // Calculate the time left
                let timeRemaining = timeLeft(currentTime, endTime);

                // Display the time left
                console.log(`Time Left: ${timeRemaining.years} years, ${timeRemaining.months} months, ${timeRemaining.days} days, ${timeRemaining.hours} hours, ${timeRemaining.minutes} minutes, and ${timeRemaining.seconds} seconds.`);
                let displayTime = "Time Left: "

                if(timeRemaining.years != 0) {
                    displayTime += `${timeRemaining.years} years,`
                }

                if(timeRemaining.months != 0) {
                    displayTime += `${timeRemaining.months} months,`
                }

                if(timeRemaining.days != 0) {
                    displayTime += `${timeRemaining.days} days,`
                }

                if(timeRemaining.hours != 0) {
                    displayTime += `${timeRemaining.hours} hours,`
                }

                if(timeRemaining.minutes != 0) {
                    displayTime += `${timeRemaining.minutes} minutes,`
                }

                if(displayTime == '' && timeRemaining.seconds == 0){
                    displayTime = 'Time is up!'
                }
                else{
                    displayTime += `${timeRemaining.seconds} seconds`
                }
                console.log(`Time Left: ${timeRemaining.years} years, ${timeRemaining.months} months, ${timeRemaining.days} days, ${timeRemaining.hours} hours, ${timeRemaining.minutes} minutes, and ${timeRemaining.seconds} seconds.`);
                console.log(`Progress: ${progressPercentage.toFixed(2)}%`);
                $('#time-left').text(displayTime)
                $("#progress-bar").css("width", `${progressPercentage.toFixed(2)}%`);
                $("#progress-text").text(`${progressPercentage.toFixed(2)}%`);
                // If time is up, clear the interval
                if (timeRemaining.timeDiff <= 0) {
                    clearInterval(interval);
                    location.reload();
                    $('#time-left').text('Time is up!')
                    console.log("Time is up!");
                }
            }, 1000); // 1000 milliseconds = 1 second
        }

        // Call the function to
        // dont call the function is it ended
        let endTime1 = new Date("{{ $endDate->format('d M Y H:i:s') }}"); // e.g., "2024-08-29 15:00:00"
        let currentTime1 = new Date();
        let timeRemaining1 = timeLeft(currentTime1, endTime1);
        if(timeRemaining1.timeDiff > 0){
            countTime();
        }

    </script>

@endsection
