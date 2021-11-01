<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/'.setting('icon')) }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.0/font/bootstrap-icons.css">

    <!-- Style -->
    <style type="text/css">
        a {text-decoration: none;}
        .digital-time {text-align: center; border: 1px solid #bbbbbb; border-radius: .2rem; background-color: #fff; padding: .5rem;}
        .digital-date {font-size: 1.2rem;}
        .digital-clock {font-size: 1.8rem;}
        .card-absensi-masuk:hover, .card-absensi-keluar:hover, .card-absensi-penuh:hover {text-decoration: none;}
        .card-absensi-masuk:hover .card, .card-absensi-keluar:hover .card, .card-absensi-penuh:hover .card {background-color: #eeeeee;}
    </style>

    <title>Absensi Member | {{ setting('name') }}</title>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('member.dashboard') }}">E-Absensi</a>
            <div class="btn-group">
                <a class="btn btn-info" href="{{ route('member.attendance.detail', ['id' => null]) }}">
                    <i class="bi-list"></i> Rekap Absensi
                </a>
                <a class="btn btn-warning" href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('form-logout').submit();">
                    <i class="bi-power"></i> Log Out
                </a>
            </div>
            <form class="d-none" id="form-logout" method="post" action="{{ route('member.logout') }}">
                @csrf
            </form>
        </div>
    </nav>

    @if(Auth::user()->end_date == null)
    <div class="container mt-5">
        <!-- Welcome Text -->
        <div class="alert alert-info text-center" role="alert">
            Selamat datang <strong>{{ Auth::user()->name }}</strong> di {{ setting('name') }}. Anda login sebagai Member <strong>({{ Auth::user()->position ? Auth::user()->position->name : '-' }})</strong>.
        </div>

        <!-- Digital Clock -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="digital-time" role="alert">
                    <div class="digital-date"></div>
                    <div class="digital-clock"></div>
                    <div class="digital-timezone text-secondary">Waktu Indonesia Barat</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="text-center mb-0">Absen {{ (count($is_entry) <= 0) ? 'Masuk' : 'Keluar' }}</h5></div>
            <div class="card-body">
                @if(Session::get('message'))
                <div class="alert alert-warning alert-dismissible text-center fade show" role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if(count($is_entry) <= 0)
                    <!-- Entry -->
                    <div class="row justify-content-center">
                        @foreach($work_hours as $work_hour)
                            @if($work_hour->status == 1)
                                <a href="#" class="col-md-4 col-sm-6 mb-3 {{ attendance($work_hour->id) >= $work_hour->quota ? 'card-absensi-penuh' : 'card-absensi-masuk' }}" data-id="{{ $work_hour->id }}" data-bs-toggle="tooltip" title="Klik untuk melakukan absensi">
                                    <div class="card h-100 card-hover text-center">
                                        <div class="card-body p-3">
                                            <h5 class="card-title mb-0">{{ $work_hour->name }}</h5>
                                            <p class="card-text"><small class="text-secondary">{{ date('H:i', strtotime($work_hour->start_at)) }} - {{ date('H:i', strtotime($work_hour->end_at)) }}</small></p>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @else
                    <!-- Exit -->
                    <div class="row justify-content-center">
                        @foreach($work_hours as $work_hour)
                            <a href="#" class="col-md-4 col-sm-6 mb-3 card-absensi-keluar" data-id="{{ $work_hour->id }}" data-bs-toggle="tooltip" title="Klik untuk melakukan absensi">
                                <div class="card h-100 card-hover text-center">
                                    <div class="card-body p-3">
                                        <h5 class="card-title mb-0">{{ $work_hour->workhour->name }}</h5>
                                        <p class="card-text"><small class="text-secondary">{{ date('H:i', strtotime($work_hour->start_at)) }} - {{ date('H:i', strtotime($work_hour->end_at)) }}</small></p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="container mt-5">
        <!-- Welcome Text -->
        <div class="alert alert-danger text-center" role="alert">
            Selamat datang <strong>{{ Auth::user()->name }}</strong> di {{ setting('name') }}. Mohon maaf untuk memberitahukan bahwa status Anda disini sudah <strong>Tidak Aktif</strong>.
        </div>
    </div>
    @endif

    <!-- Modal Alert -->
    <div class="modal fade" id="modal-alert" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p class="mb-0"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OKE</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Confirmation -->
    <div class="modal fade" id="modal-confirmation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p class="mb-0"></p>
                    <input type="hidden" name="id">
                    <input type="hidden" name="type">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-submit">OKE</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <form class="d-none" id="form-absensi-masuk" method="post" action="{{ route('member.attendance.entry') }}">
        @csrf
        <input type="hidden" name="id">
    </form>

    <form class="d-none" id="form-absensi-keluar" method="post" action="{{ route('member.attendance.exit') }}">
        @csrf
        <input type="hidden" name="id">
    </form>

    <!-- JQuery & Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        // Enable tooltip everywhere
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Entry
        $(document).on("click", ".card-absensi-masuk", function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            $("#modal-confirmation .modal-body").find("p").text("Anda yakin ingin melakukan absen masuk?");
            $("#modal-confirmation .modal-body").find("input[name=id]").val(id);
            $("#modal-confirmation .modal-body").find("input[name=type]").val("masuk");
            $("#modal-confirmation").modal("show");
        });

        // Exit
        $(document).on("click", ".card-absensi-keluar", function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            $("#modal-confirmation .modal-body").find("p").text("Anda yakin ingin mengakhiri waktu bekerja Anda sekarang?");
            $("#modal-confirmation .modal-body").find("input[name=id]").val(id);
            $("#modal-confirmation .modal-body").find("input[name=type]").val("keluar");
            $("#modal-confirmation").modal("show");
        });

        // Full
        $(document).on("click", ".card-absensi-penuh", function(e) {
            e.preventDefault();
            $("#modal-alert .modal-body").find("p").text("Tidak dapat melakukan absensi karena JAM KERJA sudah terisi!");
            $("#modal-alert").modal("show");
        });

        // Button Submit
        $(document).on("click", ".btn-submit", function(e) {
            e.preventDefault();
            var id = $("#modal-confirmation .modal-body").find("input[name=id]").val();
            var type = $("#modal-confirmation .modal-body").find("input[name=type]").val();
            if(type === "masuk") {
                $("#form-absensi-masuk input[name=id]").val(id);
                $("#form-absensi-masuk").submit();
            }
            else if(type === "keluar") {
                $("#form-absensi-keluar input[name=id]").val(id);
                $("#form-absensi-keluar").submit();
            }
        });

        // Timer
        window.setInterval("timer()", 1000);
        function timer() {
            var waktu = new Date();
            var hariIndo = ["Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu"];
            var bulanIndo = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            var tanggal = hariIndo[waktu.getDay()] + ', ' + prefix_zero(waktu.getDate()) + ' ' + bulanIndo[waktu.getMonth()] + ' ' + waktu.getFullYear();
            var waktuberjalan = prefix_zero(waktu.getHours()) + ' : ' + prefix_zero(waktu.getMinutes()) + ' : ' + prefix_zero(waktu.getSeconds());
            $(".digital-date").text(tanggal);
            $(".digital-clock").text(waktuberjalan);
        }

        function prefix_zero(number){
            return number < 10 ? '0' + number : number;
        }
    </script>
</body>
</html>