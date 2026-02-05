<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\DashboardCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationFormController extends Controller
{
    public function index()
    {
        return view('form.index');
    }

    public function submit(Request $request)
    {
        $sip = $this->normalizeSipNumber($request->input('sip_number', ''));
        $customerName = trim($request->input('customer_name', ''));

        if (!$sip || !$customerName) {
            return response('SIP Number and Company Name are required!', 422);
        }

        if (DashboardCompany::where('sip_number', $sip)->exists()) {
            return response('SIP Number already exists!', 409);
        }

        $permAddress = $this->formatAddress(
            $request->input('province_perm'),
            $request->input('district_perm'),
            $request->input('municipality_perm'),
            $request->input('ward_perm'),
            $request->input('tole_perm')
        );
        $instAddress = $this->formatAddress(
            $request->input('province_install'),
            $request->input('district_install'),
            $request->input('municipality_install'),
            $request->input('ward_install'),
            $request->input('tole_install')
        );

        $appData = [
            'customer_name'       => $customerName,
            'customer_type'       => $this->emptyToNull($request->input('customer_type')),
            'sip_type'            => $this->emptyToNull($request->input('sip_type')),
            'sessions'            => $request->filled('sessions') ? (int) $request->sessions : null,
            'did'                 => $request->filled('did') ? (int) $request->did : null,
            'status'              => 'pending',
            'name_of_proprietor'  => $this->emptyToNull($request->input('name_of_proprietor')),
            'company_reg_no'      => $this->emptyToNull($request->input('company_reg_no')),
            'reg_date'            => $this->emptyToNull($request->input('reg_date')),
            'pan_no'              => $this->emptyToNull($request->input('pan_no')),
            'province_perm'       => $this->emptyToNull($request->input('province_perm')),
            'district_perm'       => $this->emptyToNull($request->input('district_perm')),
            'municipality_perm'   => $this->emptyToNull($request->input('municipality_perm')),
            'ward_perm'           => $this->emptyToNull($request->input('ward_perm')),
            'tole_perm'           => $this->emptyToNull($request->input('tole_perm')),
            'province_install'    => $this->emptyToNull($request->input('province_install')),
            'district_install'    => $this->emptyToNull($request->input('district_install')),
            'municipality_install'=> $this->emptyToNull($request->input('municipality_install')),
            'ward_install'        => $this->emptyToNull($request->input('ward_install')),
            'tole_install'        => $this->emptyToNull($request->input('tole_install')),
            'landline'            => $this->emptyToNull($request->input('landline')),
            'mobile'              => $this->emptyToNull($request->input('mobile')),
            'email'               => $this->emptyToNull($request->input('email')),
            'website'             => $this->emptyToNull($request->input('website')),
            'objectives'          => $this->emptyToNull($request->input('objectives')),
            'purpose'             => $this->emptyToNull($request->input('purpose')),
            'authorized_signature'=> $this->emptyToNull($request->input('authorized_signature')),
            'signature_name'      => $this->emptyToNull($request->input('signature_name')),
            'position'            => $this->emptyToNull($request->input('position')),
            'signature_date'      => $this->emptyToNull($request->input('signature_date')),
            'seal'                => $this->emptyToNull($request->input('seal')),
        ];

        $companyData = [
            'sip_number'          => $sip,
            'DN'                  => $this->emptyToNull($request->input('service_dn')),
            'company_name'        => $customerName,
            'customer_type'       => $appData['customer_type'],
            'proprietor_name'     => $appData['name_of_proprietor'],
            'company_reg_no'      => $appData['company_reg_no'],
            'reg_date'            => $appData['reg_date'],
            'pan_no'              => $appData['pan_no'],
            'address_perm'        => $permAddress,
            'address_install'     => $instAddress,
            'landline'            => $appData['landline'],
            'mobile'              => $appData['mobile'],
            'email'               => $appData['email'],
            'website'             => $appData['website'],
            'sip_type'            => $appData['sip_type'],
            'sessions'            => $appData['sessions'],
            'did'                 => $appData['did'],
            'objectives'          => $appData['objectives'],
            'purpose'             => $appData['purpose'],
            'authorized_signature'=> $appData['authorized_signature'],
            'signature_name'      => $appData['signature_name'],
            'position'            => $appData['position'],
            'signature_date'      => $appData['signature_date'],
            'seal'                => $appData['seal'],
            'perm_province'       => $appData['province_perm'],
            'perm_district'       => $appData['district_perm'],
            'perm_municipality'   => $appData['municipality_perm'],
            'perm_ward'           => $appData['ward_perm'],
            'perm_tole'           => $appData['tole_perm'],
            'inst_province'       => $appData['province_install'],
            'inst_district'       => $appData['district_install'],
            'inst_municipality'   => $appData['municipality_install'],
            'inst_ward'           => $appData['ward_install'],
            'inst_tole'           => $appData['tole_install'],
        ];

        DB::transaction(function () use ($appData, $companyData) {
            Application::create($appData);
            DashboardCompany::create($companyData);
        });

        return response('SUCCESS', 200);
    }

    private function emptyToNull($value)
    {
        $t = trim($value ?? '');
        return $t === '' ? null : $t;
    }

    private function formatAddress($province, $district, $municipality, $ward, $tole)
    {
        $segments = [];
        foreach ([$province, $district, $municipality, $ward ? 'Ward ' . trim($ward) : '', $tole] as $p) {
            $v = trim($p ?? '');
            if ($v === '') continue;
            $segments[] = $v;
        }
        return $segments ? implode(', ', $segments) : null;
    }

    private function normalizeSipNumber($sip)
    {
        $sip = trim($sip ?? '');
        if ($sip === '') return '';
        $sip = preg_replace('/^sip\s*/i', '', $sip);
        return 'SIP' . $sip;
    }
}
