<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>ট্রেড লাইসেন্স - {{ $data->license_no ?? $data->app_id }}</title>
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
      font-family: 'Nikosh', 'Kalpurush', 'Tiro Bangla', sans-serif;
      overflow: hidden !important;
    }
    .trade-cert-frame {
      border: 3px solid #8B0000 !important;
      outline: 2px dashed #b8860b !important;
      outline-offset: 4px;
      padding: 14mm 16mm 12mm 16mm !important;
      background-color: #ffffff !important;
      background-image: radial-gradient(#8B0000 0.5px, transparent 0.5px), radial-gradient(#b8860b 0.4px, #ffffff 0.4px) !important;
      background-size: 20px 20px !important;
      background-position: 0 0, 10px 10px !important;
      position: relative !important;
      box-sizing: border-box !important;
      display: flex !important;
      flex-direction: column !important;
      justify-content: space-between !important;
      height: 295mm !important;
      max-height: 295mm !important;
      width: 210mm !important;
      margin: 0 auto !important;
      overflow: hidden !important;
      page-break-after: avoid !important;
      page-break-inside: avoid !important;
    }
    .trade-watermark-img {
      position: absolute !important;
      top: 52% !important;
      left: 50% !important;
      transform: translate(-50%, -50%) !important;
      width: 500px !important;
      max-width: 88% !important;
      opacity: 0.08 !important;
      pointer-events: none;
      z-index: 1;
    }
    .trade-cert-inner {
      position: relative;
      z-index: 2;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .ribbon-container-3d {
      text-align: center !important;
      position: relative !important;
      margin: 6px 0 10px 0 !important;
    }
    .gorgeous-3d-ribbon {
      position: relative !important;
      display: inline-block !important;
      background: linear-gradient(180deg, #a71414 0%, #7d0b0b 100%) !important;
      color: #ffffff !important;
      font-size: 23px !important;
      font-weight: bold !important;
      padding: 4px 46px !important;
      letter-spacing: 1px !important;
      box-shadow: 0 4px 6px rgba(0,0,0,0.3) !important;
      z-index: 2 !important;
      border-radius: 2px !important;
    }
    .gorgeous-3d-ribbon::before, .gorgeous-3d-ribbon::after {
      content: "" !important;
      position: absolute !important;
      top: 6px !important;
      border: 16px solid #4a0505 !important;
      z-index: -1 !important;
    }
    .gorgeous-3d-ribbon::before { left: -22px !important; border-right-width: 12px !important; border-left-color: transparent !important; }
    .gorgeous-3d-ribbon::after { right: -22px !important; border-left-width: 12px !important; border-right-color: transparent !important; }
    .cert-meta-bar {
      display: flex !important;
      justify-content: space-between !important;
      align-items: center !important;
      border-bottom: 2px solid #8B0000 !important;
      padding: 3px 6px !important;
      font-size: 14px !important;
      margin-top: 2px !important;
    }
    .fiscal-year-pill {
      background: #8B0000 !important;
      color: #ffffff !important;
      font-size: 13px;
      font-weight: bold;
      padding: 2px 16px;
      border-radius: 4px;
      display: inline-block;
    }
    .trade-body-table {
      width: 100% !important;
      font-size: 15px !important;
      line-height: 1.6 !important;
      border-collapse: collapse !important;
    }
    .trade-body-table td { padding: 2.5px 2px !important; vertical-align: top !important; }
    .trade-table-fee-grid {
      width: 72% !important;
      border-collapse: collapse !important;
      margin-top: 3px !important;
      background: #ffffff !important;
    }
    .trade-table-fee-grid th, .trade-table-fee-grid td {
      border: 1px solid #8B0000 !important;
      padding: 3px 8px !important;
      font-size: 13px !important;
      color: #000 !important;
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
    $fiscalParts = explode('-', $data->fiscal_year ?? '২০২৫-২০২৬');
    $fiscalEndYear = count($fiscalParts) > 1 ? $fiscalParts[1] : '২০২৬';
    
    $qrPayload = "১১নং আউলিযাপুর ইউনিয়ন পরিষদ\nট্রেড লাইসেন্স নং: " . ($data->license_no ?? $data->app_id) . "\nপ্রতিষ্ঠান: {$data->org_name}\nমালিক: {$data->owner_name}\nমোবাইল: {$data->mobile}\nমেয়াদ: ৩০ জুন {$fiscalEndYear} ইং পর্যন্ত\nঅনলাইন ভেরিফায়েড";
    $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($qrPayload);
    $ownerPhoto = !empty($data->photo) ? $data->photo : 'https://via.placeholder.com/96x112?text=Photo';
  @endphp

  <div class="trade-cert-frame">
    <!-- ওয়াটারমার্ক -->
    <img src="https://lh3.googleusercontent.com/d/1_LMOoAtirVZeGxE4sz_CVQtQpInPPQTs" class="trade-watermark-img" alt="Watermark">

    <div class="trade-cert-inner">
      <!-- হেডার -->
      <table style="width:100%; border-collapse:collapse; margin-bottom:2px;">
        <tr>
          <td style="width:75px; vertical-align:middle; text-align:left;">
            <img src="https://lh3.googleusercontent.com/d/1_LMOoAtirVZeGxE4sz_CVQtQpInPPQTs" width="90" height="90" alt="Logo" style="object-fit:contain;">
          </td>
          <td style="text-align:center; vertical-align:middle;">
            <div style="font-size:13px; font-weight:bold; color:#333; margin-bottom:1px;">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার (স্থানীয় সরকার বিভাগ)</div>
            <h1 style="font-size:30px; font-weight:bold; color:#8B0000; margin:0; line-height:1.15; letter-spacing:0.5px;">১১নং আউলিয়াপুর ইউনিয়ন পরিষদ</h1>
            <div style="font-size:15.5px; font-weight:bold; color:#005a2b; margin-top:2px;">উপজেলা: পটুয়াখালী সদর, জেলা: পটুয়াখালী</div>
          </td>
          <td style="width:75px; vertical-align:middle; text-align:right;">
            <img src="{{ $ownerPhoto }}" style="width:92px; height:108px; border:2px solid #8B0000; object-fit:cover; padding:1px; border-radius:3px; background:#fff;">
          </td>
        </tr>
      </table>

      <!-- মেটা বার -->
      <div class="cert-meta-bar">
        <div style="flex:1; text-align:left; white-space:nowrap;">
          <strong>রসিদ নং:</strong> <span style="font-weight:bold; color:#8B0000;">{{ toBn($data->receipt_no ?? '০১') }}</span>
        </div>
        <div style="flex:1; text-align:center; white-space:nowrap;">
          <span class="fiscal-year-pill">অর্থ বছর: {{ $data->fiscal_year ?? '২০২৫-২০২৬' }}</span>
        </div>
        <div style="flex:1; text-align:right; white-space:nowrap;">
          <strong>{{ $data->is_renewal ? 'লাইসেন্স নবায়নের তারিখ' : 'লাইসেন্স ইস্যুর তারিখ' }}:</strong>
          <span style="font-weight:bold; color:#000;">{{ $data->apply_date }} ইং</span>
        </div>
      </div>

      <!-- ৩ডি রিবন -->
      <div class="ribbon-container-3d">
        <div class="gorgeous-3d-ribbon">
          <span>{{ $data->is_renewal ? 'নবায়নকৃত ট্রেড লাইসেন্স' : 'ট্রেড লাইসেন্স' }}</span>
        </div>
      </div>

      <!-- মূল তথ্য ছক -->
      <div style="padding: 0 10px;">
        <table class="trade-body-table">
          <tr><td style="width:26%; font-weight:bold;">ট্রেড লাইসেন্স নম্বর</td><td style="width:2%;">:</td><td style="width:72%;"><strong style="color:#8B0000; font-size:16px;">{{ toBn($data->license_no ?? $data->app_id) }}</strong></td></tr>
          <tr><td style="font-weight:bold;">লাইসেন্সধারীর নাম</td><td>:</td><td><strong style="color:#000;">{{ $data->owner_name }}</strong></td></tr>
          <tr><td style="font-weight:bold;">পিতার নাম</td><td>:</td><td>{{ $data->father_name ?? '-' }}</td></tr>
          <tr><td style="font-weight:bold;">মাতার নাম</td><td>:</td><td>{{ $data->mother_name ?? '-' }}</td></tr>
          <tr><td style="font-weight:bold;">জাতীয় পরিচয়পত্র নম্বর</td><td>:</td><td>{{ toBn($data->nid ?? '-') }}</td></tr>
          <tr><td style="font-weight:bold;">মোবাইল নম্বর</td><td>:</td><td>{{ toBn($data->mobile ?? '-') }}</td></tr>
          <tr><td style="font-weight:bold;">লাইসেন্সধারীর ঠিকানা</td><td>:</td><td>{{ $data->owner_address ?? '-' }}</td></tr>
          <tr><td style="font-weight:bold; color:#006837;">প্রতিষ্ঠানের নাম</td><td>:</td><td><strong style="color:#006837; font-size:16px;">“{{ $data->org_name }}”</strong></td></tr>
          <tr><td style="font-weight:bold;">ব্যবসার ধরন</td><td>:</td><td>{{ $data->biz_details ?? $data->category }}</td></tr>
          <tr><td style="font-weight:bold;">ব্যবসা প্রতিষ্ঠানের ঠিকানা</td><td>:</td><td>{{ $data->biz_address }}</td></tr>
          <tr><td style="font-weight:bold;">বৈধতার মেয়াদ</td><td>:</td><td><span style="font-weight:bold; color:#8B0000;">৩০ জুন, {{ $fiscalEndYear }} ইং পর্যন্ত</span></td></tr>
          
          <!-- আর্থিক বিবরণী টেবিল -->
          <tr>
            <td style="font-weight:bold; vertical-align:top; padding-top:4px;">আর্থিক বিবরণ</td>
            <td style="vertical-align:top; padding-top:4px;">:</td>
            <td style="padding-top:4px;">
              <table class="trade-table-fee-grid">
                <tr style="background:#f3f4f6;"><th style="width:65%;">আদায়ের বিবরণ</th><th style="text-align:right; width:35%;">পরিমাণ</th></tr>
                <tr><td>ট্রেড লাইসেন্স ফি / নবায়ন ফি</td><td style="text-align:right;">৳ {{ toBn(number_format($data->license_fee, 0)) }}</td></tr>
                <tr><td>ভ্যাট (১৫%)</td><td style="text-align:right;">৳ {{ toBn(number_format($data->vat_fee, 0)) }}</td></tr>
                <tr><td>বাণিজ্যিক কর (বার্ষিক)</td><td style="text-align:right;">৳ {{ toBn(number_format($data->comm_tax, 0)) }}</td></tr>
                <tr><td>সাইনবোর্ড কর (বার্ষিক)</td><td style="text-align:right;">৳ {{ toBn(number_format($data->sign_tax, 0)) }}</td></tr>
                <tr style="font-weight:bold; background:#fff2f2;"><td>সর্বমোট</td><td style="text-align:right; color:#8B0000; font-size:14px;">৳ {{ toBn(number_format($data->total_fee, 0)) }}</td></tr>
              </table>
            </td>
          </tr>
        </table>
      </div>

      <div style="border-top:1px solid #aaa; margin:4px 10px 0 10px; padding-top:3px; font-size:12px; text-align:justify; line-height:1.4;">
        উল্লেখিত প্রতিষ্ঠানের অনুকূলে প্রদত্ত লাইসেন্স ফি গ্রহণ করিয়া <strong>{{ $data->fiscal_year }}</strong> ইং সালের জন্য অভ্যন্তরীণ বাণিজ্য চালিয়ে যাওয়ার অনুমতি দেওয়া হইল। <strong>৩০ জুন {{ $fiscalEndYear }}</strong> ইং পর্যন্ত এই লাইসেন্স বৈধ বলিয়া বিবেচিত হইবে এবং প্রতি বছর নবায়ন করিতে হইবে।
      </div>

      <!-- সিগনেচার ও QR কোড -->
      <table style="width:100%; margin-top:8px; font-size:13px; border-collapse:collapse;">
        <tr>
          <td style="text-align:center; vertical-align:bottom; width:33%;">
            <div style="font-weight:bold;">{{ $settings['secretary'] ?? 'মোঃ মোতাহার উদ্দিন' }}</div>
            <div>সচিব</div>
            <div>১১নং আউলিয়াপুর ইউনিয়ন পরিষদ</div>
          </td>
          <td style="text-align:center; vertical-align:bottom; width:33%;">
            <img src="{{ $qrCodeUrl }}" style="width:85px; height:85px; border:1px solid #ccc; padding:2px; background:#fff; border-radius:3px;">
            <div style="font-size:10px; margin-top:2px; font-weight:bold; color:#006837;"><i class="fa fa-shield-alt"></i> অনলাইন ভেরিফায়েড</div>
          </td>
          <td style="text-align:center; vertical-align:bottom; width:33%;">
            <div style="font-weight:bold;">{{ $settings['chairman'] ?? 'অ্যাড. মোঃ হুমায়ুন কবির' }}</div>
            <div>{{ $data->signatory_role ?? 'চেয়ারম্যান' }}</div>
            <div>১১নং আউলিয়াপুর ইউনিয়ন পরিষদ</div>
          </td>
        </tr>
      </table>

      <!-- ফুটার লিংক -->
      <div style="text-align:center; margin-top:3px; padding-top:2px; border-top:1px solid #8B0000; font-size:11px; color:#444;">
        সনদ যাচাইয়ের জন্য QR স্ক্যান করুন অথবা ভিজিট করুন: <strong style="color:#006837;">www.aup.auliapur.com</strong>
      </div>
    </div>
  </div>

</body>
</html>
