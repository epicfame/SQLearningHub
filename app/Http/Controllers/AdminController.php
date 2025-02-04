<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Menu;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Competition;
use App\Models\UserAnswer;
use DB;
use Auth;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Excel;
use App\Exports\ReportCoreExport;

class AdminController extends Controller
{

    public function index(){
        $user = Auth::user();
        return view('admin.index',['menu' => 'admin', 'user'=>$user]);
    }
    //
    public function reportPractice(){
        $user = Auth::user();
        $questions = Question::leftJoin('user_answers', 'user_answers.question_id', '=', 'questions.id')
            ->join('users', 'users.id', '=', 'user_answers.user_id')
            // ->where('user_answers.user_id', '=', Auth::user()->id)
            ->select(
                'questions.id',
                'questions.title',
                'name',
                DB::raw('MAX(user_answers.is_true) as is_correct'), // true if the user has a correct answer, false otherwise
                DB::raw('COUNT(user_answers.id) as attempts') // the number of attempts the user has made
            )
            ->groupBy('questions.title','questions.id', 'user_id', 'name')
            ->paginate(10);
        return view('admin.practice.report',['questions' => $questions, 'menu' => 'admin', 'user'=>$user]);
    }

    public function reportPracticeData(){
        $questions = Question::leftJoin('user_answers', 'user_answers.question_id', '=', 'questions.id')
            ->join('users', 'users.id', '=', 'user_answers.user_id')
            // ->where('user_answers.user_id', '=', Auth::user()->id)
            ->select(
                'questions.id',
                'questions.title',
                'name as user_name',
                DB::raw('MAX(user_answers.is_true) as is_correct'), // true if the user has a correct answer, false otherwise
                DB::raw('COUNT(user_answers.id) as attempts') // the number of attempts the user has made
            )
            ->groupBy('questions.title','questions.id', 'name')
            ->get();
            // $temp = [];
            // for($i=0 ; $i<10 ; $i++){
            //     foreach($questions as $question){
            //         $temp[] = [
            //             'id' => $question->id,
            //             'title' => $question->title,
            //             'user_name' => $question->user_name,
            //             'is_correct' => $question->is_correct,
            //             'attempts' => $question->attempts,
            //         ];
            //     }
            // }
            // $questions = $temp;
            $datatables = Datatables::of($questions)
            ->editColumn('is_correct', function($data){
                if($data['is_correct'] == true)
                    return '<span style="color:green">Yes</span>';
                return '<span style="color:red">No</span>';
            })
            ->rawColumns(['is_correct'])
            ->make(true);
            return $datatables;
    }

    public function indexPractice(){
        $user = Auth::user();
        return view('admin.practice.index',['menu' => 'admin', 'user'=>$user]);
    }

    public function addPractice(){
        $user = Auth::user();
        return view('admin.practice.add',['menu' => 'admin', 'user'=>$user]);
    }

    public function addQuestion(Request $request){
        $data = $request->all();
        $title = $data['title'];
        $subtitle = $data['subtitle'];
        $introduction = $data['introduction'];
        $questionText = $data['question'];
        $difficulty = strtoupper($data['difficulty']);
        $questionCategory = strtoupper($data['question_category']);
        $tableList = json_decode($data['tableList'], true); // Decoding JSON data
        $maxMemory = $data['max-memory'];
        $maxTime = $data['max-time'];
        $answerText = strtoupper($data['answer']);
        // $tableList
        // array:2 [
        //     0 => array:2 [
        //       "dbName" => "TEST 1"
        //       "columns" => array:2 [
        //         0 => array:2 [
        //           "name" => "KOLOM 1 1"
        //           "dataType" => "HALO"
        //         ]
        //         1 => array:2 [
        //           "name" => "KOLOM 1 2"
        //           "dataType" => "HALO1"
        //         ]
        //       ]
        //     ]
        //     1 => array:2 [
        //       "dbName" => "TEST 2"
        //       "columns" => array:1 [
        //         0 => array:2 [
        //           "name" => "KOLOM 2 1"
        //           "dataType" => "HALO3"
        //         ]
        //       ]
        //     ]
        //   ]

        $response = [
            'success' => false,
            'message' => 'UNKOWN ERROR',
        ];

        // validation
        
        // if($title == '' || ){
        //     $response['message'] = 'Fill all the mandatory';
        //     return response()->json($response);
        // }

        if (empty($title) || empty($subtitle) || empty($introduction) || empty($questionText) || empty($difficulty)) {
            $response['message'] = 'Fill all the mandatory fields with valid data';
            return response()->json($response);
        }

        // Check tableList is not empty and has at least one table with at least one column name and type
        if (empty($tableList) || !is_array($tableList)) {
            $response['message'] = 'Table list is required and must be an array';
            return response()->json($response);
        }

        // Validate that tableList contains at least one table and that each table has a dbName and columns
        foreach ($tableList as $tableData) {
            if (empty($tableData['dbName']) || !is_string($tableData['dbName'])) {
                $response['message'] = 'Each table must have a valid database name';
                return response()->json($response);
            }

            if (empty($tableData['columns']) || !is_array($tableData['columns'])) {
                $response['message'] = 'Each table must have at least one column with name and dataType';
                return response()->json($response);
            }

            foreach ($tableData['columns'] as $columnData) {
                if (empty($columnData['name']) || !is_string($columnData['name'])) {
                    $response['message'] = 'Each column must have a valid name';
                    return response()->json($response);
                }

                if (empty($columnData['dataType']) || !is_string($columnData['dataType'])) {
                    $response['message'] = 'Each column must have a valid dataType';
                    return response()->json($response);
                }
            }
        }

        DB::beginTransaction();
        try{
            $tableText = '';
            // Process TableList
            foreach ($tableList as $tableData) {
                // Create a Table
                $tableText .= $tableData['dbName'] . ',';

                // hutan,id-integer;nama_hutan-varchar;wilayah_hutan-varchar;jenis_pohon_id-integer;banyak-integer:jenis_pohon,id-integer;jenis_pohon-varchar
                // DATABASE NAME1,KOLOM 1-KOLOM TYPE 1;KOLOM 2-KOLOM TYPE 2;:DATABASE NAME2,KOLOM 2-KOLOM 2 TYPE 1;:
                // DATABASE NAME1,KOLOM 1-KOLOM TYPE 1;KOLOM 2-KOLOM TYPE 2:DATABASE NAME2,KOLOM 2-KOLOM 2 TYPE 1:
                // Process Columns
                $columnCount = count($tableData['columns']);
                $count = 0;
                foreach ($tableData['columns'] as $columnData) {
                    $count++;
                    $tableText .=  $columnData['name'] . '-' . $columnData['dataType'];
                    if($count < $columnCount){
                        $tableText .= ';';
                    }
                }
                $tableText .= ':';
            }

            // REMOVE THE LAST ':'
            $tableText = rtrim($tableText, ':');

            // save the question first
            $question = new Question();
            $question->title = $title;
            $question->subtitle = $subtitle;
            $question->introduction = $introduction;
            $question->question = $questionText;
            $question->question_category = $questionCategory;
            $question->difficulty_level = $difficulty;
            $question->table_list = $tableText;
            $question->save();

            $questionId = $question->id;

            // save the answer
            $answer = new Answer();
            $answer->code_answer = $answerText;
            $answer->max_memory = $maxMemory;
            $answer->max_time = $maxTime;
            $answer->question_id = $questionId;

            $answer->save();

            $response['success'] = true;
            $response['message'] = 'Successfuly insert question and answer!';
            DB::commit();
        } catch(Exception $e){
            $response['message'] = 'Error when adding question!';
            DB::rollback();
        }
        return response()->json($response);
    }

    public function getQuestion(){
        $questions = Question::all();
        $datatables = Datatables::of($questions)
                                ->addColumn('action', function($data){
                                    return '<button class="btn btn-secondary" onclick="goToQuestionDetail(' . $data->id . ')"><i class="fa fa-eye"></button>';
                                })
                                 ->make(true);
        return $datatables;
    }

    public function questionDetail($id){
        $user = Auth::user();
        $question = Question::find($id);
        $answer = Answer::where('question_id',$id)->first();
        return view('admin.practice.question_detail',['menu' => 'admin', 'user'=>$user, 'question' => $question, 'answer' => $answer]);
    }

    // CONTEST
    // =========================================================

    public function indexContest(){
        $user = Auth::user();
        return view('admin.contest.index',['menu' => 'admin', 'user'=>$user]);
    }

    public function getContest(){
        $contests = Competition::all();
        $datatables = Datatables::of($contests)
                                ->addColumn('action', function($data){
                                    return '<button class="btn btn-secondary" onclick="goToContestDetail(' . $data->id . ')"><i class="fa fa-eye"></button>';
                                })
                                ->editColumn('start_date', function($data) {
                                    $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->start_date)->format('d/m/Y H:i:s');
                                    return $startDate;
                                })
                                ->editColumn('end_date', function($data) {
                                    $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->end_date)->format('d/m/Y H:i:s');
                                    return $endDate;
                                })
                                ->make(true);
        return $datatables;
    }

    public function addContest(){
        $user = Auth::user();
        $questions = Question::where('question_category', 'CONTEST')->get();
        $contestants = User::where('role', 'Guest')->get();
        return view('admin.contest.add',['menu' => 'admin', 'user'=>$user, 'questions' => $questions, 'contestants' => $contestants]);
    }
    

    public function addContestData(Request $request){
        $data = $request->all();
        $competitionName = $data['competition_name'];
        $startDate = $data['start_date'];
        $endDate = $data['end_date'];
        $questions = $data['questions']; // array for question id
        $contestants = $data['contestants']; // array for contestant id

        $questionText = implode(',', $questions);
        $contestantText = implode(',', $contestants);

        $competition = new Competition();
        $competition->competition_name = $competitionName;
        $competition->start_date = $startDate;
        $competition->end_date = $endDate;
        $competition->question_id = $questionText;
        $competition->contestant_id = $contestantText;

        $competition->save();

    }

    public function showContestDetail($id){
        $user = Auth::user();
        $contest = Competition::find($id);
        $questionId = explode(',', $contest->question_id);
        $contestantId = explode(',', $contest->contestant_id);
        $questions = Question::whereIn('id', $questionId)->get();
        $contestants = User::whereIn('id', $contestantId)->where('role', 'Guest')->get();

        $temp = [];

        $maxScore = 0;
        foreach($questions as $question) {
            if($question->difficulty_level == 'HARD'){
                $maxScore += 3;
            }
            else if($question->difficulty_level == 'MEDIUM'){
                $maxScore += 2;
            }
            else if($question->difficulty_level == 'EASY'){
                $maxScore += 1;
            }
        }

        foreach($contestants as $contestant){
            foreach($questions as $question){
                $userAnswer = UserAnswer::where('user_id', $contestant->id)
                                        ->where('question_id', $question->id)
                                        ->where('competition_batch_id', $id)
                                        ->where('is_true', 1)
                                        ->first();
                $isSubmitted = UserAnswer::where('user_id', $contestant->id)
                                        ->where('question_id', $question->id)
                                        ->where('competition_batch_id', $id)
                                        ->first();
                $score = 0;
                if($question->difficulty_level == 'HARD'){
                    $score = 3;
                }
                else if($question->difficulty_level == 'MEDIUM'){
                    $score = 2;
                }
                else if($question->difficulty_level == 'EASY'){
                    $score = 1;
                }

                $correct = '';
                if($isSubmitted){
                    if($userAnswer){
                        $correct = 'Correct';
                    }
                    else{
                        $correct = 'Not Correct';
                    }
                }
                else{
                    $correct = 'Not Submitted';
                }

                $temp[] = [
                    'name' => $contestant->name,
                    'email' => $contestant->email,
                    'title' => $question->title,
                    'correct' => $correct,
                    'score' => $userAnswer ? $score : 0,
                    'difficulty_level' => $question->difficulty_level,
                    'max_score' => $maxScore,
                ];
            }
        }
        // $report = User::whereIn('id', $contestantId)
        //                     ->leftJoin('user_answers', function($join){
        //                         $join->on('user_answers.user_id', 'user.id');
        //                     })
        //                     ->where()
        //                     ->where('role', 'Guest')
        //                     ->get();
        $sqlReportQuery = "
        SELECT
            users.name,
            users.email,
            SUM(CASE 
                    WHEN user_answers.is_true = 1 THEN
                        CASE 
                            WHEN questions.difficulty_level = 'HARD' THEN 3
                            WHEN questions.difficulty_level = 'MEDIUM' THEN 2
                            WHEN questions.difficulty_level = 'EASY' THEN 1
                            ELSE 0
                        END
                ELSE 0 END) as `score`
        FROM
            users
        LEFT JOIN user_answers ON user_answers.user_id = users.id AND user_answers.competition_batch_id = $id
        LEFT JOIN questions ON questions.id = user_answers.question_id
        WHERE
            users.id IN (" . implode(',', $contestantId) . ")
        GROUP BY
            users.name,
            users.email
        ";

        $reportQuestionQuery = "
       SELECT
            users.name,
            users.email,
            questions.title,
            CASE
                WHEN user_answers.is_true = 1 THEN 'Correct'
                ELSE 'Not correct'
            END AS `correct`,
            questions.difficulty_level AS 'difficulty_level'
        FROM
            users
        CROSS JOIN questions
        LEFT JOIN user_answers ON user_answers.user_id = users.id
                            AND user_answers.question_id = questions.id
                            AND user_answers.competition_batch_id = $id
        WHERE
            users.id IN (" . implode(',', $contestantId) . ")
        ORDER BY
            users.name,
            users.email,
            questions.title
        ";

        $reportTemp = [];

        foreach($contestants as $contestant){
            $data = [
                'name' => $contestant->name,
                'email' => $contestant->email,
                'score' => 0,
                'difficulty_level' => '',
                'max_score' => $maxScore,
            ];
            foreach($questions as $question){
                $userAnswer = UserAnswer::where('user_id', $contestant->id)
                                        ->where('question_id', $question->id)
                                        ->where('competition_batch_id', $id)
                                        ->where('is_true', 1)
                                        ->first();
                $isSubmitted = UserAnswer::where('user_id', $contestant->id)
                                        ->where('question_id', $question->id)
                                        ->where('competition_batch_id', $id)
                                        ->first();
                $score = 0;
                if($question->difficulty_level == 'HARD'){
                    $score = 3;
                }
                else if($question->difficulty_level == 'MEDIUM'){
                    $score = 2;
                }
                else if($question->difficulty_level == 'EASY'){
                    $score = 1;
                }

                $correct = '';
                if($isSubmitted){
                    if($userAnswer){
                        $data['score'] += $score;
                    }
                }

            }
            $reportTemp[] = $data;
        }


        $reports = DB::select(DB::raw($sqlReportQuery));
        $reportQuestions = DB::select(DB::raw($reportQuestionQuery));
        return view('admin.contest.detail',
        [
            'menu' => 'admin', 
            'user'=>$user, 'questions' => $questions, 
            'contestants' => $contestants, 
            'contest' => $contest, 
            'reports' => $reports,
            'reportTemp' => $reportTemp,
            'reportQuestions' => $temp
        ]);
    }

    public function downloadExcel($id){
        $user = Auth::user();
        $contest = Competition::find($id);
        $questionId = explode(',', $contest->question_id);
        $contestantId = explode(',', $contest->contestant_id);
        $questions = Question::whereIn('id', $questionId)->get();
        $contestants = User::whereIn('id', $contestantId)->where('role', 'Guest')->get();

        $temp = [];
        $maxScore = 0;
        foreach($questions as $question) {
            if($question->difficulty_level == 'HARD'){
                $maxScore += 3;
            }
            else if($question->difficulty_level == 'MEDIUM'){
                $maxScore += 2;
            }
            else if($question->difficulty_level == 'EASY'){
                $maxScore += 1;
            }
        }
        
        foreach($contestants as $contestant){
            foreach($questions as $question){
                $userAnswer = UserAnswer::where('user_id', $contestant->id)
                                        ->where('question_id', $question->id)
                                        ->where('competition_batch_id', $id)
                                        ->where('is_true', 1)
                                        ->first();
                $isSubmitted = UserAnswer::where('user_id', $contestant->id)
                                        ->where('question_id', $question->id)
                                        ->where('competition_batch_id', $id)
                                        ->first();
                $score = 0;
                if($question->difficulty_level == 'HARD'){
                    $score = 3;
                }
                else if($question->difficulty_level == 'MEDIUM'){
                    $score = 2;
                }
                else if($question->difficulty_level == 'EASY'){
                    $score = 1;
                }

                $correct = '';
                if($isSubmitted){
                    if($userAnswer){
                        $correct = 'Correct';
                    }
                    else{
                        $correct = 'Not Correct';
                    }
                }
                else{
                    $correct = 'Not Submitted';
                }

                $temp[] = [
                    'name' => $contestant->name,
                    'email' => $contestant->email,
                    'title' => $question->title,
                    'correct' => $correct,
                    'score' => $userAnswer ? $score : 0,
                    'difficulty_level' => $question->difficulty_level,
                    'max_score' => $maxScore,
                ];
            }
        }
        // $report = User::whereIn('id', $contestantId)
        //                     ->leftJoin('user_answers', function($join){
        //                         $join->on('user_answers.user_id', 'user.id');
        //                     })
        //                     ->where()
        //                     ->where('role', 'Guest')
        //                     ->get();
        $sqlReportQuery = "
        SELECT
            users.name,
            users.email,
            SUM(CASE 
                    WHEN user_answers.is_true = 1 THEN
                        CASE 
                            WHEN questions.difficulty_level = 'HARD' THEN 3
                            WHEN questions.difficulty_level = 'MEDIUM' THEN 2
                            WHEN questions.difficulty_level = 'EASY' THEN 1
                            ELSE 0
                        END
                ELSE 0 END) as `score`
        FROM
            users
        LEFT JOIN user_answers ON user_answers.user_id = users.id AND user_answers.competition_batch_id = $id
        LEFT JOIN questions ON questions.id = user_answers.question_id
        WHERE
            users.id IN (" . implode(',', $contestantId) . ")
        GROUP BY
            users.name,
            users.email
        ";

        $reportQuestionQuery = "
       SELECT
            users.name,
            users.email,
            questions.title,
            CASE
                WHEN user_answers.is_true = 1 THEN 'Correct'
                ELSE 'Not correct'
            END AS `correct`,
            questions.difficulty_level AS 'difficulty_level'
        FROM
            users
        CROSS JOIN questions
        LEFT JOIN user_answers ON user_answers.user_id = users.id
                            AND user_answers.question_id = questions.id
                            AND user_answers.competition_batch_id = $id
        WHERE
            users.id IN (" . implode(',', $contestantId) . ")
        ORDER BY
            users.name,
            users.email,
            questions.title
        ";

        $reportTemp = [];

        foreach($contestants as $contestant){
            $data = [
                'name' => $contestant->name,
                'email' => $contestant->email,
                'score' => 0,
                'difficulty_level' => '',
                'max_score' => $maxScore,
            ];
            foreach($questions as $question){
                $userAnswer = UserAnswer::where('user_id', $contestant->id)
                                        ->where('question_id', $question->id)
                                        ->where('competition_batch_id', $id)
                                        ->where('is_true', 1)
                                        ->first();
                $isSubmitted = UserAnswer::where('user_id', $contestant->id)
                                        ->where('question_id', $question->id)
                                        ->where('competition_batch_id', $id)
                                        ->first();
                $score = 0;
                if($question->difficulty_level == 'HARD'){
                    $score = 3;
                }
                else if($question->difficulty_level == 'MEDIUM'){
                    $score = 2;
                }
                else if($question->difficulty_level == 'EASY'){
                    $score = 1;
                }

                $correct = '';
                if($isSubmitted){
                    if($userAnswer){
                        $data['score'] += $score;
                    }
                }

            }
            $reportTemp[] = $data;
        }


        $reports = DB::select(DB::raw($sqlReportQuery));
        $reportQuestions = DB::select(DB::raw($reportQuestionQuery));
        $data = [
            'summary' => $reportTemp,
            'detail' => $temp,
        ];
        return Excel::download(new ReportCoreExport($data, $contest->title), "Training Report.xlsx");

    }


}
