<!DOCTYPE html>
<html>

<head>
    <title>Welcome to Our Website</title>
</head>

<body>
    <h1>Hello, {{ $user->name }}!</h1>
    <p>Thank you for registering with us on {{ date("d/m/Y H:i:s") }}.</p>
</body>

</html>