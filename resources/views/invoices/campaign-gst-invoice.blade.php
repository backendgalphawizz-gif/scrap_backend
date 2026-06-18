<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GST Tax Invoice - {{ $invoice_number }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #222; margin: 24px; }
        h2 { font-size: 14px; margin: 12px 0 8px; color: #222; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 7px 9px; text-align: left; vertical-align: top; }
        th { background: #fff; font-weight: bold; }
        .company-bar {
            background: #1a3a5c;
            color: #fff;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
            padding: 10px 12px;
            margin-bottom: 12px;
        }
        .doc-title { font-size: 14px; font-weight: bold; text-transform: uppercase; margin: 0 0 8px; }
        .meta td { border: 1px solid #000; padding: 6px 9px; vertical-align: top; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .header-grid { display: flex; gap: 0; margin-top: 10px; }
        .party { flex: 1; border: 1px solid #000; padding: 8px 10px; }
        .party:first-child { border-right: none; }
        .party h2 { margin: 0 0 6px; font-size: 13px; }
        .muted { color: #555; font-size: 11px; }
        .gst-sub td { border-top: none; }
        .total-row td { font-weight: bold; }
        .declaration { margin-top: 20px; font-size: 12px; }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    @include('invoices._campaign-invoice-body')
</body>
</html>
