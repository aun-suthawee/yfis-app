<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormSelectionController extends Controller
{
    public function index()
    {
        // Define available forms here
        // You can add more forms to this array in the future
        $forms = [
            // [
            //     'title' => 'แจ้งเหตุสาธารณภัย',
            //     'description' => 'แบบฟอร์มสำหรับรายงานเหตุการณ์ภัยพิบัติและขอความช่วยเหลือ',
            //     'route' => 'disaster.create',
            //     'icon' => 'bi-exclamation-triangle-fill', 
            //     'color' => 'danger' 
            // ],
            // [
            //     'title' => 'แจ้งข้อมูลศูนย์พักพิง',
            //     'description' => 'แบบฟอร์มสำหรับรายงานข้อมูลศูนย์พักพิง',
            //     'route' => 'shelters.index',
            //     'icon' => 'bi-house-heart-fill',
            //     'color' => 'info'
            // ],
        ];

        // Logic: If there is only 1 form, redirect directly to it.
        if (count($forms) === 1) {
            return redirect()->route($forms[0]['route']);
        }

        // If there are multiple forms, show the selection page.
        return view('forms.index', compact('forms'));
    }
}
