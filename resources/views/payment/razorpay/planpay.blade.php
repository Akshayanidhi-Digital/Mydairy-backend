<!-- resources/views/checkout.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razorpay Checkout</title>
    <!-- Include Razorpay JavaScript SDK -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>


<body>
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            startRazorpayPayment();
        });

        function startRazorpayPayment() {
            var options = {
                key: '{{ $data['razorpay_key'] }}',
                amount: '{{ $data['amount'] }}',
                currency: '{{ $data['currency'] }}',
                name: 'Your Company Name',
                description: 'Item Purchase',
                order_id: '{{ $data['order_id'] }}',
                handler: function(response) {
                    window.location.href = '{{ route('user.razorpay.status', ['id' => 'PAYID']) }}'.replace('PAYID',
                        response.razorpay_order_id);
                },
                theme: {
                    color: '#528FF0',
                },
            };

            var rzp = new Razorpay(options);
            rzp.open();
        }
    </script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            startPayment();
        });

        function startPayment() {
            var options = {
                key: "{{ $data['razorpay_key'] }}",
                amount: '{{ $data['amount'] }}',
                currency: "{{ $data['currency'] }}",
                name: "Your Company Name",
                description: "Test transaction",
                image: "https://akshayanidhi.digital/assets/img/logo.png",
                order_id: "{{ $data['order_id'] }}",
                prefill: {
                    name: "{{ auth()->user()->name }}",
                    email: "{{ auth()->user()->email }}",
                    contact: "{{ auth()->user()->country_code . auth()->user()->mobile }}"
                },
                notes: {
                    address: "Razorpay Corporate Office"
                },
                theme: {
                    "color": "#3399cc"
                },
                handler: function(response) {
                    window.location.href = '{{ route('user.plans.pay.status', ['id' => 'PAYID']) }}'.replace(
                        'PAYID',
                        response.razorpay_order_id);
                },
            };
            var rzp = new Razorpay(options);
            rzp.open();
        }
    </script>
</body>

</html>
