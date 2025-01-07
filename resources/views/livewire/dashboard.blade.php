<div>
    <div class="content-header">Dashboard</div>
    
        <div class="flex gap-4">
            <div id="incomeChart" class="mt-4 bg-white p-4 rounded-lg w-2/3"></div>
            {{-- <div id="pieChart" class="mt-4 bg-white p-4 rounded-lg w-1/3"></div> --}}
        </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('livewire:init', function() {
        // กำหนดค่าเริ่มต้น
        var stationList = @json($stationList);  // สถานีที่กำหนดล่วงหน้า
        var seriesData = [];  // ข้อมูล series สำหรับกราฟ
        var weeks = [];       // รายการ weeks ที่ใช้ใน legend
        
        // สร้างข้อมูลกราฟแยกตาม week
        @foreach($incomeData as $week => $data)
            weeks.push('Week ' + {{ $week }});  // เพิ่มชื่อ week ใน legend
            var weekData = [];
            stationList.forEach(function(station) {
                // หากสถานีมีข้อมูลในสัปดาห์นั้น เพิ่มค่า total_fault_sum, หากไม่มีให้เป็น 0
                var fault = @json($incomeData)[{{ $week }}].find(function(entry) {
                    return entry.station === station;
                })?.total_fault_sum || 0;
                
                weekData.push(fault);
            });
            seriesData.push({
                name: 'Week ' + {{ $week }},
                data: weekData
            });
        @endforeach

        seriesData.sort((a, b) =>{
        const weekA = parseInt(a.name.replace('Week ', '')); // ดึงเลขจากชื่อ Week
        const weekB = parseInt(b.name.replace('Week ', '')); // ดึงเลขจากชื่อ Week
        return weekA - weekB; // เรียงลำดับจากน้อยไปมาก
        });

        // กำหนด options สำหรับ ApexCharts
        var options = {
            chart: {
                type: 'bar',
                height: 350,
                stacked: true,
            },
            series: seriesData,  // ใช้ข้อมูล series ที่มีหลาย week
            xaxis: {
                categories: stationList  // ใช้สถานีที่กำหนดล่วงหน้า
            },yaxis: {
        labels: {
            formatter: function (val) {
                return Math.floor(val); // แสดงตัวเลขเป็นจำนวนเต็ม
            }
        },
        tickAmount: Math.ceil(Math.max(...seriesData.flatMap(s => s.data))) || 5, // คำนวณจำนวนช่วงอัตโนมัติ
        min: 0 // เริ่มต้นที่ 0
    },
            title: {
                
                text: 'Faults of station 2024 Zone 2',
                align: 'center'

            },
            legend: {
        position: 'right',
        horizontalAlign: 'center', // ตั้งค่าให้อยู่กลาง
        floating: false,
        itemMargin: {
            vertical: 5
        },
        height: 150, // กำหนดความสูงให้ legend (ความสูงที่มี scroll bar)
        onItemClick: {
            toggleDataSeries: true // เปิด/ปิด series จาก legend ได้
        }
    },
    grid: {
        padding: {
            right: 20
        }
    }
        }

        var chart = new ApexCharts(document.querySelector("#incomeChart"), options);
        chart.render();
    });

   
</script>
@endpush