{{-- CI: application/views/Products/product_detail.php --}}
<div class="col-md-12 col-md-12 col-md-8">
  @php
    $sina = config('sina');
    $shipping_extra_days = $sina['shipping_extra_days'] ?? 0;
  @endphp
  
  {{-- Dynamic Attribute Form Fields --}}
  @foreach($attributes as $attribute)
    <div class="single-review attribute-{{ str_replace(' ', '-', $attribute['attribute_id']) }} {{ ($attribute['type'] == 2) ? 'size' : '' }}">
      <label>{{ ucfirst($language_name == 'French' ? $attribute['label_fr'] : $attribute['label']) }} <span class="required">*</span></label>
      
      @if($attribute['use_items'] == 0)
        @if($attribute['value_min'] > 0 || $attribute['value_max'] > 0)
          <input type="number" class="attribute field" name="attributes[{{ $attribute['attribute_id'] }}]" required id="attribute-{{ $attribute['attribute_id'] }}" 
            placeholder="{{ ($attribute['value_min'] > 0 ? $attribute['value_min'] : '') . ' ~ ' . ($attribute['value_max'] > 0 ? $attribute['value_max'] : '') }}"
            {!! $attribute['value_min'] > 0 ? 'min="' . $attribute['value_min'] . '"' : '' !!} {!! $attribute['value_max'] > 0 ? 'max="' . $attribute['value_max'] . '"' : '' !!}>
        @else
          <input type="text" class="attribute field" name="attributes[{{ $attribute['attribute_id'] }}]" required id="attribute-{{ $attribute['attribute_id'] }}">
        @endif
      @else
        <select class="attribute field" name="attributes[{{ $attribute['attribute_id'] }}]" required id="attribute-{{ $attribute['attribute_id'] }}">
          <option value="">
            {{ ucfirst($language_name == 'French' ? "Sélectionnez {$attribute['label_fr']}" : "Select {$attribute['label']}") }}
          </option>
          @foreach($attribute_items as $item)
            @if($item['attribute_id'] == $attribute['attribute_id'])
              <option value="{{ $item['attribute_item_id'] }}">
                {{ ucfirst($language_name == 'French' ? $item['attribute_item_name_fr'] : $item['attribute_item_name']) }}
              </option>
            @endif
          @endforeach
        </select>
      @endif
      <span style="color:red" id="attribute-{{ $attribute['attribute_id'] }}_error"></span>
    </div>
    
    {{-- Custom Size Options --}}
    @if($Product['use_custom_size'] == 1)
      @if($attribute['use_items'] != 0 && $attribute['type'] == 2)
        <div class="single-review">
          <label for="custom_size">{{ $language_name == 'french' ? 'Besoin d\'une taille personnalisée?' : 'Need a custom size?' }}</label>
          <label for="custom_size" class="attribute field">
            <input type="checkbox" id="custom_size" name="custom[size][use]" value="1">
            {{ $language_name == 'french' ? 'Oui' : 'Yes' }}
          </label>
        </div>
        <div class="single-review custom-field custom-size d-none">
          <label for="custom_size_width">{{ $language_name == 'french' ? 'Largeur' : 'Width' }} <span class="required">*</span></label>
          <input class="attribute field" type="number" id="custom_size_width" name="custom[size][width]" data-field="width">
        </div>
        <div class="single-review custom-field custom-size d-none">
          <label for="custom_size_length">{{ $language_name == 'french' ? 'Longueur' : 'Length' }} <span class="required">*</span></label>
          <input class="attribute field" type="number" id="custom_size_length" name="custom[size][length]" data-field="length">
        </div>
      @endif
    @endif
  @endforeach

  @php
    // Check if product belongs to Color Copies & BW Copies category
    $showCustomization = false;
    
    if (isset($Product['name']) || isset($Product['description'])) {
      $productName = strtolower($Product['name'] ?? '');
      $productDesc = strtolower($Product['description'] ?? '');
      
      $keywords = ['color copy', 'color copies', 'colour copy', 'colour copies',
                  'bw copy', 'bw copies', 'b/w copy', 'b/w copies',
                  'black and white copy', 'black and white copies',
                  'photocopy', 'photocopies', 'photocopying'];
      
      foreach ($keywords as $keyword) {
        if (strpos($productName, $keyword) !== false || strpos($productDesc, $keyword) !== false) {
          $showCustomization = true;
          break;
        }
      }
    }
    
    // Get custom pricing from configurations
    $custom_pricing = [];
    $announcement = $configurations['announcement'] ?? '';
    
    if (preg_match('/<!-- CUSTOM_PRICING_DATA:(.*?) -->/', $announcement, $matches)) {
      $pricing_json = $matches[1];
      $custom_pricing = json_decode($pricing_json, true) ?: [];
    }
    
    $turnaround_standard_price = $custom_pricing['turnaround_standard_price'] ?? 0;
    $turnaround_rush_price = $custom_pricing['turnaround_rush_price'] ?? 15;
    $turnaround_same_day_price = $custom_pricing['turnaround_same_day_price'] ?? 25;
    $folding_price = $custom_pricing['folding_price'] ?? 5;
    $drilling_price = $custom_pricing['drilling_price'] ?? 3;
    $collate_price = $custom_pricing['collate_price'] ?? 2;
  @endphp

  {{-- Special Customization for Copy Products --}}
  @if($showCustomization)
  <div class="customize-more">
    <label>{{ $language_name == 'french' ? 'Personnaliser Plus' : 'Customize More' }} <span class="required">*</span></label>

    <div class="customize-container">
      {{-- Folding Options --}}
      <div class="customize-card">
        <div class="card-header">
          <span class="header-title">
            {{ $language_name == 'french' ? 'Options de Pliage' : 'Folding Options' }}
            <i class="fas fa-question-circle help-icon" title="{{ $language_name == 'french' ? 'Comment votre document sera plié' : 'How your document will be folded' }}"></i>
          </span>
        </div>
        <div class="options-list">
          <div class="option-item">
            <input type="radio" id="fold-none" name="folding" value="none" class="custom-radio" checked>
            <label for="fold-none" class="option-button">
              <div class="svg-icon">
                <img src="{{ url('assets/custom_images/no-folding.png') }}" alt="No Folding" width="60" height="60">
              </div>
              <span>{{ $language_name == 'french' ? 'Sans Pliage' : 'No Folding' }}</span>
            </label>
          </div>
          <div class="option-item">
            <input type="radio" id="fold-letter-out" name="folding" value="letter_out" class="custom-radio">
            <label for="fold-letter-out" class="option-button">
              <div class="svg-icon">
                <img src="{{ url('assets/custom_images/letter-fold-out.png') }}" alt="Letter Fold Out" width="60" height="60">
              </div>
              <span>{{ $language_name == 'french' ? 'Pli Lettre Extérieur' : 'Letter Fold Out' }}</span>
            </label>
          </div>
          <div class="option-item">
            <input type="radio" id="fold-letter-in" name="folding" value="letter_in" class="custom-radio">
            <label for="fold-letter-in" class="option-button">
              <div class="svg-icon">
                <img src="{{ url('assets/custom_images/letter-fold-in.png') }}" alt="Letter Fold In" width="60" height="60">
              </div>
              <span>{{ $language_name == 'french' ? 'Pli Lettre Intérieur' : 'Letter Fold In' }}</span>
            </label>
          </div>
          <div class="option-item">
            <input type="radio" id="fold-half" name="folding" value="half" class="custom-radio">
            <label for="fold-half" class="option-button">
              <div class="svg-icon">
                <img src="{{ url('assets/custom_images/half-fold.png') }}" alt="Half Fold Std" width="60" height="60">
              </div>
              <span>{{ $language_name == 'french' ? 'Demi-pli Standard' : 'Half Fold Std' }}</span>
            </label>
          </div>
          <div class="option-item">
            <input type="radio" id="fold-half-inside" name="folding" value="half_inside" class="custom-radio">
            <label for="fold-half-inside" class="option-button">
              <div class="svg-icon">
                <img src="{{ url('assets/custom_images/half-fold.png') }}" alt="Half Fold Inside" width="60" height="60">
              </div>
              <span>{{ $language_name == 'french' ? 'Demi-pli Intérieur' : 'Half Fold Inside' }}</span>
            </label>
          </div>
        </div>
      </div>

      {{-- Drilling/Hole Punching Options --}}
      <div class="customize-card">
        <div class="card-header">
          <span class="header-title">
            {{ $language_name == 'french' ? 'Perçage' : 'Drilling' }}
            <i class="fas fa-question-circle help-icon" title="{{ $language_name == 'french' ? 'Options de perforation' : 'Hole punching options' }}"></i>
          </span>
        </div>
        <div class="options-list">
          <div class="option-item">
            <input type="radio" id="drill-none" name="drilling" value="none" class="custom-radio" checked>
            <label for="drill-none" class="option-button">
              <div class="svg-icon">
                <img src="{{ url('assets/custom_images/no-drilling.png') }}" alt="No Drilling" width="60" height="60">
              </div>
              <span>{{ $language_name == 'french' ? 'Sans Perçage' : 'No Drilling' }}</span>
            </label>
          </div>
          <div class="option-item">
            <input type="radio" id="drill-3holes" name="drilling" value="3holes" class="custom-radio">
            <label for="drill-3holes" class="option-button">
              <div class="svg-icon">
                <img src="{{ url('assets/custom_images/3-holes.png') }}" alt="3 Holes" width="60" height="60">
              </div>
              <span>{{ $language_name == 'french' ? '3 Trous' : '3 Holes' }}</span>
            </label>
          </div>
        </div>
      </div>

      {{-- Collate Options --}}
      <div class="customize-card">
        <div class="card-header">
          <span class="header-title">
            {{ $language_name == 'french' ? 'Assemblage' : 'Collate' }}
            <i class="fas fa-question-circle help-icon" title="{{ $language_name == 'french' ? 'Comment organiser vos copies' : 'How to organize your copies' }}"></i>
          </span>
        </div>
        <div class="options-list">
          <div class="option-item">
            <input type="radio" id="collate-job" name="collate" value="collate" class="custom-radio" checked>
            <label for="collate-job" class="option-button">
              <div class="svg-icon">
                <img src="{{ url('assets/custom_images/collate-job.png') }}" alt="Collate Job" width="60" height="60">
              </div>
              <span>{{ $language_name == 'french' ? 'Assembler' : 'Collate Job' }}</span>
            </label>
          </div>
          <div class="option-item">
            <input type="radio" id="collate-separate" name="collate" value="separate" class="custom-radio">
            <label for="collate-separate" class="option-button">
              <div class="svg-icon">
                <img src="{{ url('assets/custom_images/separate-groups.png') }}" alt="Separate Groups" width="60" height="60">
              </div>
              <span>{{ $language_name == 'french' ? 'Groupes Séparés' : 'Separate Groups' }}</span>
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Turnaround Time Selection --}}
  <div class="single-review turnaround-time">
    <label>{{ $language_name == 'french' ? 'À quelle vitesse le voulez-vous?' : 'How fast do you want it?' }} <span class="required">*</span></label>
    <div class="turnaround-options">
      <div class="option-group">
        <input type="radio" id="turnaround-standard" name="turnaround_time" value="standard" class="turnaround-radio" checked>
        <label for="turnaround-standard" class="option-label">
          <div class="option-content">
            <div class="time-icon">
              <i class="fas fa-clock"></i>
              <span class="days">3-5</span>
            </div>
            <div class="option-details">
              <span class="option-name">{{ $language_name == 'french' ? 'Standard' : 'Standard' }}</span>
              <span class="option-price">{{ $language_name == 'french' ? '+' . $turnaround_standard_price . '$' : '+$' . $turnaround_standard_price }}</span>
            </div>
          </div>
        </label>
      </div>

      <div class="option-group">
        <input type="radio" id="turnaround-rush" name="turnaround_time" value="rush" class="turnaround-radio">
        <label for="turnaround-rush" class="option-label">
          <div class="option-content">
            <div class="time-icon">
              <i class="fas fa-clock"></i>
              <span class="days">1</span>
            </div>
            <div class="option-details">
              <span class="option-name">{{ $language_name == 'french' ? 'Service Rush' : 'Rush Service' }}</span>
              <span class="option-price">{{ $language_name == 'french' ? '+' . $turnaround_rush_price . '$' : '+$' . $turnaround_rush_price }}</span>
            </div>
          </div>
        </label>
      </div>

      <div class="option-group">
        <input type="radio" id="turnaround-same-day" name="turnaround_time" value="same_day" class="turnaround-radio">
        <label for="turnaround-same-day" class="option-label">
          <div class="option-content">
            <div class="time-icon">
              <i class="fas fa-clock"></i>
              <span class="days">0</span>
            </div>
            <div class="option-details">
              <span class="option-name">{{ $language_name == 'french' ? 'Même Jour' : 'Same Day' }}</span>
              <span class="option-price">{{ $language_name == 'french' ? '+' . $turnaround_same_day_price . '$' : '+$' . $turnaround_same_day_price }}</span>
            </div>
          </div>
        </label>
      </div>
    </div>
  </div>
  @endif

  {{-- Share Button --}}
  <div class="share-product-btn">
    <button type="button" class="btn btn-outline-primary share-btn" onclick="openShareModal()">
      <i class="fas fa-share-alt"></i> {{ $language_name == 'french' ? 'Partager' : 'Share' }}
    </button>
  </div>

  {{-- Share Modal --}}
  <div class="modal" id="shareModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            {{ $language_name == 'french' ? 'Partager le Produit' : 'Share Product' }}
          </h5>
          <button type="button" class="close" onclick="closeShareModal()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="share-url-container">
            <label for="shareUrl" class="form-label">
              {{ $language_name == 'french' ? 'Lien du Produit' : 'Product Link' }}
            </label>
            <div class="input-group">
              <input type="text" class="form-control" id="shareUrl" value="{{ url()->current() }}" readonly>
              <button class="btn btn-primary" type="button" onclick="copyShareUrl()">
                <i class="fas fa-copy"></i>
                {{ $language_name == 'french' ? 'Copier' : 'Copy' }}
              </button>
            </div>
            <div class="copy-feedback mt-2 d-none text-success">
              <i class="fas fa-check"></i>
              {{ $language_name == 'french' ? 'Lien copié!' : 'Link copied!' }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Share Modal Functions --}}
<script>
function openShareModal() {
  document.getElementById('shareModal').style.display = 'block';
  document.getElementById('shareModal').classList.add('show');
  document.body.classList.add('modal-open');

  var backdrop = document.createElement('div');
  backdrop.className = 'modal-backdrop fade show';
  document.body.appendChild(backdrop);
}

function closeShareModal() {
  document.getElementById('shareModal').style.display = 'none';
  document.getElementById('shareModal').classList.remove('show');
  document.body.classList.remove('modal-open');

  var backdrop = document.querySelector('.modal-backdrop');
  if (backdrop) {
    backdrop.remove();
  }
}

function copyShareUrl() {
  var shareUrl = document.getElementById('shareUrl');
  shareUrl.select();
  document.execCommand('copy');

  document.querySelector('.copy-feedback').classList.remove('d-none');

  setTimeout(function() {
    document.querySelector('.copy-feedback').classList.add('d-none');
  }, 3000);
}

window.onclick = function(event) {
  var modal = document.getElementById('shareModal');
  if (event.target == modal) {
    closeShareModal();
  }
}
</script>

{{-- Price Calculation Script --}}
<script>
  var attributes = {!! json_encode($attributes) !!};
  var attribute_items = {!! json_encode($attribute_items) !!};
  console.log('attribute', attributes);
  console.log('attribute_items', attribute_items);

  $(document).ready(function() {
    $('.option-width').hide();
    $('.option-length').hide();
    $('.option-diameter').hide();

    $('.single-review #custom_size').on('change', toggleCustomSize);
    $('.single-review select').on('change', updatePrice);
    $('.single-review input').on('change', updatePrice);
    
    @if($showCustomization)
    $('.turnaround-radio').on('change', updatePrice);
    $('.custom-radio').on('change', updatePrice);
    @endif
  });
  
  function parseValue(str) {
    var v = parseFloat(str);
    if (isNaN(v))
      return null;
    else
      return v;
  }
  
  function parseSize(sizeStr) {
    var dims = sizeStr.match(/([\d\.]+)/g);
    if (dims)
      return (parseValue(dims[0]) ?? 1) * (parseValue(dims[1]) ?? 1);
    return 1;
  }
  
  function toggleCustomSize(e) {
    if ($(this).prop('checked')) {
      $('.single-review.size').addClass('disabled');
      $('.single-review.size .field').prop('disabled', true);
      $('.single-review.custom-size').removeClass('d-none');
      $('.single-review.custom-size .field').prop('required', true);
    } else {
      $('.single-review.size').removeClass('disabled');
      $('.single-review.size .field').prop('disabled', false);
      $('.single-review.custom-size').addClass('d-none');
      $('.single-review.custom-size .field').prop('required', false);
    }
    updatePrice();
  }
  
  function updatePrice()
  {
    var percentages = [];
    var quantity = 1, size = 1, width = 1, length = 1, diameter = 1, depth = 1, pages = 1, processing_fees = 0;
    var sizePrices = [];
    for (var i = 0; i < attributes.length; i++) {
      var attribute = attributes[i];
      if ($('#attribute-' + attribute.attribute_id).prop('disabled'))
        continue;
      if (attribute.use_items == 1) {
        var attribute_item_id = $('#attribute-' + attribute.attribute_id).val();
        for (var j = 0; j < attribute_items.length; j++) {
          var item = attribute_items[j];
          if (item.attribute_id != attribute.attribute_id)
            continue;
          if (item.attribute_item_id != attribute_item_id)
            continue;

            var fee = parseFloat(item.additional_fee ?? 0);

            if(item.attribute_name === "Processing fees"){
              processing_fees = 2;
              fee = 0;
            }

          if (attribute.use_percentage == 1 || attribute.type == <?= App\Common\AttributeType::Quantity ?>) {
            var percentage = parseValue(item.additional_fee) ?? 0;
            if (percentage != 0)
              percentages.push(percentage);
            // continue;
          }

          if (attribute.type == <?= App\Common\AttributeType::Quantity ?>) {
            quantity = parseValue(item.attribute_item_name) ?? 1;
            continue;
          }
          else if (attribute.type == <?= App\Common\AttributeType::Size ?>)
            size = value = parseSize(item.attribute_item_name);
          else if (attribute.type == <?= App\Common\AttributeType::Width ?>)
            width = value = parseValue(item.attribute_item_name) ?? 1;
          else if (attribute.type == <?= App\Common\AttributeType::Length ?>)
            length = value = parseValue(item.attribute_item_name) ?? 1;
          else if (attribute.type == <?= App\Common\AttributeType::Diameter ?>)
            diameter = value = parseValue(item.attribute_item_name) ?? 1;
          else if (attribute.type == <?= App\Common\AttributeType::Depth ?>)
            depth = value = parseValue(item.attribute_item_name) ?? 1;
          else if (attribute.type == <?= App\Common\AttributeType::Pages ?>)
            pages = value = parseValue(item.attribute_item_name) ?? 1;
          else
            continue;

          sizePrices.push({
            value: attribute.type == <?= App\Common\AttributeType::Diameter ?> ? value * value : value,
            additional_fee: parseValue(item.additional_fee) ?? 0,
            use_percentage: attribute.use_percentage == 1,
          });
        }
      } else {
        var valueStr = $('#attribute-' + attribute.attribute_id).val();
        var value = parseValue(valueStr) ?? 1;
        if (attribute.value_min > 0 && valueStr != '' && value < attribute.value_min) {
          $('#attribute-' + attribute.attribute_id).focus();
          kendo.alert(attribute.label<?= $language_name == 'French' ? '_fr' : '' ?> + '<?= $language_name == 'French' ? ' doit être plus grand que ' : ' should be bigger than ' ?>' + attribute.value_min);
          return;
        }
        if (attribute.value_max > 0 && valueStr != '' && value > attribute.value_max) {
          $('#attribute-' + attribute.attribute_id).focus();
          kendo.alert(attribute.label<?= $language_name == 'French' ? '_fr' : '' ?> + '<?= $language_name == 'French' ? ' doit être inférieur à ' : ' should be less than ' ?>' + attribute.value_max);
          return;
        }
        if (attribute.use_percentage == 1 || attribute.type == <?= App\Common\AttributeType::Quantity ?>) {
          var percentage = parseValue(attribute.additional_fee) ?? 0;
          if (percentage != 0)
            percentages.push(percentage);
          // continue;
        }
        if (attribute.type == <?= App\Common\AttributeType::Quantity ?>) {
          quantity = value;
          continue;
        }
        // else if (attribute.type == <?= App\Common\AttributeType::Size ?>)
        //     size = parseSize(value);
        else if (attribute.type == <?= App\Common\AttributeType::Width ?>)
          width = value;
        else if (attribute.type == <?= App\Common\AttributeType::Length ?>)
          length = value;
        else if (attribute.type == <?= App\Common\AttributeType::Diameter ?>)
          diameter = value;
        else if (attribute.type == <?= App\Common\AttributeType::Depth ?>)
          depth = value;
        else if (attribute.type == <?= App\Common\AttributeType::Pages ?>)
          pages = value;
        else
          continue;

        sizePrices.push({
          value: attribute.type == <?= App\Common\AttributeType::Diameter ?> ? value * value : value,
          additional_fee: (parseValue(attribute.additional_fee) ?? 0) * value,
          use_percentage: attribute.use_percentage == 1,
        });
      }
    }
    if ($('#custom_size').prop('checked')) {
      var customFields = $('.single-review.custom-field input');
      for (var i = 0; i < customFields.length; i++) {
        var fieldName = $(customFields[i]).attr('data-field');
        if (fieldName === 'width')
          width = parseValue($(customFields[i]).val()) ?? 1;
        else if (fieldName === 'length')
          length = parseValue($(customFields[i]).val()) ?? 1;
      }
      sizePrices.push({
        value: width * length,
        additional_fee: 0,
        use_percentage: false,
      });
    }

    <?php /* Apply size multiplication */ ?>
    var price = <?= $Product['price'] ?>;
    for (var i = 0; i < attributes.length; i++) {
      var attribute = attributes[i];
      if (attribute.use_percentage == 1)
        continue;
      if ((attribute.type == <?= App\Common\AttributeType::Quantity ?>) ||
        (attribute.type == <?= App\Common\AttributeType::Size ?>) ||
        (attribute.type == <?= App\Common\AttributeType::Width ?>) ||
        (attribute.type == <?= App\Common\AttributeType::Length ?>) ||
        (attribute.type == <?= App\Common\AttributeType::Diameter ?>) ||
        (attribute.type == <?= App\Common\AttributeType::Depth ?>) ||
        (attribute.type == <?= App\Common\AttributeType::Pages ?>))
        continue;
      if (attribute.use_items == 1) {
        var attribute_item_id = $('#attribute-' + attribute.attribute_id).val();
        for (var j = 0; j < attribute_items.length; j++) {
          var item = attribute_items[j];
          if (item.attribute_id != attribute.attribute_id)
            continue;
          if (item.attribute_item_id != attribute_item_id)
            continue;

            var fee = parseFloat(item.additional_fee ?? 0);

            if(item.attribute_name === "Processing fees"){
              processing_fees = 2;
              fee = 0;
            }

          if (attribute.fee_apply_size == 1)
            fee *= size;
          if (attribute.fee_apply_width == 1)
            fee *= width;
          if (attribute.fee_apply_length == 1)
            fee *= length;
          if (attribute.fee_apply_diameter == 1)
            fee *= diameter * diameter;
          if (attribute.fee_apply_depth == 1)
            fee *= depth;
          if (attribute.fee_apply_pages == 1)
            fee *= pages;

          price += fee;
        }
      } else {
        var fee = parseFloat(attribute.additional_fee ?? 0);

        if (attribute.fee_apply_size == 1)
          fee *= size;
        if (attribute.fee_apply_width == 1)
          fee *= width;
        if (attribute.fee_apply_length == 1)
          fee *= length;
        if (attribute.fee_apply_diameter == 1)
          fee *= diameter * diameter;
        if (attribute.fee_apply_depth == 1)
          fee *= depth;
        if (attribute.fee_apply_pages == 1)
          fee *= pages;

        price += fee;
      }
    }
    <?php /* Apply size prices */ ?>
    for (var i = 0; i < sizePrices.length; i++) {
      if (sizePrices[i].use_percentage) {
        if (sizePrices[i].additional_fee != 0 && sizePrices[i].additional_fee > -100) {
          console.log(price, sizePrices[i].additional_fee);
          price *= (100 + sizePrices[i].additional_fee) / 100;
        }
      } else {
        if (sizePrices[i].additional_fee != 0) {
          var copies = 1;
          for (var j = 0; j < sizePrices.length; j++) {
            if (j == i)
              continue;
            copies *= sizePrices[j].value;
          }
          price += sizePrices[i].additional_fee * copies;
          console.log(price, sizePrices[i].additional_fee * copies);
        }
      }
    }
    <?php /* Apply percentages */ ?>
    for (var i = 0; i < percentages.length; i++) {
      if (percentages[i] > -100)
        price *= (100 + percentages[i]) / 100.0;
    }

    <?php if ($showCustomization): ?>
    // Only apply these fees for Color Copies & BW Copies category
    var turnaroundFee = 0;
    var selectedTurnaround = $('input[name="turnaround_time"]:checked').val();

    // Get current quantity
    var currentQuantity = 1;
    
    // Look for the quantity attribute in the attributes collection
    for (var i = 0; i < attributes.length; i++) {
      if (attributes[i].type == <?= App\Common\AttributeType::Quantity ?>) {
        var quantityField = $('#attribute-' + attributes[i].attribute_id);
        if (quantityField.length > 0) {
          var qtyValue = parseInt(quantityField.val());
          if (!isNaN(qtyValue) && qtyValue > 0) {
            currentQuantity = qtyValue;
            console.log('Found quantity value:', currentQuantity);
          }
        }
        break;
      }
    }

    if (selectedTurnaround === 'rush') {
      turnaroundFee = <?= $turnaround_rush_price ?> * currentQuantity; // Multiply by quantity for per-unit pricing
    } else if (selectedTurnaround === 'same_day') {
      turnaroundFee = <?= $turnaround_same_day_price ?> * currentQuantity; // Multiply by quantity for per-unit pricing
    }

    // Add turnaround fee to processing fees
    processing_fees = turnaroundFee;

    var foldingFee = 0;
    var drillingFee = 0;
    var collateFee = 0;

    var selectedFolding = $('input[name="folding"]:checked').val();
    var selectedDrilling = $('input[name="drilling"]:checked').val();
    var selectedCollate = $('input[name="collate"]:checked').val();
    
    // Add fees based on selections - multiply by quantity as per client request
    if (selectedFolding !== 'none') {
      console.log('Folding price:', <?= $folding_price ?>, 'Quantity:', currentQuantity);
      foldingFee = <?= $folding_price ?> * currentQuantity; // Fee per unit × quantity
      console.log('Folding fee:', foldingFee);
    }
    if (selectedDrilling === '3holes') {
      console.log('Drilling price:', <?= $drilling_price ?>, 'Quantity:', currentQuantity);
      drillingFee = <?= $drilling_price ?> * currentQuantity; // Fee per unit × quantity
      console.log('Drilling fee:', drillingFee);
    }
    if (selectedCollate === 'separate') {
      console.log('Collate price:', <?= $collate_price ?>, 'Quantity:', currentQuantity);
      collateFee = <?= $collate_price ?> * currentQuantity; // Fee per unit × quantity
      console.log('Collate fee:', collateFee);
    }

    // Add to processing fees
    processing_fees += foldingFee + drillingFee + collateFee;
    console.log('Total processing fees:', processing_fees);
    <?php endif; ?>

    $('[name="price"]').val((price * quantity) + processing_fees);
    $('#total-price').html(((price * quantity) + processing_fees).toFixed(2));
  }
</script>

{{-- Comprehensive Styling --}}
<style>
/* Attribute Form Fields */
.single-review {
  margin-bottom: 20px;
}

.single-review label {
  display: block;
  margin-bottom: 10px;
  color: #183e73;
  font-weight: 500;
  font-size: 14px;
}

.single-review .required {
  color: #e74c3c;
  margin-left: 2px;
}

.single-review .field {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #e0e0e0;
  border-radius: 4px;
  font-size: 14px;
  transition: border-color 0.2s ease;
}

.single-review .field:focus {
  outline: none;
  border-color: #183e73;
  box-shadow: 0 0 0 2px rgba(24, 62, 115, 0.1);
}

.single-review.disabled {
  opacity: 0.6;
  pointer-events: none;
}

/* Custom Size Options */
.single-review.custom-size {
  background: #f9f9f9;
  padding: 15px;
  border-radius: 4px;
  border-left: 3px solid #f28738;
}

/* Customize More Container */
.customize-more {
  margin-bottom: 25px;
}

.customize-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
  margin-top: 15px;
}

.customize-card {
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  background: #fff;
}

.customize-card .card-header {
  background-color: #183e73;
  color: white;
  padding: 12px 15px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.customize-card .header-title {
  font-weight: 600;
  font-size: 15px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.customize-card .help-icon {
  cursor: help;
  font-size: 14px;
  opacity: 0.8;
  transition: opacity 0.2s ease;
}

.customize-card .help-icon:hover {
  opacity: 1;
}

.customize-card .options-list {
  display: flex;
  flex-wrap: wrap;
  padding: 15px;
  gap: 10px;
  background-color: #f9f9f9;
}

.customize-card .option-item {
  flex: 1;
  min-width: 100px;
}

.customize-card .custom-radio {
  display: none;
}

.customize-card .option-button {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 12px 10px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  background-color: white;
  cursor: pointer;
  transition: all 0.3s ease;
  height: 100%;
  text-align: center;
  position: relative;
}

.customize-card .svg-icon {
  margin-bottom: 8px;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 70px;
}

.customize-card .svg-icon img {
  max-width: 100%;
  height: auto;
}

.customize-card .option-button span {
  font-size: 13px;
  margin-top: 5px;
  font-weight: 500;
  color: #333;
}

.customize-card .custom-radio:checked + .option-button {
  border-color: #f28738;
  background-color: #fff8f7;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(242, 135, 56, 0.2);
}

.customize-card .custom-radio:checked + .option-button::after {
  content: "";
  position: absolute;
  top: 8px;
  right: 8px;
  width: 20px;
  height: 20px;
  background-color: #f28738;
  border-radius: 50%;
}

.customize-card .custom-radio:checked + .option-button::before {
  content: "✓";
  position: absolute;
  top: 8px;
  right: 8px;
  width: 20px;
  height: 20px;
  color: white;
  font-size: 12px;
  font-weight: bold;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1;
}

.customize-card .option-button:hover {
  border-color: #f28738;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Turnaround Time Styles */
.turnaround-time {
  margin-top: 25px;
}

.turnaround-options {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
  margin-top: 10px;
}

.turnaround-options .option-group {
  flex: 1;
  min-width: 200px;
}

.turnaround-radio {
  display: none;
}

.turnaround-options .option-label {
  display: block;
  padding: 15px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  background: white;
}

.turnaround-options .option-content {
  display: flex;
  align-items: center;
  gap: 15px;
}

.turnaround-options .time-icon {
  position: relative;
  width: 45px;
  height: 45px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f0f4f8;
  border-radius: 50%;
}

.turnaround-options .time-icon i {
  font-size: 20px;
  color: #183e73;
}

.turnaround-options .time-icon .days {
  position: absolute;
  top: -5px;
  right: -5px;
  background: #f28738;
  color: white;
  font-size: 11px;
  font-weight: 600;
  padding: 3px 7px;
  border-radius: 10px;
  min-width: 24px;
  text-align: center;
}

.turnaround-options .option-details {
  flex: 1;
}

.turnaround-options .option-name {
  display: block;
  color: #183e73;
  font-weight: 600;
  font-size: 15px;
  margin-bottom: 3px;
}

.turnaround-options .option-price {
  color: #f28738;
  font-size: 14px;
  font-weight: 500;
}

.turnaround-radio:checked + .option-label {
  border-color: #f28738;
  background: rgba(242, 135, 56, 0.05);
  box-shadow: 0 4px 8px rgba(242, 135, 56, 0.2);
}

.turnaround-radio:checked + .option-label .time-icon {
  background: #f28738;
}

.turnaround-radio:checked + .option-label .time-icon i {
  color: white;
}

.turnaround-options .option-label:hover {
  border-color: #f28738;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Share Button and Modal */
.share-product-btn {
  position: fixed;
  bottom: 30px;
  right: 30px;
  z-index: 1000;
}

.share-product-btn .btn {
  border-radius: 50px;
  padding: 12px 24px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  background: #183e73;
  border: none;
  color: #fff;
  transition: all 0.3s ease;
  font-size: 14px;
  font-weight: 500;
}

.share-product-btn .btn:hover {
  background: #f28738;
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(242, 135, 56, 0.3);
}

.share-product-btn .btn i {
  margin-right: 6px;
}

.modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.5);
  z-index: 1050;
  overflow-y: auto;
}

.modal.show {
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-dialog {
  margin: 1.75rem auto;
  max-width: 500px;
  width: 90%;
}

.modal-content {
  position: relative;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}

.modal-header {
  padding: 15px 20px;
  border-bottom: 1px solid #e0e0e0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #183e73;
  color: white;
  border-radius: 8px 8px 0 0;
}

.modal-header .modal-title {
  font-size: 18px;
  font-weight: 600;
  margin: 0;
}

.modal-header .close {
  padding: 0;
  margin: 0;
  background: transparent;
  border: 0;
  color: white;
  font-size: 24px;
  opacity: 0.8;
  cursor: pointer;
  line-height: 1;
  transition: opacity 0.2s ease;
}

.modal-header .close:hover {
  opacity: 1;
}

.modal-body {
  padding: 20px;
}

.share-url-container .form-label {
  font-weight: 500;
  color: #183e73;
  margin-bottom: 8px;
  display: block;
}

.share-url-container .input-group {
  display: flex;
  position: relative;
}

.share-url-container .input-group .form-control {
  border: 1px solid #e0e0e0;
  padding: 10px 12px;
  border-radius: 4px 0 0 4px;
  font-size: 14px;
  flex: 1;
}

.share-url-container .input-group .btn-primary {
  background: #183e73;
  border: none;
  border-radius: 0 4px 4px 0;
  padding: 10px 20px;
  color: white;
  font-size: 14px;
  font-weight: 500;
  transition: background 0.2s ease;
}

.share-url-container .input-group .btn-primary:hover {
  background: #f28738;
}

.share-url-container .input-group .btn-primary i {
  margin-right: 5px;
}

.copy-feedback {
  font-size: 14px;
  color: #28a745;
  margin-top: 10px;
  font-weight: 500;
}

.copy-feedback i {
  margin-right: 5px;
}

.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.5);
  z-index: 1040;
}

/* Responsive Design */
@media (max-width: 768px) {
  .customize-card .options-list {
    flex-direction: column;
  }

  .customize-card .option-item {
    width: 100%;
    min-width: 100%;
  }

  .turnaround-options {
    flex-direction: column;
  }

  .turnaround-options .option-group {
    min-width: 100%;
  }

  .share-product-btn {
    bottom: 20px;
    right: 20px;
  }

  .share-product-btn .btn {
    padding: 10px 20px;
    font-size: 13px;
  }

  .modal-dialog {
    margin: 1rem;
    width: calc(100% - 2rem);
  }
}

@media (max-width: 480px) {
  .customize-card .svg-icon {
    height: 50px;
  }

  .customize-card .svg-icon img {
    width: 40px;
    height: 40px;
  }

  .customize-card .option-button {
    padding: 10px 8px;
  }

  .customize-card .option-button span {
    font-size: 12px;
  }

  .turnaround-options .time-icon {
    width: 40px;
    height: 40px;
  }

  .turnaround-options .time-icon i {
    font-size: 18px;
  }
}

/* Error Messages */
.single-review span[id$="_error"] {
  display: block;
  margin-top: 5px;
  font-size: 13px;
  color: #e74c3c;
}

/* Disabled State */
.field:disabled {
  background-color: #f5f5f5;
  cursor: not-allowed;
}
</style>
