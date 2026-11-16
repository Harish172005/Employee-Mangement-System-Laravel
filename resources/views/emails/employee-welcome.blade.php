<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
</head>
<body>

    <h2>Welcome, {{ $employee->first_name }}!</h2>

    <p>
        Welcome to the Employee Management System.
        Your employee account has been created successfully.
    </p>

    <p>
        <strong>Employee Code:</strong>
        {{ $employee->employee_code }}
    </p>

    <p>
        <strong>Designation:</strong>
        {{ $employee->designation }}
    </p>

    <p>
        Thank you!
    </p>

</body>
</html>