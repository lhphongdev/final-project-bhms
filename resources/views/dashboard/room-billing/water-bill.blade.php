@extends('layouts.main')
@section('content')
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Water bill</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Water bill</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <form method="POST" action="{{ route('water-filter') }}">
                            @csrf
                            <div class="dropdown d-flex">
                                <input type="text" class="form-control form-control-sm month-picker mr-3"
                                    placeholder="Month picker" value="{{ $date }}" name="month-filter">
                                <select class="form-control form-control-sm font-13 mr-3" name="house-filter">
                                    <option value="all-house" selected>All house</option>
                                    @foreach ($houseList as $house)
                                        <option value="{{ $house->house_id }}"
                                            {{ last(request()->segments()) == $house->house_id ? 'selected' : '' }}>
                                            {{ $house->house_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('water.insert') }}">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix mb-20">
                        <div class="pull-left">
                            <div class="d-flex justify-content-center align-items-center">
                                <h4 class="text-blue h4"> <i class="dw dw-calendar-11"></i> <u>{{ $date }}</u> -
                                    Water bill</h4>

                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show ml-3" role="alert">
                                        <strong>Success! </strong>{{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                        {{-- <div class="pull-right">
                        <a href="javascript:;" data-toggle="modal" data-target="#service-add"
                            class="btn btn-success btn-sm"><i class="ion-plus-round"></i> Add a new service</a>
                    </div> --}}
                        <div class="pull-right">
                            <a href="{{ route('export-water', $date) }}" class="btn btn-info btn-sm"><i
                                    class="fa fa-file-excel-o"></i> Export excel</a>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="icon-copy dw dw-diskette2"></i>
                                &nbsp; Save</button>
                            <a href="{{ route('home') }}" class="btn btn-danger btn-sm"><i class="icon-copy fa fa-close"
                                    aria-hidden="true"></i> &nbsp; Cancel</a>
                        </div>
                    </div>
                    <div class="table-responsive">

                        <input type="text" class="form-control form-control-sm col-6 mb-3" placeholder="Search..."
                            name="search" id="search">

                        <table class="table table-striped table-bordered" id="water-table">
                            <thead>
                                <tr>
                                    <th scope="col" rowspan=2># </th>
                                    <th scope="col" rowspan=2 style="width: 150px">House name</th>
                                    <th scope="col" rowspan=2 style="width: 150px">Room name</th>
                                    <th scope="col" rowspan=2 style="width: 200px">Tenant name</th>
                                    <th scope="col" colspan=3 style="text-align: center">Water index</th>
                                    <th scope="col" rowspan="2" style="width: 150px;text-align: center">Total amount
                                    </th>
                                </tr>
                                <tr style="text-align: center">
                                    <th scope="col">Old index</th>
                                    <th scope="col">New index</th>
                                    <th scope="col">Consume</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataList as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->house_name }}</td>
                                        <td>{{ $data->room_name }}</td>
                                        <td>{{ $data->fullname }}</td>
                                        <td>
                                            <input type="hidden" name="rentalRoomID[]"
                                                value="{{ $data->rental_room_id }}">

                                            @if (collect($oldIndexes)->where('rental_room_id', $data->rental_room_id)->where('new_water_index', '!=', 0)->isNotEmpty())
                                                <input class="form-control" type="number" name="oldIndex_water[]"
                                                    id="oldIndex_water" placeholder="0" min="0"
                                                    value="{{ collect($oldIndexes)->where('rental_room_id', $data->rental_room_id)->pluck('new_water_index')->first() }}">
                                            @else
                                                @if (collect($currentIndexes)->where('rental_room_id', $data->rental_room_id)->isNotEmpty())
                                                    <input class="form-control" type="number" name="oldIndex_water[]"
                                                        id="oldIndex_water" placeholder="0" min="0"
                                                        value="{{ collect($currentIndexes)->where('rental_room_id', $data->rental_room_id)->pluck('old_water_index')->first() }}">
                                                @else
                                                    <input class="form-control" type="number" name="oldIndex_water[]"
                                                        id="oldIndex_water" placeholder="0" min="0">
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if (collect($currentIndexes)->where('rental_room_id', $data->rental_room_id)->isNotEmpty())
                                                <input class="form-control" type="number" name="newIndex_water[]"
                                                    id="newIndex_water" placeholder="0" min="0"
                                                    value="{{ collect($currentIndexes)->where('rental_room_id', $data->rental_room_id)->pluck('new_water_index')->first() }}">
                                            @else
                                                <input class="form-control" type="number" name="newIndex_water[]"
                                                    id="newIndex_water" placeholder="0" min="0">
                                            @endif
                                        </td>
                                        <td style="text-align: center">
                                            <div id="usedIndex_water"
                                                style="background: rgb(222, 222, 222); border-radius: 5px; padding: 8px">0
                                            </div>
                                        </td>
                                        <td style="text-align: center; font-weight: bold">
                                            <input type="hidden" value="{{ $data->price_if_changed }}" id="priceUnit">
                                            <div id="totalAmount"
                                                style="background: rgb(200, 200, 200); border-radius: 5px; padding: 8px">
                                                0
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </form>
        </div>
    </div>


    <script>
        // NOTE: get all the rows in the table body
        var rows = document.querySelectorAll('#water-table tbody tr');

        // loop through each row and add an event listener to the new index electric input field
        rows.forEach(function(row) {
            var input2 = row.querySelector('input[id="newIndex_water"]');
            var input1 = row.querySelector('input[id="oldIndex_water"]');

            input1.addEventListener('input', function() {
                var value2 = parseInt(input2.value);
                var value1 = parseInt(input1.value);
                var priceUnit = parseInt(row.querySelector('input[id="priceUnit"]').value);

                if (!isNaN(value2) && !isNaN(value1) && value2 >= value1) {
                    let consume = value2 - value1;
                    let totalAmount = consume * priceUnit;

                    row.querySelector('div[id="usedIndex_water"]').innerHTML = consume;
                    row.querySelector('div[id="totalAmount"]').innerHTML = totalAmount;

                    input1.classList.remove('form-control-warning');
                    input2.classList.remove('form-control-warning');
                } else {
                    row.querySelector('div[id="usedIndex_water"]').innerHTML = "Errors";
                    row.querySelector('div[id="totalAmount"]').innerHTML = "Errors";
                    input1.classList.add('form-control-warning');
                    input2.classList.add('form-control-warning');
                }
            });

            input2.addEventListener('input', function() {
                var value2 = parseInt(input2.value);
                var value1 = parseInt(input1.value);
                var priceUnit = parseInt(row.querySelector('input[id="priceUnit"]').value);

                if (!isNaN(value2) && !isNaN(value1) && value2 >= value1) {
                    let consume = value2 - value1;
                    let totalAmount = consume * priceUnit;

                    row.querySelector('div[id="usedIndex_water"]').innerHTML = consume;
                    row.querySelector('div[id="totalAmount"]').innerHTML = totalAmount;

                    input1.classList.remove('form-control-warning');
                    input2.classList.remove('form-control-warning');
                } else {
                    row.querySelector('div[id="usedIndex_water"]').innerHTML = "Errors";
                    row.querySelector('div[id="totalAmount"]').innerHTML = "Errors";
                    input1.classList.add('form-control-warning');
                    input2.classList.add('form-control-warning');
                }
            });

            // NOTE: if the new index electric input field has a value, then calculate the used index electric
            if (input2.value && input2.value) {
                var value2 = parseInt(input2.value);
                var value1 = parseInt(input1.value);
                var priceUnit = parseInt(row.querySelector('input[id="priceUnit"]').value);

                if (!isNaN(value2) && !isNaN(value1) && value2 >= value1) {
                    let consume = value2 - value1;
                    let totalAmount = consume * priceUnit;

                    row.querySelector('div[id="usedIndex_water"]').innerHTML = consume;
                    row.querySelector('div[id="totalAmount"]').innerHTML = totalAmount;

                    input1.classList.remove('form-control-warning');
                    input2.classList.remove('form-control-warning');
                } else {
                    row.querySelector('div[id="usedIndex_water"]').innerHTML = "Errors";
                    row.querySelector('div[id="totalAmount"]').innerHTML = "Errors";
                    input1.classList.add('form-control-warning');
                    input2.classList.add('form-control-warning');
                }
            }
        });
    </script>

    <script>
        const search = document.querySelector('#search');
        const table = document.querySelector('#water-table');

        search.addEventListener('input', function() {
            const searchTerm = search.value.toLowerCase();

            table.querySelectorAll('tbody tr').forEach(function(row) {
                let machFound = false;

                row.querySelectorAll('td').forEach(function(cell) {
                    if (cell.textContent.toLowerCase().indexOf(searchTerm) > -1) {
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
@endsection
