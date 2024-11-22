<!DOCTYPE html>
<html>
<head>
    <title>Email from Laravel Form</title>
</head>
<body>
    <h1>Hi,</h1>
    <p>You have a new email from {{ $data['name'] }} ({{ $data['email'] }}).</p>
    <p>Message: {{ $data['message'] }}</p>
</body>
</html>
