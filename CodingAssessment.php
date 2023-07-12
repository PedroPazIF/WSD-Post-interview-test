<?php
function calculateTotalTime($queue, $numberTaps, $walkingTime /*Bonus 2*/, $flow /*Bonus 3 */) {
    $queue = explode(';', $queue);
    $queueSize = count($queue);

    $numberTaps = min($numberTaps, $queueSize);
    $time = 0;
    $tapTimes = array_fill(0, $numberTaps, 0);

    for($i = 0; $i < $queueSize; $i++){
        $tapIndex = $i % $numberTaps;
        $flowIndex = $tapIndex % count($flow);
        $personTime = $queue[$i] / $flow[$flowIndex];
        $tapTimes[$tapIndex] += $personTime;
        $time = max($time, $tapTimes[$tapIndex]);
        
    }
    $time += $walkingTime * $queueSize;
    return $time;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $queueInput = $_POST['queue'];
    $numberTaps = $_POST['numberTaps'];
    $flow = [50,200];

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


<!-- 
Bonus 4) Faster taps, slower time

Considering that the defined flow is $flow = [50, 200], if I test the queue of 500ml; 2000ml with two taps, the result would be:

Time required for each person to fill their bottle according to its size:
Person 1 takes: 10.0 seconds
Person 2 takes: 10.0 seconds

Taking into account an additional 10 seconds (5 seconds each) for walkingTime,

Total time required: 20.0 seconds

Now, if I increase the flow to $flow = [100, 200] and test it again with the same queue of 500ml; 2000ml
and two taps, the result would be different:

Total time required: 20.0 seconds

Time required for each person to fill their bottle according to its size:
Person 1 takes: 5.0 seconds
Person 2 takes: 10.0 seconds

Note that the total remained the same because Person 2 still took 10 seconds + 10 seconds (5 seconds each) of walkingTime = 20 seconds.
However, Person 1, with the increased flow of tap 1, reduced the time to 5 seconds.

Here's another example to make it clear:

Now, I will increase the flow of the second tap too, $flow = [100, 400], and test it again with the same queue of 500ml; 2000ml and two taps.
This would be the result:

Total time required: 15.0 seconds

Time required for each person to fill their bottle according to its size:
Person 1 takes: 5.0 seconds
Person 2 takes: 5.0 seconds

Note that the total time has changed because the time taken by both is 5 seconds + 10 seconds (5 seconds each) of walkingTime = 15 seconds.
Also, note that now, in addition to Person 1's time decreasing due to the flow increase,
increasing the flow of tap 2 also reduced the time taken by Person 2 from the default 10 seconds to 5 seconds by doubling the flow.
 -->



<!DOCTYPE html>
<html>
<head>
    <title>WSD</title>
</head>
<body>

    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <!-- Bonus 1 below -->
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