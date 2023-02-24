<?php

use Umb\Mentorship\Models\Question;
use Umb\Mentorship\Models\Response;

require_once __DIR__ . '/../../vendor/autoload.php';

$sectionId = $_GET['section_id'];
$visitId = $_GET['visit_id'];

$questions = Question::where('section_id', $sectionId)->get();

?>
<div class="container-fluid">
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <th>Question</th>
                <th>Response</th>
            </thead>
            <tbody>
                <?php foreach($questions as $question): 
                    $response = Response::where('question_id', $question->id)->where('visit_id', $visitId)->first();
                    ?>
                    <tr>
                        <td><?php echo $question->question ?></td>
                        <td>
                            <?php
                            if($question->type == 'textfield_s'){
                                echo $response->answer;
                            } elseif($question->type == 'radio_opt'){
                                $ans = $response->answer;
                                echo json_decode($question->frm_option)->$ans;
                            } elseif($question->type == 'check_opt'){
                                if($response->answer != ''){
                                    $answers = explode(',', $response->answer);
                                    $pool = '';
                                    $i = 0;
                                    foreach($answers as $answer){
                                        if($i != 0) $pool .= ', ';
                                        $pool .= json_decode($question->frm_option)->$answer;
                                        $i++;
                                    }
                                    echo $pool;
                                }
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
