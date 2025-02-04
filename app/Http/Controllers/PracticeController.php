<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Models\Menu;
use App\Models\Question;
use App\Models\UserAnswer;
use Auth;
use Illuminate\Support\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class PracticeController extends Controller
{
    /**
     * index page
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function __construct(){
        $this->menu = 'practice';
    }

    public function index() {
        // $question = Question::join('user_answers', 'user_answers.question_id', '=', 'questions.id')
        // ->where('user_answers.user_id', '=', Auth::user()->id)
        // ->select(
        //     'questions.title',
        // )
        // ->get();

        // $questions = Question::leftJoin('user_answers', function($join)
        //     {
        //         $join->on('user_answers.question_id', '=', 'questions.id')
        //             ->where('user_answers.user_id', '=', Auth::user()->id);
        //     })
        //     ->select(
        //         'questions.title',
        //         'user_answers.user_answer',
        //         'user_answers.is_true'
        //     )
        //     ->get();

        //     $questions = Question::leftJoin('user_answers', function($join)
        //     {
        //         $join->on('user_answers.question_id', '=', 'questions.id')
        //              ->where('user_answers.user_id', '=', Auth::user()->id)
        //              ->where('user_answers.is_true', '=', true); // only consider correct answers
        //     })
        //     ->select(
        //         'questions.title',
        //         'user_answers.user_answer',
        //         'user_answers.is_true'
        //     )
        //     ->get();
        
  
        $questionLists = Question::where('question_category', 'PRACTICE')->paginate(10);
        $user = Auth::user();
        $userAnswer = UserAnswer::where('user_id', $user->id)->where('is_true', true)->get()->keyBy('question_id');
        return view('practice.index', [
            'user' => $user,
            'menu' => $this->menu,
            'questions' => $questionLists,
            'answers' => $userAnswer,
        ]);
    }

    public function practice($id){
        $question = Question::find($id);
        $user = Auth::user();
        $userAnswer = UserAnswer::where('user_id', $user->id)
                                ->where('competition_batch_id', null)
                                ->orderBy('id', 'DESC')
                                ->first();
        $answer = '';
        if($userAnswer){
            $answer = $userAnswer->user_answer;
        }
        return view('practice.practice', [
            'user' => $user,
            'menu' => $this->menu,
            'question' => $question,
            'id' => $id,
            'answer' => $answer,
        ]);
    }

    public function test(){
        return view('sql_compiler', [
        ]);
    }

    private function sortArrayByColumnOrder($array, $columnOrder) {
        return array_map(function($item) use ($columnOrder) {
            $sortedItem = [];
            foreach ($columnOrder as $column) {
                $sortedItem[$column] = $item[$column];
            }
            return $sortedItem;
        }, $array);
    }

    private function keyToIndex($array, $size){
        $newArray = [];
        foreach ($array as $key => $value) {
          $newArray[$key] = array_values($value);
        }
        return $newArray;
    }

    private function compareValue($arrInput, $arrAnswer, $sort){
        // arrInput  = hasil query dari pengguna
        // arrAnswer = hasil query dari jawaban
        // sort = false/true, jika false disorting, true jangan disorting

        // check the arr size too for duplicate
        if(count($arrInput) != count($arrAnswer))
            return false;

        foreach($arrAnswer as $answer){
            if(!$sort)
                sort($answer);
            $flag = false;
            // search $answer to $arrInput
            foreach($arrInput as $key => $input){
                if(!$sort)
                    sort($input);
                if($input == $answer){
                    $flag = true;
                    // delete the input
                    unset($arrInput[$key]);
                    break;
                }
            }
            if($flag == false)
                return false;
        }

        // jika ternyata arrInput masih sisa berarti ada data duplikat/sisa, memastikan lagi
        if(count($arrInput) == 0){
            return true;
        }
        else{
            return false;
        }
    }

    public function compile(Request $request, $id){
        // id = id dari soal
        $response = [
            'success' => false,
            'message' => 'UNKOWN ERROR',
        ];

        // validation check for competition table

        $query = $request['query'];
        $time = 0;
        $memory = 0;
        $isFalse = false;

        // $response1 = Http::post('http://localhost:8080/compile', [
        //     'query' => $query,
        // ]);

        // dd($response1->body());

        DB::beginTransaction();
        try{
            // set max time and memory
            ini_set('max_execution_time', 300); // 300 seconds = 5 minutes
            ini_set('memory_limit', '256M'); // 256 megabytes

            // check the result
            $answer = DB::table('answers')->where('question_id', $id)->first();
            $queryAnswer = $answer->code_answer;
            $maxTime = $answer->max_time;
            $maxMemory = $answer->max_memory;
            $resultAnswers = DB::select(DB::raw($queryAnswer));

            // validation - cannot update,delete,alter,create table
            // $query = strtoupper($query);
            // $keywords = ['UPDATE', 'DELETE', 'ALTER', 'CREATE TABLE', 'DROP', 'TRUNCATE', 'BEGIN', 'START', 'TRANSACTION', 'ROLLBACK'];
            $keywords = [
                'UPDATE', 'DELETE', 'ALTER', 'CREATE TABLE', 'DROP', 'TRUNCATE',
                'BEGIN', 'START', 'TRANSACTION', 'ROLLBACK', 'GRANT', 'REVOKE',
                'EXECUTE', 'INSERT', 'MERGE', 'LOCK', 'UNLOCK', 'SHUTDOWN',
                'COMMIT', 'SAVEPOINT', 'RELEASE SAVEPOINT', 'RENAME', 'SET',
                'ANALYZE', 'ATTACH', 'DETACH', 'DISABLE', 'ENABLE', 'RESET',
                'DISCARD', 'REINDEX', 'CLUSTER', 'VACUUM', 'EXPLAIN', 'LOAD',
                'LISTEN', 'NOTIFY', 'UNLISTEN', 'PREPARE', 'DEALLOCATE', 'COPY',
                'CROSS', 'CUBE', 'ROLLUP', 'UNION', 'INTERSECT', 'EXCEPT',
                'RETURN', 'RETURNING', 'GRANT', 'REVOKE', 'REPAIR', 'OPTIMIZE',
                'CALL', 'HANDLER', 'DO', 'DEALLOCATE PREPARE', 'PURGE', 'PURGE BINARY LOGS',
                'KILL', 'SHOW', 'DESCRIBE', 'DESC', 'SET PASSWORD', 'RESET MASTER',
                'RESET SLAVE', 'START SLAVE', 'STOP SLAVE', 'STOP REPLICA',
                'START REPLICA', 'WITH', 'CASCADE', 'RESTRICT', 'ADD', 'CHANGE',
                'REPLACE', 'INTO'
            ];
            foreach ($keywords as $keyword) {
                if (preg_match("/\b$keyword\b/i", $query)) {
                    // $response['message'] = 'Query cannot contain UPDATE, DELETE, ALTER, CREATE TABLE, DROP TABLE';
                    $response['message'] = 'Query cannot contain a restricted query';
                    $isFalse = true;
                }
            }
            if(!$isFalse){

                // count time and memory usage for the query
                $start_time = microtime(true);
                $start_memory = memory_get_usage();

                // replace ' to "
                $query = str_replace('\'', '"', $query);
                $results = DB::select(DB::raw($query));

                // Calculate the time and memory usage
                $time = microtime(true) - $start_time;
                $memory = memory_get_usage() - $start_memory;

                // Convert both results to arrays for comparison
                $resultsArray = json_decode(json_encode($results), true);
                $resultAnswersArray = json_decode(json_encode($resultAnswers), true);
                // Get the column order from the answer array
                $columnOrder = count($resultsArray) != 0 ? array_keys($resultsArray[0]) : [];
                $columnOrderAnswer = count($resultAnswersArray) != 0 ? array_keys($resultAnswersArray[0]) : [];
                $countColumnOrder = count($columnOrder);
                $countColumnOrderAnswer = count($columnOrderAnswer);
                // dd($countColumnOrder, $countColumnOrderAnswer);

                $resultsArray = $this->keyToIndex($resultsArray, $countColumnOrder);
                $resultAnswersArray = $this->keyToIndex($resultAnswersArray, $countColumnOrderAnswer);

                // sorting DEFAULT / CUSTOM
                // Sort arrays to ensure order is the same for comparison
                // $sort = $question->sort;
                $sort = false;
                $compareResult = $this->compareValue($resultsArray, $resultAnswersArray, $sort);
                // Compare the results
                if($compareResult == false){
                    $response['message'] = 'The result is different! Please check your query again.';
                    $isFalse = true;
                }
    
                // check time execution and memory
                if($time > $maxTime){
                    $response['message'] = 'Time limit!';
                    $isFalse = true;
                }
    
                if($memory > $maxMemory){
                    $response['message'] = 'Memory limit! ' . $memory . '|' . $maxMemory;
                    return response()->json($response);
                }
    
                // else success
                if(!$isFalse){
                    $response['success'] = true;
                    $response['message'] = 'Success!';
                }
            }
            DB::rollback();
        } catch(\Exception $e){
            $response['message'] = $e->getMessage();
            if(str_contains($e->getMessage(), 'Syntax error')){
                $response['message'] = 'Syntax error, please check your code again!';
            }
            DB::rollback();
        }

        // save to db for the user answer
        $newUserAnswer = new UserAnswer();
        $newUserAnswer->user_id = Auth::user()->id;
        $newUserAnswer->question_id = $id;
        $newUserAnswer->competition_batch_id = NULL;
        $newUserAnswer->user_answer = $query;
        $newUserAnswer->is_true = $response['success'];

        DB::beginTransaction();
        try{
            $newUserAnswer->save();
            DB::commit();
        } catch(\Exception $e){
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            DB::rollback();
        }

        return response()->json($response);
    }


    public function makeCompetition(Request $request){

        $response = [
            'success' => false,
            'message' => 'UNKOWN ERROR',
        ];

        try{
            // create new Competition
            // add user for the competition to user_competition

        } catch(\Exception $e) {

        }

    }
    
    public function report(){
        // bisa dari competition ataupun jawaban user
        // per user
        $questions = Question::leftJoin('user_answers', 'user_answers.question_id', '=', 'questions.id')
        ->where('user_answers.user_id', '=', Auth::user()->id)
        ->select(
            'questions.id',
            'questions.title',
            DB::raw('MAX(user_answers.is_true) as is_correct'), // true if the user has a correct answer, false otherwise
            // DB::raw('COUNT(user_answers.id) as attempts') // the number of attempts the user has made
        )
        ->groupBy('questions.title','questions.id')
        ->get();
    

    dd($questions->toArray());
    }



}