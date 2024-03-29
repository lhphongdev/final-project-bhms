<?php

namespace App\Http\Controllers\Room;

use App\Exports\ExportRooms;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\RentalRoom;
use App\Models\Services;
use App\Models\Members;


class RoomController extends Controller
{
    public function index($id)
    {
        $rooms = Room::where('house_id', $id)->paginate(20);
        $tenants = Tenant::where('user_id', auth()->user()->id)->where('status', 0)->get();
        $serviceUsed = DB::table('tb_services_used')
            ->join('tb_services', 'tb_services.service_id', '=', 'tb_services_used.service_id')
            ->join('tb_type_service', 'tb_type_service.type_id', '=', 'tb_services.type_id')
            ->join('tb_rental_room', 'tb_rental_room.rental_room_id', '=', 'tb_services_used.rental_room_id')
            ->join('tb_rooms', 'tb_rooms.room_id', '=', 'tb_rental_room.room_id')
            ->join('tb_main_tenants', 'tb_main_tenants.tenant_id', '=', 'tb_rental_room.tenant_id')
            ->get();

        // $countTotal = Room::where('house_id', $id)->count();
        // $countRentedRoom = Room::where('house_id', $id)->where('status', 1)->count();
        // $countAvailableRoom = Room::where('house_id', $id)->where('status', 0)->count();

        return view('dashboard.room.index', compact(
            [
                'rooms',
                'tenants',
                'serviceUsed',
                'id',
                // 'countTotal',
                // 'countRentedRoom',
                // 'countAvailableRoom'
            ]
        ))->with('title', 'Room Management');
    }

    public function addSingleRoom(Request $request, $id)
    {
        // validate
        $request->validate([
            'room_name' => 'required',
            'price' => 'required',
        ]);

        if ($request->price != 'NaN') {
            //add room to database
            $room = new Room();
            $room->room_name = $request->room_name;
            $room->price = intval(str_replace(",", "", $request->price));
            $room->room_description = $request->room_description;
            $room->house_id = $id;
            $room->save();
            return redirect()->route('room.index', $id);
        } else {
            return redirect()->route('room.index', $id)->with('errorPrice', 'Price is not valid');
        }
    }

    public function addMultipleRooms(Request $request, $id)
    {
        // validate
        $request->validate([
            'room_name' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'start_from' => 'required',
        ]);

        for ($i = 0; $i < $request->quantity; $i++) {
            $room = new Room();
            $room->room_name = $request->room_name . ' ' . ($request->start_from + $i);
            $room->price = intval(str_replace(",", "", $request->price));
            $room->room_description = $request->room_description;
            $room->house_id = $id;
            $room->save();
        }
        return redirect()->route('room.index', $id);
    }

    public function update(Request $request, $id)
    {
        // validate
        $request->validate([
            'room_name_edit' => 'required',
            'price_edit' => 'required',
        ]);

        $room = Room::find($id);
        $room->room_name = $request->room_name_edit;
        $room->price = intval(str_replace(",", "", $request->price_edit));
        $room->room_description = $request->room_description_edit;
        $room->save();
        return redirect()->route('room.index', $room->house_id)->with('success', 'Room has been updated successfully');
    }

    public function delete(Request $request, $house_id)
    {
        $id = $request->id;
        $room = Room::find($id);
        $room->delete();
        return redirect()->route('room.index', $house_id)->with('success', 'Room has been deleted successfully');
    }

    public function deleteMultiple(Request $request)
    {
        $selectedRooms = $request->selectedRoom;

        foreach ($selectedRooms as $room) {
            $room = Room::find($room);
            $room->delete();
        }

        return redirect()->back()->with('success', 'Rooms have been deleted successfully');
    }

    public function search(Request $request, $id)
    {
        $search = $request->search;
        $status = $request->status;

        if ($search == null && $status == null) {
            return redirect()->route('room.index', $id);
        } else {

            $rooms = Room::where('room_name', 'like', '%' . $search . '%')->where('house_id', $id)->paginate(20);

            if ($status == 2) { // room status == Occupied
                $rooms = Room::where('room_name', 'like', '%' . $search . '%')->where('house_id', $id)->where('status', 1)->paginate(20);
            } else if ($status == 1) { // room status == Available
                $rooms = Room::where('room_name', 'like', '%' . $search . '%')->where('house_id', $id)->where('status', 0)->paginate(20);
            }

            if ($rooms->count() == 0) {
                $rooms = Room::join('tb_rental_room', 'tb_rental_room.room_id', '=', 'tb_rooms.room_id')
                    ->join('tb_main_tenants', 'tb_main_tenants.tenant_id', '=', 'tb_rental_room.tenant_id')
                    ->where('tb_main_tenants.fullname', 'like', '%' . $search . '%')
                    ->where('tb_rooms.house_id', $id)
                    ->paginate(20);
            }

            $tenants = Tenant::where('user_id', auth()->user()->id)->where('status', 0)->get();
            $serviceUsed = DB::table('tb_services_used')
                ->join('tb_services', 'tb_services.service_id', '=', 'tb_services_used.service_id')
                ->join('tb_type_service', 'tb_type_service.type_id', '=', 'tb_services.type_id')
                ->join('tb_rental_room', 'tb_rental_room.rental_room_id', '=', 'tb_services_used.rental_room_id')
                ->join('tb_rooms', 'tb_rooms.room_id', '=', 'tb_rental_room.room_id')
                ->join('tb_main_tenants', 'tb_main_tenants.tenant_id', '=', 'tb_rental_room.tenant_id')
                ->get();

            return view('dashboard.room.index', compact(
                [
                    'rooms',
                    'tenants',
                    'serviceUsed',
                    'id',
                    'search',
                    'status'
                ]
            ))->with('title', 'Room Management');
        }
    }

    public function assignTenant($id)
    {
        $room = Room::find($id);
        $tenants = Tenant::where('user_id', auth()->user()->id)->where('status', '!=', 1)->get();
        $services = Services::where('user_id', auth()->user()->id)->get();
        // dd($room);
        return view('dashboard.room.assign-tenant', compact(['room', 'tenants', 'services']))->with('title', 'Assign Tenant');
    }

    public function assignTenant_action(Request $request, $id)
    {

        // validate
        $request->validate([
            'fullname' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'id_card' => 'required',
            'phone' => 'required',
            'hometown' => 'required',
            'start_date' => 'required',
        ]);

        DB::beginTransaction();

        try {
            if ($request->tenant_id == null) {
                // check phone number exists
                $checkPhone = Tenant::where('phone_number', $request->phone)->first();
                $checkEmail = Tenant::where('email', $request->email)->first();
                $checkIdCard = Tenant::where('id_card', $request->id_card)->first();

                if ($checkPhone) {
                    return redirect()->back()->with('error', 'Phone number already exists')->withInput($request->all());
                } elseif ($checkEmail) {
                    return redirect()->back()->with('error', 'Email already exists')->withInput($request->all());
                } elseif ($checkIdCard) {
                    return redirect()->back()->with('error', 'ID card already exists')->withInput($request->all());
                } else {
                    // add new tenant
                    $tenant = new Tenant();
                    $tenant->fullname = $request->fullname;
                    $tenant->gender = $request->gender;
                    $tenant->dob = $request->dob;
                    $tenant->id_card = $request->id_card;
                    $tenant->phone_number = $request->phone;
                    $tenant->email = $request->email;
                    $tenant->hometown = $request->hometown;
                    $tenant->status = 1;
                    $tenant->user_id = auth()->user()->id;
                    $tenant->save();
                }
            } else {
                // update status of tenant to 1, means it is rented
                $tenant = Tenant::find($request->tenant_id);
                $tenant->status = 1;
                $tenant->save();
            }

            // update status of room to 1, means it is rented
            $room = Room::find($id);
            $room->status = 1;
            $room->save();

            // add data to rental room
            $rental = new RentalRoom();
            $rental->room_id = $id;
            $rental->tenant_id = $tenant->tenant_id;
            $rental->start_date = $request->start_date;
            // $rental->end_date = $request->end_date;
            $rental->save();

            // add data to services
            $selectedServices = $request->input('selectService');
            $serviceID = $request->input('serviceID');
            $servicePrice = $request->input('servicePrice');
            $roomID = $request->input('roomID');

            for ($i = 0; $i < count($serviceID); $i++) {
                if (in_array($serviceID[$i], $selectedServices)) {
                    DB::table('tb_services_used')->insert([
                        'service_id' => $serviceID[$i],
                        'rental_room_id' => $rental->rental_room_id,
                        'price_if_changed' => $servicePrice[$i]
                    ]);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();
        return redirect()->route('room.index', $room->house_id)->with('success', 'Room has been assigned successfully');
    }

    public function editServicesUsed($roomID, $rentalID)
    {
        $rental = RentalRoom::find($rentalID);
        $services = Services::where('user_id', auth()->user()->id)->get();
        return view('dashboard.room.update-tenant-room', compact(['rental', 'services']))->with('title', 'Update Tenant Room');
    }

    public function editServicesUsed_action(Request $request, $houseID, $rentalID)
    {
        $selectedServices = $request->input('selectService');
        $serviceID = $request->input('serviceID');
        $servicePrice = $request->input('servicePrice');

        for ($i = 0; $i < count($serviceID); $i++) {
            if (in_array($serviceID[$i], $selectedServices)) {

                // check exists
                $check = DB::table('tb_services_used')->where('service_id', $serviceID[$i])->where('rental_room_id', $rentalID)->first();

                if (!$check) {
                    DB::table('tb_services_used')->insert([
                        'service_id' => $serviceID[$i],
                        'rental_room_id' => $rentalID,
                        'price_if_changed' => $servicePrice[$i]
                    ]);
                } else {
                    DB::table('tb_services_used')->where('service_id', $serviceID[$i])->where('rental_room_id', $rentalID)->update([
                        'price_if_changed' => $servicePrice[$i]
                    ]);
                }
            } else {
                DB::table('tb_services_used')->where('service_id', $serviceID[$i])->where('rental_room_id', $rentalID)->delete();
            }
        }

        return redirect()->route('room.index', $houseID)->with('success', 'Room has been assigned successfully');
        // dd($request->all());
    }

    public function assignMembers(Request $request)
    {
        $mainTenantID = $request->main_tenant_id;

        $fullname = $request->input('fullname');
        $id_card = $request->input('id_card');
        $phone_number = $request->input('phone');
        $email = $request->input('email');
        $hometown = $request->input('hometown');
        $gender = $request->input('gender');
        $dob = $request->input('dob');

        for ($i = 0; $i < count($fullname); $i++) {
            $members = new Members();
            $members->fullname = $fullname[$i];
            $members->id_card = $id_card[$i];
            $members->phone_number = $phone_number[$i];
            $members->email = $email[$i];
            $members->hometown = $hometown[$i];
            $members->gender = $gender[$i];
            $members->dob = $dob[$i];
            $members->tenant_id = $mainTenantID;

            $id_card_front_name = null;
            $id_card_back_name = null;

            try {
                $id_card_front = $request->file('idcard_front')[$i];
                $id_card_back = $request->file('idcard_back')[$i];

                $id_card_front_name = 'front - ' .  $fullname[$i] . time() . $id_card[$i] . '.' . $id_card_front->getClientOriginalExtension();
                $id_card_back_name = 'back - ' . $fullname[$i] . time() . $id_card[$i] . '.' . $id_card_back->getClientOriginalExtension();

                $id_card_front->move(public_path('uploads/members/id_card_front'), $id_card_front_name);
                $id_card_back->move(public_path('uploads/members/id_card_back'), $id_card_back_name);
            } catch (\Exception $e) {
                DB::rollBack();
            }

            $members->citizen_card_front_image = $id_card_front_name;
            $members->citizen_card_back_image = $id_card_back_name;

            $members->save();
        }
        return redirect()->back()->with('success', 'Members has been added successfully');
    }

    public function exportRooms($houseID)
    {
        return Excel::download(new ExportRooms($houseID), 'rooms.xlsx');
    }
}
