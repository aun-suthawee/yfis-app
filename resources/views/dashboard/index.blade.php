@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-1 fw-bold text-primary-custom">แดชบอร์ดสถานการณ์ภัยพิบัติ</h1>
        <p class="text-muted mb-0">ข้อมูลเรียลไทม์สำหรับการตัดสินใจเชิงนโยบาย</p>
    </div>

    @include('components.filter-bar', [
        'action' => route('dashboard.index'),
        'method' => 'GET',
        'filters' => $filters,
        'districts' => $districts,
        'affiliations' => $affiliations,
    ])

    <div class="d-flex justify-content-end gap-2 mb-4">
        <a href="{{ route('dashboard.export.pdf', $filters) }}" class="btn btn-outline-danger btn-sm d-flex align-items-center">
            <i class="bi bi-file-pdf me-2"></i> Export PDF
        </a>
        <a href="{{ route('dashboard.export.excel', $filters) }}" class="btn btn-outline-success btn-sm d-flex align-items-center">
            <i class="bi bi-file-excel me-2"></i> Export Excel
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-4 g-3">
                <x-card-stat title="จำนวนหน่วยงานที่ได้รับผลกระทบ" :value="number_format($dashboard['metrics']['affected_units'])" icon="buildings" variant="primary" />
                <x-card-stat title="จำนวนนักเรียนที่ได้รับผลกระทบทั้งหมด" :value="number_format($dashboard['metrics']['total_students_affected'])" icon="people-fill" variant="warning" />
                <x-card-stat title="จำนวนบุคลากรที่ได้รับผลกระทบทั้งหมด" :value="number_format($dashboard['metrics']['total_staff_affected'])" icon="person-lines-fill" variant="info" />
                <x-card-stat title="มูลค่าความเสียหายรวม" :value="number_format($dashboard['metrics']['total_damage'], 2) . ' บาท'" icon="cash" variant="danger" />
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h2 class="h6 mb-0">ค่าเสียหายแยกตามประเภท</h2>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 220px;">
                        <canvas id="damageBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h2 class="h6 mb-0">สัดส่วนประเภทภัยพิบัติ</h2>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 220px;">
                        <canvas id="disasterPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-xl-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h2 class="h6 mb-0">จำนวนเหตุการณ์ตามวันเวลา</h2>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 220px;">
                        <canvas id="timelineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h2 class="h6 mb-0">สถานการณ์ปัจจุบัน</h2>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 220px;">
                        <canvas id="statusDonutChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-xl-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h2 class="h6 mb-0">สถานะการเปิดเรียน</h2>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 220px;">
                        <canvas id="teachingStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h2 class="h6 mb-0">ผลกระทบต่อบุคคล (บาดเจ็บ/เสียชีวิต)</h2>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 220px;">
                        <canvas id="humanImpactChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-xl-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h2 class="h6 mb-0">ความเสียหายรายอำเภอ (บาท)</h2>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 300px;">
                        <canvas id="damageDistrictChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h2 class="h6 mb-0">จำนวนรายงานแยกตามสังกัด</h2>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 300px;">
                        <canvas id="affiliationChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header bg-white border-0">
            <h2 class="h6 mb-0">แผนที่หน่วยงานที่ได้รับผลกระทบ</h2>
        </div>
        <div class="card-body">
            <div id="dashboard-map" style="height: 420px;"></div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
    <style>
        .custom-tooltip .leaflet-tooltip-content {
            font-family: 'Prompt', sans-serif;
            font-size: 0.8rem;
        }
        /* Custom Cluster Colors for Severity */
        .marker-cluster-small {
            background-color: rgba(253, 126, 20, 0.4); /* Orange */
        }
        .marker-cluster-small div {
            background-color: rgba(253, 126, 20, 0.7);
        }
        .marker-cluster-medium {
            background-color: rgba(220, 53, 69, 0.4); /* Red */
        }
        .marker-cluster-medium div {
            background-color: rgba(220, 53, 69, 0.7);
        }
        .marker-cluster-large {
            background-color: rgba(139, 0, 0, 0.4); /* Dark Red */
        }
        .marker-cluster-large div {
            background-color: rgba(139, 0, 0, 0.7);
        }

        /* Increase Cluster Size */
        .marker-cluster {
            background-clip: padding-box;
            border-radius: 30px;
        }
        .marker-cluster div {
            width: 50px;
            height: 50px;
            margin-left: 5px;
            margin-top: 5px;
            text-align: center;
            border-radius: 25px;
            font-family: 'Prompt', sans-serif;
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .marker-cluster span {
            line-height: 50px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Ensure Chart.js is loaded
            if (typeof Chart === 'undefined') {
                console.error('Chart.js library not loaded');
                return;
            }

            // Helper to create charts safely
            const createChart = (id, type, data, options = {}) => {
                const canvas = document.getElementById(id);
                if (!canvas) {
                    console.warn(`Canvas element ${id} not found`);
                    return;
                }
                
                try {
                    // Destroy existing chart if any (though unlikely on page load)
                    const existingChart = Chart.getChart(canvas);
                    if (existingChart) existingChart.destroy();

                    new Chart(canvas, {
                        type: type,
                        data: data,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        boxWidth: 12,
                                        padding: 15,
                                        font: { family: "'Prompt', sans-serif" }
                                    }
                                },
                                tooltip: {
                                    titleFont: { family: "'Prompt', sans-serif" },
                                    bodyFont: { family: "'Prompt', sans-serif" }
                                }
                            },
                            ...options
                        }
                    });
                } catch (e) {
                    console.error(`Error creating chart ${id}:`, e);
                }
            };

            // 1. Damage Bar Chart
            const damageData = @json(array_values($dashboard['damageByCategory']));
            const damageLabels = ['อาคาร', 'ครุภัณฑ์', 'วัสดุ'];
            
            createChart('damageBarChart', 'bar', {
                labels: damageLabels,
                datasets: [{
                    label: 'มูลค่าความเสียหาย (บาท)',
                    data: damageData,
                    backgroundColor: ['#0d6efd', '#20c997', '#ffc107'],
                    borderWidth: 1
                }]
            }, {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('th-TH', { notation: "compact" }).format(value);
                            },
                            font: { family: "'Prompt', sans-serif" }
                        }
                    },
                    x: {
                        ticks: { font: { family: "'Prompt', sans-serif" } }
                    }
                }
            });

            // 2. Disaster Type Pie Chart
            const typeData = @json(array_values($dashboard['disasterTypeTotals']->toArray()));
            const typeLabels = @json(array_keys($dashboard['disasterTypeTotals']->toArray()));

            createChart('disasterPieChart', 'pie', {
                labels: typeLabels,
                datasets: [{
                    data: typeData,
                    backgroundColor: [
                        '#0d6efd', '#6610f2', '#d63384', '#fd7e14', '#198754', '#20c997', '#0dcaf0', '#ffc107'
                    ],
                    borderWidth: 1
                }]
            });

            // 3. Timeline Line Chart
            const timelineLabels = @json(array_keys($dashboard['timeline']->toArray()));
            const timelineData = @json(array_values($dashboard['timeline']->toArray()));

            createChart('timelineChart', 'line', {
                labels: timelineLabels,
                datasets: [{
                    label: 'จำนวนเหตุการณ์',
                    data: timelineData,
                    fill: true,
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderColor: '#0d6efd',
                    tension: 0.3,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            }, {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            });

            // 4. Status Donut Chart
            const statusLabels = @json(array_keys($dashboard['statusBreakdown']->toArray()));
            const statusData = @json(array_values($dashboard['statusBreakdown']->toArray()));
            
            // Map status to colors
            const statusColors = statusLabels.map(status => {
                if (status === 'เสร็จสิ้น') return '#198754'; // Success
                if (status === 'กำลังดำเนินการ') return '#ffc107'; // Warning
                if (status === 'รอดำเนินการ') return '#dc3545'; // Danger
                return '#0dcaf0'; // Info/Default
            });

            createChart('statusDonutChart', 'doughnut', {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: statusColors,
                    borderWidth: 1
                }]
            });

            // 5. Teaching Status Chart
            const teachingDataRaw = @json($dashboard['teachingStatus']);
            const teachingLabels = Object.keys(teachingDataRaw).map(k => k === 'open' ? 'เปิดเรียนปกติ' : 'ปิดเรียน');
            const teachingData = Object.values(teachingDataRaw);

            createChart('teachingStatusChart', 'pie', {
                labels: teachingLabels,
                datasets: [{
                    data: teachingData,
                    backgroundColor: ['#198754', '#dc3545'],
                    borderWidth: 1
                }]
            });

            // 6. Human Impact Chart (Grouped Bar)
            const humanImpact = @json($dashboard['humanImpact']);
            
            createChart('humanImpactChart', 'bar', {
                labels: ['นักเรียน', 'บุคลากร'],
                datasets: [
                    {
                        label: 'บาดเจ็บ',
                        data: [humanImpact.students.injured, humanImpact.staff.injured],
                        backgroundColor: '#ffc107'
                    },
                    {
                        label: 'เสียชีวิต',
                        data: [humanImpact.students.dead, humanImpact.staff.dead],
                        backgroundColor: '#dc3545'
                    }
                ]
            }, {
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            });

            // 7. Damage by District (Horizontal Bar)
            const districtDataRaw = @json($dashboard['damageByDistrict']);
            const districtLabels = Object.keys(districtDataRaw);
            const districtData = Object.values(districtDataRaw);

            createChart('damageDistrictChart', 'bar', {
                labels: districtLabels,
                datasets: [{
                    label: 'มูลค่าความเสียหาย',
                    data: districtData,
                    backgroundColor: '#0d6efd',
                    borderRadius: 4
                }]
            }, {
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('th-TH', { notation: "compact" }).format(value);
                            }
                        }
                    }
                }
            });

            // 8. Reports by Affiliation (Horizontal Bar)
            const affiliationDataRaw = @json($dashboard['reportsByAffiliation']);
            const affiliationLabels = Object.keys(affiliationDataRaw);
            const affiliationData = Object.values(affiliationDataRaw);

            createChart('affiliationChart', 'bar', {
                labels: affiliationLabels,
                datasets: [{
                    label: 'จำนวนรายงาน',
                    data: affiliationData,
                    backgroundColor: '#20c997',
                    borderRadius: 4
                }]
            }, {
                indexAxis: 'y',
                scales: {
                    x: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            });

            // 9. Leaflet Map
            if (typeof L !== 'undefined') {
                const mapElement = document.getElementById('dashboard-map');
                if (mapElement) {
                    // Default view: Yala Province
                    const map = L.map('dashboard-map').setView([6.541, 101.281], 9);
                    
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 18,
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    const points = @json($dashboard['mapPoints']);
                    
                    // Create Marker Cluster Group
                    const markers = L.markerClusterGroup({
                        showCoverageOnHover: true,
                        zoomToBoundsOnClick: true,
                        maxClusterRadius: 50, // Cluster radius in pixels
                        iconCreateFunction: function(cluster) {
                            const childCount = cluster.getChildCount();
                            let c = ' marker-cluster-';
                            if (childCount < 10) {
                                c += 'small';
                            } else if (childCount < 100) {
                                c += 'medium';
                            } else {
                                c += 'large';
                            }

                            return new L.DivIcon({ 
                                html: '<div><span>' + childCount + '</span></div>', 
                                className: 'marker-cluster' + c, 
                                iconSize: new L.Point(60, 60) 
                            });
                        }
                    });

                    points.forEach(point => {
                        if (point.lat && point.lng) {
                            // Determine color based on damage severity
                            let pinColor = '#198754'; // Green (Low)
                            let severityClass = 'text-success';
                            
                            if (point.damage > 1000000) {
                                pinColor = '#dc3545'; // Red (High)
                                severityClass = 'text-danger';
                            } else if (point.damage > 100000) {
                                pinColor = '#fd7e14'; // Orange (Medium)
                                severityClass = 'text-warning';
                            }

                            // Create custom icon
                            const customIcon = L.divIcon({
                                className: 'custom-pin',
                                html: `<i class="bi bi-geo-alt-fill" style="color: ${pinColor}; font-size: 2rem; filter: drop-shadow(0 2px 2px rgba(0,0,0,0.3));"></i>`,
                                iconSize: [32, 32],
                                iconAnchor: [16, 32],
                                popupAnchor: [0, -32]
                            });

                            const marker = L.marker([point.lat, point.lng], { icon: customIcon });
                            
                            const popupContent = `
                                <div class="p-2">
                                    <h6 class="fw-bold mb-1">${point.organization}</h6>
                                    <span class="badge bg-${point.status === 'เสร็จสิ้น' ? 'success' : (point.status === 'กำลังดำเนินการ' ? 'warning' : 'danger')} mb-2">
                                        ${point.status}
                                    </span>
                                    <div class="small text-muted">
                                        มูลค่าความเสียหาย: <span class="fw-bold ${severityClass}">${new Intl.NumberFormat('th-TH').format(point.damage)}</span> บาท
                                    </div>
                                </div>
                            `;
                            
                            marker.bindPopup(popupContent);
                            
                            // Tooltip
                            const tooltipContent = `
                                <div class="text-center">
                                    <strong>${point.organization}</strong><br>
                                    <small class="${severityClass}">เสียหาย: ${new Intl.NumberFormat('th-TH', { notation: "compact" }).format(point.damage)} บ.</small>
                                </div>
                            `;
                            
                            marker.bindTooltip(tooltipContent, {
                                permanent: false,
                                direction: 'top',
                                className: 'custom-tooltip'
                            });

                            markers.addLayer(marker);
                        }
                    });

                    map.addLayer(markers);

                    // Add Shelters Markers
                    const shelters = @json($shelters ?? []);
                    shelters.forEach(shelter => {
                        if (shelter.latitude && shelter.longitude) {
                            const shelterIcon = L.divIcon({
                                className: 'custom-pin-shelter',
                                html: `<i class="bi bi-house-heart-fill" style="color: #198754; font-size: 2rem; filter: drop-shadow(0 2px 2px rgba(0,0,0,0.3));"></i>`,
                                iconSize: [32, 32],
                                iconAnchor: [16, 32],
                                popupAnchor: [0, -32]
                            });

                            const marker = L.marker([shelter.latitude, shelter.longitude], { icon: shelterIcon })
                                .addTo(map);

                            const popupContent = `
                                <div class="p-2">
                                    <h6 class="fw-bold mb-1 text-success"><i class="bi bi-house-heart-fill me-1"></i> ${shelter.name}</h6>
                                    <div class="small text-muted">
                                        ความจุ: <span class="fw-bold text-dark">${new Intl.NumberFormat('th-TH').format(shelter.capacity)}</span> คน
                                    </div>
                                    <div class="mt-2">
                                        <a href="https://www.google.com/maps/search/?api=1&query=${shelter.latitude},${shelter.longitude}" target="_blank" class="btn btn-sm btn-outline-success w-100">
                                            <i class="bi bi-geo-alt"></i> นำทาง
                                        </a>
                                    </div>
                                </div>
                            `;
                            
                            marker.bindPopup(popupContent);
                            marker.bindTooltip(`<strong>${shelter.name}</strong>`, {
                                permanent: false,
                                direction: 'top',
                                className: 'custom-tooltip'
                            });
                        }
                    });
                }
            } else {
                console.error('Leaflet library not loaded');
            }
        });
    </script>
@endpush
