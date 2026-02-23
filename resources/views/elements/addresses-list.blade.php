@php
    $addresses = $addresses ?? [];
    $showRadio = $showRadio ?? false;
    $selectedAddressId = $selectedAddressId ?? null;
@endphp

<div class="addresses-list-container">
    @if(!empty($addresses) && count($addresses) > 0)
        @foreach($addresses as $address)
            @php
                $isDefault = isset($address['default_delivery_address']) && $address['default_delivery_address'] == 1;
                $isSelected = $selectedAddressId == $address['id'];
            @endphp
            
            <div class="saved-address-box {{ $isDefault ? 'default-address' : '' }} {{ $isSelected ? 'selected' : '' }}">
                <div class="adrs-section">
                    <div class="email-field-t">
                        @if($showRadio)
                            <div class="address-radio">
                                <input type="radio" 
                                       name="delivery_address_id" 
                                       id="address_{{ $address['id'] }}" 
                                       value="{{ $address['id'] }}" 
                                       {{ $isDefault ? 'checked' : '' }}>
                            </div>
                        @endif
                        
                        <label for="address_{{ $address['id'] }}" class="email-text-t">
                            <span class="address-type-name {{ $address['address_type'] ?? 'home' }}">
                                {{ ucfirst($address['address_type'] ?? 'Home') }}
                                @if($isDefault)
                                    <span class="badge badge-primary ml-2">
                                        {{ $language_name == 'french' ? 'Adresse par défaut' : 'Default Delivery Address' }}
                                    </span>
                                @endif
                            </span>
                            <br>
                            <span class="address-name">
                                {{ ucfirst($address['name'] ?? '') }} 
                                {{ $address['mobile'] ?? '' }} 
                                @if(!empty($address['alternate_phone']))
                                    , {{ $address['alternate_phone'] }}
                                @endif
                                @if(!empty($address['company_name']))
                                    ({{ $address['company_name'] }})
                                @endif
                            </span>
                            <br>
                            <span class="tt-t address-details">
                                {{ $address['address'] ?? '' }},
                                {{ $address['cityName'] ?? $address['city'] ?? '' }}, 
                                {{ $address['StateName'] ?? $address['state'] ?? '' }}, 
                                {{ $address['CountryName'] ?? $address['country'] ?? '' }} - 
                                <strong>{{ $address['pin_code'] ?? '' }}</strong>
                            </span>
                        </label>
                        
                        <div class="dot-menu">
                            <button type="button" class="dot-menu-btn">
                                <i class="fa fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dot-menu-section">
                                <a href="{{ url('MyAccounts/addEditAddress/' . base64_encode($address['id'])) }}" class="dropdown-item">
                                    <i class="las la-edit"></i>
                                    {{ $language_name == 'french' ? 'Éditer' : 'Edit' }}
                                </a>
                                <a href="{{ url('MyAccounts/deleteAddress/' . base64_encode($address['id'])) }}" 
                                   class="dropdown-item text-danger"
                                   onclick="return confirm('{{ $language_name == 'french' ? 'Êtes-vous sûr de vouloir supprimer cette adresse?' : 'Are you sure you wish to delete this address?' }}');">
                                    <i class="las la-trash"></i>
                                    {{ $language_name == 'french' ? 'Supprimer' : 'Delete' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="empty-addresses text-center py-5">
            <i class="las la-map-marker-alt" style="font-size: 48px; color: #ccc;"></i>
            <p class="mt-3">
                {{ $language_name == 'french' ? 'Aucune adresse enregistrée' : 'No saved addresses' }}
            </p>
        </div>
    @endif
</div>

<style>
    .addresses-list-container {
        width: 100%;
    }
    
    .saved-address-box {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .saved-address-box:hover {
        border-color: #007bff;
        box-shadow: 0 4px 12px rgba(0,123,255,0.15);
    }
    
    .saved-address-box.default-address {
        border-color: #28a745;
        background: #f8fff9;
    }
    
    .saved-address-box.selected {
        border-color: #007bff;
        background: #f0f8ff;
    }
    
    .adrs-section {
        position: relative;
    }
    
    .email-field-t {
        display: flex;
        align-items: flex-start;
        gap: 15px;
    }
    
    .address-radio {
        flex-shrink: 0;
        padding-top: 3px;
    }
    
    .address-radio input[type="radio"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
    
    .email-text-t {
        flex: 1;
        cursor: pointer;
    }
    
    .address-type-name {
        font-weight: 700;
        font-size: 16px;
        color: #333;
        text-transform: capitalize;
        display: inline-block;
        margin-bottom: 8px;
    }
    
    .address-type-name.home {
        color: #007bff;
    }
    
    .address-type-name.work {
        color: #6c757d;
    }
    
    .badge {
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 12px;
        font-weight: 600;
    }
    
    .badge-primary {
        background: #28a745;
        color: white;
    }
    
    .address-name {
        font-size: 14px;
        color: #555;
        font-weight: 500;
        display: block;
        margin: 5px 0;
    }
    
    .address-details {
        font-size: 14px;
        color: #666;
        line-height: 1.6;
        display: block;
    }
    
    .dot-menu {
        position: relative;
        flex-shrink: 0;
    }
    
    .dot-menu-btn {
        background: transparent;
        border: none;
        padding: 8px 12px;
        cursor: pointer;
        font-size: 18px;
        color: #666;
        transition: all 0.3s ease;
        border-radius: 4px;
    }
    
    .dot-menu-btn:hover {
        background: #f0f0f0;
        color: #333;
    }
    
    .dot-menu-section {
        display: none;
        position: absolute;
        right: 0;
        top: 100%;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        min-width: 150px;
        z-index: 1000;
        margin-top: 5px;
    }
    
    .dot-menu:hover .dot-menu-section,
    .dot-menu-section:hover {
        display: block;
    }
    
    .dot-menu-section .dropdown-item {
        display: block;
        padding: 10px 15px;
        color: #333;
        text-decoration: none;
        transition: background 0.2s ease;
        font-size: 14px;
    }
    
    .dot-menu-section .dropdown-item:hover {
        background: #f8f9fa;
    }
    
    .dot-menu-section .dropdown-item.text-danger {
        color: #dc3545;
    }
    
    .dot-menu-section .dropdown-item.text-danger:hover {
        background: #fff5f5;
    }
    
    .dot-menu-section .dropdown-item i {
        margin-right: 8px;
        font-size: 16px;
    }
    
    .empty-addresses {
        padding: 40px 20px;
        background: #f8f9fa;
        border-radius: 8px;
        color: #999;
    }
    
    .empty-addresses i {
        font-size: 64px;
        color: #ddd;
    }
    
    .empty-addresses p {
        font-size: 16px;
        margin: 0;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .saved-address-box {
            padding: 15px;
        }
        
        .email-field-t {
            flex-direction: column;
            gap: 10px;
        }
        
        .address-radio {
            padding-top: 0;
        }
        
        .dot-menu {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        
        .address-type-name {
            font-size: 14px;
        }
        
        .address-name,
        .address-details {
            font-size: 13px;
        }
        
        .dot-menu-section {
            right: 0;
            left: auto;
        }
    }
    
    @media (max-width: 480px) {
        .saved-address-box {
            padding: 12px;
        }
        
        .badge {
            display: block;
            margin-top: 5px;
            width: fit-content;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle radio button selection
        const radioButtons = document.querySelectorAll('.address-radio input[type="radio"]');
        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove selected class from all boxes
                document.querySelectorAll('.saved-address-box').forEach(box => {
                    box.classList.remove('selected');
                });
                
                // Add selected class to the parent box
                const parentBox = this.closest('.saved-address-box');
                if (parentBox) {
                    parentBox.classList.add('selected');
                }
            });
        });
        
        // Handle clicking on the label to select radio
        const labels = document.querySelectorAll('.email-text-t');
        labels.forEach(label => {
            label.addEventListener('click', function(e) {
                if (e.target.tagName !== 'A') {
                    const radio = this.parentElement.querySelector('input[type="radio"]');
                    if (radio) {
                        radio.checked = true;
                        radio.dispatchEvent(new Event('change'));
                    }
                }
            });
        });
    });
</script>
