<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Transaction</title>
    <style>
        * {
            transition: .3s all;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #181a1f;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 120px auto;
            background-color: #1f2128;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            border-radius: 24px;
            padding: 30px;
            color: #ddd;
        }

        h1 {
            text-align: center;
            margin: 30px auto;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 16px;
            margin-bottom: 10px;
            margin-top: 18px;
            color: #bcbcbc
        }

        input {
            width: 100%;
            padding: 18px;
            border-radius: 5px;
            background-color: #2c2f39;
            color: #eaecfd;
            margin-bottom: 20px;
            font-size: 16px;

            border: 1px solid #404040;
            color: rgb(228, 228, 228) !important;
            border-radius: 14px;
            margin-top: 4px;
        }

        .btn {
            background-color: #355dff;
            color: #fff;
            border: none;
            border-radius: 24px;
            font-size: 18px;
            cursor: pointer;
            margin-top: 24px;

            padding: 14px !important;
            border-radius: 28px;
            box-shadow: 0px 2px 21px #355dff3b;
        }

        .btn:hover {
            box-shadow: 0px 2px 21px #355dff;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Confirm Transaction</h1>

        <form action="" method="POST">
            {{ csrf_field() }}

            <label for="currency">Currency:</label>
            <input type="text" name="currency" value="{{ $data['ticker'] }}" disabled>

            <label for="amount">Amount:</label>
            <input type="text" name="amount" value="{{ $data['amount'] }}" disabled>

            <label for="transaction_id">Transaction ID:</label>
            <input type="text" name="transaction_id" value="{{ $data['unique_slug'] }}" disabled>

            <button type="submit" class="btn">Confirm Transaction</button>
        </form>
    </div>
</body>

</html>
