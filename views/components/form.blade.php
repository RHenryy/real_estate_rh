<div class="total-form">
    <form action='$action_url' method='$method'>
        <div class="form" style="display:flex;">
            @foreach ($inputs as $input)
                <div class='form-group'>
                    <label for="{{ $input['name'] }}">{{ $input['label'] }} :</label>
                    <input type="{{ $input['type'] }}" placeholder="{{ $input['name'] }}" value="{{ $input['value'] }}"
                        name="{{ $input['name'] }}" id="{{ $input['name'] }}">
                </div>
            @endforeach
        </div>
        <div style="margin-top:2rem;" class="submit">
            <button type='submit' class='btn btn-primary'>Submit</button>
        </div>
    </form>
</div>
