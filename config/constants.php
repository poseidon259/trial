<?php
// HTTP Status code
define('HTTP_SUCCESS', 200);
define('HTTP_CREATED', 201);
define('HTTP_ACCEPTED', 202);
define('HTTP_NO_CONTENT', 204);
define('HTTP_RESET_CONTENT', 205);
define('HTTP_BAD_REQUEST', 400);
define('HTTP_UNAUTHORIZED', 401);
define('HTTP_PAYMENT_REQUIRED', 402);
define('HTTP_FORBIDDEN', 403);
define('HTTP_NOT_FOUND', 404);
define('HTTP_METHOD_NOT_ALLOWED', 405);
define('HTTP_NOT_ACCEPTABLE', 406);
define('HTTP_REQUEST_TIMEOUT', 408);
define('HTTP_INTERNAL_SERVER_ERROR', 500);
define('HTTP_NOT_IMPLEMENTED', 501);
define('HTTP_BAD_GATEWAY', 502);
define('HTTP_SERVICE_UNAVAILABLE', 503);
define('HTTP_GATEWAY_TIMEOUT', 504);
define('HTTP_VERSION_NOT_SUPPORTED', 505);

define('LIMIT', 10);
define('PAGE', 1);

// Auth
// Type login
define('LOGIN_MAIL', 1);
define('LOGIN_USER_NAME', 2);
define('LOGIN_PHONE_NUMBER', 3);

// user
define('INACTIVE', 2);
define('ACTIVE', 1);

// Role
define('ROLE_ADMIN', 1);
define('ROLE_STORE', 2);
define('ROLE_EMP', 3);
define('ROLE_USER', 4);

// user profile
define('MALE', 1);
define('FEMALE', 2);

// store status
define('STORE_INACTIVE', 0);
define('STORE_ACTIVE', 1);
define('STORE_PENDING', 2);

define('MAX_UPLOAD_FILE_SIZE', 102400);

// user address
define('ADDRESS_DEFAULT', 1);
define('ADDRESS_NOT_DEFAULT', 0);
define('LIMIT_ADDRESS', 5);

// product
define('PRODUCT_INACTIVE', 2);
define('PRODUCT_ACTIVE', 1);

define('STATUS_ACTIVE', 1);
define('STATUS_INACTIVE', 0);

// product image
define('IS_DELETE', 1);
define('IS_ADD', 2);
define('IS_UPDATE', 3);


// product favorite
define('PRODUCT_FAVORITE_INACTIVE', 0);
define('PRODUCT_FAVORITE_ACTIVE', 1);

//banner
define('BANNER_INACTIVE', 0);
define('BANNER_ACTIVE', 1);
define('BANNER_LIMIT', 5);

// comment
define('AVATAR_DEFAULT', 'https://ik.imagekit.io/4rdoffaxp/avt-default.jpg');
define('COMMENT_INACTIVE', 0);
define('COMMENT_ACTIVE', 1);

//slug role
define('SLUG_PERMISSION_ADMIN', 'admin');
define('SLUG_PERMISSION_STORE', 'store');
define('SLUG_PERMISSION_EMP', 'employee');
define('SLUG_PERMISSION_USER', 'user');

//order
define('ORDER_NEW', 1);
define('ORDER_PAID', 2);
define('ORDER_SHIPPING', 3);
define('ORDER_DELIVERED', 4);
define('ORDER_CANCELLED', 5);

define('DELIVERY_FEE', 30000);

define('VNPAY_CODE_LENGTH', 12);
