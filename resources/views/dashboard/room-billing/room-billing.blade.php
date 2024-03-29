@extends('layouts.main')
@section('content')
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Room billing</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Room billing</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="pd-20 card-box mb-30">
                <div class="clearfix mb-20">
                    <div class="pull-left">
                        <h4 class="text-blue h4">Room billing - {{ $month }}</h4>
                    </div>
                    <div class="pull-right d-flex justify-content-between">
                        <button class="btn btn-success btn-sm mr-2" data-target="#room-billing-modal" data-toggle="modal"><i
                                class="ion-calculator"></i> &nbsp; Calculate</button>
                        @if (collect($data)->count() > 0)
                            <a href="{{ route('export-bill', $month) }}" class="btn btn-info btn-sm mr-2"><i
                                    class="fa fa-file-excel-o"></i> &nbsp; Export
                                Excel</a>

                            <a href="{{ route('export-pdf', [$month, $house]) }}" class="btn btn-primary btn-sm mr-2"><i
                                    class="icon-copy fi-print"></i> &nbsp; Print bills</a>

                            <form class="mr-2" method="POST" action="{{ route('mail.send-bill', [$month, $house]) }}"
                                id="send-email-form">
                                @csrf
                                <button class="btn btn-primary btn-sm" type="submit"><i class="icon-copy fi-mail"></i>
                                    &nbsp;
                                    Send email</button>
                            </form>

                            <form method="POST" action="{{ route('sms.send-bill', [$month, $house]) }}" id="send-sms-form">
                                @csrf
                                <button class="btn btn-primary btn-sm" type="submit"><i class="icon-copy fi-mail"></i>
                                    &nbsp;
                                    Send SMS</button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="table-responsive">
                    <input type="text" class="form-control form-control-sm col-6 mb-3" placeholder="Search..."
                        name="search" id="search">

                    <table class="table table-striped" id="bill-table">
                        {{-- alert --}}
                        @if (session('success'))
                            <div class="col-md-6">
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success!</strong> {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        @elseif (session('error'))
                            <div class="col-md-6">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong> {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">House name</th>
                                <th scope="col">Room name </th>
                                <th scope="col">Tenant name</th>
                                <th scope="col">Total price</th>
                                <th scope="col">Status</th>
                                <th scope="col">Details</th>
                                <th scope="col">Action</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $bill)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $bill->house_name }}</td>
                                    <td>{{ $bill->room_name }}</td>
                                    <td>{{ $bill->tenant_name }}</td>
                                    <td class="font-weight-bold">
                                        <div
                                            style="background: rgb(222, 222, 222); border-radius: 5px; padding: 8px; width: fit-content">
                                            {{ number_format($bill->total, 0, ',', ',') }}
                                        </div>
                                    </td>
                                    <td>
                                        @if (collect($roomBilling)->where('rental_room_id', $bill->rental_room_id)->where('date', $bill->billDate)->first()->status == 0)
                                            <span class="badge badge-pill badge-warning">Pending</span>
                                        @else
                                            <span class="badge badge-pill badge-success">Paid</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" id="view-details-btn"
                                            data-objBill="{{ json_encode($bill) }}">
                                            <i class="fa fa-info-circle"></i>
                                        </button>
                                    </td>
                                    <td>
                                        @if (collect($roomBilling)->where('rental_room_id', $bill->rental_room_id)->where('date', $bill->billDate)->first()->status == 0)
                                            <button class="btn btn-success btn-sm" id="update-status-btn" type="button"
                                                data-billID="{{ collect($roomBilling)->where('rental_room_id', $bill->rental_room_id)->where('date', $bill->billDate)->first()->id }}"
                                                data-totalPrice="{{ $bill->total }}">
                                                <i class="icon-copy dw dw-tick"></i> &nbsp; Mark as Paid
                                            </button>
                                        @else
                                            <button class="btn btn-info btn-sm" id="update-status-btn" type="button"
                                                data-billID="{{ collect($roomBilling)->where('rental_room_id', $bill->rental_room_id)->where('date', $bill->billDate)->first()->id }}"
                                                data-totalPrice="{{ $bill->total }}">
                                                <i class="icon-copy dw dw-tick"></i> &nbsp; Mark as Unpaid
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        @if (collect($roomBilling)->where('rental_room_id', $bill->rental_room_id)->where('date', $bill->billDate)->first()->status != 0 &&
                                                $bill->status != 0 &&
                                                Carbon\Carbon::parse($bill->end_date)->format('F Y') == $month)
                                            <button class="btn btn-danger btn-sm" id="return-room-btn" type="button"
                                                data-rentalRoomID="{{ $bill->rental_room_id }}"
                                                data-roomID="{{ $bill->room_id }}" data-tenantID="{{ $bill->tenant_id }}"
                                                data-tenantName="{{ $bill->tenant_name }}"
                                                data-roomName="{{ $bill->room_name }}">
                                                <i class="icon-copy dw dw-tick"></i> &nbsp; Return room
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="room-billing-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Calculate room billing</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('calculate.room-billing') }}">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-4">Month / Year</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control month-picker" placeholder="Month picker"
                                    value="{{ $month }}" name="month">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">House</label>
                            <div class="col-md-8">
                                <select class="form-control font-13" name="house">
                                    <option value="all-house" selected>All houses</option>
                                    @foreach ($houseList as $house)
                                        <option value="{{ $house->house_id }}"
                                            {{ last(request()->segments()) == $house->house_id ? 'selected' : '' }}>
                                            {{ $house->house_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary"><i class="ion-calculator"></i> &nbsp;
                                Calculate</button>
                            <button type="reset" class="btn btn-secondary" data-dismiss="modal"><i
                                    class="icon-copy fa fa-close" aria-hidden="true"></i> &nbsp; Close</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bs-example-modal-lg" id="modal-view-details" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="invoice-wrap">
                    <div class="invoice-box">
                        <div class="invoice-header">
                            <div class="logo text-center">
                                <img src="vendors/images/deskapp-logo.png" alt="">
                            </div>
                        </div>
                        {{-- <h5 class="text-center mb-5 weight-600">INVOICE</h5> --}}
                        <div class="row pb-30">
                            <div class="col-md-6">
                                <p class="font-14 mb-5">Full name: <strong class="weight-600" id="tenantName">Lưu Hoài
                                        Phong</strong></p>
                                <p class="font-14 mb-5">Room name: <strong class="weight-600" id="roomName">Phòng
                                        14B</strong></p>
                                <p class="font-14 mb-5">Phone number: <strong class="weight-600"
                                        id="phoneNumber">0398371050</strong></p>
                                <p class="font-14 mb-5">Email: <strong class="weight-600"
                                        id="email">luuhoaiphong147@gmail.com</strong>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <div class="text-right">
                                    <p class="font-14 mb-5">Belongs to the house: <strong class="weight-600"
                                            id="houseName">Trọ Hoàn Hảo
                                            2</strong></p>
                                    <p class="font-14 mb-5">House address: <strong class="weight-600"
                                            id="houseAddress">225/12/47 Hẻm 391,
                                            đường 30/4, Hưng Lợi, Ninh Kiều, Cần Thơ</strong></p>
                                    <p class="font-14 mb-5">Bill month: <strong class="weight-600" id="billDate">March
                                            2023</strong></p>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-desc pb-30">
                            <div class="invoice-desc-head clearfix">
                                <div class="invoice-sub">Services</div>
                                <div class="invoice-rate text-center">Consumed</div>
                                <div class="invoice-hours text-center">Unit Price</div>
                                <div class="invoice-subtotal text-center">Subtotal</div>
                            </div>
                            <div class="invoice-desc-body">
                                <ul>
                                    <li class="clearfix">
                                        <div class="invoice-sub">Room price</div>
                                        <div class="invoice-rate"></div>
                                        <div class="invoice-hours"></div>
                                        <div class="invoice-subtotal text-center"><span class="weight-600"
                                                id="room_pirce">1000000</span></div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="invoice-sub">
                                            Water bill <br>
                                            (Old: <i id="oldWaterIndex">1234</i>,
                                            New: <i id="newWaterIndex">2345</i>)
                                        </div>
                                        <div class="invoice-rate text-center">
                                            <span id="water_consumed">333</span>
                                        </div>
                                        <div class="invoice-hours text-center">
                                            <span id="water_unit_price">333</span>
                                        </div>
                                        <div class="invoice-subtotal text-center">
                                            <span class="weight-600" id="water_totalConsumed">100000</span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="invoice-sub">
                                            Electricity bill <br>
                                            (Old: <i id="oldElectricityIndex">1234</i>,
                                            New: <i id="newElectricityIndex">2345</i>)
                                        </div>
                                        <div class="invoice-rate text-center">
                                            <span id="electricity_consumed">333</span>
                                        </div>
                                        <div class="invoice-hours text-center">
                                            <span id="electricity_unit_price">333</span>
                                        </div>
                                        <div class="invoice-subtotal text-center">
                                            <span class="weight-600" id="electricity_totalConsumed">100000</span>
                                        </div>
                                    </li>
                                    <div id="costsIncurredSection">

                                    </div>
                                    <div id="otherServices">

                                    </div>
                                    <div class="invoice-desc-body">
                                        <ul>
                                            <li class="clearfix">
                                                <div class="invoice-sub"></div>
                                                <div class="invoice-rate font-20 weight-600">Total</div>
                                                <div class="invoice-subtotal text-center">
                                                    <span class="weight-600 font-24 text-danger"
                                                        id="totalBill">$8000</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loading-modal" tabindex="-1" role="dialog" aria-labelledby="loadingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        Sending...
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- SECTION-START: confirm delete popup --}}
    <div class="modal fade" id="update-status" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center font-18">
                    <h4 class="padding-top-30 mb-30 weight-500" id="msg-delete-confirm">Please double check the
                        information before confirming!
                    </h4>
                    <form id="update-status-form" method="post">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-3 col-form-label">Total price</label>
                            <div class="col-sm-12 col-md-8">
                                <input type="text" class="form-control form-control-sm" id="totalPrice" disabled>
                            </div>
                        </div>
                        <div class="padding-bottom-30 row" style="max-width: 300px; margin: 0 auto;">
                            <div class="col-4">
                                <button type="button"
                                    class="btn btn-secondary border-radius-100 btn-block confirmation-btn"
                                    data-dismiss="modal"><i class="fa fa-times"></i></button>
                                Cancel
                            </div>
                            <div class="col-4">
                                <button type="submit" name="status" value="unpaid"
                                    class="btn btn-danger border-radius-100 btn-block confirmation-btn"><i
                                        class="fa fa-times"></i></button>
                                Unpaid
                            </div>
                            <div class="col-4">
                                <button type="submit" name="status" value="paid"
                                    class="btn btn-primary border-radius-100 btn-block confirmation-btn"><i
                                        class="fa fa-check"></i></button>
                                Paid
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- SECTION-END: confirm delete popup --}}

    {{-- SECTION-START: confirm delete popup --}}
    <div class="modal fade" id="return-room-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center font-18">
                    <h4 class="padding-top-30 weight-500" id="msg-delete-confirm">Room return confirmation!</h4>
                    <h6 class="padding-top-10 mb-30 weight-500 text-danger">Information will be deleted and cannot be
                        recovered.</h6>
                    <form id="return-room-form" method="post">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-3 col-form-label">Tenant name</label>
                            <div class="col-sm-12 col-md-8">
                                <input type="hidden" id="inputTenantID" name="tenantID">
                                <input type="text" class="form-control form-control-sm" id="inputFullname" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-3 col-form-label">Room name</label>
                            <div class="col-sm-12 col-md-8">
                                <input type="hidden" id="inputRoomID" name="roomID">
                                <input type="hidden" id="inputRentalRoomID" name="rentalRoomID">
                                <input type="text" class="form-control form-control-sm" id="inputRoomName" disabled>
                            </div>
                        </div>

                        <div class="padding-bottom-30 row" style="max-width: 100%; margin: 0 auto;">
                            <div class="col-4">
                                <button type="button"
                                    class="btn btn-secondary border-radius-100 btn-block confirmation-btn"
                                    data-dismiss="modal"><i class="fa fa-times"></i></button>
                                Cancel
                            </div>
                            <div class="col-4">
                                <button type="submit" name="status" value="keep_staying"
                                    class="btn btn-danger border-radius-100 btn-block confirmation-btn"><i
                                        class="fa fa-times"></i></button>
                                Keep staying
                            </div>
                            <div class="col-4">
                                <button type="submit" name="status" value="return"
                                    class="btn btn-primary border-radius-100 btn-block confirmation-btn"><i
                                        class="fa fa-check"></i></button>
                                Return
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- SECTION-END: confirm delete popup --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var updateStatusBtn = document.querySelectorAll('#update-status-btn');
            updateStatusBtn.forEach(function(e) {
                e.addEventListener('click', function() {
                    var id = e.getAttribute('data-billID');
                    var totalPrice = parseFloat(e.getAttribute('data-totalPrice'));

                    var inputTotal = document.querySelector('#totalPrice');
                    inputTotal.value = totalPrice.toLocaleString();

                    var form = document.querySelector('#update-status-form');
                    form.action = "{{ route('update-status-bill', ':id') }}".replace(':id', id);

                    $('#update-status').modal('show');
                });
            });

            var returnRoom = document.querySelectorAll('#return-room-btn');
            returnRoom.forEach(function(e) {
                e.addEventListener('click', function() {
                    var roomID = e.getAttribute('data-roomID');
                    var roomName = e.getAttribute('data-roomName');
                    var rentalRoomID = e.getAttribute('data-rentalRoomID');
                    var tenantID = e.getAttribute('data-tenantID');
                    var tenantName = e.getAttribute('data-tenantName');

                    var inputTenantID = document.querySelector('#inputTenantID');
                    var inputFullname = document.querySelector('#inputFullname');
                    var inputRentalRoomID = document.querySelector('#inputRentalRoomID');
                    var inputRoomID = document.querySelector('#inputRoomID');
                    var inputRoomName = document.querySelector('#inputRoomName');

                    inputTenantID.value = tenantID;
                    inputFullname.value = tenantName;
                    inputRentalRoomID.value = rentalRoomID;
                    inputRoomID.value = roomID;
                    inputRoomName.value = roomName;

                    var form = document.querySelector('#return-room-form');
                    form.action = "{{ route('confirm-return-room') }}";

                    $('#return-room-modal').modal('show');
                });
            });
        });
    </script>

    <script>
        const formEmail = document.querySelector('#send-email-form');
        formEmail.querySelector('button[type="submit"]').addEventListener('click', function(e) {
            e.preventDefault();
            $('#loading-modal').modal('show');
            formEmail.submit();
        });
    </script>

    <script>
        const formSMS = document.querySelector('#send-sms-form');
        formSMS.querySelector('button[type="submit"]').addEventListener('click', function(e) {
            e.preventDefault();
            $('#loading-modal').modal('show');
            formSMS.submit();
        });
    </script>

    <script>
        const search = document.querySelector('#search');
        const table = document.querySelector('#bill-table');

        search.addEventListener('input', function() {
            const searchTerm = search.value.trim().toLowerCase();

            table.querySelectorAll('tbody tr').forEach(function(row) {
                let machFound = false;

                row.querySelectorAll('td').forEach(function(cell) {
                    if (cell.textContent.trim().toLowerCase().indexOf(searchTerm) > -1) {
                        machFound = true;
                    }
                });

                if (machFound) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

    <script src="{{ asset('vendors/scripts/handle-room-billing.js') }}"></script>
@endsection
