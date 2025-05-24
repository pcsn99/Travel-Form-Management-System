@extends('layouts.app')

@section('title', 'Edit Overseas Travel Form')

@section('styles')
<style>
    body {
        background-color: #f0f2f5;
        color: #17224D;
        font-family: 'Inter', sans-serif;
        padding: 40px;
    }

    .dashboard-header {
        background: 
            linear-gradient(to right, rgba(23, 34, 77, 0.85), rgba(23, 34, 77, 0.85)),
            url('/images/bg.jpeg') no-repeat center center;
        background-size: cover;
        padding: 40px 20px;
        font-size: 26px;
        font-weight: bold;
        text-align: center;
        color: white;
        border-bottom: 3px solid #17224D;
        margin-bottom: 40px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        border-radius: 8px;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
        background: rgba(255, 255, 255, 0.95);
        padding: 30px;
        margin-bottom: 30px;
    }

    textarea, select, input[type="text"], input[type="file"] {
        width: 100%;
        padding: 12px;
        border: 1px solid #17224D;
        border-radius: 6px;
        background: #f8f9fa;
        margin-bottom: 10px;
    }

    button {
        background-color: #17224D;
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 10px 20px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        margin-top: 10px;
    }

    button:hover {
        background-color: #1f2f5f;
    }

    .btn-danger {
        background-color: red;
        margin-top: 20px;
    }

    .file-list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }

    .file-list form {
        display: inline;
    }

    a button {
        margin-top: 30px;
        background-color: #6c757d;
    }

    .request-info {
        background-color: #f8f9fa;
        border-left: 4px solid #17224D;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 30px;
    }
</style>
@endsection

@section('content')
<div class="dashboard-header">Edit Overseas Travel Form</div>

<div class="container-custom">
    <div class="request-info">
        <h5>📋 Travel Request Summary</h5>
        <p><strong>Type:</strong> {{ ucfirst($form->request->type) }}</p>
        <p><strong>Departure:</strong> {{ $form->request->intended_departure_date }}</p>
        <p><strong>Return:</strong> {{ $form->request->intended_return_date }}</p>
        @if($form->request->admin_comment)
            <p><strong>Request Remarks:</strong> {{ $form->admin_comment }}</p>
        @endif

        @if($form->admin_comment)
            <p><strong>Form Remarks:</strong> {{ $form->admin_comment }}</p>
        @endif
    </div>

    <div class="card">
        @if(session('success'))
            <p style="color: green">{{ session('success') }}</p>
        @endif

        {{-- Answer Questions --}}
        <form id="mainTravelForm" method="POST" action="{{ route('member.Overseas-forms.update', $form->id) }}">

            @csrf

            @foreach($questions as $q)
                @php
                    $choices = is_array($q->choices) ? $q->choices : json_decode($q->choices ?? '[]', true);
                    $existingAnswer = $form->answers->where('question_id', $q->id)->first()?->answer ?? '';
                    $isOther = $q->type === 'choice' && $q->allow_other && !in_array($existingAnswer, $choices);
                @endphp

                <div style="margin-bottom: 20px;">
                    <label><strong>{{ $q->question }}</strong></label><br>

                    @if($q->type === 'text')
                        <textarea name="answers[{{ $q->id }}]" rows="2" required>{{ $existingAnswer }}</textarea>

                    @elseif($q->type === 'choice')
                        <select name="answers[{{ $q->id }}]" onchange="toggleOther(this, '{{ $q->id }}')" required>
                            <option value="">-- Select an option --</option>
                            @foreach($choices as $choice)
                                <option value="{{ $choice }}" {{ $existingAnswer === $choice ? 'selected' : '' }}>
                                    {{ $choice }}
                                </option>
                            @endforeach
                            @if($q->allow_other)
                                <option value="__other__" {{ $isOther ? 'selected' : '' }}>Other (please specify)</option>
                            @endif
                        </select>

                        @if($q->allow_other)
                            <input 
                                type="text" 
                                name="answers_other[{{ $q->id }}]" 
                                id="other-{{ $q->id }}" 
                                placeholder="Other..."
                                style="margin-top: 5px; display: {{ $isOther ? 'inline-block' : 'none' }};"
                                value="{{ $isOther ? $existingAnswer : '' }}"
                            >
                        @endif
                    @endif
                </div>
            @endforeach

            <button type="button" class="btn btn-success" onclick="handleFormSubmit()">✅ Submit Form</button>


        </form>
    </div>

    <script>
        function handleFormSubmit() {
            const hasSignature = @json(Auth::user()->signature !== null);

            if (!hasSignature) {
                const modal = new bootstrap.Modal(document.getElementById('uploadSignatureModal'));
                modal.show();
                return;
            }

            document.getElementById('mainTravelForm').submit();
        }
    </script>

    @php
        $showUpload = in_array($form->status, ['submitted', 'rejected']);
    @endphp

    @if($showUpload)
    <div class="card mt-4">
        <h4 class="mb-3">
            <i class="bi bi-upload me-2"></i>Upload Additional Requirement
        </h4>

        <form action="{{ route('attachments.upload') }}" method="POST" enctype="multipart/form-data" class="mb-4">
            @csrf
            <input type="hidden" name="form_type" value="Overseas">
            <input type="hidden" name="form_id" value="{{ $form->id }}">

            <div class="mb-3">
                <input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-cloud-arrow-up me-1"></i>Upload
            </button>
        </form>

        @if($form->attachments->count())
        <h4 class="mb-3"><i class="bi bi-paperclip me-2"></i>Uploaded Files</h4>
        <ul class="list-group">
            @foreach($form->attachments as $file)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ route('attachments.download', $file->id) }}" target="_blank">
                        {{ $file->original_name }}
                    </a>
                    <form action="{{ route('attachments.delete', $file->id) }}" method="POST" class="ms-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </li>
            @endforeach
        </ul>
        @endif
    </div>
    @else
        <p class="mt-4 text-muted">
            <i class="bi bi-folder2-open me-2"></i>File upload is available after form submission.
        </p>
    @endif


    <form method="POST" action="{{ route('member.Overseas-forms.cancel', $form->id) }}"
        onsubmit="return confirm('Are you sure you want to cancel this travel form? This cannot be undone.');">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn-danger">❌ Cancel Form</button>
    </form>

    <a href="{{ route('dashboard') }}">
        <button>⬅ Back to Dashboard</button>
    </a>
</div>

<!-- Upload Signature Modal -->
<div class="modal fade" id="uploadSignatureModal" tabindex="-1" aria-labelledby="uploadSignatureModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadSignatureModalLabel">Upload Signature Required</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">You must upload a signature before submitting this form. This will be used in your export file.</p>

                @if(Auth::user()->signature)
                    <p><strong>Current Signature:</strong></p>
                    <img src="{{ asset('shared/' . Auth::user()->signature) }}" class="img-fluid mb-3" style="max-height: 120px;">
                @endif

                <form id="signatureUploadForm" enctype="multipart/form-data">
                    @csrf
                    <label for="signature"><strong>Select Signature File (PNG, JPG):</strong></label>
                    <input type="file" name="signature" class="form-control mb-3" required accept=".jpg,.jpeg,.png">
                    <button type="submit" class="btn btn-primary">Upload Signature</button>
                </form>

                <div id="signatureUploadMessage" class="mt-2 text-success" style="display:none;">
                    ✅ Signature uploaded. Submitting form...
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.getElementById('uploadSignatureModal').addEventListener('shown.bs.modal', function () {
        document.querySelector('#uploadSignatureModal input[name="signature"]').focus();
    });
</script>

<script>
    document.getElementById('uploadSignatureModal').addEventListener('shown.bs.modal', function () {
        document.querySelector('#uploadSignatureModal input[name="signature"]').focus();
    });

    document.getElementById('signatureUploadForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch("{{ route('member.signature.upload') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) throw new Error('Upload failed');
            return response.json(); 
        })
        .then(data => {
            document.getElementById('signatureUploadMessage').style.display = 'block';
            setTimeout(() => {
                bootstrap.Modal.getInstance(document.getElementById('uploadSignatureModal')).hide();
                document.getElementById('mainTravelForm').submit();
            }, 1500);
        })
        .catch(error => {
            alert("Failed to upload signature. Please try again.");
            console.error(error);
        });
    });
</script>



<script>
    function toggleOther(selectEl, id) {
        const otherInput = document.getElementById('other-' + id);
        if (selectEl.value === '__other__') {
            otherInput.style.display = 'inline-block';
            otherInput.required = true;
        } else {
            otherInput.style.display = 'none';
            otherInput.required = false;
            otherInput.value = '';
        }
    }
</script>
@endsection
