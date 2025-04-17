<?php
// Test script to check execution time limits

// Display current settings
echo "PHP Version: " . phpversion() . "\n";
echo "max_execution_time: " . ini_get('max_execution_time') . "\n";
echo "memory_limit: " . ini_get('memory_limit') . "\n";

// Try to set execution time to unlimited
ini_set('max_execution_time', 0);
echo "After ini_set, max_execution_time: " . ini_get('max_execution_time') . "\n";

// Start time
$start_time = microtime(true);
echo "Starting long-running process at: " . date('Y-m-d H:i:s') . "\n";

// Simulate a long-running process
$duration = 10; // Run for 10 seconds
$end_time = $start_time + $duration;

while (microtime(true) < $end_time) {
    // Do some work to consume CPU
    for ($i = 0; $i < 1000000; $i++) {
        $x = sqrt($i);
    }

    // Report progress
    $elapsed = microtime(true) - $start_time;
    echo "Elapsed time: " . number_format($elapsed, 2) . " seconds\n";

    // Sleep a bit to avoid consuming too much CPU
    usleep(500000); // 0.5 seconds
}

// End time
$total_time = microtime(true) - $start_time;
echo "Process completed at: " . date('Y-m-d H:i:s') . "\n";
echo "Total execution time: " . number_format($total_time, 2) . " seconds\n";
?>
