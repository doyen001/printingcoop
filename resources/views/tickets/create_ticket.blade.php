@extends('elements.app')

@section('content')
<div class="universal-spacing universal-bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="universal-dark-title mb-4">
                    <h2>{{ $language_name == 'french' ? 'Créer un ticket de support' : 'Create Support Ticket' }}</h2>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div id="conntent">
                    @if(session('save_success'))
                        <div class="alert alert-success text-center">
                            {{ $language_name == 'french' ? 'Ticket créé avec succès!' : 'Ticket created successfully!' }}
                        </div>
                        <script>
                            setTimeout(function() {
                                window.location.href = "{{ url('Tickets/index') }}";
                            }, 2000);
                        </script>
                    @endif
                    
                    @if(session('message_error'))
                        <div class="alert alert-danger text-center">
                            {{ session('message_error') }}
                        </div>
                    @endif
                    
                    <div class="new-ticket-section-fields">
                        <form method="POST" action="{{ url('Tickets/createTicket') }}" class="form-horizontal" id="TicketCreateFrom" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            <span style="color:red">*</span> 
                                            {{ $language_name == 'french' ? 'Votre nom' : 'Your Name' }}
                                        </label>
                                        <input type="text" 
                                               name="name" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               value="{{ old('name', $user->name ?? '') }}" 
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            <span style="color:red">*</span> 
                                            {{ $language_name == 'french' ? 'Votre email' : 'Your Email' }}
                                        </label>
                                        <input type="email" 
                                               name="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               value="{{ old('email', $user->email ?? '') }}" 
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>
                                            <span style="color:red">*</span> 
                                            {{ $language_name == 'french' ? 'Votre numéro de contact' : 'Your Contact no.' }}
                                        </label>
                                        <input type="tel" 
                                               name="contact_no" 
                                               class="form-control @error('contact_no') is-invalid @enderror" 
                                               value="{{ old('contact_no', $postData['contact_no'] ?? '') }}" 
                                               required>
                                        @error('contact_no')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>
                                            <span style="color:red">*</span> 
                                            {{ $language_name == 'french' ? 'Sujet' : 'Subject' }}
                                        </label>
                                        <input type="text" 
                                               name="subject" 
                                               class="form-control @error('subject') is-invalid @enderror" 
                                               value="{{ old('subject', $postData['subject'] ?? '') }}" 
                                               required>
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>
                                            <span style="color:red">*</span> 
                                            {{ $language_name == 'french' ? 'Votre message' : 'Your Message' }}
                                        </label>
                                        <textarea name="message" 
                                                  class="form-control @error('message') is-invalid @enderror" 
                                                  rows="6" 
                                                  required>{{ old('message', $postData['message'] ?? '') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="new-ticket-button">
                                        <button type="submit" id="TicketCreateFromSubmit" class="btn btn-primary">
                                            {{ $language_name == 'french' ? 'Soumettre' : 'Submit' }}
                                        </button>
                                        <a href="{{ url('Tickets/index') }}" class="btn btn-secondary ml-2">
                                            {{ $language_name == 'french' ? 'Annuler' : 'Cancel' }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .new-ticket-section-fields {
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .new-ticket-section-fields .form-group {
        margin-bottom: 20px;
    }
    
    .new-ticket-section-fields label {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        color: #333;
    }
    
    .new-ticket-section-fields input[type="text"],
    .new-ticket-section-fields input[type="email"],
    .new-ticket-section-fields input[type="tel"],
    .new-ticket-section-fields textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }
    
    .new-ticket-section-fields input:focus,
    .new-ticket-section-fields textarea:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    
    .new-ticket-section-fields textarea {
        resize: vertical;
        min-height: 120px;
    }
    
    .new-ticket-button {
        margin-top: 20px;
        text-align: right;
    }
    
    .new-ticket-button button,
    .new-ticket-button a {
        padding: 12px 30px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 4px;
        transition: all 0.3s ease;
    }
    
    .new-ticket-button button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 14px;
        margin-top: 5px;
    }
    
    .is-invalid {
        border-color: #dc3545;
    }
    
    @media (max-width: 768px) {
        .new-ticket-section-fields {
            padding: 20px 15px;
        }
        
        .new-ticket-button {
            text-align: center;
        }
        
        .new-ticket-button button,
        .new-ticket-button a {
            width: 100%;
            margin-bottom: 10px;
        }
    }
</style>

<script>
    $(document).ready(function() {
        $('#TicketCreateFrom').submit(function(e) {
            e.preventDefault();
            
            var form = $(this);
            var formsubmit = true;
            $('#TicketCreateFromSubmit').attr("disabled", true);
            
            if (formsubmit == true) {
                var url = "{{ url('Tickets/createTicket') }}";
                
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    success: function(data) {
                        $('#conntent').html(data);
                    },
                    error: function(error) {
                        $('#TicketCreateFromSubmit').attr("disabled", false);
                        
                        var errorMsg = "{{ $language_name == 'french' ? 'Une erreur s\'est produite. Veuillez réessayer.' : 'An error occurred. Please try again.' }}";
                        
                        if (error.responseJSON && error.responseJSON.message) {
                            errorMsg = error.responseJSON.message;
                        }
                        
                        alert(errorMsg);
                    }
                });
            } else {
                $('#TicketCreateFromSubmit').attr("disabled", false);
            }
        });
    });
</script>
@endsection
