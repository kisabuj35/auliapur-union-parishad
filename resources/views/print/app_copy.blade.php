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
    .trade-app-header {
      border: 1.5px solid #006837;
      border-radius: 4px;
      padding: 10px 14px;
      margin-bottom: 10px;
      text-align: center;
    }
    .trade-app-header h2 {
      margin: 0;
      color: #006837;
      font-size: 23px;
      line-height: 1.3;
    }
    .trade-app-header p { margin: 2px 0 0; font-size: 13px; }
    .trade-app-meta {
      display: flex;
      justify-content: space-between;
      gap: 12px;
      padding: 6px 10px;
      margin-bottom: 10px;
      border-top: 1px solid #cbd5e1;
      border-bottom: 1px solid #cbd5e1;
      font-size: 13px;
      font-weight: bold;
    }
    .trade-applicant-summary {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0 20px;
      border: 1px solid #64748b;
      padding: 7px 10px;
      margin-bottom: 12px;
      font-size: 14px;
      line-height: 1.55;
    }
    .trade-applicant-summary div { border-bottom: 1px dotted #94a3b8; padding: 2px 0; }
    .trade-applicant-summary div:nth-last-child(-n+2) { border-bottom: 0; }
    .trade-app-letter { margin-bottom: 9px; font-size: 14px; line-height: 1.6; }
    .trade-app-letter p { margin: 0; }
    .trade-app-subject { margin-top: 5px !important; font-weight: bold; text-decoration: underline; color: #006837; }
    .trade-app-intro { margin-top: 4px !important; text-align: justify; }
    .trade-app-section-title {
      background: #006837;
      color: #fff;
      padding: 5px 8px;
      font-size: 15px;
      font-weight: bold;
      text-align: center;
    }
    .trade-app-table {
      width: 100%;
      border-collapse: collapse;
      margin: 0;
    }
    .trade-app-table th, .trade-app-table td {
      border: 1px solid #64748b;
      padding: 4px 7px;
      font-size: 13px;
      line-height: 1.45;
      vertical-align: top;
    }
    .trade-app-table th { width: 34%; background: #f0fdf4; text-align: left; }
    .trade-app-columns {
      display: grid;
      grid-template-columns: 1.25fr 0.9fr;
      gap: 10px;
      margin-top: 10px;
      align-items: start;
    }
    .trade-app-panel { border: 1px solid #64748b; }
    .trade-app-panel .trade-app-table th,
    .trade-app-panel .trade-app-table td { border-left: 0; border-right: 0; }
    .trade-app-panel .trade-app-table tr:first-child th,
    .trade-app-panel .trade-app-table tr:first-child td { border-top: 0; }
    .trade-app-panel .trade-app-table tr:last-child th,
    .trade-app-panel .trade-app-table tr:last-child td { border-bottom: 0; }
    .trade-app-total th, .trade-app-total td { background: #fff7ed !important; font-weight: bold; font-size: 14px !important; }
    .trade-app-discount th, .trade-app-discount td { color: #b91c1c; }
    .trade-app-note { margin: 9px 0 0; font-size: 12.5px; line-height: 1.5; text-align: justify; }
    @media print {
      .trade-app-columns { break-inside: avoid; }
      .trade-app-panel { break-inside: avoid; }
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

  @if(isset($record->license_no))
  <div class="app-paper">
    <div class="trade-app-header">
      <h2>১১নং আউলিয়াপুর ইউনিয়ন পরিষদ কার্যালয়</h2>
      <p>পটুয়াখালী সদর, পটুয়াখালী</p>
      <p><strong>ট্রেড লাইসেন্স {{ $record->is_renewal ? 'নবায়ন' : 'ইস্যু' }}-এর আবেদনপত্র</strong></p>
    </div>

    <div class="trade-app-meta">
      <span>আবেদন আইডি: <strong style="color:#0b5bc5;">{{ $record->app_id }}</strong></span>
      <span>আবেদনের ধরন: {{ $record->is_renewal ? 'নবায়ন' : 'নতুন লাইসেন্স' }}</span>
      <span>তারিখ: {{ $record->apply_date ?? date('d/m/Y') }} ইং</span>
    </div>

    <div class="trade-applicant-summary">
      <div><strong>আবেদনকারীর নাম:</strong> {{ $record->owner_name }}</div>
      <div><strong>পিতার নাম:</strong> {{ $record->father_name ?? '-' }}</div>
      <div><strong>ঠিকানা:</strong> {{ $record->owner_address ?? '-' }}</div>
      <div><strong>এনআইডি নম্বর:</strong> {{ toBn($record->nid ?? '-') }}</div>
      <div><strong>ফোন নম্বর:</strong> {{ toBn($record->mobile ?? '-') }}</div>
      <div><strong>প্রতিষ্ঠানের নাম:</strong> {{ $record->org_name }}</div>
    </div>

    <div class="trade-app-letter">
      <p>বরাবর,</p>
      <p><strong>চেয়ারম্যান মহোদয়</strong></p>
      <p>১১নং আউলিয়াপুর ইউনিয়ন পরিষদ, পটুয়াখালী সদর, পটুয়াখালী।</p>
      <p class="trade-app-subject">বিষয়: ব্যবসা প্রতিষ্ঠানের অনুকূলে ট্রেড লাইসেন্স {{ $record->is_renewal ? 'নবায়ন' : 'ইস্যু' }} প্রসঙ্গে।</p>
      <p class="trade-app-intro">জনাব, যথাবিহিত সম্মানপূর্বক নিবেদন এই যে, নিম্নস্বাক্ষরকারী উল্লিখিত ব্যবসা প্রতিষ্ঠানের জন্য প্রযোজ্য আইন, বিধি ও ইউনিয়ন পরিষদের নির্ধারিত শর্তাবলি মেনে ট্রেড লাইসেন্স {{ $record->is_renewal ? 'নবায়নের' : 'ইস্যুর' }} আবেদন করছি। অতএব, প্রয়োজনীয় তথ্য ও কাগজপত্র যাচাইপূর্বক আবেদনটি সদয় বিবেচনা করে ট্রেড লাইসেন্স {{ $record->is_renewal ? 'নবায়ন' : 'প্রদানের' }} জন্য বিনীত অনুরোধ করছি।</p>
    </div>

    <div class="trade-app-columns">
      <div class="trade-app-panel">
        <div class="trade-app-section-title">ব্যবসা প্রতিষ্ঠানের তথ্য</div>
        <table class="trade-app-table">
          <tr><th>প্রতিষ্ঠানের নাম</th><td><strong>{{ $record->org_name }}</strong></td></tr>
          <tr><th>ব্যবসার ধরন</th><td>{{ $record->category ?? '-' }}</td></tr>
          <tr><th>ব্যবসার প্রকৃতি ও পণ্য/সেবা</th><td>{{ $record->biz_details ?? '-' }}</td></tr>
          <tr><th>স্থায়ী ঠিকানা</th><td>{{ $record->biz_permanent_address ?? $record->biz_address ?? '-' }}</td></tr>
          <tr><th>অস্থায়ী/চলতি ঠিকানা</th><td>{{ $record->biz_present_address ?? $record->biz_address ?? '-' }}</td></tr>
          <tr><th>ব্যবসা শুরুর তারিখ</th><td>{{ $record->biz_start_date ?? '-' }}</td></tr>
          <tr><th>অর্থ বছর</th><td>{{ $record->fiscal_year ?? '-' }}</td></tr>
          <tr><th>মূলধন</th><td>{{ $record->capital ?? '-' }} টাকা</td></tr>
          <tr><th>কর্মচারী সংখ্যা</th><td>{{ $record->employee_count ?? '-' }}</td></tr>
          <tr><th>সাইনবোর্ডের মাপ</th><td>{{ $record->signboard_size ?? '-' }}</td></tr>
        </table>
      </div>

      <div class="trade-app-panel">
        <div class="trade-app-section-title">আর্থিক বিবরণ</div>
        <table class="trade-app-table">
          <tr><th>ট্রেড লাইসেন্স ফি</th><td>৳ {{ toBn(number_format($record->license_fee ?? 0, 0)) }}</td></tr>
          <tr><th>ভ্যাট</th><td>৳ {{ toBn(number_format($record->vat_fee ?? 0, 0)) }}</td></tr>
          <tr><th>বাণিজ্যিক কর</th><td>৳ {{ toBn(number_format($record->comm_tax ?? 0, 0)) }}</td></tr>
          <tr><th>সাইনবোর্ড কর</th><td>৳ {{ toBn(number_format($record->sign_tax ?? 0, 0)) }}</td></tr>
          @if(($record->discount_amount ?? 0) > 0)
          <tr class="trade-app-discount"><th>ডিসকাউন্ট</th><td>- ৳ {{ toBn(number_format($record->discount_amount, 0)) }}<br><small>{{ $record->discount_reason ?? '' }}</small></td></tr>
          @endif
          <tr class="trade-app-total"><th>সর্বমোট প্রদেয়</th><td>৳ {{ toBn(number_format($record->total_fee ?? 0, 0)) }}</td></tr>
        </table>
      </div>
    </div>

    <p class="trade-app-note"><strong>অঙ্গীকারনামা:</strong> আমি ঘোষণা করছি যে, উপরোক্ত তথ্যাদি আমার জ্ঞান ও বিশ্বাসমতে সত্য ও সঠিক। ব্যবসা পরিচালনার ক্ষেত্রে প্রচলিত আইন, বিধি এবং ইউনিয়ন পরিষদের সকল নির্দেশনা যথাযথভাবে পালন করব। কোনো তথ্য অসত্য প্রমাণিত হলে কর্তৃপক্ষ প্রচলিত আইন অনুযায়ী ব্যবস্থা গ্রহণ করতে পারবেন।</p>
    <div class="sign-section"><div class="sign-block">ইউপি সদস্য<br><small>১১নং আউলিয়াপুর ইউ.পি</small></div><div class="sign-block">আবেদনকারীর স্বাক্ষর<br><small>{{ $record->owner_name }}</small></div><div class="sign-block">গ্রহণকারী কর্মকর্তা<br><small>১১নং আউলিয়াপুর ইউ.পি</small></div></div>
  </div>
  @else
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
  @endif

</body>
</html>
