@extends('layouts.main')
@section('content')
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>@lang('messages.navRoom')</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">@lang('messages.navHome')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('house.index') }}">House management</a></li>
                                <li class="breadcrumb-item active" aria-current="page">@lang('messages.navRoom')</li>
                            </ol>
                        </nav>
                    </div>
                    {{-- <div class="col-md-6 col-sm-12">
                        <div class="dropdown">
                            <form action="" style="display: flex; justify-content: space-between">
                                <input type="text" name="search" placeholder="Room number" class="form-control"
                                    style="margin-right: 5%; font-size: 13px">
                                <select class="form-control" name="" style="margin-right: 5%; font-size: 13px">
                                    <option value="">Room status</option>
                                    <option value="">1</option>
                                    <option value="">1</option>
                                    <option value="">1</option>
                                    <option value="">1</option>
                                </select>
                                <select class="form-control" name="" style="margin-right: 5%; font-size: 13px">
                                    <option value="">Room billed</option>
                                    <option value="">1</option>
                                    <option value="">1</option>
                                    <option value="">1</option>
                                    <option value="">1</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">Search</button>
                            </form>
                        </div>
                    </div> --}}
                </div>
            </div>

            {{-- NOTE: if the landlords have only house, this appears for the user if they wanna add another new house --}}
            @if (session('hasOneHouse') && count($rooms) > 0)
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="title">
                                <i>
                                    <h6>{{ $rooms[0]->houses->house_name }}</h6>
                                    {{ $rooms[0]->houses->house_address }}
                                </i>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="pull-right" style="display: flex">
                                <a class="btn btn-outline-primary btn-sm" id="toggle-btn" style="display: flex; align-items: center">Hide</a>
                                <div id="content">
                                    &nbsp;&nbsp;&nbsp;You have another house?
                                    <button class="btn btn-success btn-sm" data-toggle="modal"data-target="#house-add"><i
                                            class="ion-plus-round"></i> Add new</button>&nbsp;&nbsp;OR&nbsp;&nbsp;

                                    <button id="edit-house" data-houseID="{{ $rooms[0]->houses->house_id }}"
                                        data-houseName="{{ $rooms[0]->houses->house_name }}"
                                        data-houseAddress="{{ $rooms[0]->houses->house_address }}"
                                        data-houseDescription="{{ $rooms[0]->houses->house_description }}"
                                        class="btn btn-secondary btn-sm" title="Edit house"><i class="fa fa-edit"></i> Edit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- NOTE: script handle show hide above element --}}
                <script>
                    const toggleBtn = document.querySelector('#toggle-btn');
                    const content = document.querySelector('#content');

                    content.style.display = 'none';
                    toggleBtn.innerHTML = 'Show';

                    let isHidden = true;

                    toggleBtn.addEventListener('click', () => {
                        if (isHidden) {
                            content.style.display = 'block';
                            toggleBtn.innerHTML = 'Hide';
                        } else {
                            content.style.display = 'none';
                            toggleBtn.innerHTML = 'Show';
                        }
                        isHidden = !isHidden;
                    });
                </script>


                {{-- SECTION-START: add & update house popup --}}

                @include('dashboard.house.modal-add-update')

                <!-- SECTION-END: add & update house popup -->
            @endif

            {{-- <div class="page-header">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="dropdown">
                            <form action="" style="display: flex; justify-content: space-between">
                                <input type="text" name="search" placeholder="Room number" class="form-control"
                                    style="margin-right: 5%; font-size: 13px">
                                <select class="form-control" name="" style="margin-right: 5%; font-size: 13px"> --}}

            {{-- @foreach ($house as $item)
                                        <option value="{{ $item->house_id }}">{{ $item->house_name }}</option>
                                    @endforeach --}}
            {{-- <option value="">Room status</option>
                                    <option value="">1</option>
                                    <option value="">1</option>
                                    <option value="">1</option>
                                    <option value="">1</option> --}}
            {{-- </select>
                                <select class="form-control" name="" style="margin-right: 5%; font-size: 13px">
                                    <option value="">Room billed</option>
                                    <option value="">1</option>
                                    <option value="">1</option>
                                    <option value="">1</option>
                                    <option value="">1</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">Search</button>
                            </form>
                        </div> --}}
            {{-- <div class="dropdown">
                            <label style="font-size: 15px; font-weight: bold">Area: </label>
                            <a class="btn btn-success btn-sm dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                January 2018
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Export List</a>
                                <a class="dropdown-item" href="#">Policies</a>
                                <a class="dropdown-item" href="#">View Assets</a>
                            </div>
                        </div> --}}
            {{-- </div>
                </div>
            </div> --}}
            <div class="row">
                <div class="col-xl-3 mb-30">
                    <div class="card-box height-100-p widget-style1">
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="progress-data">
                                <div id="statistic1"></div>
                            </div>
                            <div class="widget-data">
                                <div class="h4 mb-0" id="s1">{{ $countTotal }}</div>
                                <div class="weight-600 font-14">Total room</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 mb-30">
                    <div class="card-box height-100-p widget-style1">
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="progress-data">
                                <div id="statistic2"></div>
                            </div>
                            <div class="widget-data">
                                <div class="h4 mb-0" id="s2">{{ $countRentedRoom }}</div>
                                <div class="weight-600 font-14">Rented room</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 mb-30">
                    <div class="card-box height-100-p widget-style1">
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="progress-data">
                                <div id="statistic3"></div>
                            </div>
                            <div class="widget-data">
                                <div class="h4 mb-0" id="s3">{{ $countAvailableRoom }}</div>
                                <div class="weight-600 font-14">Available</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 mb-30">
                    <div class="card-box height-100-p widget-style1">
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="progress-data">
                                <div id="statistic4"></div>
                            </div>
                            <div class="widget-data">
                                <div class="h4 mb-0" id="s4">0</div>
                                <div class="weight-600 font-14">Unknown</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 mb-30">
                    <div class="pd-20 card-box">

                        <div class="clearfix mb-10">
                            <div class="pull-right">
                                <a href="javascript:;" data-toggle="modal" data-target="#room-add"
                                    class="btn btn-success btn-sm" style="width: fix-content"><i
                                        class="icon-copy fa fa-plus" aria-hidden="true"></i>
                                    Add a new room</a>
                                <a href="javascript:;" data-toggle="modal" data-target="#room-add-multiple"
                                    class="btn btn-info btn-sm"><i class="icon-copy fa fa-plus" aria-hidden="true"></i>
                                    Add
                                    multiple new rooms</a>
                            </div>
                        </div>


                        <div class="tab">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="home" role="tabpanel">
                                    <div class="pd-20">
                                        <div class="row clearfix">
                                            @if (count($rooms) == 0)
                                                <h4>No data found</h4>
                                            @else
                                                @foreach ($rooms as $room)
                                                    <div class="col-sm-12 col-md-3 mb-30">
                                                        <div class="card card-box">
                                                            @if ($room->status == 0)
                                                                <div class="card-header"
                                                                    style="background-color: #B3DBF8">
                                                                    <i class="icon-copy dw dw-house"></i>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;{{ $room->room_name }}
                                                                </div>
                                                            @else
                                                                <div class="card-header"
                                                                    style="background-color: #1899F5">
                                                                    <i class="icon-copy dw dw-house"></i>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;{{ $room->room_name }}
                                                                    <span class="badge badge-pill badge-success">
                                                                        Rented
                                                                    </span>
                                                                </div>
                                                            @endif

                                                            <div class="card-body">
                                                                {{-- <h5 class="card-title">Special title treatment</h5> --}}
                                                                <p class="card-text">
                                                                    <i class="icon-copy dw dw-user-3"></i>
                                                                    @if ($room->status == 0)
                                                                        <span class="badge badge-pill badge-danger">
                                                                            Available
                                                                        </span>
                                                                    @else
                                                                        <b style="color: green">
                                                                            {{ $room->rentals->tenants->fullname }}
                                                                        </b>
                                                                    @endif
                                                                </p>
                                                                <p class="card-text"
                                                                    style="display: flex; align-items: center">
                                                                    <i class="icon-copy dw dw-money-2"
                                                                        style="font-size: 20px"></i>
                                                                    &nbsp;{{ number_format($room->price, 0, ',', ',') }}
                                                                </p>

                                                                @if ($room->status == 0)
                                                                    <div class="pull-left">
                                                                        <a href="{{ route('room.assign-tenant', $room->room_id) }}"
                                                                            class="btn btn-secondary btn-sm"><i
                                                                                class="icon-copy dw dw-add"></i></a>
                                                                    </div>
                                                                @else
                                                                    <div class="pull-left">
                                                                        <a href="#"
                                                                            class="btn btn-outline-secondary btn-sm"
                                                                            title="Return"><i
                                                                                class="icon-copy dw dw-refresh2"></i></a>
                                                                    </div>
                                                                @endif

                                                                <div class="pull-right">
                                                                    {{-- <a href="#" class="btn btn-primary btn-sm">
                                                                    <i class="icon-copy dw dw-edit"></i>
                                                                </a>
                                                                <a href="#" class="btn btn-danger btn-sm">
                                                                    <i class="icon-copy dw dw-trash"></i>
                                                                </a> --}}

                                                                    <form id="delete-room"
                                                                        action="{{ route('room.delete', $room->room_id) }}"
                                                                        method="Post">
                                                                        <a href="#" class="btn btn-primary btn-sm"
                                                                            role="button" title="Show details"><i
                                                                                class="fa fa-eye"></i></a>
                                                                        <a href="javascript:;" data-toggle="modal"
                                                                            id="edit-room-modal-btn"
                                                                            data-id="{{ $room->room_id }}"
                                                                            data-roomName="{{ $room->room_name }}"
                                                                            data-price="{{ $room->price }}"
                                                                            data-description="{{ $room->room_description }}"
                                                                            class="btn btn-secondary btn-sm"
                                                                            title="Edit Room"><i
                                                                                class="fa fa-edit"></i></a>
                                                                        @csrf
                                                                        {{-- <button type="submit" class="btn btn-danger"
                                                                        onclick="return confirm('Are you sure to delete?')"><i
                                                                            class="fa fa-trash"></i></button> --}}

                                                                        <button class="btn btn-danger btn-sm"
                                                                            type="button" id="confirm-delete-modal-btn"
                                                                            data-id="{{ $room->room_id }}"
                                                                            data-roomName="{{ $room->room_name }}"
                                                                            data-houseID="{{ $id }}"><i
                                                                                class="fa fa-trash"></i></button>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div style="display: flex;justify-content: center;align-items: center;">
                                            {{ $rooms->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- SECTION-START: add room popup -->
    <div class="modal fade" id="room-add" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Add a new room</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form name="formAddRoom" method="post" action="{{ route('room.add', $id) }}">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-4">Room name</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="room_name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Price</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="price" id="price">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Description</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="room_description"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Add</button>
                            <button type="reset" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- SECTION-END: add room popup -->

    <!-- SECTION-START: add multiple rooms popup -->
    <div class="modal fade bs-example-modal-lg" id="room-add-multiple" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Add multiple new rooms</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form name="formAddMultipleRoom" method="post" action="{{ route('room.add.multiple', $id) }}">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-4">How many rooms do you want to create?</label>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="quantity">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Room name</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="room_name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Price</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="price" id="price">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Description</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="room_description"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Add</button>
                            <button type="reset" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- SECTION-END: add multiple rooms popup -->

    {{-- SECTION-START: update room popup --}}
    <div class="modal fade" id="room-edit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Update room</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form name="formUpdateRoom" method="post">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-4">Room name</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="room_name_edit" id="room_name_edit">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Price</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="price_edit" id="price_edit">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Description</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="room_description_edit" id="room_description_edit"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="reset" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
    <!-- SECTION-END: update room popup -->





    {{-- <!-- Confirmation modal -->

    <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center font-18">
                    <h4 class="padding-top-30 mb-30 weight-500" id="msg-delete-confirm">Are you sure you want to continue?</h4>
                    <form method="post" action="{{ route('room.delete', $id) }}">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <div class="padding-bottom-30 row" style="max-width: 170px; margin: 0 auto;">
                            <div class="col-6">
                                <button type="button"
                                    class="btn btn-secondary border-radius-100 btn-block confirmation-btn"
                                    data-dismiss="modal"><i class="fa fa-times"></i></button>
                                NO
                            </div>
                            <div class="col-6">
                                <button type="submit"
                                    class="btn btn-primary border-radius-100 btn-block confirmation-btn"><i
                                        class="fa fa-check"></i></button>
                                YES
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- @include('layouts.confirm-popup') --}}
    {{-- SECTION-START: confirm delete popup --}}
    <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center font-18">
                    <h4 class="padding-top-30 mb-30 weight-500" id="msg-delete-confirm">Are you sure you want to continue?
                    </h4>
                    <form id="delete-form" method="post">
                        @csrf
                        <input type="hidden" name="id" id="id">

                        <div class="padding-bottom-30 row" style="max-width: 170px; margin: 0 auto;">
                            <div class="col-6">
                                <button type="button"
                                    class="btn btn-secondary border-radius-100 btn-block confirmation-btn"
                                    data-dismiss="modal"><i class="fa fa-times"></i></button>
                                NO
                            </div>
                            <div class="col-6">
                                <button type="submit"
                                    class="btn btn-primary border-radius-100 btn-block confirmation-btn"><i
                                        class="fa fa-check"></i></button>
                                YES
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

            // NOTE: passing value to delete room confirm modal
            var deleteButtons = document.querySelectorAll('#confirm-delete-modal-btn');
            deleteButtons.forEach(function(e) {
                e.addEventListener('click', function() {
                    var roomID = e.getAttribute('data-id');
                    var houseID = e.getAttribute('data-houseID');
                    var name = e.getAttribute('data-roomName');
                    var msg = document.querySelector('#msg-delete-confirm');
                    var cardIDInput = document.querySelector(
                        '#confirm-delete-modal input[name="id"]');
                    var formDelete = document.querySelector('#delete-form');
                    formDelete.action = "{{ route('room.delete', ':id') }}".replace(':id',
                        houseID);

                    msg.innerHTML = 'Are you sure you want to delete room ' + name + '?';
                    cardIDInput.value = roomID;
                    $('#confirm-delete-modal').modal('show');
                });
            });

            // NOTE: passing value to edit room modal
            var editButtons = document.querySelectorAll('#edit-room-modal-btn');
            editButtons.forEach(function(e) {
                e.addEventListener('click', function() {

                    var roomID = e.getAttribute('data-id');
                    var roomName = e.getAttribute('data-roomName');
                    var roomDescription = e.getAttribute('data-description');
                    var price = e.getAttribute('data-price');

                    var formUpdate = document.querySelector(
                        '#room-edit form[name="formUpdateRoom"]');
                    formUpdate.action = "{{ route('room.update', ':id') }}".replace(':id',
                        roomID);

                    var roomNameInput = document.querySelector(
                        '#room-edit input[name="room_name_edit"]');
                    var roomDescriptionInput = document.querySelector(
                        '#room-edit textarea[name="room_description_edit"]');
                    var priceInput = document.querySelector('#room-edit input[name="price_edit"]');

                    roomNameInput.value = roomName;
                    roomDescriptionInput.value = roomDescription;
                    priceInput.value = price;

                    $('#room-edit').modal('show');
                });
            });

            // NOTE: passing value to edit house modal
            var editHouseBtn = document.querySelectorAll('#edit-house');
            editHouseBtn.forEach(function(e) {
                e.addEventListener('click', function() {
                    var houseID = e.getAttribute('data-houseID');
                    var houseName = e.getAttribute('data-houseName');
                    var houseAddress = e.getAttribute('data-houseAddress');
                    var houseDescription = e.getAttribute('data-houseDescription');

                    var inputName = document.querySelector('#house_name_edit');
                    var inputAddress = document.querySelector('#house_address_edit');
                    var inputDescription = document.querySelector('#house_description_edit');
                    var formUpdate = document.querySelector('#formUpdateHouse');

                    inputName.value = houseName;
                    inputAddress.value = houseAddress;
                    inputDescription.value = houseDescription;
                    formUpdate.action = "{{ route('house.update', ':id') }}".replace(':id',
                        houseID);

                    $('#house-edit').modal('show');
                });
            });
        });

        // function assignTenantSubmit() {
        //     const tenantForm = document.querySelector('#room-assign-tenant');
        //     tenantForm.submit();
        // }

        // Format number input with commas as thousands separators
        const numberInput = document.querySelector("#room-add #price");
        const numberInputMultiple = document.querySelector("#room-add-multiple #price");
        const numberInputEdit = document.querySelector("#room-edit #price_edit");


        // Add event listener for when input value changes
        numberInput.addEventListener("input", formatNumber);
        numberInputMultiple.addEventListener("input", formatNumber);
        numberInputEdit.addEventListener("input", formatNumber);

        function formatNumber() {

            if (this.value.length === 0) return;
            // Get the input value and remove any non-numeric characters except for the decimal point
            let input = this.value.replace(/[^0-9.]/g, "");

            // Parse the input as a float and format it with commas as thousands separators
            let formatted = parseFloat(input).toLocaleString();

            // Update the input value with the formatted value
            this.value = formatted;
        }
    </script>
@endsection
