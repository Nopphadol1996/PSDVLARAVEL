<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Weekly;
use Illuminate\Support\Facades\DB;



class Dashboard extends Component {

    public $incomeData = []; // รายการข้อมูลที่แยกตาม week
    public $stationList = [ 'CEN-SKT','CEN-SLM', 'E6', 'E9', 'S2', 'S3', 'S5'];

    public function mount() {
        $incomeData = Weekly::select('station', 'week', DB::raw('SUM(total_fault) as total_fault_sum'))
                            ->groupBy('station', 'week')
                            ->get();

        $this->incomeData = $incomeData->groupBy('week')->toArray();

    }

    public function render() {
        return view('livewire.dashboard');
    }
}