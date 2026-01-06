@extends('layouts.layoutS')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <div id="printableArea" class="bg-white p-6">
        @include('report.report_contents', ['patient' => $patient])
    </div>

    <div class="mt-4 flex justify-center gap-4 no-print">
        <a href="{{ route('patientrecord.report.download', $patient->id) }}"
           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            💾 Save as PDF
        </a>

        <button onclick="printReport()" 
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
            🖨️ Print Report
        </button>
    </div>
</div>

<script>
function printReport() {
    const originalContents = document.body.innerHTML;
    const printContents = document.getElementById('printableArea').innerHTML;

    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}
</script>
@endsection
