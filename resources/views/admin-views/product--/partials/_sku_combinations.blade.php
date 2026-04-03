@if(count($combinations[0]) > 0)
	<table class="table table-bordered physical_product_show">
		<thead>
			<tr>
				<td class="text-center">
					<label for="" class="control-label">{{\App\CPU\translate('Variant')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{\App\CPU\translate('Variant Price')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{\App\CPU\translate('SKU')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">
						<span>{{ \App\CPU\translate('per_pack_unit') }}</span>  <br>
						<span>{{' (' . implode(', ', \App\CPU\Helpers::units()) . ')' }}</span>
					</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{\App\CPU\translate('Quantity')}}</label>
				</td>
			</tr>
		</thead>
		<tbody>
@endif
@foreach ($combinations as $key => $combination)
	@php
		$sku = '';
		foreach (explode(' ', $product_name) as $key => $value) {
			$sku .= substr($value, 0, 1);
		}

		$str = '';
		foreach ($combination as $key => $item){
			if($key > 0 ){
				$str .= '-'.str_replace(' ', '', $item);
				$sku .='-'.str_replace(' ', '', $item);
			}
			else{
				if($colors_active == 1){
					$color_name = \App\Model\Color::where('code', $item)->first()->name;
					$str .= $color_name;
					$sku .='-'.$color_name;
				}
				else{
					$str .= str_replace(' ', '', $item);
					$sku .='-'.str_replace(' ', '', $item);
				}
			}
		}
	@endphp

	@if(strlen($str) > 0)
			<tr>
				<td>
					<label for="" class="control-label">{{ $str }}</label>
				</td>
					{{-- @php
						// Default values
						$existing_price = $unit_price;
						$existing_sku = $sku;
						$existing_per_pack_quantity = 1;
						$existing_qty = 1;

						// Check if variation exists
						foreach ($variations as $variation) {
							if ($variation['type'] == $str) {
								$existing_price = \App\CPU\BackEndHelper::usd_to_currency($variation['price']);
								$existing_sku = $variation['sku'];
								$existing_per_pack_quantity = $variation['per_pack_quantity'];
								$existing_qty = $variation['qty'];
								break;
							}
						}
					@endphp --}}

					@php
						// Default values
						$existing_price = $unit_price;
						$existing_sku = $sku;
						$existing_per_pack_quantity = 1;
						$existing_qty = 1;

						// Check if variation exists
						foreach ($variations as $variation) {
							if (isset($variation['type']) && $variation['type'] == $str) {
								$existing_price = isset($variation['price']) 
									? \App\CPU\BackEndHelper::usd_to_currency($variation['price']) 
									: $unit_price;

								$existing_sku = $variation['sku'] ?? $sku;
								$existing_per_pack_quantity = $variation['per_pack_quantity'] ?? 1;
								$existing_qty = $variation['qty'] ?? 1;

								break;
							}
						}
					@endphp

					<td>
						<input type="number" name="price_{{ $str }}" value="{{ $existing_price }}" min="0" step="0.01" class="form-control" required>
					</td>
					<td>
						<input type="text" name="sku_{{ $str }}" value="{{ $existing_sku }}" class="form-control" required>
					</td>
					<td>
						<input type="text" name="per_pack_quantity_{{ $str }}" value="{{ $existing_per_pack_quantity }}" class="form-control" required>
					</td>
					<td>
						<input type="number" name="qty_{{ $str }}" value="{{ $existing_qty }}" min="1" max="1000000" step="1" class="form-control" required>
					</td>

			</tr>
	@endif
@endforeach
	</tbody>
</table>

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
