<?php
use Listing\Listing;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../libs/vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
    ]
]);

$app->get('/listings', function (Request $request, Response $response) {
    require 'classes/listing.php';
    $listings = new Listing();
    return $response->withJson($listings->retrieveLatestListings());
});

$app->get('/categories', function (Request $request, Response $response) {
    require 'classes/listing.php';
    $listings = new Listing();
    return $response->withJson($listings->getAvailableCategories());
});

$app->get('/listings/{userId}', function (Request $request, Response $response, $args) {
    require 'classes/listing.php';

    $userId = (int)$args['userId'];
    $listings = new Listing();
    return $response->withJson($listings->retrieveUserLatestListings($userId));
});

$app->post('/listings', function (Request $request, Response $response) {
    require 'classes/listing.php';
    $params = $request->getParsedBody();

    $check = verifyRequiredParams($params, ['userId', 'title', 'description', 'categoryId','price','currency', 'contactName',
    'contactEmail', 'cityArea', 'country', 'region', 'address']);
    if ($check['error'] == true) {
        return $response->withJson($check, 400);
    }
    $listings = new Listing($params);
    return $response->withJson($listings->postNewListing());
});


$app->get('/user/{userId}', function (Request $request, Response $response, $args) {
    require 'classes/user.php';

    $userId = (int)$args['userId'];
    $user = new \User\User();
    return $response->withJson($user->retrieveUserDetails($userId));
});

$app->post('/user', function (Request $request, Response $response) {
    require 'classes/user.php';

    $params = $request->getParsedBody();

    $check = verifyRequiredParams($params, ['fullName', 'email', 'phoneNumber', 'profilePicture','facebookId']);
    if ($check['error'] == true) {
        return $response->withJson($check, 400);
    }


    $fullName = $params['fullName'];
    $email = $params['email'];
    $phone = $params['phoneNumber'];
    $profilePicture = $params['profilePicture'];
    $facebookId = $params['facebookId'];

    $user = new \User\User();
    $ouput = $user->createNewUser($fullName, $email, $phone, $profilePicture, $facebookId);
    return $response->withJson($ouput, $ouput['statusCode']);
});

$app->post('/user/update', function (Request $request, Response $response, $args) {
    require 'classes/user.php';

    $params = $request->getParsedBody();

    $check = verifyRequiredParams($params, ['userId', 'fullName', 'email', 'phoneNumber', 'dayOfBirth',
        'address', 'gender']);
    if ($check['error'] == true) {
        return $response->withJson($check, 400);
    }

    $userId = (int)$params['userId'];
    $fullName = $params['fullName'];
    $email = $params['email'];
    $phone = $params['phoneNumber'];
    $profilePicture = $params['profilePicture'];
    $dayOfBirth = $params['dayOfBirth'];
    $address = $params['address'];
    $gender = (int)$params['gender'];

    $user = new \User\User();
    return $response->withJson($user->updateUserInformation($userId, $fullName, $email, $phone, $profilePicture,
        $dayOfBirth, $address, $gender));
});

$app->post('/user/login/phone', function (Request $request, Response $response, $args) {
    require 'classes/user.php';

    $params = $request->getParsedBody();

    $check = verifyRequiredParams($params, ['phoneNumber']);
    if ($check['error'] == true) {
        return $response->withJson($check, 400);
    }
    $phone = $params['phoneNumber'];

    $user = new \User\User();
    $output = $user->getUserByPhoneNumber($phone);

    if($output['statusCode'] == '404')
    {
        return $response->withJson($output, 404);
    }

    return $response->withJson($output);
});

$app->post('/user/login/email', function (Request $request, Response $response, $args) {
    require 'classes/user.php';

    $params = $request->getParsedBody();

    $check = verifyRequiredParams($params, ['email']);
    if ($check['error'] == true) {
        return $response->withJson($check, 400);
    }
    $email = $params['email'];

    $user = new \User\User();
    $output = $user->getUserByEmail($email);

    if($output['statusCode'] == '404')
    {
        return $response->withJson($output, $output['statusCode']);
    }

    return $response->withJson($output);

});


$app->post('/user/login/facebook', function (Request $request, Response $response, $args) {
    require 'classes/user.php';

    $params = $request->getParsedBody();

    $check = verifyRequiredParams($params, ['facebookId']);
    if ($check['error'] == true) {
        return $response->withJson($check, 400);
    }
    $facebookId = $params['facebookId'];

    $user = new \User\User();
    $output = $user->getUserByFacebookId($facebookId);

    if($output['statusCode'] == '404')
    {
        return $response->withJson($output, $output['statusCode']);
    }

    return $response->withJson($output);

});


/**
 * Verifying required params posted or not
 */
/**
 * Verifying required params posted or not
 */
function verifyRequiredParams($request_params, $required_fields)
{
    $error = false;
    $error_fields = "";

    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        return $response;
    }
}


$app->run();