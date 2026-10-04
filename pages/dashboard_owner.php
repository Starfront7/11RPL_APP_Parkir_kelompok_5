<!doctype html>
<html lang="en">

<head>
    <title>Bootstrap 4 Website Example</title>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/admin.css">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
        integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous" />

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

    <div class="container-fluid">
        <div class="row">

            <nav class="col-md-3 col-lg-2 d-md-block bg-primary sidebar p-3 min-vh-100">
                <h4 class="text-white font-weight-bold mb-0" style="font-size: 20px; line-height: 1.2">
                    Parkir Ku
                </h4>

                <hr style="border: 1px solid rgba(255, 255, 255, 0.3); margin: 12px 0" />

                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a href="dashboard_owner.php"
                            class="btn btn-primary btn-block text-left text-white d-flex align-items-center py-2 px-3">
                            <i class="fa fa-home mr-2" style="width: 20px; text-align: center"></i>
                            <span style="font-size: 13px">Dashboard</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="rekap_owner.php"
                            class="btn btn-primary btn-block text-left text-white d-flex align-items-center py-2 px-3">
                            <i class="fa fa-users mr-2" style="width: 20px; text-align: center"></i>
                            <span style="font-size: 13px">Rekap</span>
                        </a>
                    </li>
                    <li>
                        <a href="login.php"
                            class="btn btn-primary btn-block text-left text-white d-flex align-items-center py-2 px-3">
                            <i class="fas fa-right-from-bracket icon mr-2" style="width: 20px; text-align: center"></i>
                            <span style="font-size: 13px">logout</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- KONTEN UTAMA DI SEBELAH KANAN -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 py-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard Utama</h1>
                </div>
                <div class="card">
                    <div class="card-body">
                        <p class="mb-0">Konten utama Anda akan tampil di sini...</p>
                    </div>
                </div>
            </main>

        </div>
    </div>

</body>

</html>