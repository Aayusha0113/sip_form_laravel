<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Scanned Documents - {{ $companyName }}</title>
<style>
body { 
    font-family: Arial, sans-serif; 
    padding:20px; 
    background:#eef2f6; 
    margin: 0;
}
h2 { 
    text-align:center; 
    margin-bottom:20px; 
    color:#0a3d62; 
    font-weight: 600;
}
.gallery { 
    display:flex; 
    flex-wrap:wrap; 
    gap:15px; 
    justify-content:center; 
    max-width: 1200px;
    margin: 0 auto;
}
.gallery .item { 
    border:1px solid #ccc; 
    border-radius:8px; 
    padding:10px; 
    background:white; 
    width:220px; 
    text-align:center; 
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.gallery .item:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}
.gallery img { 
    max-width:100%; 
    height:auto; 
    border-radius:6px; 
    cursor:pointer; 
    transition: transform 0.2s; 
    display: block;
    margin-bottom: 8px;
}
.gallery img:hover { 
    transform: scale(1.05); 
}
.file-link { 
    display:block; 
    margin-top:8px; 
    color:#0a3d62; 
    text-decoration:none; 
    font-size:14px; 
    font-weight: 500;
    padding: 8px 12px;
    border: 1px solid #0a3d62;
    border-radius: 4px;
    transition: all 0.2s ease;
}
.file-link:hover { 
    background: #0a3d62;
    color: white;
    text-decoration:none;
}
.source-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
}
.source-database {
    background: #28a745;
    color: white;
}
.source-filesystem {
    background: #17a2b8;
    color: white;
}
.button-bar { 
    text-align:center; 
    margin-top:30px; 
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.button-bar a { 
    text-decoration:none; 
    color:white; 
    background:#0a3d62; 
    padding:12px 24px; 
    border-radius:6px; 
    margin:0 8px; 
    cursor:pointer; 
    font-weight: 500;
    transition: background 0.2s ease;
}
.button-bar a:hover { 
    background:#062c49; 
}
.no-documents {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 8px;
    border: 2px dashed #ccc;
    max-width: 600px;
    margin: 0 auto;
}
.no-documents i {
    font-size: 48px;
    color: #999;
    margin-bottom: 15px;
}
.no-documents h3 {
    color: #666;
    margin: 0 0 10px 0;
}
.no-documents p {
    color: #999;
    margin: 0;
}
</style>
</head>
<body>

<h2>Scanned Documents - {{ $companyName }}</h2>

Debug: Company = {{ $company->company_name ?? 'No company' }}
Debug: SIP = {{ $company->sip_number ?? 'No SIP' }}
Debug: Document Count = {{ count($allDocuments) }}
Debug: First Doc = {{ $allDocuments[0]['file_name'] ?? 'No docs' }}

@if(count($allDocuments) > 0)
    <div class="gallery">
        @foreach($allDocuments as $doc)
            <div class="item" style="position: relative;">
                <span class="source-badge source-{{ $doc['source'] }}">
                    {{ $doc['source'] }}
                </span>
                @if(in_array(strtolower(pathinfo($doc['file_name'], PATHINFO_EXTENSION)), ['jpg','jpeg','png','gif']))
                    @if($doc['source'] === 'database')
                        <a href="{{ asset($doc['file_path']) }}" target="_blank">
                            <img src="{{ asset($doc['file_path']) }}" alt="Document">
                        </a>
                    @else
                        <a href="{{ asset($doc['file_path']) }}" target="_blank">
                            <img src="{{ asset($doc['file_path']) }}" alt="Document">
                        </a>
                    @endif
                @else
                    @if($doc['source'] === 'database')
                        <a href="{{ asset($doc['file_path']) }}" target="_blank" class="file-link">
                            📄 {{ $doc['file_name'] }}
                        </a>
                    @else
                        <a href="{{ asset($doc['file_path']) }}" target="_blank" class="file-link">
                            📄 {{ $doc['file_name'] }}
                        </a>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
@else
    <div class="no-documents">
        <i class="fas fa-folder-open"></i>
        <h3>No Documents Found</h3>
        <p>No documents have been uploaded for this SIP number yet.</p>
    </div>
@endif

<div class="button-bar">
    <a href="{{ url()->previous() }}">Back to Dashboard</a>
    <a href="javascript:history.back()">Back to Previous Page</a>
</div>

</body>
</html>
