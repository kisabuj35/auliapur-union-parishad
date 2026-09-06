<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>আবেদনপত্র - {{ $record->app_id }}</title>
  <link href="https://fonts.maateen.me/nikosh/font.css" rel="stylesheet">
  <style>
    @page {
      size: A4 portrait;
      margin: 10mm 15mm !important;
    }
    * {
      box-sizing: border-box;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
    }
    body {
      margin: 0;
      padding: 0;
      background: #ffffff;
      color: #000000;
      font-family: 'Nikosh', sans-serif;
      font-size: 15px;
      line-height: 1.8;
    }
    .app-paper {
      width: 100%;
      max-width: 190mm;
      margin: 0 auto;
    }
    .header-box {
      border-bottom: 2px solid #006837;
      padding-bottom: 8px;
      margin-bottom: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .header-title {
      color: #006837;
      font-size: 24px;
      font-weight: bold;
      margin: 0;
    }
    .app-table {
      width: 100%;
      border-collapse: collapse;
      margin: 15px 0;
    }
    .app-table th, .app-table td {
      border: 1px solid #333;
      padding: 6px 10px;
      font-size: 14.5px;
    }
    .app-table th {
      background-color: #f1f5f9;
      width: 30%;
      text-align: left;
    }
    .sign-section {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      margin-top: 60px;
      text-align: center;
      font-size: 14px;
      font-weight: bold;
    }
    .sign-block {
      width: 28%;
      border-top: 1.5px dashed #000;
      padding-top: 5px;
    }
  </style>
</head>
<body onload="window.print();">

  @php
    function toBn($num) {
      $en = ['0','1','2','3','4','5','6','7','8','9'];
      $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
      return str_replace($en, $bn, $num);
    }
    $applicantName = $record->name ?? $record->applicant_name ?? $record->owner_name ?? '-';
    $fatherName = $record->father_name ?? $record->father_spouse ?? '-';
    $serviceName = $record->type ?? $record->certificate_type ?? (isset($record->license_no) ? 'ট্রেড লাইসেন্স' : (isset($record->deceased_name) ? 'ওয়ারিশান সনদ' : 'নাগরিকত্ব সনদ'));
  @endphp

  <div class="app-paper">
    <div class="header-box">
      <div>
        <h2 class="header-title">১১নং আউলিয়াপুর ইউনিয়ন পরিষদ কার্যালয়</h2>
        <small>পটুয়াখালী সদর, পটুয়াখালী | ই-সেবা আবেদনপত্র</small>
      </div>
      <div style="text-align: right;">
        <span style="background: #dc2626; color: #fff; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 12px;">অনলাইন দাখিলকৃত</span>
      </div>
    </div>

    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-weight: bold;">
      <div>ট্র্যাকিং আইডি: <span style="color: #0b5bc5; font-size: 16px;">{{ $record->app_id }}</span></div>
      <div>তারিখ: {{ $record->apply_date ?? $record->date ?? date('d/m/Y') }} ইং</div>
    </div>

    <div>
      <p style="margin: 0; font-weight: bold;">বরাবর,</p>
      <p style="margin: 0;">চেয়ারম্যান মহোদয়,</p>
      <p style="margin: 0;">১১নং আউলিয়াপুর ইউনিয়ন পরিষদ কার্যালয়, পটুয়াখালী সদর, পটুয়াখালী।</p>
      <p style="margin: 8px 0; font-weight: bold; text-decoration: underline; color: #006837;">
        বিষয়: {{ $serviceName }} পাওয়ার জন্য আবেদনপত্র।
      </p>
      <p style="text-align: justify; margin-bottom: 10px;">
        জনাব, বিনীত নিবেদন এই যে, আমি আপনার ইউনিয়নের একজন স্থায়ী বাসিন্দা। আমার ব্যক্তিগত ও নাগরিক প্রয়োজনে নিম্নবর্ণিত তথ্য অনুযায়ী আমাকে একটি <strong>{{ $serviceName }}</strong> প্রদান করতে মর্জি হয়।
      </p>
    </div>

    <table class="app-table">
      <tr>
        <th>সেবার ধরন</th>
        <td><strong>{{ $serviceName }}</strong></td>
      </tr>
      <tr>
        <th>আবেদনকারীর নাম</th>
        <td><strong>{{ $applicantName }}</strong></td>
      </tr>
      <tr>
        <th>পিতা / স্বামীর নাম</th>
        <td>{{ $fatherName }}</td>
      </tr>
      @if(!empty($record->mother_name))
      <tr>
        <th>মাতার নাম</th>
        <td>{{ $record->mother_name }}</td>
      </tr>
      @endif
      <tr>
        <th>জাতীয় পরিচয়পত্র (NID)</th>
        <td>{{ toBn($record->nid ?? '-') }}</td>
      </tr>
      <tr>
        <th>মোবাইল নম্বর</th>
        <td>{{ toBn($record->mobile ?? '-') }}</td>
      </tr>
      <tr>
        <th>ঠিকানা</th>
        <td>
          ওয়ার্ড নং: {{ toBn($record->ward_no ?? '-') }}, গ্রাম: {{ $record->village ?? '-' }}, ডাকঘর: {{ $record->post_office ?? 'আউলিয়াপুর ময়দান' }}
        </td>
      </tr>
      @if(!empty($record->org_name))
      <tr>
        <th>প্রতিষ্ঠানের নাম ও ঠিকানা</th>
        <td>{{ $record->org_name }} ({{ $record->biz_address ?? '-' }})</td>
      </tr>
      @endif
      @if(!empty($record->deceased_name))
      <tr>
        <th>মৃত ব্যক্তির নাম</th>
        <td>{{ $record->deceased_name }} (মৃত্যুর তারিখ: {{ toBn($record->deceased_date ?? '-') }})</td>
      </tr>
      @endif
    </table>

    <p style="font-size: 13.5px; font-weight: bold; margin: 10px 0;">
      * অঙ্গীকারনামা: আমি ঘোষণা করছি যে, উপরে উল্লেখিত সকল তথ্য সম্পূর্ণ সত্য ও সঠিক। কোনো ভুল তথ্য প্রমাণিত হলে কর্তৃপক্ষ দায়ী থাকবে না।
    </p>

    <!-- স্বাক্ষর ব্লক -->
    <div class="sign-section">
      <div class="sign-block">
        গ্রাম পুলিশ<br><small style="font-weight: normal;">১১নং আউলিয়াপুর ইউ.পি</small>
      </div>
      <div class="sign-block">
        ইউপি সদস্য<br><small style="font-weight: normal;">১১নং আউলিয়াপুর ইউ.পি</small>
      </div>
      <div class="sign-block">
        {{ $applicantName }}<br><small style="font-weight: normal;">আবেদনকারীর স্বাক্ষর</small>
      </div>
    </div>
  </div>

</body>
</html>
