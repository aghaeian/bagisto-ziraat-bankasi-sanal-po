<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ core()->getConfigData('sales.payment_methods.ziraat_bank.title')}}</title>
    <link rel="shortcut icon" href="{{ bagisto_asset('images/favicon.ico', 'shop') }}" type="image/x-icon">

    <style>
        body {
            font-family: sans-serif, serif;
        }

        .container {
            width: 50%;
            margin-left: 25%;
        }

        .action {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .action a {
            text-decoration: none;
        }

        .btn {
            border: none;
            color: #FFF;
            padding: 10px;
            margin: 10px;
            font-size: 14px;
            background-color: #4CAF50;
            cursor: pointer;
        }

        a {
            margin-left: 10px;
        }

        p {
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            color: #ff0000;
        }
    </style>
</head>

<body>
    <div
        class="container"
        id="dropin-container"
    >
    </div>

    <div class="action">
        <button
            class="btn"
            id="submit-button"
        >
            {{ __('ziraat_bank::app.proceed-to-payment') }}
        </button>

        <button
            class="btn"
            id="back-button"
        >
            {{ __('shop::app.checkout.onepage.address.back') }}
        </button>
    </div>

    <p>{{ __('admin::app.sales.transactions.index.datagrid.transaction-amount') }} : {{ $grand_total }}</p>
    <p>** {{ __('ziraat_bank::app.do-not-reload-page') }} **</p>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://js.ziraat_bankgateway.com/web/dropin/1.40.2/js/dropin.min.js"></script><script>
        document.addEventListener('DOMContentLoaded', function() {
            var clientToken = @json($clientToken);
            var button = document.querySelector('#submit-button');
            var backButton = document.querySelector('#back-button');
            var returnRoute = "{{ route('shop.checkout.onepage.success') }}";

            var csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');

            var csrfToken = csrfTokenMeta.getAttribute('content');

            ziraat_bank.dropin.create({
                authorization: clientToken,
                container: '#dropin-container'
            }, function(createErr, instance) {
                if (createErr) {
                    return;
                }

                button.addEventListener('click', function() {
                    if (sessionStorage.getItem('paymentStatus')) {
                        sessionStorage.removeItem('paymentStatus');
                    }

                    instance.requestPaymentMethod(function(err, payload) {
                        if (err) {
                            return;
                        }

                        button.style.cursor = 'wait';
                        backButton.style.cursor = 'wait';

                        window.removeEventListener('beforeunload', beforeUnloadHandler);

                        fetch("{{ route('ziraat_bank.payment.transaction') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                nonce: payload.nonce
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.href = returnRoute;
                            } else {
                                alert('{{ __('ziraat_bank::app.something-went-wrong')}} ⚠️'+ data.error);
                                location.href = "{{ route('shop.checkout.onepage.index') }}";
                            }
                        })
                        .catch(error => {
                            alert('{{ __('ziraat_bank::app.something-went-wrong')}} ⚠️'+ error);
                            location.href = "{{ route('shop.checkout.onepage.index') }}";
                        });
                    });
                });
            });

            backButton.addEventListener('click', function() {
                location.href = "{{ route('shop.checkout.onepage.index') }}";
            });
        });

        sessionStorage.setItem('paymentStatus', 'canceled');
    </script>

    <script>
        function beforeUnloadHandler(e) {
            e.preventDefault();
            e.returnValue = '';
        }

        window.addEventListener('beforeunload', beforeUnloadHandler);
    </script>
</body>
</html>
