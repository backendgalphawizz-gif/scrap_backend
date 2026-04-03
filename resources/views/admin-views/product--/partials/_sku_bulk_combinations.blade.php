@foreach ($combinations as $key => $combination)
    @if(empty($combination))
     <div class="col-md-6 mb-4">
        <div class="variant-container border rounded p-2">
            <div class="row fw-bold border-bottom pb-2 mb-2">
                <div class="col text-center">{{ \App\CPU\translate('Variant') }}</div>
                <div class="col text-center">{{ \App\CPU\translate('From') }}</div>
                <div class="col text-center">{{ \App\CPU\translate('To') }}</div>
                <div class="col text-center">{{ \App\CPU\translate('Price') }}</div>
                <div class="col text-center">{{ \App\CPU\translate('Action') }}</div>
            </div>

            <div class="rows-container">
                @php
                    
                    $str = 'normal';
                @endphp

                @if(strlen($str) > 0)
                    <div class="row row-block align-items-center mb-2">
                        <div class="col">
                            <label class="control-label">{{ $str }}</label>
                            <input type="hidden" name="bulk_variant[0][variant]" value="{{ $str }}">
                        </div>
                        <div class="col">
							<input type="number" 
								name="bulk_variant[0][from][]" 
								placeholder="Min 1" 
								min="1" 
								max="200" 
								class="form-control"
								oninput="validateInput(this)">
						</div>

						<div class="col">
							<input type="number" 
								name="bulk_variant[0][to][]" 
								placeholder="Max 200" 
								min="1" 
								max="200" 
								class="form-control"
								oninput="validateInput(this)">
						</div>

						<div class="col">
							<input type="number" 
								name="bulk_variant[0][bulk_price][]"  
								min="0"
								step="0.01"
								class="form-control"
								oninput="validatePrice(this)">
						</div>
                        <div class="col text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary btn-sm add-row">+</button>
                                <button type="button" class="btn btn-danger btn-sm remove-row">×</button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="col-md-6 mb-4">
        <div class="variant-container border rounded p-2">
            <div class="row fw-bold border-bottom pb-2 mb-2">
                <div class="col text-center">{{ \App\CPU\translate('Variant') }}</div>
                <div class="col text-center">{{ \App\CPU\translate('From') }}</div>
                <div class="col text-center">{{ \App\CPU\translate('To') }}</div>
                <div class="col text-center">{{ \App\CPU\translate('Price') }}</div>
                <div class="col text-center">{{ \App\CPU\translate('Action') }}</div>
            </div>

            <div class="rows-container">
                @php
                    $sku = '';
                    foreach (explode(' ', $product_name) as $k => $value) {
                        $sku .= substr($value, 0, 1);
                    }

                    $str = '';
                    foreach ($combination as $k => $item){
                        if ($k > 0) {
                            $str .= '-'.str_replace(' ', '', $item);
                            $sku .='-'.str_replace(' ', '', $item);
                        } else {
                            if ($colors_active == 1) {
                                $color_name = \App\Model\Color::where('code', $item)->first()->name;
                                $str .= $color_name;
                                $sku .='-'.$color_name;
                            } else {
                                $str .= str_replace(' ', '', $item);
                                $sku .='-'.str_replace(' ', '', $item);
                            }
                        }
                    }
                @endphp

                @if(strlen($str) > 0)
                    @php
                        // Find matching bulk variant entry
                        $bulkData = collect($bulkPricing)->firstWhere('variant', $str);

                        // Defaults if no match found
                        $from_values = $bulkData['from'] ?? [null];
                        $to_values = $bulkData['to'] ?? [null];
                        $bulk_price_values = $bulkData['bulk_price'] ?? [0];
                    @endphp

                    @for($i = 0; $i < count($from_values); $i++)
                        <div class="row row-block align-items-center mb-2">
                            <div class="col">
                                <label class="control-label">{{ $str }}</label>
                                <input type="hidden" name="bulk_variant[{{ $key }}][variant]" value="{{ $str }}">
                            </div>
                            <div class="col">
                                <input type="number"
                                    name="bulk_variant[{{ $key }}][from][]"
                                    placeholder="Min 1"
                                    min="1"
                                    max="200"
                                    class="form-control"
                                    oninput="validateInput(this)"
                                    value="{{ $from_values[$i] }}">
                            </div>

                            <div class="col">
                                <input type="number"
                                    name="bulk_variant[{{ $key }}][to][]"
                                    placeholder="Max 200"
                                    min="1"
                                    max="200"
                                    class="form-control"
                                    oninput="validateInput(this)"
                                    value="{{ $to_values[$i] }}">
                            </div>

                            <div class="col">
                                <input type="number"
                                    name="bulk_variant[{{ $key }}][bulk_price][]"
                                    min="0"
                                    step="0.01"
                                    class="form-control"
                                    oninput="validatePrice(this)"
                                    value="{{ $bulk_price_values[$i] }}">
                            </div>

                            <div class="col text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-sm add-row">+</button>
                                    <button type="button" class="btn btn-danger btn-sm remove-row">×</button>
                                </div>
                            </div>
                        </div>
                    @endfor
                @endif
            </div>
        </div>
    </div>
    @endif
@endforeach

<script>
(function ($) {
  // ADD (clone only clicked row, insert after)
  $(document)
    .off('click.bulkAdd', '.add-row')
    .on('click.bulkAdd', '.add-row', function (e) {
      e.preventDefault();
      const $row = $(this).closest('.row-block');
      const $container = $row.closest('.rows-container');
      const $rows = $container.find('.row-block');

      if ($rows.length < 5) {
        const $clone = $row.clone(false, false);
        $clone.find('input[type="text"], input[type="number"]').val('');
        $row.after($clone);
      }

      // hide ALL add buttons inside this container if 5 rows reached
      if ($container.find('.row-block').length >= 5) {
        $container.find('.add-row').hide();
      }
    });

  // REMOVE (keep at least 1 row, re-show add button if < 5)
  $(document)
    .off('click.bulkRemove', '.remove-row')
    .on('click.bulkRemove', '.remove-row', function (e) {
      e.preventDefault();
      const $container = $(this).closest('.rows-container');
      const $rows = $container.find('.row-block');

      if ($rows.length > 1) {
        $(this).closest('.row-block').remove();
      }

      // re-show add button if less than 5 rows
      if ($container.find('.row-block').length < 5) {
        $container.find('.add-row').show();
      }
    });
})(jQuery);
</script>

<script>
function validateInput(el) {
    // Remove non-numeric input
    el.value = el.value.replace(/[^0-9]/g, '');

    // Apply min/max restriction
    if (el.hasAttribute('min') && el.value !== '' && parseInt(el.value) < parseInt(el.min)) {
        el.value = el.min;
    }
    if (el.hasAttribute('max') && el.value !== '' && parseInt(el.value) > parseInt(el.max)) {
        el.value = el.max;
    }
}

function validatePrice(el) {
    // Allow decimals but prevent multiple dots
    el.value = el.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');

    // Prevent negative
    if (el.value < 0) {
        el.value = 0;
    }
}
</script>

<!-- <script>


$(document).on('click', '.add-row', function (e) {
    e.preventDefault();

    let parentDiv = $(this).closest('.col-md-6');   // this table container
    let table = parentDiv.find('table tbody');      // tbody inside that container
    let rowCount = table.find('tr').length;

    if (rowCount < 5) {
        let firstRow = table.find('tr:first');
        let newRow = firstRow.clone();

        // Reset values for the cloned row
        newRow.find('input[name^="from_"]').val("");
        newRow.find('input[name^="to_"]').val("");
        newRow.find('input[name^="bulk_price"]').val("");

        table.append(newRow);

        // Hide add button if limit reached
        if (rowCount + 1 >= 5) {
            parentDiv.find('.add-row').hide();
        }
    }
});

// Remove row
$(document).on('click', '.remove-row', function () {
    let parentDiv = $(this).closest('.col-md-6');
    let table = parentDiv.find('table tbody');
    let addBtn = parentDiv.find('.add-row');

    if (table.find('tr').length > 1) {
        $(this).closest('tr').remove();

        // Show add button again if < 5 rows
        if (table.find('tr').length < 5) {
            addBtn.show();
        }
    }
});
</script> -->

<script>
	update_qty();
	function update_qty()
	{
		var total_qty = 0;
		var qty_elements = $('input[name^="qty_"]');
		for(var i=0; i<qty_elements.length; i++)
		{
			total_qty += parseInt(qty_elements.eq(i).val());
		}
		if(qty_elements.length > 0)
		{
			$('input[name="current_stock"]').attr("readonly", true);
			$('input[name="current_stock"]').val(total_qty);
		}
		else{
			$('input[name="current_stock"]').attr("readonly", false);
		}
	}
	$('input[name^="qty_"]').on('keyup', function () {
		var total_qty = 0;
		var qty_elements = $('input[name^="qty_"]');
		for(var i=0; i<qty_elements.length; i++)
		{
			total_qty += parseInt(qty_elements.eq(i).val());
		}
		$('input[name="current_stock"]').val(total_qty);
	});
</script>	
