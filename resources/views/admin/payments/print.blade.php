{{-- @extends('layouts.app')
@section('content')
@endsection --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Invoice</title>
    <style>
        /* Basic styles for the print layout */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .details {
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table th {
            background-color: #f4f4f4;
        }

        /* Print styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .container {
                padding: 10px;
                border: none;
            }

            .header h2 {
                font-size: 24px;
            }

            .details,
            .table {
                font-size: 14px;
            }

            .btn {
                display: none;
                /* Hide the print button during printing */
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h2>Payment Invoice</h2>
            <p><strong>Payment ID:</strong> {{ $data->payment_id ?? '' }}</p>
        </div>

        <div class="details">
            <p><strong>User Name:</strong> {{ $data->user->name ?? '' }}</p>
            <p><strong>Father Name:</strong> {{ $data->user->father_name ?? '' }}</p>
            <p><strong>Plan:</strong> {{ $data->plan->name ?? '' }}</p>
            <p><strong>Duration Type:</strong> {{ $data->plan->duration_type ?? '' }}</p>
            <p><strong>Amount Paid:</strong> ₹{{ number_format($data->amount, 2) }}</p>
            <p><strong>Payment Status:</strong> {!! getPaymentStatus($data->payment_status) !!}</p>
            <p><strong>Date:</strong> {{ $data->created_at->format('Y-m-d') }}</p>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $data->plan->name }}</td>
                    <td>₹{{ number_format($data->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <button onclick="window.print()" class="btn btn-primary">Print this page</button>
        </div>
    </div>

</body>

</html>
