<?php

namespace App\Http\Controllers\Calculation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Water;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportWaterBill;

class WaterController extends Controller
{
    // water bill page
    public function water_bill($date, $house_id)
    {
        if ($house_id == "all-house") {
            // get all rooms in the house of the user where the room is occupied
            $dataList = DB::table('tb_services_used')
                ->join('tb_services', 'tb_services.service_id', '=', 'tb_services_used.service_id')
                ->join('tb_type_service', 'tb_type_service.type_id', '=', 'tb_services.type_id')
                ->join('tb_user', 'tb_user.id', '=', 'tb_services.user_id')
                ->join('tb_rental_room', 'tb_rental_room.rental_room_id', '=', 'tb_services_used.rental_room_id')
                ->join('tb_rooms', 'tb_rooms.room_id', '=', 'tb_rental_room.room_id')
                ->join('tb_house', 'tb_house.house_id', '=', 'tb_rooms.house_id')
                ->join('tb_main_tenants', 'tb_main_tenants.tenant_id', '=', 'tb_rental_room.tenant_id')
                ->where('tb_services.type_id', 2)
                ->where('tb_house.user_id', auth()->user()->id)
                ->orderBy('tb_house.house_id', 'asc')
                ->get();
        } else {
            // get all rooms in the house of the user where the room is occupied
            $dataList = DB::table('tb_services_used')
                ->join('tb_services', 'tb_services.service_id', '=', 'tb_services_used.service_id')
                ->join('tb_type_service', 'tb_type_service.type_id', '=', 'tb_services.type_id')
                ->join('tb_user', 'tb_user.id', '=', 'tb_services.user_id')
                ->join('tb_rental_room', 'tb_rental_room.rental_room_id', '=', 'tb_services_used.rental_room_id')
                ->join('tb_rooms', 'tb_rooms.room_id', '=', 'tb_rental_room.room_id')
                ->join('tb_house', 'tb_house.house_id', '=', 'tb_rooms.house_id')
                ->join('tb_main_tenants', 'tb_main_tenants.tenant_id', '=', 'tb_rental_room.tenant_id')
                ->where('tb_services.type_id', 2)
                ->where('tb_house.user_id', auth()->user()->id)
                ->where('tb_house.house_id', $house_id)
                ->get();
        }

        // get current index if it exists
        $currentIndexes = DB::table('tb_water_bill')
            ->join('tb_rental_room', 'tb_rental_room.rental_room_id', '=', 'tb_water_bill.rental_room_id')
            ->where('tb_water_bill.date', $date)
            ->get();

        // get the old electricity previous month's index
        $previousMonth = ((new Carbon($date))->subMonth())->format('F Y');
        $oldIndexes = DB::table('tb_water_bill')
            ->join('tb_rental_room', 'tb_rental_room.rental_room_id', '=', 'tb_water_bill.rental_room_id')
            ->where('tb_water_bill.date', $previousMonth)
            ->get();

        // get all houses of the user
        $houseList = DB::table('tb_house')
            ->where('user_id', auth()->user()->id)
            ->get();

        return view('dashboard.room-billing.water-bill', compact(['dataList', 'oldIndexes', 'currentIndexes', 'date', 'houseList']))->with('title', 'Water bill');
    }

    public function water_insert(Request $request)
    {
        $rentalRoomID = $request->input('rentalRoomID');
        $oldIndexWater = $request->input('oldIndex_water');
        $newIndexWater = $request->input('newIndex_water');
        $date = $request->input('date');

        $data = [];

        for ($i = 0; $i < count($rentalRoomID); $i++) {
            if ($oldIndexWater[$i] != null && $newIndexWater[$i] != null && $oldIndexWater[$i] < $newIndexWater[$i]) {
                $exists = Water::where('rental_room_id', $rentalRoomID[$i])
                    ->where('date', $date)
                    ->first();

                if ($exists) {
                    if ($exists->old_water_index !== $oldIndexWater[$i] || $exists->new_water_index !== $newIndexWater[$i]) {
                        $exists->old_water_index = $oldIndexWater[$i];
                        $exists->new_water_index = $newIndexWater[$i];
                        $exists->save();
                    }
                } else {
                    $data[] = [
                        'rental_room_id' => $rentalRoomID[$i],
                        'old_water_index' => $oldIndexWater[$i],
                        'new_water_index' => $newIndexWater[$i],
                        'date' => $date,
                    ];
                }
            }
        }

        Water::insert($data);
        return redirect()->back()->with('success', 'Saved successfully');

        // dd($request->all());
    }

    public function water_filter(Request $request)
    {
        $date = $request->input('month-filter');
        $house_id = $request->input('house-filter');

        return redirect()->route('water-bill', [$date, $house_id]);
    }

    public function exportWater($date)
    {
        return Excel::download(new ExportWaterBill($date), 'Water - ' . $date . '.xlsx');
    }
}
