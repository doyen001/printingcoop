<div class="uploaded-file-single" id="teb-{{ $return_arr['skey'] }}">
    <div class="uploaded-file-single-inner">
        <a href="{{ $return_arr['file_base_url'] }}" target="_blank">
            <div class="uploaded-file-img" style="background-image: url({{ $return_arr['src'] }})"></div>
        </a>
        <div class="uploaded-file-info">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="uploaded-file-name">
                        <span>
                            <a href="{{ $return_arr['file_base_url'] }}" target="_blank">{{ $return_arr['name'] }}</a>
                        </span>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="upload-action-btn">
                        <button type="button" onclick="update_cumment('{{ $return_arr['skey'] }}')" id="smc-{{ $return_arr['skey'] }}">
                            Update Note
                        </button>
                        <button type="button" title="Delete" onclick="delete_image('{{ $return_arr['skey'] }}')" id="smd-{{ $return_arr['skey'] }}">
                            <i class="las la-trash"></i>
                        </button>
                        <input type="hidden" value="{{ $return_arr['location'] }}" id="location-{{ $return_arr['skey'] }}">
                    </div>
                </div>
            </div>
            <div class="upload-field">
                <textarea id="cumment-{{ $return_arr['skey'] }}"
                          name="file_comments[{{ $return_arr['skey'] }}]">{{ $return_arr['cumment'] }}</textarea>
            </div>
        </div>
    </div>
</div>
