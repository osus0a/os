<?php
// Script to test login functionality

// Load Laravel environment
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Get the email and password from command line arguments
$email = $argv[1] ?? null;
$password = $argv[2] ?? null;

if (!$email || !$password) {
    echo "Usage: php test_login.php <email> <password>\n";
    exit(1);
}

// Create a request
$request = Illuminate\Http\Request::create('/login', 'POST', [
    'email' => $email,
    'password' => $password,
    '_token' => csrf_token(),
]);

try {
    // Start timing
    $start_time = microtime(true);

    echo "Attempting to login with email: $email\n";

    // Process the request
    $response = $kernel->handle($request);

    // Check the response
    $status = $response->getStatusCode();

    // End timing
    $end_time = microtime(true);
    $execution_time = ($end_time - $start_time) * 1000; // Convert to milliseconds

    echo "Response status code: $status\n";
    echo "Execution time: " . number_format($execution_time, 2) . " ms\n";

    if ($status == 302) {
        // Check if redirected to dashboard (successful login) or back to login page (failed login)
        $redirect = $response->headers->get('Location');
        echo "Redirect to: $redirect\n";

        if (strpos($redirect, 'dashboard') !== false) {
            echo "Login successful!\n";
        } else {
            echo "Login failed. Check credentials or look for validation errors.\n";
        }
    } else {
        echo "Unexpected response. Check the application logs for more details.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
