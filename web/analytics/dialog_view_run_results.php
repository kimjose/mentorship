<?php

use Umb\Mentorship\Models\AnalyticQuestion;
use Umb\Mentorship\Models\AnalyticRun;
use Umb\Mentorship\Models\AnalyticRunLine;

require_once __DIR__ . '/../../vendor/autoload.php';
$id = $_GET['id'];
$run = AnalyticRun::find($id);
if ($run == null) return;
$lines = AnalyticRunLine::where('analytic_run_id', $id)->get();
$questions = [];
/** @var AnalyticQuestion[] */
$aqs = AnalyticQuestion::where("analytic_id", $run->analytic_id)->get();
foreach ($aqs as $aq) {
    $questions[] = $aq->question();
}
?>

<div class="container-fluid">
    <div class="card-body">
        <canvas class="chart" id="graphLineOutput" style="min-height: 280px; height: 280px; max-height: 280px; max-width: 100%;"></canvas>
    </div>
</div>
<script>

    function drawGraph() {
        let run = JSON.parse('<?php echo json_encode($run) ?>')
        let lines = JSON.parse('<?php echo $lines ?>')
        let questions = JSON.parse('<?php echo json_encode($questions) ?>')
        let graphLineOutput = $('#graphLineOutput')
        let ticksStyle = {
            fontColor: '#495057',
            fontStyle: 'bold'
        }
        let mode = 'index'
        let intersect = true
        let datasets = []
        let dates = []
        lines.forEach(line => {
            if (!dates.includes(line.visit_date))
                dates.push(line.visit_date)
        })
        console.log(dates);
        questions.forEach(question => {
            let values = []
            let filtered = lines.filter(line => {
                return line.question_id == question.id
            })
            let v = dates.map(d => {
                let me = filtered.find(line => line.visit_date == d)
                console.log(me);
                if (me === null || me === undefined) return ''
                else return me.answer
            })
            let bg = `rgb(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)})`
            let dataset = {
                label: question.question,
                type: 'line',
                data: v,
                backgroundColor: 'transparent',
                borderColor: bg,
                pointBorderColor: bg,
                pointBackgroundColor: bg,
                fill: false
                // pointHoverBackgroundColor: '#007bff',
                // pointHoverBorderColor    : '#007bff'
            }
            datasets.push(dataset)
        })
        let linesChart = new Chart(graphLineOutput, {
            data: {
                labels: dates,
                datasets: datasets
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: mode,
                    intersect: intersect
                },
                hover: {
                    mode: mode,
                    intersect: intersect
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: "Answers",
                        },
                        gridLines: {
                            display: true,
                            lineWidth: '4px',
                            color: 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks: $.extend({
                            beginAtZero: true,
                            suggestedMax: 10
                        }, ticksStyle)
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: "Dates",
                        },
                        display: true,
                        gridLines: {
                            display: false
                        },
                        ticks: ticksStyle
                    }]
                }
            }
        })
    }

    drawGraph()

</script>
