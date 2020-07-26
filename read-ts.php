<?php

$input = $argv[1];
$output = $argv[2];
$row = 0;
if (false !== ($ih = fopen($input, 'r'))) {
    $oh = fopen($output, 'w');

    while (false !== ($data = fgetcsv($ih))) {
    	if ($row == 0) {
    		$row++;
    		continue;
    	}
        // this is where you build your new row
        $t = gettype($data[11]);
        print($t);
        $d = time_to_decimal($data[11]);
        $outputData = array($data[5], $data[7], $d);
        fputcsv($oh, $outputData);
    }

    fclose($ih);
    fclose($oh);
}

/**
 * Convert time into decimal time.
 *
 * @param string $time The time to convert
 *
 * @return integer The time as a decimal value.
 */
function time_to_decimal($time) {
	print $time . '\n';
    $timeArr = explode(':', $time);
    print_r($timeArr);
    $decTime = ($timeArr[0]*60) + ($timeArr[1]) + ($timeArr[2]/60);
    return $decTime/60;
}