<?php

namespace App\Services;

use App\Models\EventModel;
use App\Models\PlanModel;
use Illuminate\Support\Facades\DB;

class PlanService
{
    public function moveFinishedEvents()
    {
        DB::transaction(function () {
            $finishedPlans = PlanModel::where('event_selesai', true)->get();

            foreach ($finishedPlans as $plan) {
                // Insert into events table
                EventModel::create([
                    'event_id' => $plan->event_id,
                    'nama_event' => $plan->nama_event,
                    'tanggal_mulai' => $plan->tanggal_mulai,
                    'tanggal_selesai' => $plan->tanggal_selesai,
                    'bulan_event' => $plan->bulan_event,
                    'alamat' => $plan->alamat,
                    'deskripsi' => $plan->deskripsi,
                    'gambar' => $plan->gambar,
                ]);

                // Delete from plans table
                $plan->delete();
            }
        });
    }
}
