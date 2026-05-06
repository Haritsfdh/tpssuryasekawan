<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Label for {{ $house->NO_HOUSE_BLAWB }}</title>

  {{-- <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wdth,wght@50,600&display=swap" rel="stylesheet"> --}}

  <style>
    @page{
      size: 100mm 80mm portrait;
      margin: 5mm !important;
      /* width: 80mm !important;
      height: 100mm !important;
      max-width:80mm !important;
      max-height: 100mm !important; */
    }
    body{
      /* margin: 0 !important; */
      /* width: 80mm !important;
      height: 100mm !important;
      max-width:80mm !important;
      max-height: 100mm !important; */
      font-family: Verdana, Geneva, Tahoma, sans-serif;
    }
    .table{
      width:100%;
      border-spacing: 0;
      border-collapse: collapse;
    }
    .table tr, td{
      vertical-align: top !important;
    }
    .table tbody + tbody {
      border-top: 2px solid #565656;
    }
    .table-bordered th,
    .table-bordered td {
      border: 1px solid #000000;
    }

    .table-bordered thead th,
    .table-bordered thead td {
      border-bottom-width: 1px;
    }
    .border-bottom{
      border-bottom: 1.5pt solid #000 !important;
    }
    .border-left{
      border-left: 1.5pt solid #000 !important;
    }
    .desc{
      line-height: 5px !important;
    }
    .text-center{
      text-align: center !important;
    }
    .text-right{
      text-align: right !important;
    }
    .text-sm{
      line-height: 0.5;
    }
    .bold{
      font-weight: bold;
    }
    .h3.{
      height: 3mm !important;
    }
    .h5{
      height: 5mm !important;
    }
    .h7{
      height: 7mm !important;
    }
    .h10{
      height: 10mm !important;
    }
    .h13{
      height: 13mm !important;
    }
    .h15{
      height: 15mm !important;
    }
    .h20{
      height: 20mm !important;
    }
    .h25{
      height: 25mm !important;
    }
    .h35{
      height: 35mm !important;
    }
    .h43{
      height: 43mm !important;
    }
    .f5{
      font-size: 5pt !important;
    }
    .f6{
      font-size: 6pt !important;
    }
    .f7{
      font-size: 7pt !important;
    }
    .f8{
      font-size: 8pt !important;
    }
    .f9{
      font-size: 9pt !important;
    }
    .f10{
      font-size: 10pt !important;
    }
    .f12{
      font-size: 12pt !important;
    }
    .f16{
      font-size: 16pt !important;
    }
    .f18{
      font-size: 18pt !important;
    }
    .f32{
      font-size: 32pt !important;
      font-weight: bold !important;
      line-height: 32px !important;
    }
    .f39{
      font-size: 39pt !important;
      font-weight: bold !important;
      line-height: 35px !important;
    }
    .text-nowrap{
      white-space: nowrap !important;
    }

    @media print {
      html, body {
        height: 99%;
        page-break-after: avoid !important;
        page-break-before: avoid !important;
      }
      .print-display-none,
      .print-display-none * {
        display: none !important;
      }
      .print-visibility-hide,
      .print-visibility-hide * {
        visibility: hidden !important;
      }
      .printme,
      .printme * {
        visibility: visible !important;
      }
      .printme {
        position: absolute;
        left: 0;
        top: 0;
      }

    }
  </style>
</head>
<body>
  <?php $company = $master->branch?->company ?? $house->master?->branch?->company; ?>
  <table class="table table-bordered">
    <tr>
      <td class="f5">From:<br>{{ $house->NM_PENGIRIM }}<br>{{ $house->AL_PENGIRIM }}</td>
      <td rowspan="2" class="text-right h7" style="padding-top: 1mm;padding-right:1mm;padding-bottom:0px;">
        {{-- @php
            $imgPath = public_path('/img/companies/'.$company->GC_Logo);
            if(is_dir($imgPath) || !file_exists($imgPath)){
              $imgPath = public_path('/img/default-logo-light.png');
            }
          @endphp
          <img src="{{ $imgPath }}" alt="Company Logo"
                height="70"> --}}
      </td>
    </tr>
    <tr>
      <td class="f6" >To:<br>{{ $house->NM_PENERIMA }}<br>{{ $house->AL_PENERIMA }}</td>
    </tr>
    <tr>
      <td colspan="2" class="text-center h10 f12 bold">
        <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($house->NO_HOUSE_BLAWB, 'C128', 1, 25, array(0, 0, 0))}}" alt="barcode" style="width:90% !important;height:11mm !important;padding-top:5px;" /><br>
        {{ $house->NO_HOUSE_BLAWB ?? "-" }}
      </td>
    </tr>
    <tr>
      <td class="f6">
        Description of Goods:<br>
        <ul style="margin-top:2px;">
          @forelse ($house->details as $d)
            <li style="margin-left: -25px !important; padding-left: -25px !important;">{{ $d->UR_BRG }}</li>
          @empty
          @endforelse
        </ul>
      </td>
      <td class="f7">Number of Item(s)<br>{{ $house->JML_BRG }}</td>
    </tr>
    <tr>
      <td class="f6" style="width: 60% !important;">Weight (Kg)</td>
      <td class="f6" style="width: 40% !important;">Price (USD)</td>
    </tr>
    <tr>
      <td class="f6 text-center" style="padding-top:15px;padding-bottom:10px;">{{ $house->BRUTO }}</td>
      <td class="f6 text-center" style="padding-top:15px;padding-bottom:10px;">{{ $house->CIF }}</td>
    </tr>
    <tr>
      <td class="h3 f6" style="vertical-align: bottom !important;">
        For commercial items only<br>If known. HS tariff number and country of origin of goods
      </td>
      <td style="text-align:center;vertical-align:middle !important;padding:4px;">
        <img src="data:image/png;base64,{{DNS2D::getBarcodePNG($house->NO_HOUSE_BLAWB, 'QRCODE', 4, 4, array(0,0,0))}}" alt="barcode"/>
      </td>
    </tr>
  </table>
</body>
</html>
