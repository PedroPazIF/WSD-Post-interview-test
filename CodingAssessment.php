<?php
function calculateTotalTime($queue, $numberTaps, $walkingTime, $flow) {
    $queue = explode(';', $queue);
    $queueSize = count($queue);

    $numberTaps = min($numberTaps, $queueSize);
    $time = 0;
    // $batches = array_chunk($queue, $numberTaps);
    $tapTimes = array_fill(0, $numberTaps, 0);
    // $tapIndex = 0;

    for($i = 0; $i < $queueSize; $i++){
        $tapIndex = $i % $numberTaps;
        $flowIndex = $tapIndex % count($flow);
        $personTime = $queue[$i] / $flow[$flowIndex];
        $tapTimes[$tapIndex] += $personTime;
        $time = max($time, $tapTimes[$tapIndex]);
        
    //  foreach ($batches as $batch){
    //     $maxBatchTime = 0;
        // foreach ($batch as $index => $bottle) {
        //}
    }
    $time += $walkingTime * $numberTaps;
    return $time;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Processar o formulário quando for enviado
    $queueInput = $_POST['queue'];
    $numberTaps = $_POST['numberTaps'];
    $flow = [100, 100, 100];

    // Chamar a função calcularTempoTotal
    $totalTime = calculateTotalTime($queueInput, $numberTaps, 5, $flow);
    echo "<b>Total time required:</b> ". number_format($totalTime, 1, ',', '')." seconds";

    echo "<br><br><b>Time required for each person to fill their bottle according to its size:</b> <br>";
    $queue = explode(';', $queueInput);

    for ($i = 0; $i < count($queue); $i++) {
        $tapIndex = $i % $numberTaps;
        $flowIndex = $tapIndex % count($flow);
        $flowRate = $flow[$flowIndex];
        $personTime = $queue[$i] / $flowRate;
        echo "<b>Person " . ($i + 1) . " take :</b>". number_format($personTime, 1, ',', '')." seconds<br>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>WSD</title>
</head>
<body>

    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="queue"> Enter the queue of people by inputting the amount of water in mililitres in their bottle (separate the values by semicolon): </label><br>
        <input type="text" id="queue" name="queue" placeholder="Ex: 400;750;1000" oninput="this.value = this.value.replace(/[^0-9;]/g, '');"  required><br><br>

        <label for="numberTaps">Number of taps:</label><br>
        <input type="number" id="numberTaps" name="numberTaps" required min="1">

        <input type="submit" value="Calculate">
    </form>
</body>
</html>

<style>
    form{
        margin-top: 100px;
    }
</style>