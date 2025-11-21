<!DOCTYPE html>
<html lang="th">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>YFIS Dashboard Summary</title>
    <style>
        @page {
            margin: 100px 50px 80px 50px;
        }
        body { 
            font-family: "dejavusans", sans-serif; 
            font-size: 14pt; 
            color: #333; 
            line-height: 1.5;
        }
        h1 { 
            text-align: center; 
            margin-bottom: 15px; 
            font-size: 18pt;
            font-weight: bold;
        }
        p {
            text-align: center;
            margin-bottom: 10px;
            font-size: 14pt;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            font-size: 12pt;
        }
        th, td { 
            border: 1px solid #999; 
            padding: 6px; 
            text-align: left; 
            vertical-align: top;
        }
        th { 
            background-color: #f0f2f5; 
            font-weight: bold;
        }
        .stats { 
            margin-top: 20px; 
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .stats div { 
            margin-bottom: 8px; 
            font-size: 13pt;
        }
        strong {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>สรุปข้อมูลภัยพิบัติ - Yala Flood Information System</h1>
    <p>จัดทำเมื่อ: {{ now()->timezone('Asia/Bangkok')->format('d/m/Y H:i') }} น.</p>

    <div class="stats">
        <div>จำนวนหน่วยงานที่ได้รับผลกระทบ: <strong>{{ number_format($metrics['affected_units']) }}</strong></div>
        <div>จำนวนนักเรียนที่ได้รับผลกระทบทั้งหมด: <strong>{{ number_format($metrics['total_students_affected']) }}</strong></div>
        <div>จำนวนบุคลากรที่ได้รับผลกระทบทั้งหมด: <strong>{{ number_format($metrics['total_staff_affected']) }}</strong></div>
        <div>มูลค่าความเสียหายรวม: <strong>{{ number_format($metrics['total_damage'], 2) }} บาท</strong></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>วันที่รายงาน</th>
                <th>ประเภทภัยพิบัติ</th>
                <th>หน่วยงาน</th>
                <th>อำเภอ</th>
                <th>สถานะ</th>
                <th>มูลค่าความเสียหายรวม (บาท)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $row)
                <tr>
                    <td>{{ $row['reported_at'] }}</td>
                    <td>{{ $row['disaster_type'] }}</td>
                    <td>{{ $row['organization_name'] }}</td>
                    <td>{{ $row['district'] }}</td>
                    <td>{{ $row['current_status'] }}</td>
                    <td>{{ number_format($row['damage_total_request'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
