<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>{{ $data->certificate_type ?? 'পারিবারিক সনদপত্র' }} - {{ $data->app_id }}</title>
  <link href="https://fonts.maateen.me/nikosh/font.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Kalpurush&family=Tiro+Bangla&display=swap" rel="stylesheet">
  <style>
    @page {
      size: A4 portrait;
      margin: 0mm !important;
    }
    * {
      box-sizing: border-box;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
    }
    html, body {
      width: 210mm;
      height: 297mm;
      margin: 0 !important;
      padding: 0 !important;
      background: #ffffff;
      font-family: 'Nikosh', 'Kalpurush', sans-serif;
      overflow: hidden !important;
    }
    .family-cert-container {
      width: 210mm !important;
      height: 295mm !important;
      max-height: 295mm !important;
      padding: 12mm 15mm 10mm !important;
      margin: 0 auto !important;
      border: 3px double #b91c1c !important;
      outline: 1px solid #dc2626 !important;
      outline-offset: -7px !important;
      position: relative !important;
      display: flex !important;
      flex-direction: column !important;
      justify-content: space-between !important;
      background: #ffffff !important;
      overflow: hidden !important;
      page-break-after: avoid !important;
      page-break-inside: avoid !important;
    }
    .family-cert-container::before {
      content: "";
      position: absolute;
      inset: 4mm;
      border: 1px solid rgba(185,28,28,.35);
      pointer-events: none;
      z-index: 3;
    }
    .cert-watermark-bg {
      position: absolute !important;
      top: 52% !important;
      left: 50% !important;
      transform: translate(-50%, -50%) !important;
      width: 500px !important;
      max-width: 88% !important;
      opacity: 0.09 !important;
      z-index: 1 !important;
      pointer-events: none;
    }
    .cert-main-content {
      position: relative !important;
      z-index: 2 !important;
      height: 100% !important;
      display: flex !important;
      flex-direction: column !important;
      justify-content: space-between !important;
    }
    /* ৩ডি (3D) নীল রিবন */
    .ribbon-wrapper-12 {
      position: relative;
      display: inline-block;
      margin: 6px 0 10px 0;
      text-align: center;
    }
    .ribbon-center-box {
      position: relative;
      background: linear-gradient(180deg, #2563eb 0%, #1e3a8a 100%) !important;
      color: #ffffff !important;
      font-size: 23px !important;
      font-weight: bold !important;
      padding: 5px 45px !important;
      border: 2px solid #ffffff !important;
      outline: 2px solid #1d4ed8 !important;
      box-shadow: 0 3px 0 #991b1b !important;
      z-index: 2;
      display: inline-block;
      letter-spacing: 1px;
    }
    .ribbon-wing-left, .ribbon-wing-right {
      position: absolute;
      top: 6px;
      width: 32px;
      height: 100%;
      background-color: #991b1b !important;
      z-index: 1;
    }
    .ribbon-wing-left { left: -22px; clip-path: polygon(100% 0, 0 50%, 100% 100%); }
    .ribbon-wing-right { right: -22px; clip-path: polygon(0 0, 100% 50%, 0 100%); }

    .cert-table-exact {
      width: 100% !important;
      border-collapse: collapse !important;
      margin: 8px 0 !important;
    }
    .cert-table-exact th {
      background-color: #f8fafc !important;
      color: #000000 !important;
      text-align: center;
      border: 1.5px solid #000000 !important;
      padding: 5px 4px;
      font-size: 14px;
      font-weight: bold;
    }
    .cert-table-exact td {
      border: 1.5px solid #000000 !important;
      padding: 5px 4px;
      font-size: 13.5px;
      color: #000000;
      text-align: center;
    }
    .family-cert-footer {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 6mm;
      padding: 3mm 2mm 0;
      border-top: 1.5px solid #b91c1c;
      color: #1e3a8a;
      font-size: 12.5px;
      font-weight: bold;
      white-space: nowrap;
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
    $isSuccession = ($data->certificate_type === 'উত্তরাধিকারী সনদ');
    $shortAppId = substr($data->app_id, -3);
  @endphp

  <div class="family-cert-container">
    <!-- ওয়াটারমার্ক -->
    <img src="https://lh3.googleusercontent.com/d/1_LMOoAtirVZeGxE4sz_CVQtQpInPPQTs" class="cert-watermark-bg" alt="Watermark">

    <div class="cert-main-content">
      <div>
        <!-- হেডার -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
          <div style="width: 75px;"><img src="https://lh3.googleusercontent.com/d/1_LMOoAtirVZeGxE4sz_CVQtQpInPPQTs" alt="UP Logo" width="70" height="70"></div>
          <div style="text-align: center; flex-grow: 1; padding: 0 8px;">
            <h2 style="font-size: 26px; font-weight: 800; margin: 0; color: #000;">১১নং আউলিয়াপুর ইউনিয়ন পরিষদ কার্যালয়</h2>
            <h5 style="font-size: 16px; margin: 2px 0 0 0; font-weight: bold; color: #000;">ডাকঘরঃ আউলিয়াপুর ময়দান, উপজেলা ও জেলাঃ পটুয়াখালী ।</h5>
          </div>
          <div style="width: 75px; text-align: right;">
            <div style="width: 65px; height: 60px; border: 1.5px dashed #444; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; color: #555;">সিল / ছবি</div>
          </div>
        </div>

        <div style="border-top: 2px solid #000; border-bottom: 1px solid #000; height: 4px; margin: 4px 0 6px 0;"></div>

        <!-- মেটা বার -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; font-size: 15px; font-weight: bold; color: #000;">
          <div>স্মারক নং- আ/ইউ/পটুয়া/সদর/{{ toBn(date('Y')) }}/{{ toBn($shortAppId) }}</div>
          <div>তারিখঃ {{ $data->apply_date }} ইং</div>
        </div>

        <!-- ৩ডি নীল রিবন -->
        <div style="text-align: center;">
          <div class="ribbon-wrapper-12">
            <div class="ribbon-wing-left"></div>
            <div class="ribbon-center-box">{{ $isSuccession ? 'উত্তরাধিকারী সনদপত্র' : 'পারিবারিক সনদপত্র' }}</div>
            <div class="ribbon-wing-right"></div>
          </div>
        </div>

        <!-- পারিবারিক সনদের বিবরণী -->
        @if(!$isSuccession)
        <div style="margin: 8px 0; text-align: justify; font-size: 15.5px; line-height: 2.1; text-indent: 40px; font-weight: 500;">
          এই মর্মে পারিবারিক সনদ পত্র প্রদান করা যাইতেছে যে, <strong>{{ $data->name }}</strong>, পিতা- <strong>{{ $data->father_name }}</strong>, মাতা- <strong>{{ $data->mother_name ?? '-' }}</strong>, গ্রাম- <strong>{{ $data->village }}</strong>, ডাকঘর- <strong>{{ $data->post_office }}</strong>, উপজেলা- <strong>পটুয়াখালী সদর</strong>, জেলা- <strong>পটুয়াখালী</strong>। আমি তার পরিবারকে ব্যক্তিগতভাবে চিনি ও জানি। আমার জানা মতে তাহারা বাংলাদেশের নাগরিক এবং পরিবারের সকলের মধ্যে সুসম্পর্ক বিদ্যমান। তাদের পরিবারের সকল সদস্যদের নাম নিম্নে দেওয়া হলোঃ-
        </div>
        @else
        <!-- উত্তরাধিকারী সনদের বিবরণী -->
        <div style="margin: 8px 0; text-align: justify; font-size: 15.5px; line-height: 2.1; text-indent: 40px; font-weight: 500;">
          এই মর্মে উত্তরাধিকারী সনদপত্র প্রদান করা যাইতেছে যে, <strong>{{ $data->deceased_name }}</strong>, পিতা- <strong>{{ $data->deceased_father ?? '-' }}</strong>, মাতা- <strong>{{ $data->deceased_mother ?? '-' }}</strong>, ওয়ার্ড-<strong>{{ toBn($data->deceased_ward ?? $data->ward_no) }}</strong>, গ্রাম- <strong>{{ $data->deceased_village ?? $data->village }}</strong>, ডাকঘর- <strong>{{ $data->deceased_post_office ?? $data->post_office }}</strong>, ইউনিয়ন- <strong>{{ $data->deceased_union }}</strong>, উপজেলা- <strong>{{ $data->deceased_upazila }}</strong>, জেলা- <strong>{{ $data->deceased_district }}</strong> তিনি গত <strong>{{ toBn($data->deceased_date ?? '-') }}</strong> তারিখে মৃত্যুবরণ করেন। আমি তাকে ব্যক্তিগতভাবে চিনিতাম ও জানিতাম। আমার জানামতে তাহার অবর্তমানে তিনি নিম্নলিখিত ব্যক্তিবর্গকে বৈধ উত্তরাধিকারী হিসেবে রেখে যান।
        </div>
        @endif

        <!-- সদস্যদের টেবিল -->
        <table class="cert-table-exact">
          <thead>
            <tr>
              <th style="width: 55px;">ক্রমিক</th>
              <th>পরিবারের সদস্য / উত্তরাধিকারীর নাম</th>
              <th>ভোটার আইডি / জন্ম নিবন্ধন নম্বর</th>
              <th style="width: 125px;">জন্ম তারিখ</th>
              <th style="width: 100px;">সম্পর্ক</th>
              <th style="width: 95px;">স্বাক্ষর</th>
            </tr>
          </thead>
          <tbody>
            @forelse($members as $idx => $m)
            <tr>
              <td>{{ toBn($idx + 1) }}</td>
              <td style="text-align: left; padding-left: 8px;"><strong>{{ $m['name'] ?? '-' }}</strong></td>
              <td>{{ toBn($m['nid'] ?? '-') }}</td>
              <td>{{ toBn($m['dob'] ?? '-') }}</td>
              <td>{{ $m['relation'] ?? '-' }}</td>
              <td></td>
            </tr>
            @empty
            <tr><td colspan="6">কোনো সদস্য তালিকা পাওয়া যায়নি</td></tr>
            @endforelse
          </tbody>
        </table>

        <p style="margin-top: 10px; margin-bottom: 4px; font-size: 14.5px; font-weight: bold;">
          আমার জানা মতে উপরোক্ত সদস্য/সদস্যগণ ব্যতিত <strong>{{ $data->name }}</strong> এর পরিবারে আর কোন সদস্য/উত্তরাধিকারী নেই।
        </p>
        <p style="margin-top: 2px; font-size: 13.5px; font-weight: bold; text-decoration: underline;">
          সংশ্লিষ্ট গ্রাম পুলিশ ও ওয়ার্ড মেম্বার এর প্রত্যয়ন মতে
        </p>
      </div>

      <!-- স্বাক্ষর ও ফুটার -->
      <div style="padding-top: 10px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; text-align: center; font-size: 14px; font-weight: bold;">
          <div style="width: 30%;">
            <div style="border-top: 1.5px solid #000; padding-top: 2px;">গ্রাম পুলিশ<br><small style="font-weight: normal;">আউলিয়াপুর ইউনিয়ন পরিষদ</small></div>
          </div>
          <div style="width: 30%;">
            <div style="border-top: 1.5px solid #000; padding-top: 2px;">ইউপি সদস্য<br><small style="font-weight: normal;">আউলিয়াপুর ইউনিয়ন পরিষদ</small></div>
          </div>
          <div style="width: 35%;">
            <div style="border-top: 1.5px solid #000; padding-top: 2px;">
              <span>({{ $settings['chairman'] ?? 'অ্যাড. মোঃ হুমায়ুন কবির' }})</span><br>
              <span>{{ $data->signatory_role ?? 'চেয়ারম্যান' }}</span><br>
              <small style="font-weight: normal;">আউলিয়াপুর ইউনিয়ন পরিষদ</small>
            </div>
          </div>
        </div>

        <div class="family-cert-footer">
          <span>তথ্য যাচাই করতে ভিজিট করুন: www.aup.auliapur.com</span>
        </div>
      </div>

    </div>
  </div>

</body>
</html>
