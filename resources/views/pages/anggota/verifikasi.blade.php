<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verifikasi</title>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <style>
        body {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .mail-seccess {
            text-align: center;
            background: #fff;
            border-top: 1px solid #eee;
            padding: 20px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .mail-seccess .success-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .mail-seccess .success-inner h1 {
            font-size: 80px;
            color: #006DFE;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .mail-seccess .success-inner h1 i {
            font-size: 120px;
            color: #006DFE;
            margin-bottom: 20px;
        }

        .mail-seccess .success-inner h1 span {
            display: block;
            font-size: 25px;
            color: #333;
            font-weight: 600;
            margin-top: 10px;
        }

        .mail-seccess .success-inner p {
            padding: 20px 15px;
            font-size: 18px;
            color: #555;
        }

        .mail-seccess .success-inner .btn {
            color: #fff;
            background-color: #006DFE;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }

        .mail-seccess .success-inner .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <section class="mail-seccess section">
        <div class="success-inner">
            <h1><i class="fa fa-envelope"></i><span>Terima kasih telah mendaftar!</span></h1>
            <p>Silahkan check email Anda berkala untuk mendapatkan akun anggota</p>
            <div class="d-flex">
                <a href="/page" class="btn btn-primary btn-lg">Go Home</a>
            </div>
            <!-- <a href="/landingpage" class="btn btn-primary btn-lg">Go Home</a> -->
        </div>
    </section>
</body>

</html>