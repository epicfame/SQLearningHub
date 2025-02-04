@extends('layout.layout_practice')

@section('head')
    <style>
        .contest {
            border-radius: 10px;
            background-color: rgb(255, 255, 255);
            width: 90%;
            margin: auto;
            margin-top: 20px;
            padding: 1rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        .contest:hover {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .contest-container {
            display: flex;
            flex-direction: row;
            margin: auto;
            align-items: center;
        }

        .contest-content-left {
            width: 80%;
        }

        .contest-content-button {
            width: 20%;
            text-align: center;
        }

        .btn {
            width: 100%;
            border-radius: 5px;
            padding: 10px;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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
            display: flex;
            align-items: center;
            font-weight: bold;
            margin-top: 10px;
        }

        .status .status-icon {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        .status-not-started {
            color: #17a2b8;
        }

        .status-ongoing {
            color: #28a745;
        }

        .status-ended {
            color: #6c757d;
        }

        .progress-bar-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-top: 10px;
        }

        .progress {
            height: 20px;
            width: 100%;
            background-color: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
            position: relative;
        }

        .progress-bar {
            height: 100%;
            transition: width 0.5s ease;
        }

        .progress-bar-not-started {
            background-color: #17a2b8;
        }

        .progress-bar-ongoing {
            background-color: #28a745;
        }

        .progress-bar-ended {
            background-color: #6c757d;
        }

        .progress-bar-text {
            position: absolute;
            width: 100%;
            text-align: center;
            color: #fff;
            font-weight: bold;
            line-height: 20px;
        }

        .no-contests {
            text-align: center;
            margin-top: 20px;
            font-size: 1.25rem;
            color: #6c757d;
        }
    </style>
@endsection

@section('content')

    <div id="content-separator" style="display: flex; justify-content: center;">

        <div style="flex: 1;">
            <div style="width: 100%; display: flex; flex-direction: column; justify-content: center;">
                @if(count($contests) == 0)
                    <div class="no-contests">
                        <i class="fa fa-info-circle" style="font-size: 2rem; color: #007bff;"></i>
                        <p>No contests available at the moment.</p>
                    </div>
                @else
                    @foreach ($contests as $contest)
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

                        <div class="contest">
                            <div class="contest-container">
                                <div class="contest-content-left">
                                    <h4>{{ $contest->competition_name }}</h4>
                                    <p>Start: {{ $startDate->format('d M Y H:i:s') }}</p>
                                    <p>End: {{ $endDate->format('d M Y H:i:s') }}</p>
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
                                    <div class="progress-bar-container">
                                        <div class="progress">
                                            <div class="progress-bar 
                                                {{ $isNotStarted ? 'progress-bar-not-started' : ($isEnded ? 'progress-bar-ended' : 'progress-bar-ongoing') }}" 
                                                role="progressbar"
                                                aria-valuenow="{{ $progress }}"
                                                aria-valuemin="0"
                                                aria-valuemax="100"
                                                style="width: {{ $progress }}%;">
                                                <span class="progress-bar-text">{{ $progressText }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="contest-content-button">
                                    @if ($isOngoing)
                                        <button type="button" class="btn btn-primary" onclick="goToContest({{ $contest->id }})">
                                            <i class="fa fa-play"></i> Join Contest
                                        </button>
                                    @elseif ($isNotStarted)
                                        <button type="button" class="btn btn-secondary" disabled>
                                            <i class="fa fa-hourglass-start"></i> Not Started
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-secondary" onclick="goToContest({{ $contest->id }})">
                                            <i class="fa fa-ban"></i> Contest Ended
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                {{ $contests->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

@endsection

@section('tail')

    <script>
        function goToContest(id) {
            window.location.href = "/contest/" + id;
        }
    </script>

@endsection
