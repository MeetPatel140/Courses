<?php
require_once '../01-User-Authentication/auth.php';

// Initialize Authentication class
// Example output: New Authentication instance created
$auth = new Authentication();

// Verify AJAX request
// Example: Check if request is AJAX and has valid CSRF token
function isValidAjaxRequest() {
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
              strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    $hasValidToken = isset($_POST['csrf_token']) && 
                     $_POST['csrf_token'] === $_SESSION['csrf_token'];
    return $isAjax && $hasValidToken;
}

// Send JSON response
// Example input: sendJsonResponse(['status' => 'success', 'data' => ['id' => 1]])
// Example output: {"status":"success","data":{"id":1}}
function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Handle AJAX request
// Example input: POST request with action='get_data'
// Example output: JSON response with requested data
try {
    // Verify request validity
    if (!isValidAjaxRequest()) {
        throw new Exception('Invalid request');
    }
    
    // Check authentication if required
    if (!$auth->isLoggedIn()) {
        sendJsonResponse(
            ['status' => 'error', 'message' => 'Authentication required'],
            401
        );
    }
    
    // Get request action
    // Example: action = 'get_data'
    $action = $_POST['action'] ?? '';
    
    // Process different actions
    switch ($action) {
        case 'get_data':
            // Example: Fetch and return data
            $data = [
                'id' => 1,
                'name' => 'Example Data',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            sendJsonResponse(['status' => 'success', 'data' => $data]);
            break;
            
        case 'save_data':
            // Example: Validate and save data
            $input = filter_input_array(INPUT_POST, [
                'name' => FILTER_SANITIZE_STRING,
                'value' => FILTER_SANITIZE_STRING
            ]);
            
            if (!$input['name'] || !$input['value']) {
                throw new Exception('Invalid input data');
            }
            
            // Process save operation here
            sendJsonResponse(['status' => 'success', 'message' => 'Data saved']);
            break;
            
        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    // Log error and send error response
    error_log("AJAX Error: " . $e->getMessage());
    sendJsonResponse(
        ['status' => 'error', 'message' => $e->getMessage()],
        400
    );
}