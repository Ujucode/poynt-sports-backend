<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link rel="stylesheet" href="{{ asset('app.css') }}">
</head>
<body>
    <h1 class="text-xl text-blue-600 mb-4">Hello <span>{{$user->first_name}}</span>, Welcome to Poynt-Sports</h1>
    <p class="mb-4">
        We have received a request from email: {{$user->email}} for creating account on Poynt Sports.
        So, we suggest you to verify your email through the OTP given below for activate your account.
    </p>

    <h2>OTP: <code>{{$userforEmailVerify->otp}}</code> <button type="button" class="px-2 ml-3 transform rounded shadow text-white bg-stone-500 active:scale-95 active:bg-stone-600 transition duration-100 active:text-emerald-400">copy</button></h2>

    <footer class="flex justify-center h-24 w-full bg-neutral-600 items-center mt-10">
        <div class="text-white">
            &copy;2023 Poynt Sports
        </div>
    </footer>
    <script>
        const button = document.body.querySelector('button');
        const code = document.body.querySelector('code');

        button.addEventListener('click', () => {
            navigator.clipboard.writeText(code.innerHTML);
        });
    </script>
</body>
</html>