@extends('layouts.app')
@section('title', 'Estimate for Application #'.$application->id)
@section('content')

<style>
body {font-family: Arial, sans-serif; font-size:12pt; margin:40px 60px;}
.header {display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:20px;}
.header img {width:120px; height:auto;}
.header-text {display:flex; flex-direction:column; align-items:flex-end; text-align:right; margin-right:40px;}
.header-text h1,p {margin:2px 0;}
.header-text h1 {font-size:18pt;}
.billing {font-weight:bold; margin-top:5px; margin-bottom:5px;}
table {border-collapse: collapse; border:1px solid black; margin-bottom:40px;}
table, th, td {border:1px solid black;}
th, td {padding:6px; text-align:center;}
.table-large {width:75%;}
.table-small {width:40%;}
.remarks {font-size:11pt; margin-top:20px; margin-bottom:20px; padding-left:5px;}
.btn-container {margin-top:30px; text-align:left;}
button {padding:8px 16px; margin-right:10px; font-size:12pt; cursor:pointer;}
@media print {
  @page {size:A4; margin:12mm;}
  body {font-size:11pt; margin:0;}
  .btn-container {display:none;}
  .header img {width:100px;}
  table, th, td {font-size:10pt; padding:4px;}
  .remarks {font-size:10pt;}
  html, body {height:100%; overflow:hidden;}
  a { text-decoration: none; color: black; } /* Remove link styling */
  a::after { content: none !important; } /* Remove URL display */
}

</style>

<div id="billing-content">

  <!-- Header -->
  <div class="header" style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:20px;">
    <img src="{{ asset('NTC_Logo.jpg') }}" alt="Nepal Telecom Logo" style="width:120px; height:auto;">
    <div class="header-text" style="display:flex; flex-direction:column; align-items:flex-end; text-align:right; margin-right:40px;">
      <h1>NEPAL TELECOM</h1>
      <p>(NEPAL DOORSANCHAR COMPANY LTD.)</p>
      <p>VAT No. 300044614</p>
      <p class="billing" style="font-weight:bold;">Billing Statement</p>
      <p>Date: {{ date('Y/m/d') }}</p>
    </div>
  </div>

  <!-- Customer Info -->
  <h3><u>Non-Recurring Charge (One Time Charge)</u></h3>
  <p style="margin-top:10px; margin-bottom:15px;">
    Name : {{ $application->customer_name }}<br><br>
    Address: {{ $application->municipality_perm }}<br><br>
    Customer ID: {{ $application->id }}<br><br>
    Service/Tel No.: {{ $application->mobile }}
  </p>

  <!-- Main Non-Recurring Charges Table -->
  <table class="table-large" style="border-collapse: collapse; border:1px solid black; margin-bottom:40px; width:75%;">
    <tr><th>Particular</th><th>Amount</th><th>VAT 13%</th><th>Local</th></tr>
    <tr><td>Advance</td><td>25000.00</td><td>-</td><td>25000.00</td></tr>
    <tr><td>Registration</td><td>500.00</td><td>-</td><td>500.00</td></tr>
    <tr><td>Installation</td><td>6000.00</td><td>-</td><td>6000.00</td></tr>
    <tr><td>Ownership @ 500 per DN</td><td>500.00</td><td>65.00</td><td>565.00</td></tr>
    <tr><td>Optical Fiber</td><td>-</td><td>-</td><td>-</td></tr>
    <tr><td><b>Grand Total</b></td><td colspan="3"><b>32065.00</b></td></tr>
  </table>

  <!-- Remarks -->
  <p class="remarks" style="font-size:11pt;">Remarks: SIP PBX Type 1, {{ $application->sessions }} session(s) / Existing NTFTTH CPE / OLT Sundhara</p>

  <!-- Recurring Charges -->
  <h3><u>Recurring Rental Charge (Monthly Charges)</u></h3>
  <table class="table-large" style="border-collapse: collapse; border:1px solid black; margin-bottom:40px; width:75%;">
    <tr><th>Particular</th><th>Amount (Per session)</th><th>Amount ({{ $sessions }} session)</th><th>Remarks</th></tr>
    <tr><td>Rental Charge</td><td>{{ $rental }}</td><td>{{ $rental * $sessions }}</td><td></td></tr>
    <tr><td>TSC 10%</td><td>{{ $tsc }}</td><td>{{ $tsc * $sessions }}</td><td></td></tr>
    <tr><td>Total</td><td>{{ $total_per_session }}</td><td>{{ $total_sessions }}</td><td></td></tr>
    <tr><td>VAT 13%</td><td>{{ round($total_per_session * 0.13, 1) }}</td><td>{{ $vat_total }}</td><td></td></tr>
    <tr><td><b>Total</b></td><td><b>{{ round($total_per_session * 1.13, 1) }}</b></td><td><b>{{ $grand_total }}</b></td><td></td></tr>
  </table>

  <p class="remarks">PSTN Rates shall be applied for voice calls.</p>

</div>

<!-- Buttons -->
<div class="btn-container" style="margin-top:30px; text-align:left;">
  <button onclick="window.print()">Print / Download PDF</button>
  <button onclick="sendViaOutlook()">Send via Outlook</button>
  <a href="{{ route('admin.client_apps') }}" class="btn btn-secondary">Go Back</a>
</div>

<script>
function sendViaOutlook() {
  const email = "{{ $application->email }}";
  const subject = encodeURIComponent("Your Billing Estimate from Nepal Telecom");
  const body = encodeURIComponent("Dear {{ $application->customer_name }},\n\nPlease find your billing estimate attached.\n\nDownload your PDF and attach before sending.\n\nThank you,\nNepal Telecom");
  window.location.href = `mailto:${email}?subject=${subject}&body=${body}`;
}
</script>

@endsection
