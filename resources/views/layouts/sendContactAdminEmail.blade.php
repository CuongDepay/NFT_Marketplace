<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submission</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h2 class="text-center">You have received a new contact submission</h2>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $data['name'] }}</p>
                <p><strong>Email:</strong> {{ $data['email'] }}</p>
                <p><strong>Phone Number:</strong> {{ $data['phone_number'] }}</p>
                <p><strong>Contact Category Name:</strong> {{ $data['contact_category_name'] }}</p>
                <p><strong>Title:</strong> {{ $data['title'] }}</p>
                <div class="message">
                    <strong>Message:</strong>
                    <p>{{ $data['message'] }}</p>
                </div>
            </div>
        </div>
        Thanks,<br>
    {{ config('app.name') }}
    </div>
</body>
</html>
