<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SakuSiswa.</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <style>
        html, body {
            height: 100%;
        }
        body {
            background-color: #ececec;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="p-4 bg-white shadow" style="width:420px; max-width:92%; border-radius: 15px;">
        <h1 class="text-center fw-bold">Login Page</h1>
        <form action="/SistemPoin/process/login_process.php" method="POST" class="w-100 mt-4">
            <input type="text" name="username" placeholder="Username" required class="form-control mb-4">
            <input type="password" name="password" placeholder="Password" autocomplete="off" required class="form-control mb-4">
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</body>
</html>