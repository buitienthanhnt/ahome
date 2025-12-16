 <div class="form-group form-check pl-0">
     {{-- fix error form not submit value of checkbox when not checked so not update model active value from on to off --}}
     <input type="text" hidden name="{{ $field['key'] }}">
     <input type="checkbox" class="form-check-input" id="{{ 'form-id-' . $field['key'] }}" name="{{ $field['key'] }}"
         @if (old($field['key'])) value="{{ old($field['key']) }}" @endif
         @isset($field['value']) 
            @if ($field['value'] === __('attrval.active')) checked @endif 
         @else checked @endisset
      >
     <label class="form-check-label" for="{{ 'form-id-' . $field['key'] }}">{{ __('attr.' . $field['key']) }}</label>
 </div>
