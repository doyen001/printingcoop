{{-- CI: application/views/elements/HomeSections/ecoink_search.php --}}

<style>
    .eco-search-section {
        padding: 100px 0;
        background: linear-gradient(135deg, #ffffff 0%, var(--background-light) 100%);
        position: relative;
        overflow: hidden;
    }

    .eco-search-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at top right, rgba(255, 107, 53, 0.05) 0%, transparent 70%);
        z-index: 1;
    }

    .eco-search-section .container {
        position: relative;
        z-index: 2;
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .ecosearch-inner {
        background: #ffffff;
        border-radius: 30px;
        padding: 50px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .ecosearch-inner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
    }

    .ecosearch-inner > span {
        display: block;
        text-align: center;
        font-size: 2.4rem;
        font-weight: 800;
        color: var(--secondary-color);
        margin-bottom: 40px;
        position: relative;
    }

    .ecosearch-inner > span::after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
        border-radius: 2px;
    }

    .ecosearch-select-single {
        position: relative;
        margin-bottom: 30px;
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s ease forwards;
    }

    .ecosearch-select-single:nth-child(2) { animation-delay: 0.2s; }
    .ecosearch-select-single:nth-child(3) { animation-delay: 0.4s; }
    .ecosearch-select-single:nth-child(4) { animation-delay: 0.6s; }

    .ecosearch-select-single > span {
        position: absolute;
        left: -40px;
        top: 50%;
        transform: translateY(-50%);
        width: 30px;
        height: 30px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.9rem;
        box-shadow: 0 4px 10px rgba(255, 107, 53, 0.2);
    }

    .ecosearch-select-single select {
        width: 100%;
        padding: 18px 25px;
        border: 2px solid #e1e8ed;
        border-radius: 15px;
        font-size: 1rem;
        color: var(--secondary-color);
        background: #ffffff;
        transition: all 0.3s ease;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%232d3436' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        background-size: 20px;
    }

    .ecosearch-select-single select:hover {
        border-color: var(--primary-color);
    }

    .ecosearch-select-single select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
    }

    .ecosearch-select-single select option {
        padding: 10px;
    }

    .ecosearch-inner button {
        display: block;
        width: 100%;
        padding: 20px;
        margin-top: 40px;
        border: none;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: #ffffff;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s ease forwards 0.8s;
    }

    .ecosearch-inner button::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }

    .ecosearch-inner button:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(255, 107, 53, 0.3);
    }

    .ecosearch-inner button:hover::before {
        transform: translateX(100%);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .eco-search-section {
            padding: 60px 0;
        }

        .ecosearch-inner {
            padding: 30px 20px;
            border-radius: 20px;
        }

        .ecosearch-inner > span {
            font-size: 1.8rem;
            margin-bottom: 30px;
        }

        .ecosearch-select-single {
            margin-bottom: 20px;
        }

        .ecosearch-select-single > span {
            left: 0;
            top: -35px;
        }

        .ecosearch-select-single select {
            padding: 15px 20px;
            font-size: 0.95rem;
        }

        .ecosearch-inner button {
            padding: 15px;
            font-size: 1rem;
            margin-top: 30px;
        }
    }
</style>

<div class="eco-search-section">
    <div class="container">
        <form action="{{ url('Products') }}" method="get">
            <input type="hidden" name="category_id" value="{{ base64_encode('13') }}">
            <div class="ecosearch-inner">
                <span>{{ $language_name == 'french' ? "Recherche d'encre et de toner" : 'Ink & Toner Finder' }}</span>
                <div class="ecosearch-select-single">
                    <span>1</span>
                    <select name="printer_brand" required id="printer_brand" onchange="PrinterSeries($(this).val())">
                        <option value="">{{ $language_name == 'french' ? "Sélectionnez une marque d'imprimante" : 'Select a Printer Brand' }}</option>
                        @if(isset($PrinterBrandsLists))
                            @foreach($PrinterBrandsLists as $val)
                                @php
                                    $name = $language_name == 'french' ? ($val['name_french'] ?? $val['name']) : $val['name'];
                                @endphp
                                <option value="{{ $name }}">{{ $name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="ecosearch-select-single">
                    <span>2</span>
                    <select name="printer_series" id="printer_series" onchange="PrinterModel($(this).val())">
                        <option value="">{{ $language_name == 'french' ? "Sélectionnez une série d'imprimantes" : 'Select a Printer Series' }}</option>
                    </select>
                </div>
                <div class="ecosearch-select-single">
                    <span>3</span>
                    <select name="printer_models" id="printer_models">
                        <option value="">{{ $language_name == 'french' ? "Sélectionnez un modèle d'imprimante" : 'Select a Printer Model' }}</option>
                    </select>
                </div>
                <button type="submit">{{ $language_name == 'french' ? 'Rechercher' : 'Search' }}</button>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    // Add loading state to selects during AJAX calls
    function setLoadingState(selectElement, isLoading) {
        const select = $(selectElement);
        if (isLoading) {
            select.css('background-image', 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%232d3436\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3E%3Cline x1=\'12\' y1=\'2\' x2=\'12\' y2=\'6\'%3E%3C/line%3E%3Cline x1=\'12\' y1=\'18\' x2=\'12\' y2=\'22\'%3E%3C/line%3E%3Cline x1=\'4.93\' y1=\'4.93\' x2=\'7.76\' y2=\'7.76\'%3E%3C/line%3E%3Cline x1=\'16.24\' y1=\'16.24\' x2=\'19.07\' y2=\'19.07\'%3E%3C/line%3E%3Cline x1=\'2\' y1=\'12\' x2=\'6\' y2=\'12\'%3E%3C/line%3E%3Cline x1=\'18\' y1=\'12\' x2=\'22\' y2=\'12\'%3E%3C/line%3E%3Cline x1=\'4.93\' y1=\'19.07\' x2=\'7.76\' y2=\'16.24\'%3E%3C/line%3E%3Cline x1=\'16.24\' y1=\'7.76\' x2=\'19.07\' y2=\'4.93\'%3E%3C/line%3E%3C/svg%3E")');
            select.css('background-size', '20px');
            select.prop('disabled', true);
        } else {
            select.css('background-image', 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%232d3436\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3E%3Cpolyline points=\'6 9 12 15 18 9\'%3E%3C/polyline%3E%3C/svg%3E")');
            select.css('background-size', '20px');
            select.prop('disabled', false);
        }
    }

    // Override the global PrinterSeries function
    window.PrinterSeries = function(brand) {
        if (!brand) return;
        
        const seriesSelect = $('#printer_series');
        const modelSelect = $('#printer_models');
        
        // Reset models
        modelSelect.html('<option value="">{{ $language_name == "french" ? "Sélectionnez un modèle d\'imprimante" : "Select a Printer Model" }}</option>');
        
        // Show loading state
        setLoadingState(seriesSelect, true);

        $.ajax({
            url: '{{ url("Home/PrinterSeries") }}',
            type: 'post',
            data: { 
                brand: brand,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                seriesSelect.html(data);
                setLoadingState(seriesSelect, false);
            },
            error: function() {
                setLoadingState(seriesSelect, false);
            }
        });
    };

    // Override the global PrinterModel function
    window.PrinterModel = function(series) {
        if (!series) return;
        
        const modelSelect = $('#printer_models');
        
        // Show loading state
        setLoadingState(modelSelect, true);

        $.ajax({
            url: '{{ url("Home/PrinterModel") }}',
            type: 'post',
            data: { 
                series: series,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                modelSelect.html(data);
                setLoadingState(modelSelect, false);
            },
            error: function() {
                setLoadingState(modelSelect, false);
            }
        });
    };
})();
</script>
