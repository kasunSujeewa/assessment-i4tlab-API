<?php
namespace App\Constants;


class Constant
{
    public const NOT_FOUND_MESSAGE                  = "Resource Not Found";
    public const INVALID_LOGINS                     = "Invalid credentials";
    public const FORBIDDEN                          = "Forbidden. You do not have the required permissions to access this resource";
    public const UNAUTHORIZE                        =  "Unauthorized";
    public const VALIDATION_ERROR                   =  "Validation errors";
    public const DB_ERROR                           = "Please Check Your Database Connection";

    public const REGISTERED_SUCCESS_MESSAGE         = "User Registered Successfully";
    public const LOGGED_SUCCESS_MESSAGE             = "User Login Successfull";
    public const LOGOUT_SUCCESS_MESSAGE             = "User Logout Successfull";

    public const TASKS_RECEIVED_SUCCESS_MESSAGE     = "Tasks Received Successfully";
    public const TASK_RECEIVED_SUCCESS_MESSAGE      = "Task Received Successfully";
    public const TASK_CREATED_SUCCESS_MESSAGE       = "Tasks Created Successfully";
    public const TASK_UPDATED_SUCCESS_MESSAGE       = "Task Updated Successfully";
    public const TASK_DELETED_SUCCESS_MESSAGE       = "Task Deleted Successfully";

    public const USER_UPDATED_SUCCESS_MESSAGE       = "User Updated Successfully";
    public const USER_CREATED_SUCCESS_MESSAGE       = "User Created Successfully";
    public const USER_DELETED_SUCCESS_MESSAGE       = "User Deleted Successfully";
    public const USER_SELF_DELETING_ERROR_MESSAGE   = "User Cannot Delete Own User Data";
    public const USERS_RECEIVED_SUCCESS_MESSAGE     = "Users Received Successfully";
    public const USER_RECEIVED_SUCCESS_MESSAGE      = "User Received Successfully";
    
}