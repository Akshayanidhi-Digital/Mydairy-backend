<?php


//App Info
defined('APP_NAME') or define("APP_NAME", 'My Dairy');

defined('STATUS_BAD_REQUEST') or define("STATUS_BAD_REQUEST", 400);
defined('STATUS_UNAUTHORIZED') or define("STATUS_UNAUTHORIZED", 401);
defined('TOO_MANY_REQUESTS') or define("TOO_MANY_REQUESTS", 429);
defined('STATUS_CREATED') or define("STATUS_CREATED", 201);
defined('STATUS_OK') or define("STATUS_OK", 200);
defined('STATUS_GENERAL_ERROR') or define("STATUS_GENERAL_ERROR", 500);
defined('STATUS_FORBIDDEN') or define("STATUS_FORBIDDEN", 403);
defined('STATUS_NOT_FOUND') or define("STATUS_NOT_FOUND", 404);
defined('STATUS_METHOD_NOT_ALLOWED') or define("STATUS_METHOD_NOT_ALLOWED", 405);
defined('STATUS_ALREADY_EXIST') or define("STATUS_ALREADY_EXIST", 409);
defined('UNPROCESSABLE_ENTITY') or define("UNPROCESSABLE_ENTITY", 422);
defined('STATUS_LINK_EXPIRED') or define("STATUS_LINK_EXPIRED", 410);
defined('VEHICLE_UNIT') or define("VEHICLE_UNIT", ['Litres', 'Kilograms', 'Tons']);
// defined('MILK_TYPE') or define("MILK_TYPE", ['cow' => '0', 'buffalo' => '1', 'mix' => '2', 'other' => '3']);
// defined('RATE_MILK_TYPE') or define("RATE_MILK_TYPE", ['cow' => 'Cow', 'buffalo' => 'Buffalo', 'mix' => 'Mix']);
defined('MILK_TYPE') or define("MILK_TYPE", ['Cow' => '0', 'Buffalo' => '1', 'Mix' => '2', 'Other' => '3']);
defined('MILK_TYPE_LIST') or define("MILK_TYPE_LIST", ['Cow' => 'Cow', 'Buffalo' => 'Buffalo', 'Mix' => 'Mix',]);
defined('RATE_CHART_TYPE') or define("RATE_CHART_TYPE", ['Sell' => 'sell', 'Purchase' => 'buy']);
defined('RATE_MILK_TYPE') or define("RATE_MILK_TYPE", ['Cow' => 'cow', 'Buffalo' => 'buffalo', 'Mix' => 'mix']);
defined('SHIFT_S_VALUES') or define("SHIFT_S_VALUES", ['day' => 'Day', 'morning' => 'Morning', 'evening' => 'Evening']);
return [
    'RCHART_TYPE' => [
        'Sell' => 'Sale',
        'Purchase' => 'Buy',
    ],
];
