<?php

namespace App\Constants;

class Messages
{

    const ExceptionMessage = "An exception occured!";
    const InvalidCredentials = 'Invalid credentials';
    const NotAuthorized = "You are not authorized.";
    const NoPermission = 'You donot have the permission to access this route';
    const InvalidType = "Invalid action type provided.";
    const InvalidCode = 'Invalid code please try again';
    const UserNotAuthenticated = 'User not authenticated';
    const UserRegistered = "User, Employee registered successfully";
    const UserLoggedOut = 'User successfully logged out';
    const UserNotFound = 'User/Employee not found!';
    const UserLoggedIn = "2FA verified successfully, user logged in";
    const UserDeleted = "User and employee deleted successfully";
    const UserUpdated = "User updated successfully.";
    const PasswordLinkSend = 'Password Link sent';
    const PasswordSetSuccess = 'Password reset,setup successfull';

    const EmployeesFetched = "Employees fetched successfully";
    const EmployeeUpdated = "Employee updated successfully";
    const EmployeesCount = "Employee role counts fetched successfully.";

    const CompanyCreated = "Company created successfully,";
    const getCompany = "All companies retrieved successfully";

    const BranchCreated = 'Branch created successfully';

    const getBranch = 'Branch fetched successfully';
    const getBranches = 'Branches fetched successfully';
}
