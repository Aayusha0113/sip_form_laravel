@extends('layouts.app')
@section('title', 'Nepal Telecom Letter - Application #'.$application->id)
@section('content')

<style>
body { font-family: 'Times New Roman', serif; font-size: 14pt; line-height: 1.6; margin: 20px 60px 40px 80px; color: black; }
.slogan { text-align: right; font-size: 10pt; margin-bottom: 10px; }
.header { display: flex; align-items: center; margin-top: 20px; margin-bottom: 10px; }
.header img { width: 120px; height: auto; margin-right: 15px; }
.header-text { display: flex; flex-direction: column; justify-content: center; font-size: 18pt; font-weight: bold; line-height: 1.2; }
.ref-miti { margin-top: 5px; }
.patra-sankhya, .miti { font-size: 11pt; }
.patra-sankhya { border-bottom: 1px solid black; width: 100%; display: inline-block; padding-bottom: 2px; margin-bottom: 5px; }
.miti { text-align: right; margin-bottom: 20px; }
.content { text-align: justify; margin-top: 10px; }
.subject { text-align: center; font-weight: bold; margin: 20px 0; }
.additional { margin-top: 40px; text-align: justify; }
.signature { margin-top: 80px; text-align: right; font-size: 13pt; }
.bottom-section { margin-top: 60px; font-size: 12pt; line-height: 1.4; }
.underline-full { border-top: 1px solid black; width: 100%; margin-bottom: 15px; }
.middle-text { text-align: center; margin-bottom: 8px; font-weight: normal; }
.footer-flex { display: flex; justify-content: space-between; align-items: flex-start; }
.footer-left { text-align: left; }
.footer-right { text-align: right; }
.buttons { margin-top: 40px; display: flex; flex-wrap: wrap; gap: 15px; }
.buttons button, .buttons a { padding: 8px 15px; font-size: 12pt; cursor: pointer; text-decoration: none; color: black; background-color: #d3d3d3; border: 1px solid black; border-radius: 4px; }
.buttons a { display: inline-block; }

@media print {
  @page { size: A4; margin: 15mm; }
  body { font-size: 12pt; margin: 0; }
  .buttons { display: none; }
  .header img { width: 100px; }
  .content, .additional, .signature, .bottom-section { font-size: 11.5pt; }
  html, body { height: 100%; overflow: hidden; }
}
</style>

<div class="slogan">राष्ट्र निर्माण हाम्रो लक्ष्य</div>

<div class="header">
    <img src="{{ asset('NTC_Logo.jpg') }}" alt="Nepal Telecom Logo">
    <div class="header-text">
        नेपाल टेलिकम<br>
        NEPAL TELECOM
    </div>
</div>

<div class="ref-miti">
    <div class="patra-sankhya">पत्र संख्या: {{ $application->id }}</div>
    <div class="miti">मिति: {{ $application->signature_date }}</div>
</div>

<div class="content">
    <p>
        श्री नेपाल टेलिकम, बा.प्रा.नि.नियन्त्रणालय, कुपन्डोल ।<br>
        श्री नेपाल टेलिकम, प्र.प्र.नि., विशेष ग्यारेज, कुपन्डोल ।<br>
        श्री नेपाल टेलिकम, प्र.प्र.नि., O&M शाखा, कुपन्डोल ।<br>
        श्री नेपाल टेलिकम, प्र.प्र.नि., लिङ्क शाखा, कुपन्डोल ।<br>
        श्री नेपाल टेलिकम, प्र.प्र.नि., CRTB शाखा, कुपन्डोल ।
    </p>

    <p class="subject"><u>विषय: SIP Line जोडिएको बारे जानकारी ।</u></p>

    <p>
        प्रस्तुत विषयमा, श्री {{ $application->customer_name }} को नाममा आफ्नो Company {{ $application->municipality_install }}, {{ $application->district_install }} मा संचालित रहेको SIP PBX No. {{ $application->did }} लाई Cloud Himalaya Data Center, Thapathali, Kathmandu मा स्थापित गरी संचालन गर्न मिल्ने व्यवस्था भएको जानकारीको लागि अनुरोध गरिन्छ ।
    </p>
</div>

<div class="additional">
    <p><u>वोधार्थ तथा कार्यार्थ :</u><br>
       संचालन गरिदिनु हुन आदेशानुसार अनुरोध।<br>
       श्री नेपाल टेलिकम, वायर लाईन तथा ग्राहक सेवा निर्देशनालय, छाउनी ।<br>
       श्री नेपाल टेलिकम, सुचना प्रणाली सहयोगी निर्देशनालय, जावलाखेल ।<br>
       श्री {{ $application->customer_name }}, {{ $application->municipality_install }}, {{ $application->district_install }}.<br>
       <u>{{ $application->email }}</u>
    </p>
</div>

<div class="signature">
    <p>..................................</p>
</div>

<div class="bottom-section">
    <div class="underline-full"></div>
    <div class="middle-text">
        नेपाल दूरसञ्चार कम्पनी लिमिटेडको<br>नाममा दर्ता भएको
    </div>
    <div class="footer-flex">
        <div class="footer-left">
            <div>दूरसञ्चार कार्यालय सुन्धारा</div>
            <div>प्रशासन शाखा</div>
            <div>सुन्धारा, काठगाडा ।</div>
        </div>
        <div class="footer-right">
            <div>कार्यालय प्रमुख: ०१५ ३६ ३१३९</div>
            <div>लेखा प्रमुख: ०१५३३३०००</div>
            <div>प्रशासन प्रमुख: ०१३६८४१६</div>
            <div>Email: krd.sundhara@ante.net.np</div>
        </div>
    </div>
</div>

<div class="buttons">
    <button onclick="window.print()">Print/Download</button>
    <a href="{{ route('admin.client_apps') }}">Go Back to Admin Page</a>
    <form method="POST" action="{{ route('admin.send_letter_email', $application->id) }}" style="display:inline;">
        @csrf
        <button type="submit">Send via Email</button>
    </form>
</div>

@endsection
