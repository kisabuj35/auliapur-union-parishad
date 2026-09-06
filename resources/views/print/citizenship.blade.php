<!DOCTYPE html>
<html lang="{{ $data->language ?? 'bn' }}">
<head>
  <meta charset="UTF-8">
  <title>নাগরিকত্ব সনদ - {{ $data->app_id }}</title>
  <!-- Fonts -->
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
    .cit-cert-frame {
      width: 210mm !important;
      height: 295mm !important;
      max-height: 295mm !important;
      padding: 14mm 16mm 12mm 16mm !important;
      margin: 0 auto !important;
      background: #ffffff !important;
      border: 3.5px double #d32f2f !important;
      outline: 2px dashed #d32f2f !important;
      outline-offset: 4px !important;
      box-sizing: border-box !important;
      position: relative !important;
      display: flex !important;
      flex-direction: column !important;
      justify-content: space-between !important;
      page-break-after: avoid !important;
      page-break-inside: avoid !important;
      overflow: hidden !important;
    }
    .cit-watermark-bg {
      position: absolute !important;
      top: 50% !important;
      left: 50% !important;
      transform: translate(-50%, -50%) !important;
      width: 500px !important;
      opacity: 0.10 !important;
      z-index: 1 !important;
      pointer-events: none;
    }
    .cit-content-body {
      position: relative !important;
      z-index: 2 !important;
      height: 100% !important;
      display: flex !important;
      flex-direction: column !important;
      justify-content: space-between !important;
    }
    /* ৩ডি (3D) রিবন ব্যানার */
    .cit-3d-ribbon-wrapper {
      text-align: center !important;
      margin: 6px 0 10px 0 !important;
      position: relative !important;
    }
    .cit-3d-ribbon-box {
      position: relative !important;
      display: inline-block !important;
      background: linear-gradient(180deg, #a71414 0%, #7d0b0b 100%) !important;
      color: #ffffff !important;
      font-size: 24px !important;
      font-weight: bold !important;
      padding: 4px 45px !important;
      letter-spacing: 1px !important;
      box-shadow: 0 4px 6px rgba(0,0,0,0.3) !important;
      z-index: 2 !important;
      border-radius: 2px !important;
      text-shadow: 0 1px 2px rgba(0,0,0,0.5) !important;
    }
    .cit-3d-ribbon-box::before, .cit-3d-ribbon-box::after {
      content: "" !important;
      position: absolute !important;
      top: 6px !important;
      border: 16px solid #4a0505 !important;
      z-index: -1 !important;
    }
    .cit-3d-ribbon-box::before { left: -22px !important; border-right-width: 12px !important; border-left-color: transparent !important; }
    .cit-3d-ribbon-box::after { right: -22px !important; border-left-width: 12px !important; border-right-color: transparent !important; }
    .cit-ribbon-fold-left, .cit-ribbon-fold-right { position: absolute !important; bottom: -6px !important; border: 3px solid transparent !important; z-index: 1 !important; }
    .cit-ribbon-fold-left { left: 0px !important; border-top-color: #300202 !important; border-left-color: #300202 !important; }
    .cit-ribbon-fold-right { right: 0px !important; border-top-color: #300202 !important; border-right-color: #300202 !important; }

    .cit-info-grid {
      width: 100% !important;
      border-collapse: collapse !important;
      margin: 6px 0 !important;
      font-size: 18px !important;
      line-height: 1.6 !important;
    }
    .cit-info-grid td { padding: 3px 4px !important; vertical-align: top !important; }
    
    /* ইংরেজি ফরম্যাট */
    .cit-english-certificate * { font-family: 'Times New Roman', serif !important; }
  </style>
</head>
<body onload="window.print();">

  @php
    $isEn = ($data->language === 'en');
    $qrText = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার\n১১নং আউলিযাপুর ইউনিয়ন পরিষদ\nসনদ: নাগরিকত্ব সনদ\nসনদ নং: {$data->cert_no}\nনাম: {$data->name}\nএনআইডি: {$data->nid}\nভেরিফায়েড";
    if($isEn) {
      $qrText = "Government of the People's Republic of Bangladesh\n11 No. Auliapur Union Parishad\nCitizenship Certificate\nCertificate No: {$data->cert_no}\nName: {$data->name}\nVerified";
    }
    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=140x140&margin=0&data=" . urlencode($qrText);
    
    // ডিজিট কনভার্টার
    function toBn($num) {
      $en = ['0','1','2','3','4','5','6','7','8','9'];
      $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
      return str_replace($en, $bn, $num);
    }
  @endphp

  <div class="cit-cert-frame {{ $isEn ? 'cit-english-certificate' : '' }}">
    <!-- ওয়াটারমার্ক -->
    <img src="https://lh3.googleusercontent.com/d/1_LMOoAtirVZeGxE4sz_CVQtQpInPPQTs" class="cit-watermark-bg" alt="Watermark">

    <div class="cit-content-body">
      <div>
        <!-- হেডার -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 2px;">
          <tr>
            <td style="width: 85px; vertical-align: middle; text-align: left;">
              <img src="https://upload.wikimedia.org/wikipedia/commons/8/84/Government_Seal_of_Bangladesh.svg" style="width: 75px; height: 75px; object-fit: contain;">
            </td>
            <td style="text-align: center; vertical-align: middle;">
              <div style="font-size: 14px; font-weight: bold; color: #222;">
                {{ $isEn ? "Government of the People's Republic of Bangladesh" : "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার (স্থানীয় সরকার বিভাগ)" }}
              </div>
              <h1 style="margin: 2px 0; font-size: 29px; font-weight: bold; color: #0d47a1;">
                {{ $isEn ? "11 No. Auliapur Union Parishad" : "১১নং আউলিয়াপুর ইউনিয়ন পরিষদ" }}
              </h1>
              <div style="font-size: 16px; font-weight: bold; color: #000;">
                {{ $isEn ? "Upazila: Patuakhali Sadar, District: Patuakhali" : "উপজেলা: পটুয়াখালী সদর, জেলা: পটুয়াখালী" }}
              </div>
            </td>
            <td style="width: 85px; vertical-align: middle; text-align: right;">
              <img src="https://lh3.googleusercontent.com/d/1_LMOoAtirVZeGxE4sz_CVQtQpInPPQTs" style="width: 75px; height: 75px; object-fit: contain;">
            </td>
          </tr>
        </table>

        <!-- মেটা বার -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1.5px solid #d32f2f; border-bottom: 1.5px solid #d32f2f; padding: 3px 6px; font-size: 16px; font-weight: bold; margin: 4px 0 10px 0;">
          <div>{{ $isEn ? "Serial No:" : "ক্রমিক নং:" }} {{ $isEn ? substr($data->app_id, -3) : toBn(substr($data->app_id, -3)) }}</div>
          <div>{{ $isEn ? "Date:" : "তারিখ:" }} {{ $data->apply_date }} {{ $isEn ? '' : 'ইং' }}</div>
        </div>

        <!-- ৩ডি রিবন -->
        <div class="cit-3d-ribbon-wrapper">
          <div class="cit-3d-ribbon-box">
            {{ $isEn ? "CITIZENSHIP CERTIFICATE" : "নাগরিকত্ব সনদ" }}
            <div class="cit-ribbon-fold-left"></div>
            <div class="cit-ribbon-fold-right"></div>
          </div>
        </div>

        <!-- তথ্য ছক -->
        <table class="cit-info-grid">
          <tr>
            <td style="width: 25%; font-weight: bold;">{{ $isEn ? "Certificate No." : "নাগরিকত্ব সনদ নং" }}</td>
            <td style="width: 3%;">:</td>
            <td style="width: 72%; font-weight: bold; color: #000; font-size: 18.5px;">{{ $isEn ? $data->cert_no : toBn($data->cert_no) }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">{{ $isEn ? "Name" : "সনদধারীর নাম" }}</td>
            <td>:</td>
            <td style="font-weight: bold;">{{ $data->name }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">{{ $isEn ? "Father's name" : "পিতার নাম" }}</td>
            <td>:</td>
            <td>{{ $data->father_name }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">{{ $isEn ? "Mother's name" : "মাতার নাম" }}</td>
            <td>:</td>
            <td>{{ $data->mother_name }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">{{ $isEn ? "Date of birth" : "জন্ম তারিখ" }}</td>
            <td>:</td>
            <td>{{ $isEn ? $data->dob : toBn($data->dob) . ' ইং' }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">{{ $isEn ? "National ID number" : "জাতীয় পরিচয় নম্বর" }}</td>
            <td>:</td>
            <td>{{ $isEn ? $data->nid : toBn($data->nid) }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">{{ $isEn ? "Marital status" : "বৈবাহিক অবস্থা" }}</td>
            <td>:</td>
            <td>{{ $data->marital_status }}</td>
          </tr>
          @if(!empty($data->spouse_name))
          <tr>
            <td style="font-weight: bold;">{{ $isEn ? "Spouse name" : "স্বামী/স্ত্রীর নাম" }}</td>
            <td>:</td>
            <td>{{ $data->spouse_name }}</td>
          </tr>
          @endif
          <tr>
            <td style="font-weight: bold;">{{ $isEn ? "Mobile number" : "মোবাইল নম্বর" }}</td>
            <td>:</td>
            <td>{{ $isEn ? $data->mobile : toBn($data->mobile) }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">{{ $isEn ? "Address" : "ঠিকানা" }}</td>
            <td>:</td>
            <td>
              @if($isEn)
                Ward No: {{ $data->ward_no }}, Village: {{ $data->village }}, Post Office: {{ $data->post_office }}, Upazila: Patuakhali Sadar, District: Patuakhali.
              @else
                ওয়ার্ড নং: {{ toBn($data->ward_no) }}, গ্রাম: {{ $data->village }}, ডাকঘর: {{ $data->post_office }}, উপজেলা: পটুয়াখালী সদর, জেলা: পটুয়াখালী, বিভাগ: বরিশাল ।
              @endif
            </td>
          </tr>
        </table>

        <!-- বিবরণী -->
        <p style="text-align: justify; font-size: 18px; line-height: 2.0; text-indent: 35px; margin: 14px 0 10px 0; color: #000;">
          @if($isEn)
            This is to certify that the person mentioned above is a permanent resident of this Union and a citizen of Bangladesh by birth. I know him/her personally. To the best of my knowledge, his/her moral character is good and he/she is not involved in any anti-state or anti-social activities.
          @else
            এই মর্মে নাগরিকত্ব সনদ প্রদান করা যাচ্ছে যে, উপরে উল্লেখিত ব্যক্তি অত্র ইউনিয়নের স্থায়ী বাসিন্দা ও জন্মসূত্রে বাংলাদেশের নাগরিক। আমি তাকে ব্যক্তিগতভাবে চিনি ও জানি। আমার জানা মতে, তার নৈতিক চরিত্র ভাল। তিনি রাষ্ট্রবিরোধী বা সমাজবিরোধী কোনো কর্মকাণ্ডের সাথে সম্পৃক্ত নহে।
          @endif
        </p>

        <p style="text-align: center; font-weight: bold; font-size: 18.5px; margin: 10px 0; color: #000;">
          {{ $isEn ? "I wish him/her every success and a bright future." : "আমি তাহার সার্বিক সাফল্য এবং উজ্জ্বল ভবিষ্যৎ কামনা করছি।" }}
        </p>
      </div>

      <!-- ফুটার সিল ও স্বাক্ষর -->
      <div>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 8px;">
          <tr>
            <td style="width: 32%; text-align: center; vertical-align: bottom;">
              <div style="border-top: 1.5px dashed #444; width: 85%; margin: 0 auto 3px auto;"></div>
              <div style="font-weight: bold; font-size: 15.5px;">{{ $isEn ? "UP Member's signature" : "ইউপি সদস্যের স্বাক্ষর ও সিলমোহর" }}</div>
              <div style="font-size: 13.5px; color: #444;">{{ $isEn ? "11 No. Auliapur Union Parishad" : "১১নং আউলিয়াপুর ইউনিয়ন পরিষদ" }}</div>
            </td>
            
            <td style="width: 36%; text-align: center; vertical-align: bottom;">
              <img src="{{ $qrUrl }}" style="width: 78px; height: 78px; border: 1px solid #ccc; padding: 2px; background: #fff;" alt="QR Code">
              <div style="font-size: 11px; font-weight: bold; color: #006837; margin-top: 2px;">{{ $isEn ? "ONLINE VERIFIED" : "অনলাইন ভেরিফায়েড" }}</div>
            </td>
            
            <td style="width: 32%; text-align: center; vertical-align: bottom;">
              <div style="font-weight: bold; font-size: 17px; color: #000;">
                {{ $isEn ? ($settings['chairmanEn'] ?? 'Adv. Md. Humayun Kabir') : ($settings['chairman'] ?? 'অ্যাড. মোঃ হুমায়ুন কবির') }}
              </div>
              <div style="border-top: 1.5px solid #000; width: 90%; margin: 2px auto 3px auto;"></div>
              <div style="font-weight: bold; font-size: 15.5px;">{{ $isEn ? "Chairman" : "চেয়ারম্যান" }}</div>
              <div style="font-size: 13.5px; color: #444;">{{ $isEn ? "11 No. Auliapur Union Parishad" : "১১নং আউলিয়াপুর ইউনিয়ন পরিষদ" }}</div>
            </td>
          </tr>
        </table>

        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1.5px solid #d32f2f; padding-top: 4px; font-size: 11px; font-weight: bold; color: #006837;">
          <div>{{ $isEn ? "Send your child to school" : "আপনার সন্তানকে স্কুলে পাঠান" }}</div>
          <div>{{ $isEn ? "Verify: www.aup.auliapur.com" : "নাগরিকত্ব সনদ যাচাই: www.aup.auliapur.com" }}</div>
          <div>{{ $isEn ? "Prevent child marriage" : "বাল্য বিবাহ প্রতিরোধ করুন" }}</div>
        </div>
      </div>

    </div>
  </div>

</body>
</html>
