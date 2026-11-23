<?php

namespace App\Http\Controllers;

use App\Models\SensorLog;
use App\Models\Device;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PerangkatController extends Controller
{
    public function index(): View
    {
        $terakhir = SensorLog::latest('recorded_at')->first();

        $online = $terakhir && $terakhir->recorded_at->gt(now()->subMinutes(10));

        $device = Device::latest()->paginate(5);

        return view('perangkat.index', [
            'perangkat' => $device,
            'online' => $online,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_name'     => ['required', 'string', 'max:255'],
            'esp32_topic'     => ['required', 'string', 'max:255'],
            'gsr_topic'       => ['required', 'string', 'max:255'],
            'heartrate_topic' => ['required', 'string', 'max:255'],
            'temp_topic'      => ['required', 'string', 'max:255'],
        ]);

        $device = Device::create($validated);

        return redirect()->route('perangkat.index')
            ->with('Sukses', $device->device_name . ' berhasil ditambahkan');
    }

    public function update(Request $request, Device $perangkat)
    {
        $validated = $request->validate([
            'device_name'     => ['required', 'string', 'max:255'],
            'esp32_topic'     => ['required', 'string', 'max:255'],
            'gsr_topic'       => ['required', 'string', 'max:255'],
            'heartrate_topic' => ['required', 'string', 'max:255'],
            'temp_topic'      => ['required', 'string', 'max:255'],
        ]);

        $perangkat->update($validated);

        return redirect()->route('perangkat.index')
            ->with('Sukses', $perangkat->device_name . ' berhasil diperbarui');
    }

    public function destroy(Device $perangkat)
    {
        $perangkat->delete();

        return redirect()->route('perangkat.index')
            ->with('Sukses', $perangkat->device_name . ' berhasil dihapus');
    }
}
