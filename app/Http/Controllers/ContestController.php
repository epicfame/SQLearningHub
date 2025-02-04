<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Menu;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Competition;
use App\Models\UserAnswer;
use DB;
use Auth;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class ContestController extends Controller
{
    public function index(){
        $user = Auth::user();
        $contests = Competition::where('contestant_id', 'LIKE', '%' . $user->id . '%')->paginate(10);
        return view('contest.index',['contests' => $contests, 'menu' => 'contest', 'user'=>$user]);
    }

    public function getContest($contestId){
        $user = Auth::user();
        $contest = Competition::find($contestId);
        $questionId = explode(',', $contest->question_id);
        $questions = Question::whereIn('id', $questionId)->paginate(10);
        $userAnswer = UserAnswer::where('user_id', $user->id)
                                ->where('is_true', true)
                                ->where('competition_batch_id', '!=', NULL)
                                ->get()
                                ->keyBy('question_id');
        return view('contest.contest_list', 
        [
            'menu' => 'contest',
            'contest' => $contest,
            'questions' => $questions,
            'answers' => $userAnswer,
            'user'=> $user,
        ]);
    }

    public function getContestQuestion($contestId, $questionId){
        $user = Auth::user();
        $contest = Competition::find($contestId);
        $question = Question::where('id', $questionId)->first();
        $userAnswer = UserAnswer::where('user_id', $user->id)
                                ->where('is_true', true)
                                ->where('competition_batch_id', '!=', NULL)
                                ->get()
                                ->keyBy('question_id');

        $userAnswer1 = UserAnswer::where('user_id', $user->id)
                                ->where('competition_batch_id', $contest->id)
                                ->orderBy('id', 'DESC')
                                ->first();
        $answer1 = '';
        if($userAnswer1){
            $answer1 = $userAnswer1->user_answer;
        }

        $carbonStartDate = Carbon::createFromFormat('Y-m-d H:i:s', $contest->start_date);
        $carbonEndDate = Carbon::createFromFormat('Y-m-d H:i:s', $contest->end_date);
        return view('contest.contest_question', 
        [
            'menu' => 'contest',
            'contest' => $contest,
            'startDate' => $carbonStartDate,
            'endDate' => $carbonEndDate,
            'question' => $question,
            'answers' => $userAnswer,
            'user'=> $user,
            'answer1' => $answer1,
        ]);
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


    public function compile(Request $request, $contestId, $questionId){
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
        DB::beginTransaction();
        try{
            // set max time and memory
            ini_set('max_execution_time', 300); // 300 seconds = 5 minutes
            ini_set('memory_limit', '256M'); // 256 megabytes

            // check the result
            $answer = DB::table('answers')->where('question_id', $questionId)->first();
            $queryAnswer = $answer->code_answer;
            $maxTime = $answer->max_time;
            $maxMemory = $answer->max_memory;
            $resultAnswers = DB::select(DB::raw($queryAnswer));

            // validation - cannot update,delete,alter,create table
            // $query = strtoupper($query);
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
                    $response['message'] = 'Query cannot contain a restricted query';
                    // $response['message'] = 'Query cannot contain UPDATE, DELETE, ALTER, CREATE TABLE';
                    $isFalse = true;
                }
            }

            $contest = Competition::find($contestId);
            if($contest){
                // if the contest is already ended
                if(Carbon::now() < $contest->start_date){
                    $response['message'] = "The contest hasn't started yet!";
                    $isFalse = true;
                }
                else if(Carbon::now() > $contest->end_date){
                    $response['message'] = "The contest has ended!";
                    $isFalse = true;
                }
            }
            else{
                $response['message'] = 'No contest found!';
                $isFalse = true;
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

                $resultsArray = $this->keyToIndex($resultsArray, $countColumnOrder);
                $resultAnswersArray = $this->keyToIndex($resultAnswersArray, $countColumnOrder);

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
        } catch(\Exception $e){
            $response['message'] = $e->getMessage();
            if(str_contains($e->getMessage(), 'Syntax error')){
                $response['message'] = 'Syntax error, please check your code again!';
            }
        }
        DB::rollback();

        // save to db for the user answer
        $newUserAnswer = new UserAnswer();
        $newUserAnswer->user_id = Auth::user()->id;
        $newUserAnswer->question_id = $questionId;
        $newUserAnswer->competition_batch_id = $contestId;
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

}
